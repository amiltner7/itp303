<?php 
	// Same as all other pages, I require that config.php is used so that I have my credentials to create my mysql connection
	require "config/config.php";

	// First check that we can get the player_id from the URL because we need it to get the stats about that specific player. If it is not set or it is empty, show an error
	if ( !isset($_GET['player_id']) || trim($_GET['player_id']) == '' ) {
		$error = "Please fill out all required fields.";
	}
	else {
		// Establish DB connection using constants from config.php, check for errors
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		// Store the player_id from the URL as a variable
		$player_id = $_GET['player_id'];

		// Select all player information from the players table where the id is equal to the selected player's id
		$sql = "SELECT name, team, position, AVG, OBP, OPS 
		FROM players 
		WHERE id=$player_id;";

		// Query the result
		$results = $mysqli->query($sql);

		// Check for errors
		if ( !$results ) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// We also want the team he is on, so select all teams (and ids) from the teams table and we will find the one that matches later on
		$sql_teams = "SELECT * FROM teams;";
		// Query the result
		$results_teams = $mysqli->query($sql_teams);

		if ( !$results_teams ) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// We also want the player's position, so select all positions (and ids) from the positions table and we will match the position later on
		$sql_positions = "SELECT * FROM positions;";
		$results_positions = $mysqli->query($sql_positions);

		if ( !$results_positions ) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// There will only be one row for $results because we selected one player - get this row using fetch_assoc()
		$row = $results->fetch_assoc();

		// Close connection
		$mysqli->close();
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!-- Necessary for bootstrap -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Results</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- Link main.css so we have consistency on all pages -->
	<link rel="stylesheet" href="main.css">

	<style>

		/* We want the name of the player to be centered and have space between it and the menu bar */
		h2 {
			margin-top: 50px;
			text-align: center;
		}

		#circles {

			/* We want everything in the div to be centered and have margins at the top and bottom of the page */
			margin-left: auto;
			margin-right: auto;
			margin-top: 50px;
			margin-bottom: 100px;

			/* Display flex so all the baseballs show up in an ordered way and are centered */
			display: flex;
			align-items: flex-start;
			justify-content: center;
			flex-wrap: wrap;
		}

		.circle {
			/* The border-radius rounds the div's to create circles - then I want them to be 300px wide and tall */
			border-radius: 50%;
			width: 300px;
			height: 300px;

			/* This puts space between each of the balls */
			margin-left: 20px;

			/* Hide the extra image that overflows the div and make the position relative to the caption so it shows up in the middle of the ball when we hover	*/
			overflow: hidden;
			position: relative;
		}

		/* Get the image element within the circle (baseball img) and set the height and width to be auto/100% */
		.circle > img {
			width: auto;
			height: 100%;
		}

		.caption {
			/* Make the position absolute so it shows up within the baseball */
			position: absolute;

			/* Set the color to be navy and the font size to 30px */
			color: #21224d;
			font-size: 30px;

			/* Set the same size as the circle div so it shows up as a circle when we hover */
			border-radius: 50%;
			width: 300px;
			height: 300px;

			/* Align the text in the center and center it vertically */
			text-align: center;
			padding-top: 120px;

			/* Make the background color white but transparent so you can still see the baseball image when you hover */
			background-color: rgba(255,255,255,0.7);

			/* Start the visibility as hidden and change it to visible when we hover */
			visibility: hidden;
		}

		/*	When we hover, make it so we can see the caption */
		.circle:hover .caption {
			visibility: visible;
		}

		/* Center the position and team under the name		*/
		#posteam {
			text-align: center;
		}

		/* Center the error message (if there is one)	*/
		#error {
			text-align: center;
			margin-top: 20px;
		}

		/*	Make sure the buttons aren't covered by the footer	*/
		.buttons {
			margin-bottom: 70px;
		}

	</style>
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

	<!-- First check if there is an error, echo it if so -->
	<?php if ( isset($error) && trim($error) != '' ) : ?>

		<div id="error" class="text-danger">
			<?php echo $error; ?>
		</div>

	<?php else : ?>

	<!-- If there is no error, echo the name of the player and their position/team below -->
	<h2><?php echo $row['name']; ?></h2>
	<!-- To get their position and team, loop through each row in the positions query and teams query and see which one matches the actual position/team of our selected player. Only echo the position and name which has matching id -->
	<p id="posteam">
		<?php while ($row_pos = $results_positions->fetch_assoc()) {
			if($row_pos['id'] == $row['position']){
				echo $row_pos['position'];
				$pos_name = $row_pos['position'];
			}
		}
		?>
		| 
		<?php while ($row_team = $results_teams->fetch_assoc()) {
			if($row_team['id'] == $row['team']){
				echo $row_team['team'];
				$tm_name = $row_team['team'];
			}
		}
		?>
	</p>

	<div id="circles" class="col-12">

		<!-- If they click on any of the 3 baseballs, pass through all of the necessary information to stats.php. Specifically, create a variable called stat and hardcode it to be AVG, OBP, or OPS (so stats.php knows which stat to display), along with the player id, position id, team id, position name, and team name -->
		<a href="stats.php?stat=AVG&position=<?php echo $row['position']; ?>&team=<?php echo $row['team']; ?>&pos_name=<?php echo $pos_name; ?>&tm_name=<?php echo $tm_name; ?>&id=<?php echo $player_id; ?>">
			<div class="circle">
				<!-- Display the AVG and round it to 3 decimal places -->
				<div class="caption"><b><?php echo round($row['AVG'], 3); ?></b></div>
				<!-- Display the baseball image in the img folder that has AVG in it -->
				<img src=img/avg.jpeg alt="avg">
			</div>
		</a>

		<a href="stats.php?stat=OBP&position=<?php echo $row['position']; ?>&team=<?php echo $row['team']; ?>&pos_name=<?php echo $pos_name; ?>&tm_name=<?php echo $tm_name; ?>&id=<?php echo $player_id; ?>">
			<div class="circle">
				<!-- Display the OBP and round it to 3 decimal places -->
				<div class="caption"><b><?php echo round($row['OBP'], 3); ?></b></div>
				<!-- Display the baseball image in the img folder that has OBP in it -->
				<img src=img/obp.jpeg alt="obp">

			</div>
		</a>

		<a href="stats.php?stat=OPS&position=<?php echo $row['position']; ?>&team=<?php echo $row['team']; ?>&pos_name=<?php echo $pos_name; ?>&tm_name=<?php echo $tm_name; ?>&id=<?php echo $player_id; ?>">
			<div class="circle">
				<!-- Display the OPS and round it to 3 decimal places -->
				<div class="caption"><b><?php echo round($row['OPS'], 3); ?></b></div>
				<!-- Display the baseball image in the img folder that has OPS in it -->
				<img src=img/ops.jpeg alt="ops">
			</div>
		</a>

	</div>

	<!-- Buttons to edit or delete the player. Include the id of the player in the URL so we can GET it in edit_player.php or delete_player.php -->
	<div class="buttons d-flex justify-content-center">
		<a href="edit_player.php?player_id=<?php echo $player_id; ?>" class="btn btn-warning mr-1">
				Edit Player</a>

		<!-- Double check that the user wants to delete the player by giving them a popup window -->
		<a href="delete_player.php?player_id=<?php echo $player_id; ?>&name=<?php echo $row['name']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete \'<?php echo $row['name']; ?>\'?');">Delete Player
		</a>
	</div>

	<?php endif; ?>

	<!-- Same footer on all pages -->
	<div id="footer" class="container-fluid text-center">
		Aidan Miltner Final Project &copy; 2022
	</div> <!-- close #footer -->

	
</body>
</html>