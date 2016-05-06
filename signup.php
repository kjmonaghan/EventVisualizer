<?php
$servername = "localhost";	//change to eecs mysql if needed
$username = "root";	

$db = "eventvisualizer";

//user data from .html

$userUsername = htmlspecialchars($_POST["username"]);
$userPassword = htmlspecialchars($_POST["password"]);
$userName = htmlspecialchars($_POST["name"]);

//connect to mysql
$conn = mysqli_connect($servername,$username,"",$db);
if(!$conn)
{
	die("connection failed");
}

//create unique id
$sql2 = "SELECT `id` FROM `users` ORDER BY `id` DESC";
$query2 = mysqli_query($conn, $sql2);
$ids = mysqli_fetch_row($query2);
$userId = $ids[0]+1;

//ensure username is unique

$testname = $_POST["username"];
$sql3 = "SELECT * FROM `users` WHERE `username` = '$testname'";
$query3 = mysqli_query($conn, $sql3);
$row = mysqli_fetch_row($query3);

if ($row != NULL)
{
	echo "username already used";
	header("Location: signup_form.html");
}
else
{
	$sql = "INSERT INTO `users`(`id`, `username`, `password`,`name`) VALUES ('$userId', '$userUsername', '$userPassword', '$userName')";
	$query = mysqli_query($conn, $sql);
	
	//setcookies
	$cookie_name = "id";
	setcookie($cookie_name, $userId, time() + 86400); //1 day
	header("Location: login_form.html");
}

//query to create user acc


mysqli_close($conn);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    </body>
	
	<script>
		//window.location = "gmapi.php";
	</script>
</html>
