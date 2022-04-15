<?php

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $university = $_POST['university'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once 'dbh_include.php';
    require_once 'functions_include.php';

    if (invalidUsername($username)  !== false) {
        header('location: ../signup.php?error=invaliduid');
        exit();
    }

    if (invalidEmail($email)  !== false) {
        header('location: ../signup.php?error=invalidemail');
        exit();
    }

    if (usernameExists($conn, $username)  !== false) {
        header('location: ../signup.php?error=usernametaken');
        exit();
    }

    createUser($conn, $name, $university, $email, $username, $password);

} else {
    header('location: ../login.php');
}

?>