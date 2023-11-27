function confirmDelete(delUrl, tipo) {
	switch(tipo){

		case 1:
			texto = "¿Está seguro/a que quiere eliminar ese campo? Recuerde que toda la información almacenada será eliminada en todos los registros.";
			break;
		case 2:
			texto = "¿Está seguro/a que quiere eliminar esa opción? Recuerde que cada respuesta que tenga esa opción como seleccionada tendrá valor nulo.";
			break;
		case 3:
			texto = "¿Está seguro/a que quiere eliminar ese item?";
			break;
	}
	if (confirm(texto)) {
		document.location = delUrl;
	}
}
function cambiado(nro,obj,opc) {
	switch(nro)
	{
		case 1:
			objeto = 'nombres';
			break;
		case 2:
			objeto = 'tipos';
			break;
		case 3:
			objeto = 'opciones';
			break;
	}
	if (opc == 0){
		document.getElementById('mod_'+objeto).value = document.getElementById('mod_'+objeto).value+'-'+obj;
	}else{
		texto = "Al cambiar el nombre de la opción, tendrá que decidir qué ocurrirá con los registros que la tenían seleccionada. Presione ACEPTAR para que se mantengan los mismos valores con el nombre nuevo. Presione CANCELAR para que los registros que tengan esa opción seleccionada queden sin selección.";
		if (confirm(texto)) {
			document.getElementById('mod_'+objeto).value = document.getElementById('mod_'+objeto).value+'-'+obj+','+opc+',1';
		}else{
			document.getElementById('mod_'+objeto).value = document.getElementById('mod_'+objeto).value+'-'+obj+','+opc+',0';		
		}
	}
}
function ocultarMostrar(obj) {
	no = obj.options[obj.selectedIndex].value;
	if(no != 4){
		for(i=1;i<21;i++)
		document.getElementById('EmpDiv'+i).style.display = 'none';
	}else{
		document.getElementById('EmpDiv1').style.display = 'block';
	}
}

function Mostrar(obj) {
	sig = obj+1;
	document.getElementById('EmpDiv'+sig).style.display = 'block';
	document.getElementById('bot'+obj).style.display = 'none';
}
function ModMostrar(nro,obj) {
	sig = obj+1;
	document.getElementById('ModDiv'+nro+'_'+sig).style.display = 'block';
	document.getElementById('modbot_'+nro+'_'+obj).style.display = 'none';
	document.getElementById('mod_opciones_new').value = document.getElementById('mod_opciones_new').value+'-'+nro;
}

function Ocultar(obj) {
	no  = obj;
	ant = obj-1;
	document.getElementById('EmpDiv'+no).style.display = 'none';
	document.getElementById('bot'+ant).style.display = 'block';
	document.getElementById('opcion'+no).value = 'anula_opcion';
}
function ModOcultar(nro,obj) {
	no  = obj;
	ant = obj-1;
	document.getElementById('ModDiv'+nro+'_'+no).style.display = 'none';
	document.getElementById('modbot_'+nro+'_'+ant).style.display = 'block';
	document.getElementById('newopcion_'+nro+'_'+no).value = 'anula_opcion';
}
function validaNro(numero){
if (!/^([0-9])*$/.test(numero))
alert("El valor " + numero + " no es un número y este campo fue configurado como tal.");
}
function cambiarRadio() {
	document.form.fecha[0].checked = true;
	document.form.fecha[1].checked = false;
}
function cambiarEstado(id,valor,page,estado,orden,desde,hasta,items) {
	document.location = "?page="+page+"&estado="+estado+"&orden="+orden+"&desde="+desde+"&hasta="+hasta+"&items="+items+"&func=1&id="+id+"&valor="+valor;
}
function submitformCrear() {
    document.formcrear.submit();
}
function submitformMod() {
    document.formod.submit();
}