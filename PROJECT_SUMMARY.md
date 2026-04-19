# Expert Maintenance - Project Summary

## 🎯 Project Overview

**Expert Maintenance** est une application mobile Android complète destinée à la gestion et au suivi des interventions de maintenance informatique sur les sites clients. Cette application permet aux techniciens de terrain de consulter leurs interventions assignées, de mettre à jour leur statut, de capturer des photos, et de synchroniser les données avec une base de données MySQL centrale.

---

## 📦 Contenu du Projet

### 1. Application Android (`android-app/`)

Une application Android native développée en **Kotlin** suivant l'architecture **MVVM**.

#### Structure Principale

```
android-app/
├── app/src/main/
│   ├── java/com/expert/maintenance/
│   │   ├── api/                    # Interfaces Retrofit pour l'API
│   │   │   └── ApiService.kt
│   │   ├── data/                   # Modèles et logique de données
│   │   │   ├── local/              # Base de données Room (SQLite)
│   │   │   │   ├── dao/            # Data Access Objects
│   │   │   │   │   └── DaoInterfaces.kt
│   │   │   │   └── entity/         # Entités de la base locale
│   │   │   │       └── LocalEntities.kt
│   │   │   ├── Models.kt           # Modèles de données
│   │   │   ├── AppDatabase.kt      # Configuration Room
│   │   │   └── SyncManager.kt      # Gestion de la synchronisation
│   │   ├── ui/                     # Activités et interfaces
│   │   │   ├── LoginActivity.kt    # Écran de connexion
│   │   │   ├── MainActivity.kt     # Liste des interventions
│   │   │   ├── InterventionDetailsActivity.kt
│   │   │   ├── InterventionEditActivity.kt
│   │   │   ├── ImageCaptureActivity.kt
│   │   │   ├── MapActivity.kt
│   │   │   ├── HistoryActivity.kt
│   │   │   └── SettingsActivity.kt
│   │   └── adapters/               # Adaptateurs RecyclerView
│   │       └── InterventionAdapter.kt
│   └── res/
│       ├── layout/                 # Fichiers XML de mise en page
│       │   ├── activity_login.xml
│       │   ├── activity_main.xml
│       │   ├── item_intervention.xml
│       │   └── nav_header.xml
│       ├── values/                 # Ressources
│       │   ├── strings.xml
│       │   ├── colors.xml
│       │   └── themes.xml
│       ├── drawable/               # Icônes et images vectorielles
│       ├── menu/                   # Menus de navigation
│       └── xml/                    # Configuration FileProvider
├── build.gradle                    # Configuration Gradle
└── gradle.properties               # Propriétés Gradle
```

#### Fonctionnalités Clés

- **Authentification sécurisée** avec gestion de session
- **Synchronisation automatique** MySQL ↔ SQLite
- **Navigation par date** (jour précédent/suivant)
- **Suivi de complétion** avec confirmation
- **Capture et upload d'images**
- **Intégration Google Maps**
- **Mode hors ligne** avec cache local

---

### 2. Backend PHP (`backend/`)

Une API REST simple développée en **PHP** pour connecter l'application mobile à la base de données MySQL.

#### Points de Terminaison

| Action | Méthode | Description |
|--------|---------|-------------|
| `authenticate` | POST | Authentification employé |
| `full_sync` | GET | Synchronisation complète |
| `sync_employees` | GET | Sync employés |
| `sync_clients` | GET | Sync clients |
| `sync_sites` | GET | Sync sites |
| `sync_interventions` | GET | Sync interventions |
| `sync_tasks` | GET | Sync tâches |
| `sync_priorities` | GET | Sync priorités |
| `sync_images` | GET | Sync images (métadonnées) |
| `get_intervention` | GET | Détails intervention |
| `update_intervention` | POST | Mise à jour intervention |
| `get_intervention_history` | GET | Historique par site |
| `upload_image` | POST (multipart) | Upload image |
| `get_images` | GET | Images d'une intervention |
| `get_image_binary` | GET | Image binaire |
| `delete_image` | GET | Supprimer image |

#### Stratégie de Synchronisation

Chaque table contient un champ `valsync` qui est incrémenté à chaque modification. La synchronisation utilise ce champ pour déterminer quels enregistrements doivent être mis à jour:

```sql
SELECT * FROM table WHERE valsync > last_sync_value
```

---

### 3. Base de Données MySQL (`database/`)

Schéma de base de données relationnelle pour la gestion des interventions.

#### Tables Principales

