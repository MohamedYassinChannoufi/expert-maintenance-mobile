# Expert Maintenance - Guide d'Implémentation Complète

## 📊 État Actuel du Projet

### ✅ Ce qui fonctionne déjà :
1. **LoginActivity** - Authentification avec l'API backend
2. **MainActivity** - Affichage principal avec navigation drawer
3. **Room Database** - Toutes les entités et DAOs sont créés
4. **SyncManager** - Synchronisation de base implémentée
5. **Backend API** - api.php fonctionnel avec tous les endpoints
6. **InterventionAdapter** - Adapter pour la liste des interventions

### ❌ Ce qui manque (selon le cahier des charges) :
1. Détails d'intervention complets avec toutes les informations
2. Capture d'images avec CameraX
3. Google Maps pour la localisation des sites
4. Historique des interventions par site
5. Modification/suppression d'interventions
6. Activité Clients
7. Activité Paramètres
8. Navigation drawer header avec infos employé

---

## 🚀 IMPLEMENTATION DU CODE MANQUANT

### 1. FICHIER DE STRINGS COMPLET

**Fichier:** `app/src/main/res/values/strings.xml`

```xml
<?xml version="1.0" encoding="utf-8"?>
<resources>
    <string name="app_name">Expert Maintenance</string>
    <string name="login_title">Authentification</string>
    <string name="login_hint">Identifiant</string>
    <string name="password_hint">Mot de passe</string>
    <string name="btn_login">Se connecter</string>
    <string name="btn_sync">Synchroniser</string>
    <string name="btn_back">Retour</string>
    <string name="btn_edit">Modifier</string>
    <string name="btn_delete">Supprimer</string>
    <string name="btn_open_map">Voir sur la carte</string>
    <string name="btn_take_photo">Prendre photo</string>
    <string name="btn_choose_photo">Choisir photo</string>
    <string name="btn_view_history">Voir historique</string>
    <string name="btn_yes">Oui</string>
    <string name="btn_no">Non</string>
    <string name="tab_details">Détails</string>
    <string name="tab_files">Fichiers</string>
    <string name="tab_signature">Signature</string>
    <string name="nav_interventions">Interventions</string>
    <string name="nav_interventions_to_assign">Interventions à assigner</string>
    <string name="nav_messages">Messages</string>
    <string name="nav_client">Client</string>
    <string name="nav_adresses">Adresses</string>
    <string name="nav_parametres">Paramètres</string>
    <string name="nav_a_propos">À propos</string>
    <string name="nav_deconnexion">Déconnexion</string>
    <string name="title_interventions">Interventions</string>
    <string name="title_activity_main">Expert Maintenance</string>
    <string name="no_interventions_today">Aucune intervention prévue ce jour</string>
    <string name="interventions_to_do">interventions à réaliser</string>
    <string name="interventions_all_completed">Toutes les interventions sont terminées</string>
    <string name="interventions_summary">Interventions réalisées</string>
    <string name="intervention_completed">Intervention marquée comme terminée</string>
    <string name="intervention_pending">Intervention marquée comme non terminée</string>
    <string name="confirm_uncheck_title">Décocher ?</string>
    <string name="confirm_uncheck_message">Êtes-vous sûr de vouloir décocher cette intervention ?</string>
    <string name="sync_start">Synchronisation en cours...</string>
    <string name="sync_success">Synchronisation réussie</string>
    <string name="sync_error">Erreur de synchronisation</string>
    <string name="error_login_required">Identifiant requis</string>
    <string name="error_password_required">Mot de passe requis</string>
    <string name="client_placeholder">Client</string>
    <string name="address_placeholder">Adresse</string>
    <string name="client_info_title">Informations Client</string>
    <string name="address_title">Adresse du Site</string>
    <string name="attached_files_title">Pièces Jointes</string>
    <string name="intervention_completed_label">Terminé</string>
    <string name="priority_normal">Normale</string>
    <string name="priority_urgent">Urgente</string>
    <string name="priority_critical">Critique</string>
    <string name="planned_section">Partie Planifiée</string>
    <string name="completed_section">Partie Effectuée</string>
    <string name="history_title">Historique des Interventions</string>
    <string name="cd_intervention_icon">Icône intervention</string>
    <string name="cd_sync_icon">Icône synchronisation</string>
</resources>
```

