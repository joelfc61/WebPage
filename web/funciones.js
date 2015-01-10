// JavaScript Document
function oculta(nombre)
{
 var tabla=document.getElementById(nombre);
	tabla.style.display="none";
}

function nuevoAjax(){
	var xmlhttp=false;
	try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			xmlhttp = false;
		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	
	return xmlhttp;
}

function buscarDato(texto, resultado,tipo){
	resul = document.getElementById(resultado);
	bus=document.getElementById(texto);
	ajax=nuevoAjax();
	ajax.open("POST", "busqueda.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			resul.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("busqueda="+bus.value+"&nombre="+bus.name+"&tipo="+tipo)

}

function busqueda(id)
{
	document.frmbusqueda.action="index.php?accion=buscar&id_cliente="+id;	
	document.frmbusqueda.submit();
}


function buscarCliente(texto,resultado)
{
	resul = document.getElementById(resultado);
	bus=document.getElementById(texto);
	ajax=nuevoAjax();
	ajax.open("POST", "busquedaCliente.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			resul.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("busqueda="+bus.value+"&razon="+bus.razon)

}