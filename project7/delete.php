<?php
	// Check to make sure id is provided.
	if ( !isset($_GET['id']) || trim($_GET['id']) == '' ) {
		// Missing track_id;
		$error = "Invalid URL.";
	} else {
		// Valid URL w/ track_id.

		$host = "303.itpwebdev.com";
		$user = "amiltner_db_user";
		$pass = "Cubs-2022!";
		$db = "amiltner_dvd_db";

		// Establish MySQL Connection.
		$mysqli = new mysqli($host, $user, $pass, $db);

		// Check for any Connection Errors.
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		// Get the DVD ID that we passed into the URL in search_results.php - this is the movie the user wanted to delete
		$id = $_GET['id'];

		// Delete the movie with the correct id
		$sql = "DELETE FROM dvd_titles 
				WHERE dvd_title_id = $id;";


		// echo "<hr>$sql<hr>";

		// Query the SQL delete statement
		$results = $mysqli->query($sql);

		// Check for errors
		if (!$results) {
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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Delete a DVD | DVD Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item"><a href="search_results.php">Results</a></li>
		<li class="breadcrumb-item active">Delete</li>
	</ol>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4">Delete a DVD</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<div class="row mt-4">
			<div class="col-12">

				<!-- Copied from class example, the only change is that we want to echo the title instead of the track_name when we successfully deleted a dvd - use GET method to grab the dvd title from the URL -->
				<?php if(isset($error) && trim($error) != '') : ?>

				<div class="text-danger">
					<?php echo $error; ?>
				</div>

				<?php else : ?>

				<div class="text-success"><span class="font-italic"><?php echo $_GET['title']; ?></span> was successfully deleted.</div>

				<?php endif; ?>

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