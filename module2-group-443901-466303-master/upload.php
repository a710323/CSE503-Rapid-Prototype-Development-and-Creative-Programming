<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload File</title>
</head>
<body>
<div class="body">
<?php
session_start();

// Get the filename and make sure it is valid
$filename = basename($_FILES['uploadedfile']['name']);
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
	echo "Invalid filename";
	exit;
}

// Get the username and make sure it is valid
$username = $_SESSION['username'];
if( !preg_match('/^[\w_\-]+$/', $username) ){
	echo "Invalid username";
	exit;
}

$sharing = $_POST["sharing"];
if($sharing == "everyone"){
    $full_path_everyone = sprintf("/srv/uploads/everyone/%s", $filename);
    $full_path_public = sprintf("/srv/uploads/%s/public/%s", $username, $filename);
} else if ($sharing == 'onlyMe'){
    $full_path_private = sprintf("/srv/uploads/%s/private/%s", $username, $filename);
} else{
    $_SESSION["message"] = "Select sharing option";
    header("Location: mainpage.php");
    exit();
}


//$full_path = sprintf("/srv/uploads/%s/%s", $username, $filename);

if($sharing == "everyone"){
    if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path_public) ){
        copy($full_path_public, "/srv/uploads/everyone/" . $filename);
        $_SESSION["message"] = "Upload success!";
        header("Location: upload_success.php");
        exit;
        
    }else{
        //header("Location: upload_failure.html");
        $_SESSION["message"] = "Upload Fail!";
        header("Location: upload_fail.php");
        exit;
    }
} else if($sharing == "onlyMe"){
    if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path_private) ){
        $_SESSION["message"] = "Upload success!";
        header("Location: upload_success.php");
        exit;
        
    }else{
        //header("Location: upload_failure.html");
        $_SESSION["message"] = "Upload Fail!";
        header("Location: upload_fail.php");
        exit;
    }

}
?>
</div>
</body>
</html>