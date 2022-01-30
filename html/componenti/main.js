(function () {
	document.addEventListener("click", closeMenus, { passive: true });
	document.addEventListener("click", clearMovieSearchResults, { passive: true });

	var menuItems = document.querySelectorAll("li.has-submenu");
	Array.prototype.forEach.call(menuItems, function (el, i) {
		el.addEventListener("mouseover", function (event) {
			this.className = "has-submenu open";
		});
		el.addEventListener("mouseout", function (event) {
			document.querySelector(".has-submenu.open").className = "has-submenu";
		});
		el.querySelector("a").addEventListener("click", function (event) {
			var mediaQuery = window.matchMedia("(max-width: 800px)");
			if (!mediaQuery.matches) {
				console.log("HELLO");
				if (this.parentNode.className == "has-submenu") {
					this.parentNode.className = "has-submenu open";
					this.setAttribute("aria-expanded", "true");
				} else {
					this.parentNode.className = "has-submenu";
					this.setAttribute("aria-expanded", "false");
				}
			}
			event.preventDefault();
			return false;
		});
	});
	handleFormErrorA11y();
	handleResizeChanges();
	window.addEventListener("resize", handleResizeChanges);
})();

function handleResizeChanges(event) {
	var mediaQuery = window.matchMedia("(max-width: 800px)");
	if (mediaQuery.matches) {
		var menuItems = document.querySelectorAll("li.has-submenu");
		Array.prototype.forEach.call(menuItems, function (el, i) {
			var node = el.querySelector("a");
			node.setAttribute("tabindex", "-1");
			node.removeAttribute("aria-haspopup");
			node.removeAttribute("aria-expanded");
		});
	} else {
		var menuItems = document.querySelectorAll("li.has-submenu");
		Array.prototype.forEach.call(menuItems, function (el, i) {
			var node = el.querySelector("a");
			node.removeAttribute("tabindex");
			node.setAttribute("aria-haspopup", "true");
			node.setAttribute("aria-expanded", "false");
		});
	}
}

function handleFormErrorA11y() {
	var menuItems = document.querySelectorAll("div.field.error");
	Array.prototype.forEach.call(menuItems, function (el, i) {
		var input = el.querySelector("input");
		var span = el.querySelector("span");
		input.setAttribute("aria-describedby", span.id);
	});
}

function toggleMenu(event) {
	var node = document.getElementById("heading");
	var button = document.getElementById("menuButton");
	if (node.className == "menuActive") {
		node.className = "";
		button.setAttribute("aria-expanded", "false");
		node.removeAttribute("class");
	} else {
		button.setAttribute("aria-expanded", "true");
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
	closeMenus();
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
