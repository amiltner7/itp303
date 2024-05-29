<?php 
	// Check if the id was actually inputted in the url - if not don't connect to db because we cannot get the information about that movie - we will also display an error message if this happens
	if ( !isset($_GET['id']) || trim($_GET['id']) == '' ) {
		// Track ID is missing.
		$error = "Invalid URL.";
		exit();
	}
	else {
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

		// Set encoding so we don't have weird characters
		$mysqli->set_charset('utf8');

		// First grab the id from the URL using GET and store it in a variable
		$id = $_GET['id'];

		// Then make our sql statement by grabbing everything - id, title, release date, genre, label, rating, sound, format, and award (using join statements with all the tables) where the dvd_title_id is equal to the id of the movie the user clicked on
		$sql = "SELECT dvd_title_id, title, release_date, genres.genre AS genre, labels.label AS label, ratings.rating AS rating, sounds.sound AS sound, formats.format AS format, award
			FROM dvd_titles
			LEFT JOIN formats ON dvd_titles.format_id = formats.format_id
	    	LEFT JOIN genres ON dvd_titles.genre_id = genres.genre_id
	    	LEFT JOIN labels ON dvd_titles.label_id = labels.label_id
	    	LEFT JOIN ratings ON dvd_titles.rating_id = ratings.rating_id
	    	LEFT JOIN sounds ON dvd_titles.sound_id = sounds.sound_id
	    	WHERE dvd_titles.dvd_title_id = $id;";

	    // Query the sql statement
	    $results = $mysqli->query($sql);

	    // Check for errors in our sql query
	    if (!$results) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// We only have one row since we want information about one movie so we don't need a while statement like before - instead, we can just fetch the one row and store it in $row variable
		$row = $results->fetch_assoc();

		// Close connection
		$mysqli->close();
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Details | DVD Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item"><a href="search_results.php">Results</a></li>
		<li class="breadcrumb-item active">Details</li>
	</ol>

	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4">DVD Details</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class="container">

		<div class="row mt-4">
			<div class="col-12">

				<!-- If the error is set (which means we had an issue with the id in search_results.php) then echo the error message -->
				<?php if (isset($error) && !empty($error)) : ?>

				<div class="text-danger font-italic"><?php echo $error ?></div>

				<?php endif; ?>

				<table class="table table-responsive">

					<tr>
						<th class="text-right">Title:</th>
						<!-- Then echo each value from our row (title, release_date, genre, label, rating, sound, format, and award). Note: if one of these values is null it is okay because it just won't display anything -->
						<td><?php echo $row['title']; ?></td>
					</tr>

					<tr>
						<th class="text-right">Release Date:</th>
						<td><?php echo $row['release_date']; ?></td>
					</tr>

					<tr>
						<th class="text-right">Genre:</th>
						<td><?php echo $row['genre']; ?></td>
					</tr>

					<tr>
						<th class="text-right">Label:</th>
						<td><?php echo $row['label']; ?></td>
					</tr>

					<tr>
						<th class="text-right">Rating:</th>
						<td><?php echo $row['rating']; ?></td>
					</tr>

					<tr>
						<th class="text-right">Sound:</th>
						<td><?php echo $row['sound']; ?></td>
					</tr>

					<tr>
						<th class="text-right">Format:</th>
						<td><?php echo $row['format']; ?></td>
					</tr>

					<tr>
						<th class="text-right">Award:</th>
						<td><?php echo $row['award']; ?></td>
					</tr>

				</table>


			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="search_results.php" role="button" class="btn btn-primary">Back to Search Results</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container -->
</body>
</html>