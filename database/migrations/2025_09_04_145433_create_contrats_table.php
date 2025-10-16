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
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('societe_id')->nullable();
            $table->foreign('societe_id')->references('id')->on('societes');
            $table->unsignedBigInteger('type_contrat_id')->nullable();
            $table->foreign('type_contrat_id')->references('id')->on('types_contrats');
            $table->string('titre');
            $table->unsignedBigInteger('annee_fiscale_id')->nullable();
            $table->foreign('annee_fiscale_id')->references('id')->on('annees_fiscales');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('type_renouvelement');
            $table->text('description');
            $table->text('notes_generales');
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->boolean('notif_contractant');
            $table->enum('statut', ['ebauche', 'revise','en_approbation','approuve','signe','actif','annule','expire','renouvele']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
