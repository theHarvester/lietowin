<html>
	<head>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="script.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href='http://fonts.googleapis.com/css?family=Fauna+One' rel='stylesheet' type='text/css'>
		<script type="text/javascript">
			var username = "{{ $username }}";
		</script>
	</head>
	<body>
		<div class="container">
			<div id="output"></div>

			<div id="diceAvailable"></div>
			<div id="moveHistory"></div>
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
	</body>
</html>