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
        Schema::create('contrats_contractants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrat_id')->nullable();
            $table->foreign('contrat_id')->references('id')->on('contrats');
            $table->unsignedBigInteger('contractant_id')->nullable();
            $table->foreign('contractant_id')->references('id')->on('contractants');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats_contractants');
    }
};
