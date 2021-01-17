<?php

// This is a script to log user out
session_start();
$_SESSION = array();

session_unset();
session_destroy();

header("Location: mainpage.php");
exit;
?>