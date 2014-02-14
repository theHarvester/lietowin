<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ExtendGamesTabe extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('games', function($table)
		{
		    $table->integer('user_turn');
		    $table->string('turn_order');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('games', function($table)
		{
		    $table->dropColumn('user_turn');
		    $table->dropColumn('turn_order');
		});
	}

}
