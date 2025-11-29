<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si la table existe déjà, vérifier les colonnes
        if (Schema::hasTable('presences')) {
            // Vérifier et ajouter les colonnes manquantes
            Schema::table('presences', function (Blueprint $table) {
                if (!Schema::hasColumn('presences', 'present')) {
                    $table->boolean('present')->default(true)->after('date');
                }
                
                if (!Schema::hasColumn('presences', 'validated_by_admin')) {
                    $table->boolean('validated_by_admin')->default(false)->after('present');
                }
            });

            return;
        }

        // Créer la table si elle n'existe pas
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->boolean('present')->default(true);
            $table->boolean('validated_by_admin')->default(false);
            $table->timestamps();

            // Index pour les recherches fréquentes
            $table->index(['user_id', 'date']);
            $table->index(['date']);
            $table->index(['validated_by_admin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
