# Expert Maintenance - Quick Start Guide

🚀 Get your Expert Maintenance application running in 15 minutes!

---

## ⚡ Prerequisites Check

Before starting, ensure you have:
- [ ] **XAMPP** installed (v3.2.4+)
- [ ] **Android Studio** installed (Arctic Fox or later)
- [ ] **JDK 11+** installed
- [ ] Internet connection (for Gradle dependencies)

---

## 📋 Step-by-Step Setup

### Step 1: Start XAMPP Services (2 minutes)

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache**
3. Click **Start** next to **MySQL**
4. Wait for both to show **green "Running"** status

✅ **Verify**: Open browser → http://localhost/phpmyadmin (should load)

---

### Step 2: Setup MySQL Database (3 minutes)

1. **Open phpMyAdmin**
   - Browser URL: `http://localhost/phpmyadmin`

2. **Create Database**
   - Click "New" in left sidebar
   - Database name: `gem`
   - Collation: `utf8mb4_general_ci`
   - Click "Create"

3. **Import SQL Script**
   - Click on `gem` database in left sidebar
   - Click "Import" tab at top
   - Click "Choose File"
   - Navigate to: `ExpertMaintenance/database/gem_database.sql`
   - Click "Go" at bottom
   - Wait for success message

✅ **Verify**: You should see 9 tables: clients, contrats, employes, employes_interventions, images, interventions, priorites, sites, taches

---

### Step 3: Setup PHP Backend (2 minutes)

1. **Locate XAMPP htdocs Folder**
   - **Windows**: `C:\xampp\htdocs`
   - **macOS**: `/Applications/XAMPP/htdocs`
   - **Linux**: `/opt/lampp/htdocs`

2. **Create Project Folder**
   - Inside `htdocs`, create folder: `ExpertMaintenance`
   - Inside that, create folder: `backend`

3. **Copy API File**
   - Copy `api.php` from `ExpertMaintenance/backend/` to `htdocs/ExpertMaintenance/backend/`

4. **Test API**
   - Open browser: `http://localhost/ExpertMaintenance/backend/api.php?action=full_sync&last_sync=0&employee_id=1`
   - You should see JSON data

✅ **Verify**: JSON response with employees, clients, sites, interventions arrays

---

### Step 4: Open Android Project (3 minutes)

1. **Launch Android Studio**

2. **Open Project**
   - Click "Open" or "Open an existing project"
   - Navigate to: `ExpertMaintenance/android-app`
   - Click "OK"

3. **Wait for Gradle Sync**
   - Android Studio will sync automatically
   - Watch bottom-right progress bar
   - This downloads dependencies (may take 5-10 minutes first time)
   - Wait for "BUILD SUCCESSFUL" message

✅ **Verify**: No red error messages in Build output

---

### Step 5: Configure API URL (1 minute)

**For Android Emulator:**
- No changes needed! Default is already `http://10.0.2.2:8080/`

**For Physical Device:**
1. Find your computer's IP address:
   - **Windows**: Open cmd → type `ipconfig` → look for IPv4 Address
   - **macOS/Linux**: Open terminal → type `ifconfig` → look for inet address

2. Update API URL in code:
   - Open: `android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt`
   - Line ~20: Change to your IP:
   ```kotlin
   private const val API_BASE_URL = "http://YOUR_IP_ADDRESS:8080/ExpertMaintenance/backend/api.php"
   ```

✅ **Verify**: API URL matches your setup

---

### Step 6: Configure Google Maps API Key (Optional - 2 minutes)

⚠️ **Skip this step if you just want to test basic functionality**

