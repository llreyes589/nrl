<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectorDetailsInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('director_name');
            $table->string('director_position');
            $table->text('director_designation');
            $table->text('director_signature_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('director_name');
            $table->dropColumn('director_position');
            $table->dropColumn('director_designation');
            $table->dropColumn('director_signature_path');
        });
    }
}
