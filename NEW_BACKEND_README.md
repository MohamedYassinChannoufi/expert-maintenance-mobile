# 🎉 Expert Maintenance - Nouveau Backend Complet

## 📋 Vue d'Ensemble

Ce dossier contient le **nouveau backend complet et fonctionnel** pour l'application Expert Maintenance. L'ancien backend a été entièrement refait pour résoudre les problèmes rencontrés.

---

## 🔍 Pourquoi un Nouveau Backend?

### Problèmes de l'Ancien Backend
1. Code incomplet dans certaines sections
2. Certains endpoints manquants ou mal implémentés
3. Structure peu claire et difficile à maintenir
4. Apache XAMPP ne démarrait pas (conflit de port)

### Solutions Apportées
✅ Code complet et bien structuré (987 lignes)
✅ Tous les 16 endpoints requis implémentés
✅ Gestion d'erreurs robuste avec logging détaillé
✅ Support CORS pour l'application mobile
✅ Compatible avec l'application Android existante
✅ Page de test interactive incluse

---

## 📁 Fichiers Créés

| Fichier | Description | Lignes |
|---------|-------------|--------|
| `api.php` | API principale avec tous les endpoints | 987 |
| `test_api.php` | Page de test interactive web | 560 |
| `config.example.php` | Template de configuration | 353 |
| `README.md` | Documentation du backend | - |
| `test_quick.sh` | Script de test en ligne de commande | 200 |

---

## 🚀 Installation Rapide

### Étape 1: Résoudre le Problème Apache (macOS)

**Option A - Arrêter l'Apache système:**
```bash
sudo apachectl stop
```

**Option B - Changer le port Apache:**
1. XAMPP → Configure → httpd.conf
2. `Listen 80` → `Listen 8080`
3. `ServerName localhost:80` → `ServerName localhost:8080`
4. Redémarrer Apache

### Étape 2: Démarrer XAMPP

1. Ouvrir XAMPP
2. Démarrer **MySQL** (doit être "Running" ✓)
3. Démarrer **Apache** (doit être "Running" ✓)

### Étape 3: Importer la Base de Données

1. Ouvrir phpMyAdmin: `http://localhost/phpmyadmin`
2. Créer la base `gem`
3. Importer: `ExpertMaintenance/database/gem_database_fixed.sql`

### Étape 4: Copier le Backend dans XAMPP

**macOS:**
```bash
sudo mkdir -p /Applications/XAMPP/htdocs/ExpertMaintenance/backend
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/*.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo chmod 644 /Applications/XAMPP/htdocs/ExpertMaintenance/backend/*.php
```

**Windows:**
```
Copier le dossier "backend" dans: C:\xampp\htdocs\ExpertMaintenance\
```

**Linux:**
```bash
sudo cp -r backend /opt/lampp/htdocs/ExpertMaintenance/
```

---

## 🧪 Tester l'API

### Méthode 1: Page de Test Interactive (Recommandé)

Ouvrez votre navigateur:
```
http://localhost/ExpertMaintenance/backend/test_api.php
```

Cliquez sur "🚀 Lancer tous les tests" et vérifiez que tous les tests sont verts ✓

### Méthode 2: Script de Test CLI

**macOS/Linux:**
```bash
cd /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
./test_quick.sh
```

### Méthode 3: Test Manuel

Ouvrez dans votre navigateur:
```
http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1
```

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

---

## 📡 Endpoints API

### Authentification

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `authenticate` | POST | Authentification employé |

**Exemple:**
```bash
curl -X POST "http://localhost/ExpertMaintenance/backend/api.php?action=authenticate" \
  -H "Content-Type: application/json" \
  -d '{"login":"admin","password":"admin123"}'
```

### Synchronisation

| Endpoint | Méthode | Paramètres |
|----------|---------|------------|
| `full_sync` | GET | `last_sync`, `employee_id` |
| `sync_employees` | GET | `last_sync` |
| `sync_clients` | GET | `last_sync` |
| `sync_sites` | GET | `last_sync` |
| `sync_interventions` | GET | `last_sync`, `employee_id` |
| `sync_tasks` | GET | `last_sync`, `intervention_id` |
| `sync_priorities` | GET | `last_sync` |
| `sync_images` | GET | `last_sync` |

### Interventions

| Endpoint | Méthode | Paramètres |
|----------|---------|------------|
| `get_intervention` | GET | `id` |
| `update_intervention` | POST | JSON avec champs à mettre à jour |
| `get_intervention_history` | GET | `site_id`, `limit` |

### Images

| Endpoint | Méthode | Paramètres |
|----------|---------|------------|
| `upload_image` | POST | JSON (Base64) ou Multipart |
| `get_images` | GET | `intervention_id` |
| `get_image_binary` | GET | `id` |
| `delete_image` | GET | `id` |

---

## 🔧 Configuration Android