---

### 2. INTERVENTION DETAILS ACTIVITY

**Fichier:** `app/src/main/java/com/expert/maintenance/ui/details/InterventionDetailsActivity.kt`

```kotlin
package com.expert.maintenance.ui.details

import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.expert.maintenance.R
import com.expert.maintenance.data.AppDatabase
import com.expert.maintenance.data.local.entity.Intervention
import com.expert.maintenance.data.local.entity.Site
import com.expert.maintenance.data.local.entity.Client
import com.expert.maintenance.databinding.ActivityInterventionDetailsBinding
import com.google.android.material.chip.Chip
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Locale

class InterventionDetailsActivity : AppCompatActivity() {

    private lateinit var binding: ActivityInterventionDetailsBinding
    private lateinit var database: AppDatabase
    private var interventionId: Int = 0
    private var currentIntervention: Intervention? = null
    private var currentSite: Site? = null
    private var currentClient: Client? = null

    companion object {
        const val EXTRA_INTERVENTION_ID = "intervention_id"
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityInterventionDetailsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        database = AppDatabase.getDatabase(this)
        interventionId = intent.getIntExtra(EXTRA_INTERVENTION_ID, 0)

        if (interventionId == 0) {
            Toast.makeText(this, "Intervention ID invalide", Toast.LENGTH_SHORT).show()
            finish()
            return
        }

        setupToolbar()
        loadInterventionDetails()
        setupListeners()
    }

    private fun setupToolbar() {
        binding.btnBack.setOnClickListener { finish() }
        
        binding.btnEdit.setOnClickListener {
            Toast.makeText(this, "Modification non implémentée", Toast.LENGTH_SHORT).show()
        }

        binding.btnDelete.setOnClickListener {
            showDeleteConfirmation()
        }

        binding.btnSync.setOnClickListener {
            performSync()
        }
    }

    private fun setupListeners() {
        binding.switchCompleted.setOnCheckedChangeListener { _, isChecked ->
            if (isChecked) {
                updateInterventionCompletion(true)
            } else {
                showUncheckConfirmation()
            }
        }

        binding.btnOpenMap.setOnClickListener {
            openGoogleMaps()
        }

        binding.btnTakePhoto.setOnClickListener {
            Toast.makeText(this, "Capture photo non implémentée", Toast.LENGTH_SHORT).show()
        }

        binding.btnChoosePhoto.setOnClickListener {
            Toast.makeText(this, "Choix photo non implémenté", Toast.LENGTH_SHORT).show()
        }

        binding.btnHistory.setOnClickListener {
            openHistory()
        }
    }

    private fun loadInterventionDetails() {
        binding.progressBar.visibility = View.VISIBLE

        lifecycleScope.launch {
            try {
                currentIntervention = database.interventionDao().getInterventionById(interventionId)
                
                currentIntervention?.let { intervention ->
                    // Load site and client info
                    currentSite = database.siteDao().getSiteById(intervention.siteId)
                    currentSite?.let { site ->
                        currentClient = database.clientDao().getClientById(site.clientId)
                    }

                    displayInterventionDetails(intervention)
                }

                binding.progressBar.visibility = View.GONE
            } catch (e: Exception) {
                Toast.makeText(this@InterventionDetailsActivity, 
                    "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
                binding.progressBar.visibility = View.GONE
            }
        }
    }

    private fun displayInterventionDetails(intervention: Intervention) {
        // Toolbar title
        binding.tvToolbarTitle.text = "Intervention n° ${intervention.id}"

        // Header card
        binding.tvInterventionTitle.text = intervention.titre.uppercase(Locale.FRENCH)
        
        // Priority chip
        binding.chipPriority.text = when (intervention.prioriteId) {
            1 -> getString(R.string.priority_normal)
            2 -> getString(R.string.priority_urgent)
            3 -> getString(R.string.priority_critical)
            else -> getString(R.string.priority_normal)
        }

        // Date and time
        val dateFormat = SimpleDateFormat("dd MMMM yyyy", Locale.FRENCH)
        val plannedDate = parseDate(intervention.dateplanification)
        binding.tvPlannedDate.text = dateFormat.format(plannedDate)
        binding.tvTimeRange.text = "${intervention.heuredebutplan.substring(0, 5)} - ${intervention.heurefinplan.substring(0, 5)}"

        // Completion status
        binding.switchCompleted.isChecked = intervention.terminee

        // Client info
        currentClient?.let { client ->
            binding.tvClientName.text = "Société: ${client.nom}"
            binding.tvClientContact.text = "Contact: ${client.contact}"
            binding.tvClientPhone.text = "Tél: ${client.tel}"
            binding.tvClientEmail.text = "Email: ${client.email}"
        }

        // Site address
        currentSite?.let { site ->
            binding.tvSiteAddress.text = "Adresse: ${site.rue}, ${site.codepostal} ${site.ville}"
        }

        currentIntervention = intervention
    }

    private fun parseDate(dateStr: String): java.util.Date {
        return try {
            SimpleDateFormat("yyyy-MM-dd", Locale.FRENCH).parse(dateStr) ?: java.util.Date()
        } catch (e: Exception) {
            java.util.Date()
        }
    }

    private fun updateInterventionCompletion(isCompleted: Boolean) {
        lifecycleScope.launch {
            try {
                currentIntervention?.let { intervention ->
                    val updatedIntervention = intervention.copy(
                        terminee = isCompleted,
                        dateterminaison = if (isCompleted) {
                            SimpleDateFormat("yyyy-MM-dd", Locale.FRENCH).format(java.util.Date())
                        } else "0000-00-00",
                        valsync = intervention.valsync + 1
                    )

                    database.interventionDao().update(updatedIntervention)
                    currentIntervention = updatedIntervention

                    Toast.makeText(
                        this@InterventionDetailsActivity,
                        if (isCompleted) getString(R.string.intervention_completed) 
                        else getString(R.string.intervention_pending),
                        Toast.LENGTH_SHORT
                    ).show()
                }
            } catch (e: Exception) {
                Toast.makeText(this@InterventionDetailsActivity, 
                    "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun showUncheckConfirmation() {
        AlertDialog.Builder(this)
            .setTitle(R.string.confirm_uncheck_title)
            .setMessage(R.string.confirm_uncheck_message)
            .setPositiveButton(R.string.btn_yes) { _, _ ->
                updateInterventionCompletion(false)
            }
            .setNegativeButton(R.string.btn_no) { dialog, _ ->
                binding.switchCompleted.isChecked = true
                dialog.dismiss()
            }
            .show()
    }

    private fun showDeleteConfirmation() {
        AlertDialog.Builder(this)
            .setTitle("Supprimer l'intervention ?")
            .setMessage("Êtes-vous sûr de vouloir supprimer cette intervention ?")
            .setPositiveButton("Supprimer") { _, _ ->
                deleteIntervention()
            }
            .setNegativeButton("Annuler", null)
            .show()
    }

    private fun deleteIntervention() {
        lifecycleScope.launch {
            try {
                currentIntervention?.let { intervention ->
                    database.interventionDao().delete(intervention)
                    Toast.makeText(this@InterventionDetailsActivity, 
                        "Intervention supprimée", Toast.LENGTH_SHORT).show()
                    finish()
                }
            } catch (e: Exception) {
                Toast.makeText(this@InterventionDetailsActivity, 
                    "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun openGoogleMaps() {
        currentSite?.let { site ->
            try {
                val gmmIntentUri = Uri.parse("geo:${site.latitude},${site.longitude}?q=${site.latitude},${site.longitude}(${site.rue})")
                val mapIntent = Intent(Intent.ACTION_VIEW, gmmIntentUri)
                mapIntent.setPackage("com.google.android.apps.maps")
                startActivity(mapIntent)
            } catch (e: Exception) {
                Toast.makeText(this, "Google Maps non installé", Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun openHistory() {
        currentSite?.let { site ->
            Toast.makeText(this, "Historique non implémenté", Toast.LENGTH_SHORT).show()
            // TODO: Open HistoryActivity with site.id
        }
    }

    private fun performSync() {
        Toast.makeText(this, "Synchronisation...", Toast.LENGTH_SHORT).show()
        // TODO: Implement sync for this specific intervention
    }
}
```

