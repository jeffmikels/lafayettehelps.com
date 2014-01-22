<?php

use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function($table)
		{
			$table->increments('id');
			$table->string('commentable_type'); // requests, offers
			$table->integer('commentable_id');
			$table->integer('user_id'); // user_id
			$table->text('comment');
			$table->string('status')->default('unapproved'); // approved or unapproved
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
		Schema::drop('comments');
	}

}
