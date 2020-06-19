<!-- A script that let user delete comment -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Comment</title>
</head>
<body>
<!-- 
Double check if user really want to delete -->
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
    <input type="radio" name="move" value="yes" />Yes, please delete the comment!<br/>
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

// If yes, then perform delete
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($_POST["move"]=="yes"){
        require "database.php";
        $cur_comment_id = $_SESSION["commentId"];
        $userId = $_SESSION["userId"];

        $stmt = $mysqli->prepare("select * from comments where comments.comment_id = $cur_comment_id and comments.user_id = $userId");
        if(!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $result = $stmt->get_result();
        // Check if user own the comment or not.
        if(mysqli_num_rows($result)!=1){
            echo "No such comment or you do not own the comment, redirecting to main page.";
            header("refresh:3, url=mainpage.php");
            exit;
        }

        // if user own the comment, delete it.
        $stmt = $mysqli->prepare("delete from comments where comments.comment_id = $cur_comment_id");
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