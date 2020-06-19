<!-- This is a script to display the post, commentary, and comment, 
users, register and unregister can see the page but only register user can comment on post-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> News Page</title>
</head>
<body>
<?php
session_start();
require 'database.php';

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    $logged_in = true;
} else {
    $logged_in = false;
}

if (isset($_SESSION["username"]) && isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    $username = $_SESSION["username"];
    $user_id = $_SESSION["user_id"];
}

$_SESSION['news_id'] = $_POST["cur_news_id"];
$news_id = $_SESSION['news_id'];
?>
    NEWS SITE
    <a href="mainpage.php">Main Page</a>
    <?php if ($logged_in) { ?>
        <a href="logout.php">Logout</a>
    <?php } else { ?>
        <a href="login.php">Log-In</a>
    <?php } ?>
    <br>
    <br>
    <?php
    $stmt = $mysqli->prepare("SELECT userinfo.username, link, title, commentary, key1, key2, key3, news_time
    FROM newsinfo
    JOIN userinfo ON (newsinfo.user_id = userinfo.user_id)
    WHERE newsinfo.news_id = $news_id");
    if(!$stmt){
        printf("Query Prep Failed1: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if(mysqli_num_rows($result)!=1){
        echo "No such post given the news_id.";
        header("refresh:3, url=mainpage.php");
        exit;
    }


    $stmt = $mysqli->prepare("SELECT userinfo.username, link, title, commentary, key1, key2, key3, news_time
                                FROM newsinfo
                                JOIN userinfo ON (newsinfo.user_id = userinfo.user_id)
                                WHERE newsinfo.news_id = $news_id");
    if(!$stmt){
        printf("Query Prep Failed2: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($post_username, $news_link, $news_title, $news_commentary, $news_key1, $news_key2, $news_key3, $news_time);
    $stmt->fetch();
    echo $news_id." | ".$news_title." | ".$post_username;
    echo "<br>";
    echo "<a href=$news_link>$news_link</a>"." | ".$news_time;
    echo "<br>";
    echo $news_key1;
    if (strlen($news_key2) > 0) {
        echo " | ".$news_key2;
    }
    if (strlen($news_key3) > 0) {
        echo " | ".$news_key3;
    }
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "COMMENTARY: ";
    echo "<br>";
    echo "<br>";
    echo $news_commentary;
    echo "<br><br>";
    $stmt->close();
    ?>

    <br><br>
    <form action="comment.php" method="POST">
    <textarea name="comment" rows="5" cols="40"></textarea>
    <br>
    <input type="submit" name="submit" value="Comment!">
    </form>
    <br><br>

    <?php 
    $stmt = $mysqli->prepare("SELECT comment_id, userinfo.username, comment, comment_time
                                FROM comments
                                JOIN userinfo ON (comments.user_id = userinfo.user_id)
                                WHERE news_id = $news_id
                                ORDER BY comment_time DESC");
    if(!$stmt){
        printf("Query Prep Failed3: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($comment_id, $comment_username, $comment, $comment_time);
    while ($stmt->fetch()) {
        echo "--------------------------------------";
        echo "<br>";
        echo $comment_id." | ".$comment_username." | ".$comment_time;
        echo "<br>";
        echo $comment;
        echo "<br>";
        echo "--------------------------------------";
    }
    $stmt->close();
    ?>
</body>
</html>