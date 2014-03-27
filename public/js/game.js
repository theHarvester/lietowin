var queueUrl = window.urlPathPrefix + "/api/v1/queue";
var gameUrl = window.urlPathPrefix + "/api/v1/game";
var moveUrl = window.urlPathPrefix + "/api/v1/game/move";
var lastDiceUrl = window.urlPathPrefix + "/api/v1/game/dice";
var currentRound = 1;
var lastCelebratedRound = 0;
var playerOrder;
var renderPlayers = false;
var currentPayersTurn;
var lastBetDice = 0;
var lastBetAmount = 0;
var userInControl = false;
var myBetAmount = 1;
var myBetDice = 1;
var totalDiceInPlay = 100;

// ensures the round over animation is only done once
var toggleRoundOver = false;

var isNewRound = true;

$(document).ready(function(){
	$('#turnForm').hide();
	$('#turnForm form').submit(function(){
        makeMove(this);
		return false;
	});

    $('.button').click(function(){
        $(this).parent('form').submit();
    });

    $('.white_content .exit').click(function(){
        $(this).parent('.white_content').hide();
        $('.black_overlay').hide();
    });

    $('#diceAmt .raiseArrow').click(function(){
        changeBet("raiseAmt");
    });
    $('#diceAmt .lowerArrow').click(function(){
        changeBet("lowerAmt");
    });

    $('#diceNum .raiseArrow').click(function(){
        changeBet("raiseNum");
    });

    $('#diceNum .lowerArrow').click(function(){
        changeBet("lowerNum");
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
    $('#amount').val(String(myBetAmount));
    $('#dice_number').val(String(myBetDice));
    $.ajax({
        url: moveUrl,
        type: "POST",
        data: $(form).serialize(),
        success: function(data){
//            console.log(data);
        }, dataType: "json"
    });
    userInControl = false;
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

function changeBet(diff){
    userInControl = true;
    myBetAmount = parseInt(myBetAmount);
    myBetDice = parseInt(myBetDice);

    switch (diff){
        case "raiseAmt":
            myBetAmount += 1;
            break;
        case "lowerAmt":
            myBetAmount -= 1;
            break;
        case "raiseNum":
            myBetDice += 1;
            break;
        case "lowerNum":
            myBetDice -= 1;
            break;
    }
    updatePlayersTurn({});
}

function prepareBetArrows(){
    myBetAmount = parseInt(myBetAmount);
    myBetDice = parseInt(myBetDice);
    lastBetDice = parseInt(lastBetDice);
    lastBetAmount = parseInt(lastBetAmount);

    $('#diceAmt .raiseArrow').css('visibility', 'visible');
    $('#diceAmt .lowerArrow').css('visibility', 'visible');
    $('#diceNum .raiseArrow').css('visibility', 'visible');
    $('#diceNum .lowerArrow').css('visibility', 'visible');

    // Raise amount arrow
    if(myBetAmount >= totalDiceInPlay){
        $('#diceAmt .raiseArrow').css('visibility', 'hidden');
    }

    // Lower amount arrow
    if(myBetAmount <= lastBetAmount && myBetAmount > 1){
        $('#diceAmt .lowerArrow').css('visibility', 'hidden');
    }

    if(myBetDice < lastBetDice && myBetAmount == (lastBetAmount + 1)){
        $('#diceAmt .lowerArrow').css('visibility', 'hidden');
    }
    // Raise dice arrow
    if(myBetDice >= 6){
        $('#diceNum .raiseArrow').css('visibility', 'hidden');
    }

    // Lower dice arrow
    if(myBetDice <= 1){
        $('#diceNum .lowerArrow').css('visibility', 'hidden');
    }
    if(myBetAmount <= lastBetAmount && myBetDice <= lastBetDice){
        $('#diceNum .lowerArrow').css('visibility', 'hidden');
    }

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

        totalDiceInPlay = 0;
        for (var dice in diceAvailable) {
            totalDiceInPlay += parseInt(diceAvailable[dice]);
        }
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
                    $('#diceAvailable .p_'+player+' .opponentsDice').append(drawDie('?'));
                };
            }
            else {
                // update player dice
                $('#diceAvailable .p_'+player+' .opponentsDice').empty();

                for(var i = 0; i < parseInt(diceAvailable[player]); i++){
                    $('#diceAvailable .p_'+player+' .opponentsDice').append(drawDie('?'));
                };
            }
        }
    }
}

