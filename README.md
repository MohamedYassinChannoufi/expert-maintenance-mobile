# Expert Maintenance - Android Mobile Application

A comprehensive mobile application for tracking and managing maintenance interventions. This application allows field technicians to view their assigned interventions, update completion status, capture photos, and synchronize data with a central MySQL database.

## 📋 Table of Contents

- [Features](#features)
- [Architecture](#architecture)
- [Prerequisites](#prerequisites)
- [Installation & Setup](#installation--setup)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Testing Credentials](#testing-credentials)
- [Troubleshooting](#troubleshooting)
- [Project Structure](#project-structure)

---

## ✨ Features

### Authentication
- Secure employee login with credentials validation
- Offline authentication support with cached credentials
- Session management with persistent login

### Synchronization
- Automatic synchronization between MySQL (remote) and SQLite (local)
- Delta sync using `valsync` field for efficient updates
- Manual sync trigger via floating action button
- Full data sync including: employees, clients, sites, interventions, tasks, priorities, images

### Interventions Management
- Daily view of assigned interventions
- Date navigation (previous/next day)
- Completion status tracking with checkbox
- Confirmation dialog for unchecking completed interventions
- Detailed intervention view with:
  - Planned vs. Actual information
  - Client details
  - Site location
  - Tasks list
  - Attached images
  - Intervention history

### Image Capture
- Camera integration for photo capture
- Gallery access for existing photos
- Image upload to server
- Local storage of images

### Maps Integration
- Google Maps integration for site location
- Navigate to intervention site

### Navigation
- Navigation drawer with menu options
- Interventions, Messages, Clients, Addresses, Settings

---

## 🏗️ Architecture

### Technology Stack
- **Mobile App**: Android (Kotlin)
- **Local Database**: SQLite with Room
- **Remote Database**: MySQL (via XAMPP)
- **Backend API**: PHP
- **Network**: Retrofit + OkHttp
- **Architecture**: MVVM (Model-View-ViewModel)

### Database Synchronization Strategy
The application uses a `valsync` field in each table to track changes:
- Each time a record is modified on the server, `valsync` is incremented
- During sync, the app requests records where `valsync > last_sync_timestamp`
- This ensures only changed records are downloaded

---

## 📦 Prerequisites

### For Backend (Server)
1. **XAMPP** (v3.2.4 or later)
   - Download: https://www.apachefriends.org/
   - Includes: Apache, MySQL, PHP

2. **PHP** 7.4 or later (included with XAMPP)

3. **MySQL** 10.4 or later (included with XAMPP)

### For Android Development
1. **Android Studio** Arctic Fox or later
   - Download: https://developer.android.com/studio

2. **JDK** 11 or later

3. **Android SDK** API 34 (or minimum API 24)

4. **Google Maps API Key** (for map functionality)
   - Get it from: https://console.cloud.google.com/

---

## 🚀 Installation & Setup

### Step 1: Setup MySQL Database

1. **Start XAMPP**
   - Launch XAMPP Control Panel
   - Start Apache and MySQL services

2. **Import Database**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create new database named `gem`
   - Import the SQL file: `ExpertMaintenance/database/gem_database.sql`
   - Or run the SQL script directly in phpMyAdmin SQL tab

3. **Verify Database**
   - Check that all tables are created:
     - `clients`, `contrats`, `employes`, `employes_interventions`
     - `images`, `interventions`, `priorites`, `sites`, `taches`
   - Verify demo data is inserted

### Step 2: Setup PHP Backend

1. **Copy Backend Files**
   - Locate XAMPP's htdocs folder:
     - Windows: `C:\xampp\htdocs`
     - macOS: `/Applications/XAMPP/htdocs`
     - Linux: `/opt/lampp/htdocs`
   
   - Create folder: `ExpertMaintenance`
   - Copy `backend/api.php` to this folder

2. **Configure Database Connection**
   - Open `backend/api.php`
   - Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'gem');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Default XAMPP password is empty
   ```

3. **Test API**
   - Open browser and navigate to:
     ```
     http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1
     ```
   - You should see JSON response with data

### Step 3: Setup Android Application

1. **Open Project in Android Studio**
   - Launch Android Studio
   - Select "Open an existing project"
   - Navigate to `ExpertMaintenance/android-app`
   - Click OK

2. **Sync Gradle**
   - Android Studio will automatically sync Gradle files
   - Wait for dependency download to complete
   - This may take several minutes on first run

3. **Configure Google Maps API Key**
   - Open `android-app/app/src/main/AndroidManifest.xml`
   - Replace `${GOOGLE_MAPS_API_KEY}` with your actual API key:
   ```xml
   <meta-data
       android:name="com.google.android.geo.API_KEY"
       android:value="YOUR_ACTUAL_API_KEY_HERE" />
   ```
   
   - Or create `local.properties` in project root:
   ```properties
   GOOGLE_MAPS_API_KEY=YOUR_ACTUAL_API_KEY_HERE
   ```

4. **Configure API URL**
   - Open `SyncManager.kt`
   - Update `API_BASE_URL` if your server is not on localhost:
   ```kotlin
   companion object {
       private const val API_BASE_URL = "http://10.0.2.2:8080/ExpertMaintenance/backend/api.php"
       // For emulator, 10.0.2.2 refers to localhost
       // For physical device, use your computer's IP address
   }
   ```

5. **Build the Project**
   - Select Build > Make Project
   - Fix any dependency issues if they occur

---

## ⚙️ Configuration

### Network Security

For cleartext HTTP traffic (development only):

The `AndroidManifest.xml` already includes:
```xml
android:usesCleartextTraffic="true"
```

⚠️ **Warning**: For production, use HTTPS and update API URLs accordingly.

### Emulator vs Physical Device

**Android Emulator:**
- Use `http://10.0.2.2:8080/...` or `http://localhost:8080/...`

**Physical Device:**
- Find your computer's IP address:
  - Windows: `ipconfig` in cmd
  - macOS/Linux: `ifconfig` or `ip addr`
- Update API URL to: `http://YOUR_IP:8080/ExpertMaintenance/backend/api.php`
- Ensure firewall allows incoming connections on port 8080

---

## ▶️ Running the Application

### Start the Backend

1. Launch XAMPP Control Panel
2. Start Apache
3. Start MySQL
4. Verify both services show green "Running" status

### Run the Android App

1. **Connect Device or Start Emulator**
   - USB debugging enabled for physical device
   - Or create virtual device in AVD Manager

2. **Run Application**
   - Click the green Run button (▶️) in Android Studio
   - Or: Run > Run 'app'
   - Select your device/emulator

3. **First Launch**
   - Login screen appears
   - Enter credentials (see Testing Credentials below)
   - Automatic synchronization occurs
   - Main interventions screen displays

---

## 🔐 Testing Credentials

The database includes demo employee accounts:

| Login  | Password | Name          | Email                          |
|--------|----------|---------------|--------------------------------|
| admin  | admin123 | Jean Dupont   | jean.dupont@expert-maintenance.fr |
| enzo   | enzo123  | Enzo Martin   | enzo.martin@expert-maintenance.fr |

### Demo Data

The database includes:
- **2 Employees** (see credentials above)
- **1 Client**: La Société Exemple
- **1 Site**: Rue de Paradis, 75010 Paris
- **2 Interventions** for June 21, 2018:
  - Intervention Mobile (07:00-10:00) - Completed ✓
  - Intervention Mobile 2 (15:00-18:00) - Pending ⬜
- **3 Priorities**: Normale, Urgente, Critique

---

## 🐛 Troubleshooting

### Database Connection Issues

**Problem**: API returns database connection error

**Solutions**:
1. Verify MySQL is running in XAMPP
2. Check database credentials in `api.php`
3. Ensure database `gem` exists
4. Check phpMyAdmin: http://localhost/phpmyadmin

### Synchronization Issues

**Problem**: Sync fails or returns empty data

**Solutions**:
1. Verify API is accessible:
   ```
   http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1
   ```
2. Check network permissions in AndroidManifest.xml
3. For emulator, use `10.0.2.2` instead of `localhost`
4. For physical device, use computer's IP address
5. Check firewall settings

### Build Errors

**Problem**: Gradle sync fails

**Solutions**:
1. File > Invalidate Caches / Restart
2. Delete `.gradle` folder and sync again
3. Check internet connection for dependency download
4. Update Android Studio to latest version

**Problem**: Kotlin version mismatch

**Solutions**:
1. Update `kotlin_version` in `build.gradle` (project level)
2. Sync Gradle files
3. Clean and rebuild project

### Camera/Storage Permission Issues

**Problem**: Cannot capture or save images

**Solutions**:
1. Grant permissions when prompted
2. Check app permissions in device settings
3. For Android 10+, scoped storage is handled automatically
4. Verify FileProvider configuration in AndroidManifest.xml

### Google Maps Issues

**Problem**: Map not displaying or shows blank screen

**Solutions**:
1. Verify API key is correct in AndroidManifest.xml
2. Enable Maps SDK for Android in Google Cloud Console
3. Add your app's SHA-1 fingerprint to API key restrictions
4. Check internet connectivity

### Login Issues

**Problem**: Authentication fails

**Solutions**:
1. Verify credentials match database
2. Check employee `actif` field is set to 1
3. Test API endpoint directly in browser
4. Check network connectivity

---

## 📁 Project Structure

```
ExpertMaintenance/
│
├── android-app/                 # Android application
│   ├── app/
│   │   ├── src/main/
│   │   │   ├── java/com/expert/maintenance/
│   │   │   │   ├── api/              # Retrofit API interfaces
│   │   │   │   ├── data/             # Data models & database
│   │   │   │   │   ├── local/        # Room database components
│   │   │   │   │   │   ├── dao/      # Data Access Objects
│   │   │   │   │   │   └── entity/   # Entity classes
│   │   │   │   │   ├── Models.kt     # Data models
│   │   │   │   │   └── SyncManager.kt # Synchronization logic
│   │   │   │   ├── ui/               # Activities & UI components
│   │   │   │   └── adapters/         # RecyclerView adapters
│   │   │   ├── res/
│   │   │   │   ├── layout/           # XML layouts
│   │   │   │   ├── values/           # Strings, colors, themes
│   │   │   │   ├── drawable/         # Images & icons
│   │   │   │   ├── menu/             # Navigation menus
│   │   │   │   └── xml/              # File paths, configs
│   │   │   └── AndroidManifest.xml
│   │   └── build.gradle
│   └── build.gradle
│
├── backend/                     # PHP backend API
│   └── api.php                  # Main API endpoint
│
├── database/                    # Database scripts
│   └── gem_database.sql         # MySQL schema & demo data
│
└── README.md                    # This file
```

---

## 📝 Additional Notes

### Security Considerations

⚠️ **Development Environment Only**: This project is designed for educational/development purposes. For production:

1. **Password Encryption**: Implement password hashing (bcrypt, Argon2)
2. **HTTPS**: Use SSL/TLS for all API communications
3. **Token-based Authentication**: Implement JWT or OAuth2
4. **API Rate Limiting**: Prevent abuse
5. **Input Validation**: Sanitize all user inputs
6. **SQL Injection Prevention**: Use prepared statements (already implemented)

### Performance Optimization

- Use pagination for large intervention lists
- Implement image compression before upload
- Cache frequently accessed data
- Use WorkManager for background sync

### Future Enhancements

- [ ] Push notifications for new interventions
- [ ] Offline mode improvements
- [ ] Signature capture for intervention validation
- [ ] Report generation (PDF)
- [ ] Multi-language support
- [ ] Dark mode theme
- [ ] Biometric authentication

---

## 📞 Support

For questions or issues:
1. Check the Troubleshooting section above
2. Review PHP error logs in XAMPP
3. Check Logcat for Android errors
4. Verify database structure matches schema

---

## 📄 License

This project is created for educational purposes as part of the "Expert Maintenance" case study.

---

## ✅ Quick Start Checklist

- [ ] XAMPP installed and running
- [ ] MySQL database `gem` created
- [ ] SQL script imported successfully
- [ ] Backend API tested in browser
- [ ] Android Studio project opened
- [ ] Gradle sync completed
- [ ] Google Maps API key configured
- [ ] API URL configured for device/emulator
- [ ] Project builds successfully
- [ ] App runs on device/emulator
- [ ] Login successful with demo credentials
- [ ] Synchronization working
- [ ] Interventions displaying correctly

**Congratulations! Your Expert Maintenance application is ready to use! 🎉**