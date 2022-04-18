<?php
if (isset($_POST["submitSA"])) {
	
	session_start();
	extract($_SESSION['post'], EXTR_PREFIX_ALL, "c");
	
	$lat = $_POST["lat"];
	$long = $_POST["long"];
	$desc = $_POST["desc"];
	
	require_once "database-include.php";
	require_once "functions-include.php";
	
	if (emptySignupSA($lat, $long, $desc) !== false) {
		header("location: ../signup.php?error=empty_input");
		exit();
	}
	
	createSuperAdmin($conn, $c_userName, $c_uPassword, $c_schoolName, $lat, $long, $desc);
}
else {
	header("location: ../signup.php");
	exit();
}