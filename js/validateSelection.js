
var selectedCourses = [];
var jsonPrereqs = [];
var finishedCourses = [];
var hasLabArr = [];
var missingPrereqs = []; //array of classes missing prereqs (helps user know which classes are causing problems)
var duplicatesExist = false;
var missingOther = []; //array of classes that are missing their lab/class component

function onChangeCheckBox(checkBox) {
	
	if(checkBox.checked){
		selectedCourses[selectedCourses.length] = checkBox.name;
	} else{
		selectedCourses.splice(selectedCourses.indexOf(checkBox.name), 1);
	}

	var className = checkBox.name.substring(0,8);
	validateCourseSelection(className);
	validateLabSelected(className);
	validateAllSelectionsForDuplicates();
	setRequirementsMsg();

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

function validateAllSelectionsForDuplicates() {
	var duplicate = false;
	for(var b in selectedCourses) {
		var courseFull = selectedCourses[b];
		var course = courseFull.substring(0,8);
		var section = courseFull.substring(9,courseFull.length);
		var isLab = /\d$/.test(section);
		for(var a in selectedCourses) {
			var current = selectedCourses[a];
			var currentName = current.substring(0,8);
			var curSection = current.substring(9,current.length);
			var curIsLab = /\d$/.test(curSection);
			if(course == currentName && isLab == curIsLab && section != curSection) {
				//we have a duplicate course
				duplicate = true;
				break;
			} 
		}
		if(duplicate) {
			break;
		}
	}
	if(duplicate) {
		duplicatesExist = true;
		disableSubmit();
	} else {
		duplicatesExist = false;
		enableSubmit();
	}
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
				//console.log("missing prereq for " + className);
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
	if((!hasCourse && hasLab) || (!hasLab && hasCourse)) {
		if(missingOther.indexOf(className) == -1) missingOther[missingOther.length] = className;
		disableSubmit();
	} else {
		if(missingOther.indexOf(className) != -1) {
			missingOther.splice(missingOther.indexOf(className),1);
		}
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

function setRequirementsMsg() {
	var msg = "";
	if(missingOther.length > 0) msg += "Missing lab/class segment of: ";
	for(var missing in missingOther) {
		msg += missingOther[missing];
		if(missing+1 != missingOther.length) {
			msg += ", ";
		}
	}
	msg += "\n";
	if(duplicatesExist) msg += "duplicates classes and/or labs exist";
	console.log(msg);
}