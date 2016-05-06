<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $servername = "localhost";
        $username = "root";

        $db = "eventvisualizer";
        
        //user data from .html
        
        $eventId = htmlspecialchars($_POST["eventId"]);
        $userId = $_COOKIE["id"];
		$timeStamp = date('m/d') . " " . date('h:i');
		//echo ($timeStamp);
		$comment = htmlspecialchars($_POST["comment"]);
		
		
		//echo $comment;
		//echo $userId;
		//echo $eventId;
        //echo $timeStamp;
		
        $conn = mysqli_connect($servername,$username,"", $db);
        if(!$conn)
        {
            die("connection failed");
        }
		
		//Event id is currently hardcoded.

		$sql = "INSERT INTO `comments`(`eventId`, `userId`, `timeStamp`, `comment`) VALUES ('$eventId', '$userId', '$timeStamp', '$comment')";
        $query = mysqli_query($conn, $sql);
		mysqli_close($conn);
		header("Location: gmapi.php");
        
        ?>
    </body>
</html>
