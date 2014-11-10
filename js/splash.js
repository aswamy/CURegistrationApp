function isOnTrack(bool) {
	if (JSON.parse(bool.value) == true) {
		document.getElementById('yearscompleted').disabled = false;
	} else {
		document.getElementById('yearscompleted').disabled = true;
	}
}