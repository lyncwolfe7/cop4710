<?php

if (isset($_POST['submit'])) {
	$uni = $_POST['uni'];
    $name = $_SESSION['name'];
    $uid = $_SESSION['userid'];

    require_once 'dbh_include.php';
    require_once 'functions_include.php';

    if (takenUni($conn, $uni)  !== false) {
        header('location: ../home.php?error=Uninametaken');
        exit(); 
    }

    createUni($conn, $uni, $uid);

} else {
    header('location: ./home.php');
}

?>