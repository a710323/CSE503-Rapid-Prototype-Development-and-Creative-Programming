<?php

// This is a script to allow user edit post
session_start();
require 'database.php';
if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("Location: login.php");
    exit;
} 

$logged_in = $_SESSION["logged_in"];
$username = $_SESSION["username"];
$cur_user_id = $_SESSION["user_id"];

if($_POST["postId"] != "" && $_POST["options"] == "edit"){
    #echo $_POST["postId"];
    $postId = $_POST["postId"];
    #echo $cur_user_id;
    $_SESSION["postId"] = $postId;
    $stmt = $mysqli->prepare("select user_id, news_id, title, link, commentary, key1, key2, key3, news_time from newsinfo 
                            where newsinfo.news_id = $postId and newsinfo.user_id = $cur_user_id");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    #$stmt->bind_result($user_id, $news_id, $title, $link, $commentary, $key1, $key2, $key3, $new_time);
    $result = $stmt->get_result();
    if(mysqli_num_rows($result)!=1){
        echo "No such post or you do not own the post, redirecting to main page.";
        header("refresh:3, url=mainpage.php");
        exit;
    }

} if($_POST["postId"] != "" && $_POST["options"] == "delete"){
    $_SESSION["postId"] = $_POST["postId"];
    $_SESSION["userId"] = $cur_user_id;
    header("Location: deletepost.php");
    exit;
} if(empty($_POST["postId"])){
    echo "Please enter the News_ID in order to delete/edit your post.";
    header("refresh:2, url=mainpage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Info</title>
</head>
<body>
    <h1>The details of your post.</h1>
    <br/>
</body>

<!-- print out the post info -->
<?php
    while($row = $result->fetch_assoc()){
        echo sprintf("Post Title: \t %s <br> Post Link: \t %s <br>  Post Keyword1: \t %s <br>
        Post Keyword2: \t %s <br>Post Keyword3: \t %s <br>Post Commentary: \t %s <br>",
        $row["title"], $row["link"], $row["key1"], $row["key2"], $row["key3"] ,$row["commentary"]);
        $title = $row["title"];
        $link = $row["link"];
        $key1 = $row["key1"];
        $key2 = $row["key2"];
        $key3 = $row["key3"];
        $commentary = $row["commentary"];
    
    $titleErr = $linkErr = $key1Err =$commentaryErr= "";
    }
?>
<!-- 
Display the form to accept user's input -->
<body>
    <h1>New Post</h1>
    * required field
    <br>
    <br>
    <form method="POST" action="editpost1.php">
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
    <br>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <br>
    <input type="submit" name="submit" value="Edit!">
    </form>
</body>
</html>