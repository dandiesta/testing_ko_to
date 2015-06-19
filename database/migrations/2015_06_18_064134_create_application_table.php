<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('application', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('api_key')->unique();
            $table->string('icon_key')->nullable()->default(NULL);
            $table->longText('description')->nullable()->default(NULL);
            $table->longText('repository')->nullable()->default(NULL);
            $table->timestamp('last_upload')->nullable()->default(NULL);
            $table->timestamp('last_commented')->nullable()->default(NULL);
            $table->timestamp('date_to_sort');
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
        Schema::drop('application');
	}

}
