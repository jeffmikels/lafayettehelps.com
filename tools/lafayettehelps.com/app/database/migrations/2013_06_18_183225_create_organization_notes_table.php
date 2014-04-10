<?php

use Illuminate\Database\Migrations\Migration;

class CreateOrganizationNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organization_notes', function($table)
		{
			$table->increments('id');
			$table->integer('contributed_by'); // user id
			$table->integer('organization_id'); // organization id
			$table->integer('user_id');
			$table->text('body');
			$table->boolean('red_flag')->default(0);
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
		Schema::drop('organization_notes');
	}

}
