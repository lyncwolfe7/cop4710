<?php

/*** begin our session ***/
session_start();
include_once "includes/database-includes.php";
include_once "includes/functions-includes.php";

?>

<link rel="stylesheet" href="styles.css">
<div class="navigation">
	<ul>
		<a class = "active" href = "index.php">Home</a>
		<?php
			// Header for logged in person
			if (isset($_SESSION["user_ID"])) {
				$user_ID = $_SESSION["user_ID"];
				// Options for all users
				echo "<a href = 'myEvents.php'>My Events</a>";
				echo "<a href = 'myRsos.php'>My RSOs</a>";
				echo "<a href = 'events.php'>Events</a>";
				echo "<a href = 'rsos.php'>RSOs</a>";
				
				// Admin
				if (isAdmin($conn, $user_ID) !== FALSE) {
					echo "<a href = 'createEvent.php'>Create An Event</a>";
				}
				// Super Admin
				if (isSuperAdmin($conn, $usersId) !== FALSE) {
					echo "<a href = 'pendingEvents.php'>Pending Events</a>";
				}
				// Normal User
				if (isAdmin($conn, $usersId) === FALSE && isSuperAdmin($conn, $user_ID) === FALSE) {
					;
				}
				
				// More options for all users
				echo "<a href = 'requestEvent.php'>Request An Event</a>";
				echo "<a href = 'makeRSO.php'>Make An RSO</a>";
				echo "<a href = 'logout-includes.php'>Log Out</a>";
			}
			// Header for visiters
			else {
				echo "<a href = 'signup.php'>Sign Up</a>";
				echo "<a href = 'login.php'>Log In</a>";
			}
		?>
	</ul>
</div>