function updatePlayersTurn(player){

    if(!isEmpty(player)){
        currentPayersTurn = player;
    }

    if(currentPayersTurn == username){
        prepareBetArrows();
        $('#turnForm').show();

        if(userInControl){
            $('#raiseDiceAmount').html(drawDie(myBetAmount + "x"));
            $('#raiseDiceNumber').html(drawDie(myBetDice));
        } else {
            // it's your turn
            $('#turnForm').show();
            if(lastBetAmount > 0){
                $('#raiseDiceAmount').html(drawDie(lastBetAmount + "x"));
                myBetAmount = lastBetAmount;
            } else {
                $('#raiseDiceAmount').html(drawDie("1x"));
                myBetAmount = 1;
            }
            if(lastBetDice >= 1 && lastBetDice <= 6){
                $('#raiseDiceNumber').html(drawDie(lastBetDice));
                myBetDice = lastBetDice;
            } else {
                $('#raiseDiceNumber').html(drawDie(1));
                myBetDice = 1;
            }
            prepareBetArrows();
        }

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
                lastBetDice = moves[i].diceFace;
                lastBetAmount = moves[i].amount;
			}
			elementToAppend += '</div>';
			$('#moveHistory').append(elementToAppend);
		}
	}
}

function updateMyDice(dice){
    if(isNewRound){
        $('#myDice').empty();
        $('#myDice').append('<div class="username">You</div>');
        $(dice).each(function(){

            $('#myDice').append(drawDie(this[0]));
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
            $('.black_overlay').show();
            $.ajax({
                url: lastDiceUrl,
                success: function(data){
                    if(data.previousDice != 'undefined'){
                        revealDice(data.previousDice);
                    }

                }, dataType: "json"
            });

            if(lastRound.player == lastRound.loser){
                var roundLoser = lastRound.loser;
                if(roundLoser == username){
                    roundLoser = 'You';
                }

                $('#roundResult .content').html('<div id="revealHeading">' + roundLoser + ' called ' + lastRound.call + ' and lost</div>');
            } else {
                var roundLoser = lastRound.loser;
                if(roundLoser == username){
                    roundLoser = 'you';
                }
                var roundWinner = lastRound.player;
                if(roundWinner == username){
                    roundWinner = 'You';
                }
                $('#roundResult .content').html('<div id="revealHeading">' + roundWinner + ' called ' + lastRound.call + ' on ' + roundLoser + ' and won</div>');
            }

            toggleRoundOver = false;
            $('#roundResult').delay(20000).hide(1000);
            $('.black_overlay').delay(20000).hide(1000);
        }
    }
}

function revealDice(dice){
    var appendMe = '<div id="revealLastDice">';
    $(dice).each(function(){
        appendMe += '<div class="clear revealUsername">';
        if(this.username == username){
            appendMe += 'You';
        } else {
            appendMe += this.username
        }
        appendMe += '</div> <div class="diceRow">';
        $(this.dice).each(function(){
            appendMe += drawDie(this[0]).html() + " ";
        });
        appendMe += "</div>";
    });
    appendMe += '</div>';
    $('#roundResult .content').append(appendMe);
}


function drawDie(dieNumber){
    dieNumber = String(dieNumber);
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

// Speed up calls to hasOwnProperty
var hasOwnProperty = Object.prototype.hasOwnProperty;

function isEmpty(obj) {

    // null and undefined are "empty"
    if (obj == null) return true;

    // Assume if it has a length property with a non-zero value
    // that that property is correct.
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and valueOf enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}