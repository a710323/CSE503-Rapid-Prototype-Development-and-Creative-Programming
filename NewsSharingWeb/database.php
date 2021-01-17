<!-- connecting to news_site database (from CSE330 Wiki) -->
<?php

$mysqli = new mysqli('localhost', 'news_admin', 'news_pass', 'news_site');

if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

?>