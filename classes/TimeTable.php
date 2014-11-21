<?php

	require_once 'TimeSlot.php';
	class TimeTable {

		private $courseList = array();
		private $htmlTimeTable = "";
		private $generated = FALSE;
		private $testArr;


		private $conflict = "none";
		private $dayMap = array(
	    	"M" => 0,
	    	"T" => 1,
	    	"W" => 2,
	    	"R" => 3,
	    	"F" => 4,
	    );
    	private $dayArr = array(
    		"Monday","Tuesday","Wednesday","Thursday","Friday",
    	);
		private $timeMap = array(
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
		


		public function __construct( /*string*/ $courses = array()){
			$this->courseList = $courses;
		}
		public function __get($property) {
    		if (property_exists($this, $property)) {
      			return $this->$property;
    		}
  		}

		public function __set($property, $value) {
		    if (property_exists($this, $property)) {
		     	$this->$property = $value;
		    }
			return $this;
		}

		public function hasConflict() {
			if($this->conflict == "none") {
				return FALSE;
			} else {
				return TRUE;
			}
		}


		function timeSlotsRequired($start, $end){
	    	if($start == $end) {
	    		return 0;
	    	}
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

   		function generateTimeTable() {
   			$this->testArr = array();
	    	for($i=0;$i<6;$i++) {
		    	array_push($this->testArr,array());
		    	for($j=0;$j<26;$j++) {
		    		array_push($this->testArr[$i],array());
		    	}
	    	}

	    	$returnString = "";



	    	foreach( $this->courseList as $entry) {

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
					if($start == "00:00:00") {
						continue;
					}

					$days = explode(",", $daystr);
					$numTimeSLots = $this->timeSlotsRequired($start,$end);
					$startIndex = $this->timeMap[$start];
					foreach($days as $day){
						$val = $this->dayMap[$day];
						for($i=$startIndex;$i < $startIndex+$numTimeSLots;$i++){

							$timeArr[$val][$i] = $entry;
							$timeSlot = new TimeSlot($entry,$start,$numTimeSLots);
							array_push($this->testArr[$val][$i],$timeSlot);
							
							if(sizeof($this->testArr[$val][$i]) > 1){
								$this->conflict = "conflict";
							}
						}
					}


					
				}
	    	}
	    	$this->generated = TRUE;
	    }






	    function displayHtml() {
	    	if(!$this->generated) {
	    		$this->generateTimeTable();
	    	}
	    	//create html
	    	$returnString = '<style type="text/css">
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
				}
				</style>';
			    //echo "<table>";
			    $returnString = $returnString . '<input id="conflict" type="hidden" value="' . $this->conflict . '"/>';
			    $returnString = $returnString . '<div id="times" class="dayColumn">time\day' . 
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
		    	$returnString = $returnString . '<div id=' . $this->dayArr[$k] . ' class="dayColumn">'. $this->dayArr[$k];
		    	//echo $k . "		" . $this->dayArr[$k];
		    	$displayNextDivAt = -1;
		    	$displayLevel = 0;
		    	$overallCourseText = "";
		    	$overallEarliest = "";
		    	$overallNum = 0;
		    	$lastNum = -1;
		    	$lastStart = "";
		    	$lastLength = -1;
		    	$numCourses = 0;
			    for($i = 0; $i < 26 ; $i++) {

			    	$courseText = "";
			    	$earliestStart = "";
			    	$totalNum = 0;

			    	$course = "";
			    	$start = "";
			    	$num = -1;
			    	$placed = false;
			    	$sizeofSection = sizeof($this->testArr[$k][$i]);
			    	if($lastLength > $sizeofSection && $lastLength != -1) {
			    		$displayLevel = $displayLevel - 1;
			    	} else{
			    		$lastLength = $sizeofSection;
			    	}
			    	for($j = 0 ; $j < $sizeofSection ; $j++) {
			    		
			    		
			    		$placed = false;
			    		$course = $this->testArr[$k][$i][$j]->courseName;
			    		$start = $this->testArr[$k][$i][$j]->startTime;
			    		$num = $this->testArr[$k][$i][$j]->length;


						if(strpos($overallCourseText,$course) === FALSE){
			    			$numCourses++;
			    			$overallCourseText = $overallCourseText . $course . '</br>';
			    		}
			    		if($lastStart == "") {
			    			$lastStart = $start;
			    		}
			    		if($overallEarliest == "") {
			    			$overallEarliest = $start;
			    		}
			    		if($displayNextDivAt == -1) {
			    			$displayNextDivAt = $i + $num;
			    		}
			    		if($overallNum == 0) {
			    			$overallNum = $num;
			    		}
			    		if($displayLevel == $j) {
			    			$val = $this->timeSlotsRequired($lastStart,$start);
			    			$lastStart = $start;
			    			$overallNum = $overallNum + $val;

			    			$displayNextDivAt = $i + $num;
			    			$lastNum = $num;
			    			$displayLevel += 1;
			    		}
			    	}
			    	if($i == $displayNextDivAt-1) {

			    		$color = '#BDBDBD';
			    		if($numCourses > 1) {
			    			$color = '#FA5858';
			    		}
			    		$returnString = $returnString . '<div class="specialColumn" style="height:' . $overallNum*25 . 'px;top:' . ($this->timeMap[$overallEarliest]+1)*25 . 'px;background-color: ' . $color . ';">' . $overallCourseText . '</div>';
			    		$displayNextDivAt = -1;
		    			$displayLevel = 0;
		    			$overallCourseText = "";
		    			$overallEarliest = ""; 
		    			$overallNum = 0;
		    			$lastNum = -1;
		    			$lastStart = "";
		    			$lastLength = -1;
		    			$numCourses = 0;
			    	}
			    }
		    	$returnString = $returnString . '</div>';
		    	
   			}
		$this->htmlTimeTable = $returnString;
		return $this->htmlTimeTable;
		}

		/*function displayHtml() {
			return $this->htmlTimeTable;
		}*/
	}




?>