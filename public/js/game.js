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
var lastGameState = {};
var deadPlayers = Array();
var playerDead = false;

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
        if(!$(this).hasClass('inactive')){
            $(this).parent('form').submit();
        }
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
        }, dataType: "json"
    });
    userInControl = false;
}

function updateGame(gameState){
    if(gameState.losers !== 'undefinded'){
        if(gameState.losers !== lastGameState.losers){
            lastGameState.losers = gameState.losers;
        }
    }

    if(gameState.round !== 'undefinded'){
        if(gameState.round !== lastGameState.round){
            updateRound(gameState.round);
            lastGameState.round = gameState.round;
        }
    }

	if(gameState.playerOrder !== 'undefinded'){
        if(gameState.playerOrder !== lastGameState.playerOrder){
            updatePlayerOrder(gameState.playerOrder);
            lastGameState.playerOrder = gameState.playerOrder;
        }
	}

	if(gameState.diceAvailable !== 'undefinded'){
        if(gameState.diceAvailable !== lastGameState.diceAvailable){
            updateDiceAvailable(gameState.diceAvailable);
            lastGameState.diceAvailable = gameState.diceAvailable;
        }
	}

    if(gameState.moves !== 'undefinded'){
        if(gameState.moves !== lastGameState.moves){
            updateMoves(gameState.moves);
            lastGameState.moves = gameState.moves;
        }
    }

	if(gameState.playersTurn !== 'undefinded'){
        if(gameState.playersTurn !== lastGameState.playersTurn){
            updatePlayersTurn(gameState.playersTurn);
            lastGameState.playersTurn = gameState.playersTurn;
        }
	}

    if(gameState.myDice !== 'undefinded'){
        if(gameState.myDice !== lastGameState.myDice){
            updateMyDice(gameState.myDice);
            lastGameState.myDice = gameState.myDice;
        }
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

    if(myBetAmount <= 1){
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

    prepareBetButtons();
}

function prepareBetButtons() {
    if($('#diceNum .lowerArrow').css('visibility') == 'hidden' && $('#diceAmt .lowerArrow').css('visibility') == 'hidden'){
        $('#turnFormRaise a').addClass('inactive');
    } else {
        $('#turnFormRaise a').removeClass('inactive');
    }

    if(myBetAmount == (lastBetAmount + 1) && myBetDice == 1){
        $('#turnFormRaise a').removeClass('inactive');
    }

    if(lastBetDice == 0 && lastBetAmount == 0){
        $('#turnFormLie a').addClass('inactive');
        $('#turnFormPerfect a').addClass('inactive');
    }
}

function updateDiceAvailable(diceAvailable){
    if(isNewRound){
        deadPlayers = Array();
        playerDead = false;
        for (var i in diceAvailable) {
            if (!parseInt(diceAvailable[i])) {
                deadPlayers.push(i);
                if (i == username) {
                    playerDead = true;
                }
            }
        }
        drawDiceAvailable(diceAvailable);
    }

}

function drawDiceAvailable(diceAvailable){
    if(playerDead) {
        renderPlayers = true;
        loopDiceAvailable(diceAvailable);
    } else {
        renderPlayers = false;
        // we need to start with the player logged in
        // so we loop to that player and start drawing
        // and then loop back at the start until we hit
        // the player again
        loopDiceAvailable(diceAvailable);
        loopDiceAvailable(diceAvailable);
    }

    loopDeadPlayers();

    totalDiceInPlay = 0;
    for (var dice in diceAvailable) {
        totalDiceInPlay += parseInt(diceAvailable[dice]);
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
                elementToAppend += '<div class="lastBet"></div>';
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

function loopDeadPlayers(){
    for (var i in deadPlayers){
        var player = deadPlayers[i];
        if($('#diceAvailable .p_'+player).length == 0){
            // draw for the first time
            var elementToAppend = '<div class="p_'+player+'">';
            elementToAppend += '<div class="username">'
            elementToAppend += player;
            elementToAppend += '</div>';
            elementToAppend += '<div class="lastBet"></div>';
            elementToAppend += '<div class="opponentsDice">&nbsp;</div>';
            elementToAppend += '</div>';
            $('#diceAvailable').append(elementToAppend);
        }
        $('#diceAvailable .p_'+player+' .opponentsDice').empty();
        $('#diceAvailable .p_'+player+' .opponentsDice').append(drawDie('skull'));
    }
}

function updatePlayersTurn(player){
    $('.opponentsDice').css('background-color', '');

    if(!isEmpty(player)){
        currentPayersTurn = player;
        $('#diceAvailable .p_'+player+' .opponentsDice').css('background-color', '#6eb4da');
    }

    if(currentPayersTurn == username){
        if(userInControl){
            $('#raiseDiceAmount').html(drawDie(myBetAmount + "x"));
            $('#raiseDiceNumber').html(drawDie(myBetDice));
            prepareBetArrows();
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
            $('#turnForm').show();
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
        var lastBetElem = $('#diceAvailable .p_'+moves[i].username+' .lastBet');
        lastBetElem.empty();
        lastBetElem.append(drawSmallDie(moves[i].amount+'x'));
        lastBetElem.append(drawSmallDie(moves[i].diceFace));

        lastBetDice = moves[i].diceFace;
        lastBetAmount = moves[i].amount;
	}
}

function updateMyDice(dice){
    if(isNewRound){
        $('#myDice').empty();
        $('#myDice').append('<div class="username">You</div>');
        if(playerDead) {
            $('#myDice').append(drawDie("skull"));
        } else {
            $(dice).each(function () {
                $('#myDice').append(drawDie(this[0]));
            });
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

            lastGameState = {};
            lastBetAmount = 0;
            lastBetDice = 0;
            $.ajax({
                url: lastDiceUrl,
                success: function(data){
                    if(data.previousDice != 'undefined'){
                        revealDice(data);
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

            $('#turnFormRaise a').removeClass('inactive');
            $('#turnFormPerfect a').removeClass('inactive');

            toggleRoundOver = false;
            $('#roundResult').delay(20000).hide(1000);
            $('.black_overlay').delay(20000).hide(1000);
        }
    }
}

function revealDice(dice){
    var appendMe = '<div id="revealLastDice">';
    var lineMyDice = false;
    var startRevealDice = '';
    var endRevealDice = '';

    var lastBet = '<div id="revealLastBet" class="clear">Last bet: ';
    lastBet += dice.lastBet.username + drawSmallDie(dice.lastBet.amount+'x').html() + drawSmallDie(dice.lastBet.dice_number).html();
    lastBet += '</div>';

    $(dice.previousDice).each(function(){
        var lineHTML = '<div class="clear revealUsername">';
        if(this.username == username){
            lineHTML += 'You';
            lineMyDice = true;
        } else {
            lineHTML += this.username
        }
        lineHTML += '</div> <div class="diceRow">';
        $(this.dice).each(function(){
            if(this[0] != undefined) {
                lineHTML += drawDie(this[0]).html() + " ";
            } else {
                lineHTML += drawDie("skull").html() + " ";
            }
        });
        lineHTML += "</div>";

        if(!lineMyDice){
            endRevealDice += lineHTML;
        } else {
            startRevealDice += lineHTML;
        }
    });
    appendMe += startRevealDice + endRevealDice;
    appendMe += '</div>';
    $('#roundResult .content').append(lastBet + appendMe);
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

            if(dieNumber == 'skull'){
                $(die).children('.textContent').html("&#9760;");
                $(die).children('.textContent').css('font-size', '100px');
            } else {
                $(die).children('.textContent').text(dieNumber);
            }
            break;
    }
    die.removeAttr('id');

    return die;
}

function drawSmallDie(dieNumber){
    dieNumber = String(dieNumber);
    var die = null;
    switch (dieNumber)
    {
        case "1":
            die = $('#diceSmall1').clone();
            break;
        case "2":
            die = $('#diceSmall2').clone();
            break;
        case "3":
            die = $('#diceSmall3').clone();
            break;
        case "4":
            die = $('#diceSmall4').clone();
            break;
        case "5":
            die = $('#diceSmall5').clone();
            break;
        case "6":
            die = $('#diceSmall6').clone();
            break;
        default :
            die = $('#emptyDiceSmall').clone();
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