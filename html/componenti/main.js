function toggleMenu(menuID) {
	var node = document.getElementById(menuID);
	if (node.className == "menuActive") {
		node.className = "";
		node.removeAttribute("class");
	} else {
		node.className = "menuActive";
	}
}

function getMovieSearchResults(string) {
	var suggestionList = document.getElementById("movieSuggestionList");
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
}

function clearMovieSearchResults(event) {
	document.getElementById("movieSearch").value = "";
	document.getElementById("movieSuggestionList").innerHTML = "";
}

function getActorSearchResults(string) {
	var list = document.getElementById("actorSuggestionList");
	if (string.length < 2) {
		list.innerHTML = "";
		return;
	}

	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function () {
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
			list.innerHTML = this.responseText;
		}
	};
	xmlHttp.open("GET", "../../php/database/suggerimenti_attore.php?q=" + string, true);
	xmlHttp.send();
}

function clearActorSearchResults() {
	document.getElementById("actorSuggestionList").innerHTML = "";
	document.getElementById("actorSearch").value = "";
}

function insertActor(nome_attore, id_attore) {
	if (document.getElementById(nome_attore.replace(/\s/g, ""))) return;
	document.getElementById("hiddenActorList").innerHTML +=
		'<input id="' +
		id_attore +
		"box" +
		'" type="checkbox" hidden checked disabled name="actors[]" value="' +
		id_attore +
		'" />';

	document.getElementById("actorList").innerHTML +=
		"<li id=" +
		id_attore +
		">" +
		nome_attore +
		"<button onclick='removeActor(\"" +
		id_attore +
		'")\' type="button">X</button></li>';
}

function removeActor(id_attore) {
	var checkbox = document.getElementById(id_attore + "box");
	var cipsetItem = document.getElementById(id_attore);
	checkbox.parentNode.removeChild(checkbox);
	cipsetItem.parentNode.removeChild(cipsetItem);
}
