function date(){
    var m = "AM";
    var gd = new Date();
    var secs = gd.getSeconds();
    var minutes = gd.getMinutes();
    var hours = gd.getHours();
    var day = gd.getDay();
    var month = gd.getMonth();
    var date = gd.getDate();
    var year = gd.getYear();

    if(year<1000){
        year += 1900;
    }
	/*
    if(hours==0){
        hours = 12;
    }
    if(hours>12){
        hours = hours - 12;
        m = "PM";
    }*/
    if(secs<10){
        secs = "0"+secs;
    }
    if(minutes<10){
        minutes = "0"+minutes;
    }

    var dayarray = new Array ("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    var montharray = new Array ("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	var monthnumarray = new Array ("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

    var fulldate = dayarray[day]+", "+montharray[month]+" "+date+", "+year+" at "+hours+":"+minutes+":"+secs+" "+m;
	var fecha    = dayarray[day]+"<br />"+date+"."+monthnumarray[month]+"."+year;
	var hora     = +hours+":"+minutes;

    $("#lfecha").html(fecha);
	$("#lhora").html(hora);
    setTimeout("date()", 1000);
}
date();