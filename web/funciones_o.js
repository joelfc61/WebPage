//////////////// TOOLTIP

window.addEvent('domready', function(){

	var Tips4 = new Tips($$('.Tips4'), {

		className: 'custom'

	});

});



function addTextExtruder(z,x,y,t) 

{

	$j(document).ready(function(){

								

	var obj 		= document.getElementById("texto"+x);

	var contador 	= document.getElementById(y+'_extr'+x).value;

	var textBoxNro 	= y+1;								



		if( document.getElementById(y+'_extr'+x).value != "" && document.getElementById(textBoxNro+'_extr'+x) == null) {

					

		var $createIn;

		var attrs;

				

		$createIn = $j(obj);					

		$createIn

			.append(

			$j('<table border="0"  cellpadding="0" cellspacing="0"></table>')

				.append(

					$j('<tr></tr>')

					.append(	

						$j('<td width="85" height="19px" align="left" ></td>')///////////NUMERO DE ORDEN

						.append(

						$j('<input type="text" class="text" name="ot_extruder'+z+'[]"" id="ot_extruder'+x+'_'+textBoxNro+'" >')

						),

						$j('<td width="84" align="left"></td>')////////////CHECKBOX PARA CONVERSION

						.append(

							$j('<input type="text" class="text2" name="kg_extruder'+z+'[]" id="'+textBoxNro+'_extr'+x+'" onkeyup="addTextExtruder('+z+','+x+','+textBoxNro+');" onChange=" sumar('+x+');  sumarExtr();  kh('+t+');" >')

						),						

						$j('<td width="56" align="left" ></td>')//////////////FACTOR

						.append(

							$j('<input type="hidden" class="text2" name="id_detalle_resumen_maquina_ex'+z+'[]" id="'+textBoxNro+'id_detalle_resumen_maquina_ex'+x+'" value="0" >')

						)

					)

				)

			);

		textBoxNro++;

		}

	});

}



////////////////////IMPRESION //////////////////////////////////////////////////////////

function addTextBox(z,x,y,t) 

{

	$j(document).ready(function(){



	 var obj = document.getElementById("texto"+x); 

	 var contador = document.getElementById(y+'_impr'+x).value;

	 var textBoxNro = y+1;





		if( document.getElementById(y+'_impr'+x).value != "" &&  document.getElementById(textBoxNro+'_impr'+x) == null) {



		var $createIn;				

		$createIn = $j(obj);					

		$createIn

			.append(

			$j('<table border="0"  cellpadding="0" cellspacing="0"></table>')

				.append(

					$j('<tr></tr>')

					.append(	

						$j('<td width="75px" height="19px" align="left" ></td>')///////////NUMERO DE ORDEN

						.append(

						$j('<input type="text" size="12" class="text" name="ot_impresion'+z+'[]"" id="ot_impresion'+x+'_'+textBoxNro+'" >')

						),

						$j('<td width="63" align="left"></td>')////////////CHECKBOX PARA CONVERSION

						.append(

							$j('<input type="text" class="text2" name="kg_impresion'+z+'[]" id="'+textBoxNro+'_impr'+x+'" onkeyup="addTextBox('+z+','+x+','+textBoxNro+','+t+');" onChange=" sumarImpr2('+x+','+textBoxNro+');  sumarImpr();  kh('+t+');" >')

						),						

						$j('<td width="25" align="left" ></td>')//////////////FACTOR

						.append(

							$j('<input type="checkbox" name="bd_impresion'+z+'[]" id="'+textBoxNro+'"_impr_bd"'+x+'" value="1" onClick="sumarImpr2('+x+','+textBoxNro+');  sumarImpr(); kh('+t+');" >')

						),

						$j('<td width="56" align="left" ></td>')//////////////FACTOR

						.append(

							$j('<input type="hidden" name="id_detalle_resumen_maquina_im'+z+'[]" id="'+textBoxNro+'id_detalle_resumen_maquina_im'+x+'" value="0" onclick="sumarImpr2('+x+','+textBoxNro+');  sumarImpr();  kh('+t+');" >')

						)				

					)

				)

			);

		textBoxNro++;

		}

	});

}



function addTextBox2(z,x,y,t) 

