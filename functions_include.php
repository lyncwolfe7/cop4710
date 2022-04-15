<?php

function emptyInputSignup($name, $university, $email, $username, $password) {
    $result;
    if (empty($name) || empty($university) || empty($email) || empty($username) || empty($password)) {
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
    $sql = "SELECT * FROM users WHERE u_name = ?";
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

function createUser($conn, $name, $university, $email, $username, $password) {
    $sql = "INSERT INTO users (name, university, email, u_name, pwd) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../signup.php?error=stmtfailed');
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'sssss', $name, $university, $email, $username, $password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('location: ./login.php?error=none');
    exit();
}

function loginUser($conn, $username, $password) {
    $exists = usernameExists($conn, $username);

    if ($exists === false) {
        header('location: ./login.php?error=doesntexist');
        exit();
    } else {
        if ($password === $exists['pwd']) {
            session_start();
            $_SESSION['userid'] = $exists['uid'];
            header('location: ./home.php?error=none');
            exit();
        }
    }
    
}
?>