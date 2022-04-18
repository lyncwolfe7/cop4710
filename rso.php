<?php
	include_once "header.php"
?>

<link rel="stylesheet" href="styles.css">
<script src="javascript/jquery.min.js"></script>
<script src="javascript/RSOsAjax.js"></script>

<div class="box">
	<p class="heading4">RSOs</p>
	<section class="events">
		<?php
			if (isset($_SESSION["users_ID"])) {
				$userSchool = getUsersSchool($conn, $_SESSION["users_ID"]);
				$schoolName = getSchoolName($conn, $schoolName);
				echo "<h2>All RSOs for " . $schoolName . "</h2>";
			}
			else {
					header("location: index.php");
			}
		?>
		<ul class="tilesWrap">
			<?php
				if (isset($_SESSION["user_ID"])) {
					$userID = $_SESSION["user_ID"];
					$userSchool = getUsersSchool($conn, $userID);
					$rsoData = getSchoolRSOs($conn, $userSchool);
					while($row = mysqli_fetch_assoc($rsoData)) {
						echo "<li>";
						$rsoID = $row["rsoSchool_RID"];
						$rsoInfo = getRSOInfo($conn, $rsoid);
						$rsoName = $rsoInfo["rsosName"];
						$rsoDesc = $rsoInfo["rsosDesc"];
						$rsoSID = getRSOSchool($conn, $rsoID);
						$schoolName = getUniversityName($conn, $rsoSID);
						echo "<h2>" . $schoolName . "</h2>";
						echo "<h4><b>" . $rsoName . "</b></h4>";
						echo "<p>" . $rsoDesc . "</p>";
						
						if (userInRSO($conn, $rsoID, $userID) === TRUE) {
							echo "<button type='button'>RSO Already Joined</button>";
						}
						else {
							echo "<button type='button' onclick='joinRSO(" . $rsoID . ")'>Join RSO</button>";
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

<?php
	include_once "footer.php";
?>