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


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //if username is empty, return error message to enter username
    if (empty($_POST["username"])) {
        $username_error = "ERROR: ENTER USERNAME";
    } else {
        $username = sanitize_username($_POST["username"]);
        //check username validity
        if (preg_match("/[^A-Za-z0-9]/", $username) || strlen($username) < 1) {
            $username_error = "ERROR: INVALID USERNAME. USERNAME MUST ONLY HAVE LETTERS AND NUMBERS";
        } else {
            $regist_fail = false;
            //check if username is taken
            $file = fopen("/srv/uploads/users.txt", "r+");
            while(!feof($file)) {
                $data_per_line = sanitize_username(fgets($file));
                if ($data_per_line == $username) {
                    $username_error = "ERROR: USERNAME ALREADY TAKEN. PLEASE ENTER ANOTHER USERNAME";
                    $regist_fail = true;
                break;
                }
            }
            //username is not taken so registration should be success
            if(!$regist_fail) {
                session_start();
                $_SESSION["regist"] = true;
                file_put_contents("/srv/uploads/users.txt", "\n", FILE_APPEND);
                file_put_contents("/srv/uploads/users.txt", $username, FILE_APPEND);
                fclose($file);
                $new_user_directory="/srv/uploads/".$username;
                mkdir($new_user_directory, 0777);
                mkdir($new_user_directory . "/private", 0777);
                mkdir($new_user_directory . "/public", 0777);
                header("location: login.php");
                exit;
            }
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
    <title>New User</title>
    <link rel="stylesheet" type="text/css" href="login.css" />
</head>
<body>
    <div>
    <div class="center">New User Registration</div>
    <br/>
    <br/>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    New Username: <input type="text" name="username">
    <input type="submit" value="Register">
    </form>
    <div class="error">
    <?php
    if (!empty($username_error)) {
        echo $username_error;
    }
    ?>
    </div>
    <a href="login.php">Return to log-in page</a>
    </div>
</body>
</html>
