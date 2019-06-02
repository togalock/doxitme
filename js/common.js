function searchCheck(id) {
	if (document.getElementById(id).value == "") {
		//make a container hidden. pop it up when the var is empty
		var link = "searchResult.html";
		window.location.href = link;
	} else {
		var link = "searchResult.html";
		window.location.href = link;
	}
}

function openTab(id) {
	var i;
	var x = document.getElementsByClassName("frontPageTab");
	for (i = 0; i < x.length; i++) {
		x[i].style.display = "none";
	}
	document.getElementById(id).style.display = "block";
}

function toggleLoginBox() {
  var x = document.getElementById("loginBox");
  if (x.className.indexOf("w3-show") == -1) {
    x.className = x.className.replace("w3-hide", "w3-show");
  } else {
    x.className = x.className.replace("w3-show", "w3-hide");
  }
}

function toggleView() {
  var x = document.getElementById("toggleView");
  if (x.src.indexOf("listView") == -1) {
    x.src = "img/listView.svg";
	document.getElementById("listView").style.display = "none";
	document.getElementById("gridView").style.display = "";
  } else {
    x.src = "img/gridView.svg";
	document.getElementById("gridView").style.display = "none";
	document.getElementById("listView").style.display = "";
  }
}
