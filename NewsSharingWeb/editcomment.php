<?php

// This is a script that let user edit the comment

session_start();
require 'database.php';
if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("Location: login.php");
    exit;
} 
$logged_in = $_SESSION["logged_in"];
$username = $_SESSION["username"];
$cur_user_id = $_SESSION["user_id"];
if($_POST["commentId"] != "" && $_POST["options"] == "edit"){
    #echo $_POST["postId"];
    $commentId = $_POST["commentId"];
    $_SESSION["commentId"] = $commentId;
    // Get the info of the comment
    $stmt = $mysqli->prepare("select user_id, news_id, comment_id, comment, comment_time from comments 
                                where comments.comment_id = $commentId and comments.user_id = $cur_user_id");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    //Check if the comment posted by the user.
    if(mysqli_num_rows($result)!=1){
        echo "No such comment or you do not own the comment, redirecting to main page.";
        header("refresh:3, url=mainpage.php");
        exit;
    }
} if($_POST["commentId"] != "" && $_POST["options"] == "delete"){
    $_SESSION["commentId"] = $_POST["commentId"];
    $_SESSION["userId"] = $cur_user_id;
    header("Location: deletecomment.php");
    exit;
} if(empty($_POST["commentId"])){
    echo "Please enter the Comment_ID in order to delete/edit your Comment.";
    header("refresh:2, url=mainpage.php");
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
    <h1>Below is your comment.</h1>
    <br/>
</body>
<?php
    while($row = $result->fetch_assoc()){
        echo sprintf("Comment ID is %s: <br> %s",$row["comment_id"], $row["comment"]);
        // $userId = $row["user_id"];
        // $newsId = $row["news_id"];
        // $commentId = $row["comment_id"];
        $comment = $row["comment"];
        #$timestamp = $row["comment_time"];
    }
?>

<body>
    <h1>New Comment</h1>
    <br>
    <br>
    <form method="POST" action="editcommentAction.php">
    <textarea name="comment" rows="5" cols="40"></textarea>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <br>
    <input type="submit" name="submit" value="Comment!">
    <br><br>
</body>
