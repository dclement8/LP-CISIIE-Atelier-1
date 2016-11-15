var spoiler = function(id) {
	var div = document.querySelector("#spoiler-"+id);
	if(div) {
		if(div.style.display === "block") {
			div.style.display = 'none';
		}
		else {
			div.style.display = 'block';
		}
	}
}
