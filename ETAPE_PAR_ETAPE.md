# 🚀 Expert Maintenance - Guide Étape par Étape

## ⚠️ IMPORTANT: À LIRE EN PREMIER

Ce guide vous accompagne dans l'installation complète du nouveau backend. Suivez **chaque étape dans l'ordre**.

---

## 📋 Résumé de la Situation Actuelle

**Ce qui a été fait:**
- ✅ Ancien dossier backend **SUPPRIMÉ**
- ✅ Nouveau backend complet **CRÉÉ** dans `/Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/`
- ✅ Fichiers créés: `api.php`, `test_api.php`, `config.example.php`, `README.md`, `test_quick.sh`

**Ce qui reste à faire:**
- 🔴 Démarrer Apache dans XAMPP
- 🔴 Importer la base de données
- 🔴 Copier le backend dans XAMPP/htdocs
- 🔴 Tester l'API
- 🔴 Configurer l'application Android

---

## ÉTAPE 1: Résoudre le Problème Apache (5 minutes)

### Pourquoi Apache est stoppé?

Sur macOS, le port 80 est utilisé par le service Apache intégré au système.

### Solution RAPIDE:

Ouvrez le **Terminal** et exécutez:

```bash
sudo apachectl stop
```

**Entrez votre mot de passe** quand demandé (il ne s'affichera pas, c'est normal).

### Vérification:

1. Ouvrez **XAMPP**
2. Allez dans l'onglet **"Manage Servers"**
3. Cliquez sur **"Start"** pour **Apache Web Server**
4. ✅ Le statut doit passer à **"Running"** avec une icône **verte**

**Si Apache ne démarre toujours pas:**

1. Dans XAMPP, cliquez sur **"Configure"** → **"httpd.conf"**
2. Cherchez la ligne: `Listen 80`
3. Changez en: `Listen 8080`
4. Cherchez: `ServerName localhost:80`
5. Changez en: `ServerName localhost:8080`
6. Sauvegardez et redémarrez Apache

---

## ÉTAPE 2: Démarrer MySQL (2 minutes)

1. Dans **XAMPP** → **"Manage Servers"**
2. Cliquez sur **"Start"** pour **MySQL Database**
3. ✅ Le statut doit être **"Running"** (icône verte)

---

## ÉTAPE 3: Importer la Base de Données (5 minutes)

### 3.1 Ouvrir phpMyAdmin

Dans votre navigateur:
```
http://localhost/phpmyadmin
```

### 3.2 Créer la Base de Données

1. Cliquez sur **"Nouvelle"** dans la barre latérale gauche
2. Nom de la base: `gem`
3. Encodage: `utf8mb4_general_ci`
4. Cliquez sur **"Créer"**

### 3.3 Importer le Script SQL

1. Cliquez sur la base `gem` dans la barre latérale
2. Cliquez sur l'onglet **"Importer"** en haut
3. Cliquez sur **"Choisir un fichier"**
4. Naviguez vers:
   ```
   /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/database/gem_database_fixed.sql
   ```
5. Cliquez sur **"Exécuter"** en bas
6. ✅ Message de succès: "Votre requête SQL a été exécutée avec succès"

### 3.4 Vérifier l'Import

Vous devez voir **9 tables** dans phpMyAdmin:
- ✅ clients
- ✅ contrats
- ✅ employes
- ✅ employes_interventions
- ✅ images
- ✅ interventions
- ✅ priorites
- ✅ sites
- ✅ taches

---

## ÉTAPE 4: Copier le Backend dans XAMPP (5 minutes)

### 4.1 Ouvrir le Terminal

### 4.2 Créer le Dossier

```bash
sudo mkdir -p /Applications/XAMPP/htdocs/ExpertMaintenance/backend
```

### 4.3 Copier les Fichiers

```bash
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/api.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/test_api.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/config.example.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
```

### 4.4 Définir les Permissions

```bash
sudo chmod 644 /Applications/XAMPP/htdocs/ExpertMaintenance/backend/*.php
```

### 4.5 Vérifier

Ouvrez le Finder et allez à:
```
/Applications/XAMPP/htdocs/ExpertMaintenance/backend/
```

