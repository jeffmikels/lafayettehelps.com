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
			$table->integer('on_behalf_of'); // organization id
			$table->string('object_type'); // users, requests, offers
			$table->integer('object_id');
			$table->text('notes');
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