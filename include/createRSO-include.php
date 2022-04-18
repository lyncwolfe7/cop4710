<?php

if (isset($_POST["submitCeateRSO"])) {
	
	$rsoName = $_POST["rsoName"];
	$rsoDesc = $_POST["rsoDesc"];
	
	require_once "dbh-include.php";
	require_once "functions-include.php";
	
	if (emptyCreateRSO($rsoName, $rsoDesc) !== false) {
		header("location: ../createRSO.php?error=empty_input");
		exit();
	}
	
	session_start();
	
	$users_ID = $_SESSION["users_ID"];
	createRSO($conn, $rsoName, $rsoDesc, $users_ID);
	$rso_ID = rsoExists($conn, $rsoName);
	$rso_ID = $rso_ID['rso_ID'];
	joinRSO($conn, $rso_ID, $users_ID);
	
	header("location: ../createRSO.php?error=none");
}
else {
	header("location: ../createRSO.php");
	exit();
}