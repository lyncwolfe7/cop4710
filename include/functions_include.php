<?php

function invalidUsername($username) {
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function passwordMismatch($password, $passwordConfirm) {
	if ($password !== $passwordConfirm) {
		return true;
	}
	else {
		return false;
	}
}

function userExists($conn, $username) {
	$sql = "SELECT * FROM users WHERE userName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "s", $username);
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

function schoolExists($conn, $schoolName) {
	$sql = "SELECT * FROM schools WHERE schoolName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "s", $schoolName);
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

function createUser($conn, $name, $email, $username, $password, $school, $rtnflag) {
    $sql = "INSERT INTO users (fullName, email, userName, uPassword) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../signup.php?error=stmtfailed');
        exit();
    }

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $hashPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

	$sql = "SELECT users_ID FROM users WHERE userName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);

	$res = mysqli_stmt_get_result($stmt);
	while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
		$users_ID = $row[0];
	}
	mysqli_stmt_close($stmt);

	$sql = "SELECT school_ID FROM schools WHERE schoolName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "s", $schoolName);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
		$school_ID = $row[0];
	}
	mysqli_stmt_close($stmt);

	$sql = "INSERT INTO schoolUser (schoolUser_UID, schoolUser_SID) VALUES (?, ?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ii", $users_ID, $school_ID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	if ($rtnflag == True) {
		header("location: signup.php?error=none");
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

function createLoc($conn, $lat, $long) {
	$sql = "INSERT INTO locations (locLatt, locLong) VALUES (?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ss", $long, $lat);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

function getLocInfo($conn, $locID) {
	$sql = "SELECT * FROM locations WHERE loc_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getLocInfo";
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

function createSuperAdmin($conn, $c_fullName, $c_email, $c_userName, $c_uPassword, $c_schoolName, $lat, $long, $desc) {
	
	$locresult = locExists($conn, $lat, $long);
	if ($locresult === FALSE) {
		createLoc($conn, $lat, $long);
	}
	
	$locresult = locExists($conn, $lat, $long);
	$loc_ID = $locresult['loc_ID'];
	
	$sql = "INSERT INTO schools (schoolName, schoolDesc) VALUES (?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ss", $c_schoolName, $desc);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	$sql = "SELECT school_ID FROM schools WHERE schoolName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "s", $c_schoolName);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
		$schoolID = $row[0];
	}
	mysqli_stmt_close($stmt);
	
	$sql = "INSERT INTO schoolLoc (schoolLoc_UID, schoolLoc_LID) VALUES (?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ii", $schoolID, $loc_ID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	createUser($conn, $c_fullName, $c_email, $c_userName, $c_uPassword, $c_schoolName, False);
	
	$sql = "SELECT users_ID FROM users WHERE usersName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "s", $c_userName);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
		$userID = $row[0];
	}
	mysqli_stmt_close($stmt);
	
	$sql = "INSERT INTO superAdmins (sAdmin_ID) VALUES (?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../signup.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "i", $userID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	header("location: ../signup.php?error=none");
}

function getSuperAdminID($conn, $schoolID) {
	$sql = "SELECT * FROM superAdmins WHERE sAdmin_ID IN 
		(SELECT schoolUser_UID FROM schoolUser WHERE schoolUser_SID = ?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getSuperAdminID";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $schoolID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	
	// found superadmin, return id
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row['sAdmin_ID'];
	}
	
	return FALSE;
}

function isSuperAdmin($conn, $userID) {
	$sql = "SELECT * FROM superAdmins WHERE sAdmin_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: index.php");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "s", $userID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	return FALSE;
}

function isAdmin($conn, $userID) {
	$sql = "SELECT * FROM admins WHERE admins_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: index.php");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "s", $userID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	return FALSE;
}

function loginUser($conn, $username, $password) {
    $exists = usernameExists($conn, $username);

    if ($exists === false) {
        header('location: login.php?error=incorrect_login');
        exit();
    } 

    $passwordHash = $exists["uPassword"];
	$checkPassword = password_verify($password, $passwordHash);
    
    if ($checkPassword === false) {
		header("location: login.php?error=incorrect_login");
		exit();
	}
	
	else if ($checkPassword === true) {
		session_start();
		$_SESSION["users_ID"] = $exists["users_ID"];
		$_SESSION["userName"] = $exists["userName"];
		header("location: ../index.php");
		exit();
	}
}

