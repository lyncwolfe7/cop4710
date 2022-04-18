<?php
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['uPassword'];

    require_once 'dbh_include.php';
    require_once 'functions_include.php';

    if (emptyLogin($username, $password) !== false) {
		header("location: login.php?error=empty_input");
		exit();
	}

    loginUser($conn, $username, $password);
} else {
    header('location: login.php');
    exit();
}

/*
foreach ($_POST as $key => $value) {
	$_SESSION['post'][$key] = $value;
}
*/
?>