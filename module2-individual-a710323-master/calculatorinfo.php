<!-- 
    For the calculator php file, I quoted from course wiki:
    https://classes.engineering.wustl.edu/cse330/index.php?title=PHP
    specifically the section of POST: Passing Variables via Form  
    
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Operation Information</title>
</head>
<body>
<?php

//Cast both numbers into float, just want to be explicit and clear in this script.
$firstnum = (float) $_POST['firstnumber'];
$secondnum = (float) $_POST['secondnumber'];
// $birthyear = (int) $_POST['birthyear'];
// $gender = $_POST['gender'];
$operation = $_POST['operation'];

// The following is quoted from course wiki, php switch cases:
// $food = "Apple";

// switch ($food) {

// case "Apple":
// case "Banana":
// case "Cherry":
// case "Grapefruit":
// 	echo "You must like fruit!";
// 	break;
// case "Broccoli":
// case "Spinach":
// 	echo "You must like vegetables!";
// 	break;
// default:
// 	echo "You don't like fruits or vegetables!";
// 	break;

// }

switch($operation){
    case "add":
    $ans = $firstnum + $secondnum;
    $sign = "+";
    break;
    case "subtract":
    $ans = $firstnum - $secondnum;
    $sign = "-";
    break;
    case "multiply":
    $ans = $firstnum * $secondnum;
    $sign = "*";
    break;

    /* For the case of division, we want to make sure the denominator isn't zero */
    case "divide":
    if($secondnum == 0.0){
        echo "Denominator cannot be zero";
        exit();
    }
    $ans = $firstnum / $secondnum;
    $sign = "/";
    break;
}

//Display the result in a clear format.
/* The template of print statement is from course wiki: https://classes.engineering.wustl.edu/cse330/index.php?title=PHP
    especially from GET: Passing Variables via URL */
printf("<p> The result is as follows: \n");
printf("<p> %.2f %s %.2f = %.2f </p> \n",
    htmlentities($firstnum),
    $sign,
    htmlentities($secondnum),
    htmlentities($ans)
);
?>
</body>
</html>