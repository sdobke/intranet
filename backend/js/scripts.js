/*C�digos Unicode
\u00e1 -> � 
\u00e9 -> � 
\u00ed -> � 
\u00f3 -> � 
\u00fa -> � 
\u00c1 -> � 
\u00c9 -> � 
\u00cd -> � 
\u00d3 -> � 
\u00da -> � 
\u00f1 -> � 
\u00d1 -> � - See more at: http://geeks.ms/blogs/lruiz/archive/2008/04/25/acentos-en-javascript-alert-y-confirm.aspx#sthash.vlVGNnXf.dpuf
� -> \u00FC
� -> \u00DC
� -> \u00E7
� -> \u00C7
� -> \u00BF
� -> \u00A1
O si quereis algo mas espec�fico, aqui esta la lista completa, solo hay que poner \u00 delante: unicode.coeurlumiere.com
*/
function show(layer_ref) {
	var state = 'block';
	if (document.all) { //IS IE 4 or 5 (or 6 beta)
		eval("document.all." + layer_ref + ".style.display = state");
	}
	if (document.layers) { //IS NETSCAPE 4 or below
		document.layers[layer_ref].display = state;
	}
	if (document.getElementById && !document.all) {
		hza = document.getElementById(layer_ref);
		hza.style.display = state;
	}
}
function mostrar(nomdiv) {
	document.getElementById(nomdiv).style.display = "block";
}
function ocultar(nomdiv) {
	document.getElementById(nomdiv).style.display = "none";
}
function hide(layer_ref) {
	var state = 'none';
	if (document.all) { //IS IE 4 or 5 (or 6 beta)
		eval("document.all." + layer_ref + ".style.display = state");
	}
	if (document.layers) { //IS NETSCAPE 4 or below
		document.layers[layer_ref].display = state;
	}
	if (document.getElementById && !document.all) {
		hza = document.getElementById(layer_ref);
		hza.style.display = state;
	}
}
function MM_showHideLayers() { //v9.0
	var i, p, v, obj, args = MM_showHideLayers.arguments;
	for (i = 0; i < (args.length - 2); i += 3)
		with (document) if (getElementById && ((obj = getElementById(args[i])) != null)) {
			v = args[i + 2];
			if (obj.style) { obj = obj.style; v = (v == 'show') ? 'visible' : (v == 'hide') ? 'hidden' : v; }
			obj.visibility = v;
		}
}
function agregaCampos(nro) {
	var nroDiv = nro + 1;
	document.getElementById('DivCont' + nroDiv).style.display = 'block';
	document.getElementById('agregar_fotos').value = '1';
}
function agregaCamposV(nro) {
	var nroDiv = nro + 1;
	document.getElementById('DivContV' + nroDiv).style.display = 'block';
	document.getElementById('agregar_videos').value = '1';
}
function confirmDelete(delUrl) {
	if (confirm("\u00BFEst\u00e1 seguro que quiere eliminar ese registro?")) {
		document.location = delUrl;
	}
}
function confirmDeleteDependencias(delUrl) {
	if (confirm("\u00BFEst\u00e1 seguro que quiere eliminar ese registro?")) {
		UrlBorrar = delUrl + '&nuevodato=' + document.getElementById('nuevoelim').value;
		document.location = UrlBorrar;
	}
}
function confirmaSend() {
	if (document.getElementById('habilita_envio').value == 'no') {
		alert('No se realiz\u00f3 el recorte de la imagen');
		return false;
	} else {
		return true;
	}
}
function Abrir_ventana(id, divid=0, valor=0) {
	if (divid != '0' && valor != '0') {
		$("#" + divid).val(valor);
	}
	document.getElementById('habilita_envio').value = 'no';
	var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1010, height=810, top=10, left=10";
	if (!window.open("inc/recorte/recorte.php?id=" + id, "", opciones)) {
		document.getElementById('result').innerHTML = "<a href=\"inc/recorte/recorte.php?id=" + id + "\" target=\"_blank\" onclick=\"setTimeout('CheckULP();', 4000);\">Hacer recorte de imagen</b></a>";
	}
	/*result=document.getElementById('result');*/
}
