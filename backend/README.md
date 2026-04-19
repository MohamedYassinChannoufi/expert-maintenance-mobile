# Expert Maintenance - Backend API

Backend PHP pour l'application mobile Expert Maintenance.

## 📋 Configuration Requise

- **XAMPP** v3.2.4 ou supérieur
- **PHP** 7.4 ou supérieur
- **MySQL** 10.4 ou supérieur (MariaDB)

## 🚀 Installation

### Étape 1: Démarrer XAMPP

1. Lancez XAMPP Control Panel
2. Démarrez **Apache** (cliquez sur "Start")
3. Démarrez **MySQL** (cliquez sur "Start")

> ⚠️ **Si Apache ne démarre pas:**
> - Le port 80 est probablement utilisé par un autre service
> - Solution 1: Arrêtez le service Apache système: `sudo apachectl stop`
> - Solution 2: Changez le port Apache dans `httpd.conf` (8080 au lieu de 80)

### Étape 2: Importer la Base de Données

1. Ouvrez phpMyAdmin: http://localhost/phpmyadmin
2. Créez une nouvelle base de données nommée `gem`
3. Cliquez sur l'onglet "Importer"
4. Sélectionnez le fichier: `ExpertMaintenance/database/gem_database_fixed.sql`
5. Cliquez sur "Exécuter"

### Étape 3: Copier le Backend dans XAMPP

**Sur macOS:**
```bash
# Localiser le dossier htdocs de XAMPP
# Généralement: /Applications/XAMPP/htdocs

# Créer le dossier ExpertMaintenance
mkdir -p /Applications/XAMPP/htdocs/ExpertMaintenance

# Copier le dossier backend
cp -r /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend /Applications/XAMPP/htdocs/ExpertMaintenance/
```

**Sur Windows:**
```
1. Copiez le dossier "backend" dans: C:\xampp\htdocs\ExpertMaintenance\
```

**Sur Linux:**
```bash
sudo cp -r backend /opt/lampp/htdocs/ExpertMaintenance/
```

### Étape 4: Vérifier la Configuration

