<?php
// This is the script perform edit post query

session_start();
require 'database.php';


// assign tons of variables
$user_id = $_SESSION["user_id"];
$title = $_SESSION["post_title"];
unset($_SESSION["post_title"]);
$link = $_SESSION["post_link"];
unset($_SESSION["post_link"]);
$key1 = $_SESSION["post_key1"];
unset($_SESSION["post_key1"]);
$cur_news_id = $_SESSION["postId"];

if (isset($_SESSION["post_commentary"])) {
    $commentary = $_SESSION["post_commentary"];
    unset($_SESSION["post_commentary"]);
} else {
    $commentary = "";
}
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
// end of assigning variables

// prepare sql query
$stmt = $mysqli->prepare("update newsinfo set title = ?, link=?, commentary=?, key1=?, key2=?, key3=?, news_time=?
                            where newsinfo.news_id = $cur_news_id");
if(!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('sssssss', $title, $link, $commentary, $key1, $key2, $key3, $timestamp);
$stmt->execute();
$stmt->close();
header("Location: mainpage.php");
exit;
?>