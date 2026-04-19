# 📋 Expert Maintenance - Commandes à Copier-Coller

Ce fichier contient toutes les commandes à exécuter dans le Terminal pour installer le backend rapidement.

---

## 🚀 Installation Complète (Tout Copier-Coller)

### 1. Arrêter l'Apache Système (macOS)

```bash
sudo apachectl stop
```

### 2. Créer le Dossier Backend dans XAMPP

```bash
sudo mkdir -p /Applications/XAMPP/htdocs/ExpertMaintenance/backend
```

### 3. Copier les Fichiers du Backend

```bash
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/api.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/test_api.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/config.example.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/test_quick.sh /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
```

### 4. Définir les Permissions

```bash
sudo chmod 644 /Applications/XAMPP/htdocs/ExpertMaintenance/backend/*.php
sudo chmod +x /Applications/XAMPP/htdocs/ExpertMaintenance/backend/test_quick.sh
```

### 5. Vérifier que les Fichiers sont Copiés

```bash
ls -la /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
```

**Résultat attendu:**
```
-rw-r--r--  api.php
-rw-r--r--  test_api.php
-rw-r--r--  config.example.php
-rwxr-xr-x  test_quick.sh
-rw-r--r--  README.md
```

---

## 🧪 Tester l'API

### Option 1: Page de Test Web

Ouvrez dans votre navigateur:
```
http://localhost/ExpertMaintenance/backend/test_api.php
```

### Option 2: Script de Test CLI

```bash
cd /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
./test_quick.sh
```

### Option 3: Test Rapide avec curl

```bash
curl "http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1"
```

### Option 4: Tester l'Authentification

```bash
curl -X POST "http://localhost/ExpertMaintenance/backend/api.php?action=authenticate" \
  -H "Content-Type: application/json" \
  -d '{"login":"admin","password":"admin123"}'
```

---

## 🗄️ Base de Données

### Vérifier si MySQL Tourne

```bash
# Dans XAMPP, MySQL doit être "Running"
# Ou testez dans le navigateur:
open http://localhost/phpmyadmin
```

### Importer la Base de Données (Via Terminal)

```bash
# Se connecter à MySQL
/Applications/XAMPP/bin/mysql -u root -p

# Dans MySQL, exécuter:
CREATE DATABASE IF NOT EXISTS `gem` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gem`;
SOURCE /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/database/gem_database_fixed.sql;
EXIT;
```

### Vérifier les Tables Importées

```bash
/Applications/XAMPP/bin/mysql -u root -e "USE gem; SHOW TABLES;"
```

**Résultat attendu:**
```
+-----------------------+
| Tables_in_gem         |
+-----------------------+
| clients               |
| contrats              |
| employes              |
| employes_interventions|
| images                |
| interventions         |
| priorites             |
| sites                 |
| taches                |
+-----------------------+
```

### Vérifier les Données de Test

```bash
# Vérifier les employés
/Applications/XAMPP/bin/mysql -u root -e "USE gem; SELECT id, login, nom, prenom FROM employes;"

# Vérifier les interventions
/Applications/XAMPP/bin/mysql -u root -e "USE gem; SELECT id, titre, datedebut, terminee FROM interventions;"
```

---

## 📊 Logs et Diagnostics

### Voir les Logs API en Temps Réel

```bash
tail -f /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log
```

### Voir les Logs Apache

```bash
tail -f /Applications/XAMPP/var/log/apache2/error_log
```

### Vérifier si Apache Tourne

```bash
ps aux | grep apache
```

### Trouver son Adresse IP (pour Appareil Physique)

```bash
ifconfig | grep "inet "
```

**Note:** Utilisez l'adresse qui commence par `192.168.x.x` ou `10.x.x.x`

---

## 🔧 Dépannage

### Redémarrer Apache

```bash
# Dans XAMPP, cliquez sur Stop puis Start
# Ou en ligne de commande:
sudo apachectl stop
sudo apachectl start
```

### Redémarrer MySQL

```bash
# Dans XAMPP, cliquez sur Stop puis Start
```

### Changer le Port Apache (si conflit)

```bash
# Éditer le fichier httpd.conf
sudo nano /Applications/XAMPP/etc/httpd.conf

