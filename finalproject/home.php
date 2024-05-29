<?php
	// First, I want to require that my config.php file is included because this stores my host, username, password, and database name that I need to connect to the mysql server. Note: it is in the config folder 
	require "config/config.php";
	// Then I create my mysql connection using the host, username, password, and database name constants I defined in config.php
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	// Check for errors and exit if so
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	// Set default character set
	$mysqli->set_charset('utf8');

	// For my homepage, I only have 1 sql query because I just need all player names in a dropdown menu - so get all information from the players table
	$sql_players = "SELECT * FROM players;";

	// Query the result (like clicking lightning bolt in workbench)
	$results_players = $mysqli->query($sql_players);

	// Check for SQL errors and close the connection/exit if so
	if ( !$results_players ) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

	// Close the mysql connection
	$mysqli->close();

?>


<!DOCTYPE html>
<html>
<head>

	<!-- We need the meta tag and external href to use bootstrap -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

	<title>Homepage</title>
	<!-- I am using external css to have consistency across all of my pages, so link the main.css file -->
	<link rel="stylesheet" href="main.css">
	<style>

		/* I want my image to have some space between it and the text below */
		#head_img {
			margin-bottom: 20px;
		}

		/* Center all h1 tags (I only have one in this case) */
		h1 {
			text-align: center;
		}

		/* I want my search section to have a rounded border and center the text within. Also separate it from the exp section by having a margin. I want a set height for the section because I only have my form within it. */
		#search {
			border: 2px solid #dbdbdb;
			border-radius: 25px;
			text-align: center;
			margin-left: 50px;
			margin-top: 50px;

			height: 350px;

			margin-bottom: 100px;
		}

		/* There is default styling for exp in main.css, but on this page I want to add a larger bottom margin so that it shows over the footer */
		#exp {
			margin-bottom: 70px;
		}

		/* I want text to be aligned to the left for the text in the explanation (exp) section - there are only <p> and <ul> tags so this will suffice  */
		p, ul {
			text-align: left;
		}

		/* Separate the submit and reset buttons from the form 20px */
		.buttons {
			margin-top: 20px;
		}

		/* Center the form within the search section */
		form {
			margin-top: 50px;
			margin-bottom: 100px;
		}

		/* For medium/small devices, I want to remove the left margin that separates the exp and search sections on large devices because they will move below one another  */
		@media (max-width: 992px) {
			#news{
				margin-left: 0px;
			}
		}
	
	</style>

