<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScoreInProficiencyTestingApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proficiency_testing_applications', function (Blueprint $table) {
            $table->integer('score')->nullable();
            $table->integer('scored_by')->nullable();
            $table->timestamp('scored_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proficiency_testing_applications', function (Blueprint $table) {
            //
        });
    }
}