# Trouver et modifier:
# Listen 80  →  Listen 8080
# ServerName localhost:80  →  ServerName localhost:8080
```

### Nettoyer et Réinstaller le Backend

```bash
# Supprimer l'ancien backend
sudo rm -rf /Applications/XAMPP/htdocs/ExpertMaintenance/backend

# Recréer et copier
sudo mkdir -p /Applications/XAMPP/htdocs/ExpertMaintenance/backend
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/*.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/test_quick.sh /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo chmod 644 /Applications/XAMPP/htdocs/ExpertMaintenance/backend/*.php
sudo chmod +x /Applications/XAMPP/htdocs/ExpertMaintenance/backend/test_quick.sh
```

### Tester la Connexion à la Base de Données

```bash
/Applications/XAMPP/bin/mysql -u root -e "SELECT 'Connection successful!' AS status;"
```

---

## 📱 Configuration Android

### Trouver l'IP de Votre Mac (pour Appareil Physique)

```bash
ipconfig getifaddr en0
```

### Modifier SyncManager.kt

**Ouvrez ce fichier:**
```
/Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt
```

**Pour l'émulateur, ligne ~30:**
```kotlin
private const val API_BASE_URL = "http://10.0.2.2/ExpertMaintenance/backend/api.php"
```

**Pour appareil physique, ligne ~30:**
```kotlin
private const val API_BASE_URL = "http://192.168.1.XXX/ExpertMaintenance/backend/api.php"
// Remplacez XXX par l'IP trouvée avec la commande ci-dessus
```

### Ouvrir le Projet Android

```bash
open -a "Android Studio" /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/android-app/
```

---

## ✅ Checklist Rapide

Exécutez ces commandes dans l'ordre:

```bash
# 1. Arrêter Apache système
sudo apachectl stop

# 2. Copier le backend
sudo mkdir -p /Applications/XAMPP/htdocs/ExpertMaintenance/backend
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/*.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/test_quick.sh /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo chmod 644 /Applications/XAMPP/htdocs/ExpertMaintenance/backend/*.php
sudo chmod +x /Applications/XAMPP/htdocs/ExpertMaintenance/backend/test_quick.sh

# 3. Vérifier
ls -la /Applications/XAMPP/htdocs/ExpertMaintenance/backend/

# 4. Tester l'API
curl "http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1"

# 5. Si succès, ouvrez la page de test
open http://localhost/ExpertMaintenance/backend/test_api.php
```

---

## 🎯 URLs Importantes

| URL | Description |
|-----|-------------|
| `http://localhost/phpmyadmin` | Gestion de la base de données |
| `http://localhost/ExpertMaintenance/backend/test_api.php` | Page de test interactive |
| `http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1` | Test synchronisation |
| `http://localhost/ExpertMaintenance/backend/api.php?action=authenticate` | Test authentification (POST) |

---

## 📞 Support

### Fichiers de Log

```bash
# API
cat /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log

# Apache
cat /Applications/XAMPP/var/log/apache2/error_log

# MySQL
cat /Applications/XAMPP/var/log/mysql_error.log
```

### Commandes de Diagnostic

```bash
# Vérifier Apache
ps aux | grep apache

# Vérifier MySQL
ps aux | grep mysql

# Vérifier le port 80
lsof -i :80

# Vérifier le port 8080
lsof -i :8080

# Vérifier les permissions
ls -la /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
```

---

*Expert Maintenance - Commandes à Copier-Coller*
*Version: 1.0 - Janvier 2024*
*Gardez ce fichier sous la main pour référence rapide*