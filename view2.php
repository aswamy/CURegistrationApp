<?php


  require_once 'classes/TimeTable.php';
  require_once 'classes/Database.php';

	
	session_start();

	$finishedCourseList = array();
	$coursesToTake = array();
	foreach($_POST as $key => $val) {
		if(preg_match("/[A-Z]{4}[0-9]{4}/", "$key", $matches)) {
  			array_push($coursesToTake, $key);
		}
	}

	$degree = $_SESSION['degree'];
	$term = $_SESSION['registering_semester'];
	$year_status = $_SESSION['registering_year'];

	//get database object
	$db = new Database("sysc4504");
	$formattedCoursesToTake = "(";
	
	foreach($coursesToTake as $course) {
		$formattedCoursesToTake = $formattedCoursesToTake . "'" . $course . "',";		
	}
	$formattedCoursesToTake = rtrim($formattedCoursesToTake, ",") . ")";

	$courses_array = $db->getNameSectionPairs($formattedCoursesToTake, $term);
	$t1 = pickCourses($coursesToTake[0], $coursesToTake, array(), $courses_array);
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

	$selectedIndex = 0;

	echo '<script src="js/page3.js"></script>';
	echo "<html>";
	echo "	<body>";
	echo "		<form method='POST' action='page4.php' style='overflow:auto;' >";
	echo "			<div id='global' style='overflow:auto;'>";
	echo "				<div id='timeTable' style='display:block;'>";
	echo "				</div>";
	echo "				<div id='solutions'>";
	
	$solutionStr = "";
	$sizeSolutions = sizeof($updatedSolutions);
	for($i=0; $i != $sizeSolutions; $i++) {
		$solutionStr = "";
		foreach($updatedSolutions[$i] as $course) {
			$solutionStr = $solutionStr . $course . ",";
		}
		$solutionStr = rtrim($solutionStr, ",");
		echo "					<div style='display:none;'>" .  $solutionStr . "</div>";
		
	}
	echo "				</div>";
	echo "			</div>";
	echo "</br>";
	echo "			<div id='courseList'>";
	echo "				<p>Courses in schedule: $solutionStr</p>
					</div></br>";
	echo "			<button type='button' onclick='myAjaxFunc(-1)'>previous</button>";
	echo "			<button type='button' onclick='myAjaxFunc(1)'>next</button>";
	echo " 			<input type='submit' value='Register'/>";
	echo "		</form>";
	echo "	</body>";
	echo "</html>";
	echo "<script>myAjaxFunc(0);</script>";


?>


<?php

	function pickCourses($course, $leftToTake, $coursesPicked) {
		$sol = array();
		return pickCourse($course, $leftToTake, $coursesPicked, "", $sol);
	}

	function pickCourse($course, $leftToTake, $coursesPicked, $lab, $solutions) {
		
		global $courses_array;
		$coursesArr = $courses_array;
		//end conditions
		if($course == "" && empty($leftToTake) && !empty($coursesPicked)) {
			
			$timeTable = new TimeTable($coursesPicked);
			$timeTable->generateTimeTable();

			//make sure there isn't a conflict in the timetable, then add solution to list
			if($timeTable->hasConflict() === FALSE) {
				if(is_null($solutions)) {
					$solutions = array();
				}
				array_push($solutions, $timeTable->courseList);
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
					$solutions = pickCourse("", array(), $picked, "", $solutions);
					if(sizeof($solutions) > 9) {
								return $solutions;
								//break;
							}
				} else if(!$empty){
					//not last course, keep looking for solutions
					$solutions = pickCourse($left[0], $left, $picked, "", $solutions);
					if(sizeof($solutions) > 9) {
								return $solutions;
								//break;
							}
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
						$solutions = pickCourse($course, $leftToTake, $picked, substr($section, 0, 1), $solutions);
						if(sizeof($solutions) > 9) {
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
							$solutions = pickCourse("", array(), $picked, "", $solutions);
							if(sizeof($solutions) > 9) {
								return $solutions;
								//break;
							}
							continue;
						} else{
							//try next course
							$solutions = pickCourse($left[0], $left, $picked, "", $solutions); 
							if(sizeof($solutions) > 9) {
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
			} 
			// nothing left to do... end
			return $solutions;
		}

	}

?>