function getUsersSchool($conn, $userID) {
	$sql = "SELECT * FROM schoolUser WHERE schoolUser_UID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getUsersSchool";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $userID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row['schoolUser_SID'];
	}
	return FALSE;
}

function getUserName($conn, $userID) {
	$sql = "SELECT * FROM users WHERE users_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getUserName";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $userID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row['userName'];
	}
	return FALSE;
}

function getSchoolName($conn, $schoolID) {
	$sql = "SELECT * FROM schools WHERE school_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getSchoolName";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $schoolID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row['schoolName'];
	}
	return FALSE;
}

function rsoExists($conn, $rsoName) {
	$sql = "SELECT * FROM rsos WHERE rsoName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT rsoExists";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "s", $rsoName);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	return FALSE;
}

function createRSO($conn, $rsoName, $rsoDesc, $userID) {
	$sql = "INSERT INTO rsos (rsosName, rsosDesc, rso_OID) VALUES (?,?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../createRSO.php?error=bad_stmt");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ssi", $rsoName, $rsoDesc, $userID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	$ownerSID = getUsersSchool($conn, $userID);
	
	$sql = "INSERT INTO rsoSchool (rsoSchool_UID, rsoSchool_RID) VALUES (?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../createRSO.php?error=bad_stmt");
		exit();
	}
	
	$rsoID = rsoExists($conn, $rsoName);
	$rsoID = $rsoID['rso_ID'];
	
	mysqli_stmt_bind_param($stmt, "ii", $ownerSID, $rsoID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

function joinRSO($conn, $rsoID, $userID) {
	$sql = "INSERT INTO rsoUser (rsoUser_RID, rsoUser_ID) VALUES (?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT joinRSO";
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ii", $rsoID, $userID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

function leaveRSO($conn, $rsoID, $userID) {
	$sql = "DELETE FROM rsoUser WHERE rsoUser_RID = ? AND rsoUser_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT leaveRSO";
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ii", $rsoID, $userID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	$sql = "SELECT COUNT(*) AS total FROM rsoUser WHERE rsoUser_RID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT leaveRSO";
		exit();
	}
	mysqli_stmt_bind_param($stmt, "i", $rsoID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	
	$row = mysqli_fetch_assoc($resultData);
	$userCount = $row['total'];
	
	if ($usercount < 5) {
		$sql = "UPDATE rsos SET rsoStatus = 'inactive' WHERE rso_ID = ?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt,$sql)) {
			echo "BAD STMT leaveRSO";
			exit();
		}
		mysqli_stmt_bind_param($stmt, "i", $rsoID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

function getRSOInfo($conn, $rsoID) {
	$sql = "SELECT * FROM rsos WHERE rso_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getRSOInfo";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $rsoID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	return FALSE;
}

function getRSOName($conn, $rsoID) {
	$sql = "SELECT * FROM rsos WHERE rso_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getRSOName";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $rsoID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row['rsosName'];
	}
	return FALSE;
}

function getUserRSOs($conn, $userID) {
	$sql = "SELECT * FROM rsoUser WHERE rsoUser_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getUserRSOs";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $userID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	
	return $resultData;
}

function userInRSO($conn, $rsoID, $userID) {
	$sql = "SELECT * FROM rsoUser WHERE rsoUser_RID = ? AND rsoUser_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT userInRSO";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ii", $rsoID, $userID);
	mysqli_stmt_execute($stmt);
	
	$resultData = mysqli_stmt_get_result($stmt);
	
	mysqli_stmt_close($stmt);
		
	if (mysqli_fetch_assoc($resultData)) {
		return TRUE;
	}
	return FALSE;
}

function getSchoolRSOs($conn, $schoolID) {
	$sql = "SELECT * FROM rsoSchool WHERE rsoSchool_UID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getSchoolRSOs";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $schoolID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	
	return $resultData;
}

function getRSOSchool($conn, $rsoID) {
	$sql = "SELECT * FROM rsoSchool WHERE rsoSchool_RID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getRSOSchool";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $rsoID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row["rsoSchool_UID"];
	}
	return FALSE;
}

function eventExists($conn, $eventDateTime, $eventLat, $eventLong) {
	$row = locExists($conn, $eventLat, $eventlLng);
	if ($row !== FALSE) {
		$locID = $row['loc_ID'];
		$sql = "SELECT * FROM events WHERE eventDateTime = ? AND event_LID = ?;";

		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt,$sql)) {
			header("location: ../signup.php?error=bad_stmt");
			exit();
		}
		
		mysqli_stmt_bind_param($stmt, "si", $eventDateTime, $locID);
		mysqli_stmt_execute($stmt);
		$resultData = mysqli_stmt_get_result($stmt);
		
		if ($row = mysqli_fetch_assoc($resultData)) {
			return $row;
		}
		else {
			return FALSE;
		}
	}
	else {
		return FALSE;
	}
}

