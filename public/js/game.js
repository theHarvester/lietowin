var queueUrl = window.urlPathPrefix + "/api/v1/queue";
var gameUrl = window.urlPathPrefix + "/api/v1/game";
var moveUrl = window.urlPathPrefix + "/api/v1/game/move";
var lastDiceUrl = window.urlPathPrefix + "/api/v1/game/dice";
var currentRound = 1;
var lastCelebratedRound = 0;
var playerOrder;
var renderPlayers = false;

// ensures the round over animation is only done once
var toggleRoundOver = false;

var isNewRound = true;

$(document).ready(function(){
	$('#turnForm').hide();
	$('#turnForm form').submit(function(){
        makeMove(this);
		return false;
	});

    $('.white_content .exit').click(function(){
        $(this).parent('.white_content').hide();
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
				  	},
				  	dataType: "json"
				});
	        }
    	}, dataType: "json"
	});
}, 2000);

function makeMove(form){
    $('#turnForm').hide();
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
    if(gameState.round !== 'undefinded'){
        updateRound(gameState.round);
    }

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

    if(gameState.lastRoundEnd !== 'undefinded'){
        roundEnd(gameState.lastRoundEnd);
    }
    isNewRound = false;
}

function updateDiceAvailable(diceAvailable){
    if(isNewRound){
        renderPlayers = false;
        // we need to start with the player logged in
        // so we loop to that player and start drawing
        // and then loop back at the start until we hit
        // the player again
        loopDiceAvailable(diceAvailable);
        loopDiceAvailable(diceAvailable);
    }

}

function loopDiceAvailable(diceAvailable){
    for (var i in window.playerOrder){
        var player = window.playerOrder[i];
        if(player == username){
            if(renderPlayers)
                renderPlayers = false;
            else
                renderPlayers = true;
        } else if (renderPlayers){
            if($('#diceAvailable .p_'+player).length == 0){
                // draw for the first time
                var elementToAppend = '<div class="p_'+player+'">';
                elementToAppend += '<div class="username">'
                elementToAppend += player;
                elementToAppend += '</div>';
                elementToAppend += '<div class="opponentsDice">&nbsp;</div>';
                elementToAppend += '</div>';
                $('#diceAvailable').append(elementToAppend);

                $('#diceAvailable .p_'+player+' .opponentsDice').empty();

                for(var i = 0; i < parseInt(diceAvailable[player]); i++){
                    $('#diceAvailable .p_'+player+' .opponentsDice').append(getDieByInt('?'));
                };
            }
            else {
                // update player dice
                $('#diceAvailable .p_'+player+' .opponentsDice').empty();

                for(var i = 0; i < parseInt(diceAvailable[player]); i++){
                    $('#diceAvailable .p_'+player+' .opponentsDice').append(getDieByInt('?'));
                };
            }
        }
    }
}

function updatePlayersTurn(player){
	if(player == username){
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
    if(isNewRound){
        $('#myDice').empty();

        $(dice).each(function(){

            $('#myDice').append(getDieByInt(this[0]));
        });
    }
}

function currentlyQueued(isQueued){
    if(isQueued){
        $('#currentlyQueued').show();
    } else {
        $('#currentlyQueued').hide();
    }
}

function updateRound(round){
    if(round != currentRound){
        toggleRoundOver = true;
        isNewRound = true;
    }
    currentRound = round;
}

function roundEnd(lastRound){
    if(currentRound > 1 && toggleRoundOver){
        if(currentRound - 2 >= lastCelebratedRound){
            //the round is definitely over
            $('#moveHistory').empty();

            $('#roundResult .content').empty();
            $('#roundResult').show();
            $.ajax({
                url: lastDiceUrl,
                success: function(data){
                    if(data.previousDice != 'undefined'){
                        revealDice(data.previousDice);
                    }

                }, dataType: "json"
            });

            if(lastRound.player == lastRound.loser){
                $('#roundResult .content').html('<div id="revealHeading">' + lastRound.loser + ' called ' + lastRound.call + ' and lost</div>');
            } else {
                $('#roundResult .content').html('<div id="revealHeading">' + lastRound.player + ' called ' + lastRound.call + ' on ' + lastRound.loser + ' and won</div>');
            }

            toggleRoundOver = false;
            $('#roundResult').delay(20000).hide(1000);
        }
    }
}

function revealDice(dice){
    var appendMe = '<div id="revealLastDice">';
    $(dice).each(function(){
        appendMe += '<div class="revealUsername">';
        if(this.username == username){
            appendMe += 'You';
        } else {
            appendMe += this.username
        }
        appendMe += '</div> <div class="diceRow">';
        $(this.dice).each(function(){
            appendMe += getDieByInt(this[0]).html() + " ";
            console.log(getDieByInt(this[0]).html());
        });
        appendMe += "</div>";
    });
    appendMe += '</div>';
    $('#roundResult .content').append(appendMe);
}


function getDieByInt(dieNumber){
    var die = null;
    switch (dieNumber)
    {
        case "1":
            die = $('#dice1').clone();
            break;
        case "2":
            die = $('#dice2').clone();
            break;
        case "3":
            die = $('#dice3').clone();
            break;
        case "4":
            die = $('#dice4').clone();
            break;
        case "5":
            die = $('#dice5').clone();
            break;
        case "6":
            die = $('#dice6').clone();
            break;
        default :
            die = $('#emptyDice').clone();
            $(die).children('.textContent').text(dieNumber);
            break;
    }
    die.removeAttr('id');

    return die;
}
