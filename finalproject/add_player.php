<?php 
	// Same as other pages - require config.php so I have credentials to create mysql connection
	require "config/config.php";
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	$mysqli->set_charset('utf8');

	// Get all data from the teams table and positions table so I can add all teams and positions to dropdown menus for the user to select from when adding a player. Query each and check for errors
	$sql_teams = "SELECT * FROM teams;";

	$results_teams = $mysqli->query($sql_teams);

	// Check for SQL Errors.
	if ( !$results_teams ) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	$sql_positions = "SELECT * FROM positions;";

	$results_positions = $mysqli->query($sql_positions);

	// Check for SQL Errors.
	if ( !$results_positions ) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// Close connection
	$mysqli->close();

?>


<!DOCTYPE html>
<html>
<head>

	<!-- We need the meta tag and external href to use bootstrap -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

	<title>Add Player</title>
	<!-- Link the main.css file so that we can use its css -->
	<link rel="stylesheet" href="main.css">
	<style>

		/* Add margin so there is space between each of the rows */
		.row {
			margin-bottom: 5px;
		}

	</style>

</head>
<body>

	<!-- Same menu bar on every page for consistency -->
	<ul id="menu_bar">
		<li><a href="home.php"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Major_League_Baseball_logo.svg/1200px-Major_League_Baseball_logo.svg.png" alt="MLB Logo"></a></li>
		<li>
			<!-- Add active-nav to this tag so that it is highlighted since we are on the 'add player' tab -->
			<a id="active-nav" href="add_player.php">Add Player</a>
		</li>
		<li><a href="email.html">Email me more</a></li>
	</ul>

	<div class="container">
			<div id="exp" class=col-12>
				<!-- Post all information to add_player_confirmation.php so we can actually add the player to the database -->
				<form action="add_player_confirmation.php" method="POST">

					<div class="form-group row">
						<div class="col-sm-3 col-form-label">
							<h2>Add a Player</h2>
						</div>
					</div>

					<!-- First, require that they enter the player's name -->
					<div class="form-group row">
						<label for="name-id" class="col-sm-3 col-form-label text-sm-right">Name: <span class="text-danger">*</span></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="name-id" name="name">
						</div>
					</div> <!-- .form-group -->

					<div class="form-group row">
						<label for="team-id" class="col-sm-3 col-form-label text-sm-right">Team: <span class="text-danger">*</span></label>
						<div class="col-sm-9">
							<select name="team_id" id="team-id" class="form-control">
								<option value="" selected disabled>-- Select One --</option>

								<!-- Go through each row in the results_teams query and add each team as a value in our dropdown menu -->
								<?php while ($row = $results_teams->fetch_assoc()) : ?>

								<!-- Make the id of the team the value that is passed to add_player_confirmation, but echo the actual team name in the dropdown menu -->
								<option value=<?php echo $row['id']; ?>>
									<?php echo $row['team']; ?>
								</option>


								<?php endwhile; ?>

							</select>
						</div>
					</div> <!-- .form-group -->

					<div class="form-group row">
						<label for="position-id" class="col-sm-3 col-form-label text-sm-right">Position: <span class="text-danger">*</span></label>
						<div class="col-sm-9">
							<select name="position_id" id="position-id" class="form-control">
								<option value="" selected disabled>-- Select One --</option>

								<!-- Go through each row in results_positions and add each position as a value in our dropdown menu -->
								<?php while ($row = $results_positions->fetch_assoc()) : ?>

								<!-- Make the id of the position the value that is passed to add_player_confirmation, but echo the actual position in the dropdown menu -->
								<option value=<?php echo $row['id']; ?>>
									<?php echo $row['position']; ?>
								</option>


								<?php endwhile; ?>

							</select>
						</div>
					</div> <!-- .form-group -->

					<!-- Add optional fields for AVG, OBP, and OPS if the user wants to input stats for this new player -->
					<div class="form-group row">
						<label for="avg" class="col-sm-3 col-form-label text-sm-right">AVG:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="avg" name="avg">
						</div>
					</div> <!-- .form-group -->

					<div class="form-group row">
						<label for="obp" class="col-sm-3 col-form-label text-sm-right">OBP:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="obp" name="obp">
						</div>
					</div> <!-- .form-group -->

					<div class="form-group row">
						<label for="ops" class="col-sm-3 col-form-label text-sm-right">OPS:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="ops" name="ops">
						</div>
					</div> <!-- .form-group -->

					<div class="form-group row">
						<div class="ml-auto col-sm-7">
							<span class="text-danger font-italic">* Required</span>
						</div>
					</div> <!-- .form-group -->

					<div class="form-group row">
						<div class="col-sm-3">
						<!-- <div class="col-sm-9 mt-2"> -->
							<button type="submit" class="btn btn-primary">Submit</button>
							<button type="reset" class="btn btn-light">Reset</button>
						<!-- </div> -->
						</div>
					</div> <!-- .form-group -->

				</form>
			</div>
	</div>

	<!-- Same footer on all pages -->
	<div id="footer" class="container-fluid text-center">
		Aidan Miltner Final Project &copy; 2022
	</div> <!-- close #footer -->

	<!-- Need script right before the body closes for bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>


</body>
</html>