</head>
<body>

	<!-- To make our menu bar, we need to create an unordered list, which I will call "menu_bar" -->
	<ul id="menu_bar">
		<!-- Then we create our first list item, which is for our home page. I made it the MLB logo to look like the mlb website, but you can still click on it to get back to the home page (this page) -->
		<li><a id="active-nav" href="home.php"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Major_League_Baseball_logo.svg/1200px-Major_League_Baseball_logo.svg.png" alt="MLB Logo"></a></li>
		<!-- The second menu option is to add a player - so link the add_player.php page -->
		<li><a href="add_player.php">Add Player</a></li>
		<!-- Finally, the user can email themselves more information, so link the email.html page -->
		<li><a href="email.html">Email me more</a></li>
	</ul>

	<!-- Outside container that will hold everything -->
	<div class="container">
		<!-- Row that will hold explanation and search sections -->
		<div class="row">
			<!-- First section is for the explanation - it will span 7 columns on large devices and 12 columns for small devices -->
			<div id="exp" class="col-lg-7 col-sm-12">
				<h1>Welcome!</h1>
				<!-- Here, I explain how the website works and how the different statistics are calculated -->
				<img id="head_img" src=img/scoreboard.jpeg class="col-10" alt="Scoreboard">
				<p>This website allows you to view and analyze MLB player statistics, along with other functionality! Start by selecting a player from the dropdown menu to your right and clicking ‘Submit’ - this will take you to a separate page where you can see that player’s AVG, OBP, and OPS. If you’re wondering what these mean or how they are calculated, here is a brief description:</p>
				<ul>
					<li>AVG: Batting Average - measures how often a player gets a hit; it is calculated by dividing a player’s hits by his total at-bats (gives a number between zero/.000 and one/1.000). <br><b>AVG = Hits / At Bats</b></li>
					<li>OBP: On Base Percentage - measures how often a player reaches base; it is calculated by diving the number of times on base by the number of plate appearances. <br><b>OBP = (Hits + Walks + Hit by Pitch) / (At Bats + Walks + Hit by Pitch + Sacrifice Flies)</b></li>
					<li>OPS: On-base Plus Slugging - a modern baseball statistic used to combine how often a player gets on base and how much power he has; it is calculated by adding a player’s On Base Percentage (OBP) and Slugging Percentage (SLG). <br><b>OPS = OBP + SLG (see OBP formula above, SLG measures power and gives more weight to extra base hits (doubles/2B, triples/3B, and home runs/HR). SLG = (1B + 2*2B + 3*3B + 4*HR) / At Bats)</b></li>
				</ul>
				<p>You can click on any of the statistics to get a more granular view. Specifically, you are able to see the average of the statistic you chose among all players with that player’s position, and among all teammates of that player. Below, you will see that player highlighted and can compare with all other individual players in the database.</p>
				<p>The database only includes 2 players from each team, so it is up to you to add new ones and grow the database! Click the “Add Player” tab and enter a player’s Name, Team, Position, and stats (if you have them) in order to add that player to the database! Also, feel free to edit players or delete players if their stats change or they retire/are no longer relevant by selecting the player from the homepage and clicking ‘Edit Player’ or ‘Delete Player’ on the following page.</p>
				<p>Finally, if you are still curious and want more information, or if you want to see where I got my statistics in order to add/edit players, click the ‘Email me more’ tab and enter your email to get an email with more information! Thank you for visiting my page and I hope you enjoy analyzing these MLB statistics!</p>
			</div>

			<!-- The search section is next to the explanation section and spans 4 columns for large devices (so it shows up in one row with exp) and 12 columns for small devices (full width of page below exp section) -->
			<div id="search" class="col-lg-4 col-sm-12">
				<!-- Inside the search div, I have my search form - I will pass the id of the player into the URL of results.php so we use the GET method -->
				<form class="col-12" action="results.php" method="GET">

					<!-- Title of the form -->
					<div class="form-group row">
						<div class="col-form-label">
							<h2>Select a Player</h2>
						</div>
					</div>

					<!-- I am allowing the user to pick a player from the dropdown menu -->
					<div class="form-group row">
						<label for="player-id" class="col-sm-3 col-form-label text-sm-right">Name: <span class="text-danger">*</span></label>
						<div class="col-sm-9">
							<select name="player_id" id="player-id" class="form-control">
								<option value="" selected disabled>-- Select One --</option>

								<!-- Go through each row in the sql query for players and add each player name as a value in our dropdown menu -->
								<?php while ($row = $results_players->fetch_assoc()) : ?>

								<!-- Echo the id of the selected player to be put in the URL so that results.php can get the stats about that specific player -->
								<option value="<?php echo $row['id']; ?>">
									<!-- Echo the player name as the option -->
									<?php echo $row['name']; ?>
								</option>

								<?php endwhile; ?>

							</select>
						</div>
					</div> <!-- .form-group -->

					<!-- This is a required field (they must select a player) -->
					<div class="form-group row">
						<div class="ml-auto col-sm-9">
							<span class="text-danger font-italic">* Required</span>
						</div>
					</div> <!-- .form-group -->

					<!-- Submit and reset buttons below -->
					<div class="buttons form-group row">
						<div class="col-11">
						<!-- <div class="col-sm-9 mt-2"> -->
							<button type="submit" class="btn btn-primary mr-1">Submit</button>
							<button type="reset" class="btn btn-light">Reset</button>
						<!-- </div> -->
						</div>
					</div> <!-- .form-group -->
				</form>
			</div>
		</div>
	</div>

	<!-- Same footer as assignment 2. We set it as container-fluid so it spans across the whole page, and we want the text centered -->
	<div id="footer" class="container-fluid text-center">
		Aidan Miltner Final Project &copy; 2022
	</div> <!-- close #footer -->

	<!-- Need script right before the body closes for bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>


</body>
</html>


