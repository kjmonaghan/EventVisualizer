<!DOCTYPE html>
<html>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="//code.jquery.com/qunit/qunit-1.20.0.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<?php
        $servername = "localhost";
        $username = "root";

        $db = "eventvisualizer";
        
        $conn = mysqli_connect($servername,$username,"", $db);
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
		echo "var events = new Array();" . "\n";
		while ($row = mysqli_fetch_assoc($query)) {
			echo "events[".$i."] = new Array();" . "\n";
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
		
		//comments
		$i=0;
		$sql2 = "SELECT * FROM `comments` WHERE 1";
		$query2 = mysqli_query($conn, $sql2);
		echo "var comments = new Array();" . "\n";
		while ($row = mysqli_fetch_assoc($query2))
		{
			echo "comments[".$i."] = new Array();" . "\n";
			echo "comments[".$i."][0] = ". $row["eventId"] .";" . "\n";
			echo "comments[".$i."][1] = ". $row["userId"] .";" . "\n";
			echo "comments[".$i.'][2] = "'. $row["timeStamp"] .'";' . "\n";
			echo "comments[".$i.'][3] = "'. $row["comment"] . '";' . "\n";
			$i++;
		}
		echo "</script>";
        mysqli_close($conn);
        
        ?>
  <head>
    <style type="text/css">
      html, body { height: 100%; margin: 0; padding: 0; }
      #map { height: 100%; }
    </style>
  </head>
  <body>

    <div id="map"></div>
	<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQtTpClvq2kWe7jBUqbcZxhCDCkV7zTYI&callback=initMap">
    </script>
    <script type="text/javascript">

var map;



//Initialize Google Map.
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 38.9544, lng: -95.2502},
    zoom: 15
  });
	
//The content string is added from the create_event_form HTML.
	var contentString = document.getElementById("createeventform");
  
	var infoString = "Event Info";
  
  //Creates an infoWindow with the content stored in the contentString var above.
	var createEventWindow = new google.maps.InfoWindow({
		content: contentString
	});
	
	var infoWindow = new google.maps.InfoWindow({
		content: infoString
	});

	document.body.style.overflow = 'hidden';


//The listener is added to watch for map clicks. A marker will be added on click.
//The DivID for the map is, conveniently enough, "map". 
  map.addListener('click', function(e) { 
			var thismarker = addMarker({position:{lat : e.latLng.lat(),lng : e.latLng.lng()}, marker: 5}, createEventWindow);
			console.log(thismarker);
			createEventWindow.open(map,thismarker);
			document.cookie= ("xCoordinate=" + e.latLng.lat());
			document.cookie= ("yCoordinate=" + e.latLng.lng());
	});
	
//Adds a marker at the location specified in "feature."
	var addMarker = function(feature,createEventWindow){
		var iconBase = 'Markers/';
//The image file chosen for the marker will be selected depending on the "color" included in feature.
		switch(feature.marker){
//For instance, if the color is "blue," then the file chosen will be the blue marker file.
			case 0	:					//KU Event
				iconBase += 'red_';
				break;
			case 1 : 					//Personal
				iconBase += 'blue_';
				break;
			case 2 : 					//Study Group
				iconBase += 'green_';
				break;
			case 3 :					//Sports
				iconBase += 'yellow_';
				break;
			case 4 :					//Community Event
				iconBase += 'orange_';
				break;
//In the default case that a color is not established, a brown marker will be displayed. This is the OTHER case.
			default :
				iconBase += 'brown_';
				break;
		}
//Create a new marker.
		var marker = new google.maps.Marker({
//Obtains the position that is included in the feature object.
		position: feature.position,
		icon: iconBase + 'MarkerE.png',
		map: map
		});

//When the marker is clicked, an infoWindow opens attached to it. This function creates the listener that will "listen" for when the marker is clicked.
		marker.addListener('click', function() { 
			infoWindow.open(map,marker)
	});
	
	return marker;
	}

	//These are really just dummy values for creating a sample array. Delete these later.
	//CHANGING FORMAT! Features will be in the form {eventId, userId, name, startingTime,
	//endingTime, description, xCoordinate, yCoordinate, marker}
		for (var i=0; i<events.length; i++)
		{
		var feature = {eventId: events[i][0], userId: events[i][1], name: events[i][2], startingTime: events[i][3], endingTime: events[i][4], description: events[i][5], xCoordinate : events[i][6], yCoordinate : events[i][7], marker: events[i][8]};
		var features = [feature];
		window.addMarkers(features);
		}
