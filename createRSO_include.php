<?php

if (isset($_POST['submit'])) {
    $rsoname = $_POST['name'];
    $uni = $_SESSION['uni'];
    $uid = $_SESSION['userid'];

    require_once 'dbh_include.php';
    require_once 'functions_include.php';

    if (takenRSO($conn, $rsoname)  !== false) {
        header('location: ../home.php?error=RSOnametaken');
        exit();
    }

    createRSO($conn, $rsoname, $uni, $uid);

} else {
    header('location: ./home.php');
}

?>