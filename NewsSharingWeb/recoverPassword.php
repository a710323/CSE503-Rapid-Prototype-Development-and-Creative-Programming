<?php

// This is a script to let user input the new password if the security question is matched.
require 'database.php';
session_start();
function sanitize_username($username) {
    return htmlspecialchars(stripslashes(strtolower(filter_var(trim($username), FILTER_SANITIZE_STRING))));
}
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("location: mainpage.php");
    exit;
}
$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //if username & passwords aren't empty and two passwords match, try registering
    if (!empty($_POST["username"]) && !empty($_POST["securityQ"])) {
        $username = $_POST["username"];
        $securityQ = $_POST["securityQ"];

        $stmt = $mysqli->prepare("SELECT COUNT(*), user_id, sq_hash FROM userinfo WHERE username=?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($cnt, $user_id, $sq_hash);
        $stmt->fetch();

        if($cnt != 1){
            echo "Either username or security question is wrong, or both are wrong";
        } else if($cnt == 1 && password_verify($securityQ, $sq_hash)){
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            unset($_POST["username"]);
            unset($_POST["securityQ"]);
            unset($username);
            unset($securityQ);
            header("Location: recoverAction.php");
            exit;
        }else{
            $_SESSION["auth_fail"] = true;
            header("Location: login.php");
            exit;        
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Recover Password</title>
</head>
<div class="register">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="username" placeholder="Username">
    <!-- <error> -->
    <?php
    if (!empty($username_error)) {
        echo $username_error;
    }
    ?>

    <input type="text" name="securityQ" placeholder="Security Question: Mother's Maiden Name">
    
    <?php
    if(!empty($securityQ_error)){
        echo $securityQ_error;
    }
    ?> 

    <input type="submit" value="Recover">
    </form>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="login.php">Log-In</a> 
</div>
</html>