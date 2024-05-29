<?php 
	// If the user didn't input a title (required field), create an error message variable that will be displayed on the page. Don't connect to db if so
	if(!isset($_POST['title']) || trim($_POST['title']) == '') {
		$error = "Please fill out all required fields.";
	}
	else {
		// Everything valid - perform db operations
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

		// var_dump($_POST);

		// We can grab the title and id from edit_form and put it in a variable because we know the user has to input a title (required field) and we passed in the id as a hidden field
		$id = $_POST['id'];
		$title = $_POST['title'];

		// Now check for non-required fields - we need an if/else statement for each so that we know if the user actually inputted the value - if they did create a variable with their input using POST - otherwise set it to null
		if (isset($_POST['release_date']) && trim($_POST['release_date']) != '') {
			$release_date = "'" . $_POST['release_date'] . "'";
		}
		else {
			$release_date = "null";
		}

		if (isset($_POST['label']) && trim($_POST['label']) != '') {
			$label_id = $_POST['label'];
		}
		else {
			$label_id = "null";
		}

		if (isset($_POST['sound']) && trim($_POST['sound']) != '') {
			$sound_id = $_POST['sound'];
		}
		else {
			$sound_id = "null";
		}

		if (isset($_POST['genre']) && trim($_POST['genre']) != '') {
			$genre_id = $_POST['genre'];
		}
		else {
			$genre_id = "null";
		}

		if (isset($_POST['rating']) && trim($_POST['rating']) != '') {
			$rating_id = $_POST['rating'];
		}
		else {
			$rating_id = "null";
		}

		if (isset($_POST['format']) && trim($_POST['format']) != '') {
			$format_id = $_POST['format'];
		}
		else {
			$format_id = "null";
		}

		if (isset($_POST['award']) && trim($_POST['award']) != '') {
			// Put quotes here rather than in INSERT statement because we don't want null to be in quotes if there is no value (but we want the actual value to be in a string if there is one)
			$award = "'" . $_POST['award'] . "'";
		}
		else {
			$award = "null";
		}

		// Now we use our UPDATE SQL statement to actually update the values into our db
		$sql = "UPDATE dvd_titles 
				SET dvd_title_id = $id,
					title = '$title',
					release_date = $release_date,
					label_id = $label_id,
					sound_id = $sound_id,
					genre_id = $genre_id,
					rating_id = $rating_id,
					format_id = $format_id,
					award = $award
				WHERE dvd_title_id = $id;";

		// Query the sql statement
		$result = $mysqli->query($sql);

		// Check for errors and close the connection if errors exist
		if (!$result) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}

		// echo "<pre>";
		// echo $sql;
		// echo "</pre>";

		// Close db connection
		$mysqli->close();
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Confirmation | DVD Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item"><a href="search_results.php">Results</a></li>
		<!-- Echo the ID using POST so we get back to the correct details and edit_form pages -->
		<li class="breadcrumb-item"><a href="details.php?id=<?php echo $_POST['id']; ?>">Details</a></li>
		<li class="breadcrumb-item"><a href="edit_form.php?id=<?php echo $_POST['id']; ?>">Edit</a></li>
		<li class="breadcrumb-item active">Confirmation</li>
	</ol>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4">Edit a DVD</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<div class="row mt-4">
			<div class="col-12">

				<!-- Check to see if our error message exists - meaning the user didn't input a title. If they didn't, echo the error message we defined at the top saying they need to input required fields -->
				<?php if (isset($error) && !empty($error)) : ?>

					<div class="text-danger">
						<?php echo $error ?>
					</div>

				<!-- If they did input everything right, tell them that their dvd was successfully edited -->
				<?php else : ?>

					<div class="text-success">
						<!-- Echo the actual title -->
						<span class="font-italic"><?php echo $title ?></span> was successfully updated.
					</div>

				<?php endif; ?>

			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="details.php?id=<?php echo $_POST['id']; ?>" role="button" class="btn btn-primary">Back to Details</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container -->
</body>
</html>