| Table | Description | Champs Principaux |
|-------|-------------|-------------------|
| `clients` | Entreprises clientes | id, nom, adresse, tel, email, contact |
| `sites` | Lieux d'intervention | id, client_id, longitude, latitude, adresse, ville |
| `employes` | Techniciens | id, login, pwd, nom, prenom, email, actif |
| `interventions` | Interventions | id, titre, dates, heures, terminee, site_id, priorite_id |
| `taches` | Tâches spécifiques | id, intervention_id, nom, duree, prixheure |
| `priorites` | Niveaux de priorité | id, nom (Normale, Urgente, Critique) |
| `images` | Photos capturées | id, intervention_id, img (BLOB), dateCapture |
| `contrats` | Contrats de maintenance | id, client_id, datedebut, datefin, redevence |
| `employes_interventions` | Assignment employés | employe_id, intervention_id |

#### Diagramme de Relations

```
clients (1) ----< sites (N)
clients (1) ----< contrats (N)
sites (1) ----< interventions (N)
priorites (1) ----< interventions (N)
interventions (1) ----< taches (N)
interventions (1) ----< images (N)
employes (N) >< (N) interventions (via employes_interventions)
```

---

## 🔧 Technologies Utilisées

### Mobile (Android)
- **Langage**: Kotlin
- **SDK Minimum**: API 24 (Android 7.0)
- **SDK Cible**: API 34 (Android 14)
- **Architecture**: MVVM
- **Base de données locale**: Room (SQLite)
- **Réseau**: Retrofit + OkHttp
- **UI**: Material Design Components
- **Navigation**: Navigation Drawer + RecyclerView
- **Coroutines**: Gestion asynchrone

### Backend (Serveur)
- **Serveur Web**: Apache (XAMPP)
- **Langage**: PHP 7.4+
- **Base de données**: MySQL 10.4+ (MariaDB)
- **API Style**: REST

### Outils de Développement
- **IDE Android**: Android Studio
- **Serveur Local**: XAMPP v3.2.4+
- **Gestion de dépendances**: Gradle
- **Contrôle de version**: Git (recommandé)

---

## 📋 Configuration Requise

### Pour le Développement
- **Système d'exploitation**: Windows 10+, macOS 10.14+, Linux
- **RAM**: 8 GB minimum (16 GB recommandé)
- **Espace disque**: 5 GB libre minimum
- **Connexion Internet**: Pour téléchargement dépendances

### Pour l'Exécution
- **XAMPP**: v3.2.4 ou supérieur
- **Android Studio**: Arctic Fox (2020.3.1) ou supérieur
- **JDK**: Version 11 ou supérieure
- **Émulateur/Device**: Android 7.0+ (API 24+)

---

## 🚀 Installation Rapide

### Étape 1: Backend
```bash
1. Installer XAMPP
2. Démarrer Apache et MySQL
3. Importer database/gem_database.sql dans phpMyAdmin
4. Copier backend/api.php dans htdocs/ExpertMaintenance/backend/
```

### Étape 2: Android
```bash
1. Ouvrir android-app/ dans Android Studio
2. Attendre la synchronisation Gradle
3. Configurer GOOGLE_MAPS_API_KEY dans AndroidManifest.xml
4. Build → Make Project
5. Run → Run 'app'
```

### Étape 3: Test
```
Login: admin / Password: admin123
ou
Login: enzo / Password: enzo123
```

---

## 🎓 Cas d'Utilisation

### Scénario Principal

1. **Authentification**
   - Le technicien ouvre l'application
   - Saisit son login et mot de passe
   - Synchronisation automatique au premier lancement

2. **Consultation des Interventions**
   - Affichage des interventions du jour
   - Navigation entre les jours (flèches)
   - Visualisation du statut (coché = terminé)

3. **Mise à jour du Statut**
   - Cocher la case quand intervention terminée
   - Confirmation pour décocher

4. **Détails de l'Intervention**
   - Clic sur une intervention
   - Vue détaillée: planifié vs effectué
   - Infos client, adresse, tâches
   - Bouton pour ouvrir Google Maps

5. **Capture de Photos**
   - Onglet "Fichiers"
   - Prendre photo ou choisir depuis galerie
   - Upload automatique au serveur

6. **Historique**
   - Consulter l'historique des interventions sur un site
   - Tableau avec dates et statuts

7. **Synchronisation**
   - Automatique au login
   - Manuel via bouton FAB
   - Mode hors ligne disponible

---

## 🔐 Sécurité (Développement)

⚠️ **Important**: Cette version est destinée au développement/éducation. Pour la production:

- [ ] Implémenter hachage de mots de passe (bcrypt)
- [ ] Utiliser HTTPS (SSL/TLS)
- [ ] Tokens JWT pour authentification
- [ ] Rate limiting sur l'API
- [ ] Validation des entrées utilisateur
- [ ] Protection CSRF/XSS

---

## 📊 Métriques du Projet

### Fichiers Créés
- **Android**: ~15 fichiers Kotlin
- **Layouts XML**: ~8 fichiers
- **Ressources**: ~5 fichiers (strings, colors, themes)
- **Backend**: 1 fichier PHP principal
- **Database**: 1 fichier SQL

