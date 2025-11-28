<?php

/**
 * Configuration du module Présences
 * Fichier de configuration centrale pour le module de gestion des présences
 */

return [
    'module' => 'presence',
    'version' => '1.0.0',
    'enabled' => true,

    /**
     * Configuration des statuts de présence
     */
    'statuses' => [
        'present' => [
            'value' => true,
            'label' => 'Présent',
            'icon' => 'check_circle',
            'color' => 'green-500',
            'badge' => 'bg-green-100 text-green-700',
        ],
        'absent' => [
            'value' => false,
            'label' => 'Absent',
            'icon' => 'cancel',
            'color' => 'red-500',
            'badge' => 'bg-red-100 text-red-700',
        ],
        'pending' => [
            'value' => true,
            'label' => 'En attente de validation',
            'icon' => 'schedule',
            'color' => 'yellow-500',
            'badge' => 'bg-yellow-100 text-yellow-700',
        ],
    ],

    /**
     * Configuration des validations
     */
    'validation' => [
        'min_percentage' => 60,      // Pourcentage minimum de présence
        'min_goals_to_rank' => 2,    // Minimum de buts pour être classé
        'working_days_only' => false, // Compter seulement jours ouvrables
    ],

    /**
     * Permissions par rôle
     */
    'permissions' => [
        'admin' => [
            'view_all_presences',
            'validate_presences',
            'create_presences',
            'update_presences',
            'delete_presences',
            'export_reports',
            'view_monthly_report',
        ],
        'bureau' => [
            'view_all_presences',
            'view_monthly_report',
        ],
        'member' => [
            'declare_presence',
            'view_own_presences',
            'view_calendar',
        ],
    ],

    /**
     * Configuration des rapports
     */
    'reports' => [
        'export_formats' => ['csv', 'pdf', 'xlsx'],
        'include_statistics' => true,
        'include_trends' => true,
        'decimal_places' => 2,
    ],

    /**
     * Configuration du calendrier
     */
    'calendar' => [
        'locale' => 'fr_FR',
        'start_day' => 'monday',      // Lundi ou dimanche
        'week_format' => 'calendar',  // calendar ou list
        'show_weekends' => true,      // Afficher samedi/dimanche
    ],

    /**
     * Configuration de la recherche
     */
    'search' => [
        'searchable_fields' => ['name', 'email'],
        'min_characters' => 2,
        'debounce_ms' => 300,
    ],

    /**
     * Routes du module
     */
    'routes' => [
        'prefix' => 'presence',
        'middleware' => ['auth', 'verified'],
        'admin_middleware' => ['auth', 'verified', 'role:admin'],
    ],

    /**
     * Notifications
     */
    'notifications' => [
        'enabled' => true,
        'send_validation_email' => false,
        'send_reminder_email' => false,
    ],

    /**
     * Pagination
     */
    'pagination' => [
        'per_page' => 15,
        'per_page_options' => [10, 15, 25, 50],
    ],

    /**
     * Points de participation
     * Configuration pour l'intégration avec le module Finance
     */
    'participation_points' => [
        'present' => 1,      // Points pour une présence
        'validated' => 0.5,  // Points bonus si validée
        'multiplier' => 1.0, // Multiplicateur général
    ],

    /**
     * Cache configuration
     */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 heure
        'cache_key' => 'presence_',
    ],

    /**
     * Logs
     */
    'logs' => [
        'enabled' => true,
        'log_validations' => true,
        'log_declarations' => true,
        'log_updates' => true,
    ],
];
