<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View File</title>
</head>
<body>
<?php
session_start();
// The file_share variable is redundant for viewFile.php while we want to keep our coding style at the same level
// of the removeaction.php and editaction.php.
// If the file is got from public(shared to the public)
if($_SESSION["file_share"] == "true"){
    $filename = $_SESSION["filename"];
    $full_path = $_SESSION["path_everyone"];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($full_path);

    // Finally, set the Content-Type header to the MIME type of the file, and display the file.
    header("Content-Type: ".$mime);
    header('content-disposition: inline; filename="'.$filename.'";');
    ob_clean();
    readfile($full_path);
// If the files is got from private folder of the user.
}else if($_SESSION["file_share"] == "false"){
    $filename = $_SESSION["filename"];
    $full_path = $_SESSION["path_private"];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($full_path);

    // Finally, set the Content-Type header to the MIME type of the file, and display the file.
    header("Content-Type: ".$mime);
    header('content-disposition: inline; filename="'.$filename.'";');
    ob_clean();
    readfile($full_path);
}
?>
</body>
</html>