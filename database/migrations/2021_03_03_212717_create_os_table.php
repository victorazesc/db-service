<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('os', function (Blueprint $table) {
            $table->id();
            $table->timestamp('initial_date')->nullable();
            $table->timestamp('final_date')->nullable();
            $table->string('warranty')->nullable();
            $table->string('product_description')->nullable();
            $table->string('defect')->nullable();
            $table->string('status')->nullable();
            $table->string('comments')->nullable();
            $table->string('technical_report')->nullable();
            $table->string('amount')->nullable();

            $table->unsignedBigInteger('client_id');
         
            $table->unsignedBigInteger('user_id');
        

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
        Schema::dropIfExists('os');
    }
}
