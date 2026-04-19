# Résolution du Problème de Synchronisation des Images

## 📋 Problème Identifié

Les images capturées depuis l'application mobile étaient **enregistrées dans la base SQLite locale** mais **n'étaient jamais envoyées au serveur MySQL**. Cela signifiait que :

- ✅ Les photos étaient visibles sur le téléphone qui les a capturées
- ❌ Les photos n'étaient **pas visibles** sur les autres appareils
- ❌ Les photos n'étaient **pas sauvegardées** dans la base de données centrale MySQL

## 🔍 Cause Racine

Le `SyncManager.kt` implémentait uniquement la synchronisation **du serveur vers le local** (download), mais pas **du local vers le serveur** (upload) pour les images.

## ✅ Solutions Implémentées

### 1. Ajout de l'Upload d'Images dans SyncManager

**Fichier :** `android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt`

Deux nouvelles méthodes ont été ajoutées :

#### a) `uploadImageToServer()` - Upload immédiat
```kotlin
suspend fun uploadImageToServer(
    imageData: ByteArray,
    interventionId: Int,
    imageName: String,
    dateCapture: String
): Boolean
```
- Utilisée **immédiatement après la capture** d'une photo
- Envoie l'image au serveur en Base64 via HTTP POST
- Retourne `true` si l'upload a réussi

#### b) `uploadLocalImages()` - Synchronisation en masse
```kotlin
private suspend fun uploadLocalImages()
```
- Appelée automatiquement lors de la synchronisation complète
- Récupère toutes les images avec `valsync = 0` (non synchronisées)
- Les envoie une par une au serveur
- Met à jour `valsync = 1` après succès

### 2. Modification de ImageCaptureActivity

**Fichier :** `android-app/app/src/main/java/com/expert/maintenance/ui/ImageCaptureActivity.kt`

Après l'enregistrement local, l'image est **immédiatement envoyée au serveur** :

```kotlin
// Insertion locale
database.imageDao().insert(imageName)

// Upload au serveur
val uploadSuccess = syncManager.uploadImageToServer(
    imageData = byteArray,
    interventionId = interventionId,
    imageName = imageName.nom,
    dateCapture = imageName.dateCapture
)

// Mise à jour du statut de synchronisation
if (uploadSuccess) {
    val syncedImage = imageName.copy(valsync = 1)
    database.imageDao().update(syncedImage)
}
```

### 3. Ajout d'une Méthode DAO pour Images Non Synchronisées

**Fichier :** `android-app/app/src/main/java/com/expert/maintenance/data/local/dao/DaoInterfaces.kt`

```kotlin
@Query("SELECT * FROM images WHERE valsync = 0 ORDER BY dateCapture DESC")
suspend fun getUnsyncedImages(): List<Image>
```

## 🧪 Comment Tester

### Test 1 : Capture d'une Nouvelle Photo

1. Lancez l'application mobile
2. Connectez-vous avec un employé
3. Ouvrez une intervention
4. Cliquez sur "Prendre une photo"
5. Capturez une image

**Résultat attendu :**
- Toast : "✓ Photo enregistrée et synchronisée (XXX KB)"
- Dans les logs : `✅ Image uploadée avec succès, server_id=XX`
- La photo apparaît dans la galerie de l'intervention

### Test 2 : Vérification dans phpMyAdmin

Après avoir capturé une photo, exécutez cette requête SQL :

```sql
SELECT id, nom, LENGTH(img) as taille_bytes, dateCapture, intervention_id 
FROM images 
ORDER BY id DESC 
LIMIT 5;
```

**Résultat attendu :**
- La dernière photo doit apparaître avec une taille > 0
- L'intervention_id doit correspondre

### Test 3 : Synchronisation sur un Autre Appareil

1. Capturez une photo sur l'appareil A
2. Lancez la synchronisation sur l'appareil B
3. Ouvrez la même intervention sur l'appareil B

**Résultat attendu :**
- La photo capturée sur l'appareil A doit être visible sur l'appareil B

### Test 4 : Mode Hors Ligne

1. Désactivez la connexion réseau
2. Capturez une photo
3. Vous devriez voir : "⚠️ Photo enregistrée (sera synchronisée plus tard)"
4. Réactivez le réseau et synchronisez

