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
        Schema::table('presences', function (Blueprint $table) {
            $table->unsignedBigInteger('declared_by_user_id')->nullable()->after('validated_by_admin');
            $table->foreign('declared_by_user_id')->references('id')->on('users')->onDelete('set null');
            // Si declared_by_user_id est NULL, c'est une auto-déclaration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropForeign(['declared_by_user_id']);
            $table->dropColumn('declared_by_user_id');
        });
    }
};
