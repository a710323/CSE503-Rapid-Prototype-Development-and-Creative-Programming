<?php

// This is a script that perform query to store the post uplodaed by user into database
session_start();
require 'database.php';

$user_id = $_SESSION["user_id"];
$title = $_SESSION["post_title"];
unset($_SESSION["post_title"]);
$link = $_SESSION["post_link"];
unset($_SESSION["post_link"]);
$key1 = $_SESSION["post_key1"];
unset($_SESSION["post_key1"]);
$commentary = $_SESSION["commentary"];
unset($_SESSION["commentary"]);

// if (isset($_SESSION["post_commentary"])) {
//     $commentary = $_SESSION["post_commentary"];
//     unset($_SESSION["post_commentary"]);
// } else {
//     $commentary = "";
// }
if (isset($_SESSION["post_key2"])) {
    $key2 = $_SESSION["post_key2"];
    unset($_SESSION["post_key2"]);
} else {
    $key2 = "";
}
if (isset($_SESSION["post_key3"])) {
    $key3 = $_SESSION["post_key3"];
    unset($_SESSION["post_key3"]);
} else {
    $key3 = "";
}
$timestamp = date("Y-m-d H:i:s");

$stmt = $mysqli->prepare("INSERT into newsinfo (user_id, title, link, commentary, key1, key2, key3, news_time) values (?, ?, ?, ?, ?, ?, ?, ?)");
if(!$stmt) {
    printf("Query Prep Failed1: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('isssssss', $user_id, $title, $link, $commentary, $key1, $key2, $key3, $timestamp);
$stmt->execute();
$stmt->close();

// $stmt = $mysqli->prepare("SELECT news_id, max(news_time) as newRecord from newsinfo ");
// if(!$stmt){
//     printf("Query Prep Filed2: %s\n" , $mysqli->error);
//     exit;
// }
// $stmt->execute();
// $result = $stmt->get_result();
// while($row = $result->fetch_assoc()){
//     $news_id = $row["news_id"];
// }


// $stmt = $mysqli->prepare("insert into links (news_id, link) values (?,?)");
// if(!$stmt){
//     printf("Query Prep Failed3: %s\n", $mysqli->error);
//     exit;
// }
// $stmt->bind_param('ss', $news_id, $link);
// $stmt->execute();
// $stmt->close();

header("Location: mainpage.php");
exit;
?>