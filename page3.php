<?php


  require_once 'timeTableClass.php';

	
	session_start();





	$selectedCourses = array();

	$courselist = "";
	$finishedCourseList = array();
	$validCourses = array();
	foreach($_POST as $key => $val) {
		if(preg_match("/[A-Z]{4}[0-9]{4}/", "$key", $matches)) {
			$courselist = $courselist . "'" . $key . "',";
  			array_push($finishedCourseList, $key);
		}
	}

	//$query  = explode('&', $_SERVER['QUERY_STRING']);
	$degree = $_SESSION['degree'];
	//$degree = "SE";
	//$year_status = 1;
	$year_status = $_SESSION['years_completed'] + 1;
	$params = array();

    $inString = rtrim($courselist, ",");
    $inString = "(".$inString.")";


	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}

	$courses_query = "SELECT p.*, o.course_prerequisite, o.course_has_lab FROM cu_program_progression p LEFT JOIN cu_offered_courses o ON p.course_name = o.course_name WHERE p.degree_name='$degree' AND p.course_year = $year_status AND p.course_semester = 'fall' ORDER BY p.course_year, p.course_semester";
	//echo $courses_query . "</br>";
	$courses_query_rs = $conn->query($courses_query);
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);
	$course_prereq_json = "{";

	
		
		
	$courseHasLabArr = array();
	foreach($courses_array as $course) {
		$courseName = $course['course_name'];
		$courseHasLabArr[$courseName] = $course['course_has_lab'];
		if (!strpos($courseName, "ELECT")) {
			$course_prereq_json .= "\"$course[course_name]\" : $course[course_prerequisite],";
		}
	}

	$course_prereq_json = rtrim($course_prereq_json, ',');
	$course_prereq_json .= "}";


	//get list of valid courses based on finished courses
	$jsonArr = json_decode($course_prereq_json);

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
		$formattedValidCourses = $formattedValidCourses . "'" . $val . "',";
	}
	$formattedValidCourses = rtrim($formattedValidCourses, ",") . ")";


	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}
	$courses_query = "SELECT course_name FROM cu_program_progression WHERE degree_name = '$degree' AND course_year = $year_status AND course_name IN $formattedValidCourses AND course_semester = 'fall'";
	//"SELECT r.*, o.course_has_lab FROM cu_running_courses r lEFT JOIN cu_offered_courses o ON r.course_name = o.course_name WHERE r.course_name IN $formattedValidCourses;"; 
	$courses_query_rs = $conn->query($courses_query);
	//echo $courses_query . "</br>";
	if(!$courses_query_rs){
		echo "invalid query";
	}
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);
	$coursesToTake = array();
	$formattedCoursesToTake = "(";
	foreach($courses_array as $course) {
		if(!strpos($course['course_name'], "ELECT")) {
			$formattedCoursesToTake = $formattedCoursesToTake . "'" . $course['course_name'] . "',";
		}
		array_push($coursesToTake, $course['course_name']);
		
	}
	$formattedCoursesToTake = rtrim($formattedCoursesToTake, ",") . ")";
	

	$query = "SELECT course_name, course_section FROM cu_running_courses WHERE course_name IN $formattedCoursesToTake AND (seats_left > 0 OR seats_left = -1) AND course_semester = 'fall'";
	$courses_query_rs = $conn->query($query);
	if(!$courses_query_rs){
		echo "invalid query";
	}
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);
	//echo $query . "</br>";
	//echo "picking courses now... sizeof... " . sizeof($coursesToTake) . 'size of queryarr: ' . sizeof($courses_array) .  "</br>";
	$t1 = pickCourses($coursesToTake[0], $coursesToTake, array(), $courses_array);
	//echo "done...</br>";
	$maxLength = 0;
	foreach($t1 as $solution) {
		if(sizeof($solution) > $maxLength) {
			$maxLength = sizeof($solution);
		}
	}

	//only get the largest solutions
	$updatedSolutions = array();
	foreach($t1 as $solution) {
		if(sizeof($solution) == $maxLength) {
			array_push($updatedSolutions, $solution);
		}
	}
	//foreach($updatedSolutions as $solution){
	//	foreach($solution as $course) {
	//		echo '"'.$course.'",';
	//	}
	//	echo "</br>";
	//}
	$selectedIndex = 0;

	echo '<script src="js/page3.js"></script>';
	echo "<html>";
	echo "	<body>";
	echo "		<form>";
	echo "			<div id='global'>";
	echo "				<div id='timeTable' style='display:block;'>";
	//$timetable = new TimeTable($updatedSolutions[$selectedIndex]);
	//echo $timetable->displayHtml();
	echo "				</div>";
	echo "				<div id='solutions'>";
	$sizeSolutions = sizeof($updatedSolutions);
	for($i=0; $i != $sizeSolutions; $i++) {
		$solutionStr = "";

		foreach($updatedSolutions[$i] as $course) {
			$solutionStr = $solutionStr . $course . ",";
		}
		echo "					<div style='display:none;'>" .  $solutionStr . "</div>";
		
	}
	echo "				</div>";
	echo "				<button type='button' onclick='myAjaxFunc(-1)'>previous</button>";
	echo "				<button type='button' onclick='myAjaxFunc(1)'>next</button>";
	echo "			</div>";
	echo "		</form>";
	echo "	</body>";
	echo "</html>";
	echo "<script>myAjaxFunc(0);</script>";


