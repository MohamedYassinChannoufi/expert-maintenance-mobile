# 🚀 Expert Maintenance - Backend Setup Guide

Guide complet pour installer et configurer le backend PHP/MySQL pour l'application Expert Maintenance.

---

## 📋 Table des Matières

1. [Problème Apache - Solution](#problème-apache---solution)
2. [Installation de XAMPP](#installation-de-xampp)
3. [Configuration de la Base de Données](#configuration-de-la-base-de-données)
4. [Installation du Backend](#installation-du-backend)
5. [Tester l'API](#tester-lapi)
6. [Configuration Android](#configuration-android)
7. [Dépannage](#dépannage)

---

## 🔴 Problème Apache - Solution

### Pourquoi Apache est stoppé?

Sur macOS, le port 80 est souvent utilisé par le service Apache système intégré. Voici comment résoudre ce problème:

### Solution 1: Arrêter l'Apache système (Recommandé)

Ouvrez le Terminal et exécutez:

```bash
sudo apachectl stop
```

Ensuite, dans XAMPP:
1. Cliquez sur "Start" pour Apache Web Server
2. Apache devrait démarrer correctement

### Solution 2: Changer le port Apache

Si vous ne pouvez pas arrêter l'Apache système:

1. Dans XAMPP, cliquez sur **Configure** → **httpd.conf**
2. Trouvez la ligne: `Listen 80`
3. Changez en: `Listen 8080`
4. Trouvez la ligne: `ServerName localhost:80`
5. Changez en: `ServerName localhost:8080`
6. Sauvegardez le fichier
7. Redémarrez Apache dans XAMPP

**Important:** Si vous utilisez le port 8080, vous devrez mettre à jour toutes les URLs de l'API:
- Au lieu de: `http://localhost/ExpertMaintenance/backend/api.php`
- Utilisez: `http://localhost:8080/ExpertMaintenance/backend/api.php`

---

## 📦 Installation de XAMPP

### Étape 1: Télécharger XAMPP

1. Rendez-vous sur: https://www.apachefriends.org/
2. Téléchargez XAMPP pour macOS (version 8.1.17-0 ou supérieure)
3. Installez XAMPP dans `/Applications/`

### Étape 2: Démarrer les Services

1. Ouvrez XAMPP
2. Allez dans l'onglet "Manage Servers"
3. Démarrez **MySQL Database** (cliquez sur "Start")
4. Démarrez **Apache Web Server** (cliquez sur "Start")

✅ Les deux services doivent afficher "Running" avec une icône verte

---

## 🗄️ Configuration de la Base de Données

### Étape 1: Accéder à phpMyAdmin

Ouvrez votre navigateur et allez à:
```
http://localhost/phpmyadmin
```

### Étape 2: Créer la Base de Données

**Option A - Via l'interface:**

1. Cliquez sur "Nouvelle base de données" dans la barre latérale
2. Nommez-la: `gem`
3. Choisissez l'encodage: `utf8mb4_general_ci`
4. Cliquez sur "Créer"

**Option B - Via SQL:**

Cliquez sur l'onglet "SQL" et exécutez:
```sql
CREATE DATABASE IF NOT EXISTS `gem` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### Étape 3: Importer le Script SQL

1. Sélectionnez la base `gem` dans la barre latérale
2. Cliquez sur l'onglet "Importer"
3. Cliquez sur "Choisir un fichier"
4. Sélectionnez: `ExpertMaintenance/database/gem_database_fixed.sql`
5. Cliquez sur "Exécuter" en bas de la page

### Étape 4: Vérifier l'Import

Après l'import, vous devriez voir ces tables dans phpMyAdmin:

- ✅ `clients`
- ✅ `contrats`
- ✅ `employes`
- ✅ `employes_interventions`
- ✅ `images`
- ✅ `interventions`
- ✅ `priorites`
- ✅ `sites`
- ✅ `taches`

**Données de test incluses:**
- 2 employés: `admin/admin123` et `enzo/enzo123`
- 1 client: "La Société Exemple"
- 1 site: Paris
- 2 interventions pour le 21 juin 2018
- 3 priorités: Normale, Urgente, Critique

---

## 🔧 Installation du Backend

### Étape 1: Localiser le dossier htdocs

Sur macOS, le dossier htdocs de XAMPP est généralement situé à:
```
/Applications/XAMPP/htdocs
```

### Étape 2: Créer le dossier ExpertMaintenance

Ouvrez le Terminal et exécutez:

```bash
cd /Applications/XAMPP/htdocs
sudo mkdir -p ExpertMaintenance/backend
```

### Étape 3: Copier le Backend

Depuis votre dossier de projet:

```bash
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/api.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/test_api.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
```

**Alternative manuelle:**
1. Ouvrez le Finder
2. Allez dans `/Applications/XAMPP/htdocs`
3. Créez un dossier nommé `ExpertMaintenance`
4. À l'intérieur, créez un dossier nommé `backend`
5. Copiez les fichiers `api.php` et `test_api.php` dans ce dossier

### Étape 4: Vérifier les Permissions

Assurez-vous que les fichiers sont lisibles par Apache:

```bash
sudo chmod 644 /Applications/XAMPP/htdocs/ExpertMaintenance/backend/*.php
```

### Étape 5: Vérifier la Configuration Database

Ouvrez le fichier `/Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.php` et vérifiez ces lignes (début du fichier):

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gem');
define('DB_USER', 'root');
define('DB_PASS', '');
```

Ces valeurs doivent correspondre à votre configuration XAMPP par défaut.

---

## 🧪 Tester l'API

### Test 1: Page de Test Interactive

La méthode la plus simple pour tester:

1. Ouvrez votre navigateur
2. Allez à:
   ```
   http://localhost/ExpertMaintenance/backend/test_api.php
   ```
3. Cliquez sur "🚀 Lancer tous les tests"
4. Vérifiez que tous les tests sont en vert (✓ Succès)

### Test 2: Test Manuel des Endpoints

**Tester la synchronisation complète:**
```
http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1
```

**Réponse attendue (JSON):**
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

**Tester l'authentification:**

Ouvrez le Terminal et exécutez:
```bash
curl -X POST "http://localhost/ExpertMaintenance/backend/api.php?action=authenticate" \
  -H "Content-Type: application/json" \
  -d '{"login":"admin","password":"admin123"}'
```

**Réponse attendue:**
```json
{
  "success": true,
  "employee": {
    "id": 1,
    "login": "admin",
    "nom": "Dupont",
    "prenom": "Jean",
    "email": "jean.dupont@expert-maintenance.fr",
    "actif": 1
  },
  "token": "abc123def456..."
}
```

**Tester la récupération d'une intervention:**
```
http://localhost/ExpertMaintenance/backend/api.php?action=get_intervention&id=1
```

### Test 3: Vérifier les Logs

Si quelque chose ne fonctionne pas, consultez le fichier de log:

```bash
tail -f /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log
```

---

## 📱 Configuration Android

### Étape 1: Mettre à jour l'URL de l'API

Dans votre projet Android, ouvrez le fichier:
```
android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt
```

Trouvez la ligne:
```kotlin
private const val API_BASE_URL = "http://192.168.100.39/ExpertMaintenance/backend/api.php"
```

**Pour l'émulateur Android:**
```kotlin
private const val API_BASE_URL = "http://10.0.2.2/ExpertMaintenance/backend/api.php"
```

**Pour un appareil physique (même réseau WiFi):**
1. Trouvez l'adresse IP de votre Mac:
   ```bash
   ifconfig | grep "inet "
   ```
2. Utilisez cette IP:
   ```kotlin
   private const val API_BASE_URL = "http://192.168.1.XXX/ExpertMaintenance/backend/api.php"
   ```

**Si vous utilisez le port 8080:**
```kotlin
private const val API_BASE_URL = "http://10.0.2.2:8080/ExpertMaintenance/backend/api.php"
```

### Étape 2: Autoriser le Traffic HTTP (Development)

Dans `AndroidManifest.xml`, assurez-vous d'avoir:
```xml
<application
    android:usesCleartextTraffic="true"
    ...>
```

### Étape 3: Tester la Connexion

1. Lancez l'application Android
2. Connectez-vous avec: `admin` / `admin123`
3. La synchronisation devrait se lancer automatiquement
4. Vérifiez que les interventions s'affichent

---

## 🐛 Dépannage

### Apache ne démarre pas

**Erreur: "Port 80 in use"**

Solution:
```bash
# Arrêter l'Apache système
sudo apachectl stop

# Ou changer le port dans httpd.conf
# Listen 80 → Listen 8080
```

### Erreur de connexion à la base de données

**Vérifications:**
1. ✅ MySQL est-il démarré dans XAMPP?
2. ✅ La base `gem` existe-t-elle?
3. ✅ Les identifiants sont-ils corrects dans `api.php`?

**Test:**
```
http://localhost/phpmyadmin
```
Si phpMyAdmin ne s'ouvre pas, MySQL n'est pas démarré.

### Erreur CORS

Si vous voyez des erreurs CORS dans la console du navigateur:

Vérifiez que `api.php` contient ces lignes au début:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

### L'API retourne une erreur 400

**Cause:** Action invalide

**Solution:** Vérifiez que le paramètre `action` est correct:
```
?action=authenticate
?action=full_sync
?action=sync_employees
...
```

### Les données ne se synchronisent pas

**Vérifications:**
1. ✅ L'URL de l'API est-elle correcte dans `SyncManager.kt`?
2. ✅ L'employé existe-t-il dans la base?
3. ✅ L'employé est-il actif (`actif = 1`)?
4. ✅ Les interventions sont-elles assignées à cet employé (table `employes_interventions`)?

**Test SQL:**
```sql
SELECT * FROM employes WHERE login = 'admin';
SELECT * FROM employes_interventions WHERE employe_id = 1;
SELECT * FROM interventions WHERE id IN (
    SELECT intervention_id FROM employes_interventions WHERE employe_id = 1
);
```

### Images ne s'affichent pas

**Vérifications:**
1. ✅ La table `images` contient-elle des données?
2. ✅ Le champ `img` contient-il des données binaires?

**Test:**
```sql
SELECT id, nom, LENGTH(img) as taille FROM images;
```

### Logs d'erreur

**Consulter les logs Apache:**
```bash
# Dans XAMPP → Apache → Logs
# Ou dans le Terminal:
tail -f /Applications/XAMPP/var/log/apache2/error_log
```

**Consulter les logs de l'API:**
```bash
tail -f /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log
```

---

## ✅ Checklist Finale

Avant de considérer l'installation comme terminée:

- [ ] XAMPP installé et fonctionnel
- [ ] Apache démarré (icône verte)
- [ ] MySQL démarré (icône verte)
- [ ] Base de données `gem` créée
- [ ] Script SQL importé avec succès
- [ ] 9 tables visibles dans phpMyAdmin
- [ ] Dossier `ExpertMaintenance/backend` créé dans htdocs
- [ ] Fichier `api.php` copié
- [ ] Fichier `test_api.php` copié
- [ ] Test API réussi (tous les tests en vert)
- [ ] Authentification fonctionne (admin/admin123)
- [ ] URL API configurée dans Android
- [ ] Application Android peut se connecter

---

## 📞 Support

Si vous rencontrez des problèmes:

1. **Vérifiez les logs** (`api.log` et logs Apache)
2. **Testez avec phpMyAdmin** que la base est accessible
3. **Utilisez la page de test** (`test_api.php`) pour isoler le problème
4. **Vérifiez la connectivité réseau** entre Android et le serveur

---

**🎉 Félicitations! Votre backend est maintenant opérationnel!**

Une fois cette configuration terminée, vous pouvez:
- ✅ Lancer l'application Android
- ✅ Vous authentifier
- ✅ Synchroniser les données
- ✅ Consulter les interventions
- ✅ Modifier des interventions
- ✅ Capturer et uploader des images

---

*Document créé pour Expert Maintenance - Backend Setup*
*Version: 1.0 - Janvier 2024*