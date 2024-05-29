<?php 
	// First make sure that the player id was actually passed into the URL in results.php - otherwise set the error
	if ( !isset($_GET['player_id']) || trim($_GET['player_id']) == '' ) {
		$error = "Invalid URL.";
	} 
	else {
		// Require credentials so I can open mysql connection
		require "config/config.php";
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		// Get the player id and name from the URL using GET 
		$id = $_GET['player_id'];
		$name = $_GET['name'];

		// Delete the player from the database with the matching id
		$sql = "DELETE FROM players
				WHERE id = $id;";

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

	<title>Delete Player</title>
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
		<div id="exp" class=col-12>
			<!-- If there is an error, display it -->
			<?php if (isset($error) && !empty($error)) : ?>

				<div class="text-danger">
					<?php echo $error ?>
				</div>

			<!-- If there aren't any errors, tell the user that the player was successfully deleted -->
			<?php else : ?>

				<div class="text-success">
					<!-- Echo the actual player name -->
					<span class="font-italic"><?php echo $name ?></span> was successfully deleted.
				</div>

			<?php endif; ?>
		</div>
	</div>

	<!-- Same footer on every page -->
	<div id="footer" class="container-fluid text-center">
		Aidan Miltner Final Project &copy; 2022
	</div> <!-- close #footer -->

	<!-- Need script right before the body closes for bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>


</body>
</html>