<?php
	echo '<script src="js/validateSelection.js"></script>';

	$query  = explode('&', $_SERVER['QUERY_STRING']);
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
    //echo $inString;


	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}

	$courses_query = "SELECT p.*, o.course_prerequisite FROM cu_program_progression p LEFT JOIN cu_offered_courses o ON p.course_name = o.course_name WHERE p.degree_name='$_GET[degree]' ORDER BY p.course_year, p.course_semester";
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
	$courses_query = "SELECT * FROM cu_running_courses WHERE course_name IN $formattedValidCourses;"; 
	//echo "\r\n" . $courses_query . "\r\n";
	$courses_query_rs = $conn->query($courses_query);
	if(!$courses_query_rs){
		echo "invalid query";
	}
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);

	echo "<table>";
	foreach($courses_array as $course){
		echo "<tr>";
		echo "<td>" . "<input type=checkbox name=" . $course['course_name'] . "-" . $course['course_section'] . " value=" . $course['course_name'] . "-" . $course['course_section'] .  " onclick=" . '"' . "onChangeCheckBox (this)" . '"' .">";
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
	echo "</table>";
	

?>