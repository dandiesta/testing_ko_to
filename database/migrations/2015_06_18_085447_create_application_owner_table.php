<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationOwnerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('application_owner', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('app_id')->references('id')->on('application');
            $table->string('owner_email')->references('mail')->on('user_pass');
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
        Schema::drop('application_owner');
	}

}
