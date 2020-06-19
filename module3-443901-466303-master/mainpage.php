<?php

// This is the main page that displays posts and information of posts.
session_start();
require 'database.php';
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    $logged_in = true;
} else {
    $logged_in = false;
}
$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>News</title>
</head>
<body>
<div class="mainpage">
    NEWS SITE
    <?php if ($logged_in) { ?>
        <a href="userpage.php">My Page</a>
        <a href="newpost.php">Post</a>
        <a href="logout.php">Logout</a>
    <?php } else { ?>
        <a href="login.php">Log-In</a>
    <?php } ?>
    <br>
    <br>
    <!-- ask user to input the query to search post -->
    <form method="POST" action="search.php">
    Search:
    <input type="text" name="search_key"/><br/> 
    <input type="radio" name="option" value="id"/>News ID<br/>
    <input type="radio" name="option" value="username"/>Username<br/>
    <input type="radio" name="option" value="title"/>Title<br/>
    <input type="radio" name="option" value="keyword"/>Keyword<br/>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <input type="submit" value='Search!' />
	<input type="reset" />
    </form>
    <br>
    <br>

    <!-- ask user to input the news_id to see the whole post -->
    <form method="POST" action="newspage.php">
    <input type="number" name="cur_news_id">
    <input type="submit" name="submit" value="View Full Post by Post ID Number">
    </form>
    <br>
    <br>
    <?php 
    $stmt = $mysqli->prepare("SELECT userinfo.username, news_id, title, link, news_time
                                FROM newsinfo
                                JOIN userinfo ON (newsinfo.user_id = userinfo.user_id)
                                ORDER BY news_time DESC");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($news_username, $news_id, $news_title, $link, $news_time);
    while($stmt->fetch()) {
        echo "<br>";
        echo "ID: ".$news_id." | ".$news_title." | ".$news_username;
        echo "<br>";
        echo "<a href=$link>Here is the link to the news.</a>"." | ".$news_time;
        echo "<br>";
    }
    $stmt->close();

    ?>
</div>
</body>
</html>