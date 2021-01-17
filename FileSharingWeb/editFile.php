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
<!-- A form to let the user decide what the sharing options the want to perform -->
<form action="editaction.php" method="POST">
	<input type="radio" name="check" value="everyone"/>Share to everyone!<br/>
    <input type="radio" name="check" value="onlyMe"/>Do not share to anyone!<br/>
    <input type="submit" value='Submit' />
	<input type="reset" />
</form>
</div>
</body>
</html>