//Adds a marker for each of the two features, using the addMarker function defined above. In this case, the infoWindow is the same for both markers and will
//read "test."

}

//The purpose of the addMarkers function is to add markers from some array, which will be passed into the function.
function addMarkers(features){
//Get number of markers. The number is treated as 2 for this sample.
		var numMarkers = features.length;
		var slashIndex = 2;
		var spaceIndex = 5;
		var date = new Date();
		var milliseconds = 0;
//Sample features are displayed below to be placed on the map. The latitude/longitude objects are the first elements of each feature object,
//while the color string is the second element. The format for the features	is feature = {position: {lat:lat, lng:lng}, color : 'color'}

		for(var i = 0; i< numMarkers;i++)
		{
			slashIndex = features[i].endingTime.search("/");
			spaceIndex = features[i].endingTime.search(" ");
			var month = features[i].endingTime.substr(0, slashIndex);
			var day = features[i].endingTime.substr(slashIndex + 1, spaceIndex - 1);
			var hour = features[i].endingTime.substr(spaceIndex + 1, spaceIndex + 2);
			var minute = features[i].endingTime.substr(spaceIndex + 4, spaceIndex + 5);
			
			month = parseInt(month, 10);
			day = parseInt(day, 10);
			hour = parseInt(hour,10);
			minute = parseInt(minute,10);
		


			var currentDate = new Date();
			var nextMonth = new Date();
			var currentDay = currentDate.getDate();
			var monthword = "null";
			var year = currentDate.getFullYear();
			
			nextMonth.setDate(currentDay+30);
			
			console.log(nextMonth);

		switch(month){
			case 1	:					
				monthWord = 'January';
				break;
			case 2	:
				monthWord = 'February';
				break;
			case 3	:
				monthWord = 'March';
				break;
			case 4	:
				monthWord = 'April';
				break;
			case 5	:
				monthWord = 'May';
				break;
			case 6	:
				monthWord = 'June';
				break;
			case 7	:
				monthWord = 'July';
				break;
			case 8	:
				monthWord = 'August';
				break;
			case 9	:
				monthWord = 'September';
				break;
			case 10	:
				monthWord = 'October';
				break;
			case 11	:
				monthWord = 'November';
				break;
			default :
				monthWord = 'December';
				break;
			}			
			var eventDate = new Date(year, month-1, day, hour, minute, 0,0);

			if(eventDate < currentDate)
				eventDate.setFullYear(eventDate.getFullYear()+1)

			
			if(nextMonth > eventDate)
			{
				placeMarker(features[i]);		
			}
		}
}

//Created a string of HTML to be displayed in the infoWindow, storing in the var buildHTML.
//This function is not implemented in this program at the moment. It was used to build a test HTML and was replaced with create_event_form.html  
function buildHTML(feature){
		var marker = "Event";
		switch(feature.marker){
//For instance, if the color is "blue," then the file chosen will be the blue marker file.
			case 0	:					//KU Event
				marker = "KU Event";
				break;
			case 1 : 					//Personal
				marker = "Personal";
				break;
			case 2 : 					//Study Group
				marker = "Study Group";
				break;
			case 3 :					//Sports
				marker = "Sports";
				break;
			case 4 :					//Community Event
				marker = "Community Event";
				break;
//In the default case that a color is not established, a brown marker will be displayed. This is the OTHER case.
			default :
				marker = "Event";
				break;
		}
//		var content = {eventId: comments[i][0], userId: comments[i][1], timeStamp: comments[i][2], commentString: comments[i][3]};
		result = '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 id="firstHeading" class="firstHeading">' + feature.name + '</h1>'+
      '<hr>' +
	  '<div id="bodyContent">'+
      '<p>' + feature.description + '</p>' +
	  '<p> <i>' + marker + '</i> </p>' +
	  '<hr>' +
	  '<p>' + "From: " + feature.startingTime + '</p>' +
	  '<p>' + "To:   " + feature.endingTime + '</p>' +
	  '<p>' + "Comments" + '</p>' +
	  '<hr>';
	  var comCount = 1;
	  for(var i = 0; i<comments.length; i++)
	  {
		  if(feature.eventId == comments[i][0])
		  {
			 result = result + '<p>' + comCount + ': ' + comments[i][3] + '</p>'; 
			comCount++;
	      }
	  }
	  result = result +'</div>'+
	  '<form name="comment_form" action="comment.php" method="post">Comment: <input type="text" class="form-control" name="comment"/><br><input type="submit" class="btn" value="Submit" onclick="return Redirect2();"/><input type="text" name="eventId" style="display: none;" value="' + feature.eventId.toString() + '"/></form>'+
      '</div>'
		;
//Return said string.
		return result;		
}

