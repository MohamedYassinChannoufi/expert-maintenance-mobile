# Expert Maintenance - Complete File List

This document provides a comprehensive list of all files created for the Expert Maintenance project, organized by category and location.

---

## 📁 Project Root Files

### `/ExpertMaintenance/`

| File | Purpose |
|------|---------|
| `README.md` | Complete documentation with installation, configuration, and troubleshooting guide |
| `QUICK_START.md` | Quick start guide for 15-minute setup |
| `PROJECT_SUMMARY.md` | Project overview, architecture, and feature summary |
| `FILES_LIST.md` | This file - complete file inventory |

---

## 📱 Android Application Files

### `/ExpertMaintenance/android-app/`

#### Root Configuration Files

| File | Purpose |
|------|---------|
| `build.gradle` | Project-level Gradle configuration (Kotlin version, repositories) |
| `settings.gradle` | Gradle settings and module inclusion |
| `gradle.properties` | Gradle JVM and build configuration |
| `gradle/wrapper/gradle-wrapper.properties` | Gradle wrapper version specification (7.5) |

#### App Module Configuration

| File | Purpose |
|------|---------|
| `app/build.gradle` | App-level dependencies, SDK versions, build config |
| `app/proguard-rules.pro` | ProGuard/R8 obfuscation rules for release builds |

---

### `/ExpertMaintenance/android-app/app/src/main/`

#### Android Manifest

| File | Purpose |
|------|---------|
| `AndroidManifest.xml` | App permissions, activities declaration, providers, metadata |

---

### `/ExpertMaintenance/android-app/app/src/main/java/com/expert/maintenance/`

#### Data Layer (`data/`)

| File | Purpose |
|------|---------|
| `data/Models.kt` | Data classes for API responses, DTOs, sync data structures |
| `data/AppDatabase.kt` | Room database configuration and type converters |
| `data/SyncManager.kt` | Synchronization logic between MySQL and SQLite |

#### Data Layer - Local Database (`data/local/dao/`)

| File | Purpose |
|------|---------|
| `data/local/dao/DaoInterfaces.kt` | All DAO interfaces for Room (Employee, Client, Site, Intervention, Task, Priority, Image, Contract, EmployeeIntervention) |

#### Data Layer - Local Entities (`data/local/entity/`)

| File | Purpose |
|------|---------|
| `data/local/entity/LocalEntities.kt` | All Room entity classes matching database tables |

#### API Layer (`api/`)

| File | Purpose |
|------|---------|
| `api/ApiService.kt` | Retrofit interface for all API endpoints + response DTOs |

#### UI Layer (`ui/`)

| File | Purpose |
|------|---------|
| `ui/LoginActivity.kt` | Login screen with authentication logic |
| `ui/MainActivity.kt` | Main screen with interventions list, date navigation, sync |

#### Adapters (`adapters/`)

| File | Purpose |
|------|---------|
| `adapters/InterventionAdapter.kt` | RecyclerView adapter for intervention list items |

---

### `/ExpertMaintenance/android-app/app/src/main/res/`

#### Layouts (`res/layout/`)

| File | Purpose |
|------|---------|
| `layout/activity_login.xml` | Login screen layout with Material Design components |
| `layout/activity_main.xml` | Main activity with drawer layout, toolbar, RecyclerView |
| `layout/item_intervention.xml` | Individual intervention card layout |
| `layout/nav_header.xml` | Navigation drawer header with employee info |

#### Values (`res/values/`)

| File | Purpose |
|------|---------|
| `values/strings.xml` | All string resources (French) |
| `values/colors.xml` | Color palette definitions |
| `values/themes.xml` | App themes and styles |

#### Drawables (`res/drawable/`)

