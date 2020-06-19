
<?php
session_start();

//check if user is logged in, if not, redirect to login page
if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("location: login.php");
    exit;
}

//set username
$username = $_SESSION["username"];
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo ucfirst($username); ?></title>
    <link rel="stylesheet" type="text/css" href="main.css"/>
</head>
<body>
    <div>
    <div class="title">Welcome, <?php echo ucfirst($username) ?>!</div>
    <div class="body">
    <form action="logout.php" method="POST">
        <input type="submit" value="Logout" />
    </form>
    </div>
    <div class="title">Upload File</div>
    <div class="body">
    <form enctype="multipart/form-data" action="upload.php" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
		<label for="uploadfile_input">Choose a file to upload (Maximum size: 2MB) </label> 
        <br/>
        <input name="uploadedfile" type="file" id="uploadfile_input" />
        <br/>
        <input type="radio" name="sharing" value="everyone"/> To Everyone
        <input type="radio" name="sharing" value="onlyMe" /> Only Me
		<input type="submit" value="Upload File" />
    </form>
    </div>
    <div class="title">
    Private Files:
    </div>
    <div class="body">
    <?php
    
    // We google how to get all files in a directory, and scandir is what we got on: https://www.php.net/manual/en/function.scandir.php
    // And we explore more __dir functions from the right hand side of the website, it lists relevant functions. 
    $dir = "/srv/uploads/" . $username . "/private";
    $myarr = scandir($dir);
    
    //To see if this is a directory or not
    if(is_dir($dir)){
        // If we successfully open the directory, opendir is from: https://www.php.net/manual/en/function.opendir.php
        if($opendir = opendir($dir)){
            // readdir is from: https://www.php.net/manual/en/function.readdir.php
            // If we successfully read from the directory
            while(($file = readdir($opendir))!==false){
                // It turns out there are two files are not visible, . and .., we consulted a TA, and we decided to not display the files.
                if($file == "." or $file == ".."){
                    continue;
                } else{
                    echo "File: " . $file . "<br>";
                }
            }
            // close the directory
            closedir($opendir);
        }
    }
    ?>
    </div>
    <div class="title">
    Files You Shared to Public:
    </div>
    <div class="body">
    <?php

    //same code from Private Files
    $dir = "/srv/uploads/" . $username . "/public";
    $myarr = scandir($dir);
    
    if(is_dir($dir)){
        if($opendir = opendir($dir)){
            while(($file = readdir($opendir))!==false){
                if($file == "." or $file == ".."){
                    continue;
                } else{
                    echo "File: " . $file . "<br>";
                }
            }
            closedir($opendir);
        }
    }
    ?>
    </div>

    <div class="title">
        Public Files:
    </div>
    <div class="body">
        <?php
        //same code from Private Files
        $dir = "/srv/uploads/everyone/";
        $myarr = scandir($dir);
        
        if(is_dir($dir)){
            if($opendir = opendir($dir)){
                while(($file = readdir($opendir))!==false){
                    if($file == "." or $file == ".."){
                        continue;
                    } else{
                        echo "File: " . $file . "<br>";
                    }
                }
                closedir($opendir);
            }
        }
        ?>
    </div>
    
    <!-- We use the concept in individual assignment from module 2, let the users to choose what kind of actions they want to take,
    on what files -->
    <form action="getFile.php" method="POST">
    <div class="body">
		<label for="fileRequested"> File Requested:</label>
        <input type="text" name="file" id="fileinput" /> <br/>
        <input type="radio" name="options" value="view"/>View/Download<br/>
        <input type="radio" name="options" value="delete" />Delete<br/>
        <input type="radio" name="options" value="edit" />Edit Sharing Options<br/>
        <input type="submit" value='Submit' />
		<input type="reset" />
	</div>
    </div>
</body>
</html>