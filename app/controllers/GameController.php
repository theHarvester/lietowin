<?php

class GameController extends BaseController
{

    /**
     * Returns current state of game
     *
     * @return Response
     */
    public function index()
    {
        $gameId = Session::get('game_id');
        $userId = Auth::user()->id;
        $timeoutLimit = 120;

        if ($gameId != null) {
            $game = Game::find($gameId);
            if ($game->active == true) {
                $turnOrder = array();

                $gamePlayers = GamePlayer::where('game_id', '=', $gameId)
                    ->where('still_playing', '=', true)
                    ->get();
                $currentPlayers = array();
                foreach ($gamePlayers as $player) {
                    $currentPlayers[] = $player->user_id;
                }

                $playersTurn = '';
                foreach (explode(',', $game['turn_order']) as $playerId) {
                    if (in_array($playerId, $currentPlayers)) {
                        $user = User::find($playerId);
                        $turnOrder[] = $user['username'];
                        if ($game->user_turn == $playerId) {
                            $playersTurn = $user['username'];
                        }
                    }
                }

                $columns = array(
                    DB::raw('moves.id as `move_id`'),
                    DB::raw('moves.call as `move_call`'),
                    DB::raw('moves.dice_number as `move_dice_num`'),
                    DB::raw('moves.amount as `move_amount`'),
                    DB::raw('moves.created_at as `move_ts`'),
                    DB::raw('users.username as `username`')
                );

                // get all moves
                $displayMoves = array();
                $moves = Moves::where('game_id', $gameId)
                    ->join('users', 'users.id', '=', 'moves.user_id')
                    ->where('round', $game->current_round)
                    ->get($columns);

                if (count($moves)) {
                    foreach ($moves as $move) {
                        $secondsSince = time() - strtotime($move->move_ts);
                        $displayMoves[$move->move_id] = array(
                            'call' => $move->move_call,
                            'username' => $move->username,
                            'diceFace' => $move->move_dice_num,
                            'amount' => $move->move_amount,
                            'seconds_since' => $secondsSince
                        );
                    }
                } else {
                    // this is a brand new round
                    $lastMove = Moves::where('round', '=', $game->current_round - 1)
                        ->where('game_id', $gameId)
                        ->orderBy('created_at', 'DESC')
                        ->first();

                    if(isset($lastMove)){
                        $secondsSince = time() - strtotime((string)$lastMove->created_at);
                    } else {
                        $secondsSince = time() - strtotime((string)$game->created_at);
                    }
                }

                // get current dice face
                $myDice = Dice::where('game_id', $gameId)
                    ->where('round', $game->current_round)
                    ->where('user_id', $userId)
                    ->first();

                $myDiceFace = explode(',', $myDice['dice_face']);

                // get all players amount of dice
                $diceAvailable = Dice::where('game_id', $gameId)
                    ->join('users', 'users.id', '=', 'dice.user_id')
                    ->where('round', $game->current_round)
                    ->get()
                    ->toArray();

                $diceAvailableArr = array();
                foreach ($diceAvailable as $value) {
                    $diceAvailableArr[$value['username']] = $value['dice_available'];
                }

                $lastRoundEnd = array();
                if ($game->current_round > 1) {
                    $lastRoundEndObj = Moves::where('game_id', $gameId)
                        ->join('users', 'users.id', '=', 'moves.user_id')
                        ->where('round', $game->current_round - 1)
                        ->whereIn('call', array('perfect', 'lie', 'timeout'))
                        ->first()
                        ->toArray();

                    $lastRoundEnd['player'] = $lastRoundEndObj['username'];
                    $lastRoundEnd['call'] = $lastRoundEndObj['call'];
                    $lastRoundEnd['diceFace'] = $lastRoundEndObj['dice_number'];
                    $lastRoundEnd['amount'] = $lastRoundEndObj['amount'];
                    $lastRoundEnd['round'] = $lastRoundEndObj['round'];
                    $lastRoundEnd['loser'] = User::find($lastRoundEndObj['loser_id'])->toArray()['username'];
                }

                // detect player timeout
                if(isset($secondsSince) && $secondsSince > $timeoutLimit) {
                    $move = new Moves;
                    $move->game_id = $gameId;
                    $move->user_id = $game->user_turn;
                    $move->call = 'timeout';
                    $move->amount = 0;
                    $move->dice_number = 0;
                    $move->loser_id = $game->user_turn;
                    $move->round = $game->current_round;
                    $move->save();
                    $this->endRound($gameId, $game->user_turn, $diceAvailable);
                }

                $lostPlayers = GamePlayer::where('game_id', '=', $gameId)
                    ->join('users', 'users.id', '=', 'game_player.user_id')
                    ->where('still_playing', '=', false)
                    ->get();
                $losers = array();
                foreach ($lostPlayers as $loser) {
                    $losers[] = $loser->username;
                }

                return Response::json(array(
                        'error' => false,
                        'secondsElapsed' => $secondsSince,
                        'myDice' => $myDiceFace,
                        'diceAvailable' => $diceAvailableArr,
                        'playersTurn' => $playersTurn,
                        'playerOrder' => $turnOrder,
                        'losers' => $losers,
                        'round' => $game->current_round,
                        'lastRoundEnd' => $lastRoundEnd,
                        'moves' => $displayMoves
                    ),
                    200
                );

            } else {
                //game's over
                $lostPlayers = GamePlayer::where('game_id', '=', $gameId)
                    ->join('users', 'users.id', '=', 'game_player.user_id')
                    ->where('still_playing', '=', false)
                    ->get();
                $losers = array();
                foreach ($lostPlayers as $loser) {
                    $losers[] = $loser->username;
                }

                $winner = User::find($game->winner_id);

                return Response::json(array(
                        'error' => false,
                        'winner' => $winner->username,
                        'losers' => $losers
                    ),
                    200
                );
            }
        }


    }

