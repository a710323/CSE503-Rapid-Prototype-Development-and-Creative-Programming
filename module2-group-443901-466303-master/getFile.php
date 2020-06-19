<?php
session_start();

if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("location: login.php");
    exit;
}

$username=$_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Getting File...</title>
</head>
<body>
<div class="body">
<?php

//check if filename is empty or invalid
if(empty($_POST["file"])) {
    echo "ENTER FILENAME!";
    header("refresh:2, url=mainpage.php");
    exit;
} else if (!preg_match('/^[\w_\.\-]+$/', $_POST["file"])){
    echo "INVALID FILENAME!";
    header("refresh:2, url=mainpage.php");
    exit;
} else {
	$filename = $_POST["file"];
	$_SESSION["filename"] = $filename;
}
//check if username is valid for getting file
if( !preg_match('/^[\w_\-]+$/', $username) ){
    echo "INVALID USERNAME! CONTACT ADMIN";
    header("refresh:2, url=mainpage.php");
	exit;
}
//check if option is checked
if (empty($_POST["options"])) {
    echo "PLEASE SELECT AN OPTION!";
    header("refresh:2, url=mainpage.php");
    exit;
} else {
    $option = $_POST["options"];
}

//getting file path and checking if it exists
//this checks the individual folder first, then the everyone folder, and redirects to mainpage if there isn't a file anywhere
// If the file is found in a private folder, set the file_share variable to false for later usage, delete and edit sharing options,
// to check if the user has the permission or not.
$file_path_private = sprintf("/srv/uploads/%s/private/%s", $username, $filename);
if (!file_exists($file_path_private)) {
	$file_path_everyone = sprintf("/srv/uploads/everyone/%s", $filename);
	$file_path = sprintf("/srv/uploads/everyone/%s", $filename);
	$_SESSION["file_share"] = sprintf("true");
	$_SESSION["path_everyone"] = $file_path_everyone;
} else{
	$_SESSION["file_share"] = sprintf("false");
	$_SESSION["path_private"] = $file_path_private;
	$file_path = sprintf("/srv/uploads/%s/private/%s", $username, $filename);
}
if (!file_exists($file_path)) {
    echo "INVALID FILENAME!!!!";
    header("refresh:2, url=mainpage.php");
    exit;
}

$_SESSION["user"] = $username;


// based on the options the user choose, we navigate user to different php script.
if ($option == "view") {
    echo "GETTING FILE FOR VIEWING...";
	header("refresh:2, url=viewFile.php");
	exit();
} else if ($option == "edit") {
    echo "GETTING FILE FOR EDITING SHARING OPTIONS...";
	header("refresh:2, url=editFile.php");
	exit();
} else if ($option == "delete") {
    echo "GETTING FILE FOR DELETING...";
	header("refresh:2, url=deleteFile.php");
	exit();
}


?>
</div>
</body>
</html>