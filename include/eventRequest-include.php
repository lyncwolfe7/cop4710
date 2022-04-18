<?php

if (isset($_POST["submitEventRequest"])) {
	
	$eventName = $_POST["eventName"];
	$eventDesc = $_POST["eventDesc"];
	$eventPhone = $_POST["eventphone"];
	$eventDate = $_POST["eventPhone"];
	$eventTime = $_POST["eventTime"];
	$eventTime2 = $_POST["eventTime2"];
	$eventLat = $_POST["eventLat"];
	$eventLong = $_POST["eventLong"];
	$eventType = $_POST["eventType"];
	
	require_once "dbh-include.php";
	require_once "functions-include.php";
	
	if (emptyRequestEvent($eventName, $eventDesc, $eventPhone, $eventDate, $eventTime, $eventTime2,
			$eventLat, $eventLong, $eventType) !== false) {
		header("location: ../eventRequest.php?error=empty_input");
		exit();
	}
	
	$eventTime3 = (int) $eventTime + ($eventTime2 === "pm" ? 12 : 0);
	if ((int)$eventTime === 12) {
		$eventTime3 = $eventTime3 - 12;
	}

	$eventDateTime = $eventDate . " " . $eventTime3 . ":00:00";
	
	if (eventExists($conn, $eventDateTime, $eventLat, $eventLong) !== FALSE) {
		header("location: ../eventRequest.php?error=time_place_conflict&datetime=".$eventDateTime.
				"&lat=".$eventLat."&long=".$eventLong);
		exit();
	}
	
	session_start();
	
	$users_ID = $_SESSION["users_ID"];
	
	
	requestEvent($conn, $eventName, $eventDesc, $eventPhone, $eventDateTime, 
		$eventLat, $eventLong, $eventType, $users_ID);
	
	header("location: ../eventRequest.php?error=none");
}
else {
	header("location: ../eventRequest.php");
	exit();
}