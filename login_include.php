<?php
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once 'dbh_include.php';
    require_once 'functions_include.php';

    if (isEmptyLogin($username, $password) !== false) {
		header("location: ../login.php?error=empty_input");
		exit();
	}

    loginUser($conn, $username, $password);
} else {
    header('location: ./login.php');
    exit();
}
?>