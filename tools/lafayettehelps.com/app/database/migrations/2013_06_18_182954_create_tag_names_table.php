<?php
// contains the tag terms
// the table linking tags to objects is
// called simply `tags`

use Illuminate\Database\Migrations\Migration;

class CreateTagNamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tag_names', function($table)
		{
			$table->increments('id');
			$table->string('name');
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
		Schema::drop('tag_names');
	}

}