# 🚀 Expert Maintenance - Démarrage Ultra-Rapide du Backend

## ⚡ 5 Minutes pour un Backend Opérationnel

---

## Étape 1: Apache (1 min)

**Terminal:**
```bash
sudo apachectl stop
```

**XAMPP:**
- Start Apache ✓ (doit être vert)
- Start MySQL ✓ (doit être vert)

---

## Étape 2: Base de Données (2 min)

**Navigateur:**
```
http://localhost/phpmyadmin
```

1. Créer base: `gem`
2. Importer: `/Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/database/gem_database_fixed.sql`
3. ✅ 9 tables doivent apparaître

---

## Étape 3: Backend (1 min)

**Terminal:**
```bash
sudo mkdir -p /Applications/XAMPP/htdocs/ExpertMaintenance/backend
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/*.php /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo cp /Users/mohamedyassinchannoufi/Documents/ExpertMaintenance/backend/test_quick.sh /Applications/XAMPP/htdocs/ExpertMaintenance/backend/
sudo chmod 644 /Applications/XAMPP/htdocs/ExpertMaintenance/backend/*.php
sudo chmod +x /Applications/XAMPP/htdocs/ExpertMaintenance/backend/test_quick.sh
```

---

## Étape 4: Test (1 min)

**Navigateur:**
```
http://localhost/ExpertMaintenance/backend/test_api.php
```

Cliquez "🚀 Lancer tous les tests" → Tous VERTS ✓

---

## Étape 5: Android (1 min)

**Fichier:** `android-app/app/src/main/java/com/expert/maintenance/data/SyncManager.kt`

**Ligne ~30:**
```kotlin
private const val API_BASE_URL = "http://10.0.2.2/ExpertMaintenance/backend/api.php"
```

**Build & Run:**
- Build → Make Project
- Run → Run 'app'
- Login: `admin` / `admin123`

---

## ✅ Checklist

- [ ] Apache Running (vert)
- [ ] MySQL Running (vert)
- [ ] 9 tables dans phpMyAdmin
- [ ] Backend copié dans htdocs
- [ ] Tests API tous verts
- [ ] Android connecté

---

## 🐛 Problème?

**Apache ne démarre pas:**
```bash
sudo apachectl stop
# Puis Start dans XAMPP
```

**Erreur database:**
- MySQL Running?
- Base `gem` créée?
- Test: `http://localhost/phpmyadmin`

**API ne répond pas:**
```bash
tail -f /Applications/XAMPP/htdocs/ExpertMaintenance/backend/api.log
```

---

## 📞 Références

| Document | Quand l'utiliser |
|----------|------------------|
| `00_LIRE_MOI_EN_PREMIER.md` | Vue d'ensemble complète |
| `ETAPE_PAR_ETAPE.md` | Guide détaillé |
| `COMMANDES_A_COPIER.md` | Toutes les commandes |
| `BACKEND_SETUP_GUIDE.md` | Dépannage complet |

---

**🎉 Backend opérationnel en 5 minutes!**

*Expert Maintenance - Quick Start v1.0*