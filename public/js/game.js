var queueUrl = window.urlPathPrefix + "/api/v1/queue";
var gameUrl = window.urlPathPrefix + "/api/v1/game";
var moveUrl = window.urlPathPrefix + "/api/v1/game/move";
var lastDiceUrl = window.urlPathPrefix + "/api/v1/game/dice";
var gameId = 0;
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
var gameEnded = false;
var timeLeft = 120;
var isPlayersTurn = false;
var divisor_for_minutes = 0;
var divisor_for_seconds = 0;

// ensures the round over animation is only done once
var toggleRoundOver = false;

var isNewRound = true;

$(document).ready(function(){
	$('#turnForm').hide();
    $('#lastRaiseContainer').hide();
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
        closeLightBox();
    });

    $('.black_overlay').click(function(){
        closeLightBox();
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
    if(gameId != 0){
        $.ajax({
            url: gameUrl,
            success: function(game){
                updateGame(game);
            },
            dataType: "json"
        });
    } else {
        $.ajax({
            url: queueUrl,
            success: function(data){
                if(data.queued == true){
                    currentlyQueued(true, data.queued_count);
                } else if(data.game_id !== 'undefinded'){
                    gameId = data.game_id;
                    currentlyQueued(false, 0);
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
    }
}, 2000);

setInterval(function(){
    if(timeLeft > 0){
        --timeLeft;
    }
    updateTimeLeft(timeLeft);
    if(isPlayersTurn && timeLeft % 2){
        $('title').text('Your turn!');
    } else {
        $('title').text('Lie to Win');
    }
}, 1000);

function closeLightBox(){
    $('.white_content').hide();
    $('.black_overlay').hide();
    animateRoll();
}

function animateRoll(){
    $("#myDiceRow .dice").each(function(){
        $(this).addClass('bounce');
        var randInvterval = getRandomInt(1,200) +"ms";
        $(this).css('animation-delay', randInvterval);
        $(this).css('-moz-animation-delay', randInvterval);
        $(this).css('-webkit-animation-delay', randInvterval);
        $(this).addClass('animated');
    });
}

function makeMove(form){
    $('#turnForm').hide();
    $('#lastRaiseContainer').hide();
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
    if(gameState.losers !== 'undefined'){
        if(gameState.losers !== lastGameState.losers){
            lastGameState.losers = gameState.losers;
        }
    }

    if(gameState.round !== 'undefined'){
        if(gameState.round !== lastGameState.round){
            updateRound(gameState.round);
            lastGameState.round = gameState.round;
        }
    }

	if(gameState.playerOrder !== 'undefined'){
        if(gameState.playerOrder !== lastGameState.playerOrder){
            updatePlayerOrder(gameState.playerOrder);
            lastGameState.playerOrder = gameState.playerOrder;
        }
	}

	if(gameState.diceAvailable !== 'undefined'){
        if(gameState.diceAvailable !== lastGameState.diceAvailable){
            updateDiceAvailable(gameState.diceAvailable);
            lastGameState.diceAvailable = gameState.diceAvailable;
        }
	}

    if(gameState.moves !== 'undefined'){
        if(JSON.stringify(gameState.moves) !== JSON.stringify(lastGameState.moves)){
            updateMoves(gameState.moves);
            lastGameState.moves = gameState.moves;
        }
    }

	if(gameState.playersTurn !== 'undefined'){
        if(gameState.playersTurn !== lastGameState.playersTurn){
            updatePlayersTurn(gameState.playersTurn);
            lastGameState.playersTurn = gameState.playersTurn;
        }
	}

    if(gameState.myDice !== 'undefined'){
        if(gameState.myDice !== lastGameState.myDice){
            updateMyDice(gameState.myDice);
            lastGameState.myDice = gameState.myDice;
        }
    }

    if(gameState.lastRoundEnd !== 'undefined'){
        roundEnd(gameState.lastRoundEnd);
    }

    if(gameState.secondsElapsed !== 'undefined'){
        $('#timeLeftContainer').show();
        var newTimeLeft = 120 - gameState.secondsElapsed;
        if(newTimeLeft != timeLeft) {
            timeLeft = newTimeLeft;
        }
    }

    if(typeof(gameState.winner) !== "undefined"){
        if(!gameEnded) {
            gameOver(gameState.winner);
        }
        gameEnded = true;
    }
    isNewRound = false;
}

function gameOver(winner) {
    $('#game').css('display', 'none');
    $('#winnerContainer').css('display', 'block');
    if(username == winner){
        $('#gameOver').text('You win!');
        $('#winnerAnnouncement').text('Congratulations buddy, I always knew you had it in you');
    } else {
        $('#gameOver').text('You lose!');
        $('#winnerAnnouncement').text(winner + ' won the game. I think they insulted your mother, are you gonna\' take that?');
    }
}

function updateTimeLeft(roundTimeLeft){
    divisor_for_minutes = roundTimeLeft % (60 * 60);
    var minutes = Math.floor(divisor_for_minutes / 60);

    divisor_for_seconds = divisor_for_minutes % 60;
    var seconds = Math.ceil(divisor_for_seconds);

    $('#timeLeft').text(minutes +':' + pad(seconds, 2));
}

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
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
        if(player != username) {
            if ($('#diceAvailable .p_' + player).length == 0) {
                // draw for the first time
                var elementToAppend = '<div class="p_' + player + '">';
                elementToAppend += '<div class="username">'
                elementToAppend += player;
                elementToAppend += '</div>';
                elementToAppend += '<div class="lastBet"></div>';
                elementToAppend += '<div class="opponentsDice">&nbsp;</div>';
                elementToAppend += '</div>';
                $('#diceAvailable').append(elementToAppend);
            }
            $('#diceAvailable .p_' + player + ' .opponentsDice').empty();
            $('#diceAvailable .p_' + player + ' .opponentsDice').append(drawDie('skull'));
        }
    }
}

function updatePlayersTurn(player){
    $('.opponentsDice').removeClass('playersTurn');

    if(!isEmpty(player)){
        currentPayersTurn = player;
        $('#diceAvailable .p_'+player+' .opponentsDice').addClass('playersTurn');
    }

    if(currentPayersTurn == username){
        isPlayersTurn = true;
        $('#timeLeftPlayerName').text('your');
        $('#myDiceRow').addClass('playersTurn');
        if(userInControl){
            $('#raiseDiceAmount').html(drawDie(myBetAmount + "x"));
            $('#raiseDiceNumber').html(drawDie(myBetDice));
            prepareBetArrows();
        } else {
            // it's your turn
            $('#turnFormRaise a').removeClass('inactive');
            $('#turnFormLie a').removeClass('inactive');
            $('#turnFormPerfect a').removeClass('inactive');
            showTurnForm();
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
            showTurnForm();
        }

    } else {
        isPlayersTurn = false;
        $('#timeLeftPlayerName').text(player + '\'s');
        $('#myDiceRow').removeClass('playersTurn');
        $('#turnForm').hide();
    }
}

function showTurnForm(){
    $("#lastRaiseContainer").show();
    $('#lastRaiseLabel').show();
    $('#lastRaiseContainer').addClass('animated');

    $('#turnForm').show();
    $('#turnForm').addClass('animated');
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

        var lastBetTop = $('#lastRaise');
        lastBetTop.empty();
        lastBetTop.append(drawSmallDie(moves[i].amount+'x'));
        lastBetTop.append(drawSmallDie(moves[i].diceFace));

        lastBetDice = moves[i].diceFace;
        lastBetAmount = moves[i].amount;
	}
}

function updateMyDice(dice){
    if(isNewRound){
        $('#myDiceRow').empty();
        $('#myDiceName').show();
        if(playerDead) {
            $('#myDiceRow').append(drawDie("skull"));
        } else {
            $(dice).each(function () {
                $('#myDiceRow').append(drawDie(this[0]));
            });
        }
    }
}

function currentlyQueued(isQueued, queueCount){
    if(isQueued){
        $('#currentlyQueued').show();
        $('#queueCount').text(queueCount + ' players currently queued.');
        $('#turnForm').hide();
        $('#lastRaiseContainer').hide();
        $('#myDice').hide();
    } else {
        $('#currentlyQueued').hide();
        $('#myDice').show();
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
            $('#diceAvailable .lastBet').empty();

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

            if(lastRound.call == 'timeout'){
                $('#roundResult .content').html('<div id="revealHeading">' + lastRound.loser + ' took to long to make a move</div>');
            } else {
                if (lastRound.player == lastRound.loser) {
                    var roundLoser = lastRound.loser;
                    if (roundLoser == username) {
                        roundLoser = 'You';
                    }

                    $('#roundResult .content').html('<div id="revealHeading">' + roundLoser + ' called ' + lastRound.call + ' and lost</div>');
                } else {
                    var roundLoser = lastRound.loser;
                    if (roundLoser == username) {
                        roundLoser = 'you';
                    }
                    var roundWinner = lastRound.player;
                    if (roundWinner == username) {
                        roundWinner = 'You';
                    }
                    $('#roundResult .content').html('<div id="revealHeading">' + roundWinner + ' called ' + lastRound.call + ' on ' + roundLoser + ' and won</div>');
                }
            }

            $('#turnFormRaise a').removeClass('inactive');
            $('#turnFormLie a').removeClass('inactive');
            $('#turnFormPerfect a').removeClass('inactive');

            $('#lastRaiseContainer').hide();
            $('#lastRaiseLabel').hide();
            $('#lastRaise').empty();

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
                if(this[0] == dice.lastBet.dice_number){
                    lineHTML += drawDie(this[0]).html() + " ";
                } else {
                    lineHTML += drawDie(this[0], false).html() + " ";
                }

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


function drawDie(dieNumber, validDie){
    validDie = (typeof validDie === "undefined") ? true : validDie;

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
        case "skull":
            die = $('#diceSkull').clone();
            break;
        default :
            die = $('#emptyDice').clone();
            $(die).children('.textContent').text(dieNumber);
            break;
    }
    die.removeAttr('id');
    if(!validDie){
        die.children('.dice').addClass('invalid-die');
    }


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

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}