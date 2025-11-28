#!/bin/bash

# Script de vérification du module Présences
# Exécution : bash VERIFY_PRESENCE_MODULE.sh

echo "🔍 Vérification du module Présences..."
echo "════════════════════════════════════════════════"

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Compteurs
FOUND=0
MISSING=0

# Fonction pour vérifier un fichier
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✅${NC} $1"
        ((FOUND++))
    else
        echo -e "${RED}❌${NC} $1 (MANQUANT)"
        ((MISSING++))
    fi
}

# Fonction pour vérifier un dossier
check_dir() {
    if [ -d "$1" ]; then
        echo -e "${GREEN}✅${NC} $1/"
        ((FOUND++))
    else
        echo -e "${RED}❌${NC} $1/ (MANQUANT)"
        ((MISSING++))
    fi
}

echo ""
echo "📂 Vérification des fichiers..."
echo "────────────────────────────────────────────────"

# Backend
check_file "app/Http/Controllers/PresenceController.php"
check_file "app/Console/Commands/ValidatePresencesCommand.php"
check_file "app/Models/Presence.php"

# Frontend
check_dir "resources/views/Presence"
check_file "resources/views/Presence/Index.vue"
check_file "resources/views/Presence/History.vue"
check_file "resources/views/Presence/MonthlyReport.vue"
check_file "resources/views/Presence/Components/PresenceStatusCell.vue"
check_file "resources/views/Presence/Components/DeclarePresenceModal.vue"

# Services
check_file "resources/js/Services/calendarService.js"

# Tests et Factories
check_file "tests/Feature/PresenceTest.php"
check_file "database/factories/PresenceFactory.php"
check_file "database/seeders/PresenceSeeder.php"

# Configuration
check_file "config/presence.php"

# Migration
check_file "database/migrations/2025_11_27_000000_create_presences_table.php"

# Documentation
check_file "PRESENCE_MODULE.md"
check_file "INTEGRATION_GUIDE_PRESENCE.md"
check_file "PRESENCE_README.md"
check_file "SUMMARY_PRESENCE.md"

# Routes
echo ""
echo "🛣️  Vérification des routes..."
echo "────────────────────────────────────────────────"
if grep -q "PresenceController" routes/web.php; then
    echo -e "${GREEN}✅${NC} Routes présence dans web.php"
    ((FOUND++))
else
    echo -e "${RED}❌${NC} Routes présence NOT found in web.php"
    ((MISSING++))
fi

# Configuration Laravel
echo ""
echo "⚙️  Vérification de la configuration..."
echo "────────────────────────────────────────────────"
if grep -q "presence" config/presence.php; then
    echo -e "${GREEN}✅${NC} Configuration présences existante"
    ((FOUND++))
else
    echo -e "${RED}❌${NC} Configuration présences manquante"
    ((MISSING++))
fi

# Résumé
echo ""
echo "════════════════════════════════════════════════"
echo "📊 Résumé:"
echo "  ${GREEN}✅ Trouvés: $FOUND${NC}"
echo "  ${RED}❌ Manquants: $MISSING${NC}"
echo ""

if [ $MISSING -eq 0 ]; then
    echo -e "${GREEN}🎉 Tous les fichiers du module Présences sont en place !${NC}"
    echo ""
    echo "📝 Prochaines étapes:"
    echo "  1. Exécuter: php artisan migrate"
    echo "  2. Exécuter: php artisan db:seed --class=PresenceSeeder"
    echo "  3. Accéder à: http://localhost:8000/presence"
    echo "  4. Lancer les tests: php artisan test tests/Feature/PresenceTest.php"
    exit 0
else
    echo -e "${RED}⚠️  Des fichiers manquent !${NC}"
    echo ""
    echo "📝 Consulter INTEGRATION_GUIDE_PRESENCE.md pour l'installation complète"
    exit 1
fi
