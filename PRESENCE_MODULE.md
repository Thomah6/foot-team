# Module Présences - Documentation Complète

## 📋 Vue d'ensemble

Le module **Présences** permet :
- ✅ **Déclaration de présence** : Les membres déclarent leur présence
- ✅ **Validation admin** : L'admin valide les présences déclarées
- ✅ **Historique** : Visualisation de l'historique pour tous les utilisateurs
- ✅ **Calendrier** : Navigation mensuels avec dates de présences
- ✅ **Recherche** : Filtrage des membres par nom
- ✅ **Rapport mensuel** : Statistiques complètes du mois (admin uniquement)

---

## 🗂️ Structure des fichiers

```
app/
  ├── Http/Controllers/
  │   └── PresenceController.php        # Controller principal
  │
  └── Models/
      └── Presence.php                  # Modèle (déjà créé)

routes/
  └── web.php                           # Routes (mise à jour)

resources/views/Presence/
  ├── Index.vue                         # Vue principale - Calendrier
  ├── History.vue                       # Vue d'historique
  ├── MonthlyReport.vue                 # Rapport mensuel (admin)
  └── Components/
      ├── PresenceStatusCell.vue        # Cellule de statut
      └── DeclarePresenceModal.vue      # Modal déclaration

resources/js/Services/
  └── calendarService.js                # Utilitaires de calendrier
```

---

## 🚀 Guide d'utilisation

### 1. **Page de Calendrier** (`/presence`)

#### Accessible à :
- ✅ Tous les utilisateurs authentifiés

#### Fonctionnalités :
- **Tableau mensuel** : Affiche tous les membres et leurs présences du mois
- **Navigation** : Flèches pour aller au mois précédent/suivant
- **Bouton Aujourd'hui** : Calendrier → retour à aujourd'hui
- **Recherche** : Filtrage en temps réel par nom de membre
- **Déclaration** : Bouton pour déclarer sa présence (non-admin uniquement)

#### Pour les admins :
- Clic sur une cellule → Menu dropdown pour modifier le statut :
  - ✅ Présent validé (vert)
  - ⏳ Présent en attente (jaune)
  - ❌ Absent (rouge)

#### Pour les utilisateurs :
- Lecture seule du statut
- Bouton "Déclarer ma présence" pour ajouter une présence

---

### 2. **Modal de Déclaration**

#### Accès :
- Clic sur le bouton "Déclarer ma présence"

#### Fonctionnement :
- Sélectionner une date
- Confirmer → Présence en attente de validation admin

#### Validations :
- ✅ Impossible de déclarer deux fois pour le même jour
- ✅ Gestion automatique de la date du jour

---

### 3. **Page d'Historique** (`/presence/history`)

#### Accessible à :
- ✅ Tous les utilisateurs

#### Pour les utilisateurs :
- Voir son propre historique de présences
- Filtrer par mois

#### Pour les admins :
- Voir l'historique de TOUS les membres
- Sélectionner un membre spécifique
- Statistiques globales du mois :
  - Total absences
  - Total présences
  - En attente de validation
  - Validées

---

### 4. **Rapport Mensuel** (`/presence/monthly-report`)

#### Accessible à :
- 🔒 Admins uniquement

#### Contenu :
- **Table complète** : Tous les membres avec leurs stats
- **Colonnes** :
  - Membre
  - Présent (nombre)
  - Absent (nombre)
  - Validé (nombre)
  - Taux de présence (%)

- **Statistiques globales** : Total présent/absent/en attente/validé
- **Visualisation** : Barre de progression du taux de présence
- **Export CSV** : Télécharger le rapport en CSV

---

## 🔌 Routes disponibles

### Routes publiques (utilisateur)
```
GET     /presence                       # Vue principale - Calendrier
GET     /presence/history               # Historique des présences
POST    /presence                       # Déclarer une présence
GET     /presence/day                   # Récupérer présences du jour (API)
```

### Routes admin
```
PATCH   /presence/{presence}/validate   # Valider une présence
PATCH   /presence/{presence}            # Modifier une présence
GET     /presence/monthly-report        # Rapport mensuel
```

---

## 📊 Modèle de données

### Table `presences`
```php
$table->id();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->date('date');
$table->boolean('present');                    // true = présent, false = absent
$table->boolean('validated_by_admin')->default(false);
$table->timestamps();
```

### Relations
```php
Presence -> belongsTo -> User
User -> hasMany -> Presence
```

---

## 🎨 Composants Vue

### `Index.vue` - Calendrier principal
**Props** :
- `presenceData`: Array - Données de présence formatées
- `dayPresences`: Array - Présences du jour sélectionné
- `selectedDate`: String - Date actuellement sélectionnée
- `month`: String - Mois actuel
- `searchQuery`: String - Requête de recherche
- `isAdmin`: Boolean - Si l'utilisateur est admin
- `monthDates`: Array - Dates du mois avec présences

**Événements** :
- Recherche en temps réel
- Navigation entre mois
- Déclaration de présence

---

