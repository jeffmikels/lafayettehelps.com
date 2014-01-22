<?php

use Illuminate\Database\Migrations\Migration;

class CreateRecommendationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recommendations', function($table)
		{
			$table->increments('id');
			$table->integer('contributed_by'); // user id
			$table->integer('contributed_for'); // user id
			$table->text('body');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recommendations');
	}

}
