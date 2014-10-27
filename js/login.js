function postLogin() {

	//Combo boxes are singular elements, so we can read the value from them.
	var degree = document.getElementById('degree').value;
	/*For the radio boxes I can be clever and just 
	  check one of the two boxes "checked" attribute
	  since we only have two choices.
	*/
	var onTrack = document.getElementById('ontrack').checked;
	if (onTrack ==true) {
		var yearStatus  = document.getElementById('yearstatus').value;
	}
	else {
		var yearStatus  = NaN; // This number does not matter if we aren't on track.
	}
	
	// Post the login data to the server
	var body = "yearStatus="+ yearStatus 
			   +"&onTrack=" + onTrack
			   +"&degree="  + degree;
			   
	var request = new XMLHttpRequest();
	request.open("post", "login.php", false);
	request.setRequestHeader("content-type", "application/x-www-form-urlencoded");
	var reply = request.send(body);
	
	//Redirect to the program tree
	window.location = "../programtree.php";
}