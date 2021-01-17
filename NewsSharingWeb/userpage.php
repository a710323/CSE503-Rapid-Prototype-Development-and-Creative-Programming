<?php
session_start();
require 'database.php';

if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("Location: login.php");
    exit;
} 

$logged_in = $_SESSION["logged_in"];
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Page</title>
</head>
<body>
    NEWS SITE
    <?php if ($logged_in) { ?>
        <a href="mainpage.php">Main Page</a>
        <a href="logout.php">Logout</a>
    <?php } else { ?>
        <a href="login.php">Log-In</a>
    <?php } ?>
    <br>
    <br>
    <form method="POST" action="newspage.php">
    <input type="number" name="cur_news_id">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <input type="submit" name="submit" value="View Full Post by Post ID Number">
    </form>
    <br>
    <br>
    <?php 
    echo "Your Username: ".$username." | Your User ID: ".$user_id;
    ?>
    <br><br>

    <h3> Edit/Delete Your Post </h3>
    <form action="editpost.php" method="POST">
		<label for="postRequested"> Post Requested:</label>
        <input type="text" name="postId" /> <br/>
        <input type="radio" name="options" value="delete" />Delete Post<br/>
        <input type="radio" name="options" value="edit" />Edit Post<br/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <input type="submit" value='Edit/Delete' />
		<input type="reset" />
    </form>
    <?php

    echo "Your Posts<br>";
    echo "-----------------------------";
    $stmt = $mysqli->prepare("SELECT userinfo.username, news_id, link, title, news_time
                                FROM newsinfo
                                JOIN userinfo ON (newsinfo.user_id = userinfo.user_id)
                                WHERE newsinfo.user_id = $user_id
                                ORDER BY news_time DESC");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($news_username, $news_id, $news_link, $news_title, $news_time);
    while($stmt->fetch()) {
        echo "<br>";
        echo "ID: ".$news_id." | ".$news_title." | ".$news_username;
        echo "<br>";
        echo "<a href=$news_link>$news_link</a>"." | ".$news_time;
        echo "<br>";
    }
    $stmt->close();
    ?>
    <br><br>

    <h3> Edit/Delete Your Comment </h3>
    <form action="editcomment.php" method="POST">
    <div class="body">
		<label for="commentRequested"> Comment Requested:</label>
        <input type="text" name="commentId" id="commentinput" /> <br/>
        <input type="radio" name="options" value="delete" />Delete Comment<br/>
        <input type="radio" name="options" value="edit" />Edit Comment<br/>
        <input type="submit" value='Submit' />
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
		<input type="reset" />
	</div>

    <?php
    echo "Your Comments<br>";
    echo "-----------------------------";
    $stmt = $mysqli->prepare("SELECT comment_id, userinfo.username, comment, comment_time
                                FROM comments
                                JOIN userinfo ON (comments.user_id = userinfo.user_id)
                                WHERE comments.user_id = $user_id
                                ORDER BY comment_time DESC");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($comment_id, $comment_username, $comment, $comment_time);
    while ($stmt->fetch()) {
        echo "<br>";
        echo $comment_id." | ".$comment_username." | ".$comment_time;
        echo "<br>";
        echo $comment;
        echo "<br>";
    }
    $stmt->close();
    ?>

</body>
</html>