### `History.vue` - Historique
**Props** :
- `presenceHistory`: Object|Array - Historique formaté
- `month`: String - Mois actuel
- `isAdmin`: Boolean
- `users`: Array - Liste des utilisateurs

**Statistiques** :
- Total présences/absences/validation
- Affichage groupé par membre (admin)

---

### `MonthlyReport.vue` - Rapport mensuel
**Props** :
- `report`: Array - Données du rapport
- `month`: String - Mois du rapport

**Statistiques** :
- 4 cartes KPI
- Table avec barres de progression
- Export CSV

---

### `PresenceStatusCell.vue` - Cellule de statut
**Props** :
- `presence`: Object - Données de présence
- `isAdmin`: Boolean

**Affichage** :
- Admin : Clic pour menu dropdown
- User : Lecture seule

---

### `DeclarePresenceModal.vue` - Modal de déclaration
**Événements** :
- `@close` : Fermer le modal
- `@submit` : Soumettre la déclaration

**Validation** :
- Date requise
- Vérification duplicate côté backend

---

## 🛠️ Service `calendarService.js`

Fonctions utilitaires disponibles :

```javascript
// Obtenir les dates du mois
getMonthDates(date)

// Formater une date
formatDateFR(dateStr)
formatMonthYear(date)
getShortDateFormat(dateStr)
formatDateRange(startDate, endDate)

// Manipulation de dates
addDays(date, days)
getFirstDayOfMonth(date)
getLastDayOfMonth(date)
isSameDay(date1, date2)

// Calculs
calculatePresencePercentage(present, total)
getPresenceStatus(present, validated)
groupDatesByWeek(dates)
getWorkingDaysOfMonth(date)

// Comparaisons
compareMonths(date1, date2)
```

---

## 🔐 Autorisations

### Middleware `role:admin`
- Validation des présences
- Modification des présences
- Rapport mensuel
- Gestion complète (admin)

### Utilisateurs normaux
- Déclaration de présence
- Consultation de son historique
- Consultation du calendrier (lecture seule)

---

## 🎯 Workflow typique

### Utilisateur simple :
1. Accès à `/presence`
2. Voir le calendrier du mois
3. Clic sur "Déclarer ma présence"
4. Sélectionner une date
5. Confirmation → en attente de validation
6. Admin valide → présence confirmée
7. Consultation de l'historique sur `/presence/history`

### Admin :
1. Accès à `/presence`
2. Voir le calendrier avec tous les membres
3. Clic sur une cellule de présence
4. Menu pour modifier le statut
5. Accès au rapport mensuel sur `/presence/monthly-report`
6. Télécharger les statistiques en CSV

---

## 🎨 Design responsive

Le design est **mobile-first** avec Tailwind CSS :
- ✅ Tableau responsive avec colonnes masquées selon la taille
- ✅ Sticky colonne de membre (gauche)
- ✅ Grille d'icônes Material Symbols
- ✅ Dark mode complet
- ✅ Animations et transitions fluides

---

## 📈 Statistiques disponibles

### Pour chaque utilisateur :
- Nombre de présences
- Nombre d'absences
- Nombre de validations
- Taux de présence (%)

### Globales (admin) :
- Total présences du mois
- Total absences du mois
- En attente de validation
- Taux moyen de présence
- Évolution mensuelle possible

---

## 🐛 Gestion des erreurs

### Backend
- ✅ Validation des données
- ✅ Vérification des duplicates
- ✅ Gestion des autorisations
- ✅ Messages d'erreur clairs

### Frontend
- ✅ Validations côté client
- ✅ Gestion des modals
- ✅ Feedback utilisateur
- ✅ Rechargement des données

---

## 📝 Notes importantes

1. **Présences actuelles** : Les présences déclarées sont stockées avec la date du jour
2. **Validation** : Un admin doit valider chaque présence pour qu'elle soit "officielle"
3. **Historique** : Visible pour tous, mais filtré par utilisateur si non-admin
4. **Rapport** : Export en CSV avec date d'export
5. **Calendrier** : Navigation fluide entre mois avec chargement automatique des données

---

## 🚀 Développements futurs possibles

- 📅 Intégration iCal pour sync calendrier
- 📧 Notifications email pour validation
- 📊 Graphiques avancés avec Chart.js
- 🔔 Notifications en temps réel
- 📱 Application mobile
- ⚙️ Configuration des jours de travail
- 🎖️ Points de participation automatiques
- 📈 Graphique tendance de présence

---

## ✅ Checklist d'intégration

- [x] Controller créé
- [x] Routes configurées
- [x] Modèle existant
- [x] Vue calendrier
- [x] Vue historique
- [x] Rapport mensuel
- [x] Composants réutilisables
- [x] Service utilitaires
- [ ] Tests unitaires
- [ ] Tests d'intégration
- [ ] Migration des données (si existantes)
- [ ] Emails de notification (optionnel)

---

**Module créé le** : 27 novembre 2025
**Version** : 1.0.0
**Statut** : ✅ Prêt pour développement
