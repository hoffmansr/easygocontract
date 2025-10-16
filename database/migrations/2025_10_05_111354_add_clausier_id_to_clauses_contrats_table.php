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
        Schema::table('clauses_contrats', function (Blueprint $table) {
            $table->unsignedBigInteger('clausier_id')->nullable()->after('id'); // ou après une autre colonne
            $table->foreign('clausier_id')->references('id')->on('clausiers')->onDelete('set null');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clauses_contrats', function (Blueprint $table) {
             $table->dropForeign(['clausier_id']); // supprime la contrainte FK
            $table->dropColumn('clausier_id');    // supprime la colonne
            //
        });
    }
};
