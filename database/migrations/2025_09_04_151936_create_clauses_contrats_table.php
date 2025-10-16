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
        Schema::create('clauses_contrats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrat_id')->nullable();
            $table->foreign('contrat_id')->references('id')->on('contrats');
            $table->unsignedBigInteger('modele_contrat_id')->nullable();
            $table->foreign('modele_contrat_id')->references('id')->on('modeles_contrats');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clauses_contrats');
    }
};