1. **Get API Key** (if you don't have one):
   - Go to: https://console.cloud.google.com/
   - Create new project or select existing
   - Enable "Maps SDK for Android"
   - Create API key

2. **Add to AndroidManifest.xml**:
   - Open: `android-app/app/src/main/AndroidManifest.xml`
   - Find line with `GOOGLE_MAPS_API_KEY`
   - Replace `${GOOGLE_MAPS_API_KEY}` with your actual key

✅ **Verify**: API key is a 39-character string starting with `AIza`

---

### Step 7: Build & Run (3 minutes)

1. **Build Project**
   - Click: Build → Make Project
   - Or press: `Ctrl+F9` (Windows) / `Cmd+F9` (macOS)
   - Wait for "BUILD SUCCESSFUL"

2. **Start Emulator or Connect Device**
   - **Emulator**: Click device dropdown → Select/Create virtual device
   - **Physical Device**: Connect via USB with USB debugging enabled

3. **Run App**
   - Click green ▶️ (Run) button
   - Or: Run → Run 'app'
   - Select your device

✅ **Verify**: App launches to login screen

---

## 🔐 Test Login

Use these demo credentials:

**Option 1:**
- **Login**: `admin`
- **Password**: `admin123`

**Option 2:**
- **Login**: `enzo`
- **Password**: `enzo123`

✅ **Verify**: After login, you see interventions list for June 21, 2018

---

## 🎯 Test Core Features

### 1. View Interventions
- You should see 2 interventions for June 21, 2018
- One checked (completed), one unchecked

### 2. Navigate Dates
- Click ← arrow for previous day
- Click → arrow for next day
- Long-press date for "today"

### 3. Toggle Completion
- Click checkbox on unchecked intervention
- Confirm dialog appears when unchecking

### 4. Open Intervention Details
- Tap on any intervention card
- View planned vs. actual details

### 5. Manual Sync
- Click floating action button (bottom-right)
- Watch sync progress toast message

### 6. Navigation Drawer
- Click hamburger menu (top-left)
- Explore menu options

---

## 🐛 Common Issues & Quick Fixes

### ❌ "Database connection failed"
**Fix**: 
1. Check MySQL is running in XAMPP (green status)
2. Verify database name is exactly `gem` (lowercase)
3. Check api.php has correct credentials

### ❌ "App crashes on login"
**Fix**:
1. Check API URL in SyncManager.kt
2. Verify backend is accessible in browser
3. Check Logcat for error details

### ❌ "Gradle sync failed"
**Fix**:
1. File → Invalidate Caches / Restart
2. Delete `.gradle` folder in project root
3. Check internet connection

### ❌ "No interventions showing"
**Fix**:
1. Navigate to June 21, 2018 using date arrows
2. Click sync button (FAB)
3. Check if employee is assigned to interventions in database

### ❌ "Network error" on physical device
**Fix**:
1. Use computer's IP address (not localhost)
2. Disable Windows firewall temporarily
3. Ensure both device and computer on same WiFi

---

## 📱 Default Demo Data

After successful login, you should see:

**Date**: June 21, 2018

**Interventions**:
1. ✅ **Intervention Mobile**
   - Client: La Société Exemple
   - Time: 07:00 - 10:00
   - Status: Completed

2. ⬜ **Intervention Mobile 2**
   - Client: La Société Exemple
   - Time: 15:00 - 18:00
   - Status: Pending

**Location**: Rue de Paradis, 75010 Paris

---

## 🎓 Next Steps

Once basic setup is working:

1. **Read Full Documentation**: See `README.md` for detailed info
2. **Add More Data**: Insert additional records in phpMyAdmin
3. **Customize UI**: Modify layouts in `res/layout/`
4. **Extend Features**: Add new functionality as needed
5. **Test on Device**: Install APK on physical Android device

---

## 📞 Still Stuck?

Check these resources:

1. **Full README**: `ExpertMaintenance/README.md`
2. **PHP Error Logs**: XAMPP → Apache → Logs → error.log
3. **Android Logs**: Android Studio → Logcat tab
4. **Database Structure**: phpMyAdmin → gem database → Structure tab

---

## ✅ Success Checklist

- [ ] XAMPP Apache running (green)
- [ ] XAMPP MySQL running (green)
- [ ] Database `gem` created with 9 tables
- [ ] API test in browser returns JSON
- [ ] Android project opens in Android Studio
- [ ] Gradle sync completes successfully
- [ ] Build succeeds without errors
- [ ] App launches on device/emulator
- [ ] Login works with demo credentials
- [ ] Interventions display correctly
- [ ] Date navigation works
- [ ] Sync button functions

**All checked? 🎉 You're ready to go!**

---

**Estimated Total Time**: 15-20 minutes (first time)

**Happy Coding!** 👨‍💻👩‍💻