function createEvent($conn, $eventName, $eventDesc, $eventPhone,
		$eventDateTime, $eventLat, $eventLong, $eventType, $schoolID) {
	if (locExists($conn, $eventLat, $eventLong) === FALSE) {
		createLoc($conn, $eventLat, $eventLong);
	}
	
	$row = locExists($conn, $eventLat, $eventLong);
	$locID = $row['loc_ID'];
	
	
	$sql = "INSERT INTO events (eventName, eventDesc, eventPhone, eventDateTime, event_LID, event_UID)
			VALUES (?,?,?,?,?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT createEvent";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ssssii", $eventName, $eventDesc, $eventPhone, $eventDateTime, $locID, $schoolID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	$eventdata = eventExists($conn, $eventDateTime, $eventLat, $eventLong);
	$eventID = $eventdata['event_ID'];
	
	if ($eventType === "private") {
		$sql = "INSERT INTO privateEvents (pvEvent_ID) VALUES (?);";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt,$sql)) {
			echo "BAD STMT createEvent";
			exit();
		}
		mysqli_stmt_bind_param($stmt, "i", $eventID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	else {
		$sql = "INSERT INTO publicEvents (pbEvent_ID) VALUES (?);";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt,$sql)) {
			echo "BAD STMT createEvent";
			exit();
		}
		mysqli_stmt_bind_param($stmt, "i", $eventID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

function joinEvent($conn, $eventID, $userID) {
	$sql = "INSERT INTO eventUser (eUser_EID, eUser_UID) VALUES (?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT joinEvent";
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ii", $eventID, $userID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

function leaveEvent($conn, $eventID, $userID) {
	$sql = "DELETE FROM eventUser WHERE eUser_EID = ? AND eUser_UID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT leaveEvent";
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ii", $eventID, $userID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

function userInEvent($conn, $eventID, $userID) {
	$sql = "SELECT * FROM eventUser WHERE eUser_EID = ? AND eUser_UID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT userInEvent";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ii", $eventID, $userID);
	mysqli_stmt_execute($stmt);
	
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	if (mysqli_fetch_assoc($resultData)) {
		return TRUE;
	}
	return FALSE;
}

function isEventPublic($conn, $eventID) {
	$sql = "SELECT * FROM publicEvents WHERE pbEvent_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT isEventPublic";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $eventID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
		
	if (mysqli_fetch_assoc($resultData)) {
		return TRUE;
	}
	return FALSE;
}

function isEventPrivate($conn, $eventID) {
	$sql = "SELECT * FROM privateEvents WHERE pvEvent_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT isEventPrivate";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $eventID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
		
	if (mysqli_fetch_assoc($resultData)) {
		return TRUE;
	}
	return FALSE;
}

function isRSOEvent($conn, $eventID) {
	$sql = "SELECT * FROM rsoEvents WHERE rsoEvent_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT isRSOEvent";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $eventID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
		
	if (mysqli_fetch_assoc($resultData)) {
		return TRUE;
	}
	return FALSE;
}

function getEventType($conn, $eventID) {
	if (isEventPublic($conn,$eventID)) {
		return "public";
	}
	else if (isEventPrivate($conn,$eventID)) {
		return "private";
	}
	else if (isRSOEvent($conn,$eventID)) {
		return "rso";
	}
	else {
		return "unknown";
	}
}

function getEventInfo($conn, $eventID) {
	$sql = "SELECT * FROM events WHERE event_ID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getEventInfo";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $eventID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	
	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	return FALSE;
}

function createRSOEvent($conn, $eventName, $eventDesc, $eventPhone, 
	$eventDateTime, $eventLat, $eventLong, $eventType,$userID, $rsoID, $schoolID) {
	
	if (locExists($conn, $eventLat, $eventLong) === FALSE) {
		createLoc($conn, $eventLat, $eventLong);
	}
	
	$row = locExists($conn, $eventLat, $eventLong);
	$locID = $row['loc_ID'];
	
	
	
	$sql = "INSERT INTO events (eventName, eventDesc, eventPhone, eventDateTime, event_LID, event_RID, event_UID)
			VALUES (?,?,?,?,?,?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT createRSOEvent";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ssssiii", $eventName, $eventDesc, $eventPhone, $eventDateTime, $locID, $rsoID, $schoolID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	$eventdata = eventExists($conn, $eventDateTime, $eventLat, $eventLong);
	$eventID = $eventdata['event_ID'];
	
	if ($eventType === "private") {
		$sql = "INSERT INTO privateEvents (pvEvent_ID) VALUES (?);";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt,$sql)) {
			echo "BAD STMT createRSOEvent";
			exit();
		}
		mysqli_stmt_bind_param($stmt, "i", $eventID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	else if ($eventType === "public"){
		$sql = "INSERT INTO publicEvents (pbEvent_ID) VALUES (?);";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt,$sql)) {
			echo "BAD STMT createRSOEvent";
			exit();
		}
		mysqli_stmt_bind_param($stmt, "i", $eventID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	else {
		$sql = "INSERT INTO rsoEvents (rsoEvent_ID) VALUES (?);";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt,$sql)) {
			echo "BAD STMT createRSOEvent";
			exit();
		}
		mysqli_stmt_bind_param($stmt, "i", $eventID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	
	joinEvent($conn, $eventID, $userID);
}

function requestEvent($conn, $eventName, $eventDesc, $eventPhone,
		$eventDateTime, $eventLat, $eventLong, $eventType, $userID) {
	$schoolID = getUsersSchool($conn, $userID);
	
	createEvent($conn, $eventName, $eventDesc, $eventPhone,
		$eventDateTime, $eventLat, $eventLong, $eventType, $schoolID);
	$row = eventExists($conn, $eventDateTime, $eventLat, $eventLong);
	$eventID = $row['event_ID'];
	
	$schoolID = getUsersSchool($conn, $userID);
	$sAdminID = getSuperAdminID($conn, $schoolID);
	
	$sql = "INSERT INTO eventApprove (eApproval_EID, eApproval_SID) VALUES (?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		header("location: ../requestevent.php?error=bad_stmt");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ii", $eventID, $sAdminID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	joinEvent($conn, $eventID, $userID);
}

function getEventRequests($conn, $sAdminID) {
	$sql = "SELECT * FROM eventApprove WHERE eApproval_SID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT getEventRequests";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $sAdminID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	return $resultData;
}

function activeRSOs($conn, $adminID) {
	$sql = "SELECT * FROM rsos WHERE rso_OID = ? AND rsoStatus = 'active';";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT activeRSOs";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $adminID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	return $resultData;
}

function valCreateEvent($conn, $rsoID, $adminID) {
	$sql = "SELECT * FROM rsos WHERE rso_ID = ? AND rso_OID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT valCreateEvent";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ii", $rsoID, $adminID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
		
	if (mysqli_fetch_assoc($resultData)) {
		return TRUE;
	}
	return FALSE;
}

function displayAvailableEvents($conn, $userID) {
	$schoolID = getUsersSchool($conn, $userID);
	$sql = "(SELECT * FROM events WHERE (event_ID IN (SELECT pbEvent_ID FROM publicEvents))
			AND (event_ID NOT IN (SELECT eApproval_EID FROM eventApprove)))
		UNION
		(SELECT * FROM events WHERE (event_ID IN (SELECT pvEvent_ID FROM privateEvents)) AND event_UID = ?
			AND (event_ID NOT IN (SELECT eApproval_EID FROM eventApprove)))
		UNION
		(SELECT * FROM events WHERE (event_RID IN (SELECT rsoUser_RID FROM rsoUser WHERE rsoUser_ID = ?)));";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT displayAvailableEvents";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ii", $schoolID, $userID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	return $resultData;
}

function availableUserEvents($conn, $userID) {	
	$sql = "SELECT * FROM events WHERE (event_ID IN (SELECT eUser_EID FROM eventUser WHERE eUser_UID = ? ))
		AND (event_ID NOT IN (SELECT eApproval_EID FROM eventApprove));";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT availableUserEvents";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $userID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	return $resultData;
}

function verCommenter($conn, $eventID, $userID) {
	$sql = "SELECT * FROM eventUser WHERE eUser_EID = ? AND eUser_UID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT verCommenter";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ii", $eventID, $userID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
		
	if (mysqli_fetch_assoc($resultData)) {
		return TRUE;
	}
	return FALSE;
}

function createComment($conn, $eventID, $userID, $desc) {
	if (verCommenter($conn, $eventID, $userID) === FALSE) {
		return FALSE;
	}
	
	$sql = "INSERT INTO comments (comment_EID, comment_UID, cmntDesc) VALUES (?,?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT createComment";
		return FALSE;
	}
	mysqli_stmt_bind_param($stmt, "iis", $eventID, $userID, $desc);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	return TRUE;
}

function availableComments($conn, $eventID, $userID) {
	if (verCommenter($conn, $eventID, $userID) === FALSE) {
		return FALSE;
	}
	
	$sql = "SELECT * FROM comments WHERE comment_EID = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT availableComments";
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "i", $eventID);
	mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	return $resultData;
}

function editComment($conn, $eventID, $userID, $originalTime, $desc) {
	if (verCommenter($conn, $eventID, $userID) === FALSE) {
		return FALSE;
	}
	
	$sql = "UPDATE comments SET desc = ? WHERE comment_EID = ? AND comment_UID = ? AND cmntTime = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt,$sql)) {
		echo "BAD STMT editComment";
		return FALSE;
	}
	mysqli_stmt_bind_param($stmt, "siis", $desc, $eventID, $userID, $originalTime);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	return TRUE;
}

function emptyInputSignup($name, $email, $username, $password, $passwordConfirm, $schoolName) {
    $result;
    if (empty($name) || empty($email) || empty($username) || empty($password) || empty($passwordConfirm || empty($schoolName) )) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function emptyLogin($username, $password) {
	if (empty($username) || empty($password)) {
		return true;
	}
	else {
		return false;
	}
}

function emptySignupSA($lat, $long, $desc) {
	if (empty($lat) || empty($long) || empty($desc)) {
		return true;
	}
	else {
		return false;
	}
}

function emptyCreateRSO($rsoName, $rsoDesc) {
	if (empty($rsoName) || empty($rsoDesc)) {
		return true;
	}
	else {
		return false;
	}
}

function emptyRequestEvent($eventName, $eventDesc, $eventPhone, $eventDate, $eventTime, $eventTime2,
			$eventLat, $eventLong, $eventType) {
	if (empty($eventName) || empty($eventDesc) || empty($eventPhone) || empty($eventDate) || empty($eventTime)
			 || empty($eventTime2) || empty($eventLat) || empty($eventLong) || empty($eventType)) {
		return true;
	}
	else {
		return false;
	}
}

function emptyCreateEvent($rsoID, $eventName, $eventDesc, $eventPhone, $eventDate, $eventTime, $eventTime2,
		$eventLat, $eventLong, $eventType) {
	if (empty($rsoID) ||empty($eventName) || empty($eventDesc) || empty($eventPhone) || empty($eventDate) || empty($eventTime)
			 || empty($eventTime2) || empty($eventLat) || empty($eventLong) || empty($eventType)) {
		return true;
	}
	else {
		return false;
	}
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


?>

