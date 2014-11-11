<?php

	echo '<script src="js/validateSelection.js"></script>';
	session_start();
	$query  = explode('&', $_SERVER['QUERY_STRING']);
	//$degree = $_SESSION['degree'];
	$degree = "SE";
	$year_status = 1;
	//$year_status = $_SESSION['year_status'];
	$params = array();

	$selectedCourses = array();

	foreach( $query as $param )
	{
 	 list($name, $value) = explode('=', $param);
 	 $params[urldecode($name)][] = urldecode($value);
	}
	$courselist = "";
	while (list($key, $val) = each($params)) {
		if (preg_match("/[A-Z]{4}[0-9]{4}/", "$key", $matches)) {
  			$courselist = $courselist . "'" . $key . "',";
  			array_push($selectedCourses, $key);
		}		
    } 
    $dayMap = array(
    	"M" => 0,
    	"T" => 1,
    	"W" => 2,
    	"R" => 3,
    	"F" => 4,
    	);
    $dayArr = array(
    	"Monday","Tuesday","Wednesday","Thursday","Friday",
    	);
	$timeMap = array(
    	"08:35:00" => 0,
    	"09:05:00" => 1,
    	"09:35:00" => 2,
    	"10:05:00" => 3,
    	"10:35:00" => 4,
    	"11:05:00" => 5,
    	"11:35:00" => 6,
    	"12:05:00" => 7,
    	"12:35:00" => 8,
    	"13:05:00" => 9,
    	"13:35:00" => 10,
    	"14:05:00" => 11,
    	"14:35:00" => 12,
    	"15:05:00" => 13,
    	"15:35:00" => 14,
    	"16:05:00" => 15,
    	"16:35:00" => 16,
    	"17:05:00" => 17,
    	"17:35:00" => 18,
    	"18:05:00" => 19,
    	"18:35:00" => 20,
    	"19:05:00" => 21,
    	"19:35:00" => 22,
    	"20:05:00" => 23,
    	"20:35:00" => 24,
    	"21:05:00" => 25,
    	);
	$testArr = array();
    $timeArr = array();
    for($i=0;$i<6;$i++) {
    	array_push($timeArr,array());
    	array_push($testArr,array());
    	for($j=0;$j<26;$j++) {
    		array_push($timeArr[$i],"");
    		array_push($testArr[$i],array());
    	}
    }

    function timeSlotsRequired($start, $end){
    	$bigVal1 = substr($start,0,2);
    	$smallVal1 = substr($start,3,2);
    	$totalVal1 = intVal($bigVal1);
    	if (strpos($smallVal1,'3') !== false) {
    		$totalVal1 = $totalVal1 + 0.5;
		}
		$bigVal2 = substr($end,0,2);
    	$smallVal2 = substr($end,3,2);
    	$totalVal2 = intVal($bigVal2);
    	if (strpos($smallVal2,'2') !== false) {
    		$totalVal2 = $totalVal2 + 0.5;
		} else if (strpos($smallVal2,'5') !== false) {
			$totalVal2 = $totalVal2 + 1;
		}
		return ($totalVal2 - $totalVal1) / 0.5;
    }




    $conflict = "none";
    foreach( $selectedCourses as $entry) {
    	$course = substr($entry,0,8);
    	$sect = substr($entry,9);
    	

    	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
		if ($conn->connect_error) {
			trigger_error('Connection to database has failed');
		}

		$courses_query = "SELECT * FROM cu_running_courses WHERE course_name ='$course' AND course_section = '$sect';";
		$courses_query_rs = $conn->query($courses_query);
		$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);
		//echo $courses_query;

		foreach($courses_array as $course) {
			//echo $course['course_name'];
			$start = $course['class_start'];
			$end = $course['class_end'];
			$daystr = $course['class_days'];

			$days = explode(",", $daystr);
			$numTimeSLots = timeSlotsRequired($start,$end);
			$startIndex = $timeMap[$start];
			foreach($days as $day){
				$val = $dayMap[$day];
				for($i=$startIndex;$i < $startIndex+$numTimeSLots;$i++){

					$timeArr[$val][$i] = $entry;
					array_push($testArr[$val][$i], $entry);
					array_push($testArr[$val][$i], $start);
					array_push($testArr[$val][$i],$numTimeSLots);
					if($i == $startIndex){
						array_push($testArr[$val][$i],"place");
					} else{
						array_push($testArr[$val][$i],"placed");
					}
					if(sizeof($testArr[$val][$i]) > 4){
						$conflict = "conflict";
					}
				}
			}


			
		}
    }

    echo '<style type="text/css">
	.dayColumn {
		width: 15%;
		display: inline;
		float:left;
		height:675;
		position:relative;
	}
	.Column {
		height: 25px;
	} 
	.specialColumn{
		width:100%;
		position:absolute;
		background-color: #BDBDBD;
	}
	</style>';
    //echo "<table>";
    echo '<input id="conflict" type="hidden" value="' . $conflict . '"/>';
    echo '<div id="times" class="dayColumn">time\day' . 
    		'<div class="Column">8:35</div>
				<div class="Column">9:05</div>
				<div class="Column">9:35</div>
				<div class="Column">10:05</div>
				<div class="Column">10:35</div>
				<div class="Column">11:05</div>
				<div class="Column">11:35</div>
				<div class="Column">12:05</div>
				<div class="Column">12:35</div>
				<div class="Column">13:05</div>
				<div class="Column">13:35</div>
				<div class="Column">14:05</div>
				<div class="Column">14:35</div>
				<div class="Column">15:05</div>
				<div class="Column">15:35</div>
				<div class="Column">16:05</div>
				<div class="Column">16:35</div>
				<div class="Column">17:05</div>
				<div class="Column">17:35</div>
				<div class="Column">18:05</div>
				<div class="Column">18:35</div>
				<div class="Column">19:05</div>
				<div class="Column">19:35</div>
				<div class="Column">20:05</div>
				<div class="Column">20:35</div>
				<div class="Column">21:05</div>' .
			'</div>';

   	
    for($k = 0; $k < 5 ; $k++) {
    	echo '<div id=' . $dayArr[$k] . ' class="dayColumn">'. $dayArr[$k];

	    for($i = 0; $i < 26 ; $i++) {

	    	$course = "";
	    	$start = "";
	    	$num = -1;
	    	$placed = false;
	    	$sizeofSection = sizeof($testArr[$k][$i]);
	    	for($j = 0 ; $j < $sizeofSection ; $j++) {
	    		
	    		if($j % 4 == 0) {
	    			if($course != "") {
	    				echo '<div class="specialColumn" style="height:' . $num*25 . 'px;top:' . ($timeMap[$start]+1)*25 . 'px;">' . $course . '</div>';
	    			}
	    			$course = $testArr[$k][$i][$j];
	    		}
	    		if($j % 4 == 1) {
	    			$start = $testArr[$k][$i][$j];
	    		}
	    		if($j % 4 == 2) {
	    			$num = $testArr[$k][$i][$j];
	    		}
	    		if($j % 4 == 3) {
	    			if("placed" == $testArr[$k][$i][$j]){
	    				$placed = true;
	    			}
	    		}
	    	}
	    	if(!$placed && $course != "" && $start != "" && $num != -1) {
	    		
	    		echo '<div class="specialColumn" style="height:' . $num*25 . 'px;top:' . ($timeMap[$start]+1)*25 . 'px;">' . $course . '</div>';
	    	}
	    }
    	echo '</div>';
    }
    
    

    /*for($i=0;$i<26;$i++) {
    	echo "<tr>";
    	for($j=0;$j<5;$j++) {
    		echo "<td>";
    		echo $timeArr[$j][$i];
    		echo "</td>";
    	}
    	echo "</tr>";
    }
    echo "</table>";*/
    

?>