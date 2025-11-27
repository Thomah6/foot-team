# 🎯 **JOUR 2 - TÂCHES DÉTAILLÉES PAR PERSONNE + ROUTES**

## 👥 **RÉPARTITION COMPLÈTE**

### **1️⃣ ALEXIO - Gestion Membres Bureau**
**TÂCHES :**
```
✅ Consultation liste membres (sans modification)
✅ Recherche membres (nom, email, équipe)
✅ Filtres (date inscription, rôle, équipe)
✅ Profil détaillé membre
✅ Pagination résultats
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/members')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauMemberController::class, 'index'])->name('bureau.members.index');
    Route::get('/search', [BureauMemberController::class, 'search'])->name('bureau.members.search');
    Route::get('/{user}', [BureauMemberController::class, 'show'])->name('bureau.members.show');
    Route::get('/filter/by-team/{team}', [BureauMemberController::class, 'filterByTeam'])->name('bureau.members.filter.team');
    Route::get('/filter/by-role/{role}', [BureauMemberController::class, 'filterByRole'])->name('bureau.members.filter.role');
});
```

### **2️⃣ FRANCIS - Sous-équipes Bureau**
**TÂCHES :**
```
✅ Consultation liste équipes
✅ Voir membres par équipe
✅ Statistiques équipes (nb membres, performances)
✅ Interface visuelle équipes
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/teams')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauTeamController::class, 'index'])->name('bureau.teams.index');
    Route::get('/{team}', [BureauTeamController::class, 'show'])->name('bureau.teams.show');
    Route::get('/{team}/members', [BureauTeamController::class, 'members'])->name('bureau.teams.members');
    Route::get('/{team}/stats', [BureauTeamController::class, 'stats'])->name('bureau.teams.stats');
});
```

### **3️⃣ MATHIEU - Réflexions Bureau**
**TÂCHES :**
```
✅ Formulaire création réflexion
✅ Soumission réflexion (uniquement bureau)
✅ Listing réflexions personnelles
✅ Consultation réflexions en cours
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/reflections')->middleware('role:bureau')->group(function () {
    Route::get('/create', [BureauReflectionController::class, 'create'])->name('bureau.reflections.create');
    Route::post('/', [BureauReflectionController::class, 'store'])->name('bureau.reflections.store');
    Route::get('/my-reflections', [BureauReflectionController::class, 'myReflections'])->name('bureau.reflections.my');
    Route::get('/active', [BureauReflectionController::class, 'active'])->name('bureau.reflections.active');
    Route::get('/{reflection}', [BureauReflectionController::class, 'show'])->name('bureau.reflections.show');
});
```

### **4️⃣ PRINCE - Votes Bureau**
**TÂCHES :**
```
✅ Interface votes en cours
✅ Système vote pondéré (poids x2)
✅ Calcul résultats en temps réel
✅ Historique votes personnels
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/votes')->middleware('role:bureau')->group(function () {
    Route::get('/active', [BureauVoteController::class, 'active'])->name('bureau.votes.active');
    Route::post('/{reflection}/vote', [BureauVoteController::class, 'vote'])->name('bureau.votes.submit');
    Route::get('/{reflection}/results', [BureauVoteController::class, 'results'])->name('bureau.votes.results');
    Route::get('/my-votes', [BureauVoteController::class, 'myVotes'])->name('bureau.votes.my');
    Route::get('/history', [BureauVoteController::class, 'history'])->name('bureau.votes.history');
});
```

### **5️⃣ NADEGE - Stats & Performances Bureau**
**TÂCHES :**
```
✅ Dashboard statistiques globales
✅ Classements (buteurs, passeurs, gardiens)
✅ Consultation stats par membre
✅ Graphiques performances équipes
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/stats')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauStatController::class, 'index'])->name('bureau.stats.index');
    Route::get('/leaderboards', [BureauStatController::class, 'leaderboards'])->name('bureau.stats.leaderboards');
    Route::get('/leaderboards/goals', [BureauStatController::class, 'goalLeaders'])->name('bureau.stats.leaderboards.goals');
    Route::get('/leaderboards/assists', [BureauStatController::class, 'assistLeaders'])->name('bureau.stats.leaderboards.assists');
    Route::get('/leaderboards/goalkeepers', [BureauStatController::class, 'goalkeeperLeaders'])->name('bureau.stats.leaderboards.goalkeepers');
    Route::get('/members/{user}/stats', [BureauStatController::class, 'memberStats'])->name('bureau.stats.member');
});
```

### **6️⃣ RAOUL - Présences Bureau**
**TÂCHES :**
```
✅ Calendrier présences consultation
✅ Statistiques taux présence
✅ Historique présences membres
✅ Export données présences
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/presences')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauPresenceController::class, 'index'])->name('bureau.presences.index');
    Route::get('/calendar', [BureauPresenceController::class, 'calendar'])->name('bureau.presences.calendar');
    Route::get('/statistics', [BureauPresenceController::class, 'statistics'])->name('bureau.presences.statistics');
    Route::get('/history', [BureauPresenceController::class, 'history'])->name('bureau.presences.history');
    Route::get('/export', [BureauPresenceController::class, 'export'])->name('bureau.presences.export');
    Route::get('/members/{user}/presences', [BureauPresenceController::class, 'memberPresences'])->name('bureau.presences.member');
});
```

