<?php

	include_once "header.php"

?>

<h2> Create RSO Event</h2>

<?php
	if (!isset($_SESSION["user_ID"]) OR isAdmin($conn, $user_ID) === FALSE) {
		header("location: index.php");
	}
?>

<div class="box box1">
	<div class="box-body">
		<?php
			if(isset($_GET["error"])) {
				if ($_GET["error"] == "empty_input") {
					echo "<p style='color:#500'>All fields must be filled in.<br><br></p>";
				}
				if ($_GET["error"] == "bad_stmt") {
					echo "<p style='color:#500'>Database error. Please try again.<br><br></p>";
				}
				if ($_GET["error"] == "time_place_conflict") {
					if(isset($_GET["datetime"]) AND isset($_GET["lat"]) AND isset($_GET["long"])) {
						echo "<p style='color:#500'>Event already exists on ".$_GET["datetime"].
								"<br>at Latitude= " .$_GET["lat"]. ", Longitude= ".$_GET["long"].".<br><br></p>";
					}
					else {
						echo "<p style='color:#500'>Event already exists (could not grab details).<br><br></p>";
					}
				}
				if ($_GET["error"] == "invalid_access") {
					echo "<p style='color:#500'>You do not have permission to create an event for this RSO.<br><br></p>";
				}
				if ($_GET["error"] == "none") {
					echo "<p style='color:#500'>Event submitted.<br><br></p>";
				}
			}
		?>
		<form action="includes/createevent-includes.php" method="post">
			<h4><b>Event Information</b></h4>
			<div class="inputGroup">
				<div class="rs-select2 js-select-simple select--no-search">
					<select name="rsoID" required>
						<?php
							$userid = $_SESSION["user_ID"];
							$activersodata = getActiveRSOs($conn, $userid);
							$x = 0;
							while ($row = mysqli_fetch_assoc($activersodata)) {
								$x = $x + 1;
								$rsoID = $row["rso_ID"];
								$rsoname = $row["rsoName"];
								echo "<option value=$rsoID>$rsoname</option>";
							}
							if ($x === 0) {
								echo "<option disabled='disabled' selected='selected'>NO ACTIVE RSOs</option>";
							}
							else {
								echo "<option disabled='disabled' selected='selected'>SELECT RSO...</option>";
							}
						?>
					</select>
					<div class="select-dropdown"></div>
				</div>
			</div>
			<div class="inputGroup">
				<input class="inputS1" type="text" name="eventName" placeholder="Event Name" required>
			</div>
			<div class="inputGroup">
				<input class="inputS1" type="text" name="eventDesc" placeholder="Event Description" required>
			</div>
			<div class="inputGroup">
				<input class="inputS1" type="tel" id="eventPhone" name="eventPhone" placeholder="Phone #: 123-456-7890"
					pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
			</div>
			<h4><b>DATE AND TIME</b></h4>
			<div class="inputGroup">
				<input class="inputS1" type="date" name="eventDate" placeholder="Event Date" required>
				<i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
			</div>
			<div class="row row-space">
				<div class="col-2">
					<div class="inputGroup">
						<div class="rs-select2 js-select-simple select--no-search">
							<select name="eventTime" required>
								<?php
									$select = 0;
									for ($i = 0; $i < 12; $i++) {
										$select = (($i + 11) % 12) + 1;
										echo "<option value=$select>$select</option>";
									}
								?>
							</select>
							<div class="select-dropdown"></div>
						</div>
					</div>
				</div>
				<div class="col-2">
					<div class="inputGroup">
						<div class="rs-select2 js-select-simple select--no-search">
							<select name="eventTime2" required>
								<option value="am">AM</option>
								<option value="pm">PM</option>
							</select>
							<div class="select-dropdown"></div>
						</div>
					</div>
				</div>
			</div>
			<h4><b>LOCATION</b></h4>
			<div class="row row-space">
				<div class="col-2">
					<div class="inputGroup">
						<input class="input--style-1" type="text" name="eventLat" placeholder="Latitude" required>
					</div>
				</div>
				<div class="col-2">
					<div class="inputGroup">
						<input class="input--style-1" type="text" name="eventLong" placeholder="Longitude" required>
					</div>
				</div>
			</div>
			<h4><b>EVENT TYPE</b></h4>
			<div class="inputGroup">
				<div class="rs-select2 js-select-simple select--no-search">
					<select name="eventType" required>
						<option disabled="disabled" selected="selected">SELECT TYPE...</option>
						<option value="public">Public</option>
						<option value="private">Private</option>
						<option value="rso">RSO</option>
					</select>
					<div class="select-dropdown"></div>
				</div>
			</div>
			<div class="p-t-20">
				<button class="btn btn--radius btn--green" type="submit" name="submitrequestevent">Submit</button>
			</div>
		</form>
	</div>
</div>

<?php

	include_once "footer.php";

?>