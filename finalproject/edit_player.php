<?php 
	// We passed in the id of the player we want to edit to the URL (in results.php), so we need to make sure we can actually GET the value (or it is not empty). If it is set $error
	if(!isset($_GET['player_id']) || trim($_GET['player_id']) == '') {
		$error = "Invalid URL.";
	}
	else {
		// Like all other pages, require config.php so I can establish mysql connection
		require "config/config.php";
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		// Store the value from the URL in $id using GET
		$id = $_GET['player_id'];

		// Get the actual player 
		$sql_player = "SELECT * FROM players 
						WHERE id = $id;";

		// Query and check for errors
		$result_player = $mysqli->query($sql_player);

		// Check for SQL Errors.
		if ( !$result_player ) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// Get all teams data and positions data so we can get the actual team name and position (rather than id) that the user can edit, query and check for errors
		$sql_teams = "SELECT * FROM teams;";

		$results_teams = $mysqli->query($sql_teams);

		// Check for SQL Errors.
		if ( !$results_teams ) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// Get the single row from the player query (we know it will be one row because we are using an id which is a primary key)
		$row_player = $result_player->fetch_assoc();

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
	}

?>


<!DOCTYPE html>
<html>
<head>

	<!-- We need the meta tag and external href to use bootstrap -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

	<title>Edit Player</title>
	<!-- Link the main.css file so that we can use its css -->
	<link rel="stylesheet" href="main.css">
	<style>

		/* Put spaces between all of the rows in the form */
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
			<a href="add_player.php">Add Player</a>
		</li>
		<li><a href="email.html">Email me more</a></li>
	</ul>

	<!-- Container for bootstrap -->
	<div class="container">
		<!-- Same rounded div as all other pages (formatted in main.css) -->
		<div id="exp" class=col-12>
			<!-- We want to POST all data to edit_player_confirmation.php so that we can actually make the edits and give a confirmation message to the user. We use POST because it is sensitive data and there is a lot of it that we don't want to store in URL -->
			<form action="edit_player_confirmation.php" method="POST">

				<!-- Echo the player id but make it hidden because we don't need to show it on the page -->
				<input type="hidden" name="player_id" value="<?php echo $id ?>">

				<div class="form-group row">
					<div class="col-sm-3 col-form-label">
						<h2>Edit Player</h2>
					</div>
				</div>

				<div class="form-group row">
					<label for="name-id" class="col-sm-3 col-form-label text-sm-right">Name: <span class="text-danger">*</span></label>
					<div class="col-sm-9">
						<!-- First display the actual name of the player by grabbing the 'name' attribute from the saved row -->
						<input type="text" class="form-control" id="name-id" name="name" value="<?php echo $row_player['name']; ?>">
					</div>
				</div> <!-- .form-group -->

				<!-- Next, we want to create dropdown menus for team and position (since there are only a specified number of teams and positions to choose from) -->
				<div class="form-group row">
					<label for="team-id" class="col-sm-3 col-form-label text-sm-right">Team: <span class="text-danger">*</span></label>
					<div class="col-sm-9">
						<select name="team_id" id="team-id" class="form-control">
							<option value="" selected disabled>-- Select One --</option>

							<!-- Loop through each row in our results_teams table which contains all team id's and names - then see which one matches the team of our selected player and make that one the "selected" option -->
							<?php while( $row = $results_teams->fetch_assoc() ): ?>

								<?php if($row['id'] == $row_player['team']) : ?>

									<option value="<?php echo $row['id']; ?>" selected>
										<?php echo $row['team']; ?>
									</option>

								<?php else : ?>

									<!-- If it is not the actual team, still make it an option so the user can change it if they want -->
									<option value="<?php echo $row['id']; ?>">
										<?php echo $row['team']; ?>
									</option>

								<?php endif; ?>

							<?php endwhile; ?>

						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="position-id" class="col-sm-3 col-form-label text-sm-right">Position: <span class="text-danger">*</span></label>
					<div class="col-sm-9">
						<select name="position_id" id="position-id" class="form-control">
							<option value="" selected disabled>-- Select One --</option>

							<!-- Loop through each row in our results_positions table which contains all position id's and names - then see which one matches the position of our selected player and make that one the "selected" option -->
							<?php while( $row = $results_positions->fetch_assoc() ): ?>

								<?php if($row['id'] == $row_player['position']) : ?>

									<option value="<?php echo $row['id']; ?>" selected>
										<?php echo $row['position']; ?>
									</option>

								<?php else : ?>

									<!-- If it is not the actual position, still make it an option so the user can change it if they want -->
									<option value="<?php echo $row['id']; ?>">
										<?php echo $row['position']; ?>
									</option>

								<?php endif; ?>

							<?php endwhile; ?>

						</select>
					</div>
				</div> <!-- .form-group -->

				<!-- For each of the statistics, display the current AVG, OBP, and OPS in the database but allow the user to change it (as a text field) - this could be if the player gets a hit mid-season and the stats need to be updated -->
				<div class="form-group row">
					<label for="avg" class="col-sm-3 col-form-label text-sm-right">AVG:</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="avg" name="avg" value="<?php echo $row_player['AVG']; ?>">
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="obp" class="col-sm-3 col-form-label text-sm-right">OBP:</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="obp" name="obp" value="<?php echo $row_player['OBP']; ?>">
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="ops" class="col-sm-3 col-form-label text-sm-right">OPS:</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="ops" name="ops" value="<?php echo $row_player['OPS']; ?>">
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

		<!-- Same footer as all other pages -->
		<div id="footer" class="container-fluid text-center">
			Aidan Miltner Final Project &copy; 2022
		</div> <!-- close #footer -->

		<!-- Need script right before the body closes for bootstrap -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>


</body>
</html>