{

	$j(document).ready(function(){



	 var obj = document.getElementById("limprtexto"+x);

	 var contador = document.getElementById(y+'_limpr'+x).value;

	 var textBoxNro2 = y+1;



		if( document.getElementById(y+'_limpr'+x).value != "" && document.getElementById(textBoxNro2+'_limpr'+x) == null) {



	   

		var $createIn;				

		$createIn = $j(obj);					

		$createIn

			.append(

			$j('<table border="0"  cellpadding="0" cellspacing="0"></table>')

				.append(

					$j('<tr></tr>')

					.append(	

						$j('<td width="75px" height="19px" align="left" ></td>')///////////NUMERO DE ORDEN

						.append(

						$j('<input type="text" size="12" class="text" name="ot_limpresion'+z+'[]"" id="ot_limpresion'+x+'_'+textBoxNro2+'" >')

						),

						$j('<td width="63" align="left"></td>')////////////CHECKBOX PARA CONVERSION

						.append(

							$j('<input type="text" class="text2" name="kg_limpresion'+z+'[]" id="'+textBoxNro2+'_limpr'+x+'" onkeyup="addTextBox2('+z+','+x+','+textBoxNro2+','+t+');" onChange=" sumarLimpr2('+x+','+textBoxNro2+');  sumarImpr();  kh('+t+');" >')

						),						

						$j('<td width="25" align="left" ></td>')//////////////FACTOR

						.append(

							$j('<input type="checkbox" name="bd_limpresion'+z+'[]" id="'+textBoxNro2+'"_limpr_bd"'+x+'" value="1" onClick="sumarLimpr2('+x+','+textBoxNro2+');  sumarImpr(); kh('+t+');" >')

						),

						$j('<td width="56" align="left" ></td>')//////////////FACTOR

						.append(

							$j('<input type="hidden" name="id_detalle_resumen_maquina_lim'+z+'[]" id="'+textBoxNro2+'id_detalle_resumen_maquina_lim'+x+'" value="0" onclick="sumarImpr2('+x+','+textBoxNro2+');  sumarImpr();  kh('+t+');" >')

						)				

					)

				)

			);

		textBoxNro2++;

		}

	});

}





	function redondear(cantidad, decimales) 

	{

		var cantidad = parseFloat(cantidad);

		var decimales = parseFloat(decimales);

		decimales = (!decimales ? 2 : decimales);

		return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);

	}





	function diferencia(valor1,valor2,valorTotal)

	{

		var a=document.getElementById(valor1);

		var b=document.getElementById(valor2);

		var total=document.getElementById(valorTotal);		

		total.value=parseFloat(a.value)-parseFloat(b.value);

	}







function muestra(id)

{

	id="div_"+id;

	

	var objeto=document.getElementById(id);

	if(objeto.style.display=="none")

		objeto.style.display="block";

	else if(objeto.style.display=="block")

		objeto.style.display="none";

	

}



function muestra_i(id)

{

	id="div_impr"+id;

	

	var objeto=document.getElementById(id);

	if(objeto.style.display=="none")

		objeto.style.display="block";

	else if(objeto.style.display=="block")

		objeto.style.display="none";

	

}



function muestra_b(id)

{

	id="div_bol"+id;

	

	var objeto=document.getElementById(id);

	if(objeto.style.display=="none")

		objeto.style.display="block";

	else if(objeto.style.display=="block")

		objeto.style.display="none";

	

}





function muestra_extr(id)

{

	var objeto="extruder"+id;

	

	var div=document.getElementById(objeto);

	

	if(div.style.display=="none")

		div.style.display="block";

	else

	    div.style.display="none";		

}



function muestra_impr(id,palabra)

{

	var objeto=palabra+id;

	

	var div=document.getElementById(objeto);

	

	if(div.style.display=="none")

		div.style.display="block";

	else

	    div.style.display="none";		

}





function mostrar2(id)

{

	id="tiempo2_"+id;

	

	var objeto=document.getElementById(id);

	if(objeto.style.display=="none")

		objeto.style.display="block";

	else if(objeto.style.display=="block")

		objeto.style.display="none";

	

}



function mostrar(id)

