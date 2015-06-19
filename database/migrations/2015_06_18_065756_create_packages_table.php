<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('packages', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('app_id')->references('id')->on('applications') ;
            $table->string('platform', 31);
            $table->string('file_name');
            $table->string('title');
            $table->longText('description');
            $table->string('ios_identifier')->nullable()->default(NULL);
            $table->string('original_file_name')->nullable()->default(NULL);
            $table->integer('file-size')->nullable()->default(NULL);
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
        Schema::drop('packages');
	}

}
