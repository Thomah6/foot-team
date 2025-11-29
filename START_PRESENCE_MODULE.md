# 🚀 INSTRUCTIONS DE DÉMARRAGE - Module Présences

> Créé par **Raoul Sagui**  
> Date : **27 novembre 2025**  
> Version : **1.0.0**

---

## ⚡ Quick Start (5 min)

### 1️⃣ Mettre à jour les migrations
```bash
php artisan migrate
```

### 2️⃣ Remplir les données de test (optionnel)
```bash
php artisan db:seed --class=PresenceSeeder
```

### 3️⃣ Accéder au module
```
http://localhost:8000/presence
```

### 4️⃣ Lancer les tests
```bash
php artisan test tests/Feature/PresenceTest.php
```

---

## 📋 Commandes disponibles

### Module Présences
```bash
# Valider les présences en attente (interactif)
php artisan presence:validate

# Valider automatiquement
php artisan presence:validate --auto

# Valider pour un mois spécifique
php artisan presence:validate --month=2025-11
```

---

## 🧪 Vérifier l'installation

### Option 1 : Script de vérification
```bash
bash VERIFY_PRESENCE_MODULE.sh
```

### Option 2 : Vérification manuelle
```bash
# Vérifier les fichiers
ls app/Http/Controllers/PresenceController.php
ls resources/views/Presence/

# Vérifier les routes
php artisan route:list | grep presence

# Vérifier la migration
php artisan migrate:status
```

---

## 🧪 Tester les fonctionnalités

### Tests unitaires
```bash
php artisan test tests/Feature/PresenceTest.php
```

### Tests spécifiques
```bash
# Test déclaration de présence
php artisan test tests/Feature/PresenceTest.php --filter=declare_presence

# Test validation admin
php artisan test tests/Feature/PresenceTest.php --filter=admin_can_validate

# Tous les tests avec rapport
php artisan test tests/Feature/PresenceTest.php --verbose
```

---

## 🎨 Utiliser le module en ligne

### Accès utilisateur simple
```
1. Aller à http://localhost:8000/presence
2. Cliquer sur "Déclarer ma présence"
3. Sélectionner une date
4. Confirmer
5. Attendre validation de l'admin
```

### Accès administrateur
```
1. Aller à http://localhost:8000/presence
2. Cliquer sur une cellule de présence
3. Sélectionner le statut (Présent/En attente/Absent)
4. Aller sur /presence/monthly-report pour le rapport
5. Exporter en CSV si nécessaire
```

---

## 📚 Documentation disponible

| Document | Contenu |
|----------|---------|
| **PRESENCE_MODULE.md** | Documentation technique complète (20+ pages) |
| **INTEGRATION_GUIDE_PRESENCE.md** | Guide intégration équipe |
| **PRESENCE_README.md** | Résumé technique rapide |
| **SUMMARY_PRESENCE.md** | Ce qui a été créé |
| **Ce fichier** | Instructions de démarrage |

---

## 🔗 Routes principales

| Route | Méthode | Description | Rôle |
|-------|---------|-------------|------|
| `/presence` | GET | Calendrier mensuel | Tous |
| `/presence/history` | GET | Historique personnel | Tous |
| `/presence` | POST | Déclarer présence | Tous |
| `/presence/day` | GET | API présences jour | Tous |
| `/presence/{id}/validate` | PATCH | Valider présence | Admin |
| `/presence/{id}` | PATCH | Modifier présence | Admin |
| `/presence/monthly-report` | GET | Rapport mensuel | Admin |

---

## 🔐 Permissions

### Admin
- Voir toutes les présences
- Valider les présences
- Modifier les présences
- Voir le rapport mensuel
- Exporter en CSV

### Utilisateur simple
- Déclarer sa présence
- Voir son historique
- Voir le calendrier (lecture seule)

---

## 🐛 En cas de problème

### "Table presences doesn't exist"
```bash
php artisan migrate
```

### "Route not found"
```bash
php artisan route:cache
php artisan cache:clear
```

### "Vue component not found"
```bash
npm run dev
# ou
npm run build
```

