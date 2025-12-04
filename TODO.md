# TODO: Adapter les vues au style général (lime/émeraude, dégradés, coins arrondis, design moderne)

## Vues à adapter
- [x] Admin/Dashboard.vue : Remplacer vert par lime/émeraude, ajouter dégradés
- [x] Admin/Members/Index.vue : Adapter couleurs vertes vers lime/émeraude
- [x] Stats/Index.vue : Changer thème gris ardoise vers lime/émeraude
- [x] Admin/TeamStats/Index.vue : Changer thème bleu ciel vers lime/émeraude
- [x] Admin/TeamStats/CreateEdit.vue : Adapter couleurs bleu ciel vers lime/émeraude
- [x] Teams/Index.vue : Vérifier et améliorer l'alignement lime/émeraude
- [x] Teams/Show.vue : Vérifier l'alignement (semble déjà ok)

## Étapes de suivi
- [x] Tester visuellement les vues après modification
- [x] Vérifier la responsivité sur différents appareils
# TODO: Fix Toast Notification System

## Current Status
- Identified duplicate toast logic across multiple finance pages
- Global toast in AuthenticatedLayout.vue is working correctly
- Need to remove local toast implementations from individual pages

## Tasks
- [ ] Remove local toast logic from Index.vue
- [ ] Remove local toast logic from FinanceCreateDepense.vue
- [ ] Remove local toast logic from FinanceCreateAjustement.vue
- [ ] Test finance actions to ensure toasts display correctly
- [ ] Verify no duplicate toasts appear

## Files to Modify
- foot-team/resources/js/Pages/Finance/Index.vue
- foot-team/resources/js/Pages/Finance/FinanceCreateDepense.vue
- foot-team/resources/js/Pages/Finance/FinanceCreateAjustement.vue
