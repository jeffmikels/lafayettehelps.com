<?php

use Illuminate\Database\Migrations\Migration;

class CreatePledgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pledges', function($table)
		{
			$table->increments('id');
			$table->integer('user_id'); // user id
			$table->integer('request_id'); // request id
			$table->float('dollars')->default(0);
			$table->string('alternatives')->default('');
			$table->string('status')->default('uncompleted'); // uncompleted, completed
			$table->integer('status_verified_by')->nullable(); // user id or "0" for automatic verification
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
		Schema::drop('pledges');
	}

}