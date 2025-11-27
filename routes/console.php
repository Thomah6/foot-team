<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler pour la réinitialisation mensuelle
Schedule::command('stats:reset-monthly')
    ->monthlyOn(1, '00:01')
    ->description('Réinitialisation mensuelle et désignation du joueur du mois');
