window.onload = function() {
	var partager = document.querySelector("#partager");
	partager.value = window.location.href;
	partager.onclick = function() {
		this.select();
		document.execCommand("copy");
	};
}
