<?php
session_start();

// This is the script to perform user login and print out proper error message

function sanitize_username($username) {
    return htmlspecialchars(stripslashes(strtolower(filter_var(trim($username), FILTER_SANITIZE_STRING))));
}

//if user is already logged in, redirect to mainpage
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("location: mainpage.php");
    exit;
}

$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

$regist_message=$login_error=$username_error=$password_error="";

//if user just finished registering, show login message
if (isset($_SESSION["regist"]) && $_SESSION["regist"]) {
    $regist_message = "You have been registered. Please log-in using your new username and password";
    $_SESSION["regist"] = false;
}

//if authentication failed, show login error message
if (isset($_SESSION["auth_fail"]) && $_SESSION["auth_fail"]) {
    $login_error = "Invalid username or password/security question answer. Please try again.";
    $_SESSION["auth_fail"] = false;
}


//check if username or password is empty
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //if not empty, try authenticator to login
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $_SESSION["try_username"] = sanitize_username($_POST["username"]);
        $_SESSION["try_password"] = $_POST["password"];
        header("Location: authenticator.php");
        exit;
    } 
    //if empty, show empty error messages
    else {
        if (empty($_POST["password"])) {
            $password_error = "Please enter password.";
        }
        if (empty($_POST["username"])) {
            $username_error = "Please enter username.";
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
    <title>Log-In</title>
</head>
<body>
<div class="login">
    <!-- <message> -->
    <?php
    if (!empty($regist_message)) {
        echo $regist_message;
    }
    ?>
    <!-- </message>
    <error> -->
    <?php
    if (!empty($login_error)) {
        echo $login_error;
    }
    ?>
    <!-- </error> -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="username" placeholder="Username">
    <!-- <error> -->
    <?php
    if (!empty($username_error)) {
        echo $username_error;
    }
    ?>
    <!-- </error> -->
    <input type="password" name="password" placeholder="Password">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />

    <!-- <error> -->
    <?php
    if (!empty($password_error)) {
        echo $password_error;
    }
    ?>
    <!-- </error> -->
    <input type="submit" value="Log-In">
    </form>
    <!-- <regist> -->
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="mainpage.php">Main Page</a> &nbsp; | &nbsp; New User? <a href="register.php">Register</a> 
    <br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Forget Password? <a href="recoverPassword.php">Recover Password</a>
    <!-- </regist> -->
    
</div>
</body>
</html>