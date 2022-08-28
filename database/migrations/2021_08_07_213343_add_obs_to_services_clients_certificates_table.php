<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObsToServicesClientsCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('services', function (Blueprint $table) {
            $table->string('obs')->nullable();
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->string('obs')->nullable();
        });
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('obs')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('obs')->nullable();
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('obs')->nullable();
        });
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('obs')->nullable();
        });
    }
}
