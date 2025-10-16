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
        Schema::table('modeles_contrats', function (Blueprint $table) {
            // Ajout d’une colonne JSON pour stocker les variables extraites
            $table->json('variables')->nullable()->after('fichier_modele');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modeles_contrats', function (Blueprint $table) {
             $table->dropColumn('variables');
            //
        });
    }
};
