<?php

/*** begin our session ***/
session_start();
include_once "includes/dbh-include.php";
include_once "includes/functions-include.php";

?>

<link rel="stylesheet" href="styles.css">
<div class="navigation">
	<ul>
		<a class = "active" href = "index.php">Home</a>
		<?php
			if (isset($_SESSION["user_ID"])) {
				$user_ID = $_SESSION["user_ID"];
				echo "<a href = 'myEvents.php'>My Events</a>";
				echo "<a href = 'myRsos.php'>My RSOs</a>";
				echo "<a href = 'events.php'>Events</a>";
				echo "<a href = 'rsos.php'>RSOs</a>";
				
				if (isAdmin($conn, $user_ID) !== FALSE) {
					echo "<a href = 'createEvent.php'>Create Event</a>";
				}
				if (isSuperAdmin($conn, $user_ID) !== FALSE) {
					echo "<a href = 'pendingEvents.php'>Pending Events</a>";
				}
				if (isAdmin($conn, $user_ID) === FALSE && isSuperAdmin($conn, $user_ID) === FALSE) {
					;
				}

				echo "<a href = 'eventRequest.php'>Request Event</a>";
				echo "<a href = 'createRSO.php'>Make RSO</a>";
				echo "<a href = 'logout-include.php'>Log Out</a>";
			}
			else {
				echo "<a href = 'signup.php'>Sign Up</a>";
				echo "<a href = 'login.php'>Log In</a>";
			}
		?>
	</ul>
</div>