<?php
session_start();
require 'database.php';

function sanitize_username($username) {
    return htmlspecialchars(stripslashes(strtolower(filter_var(trim($username), FILTER_SANITIZE_STRING))));
}

//if user is already logged in, redirect to mainpage
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("location: mainpage.php");
    exit;
}

$username_error=$password_error=$match_error="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //if username & passwords aren't empty and two passwords match, try registering
    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["re_password"] && !empty($_POST["securityQ"])) 
    && ($_POST["password"] == $_POST["re_password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $securityQ = $_POST["securityQ"];

        //check if username exists
        $stmt = $mysqli->prepare("SELECT COUNT(*), user_id, pw_hash, sq_hash FROM userinfo WHERE username=?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($cnt, $user_id, $pw_hash, $sq_hash);
        $stmt->fetch();

        if ($cnt == 1) {
            $username_error = "Username already exists! Please try another username.";
        } 
        //if username doesn't exist, register
        else {
            //close search statement
            $stmt->close();
            //start insert statement
            $stmt = $mysqli->prepare("INSERT into userinfo (username, pw_hash, sq_hash) values (?, ?, ?)");
            if (!$stmt) {
                printf("Query Prep Failed: %s\n", $mysqli->error);
            }
            $stmt->bind_param('sss', $username, password_hash($password, PASSWORD_DEFAULT), password_hash($securityQ, PASSWORD_DEFAULT));
            $stmt->execute();
            $stmt->close();

            //regist success
            $_SESSION["regist"] = true;
            header("Location: login.php");
            exit;
        }

    } else {
        if (empty($_POST["username"])) {
            $username_error = "Please enter username.";
        }
        if (empty($_POST["password"]) || empty($_POST["re_password"])) {
            $password_error = "Please enter the same password twice.";
        }
        if ($_POST["password"] != $_POST["re_password"]) {
            $match_error = "Your passwords do not match. Please try again.";
        }
        if(empty($_POST["securityQ"])){
            $securityQ_error = "Please enter the security question for the purpose of password recovery.";
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
    <title>Register</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="register">
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
    <input type="password" name="re_password" placeholder="Re-Enter Password">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <!-- <error> -->
    <?php
    if (!empty($password_error)) {
        echo $password_error;
    }
    if (!empty($match_error)) {
        echo $match_error;
    }
    ?>
    <input type="text" name="securityQ" placeholder="Security Question" >
    <?php
    if(!empty($securityQ_error)){
        echo $securityQ_error;
    }
    ?>
    <!-- </error> -->
    <input type="submit" value="Register">
    </form>
    <!-- <login> -->
        <a href="login.php">Log-In</a> 
    <!-- </login> -->
</div>
</body>
</html>