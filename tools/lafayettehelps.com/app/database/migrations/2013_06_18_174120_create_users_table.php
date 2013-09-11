<?php
// Users Table


use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('username')->unique();
			$table->string('password');
			$table->string('email')->unique();
			$table->string('first_name');
			$table->string('last_name');
			$table->string('phone');
			$table->string('address');
			$table->string('city');
			$table->string('state');
			$table->string('zip');
			$table->integer('reputation')->default(0);
			$table->string('status')->default('unverified');
			$table->string('role')->default('user'); // administrator, editor, user
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
		Schema::drop('users');
	}

}