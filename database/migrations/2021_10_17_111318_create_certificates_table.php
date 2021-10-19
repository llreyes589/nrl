<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->integer('facility_id');
            $table->string('or_no')->unique();
            $table->string('validity');
            $table->string('performance');
            $table->string('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('endorsed_by')->nullable();
            $table->timestamp('endorsed_at')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('facility_verified_by')->nullable();
            $table->timestamp('facility_verified_at')->nullable();
            $table->text('key')->nullable();
            $table->string('issued_by')->nullable();
            $table->timestamp('issued_at')->nullable();
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
        Schema::dropIfExists('certificates');
    }
}
