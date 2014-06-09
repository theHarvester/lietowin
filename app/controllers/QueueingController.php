<?php

class QueueingController extends BaseController {

	public function index()
	{
		$userId = Auth::user()->id;
		// $userId = 2;

        $queuedPlayers = Queueing::where('queued', true)
            ->get();

		//Check if in queue
		$queueing = Queueing::where('user_id', $userId)
			->where('queued', true)
			->first();
		if(!empty($queueing)){
			return Response::json(array(
			        'error' => false,
                    'queued_count' => count($queuedPlayers),
			        'queued' => true),
			        200
			    );
		}

		//Check if in game
		$player = GamePlayer::where('user_id', $userId)
			->orderBy('created_at', 'DESC')
			->first();

		if(!empty($player)){
			$playerArr = $player->toArray();

			if(!empty($playerArr)){
				$lastGame = Game::find($playerArr['game_id']);
				
				if(array_key_exists('active', $lastGame->toArray()) 
					&& $lastGame->toArray()['active'] == true) {

					Session::put('game_id', $playerArr['game_id']);
					return Response::json(array(
				        'error' => false,
				        'game_id' => $playerArr['game_id']
				        ),
				        200
				    );
				}
			}
		}

		//Join the queue
	 	$queue = new Queueing;
	    $queue->user_id = Auth::user()->id;
	    $queue->queued = true;
	    $queue->save();
	 
	    return Response::json(array(
	        'error' => false,
            'queued_count' => count($queuedPlayers),
	        'queued' => true
            ),
	        200
	    );
	    
	}

    public function count()
    {
        $queuedPlayers = Queueing::where('queued', true)
            ->get();

        return Response::json(array(
                'queued_count' => count($queuedPlayers)
            ),
            200
        );
    }
}