Vous devez voir:
- ✅ `api.php`
- ✅ `test_api.php`
- ✅ `config.example.php`

---

## ÉTAPE 5: Tester l'API (3 minutes)

### Méthode 1: Page de Test (Recommandé)

1. Ouvrez votre navigateur
2. Allez à:
   ```
   http://localhost/ExpertMaintenance/backend/test_api.php
   ```
3. Cliquez sur **"🚀 Lancer tous les tests"**
4. ✅ **TOUS les tests doivent être VERTS** (✓ Succès)

### Méthode 2: Test Rapide

1. Ouvrez votre navigateur
2. Allez à:
   ```
   http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1
   ```
3. ✅ Vous devez voir un **JSON** avec `"success": true`

**Réponse attendue:**
```json
{
  "success": true,
  "data": {
    "employees": [...],
    "clients": [...],
    "sites": [...],
    "interventions": [...],
    "tasks": [...],
    "priorities": [...],
    "images": [...]
  },
  "timestamp": 1234567890
}
```

### Méthode 3: Tester l'Authentification

1. Ouvrez le **Terminal**
2. Exécutez:
   ```bash
   curl -X POST "http://localhost/ExpertMaintenance/backend/api.php?action=authenticate" \
     -H "Content-Type: application/json" \
     -d '{"login":"admin","password":"admin123"}'
   ```
3. ✅ Réponse attendue:
   ```json
   {
     "success": true,
     "employee": {...},
     "token": "..."
   }
   ```

---

## ÉTAPE 6: Configurer l'Application Android (5 minutes)

### 6.1 Ouvrir le Projet Android

1. Ouvrez **Android Studio**
2. Ouvrez le projet:
   ```
   /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/android-app/
   ```

### 6.2 Modifier l'URL de l'API

1. Ouvrez le fichier:
   ```
   android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt
   ```

2. Trouvez la ligne (vers ligne 30):
   ```kotlin
   private const val API_BASE_URL = "http://192.168.100.39/ExpertMaintenance/backend/api.php"
   ```

3. **Pour l'émulateur Android**, changez en:
   ```kotlin
   private const val API_BASE_URL = "http://10.0.2.2/ExpertMaintenance/backend/api.php"
   ```

   **Pour un appareil physique** (même WiFi), trouvez votre IP Mac:
   ```bash
   ifconfig | grep "inet "
   ```
   Puis utilisez:
   ```kotlin
   private const val API_BASE_URL = "http://192.168.1.XXX/ExpertMaintenance/backend/api.php"
   ```

   **Si vous utilisez le port 8080:**
   ```kotlin
   private const val API_BASE_URL = "http://10.0.2.2:8080/ExpertMaintenance/backend/api.php"
   ```

### 6.3 Vérifier le Manifest

Ouvrez `android-app/app/src/main/AndroidManifest.xml` et vérifiez:
```xml
<application
    android:usesCleartextTraffic="true"
    ...>
```

### 6.4 Sync Gradle

1. Cliquez sur **"Sync Now"** en haut si demandé
2. Attendez la fin de la synchronisation

### 6.5 Builder le Projet

1. Menu: **Build** → **Make Project**
2. ✅ Build successful

---

## ÉTAPE 7: Lancer et Tester l'Application (5 minutes)

### 7.1 Lancer l'Émulateur ou Connecter un Appareil

**Option A: Émulateur**
1. Ouvrez **Device Manager** dans Android Studio
2. Lancez un émulateur (Pixel 5, API 34 recommandé)

**Option B: Appareil Physique**
1. Activez le **Débogage USB** sur votre téléphone
2. Connectez-le via USB
3. Autorisez le débogage

### 7.2 Lancer l'Application

1. Cliquez sur le bouton **Run** (▶️ vert)
2. Sélectionnez votre appareil/émulateur
3. L'application s'installe et démarre

### 7.3 Se Connecter

**Écran de login:**
- Login: `admin`
- Password: `admin123`

### 7.4 Vérifier la Synchronisation

Après connexion:
1. ✅ La synchronisation se lance automatiquement
2. ✅ La liste des interventions doit s'afficher
3. ✅ Vous devez voir 2 interventions pour le 21 juin 2018

### 7.5 Tester les Fonctionnalités

