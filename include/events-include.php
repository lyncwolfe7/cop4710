<?php

header('Content-Type: application/json');

include_once "database-include.php";
include_once "functions-includes.php";

echo $_POST["method"]($conn);

function joinEventHelp($conn) {
	if (isset($_POST["EID"])) {
		$eventID = json_decode($_POST["EID"]);
	}
	
	session_start();
	
	$users_ID = $_SESSION["users_ID"];
	joinEvent($conn, $eventID, $users_ID);
	echo json_encode(array('status' => 'ok'));
}