{

	id="tiempo_"+id;

	

	var objeto=document.getElementById(id);

	if(objeto.style.display=="none")

		objeto.style.display="block";

	else if(objeto.style.display=="block")

		objeto.style.display="none";

	

}







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









	var numRegex = /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/;

	

	function IsNumeric(sText)

	{

		var ValidChars = "0123456789.";

		var result = false;

		for (i = 0; i < sText.length ; i++) 

			if (ValidChars.indexOf(sText.charAt(i)) == -1) 

				return false;

		return true;

	}



	function sumaLocal(id)

	{

		var obj = document.getElementById('tabla_'+id).getElementsByTagName('input');

		var skg = 0; var sml = 0; var sdt = 0; var sdtr = 0; var sse = 0; var tmp;

		for(var i = 0; i < obj.length ; i++)

		{

			tmp = parseFloat(obj[i].value);

			if( !numRegex.test(obj[i].value) )

			{

				//obj[i].value = '';

				continue;

			}

			if( obj[i].id.indexOf('kg_'+id) != -1 )

				skg += tmp;

			else if( obj[i].id.indexOf('ml_'+id) != -1 )

				sml += tmp	

			else if( obj[i].id.indexOf('dt_'+id) != -1 )

				sdt += tmp;

			else if( obj[i].id.indexOf('dtr_'+id) != -1 )

				sdtr += tmp;

			else if( obj[i].id.indexOf('se_'+id) != -1 )

				sse += tmp;

		}

		document.getElementById('kgs_'+id).value = skg;

		document.getElementById('mls_'+id).value = sml;

		document.getElementById('dts_'+id).value = sdt;

		document.getElementById('dtrs_'+id).value = sdtr;

		document.getElementById('ses_'+id).value = sse;

		sumaTotal();

	}







	function convertir(id,x)

	{

		

		var obj = document.getElementById('tabla_'+id).getElementsByTagName('input');

		var rkg = 0;

		

		if(document.getElementById('sel_'+id+'_'+x).checked == true  ){

			

			

		if(document.getElementById('factor_'+id+'_'+x).value == '' || document.getElementById('factor_'+id+'_'+x).value == 0 ) {

			document.getElementById('kg_'+id+'_'+x).value = 0;		

			document.getElementById('factor_'+id+'_'+x).value = 0;	

			document.getElementById('ml_'+id+'_'+x).value = 0;

			

		}

		else 

		{

			var rkg = 0;

		

			rkg = document.getElementById('kg_'+id+'_'+x).value  / document.getElementById('factor_'+id+'_'+x).value;		



		document.getElementById('ml_'+id+'_'+x).value = Math.round(rkg);

		}

		}

		if(document.getElementById('sel_'+id+'_'+x).checked == false ){

			

		if(document.getElementById('factor_'+id+'_'+x).value == '' || document.getElementById('factor_'+id+'_'+x).value == 0) {

			document.getElementById('ml_'+id+'_'+x).value = 0;		

			document.getElementById('factor_'+id+'_'+x).value = 0;	

			document.getElementById('kg_'+id+'_'+x).value = 0;

		}

		else {

		

		var rkg = 0; 	

			

		rkg =  document.getElementById('ml_'+id+'_'+x).value * document.getElementById('factor_'+id+'_'+x).value ;

		

		document.getElementById('kg_'+id+'_'+x).value = Math.round(rkg);

		}

		}		

		

		sumaLocal(id)

		sumaTotal();

	}

	

////////////////CONFIGURACION PARA PRODUCCION DE BOLSEOOOOOOOOOOO////////////////////////////////////////////////////////////////////


