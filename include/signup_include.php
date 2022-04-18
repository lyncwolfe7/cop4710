<?php
	include_once "header.php";
?>

<?php

if (isset($_POST['submit'])) {
    
	$name = $_POST['fullName'];
    $email = $_POST['email'];
    $username = $_POST['userName'];
    $password = $_POST['uPassword'];
    $passwordConfirm = $_POST['passwordConfirm'];
    $schoolName = $_POST['schoolName'];

    require_once 'dbh_include.php';
    require_once 'functions_include.php';

    if (emptyInputSignup($name, $email, $username, $password, $passwordConfirm, $school) !== false) {
		header("location: signup.php?error=empty_input");
		exit();
	}
    
    if (invalidUsername($username)  !== false) {
        header('location: signup.php?error=invalidUsername');
        exit();
    }

    if (invalidEmail($email)  !== false) {
        header('location: signup.php?error=invalidemail');
        exit();
    }

    if (usernameExists($conn, $username)  !== false) {
        header('location: signup.php?error=usernametaken');
        exit();
    }
	
	if (passwordMismatch($password, $passwordConfirm) !== false) {
		header("location: signup.php?error=password_mismatch");
		exit();
	}
	
	if (emailExists($conn, $email) !== false) {
		header("location: signup.php?error=email_exists");
		exit();
	}
	
	if (schoolExists($conn, $school) !== false) {
		createUser($conn, $name, $email, $username, $password, $school, TRUE);
		exit();
	}

} else {
    header('location: ../signup.php');
    exit();
}

foreach ($_POST as $key => $value) {
	$_SESSION['post'][$key] = $value;
}

?>

<div class="box box1">
	<div class="box-body">
		<form action="signup_include.php" method="post">
            <h2><b>New School Information</b></h2>
            <h3> Complete sign up for new school to become super-admin for the school. </h3>
			<div class="row row-space">
				<div class="col-2">
					<div class="inputGroup">
						<input class="inputS1" type="text" name="lat" placeholder="Latitude">
					</div>
				</div>
				<div class="col-2">
					<div class="inputGroup">
						<input class="inputS1" type="text" name="long" placeholder="Longitude">
					</div>
				</div>
			</div>
			<h4><b>School Information</b></h4>
			<div class="inputGroup">
				<input class="inputS1" type="text" name="desc" placeholder="School Descript">
			</div>
			<div class="p-t-20">
				<button class="btn btn--radius btn--green" type="submit" name="submitsa">Sign Up</button>
			</div>
		</form>
	</div>
</div>