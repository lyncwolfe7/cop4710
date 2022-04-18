<?php
	include_once "header.php"
?>

<?php
	if (!isset($_SESSION["users_ID"])) {
		header("location: index.php");
	}
	if (isSuperAdmin($conn, $users_ID) !== FALSE) {
		echo "<h3>(Since you're a super-admin, just fill in a request and then accept it in 'Pending Events'.)</h3>";
	}
?>

<div class="box">
	<?php
		$message = null;
		if(isset($_GET["error"])) {
			if ($_GET["error"] == "empty_input") {
				$message = "<p style='color:red'>You must fill in all fields.<br><br></p>";
			}
			if ($_GET["error"] == "bad_stmt") {
				$message =  "<p style='color:red'>Database error. Please try again.<br><br></p>";
			}
			if ($_GET["error"] == "time_place_conflict") {
				if(isset($_GET["datetime"]) AND isset($_GET["lat"]) AND isset($_GET["long"])) {
					$message = "<p style='color:red'>Event already exists on ".$_GET["datetime"].
							"<br>at LATITUDE= " .$_GET["lat"]. ", LONGITUDE= ".$_GET["long"].".<br><br></p>";
				}
				else {
					$message = "<p style='color:red'>Event already exists (could not grab details).<br><br></p>";
				}
			}
			if ($_GET["error"] == "none") {
				$message = "<p style='color:#500'>Event request submitted. Waiting for super-admin approval.<br><br></p>";
			}
		}
	?>

	<form class="form5" action="include/eventRequest-include.php" method="post">
		<div>
			<p class="heading2">Request An Event</p>
			<div class="error5">
				<?php
				if (!is_null($message)) {
				echo $message;
				}
				?>
			</div>
			<center><h2><b>Event General Information</b></h2></center>
			<label>
				<span class="label5">Event Name</span>
				<input class="input5" type="text" name="eventName">
			</label>

			<label>
				<span class="label5">Event Description</span>
				<input class="input5" type="text" name="eventDesc">
			</label>

			<label>
				<span class="label5">Event Contact Number</span>
				<input class="input5" type="tel" name="eventPhone" placeholder="123-456-7890" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
			</label>

			<label>
				<span class="label5">Event Date</span>
				<input class="input5" type="date" name="eventDate" placeholder="EVENT DATE" required>
				<i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
			</label>

			<label>
				<span class="label5">Event Time</span>
				<div class="col-container">
					<div class="column1">
						<div class="rs-select2 js-select-simple select--no-search">
							<select class="input7" name="eventTime" required style="width: 100%; height:25px;">
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
					<div class="column2">
						<div class="rs-select2 js-select-simple select--no-search">
							<select name="eventTime2" required style="width: 100%; height:25px;">
								<option value="am">AM</option>
								<option value="pm">PM</option>
							</select>
							<div class="select-dropdown"></div>
						</div>
					</div>
				</div>
			</label>
			
			<label>
				<span class="label5">Event Location</span>
				<div class="col-container">
					<div class="column1">
							<input class="input6" type="text" name="eventLat" placeholder="Latitude" required>
					</div>
					<div class="column2">
							<input class="input6" type="text" name="eventLong" placeholder="Longitude" required>
					</div>
				</div>
			</label>

			<label>
				<span class="label5">Event Type</span>	
				<div class="rs-select2 js-select-simple select--no-search">
					<select class="input5" name="eventType" required style="width: 375px; height:25px;">
						<option disabled="disabled" selected="selected">Select Event Type...</option>
						<option value="public">Public</option>
						<option value="private">Private</option>
					</select>
					<div class="select-dropdown"></div>
				</div>
			</label>

			<button class="button5" type="submit" name="submitEventRequest">Submit</button>
		</div>
	</form>

</div>

<?php
	include_once "footer.php";
?>