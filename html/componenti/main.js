function toggleMenu(menuID) {
	var node = document.getElementById(menuID);
	if (node.className == "menuActive") {
		node.className = "";
		node.removeAttribute("class");
	} else {
		node.className = "menuActive";
	}
}

function getSearchResults(string) {
	var suggestionList = document.getElementById("suggestionList");
	if (string.length < 2) {
		suggestionList.innerHTML = "";
		return;
	}

	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function () {
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
			suggestionList.innerHTML = this.responseText;
		}
	};
	xmlHttp.open("GET", "../../php/database/ricerca.php?q=" + string, true);
	xmlHttp.send();

	suggestionList.addEventListener("mousedown", (event) => {
		event.preventDefault();
	});
	suggestionList.addEventListener("touchstart", (event) => {
		event.preventDefault();
	});
}

function clearSearchResults(event) {
	document.getElementById("search").value = "";
	document.getElementById("suggestionList").innerHTML = "";
}
