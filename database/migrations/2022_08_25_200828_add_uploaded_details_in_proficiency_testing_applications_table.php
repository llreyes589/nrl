<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUploadedDetailsInProficiencyTestingApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proficiency_testing_applications', function (Blueprint $table) {
            $table->timestamp('receipt_uploaded_at')->nullable();
            $table->timestamp('result_uploaded_at')->nullable();
            $table->timestamp('verified_payment_at')->nullable();
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
