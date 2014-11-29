<?php
	require_once 'classes/Database.php';

	session_start();
	$finishedCourseList = array();
	$validCourses = array();
	foreach($_POST as $key => $val) {
		if(preg_match("/[A-Z]{4}[0-9]{4}/", "$key", $matches) OR strpos($key, "_ELECT") !== FALSE) {
  			array_push($finishedCourseList, $key);
		}
	}

	$finishedCourseString = "";
	foreach($finishedCourseList as $course) {
		$finishedCourseString .= $course . ",";
	}
	$finishedCourseString = rtrim($finishedCourseString, ",");
	$_SESSION['finishedCourses'] = $finishedCourseString;
	$degree = $_SESSION['degree'];
	$year_status = $_SESSION['registering_year'];
	$term = $_SESSION['registering_semester'];
	$params = array();



	$db = new Database("sysc4504");

	$courses_array = $db->getPrereqs($degree, $year_status, $term);
	$electives_map = $db->getDegreeElectives($degree);
	$course_prereq_json = "{";
		
		
	$courseHasLabArr = array();
	$coursesInYear = array();
	$electivesInYear = array();

	foreach($courses_array as $course) {
		$courseName = $course['course_name'];
		$courseHasLabArr[$courseName] = $course['course_has_lab'];
		if (strpos($courseName, "ELECT") === FALSE) {
			$course_prereq_json .= "\"$course[course_name]\" : $course[course_prerequisite],";
			array_push($coursesInYear, $course['course_name']);
		} else {
			if (!in_array($courseName, $finishedCourseList)) {
				array_push($electivesInYear, $courseName);
			}
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
	  						if(in_array($trueName->name, $finishedCourseList) or ($trueName->concurrent == 'true' and in_array($trueName->name, $coursesInYear))) {
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
?>

	<head>
		<script src="js/view2a.js"></script>
		<link rel="stylesheet" type="text/css" href="css/common.css" />
	</head>
	<body style="background-color: gray">
	<?php include 'header.html';
		echo "<script>" . "setJsonPrereqs(" . $course_prereq_json . "); </script>";
		echo "<script>" . "setFinishedCourses(" . json_encode($finishedCourseList) . "); </script>";


	?>
		<div class="mainMessage">
			* Select which courses to take this semester:
		</div>
		<form method="POST" action='view2b.php' onsubmit='return validateForm()'>

			<div id="courseSelection">
				<?php
					echo "<div class='subMessage'>Courses you can take in <strong>year $_SESSION[registering_year], $_SESSION[registering_semester]</strong> based on completed courses:</div>";
					foreach($validCourses as $course) {
						echo "<input id='$course' type='checkbox' name='$course' value=on onclick='onChangeCheckBox (this)'/>$course</br>";
					}
					echo "<br>";
					foreach($electivesInYear as $elective) {
						echo "<div style='padding-bottom:5px'>$elective : <select class='inputField' name='$elective'>";
						echo "<option value='---'>---</option>";
						foreach($electives_map as $elect) {
							if (strpos($elective, $elect['elective_type']) !== FALSE) {
								echo "<option value='$elect[course_name]'>$elect[course_name]</option>";
							}
						}
						echo '</select></div>';
					}
				?>
				<br >
				<input id="view2aSubmit" type="submit" value="Submit"/>
			</div>

		</form>
	</body>
</html>
