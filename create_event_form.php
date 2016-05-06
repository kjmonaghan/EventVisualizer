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
        
        $title = htmlspecialchars($_POST["title"]);
        $description = htmlspecialchars($_POST["desc"]);
        $fromMonth = htmlspecialchars($_POST["fromMonthList"]);
        $fromDay = htmlspecialchars($_POST["fromDayList"]);
        $fromTime = htmlspecialchars($_POST["fromTimeList"]);
        $toMonth = htmlspecialchars($_POST["toMonthList"]);
        $toDay = htmlspecialchars($_POST["toDayList"]);
        $toTime = htmlspecialchars($_POST["toTimeList"]);
        $category = htmlspecialchars($_POST["category"]);
        $fromam = htmlspecialchars($_POST["fromRadioGroup"]);
        $toam = htmlspecialchars($_POST["toRadioGroup"]);
        $userId = $_COOKIE["id"];
        $xCoord = $_COOKIE["xCoordinate"];
        $yCoord = $_COOKIE["yCoordinate"];

		echo ($title . " " . $userId);

        $start = $fromMonth . "/" . $fromDay . " " . $fromTime . $fromam;
        $end = $toMonth . "/" . $toDay . " " . $toTime . $toam;

        $conn = mysqli_connect($servername,$username,"", $db);
        if(!$conn)
        {
            die("connection failed");
        }
		
        //create unique id
        $sql = "SELECT `eventId` FROM `events` ORDER BY `eventId` DESC";
        $query = mysqli_query($conn, $sql);
        $ids = mysqli_fetch_row($query);
        $eventId = $ids[0]+1;

        //Get name
        $sql3 = "SELECT name FROM `users` WHERE id = '$userId'";
        $query3 = mysqli_query($conn,$sql3);
        $userName = mysqli_fetch_row($query3);

        //Post event to database
        #$sql2 = "INSERT INTO `events`(`eventId`,`userId`,`name`,`startingTime`,'endingTime','description','xCoordinate','yCoordinate','marker') VALUES ('$eventId','$userId','$userName[0]','$start','$end','$description','5.0','5.0','$category')";
        #$query2 = mysqli_query($conn,$sql2);
        
        $sql4 = "INSERT INTO `events`(`description`,`endingTime`,`startingTime`,`userId`,`name`,`xCoordinate`,`yCoordinate`, `marker`, `eventId`) VALUES ('$description','$end','$start','$userId','$title','$xCoord','$yCoord','$category', '$eventId')";
        $query4 = mysqli_query($conn, $sql4);

        mysqli_close($conn);

        header("Location: gmapi.php");

        ?>
    </body>
</html>
