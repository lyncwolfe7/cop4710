<?php
	include_once "header.php";
?>

<div class="box">
	<?php
		$message = null;
		if(isset($_GET["error"])) {
			if ($_GET["error"] == "empty_input") {
				$message = "<p style='color:#500'>You must fill in all fields.<br><br></p>";
			}
			if ($_GET["error"] == "bad_stmt") {
				$message = "<p style='color:#500'>Database error. Please try again.<br><br></p>";
			}
			if ($_GET["error"] == "none") {
				$message =  "<p style='color:#500'>RSO successfully made. Admin privleges granted.<br>";
				$message = "Once <b>4 more users</b> join the RSO, you will be able to create events for it.<br><br></p>";
			}
		}
	?>
	
	<form class="form5" action="include/createRSO-include.php" method="post">
		<div>
			<p class="heading2">Create RSO</p>
			<div class="error5">
				<?php
					if (!is_null($message)) {
					echo $message;
					}
				?>
			</div>
			<label>
            	<span class="label5">RSO Name</span>
				<input class="input5" type="text" name="rsoName" required>
            </label>

            <label>
            	<span class="label5">RSO Description</span>
				<input class="input5" type="text" name="rsoDesc" required>
            </label>

            <button class="button5" type="submit" name="submitCeateRSO">Create RSO</button>
		</div>
	</form>
</div>

<?php
	include_once "footer.php";
?>