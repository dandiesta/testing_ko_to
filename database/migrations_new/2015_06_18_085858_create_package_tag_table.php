<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageTagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('package_tag', function(Blueprint $table)
        {
            $table->increments('id');
            $table->int('package_id')->references('id')->on('packages');
            $table->int('tag_id')->references('id')->on('tag');
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
        Schema::drop('package_tag');
	}

}