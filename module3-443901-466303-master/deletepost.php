<!-- This is a script to delete post -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post</title>
</head>
<body>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
    <input type="radio" name="move" value="yes" />Yes, please delete the post!<br/>
    <input type="radio" name="move" value="no" />No, I do not want to delete...<br/>
    <input type="submit" value="Submit!" />
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <input type="reset" />
</form>
<?php
session_start();

if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("Location: login.php");
    exit;
} 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($_POST["move"]=="yes"){

        require "database.php";
        $cur_post_id = $_SESSION["postId"];
        $userId= $_SESSION["userId"];
        // check if the user can delete the post or not
        $stmt = $mysqli->prepare("select * from newsinfo where newsinfo.news_id = $cur_post_id and newsinfo.user_id = $userId");
        if(!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if(mysqli_num_rows($result)!=1){
            echo "No such post or you do not own the post, redirecting to main page.";
            header("refresh:3, url=mainpage.php");
            exit;
        }
        // Delete the comments associated with the story.
        $stmt = $mysqli->prepare("delete from comments where comments.news_id = $cur_post_id");
        $stmt->execute();
        $stmt->close();
        $stmt = $mysqli->prepare("delete from newsinfo where newsinfo.news_id = $cur_post_id and newsinfo.user_id = $userId");
        if(!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $stmt->close();
        echo "Delete Complete!";
        header("refresh:2, url=mainpage.php");
    }
}
?>
</body>
</html>