### Modifier l'URL de l'API

**Fichier:** `android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt`

**Pour l'émulateur Android:**
```kotlin
private const val API_BASE_URL = "http://10.0.2.2/ExpertMaintenance/backend/api.php"
```

**Pour appareil physique (même WiFi):**
```kotlin
private const val API_BASE_URL = "http://192.168.1.XXX/ExpertMaintenance/backend/api.php"
```

**Si vous utilisez le port 8080:**
```kotlin
private const val API_BASE_URL = "http://10.0.2.2:8080/ExpertMaintenance/backend/api.php"
```

### Autoriser le Traffic HTTP

Dans `AndroidManifest.xml`:
```xml
<application
    android:usesCleartextTraffic="true"
    ...>
```

---

## 🗄️ Base de Données

### Structure

9 tables principales:
- `clients` - Entreprises clientes
- `contrats` - Contrats de maintenance
- `employes` - Techniciens
- `employes_interventions` - Assignation employés
- `images` - Photos capturées
- `interventions` - Interventions
- `priorites` - Niveaux de priorité
- `sites` - Lieux d'intervention
- `taches` - Tâches spécifiques

### Stratégie de Synchronisation

Chaque table utilise le champ `valsync`:
1. À chaque modification sur le serveur, `valsync` est incrémenté
2. L'application mobile stocke son dernier timestamp de sync
3. Lors de la sync, seuls les enregistrements avec `valsync > last_sync` sont récupérés

### Données de Test

**Employés:**
- `admin` / `admin123` (Jean Dupont)
- `enzo` / `enzo123` (Enzo Martin)

**Interventions:**
- Intervention Mobile - 21/06/2018, 07:00-10:00 (Terminée ✓)
- Intervention Mobile 2 - 21/06/2018, 15:00-18:00 (En attente ⬜)

---

## 🐛 Dépannage

### Apache ne démarre pas

**Problème:** Port 80 déjà utilisé

**Solution macOS:**
```bash
sudo apachectl stop
# Puis redémarrer Apache dans XAMPP
```

**Solution alternative:** Changer le port dans `httpd.conf`

### Erreur de connexion à la base de données

**Vérifications:**
1. ✅ MySQL est-il "Running" dans XAMPP?
2. ✅ La base `gem` existe-t-elle?
3. ✅ Les credentials sont-ils corrects dans `api.php`?

**Test:**
```
http://localhost/phpmyadmin
```

### L'API retourne une erreur

**Consulter les logs:**
```bash
tail -f /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log
```

### Android ne se connecte pas

**Vérifications:**
1. ✅ URL correcte dans `SyncManager.kt`?
2. ✅ Pour émulateur: utilisez `10.0.2.2` pas `localhost`
3. ✅ `android:usesCleartextTraffic="true"` dans le manifest?

---

## 📞 Support

### Fichiers de Log

- **API:** `backend/api.log`
- **Apache:** `/Applications/XAMPP/var/log/apache2/error_log`
- **MySQL:** Via phpMyAdmin → Logs

### URLs Utiles

- **phpMyAdmin:** `http://localhost/phpmyadmin`
- **Test API:** `http://localhost/ExpertMaintenance/backend/test_api.php`
- **Full Sync:** `http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1`

### Commandes Utiles

```bash
# Voir les logs en temps réel
tail -f /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log

# Vérifier si Apache tourne
ps aux | grep apache

# Trouver son IP (pour appareil physique)
ifconfig | grep "inet "
```

---

## ✅ Checklist Finale

- [ ] XAMPP installé
- [ ] Apache démarré (icône verte)
- [ ] MySQL démarré (icône verte)
- [ ] Base `gem` créée dans phpMyAdmin
- [ ] Script SQL importé (9 tables visibles)
- [ ] Dossier backend copié dans htdocs
- [ ] Test API réussi (page test_api.php)
- [ ] Authentication testée (admin/admin123)
- [ ] URL API configurée dans Android
- [ ] Application Android peut se connecter
- [ ] Synchronisation fonctionne
- [ ] Interventions s'affichent correctement

---

## 📚 Documentation Complète

Pour plus de détails, consultez:

1. **BACKEND_SETUP_GUIDE.md** - Guide complet d'installation
2. **BACKEND_COMPLETE.md** - Résumé détaillé du nouveau backend
3. **backend/README.md** - Documentation spécifique du backend
4. **PROJECT_SUMMARY.md** - Vue d'ensemble du projet complet

---

## 🎯 Prochaines Étapes

1. ✅ Suivez le guide d'installation (`BACKEND_SETUP_GUIDE.md`)
2. ✅ Testez avec `test_api.php`
3. ✅ Configurez l'application Android
4. ✅ Lancez et testez l'application complète

---

**🎉 Félicitations! Votre backend est maintenant opérationnel!**

*Expert Maintenance - Backend v1.0*
*Janvier 2024*