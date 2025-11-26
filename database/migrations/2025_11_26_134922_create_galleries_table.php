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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            // Lien vers l'utilisateur qui a uploadé la photo
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Chemin du fichie image
            $table->string('image_path');

            // Description optionnelle de la photo
            $table->text('description')->nullable();

            // Dates de création et mise à jour
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
