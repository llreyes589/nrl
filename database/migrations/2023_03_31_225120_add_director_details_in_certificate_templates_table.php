<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectorDetailsInCertificateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
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
        Schema::table('certificate_templates', function (Blueprint $table) {
            //
        });
    }
}