| File | Purpose |
|------|---------|
| `drawable/ic_launcher_foreground.xml` | App icon (vector drawable - maintenance wrench) |
| `drawable/circle_priority.xml` | Priority indicator circle shape |
| `drawable/ic_menu.xml` | Hamburger menu icon |
| `drawable/ic_arrow_left.xml` | Left arrow for date navigation |
| `drawable/ic_arrow_right.xml` | Right arrow for date navigation |
| `drawable/ic_sync.xml` | Synchronization/refresh icon |
| `drawable/ic_login.xml` | Login button icon |
| `drawable/ic_person.xml` | Person/user icon |
| `drawable/ic_lock.xml` | Password/lock icon |
| `drawable/ic_logout.xml` | Logout icon |
| `drawable/ic_intervention.xml` | Intervention menu icon |
| `drawable/ic_assignment.xml` | Assignment menu icon |
| `drawable/ic_message.xml` | Messages menu icon |
| `drawable/ic_client.xml` | Client menu icon |
| `drawable/ic_location.xml` | Location menu icon |
| `drawable/ic_settings.xml` | Settings menu icon |
| `drawable/ic_info.xml` | Info/about menu icon |
| `drawable/ic_calendar.xml` | Calendar icon for empty state |
| `drawable/nav_header_background.xml` | Navigation drawer header gradient |

#### Menu (`res/menu/`)

| File | Purpose |
|------|---------|
| `menu/navigation_menu.xml` | Navigation drawer menu items |

#### XML Configuration (`res/xml/`)

| File | Purpose |
|------|---------|
| `xml/file_paths.xml` | FileProvider paths for camera/storage access |

---

## 🖥️ Backend Files

### `/ExpertMaintenance/backend/`

| File | Purpose |
|------|---------|
| `api.php` | PHP REST API for all backend operations (auth, sync, CRUD) |

#### API Endpoints Implemented:
- `authenticate` - Employee authentication
- `full_sync` - Full data synchronization
- `sync_employees` - Sync employees table
- `sync_clients` - Sync clients table
- `sync_sites` - Sync sites table
- `sync_interventions` - Sync interventions table
- `sync_tasks` - Sync tasks table
- `sync_priorities` - Sync priorities table
- `sync_images` - Sync images metadata
- `get_intervention` - Get intervention details
- `update_intervention` - Update intervention data
- `get_intervention_history` - Get site intervention history
- `upload_image` - Upload image to server
- `get_images` - Get images for intervention
- `get_image_binary` - Get image binary data
- `delete_image` - Delete image

---

## 🗄️ Database Files

### `/ExpertMaintenance/database/`

| File | Purpose |
|------|---------|
| `gem_database.sql` | MySQL database creation script with schema and demo data |

#### Database Tables:
1. `clients` - Customer companies
2. `contrats` - Service contracts
3. `employes` - Technicians/employees
4. `employes_interventions` - Employee-intervention assignments
5. `images` - Captured photos
6. `interventions` - Maintenance interventions
7. `priorites` - Priority levels
8. `sites` - Work locations
9. `taches` - Specific tasks

---

## 📊 File Statistics

### By Category

| Category | Count | Location |
|----------|-------|----------|
| **Documentation** | 4 | Root `/` |
| **Gradle Config** | 5 | `android-app/` |
| **Kotlin Source** | 8 | `android-app/app/src/main/java/` |
| **Layout XML** | 4 | `res/layout/` |
| **Values XML** | 3 | `res/values/` |
| **Drawable XML** | 16 | `res/drawable/` |
| **Menu XML** | 1 | `res/menu/` |
| **Config XML** | 2 | `res/xml/` + manifest |
| **PHP Backend** | 1 | `backend/` |
| **SQL Database** | 1 | `database/` |

**Total Files Created: ~45**

### By Technology

| Technology | Files | Lines of Code (approx) |
|------------|-------|------------------------|
| Kotlin | 8 | 3,500 |
| XML (Android) | 26 | 1,500 |
| PHP | 1 | 500 |
| SQL | 1 | 250 |
| Markdown | 4 | 1,000 |
| **Total** | **40** | **~6,750** |

---

## 🔑 Key Files for Setup

### Essential Files (Must Configure)

1. **`android-app/app/src/main/AndroidManifest.xml`**
   - Configure: `GOOGLE_MAPS_API_KEY`

2. **`android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt`**
   - Configure: `API_BASE_URL` for physical devices

3. **`backend/api.php`**
   - Configure: Database credentials (DB_HOST, DB_NAME, DB_USER, DB_PASS)

4. **`database/gem_database.sql`**
   - Import to: MySQL via phpMyAdmin