### "Permission denied"
```bash
# Vérifier le middleware role:admin dans routes/web.php
php artisan route:list | grep presence
```

### Plus de problèmes ?
→ Consultez `INTEGRATION_GUIDE_PRESENCE.md` section Troubleshooting

---

## 📊 Configuration

### Configuration principale : `config/presence.php`

Les configurations clés :
- **Statuts** : Icônes et couleurs des statuts de présence
- **Permissions** : Par rôle (admin/bureau/member)
- **Rapports** : Formats d'export disponibles
- **Calendrier** : Locale, premier jour de la semaine
- **Notifications** : Activation des emails (optionnel)
- **Points** : Intégration module Finance

---

## 🔧 Intégration avec autres modules

### Finance Module
```php
// Récupérer les points de participation
$presences = Presence::where('user_id', $userId)
    ->where('validated_by_admin', true)
    ->whereMonth('date', now()->month)
    ->count();
```

### Statistiques Module
```php
// Taux de présence pour classement
$percentage = calculatePresencePercentage($presences, $total);
```

---

## 📱 Test responsif

### Navigateur DevTools
1. Appuyer sur `F12`
2. Cliquer sur l'icône device (📱)
3. Tester sur différentes résolutions

### Résolutions testées
- ✅ Desktop (1920x1080)
- ✅ Tablet (768x1024)
- ✅ Mobile (375x667)
- ✅ iPhone (390x844)
- ✅ Android (412x915)

---

## 🌙 Dark Mode

Le module supporte complètement le dark mode :
- ✅ Activation système (dark)
- ✅ Toggle manuel si disponible
- ✅ Persistence des préférences

Tester :
```
1. Aller à Préférences système → Appearance → Dark
2. Ou ajouter class "dark" à <html>
3. Le module s'adapte automatiquement
```

---

## ✅ Checklist pré-production

- [ ] Migration exécutée (`php artisan migrate`)
- [ ] Données de test chargées (`php artisan db:seed --class=PresenceSeeder`)
- [ ] Tests passent (`php artisan test`)
- [ ] Routes fonctionnelles (`php artisan route:list`)
- [ ] Interface accessible (`http://localhost:8000/presence`)
- [ ] Dark mode testé
- [ ] Responsive testé
- [ ] Admin peut valider
- [ ] Export CSV fonctionne
- [ ] Documentation lue

---

## 🎯 Prochaines étapes recommandées

### Court terme (cette semaine)
1. Tester le module complet
2. Intégrer avec Finance (Manu Jr)
3. Intégrer avec Stats (Nadège)
4. Ajouter aux navigations

### Moyen terme (semaine prochaine)
1. Notifications email (optionnel)
2. Graphiques Charts.js
3. Import Excel (optionnel)

### Long terme (après MVP)
1. Mobile app
2. Synchronisation iCal
3. Rapports avancés
4. Prédictions ML

---

## 📞 Support

### Questions sur le code ?
→ Consulter les fichiers `.php` et `.vue` (bien commentés)

### Questions sur l'intégration ?
→ Consulter `INTEGRATION_GUIDE_PRESENCE.md`

### Questions techniques ?
→ Consulter `PRESENCE_MODULE.md`

### Besoin d'aide ?
Consultez :
1. Ce fichier (instructions basiques)
2. `SUMMARY_PRESENCE.md` (ce qui a été fait)
3. `PRESENCE_README.md` (résumé technique)
4. `PRESENCE_MODULE.md` (documentation complète)
5. `INTEGRATION_GUIDE_PRESENCE.md` (intégration équipe)

---

## 🎉 Bravo !

Vous avez maintenant le **module Présences complet et fonctionnel** 🚀

```
✅ Backend API
✅ Frontend Vue.js
✅ Tests
✅ Documentation
✅ Configuration
✅ Migration DB
✅ Commandes CLI
✅ Seeder/Factory
✅ Responsive design
✅ Dark mode
✅ Permission
✅ Export CSV
```

**Bon développement ! 💪**

---

**Créé avec ❤️ par Raoul Sagui**  
**Bootcamp 2025**