### Lignes de Code Estimées
- **Kotlin**: ~3,500 lignes
- **XML**: ~1,500 lignes
- **PHP**: ~500 lignes
- **SQL**: ~250 lignes
- **Documentation**: ~1,000 lignes

**Total**: ~6,750 lignes de code

---

## 📁 Arborescence Complète

```
ExpertMaintenance/
│
├── README.md                     # Documentation complète
├── QUICK_START.md               # Guide de démarrage rapide
├── PROJECT_SUMMARY.md           # Ce fichier
│
├── android-app/                 # Application Android
│   ├── app/
│   │   ├── src/main/
│   │   │   ├── java/com/expert/maintenance/
│   │   │   │   ├── api/
│   │   │   │   │   └── ApiService.kt
│   │   │   │   ├── data/
│   │   │   │   │   ├── local/
│   │   │   │   │   │   ├── dao/
│   │   │   │   │   │   │   └── DaoInterfaces.kt
│   │   │   │   │   │   └── entity/
│   │   │   │   │   │       └── LocalEntities.kt
│   │   │   │   │   ├── Models.kt
│   │   │   │   │   ├── AppDatabase.kt
│   │   │   │   │   └── SyncManager.kt
│   │   │   │   ├── ui/
│   │   │   │   │   ├── LoginActivity.kt
│   │   │   │   │   ├── MainActivity.kt
│   │   │   │   │   └── (autres activités...)
│   │   │   │   └── adapters/
│   │   │   │       └── InterventionAdapter.kt
│   │   │   ├── res/
│   │   │   │   ├── layout/
│   │   │   │   │   ├── activity_login.xml
│   │   │   │   │   ├── activity_main.xml
│   │   │   │   │   ├── item_intervention.xml
│   │   │   │   │   └── nav_header.xml
│   │   │   │   ├── values/
│   │   │   │   │   ├── strings.xml
│   │   │   │   │   ├── colors.xml
│   │   │   │   │   └── themes.xml
│   │   │   │   ├── drawable/
│   │   │   │   │   └── ic_launcher_foreground.xml
│   │   │   │   ├── menu/
│   │   │   │   │   └── navigation_menu.xml
│   │   │   │   └── xml/
│   │   │   │       └── file_paths.xml
│   │   │   └── AndroidManifest.xml
│   │   └── build.gradle
│   ├── gradle/wrapper/
│   │   └── gradle-wrapper.properties
│   ├── build.gradle
│   ├── settings.gradle
│   └── gradle.properties
│
├── backend/                     # API PHP
│   └── api.php
│
└── database/                    # Scripts SQL
    └── gem_database.sql
```

---

## 🎯 Objectifs Pédagogiques

Ce projet démontre:

1. ✅ **Architecture Mobile Moderne**: MVVM avec Room et Retrofit
2. ✅ **Synchronisation de Données**: Stratégie delta-sync avec valsync
3. ✅ **Authentification**: Gestion de session et mode hors ligne
4. ✅ **Base de Données Relationnelle**: Modélisation UML → SQL
5. ✅ **API REST**: Communication client-serveur
6. ✅ **UI/UX Mobile**: Material Design, navigation drawer
7. ✅ **Permissions Android**: Caméra, stockage, localisation
8. ✅ **Gestion d'Images**: Capture, stockage, upload

---

## 📞 Support et Ressources

### Documentation
- **README.md**: Guide d'installation détaillé
- **QUICK_START.md**: Démarrage en 15 minutes
- **Code Comments**: Commentaires inline dans le code

### Debugging
- **Logcat**: Logs Android dans Android Studio
- **phpMyAdmin**: Gestion et débogage MySQL
- **Apache Logs**: XAMPP → Apache → Logs

### Ressources Externes
- [Documentation Android](https://developer.android.com/)
- [Documentation Retrofit](https://square.github.io/retrofit/)
- [Documentation Room](https://developer.android.com/training/data-storage/room)
- [Documentation Material Design](https://material.io/develop/android)

---

## 🏆 Conclusion

**Expert Maintenance** est une application complète et fonctionnelle qui couvre l'ensemble du cycle de développement d'une application mobile enterprise:

- **Backend** robuste avec API PHP
- **Base de données** relationnelle MySQL
- **Application mobile** Android native Kotlin
- **Synchronisation** bidirectionnelle
- **Expérience utilisateur** soignée

Ce projet peut servir de base pour:
- Apprendre le développement Android professionnel
- Comprendre l'architecture client-serveur
- Implémenter des fonctionnalités enterprise réelles
- Étendre avec de nouvelles fonctionnalités

**Prêt à être déployé et personnalisé selon vos besoins!** 🚀

---

*Document créé pour le projet "Expert Maintenance" - Développement Mobile Android*
*Version: 1.0 - Janvier 2024*