<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('contrats', function (Blueprint $table) {
        $table->date('date_notification')->nullable()->after('type_renouvelement');
        $table->integer('duree_auto_renouvellement')->nullable()->after('date_notification');
        $table->integer('jours_preavis_resiliation')->nullable()->after('duree_auto_renouvellement');
    });
}

public function down()
{
    Schema::table('contrats', function (Blueprint $table) {
        $table->dropColumn([
            'date_notification',
            'duree_auto_renouvellement',
            'jours_preavis_resiliation',
        ]);
    });
}

};
