<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute une colonne validated à la table team_stats pour permettre la validation
     * des stats par l'admin (utile pour les futures implémentations)
     */
    public function up(): void
    {
        if (!Schema::hasColumn('team_stats', 'validated')) {
            Schema::table('team_stats', function (Blueprint $table) {
                $table->boolean('validated')->default(true)->after('month');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('team_stats', 'validated')) {
            Schema::table('team_stats', function (Blueprint $table) {
                $table->dropColumn('validated');
            });
        }
    }
};