**Résultat attendu :**
- La photo est envoyée au serveur lors de la prochaine synchronisation

## 🛠️ Dépannage

### Les photos ne s'envoient pas au serveur ?

**Vérification 1 : Logs Android**
```bash
# Sur Mac
~/Library/Android/sdk/platform-tools/adb logcat | grep "PHOTO_DEBUG\|SyncManager"

# Sur Windows
adb logcat | findstr "PHOTO_DEBUG SyncManager"
```

**Vérification 2 : Backend PHP**
Consultez le fichier de log du backend :
```
ExpertMaintenance/backend/api.log
```

**Vérification 3 : Adresse du serveur**
Dans `SyncManager.kt`, vérifiez que l'adresse IP est correcte :
```kotlin
private const val API_BASE_URL = "http://192.168.5.225:80/ExpertMaintenance/backend/api.php"
```
→ Remplacez `192.168.5.225` par l'adresse IP de votre serveur XAMPP

**Vérification 4 : Test direct de l'API**
Ouvrez dans votre navigateur :
```
http://192.168.5.225/ExpertMaintenance/backend/api.php?action=test
```
Devrait retourner : `{"success":true,"message":"API is working"}`

### Erreur "Image data is empty" ?

Cela signifie que le tableau d'octets est vide. Vérifiez :
1. La permission de stockage est accordée
2. Le fichier image est bien lisible via `contentResolver.openInputStream()`

### Erreur de connexion au serveur ?

1. Vérifiez que XAMPP est démarré
2. Vérifiez que Apache est actif
3. Vérifiez que le pare-feu ne bloque pas le port 80
4. Sur mobile, assurez-vous d'être sur le même réseau WiFi que le serveur

## 📊 Structure de Synchronisation

```
┌─────────────────────────────────────────────────────────────┐
│                    SYNCHRONISATION IMAGES                   │
└─────────────────────────────────────────────────────────────┘

┌──────────────┐                           ┌──────────────┐
│  Mobile      │                           │  Serveur     │
│  (SQLite)    │                           │  (MySQL)     │
├──────────────┤                           ├──────────────┤
│ valsync = 0  │ ────── HTTP POST ───────→ │  INSERT      │
│ (nouvelle)   │      (Base64)             │  dans images │
│              │                           │              │
│ valsync = 1  │ ←─────── UPDATE ──────────│  id = X      │
│ (synchronisé)│       valsync=1          │              │
└──────────────┘                           └──────────────┘

Lors de la sync complète :
┌──────────────┐                           ┌──────────────┐
│  Mobile      │                           │  Serveur     │
│  (SQLite)    │                           │  (MySQL)     │
├──────────────┤                           ├──────────────┤
│ SELECT       │ ────── GET ─────────────→ │  SELECT      │
│ valsync = 0  │                           │  images      │
│              │                           │  WHERE...    │
│              │ ←── JSON (Base64) ────────│              │
│  INSERT      │                           │              │
│  en local    │                           │              │
└──────────────┘                           └──────────────┘
```

## 🔑 Points Clés à Retenir

1. **valsync = 0** → Image non synchronisée (existe seulement en local)
2. **valsync = 1** → Image synchronisée (existe en local ET sur le serveur)
3. L'upload se fait **immédiatement** après capture pour un retour utilisateur rapide
4. En cas d'échec, l'image reste en local et sera synchronisée plus tard
5. Le format Base64 est utilisé pour transmettre les images via JSON

## 📝 Fichiers Modifiés

| Fichier | Modifications |
|---------|---------------|
| `SyncManager.kt` | Ajout méthodes `uploadImageToServer()` et `uploadLocalImages()` |
| `ImageCaptureActivity.kt` | Appel à `uploadImageToServer()` après capture |
| `DaoInterfaces.kt` | Ajout méthode `getUnsyncedImages()` |

## 🎯 Prochaines Étapes

Après avoir appliqué ces corrections :

1. **Rebuild** l'application Android
2. **Testez** la capture d'une nouvelle photo
3. **Vérifiez** dans phpMyAdmin que l'image est bien présente
4. **Testez** sur un deuxième appareil pour vérifier la synchronisation

---

**Document créé le :** 2025
**Projet :** Expert Maintenance - Application Mobile Android