<?php 
	$host = "303.itpwebdev.com";
	$user = "amiltner_db_user";
	$pass = "Cubs-2022!";
	$db = "amiltner_dvd_db";

	// 1. Establish MySQL Connection
	$mysqli = new mysqli($host, $user, $pass, $db);

	// Check for MySQL Connection Errors
	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	// 2. Perform SQL Queries - get everything from the genres table, ratings table, labels table, formats table, and sounds table and query them. We will grab each value from the tables further down in the code so we can add everything to our dropdown menus
	$sql_genres = "SELECT * FROM genres;";

	// Equivalent of lightning bolt in workbench - actually does operation
	$results_genres = $mysqli->query($sql_genres);

	if ($results_genres == false) {
		echo $mysqli->error;
		// Need to close it because the connection is already established when the query fails. In earlier if statement don't need to close because we never established a connection
		$mysqli->close();
		exit();
	}

	$sql_ratings = "SELECT * FROM ratings;";
	$results_ratings = $mysqli->query($sql_ratings);
	if ($results_ratings == false) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	$sql_labels = "SELECT * FROM labels;";
	$results_labels = $mysqli->query($sql_labels);
	if ($results_labels == false) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	$sql_formats = "SELECT * FROM formats;";
	$results_formats = $mysqli->query($sql_formats);
	if ($results_formats == false) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	$sql_sounds = "SELECT * FROM sounds;";
	$results_sounds = $mysqli->query($sql_sounds);
	if ($results_sounds == false) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// 3. Close the DB
	$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DVD Search Form</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item active">Search</li>
	</ol>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">DVD Search Form</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<form action="search_results.php" method="GET">
			<div class="form-group row">
				<label for="title-id" class="col-sm-3 col-form-label text-sm-right">DVD Title:</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="title-id" name="title">
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label for="genre-id" class="col-sm-3 col-form-label text-sm-right">Genre:</label>
				<div class="col-sm-9">
					<select name="genre_id" id="genre-id" class="form-control">
						<option value="" selected>-- All --</option>

						<!-- As long as there is a new row in the table, grab it and display the actual value (same for genre, rating, label, format, and sound) -->
						<?php while ($row = $results_genres->fetch_assoc()) : ?>


							<option value='<?php echo $row['genre_id']; ?>'>
								<?php echo $row['genre']; ?>
							</option>
							

						<?php endwhile; ?>


					</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label for="rating-id" class="col-sm-3 col-form-label text-sm-right">Rating:</label>
				<div class="col-sm-9">
					<select name="rating_id" id="rating-id" class="form-control">
						<option value="" selected>-- All --</option>

						<?php while ($row = $results_ratings->fetch_assoc()) : ?>


							<option value='<?php echo $row['rating_id']; ?>'>
								<?php echo $row['rating']; ?>
							</option>
							

						<?php endwhile; ?>


					</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label for="label-id" class="col-sm-3 col-form-label text-sm-right">Label:</label>
				<div class="col-sm-9">
					<select name="label_id" id="label-id" class="form-control">
						<option value="" selected>-- All --</option>

						<?php while ($row = $results_labels->fetch_assoc()) : ?>


							<option value='<?php echo $row['label_id']; ?>'>
								<?php echo $row['label']; ?>
							</option>
							

						<?php endwhile; ?>


					</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label for="format-id" class="col-sm-3 col-form-label text-sm-right">Format:</label>
				<div class="col-sm-9">
					<select name="format_id" id="format-id" class="form-control">
						<option value="" selected>-- All --</option>

						<?php while ($row = $results_formats->fetch_assoc()) : ?>


							<option value='<?php echo $row['format_id']; ?>'>
								<?php echo $row['format']; ?>
							</option>
							

						<?php endwhile; ?>


					</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label for="sound-id" class="col-sm-3 col-form-label text-sm-right">Sound:</label>
				<div class="col-sm-9">
					<select name="sound_id" id="sound-id" class="form-control">
						<option value="" selected>-- All --</option>

						<?php while ($row = $results_sounds->fetch_assoc()) : ?>


							<option value='<?php echo $row['sound_id']; ?>'>
								<?php echo $row['sound']; ?>
							</option>
							

						<?php endwhile; ?>


					</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label class="col-sm-3 col-form-label text-sm-right">Award:</label>
				<div class="col-sm-9">
					<div class="form-check form-check-inline">
						<label class="form-check-label my-1">
							<input class="form-check-input mr-2" type="radio" name="award" id="inlineCheckbox3" value="any" checked>Any
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label my-1">
							<input class="form-check-input mr-2" type="radio" name="award" id="inlineCheckbox1" value="yes">Yes
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label my-1">
							<input class="form-check-input mr-2" type="radio" name="award" id="inlineCheckbox2" value="no">No
						</label>
					</div>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label class="col-sm-3 col-form-label text-sm-right">Release Date:</label>
				<div class="col-sm-9">
					<div class="row">
						<div class="col">
							<label class="form-check-label my-1">
								From: 
								<input type="date" class="form-control mt-2" name="release_date_from">
							</label>
						</div> <!-- .col -->
						<div class="col">
							<label class="form-check-label my-1">
								to: 
								<input type="date" class="form-control mt-2" name="release_date_to">
							</label>
						</div> <!-- .col -->
					</div> <!-- .row -->
				</div> <!-- .col -->
			</div> <!-- .form-group -->
			<div class="form-group row">
				<div class="col-sm-3"></div>
				<div class="col-sm-9 mt-2">
					<button type="submit" class="btn btn-primary">Search</button>
					<button type="reset" class="btn btn-light">Reset</button>
				</div>
			</div> <!-- .form-group -->
		</form>
	</div> <!-- .container -->
</body>
</html>