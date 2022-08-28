<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name_client');
            $table->integer('juridic_person')->nullable();
            $table->string('document_client')->nullable();
            $table->string('cellphone_client')->nullable();
            $table->string('telephone_client')->nullable();
            $table->string('email_client')->nullable();
            $table->string('street_client')->nullable();
            $table->string('number_client')->nullable();
            $table->string('district_client')->nullable();
            $table->string('city_client')->nullable();
            $table->string('state_client')->nullable();
            $table->string('cep_client')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
