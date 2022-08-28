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
            $table->date('emission_date')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('certificate_name')->nullable();
            $table->string('alert_days')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('email_notification')->nullable();
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
