# 🚀 Guide d'Intégration - Module Présences

## 📌 Résumé pour l'équipe

Raoul a développé le **module de gestion des présences** complet incluant :
- ✅ Backend API (Laravel Controller)
- ✅ Routes web et API
- ✅ 3 vues Vue.js principales
- ✅ 2 composants réutilisables
- ✅ Service utilitaire de calendrier
- ✅ Tests unitaires
- ✅ Factory et Seeder pour données de test
- ✅ Configuration centralisée

---

## 🔗 Points d'Intégration avec d'autres modules

### 1️⃣ **Module Finance** (Manu Jr)
**Lien** : Points de participation basés sur les présences

```php
// Dans le module Finance, récupérer les présences du mois :
$presences = Presence::where('user_id', $userId)
    ->whereMonth('date', $month)
    ->where('validated_by_admin', true)
    ->count();

$points = $presences * config('presence.participation_points.present');
```

**Configuration** : `config/presence.php` → `participation_points`

---

### 2️⃣ **Module Gestion des Membres** (Alexio)
**Lien** : Récupérer la liste des membres pour le calendrier

```php
// Les présences utilisent :
User::all() // Pour afficher tous les membres
```

**Impact** : Aucun - les relations existent déjà dans le Modèle User

---

### 3️⃣ **Module Statistiques** (Nadège)
**Lien** : Taux de présence pour le classement des joueurs

```php
// Récupérer le taux de présence d'un joueur :
$presence = Presence::where('user_id', $userId)
    ->where('validated_by_admin', true)
    ->count();

$total = Presence::where('user_id', $userId)->count();
$percentage = ($presence / $total) * 100;
```

**À faire** : Intégrer ce calcul dans le dashboard statistiques

---

### 4️⃣ **Module Notifications** (Optionnel)
**Lien** : Envoyer des emails de rappel/validation

Configuration dans `config/presence.php` :
```php
'notifications' => [
    'enabled' => true,
    'send_validation_email' => true,  // ← À activer
    'send_reminder_email' => true,    // ← À activer
]
```

---

## 📂 Fichiers créés / modifiés

```
✅ CRÉÉS :
├── app/Http/Controllers/PresenceController.php
├── resources/views/Presence/
│   ├── Index.vue
│   ├── History.vue
│   ├── MonthlyReport.vue
│   └── Components/
│       ├── PresenceStatusCell.vue
│       └── DeclarePresenceModal.vue
├── resources/js/Services/calendarService.js
├── database/factories/PresenceFactory.php
├── database/seeders/PresenceSeeder.php
├── tests/Feature/PresenceTest.php
└── PRESENCE_MODULE.md

⚠️ MODIFIÉS :
├── routes/web.php
└── config/presence.php
```

---

## 🎯 Checklist d'Intégration

### Pour le Lead Dev (Thomah)
- [ ] Vérifier les migrations sont bien appliquées
- [ ] Lancer `php artisan migrate` si nécessaire
- [ ] Vérifier les rôles/permissions dans Middleware
- [ ] Tester les routes sur `http://localhost:8000/presence`

### Pour chaque module à intégrer

#### Finance (Manu Jr)
```
- [ ] Importer le service de présence
- [ ] Calculer les points de participation
- [ ] Ajouter au tableau de bord finance
- [ ] Tester l'export CSV
```

#### Statistiques (Nadège)
```
- [ ] Intégrer le taux de présence au classement
- [ ] Ajouter un filtre "présences >= X%"
- [ ] Afficher sur le leaderboard
- [ ] Tester les calculs
```

#### Dashboard (Thomas)
```
- [ ] Ajouter un lien vers /presence dans la nav
- [ ] Ajouter un KPI "Présence du mois"
- [ ] Intégrer le widget de présence
- [ ] Tester la navigation mobile
```

---

## 🧪 Tests locaux

### 1. Lancer les tests
```bash
php artisan test tests/Feature/PresenceTest.php
```

### 2. Remplir les données de test
```bash
php artisan db:seed --class=PresenceSeeder
```

