<?php
require_once 'classes/Database.php';

$db = new Database("");
$db->create('sysc4504');
//if($db->getError()) {
//	http_response_code(503); // Service is unavailable
//	echo "<p>The database service is currently unavailable. Please try again</p>";
//	exit();
//}

$db2 = new Database("sysc4504");

$dirContents = scandir('sql'); //Get an Array of the contents of the SQL folder.

//We only want to process files that match *.sql
foreach($dirContents as $toProcess) {
	if(substr($toProcess,-4,4) == ".sql") {
		$db2->import('./sql/'.$toProcess);
	}
	//Else do nothing
}




?>