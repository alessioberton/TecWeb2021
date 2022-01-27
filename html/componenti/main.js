(function () {
	document.addEventListener("touchstart", closeMenus);
	document.addEventListener("touchstart", clearMovieSearchResults);
	document.addEventListener("click", clearMovieSearchResults);
})();

function toggleMenu(event) {
	var node = document.getElementById("heading");
	if (node.className == "menuActive") {
		node.className = "";
		node.removeAttribute("class");
	} else {
		node.className = "menuActive";
	}
	event.stopPropagation();
}

function closeMenus() {
	var mainMenu = document.getElementById("heading");
	if (mainMenu) mainMenu.className = "";
}

function stopPropagation(domEvent) {
	domEvent.stopPropagation();
}

function getMovieSearchResults(string) {
	var suggestionList = document.getElementById("movieSuggestionList");
	var headerFlexbox = document.getElementById("topbar");
	headerFlexbox.className = "searching";
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

function clearMovieSearchResultsAndBar(event) {
	var headerFlexbox = document.getElementById("topbar");
	headerFlexbox.className = "";
	document.getElementById("movieSearch").value = "";
	document.getElementById("movieSuggestionList").innerHTML = "";
}

function clearMovieSearchResults(event) {
	var headerFlexbox = document.getElementById("topbar");
	headerFlexbox.className = "";
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
	if (document.getElementById(id_attore)) return;
	document.getElementById("hiddenActorList").innerHTML +=
		'<input id="' + id_attore + "box" + '" type="checkbox" hidden checked name="actors[]" value="' + id_attore + '" />';

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
