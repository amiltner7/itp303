<?php 
	if (!isset($_GET['id']) || empty($_GET['id'])) {
		$error = "Invalid URL";
		exit();
	}

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

	$mysqli->set_charset('utf8');

	// 2. Perform SQL Queries - same as in add_form.php
	$sql_genres = "SELECT * FROM genres;";
	$results_genres = $mysqli->query($sql_genres);
	if ($results_genres == false) {
		echo $mysqli->error;
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

	// Get the id of the dvd that the user wants to edit (passed into URL in details.php)
	$id = $_GET['id'];

	// Create another SQL statement to get all information about the specific movie the user selected so that we can pre-fill all attributes with correct data about the movie and then allow the user to change what they want about it
	$sql_dvd = "SELECT *
				FROM dvd_titles
				WHERE dvd_title_id = $id;";

	// Query the sql statement
	$results_dvd = $mysqli->query($sql_dvd);

	// Grab the row (there will only be one because dvd_title_id is primary key)
	$row_dvd = $results_dvd->fetch_assoc();

	// 3. Close the connection
	$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Form | DVD Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<style>
		.form-check-label {
			padding-top: calc(.5rem - 1px * 2);
			padding-bottom: calc(.5rem - 1px * 2);
			margin-bottom: 0;
		}
	</style>
</head>
<body>

	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item"><a href="search_results.php">Results</a></li>
		<li class="breadcrumb-item"><a href='details.php?id=<?php echo $id; ?>'>Details</a></li>
		<li class="breadcrumb-item active">Edit</li>
	</ol>

	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Edit a DVD</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class="container">

			<!-- If the error is set (which means we had an issue with the id in details.php) then echo the error message -->
			<?php if (isset($error) && !empty($error)) : ?>

				<div class="text-danger font-italic"><?php echo $error ?></div>

			<?php endif; ?>

			<!-- When the form submits we want to go to edit_confirmation.php and use POST to send our information since there is a lot of it (we don't want it all in URL) and it is sensitive -->
			<form action="edit_confirmation.php" method="POST">

				<!-- First input our id so we can get it through POST in edit_confirmation.php - use hidden so we don't actually display it on the page -->
				<input type="hidden" name="id" value="<?php echo $id ?>">

				<div class="form-group row">
					<label for="title-id" class="col-sm-3 col-form-label text-sm-right">Title: <span class="text-danger">*</span></label>
					<div class="col-sm-9">
						<!-- Display the movie title and POST the value as well so we can use it in edit_confirmation -->
						<input type="text" class="form-control" id="title-id" name="title" value="<?php echo $row_dvd['title']; ?>">
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="release-date-id" class="col-sm-3 col-form-label text-sm-right">Release Date:</label>
					<div class="col-sm-9">
						<!-- Same as title but for release_date -->
						<input type="date" class="form-control" id="release-date-id" name="release_date" value="<?php echo $row_dvd['release_date']; ?>">
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="label-id" class="col-sm-3 col-form-label text-sm-right">Label:</label>
					<div class="col-sm-9">
						<select name="label" id="label-id" class="form-control">
							<option value="" selected disabled>-- Select One --</option>

							<!-- Loop through each row in our results_labels table which contains all label id's and names - then see which one matches the label of our actual dvd the user selected and make that one the "selected" option -->
							<?php while( $row = $results_labels->fetch_assoc() ): ?>

								<!-- row is labels of all them, row_dvd is the row of our specifically chosen dvd - check if labels are the same and if they are, make that label name the selected option -->
								<?php if($row['label_id'] == $row_dvd['label_id']) : ?>

								<!-- Selected selects sometehing by default -->
								<option value="<?php echo $row['label_id']; ?>" selected>
									<?php echo $row['label']; ?>
								</option>

								<?php else : ?>

								<!-- If it is not the actual label, still make it an option so the user can change it if they want -->
								<option value="<?php echo $row['label_id']; ?>">
									<?php echo $row['label']; ?>
								</option>

								<?php endif; ?>

							<!-- SAME PROCESS FOR ALL OTHER DROPDOWN MENUS -->
							<?php endwhile; ?>

						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="sound-id" class="col-sm-3 col-form-label text-sm-right">Sound:</label>
					<div class="col-sm-9">
						<select name="sound" id="sound-id" class="form-control">
							<option value="" selected disabled>-- Select One --</option>

							<?php while( $row = $results_sounds->fetch_assoc() ): ?>

								<!-- same as above but for sounds -->
								<?php if($row['sound_id'] == $row_dvd['sound_id']) : ?>

								<option value="<?php echo $row['sound_id']; ?>" selected>
									<?php echo $row['sound']; ?>
								</option>

								<?php else : ?>

								<option value="<?php echo $row['sound_id']; ?>">
									<?php echo $row['sound']; ?>
								</option>

								<?php endif; ?>

							<?php endwhile; ?>

						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="genre-id" class="col-sm-3 col-form-label text-sm-right">Genre:</label>
					<div class="col-sm-9">
						<select name="genre" id="genre-id" class="form-control">
							<option value="" selected disabled>-- Select One --</option>

							<?php while( $row = $results_genres->fetch_assoc() ): ?>

								<!-- same as above but for genres -->
								<?php if($row['genre_id'] == $row_dvd['genre_id']) : ?>

								<option value="<?php echo $row['genre_id']; ?>" selected>
									<?php echo $row['genre']; ?>
								</option>

								<?php else : ?>

								<option value="<?php echo $row['genre_id']; ?>">
									<?php echo $row['genre']; ?>
								</option>

								<?php endif; ?>

							<?php endwhile; ?>

						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="rating-id" class="col-sm-3 col-form-label text-sm-right">Rating:</label>
					<div class="col-sm-9">
						<select name="rating" id="rating-id" class="form-control">
							<option value="" selected disabled>-- Select One --</option>

							<?php while( $row = $results_ratings->fetch_assoc() ): ?>

								<!-- same as above but for ratings -->
								<?php if($row['rating_id'] == $row_dvd['rating_id']) : ?>

								<option value="<?php echo $row['rating_id']; ?>" selected>
									<?php echo $row['rating']; ?>
								</option>

								<?php else : ?>

								<option value="<?php echo $row['rating_id']; ?>">
									<?php echo $row['rating']; ?>
								</option>

								<?php endif; ?>

							<?php endwhile; ?>

						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="format-id" class="col-sm-3 col-form-label text-sm-right">Format:</label>
					<div class="col-sm-9">
						<select name="format" id="format-id" class="form-control">
							<option value="" selected disabled>-- Select One --</option>

							<?php while( $row = $results_formats->fetch_assoc() ): ?>

								<!-- same as above but for formats -->
								<?php if($row['format_id'] == $row_dvd['format_id']) : ?>

								<option value="<?php echo $row['format_id']; ?>" selected>
									<?php echo $row['format']; ?>
								</option>

								<?php else : ?>

								<option value="<?php echo $row['format_id']; ?>">
									<?php echo $row['format']; ?>
								</option>

								<?php endif; ?>

							<?php endwhile; ?>

						</select>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="award-id" class="col-sm-3 col-form-label text-sm-right">Award:</label>
					<div class="col-sm-9">
						<!-- Award is slightly different because it is a textarea. However, it is not too complicated because whatever is between the opening and closing textarea tags will be POSTED to edit_confirmation automatically - so when the user changes something it will update. We do need to make sure that 'null' doesn't show up since lots of dvd's don't have awards so are set to null. Make an if statement to check for this and display nothing if the award is null and the actual award name if there is an award -->
						<textarea name="award" id="award-id" class="form-control" ><?php if($row_dvd['award'] == "null") {} else{echo $row_dvd['award'];} ?></textarea>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<div class="ml-auto col-sm-9">
						<span class="text-danger font-italic">* Required</span>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<div class="col-sm-3"></div>
					<div class="col-sm-9 mt-2">
						<button type="submit" class="btn btn-primary">Submit</button>
						<button type="reset" class="btn btn-light">Reset</button>
					</div>
				</div> <!-- .form-group -->

			</form>

	</div> <!-- .container -->
</body>
</html>