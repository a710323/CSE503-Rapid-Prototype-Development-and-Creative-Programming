<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete File</title>
</head>
<body>
<!-- A form to confirm that the user really want to delete a file,  -->
<div class="body">
<form action="removeaction.php" method="POST">
	<input type="radio" name="check" value="yes"/>Hell Yeah!!!<br/>
    <input type="radio" name="check" value="no"/>No, I do not want to delete the file.<br/>
    <input type="submit" value='Submit' />
	<input type="reset" />
</form>
</div>
</body>
</html>
