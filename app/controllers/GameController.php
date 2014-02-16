<?php

class GameController extends BaseController {

	/**
	 * Returns current state of game
	 *
	 * @return Response
	 */
	public function index()
	{
		$gameId = Session::get('game_id');
		$userId = Auth::user()->id;

		if ($gameId != null) {
			$game = Game::find($gameId)->toArray();
			if($game['active'] == true){
				$turnOrder = array();

				foreach (explode(',', $game['turn_order']) as $playerId) {
					$user = User::find($playerId);
					$turnOrder[] = $user['username'];
					if($game['user_turn'] == $playerId){
						$playersTurn = $user['username'];
					}
				}


				// get all moves
                $displayMoves = array();
				$moves = Moves::where('game_id', $gameId)
					->join('users', 'users.id', '=', 'moves.user_id')
					->where('round', $game['current_round'])
					->get()
					->toArray();

				if(count($moves)){
					foreach ($moves as $move) {
						$displayMoves[$move['move_guid']] = array(
							'call' => $move['call'],
							'username' => $move['username'],
							'diceFace' => $move['dice_number'],
							'amount' => $move['amount']
						);
					}
				} else {
					// this is a brand new round
				}

				// get current dice face
				$myDice = Dice::where('game_id', $gameId)
					->where('round', $game['current_round'])
					->where('user_id', $userId)
					->first();

				$myDiceFace = explode(',', $myDice['dice_face']);

				// get all players amount of dice
				$diceAvailable = Dice::where('game_id', $gameId)
					->join('users', 'users.id', '=', 'dice.user_id')
					->where('round', $game['current_round'])
					->get()
					->toArray();

				$diceAvailableArr = array();
				foreach ($diceAvailable as $value) {
					$diceAvailableArr[$value['username']] = $value['dice_available'];
				}

                $lastRoundEnd = array();
                if($game['current_round'] > 1){
                    $lastRoundEndObj = Moves::where('game_id', $gameId)
                        ->join('users', 'users.id', '=', 'moves.user_id')
                        ->where('round', $game['current_round']-1)
                        ->whereIn('call', array('perfect','lie'))
                        ->first()
                        ->toArray();

                    $lastRoundEnd['player'] = $lastRoundEndObj['username'];
                    $lastRoundEnd['call'] = $lastRoundEndObj['call'];
                    $lastRoundEnd['diceFace'] = $lastRoundEndObj['dice_number'];
                    $lastRoundEnd['amount'] = $lastRoundEndObj['amount'];
                    $lastRoundEnd['round'] = $lastRoundEndObj['round'];
                    $lastRoundEnd['loser'] = User::find($lastRoundEndObj['loser_id'])->toArray()['username'];
                }

				return Response::json(array(
			        'error' => false,
			        'myDice' => $myDiceFace,
			        'diceAvailable' => $diceAvailableArr,
			        'playersTurn' => $playersTurn,
			        'playerOrder' => $turnOrder,
                    'round' => $game['current_round'],
                    'lastRoundEnd' => $lastRoundEnd,
			        'moves' => $displayMoves
			        ),
			        200
			    );

			} else {
				//game's over
			}
       	}
	}

    /**
     * Returns current state of game
     *
     * @return Response
     */
    public function previousRound() {

        $gameId = Session::get('game_id');
        $userId = Auth::user()->id;

        if ($gameId != null) {
            $game = Game::find($gameId)->toArray();
            if($game['current_round'] > 1){
                $dice = Dice::join('users', 'users.id', '=', 'dice.user_id')
                    ->where('game_id', $gameId)
                    ->where('round', $game['current_round']-1)
                    ->get()
                    ->toArray();

                $previousDice = array();
                foreach($dice as $id => $die){
                    $previousDice[$id]['username'] = $die['username'];
                    $previousDice[$id]['dice'] = explode(',',$die['dice_face']);
                }

                return Response::json(array(
                        'error' => false,
                        'previousDice' => $previousDice
                    ),
                    200
                );

            }
        }
    }

