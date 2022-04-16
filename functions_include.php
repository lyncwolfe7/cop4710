<?php

function emptyInputSignup($name, $university, $email, $username, $password, $passwordConfirm) {
    $result;
    if (empty($name) || empty($university) || empty($email) || empty($username) || empty($password) || empty($passwordConfirm)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invalidUsername($username) {
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email) {
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function usernameExists($conn, $username) {
    $sql = "SELECT * FROM users WHERE userName = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../signup.php?error=stmtfailed');
        exit();
    }

    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function emailExists($conn, $email) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../signup.php?error=stmtfailed');
        exit();
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

//Creates New User
function createUser($conn, $name, $email, $username, $password, $university, $rtnflag) {
    $sql = "INSERT INTO users (fullName, email, userName, uPassword) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../signup.php?error=stmtfailed');
        exit();
    }

    //Hashes Password
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $username, $hashPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);


    //Creates New User ID
	$sql = "SELECT usersId FROM users WHERE userName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
		$userid = $row[0];
	}
	mysqli_stmt_close($stmt);

    //Gets School ID
	$sql = "SELECT school_ID FROM schools WHERE schoolName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "s", $university);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
		$schoolID = $row[0];
	}
	mysqli_stmt_close($stmt);

    //Links School to User
	$sql = "INSERT INTO schoolUser (schoolUser_UID, schoolUser_SID) VALUES (?, ?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ii", $userid, $schoolID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	if ($rtnflag == True) {
		header("location: signup.php?error=none");
	}

}

function loginUser($conn, $username, $password) {
    $exists = usernameExists($conn, $username);

    if ($exists === false) {
        header('location: ./login.php?error=doesntexist');
        exit();
    } 

    $passwordHash = $userExists["uPassword"];
	$checkPassword = password_verify($password, $passwordHash);
    
    if ($checkPassword === false) {
		header("location: ../login.php?error=incorrect_login");
		exit();
	}
	//Creates a new session
	else if ($checkPassword === true) {
		session_start();
		$_SESSION["users_ID"] = $userExists["users_ID"];
		$_SESSION["userName"] = $userExists["userName"];
		header("location: ../index.php");
		exit();
	}
}

function schoolExists($conn, $university) {
	$sql = "SELECT * FROM schools WHERE schoolName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "s", $university);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	
	// User exists
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	// User doesn't exist
	else {
		$result = false;
		return $result;
	}
	
}

function locExists($conn, $locLat, $locLong) {
	$sql = "SELECT * FROM locations WHERE locLatt = ? AND locLong = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ss", $locLat, $locLong);
	mysqli_stmt_execute($stmt);
	
	$resultData = mysqli_stmt_get_result($stmt);
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	else {
		$result = false;
		return $result;
	}
}

function newLoc($conn, $lat, $long) {
	$sql = "INSERT INTO locations (locLatt, locLong) VALUES (?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ss", $lat, $long);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

function getLocInfo($conn, $locID) {
	$sql = "SELECT * FROM locations WHERE loc_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getLocationInfo";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $locID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	return FALSE;
}












?>

