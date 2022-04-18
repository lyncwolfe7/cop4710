<?php

include_once "dbh-include.php";
include_once "functions-include.php";

echo submitCommentHelp($conn);

function submitdCommentHelp($conn) {
	echo json_encode("Tddest");
}

function submitCommentHelp($conn) {
	if (isset($_POST["event_ID"])) {
		$eventid = $_POST["event_ID"];
	}
	if (isset($_POST["comment_field"])) {
		$desc = $_POST["comment_field"];
	}
	
	session_start();
	
	$users_ID = $_SESSION["users_ID"];

	$data = '';
	// takes the user out of the rso
	if (createComment($conn, $event_ID, $users_ID, $desc) !== FALSE) {
		$data = "Comment added";
	}
	else {
		$data = "Unable to add comment.";
	}
	
	echo json_encode($data);
}