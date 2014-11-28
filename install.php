<head>
	<link rel="stylesheet" type="text/css" href="css/common.css" />
</head>
<body style="background-color: gray">
	<?php include 'header.html';?>
</body>
<?php
require_once 'classes/Database.php';

$db = new Database("");
if($db->getConnError()) {
	http_response_code(503); // Service is unavailable
	echo "<h1>The database service is currently unavailable. Please try again</h1>";
	exit();
}

$db->create('sysc4504'); //Create the sysc4504 database

$db2 = new Database("sysc4504"); //Connect to the sysc4504 database

$dirContents = scandir('sql'); //Get an Array of the contents of the SQL folder.

//We only want to process files that match *.sql
foreach($dirContents as $toProcess) {
	if(substr($toProcess,-4,4) == ".sql") {
		$db2->import('./sql/'.$toProcess);
	}
	//Else do nothing
}

echo "<h1>Done importing sysc4504 database</h1>";


?>
