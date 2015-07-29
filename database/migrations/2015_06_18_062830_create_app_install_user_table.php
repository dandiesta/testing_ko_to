<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppInstallUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('app_install_user', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('app_id')->references('id')->on('application');
            $table->string('mail')->references('mail')->on('user_pass');
            $table->tinyInteger('notify')->default(true);
            $table->timestamp('last_installed');
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
        Schema::drop('app_install_user');
	}

}
