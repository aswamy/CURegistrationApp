
var selectedCourses = [];
var jsonPrereqs = [];
var finishedCourses = [];

function onChangeCheckBox(checkBox) {
	
	if(checkBox.checked){
		selectedCourses[selectedCourses.length] = checkBox.value;
	} else{
		selectedCourses.splice(selectedCourses.indexOf(checkBox.value), 1);
	}

	var className = checkBox.value.substring(0,8);
	validateCourseSelection(className);

}

function setJsonPrereqs(jsonString){
	jsonPrereqs = jsonString;
}

function setFinishedCourses(courses) {
	finishedCourses = courses;
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
			if(!accept) console.log("missing prereq for " + className);
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