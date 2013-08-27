<?php

use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('relationships', function($table)
		{
			$table->increments('id');
			$table->string('user_id');
			$table->string('organization_id');
			$table->string('relationship_id');
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
		Schema::drop('relationships');
	}

}