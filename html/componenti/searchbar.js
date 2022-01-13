function getSearchResults(string) {
	if (string.length < 2) {
		document.getElementById("suggestionList").innerHTML = "";
		return;
	}

	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function () {
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
			console.log(this.response);
			document.getElementById("suggestionList").appendChild(document.createTextNode(this.response));
		}
	};
	xmlHttp.open("GET", "../../php/database/ricerca.php?q=" + string, true);
	xmlHttp.send();
}

function cleanSearchBar() {
	document.getElementById("suggestionList").innerHTML = "";
}
