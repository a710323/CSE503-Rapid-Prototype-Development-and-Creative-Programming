<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Sharing Option</title>
</head>
<body>
<div class="body">
<?php
session_start();
// if the file got from public(everyone folder)
if ($_SESSION["file_share"] == "true"){
    $username = $_SESSION["user"];
    $filename= $_SESSION["filename"];
    // if the user want to make the file to the public, nothing needs to change.
    if($_POST["check"] == "everyone"){
        echo "File is already sharing!";
        header("refresh: 2, url=mainpage.php");
        exit();
    }

    // if user want to change to file to private.
    if($_POST["check"] == "onlyMe"){
        // check if the user has the permission by searching file from the user's public folder, if there's no such a file,
        // which means the file does not belong to him/her.
        if(!file_exists("/srv/uploads/" . $username . "/public/" . $filename)){
            echo "No permission!";
            header("refresh: 2, url = mainpage.php");
            exit();
        } 
        // If we find the file in user's public folder.
        else{
            $path = $_SESSION["path_everyone"];
            $path_private = sprintf("/srv/uploads/%s/private/%s", $username, $filename);
            $path_public = sprintf("/srv/uploads/%s/public/%s", $username, $filename);
            copy($path, $path_private);
            unlink($path);
            unlink($path_public);
            echo "Getting All Things Set Up Properly ...";
            header("refresh:2, url=mainpage.php");
        }
    }
}
// if the file got from private folder from the user.
else if($_SESSION["file_share"] == "false"){
    $username = $_SESSION["user"];
    $filename= $_SESSION["filename"];
    // if the user want to make it to public.
    if($_POST["check"] == "everyone"){
        // it may seen to be redundant as the file is found in the user's private folder.
        // which means it should not visible by other users.
        // But if a malicious user somehow knows the filenames which is private files of other users, 
        // we need this if statement to prevent unauthorized changes.
        if(!file_exists("/srv/uploads/".$username . "/private/" . $filename)){
            echo "No permission!!!";
            header("refresh: 2, url = mainpage.php");
            exit();
        } 
        // the user want to make public and the user has the file in private folder.
        // copy the file to public folder and everyone folder and delete the file in private folder.
        else{
            $path = $_SESSION["path_private"];
            $path_everyone = sprintf("/srv/uploads/everyone/%s", $filename);
            $path_public = sprintf("/srv/uploads/%s/public/%s", $username, $filename);
            copy($path, $path_everyone);
            copy($path, $path_public);
            unlink($path);
            echo "Getting All Things Set Up Properly ...";
            header("refresh: 2, url = mainpage.php");
            exit();
        }
    }
    // nothing needs to be changed if the user want a private file to be private.
    else if($_POST["file_share"] == "onlyMe"){
        echo "The File is already private" ;
        header("refresh:2 , url=mainpage.php");
        exit();
    }
}

header("Location: mainpage.php");
exit;
?>
</div>
</body>
</html>