    /**
     * Returns current state of game
     *
     * @return Response
     */
    public function previousRound()
    {

        $gameId = Session::get('game_id');
        $userId = Auth::user()->id;

        if ($gameId != null) {
            $game = Game::find($gameId)->toArray();
            if ($game['current_round'] > 1) {
                $dice = Dice::join('users', 'users.id', '=', 'dice.user_id')
                    ->where('game_id', $gameId)
                    ->where('round', $game['current_round'] - 1)
                    ->get()
                    ->toArray();

                $previousDice = array();
                foreach ($dice as $id => $die) {
                    $previousDice[$id]['username'] = $die['username'];
                    $previousDice[$id]['dice'] = explode(',', $die['dice_face']);
                }

                $lastBets = Moves::join('users', 'users.id', '=', 'moves.user_id')
                    ->where('game_id', '=', $gameId)
                    ->where('round', '=', $game['current_round'] - 1)
                    ->where('call', '=', 'raise')
                    ->orderBy('moves.created_at', 'DESC')
                    ->first();

                $lastBet = array(
                    'username' => $lastBets->username,
                    'dice_number' => $lastBets->dice_number,
                    'amount' => $lastBets->amount
                );

                return Response::json(array(
                        'error' => false,
                        'previousDice' => $previousDice,
                        'lastBet' => $lastBet
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

            if ($game['active'] == true && $game['user_turn'] == $userId) {
                //make the move

                $lastRaise = Moves::where('game_id', $gameId)
                    ->where('round', $game['current_round'])
                    ->orderBy('created_at', 'DESC')
                    ->first();

                $diceAvailable = Dice::where('game_id', $gameId)
                    ->where('round', $game['current_round'])
                    ->get()
                    ->toArray();


                $turnOrder = explode(',', $game['turn_order']);
                $turnKey = array_search($userId, $turnOrder);
                If ((count($turnOrder) - 1) == $turnKey) {
                    $nextPlayerId = $turnOrder[0];
                } else {
                    $nextPlayerId = $turnOrder[$turnKey + 1];
                }

                $gamePlayers = GamePlayer::where('game_id', '=', $gameId)
                    ->where('still_playing', '=', true)
                    ->get();
                $activePlayers = array();
                $seekNextActive = true;

                foreach ($gamePlayers as $player) {
                    if ($nextPlayerId == $player->user_id) {
                        $seekNextActive = false;
                    }
                    $activePlayers[] = $player->user_id;
                }

                $startLooking = false;

                // this has to be a while because it needs to start searching in turn order so it needs to loop
                while ($seekNextActive) {
                    foreach ($turnOrder as $userTurnId) {
                        // var_dump($userTurnId, $startLooking, '--------------');
                        if ($startLooking) {
                            if (in_array($userTurnId, $activePlayers)) {
                                $seekNextActive = false;
                                $nextPlayerId = $userTurnId;
                                break;
                            }
                        } else {
                            if ($userTurnId == $nextPlayerId) {
                                $startLooking = true;
                            }
                        }
                    }
                }

                switch (Input::get('call')) {
                    case 'raise':

                        $makeMove = false;

                        $amount = (int)Input::get('amount');

                        $totalDiceAvailable = 0;
                        foreach ($diceAvailable as $value) {
                            $totalDiceAvailable += (int)$value["dice_available"];
                        }

                        $diceNum = (int)Input::get('dice_number');
                        if ($diceNum >= 1 && $diceNum <= 6) {

                            if ($amount >= 1 && $amount <= $totalDiceAvailable) {
                                if ($lastRaise == null) {
                                    // this is the first move
                                    $makeMove = true;
                                } else {

                                    if ($amount == $lastRaise['amount']
                                        && $diceNum > $lastRaise['dice_number']
                                    ) {
                                        $makeMove = true;
                                    } else if ($amount > $lastRaise['amount']) {
                                        $makeMove = true;
                                    } else {
                                        $errMsg = 'You need to bet higher dice with the same amount or higher amount with any dice number';
                                    }
                                }
                            }
                        }

                        if ($makeMove) {
                            //valid call
                            $myMove = new Moves;
                            $myMove->game_id = $gameId;
                            $myMove->user_id = $userId;
                            $myMove->call = 'raise';
                            $myMove->dice_number = $diceNum;
                            $myMove->amount = $amount;
                            $myMove->round = $game['current_round'];
                            $myMove->save();

                            $gameObj->user_turn = $nextPlayerId;
                            $gameObj->save();

                            // output success
                        }

                        break;
                    case 'perfect':
                    case 'lie':
                        $diceTotals = array();
                        for ($i = 1; $i <= 6; $i++) {
                            $diceTotals[$i] = 0;
                        }
                        foreach ($diceAvailable as $value) {
                            foreach (explode(',', $value['dice_face']) as $diceFace) {
                                if (array_key_exists($diceFace, $diceTotals)) {
                                    $diceTotals[$diceFace] = $diceTotals[$diceFace] + 1;
                                }
                            }
                        }

                        $lastRaise = Moves::where('game_id', $gameId)
                            ->where('round', $game['current_round'])
                            ->orderBy('id', 'DESC')
                            ->first()
                            ->toArray();

                        if (Input::get('call') == 'perfect') {
                            if ($diceTotals[(int)$lastRaise['dice_number']] == (int)$lastRaise['amount']) {
                                // this player wins the round, the last player loses
                                $playerLostId = $lastRaise['user_id'];

                            } else {
                                // this player loses the round
                                $playerLostId = $userId;
                            }
                        } else if (Input::get('call') == 'lie') {
                            //if total 3s is more than the last bet
                            if ($diceTotals[(int)$lastRaise['dice_number']] < (int)$lastRaise['amount']) {
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
                        $move->loser_id = $playerLostId;
                        $move->save();

                        $this->endRound($gameId, $playerLostId, $diceAvailable);

                        $playerLost = User::find((int)$playerLostId);

                        $displayArr['diceTotals'] = $diceTotals;
                        $displayArr['playerLost'] = $playerLost['username'];

                        $gameObj->user_turn = $nextPlayerId;
                        $gameObj->save();

                        break;
                }
            }
        } else {
            // it's not your turn fuck head

        }
    }

    private function endRound($gameId, $playerLoseId, $diceAvailable)
    {
        $gameObj = Game::find($gameId);
        $gameArr = $gameObj->toArray();
        $lastRoundId = (int)$gameArr['current_round'];
        $currendRoundId = $lastRoundId + 1;
        $gameObj->current_round = $currendRoundId;
        $gameObj->save();

        foreach ($diceAvailable as $value) {
            if ($value['user_id'] == $playerLoseId) {
                $playerDiceLeft = $value['dice_available'] - 1;
                if ($playerDiceLeft <= 0) {
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
            for ($i = 0; $i < $playerDiceLeft; $i++) {
                $diceFace[] = rand(1, 6);
            }
            $diceRoll->dice_face = implode(",", $diceFace);
            $diceRoll->save();
        }
        return true;
    }

    private function playerLosesGame($loserId)
    {
        $gameId = Session::get('game_id');
        $player = GamePlayer::where('game_id', '=', $gameId)
            ->where('user_id', '=', $loserId)
            ->first();
        $player->still_playing = false;
        $player->save();

        $activePlayers = GamePlayer::where('game_id', '=', $gameId)
            ->where('still_playing', '=', true)
            ->get();
        var_dump('WE GOT A loser OVER HERE', $loserId, $activePlayers->toArray());
        if (count($activePlayers) == 1) {
            foreach ($activePlayers as $winningPlayer) {
                $this->declareWinner($winningPlayer->user_id);
            }
        }

        return true;
    }

    private function declareWinner($winnerId)
    {
        $gameId = Session::get('game_id');
        $game = Game::find($gameId);
        $game->winner_id = $winnerId;
        $game->active = false;
        $game->save();
    }

}
