
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

	//var className = checkBox.name.substring(0,8);
	check();
}

function check(){
	myAjaxFunc();
	var labSelected = validateLabSelectedtest();
	var hasPrereqs = validateCourseSelection();
	var noDuplicates = validateAllSelectionsForDuplicates();
	var noConflicts = checkTimeTableConflict();
	if(hasPrereqs && labSelected && noConflicts && noDuplicates){
		enableSubmit();
	} else{
		disableSubmit();
	}
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
		return false;
	} else {
		duplicatesExist = false;
		return true;
	}
}

function validateCourseSelection() {
	var accept = true;
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
										break;
									}
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
									//return false;
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

function validateLabSelected(checkBox) {
	var fullClassName = checkBox.name;
	var testArray = [];
	var className = fullClassName.substring(0,8);
	var classSection = fullClassName.substring(9,fullClassName.length);
	var hasCourse = false;
	var hasLab = false;
	var rightSection = false;

	if(hasLabArr[className] = 0) {
		return true;
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
		return false;
	} else {
		if(missingOther.indexOf(className) != -1) {
			missingOther.splice(missingOther.indexOf(className),1);
		}
		return true;
	}
}








function validateLabSelectedtest() {
	var testArray = [];
	//var className = fullClassName.substring(0,8);
	//var classSection = fullClassName.substring(9,fullClassName.length);
	var validated = true;
	
	for(var j in selectedCourses) {
		var currentHasCourse = false;
		var currentHasLab = false;
		var currentRightSection = false;
		var fullClassName = selectedCourses[j]
		var className = fullClassName.substring(0,8);
		var classSection = fullClassName.substring(9,fullClassName.length);
		var isLab = false;
		if(/\d$/.test(classSection)) {
			isLab = true;
			hasLab = true;
		}
		if(hasLabArr[className] = 0) {
			return true;
		}

		for(var a in selectedCourses) {
			var index = selectedCourses[a].indexOf(className);
			if(index > -1) {
				var section = selectedCourses[a].substring(9,selectedCourses[a].length);
				var isnum = /\d$/.test(section);
				var isAtoF = /[A-F]/.test(section.substring(0,1));
				var isAtoF2 = /[A-F]/.test(classSection.substring(0,1));
				if(!isLab) {
					if(isnum) {
						currentHasCourse = true;
						currentHasLab = true;
						if(classSection.substring(0,1) == section.substring(0,1) || !isAtoF) {
							currentRightSection = true;
						}
					}
				} else{
					if(!isnum){
						currentHasLab = true;
						currentHasCourse = true;
						if(classSection.substring(0,1) == section.substring(0,1) || !isAtoF2) {
							currentRightSection = true;
						}
					}
				}
			}
		}

		if(currentHasCourse && currentHasLab && currentRightSection){
			//do nothing
		} else{
			validated = false;
			break;
		}
	}
	return validated;
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

function myAjaxFunc() {
	var request = new XMLHttpRequest();
	request.onreadystatechange = function(){
		if(request.readyState == 4 && request.status == 200){
			document.getElementById("timeTable").innerHTML = request.responseText;
		}
	}
	var getRequest = "timeTable.php?";
	for(var a in selectedCourses) {
		getRequest += selectedCourses[a] + "=on&";
	}
	getRequest = getRequest.substring(0, getRequest.length - 1);
	request.open("GET", getRequest, true);
	request.setRequestHeader("content-type", "application/x-www-form-urlencoded");
	request.send(null);


}

function checkTimeTableConflict() {
	var a = document.getElementById("conflict").value;
	if(a == "conflict"){
		return false;
	} else{
		return true;
	}
}