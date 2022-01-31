(function () {
	document.getElementById("heading").addEventListener("click", function (event) {
		event.stopPropagation(event);
	});
	document.addEventListener(
		"keyup",
		function (event) {
			if (!isFocusWithin(event, "movieSelect")) clearMovieSearchResults();
			if (event.target.id !== "menuButton" && !isFocusWithin(event, "heading")) closeMenu();
		},
		{ passive: true }
	);
	document.addEventListener(
		"click",
		function (event) {
			closeMenu();
			if (!isFocusWithin(event, "movieSelect")) clearMovieSearchResults();
		},
		{ passive: true }
	);

	handleMenu();
	handleFormErrors();
	handleResizeChanges();
	window.addEventListener("resize", handleResizeChanges);
})();

function handleMenu() {
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
}

function handleFormErrors() {
	var menuItems = document.querySelectorAll("div.field");
	Array.prototype.forEach.call(menuItems, function (el, i) {
		var node = el.querySelector("input");
		var otherNode = el.querySelector("textarea");
		if (!node) node = otherNode;
		if (node) {
			node.addEventListener("keyup", function (ell, i) {
				if (!this.checkValidity()) {
					var span = el.querySelector("span");
					this.parentNode.classList.add("error");
					span.innerHTML = this.validationMessage;
					node.setAttribute("aria-describedby", span.id);
				} else this.parentNode.className = this.parentNode.className.replace("error", "");
			});
			node.addEventListener("blur", function (ell, i) {
				if (!this.checkValidity()) {
					var span = el.querySelector("span");
					this.parentNode.classList.add("error");
					span.innerHTML = this.validationMessage;
					node.setAttribute("aria-describedby", span.id);
				} else this.parentNode.className = this.parentNode.className.replace("error", "");
			});
		}
	});
}

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

function toggleMenu(event) {
	var node = document.getElementById("heading");
	var button = document.getElementById("menuButton");
	if (node.className == "menuActive") {
		node.className = "";
		button.setAttribute("aria-expanded", "false");
		node.removeAttribute("class");
		node.setAttribute("aria-hidden", "true");
	} else {
		button.setAttribute("aria-expanded", "true");
		node.className = "menuActive";
		node.setAttribute("aria-hidden", "false");
	}
	event.stopPropagation();
}

function closeMenu() {
	var mainMenu = document.getElementById("heading");
	var button = document.getElementById("menuButton");
	mainMenu.className = "";
	mainMenu.removeAttribute("class");
	mainMenu.setAttribute("aria-hidden", "true");
	button.setAttribute("aria-expanded", "false");
}

function openMenu() {
	var mainMenu = document.getElementById("heading");
	var button = document.getElementById("menuButton");
	mainMenu.className = "menuActive";
	mainMenu.setAttribute("aria-hidden", "false");
	button.setAttribute("aria-expanded", "true");
}

function getMovieSearchResults(event, string) {
	var suggestionList = document.getElementById("movieSuggestionList");

	var headerFlexbox = document.getElementById("topbar");
	headerFlexbox.className = "searching";

	if (string.length > 1) {
		var xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function () {
			if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
				suggestionList.innerHTML = this.responseText;
			}
		};
		xmlHttp.open("GET", "../../php/database/ricerca.php?q=" + string, true);
		xmlHttp.send();
	} else {
		suggestionList.innerHTML = "";
	}
}

function isFocusWithin(event, id) {
	var x = document.activeElement;
	while (x) {
		if (x.id == id || x == id) {
			return true;
		}
		x = x.parentElement;
	}
	return false;
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

function toggleSearchFilters(event, el) {
	if (el.parentNode.className == "card") {
		el.parentNode.className = "card open";
		el.setAttribute("aria-expanded", "true");
	} else {
		el.parentNode.className = "card";
		el.setAttribute("aria-expanded", "false");
	}
}
