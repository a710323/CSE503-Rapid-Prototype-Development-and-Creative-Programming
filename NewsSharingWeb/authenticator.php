<?php
session_start();
require 'database.php';

//assign session variables of username/password attempt
$username = $_SESSION["try_username"];
$password = $_SESSION["try_password"];

//destroys session variables just in case
unset($_SESSION["try_username"]);
unset($_SESSION["try_password"]); 

//search from userinfo table
$stmt = $mysqli->prepare("SELECT COUNT(*), user_id, pw_hash FROM userinfo WHERE username=?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($cnt, $user_id, $pw_hash);
$stmt->fetch();

// there is a match
if ($cnt == 1 && password_verify($password, $pw_hash)) {
    //login success
    $_SESSION["logged_in"] = true;
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $username;
    header("Location: mainpage.php");
    exit;
} else {
    //login fail
    $_SESSION["auth_fail"] = true;
    header("Location: login.php");
    exit;
}
?>