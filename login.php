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
        
        $userUsername = htmlspecialchars($_POST["username"]);
        $userPassword = htmlspecialchars($_POST["password"]);
        
        $conn = mysqli_connect($servername,$username,"", $db);
        if(!$conn)
        {
            die("connection failed");
        }
		
		$sql = "SELECT id FROM `users` WHERE username = '$userUsername' AND password = '$userPassword'";
        $query = mysqli_query($conn, $sql);
		$row = mysqli_fetch_row($query);
		
		if ($row != NULL)
		{
			echo $row[0];
			
			//setcookies
			$cookie_name = "id";
			setcookie($cookie_name, $row[0], time() + 86400); //1 day
			header("Location: gmapi.php");
		}
		else
		{
			header("Location: login_form.html");
		}

        mysqli_close($conn);
        
        ?>
    </body>
</html>
