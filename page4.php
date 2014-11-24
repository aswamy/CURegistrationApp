<?php
require_once 'classes/Database.php';
$db = new Database("sysc4504");
$postData = $_POST['courses'];
//The courses are currently sent URL encoded with brackets at the start and end.
//We should send this in a different format so the code below is quick and dirty
$postData = trim($postData,"(");
$postData = trim($postData,")");
echo "<br/>";
$subject = explode(",",$postData);
print_r($subject);
echo "<br/>";

foreach($subject as $sub){
	$temp = explode("-",$sub);
	$course = $temp[0];
	$section = $temp[1];
	echo $course;
	echo "<br/>";
	$sql = "UPDATE `cu_running_courses`
			SET `seats_left` = `seats_left` -1
			WHERE `seats_left` > 0 AND `course_name`='$course' AND `course_section`='$section'
			;";
			
	//TODO fix bug with execute not handling update queries.
	$db->execute($sql);
	
}

?>