---

### 3. NAVIGATION HEADER LAYOUT

**Fichier:** `app/src/main/res/layout/nav_header.xml`

```xml
<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="176dp"
    android:background="@drawable/nav_header_background"
    android:gravity="bottom"
    android:orientation="vertical"
    android:padding="16dp"
    android:theme="@style/ThemeOverlay.AppCompat.Dark">

    <ImageView
        android:id="@+id/iv_employee_avatar"
        android:layout_width="64dp"
        android:layout_height="64dp"
        android:contentDescription="@string/app_name"
        android:src="@drawable/ic_person"
        app:tint="@color/white" />

    <TextView
        android:id="@+id/tv_employee_name"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_marginTop="8dp"
        android:textAppearance="@style/TextAppearance.AppCompat.Subhead"
        android:textColor="@color/white"
        android:textStyle="bold"
        tools:text="Jean Dupont" />

    <TextView
        android:id="@+id/tv_employee_email"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:textAppearance="@style/TextAppearance.AppCompat.Body1"
        android:textColor="@color/white"
        tools:text="jean.dupont@expert-maintenance.fr" />

</LinearLayout>
```

**Fichier:** `app/src/main/res/drawable/nav_header_background.xml`

```xml
<?xml version="1.0" encoding="utf-8"?>
<shape xmlns:android="http://schemas.android.com/apk/res/android">
    <gradient
        android:angle="135"
        android:startColor="@color/colorPrimaryDark"
        android:endColor="@color/colorPrimary"
        android:type="linear" />
</shape>
```