### **7️⃣ MANU JR - Finances Bureau**
**TÂCHES :**
```
✅ Dashboard financier consultation
✅ Historique transactions
✅ Solde actuel caisse
✅ Graphiques évolution financière
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/finance')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauFinanceController::class, 'dashboard'])->name('bureau.finance.dashboard');
    Route::get('/transactions', [BureauFinanceController::class, 'transactions'])->name('bureau.finance.transactions');
    Route::get('/history', [BureauFinanceController::class, 'history'])->name('bureau.finance.history');
    Route::get('/balance', [BureauFinanceController::class, 'balance'])->name('bureau.finance.balance');
    Route::get('/reports', [BureauFinanceController::class, 'reports'])->name('bureau.finance.reports');
    Route::get('/members/{user}/contributions', [BureauFinanceController::class, 'memberContributions'])->name('bureau.finance.member');
});
```

### **8️⃣ KARIM - Galerie Bureau**
**TÂCHES :**
```
✅ Consultation galerie photos
✅ Système like photos
✅ Filtrage photos récentes/populaires
✅ Visualisation détaillée photo
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/gallery')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauGalleryController::class, 'index'])->name('bureau.gallery.index');
    Route::get('/{gallery}', [BureauGalleryController::class, 'show'])->name('bureau.gallery.show');
    Route::post('/{gallery}/like', [BureauGalleryController::class, 'like'])->name('bureau.gallery.like');
    Route::get('/popular', [BureauGalleryController::class, 'popular'])->name('bureau.gallery.popular');
    Route::get('/recent', [BureauGalleryController::class, 'recent'])->name('bureau.gallery.recent');
});
```

### **9️⃣ FADEL - "À la Une" Bureau**
**TÂCHES :**
```
✅ Consultation "À la une"
✅ Lecture actualités
✅ Navigation slider règlement
✅ Consultation joueur du mois
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/news')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauNewsController::class, 'index'])->name('bureau.news.index');
    Route::get('/featured', [BureauNewsController::class, 'featured'])->name('bureau.news.featured');
    Route::get('/{news}', [BureauNewsController::class, 'show'])->name('bureau.news.show');
    Route::get('/player-of-month/current', [BureauNewsController::class, 'currentPlayerOfMonth'])->name('bureau.news.player_of_month');
});
```

### **🔟 WARISS - Règlement Bureau**
**TÂCHES :**
```
✅ Consultation règlement intérieur
✅ Interface slider/scroll smooth
✅ Recherche dans règlement
✅ Version actuelle règlement
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/regulations')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauRegulationController::class, 'index'])->name('bureau.regulations.index');
    Route::get('/current', [BureauRegulationController::class, 'current'])->name('bureau.regulations.current');
    Route::get('/search', [BureauRegulationController::class, 'search'])->name('bureau.regulations.search');
    Route::get('/section/{section}', [BureauRegulationController::class, 'section'])->name('bureau.regulations.section');
});
```

### **1️⃣1️⃣ SERGIO - Joueur du Mois Bureau**
**TÂCHES :**
```
✅ Consultation joueur du mois actuel
✅ Historique joueurs du mois
✅ Statistiques joueur du mois
✅ Interface bannière déroulante
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau/player-of-the-month')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauPlayerOfMonthController::class, 'index'])->name('bureau.player_of_the_month.index');
    Route::get('/current', [BureauPlayerOfMonthController::class, 'current'])->name('bureau.player_of_the_month.current');
    Route::get('/history', [BureauPlayerOfMonthController::class, 'history'])->name('bureau.player_of_the_month.history');
    Route::get('/{playerOfMonth}', [BureauPlayerOfMonthController::class, 'show'])->name('bureau.player_of_the_month.show');
});
```

### **1️⃣2️⃣ Thamas 12 - Navigation Bureau**
**TÂCHES :**
```
✅ Sidebar/header bureau
✅ Dashboard bureau agrégé
✅ Navigation responsive
✅ Profil utilisateur bureau
```

**ROUTES PROPOSÉES :**
```php
Route::prefix('bureau')->middleware('role:bureau')->group(function () {
    Route::get('/dashboard', [BureauDashboardController::class, 'index'])->name('bureau.dashboard');
    Route::get('/profile', [BureauProfileController::class, 'edit'])->name('bureau.profile.edit');
    Route::put('/profile', [BureauProfileController::class, 'update'])->name('bureau.profile.update');
    Route::get('/notifications', [BureauNotificationController::class, 'index'])->name('bureau.notifications.index');
});
```

### **1️⃣3️⃣ PERSONNE 13 - Sécurité & Permissions**
**TÂCHES :**
```
✅ Middleware rôle bureau
✅ Vérification permissions routes
✅ Gestion accès différenciés
✅ Protection données sensibles
```

**MIDDLEWARE :**
```php
// App\Http\Middleware\CheckBureauRole.php
public function handle($request, Closure $next)
{
    if (Auth::check() && Auth::user()->role === 'bureau') {
        return $next($request);
    }
    
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('member.dashboard');
}
```
---

## 🎯 **CHECKLIST FIN JOUR 2**

### **Bureau doit avoir :**
- [ ] Dashboard bureau fonctionnel
- [ ] Consultation membres avec recherche/filtres
- [ ] Lancement réflexions (bureau seulement)
- [ ] Votes pondérés (x2)
- [ ] Consultation toutes les données (stats, finances, présences)
- [ ] Navigation bureau complète

