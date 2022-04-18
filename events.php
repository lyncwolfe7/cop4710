<?php
	include_once "header.php"
?>

<link rel="stylesheet" href="styles.css">

<script src="javascript/jquery.min.js"></script>
<script src="javascript/Events.js"></script>

<div class="box">
	<div class="form5">
		<p class="heading2">All Available Events</p>
		<section class="events">
			<?php
				if (isset($_SESSION["users_ID"])) {
					$userSchool = getUsersSchool($conn, $_SESSION["users_ID"]);
					$schoolName = getSchoolName($conn, $schoolName);
				}
				else {
						header("location: index.php");
				}
			?>
			<ul class="tilesWrapEvents">
				<?php
					if (isset($_SESSION["users_ID"])) {
						$userID = $_SESSION["users_ID"];
						$eventData = displayAvailableEvents($conn, $userID);
						while($row = mysqli_fetch_assoc($eventData)) {
							echo "<li>";
							$eventID = $row["event_ID"];
							$eventInfo = getEventInfo($conn, $eventID);
							$eventName = $eventinfo["eventName"];
							$eventDesc = $eventinfo["eventDesc"];
							$eventPhone = $eventinfo["eventPhone"];
							$eventDateTime = $eventinfo["eventDateTime"];
							$eventUID = $eventinfo["event_UID"];
							$eventLID= $eventinfo["event_LID"];
							$eventLocInfo = getLocInfo($conn, $eventLID);
							$eventLat = $eventlocinfo["locLatt"];
							$eventLong = $eventlocinfo["locLong"];
							$schoolName = getSchoolName($conn, $eventUID);
							$eventDateTime = strtoTime($eventDateTime);
							$eventType = getEventType($conn, $eventID);
						
							if ($eventInfo["event_RID"] !== NULL) {
								$eventRID = $eventInfo["event_RID"];
								$eventRSOname = getRSOName($conn, $eventRID);
								echo "<h2>" . $eventRSOname . "<br>" . $schoolName . "</h2>";
							}
							else {
								echo "<h2>" . $schoolName . "</h2>";
							}
							echo "<h4><b>" . $eventName . "<br>" . date('m-d-Y', $eventDateTime) . 
									"<br>" . date('ha', $eventDateTime) ."</b></h4>";
						
							echo "<p>" . $eventDesc . "<br><br>Latitude: " . $eventLat . "<br>Longitude: " . $eventLong .
								"<br><br>Phone #: ". $eventPhone . "<br><br>Visibility: " . strtoupper($eventType) . "</p>";
							
							if (userInEvent($conn, $eventID, $userID) === TRUE) {
								echo "<button type='button'>Event Already Joined</button>";
							}
							else {
								echo "<button type='button' onclick='attemptJoinEvent(" . $eventID . ")'>Join Event</button>";
							}
							echo "</li>";
						}
					}
					else {
						header("location: index.php");
					}
				?>
			</ul>
		</section>
	</div>
</div>

<?php
	include_once "footer.php";
?>