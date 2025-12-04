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
