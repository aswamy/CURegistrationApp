<?php
	echo '<script src="js/validateSelection.js"></script>';
	echo '<style type="text/css">
	#courseSelection td
	{
		width:10%;
	}

	#courseSelection tr
	{
		height:25px;
	}

	#courseSelection {
		border:2px solid;
		border-radius: 15px;
		height:400px;
		overflow-y:scroll;
	}

	#tableHeader
	{
		width:75%;
		display:block;
		margin-left:auto;
		margin-right:auto;
	}
	#tableHeader td
	{
		width:10%;
		height:25px;
	}
	</style>';
	session_start();
	$query  = explode('&', $_SERVER['QUERY_STRING']);
	//$degree = $_SESSION['degree'];
	$degree = "SE";
	$year_status = 1;
	//$year_status = $_SESSION['year_status'];
	$params = array();

	$finishedCourseList = array();

	foreach( $query as $param )
	{
 	 list($name, $value) = explode('=', $param);
 	 $params[urldecode($name)][] = urldecode($value);
	}
	$courselist = "";
	while (list($key, $val) = each($params)) {
		if (preg_match("/[A-Z]{4}[0-9]{4}/", "$key", $matches)) {
  			$courselist = $courselist . "'" . $key . "',";
  			array_push($finishedCourseList, $key);
		}		
    } 
    $inString = rtrim($courselist, ",");
    $inString = "(".$inString.")";
    //echo $inString;i


	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}

	$courses_query = "SELECT p.*, o.course_prerequisite FROM cu_program_progression p LEFT JOIN cu_offered_courses o ON p.course_name = o.course_name WHERE p.degree_name='$degree' AND o.course_year_status = $year_status ORDER BY p.course_year, p.course_semester";
	$courses_query_rs = $conn->query($courses_query);
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);
	$course_prereq_json = "{";

	
		
		

	foreach($courses_array as $course) {
		$course_name = $course['course_name'];

		if ($course_name != 'S_ELECTIVE' && $course_name != 'E_ELECTIVE' && $course_name != 'C_ELECTIVE') {
			//echo "<div class=courseElement id=$course[course_name] onmouseover=highlightPrerequisites(this) onmouseout=restoreCourseElements()>$course[course_name] in $course[course_semester] in year $course[course_year]</div>";
			$course_prereq_json .= "\"$course[course_name]\" : $course[course_prerequisite],";
		} else {
			//echo "<div class=courseElement id=$course[course_name]>$course[course_name] in $course[course_semester] in year $course[course_year]</div>";
		}
	}

	$course_prereq_json = rtrim($course_prereq_json, ',');
	$course_prereq_json .= "}";


echo "<script>" . "setJsonPrereqs(" . $course_prereq_json . "); </script>";
echo "<script>" . "setFinishedCourses(" . json_encode($finishedCourseList) . "); </script>";

	$validCourses = array();


	//parse json
	$jsonArr = json_decode($course_prereq_json);
	if(is_null($jsonArr)){
		echo "null";
	}
	foreach($jsonArr as $obj => $val){


		//iterate through JSON object
		$coursesArray = $val->{"courses"};
		if(!in_array($obj, $finishedCourseList)){


			//if there is nothing, then there are no prereqs
			if(empty($coursesArray)){
  				array_push($validCourses, $obj);
  			//else there are prereqs
			}else{
				$canTake = TRUE;

				//AND and OR course prereqs
				foreach ($coursesArray as $name) {
					//if this var is an array, we OR every item in that array
	  				if(is_array($name)){ 
	  					$orPrereq = FALSE;
	  					foreach($name as $trueName){
	  						if(in_array($trueName->name, $finishedCourseList) or $trueName->concurrent == 'true'){
	  							$orPrereq = TRUE;
	  							break;
	  						}
	  					}
	  					if(!$orPrereq){
	  						$canTake = FALSE;
	  					}

	  				//if this var is not an array, we AND everything together
	  				} else{
	  					if (!in_array($name->name, $finishedCourseList)) {
	  						$canTake = FALSE;
						}
	  				}
	  				
	  			}
	  			if($canTake){
	  				
	  				//make sure course isn't already there	
	  				if(!in_array($obj, $validCourses)){
	  					array_push($validCourses, $obj);
	  				}
	  			}
			}
		}
		

	}

	$formattedValidCourses = "(";
	foreach ($validCourses as $val) {
		//echo $val . "</br>";
		$formattedValidCourses = $formattedValidCourses . "'" . $val . "',";
	}
	$formattedValidCourses = rtrim($formattedValidCourses, ",") . ")";
	//echo $formattedValidCourses;



	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}
	$courses_query = "SELECT r.*, o.course_has_lab FROM cu_running_courses r lEFT JOIN cu_offered_courses o ON r.course_name = o.course_name WHERE r.course_name IN $formattedValidCourses;"; 
	//echo "\r\n" . $courses_query . "\r\n";
	$courses_query_rs = $conn->query($courses_query);
	if(!$courses_query_rs){
		echo "invalid query";
	}
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);


	$courseHasLabArr = array();
	echo "<form method='GET' action='page4.php'>";
	echo '<div id="timeTable"></div>';
	//echo '<input id="conflict" type="hidden" value="none"/>';
	echo '<div id="TableHeader"><table><tr><td></td><td>Course</td><td>Section</td><td>Type</td><td>Term</td><td>Seats Left</td><td>Days</td><td>Start time</td><td>End Time</td></tr></table>';
	echo '<div id="courseSelection"><table>';
	//echo '<tr><td></td><td>Course</td><td>Section</td><td>Type</td><td>Term</td><td>Seats Left</td><td>Days</td><td>Start time</td><td>End Time</td></tr>';
	foreach($courses_array as $course){
		$name = $course['course_name'];
		$courseHasLabArr[$name] = $course['course_has_lab'];
		echo "<tr>";
		echo "<td>" . "<input type=checkbox name=" . $course['course_name'] . "-" . $course['course_section'] . " value='on' onclick=" . '"' . "onChangeCheckBox (this)" . '"' .">";
		echo "<td>" . $course['course_name'] . "</td>";
		echo "<td>" . $course['course_section'] . "</td>";
		echo "<td>" . $course['class_type'] . "</td>";
		echo "<td>" . $course['course_semester'] . "</td>";
		echo "<td>" . $course['seats_left'] . "</td>";
		echo "<td>" . $course['class_days'] . "</td>";
		echo "<td>" . $course['class_start'] . "</td>";
		echo "<td>" . $course['class_end'] . "</td>";
		echo "</tr>";
	} 
	echo '</table></div></div>';

	echo "</br>";
	echo '<input id="page3Submit" type="submit" value="Submit">';
	echo "</form>";
	echo "<script>" . "setHasLabArr(" . json_encode($courseHasLabArr) . "); 
			check();
		 </script>";
?>