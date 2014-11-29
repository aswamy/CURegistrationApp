<head>
	<link rel="stylesheet" type="text/css" href="css/common.css" />
</head>
<body style="background-color: gray">
	<?php include 'header.html';?>
</body>
<?php
require_once 'classes/Database.php';
$db = new Database("sysc4504");
$postData = $_POST['courses'];
//The courses are currently sent URL encoded with brackets at the start and end.
//We should send this in a different format so the code below is quick and dirty
$postData = trim($postData,"(");
$postData = trim($postData,")");
$subject = explode(",",$postData);
$lectures = array();

foreach($subject as $sub){
	$temp = explode("-",$sub);
	$course = $temp[0];
	$section = $temp[1];
	
	//We cannot allow students to register in a lab without also registering in the corresponding section
	//we also want to support courses that have no limit on the number of students (these have -1 seats)
	$sql = "SELECT `class_type`,`seats_left`
		  FROM `cu_running_courses`
		  WHERE `course_name`='$course' AND `course_section`='$section';";
	$results = $db->execute($sql);
	
	if($results[0]['class_type'] == "LEC") {
		array_push($lectures,$course);
	}elseif(!in_array($course,$lectures)) {
		echo "You cannot register in $course $section because you are not registering in the corresponding lecture";
		continue; // If we get a lab that is not being registered in along with a lecture, ignore it
	}

	if($results[0]['seats_left'] == -1){
		echo "You have successfully registered in $course $section <br/>";
		continue;
	}

	$sql = "UPDATE `cu_running_courses`
			SET `seats_left` = `seats_left` -1
			WHERE `seats_left` > 0 AND `course_name`='$course' AND `course_section`='$section'
			;";
		
	$db->update($sql);
	if(mysqli_affected_rows($db->connection) >0){
		echo "You have successfully registered in $course $section <br/>";
	} else {
		echo "You couldn't register in $course $section because the course was full <br/>";
		array_pop($lectures); //Remove the course from the lectures array so that we don't let you register in the lab
	}
}

?>
