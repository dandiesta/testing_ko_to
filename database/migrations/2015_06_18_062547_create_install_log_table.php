<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallLogTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('install_log', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('app_id')->references('id')->on('application');
            $table->integer('package_id')->references('id')->on('package');
            $table->string('mail');
            $table->string('user_agent', 1000);
            $table->timestamp('installed');
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
        Schema::drop('install_log');
    }

}