//Ok, so this next function is basically a global-scope knockoff of the addMarker function in initMap. The reason I made this was so the addMarkers global function would work.
function placeMarker(feature){
	var iconBase = 'Markers/';
	switch(feature.marker){
				case 0	:					//KU Event
				iconBase += 'red_';
				break;
			case 1 : 					//Personal
				iconBase += 'blue_';
				break;
			case 2 : 					//Study Group
				iconBase += 'green_';
				break;
			case 3 :					//Sports
				iconBase += 'yellow_';
				break;
			case 4 :					//Community Event
				iconBase += 'orange_';
				break;
//In the default case that a color is not established, a brown marker will be displayed. This is the OTHER case.
			default :
				iconBase += 'brown_';
				break;
			}
			var marker = new google.maps.Marker({
			position: {lat: feature.xCoordinate, lng: feature.yCoordinate},
			icon: iconBase + 'MarkerE.png',
			map: map
			});

	var infoWindow = new google.maps.InfoWindow({
		content: buildHTML(feature)
	});
	
//When the marker is clicked, an infoWindow opens attached to it. This function creates the listener that will "listen" for when the marker is clicked.
		marker.addListener('click', function() { 
		infoWindow.open(map,marker)
	});
	
	return marker;
}

    </script>
	<div id="createeventform">
	<html>
