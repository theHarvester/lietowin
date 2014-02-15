@extends('master.page')
@section('head')
{{HTML::script('js/game.js')}}
@stop
@section('content')
    <script type="text/javascript">
        var username = "{{ $username }}";
    </script>
    <div class="container">
        <div id="output"></div>

        <div id="diceAvailable"></div>
        <div id="moveHistory"></div>
        <div id="currentlyQueued">You are in the queue, please wait while we find you a game.</div>
        <div id="turnFormContainer">
            <form id="turnForm" action="/apifight/public/api/v1/game/move" type="post">
            Dice: <input type="text" name="dice_number"><br>
            Amount: <input type="text" name="amount"><br>
            <input type="radio" name="call" value="raise" checked="checked"> Raise<br>
            <input type="radio" name="call" value="perfect"> Perfect<br>
            <input type="radio" name="call" value="lie"> Lie<br>
            <input type="submit" value="Submit">
            </form>
        </div>
    </div>
@stop