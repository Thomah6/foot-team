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
        Schema::create('regulation_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id')->constrained()->onDelete('cascade'); // lien vers le titre
            $table->string('sub_number'); // 1.1, 1.2, etc.
            $table->text('content');      // le texte du sous-point
            $table->integer('order')->default(0); // pour trier
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulation_contents');
    }
};
