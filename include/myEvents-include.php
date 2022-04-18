<?php

header('Content-Type: application/json');

include_once "dbh-include.php";
include_once "functions-include.php";

echo $_POST["method"]($conn);

function leaveEventHelp($conn) {
	if (isset($_POST["EID"])) {
		$eventID = json_decode($_POST["EID"]);
	}
	
	session_start();
	
	$user_ID = $_SESSION["user_ID"];

	leaveEvent($conn, $eventID, $user_ID);
	echo json_encode(array('status' => 'ok'));
}