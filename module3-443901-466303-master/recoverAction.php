<!-- This is a script perform query to reset password -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<?php
session_start();
require 'database.php';

$username = $_SESSION["username"];
// Sanatize username
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
    if (!empty($_POST["password"]) && !empty($_POST["re_password"])) {
        if ($_POST["password"] != $_POST["re_password"]) {
            $match_error = "Your passwords do not match. Please try again.";
            echo $match_error;
            header("refresh: 3, url=login.php");
        }
        $password = $_POST["password"];
        $user_id = $_SESSION["user_id"];

        //check if username exists
        $stmt = $mysqli->prepare("UPDATE userinfo set pw_hash = ? WHERE userinfo.username = '$username' and userinfo.user_id =$user_id");
        if(!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $pw_hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param('s', $pw_hashed);
        $stmt->execute();
        $stmt->close();
        unset($_SESSION["user_id"]);
        unset($_POST["password"]);
        unset($_SESSION["username"]);
        echo "Successfully reset the password, redirecting to login page.";
        header("refresh:2, url=login.php");
        exit;
    }
}

?>

<!-- Display the website -->
<body>
<div class="register">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
    <!-- </error> -->
    <input type="submit" value="Reset Password">
    </form>
    <!-- <login> -->
        <a href="login.php">Log-In</a> 
    <!-- </login> -->
</div>
</body>
</html>