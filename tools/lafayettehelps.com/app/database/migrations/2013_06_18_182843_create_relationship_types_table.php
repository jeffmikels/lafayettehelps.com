<?php

use Illuminate\Database\Migrations\Migration;

class CreateRelationshipTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// relationship types should be "working with" "administrator of" or "member of"
		Schema::create('relationship_types', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description')->default('');
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
		Schema::drop('relationship_types');
	}

}