function addTextBolseo2(x,y,t) {

	$j(document).ready(function(){

  	obj 		= 	document.getElementById("texto"+x);

  	contador 	= 	document.getElementById("kg_"+x+'_'+y).value;

  	textBoxNro = y+1;



		if( document.getElementById("kg_"+x+'_'+y).value != "" && document.getElementById("kg_"+x+"_"+textBoxNro) == null) {

		

		var $createIn;

		var attrs;

				

		$createIn = $j(obj);					

		$createIn

			.append(

			$j('<table></table>')

				.append(

					$j('<tr></tr>')

					.append(					

						$j('<td width="85" ></td>')///////////NUMERO DE ORDEN

						.append(

							$j('<input type="text" class="numeros" name="ot_'+x+'[]" id="ot_'+x+'_'+textBoxNro+'" onBlur="sumaLocal('+x+')" onFocus="this.select()" >')

						),

						$j('<td width="54" align="center"></td>')////////////CHECKBOX PARA CONVERSION

						.append(

							$j('<input type="checkbox" value="1" name="sel_'+x+'[]" id="sel_'+x+'_'+textBoxNro+'" onClick="checar('+x+','+textBoxNro+'); convertir('+x+','+textBoxNro+');" >')

						),						

						$j('<td width="56" align="center" ></td>')//////////////FACTOR

						.append(

							$j('<input type="text" size="4"  name="factor_'+x+'[]" id="factor_'+x+'_'+textBoxNro+'" onChange="convertir('+x+','+textBoxNro+');" >')

						),	

						$j('<td width="80" align="center"></td>')////////////MILLARES

						.append(

							$j('<input type="text" class="numeros" name="ml_'+x+'[]" id="ml_'+x+'_'+textBoxNro+'" onChange="mh('+t+');" onBlur="addTextBolseo2('+x+','+textBoxNro+','+t+'); convertir('+x+','+textBoxNro+'); sumaLocal('+x+');" onFocus="this.select()" >')

						),		

						$j('<td width="80" align="center"></td>')/////////////KILOGRAMOS

						.append(

							$j('<input type="text" class="numeros" name="kg_'+x+'[]" id="kg_'+x+'_'+textBoxNro+'" onChange="addTextBolseo2('+x+','+textBoxNro+','+t+'); mh('+t+');" onBlur="sumaLocal('+x+'); convertir('+x+','+textBoxNro+')" onFocus="this.select()" >')

						),

						$j('<td width="81" align="center"></td>')/////////////TIRA 

						.append(

							$j('<input type="text" class="numeros" name="dt_'+x+'[]" id="dt_'+x+'_'+textBoxNro+'" onBlur="sumaLocal('+x+')" onFocus="this.select()" >')

						)						

					)

				)

			);

		

		textBoxNro++;

		}

	});

}




function mh(x)

{

		 var total;

		 if(x == 1) y = 8;

		 else if(x == 2) y = 7;

		 else if(x == 3) y = 9;

		 total		=	0;

		 total 		=  document.getElementById('tml').value / y;

		 document.getElementById('m_p').value = redondear(total,2);

}

	

function kh(x)

{

		 var total;

		 if(x == 1) y = 8;

		 else if(x == 2) y = 7;

		 else if(x == 3) y = 9;	 

		 total		=	0;

		 total 		=  document.getElementById('total_extr').value / y;

		 document.getElementById('k_h').value = redondear(total,2);

}	





	function sumaTotal()

	{

		var obj = getElementsByClassName('subtotal','input',document);

		var tkg = 0; var tml = 0; var tdt = 0; var tdtr = 0; var tse = 0; var tmp;

		for(var i = 0; i < obj.length ; i++)

		{

			tmp = parseFloat(obj[i].value);

			if( isNaN(tmp) ) 

			{

				obj[i].value = '';

				continue;

			}

			if( obj[i].id.indexOf('kgs_') != -1 )

				tkg += tmp;

			else if( obj[i].id.indexOf('mls_') != -1 )

				tml += tmp;

				if(document.getElementById('repe').checked==true){

				

				tdt 	= 	document.getElementById('tdt').value;

				tdtr 	=	document.getElementById('tdtr').value;

				tse 	= 	document.getElementById('tse').value;



				}

				if(document.getElementById('repe').checked==false){

			 if( obj[i].id.indexOf('dts_') != -1 )

				tdt += tmp;

			else if( obj[i].id.indexOf('dtrs_') != -1 )

				tdtr +=tmp;

			else if( obj[i].id.indexOf('ses_') != -1 )

				tse += tmp;

				}

		}

		document.getElementById('tkg').value = tkg;

		document.getElementById('tml').value = tml;

		document.getElementById('tdt').value = tdt;

		document.getElementById('tdtr').value = tdtr;

		document.getElementById('tse').value = tse;

	}

	



	function getElementsByClassName(className, tag, elm)

	{

		var testClass = new RegExp("(^|\\s)" + className + "(\\s|$)");

		var tag = tag || "*";

		var elm = elm || document;

		var elements = (tag == "*" && elm.all)? elm.all : elm.getElementsByTagName(tag);

		var returnElements = [];

		var current;

		var length = elements.length;

		for(var i=0; i<length; i++){

			current = elements[i];

			if(testClass.test(current.className)){

				returnElements.push(current);

			}

		}

		return returnElements;

	}

//////////////////////////////////////////////////////////////////////////////////////////////

	

	

	function IsNumeric(sText)

 

{

   var ValidChars = "0123456789.,-";

   var IsNumber=true;

   var Char;

 

   for (i = 0; i < sText.length && IsNumber == true; i++) 

      { 

      Char = sText.charAt(i); 

      if (ValidChars.indexOf(Char) == -1) 

         {

         IsNumber = false;

         }

      }

   return IsNumber;

   

}

