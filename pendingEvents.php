<?php
	include_once "header.php";
?>

<link rel="stylesheet" href="styles.css">
<script src="javascript/jquery.min.js"></script>
<script src="javascript/PendingEvents.js"></script>

<div class="box">
	<p class="heading4">All Pending Events for Your School</p>
	<section class="events">
		<ul class="tilesWrapEvents">
			<?php
				if (isset($_SESSION["users_ID"]) AND isSuperAdmin($conn, $_SESSION["users_ID"]) !== FALSE) {
					$usersID = $_SESSION["users_ID"];
					$rsoData = getEventRequests($conn, $usersID);
					while($row = mysqli_fetch_assoc($rsoData)) {
						echo "<li>";
						$eventID = $row["eApproval_EID"];
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

						if ($eventinfo["event_RID"] !== NULL) {
							$eventRID = $eventinfo["event_RID"];
							$eventRSOname = getRSOName($conn, $eventRID);
							echo "<h2>" . $eventRSOname . "<br>" . $schoolName . "</h2>";
						}
						else {
							echo "<h2>" . $schoolName . "</h2>";
						}
						echo "<h4><b>" . $eventName . "<br>" . date('m-d-Y', $eventDateTime) . 
								"<br>" . date('ha', $eventDateTime) ."</b></h4>";
						//echo "<h4><b>TEST</b></h4>";
						echo "<p>" . $eventDesc . "<br><br>Latitude: " . $eventLat . "<br>Longitude: " . $eventLong .
							"<br><br>Phone #: ". $eventPhone . "<br><br>Visibility: " . strtoupper($eventType) . "</p>";
						echo "<button type='button' onclick='acceptEvent(" . $eventID . ")'>ACCEPT</button>";
						echo "<br>";
						echo "<button type='button' onclick='denyEvent(" . $eventID . ")'>DENY</button>";
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

<?php
	include_once "footer.php";
?>