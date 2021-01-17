<?php
session_start();
require 'database.php';

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    $logged_in = true;
} else {
    $logged_in = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["option"])) {
        echo "Please select search option";
        header("refresh:2, url=mainpage.php");
        exit;
    } else {
        $option = $_POST["option"];
    }
    if (empty($_POST["search_key"])) {
        echo "Please enter search key";
        header("refresh:2, url=mainpage.php");
        exit;
    } else {
        $search = $_POST["search_key"];
    }
}
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
        <a href="mainpage.php">Main Page</a>
        <a href="userpage.php">My Page</a>
        <a href="newpost.php">Post</a>
        <a href="logout.php">Logout</a>
    <?php } else { ?>
        <a href="mainpage.php">Main Page</a>
        <a href="login.php">Log-In</a>
    <?php } ?>
    <br>
    <br>
    <form method="POST" action="newspage.php">
    <input type="number" name="cur_news_id">
    <input type="submit" name="submit" value="View Full Post by Post ID Number">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <br>
    <br>
    <h1> Search Results </h1>
    <?php
    if ($option == "id") {
        $stmt = $mysqli->prepare("SELECT userinfo.username, news_id, link, title, news_time
                                    FROM newsinfo
                                    JOIN userinfo ON (newsinfo.user_id = userinfo.user_id)
                                    WHERE newsinfo.news_id = $search
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
    } elseif ($option == "username") {
        $stmt = $mysqli->prepare("SELECT userinfo.username, news_id, link, title, news_time
                                    FROM newsinfo
                                    JOIN userinfo ON (newsinfo.user_id = userinfo.user_id)
                                    WHERE userinfo.username LIKE '%$search%'
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
    } elseif ($option == "title") {
        $stmt = $mysqli->prepare("SELECT userinfo.username, news_id, link, title, news_time
                                    FROM newsinfo
                                    JOIN userinfo ON (newsinfo.user_id = userinfo.user_id)
                                    WHERE newsinfo.title LIKE '%$search%'
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
    } elseif ($option == "keyword") {
        $stmt = $mysqli->prepare("SELECT userinfo.username, news_id, link, title, news_time
                                    FROM newsinfo
                                    JOIN userinfo ON (newsinfo.user_id = userinfo.user_id)
                                    WHERE newsinfo.key1 LIKE '%$search%'
                                        OR newsinfo.key2 LIKE '%$search%'
                                        OR newsinfo.key3 LIKE '%$search%'
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
    }
    ?>
</div>
</body>
</html>