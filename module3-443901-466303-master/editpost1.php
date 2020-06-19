<?php

// This is a script that take parameter from editpost.php and perform additional steps
// including assign variables and redirect users to proper page
session_start();
require 'database.php';

    // if every required fields has user's input then
    // Link validation comes from https://www.w3schools.com/php/php_form_url_email.asp
    if ($_POST["title"] != "" && $_POST["link"]!="" && $_POST["key1"]!="" && 
        $_POST["commentary"] != "" && preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", htmlspecialchars($_POST["link"]))) {
        $_SESSION["post_title"] = $_POST["title"];
        
        $_SESSION["post_link"] = htmlspecialchars($_POST["link"]);

        $_SESSION["post_key1"] = $_POST["key1"];

        $_SESSION["post_commentary"] = $_POST["commentary"];

        // if (!empty($_POST["commentary"])) {
        //     $_SESSION["post_commentary"] = $_POST["commentary"];
        // }

        // if the post has second keyword
        if (!empty($_POST["key2"])) {
            $_SESSION["post_key2"] = $_POST["key2"];
        }
        // if the post has third keyword
        if (!empty($_POST["key3"])) {
            $_SESSION["post_key3"] = $_POST["key3"];
        }
        // redirect to the php script that perform the query
        header("Location: editpostAction.php");
        exit;
    } else {
        // missing title
        if (empty($_POST["title"])) {
            $titleErr = "Title is required";
            echo $titleErr;
            header("refresh:2, url=mainpage.php");
            exit;
        } else {
            $title = htmlspecialchars($_POST["title"]);
        }
        // missing commentary;
        if(empty($_POST["commentary"])){
            $commentaryErr = "Commentary is required";
            echo $commentaryErr;
            header("refresh:2, url=mainpage.php");
            exit;
        }
        // missing link
        if (empty($_POST["link"])) {
            $linkErr = "Link is required";
            echo $linkErr;
            header("refresh:2, url=mainpage.php");
            exit;
        } else {
            $link = htmlspecialchars($_POST["link"]);
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $link)) {
                $linkErr = "Link is invalid";
                echo $linkErr;
                header("refresh:2, url=mainpage.php");
                exit;
            }
        }
        //missing first keyword
        if (empty($_POST["key1"])) {
            $key1Err = "At least one keyword is required";
            echo $key1Err;
            header("refresh:2, url=mainpage.php");
            exit;
        } else {
            $key1 = htmlspecialchars($_POST["key1"]);
        }

        if (!empty($_POST["commentary"])) {
            $commentary = htmlspecialchars($_POST["commentary"]);
            echo $titleErr;
            header("refresh:2, url=mainpage.php");

        }
        if (!empty($_POST["key2"])) {
            $key2 = htmlspecialchars($_POST["key2"]);
        }
        if (!empty($_POST["key3"])) {
            $key3 = htmlspecialchars($_POST["key3"]);
        }
    }
?>

