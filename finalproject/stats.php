<?php 
	// Require config.php so I have constants to open my sql connection
	require "config/config.php";
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	$mysqli->set_charset('utf8');

	// Since we passed in all of the data into the URL, we can use the GET method to get the actual values - store the statistic (AVG, OBP, OPS) that they clicked on, as well as the position, team, and id of the selected player 
	$stat = $_GET['stat'];
	$position = $_GET['position'];
	$team = $_GET['team'];
	$id = $_GET['id'];

	// Use SQL aggregate functions to group players by team and then get the team id and average of the chosen statistic (AVG, OBP, OPS) where the team is the team of the selected player. In other words, this will get the average AVG, OBP, or OPS among the player's teammates
	$sql_teams = "SELECT teams.id AS team, AVG($stat) as stat
	FROM players 
	LEFT JOIN teams ON players.team = teams.id
	GROUP BY team
	HAVING teams.id = $team;";

	// Query and check for errors
	$results_teams = $mysqli->query($sql_teams);

	if ( !$results_teams ) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// Do the same thing as above but for positions instead of teams. In other words, this will get the average AVG, OBP, or OPS among players with the same position as the selected player
	$sql_positions = "SELECT positions.id AS position, AVG($stat) as stat
	FROM players 
	LEFT JOIN positions ON players.position = positions.id
	GROUP BY position
	HAVING positions.id = $position;";

	// Query and check for errors
	$results_positions = $mysqli->query($sql_positions);

	if ( !$results_positions ) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// Finally, select the name, team, position, and AVG, OBP, or OPS from the players table (joined with teams and positions so we can get the actual team name and position name rather than id's) excluding the selected player - order by DESC so we can show the players with the highest AVG/OBP/OPS first 
	$sql_players = "SELECT name, teams.team as team, positions.position as position, $stat as stat 
	FROM players
	LEFT JOIN teams ON players.team = teams.id 
	LEFT JOIN positions ON players.position = positions.id
	WHERE players.id != $id
	ORDER BY stat DESC;";

	// Query and check for errors
	$results_players = $mysqli->query($sql_players);

	if ( !$results_players ) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// Pagination: First get the # of results and set the number of results per page as 10
	$total_results = $results_players->num_rows;
	$results_per_page = 10;

	// Get the last page by dividing the total results by 10
	$last_page = ceil($total_results / $results_per_page);

	// If the page is already set (meaning we are not on the first page), set the current page to the page we are on. Otherwise set it to 1
	if ( isset($_GET['page']) && trim($_GET['page']) != '' ) {
		$current_page = $_GET['page'];
	} else {
		$current_page = 1;
	}

	// Go back to first page by default
	if ($current_page < 1 || $current_page > $last_page) {
		$current_page = 1;
	}

	// We need a start index in our LIMIT function - so set the start index to be the current page minus 1 times 10 (results per page)
	$start_index = ($current_page - 1) * $results_per_page;

	// Take off the semicolon so we can add the LIMIT to our query
	$sql_players = rtrim($sql_players, ';');

	// Append the query to have the LIMIT clause with the start index and # of results per page
	$sql_players = $sql_players . " LIMIT $start_index, $results_per_page;";

	// Re-query and check for errors
	$results_players = $mysqli->query($sql_players);

	if ( !$results_players ) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// Finally, get the name, team, position, and AVG, OBP, or OPS of the selected player so we can highlight them and compare with the rest of the players
	$sql_selected = "SELECT name, teams.team as team, positions.position as position, $stat as stat 
	FROM players
	LEFT JOIN teams ON players.team = teams.id 
	LEFT JOIN positions ON players.position = positions.id
	WHERE players.id = $id;";

	// Query and check for errors
	$results_selected = $mysqli->query($sql_selected);

	if ( !$results_selected ) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// Use fetch_assoc() to get the row corresponding to the average stat among teams and positions
	$row_teams = $results_teams->fetch_assoc();
	$row_positions = $results_positions->fetch_assoc();

	// Close the connection
	$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Stats</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- Link main.css for consistent styling -->
	<link rel="stylesheet" href="main.css">

	<style>

		/* Same styling for the circles as results.php */
		#circles {
			width: 1000px;
			margin-left: auto;
			margin-right: auto;
			margin-top: 50px;
			margin-bottom: 100px;

			display: flex;
			align-items: flex-start;
			justify-content: center;
			flex-wrap: wrap;
		}

		.circle {
			border-radius: 50%;
			width: 300px;
			height: 300px;

			margin-left: 20px;

			overflow: hidden;
			position: relative;
		}

		.circle > img {
			width: auto;
			height: 100%;
		}

		.caption {
			position: absolute;

			color: #21224d;
			font-size: 30px;

			border-radius: 50%;
			width: 300px;
			height: 300px;

			text-align: center;
			padding-top: 120px;

			background-color: rgba(255,255,255,0.7);

			visibility: hidden;
		}

		.circle:hover .caption {
			visibility: visible;
		}

		/* Set the color of the titles to be navy, center them within the div	*/
		.titles {
			color: #21224d;
			text-align: center;
		}

		/*	Center and ensure it is not covered by footer	*/
		#tab {
			text-align: center;
			margin-bottom: 20px;
		}

		/*	Set margin-bottom so it is not covered by footer	*/
		#navbar {
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

	<div id="circles" class="col-12">
		<div>
			<!-- Echo the stat (AVG, OBP, or OPS) that we are referring to, as well as the team name -->
			<p class="titles">Average <?php echo $stat; ?> among <?php echo $_GET['tm_name']; ?> players</p>
			<div class="circle">
				<!-- Echo the actual statistic rounded to 3 decimal places -->
				<div class="caption"><b><?php echo round($row_teams['stat'], 3); ?></b></div>
				<!-- I put a default image of a pitcher here for visual appeal to cover the div -->
				<img src=img/degrom.jpeg alt="Jacob deGrom">
			</div>
		</div>

		<div>
			<!-- Echo the stat (AVG, OBP, or OPS) that we are referring to, as well as the position -->
			<p class="titles">Average <?php echo $stat; ?> among <?php echo $_GET['pos_name']; ?></p>	
			<div class="circle">
				<!-- Echo the actual statistic rounded to 3 decimal places -->
				<div class="caption"><b><?php echo round($row_positions['stat'], 3); ?></b></div>
				<!-- I put a default image of a hitter here for visual appeal to cover the div -->
				<img src=img/soto.webp alt="Juan Soto">
			</div>
		</div>
	</div>

	<!-- Here I have a small table to display the rest of the players so the user can compare with the selected player -->
	<table id="tab" class="table table-hover table-responsive mt-4 d-flex justify-content-center">
		<tr>
			<th>Name</th>
			<th>Team</th>
			<th>Position</th>
			<th><?php echo $stat; ?></th>
		</tr>
		<!-- First display the selected player at the top and highlight it using table-warning class -->
		<?php while ($row = $results_selected->fetch_assoc()) : ?>
			<tr class="table-warning">
				<td>
					<?php echo $row['name']; ?>
				</td>
				<td>
					<?php echo $row['team']; ?>
				</td>
				<td>
					<?php echo $row['position']; ?>
				</td>
				<td>
					<?php echo $row['stat']; ?>
				</td>
			</tr>
		<?php endwhile; ?>

		<!-- Then display every other player and their information -->
		<?php while ($row = $results_players->fetch_assoc()) : ?>
			<tr>
				<td>
					<?php echo $row['name']; ?>
				</td>
				<td>
					<?php echo $row['team']; ?>
				</td>
				<td>
					<?php echo $row['position']; ?>
				</td>
				<td>
					<?php echo $row['stat']; ?>
				</td>
			</tr>
		<?php endwhile; ?>
	</table>

	<!-- This is our pagination bar that allows the user to sort through different players (10 per page) - we did this in class together -->
	<div id="navbar" class="col-12">
		<nav aria-label="Page navigation example">
			<ul class="pagination justify-content-center">
				<!-- The first element is always 'First' and links to the first 10 players (originally disabled til we go past the first page) -->
				<li class="page-item <?php if ($current_page <= 1) { echo 'disabled'; } ?>">
					<a class="page-link" href="<?php
					$_GET['page'] = 1;
					echo $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET);
				?>">First</a>
				</li>
				<!-- The second element goes from the current page -1 (but is originally disabled until we go past the first page) -->
				<li class="page-item <?php if ($current_page <= 1) { echo 'disabled'; } ?>">
					<a class="page-link" href="<?php
					$_GET['page'] = $current_page - 1;
					echo $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET);
				?>">Previous</a>
				</li>
				<!-- The third option shows the current page -->
				<li class="page-item active">
					<a class="page-link" href=""><?php echo $current_page; ?></a>
				</li>
				<!-- The fourth option goes from the current page +1 -->
				<li class="page-item <?php if ($current_page >= $last_page) { echo 'disabled'; } ?>">
					<a class="page-link" href="<?php
					$_GET['page'] = $current_page + 1;
					echo $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET);
				?>">Next</a>
				</li>
				<!-- The last option goes to the last 10 players -->
				<li class="page-item <?php if ($current_page >= $last_page) { echo 'disabled'; } ?>">
					<a class="page-link" href="<?php
					$_GET['page'] = $last_page;
					echo $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET);
				?>">Last</a>
				</li>
			</ul>
		</nav>
	</div> <!-- .col -->




	<!-- Same footer on every page -->
	<div id="footer" class="container-fluid text-center">
		Aidan Miltner Final Project &copy; 2022
	</div> <!-- close #footer -->


</body>
</html>