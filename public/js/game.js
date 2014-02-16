var queueUrl = window.urlPathPrefix + "/api/v1/queue";
var gameUrl = window.urlPathPrefix + "/api/v1/game";
var moveUrl = window.urlPathPrefix + "/api/v1/game/move";
var currentRound = 1;
var lastCelebratedRound = 0;
var playerOrder;

$(document).ready(function(){
	$('#turnForm').hide();
	$('#turnForm').submit(function(){
        makeMove(this);
		return false;
	});
});

setInterval(function(){
    $.ajax({ 
    	url: queueUrl, 
    	success: function(data){
            if(data.queued == true){
                currentlyQueued(true);
            } else if(data.game_id !== 'undefinded'){
                currentlyQueued(false);
	        	$.ajax({
				  	url: gameUrl,
				  	success: function(game){
				  		updateGame(game);
//				  		console.log(game);
				  	},
				  	dataType: "json"
				});
	        }
    	}, dataType: "json"
	});
}, 5000);

function makeMove(form){
    $.ajax({
        url: moveUrl,
        type: "POST",
        data: $(form).serialize(),
        success: function(data){
            console.log(data);
        }, dataType: "json"
    });
}

function updateGame(gameState){
	if(gameState.playerOrder !== 'undefinded'){
		updatePlayerOrder(gameState.playerOrder);
	}

	if(gameState.diceAvailable !== 'undefinded'){
		updateDiceAvailable(gameState.diceAvailable);
	}

	if(gameState.playersTurn !== 'undefinded'){
		updatePlayersTurn(gameState.playersTurn);
	}

    if(gameState.myDice !== 'undefinded'){
        updateMyDice(gameState.myDice);
    }
	
	if(gameState.moves !== 'undefinded'){
		updateMoves(gameState.moves);
	}

    if(gameState.round !== 'undefinded'){
        updateRound(gameState.round);
    }

    if(currentRound > 1){
//        console.log(currentRound - 2 >= lastCelebratedRound, gameState.lastRoundEnd);
        if(currentRound - 2 >= lastCelebratedRound && gameState.lastRoundEnd){
            roundEnd(gameState.lastRoundEnd);
        }
    }

//    console.log(currentRound );
}

function updateDiceAvailable(diceAvailable){
	for (var i in window.playerOrder){
		var player = window.playerOrder[i];

		if($('#diceAvailable .'+player).length == 0){
			// draw for the first time
			var elementToAppend = '<div class="'+player+'">';
			elementToAppend += player;
			elementToAppend += ' has '
			elementToAppend += diceAvailable[player];
			elementToAppend += ' dice left';
			elementToAppend += '</div>';
			$('#diceAvailable').append(elementToAppend);
		}
		else {
			// update player dice
		}
	}

}

function updatePlayersTurn(player){
	if(player == window.username){
		// it's your turn
		$('#turnForm').show();
	} else {
		$('#turnForm').hide();
	}
}



function updatePlayerOrder(playerOrder){
	window.playerOrder = playerOrder;
}

function updateMoves(moves){
	for (var i in moves){
		if($('#moveHistory .'+i).length == 0){
			// draw for the first time
			var elementToAppend = '<div class="'+i+'">';
			elementToAppend += moves[i].username;
			if(moves[i].call == "raise"){
				elementToAppend += ' has bet ';
				elementToAppend += moves[i].amount + ' ' + moves[i].diceFace + 's';
			}
			elementToAppend += '</div>';
			$('#moveHistory').append(elementToAppend);
		}
	}
}

function updateMyDice(dice){
    var message = "You rolled ";
    $(dice).each(function(){
        message += this[0] + " ";
    });
    $('#myDice').text(message);
}

function currentlyQueued(isQueued){
    if(isQueued){
        $('#currentlyQueued').show();
    } else {
        $('#currentlyQueued').hide();
    }
}

function updateRound(round){
    window.currentRound = round;
}

function roundEnd(lastRound){
//    console.log(lastRound);
    if(lastRound.player == lastRound.loser){
        $('#roundResult').text(lastRound.loser + ' called ' + lastRound.call + ' and lost.');
    } else {
        $('#roundResult').text(lastRound.player + ' called ' + lastRound.call + ' on ' + lastRound.loser + ' and won.');
    }
}

// function submitMove()
