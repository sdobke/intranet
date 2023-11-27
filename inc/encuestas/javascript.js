function makeDraggable(){

	new Draggable('polldiv',{revert:false});

}



function selectOption(obj) {

	option = $F(obj);

}



function submitPoll() {		

	$('result').innerHTML = 'Gracias por tu respuesta';

	var url = 'inc/encuestas/poll.php';

	var pars = 'option=' + option ;

	var myAjax = new Ajax.Request( url, { method: 'post', parameters: pars, onComplete: showResponse, onFailure: reportError });

}



function showResponse(originalRequest) {

	var res = originalRequest.responseText;

	if (res.indexOf('ip=') != -1) {

		var ip = res.substring(3);

		alert('Tu respuesta ya está registrado');

		$('result').innerHTML =  '';

	}

	else {

		$('result').innerHTML =  '';

		$('voteimg').innerHTML =  '';

		$('ques').innerHTML = '<div align="center">Resultados</div>';

		$('options').innerHTML = res;

	}

}



function reportError(originalRequest) {

	$('result').innerHTML = originalRequest.responseText;

}	



function hidePoll() {

	$('polldiv').style.display = 'none';

	$('showdiv').style.display = 'block';

}



function showdiv() {

	$('polldiv').style.display = 'block';

	$('showdiv').style.display = 'none';

}