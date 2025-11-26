📘 CAHIER DE CHARGES — Application de Gestion d’une Équipe de Football
Technologies obligatoires : Laravel 11 + Inertia.js + Vue.js 3 + TailwindCSS

1. Contexte du projet
L’application vise à digitaliser la gestion d’une équipe de football comportant un bureau, plusieurs sous-équipes et des membres simples. Elle devra permettre la gestion administrative, sportive, financière et décisionnelle de l’équipe.

Le but du projet : ✔ Évaluer les compétences techniques des étudiants en Laravel Inertia ✔ Tester leur capacité à produire une app réelle, structurée et maintenable ✔ Manipuler Eloquent, migrations, relations complexes, votes, rôles, statistiques, galerie, filtres, etc.

2. Acteurs du système
Administrateur
Membre du Bureau
Membre Simple
3. Fonctionnalités par Acteur
🔵 3.1. Espace Membre du Bureau
3.1.1. Gestion des membres
CRUD sur les membres
Recherche par nom, email, rôle
Filtrage :

par date d’inscription
par sous-équipe
Affichage détaillé d’un membre
3.1.2. Règlement intérieur
Consultation du règlement intérieur
Lecture seule
3.1.3. Proposition de réflexions
Formulaire pour soumettre une suggestion / réflexion
Listing des réflexions personnelles
3.1.4. Votes
Participer aux votes
Poids du vote différent :

Vote “membre simple”
Vote “membre du bureau” (ex : valeur 2)
3.1.5. Gestion de caisse
Visualisation du solde actuel
Visualisation de l’historique de points (participation aux séances, cotisations…)
Tableau détaillé mensuel + annuel
3.1.6. Sous-équipes
Voir la liste des sous-équipes
Voir les membres de chaque sous-équipe
🟢 3.2. Espace Membre Simple
3.2.1. Accueil intuitif (Rubrique “À la une”)
Affichage :

Point comptable du mois
Réflexions en cours
Votes ouverts (accepter/refuser)
Statistiques clés
3.2.2. Réflexions & commentaires
Consulter les réflexions
Voter pour / contre
Commenter une réflexion
3.2.3. Performances individuelles
Afficher pour chaque joueur :

Nombre de buts
Passes décisives
Buts encaissés
Classements :

Meilleur buteur du mois
Meilleur passeur
Meilleur gardien
3.2.4. Présence
Consulter la liste des présences passées
3.2.5. Performances des sous-équipes
Nombre de victoires dans le mois
Classement par sous-équipe
Historique mois après mois
3.2.6. Suggestions
Soumettre une suggestion
Consulter les suggestions
Voir l’auteur d’une suggestion
3.2.7. Galerie
Voir les photos
Liker les photos
🔴 3.3. Espace Administrateur
3.3.1. Gestion des réflexions
CRUD complet sur les réflexions
Validation d’une réflexion après fin des votes
3.3.2. Gestion des comptes membres
Activation / désactivation de compte
Affectation d’un membre à un rôle :

membre simple
membre du bureau
3.3.3. Gestion des sous-équipes
Affecter un membre à une sous-équipe
Mercato (transfert d’un membre d’une sous-équipe à une autre)
3.3.4. Identité visuelle
Mise à jour du logo
Mise à jour du slogan
Gestion de la galerie de photos
3.3.5. Présence
Validation des présences mensuelles
Mise à jour manuelle si besoin
3.3.6. Statistiques
Mise à jour manuelle des stats :

buts marqués
passes décisives
buts encaissés
3.3.7. Gestion de la caisse
Voir la liste complète des membres
Points/participation par séance
Total mensuel par joueur
Total global
Mise à jour du point caisse
3.3.8. Joueur du mois
Sélection du joueur du mois
Activation / affichage public
4. Fonctions Transversales
🔐 Gestion des rôles
Middleware : admin / bureau / membre simple
🗳 Système de vote
Pondération des votes
Calcul automatique du résultat
📊 Statistiques
Graphiques (nombre de buts, victoires, classement...)
🖼 Galerie
Upload d’images
Like des photos
structure moi tous ce ensemble .


Filtre de membres et par date
recherche de membre

slider pour les reglements

seul le membre de bureau lance de reflexion: affichable uniqueùent si l'admin l'active

le membre simplepeut liker ou disliker (vote) un comm et peut commenter

tout le monde vote.

lesmembre du bureau peuvent consulter l'historique de la caisse

les membres simples incremente leur caisse et l'admin valide


tout les membres ont acces à l'espace membre simple(meme interface, just e qu'en fonction des roles, chacun a plus defonctionnalités ou pas)


c'est l'admin qui redige les infos ala une
 a la une c'est uncompte rendu de la derniere sceance ou reflexion ou match.

il faut minimuim 2but pour etre dans les buteurs(dans le leaderboard),pareil pour passeur D et meilleur gardien(uniquement ceux qui ont etet constamment dans les buts et on arreté plusieurs but)

liste de presence dur le mois

on peut definir un membre simple comme gardien de but

Interface complete mobile first et cool(design interessant et originale avec des couleurs bien choisi)

gallerie avec like 

l'admin peut supprimer les photos

sur les reflexions un membre ne peut faires des suggestion publiques

admin fait crud complet de reflexion et peux activer ou desactiver ca aussi

l'admin gere donc les sous equipes
Affecter un membre à une sous-équipe(drag & drop)
possibilité de Mercato (transfert d’un membre d’une sous-équipe à une autre)

quand jeparle de sous equipes c'est les classement d'equipes pour jouer au sein de cette equipeque forme tout lesmemebres; doncpeut etre equipes A,B etc

apres un delai il peut valider une reflexion(avec la le vote gagnant)