1. **Cocher une intervention** → Elle doit être marquée comme terminée
2. **Cliquez sur une intervention** → Détails complets
3. **Onglet "Fichiers"** → Prendre une photo
4. **Bouton Carte** → Google Maps s'ouvre
5. **Bouton Sync** (si présent) → Synchronisation manuelle

---

## ✅ Checklist Finale

Cochez chaque élément:

- [ ] Apache est "Running" dans XAMPP (icône verte)
- [ ] MySQL est "Running" dans XAMPP (icône verte)
- [ ] phpMyAdmin accessible: `http://localhost/phpmyadmin`
- [ ] Base `gem` créée avec 9 tables
- [ ] Backend copié dans `/Applications/XAMPP/htdocs/ExpertMaintenance/backend/`
- [ ] Test API réussi: `http://localhost/ExpertMaintenance/backend/test_api.php`
- [ ] Tous les tests sont VERTS
- [ ] URL API modifiée dans `SyncManager.kt`
- [ ] `android:usesCleartextTraffic="true"` dans le manifest
- [ ] Projet Android build successful
- [ ] Application lancée sur émulateur/appareil
- [ ] Login fonctionne (admin/admin123)
- [ ] Synchronisation fonctionne
- [ ] Interventions s'affichent
- [ ] Modification d'intervention fonctionne
- [ ] Upload d'image fonctionne

---

## 🐛 Problèmes Courants et Solutions

### Problème: Apache ne démarre pas

**Solution:**
```bash
sudo apachectl stop
# Puis redémarrer Apache dans XAMPP
```

### Problème: Erreur "Database connection failed"

**Vérifications:**
1. MySQL est-il "Running"?
2. La base `gem` existe-t-elle?
3. Les credentials dans `api.php` sont-ils corrects?

**Test:**
```
http://localhost/phpmyadmin
```

### Problème: Tests API échouent

**Solution:**
1. Consultez les logs:
   ```bash
   tail -f /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log
   ```
2. Vérifiez que Apache et MySQL sont "Running"
3. Testez manuellement:
   ```
   http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1
   ```

### Problème: Android ne se connecte pas

**Solutions:**
1. Pour émulateur: utilisez `10.0.2.2` pas `localhost`
2. Pour appareil physique: utilisez l'IP de votre Mac
3. Vérifiez `android:usesCleartextTraffic="true"`
4. Désactivez le firewall temporairement

### Problème: Interventions vides

**Vérifications:**
1. L'employé existe-t-il?
   ```sql
   SELECT * FROM employes WHERE login='admin';
   ```
2. Les interventions sont-elles assignées?
   ```sql
   SELECT * FROM employes_interventions WHERE employe_id=1;
   ```

---

## 📞 Besoin d'Aide?

### Fichiers de Log

- **API:** `/Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log`
- **Apache:** `/Applications/XAMPP/var/log/apache2/error_log`

### Commandes Utiles

```bash
# Voir les logs en temps réel
tail -f /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log

# Vérifier Apache
ps aux | grep apache

# Trouver son IP
ifconfig | grep "inet "
```

### URLs de Test

- **phpMyAdmin:** `http://localhost/phpmyadmin`
- **Test API:** `http://localhost/ExpertMaintenance/backend/test_api.php`
- **Full Sync:** `http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1`
- **Auth:** `http://localhost/ExpertMaintenance/backend/api.php?action=authenticate` (POST)

---

## 🎉 Félicitations!

Si vous avez coché toutes les cases de la checklist, **votre backend est opérationnel!**

**Vous avez maintenant:**
- ✅ Un backend PHP complet et fonctionnel
- ✅ Une base de données MySQL avec données de test
- ✅ Une application Android connectée
- ✅ La synchronisation qui fonctionne
- ✅ La gestion des interventions opérationnelle

**Prochaines étapes optionnelles:**
- Tester toutes les fonctionnalités de l'application
- Ajouter de nouvelles interventions dans la base
- Tester la capture et l'upload de photos
- Explorer l'historique des interventions

---

*Document créé pour Expert Maintenance*
*Version: 1.0 - Janvier 2024*
*Suivez ce guide dans l'ordre pour une installation sans problème*