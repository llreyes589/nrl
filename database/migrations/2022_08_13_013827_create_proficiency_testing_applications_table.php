<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProficiencyTestingApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proficiency_testing_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('proficiency_testing_id');
            $table->text('test_method_used')->nullable();
            $table->text('cutoff_value')->nullable();
            $table->text('methamphetamine')->nullable();
            $table->text('tetrahydrocannabinol')->nullable();
            $table->text('receipt_path')->nullable();
            $table->text('unboxing_video_path')->nullable();
            // $table->integer('approved_by')->nullable();
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
        Schema::dropIfExists('proficiency_testing_applications');
    }
}
