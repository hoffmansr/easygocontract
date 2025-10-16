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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrat_id');
            $table->unsignedBigInteger('representant_legal_id');
            $table->unsignedBigInteger('societe_id');
            $table->timestamp('signed_at')->nullable();
            $table->text('signature_image')->nullable();
            $table->string('external_signature_url')->nullable();
            $table->string('external_status')->nullable();
            $table->timestamps();

            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');
            $table->foreign('representant_legal_id')->references('id')->on('representants_legaux')->onDelete('cascade');
            $table->foreign('societe_id')->references('id')->on('societes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
