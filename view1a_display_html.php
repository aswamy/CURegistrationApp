<head>
<link rel="stylesheet" type="text/css" href="css/Splash.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
</head>
<body style="background-color: gray">
<?php include 'header.html';?>
<div class='mainContainer'>
	<form class='inputForm' method='GET' action='view1b.php'>
		<table class='inputsContainer'>

		<!-- Student number to tell who is logging in -->
		<tr>
		<td>Student #:</td>
		<td>
			<input class="inputField" name="studentnum" type="text"></input>
		</td>
		</tr>

		<!-- Which degree is the user in -->
		<tr>
		<td>Degree:</td>
		<td>
			<select class="inputField" id="degree" name="degree">
			<?php
				foreach($degree_array as $degree) {
					echo "<option value='$degree[degree_name]'>$degree[degree_name]</option>";
				}
			?>
			</select>
		</td>
		</tr>

		<!-- A radio button to see if the student is on track -->
		<tr>
		<td>On Track:</td>
		<td>
			<input class="inputField" checked type="radio" name="ontrack" id="ontrack" value="true">Yes</input><input type="radio" name="ontrack" value="false">No</input>
		</td>
		</tr>
		<!-- what year he/she is in-->
		<tr>
		<td>Registering Year:</td>
		<td>
			<select class="inputField" name="registeringyear" id="registeringyear"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>
		</td>
		</tr>
		<!-- what semester he/she is in-->
		<tr>
		<td>Registering Semester:</td>
		<td>
			<input class="inputField" checked type="radio" name="registeringsemester" id="registeringsemester" value="fall">Fall</input><input type="radio" name="registeringsemester" value="winter">Winter</input>
		</td>
		</tr>		
		</table>
		<input type="submit" value="Done"/>
	</form>
</div>
</body>