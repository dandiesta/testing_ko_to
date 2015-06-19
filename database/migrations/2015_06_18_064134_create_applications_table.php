<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema;

class CreateApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('applications', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dev_id')->references('id')->on('developers');
            $table->string('title');
            $table->string('api_key')->unique();
            $table->string('icon_key')->nullable()->default(NULL);
            $table->longText('description')->nullable()->default(NULL);
            $table->longText('repository')->nullable()->default(NULL);
            $table->timestamp('last_upload')->nullable()->default(NULL);
            $table->timestamp('last_commented')->nullable()->default(NULL);
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
        Schema::drop('applications');
	}

}