	/**
	 * Make Move
	 *
	 * @return Response
	 */
	public function move()
	{
		$gameId = Session::get('game_id');
		$userId = Auth::user()->id;
		$displayArr = array();

		if ($gameId != null) {
			$game = Game::find($gameId)->toArray();
			$gameObj = Game::find($gameId);

			if($game['active'] == true && $game['user_turn'] == $userId){
				//make the move

				$lastRaise = Moves::where('game_id', $gameId)
					->where('round', $game['current_round'])
					->orderBy('created_at', 'DESC')
					->first();

				$diceAvailable = Dice::where('game_id', $gameId)
					->where('round', $game['current_round'])
					->get()
					->toArray();

				switch (Input::get('call')) {
					case 'raise':

						$makeMove = false;

						$amount = (int)Input::get('amount');

						$totalDiceAvailable = 0;
						foreach ($diceAvailable as $value) {
							$totalDiceAvailable += (int)$value["dice_available"];
						}

						$diceNum = (int)Input::get('dice_number');
						if($diceNum >= 1 && $diceNum <= 6){

							if($amount >= 1 && $amount <= $totalDiceAvailable){
								if($lastRaise == null){
									// this is the first move
									$makeMove = true;
								} else {

									if( $amount == $lastRaise['amount']
										&& $diceNum > $lastRaise['dice_number']
									){
										$makeMove = true;
									} else if($amount > $lastRaise['amount']){
										$makeMove = true;
									} else {
										$errMsg = 'You need to bet higher dice with the same amount or higher amount with any dice number';
									}
								}
							}
						}

						if($makeMove){
							//valid call
							$myMove = new Moves;
							$myMove->game_id = $gameId;
							$myMove->user_id = $userId;
							$myMove->call = 'raise';
							$myMove->dice_number = $diceNum;
							$myMove->amount = $amount;
							$myMove->round = $game['current_round'];
							$myMove->move_guid = hash('ripemd160', uniqid());
							$myMove->save();

							$turnOrder = explode(',', $game['turn_order']);
							$turnKey = array_search($userId, $turnOrder);
							If((count($turnOrder)-1) == $turnKey){
								$nextPlayerId = $turnOrder[0];
							} else {
								$nextPlayerId = $turnOrder[$turnKey+1];
							}

							$gameObj->user_turn = $nextPlayerId;
							$gameObj->save();

							// output success
						}

						break;
					case 'perfect':
					case 'lie':
						$diceTotals = array();
						for ($i=1; $i <= 6; $i++) { 
							$diceTotals[$i] = 0;
						}
						foreach ($diceAvailable as $value) {
							foreach(explode(',', $value['dice_face']) as $diceFace){
								$diceTotals[$diceFace] = $diceTotals[$diceFace] + 1;
							}
						}

						$lastRaise = Moves::where('game_id', $gameId)
							->where('round', $game['current_round'])
							->orderBy('created_at', 'DESC')
							->first()
							->toArray();

						if(Input::get('call') == 'perfect'){
							if($diceTotals[(int)$lastRaise['dice_number']] == (int)$lastRaise['amount']){
								// this player wins the round, the last player loses
								$playerLostId = $lastRaise['user_id'];

							} else {
								// this player loses the round
								$playerLostId = $userId;
							}
						} else if (Input::get('call') == 'lie')  {
							if($diceTotals[(int)$lastRaise['dice_number']] > (int)$lastRaise['amount']){
								// this player wins the round, the last player loses
								$playerLostId = $lastRaise['user_id'];

							} else {
								// this player loses the round
								$playerLostId = $userId;
							}
						}

                        $move = new Moves;
                        $move->game_id = $gameId;
                        $move->user_id = $userId;
                        $move->call = Input::get('call');
                        $move->round = $game['current_round'];
                        $move->amount = $lastRaise['amount'];
                        $move->dice_number = $lastRaise['dice_number'];
                        $move->move_guid = hash('ripemd160', uniqid());
                        $move->loser_id = $playerLostId;
                        $move->save();

						$this->endRound($gameId, $playerLostId, $diceAvailable);
						$playerLost = User::find((int)$playerLostId);

						$displayArr['diceTotals'] = $diceTotals;
						$displayArr['playerLost'] = $playerLost['username'];
						// $displayArr['']
						break;
				}
			}
		} else {
			// it's not your turn fuck head

		}

//        return Response::json(array(
//                'error' => false,
//                'myDice' => $myDiceFace,
//                'diceAvailable' => $diceAvailableArr,
//                // 'playersTurn' => $game['user_turn'],
//                'playersTurn' => $playersTurn,
//                'playerOrder' => $turnOrder,
//                // 'playerOrder' => explode(',', $game['turn_order']),
//                'moves' => $displayMoves
//            ),
//            200
//        );

	}

	private function endRound($gameId, $playerLoseId, $diceAvailable){
		$gameObj = Game::find($gameId);
		$gameArr = $gameObj->toArray();
		$lastRoundId = (int)$gameArr['current_round'];
		$currendRoundId = $lastRoundId + 1;
		$gameObj->current_round = $currendRoundId;
		$gameObj->save();

		foreach ($diceAvailable as $value) {
			if($value['user_id'] == $playerLoseId){
				$playerDiceLeft = $value['dice_available'] - 1;
				if($playerDiceLeft <= 0){
					$this->playerLosesGame($playerLoseId);
				} 
			} else {
				$playerDiceLeft = $value['dice_available'];
			}

			$diceRoll = new Dice;
			$diceRoll->game_id = $gameId;
			$diceRoll->user_id = $value['user_id'];
			$diceRoll->round = $currendRoundId;
			$diceRoll->dice_available = $playerDiceLeft;
			$diceFace = array();
			for($i = 0; $i < $playerDiceLeft; $i ++){
				$diceFace[] =  rand(1,6);
			}
			$diceRoll->dice_face = implode(",", $diceFace);
			$diceRoll->save();
		}
        return true;
	}

	private function playerLosesGame(){

	}

}
