
var selectedCourses = [];
var jsonPrereqs = [];
var finishedCourses = [];
var hasLabArr = [];

function onChangeCheckBox(checkBox) {
	
	if(checkBox.checked){
		selectedCourses[selectedCourses.length] = checkBox.name;
	} else{
		selectedCourses.splice(selectedCourses.indexOf(checkBox.name), 1);
	}

	var className = checkBox.name.substring(0,8);
	validateCourseSelection(className);
	validateLabSelected(className);

}

function setJsonPrereqs(jsonString){
	jsonPrereqs = jsonString;
}

function setFinishedCourses(courses) {
	finishedCourses = courses;
}

function setHasLabArr(courses) {
	hasLabArr = courses;
}

function validateCourseSelection(course) {
	for(var className in jsonPrereqs){
		if(typeof(jsonPrereqs[className]) == "object" && isChecked(className)) {
			for(var nextLevel in jsonPrereqs[className]) {
				var nextElem1 = jsonPrereqs[className][nextLevel];
				var accept = nextElem1.length == 0;
				if(typeof(nextElem1) == "object") {
					for(var deeper in nextElem1) {
						var nextElem2 = nextElem1[deeper];
						var innerAccept = false;
						for(object in nextElem1[deeper]) {
							var c = nextElem1[deeper][object];
							var name = c.name;
							var concurrent = c.concurrent;
							if(concurrent) {
								if(isConcurrentSelected(name) || courseCompleted(name)) {
									innerAccept = true;
									break;	
								} 
							}
						}
						accept = innerAccept;
						if(!innerAccept){
							break;
						}
					}
				}
			}
			if(!accept) {
				console.log("missing prereq for " + className);
				disableSubmit();
			} else{
				enableSubmit();
			}
		}

	}

}

function validateLabSelected(className) {
	var testArray = [];
	var hasCourse = false;
	var hasLab = false;

	if(hasLabArr[className] = 0) {
		return;
	}

	for(var a in selectedCourses) {
		var index = selectedCourses[a].indexOf(className);
		if(index > -1) {
			var section = selectedCourses[a].substring(9,selectedCourses[a].length);
			
			var isnum = /\d$/.test(section);
			if(!isnum) { 
				hasCourse = true;
			} else {
				hasLab = true;
			}
		}
	}

	/*for(var a in selectedCourses) {
		var courseName = selectedCourses[a].substring(0,8);

		if(hasLabArr[courseName] == 1) { //if there is a lab
			var section = selectedCourses[a].substring(9,selectedCourses[a].length);
			if(isNaN(section[section.length-1]) { //not a lab (add to array)
				testArray[testArray.length] = courseName;
			}
		}
	}

	for(var a in selectedCourses) {
		var courseName = selectedCourses[a].substring(0,8);

		if(hasLabArr[courseName] == 1) {
			var section = selectedCourses[a].substring(9,selectedCourses[a].length);
			if(!isNaN(section[section.length-1]) { //is a lab (remove from array if present, add otherwise) any remaining entries means either lab or course wasn't selected
												//whereas you need both
				$index = selectedCourses.indexOf(courseName);
				if(index == -1) {
					testArray[testArray.length] = courseName;
				} else {
					testArray.splice(index,1);
				}
			}
		}
	}
	*/
	if(!hasCourse || !hasLab) {
		disableSubmit();
	}
}

function isConcurrentSelected(course) {
	return isChecked(course);
}

function courseCompleted(course) {
	for(var a in finishedCourses) {
		if(finishedCourses[a] == course) {
			return true;
		}
	}
	return false;
}

function isChecked(course) {
	for(var a in selectedCourses) {
		var className = selectedCourses[a].substring(0,8);
		if(className == course) {
			return true;
		}
	}
	return false;
}

function disableSubmit() {
	document.getElementById("page3Submit").disabled = true;
}

function enableSubmit() {
	document.getElementById("page3Submit").disabled = false;
}