Ouvrez le fichier `api.php` et vérifiez les paramètres de connexion:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gem');
define('DB_USER', 'root');
define('DB_PASS', ''); // Vide par défaut sur XAMPP
```

## 🧪 Tester l'API

### Test 1: Vérifier la connexion à la base de données

Ouvrez votre navigateur et accédez à:
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

### Test 2: Tester l'authentification

Utilisez Postman ou curl:
```bash
curl -X POST http://localhost/ExpertMaintenance/backend/api.php?action=authenticate \
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
    ...
  },
  "token": "abc123..."
}
```

### Test 3: Tester les autres endpoints

| Endpoint | URL | Méthode |
|----------|-----|---------|
| Sync Employés | `?action=sync_employees&last_sync=0` | GET |
| Sync Clients | `?action=sync_clients&last_sync=0` | GET |
| Sync Sites | `?action=sync_sites&last_sync=0` | GET |
| Sync Interventions | `?action=sync_interventions&last_sync=0&employee_id=1` | GET |
| Sync Tâches | `?action=sync_tasks&last_sync=0` | GET |
| Sync Priorités | `?action=sync_priorities&last_sync=0` | GET |
| Sync Images | `?action=sync_images&last_sync=0` | GET |
| Détails Intervention | `?action=get_intervention&id=1` | GET |
| Historique Site | `?action=get_intervention_history&site_id=1&limit=50` | GET |
| Images Intervention | `?action=get_images&intervention_id=1` | GET |
| Image Binaire | `?action=get_image_binary&id=1` | GET |

## 📡 Endpoints API

### Authentication

#### POST `authenticate`
Authentifie un employé.

**Paramètres:**
- `login` (string): Identifiant
- `password` (string): Mot de passe

**Réponse:**
```json
{
  "success": true,
  "employee": {...},
  "token": "..."
}
```

### Synchronization

#### GET `full_sync`
Synchronisation complète avec delta-sync.

**Paramètres:**
- `last_sync` (int): Timestamp de dernière sync (0 pour tout)
- `employee_id` (int): ID de l'employé

**Réponse:**
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

#### GET `sync_employees`, `sync_clients`, `sync_sites`, `sync_interventions`, `sync_tasks`, `sync_priorities`, `sync_images`
Synchronisation individuelle par table.

**Paramètres:**
- `last_sync` (int): Timestamp de dernière sync
- `employee_id` (int): Pour interventions
- `intervention_id` (int): Pour tâches

### Interventions

#### GET `get_intervention`
Récupère les détails d'une intervention.

**Paramètres:**
- `id` (int): ID de l'intervention

**Réponse:**
```json
{
  "success": true,
  "intervention": {
    "id": 1,
    "titre": "...",
    "client_nom": "...",
    "site_adresse": "...",
    "taches": [...]
  }
}
```

#### POST `update_intervention`
Met à jour une intervention.

**Paramètres (JSON):**
- `id` (int): ID de l'intervention
- Champs à mettre à jour: `titre`, `terminee`, `commentaires`, etc.

**Réponse:**
```json
{
  "success": true,
  "message": "Intervention updated successfully"
}
```

#### GET `get_intervention_history`
Historique des interventions par site.

**Paramètres:**
- `site_id` (int): ID du site
- `limit` (int): Nombre max (défaut: 50)

### Images

#### POST `upload_image`
Upload d'image (Base64 ou multipart).

**Format JSON (Base64):**
```json
{
  "intervention_id": 1,
  "nom": "photo.jpg",
  "dateCapture": "2024-01-01",
  "img": "data:image/jpeg;base64,/9j/..."
}
```

**Format Multipart:**
- `image`: Fichier
- `intervention_id`: Integer
- `nom`: String (optionnel)
- `dateCapture`: Date (optionnel)

**Réponse:**
```json
{
  "success": true,
  "message": "Image uploaded successfully",
  "image_id": 123
}
```

#### GET `get_images`
Récupère les images d'une intervention.

**Paramètres:**
- `intervention_id` (int): ID de l'intervention

**Réponse:**
```json
{
  "success": true,
  "images": [
    {
      "id": 1,
      "nom": "photo.jpg",
      "dateCapture": "2024-01-01",
      "img_base64": "/9j/..."
    }
  ]
}
```

#### GET `get_image_binary`
Récupère les données binaires d'une image.

**Paramètres:**
- `id` (int): ID de l'image

**Réponse:** Image JPEG binaire

#### GET `delete_image`
Supprime une image.

**Paramètres:**
- `id` (int): ID de l'image

## 🔧 Dépannage

### Apache ne démarre pas

**Problème:** Port 80 déjà utilisé

**Solution 1 - macOS:**
```bash
sudo apachectl stop
```

**Solution 2 - Changer le port:**
1. Ouvrez XAMPP → Apache → Config → httpd.conf
2. Cherchez `Listen 80` → changez en `Listen 8080`
3. Cherchez `ServerName localhost:80` → changez en `ServerName localhost:8080`
4. Redémarrez Apache

### Erreur de connexion à la base de données

**Vérifications:**
1. MySQL est-il démarré dans XAMPP?
2. La base `gem` existe-t-elle?
3. Les identifiants sont-ils corrects dans `api.php`?

**Test:**
```
http://localhost/phpmyadmin
```

### Erreur CORS

Les en-têtes CORS sont déjà configurés dans `api.php`. Si vous avez des problèmes:

```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

### Logs d'erreur

Consultez le fichier `api.log` créé dans le dossier backend pour déboguer.

## 📊 Stratégie de Synchronisation

Chaque table utilise le champ `valsync` pour le delta-sync:

1. **À chaque modification** sur le serveur, `valsync` est incrémenté
2. **L'application mobile** stocke son dernier timestamp de sync
3. **Lors de la sync**, seul les enregistrements avec `valsync > last_sync` sont récupérés

## 🔐 Sécurité

⚠️ **Cette version est pour le développement uniquement!**

Pour la production:
- [ ] Implémenter hachage des mots de passe (bcrypt)
- [ ] Utiliser HTTPS (SSL/TLS)
- [ ] Tokens JWT pour authentification
- [ ] Rate limiting
- [ ] Validation des entrées

## 📞 Support

- **Logs:** `api.log` dans le dossier backend
- **phpMyAdmin:** http://localhost/phpmyadmin
- **Test API:** Utilisez Postman ou le navigateur

---

*Expert Maintenance Backend v1.0 - Janvier 2024*