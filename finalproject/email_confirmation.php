<?php 
	// First check if they actually entered anything - if not, set $error as 'Please enter email'
	if(!isset($_POST['email']) || trim($_POST['email']) == '') {
		$error = "Please enter your email.";
	}
	// If it is not empty but they didn't enter a valid email (here I am just making sure it has an @ symbol using strpos which tells us if a value exists in a string (talked with TA about this)) then tell them to enter a valid email
	elseif(strpos("'" . $_POST['email'] . "'", '@') == false){
		$error = "Please enter a valid email.";
	}
	else {
		// If they entered a valid email, we can proceed and set the destination as their actual email addressed they typed in email.html (using POST). I set the subject and message - and formatted my email using HTML
		$destination = $_POST['email'];
		$subject = 'More MLB stats';
		$message = '<h1>Thank you for visiting my website!</h1>
				<p>Hello! I hope you enjoyed looking at some MLB player statistics. I started this with only a few players from each team, so I encourage you to add new players and edit stats as necessary - it is a group effort!</p> 
				<p>For reference, here is the <a href="https://www.mlb.com/stats/regular-season">link to the MLB stats website</a> that I used to get my statistics. Please feel free to look through their page and do your own analysis - and add it to my website if you feel so inclined!</p>
				<p>Thank you for your time and I hope you enjoyed my website!</p><br>
				<p>All the best,</p>
				<p>Aidan</p>';
		// We need to specify that it is html in the header, and I set the from and reply-to as my email so people can reach out to me if they need to
		$header = [
			"content-type" => "text/html",
			"from" => "amiltner@usc.edu",
			"reply-to" => "amiltner@usc.edu"
		];

		// Actually send the email - if it works, give a confirmation message, and it not display an error
		if ( mail($destination, $subject, $message, $header) ) {
			$result = 'Success! Your email was sent to ' . $destination;
		} else {
			$result = 'Error';
		}
	}

?>


<!DOCTYPE html>
<html>
<head>

	<!-- We need the meta tag and external href to use bootstrap -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

	<title>Email Confirmation</title>
	<!-- Link main.css so we have consistent styling -->
	<link rel="stylesheet" href="main.css">


</head>
<body>

	<!-- Same menu bar on every page for consistency -->
	<ul id="menu_bar">
		<li><a href="home.php"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Major_League_Baseball_logo.svg/1200px-Major_League_Baseball_logo.svg.png" alt="MLB Logo"></a></li>
		<li>
			<a href="add_player.php">Add Player</a>
		</li>
		<!-- Make this active-nav since we are emailing -->
		<li><a id="active-nav" href="email.html">Email me more</a></li>
	</ul>

	<div class="container">
			<div id="exp" class="col-12">
				<!-- If there is an error, display it -->
				<?php if (isset($error) && !empty($error)) : ?>

					<div class="text-danger">
						<?php echo $error ?>
					</div>

				<!-- If the email was successfully sent, let the user know -->
				<?php else : ?>

					<span class="font-italic"><?php echo $result; ?></span>

				<?php endif; ?>

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


