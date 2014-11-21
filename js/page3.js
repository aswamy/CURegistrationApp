var index = 0;
var scheduleText = "";

function myAjaxFunc(increment) {
	var request = new XMLHttpRequest();
	request.onreadystatechange = function(){
		if(request.readyState == 4 && request.status == 200){
			document.getElementById("timeTable").innerHTML = request.responseText;
		}
	}
	var getRequest = "displayTimeTable.php?";
	var solutionsDiv = document.getElementById("solutions");
	var solutions = solutionsDiv.getElementsByTagName('div');
	var length = solutions.length;
	index += increment;
	if(index > length-1) {
		index = 0;
	} else if(index < 0) {
		index = length-1;
	}
	var b = solutions[index].innerHTML.split(',');
	b.pop();//temporary
	scheduleText = "";
	for(var a in b) {
		getRequest += b[a] + "=on&";
		scheduleText += b[a] + ", ";
	}
	scheduleText = scheduleText.substring(0,scheduleText.length-1);
	updateScheduleText();
	getRequest = getRequest.substring(0, getRequest.length - 1);
	request.open("GET", getRequest, true);
	request.setRequestHeader("content-type", "application/x-www-form-urlencoded");
	request.send(null);


}

function updateScheduleText() {
	document.getElementById("courseList").innerHTML = "<p>Courses in schedule: " + scheduleText + "</p>";
}