<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete File</title>
</head>
<body>
<div class="body">
<?php
session_start();
//If the user really wants to delete the file
    if($_POST["check"] == "yes"){
        // check is the file from public
        if($_SESSION["file_share"] == "true"){
            $username = $_SESSION["user"];
            $filename= $_SESSION["filename"];
            // if the file is from public, but did not belong to the user, reject it.
            if(!file_exists("/srv/uploads/" . $username . "/public/". $filename)){
                echo "You do not have permission!";
                header("refresh:2, url=mainpage.php");
                exit();
            }
            $path_everyone = $_SESSION["path_everyone"];
            $path_public = sprintf("/srv/uploads/%s/public/%s", $username, $filename);   
            unlink($path_everyone);
            unlink($path_public);
            echo "Delete complete";
            header("refresh:2, url=mainpage.php");
            exit();
        } else if($_SESSION["file_share"] == "false"){
            $path = $_SESSION["path_private"];  
            unlink($path);
            echo "Delete complete";
            header("refresh:2, url=mainpage.php");
            exit();
        }
    }else{
        echo "Nothing change ....";
        header("refresh:2, url=mainpage.php");
        exit();
    }
?>
</div>
</body>
</html>
