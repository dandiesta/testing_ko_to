<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('comment', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('app_id')->references('id')->on('application');
            $table->integer('package_id')->references('id')->on('package');
            $table->integer('number');
            $table->string('mail');
            $table->longText('message');
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
        Schema::drop('comment');
	}

}
