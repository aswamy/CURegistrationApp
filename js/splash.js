function isOnTrack(bool) {
	if (JSON.parse(bool.value) == true) {
		document.getElementById('yearstatus').disabled = false;
	} else {
		document.getElementById('yearstatus').disabled = true;
	}
}