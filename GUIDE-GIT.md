# 📋 **GUIDE - Procédures Git pour le Projet Football Manager**

## 🏗 **Structure des Branches**

### **🌿 Branches Principales**
- **`main`** : Version stable et production
- **`dev`** : Branche de développement principal
- **`save`** : Sauvegarde hebdomadaire du projet

---

## 👥 **Rôles et Permissions**

### **🧑‍💻 Développeurs (Tous les membres)**
- Travaillent sur la branche **`dev`**
- Pas d'accès direct à `main`
- Push autorisé sur `dev`

### **👑 Chef de Projet (Gestionnaire)**
- Accès à toutes les branches
- Fusion de `dev` vers `main`
- Création des sauvegardes `save`

---

## 🔄 **Workflow de Développement**

### **1. Initialisation (Pour tous les développeurs)**
```bash
# Cloner le dépôt
git clone [url-du-repo]
cd football-app

# Se positionner sur dev
git checkout dev
git pull origin dev
```

### **2. Travail Quotidien**
```bash
# Avant de commencer → TOUJOURS PULL
git checkout dev
git pull origin dev

# Créer une feature branch (optionnel pour les grosses features)
git checkout -b feature/nom-feature

# Travailler sur le code...
git add .
git commit -m "feat: description claire du changement"

# Si feature branch → merger dans dev
git checkout dev
git pull origin dev
git merge feature/nom-feature
git branch -d feature/nom-feature

# Pousser les changements
git push origin dev
```

### **3. Format des Messages de Commit**
```
feat: ajout système de votes pondérés
fix: correction bug calcul classement
style: amélioration responsive dashboard
docs: mise à jour readme procédures
refactor: optimisation requêtes Eloquent
```

---

## 🚀 **Procédure de Mise en Production**

### **🔒 Seul le Chef de Projet exécute :**

#### **Étape 1 : Récupération et Vérification**
```bash
# Se mettre à jour
git checkout dev
git pull origin dev

# Vérifier les derniers commits
git log --oneline -10
```

#### **Étape 2 : Fusion vers Main**
```bash
# Aller sur main et mettre à jour
git checkout main
git pull origin main

# Fusionner dev dans main
git merge dev --no-ff -m "release: v1.0.0 - Date"

# Résoudre les conflits si nécessaire
# Pousser sur main
git push origin main
```

#### **Étape 3 : Sauvegarde**
```bash
# Créer/MAJ branche save
git checkout save
git pull origin save

# Fusionner main dans save
git merge main --no-ff -m "save: backup semaine X"

# Tag de sauvegarde
git tag -a "save-v1.0.0-$(date +%Y%m%d)" -m "Sauvegarde du $(date)"

# Pousser save et tags
git push origin save
git push --tags
```

#### **Étape 4 : Retour à Dev**
```bash
git checkout dev
# Optionnel : merger main dans dev pour synchroniser
git merge main
git push origin dev
```

---

## 📋 **Checklist Pré-Production**

### **Avant merge dev → main**
- [ ] Tests unitaires passants
- [ ] Validation des fonctionnalités clés
- [ ] Revue de code par au moins 1 autre dev
- [ ] Vérification responsive design
- [ ] Test cross-rôles (admin/bureau/simple)

### **Après merge main**
- [ ] Sauvegarde sur `save` effectuée
- [ ] Tag de version créé
- [ ] Documentation mise à jour
- [ ] Backup base de données

---

## 🚨 **Règles Importantes**

### **✅ À FAIRE**
- **Toujours** `pull` avant de commencer
- **Messages de commit** clairs et descriptifs
- **Travailler** sur `dev` uniquement
- **Tester** localement avant push
- **Respecter** le format des commits

### **❌ À ÉVITER**
- Push direct sur `main`
- Commit de code non testé
- Messages de commit vagues ("fix bug")
- Travailler sans pull d'abord

---

## 🆘 **En Cas de Problème**

### **Conflits de Merge**
```bash
# Identifier les fichiers en conflit
git status

# Résoudre manuellement puis
git add .
git commit -m "fix: résolution conflits merge"
```

### **Annuler un Commit Local**
```bash
git reset --soft HEAD~1  # Garde les changements
git reset --hard HEAD~1  # Supprime les changements
```

---

## 📞 **Contacts et Support**

**Chef de Projet** : [Nom] - Responsable merges et sauvegardes  
**Lead Backend** : [Nom] - Support technique Git  
**Lead Frontend** : [Nom] - Questions workflow  

---

**⚠️ Rappel :** La branche `main` est sacrée ! Seul le Chef de Projet peut y écrire.  
**🔄 Fréquence sauvegarde :** Toutes les semaines ou avant mise en production.

**🚀 Happy Coding!** ⚽💻
