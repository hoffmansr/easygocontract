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
        Schema::table('contrats', function (Blueprint $table) {
            $table->foreignId('signature_entity_id')
              ->nullable()
              ->constrained('representants_legaux')
              ->after('statut');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrats', function (Blueprint $table) {
             $table->dropForeign(['signature_entity_id']);
             $table->dropColumn('signature_entity_id');
            //
        });
    }
};
