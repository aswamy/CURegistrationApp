
Place the CURegistrationApp  folder into the XAMPP htdocs folder, and run install.php to create the database.

1 - Benjamin Weber  100819375	benweber@cmail.carleton.ca
2 - Denis Dionne    100822373	denisdionne@cmail.carleton.ca
3 - Alok Swamy	    100822701	alokswamy@cmail.carleton.ca

2) We used the CE application to test our program.

3) Our assigned TA is Mr. Kazi

4) Group Contributions:
	Alok: 
		*View1a and View b in html
		*CSS Work
	      	*Electives
			-Table for Electives
			-Extract elective info from the Carleton websites
			-Allow registration in electives in view2a
	        *Database
			-Design of all of the SQL tables

	Denis: 
		*View2a
			-Prerequisite validation
			-Client-side validation
		*View2b
			-PHP scheduling
				-Validation to not schedule courses with no seats
			-Javascript to switch between generated schedules

		*Database class
			-Create database class for querying DB
		*Timetable classes
			-PHP generated HTML time-table

		*Parse CSV to create database tables for the supplied fall/winter data

	BenW:
		*Java GUI
			-Wrote the Java code to create a login dialog
			-Wrote the Java code to create a dialog similar to view1b
		*Database class
			-Added validation function to get connect errors
			-Added the import function which parses and imports SQL
		*Handling of registration
			-SQL code to update # of seats
			-handling of edge cases + validation
				-prevent joining lab w/o course
				-allow for joining courses with no seat limit
		*Install.php
			-Created the import utility in the database class
			-Wrote install.php
		*View1a
			-Initial design of view1a
			-Added SQL connect validation for view1a
	
		
 5) Folder organization
We have have placed the display pages in the root directory of our application. We have organized PHP classes,
and javascript functions into "classes" and "js" folders, respectively.

We use SQL dump files as import files in install.php. All of the sQL files are placed in the SQL folder get
imported by install.php

The Java folder contains all of the java source code and the JAR file that can be executed to run the GUI.
The "functions" folder contain php files that ONLY contain functions
The image folder contains the only image used in this project (CU LOGO)

6) The only thing that needs to be modified are the SQL dump files. If you decide to add more programs you 
simply need to update cu_program_progression.sql to include more programs. 
Updating cu_offered_courses.sql will allow you to add more core courses
Updating cu_offered_electives.sql will allow you to add more elective courses

7) To determine year status, we have a funtion in php that implements the login from this carleton web page:
http://carleton.ca/engineering-design/current-students/undergrad-academic-support/status-vs-standing/
To check whether the student has the year status, we first recieve all the completed courses from the user from view1b
After this, we convert the user's list into a php array, and determine the year status from how many courses the user has completed from each year

JSON Format for prerequities:
{courses: [ [{A, concurrent = true}, {B, concurrent = false}], [{C, concurrent = false}] ]}

To tell if a user can take a course concurrently with another course, our JSON, which contains all the prerequities for all courses, has a can_take_concurrently key that tells if the user can take it together
If the user does not take the prerequites courses concurrently either, we forcefully add it to the "must take list".
If the prerequite is not available, the dependant course cannot be taken.

Program Transfer is already dealt with in the JSON. The elements in the outer array are ANDed and the elements in each inner array are ORed.

As long as the User is in a program that allows a course to be taken, and the prerequisites are met, and the seats are not full, the student can take the course.


8) In order to generate a conflict free timetable, we pass it a list of courses that should be scheduled. 
From these courses, we query the database to get all lecture and lab sections. 
Using this data, we call a recursive function to brute force our way to a solution (stopping at 2).

If we are given two lecture sessions A and B, and 4 lab sessions (A1,A2,B1,B2), 
when the algorithm tries to find a lab section for the A section of the course, only the sections A1 and A2 
will be considered.

However, if we are given once again 2 lecture sections (A,B), but this time we are given 4 lab sections (L1,L2,L3,L4),
the behaviour is different. Since there is no corresponding lab section specifically for A, all 4 lab sections will 
be considered by both class sections A and B.

9) The server creates the conflict-free timetable. We chose this because the server has easy access to the sQL
databases. If the client were to do this, it would need to get the courses from the database by going through the server
because in a distributed network the SQL database may not be accessible in general from the internet. 

The client does input validation to ensure that invalid selections are not made. For Example if a course
requires a concurrant prerequisite they should both get selected at the same time.

We use the server to idenfify what courses can be taken because we store our prerequisites as JSOn
in our database. We use the server to access the database because in general the database would exist behind
a firewall on a local intranet connection and is not accesible to a general client over the internet.
