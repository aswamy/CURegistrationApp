var selectedCourses = [];
var jsonPrereqs = [];
var finishedCourses = [];

function onChangeCheckBox(checkBox) {
	
	if(checkBox.checked){
		selectedCourses[selectedCourses.length] = checkBox.name;
	} else{
		selectedCourses.splice(selectedCourses.indexOf(checkBox.name), 1);
	}
	check();
}

function check() {
	if(validateCourseSelection()) {
		enableSubmit();
	} else{
		disableSubmit();
	}
}

function setJsonPrereqs(jsonString){
	jsonPrereqs = jsonString;
}

function setFinishedCourses(courses) {
	finishedCourses = courses;
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

function validateCourseSelection() {
	var accept = true;
	var valid = getCheckboxes();
	for(var className in jsonPrereqs){
		if(typeof(jsonPrereqs[className]) == "object" && isChecked(className)) {
			for(var nextLevel in jsonPrereqs[className]) {
				var nextElem1 = jsonPrereqs[className][nextLevel];
				var accept = nextElem1.length == 0;
				if(accept) { break; }
				accept = true;
				if(typeof(nextElem1) == "object") {
					for(var deeper in nextElem1) {
						var nextElem2 = nextElem1[deeper];
						var innerAccept = false;
						if(nextElem2 instanceof Array){
							for(object in nextElem2) {
								var courseObj = nextElem2[object];
								var name = courseObj.name;
								var concurrent = courseObj.concurrent;
								if(concurrent) {
									if(isConcurrentSelected(name) || courseCompleted(name)) {
										innerAccept = true;
											
									}
									//if course is concurrent and is valid for the current term/year, then select it 
									else if(!isConcurrentSelected(name) && valid.indexOf(name) != 0 && isChecked(className)) {
										updateCourse(name,true);
										innerAccept = true;

									}
								} else {
									innerAccept = courseCompleted(name);

								} 
								if(innerAccept) {
										break;
								}
							}
							accept = innerAccept;
							if(!innerAccept){
								break;
								//break;
							}
						} else { //its an object
							var name = nextElem2.name;
							var concurrent = nextElem2.concurrent;
							if(concurrent) {
								if(!isConcurrentSelected(name) && !courseCompleted(name)) {
									if(valid.indexOf(name) != 0) {
										updateCourse(name,true);
									}
									//return false;
									else {
										accept = false;
										break;
									}
								} 
							} else {
								if(!courseCompleted(name)) {
									accept = false;
									break;
								}
							}
						}
						
						
					}
				}
			}
			if(!accept) {
				//console.log("missing prereq for " + className);
				break;
			} 
		}

	}
	return accept;

}

function checkConcurrentCourses(course, checked) {
	var validCourses = getCheckboxes
	var jsonCoursePrereq = jsonPrereqs[course];
	for(var classes in jsonPrereqs) {
		var a = jsonCoursePrereq[classes];
		for(var nextLevel in a) {
			var andArray = jsonCoursePrereq[nextLevel];
			if(andArray instanceof Array) {
				for(nextLevel2 in andArray) {
					var orArray = andArray[nextLevel2];
					if(orArray instanceof Array) {
						for(lastLevel in orArray) {
							var obj = orArray[lastLevel];
							var name = obj.name;
							
						}
					} else {
						var bla = "";
					}
				}
			}
		}
	}
}

function disableSubmit() {
	document.getElementById("view2aSubmit").disabled = true;
}

function enableSubmit() {
	document.getElementById("view2aSubmit").disabled = false;
}

function getCheckboxes() {
	var collection = document.getElementById('courseSelection').getElementsByTagName('input');
	var validCourses = [];
	for(var i in collection) {
		if(collection[i].type == 'checkbox') {
			validCourses.push(i.name);
		}
	}
	return validCourses;
}

function updateCourse(course, checked) {
	document.getElementById(course).checked = checked;
	if(checked){
		selectedCourses[selectedCourses.length] = course;
	} else{
		selectedCourses.splice(selectedCourses.indexOf(course), 1);
	}
}