function sumarExtr()

{

        var a,total, i;

        i = document.getElementsByTagName('INPUT');

        total=0;

        for(a=0; a<i.length; a++)

        {

                if (IsNumeric(i[a].value) && i[a].id.indexOf('_extr_Total')>0 )

                {

                        if (i[a].value.length>0) 

                        {

                                total = parseFloat(total) + parseFloat(i[a].value);

                        }

                }

        }

        document.getElementById("total_extr").value = parseFloat(total);

}









function sumarImpr()

{

        var a,total, i;

        i = document.getElementsByTagName('INPUT');

        total=0;

        total2=0;

        for(a=0; a<i.length; a++)

        {

                if (IsNumeric(i[a].value) && i[a].id.indexOf('_impr_Total')>0 )

                {

                        if (i[a].value.length>0) 

                        {

                                total = parseFloat(total) + parseFloat(i[a].value);

                        }

                }

                if (IsNumeric(i[a].value) && i[a].id.indexOf('_impr_bdTotal')>0 )

                {

                        if (i[a].value.length>0) 

                        {

                                total2 = parseFloat(total2) + parseFloat(i[a].value);

                        }

                }				

				

				if (IsNumeric(i[a].value) && i[a].id.indexOf('_limpr_Total')>0   )

                {

                        if (i[a].value.length>0) 

                        {

                                total = parseFloat(total) + parseFloat(i[a].value);

                        }

                }



				if (IsNumeric(i[a].value) && i[a].id.indexOf('_limpr_bdTotal')>0   )

                {

                        if (i[a].value.length>0) 

                        {

                                total2 = parseFloat(total2) + parseFloat(i[a].value);

                        }

                }





        }

        document.getElementById("total_impr_bd").value = parseFloat(total2);

        document.getElementById("total_impr_hd").value = parseFloat(total);

}









function sumar(x)

{

        var a,total, i;

        i = document.getElementsByTagName('INPUT');

        total=0;

        for(a=0; a<i.length; a++)

        {

                if (IsNumeric(i[a].value) && i[a].id.indexOf('_extr'+x)>0 )

                {

                        if (i[a].value.length>0) 

                        {

                                total = parseFloat(total) + parseFloat(i[a].value);

                        }

                }

        }

				

        document.getElementById("subtotal_"+x+"_extr_Total").value = total;

}



function sumarImpr2(x)

{

        var a,total, i;

        i = document.getElementsByTagName('INPUT');

        total=0;

       	total2	=	0;

		

		for(a=0; a<i.length; a++)

        { 

			

				if (IsNumeric(i[a].value) && i[a].id.indexOf('impr'+x)>0  )

					{	b = a +1 ;

							if (i[a].value.length>0 && i[b].checked == false) 

							{

								total = parseFloat(total) + parseFloat(i[a].value);	



							}

							else if (i[a].value.length>0 && i[b].checked == true) 

							{

								total2 = parseFloat(total2) + parseFloat(i[a].value);	

							}							

							

					}

		}

   		document.getElementById("subtotal"+x+"_impr_bdTotal").value 	= total2;	

        document.getElementById("subtotal_"+x+"_impr_Total").value 		= total;

}



function sumarLimpr2(x)

{

        var a,total, i;

        i = document.getElementsByTagName('INPUT');

        total=0;

        total2=0;





        for(a=0; a<i.length; a++)

        { 

			

				if (IsNumeric(i[a].value) && i[a].id.indexOf('limpr'+x)>0  )

					{	b = a +1 ;

							if (i[a].value.length>0 && i[b].checked == false) 

							{

								total = parseFloat(total) + parseFloat(i[a].value);	



							}

							else if (i[a].value.length>0 && i[b].checked == true) 

							{

								total2 = parseFloat(total2) + parseFloat(i[a].value);	

							}							

							

					}

       }

	   

     

	      

   		document.getElementById("subtotal"+x+"_limpr_bdTotal").value 	= total2;	

        document.getElementById("subtotal_"+x+"_limpr_Total").value 	= total;

}





function nummaximo(x){

	

	if(document.getElementById("j"+x).value > 10 ){

	document.getElementById("j"+x).value = '10' 

	}

	

	

	if(document.getElementById("i"+x).value > 10 ){

	document.getElementById("i"+x).value = '10' 

	}



	

	if(document.getElementById("h"+x).value > 10 ){

	document.getElementById("h"+x).value = '10' 

	}

	



}



