<?php
//function for sanitizing usernames
//trims username, then sanitizes it, then turns it into lowercase
function sanitize_username($username) {
    return htmlspecialchars(stripslashes(strtolower(filter_var(trim($username), FILTER_SANITIZE_STRING))));
}

session_start();

//if user is already logged in, redirect to mainpage
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("location: mainpage.php");
    exit;
}

//if user just finished registering, show login message
if (isset($_SESSION["regist"]) && $_SESSION["regist"]) {
    $regist_message = "REGISTRATION SUCCESSFUL! PLEASE LOG-IN WITH YOUR NEW USERNAME!";
    $_SESSION["regist"] = false;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //if username is empty, return error message to enter username
    if (empty($_POST["username"])) {
        $username_error = "ERROR: ENTER USERNAME";
    } else {
        //check if username exists in the users.txt file
        $username = sanitize_username($_POST["username"]);
        $file = fopen("/srv/uploads/users.txt", 'r');
        while(!feof($file)) {
            $data_per_line = sanitize_username(fgets($file));
            if ($username == $data_per_line) {
                session_start();
                $_SESSION["username"] = $username;
                $_SESSION["logged_in"] = true;
                header("location: mainpage.php");
                fclose($file);
                exit;
            }
        }
        //if username doesn't exist, return error message that username is invalid
        fclose($file);
        $username_error = "ERROR: INVALID USERNAME";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Simple File Sharing Site</title>
    <link rel="stylesheet" type="text/css" href="login.css" />
</head>
<body>
    <div>
    <div class="center">User Log-in</div>
    <br/>
    <br/>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Username: <input type="text" name="username">
    <input type="submit" value="Login">
    </form>
    <div class="error">
    <?php
    if (!empty($username_error)) {
        echo $username_error;
    }
    if (!empty($regist_message)) {
        echo $regist_message;
    }
    ?>
    </div>
    New User? <a href="register.php">Register</a>
    </div>
</body>
</html>
