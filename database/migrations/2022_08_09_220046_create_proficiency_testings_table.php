<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProficiencyTestingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proficiency_testings', function (Blueprint $table) {
            $table->id();
            $table->integer('sdtl');
            $table->integer('cycle');
            $table->unique(['sdtl', 'cycle']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proficiency_testings');
    }
}
