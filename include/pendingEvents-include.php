<?php

header('Content-Type: application/json');

include_once "dbh-include.php";

echo $_POST["method"]($conn);


function verifySuperAdmin($conn, $event_ID, $users_ID) {
	$sql = "SELECT * FROM eventApprove WHERE eApproval_EID = ? AND eApproval_SID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo json_encode(array('status' => 'err', 'statusText' => 'Database error in verifySuperAdmin.'));
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ii", $event_ID, $users_ID);
	mysqli_stmt_execute($stmt);
	
	$resultData = mysqli_stmt_get_result($stmt);
	
	mysqli_stmt_close($stmt);
	if (mysqli_fetch_assoc($resultData)) {
		return TRUE;
	}
	return FALSE;
}

function acceptEvent($conn) {
	if (isset($_POST["EID"])) {
		$eventid = json_decode($_POST["EID"]);
	}
	
	session_start();
	
	$users_ID = $_SESSION["users_ID"];

	if (verifySuperAdmin($conn, $event_ID, $users_ID) === FALSE) {
		echo json_encode(array('status' => 'err', 'statusText' => 'You do not have the access to accept this event.'));
		exit();
	}
	
	$sql = "DELETE FROM eventApprove WHERE eApproval_EID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo json_encode(array('status' => 'err', 'statusText' => 'Database error in verifySuperAdmin.'));
		exit();
	}
	mysqli_stmt_bind_param($stmt, "i", $event_ID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	echo json_encode(array('status' => 'ok'));
}

function denyEvent($conn) {
	if (isset($_POST["EID"])) {
		$event_ID = json_decode($_POST["EID"]);
	}
	
	session_start();
	$users_ID = $_SESSION["users_ID"];
	
	if (verifySuperAdmin($conn, $event_ID, $users_ID) === FALSE) {
		echo json_encode(array('status' => 'err', 'statusText' => 'You do not have the access to deny this event.'));
		exit();
	}
	
	$sql = "DELETE FROM events WHERE event_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo json_encode(array('status' => 'err', 'statusText' => 'Database error in denyEvent.'));
		exit();
	}
	mysqli_stmt_bind_param($stmt, "i", $event_ID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	echo json_encode(array('status' => 'ok'));
}