<?php 
	// First check that the user actually inputted the required fields (name, team_id, and position_id) - if not set $error
	if(!isset($_POST['name']) || trim($_POST['name']) == '' 
		|| !isset($_POST['team_id']) || trim($_POST['team_id']) == ''
		|| !isset($_POST['position_id']) || trim($_POST['position_id']) == '') {
		$error = "Please fill out all required fields.";
	}
	else {
		// Get credentials from config.php and open mysql connection
		require "config/config.php";
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		// Since we know the required values exist (error checking above), set each in a variable using POST
		$name = $_POST['name'];
		$team_id = $_POST['team_id'];
		$position_id = $_POST['position_id'];

		// Now check if they inputted any stats for AVG, OBP, or OPS. If they did, set a variable with that value, otherwise set null
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

		// Do a SQL insert statement to actually add the player to the database
		$sql = "INSERT INTO players(name, team, position, AVG, OBP, OPS)
				VALUES ('$name', $team_id, $position_id, $avg, $obp, $ops);";

		// Query and check for errors
		$result = $mysqli->query($sql);

		if ( !$result ) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// Close the connection
		$mysqli->close();
	}

?>


<!DOCTYPE html>
<html>
<head>

	<!-- We need the meta tag and external href to use bootstrap -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

	<title>Add Player Confirmation</title>
	<!-- Link the main.css file so that we can use its css -->
	<link rel="stylesheet" href="main.css">

</head>
<body>

	<!-- Same menu bar on every page for consistency -->
	<ul id="menu_bar">
		<li><a href="home.php"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Major_League_Baseball_logo.svg/1200px-Major_League_Baseball_logo.svg.png" alt="MLB Logo"></a></li>
		<li>
			<!-- Make it active-nav since we are adding player -->
			<a id="active-nav" href="add_player.php">Add Player</a>
		</li>
		<li><a href="email.html">Email me more</a></li>
	</ul>

	<!-- Container for bootstrap -->
	<div class="container">
			<div id="exp" class=col-12>
				<!-- Check if there is an error, if so echo it -->
				<?php if (isset($error) && !empty($error)) : ?>

					<div class="text-danger">
						<?php echo $error ?>
					</div>

				<!-- If there are no errors, tell the user that their player was successfully added -->
				<?php else : ?>

					<div class="text-success">
						<!-- Echo the actual player name -->
						<span class="font-italic"><?php echo $name ?></span> was successfully added.
					</div>

				<?php endif; ?>
			</div>
	</div>

	<!-- Same footer as other pages -->
	<div id="footer" class="container-fluid text-center">
		Aidan Miltner Final Project &copy; 2022
	</div> <!-- close #footer -->

	<!-- Need script right before the body closes for bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>


</body>
</html>