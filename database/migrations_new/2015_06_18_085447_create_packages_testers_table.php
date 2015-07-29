<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTestersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('package_tester', function(Blueprint $table)
        {
            $table->increments('id');
            $table->int('package_id')->references('id')->on('packages');
            $table->int('tester_id')->references('id')->on('tester');
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
        Schema::drop('package_tester');
	}

}
