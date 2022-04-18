<?php

header('Content-Type: application/json');

include_once "dbh-include.php";
include_once "functions-include.php";

echo $_POST["method"]($conn);

function leaveRSOHelp($conn) {
	if (isset($_POST["RID"])) {
		$rsoID = json_decode($_POST["RID"]);
	}
	
	session_start();
	
	$users_ID = $_SESSION["users_ID"];
	leaveRSO($conn, $rsoID, $users_ID);
	
	echo json_encode(array('status' => 'ok'));
}