<script type="text/javascript">
function Redirect(){
	console.log("test");
	var title = document.forms["create_event"]["title"].value;
	var description = document.forms["create_event"]["desc"].value;
	var fromMonthListIndex = document.create_event.fromMonthList.selectedIndex;
	var fromMonthListValue = document.create_event.fromMonthList.options[fromMonthListIndex].value;
	var fromDayListIndex = document.create_event.fromDayList.selectedIndex;
	var fromDayListValue = document.create_event.fromDayList.options[fromDayListIndex].value;
	var fromTimeListIndex = document.create_event.fromTimeList.selectedIndex;
	var fromTimeListValue = document.create_event.fromTimeList.options[fromTimeListIndex].value;
	var toMonthListIndex = document.create_event.toMonthList.selectedIndex;
	var toMonthListValue = document.create_event.toMonthList.options[toMonthListIndex].value;
	var toDayListIndex = document.create_event.toDayList.selectedIndex;
	var toDayListValue = document.create_event.toDayList.options[toDayListIndex].value;
	var toTimeListIndex = document.create_event.toTimeList.selectedIndex;
	var toTimeListValue = document.create_event.toTimeList.options[toTimeListIndex].value;
	var categoryIndex = document.create_event.category.selectedIndex;
	var categoryValue = document.create_event.category.options[categoryIndex].value;
	
	if (title == "" || description == "" || fromMonthListValue == '0' || fromDayListValue == '0' || fromTimeListValue == '0000' ||
			toMonthListValue == '0' || toDayListValue == '0' || toTimeListValue == '0000' || categoryValue == '0' ||
			!(document.create_event.AMFromRadioGroup.checked || document.create_event.PMFromRadioGroup.checked) ||
			!(document.create_event.AMToRadioGroup.checked || document.create_event.PMToRadioGroup.checked))
	{
		alert("You have left a required field blank!")
		return false;
	}
	else
	{
		return true;
	}
}
function Redirect2(){ var comment = document.forms["comment_form"]["comment"].value; if (comment == ""){alert("You have left a required field blank!"); return false;} else {return true;}}
</script>
<h1>Create Event Form</h1>
<form name="create_event" action="create_event_form.php" onsubmit="return Redirect()" method="post">
*Title: <input type="text" name="title"><br>
*Time Frame: From	<select name="fromMonthList" id="fromMonthList">
							<option value="0">Month</option>
							<option value="1">January</option>
							<option value="2">February</option>
							<option value="3">March</option>
							<option value="4">April</option>
							<option value="5">May</option>
							<option value="6">June</option>
							<option value="7">July</option>
							<option value="8">August</option>
							<option value="9">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
					</select>
					/
					<select name="fromDayList" id="fromDayList">
							<option value="0">Day</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
					</select>
					@
					<select name="fromTimeList" id="fromTimeList">
							<option value="00:00">Time</option>
							<option value="12:00">12:00</option>
							<option value="12:30">12:30</option>
							<option value="1:00">1:00</option>
							<option value="1:30">1:30</option>
							<option value="2:00">2:00</option>
							<option value="2:30">2:30</option>
							<option value="3:00">3:00</option>
							<option value="3:30">3:30</option>
							<option value="4:00">4:00</option>
							<option value="4:30">4:30</option>
							<option value="5:00">5:00</option>
							<option value="5:30">5:30</option>
							<option value="6:00">6:00</option>
							<option value="6:30">6:30</option>
							<option value="7:00">7:00</option>
							<option value="7:30">7:30</option>
							<option value="8:00">8:00</option>
							<option value="8:30">8:30</option>
							<option value="9:00">9:00</option>
							<option value="9:30">9:30</option>
							<option value="10:00">10:00</option>
							<option value="10:30">10:30</option>
							<option value="11:00">11:00</option>
							<option value="11:30">11:30</option>
					</select>
					<label>AM
					<input type="radio" name="fromRadioGroup" id="AMFromRadioGroup" value="AM">
					</label>
					<label>PM
					<input type="radio" name="fromRadioGroup" id="PMFromRadioGroup" value="PM">
					</label>
			<br>
			To		<select name="toMonthList" id="toMonthList">
							<option value="0">Month</option>
							<option value="1">January</option>
							<option value="2">February</option>
							<option value="3">March</option>
							<option value="4">April</option>
							<option value="5">May</option>
							<option value="6">June</option>
							<option value="7">July</option>
							<option value="8">August</option>
							<option value="9">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
					</select>
					/
					<select name="toDayList" id="toDayList">
							<option value="0">Day</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
					</select>
					@
					<select name="toTimeList" id="toTimeList">
							<option value="00:00">Time</option>
							<option value="12:00">12:00</option>
							<option value="12:30">12:30</option>
							<option value="1:00">1:00</option>
							<option value="1:30">1:30</option>
							<option value="2:00">2:00</option>
							<option value="2:30">2:30</option>
							<option value="3:00">3:00</option>
							<option value="3:30">3:30</option>
							<option value="4:00">4:00</option>
							<option value="4:30">4:30</option>
							<option value="5:00">5:00</option>
							<option value="5:30">5:30</option>
							<option value="6:00">6:00</option>
							<option value="6:30">6:30</option>
							<option value="7:00">7:00</option>
							<option value="7:30">7:30</option>
							<option value="8:00">8:00</option>
							<option value="8:30">8:30</option>
							<option value="9:00">9:00</option>
							<option value="9:30">9:30</option>
							<option value="10:00">10:00</option>
							<option value="10:30">10:30</option>
							<option value="11:00">11:00</option>
							<option value="11:30">11:30</option>
					</select>
					<label>AM
					<input type="radio" name="toRadioGroup" id="AMToRadioGroup" value="AM">
					</label>
					<label>PM
					<input type="radio" name="toRadioGroup" id="PMToRadioGroup" value="PM">
					</label>
<br>
*Description: <input type="text" name="desc"><br>
*Category: 			<select name="category" id="category">
						<option value="0">Category</option>
						<option value="1">KU Event</option>
						<option value="2">Personal</option>
						<option value="3">Study Group</option>
						<option value="4">Sports</option>
						<option value="5">Community</option>
						<option value="6">Other</option>
					</select>
<br>
<p>* -- required fields</p>
<input type="submit" value="Submit" class="btn">
</form>
</div>
  </body>

</html>