?>

<!--<html>

	<body>

		<form>
			<div id="global-area">
				
			</div>	
		</form>

	</body>

</html>-->

<?php

	function pickCourses($course, $leftToTake, $coursesPicked, $coursesArr) {
		$sol = array();
		return pickCourse($course, $leftToTake, $coursesPicked, $coursesArr, "", 0, $sol);
	}

	function pickCourse($course, $leftToTake, $coursesPicked, $coursesArr, $lab, $amountOfCourses, $solutions) {
		//echo "picking course: " . $course . ' lab: ' . $lab . ' size of solutions: ' . sizeof($solutions) . "</br>";
		
		//end conditions
		if($course == "" && empty($leftToTake) && !empty($coursesPicked)) {
			global $jsonArr; 
			global $finishedCourseList;
			$hasReq = hasAllPrereqs($coursesPicked, $jsonArr, $finishedCourseList);
			
			//check to make sure all picked courses meet there prerequisites
			if($hasReq){ 
				$timeTable = new TimeTable($coursesPicked);
				$timeTable->generateTimeTable();

				//make sure there isn't a conflict in the timetable, then add solution to list
				if($timeTable->hasConflict() === FALSE) {
					if(is_null($solutions)) {
						$solutions = array();
					}
					array_push($solutions, $timeTable->courseList);
				}
			} 
			return $solutions;
		} else {
			//build array of options for the current course/lab
			$newArr = array();
			foreach($coursesArr as $precise) {
				$courseName = $precise['course_name'];
				$courseSection = $precise['course_section'];
				if($course == $courseName) {
					preg_match('/\d/', $courseSection,$match);
					$checkIsLab = $match;
					if(!$checkIsLab && $lab == "") {
						array_push($newArr, $precise);
					} else if($checkIsLab && $lab !== "") {
						array_push($newArr, $precise);
					}
				}
			}

			//if the class section has a lab section with the same Letter, than that lab letter needs to be selected
			//figure out if lab section with the same letter as course section exists
			$hasLetterSection = FALSE;
			if($lab != "") {
				$courseSect = $lab;
				foreach($newArr as $checkLabSect) {
					if(substr($checkLabSect['course_section'], 0, 1) == $lab) {
						$hasLetterSection = TRUE;
						break;
					}
				}
			 }


			$courseReached = FALSE;
			global $courseHasLabArr;
			$hasLab = $courseHasLabArr[$course];

			
			if($course != "" && sizeof($newArr) == 0) {
				$picked = $coursesPicked;
				$left = $leftToTake;
				$key = array_search($course, $left);
				array_splice($left, $key, 1);
				$empty = empty($left);
				if($empty && !empty($picked)) {
					//last course, so finish
					$solutions = pickCourse("", array(), $picked, $coursesArr, "", $amountOfCourses, $solutions);
				} else if(!$empty){
					//not last course, keep looking for solutions
					$solutions = pickCourse($left[0], $left, $picked, $coursesArr, "", $amountOfCourses, $solutions);
				}
			}

			//iterate over list, selecting a course, then doing it again for the next
			foreach ($newArr as $courses) {
				$courseName = $courses['course_name'];
				$section = $courses['course_section'];
				
				//shouldn't need anymore
				preg_match('/\d/', $section,$match);
				$isLab = $match;
				if($lab && !$isLab) {
					continue;
				}
				
				//shouldn't need this if statment anymore
				if($courseName == $course) {
					$courseReached = TRUE;
					$picked = $coursesPicked;
					$left = $leftToTake;
					array_push($picked,$courseName."-".$section);
					if(!$lab && $hasLab) {
						//next course chosen should be the lab for the current course selected
						$solutions = pickCourse($course, $leftToTake, $picked, $coursesArr, substr($section, 0, 1), $amountOfCourses+1, $solutions);
						if(sizeof($solutions) > 5) {
								return $solutions;
								//break;
							}
						continue;
					}
					else{
						if($isLab) {
							if(!(($hasLetterSection && $lab == substr($section,0,1)) || !$hasLetterSection)){
								//ex: SYSC 4504 A is selected, SYSC 4504 B1 should not be a lab selected (wrong section)
								//therefore continue if this is the case
								continue;
							}
						}
						//remove the current course from the list of courses to take (don't want to select it again)
						$key = array_search($course, $left);
						array_splice($left, $key, 1);
						if(empty($left)) {
							//this was the last course, therefore see if the solution works
							$solutions = pickCourse("", array(), $picked, $coursesArr, "", $amountOfCourses, $solutions);
							if(sizeof($solutions) > 5) {
								return $solutions;
								//break;
							}
							continue;
						} else{
							//try next course
							$solutions = pickCourse($left[0], $left, $picked, $coursesArr, "", $amountOfCourses, $solutions); 
							if(sizeof($solutions) > 5) {
								return $solutions;
								//break;
							}
							continue;
						}
					}
					//might not need anymore
				} else{
					if($courseReached || $course == "") {
						break;
					}
				}
			} //out of for loop, find solutions that don't include the current course (could be useful if the course has a conflict with another course)
			/*$picked = $coursesPicked;
			$left = $leftToTake;
			$key = array_search($course, $left);
			array_splice($left, $key, 1);
			$empty = empty($left);
			if($course != "") {
				if($empty && !empty($picked)) {
					//last course, so finish
					$solutions = pickCourse("", array(), $picked, $coursesArr, "", $amountOfCourses, $solutions);
				} else if(!$empty){
					//not last course, keep looking for solutions
					$solutions = pickCourse($left[0], $left, $picked, $coursesArr, "", $amountOfCourses, $solutions);
				}
			}*/
			// nothing left to do... end
			return $solutions;
		}

	}
	


 



	function hasAllPrereqs($courses, $jsonPrereqs, $finished) {
		//$jsonArr = json_decode($prereqs);
		global $course_prereq_json;
		//echo "$course_prereq_json</br>";
		$chosen = array();
		foreach($courses as $aaa) {
			$name = substr($aaa, 0,8);
			if(!in_array($name, $chosen)){
				array_push($chosen, $name);
			}
		}
		foreach($jsonPrereqs as $obj => $val){
			$coursesArray = $val->{"courses"};
			//only check courses that are chosen
			if(in_array($obj, $chosen)){
				//if there is nothing, then there are no prereqs
				if(!empty($coursesArray)){
					$canTake = TRUE;
					foreach ($coursesArray as $name) {
						//if this var is an array, we OR every item in that array
		  				if(is_array($name)){ 
		  					$orPrereq = FALSE;
		  					foreach($name as $trueName){
		  						if(in_array($trueName->name, $finished) or $trueName->concurrent){
		  							if(!$trueName->concurrent) {
		  								$orPrereq = TRUE;
		  								break;
		  							}
		  							else if(hasConcurrentCourse($trueName->name,$chosen) or $obj == "SYSC4937"){ //added to allow 4th year project to be selected in fall
		  								$orPrereq = TRUE;
		  								break;
		  							}
								}
		  					}
		  					if(!$orPrereq){
		  						//echo "false </br>";
		  						$canTake = FALSE;
		  						break;
		  					}
						//if this var is not an array, we AND everything together
		  				} else{
		  					if (!in_array($name->name, $finished)) {
		  						$canTake = FALSE;
							}
		  				}
		  			}
		  			//can't take course, so retun false
		  			if(!$canTake){
		  				//echo "total false </br>";
		  				return FALSE;
		  			}
				}
			}
		}
		return TRUE; //we reached the end, meaning they weren't missing any prereqs
	}

	function hasConcurrentCourse($className, $classList) {
		return in_array($className, $classList);
	}

	
    

?>