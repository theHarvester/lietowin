<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMovesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moves', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id');
			$table->integer('user_id');
			$table->enum('call',  array('lie', 'perfect', 'raise'));
			$table->integer('amount');
			$table->integer('dice_number');
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
		Schema::drop('moves');
	}

}