---

### 4. MAINACTIVITY AMÉLIORÉE AVEC SYNC

**Fichier:** `app/src/main/java/com/expert/maintenance/ui/MainActivity.kt`

Ajoutez cette méthode pour charger les infos employé dans le header :

```kotlin
private fun loadEmployeeInfo() {
    lifecycleScope.launch {
        val employeeId = employeeId // from preferences
        val employee = AppDatabase.getDatabase(this@MainActivity)
            .employeeDao()
            .getEmployeeById(employeeId)
        
        employee?.let {
            val headerView = navigationView.getHeaderView(0)
            headerView?.findViewById<TextView>(R.id.tv_employee_name)?.text = 
                "${it.prenom} ${it.nom}"
            headerView?.findViewById<TextView>(R.id.tv_employee_email)?.text = it.email
        }
    }
}
```

---

### 5. HISTORY ACTIVITY (OPTIONNEL)

**Fichier:** `app/src/main/java/com/expert/maintenance/ui/history/HistoryActivity.kt`

```kotlin
package com.expert.maintenance.ui.history

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.expert.maintenance.R
import com.expert.maintenance.adapters.HistoryAdapter
import com.expert.maintenance.data.AppDatabase
import com.expert.maintenance.databinding.ActivityHistoryBinding

class HistoryActivity : AppCompatActivity() {

    private lateinit var binding: ActivityHistoryBinding
    private lateinit var database: AppDatabase
    private var siteId: Int = 0

    companion object {
        const val EXTRA_SITE_ID = "site_id"
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityHistoryBinding.inflate(layoutInflater)
        setContentView(binding.root)

        database = AppDatabase.getDatabase(this)
        siteId = intent.getIntExtra(EXTRA_SITE_ID, 0)

        setupToolbar()
        loadHistory()
    }

    private fun setupToolbar() {
        binding.toolbar.setNavigationOnClickListener { finish() }
    }

    private fun loadHistory() {
        lifecycleScope.launch {
            val history = database.interventionDao().getInterventionsBySite(siteId)
            // Display history in RecyclerView
        }
    }
}
```