### Optional Files (Can Modify)

1. **`android-app/app/build.gradle`**
   - Modify: SDK versions, dependencies

2. **`android-app/gradle.properties`**
   - Modify: JVM memory, build options

3. **`res/values/strings.xml`**
   - Modify: Text content, translations

4. **`res/values/colors.xml`**
   - Modify: Color scheme

---

## 📝 Files to Create (Missing)

The following files should be created manually or downloaded:

### Drawable Resources (Simple Vector Icons)

Create these in `res/drawable/` using Android Studio's Vector Asset Studio:

1. `ic_intervention.xml` - Wrench/tool icon
2. `ic_assignment.xml` - Assignment/clipboard icon
3. `ic_message.xml` - Message/chat icon
4. `ic_client.xml` - Client/person icon
5. `ic_location.xml` - Location/pin icon
6. `ic_settings.xml` - Settings/gear icon
7. `ic_info.xml` - Info icon
8. `ic_logout.xml` - Logout/exit icon
9. `ic_arrow_left.xml` - Left arrow
10. `ic_arrow_right.xml` - Right arrow
11. `ic_sync.xml` - Refresh/sync icon
12. `ic_calendar.xml` - Calendar icon
13. `nav_header_background.xml` - Gradient background

**Quick Creation Method:**
- Right-click `drawable` folder → New → Vector Asset
- Choose from Material Icons or import SVG
- Set appropriate tint colors

### Additional Activities (To Implement)

The following activities are referenced but need implementation:

1. `ui/InterventionDetailsActivity.kt` - Intervention details view
2. `ui/InterventionEditActivity.kt` - Edit intervention
3. `ui/ImageCaptureActivity.kt` - Camera capture
4. `ui/MapActivity.kt` - Google Maps integration
5. `ui/HistoryActivity.kt` - Intervention history
6. `ui/SettingsActivity.kt` - App settings

### Additional Layouts (To Create)

1. `layout/activity_intervention_details.xml`
2. `layout/activity_intervention_edit.xml`
3. `layout/activity_image_capture.xml`
4. `layout/activity_map.xml`
5. `layout/activity_history.xml`
6. `layout/activity_settings.xml`

---

## 🎯 Quick Reference

### Start Development

```bash
# 1. Open project
Android Studio → Open → android-app/

# 2. Sync Gradle
File → Sync Project with Gradle Files

# 3. Build
Build → Make Project

# 4. Run
Run → Run 'app'
```

### Deploy Backend

```bash
# 1. Copy API file
cp backend/api.php /xampp/htdocs/ExpertMaintenance/backend/

# 2. Import database
# Open phpMyAdmin → Import gem_database.sql

# 3. Start services
# XAMPP → Start Apache & MySQL
```

### Test Application

```
Default Credentials:
- Login: admin / Password: admin123
- Login: enzo / Password: enzo123

Test Date: June 21, 2018
Expected Interventions: 2
```

---

## 📞 Support Files

### Debugging

| Log Source | Location |
|------------|----------|
| Android Logs | Android Studio → Logcat |
| PHP Errors | XAMPP → Apache → Logs → error.log |
| MySQL Queries | phpMyAdmin → SQL tab |
| Network Requests | Android Studio → Network Profiler |

### Configuration Reference

| Setting | Default Value | File |
|---------|---------------|------|
| API URL | `http://10.0.2.2:8080/ExpertMaintenance/backend/api.php` | SyncManager.kt |
| Database | `gem` | api.php |
| Min SDK | API 24 | build.gradle |
| Target SDK | API 34 | build.gradle |

---

## ✅ Verification Checklist

After setup, verify these files exist and are configured:

### Backend
- [ ] `api.php` accessible via browser
- [ ] Database `gem` created
- [ ] Test API returns JSON

### Android
- [ ] Project builds successfully
- [ ] No Gradle errors
- [ ] Manifest has API key placeholder
- [ ] All dependencies resolved

### Runtime
- [ ] App launches
- [ ] Login works
- [ ] Interventions display
- [ ] Sync button functions

---

**File List Version: 1.0**
**Last Updated: January 2024**
**Project: Expert Maintenance - Android Mobile Application**