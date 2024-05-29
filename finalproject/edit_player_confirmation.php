<?php 
	// First check that the user filled out the required fields (name, team, and position) - if not set $error
	if(!isset($_POST['name']) || trim($_POST['name']) == '' 
		|| !isset($_POST['team_id']) || trim($_POST['team_id']) == ''
		|| !isset($_POST['position_id']) || trim($_POST['position_id']) == '') {
		$error = "Please fill out all required fields.";
	}
	else {
		// Get login credentials and open mysql connection
		require "config/config.php";
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		// Get the passed in values (we know they exist because they are required and we did error checking above) from POST since we specified POST in edit_player.php
		$id = $_POST['player_id'];
		$name = $_POST['name'];
		$team_id = $_POST['team_id'];
		$position_id = $_POST['position_id'];

		// Then check for the optional fields (AVG, OBP, and OPS). If they are set, create the variable and get the POST value - if not, just make it null
		if(isset($_POST['avg']) && trim($_POST['avg']) != '') {
			$avg = $_POST['avg'];
		}
		else{
			$avg = "null";
		}

		if(isset($_POST['obp']) && trim($_POST['obp']) != '') {
			$obp = $_POST['obp'];
		}
		else{
			$obp = "null";
		}

		if(isset($_POST['ops']) && trim($_POST['ops']) != '') {
			$ops = $_POST['ops'];
		}
		else{
			$ops = "null";
		}

		// SQL update statement - set the new name, team, position, and stats for that player
		$sql = "UPDATE players
				SET name = '$name', team = $team_id, position = $position_id, AVG = $avg, OBP = $obp, OPS = $ops
				WHERE id = $id;";

		// Query and check for errors
		$result = $mysqli->query($sql);

		// Check for SQL Errors.
		if ( !$result ) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// Close connection
		$mysqli->close();
	}

?>


<!DOCTYPE html>
<html>
<head>

	<!-- We need the meta tag and external href to use bootstrap -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

	<title>Edit Player Confirmation</title>
	<!-- Link the main.css file so that we can use its css -->
	<link rel="stylesheet" href="main.css">

</head>
<body>

	<!-- Same menu bar on every page for consistency -->
	<ul id="menu_bar">
		<li><a href="home.php"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Major_League_Baseball_logo.svg/1200px-Major_League_Baseball_logo.svg.png" alt="MLB Logo"></a></li>
		<li>
			<a href="add_player.php">Add Player</a>
		</li>
		<li><a href="email.html">Email me more</a></li>
	</ul>

	<!-- Container for bootstrap -->
	<div class="container">
		<!-- Same rounded div as other pages -->
		<div id="exp" class=col-12>
			<!-- First check if there is an error and display it if so -->
			<?php if (isset($error) && !empty($error)) : ?>

			<div class="text-danger">
				<?php echo $error ?>
			</div>

			<!-- If they did input everything right, tell them that the player was successfully updated -->
			<?php else : ?>

				<div class="text-success">
					<!-- Echo the actual name of the player -->
					<span class="font-italic"><?php echo $name ?></span> was successfully updated.
				</div>

			<?php endif; ?>
		</div>
	</div>

	<!-- Same footer as other pages-->
	<div id="footer" class="container-fluid text-center">
		Aidan Miltner Final Project &copy; 2022
	</div> <!-- close #footer -->

	<!-- Need script right before the body closes for bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>


</body>
</html>