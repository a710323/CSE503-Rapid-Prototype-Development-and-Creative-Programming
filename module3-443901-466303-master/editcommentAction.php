<?php
// This is the script that edit comment take place.

session_start();
require 'database.php';
if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("Location: login.php");
    exit;
} 

if(empty($_POST["comment"])){
    echo "Enter your comment";
    header("refresh:2, url=mainpage.php");
    exit;
} 

$comment_new = $_POST["comment"];
$user_id = $_SESSION["user_id"];
$cur_comment_id = $_SESSION["commentId"];
$timestamp = date("Y-m-d H:i:s");

$stmt = $mysqli->prepare("update comments set comment = ?, comment_time=?
                            where comments.comment_id = $cur_comment_id and comments.user_id = $user_id");
if(!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('ss', $comment_new, $timestamp);
$stmt->execute();
$stmt->close();
echo "Edit comment complete, redirecting to main page.";
header("Location: mainpage.php");
exit;

?>