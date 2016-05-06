
<html>
        <?php
        $servername = "localhost";
        $username = "root";

        $db = "eventvisualizer";
        
        $conn = mysqli_connect($servername,$username,"secret", $db);
        if(!$conn)
        {
            die("connection failed");
        }
		//data structure should be:
		//var events[0..n][0..8]
		//where n is the number of events
		//8 is each field returned.
		$sql = "SELECT * FROM `events` WHERE 1";
        $query = mysqli_query($conn, $sql);
		
		$i=0;
		echo "<script>" . "\n";
		echo "var events = new Array(5);" . "\n";
		while ($row = mysqli_fetch_assoc($query)) {
			echo "events[".$i."] = new Array(8);" . "\n";
			echo "events[".$i."][0] = ". $row["eventId"] .";" . "\n";
			echo "events[".$i."][1] = ". $row["userId"] .";" . "\n";
			echo "events[".$i.'][2] = "'. $row["name"] .'";'  . "\n";//add " " around string
			echo "events[".$i.'][3] = "'. $row["startingTime"] .'";' . "\n";
			echo "events[".$i.'][4] = "'. $row["endingTime"] .'";' . "\n";
			echo "events[".$i.'][5] = "'. $row["description"] .'";' . "\n";
			echo "events[".$i."][6] = ". $row["xCoordinate"] .";" . "\n";
			echo "events[".$i."][7] = ". $row["yCoordinate"] .";" . "\n";
			echo "events[".$i.'][8] = '. $row["marker"] .';' . "\n";
			$i++;
		}	
		echo "</script>";
        mysqli_close($conn);
        
        ?>
	<head>
    <meta charset="UTF-8">
    <title></title>
    </head>
    <body>
    </body>
</html>
