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
        Schema::create('regulations', function (Blueprint $table) {
            $table->id();
            $table->string('title');              // Titre du règlement ou section
            $table->text('content');              // Contenu du règlement
            $table->unsignedBigInteger('parent_id')->nullable(); // Pour gérer les sous-sections (1.1, 2.1, etc.)
            $table->integer('order')->default(0); // Ordre d’affichage
            $table->timestamps();

            // Clé étrangère pour la hiérarchie
            $table->foreign('parent_id')->references('id')->on('regulations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulations');
    }
};
