# Expert Maintenance - Getting Started Guide

## 🎯 Welcome!

This guide will help you set up and run the **Expert Maintenance** Android application in under 20 minutes. Follow these steps carefully, and you'll have a working application ready to test!

---

## 📋 What You'll Build

A complete mobile maintenance management system with:
- ✅ Employee authentication
- ✅ Intervention tracking and management
- ✅ Automatic data synchronization (MySQL ↔ SQLite)
- ✅ Photo capture and upload
- ✅ Google Maps integration
- ✅ Offline mode support

---

## ⚡ Quick Setup (20 Minutes)

### Step 1: Install Prerequisites (5 minutes)

#### A. Download XAMPP
1. Visit: https://www.apachefriends.org/download.html
2. Download version 3.2.4 or later for your OS
3. Install XAMPP (default location is fine)
4. Launch XAMPP Control Panel

#### B. Verify Android Studio
1. Open Android Studio
2. If not installed: https://developer.android.com/studio
3. Ensure SDK 24+ is installed

---

### Step 2: Setup MySQL Database (5 minutes)

#### A. Start XAMPP Services
1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache**
3. Click **Start** next to **MySQL**
4. Both should show green "Running" status

#### B. Create Database
1. Open browser: `http://localhost/phpmyadmin`
2. Click **"New"** in left sidebar
3. Database name: `gem` (lowercase)
4. Collation: `utf8mb4_general_ci`
5. Click **"Create"**

#### C. Import Data
1. Click on `gem` database (left sidebar)
2. Click **"Import"** tab
3. Click **"Choose File"**
4. Navigate to: `ExpertMaintenance/database/gem_database.sql`
5. Click **"Go"**
6. Wait for "Import has been successfully finished"

#### D. Verify Tables
You should see 9 tables:
- clients
- contrats
- employes
- employes_interventions
- images
- interventions
- priorites
- sites
- taches

✅ **Quick Test**: Click on `employes` table → Browse → Should show 2 employees (admin, enzo)

---

### Step 3: Setup PHP Backend (3 minutes)

#### A. Locate htdocs Folder
- **Windows**: `C:\xampp\htdocs`
- **macOS**: `/Applications/XAMPP/htdocs`
- **Linux**: `/opt/lampp/htdocs`

#### B. Create Project Folders
```
htdocs/
└── ExpertMaintenance/
    └── backend/
        └── api.php
```

#### C. Copy API File
1. Copy `api.php` from `ExpertMaintenance/backend/`
2. Paste into `htdocs/ExpertMaintenance/backend/`

#### D. Test API
Open browser and go to:
```
http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1
```

✅ **Expected Result**: JSON response with employees, clients, sites, interventions arrays

**Troubleshooting**:
- ❌ "Database connection failed" → Check MySQL is running
- ❌ "404 Not Found" → Verify file path is correct
- ❌ Empty JSON → Check database has data

---

### Step 4: Setup Android Application (5 minutes)

#### A. Open Project
1. Launch **Android Studio**
2. Click **"Open"** or **"Open an existing project"**
3. Navigate to: `ExpertMaintenance/android-app`
4. Click **OK**

#### B. Wait for Gradle Sync
- Android Studio will download dependencies (5-10 minutes first time)
- Watch progress bar at bottom-right
- Wait for **"BUILD SUCCESSFUL"** message

#### C. Configure API URL (if using physical device)

**For Emulator**: Skip this step (default works)

**For Physical Device**:
1. Find your computer's IP:
   - Windows: Open cmd → type `ipconfig` → note IPv4 Address
   - macOS/Linux: Terminal → `ifconfig` → note inet address

2. Open: `android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt`

3. Find line ~20:
   ```kotlin
   private const val API_BASE_URL = "http://10.0.2.2:8080/ExpertMaintenance/backend/api.php"
   ```

4. Replace with your IP:
   ```kotlin
   private const val API_BASE_URL = "http://192.168.1.XXX:8080/ExpertMaintenance/backend/api.php"
   ```

#### D. Google Maps API Key (Optional)

Skip for basic testing. Add later for map features:

1. Get key: https://console.cloud.google.com/
2. Open: `android-app/app/src/main/AndroidManifest.xml`
3. Replace `${GOOGLE_MAPS_API_KEY}` with your actual key

---

### Step 5: Build and Run (2 minutes)

#### A. Build Project
1. Click: **Build → Make Project**
2. Or press: `Ctrl+F9` (Windows) / `Cmd+F9` (macOS)
3. Wait for "BUILD SUCCESSFUL"

#### B. Start Emulator or Connect Device

**Option 1: Android Emulator**
1. Click device dropdown (top toolbar)
2. Select existing virtual device OR
3. Click "Create New Device" → Follow wizard

**Option 2: Physical Device**
1. Enable USB Debugging on device:
   - Settings → About Phone → Tap "Build Number" 7 times
   - Go back → Developer Options → Enable "USB Debugging"
2. Connect via USB
3. Accept RSA fingerprint on device

#### C. Run Application
1. Click green **▶️ (Run)** button
2. Or: **Run → Run 'app'**
3. Select your device
4. Wait for installation

✅ **Expected**: App launches to login screen with blue background

---

## 🔐 Test the Application

### Login

Use demo credentials:

**Option 1:**
```
Login: admin
Password: admin123
```

**Option 2:**
```
Login: enzo
Password: enzo123
```

✅ **Expected**: After login, you see "Interventions" screen

### Explore Features

#### 1. View Interventions
- You should see **June 21, 2018**
- **2 interventions** listed:
  - ✅ Intervention Mobile (07:00-10:00) - Checked
  - ⬜ Intervention Mobile 2 (15:00-18:00) - Unchecked

#### 2. Navigate Dates
- Click **← arrow**: Previous day
- Click **→ arrow**: Next day
- **Long-press** date: Go to today

#### 3. Toggle Completion
- Click checkbox on Intervention Mobile 2
- Try to uncheck it → Confirmation dialog appears

#### 4. Open Details
- Tap on any intervention card
- View detailed information

#### 5. Manual Sync
- Click floating action button (bottom-right, sync icon)
- Watch for "Synchronization réussie" toast

#### 6. Navigation Drawer
- Click **☰ hamburger menu** (top-left)
- Explore menu options:
  - Interventions
  - Interventions à assigner
  - Messages
  - Client
  - Adresses
  - Paramètres
  - À propos
  - Déconnexion

---

## 🐛 Common Issues & Solutions

### Issue: "Database connection failed"

**Symptoms**: API returns error or blank page

**Solutions**:
1. ✅ Verify MySQL is running (green status in XAMPP)
2. ✅ Check database name is exactly `gem` (lowercase)
3. ✅ Open `backend/api.php` and verify credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'gem');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

---

### Issue: "App crashes on login"

**Symptoms**: App closes or shows error after clicking login

**Solutions**:
1. ✅ Check API URL in `SyncManager.kt` line 20
2. ✅ Verify backend is accessible in browser
3. ✅ Open Android Studio → Logcat → Check error details
4. ✅ Ensure network permissions in AndroidManifest.xml

---

### Issue: "Gradle sync failed"

**Symptoms**: Red error messages in Build output

**Solutions**:
1. ✅ **File → Invalidate Caches / Restart**
2. ✅ Delete `.gradle` folder in project root
3. ✅ Check internet connection (downloading dependencies)
4. ✅ Update Android Studio to latest version

---

### Issue: "No interventions showing"

**Symptoms**: Empty list or "Aucune intervention" message

**Solutions**:
1. ✅ Navigate to **June 21, 2018** using date arrows
2. ✅ Click sync button (blue FAB)
3. ✅ Check employee is assigned in database:
   ```sql
   SELECT * FROM employes_interventions WHERE employe_id = 1;
   ```

---

### Issue: "Network error" on physical device

**Symptoms**: Login works but sync fails

**Solutions**:
1. ✅ Use computer's IP address (not localhost or 10.0.2.2)
2. ✅ Disable Windows firewall temporarily
3. ✅ Ensure both device and computer on same WiFi network
4. ✅ Test API from device browser: `http://YOUR_IP:8080/...`

---

### Issue: "Google Maps not working"

**Symptoms**: Blank map or error message

