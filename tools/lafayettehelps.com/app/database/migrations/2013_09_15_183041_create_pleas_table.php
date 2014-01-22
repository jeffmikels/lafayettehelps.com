<?php
// Requests Table


use Illuminate\Database\Migrations\Migration;

class CreatePleasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pleas', function($table)
		{
			$table->increments('id');
			$table->integer('user_id'); // id of the author of this plea
			$table->string('summary');
			$table->text('details');
			$table->float('dollars')->default(0);
			$table->string('alternatives')->default('');
			$table->date('deadline');
			$table->string('status')->default('unverified'); // unverified, verified
			$table->integer('verified_by')->nullable(); // organization id that verified this need
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
		Schema::drop('requests');
	}

}
