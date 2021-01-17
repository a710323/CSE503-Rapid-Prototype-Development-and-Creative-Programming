<?php
session_start();


# check if the user logged in or not as the comment is only for registered user.
if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("Location: login.php");
    exit;
} 

require 'database.php';

# get basic info(user, news)
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];
$news_id = $_SESSION['news_id'];

$commenterr = "";
$comment = "";

# if user entered some comment
if($_POST['comment'] != ""){
    $stmt = $mysqli->prepare("INSERT into comments (user_id, news_id, comment) values (?, ?, ?)");
    $comment = $_POST['comment'];
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // insert comment into the table
    $stmt->bind_param('sss', $user_id, $news_id, $comment);
    $stmt->execute();
    $stmt->close();
    header("Location: mainpage.php");
} else{
    echo "Enter some words....";
    header("refresh:2, url = mainpage.php");
}
