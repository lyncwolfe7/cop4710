<?php

header('Content-Type: application/json');

include_once "database-include.php";
include_once "functions-include.php";

echo commentsListHelp($conn);

function commentsListHelp($conn) {
	if (isset($_POST["event_ID"])) {
		$event_ID = json_decode($_POST["event_ID"]);
	}
	session_start();
	
	$users_ID = $_SESSION["users_ID"];

	// gets the comments for the users event
	$comments = availableComments($conn, $event_ID, $users_ID);
	
	$output = '';
	
	if ($comments !== FALSE) {
		while($row = mysqli_fetch_assoc($comments)) {
			$commenterID = $row['comment_UID'];
			$commenterName = getUserName($conn, $commenterID);
			$output .= '
			<div class="panel panel-default" style="margin-left:0px">
				<div class="panel-heading">By <b>'.$commenterName.'</b> on <i>'.$row["commentsTime"].'</i></div>
				<div class="panel-body">'.$row["commentsDesc"].'<br><br></div>
			</div>
			';
		}
	}
	
	echo $output;
}