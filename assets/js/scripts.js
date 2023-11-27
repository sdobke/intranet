
function updatemenu() {
	if (document.getElementById('responsive-menu').checked == true) {
		document.getElementById('menu').style.borderBottomRightRadius = '0';
		document.getElementById('menu').style.borderBottomLeftRadius = '0';
	} else {
		document.getElementById('menu').style.borderRadius = '5px';
	}
}

function displayItem(divId) {
	$("#" + divId).slideToggle("slow", function () {
		// Animation complete.
	});
}

var min = 8;
var max = 18;
function agrandarTexto() {
	var p = document.getElementsByTagName('p');
	for (i = 0; i < p.length; i++) {
		if (p[i].style.fontSize) {
			var s = parseInt(p[i].style.fontSize.replace("px", ""));
		} else {
			var s = 12;
		}
		if (s != max) {
			s += 1;
		}
		p[i].style.fontSize = s + "px"
	}
}
function achicarTexto() {
	var p = document.getElementsByTagName('p');
	for (i = 0; i < p.length; i++) {
		if (p[i].style.fontSize) {
			var s = parseInt(p[i].style.fontSize.replace("px", ""));
		} else {
			var s = 12;
		}
		if (s != min) {
			s -= 1;
		}
		p[i].style.fontSize = s + "px"
	}
}
function getSelectedText(elementId) {
	var elt = document.getElementById(elementId);

	if (elt.selectedIndex == -1)
		return null;

	return elt.options[elt.selectedIndex].text;
}
function getSelectedIndex(elementId) {
	var e = document.getElementById(elementId);
	var strUser = e.options[e.selectedIndex].text;
	return strUser;
}
function showIfNotEmpty(data,id){
	if(data == ''){
		$("#"+id).hide();
	}else{
		$("#"+id).show();
	}
}