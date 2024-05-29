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

	// Set encoding so we don't have weird characters
	$mysqli->set_charset('utf8');

	// 2. Perform SQL query - get id, title, release_date, genre, and rating by joining tables accordingly - set where statement as 1=1 for now and then we will add to it in our if statements below depending on if the movie actually as values for the attributes
	$sql = "SELECT dvd_title_id AS id, title, release_date, genres.genre AS genre, ratings.rating AS rating
			FROM dvd_titles
			LEFT JOIN genres ON dvd_titles.genre_id = genres.genre_id
			LEFT JOIN ratings ON dvd_titles.rating_id = ratings.rating_id
			WHERE 1=1";

	// Check if title, genre, and rating id are set in the search form and add to the sql query if so 
	if (isset($_GET['title']) && !empty($_GET['title'])) {
		// Grab the value from the search_form and assign it to a variable
		$title = $_GET['title'];
		// can inject it with double quotes
		$sql = $sql . " AND dvd_titles.title LIKE '%$title%'";
	}

	if (isset($_GET['genre_id']) && !empty($_GET['genre_id'])) {
		$genre_id = $_GET['genre_id'];
		// can inject it with double quotes
		$sql = $sql . " AND dvd_titles.genre_id = $genre_id";
	}

	if (isset($_GET['rating_id']) && !empty($_GET['rating_id'])) {
		$rating_id = $_GET['rating_id'];
		// can inject it with double quotes
		$sql = $sql . " AND dvd_titles.rating_id = $rating_id";
	}

	if (isset($_GET['format_id']) && !empty($_GET['format_id'])) {
		$format_id = $_GET['format_id'];
		// can inject it with double quotes
		$sql = $sql . " AND dvd_titles.format_id = $format_id";
	}

	if (isset($_GET['sound_id']) && !empty($_GET['sound_id'])) {
		$sound_id = $_GET['sound_id'];
		// can inject it with double quotes
		$sql = $sql . " AND dvd_titles.sound_id = $sound_id";
	}

	if (isset($_GET['label_id']) && !empty($_GET['label_id'])) {
		$label_id = $_GET['label_id'];
		// can inject it with double quotes
		$sql = $sql . " AND dvd_titles.label_id = $label_id";
	}

	if (isset($_GET['award']) && !empty($_GET['award'])) {
		if ($_GET['award'] == 'yes') {
			$sql = $sql . " AND dvd_titles.award IS NOT NULL";
		}
		else if ($_GET['award'] == 'no')
			$sql = $sql . " AND dvd_titles.award IS NULL";
	}

	// End sql statement with semicolon
	$sql = $sql . ";";

	// Query the statement
	$results = $mysqli->query($sql);

	// Check for errors - note: equivalent to $results == false
	if (!$results) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// 3. Close MySQL Connection
	$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DVD Search Results</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4">DVD Search Results</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<div class="row mb-4">
			<div class="col-12 mt-4">
				<a href="search_form.php" role="button" class="btn btn-primary">Back to Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row">
			<div class="col-12">

				<!-- Use num_rows to get the total number of results in our sql query -->
				Showing <?php echo $results->num_rows; ?> result(s).

			</div> <!-- .col -->
			<div class="col-12">
				<table class="table table-hover table-responsive mt-4">
					<thead>
						<tr>
							<th>DVD Title</th>
							<th>Release Date</th>
							<th>Genre</th>
							<th>Rating</th>
						</tr>
					</thead>
					<tbody>

						<!-- While there is another row in our query, grab the title, release date, genre, and rating to display on the page (using echo) -->
						<?php while ($row = $results->fetch_assoc()) : ?>
							<tr>
								<td>
									<!-- Delete button copied from in-class example. We pass the dvd id and title into the URL so that we can GET them in delete.php to delete the correct dvd id and display the title when confirming it was deleted. Note: onlick is inline javascript. Confirm gives us OK or CANCEL button in popup. OK = return true, goes to next page. CANCEL = return false, stays on page -->
									<a href="delete.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['title']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this movie?')">
										Delete
									</a>
								</td>
								<td>
									<!-- When we click on each title, we want to go to details.php and display the full information about the movie. So, we use an anchor tag with details.php as the href - but we also need to put in the actual value for that dvd_title_id so we can GET it in details.php and grab the corresponding information about that movie. So open a php tag and echo the id from that row.-->
									<a href='details.php?id=<?php echo $row['id']?>'> <?php echo $row['title']; ?> </a>
								</td>
								<td>
									<?php echo $row['release_date']; ?>
								</td>
								<td>
									<?php echo $row['genre']; ?>
								</td>
								<td>
									<?php echo $row['rating']; ?>
								</td>
							</tr>

						<?php endwhile; ?>

					</tbody>
				</table>
			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="search_form.php" role="button" class="btn btn-primary">Back to Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container -->
</body>
</html>