<?php
	include_once "header.php";
?>

<link rel="stylesheet" href="styles.css">
<script src="javascript/jquery.min.js"></script>
<script src="javascript/MyEvents.js"></script>

<style>
	.modal {
		display: none;
        position: fixed;
        z-index: 8;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.8);
	}
	.modal-content {
		margin: 50px auto;
        border: 1px solid #999;
		background-color: #eee;
        width: 60%;
	}
	span {
        color: #666;
        display: block;
        padding: 0 0 5px;
    }
	input,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        outline: none;
    }
	.modal-content h2 {
		color: #262a2b;
		text-align: center;
	}
	.new-comment button {
        width: 100%;
        padding: 10px;
        border: none;
        background: #444;
        font-size: 16px;
        font-weight: 400;
        color: #fff;
    }
	.display_comments button {
        width: 25%;
        padding: 5px;
        border: none;
        background: #888;
        font-size: 14px;
        font-weight: 400;
        color: #fff;
    }
    button:hover {
        background: #333;
    }
</style>

<div class="box">
	<p class="heading4">Your Events</p>
	<section class="events">
		<?php
			if (isset($_SESSION["users_ID"])) {
				$userSchool = getUsersSchool($conn, $_SESSION["users_ID"]);
				$schoolName = getSchoolName($conn, $userSchool);
			}
			else {
					header("location: index.php");
			}
		?>
		<ul class="tilesWrapEvents">
			<?php
				if (isset($_SESSION["users_ID"])) {
					$userID = $_SESSION["users_ID"];
					$eventData = availableUserEvents($conn, $userID);
					while($row = mysqli_fetch_assoc($eventData)) {
						echo "<li>";
						$event_ID = $row["event_ID"];
						$eventInfo = getEventInfo($conn, $event_ID);
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
						$eventType = getEventType($conn, $event_ID);

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
						//echo "<h4><b>TEST</b></h4>";
						echo "<p>" . $eventDesc . "<br><br>Latitude: " . $eventLat . "<br>Longitude: " . $eventLong .
							"<br><br>Phone #: ". $eventPhone . "<br><br>Visibility: " . strtoupper($eventType) . "</p>";
						
						echo "<button type='button' onclick='gotoComments(" . $event_ID . ")'>Comments</button>";
						echo "<button type='button' onclick='leaveEvent(" . $event_ID . ")'>Leave Event</button>";
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


<!-- Comments -->
<div id="modal" class="modal">
	<div class="modal-content">
		<div class="new-comment">
			<a class="close">&times;</a>
			<form method="POST" id="new_comment_form">
				<h2>Create New Comment</h2>
				<div>
					<textarea id="comment_field" name="comment_field" rows="2" placeholder="Comment goes here."></textarea>
					<input type="hidden" id="event_ID" name="event_ID" value="-1">
				</div>
				<button type="submit" id='new_comment_btn' onclick="">Submit</button>
			</form>
			<span id="comment_status"></span>
			<br />
			<div class="display_comments" id="display_comments"></div>
		</div>
		
		<div class="comments">
		</div>
	</div>
</div>


<script>
	function alertThenReload(msg) {
		alert(msg);
		window.location.reload(false);
	}

	function gotoComments (event_ID) {
		document.getElementById("modal").style.display = "block";
		document.getElementById("event_ID").value = event_ID;
		listComments();
	}
		
	function listComments() {
		var event_ID = document.getElementById("event_ID").value;
		
		$.ajax({
			url: "include/commentsList-include.php",
			method: "POST",
			dataType: "text",
			data: {method: "commentsListHelp", event_ID: event_ID},
			success: function(data) {
				$("#display_comments").html(data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				alert("Status: " + textStatus);
				alertThenReload("Error: " + errorThrown); 
			}  
		});
	}

	$("#new_comment_form").on("submit", function(event){
		event.preventDefault();
		var form_data = $(this).serializeArray();

		form_data.push({name: "method", value: "submitCommentHelp"});
		$.ajax({
			url: "include/submitComments-include.php",
			dataType: "JSON",
			method: "POST",
			data: $.param(form_data),
			success: function(response) {
				console.log(response);
				if (response != null) {
					document.getElementById("comment_field").value = "";
					$("#comment_status").html(response);
					listComments(event_ID);
				}
				else {
					alertThenReload("Error - Could not create comment.");
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				alert("Status: " + textStatus);
				alertThenReload("Error: " + errorThrown); 
			}  
		})
	});

	window.onclick = function(event) {
		if (event.target.className === "modal") {
			event.target.style.display = "none";
			$("#comment_status").html("");
		}
	}
</script>

<?php

	include_once "footer.php";

?>