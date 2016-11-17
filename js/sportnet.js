window.onload = function() {
	// Spoiler
	var div;
	var btn = document.querySelectorAll("[id^='btn-spoiler-']");
	for(var i=0; i < btn.length; i++) {
		// Les boutons ont pour id : btn-spoiler-'id spoiler'
		// Les div "spoiler" ont pour id : spoiler- 'id spoiler'
		// this.id.substr(this.id.lastIndexOf("-")+1) permet de récupérer l'id du spoiler
		btn[i].onclick = function() {
			div = document.querySelector("#spoiler-"+this.id.substr(this.id.lastIndexOf("-")+1));
			if(div.style.display === "block")
				div.style.display = 'none';
			else
				div.style.display = 'block';
		};
	}

	// Champ partager
	var partager = document.querySelector("#partager");
	if(partager) {
		partager.value = window.location.href;
		partager.readOnly = true;
		partager.onclick = function() {
			this.select();
			document.execCommand("copy");
		};
	}
}
