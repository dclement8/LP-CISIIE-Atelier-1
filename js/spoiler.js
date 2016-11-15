var spoiler = function(id) {
	var div = document.querySelector("#spoiler-"+id);
	if(div) {
		if(div.style.display === "none") {
			div.style.display = 'block';
		}
		else {
			div.style.display = 'none';
		}
	}
}