---

### 6. METTRE À JOUR ANDROIDMANIFEST.XML

Ajoutez les nouvelles activities :

```xml
<!-- Intervention Details Activity -->
<activity
    android:name=".ui.details.InterventionDetailsActivity"
    android:exported="false"
    android:parentActivityName=".ui.MainActivity"
    android:theme="@style/Theme.ExpertMaintenance.NoActionBar" />

<!-- History Activity -->
<activity
    android:name=".ui.history.HistoryActivity"
    android:exported="false"
    android:parentActivityName=".ui.details.InterventionDetailsActivity"
    android:theme="@style/Theme.ExpertMaintenance.NoActionBar" />
```

---

### 7. CLICK SUR INTERVENTION DANS MAINACTIVITY

Dans `MainActivity.kt`, modifiez `openInterventionDetails` :

```kotlin
private fun openInterventionDetails(intervention: Intervention) {
    val intent = Intent(this, com.expert.maintenance.ui.details.InterventionDetailsActivity::class.java).apply {
        putExtra("intervention_id", intervention.id)
    }
    startActivity(intent)
}
```

---

### 8. SYNCMANAGER AMÉLIORÉ

Le SyncManager actuel est fonctionnel. Assurez-vous que l'URL est correcte :

```kotlin
companion object {
    private const val TAG = "SyncManager"
    // Pour appareil physique sur même WiFi
    private const val API_BASE_URL = "http://192.168.5.225:80/ExpertMaintenance/backend/api.php"
    // Pour émulateur: "http://10.0.2.2:80/ExpertMaintenance/backend/api.php"
}
```

---

## 📋 CHECKLIST FINALE

### À faire :
- [ ] Copier tous les fichiers de code ci-dessus
- [ ] Créer les layouts manquants
- [ ] Ajouter les icônes dans `res/drawable/`
- [ ] Tester la synchronisation
- [ ] Tester l'ouverture des détails d'intervention
- [ ] Tester Google Maps
- [ ] Implémenter CameraX pour la capture d'images
- [ ] Implémenter l'historique complet

### Dépendances à ajouter dans `build.gradle` :

```gradle
// Google Maps (optionnel)
implementation 'com.google.android.gms:play-services-maps:18.2.0'

// CameraX (pour capture d'images)
implementation 'androidx.camera:camera-core:1.3.1'
implementation 'androidx.camera:camera-camera2:1.3.1'
implementation 'androidx.camera:camera-lifecycle:1.3.1'
implementation 'androidx.camera:camera-view:1.3.1'
```

---

## 🔧 COMMANDES DE BUILD

```bash
# Nettoyage et rebuild
./gradlew clean
./gradlew assembleDebug

# Install sur device
adb install -r app/build/outputs/apk/debug/app-debug.apk
```

---

## ✅ RÉSULTAT FINAL

Après avoir implémenté tout ce code, votre application aura :

1. ✅ Authentification fonctionnelle
2. ✅ Liste des interventions par jour
3. ✅ Détails complets d'intervention
4. ✅ Marqueur de completion avec confirmation
5. ✅ Navigation vers Google Maps
6. ✅ Synchronisation avec la base distante
7. ✅ Navigation drawer avec infos employé
8. ✅ Suppression d'intervention
9. ✅ Base SQLite locale synchronisée

---

**Bon courage pour l'implémentation ! 🚀**