### 3. Créer quelques présences manuellement
```php
// Tinker
$ php artisan tinker

>>> use App\Models\Presence;
>>> use App\Models\User;
>>> $user = User::first();
>>> Presence::factory(10)->forUser($user)->forMonth(11, 2025)->create();
```

### 4. Tester sur le navigateur
```
http://localhost:8000/presence
http://localhost:8000/presence/history
http://localhost:8000/presence/monthly-report (admin)
```

---

## 🔧 Configuration requise

### Permissions Middleware
Vérifier que le middleware `role:admin` existe dans `app/Http/Middleware/`

```php
// Si n'existe pas, créer :
php artisan make:middleware RoleMiddleware
```

### Middleware en place dans web.php
```php
Route::middleware('role:admin')->group(function () {
    // Routes admin protégées
});
```

---

## 📊 API Endpoints disponibles

### Public (authentifié)
```
GET     /presence                   # Calendrier mensuel
GET     /presence/history           # Historique personnel
POST    /presence                   # Déclarer présence
GET     /presence/day               # Présences du jour (JSON)
```

### Admin
```
PATCH   /presence/{id}/validate     # Valider une présence
PATCH   /presence/{id}              # Modifier une présence
GET     /presence/monthly-report    # Rapport mensuel
```

---

## 🎨 Composants Vue réutilisables

Si vous avez besoin de réutiliser le système de présence ailleurs :

```vue
<script setup>
import PresenceStatusCell from '@/views/Presence/Components/PresenceStatusCell.vue'
import DeclarePresenceModal from '@/views/Presence/Components/DeclarePresenceModal.vue'
</script>

<template>
  <PresenceStatusCell :presence="presence" :is-admin="isAdmin" />
  <DeclarePresenceModal v-if="showModal" @close="showModal = false" />
</template>
```

---

## 📈 Service `calendarService.js`

Pour utiliser les fonctions utilitaires de calendrier :

```javascript
import {
  getMonthDates,
  formatDateFR,
  formatMonthYear,
  calculatePresencePercentage,
  getPresenceStatus,
} from '@/Services/calendarService'

// Exemple
const monthDates = getMonthDates(new Date())
const percentage = calculatePresencePercentage(10, 15) // 66.67%
```

---

## 🐛 Troubleshooting

### Erreur : "Role middleware not found"
**Solution** : Vérifier que `role:admin` est enregistré dans `app/Http/Kernel.php`

```php
protected $routeMiddleware = [
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    // ...
];
```

### Erreur : "Table 'presences' doesn't exist"
**Solution** : Lancer les migrations
```bash
php artisan migrate
```

### Les présences ne s'affichent pas
**Solution** : Vérifier que les données existent dans la DB
```bash
php artisan db:seed --class=PresenceSeeder
```

### CSS/Tailwind pas appliqué
**Solution** : Recompiler le CSS
```bash
npm run build
# ou
npm run dev
```

---

## 📝 Notes importantes pour la collaboration

1. **Git Workflow** :
   - La branche `presence` est à jour
   - Commiter les changements intégration dans `presence`
   - Merger dans `main` une fois tous les tests ok

2. **Communication** :
   - Raoul = Point de contact pour le module présence
   - Signaler tout problème d'intégration

3. **Code Style** :
   - Suivre PSR-12 pour PHP
   - Suivre ESLint pour Vue/JS
   - Utiliser Prettier pour formatage

4. **Documentation** :
   - Maintenir `PRESENCE_MODULE.md` à jour
   - Ajouter des commentaires pour le code complexe
   - Mettre à jour ce guide si changements majeurs

---

## ✅ Validation finale

Avant de merger en production :

- [ ] Tous les tests passent
- [ ] Aucun bug critique
- [ ] Intégration avec Finance OK
- [ ] Intégration avec Stats OK
- [ ] Navigation fonctionnelle
- [ ] Design responsive testé
- [ ] Dark mode OK
- [ ] Export CSV fonctionne
- [ ] Emails (si activés) fonctionnent
- [ ] Permissions bien configurées
- [ ] Documentation à jour

---

**Créé par** : Raoul Sagui  
**Date** : 27 novembre 2025  
**Version** : 1.0.0  
**Statut** : ✅ Prêt pour intégration
