<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dice', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id');
			$table->integer('user_id');
			$table->integer('dice_available');
			$table->string('dice_face');
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
		Schema::drop('dice');
	}

}