**Solutions**:
1. ✅ Verify API key in AndroidManifest.xml
2. ✅ Enable "Maps SDK for Android" in Google Cloud Console
3. ✅ Add SHA-1 fingerprint to API key restrictions
4. ✅ Check internet connectivity

---

## 📊 Demo Data Reference

### Employees
| Login | Password | Name | Email |
|-------|----------|------|-------|
| admin | admin123 | Jean Dupont | jean.dupont@expert-maintenance.fr |
| enzo | enzo123 | Enzo Martin | enzo.martin@expert-maintenance.fr |

### Interventions (June 21, 2018)
| ID | Title | Time | Status |
|----|-------|------|--------|
| 1 | Intervention Mobile | 07:00-10:00 | Completed ✓ |
| 2 | Intervention Mobile 2 | 15:00-18:00 | Pending ⬜ |

### Client
- **Name**: La Société Exemple
- **Address**: Rue de Paradis, 75010 Paris
- **Contact**: Jean Paul
- **Phone**: 0612346587

### Priorities
1. Normale (Green)
2. Urgente (Orange)
3. Critique (Red)

---

## 🎓 Next Steps

Once everything is working:

### 1. Read Full Documentation
- `README.md` - Complete technical guide
- `PROJECT_SUMMARY.md` - Architecture overview
- `FILES_LIST.md` - All project files

### 2. Customize the App
- Modify colors: `res/values/colors.xml`
- Change strings: `res/values/strings.xml`
- Update layouts: `res/layout/`

### 3. Add More Data
- Open phpMyAdmin
- Insert new records in tables
- Test with real-world scenarios

### 4. Extend Functionality
- Implement missing activities (details, edit, map, etc.)
- Add push notifications
- Implement signature capture
- Generate PDF reports

---

## ✅ Success Checklist

Verify each item:

### Backend
- [ ] XAMPP Apache running (green)
- [ ] XAMPP MySQL running (green)
- [ ] Can access phpMyAdmin: http://localhost/phpmyadmin
- [ ] Database `gem` created with 9 tables
- [ ] API test returns JSON in browser

### Android
- [ ] Project opens in Android Studio
- [ ] Gradle sync completes without errors
- [ ] Build succeeds (BUILD SUCCESSFUL)
- [ ] No red error messages

### Runtime
- [ ] App launches on device/emulator
- [ ] Login screen displays
- [ ] Login successful with demo credentials
- [ ] Interventions list appears (June 21, 2018)
- [ ] 2 interventions visible
- [ ] Date navigation works (← → arrows)
- [ ] Checkbox toggle works
- [ ] Sync button functions
- [ ] Navigation drawer opens

### All Checked? 🎉

**Congratulations! Your Expert Maintenance application is ready!**

---

## 📞 Need Help?

### Debugging Tools

**Android Logs**:
```
Android Studio → Logcat tab
Filter by: "ExpertMaintenance" or "SyncManager"
```

**PHP Errors**:
```
XAMPP → Apache → Logs → error.log
Location: C:\xampp\apache\logs\error.log (Windows)
```

**Database Queries**:
```
phpMyAdmin → SQL tab
Run: SELECT * FROM employes;
```

### Documentation Resources

- `README.md` - Detailed setup and troubleshooting
- `QUICK_START.md` - 15-minute setup guide
- `PROJECT_SUMMARY.md` - Architecture and features
- `FILES_LIST.md` - Complete file inventory

### External Resources

- [Android Developer Guide](https://developer.android.com/)
- [Retrofit Documentation](https://square.github.io/retrofit/)
- [Room Database Guide](https://developer.android.com/training/data-storage/room)
- [Material Design](https://material.io/develop/android)

---

## 🏁 Summary

You've successfully set up:

✅ **MySQL Database** with demo data
✅ **PHP Backend API** for synchronization
✅ **Android Application** with full features
✅ **Two-way Sync** between server and device
✅ **Offline Support** with local caching

**Time Elapsed**: ~20 minutes

**What's Next**: Start customizing and extending the application for your specific needs!

---

**Happy Coding! 👨‍💻👩‍💻**

*Expert Maintenance - Mobile Application for Field Technicians*
*Version 1.0 - January 2024*