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
			$table->integer('pledged_by'); // user id
			$table->integer('pledged_for'); // request id
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