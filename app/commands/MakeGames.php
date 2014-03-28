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

		if($queuedCount > 2){

            $queueArr = $queueing->toArray();

            $counter = 0;
            $nextSequence = 0;
            $offset = 0;
            do {
                if($queuedCount % 5 == 0){
                    // divide by 5
                    $nextSequence = 5;
                } elseif($queuedCount % 4 == 0){
                    // divide by 4
                    $nextSequence = 4;
                } elseif($queuedCount % 3 == 0){
                    // divide by 3
                    $nextSequence = 3;
                } elseif($queuedCount > 5) {
                    // once by 5 and re-evaluate
                    $nextSequence = 5;
                }

                if(($queuedCount - $nextSequence) > 2){
                    $doProcess = true;
                } else {
                    $doProcess = false;
                }

                $game = new Game;
                $game->active = true;
                $game->current_round = 1;
                $game->save();
                $userArr = array();

                for($i = (0 + $offset); $i < ($nextSequence + $offset); $i++){
                    $player = new GamePlayer;
                    $player->user_id = $queueArr[$i]['user_id'];
                    $player->game_id = $game->id;
                    $player->save();

                    $userArr[] = $queueArr[$i]['user_id'];

                    //Unque the current player
                    $unqueuePlayer = Queueing::where('user_id', $queueArr[$i]['user_id'])
                        ->where('queued', true)
                        ->first();
                    $unqueuePlayer->queued = false;
                    $unqueuePlayer->save();

                    //Roll the dice for the current player
                    $diceRoll = new Dice;
                    $diceRoll->game_id = $game->id;
                    $diceRoll->user_id = $queueArr[$i]['user_id'];

                    $diceFace = array();
                    for($j = 0; $j < 5; $j ++){
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

                $offset += $nextSequence;
            } while ($doProcess);
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