l'adminpeut definir lesdepenses et toutles defalcations se font

joueur du mois (dispo pendant une semaine) sur baniere horizontale deroulante (goal assist)

les membres simples marques leur stats apres chaquematch et c'es l'admin qui valide


les reflexions ont une durée definie

tout lesmembres sont des joueurs et chaque mùembre a un pseudo,
email,mdp,
avatar, poster de lui  etc etc 






# 📋 **RÉPARTITION PAR FONCTIONNALITÉS COMPLÈTES - Équipe de 13**

## 🎯 **PRINCIPE : Full-Stack par Module**
**Chaque personne gère un module COMPLET : Backend + Frontend + Design**

---

## 👥 **RÉPARTITION DES MODULES**

### **1. MODULE AUTHENTIFICATION & PROFILS**
```
✅ BACKEND :
  - Model User (pseudo, email, avatar, poster, rôle)
  - Migrations users
  - Authentication Laravel Breeze
  - Middleware rôles
  - Factory User avec Faker

✅ FRONTEND :
  - Pages login/register
  - Profil utilisateur
  - Édition avatar/poster
  - Design responsive profil
```

### **2. MODULE GESTION MEMBRES & RÔLES**
```
✅ BACKEND :
  - CRUD membres complet
  - Recherche/filtres (nom, email, date)
  - Changement rôles (admin/bureau/simple)
  - Relations user-team

✅ FRONTEND :
  - Interface liste membres
  - Formulaire édition membre
  - Filtres et recherche
  - Design tableaux responsive
```

### **3. MODULE SOUS-ÉQUIPES & MERCATO**
```
✅ BACKEND :
  - Model Team
  - Relations many-to-many users-teams
  - Service affectation membres
  - Logique mercato (transferts)

✅ FRONTEND :
  - Liste équipes et membres
  - Drag & drop affectation
  - Interface mercato
  - Design cartes équipes
```

### **4. MODULE RÉFLEXIONS & SUGGESTIONS**
```
✅ BACKEND :
  - Model Reflection
  - Relations avec User
  - Système activation/désactivation admin
  - Durée limitée réflexions

✅ FRONTEND :
  - Formulaire soumission réflexion
  - Listing réflexions
  - Interface admin gestion
  - Design cartes réflexions
```

### **5. MODULE VOTES & COMMENTAIRES**
```
✅ BACKEND :
  - Model Vote (pondéré bureau x2)
  - Model Comment
  - Calcul résultats votes
  - Validation admin après délai

✅ FRONTEND :
  - Interface vote (like/dislike)
  - Système commentaires
  - Affichage résultats
  - Animations votes
```

### **6. MODULE STATISTIQUES & PERFORMANCES**
```
✅ BACKEND :
  - Model Stat (buts, passes, arrêts)
  - Services calcul classements
  - Seuils minimaux (2 buts, etc.)
  - Meilleur buteur/passeur/gardien

✅ FRONTEND :
  - Dashboard performances
  - Classements et leaderboards
  - Graphiques et charts
  - Design data visualisation
```

### **7. MODULE PRÉSENCES & PARTICIPATION**
```
✅ BACKEND :
  - Model Presence
  - Validation mensuelle admin
  - Calcul points participation
  - Historique présences

✅ FRONTEND :
  - Calendrier présences
  - Interface validation admin
  - Historique visuel
  - Design calendrier responsive
```

### **8. MODULE GESTION FINANCIÈRE**
```
✅ BACKEND :
  - Model Finance (caisse, transactions)
  - Calcul solde et points
  - Historique mensuel/annuel
  - Validation admin dépenses

✅ FRONTEND :
  - Dashboard financier
  - Tableau bord caisse
  - Graphiques finances
  - Design rapports financiers
```

### **9. MODULE GALERIE & MÉDIAS**
```
✅ BACKEND :
  - Model Gallery (photos)
  - Système upload avatars/posters/photos
  - Relations likes
  - Suppression admin

✅ FRONTEND :
  - Galerie photos
  - Système like
  - Upload multiple
  - Design grid gallery responsive
```

### **10. MODULE "À LA UNE" & ACTUALITÉS**
```
✅ BACKEND :
  - Model News
  - Gestion admin contenu
  - Relations avec réflexions/matchs
  - Système publication

✅ FRONTEND :
  - Page d'accueil "À la une"
  - Bannière joueur du mois
  - Slider règlement intérieur
  - Design magazine sportif
```

### **11. MODULE RÈGLEMENT & DOCUMENTS**
```
✅ BACKEND :
  - Model Regulation
  - Stockage contenu règlement
  - Versioning documents

✅ FRONTEND :
  - Page règlement intérieur
  - Slider/scroll smooth
  - Design document formel
```

### **12. MODULE JOUER DU MOIS & RÉCOMPENSES**
```
✅ BACKEND :
  - Model PlayerOfTheMonth
  - Sélection et activation admin
  - Durée affichage (1 semaine)
  - Historique joueurs du mois

✅ FRONTEND :
  - Bannière horizontale déroulante
  - Animation présentation
  - Design trophée/badge
```

### **13. MODULE DASHBOARD & NAVIGATION GLOBALE**
```
✅ BACKEND :
  - Services données agrégées
  - Statistiques globales
  - Calcul indicateurs KPI

✅ FRONTEND :
  - Layout principal
  - Navigation responsive
  - Sidebar/header adaptatif
  - Design system cohérent
```

---

## 🎯 **COORDINATION ET INTÉGRATION**

### **Points de Synchronisation :**
- **Models partagés** : User, Team (coordination nécessaire)
- **Design system** : Couleurs, typo, composants de base
- **Navigation globale** : Routes et layout principal
