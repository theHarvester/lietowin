var queueUrl = window.urlPathPrefix + "/api/v1/queue";
var gameUrl = window.urlPathPrefix + "/api/v1/game";
var moveUrl = window.urlPathPrefix + "/api/v1/game/move";
var playerOrder;
console.log('here');

$(document).ready(function(){
	$('#turnForm').hide();
	$('#turnForm').submit(function(){
//		console.log();
        $.ajax({
            url: moveUrl,
            type: "POST",
            data: $(this).serialize(),
            success: function(data){
                console.log(data);
            }, dataType: "json"
        });

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
				  		// console.log(game);
				  	},
				  	dataType: "json"
				});
	        }
    	}, dataType: "json"
	});
}, 5000);


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
	
	if(gameState.moves !== 'undefinded'){
		updateMoves(gameState.moves);
	}
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

function currentlyQueued(isQueued){
    if(isQueued){
        $('#currentlyQueued').show();
    } else {
        $('#currentlyQueued').hide();
    }
}

// function submitMove()
