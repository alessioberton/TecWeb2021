"use strict";

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
	function checkCommaSeparatedInput(event) {
		var value = event.target.value;
		var node = this;
		this.setCustomValidity("");
		if (!this.checkValidity()) return;

		Array.prototype.forEach.call(value.split(","), function (el, i) {
			if (!el || el === " ") node.setCustomValidity("Nomi separati da virgola richiesto");
			else if (!/^[a-zA-Z\s]+$/.test(el)) node.setCustomValidity("Caratteri non permessi presenti");
		});
	}

	function findInput(el) {
		var node = el.querySelector("input");
		var otherNode = el.querySelector("textarea");
		if (!node) node = otherNode;
		return node;
	}

	function toggleError(el) {
		var node = findInput(el);
		if (!node.checkValidity()) {
			var span = el.querySelector("span");
			span.innerHTML = node.validationMessage;
			span.setAttribute("title", node.validationMessage);
			el.classList.add("error");
			el.setAttribute("aria-describedby", span.id);
		} else {
			el.className = el.className.replace("error", "");
			el.removeAttribute("aria-describedby");
		}
	}

	function showError(el) {
		var node = findInput(el);
		var span = el.querySelector("span");
		span.innerHTML = node.validationMessage;
		span.setAttribute("title", node.validationMessage);
		el.classList.add("error");
		el.setAttribute("aria-describedby", span.id);
	}

	function hideError(el) {
		el.className = el.className.replace("error", "");
		el.removeAttribute("aria-describedby");
	}

	if (document.getElementById("registi")) {
		document.getElementById("registi").addEventListener("input", checkCommaSeparatedInput);
		document.getElementById("attori").addEventListener("input", checkCommaSeparatedInput);
	}

	var repeatPwd = document.getElementById("repeat-pwd");
	if (repeatPwd) {
		repeatPwd.addEventListener("input", function (event) {
			this.setCustomValidity("");
			if (!this.checkValidity()) return;
			if (document.getElementById("pwd").value !== event.target.value) {
				this.setCustomValidity("Password non uguale");
			}
		});
		document.getElementById("pwd").addEventListener("input", function (event) {
			var repeatPwd = document.getElementById("repeat-pwd");
			repeatPwd.setCustomValidity("");
			if (!repeatPwd.checkValidity()) return;
			if (repeatPwd.value !== event.target.value) {
				repeatPwd.setCustomValidity("Password non uguale");
				showError(repeatPwd.parentNode);
			} else {
				toggleError(repeatPwd.parentNode);
			}
		});
	}

	var menuItems = document.querySelectorAll("div.field");
	Array.prototype.forEach.call(menuItems, function (el, i) {
		var node = el.querySelector("input");
		var otherNode = el.querySelector("textarea");
		if (!node) node = otherNode;
		if (node) {
			node.addEventListener("input", function (ell, i) {
				toggleError(el);
			});
			node.addEventListener("blur", function (ell, i) {
				toggleError(el);
			});
		}
	});

	function checkboxGroupError() {
		var check = document.querySelector("#insert-film-form #genre-checkboxes");
		var checkboxes = check.querySelectorAll("input");
		var oneChecked = false;
		Array.prototype.forEach.call(checkboxes, function (el, i) {
			if (el.checked) oneChecked = true;
		});
		if (oneChecked) {
			check.className = check.className.replace("error", "");
			checkboxes[0].setCustomValidity("");
		} else {
			check.classList.add("error");
			checkboxes[0].setCustomValidity("No");
		}
	}

	var checkboxes = document.querySelectorAll("#insert-film-form #genre-checkboxes input");
	if (checkboxes.length) {
		Array.prototype.forEach.call(checkboxes, function (el, i) {
			el.addEventListener("change", checkboxGroupError);
		});
		checkboxGroupError();
	}

	var fileinput = document.getElementById("immagine");
	if (fileinput) {
		fileinput.addEventListener("change", function (event) {
			if (fileinput.files[0]) {
				const fileSize = fileinput.files[0].size;
				if (fileSize > 1300000) {
					fileinput.setCustomValidity("File troppo grande (1.3MB Max)");
				} else {
					fileinput.setCustomValidity("");
				}
				toggleError(fileinput.parentNode);
			} else {
				fileinput.setCustomValidity("");
			}
		});
	}
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

function toggleSearchFilters(event, el) {
	if (el.parentNode.className == "card") {
		el.parentNode.className = "card open";
		el.setAttribute("aria-expanded", "true");
	} else {
		el.parentNode.className = "card";
		el.setAttribute("aria-expanded", "false");
	}
}
