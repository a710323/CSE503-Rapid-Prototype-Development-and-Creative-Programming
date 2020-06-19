<?php

// This is a script to let user post commentary.
session_start();

if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("Location: login.php");
    exit;
} 

require 'database.php';

$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];

$titleErr = $linkErr = $key1Err = $commentaryErr= "";
$title = $link = $commentary = $key1 = $key2 = $key3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // if every required field is not empty
    // link validation comes from https://www.w3schools.com/php/php_form_url_email.asp
    if (!empty($_POST["title"]) && !empty($_POST["link"]) && !empty($_POST["key1"]) && !empty($_POST["commentary"])
     && preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", htmlspecialchars($_POST["link"]))) {
        $_SESSION["post_title"] = $_POST["title"];
        
        $_SESSION["post_link"] = htmlspecialchars($_POST["link"]);

        $_SESSION["post_key1"] = $_POST["key1"];

        $_SESSION["commentary"] = $_POST["commentary"];

        // if (!empty($_POST["commentary"])) {
        //     $_SESSION["post_commentary"] = $_POST["commentary"];
        // }
        if (!empty($_POST["key2"])) {
            $_SESSION["post_key2"] = $_POST["key2"];
        }
        if (!empty($_POST["key3"])) {
            $_SESSION["post_key3"] = $_POST["key3"];
        }
        
        header("Location: post.php");
        exit;
    } else {
        // assign proper error message to display.
        if (empty($_POST["title"])) {
            $titleErr = "Title is required";
        } else {
            $title = htmlspecialchars($_POST["title"]);
        }
        if(empty($_POST["commentary"])){
            $commentaryErr="Commentary is required";
        }

        if (empty($_POST["link"])) {
            $linkErr = "Link is required";
        } else {
            $link = htmlspecialchars($_POST["link"]);
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $link)) {
                $linkErr = "Link is invalid";
            }
        }

        if (empty($_POST["key1"])) {
            $key1Err = "At least one keyword is required";
        } else {
            $key1 = htmlspecialchars($_POST["key1"]);
        }

        if (!empty($_POST["commentary"])) {
            $commentary = htmlspecialchars($_POST["commentary"]);
        }
        if (!empty($_POST["key2"])) {
            $key2 = htmlspecialchars($_POST["key2"]);
        }
        if (!empty($_POST["key3"])) {
            $key3 = htmlspecialchars($_POST["key3"]);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post</title>
</head>
<body>

    <!-- A big form to let user to fill out -->
    <h1>New Post</h1>
    * required field
    <br>
    <br>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Title: <input type="text" name="title" value="<?php echo $title;?>"> *
    <?php echo $titleErr;?>
    <br>
    <br>
    Link: <input type="text" name="link" value="<?php echo $link;?>"> *
    <?php echo $linkErr;?>
    <br>
    <br>
    Keyword 1: <input type="text" name="key1" value="<?php echo $key1;?>"> *
    <?php echo $key1Err;?>
    <br>
    <br>
    Keyword 2: <input type="text" name="key2" value="<?php echo $key2;?>">
    <br>
    <br>
    Keyword 3: <input type="text" name="key3" value="<?php echo $key3;?>">
    <br>
    <br>
    Commentary: <textarea name="commentary" rows="5" cols="80" value="<?php echo $commentary;?>"></textarea> *
    <?php echo $commentaryErr;?>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <br>
    <br>
    <input type="submit" name="submit" value="Post!">
    </form>
</body>
</html>
