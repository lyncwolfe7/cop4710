<?php

if (isset($_POST['submit'])) {
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];
    $university = $_POST['university'];

    require_once 'dbh_include.php';
    require_once 'functions_include.php';

    if (invalidUsername($username)  !== false) {
        header('location: ../signup.php?error=invalidUsername');
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

    if (emptyInputSignup($name, $university, $email, $username, $password, $passwordConfirm) !== false) {
		header("location: signup.php?error=empty_input");
		exit();
	}
	
	
	if (isPasswordMismatch($password, $passwordConfirm) !== false) {
		header("location: signup.php?error=password_mismatch");
		exit();
	}
	
	if (userExists($conn, $username) !== false) {
		header("location: signup.php?error=nonunique_username");
		exit();
	}
	
	if (universityExists($conn, $university) !== false) {
		createUser($conn, $name, $university, $email, $username, $password, TRUE);
		exit();
	}

} else {
    header('location: ../signup.php');
}

foreach ($_POST as $key => $value) {
	$_SESSION['post'][$key] = $value;
}

?>

<center><section class='signup-form'>
    <h2> New School Information </h2>
    <h3> Complete sign up for new school to become super-admin for the school. </h3>
    <form action='signup_include.php' method='post'>
    <h4><b>School Location</b></h4>
        <input type='text' name='lat' placeholder='LATITUDE'> <br><br>
        <input type='text' name='long' placeholder='LONGITUDE'> <br><br>
    <h4><b>School Information</b></h4>
        <input type='text' name='desc' placeholder='School Description'> <br><br>
        <button type='submit' name='submit'>Sign Up</button> <br><br>
    </form>
</section></center>