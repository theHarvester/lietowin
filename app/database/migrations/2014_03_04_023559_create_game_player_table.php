<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGamePlayerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('game_player', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id');
//            $table->foreign('game_id')->references('id')->on('games');
			$table->integer('user_id');
//            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('still_playing');
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
		Schema::drop('game_player');
	}

}
