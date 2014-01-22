<?php

use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history', function($table)
		{
			$table->increments('id');
			$table->string('user_id');
			$table->string('verb');
			$table->string('object_type');
			$table->string('object_id');
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
		Schema::drop('history');
	}


}