function colorear(x,y){

		if(document.getElementById(y+x).value  == ''){

		

		}

	

		else if(document.getElementById(y+x).value  == 'FJ' || 

			document.getElementById(y+x).value  == 'fj' || 

			document.getElementById(y+x).value  == 'Fj' ||

			document.getElementById(y+x).value  == 'fJ'){

		

		document.getElementById(y+x).style.color = '#008800';

		

		}

		

		else if(document.getElementById(y+x).value  == 'f' || 

			document.getElementById(y+x).value  == 'F' ){

		

		document.getElementById(y+x).style.color = '#FF0000';

	

		}

		

		else if(document.getElementById(y+x).value  == 'I' || 

			document.getElementById(y+x).value  == 'i' ){

		document.getElementById(y+x).style.color = '#0000AA';	

		}

		

		else if(document.getElementById(y+x).value  == 'I' || 

			document.getElementById(y+x).value  == 'i' ){

		document.getElementById(y+x).style.color = '#0000AA';	

		}		

		

		else if(document.getElementById(y+x).value  == 'V' || 

			document.getElementById(y+x).value  == 'v' ){

		document.getElementById(y+x).style.color = '#FF00AA';	

		}				

		

		else if(document.getElementById(y+x).value  == 'B' || 

			document.getElementById(y+x).value  == 'b' ||

			document.getElementById(y+x).value  == 'A' ||

			document.getElementById(y+x).value  == 'a' ||

			document.getElementById(y+x).value  == 'J' ||

			document.getElementById(y+x).value  == 'j' ||

			document.getElementById(y+x).value  == 'BAJA'  ||

			document.getElementById(y+x).value  == 'Baja'  ||

			document.getElementById(y+x).value  == 'baja'  

			

			

		){

		document.getElementById(y+x).style.color = '#884400';	

		}		

}





function Abrir_ventana(pagina) {

var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=508, height=220, top=85, left=140";

window.open(pagina,"",opciones);

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







function buscarOrden(texto,resultado,tabla,id,texto2)

{

	resul = document.getElementById(resultado);

	bus=document.getElementById(texto);

	ajax=nuevoAjax();

	ajax.open("POST", "busquedaOrden.php",true);

	ajax.onreadystatechange=function() {

		if (ajax.readyState==4) {

			resul.innerHTML = ajax.responseText

		}

	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("busqueda="+bus.value+"&campo="+texto2+"&tabla="+tabla+"&id="+id+"&campo2="+texto)



}



function buscarOrdenExt(texto,resultado,tabla,id,texto2)

{

	resul = document.getElementById(resultado);

	bus=document.getElementById(texto);

	ajax=nuevoAjax();

	ajax.open("POST", "busqueda_ord_extruder.php",true);

	ajax.onreadystatechange=function() {

		if (ajax.readyState==4) {

			resul.innerHTML = ajax.responseText

		}

	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("busqueda="+bus.value+"&campo="+texto2+"&tabla="+tabla+"&id="+id+"&campo2="+texto)



}







function mostrarAreas(id)

{

	id=id;

	

	var objeto=document.getElementById(id);

	if(objeto.style.display=="none")

		objeto.style.display="block";

	else if(objeto.style.display=="block")

		objeto.style.display="none";

	

}







var win=null;

function NewWindow(mypage,myname,w,h,scroll,pos){

   if(pos=="random"){

      LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;

	  TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;

	  }



   if(pos=="center"){

      LeftPosition=(screen.width)?(screen.width-w)/2:100;

	  TopPosition=(screen.height)?(screen.height-h)/2:100;

	  }



   else if((pos!="center" && pos!="random") || pos==null){

      LeftPosition=0;TopPosition=20

	  }



   settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';

   win=window.open(mypage,myname,settings);



}



	function suma_limp(x)

	{

		document.getElementById('resultado_'+x).value = parseFloat(document.getElementById('pre_'+x).value) + parseFloat(document.getElementById('porcentaje_'+x).value);

	}



function valida_semana_activa(input){

	var semana = parseInt(input.value);

	var semana_nueva = parseInt(document.getElementById('semana_nueva').value);

	if(semana < semana_nueva && document.getElementById('admin').value != "1"){

		alert("La semana "+semana+" ya ha sido cerrada por el administrador");

		input.value = semana_nueva;

	}

}