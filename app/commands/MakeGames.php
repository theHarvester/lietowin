<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeGames extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'game:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates games from queue table';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$queueing = Queueing::where('queued', true)->get();
		$queuedCount = count($queueing->toArray());

		if($queuedCount > 1){
			$game = new Game;
			$game->active = true;
			$game->current_round = 1;
			$game->save();
			$newGame = $game->toArray();
			$userArr = array();
			foreach ($queueing->toArray() as $queue) {
				$player = new GamePlayer;
				$player->user_id = $queue['user_id'];
				$player->game_id = $newGame['id'];
				$player->save();

				$userArr[] = $queue['user_id'];

				//Unque the current player
				$unqueuePlayer = Queueing::where('user_id', $queue['user_id'])
					->where('queued', true)
					->first();
				$unqueuePlayer->queued = false;
				$unqueuePlayer->save();

				//Roll the dice for the current player
				$diceRoll = new Dice;
				$diceRoll->game_id = $newGame['id'];
				$diceRoll->user_id = $queue['user_id'];

				$diceFace = array();
				for($i = 0; $i < 5; $i ++){
					$diceFace[] =  rand(1,6);
				}
				$diceRoll->dice_face = implode(",", $diceFace);
				$diceRoll->round = 1;
				$diceRoll->dice_available = 5;
				$diceRoll->save();
			}

			$game->user_turn = $userArr[array_rand($userArr)];
			$game->turn_order = implode(',', $userArr);
			$game->save();
			
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
