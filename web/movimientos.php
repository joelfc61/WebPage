<? 

$tabla	=	"movimientos";

$indice	=	"id_movimiento";



$campos	=	describeTabla($tabla,$indice);



if(!empty($_POST['guardar']) || !empty($_POST['actualizar']))

{

    

	if(	isset($_POST[$indice]) ) $id = intval( $_POST[$indice] );

	else

	{

		$res_id		=	mysql_query("SELECT MAX($indice) FROM $tabla");

		$next_id	=	mysql_fetch_assoc($res_id);

		$id			=	$next_id[$indice]+1;

	}

	



	$query			=	"";

	foreach($campos as $clave)

			

			if($clave == 'dia' || $clave == 'desde' || $clave == 'hasta' || $clave == 'fecha')

			$query		.=	(($query=="")?"":",")."$clave='".fecha_tablaInv($_POST[$clave])."'";

			else 

			$query		.=	(($query=="")?"":",")."$clave='".$_POST[$clave]."'";			

			  		

	if(!empty($_POST[$indice]))

		$query		=	"UPDATE $tabla SET ".$query." WHERE ($indice=".$_POST[$indice].")";

		else

	    $query		=	"INSERT INTO $tabla SET $query";

		

	  $res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");

	  $id_movimiento	=	mysql_insert_id();	





	if(!empty($_REQUEST['actualizar']))

	{

		if(empty($_POST[$indice])){

	     $_REQUEST[$indice] = $id_movimiento;

		}else{ 

	     $_REQUEST[$indice] = $_REQUEST[$indice];

		}

		

		$qAutorizar	=	"UPDATE $tabla SET autorizado = 1 WHERE ($indice={$_REQUEST[$indice]}) LIMIT 1";

		$rAutorizar	=	mysql_query($qAutorizar) OR die("<p>$qAutorizar</p><p>".mysql_error()."</p>");

		//$redirecciona	=	true;

		//$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";

	}

	if($_REQUEST['almacen'] == 1) $alm = "almacen";

	if($_REQUEST['almacen'] == 0) $alm = "produccion";

	

    $redirecciona	=	true;

	if(isset($_SESSION['id_admin'])){

	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar&semana_reportes=".$_REQUEST['semana']."&almacen=".$alm."&anio_mov={$_REQUEST['anio_mov']}";

	}

	else {

	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin_supervisor.php?seccion={$_GET[seccion]}&accion=listar&semana_reportes=".$_REQUEST['semana']."&almacen=".$alm."&anio_mov={$_REQUEST['anio_mov']}";

	}

} 



if(!empty($_POST['cerrar_semana']))

{



	if($_REQUEST['almacen'] == 'almacen') 	{	$tipo = 1; $alm = "almacen"; }

	if($_REQUEST['almacen'] == 'produccion'){ 	$tipo = 0; $alm = "produccion"; }

		

	 $qRevisaSemana	=	"SELECT id_semana FROM semana WHERE semana = ".$_REQUEST['semana']." AND anio = ".$_REQUEST['anio_mov']." AND tipo = $tipo ";

	 $rRevisaSemana	=	mysql_query($qRevisaSemana);

	 $nRevisaSemana	=	mysql_num_rows($rRevisaSemana);



	if($nRevisaSemana < 1)

	{

	    $qCerrar		=	"INSERT INTO semana (semana, cerrar, tipo, anio) VALUES ('$_REQUEST[semana]', 1, '$tipo','".$_REQUEST['anio_mov']."')";

		$rCerrar		=	mysql_query($qCerrar);

	}



    $redirecciona	=	true;

	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar&semana_reportes=".$_REQUEST['semana']."&almacen=".$alm."&anio_mov={$_REQUEST['anio_mov']}";



}



$listar =	true;

if(!empty($_GET['accion']))

{

	$listar		=	($_GET['accion']=="listar")?true:false;

	$nuevo		=	($_GET['accion']=="nuevo")?true:false;

	$portada	=	($_GET['accion']=="portada")?true:false;

	

	if(!empty($_GET[$indice]) && is_numeric($_GET[$indice]))

	{

		$mostrar	=	($_GET['accion']=="mostrar")?true:false;

		$eliminar	=	($_GET['accion']=="eliminar")?true:false;

		$modificar	=	($_GET['accion']=="modificar")?true:false;

		$cambio_almacen	=	($_GET['accion']=="cambio_almacen")?true:false;

			

	}

	

	if(!empty($_GET[$indice2]) && is_numeric($_GET[$indice2]))

	{

		$mostrarGeneral		=	($_GET['accion']=="mostrar")?true:false;

		$eliminarGeneral	=	($_GET['accion']=="eliminar")?true:false;

		$modificarGeneral	=	($_GET['accion']=="modificar")?true:false;

			

	}

		

	if($mostrar || $modificar)

	{

		$query_dato	=	"SELECT * FROM $tabla WHERE ($indice={$_GET[$indice]})";

		$res_dato	=	mysql_query($query_dato) OR die("<p>$query_dato</p><p>".mysql_error()."</p>");

		$dato		=	mysql_fetch_assoc($res_dato);

	

					$qE	=	"SELECT * FROM operadores WHERE id_operador = ".$dato['id_operador']."";

					$rE	=	mysql_query($qE);

					$dE	=	mysql_fetch_assoc($rE);		

					

					$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dato['id_operador2']."";

					$rE2	=	mysql_query($qE2);

					$dE2	=	mysql_fetch_assoc($rE2);		

	}

				

	if($cambio_almacen)

	{

		if($_REQUEST['almacen'] == 0) $almacen	=	1;

		else $almacen	=	0;

	

		$qAutorizar	=	"UPDATE $tabla SET almacen = $almacen WHERE ($indice={$_REQUEST[$indice]}) LIMIT 1";

		$rAutorizar	=	mysql_query($qAutorizar) OR die("<p>$qAutorizar</p><p>".mysql_error()."</p>");

		

		$redirecciona	=	true;

		if($_REQUEST['almacen'] == 0){

			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar&semana_reportes=".$_REQUEST['semana_reportes']."&almacen=produccion&anio_mov={$_REQUEST['anio_mov']}";

		}

		else {

			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar&semana_reportes=".$_REQUEST['semana_reportes']."&almacen=almacen&anio_mov={$_REQUEST['anio_mov']}";

		} 

					

	}



	if($eliminar)

	{

		$q_eliminar2	=	"DELETE FROM $tabla WHERE ($indice={$_GET[$indice]}) LIMIT 1";

		$r_eliminar2	=	mysql_query($q_eliminar2) OR die("<p>$q_eliminar2</p><p>".mysql_error()."</p>");

		$redirecciona	=	true;

		if(isset($_SESSION['id_admin'])){

			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_REQUEST[seccion]}&accion=listar&semana_reportes={$_REQUEST['semana_reportes']}&anio_mov={$_REQUEST['anio_mov']}&almacen={$_REQUEST['almacen']}";

		}

		else{

			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin_supervisor.php?seccion={$_REQUEST[seccion]}&accion=listar&semana_reportes={$_REQUEST['semana_reportes']}&anio_mov={$_REQUEST['anio_mov']}&almacen={$_REQUEST['almacen']}";			

		}

	}

	

}

?>

<? if($nuevo || $mostrar || $modificar){?>                                         

<script type="text/javascript" language="javascript" >



function validar(x){

	

	var uno = document.getElementById(x).value;

	

	if( uno != 0 ){

		document.getElementById('premio').style.display = "block";

		document.getElementById('puntualidad').style.display = "block";

		document.getElementById('productividad').style.display = "block";

		document.getElementById('no_premio').style.display = "block";

	}else{

		document.getElementById('premio').style.display = "none";	

		document.getElementById('puntualidad').style.display = "none";	

		document.getElementById('productividad').style.display = "none";	

		document.getElementById('no_premio').style.display = "none";	

	}

	

	if(uno != 9 && uno != 12 )

		document.getElementById('dia').style.display = "block";

	else

		document.getElementById('dia').style.display = "none";

		

	if(uno == 8 || uno == 9)

		document.getElementById('numero_dias').style.display = "block";

	else

		document.getElementById('numero_dias').style.display = "none";

		

				

	if( uno == 20)

		document.getElementById('turno').style.display = "block";

	else

		document.getElementById('turno').style.display = "none";

			

				

	if(uno == 4 || uno == 18 || uno ==20)

		document.getElementById('operador').style.display = "block";

	else

		document.getElementById('operador').style.display = "none";	

		



	if(uno == 5 || uno == 20)

		document.getElementById('rol').style.display = "block";

	else

		document.getElementById('rol').style.display = "none";		

		



	if(uno == 6 || uno == 20)

		document.getElementById('horario').style.display = "block";

	else

		document.getElementById('horario').style.display = "none";	



	if( uno == 8 || uno == 9 || uno == 20){

		document.getElementById('desde').style.display = "block";

		document.getElementById('hasta').style.display = "block";

		}

	else{

		document.getElementById('desde').style.display = "none";	

		document.getElementById('hasta').style.display = "none";	

		}



if(  uno == 23 ){

		document.getElementById('desde_tiempo').style.display = "block";

		document.getElementById('hasta_tiempo').style.display = "block";

		}

	else{

		document.getElementById('desde_tiempo').style.display = "none";	

		document.getElementById('hasta_tiempo').style.display = "none";	

		}

	

	if(uno == 11 || uno == 12 || uno == 20)

		document.getElementById('cantidad').style.display = "block";

	else

		document.getElementById('cantidad').style.display = "none";	

		

	if( uno == 20 || uno == 10 || uno == 3 || uno == 7 || uno == 2)

		document.getElementById('horas').style.display = "block";

	else

		document.getElementById('horas').style.display = "none";

		

				

	if( uno == 20)

		document.getElementById('otro').style.display = "block";

	else

		document.getElementById('otro').style.display = "none";

}





function genera2()

{

document.form.action="reportes_pdf_nomina.php?tipo=personal&semana=<?=$dato['semana']?>&id_movimiento=<?=$_REQUEST['id_movimiento']?>";

document.form.submit();



}



function genera()

{

document.pre.action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>";

document.pre.submit();

}



function valida_campo(evt,tipo){

	var regexp = "";

	switch(tipo){

		case 1://Solo números y punto

			regexp = /^[\d]$/;

			//regexp = /^(?:\+|-)?\d+$/;

			break;

		case 2://Letras A a la Z, la 'Ñ' y espacio en blanco

			regexp = /^[A-Za-zÑñÁáÉéÍíÓóÚú\s]$/;

			break;

	}

	var keynum = evt.keyCode || evt.charCode;

	var keychar = String.fromCharCode(keynum);

    if(evt.keyCode==8 || evt.keyCode==9 || evt.keyCode==13 || evt.keyCode==16 || evt.keyCode==17 || evt.keyCode==18 || evt.keyCode==35 || evt.keyCode==36 || evt.keyCode==37 || evt.keyCode==39 || evt.keyCode==46 || evt.keyCode==116 || regexp.test(keychar)){

		return true;

    }

	return false;

}



</script>

<div align="center">

   	 	<div class="tablaCentrada">

<form  name="form" id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

                    	<table width="70%" border="0" align="center">

                   	  <tr >

                            	<td height="25" align="right"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Fecha de emisi&oacute;n: </td>

                                <td align="left" class="style5"><b>

								<?

								echo "<input type='hidden' id='admin' value='$modificar_movimientos'/>";

								if($nuevo || $modificar){

								//if($nuevo){

									$anio_aux = isset($_REQUEST['anio_mov'])?$_REQUEST['anio_mov']:substr($dato['fecha'],0,4);

								 	$qSemanaCerrar	=	"SELECT id_semana FROM semana WHERE semana = '".(isset($_REQUEST['semana'])?$_REQUEST['semana']:$dato['semana'])."' AND anio = '".$anio_aux."'";

									echo "<input type='hidden' name='anio_mov' id='anio_mov' value='$anio_aux'/>";

									//echo $qSemanaCerrar;

									$rSemanaCerrar	=	mysql_query($qSemanaCerrar);

								 	$nSemanaCerrar	=	mysql_num_rows($rSemanaCerrar);

								//}

								$qSemanaNueva	=	"SELECT MAX(semana)+1 as semana_nueva FROM semana WHERE anio = '".(isset($_REQUEST['anio_mov'])?$_REQUEST['anio_mov']:date('Y'))."'";

								$rSemanaNueva	=	mysql_query($qSemanaNueva);

								$nSemanaNueva	=	mysql_fetch_array($rSemanaNueva);

								echo "<input type='hidden' id='semana_nueva' value='$nSemanaNueva[semana_nueva]'/>";								

								 ?>

                        <input type="text" name="fecha" value="<? if($modificar){ fecha($dato['fecha']);} if($nuevo){ echo date('d-m-Y'); }?>" />

							<? }

						if($mostrar){ echo fecha($dato['fecha']); }?></b></td>

                        <td colspan="4" style="color:#FF0000; text-align:justify"><? if($nSemanaCerrar > 0 && !$modificar && !$modificar_movimientos) echo "Este movimiento se tomar&aacute; en cuenta para la semana $nSemanaNueva[semana_nueva], que es la siguiente semana activa"; else if($nSemanaCerrar > 0) echo "La semana $_REQUEST[semana] ya ha sido cerrada";?></td>

                          </tr>

                        	<tr >

                            	<td height="25" align="right"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Semana : </td>

                                <td align="left" class="style5"><b>

								<?

							 if($nuevo || $modificar){ 

								echo "<input type='text' id='semana' name='semana' onkeypress='return valida_campo(event,1);' onchange='valida_semana_activa(this)' value='";

								if($nuevo && ($nSemanaCerrar < 1 || ($nSemanaCerrar > 0 && $modificar_movimientos))){//nuevo y la semana está abierta

									echo $_REQUEST['semana'];

								} 

								else if($nuevo && $nSemanaCerrar > 0)//nuevo y está cerada la semana 

									echo $nSemanaNueva['semana_nueva'];

								 

								if($modificar){ //modificación

									fecha($dato['semana']);

								}

								echo "'"; //comilla de cierre del atrubuto "value"

								

								if($nSemanaCerrar > 0 && !$modificar_movimientos) //semana cerrada y no admin

									echo "disabled='disabled'";

								echo "/>"; //cierre del input de la semana

							} //fin de nuevo || modificar

							

							if($mostrar){ 

								echo fecha($dato['semana']); 

							}

							?></b></td>

                              <td colspan="4" style="text-align:justify; color:#FF0000"><? if($nSemanaCerrar > 0 && !$modificar && !$modificar_movimientos) echo "ya que la semana $_REQUEST[semana] ya ha sido cerrada"; ?></td>

                          </tr>

                            <tr>

                            	<td width="95" height="25" align="right" valign="middle" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Nombre:</td>

                              <? if($nuevo || $modificar){?>

                              <td colspan="2" align="left" class="style5"><b>

                             <input type="hidden" name="autorizado" id="autorizado" value="<? if($modificar && $dato['autorizado'] == 1) echo "1"; else echo "0"; ?>" />

                             <input value="<? if($modificar) echo $dE['nombre'];?>" type="text" size="40" name="nombre" id="nombre" accesskey="4" onKeyPress="buscarOrden('nombre','resultado','empleado','id_operador','tabla_empleado');" onKeyUp="buscarOrden('nombre','resultado','empleado','id_operador','tabla_empleado');" autocomplete="off" >

                             <input type="hidden" name="id_operador" id="id_operador" value="<? if($modificar) echo $dato['id_operador'];?>" />

                             <? if(!isset($_SESSION['id_admin'])){ ?>

                             <input type="hidden" name="id_supervisor" value="<? if($modificar) echo $dato['id_supervisor'];?>" />	

                             <? } if(isset($_SESSION['id_admin'])){ ?>

                             <input type="hidden" name="id_usuario" value="<? if($modificar) echo $dato['id_usuario'];?>" />	

                             <? } ?>

                      

<br>

									<div id="resultado">

									</div>        

        </b>

        <br />

        <? if($nuevo){?><input type="hidden" name="fecha" value="<?=date('Y-m-d')?>"><? } ?></td>

        <? } if($mostrar){?>

        <td width="200" height="25" align="left" class="style5"><b><?  echo  $dE['nombre'];  ?></b></td>

        <td width="71"  align="left" style="background-color: rgb(25, 121, 169); color:#FFFFFF">C&oacute;digo: </td>

        <td width="133" align="left" class="style5"><b><?=$dE['numnomina']?></b></td>

        </tr>

        <tr>

        <td  align="right" height="25" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Departamento: </td>

        <td align="left" class="style5"><b><?=$areasEmpleado[$dE['area']]?></b></td>

        <td align="left"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Puesto:</td>

        <td align="left" class="style5"><b><?=$dE['puesto']?></b></td>

<? } ?>

                          </tr>

                          <tr>

                            	<td  align="right" height="25" valign="middle" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Movimiento:</td>

                           	<td colspan="5" align="left" class="style5"><b>

       	              	<? if($nuevo || $modificar){ ?>

       				  <select name="movimiento" id="1" onChange="javascript: validar(this.id);">

                                	<? for($a = 0 ; $a < sizeof($motivos); $a++){ ?>

                                		<option value="<?=$a?>" <? if($dato['movimiento'] == $a) echo "selected";?> ><?=$motivos[$a]?></option>

                             <?  } ?>

                              </select><? } if($mostrar ){  echo $motivos[$dato['movimiento']]; } ?></b>

							</td>

                        </tr>

                        <tr>

                        	<td height="25" align="right" valign="middle" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Caracteristicas:</td>

                       	  <td colspan="5" align="left" valign="middle" class="style5"><br />

                          <? if($nuevo || $modificar){ ?>

                        	<div id="otro" style="display:<? if($dato['movimiento'] == 20) echo "block;"; else echo "none;"; ?>">Movimiento nuevo: <input type="text" name="nuevo_movimiento" value="<? if($modificar || $dato['movimiento'] == 20 ) echo $dato['nuevo_movimiento']; ?>" ><br /><br></div>

                            <div id="premio" style="display:<? if($dato['movimiento'] != 0) echo "block;"; else echo "none;"; ?>">Lo que proceda con premios :<input type="checkbox" name="premio" value="1" <? if($dato['premio'] == 1 ) echo "checked"; ?> /><br /><br></div>

                            <div id="no_premio" style="display:<? if($dato['movimiento'] != 0) echo "block;"; else echo "none;"; ?>">No afectar premio:<input type="checkbox" name="no_premio" value="1" <? if($dato['no_premio'] == 1 ) echo "checked"; ?> /><br /><br></div>

                            <div id="puntualidad" style="display:<? if($dato['movimiento'] != 0) echo "block;"; else echo "none;"; ?>">Afecta puntualidad :<input type="checkbox" name="puntualidad" value="1" <? if($dato['puntualidad'] == 1 ) echo "checked"; ?> /><br /><br></div>

                            <div id="productividad" style="display:<? if($dato['movimiento'] != 0) echo "block;"; else echo "none;"; ?>">Afecta productividad:<input type="checkbox" name="productividad" value="1" <? if($dato['productividad'] == 1 ) echo "checked"; ?> /><br /><br></div>

                            <div id="dia" style="display: <? if($dato['movimiento'] != 9 && $dato['movimiento'] != 12) echo "block;"; else echo "none;"; ?>">Fecha: <input type="text" name="dia" value="<? if($nuevo) echo date('d/m/Y'); else if($modificar && ($dato['movimiento'] != 9 &&  $dato['movimiento'] != 12 )) echo fecha($dato['dia']); ?>" ><br /><br></div>

                        	<div id="horas" style="display:<? if($dato['movimiento'] == 20 || $dato['movimiento'] == 10 || $dato['movimiento'] == 2 || $dato['movimiento'] == 3 || $dato['movimiento'] == 7) echo "block;"; else echo "none;"; ?>">Hora: <input type="text" name="horas" value="<? if($nuevo) echo "00:00"; else if($dato['movimiento'] == 20 ||$dato['movimiento'] == 10 || $dato['movimiento'] == 2 || $dato['movimiento'] == 3 || $dato['movimiento'] == 7) echo $dato['horas']?>"><br><br /></div>

                        	<div id="numero_dias" style="display:<? if($dato['movimiento'] == 9 || $dato['movimiento'] == 8 || $dato['movimiento'] == 20) echo "block;"; else echo "none;"; ?>">Numero de dias	: <input type="text" name="numero_dias" id="numero_dias" value="<? if($dato['movimiento'] == 9 || $dato['movimiento'] == 8 || $dato['movimiento'] == 20 || $modificar) echo $dato['numero_dias']?>"><br><br /></div>

                            <div id="cantidad" style="display:<? if($dato['movimiento'] == 11 || $dato['movimiento'] == 20) echo "block;"; else echo "none;"; ?>">Cantidad: $<input type="text" name="cantidad" value="<? if($nuevo) echo "0.00"; else if($dato['movimiento'] == 11) echo $dato['cantidad']?>" ><br><br /></div>

                        	<div id="desde" style="display:<? if($dato['movimiento'] == 9 || $dato['movimiento'] == 8 || $dato['movimiento'] == 20) echo "block;"; else echo "none;"; ?>">Desde: <input type="text" name="desde" value="<? if($nuevo) echo "dd/mm/aaaa"; else if($modificar && ($dato['movimiento'] == 9 || $dato['movimiento'] == 8 || $dato['movimiento'] == 20) && $dato['desde'] != '0000/00/00') echo fecha($dato['desde']); ?>"   ><br><br /></div>

                            <div id="hasta" style="display:<? if($dato['movimiento'] == 9 || $dato['movimiento'] == 8 || $dato['movimiento'] == 20) echo "block;"; else echo "none;"; ?>">Hasta: <input type="text" name="hasta" value="<? if($nuevo) echo "dd/mm/aaaa"; else if($modificar && ($dato['movimiento'] == 9 || $dato['movimiento'] == 8 || $dato['movimiento'] == 20) && $dato['hasta'] != '0000/00/00') echo fecha($dato['hasta']); ?>"  ><br><br /></div>

                        	<div id="desde_tiempo" style="display:<? if($dato['movimiento'] == 23 || $dato['movimiento'] == 20) echo "block;"; else echo "none;"; ?>">Desde la hora: <input type="text" name="desde_tiempo" value="<? if($nuevo || $modificar) echo "00:00"; else if($modificar && ($dato['movimiento'] == 23  || $dato['movimiento'] == 20) && $dato['desde_tiempo'] != '00:00') echo fecha($dato['desde_tiempo']); ?>"   ><br><br /></div>

                            <div id="hasta_tiempo" style="display:<? if($dato['movimiento'] == 23 || $dato['movimiento'] == 20) echo "block;"; else echo "none;"; ?>">Hasta la hora: <input type="text" name="hasta_tiempo" value="<? if($nuevo || $modificar) echo "00:00"; else if($modificar && ($dato['movimiento'] == 23  || $dato['movimiento'] == 20) && $dato['hasta_tiempo'] != '00:00') echo fecha($dato['hasta_tiempo']); ?>"  ><br><br /></div>



                            <div id="turno" style="display:<? if($dato['movimiento'] == 20 || $dato['movimiento'] == 4 ) echo "block;"; else echo "none;"; ?>">Turno: 

                            								<select name="turno" style="font-weight:bold">

                                                              <? for($z = 0;$z < 4; $z++){?>

                                                            	<option value="<?=$z?>" <? if($modificar && $dato['turno'] == $z) echo "selected";?> ><?=$turnos[$z]?></option>

                                                              <? } ?>

                                                            </select><br><br /></div>

                        	<div id="rol" style="display:<? if($dato['movimiento'] == 20 || $dato['movimiento'] == 5 ) echo "block;"; else echo "none;"; ?>">Rol: 

                            								<select name="rol">

                                                              <? for($y = 0;$y < sizeof($roles); $y++){?>

                                                            	<option value="<?=$y?>" <? if($modificar && $dato['rol'] == $y) echo "selected";?> ><?=$roles[$y]?></option>

                                                              <? } ?>

                                                            </select><br><br /></div>

                            <div id="horario" style="display:<? if($dato['movimiento'] == 20) echo "block;"; else echo "none;"; ?>">Cambio de Horario:<input type="text" name="horario" ><br /><br></div>

                        	<div id="operador" style="display:<? if($dato['movimiento'] == 20 || $dato['movimiento'] == 18 || $dato['movimiento'] == 4) echo "block;"; else echo "none;"; ?>">Operador: <input value="<? if($modificar && ($dato['movimiento'] == 20 || $dato['movimiento'] == 18 || $dato['movimiento'] == 4)) echo $dE2['nombre']?>" type="text" size="40" name="nombre2" id="nombre2" accesskey="4" onKeyPress="buscarOrden('nombre2','resultado2','empleado','id_operador2','tabla');" onKeyUp="buscarOrden('nombre2','resultado2','empleado','id_operador2','tabla');" autocomplete="off" />

                                    										 <input type="hidden" name="id_operador2" id="id_operador2" value="<? if($modificar && $dato['movimiento'] != 0) echo $dato['id_operador2'];?>" />

                                    <br>

									<div id="resultado2">

									</div> <br><br /></div>

							<? } if($mostrar){

								if($dato['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dato['nuevo_movimiento']."<br><br>";

								if($dato['premio'] 			== 1 ) echo "Afecta premio.<br><br>";

								if($dato['puntualidad'] 	== 1 ) echo "Lo que proceda con premios. <br><br>";

								if($dato['productividad'] 	== 1 ) echo "Afecta productividad.<br><br>";

								if($dato['no_premio'] 		== 1 ) echo "No afectar premio.<br><br>";

								if($dato['numero_dias'] 	!= 0 && $dato['movimiento'] == 9) echo "Numero de dias : ".$dato['numero_dias']."<br><br>";

								if($dato['dia'] != "" && $dato['dia'] != "0000-00-00" ){ echo "Dia: "; fecha($dato['dia']); echo "<br><br>"; }

								if($dato['horas'] != ""  && $dato['horas'] != 0) echo "Horas Extra: ".$dato['horas']."<br><br>";

								if($dato['cantidad'] != "" && $dato['cantidad'] != 0 ) echo "Cantidad: $".$dato['cantidad']."<br><br>";

								if($dato['desde'] != "" && $dato['desde'] != "0000-00-00" ){ echo "Desde: "; fecha($dato['desde']); echo "<br><br>";}

								if($dato['hasta'] != "" && $dato['hasta'] != "0000-00-00" ){ echo "Hasta: "; fecha($dato['hasta']); echo "<br><br>";}

								if($dato['desde_tiempo'] != "" && $dato['desde_tiempo'] != "00:00" ){ echo "Desde la hora: "; $dato['desde_tiempo']; echo "<br><br>";}

								if($dato['hasta_tiempo'] != "" && $dato['hasta_tiempo'] != "00:00" ){ echo "Hasta la hora: "; $dato['hasta_tiempo']; echo "<br><br>";}

								if($dato['turno'] != "" && $dato['turno'] != 0 ) echo "Cambio al turno: ".	$dato['turno']. "<br><br>";

								if($dato['rol'] != ""  &&  $dato['rol'] != 0 ) echo "Cambio al Turno: ".$dato['rol']."<br><br>";

								if($dato['horario'] != ""  && $dato['horario'] != 0 ) echo "Cambio de horario: ".$dato['horario']."<br><br>";

								if($dato['id_operador2'] != "" && $dato['id_operador2'] != 0 ){ echo "Se arreglo con: <span style='color:#FF0000'>".$dE2['numnomina']." - ".$dE2['nombre']."</span><br><br>";}

								

							} ?>	

                            </td>

                        </tr>

                                                

                            <tr>

                            	<td align="right" valign="top" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Motivo:</td>

                              <td colspan="5" align="left" valign="top" class="style5"><b>

                              <? if($nuevo || $modificar){ ?>

                              <textarea cols="50" rows="10" name="motivo"><? if($modificar || $dato['motivo'] != "") echo $dato['motivo']; ?></textarea>

                              <? } if($mostrar) { echo nl2br($dato['motivo']); echo "                            <br />

                            <br />

                            <br />

                            <br />"; }?>

                              

                              </b>

                              </td>

                          </tr>

                          <tr>

                          	<td colspan="6" align="center"><br />

                            <br />

                            <? if($nuevo){ 

							if($_REQUEST['almacen'] == 'almacen') 		$almacen	= 1;

							if($_REQUEST['almacen'] == 'produccion') 	$almacen	= 0;

							?><input type="button" name="regresar" value="Regresar" onclick="javascript: history.go(-1);" />

                              <input type="hidden" name="almacen" value="<?=$almacen?>" /> <? }?>

                            <? if($mostrar || $modificar) { ?>

                            	<input type="button" name="regresar" value="Regresar" onclick="javascript: history.go(-1);" />

                                <input type="hidden" name="almacen" value="<?=$dato['almacen']?>" />

                          	<? } if($nuevo || $modificar) {

									if($nSemanaCerrar < 1 || ($nSemanaCerrar > 0 && $modificar_movimientos)){

							?>

                            	<input type="submit" name="guardar" value="<? if($nuevo) {?>Guardar<? } else { ?>Modificar<? } ?>" onclick="genera();" />

                                <? 

									}

									if(isset($_SESSION['id_admin']) && $autoriza_movimiento && ($modificar && $dato['autorizado'] == 0)){?>

                            	<input type="submit" name="actualizar" value="Autorizar" onclick="genera();"/>

								<? } ?>                                

                            <? } ?>

                            <br />

                            <br />

                            <? if($mostrar || $modificar) {?>

                                <input type="button" name="pdf" value="Formato de impresion" onClick="javascript: genera2()" />

							<br />

                            <br />

                            	<input type="hidden" name="<?=$indice?>" value="<?=$dato[$indice]?>" />

                            <? } ?> 

					</td>

                          </tr>

	  </table>

   		  </form>

        </div>

</div>

<? } ?>



<? if($listar){



if($_REQUEST['almacen'] == 'almacen') $query	=	" AND tipo = 1";

if($_REQUEST['almacen'] == 'produccion') $query	=	" AND tipo = 0";





	$qSemanaCerrar	=	"SELECT id_semana FROM semana WHERE semana = ". $_REQUEST['semana_reportes']." AND anio = '".$_REQUEST['anio_mov']."' $query ";

	//echo $qSemanaCerrar;

	$rSemanaCerrar	=	mysql_query($qSemanaCerrar);

	$nSemanaCerrar	=	mysql_num_rows($rSemanaCerrar);

				 ?>

<div align="center">

   	 	<div class="tablaCentrada">

           <form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listar" method="post" >

        	<table width="90%" cellpadding="2" cellspacing="2">

             <tr>

               <td colspan="9" align="center">|<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=nuevo&semana=<?=$_REQUEST['semana_reportes']?>&almacen=<?=$_REQUEST['almacen']?>&anio_mov=<?=$_REQUEST['anio_mov']?>">Realizar Movimiento</a>|<br />

               <br /></td>

             </tr>            

       	    <tr>

               	  <td colspan="10">

                    	<table width="100%">

                        <tr>

                            <td width="13%">Buscar por:</td>

                          <td colspan="7"><select name="semana_reportes" >

<?						if(date('w') < 4) $semana_re = date('W')-1;

						else	$semana_re = date('W');



							

							$qSemanas		=	"SELECT semana FROM pre_nomina_calificada WHERE semana != 0 GROUP BY semana ORDER BY semana ASC";

							$rSemanas	=	mysql_query($qSemanas);	

							while($dSemanas 	=	mysql_fetch_assoc($rSemanas)){

							

						?>

               		  <option value="<?=$dSemanas['semana']?>" <? if( ($_REQUEST['semana_reportes'] == $dSemanas['semana'] )) echo "selected"; ?>>Semana No <?=$dSemanas['semana']?></option>

                         <? $semana = $dSemanas['semana']; } ?>

                         </select>

                       A&ntilde;o : 

                       <? 

					   (isset($_REQUEST['anio_mov']))?$aios = $_REQUEST['anio_mov']:$aios = date('Y');

					   

					   ?>

                       <select name="anio_mov">

                                    <option <?=($aios == '2008')?"selected=\"selected\"":""?> value="2008">2008</option>

                                    <option <?=($aios == '2009')?"selected=\"selected\"":""?> value="2009">2009</option>

                                    <option <?=($aios == '2010')?"selected=\"selected\"":""?> value="2010">2010</option>

                                    <option <?=($aios == '2011')?"selected=\"selected\"":""?> value="2011">2011</option>

                                    <option <?=($aios == '2012')?"selected=\"selected\"":""?> value="2012">2012</option>

                                    <option <?=($aios == '2013')?"selected=\"selected\"":""?> value="2013">2013</option>
                                    
                                    <option <?=($aios == '2014')?"selected=\"selected\"":""?> value="2014">2014</option>
                                    
                                    <option <?=($aios == '2015')?"selected=\"selected\"":""?> value="2015">2015</option>

                                   </select> 

                                   &nbsp;

					   Motivos : <select name="motivos">

                      			<? for($a = 0; $a < sizeof($motivos); $a++){ ?>

                     				<option value="<?=$a?>"><?=$motivos[$a]?></option>

                                 <? }  ?>

                                </select>

                      <input type="hidden" name="almacen" value="<?=$_REQUEST['almacen']?>" />

                         </td>

                            <td width="8%"><input type="submit" name="buscar" value="buscar" /><input type="hidden" name="buscar" value="1" /></td>   

                          <td width="12%" align="right"><? if($nSemanaCerrar < 1 && $prenomina_semana){ ?> <input type="submit" name="cerrar_semana" value="Cerrar Semana" onClick="javascript: return confirm('Est\u00E1 a punto de cerrar la semana de movimientos\n\u00BFEsta completamente seguro que desea realizar este movimiento?');" /><? }	 ?></td>                                

                         </tr>

                        </table>

                	    <br />

               	    <br /></td>

                </tr>

  				<tr>

                	<td colspan="9	" align="center"><span class="style4"><? if($nSemanaCerrar > 0) echo "SEMANA DE MOVIMIENTOS CERRADA"; ?><input type="hidden" name="semana" value="<?=$_REQUEST['semana_reportes']?>" /></span></td>

                </tr>

               	<tr>

               	  <td width="56" align="center"><h3>Area</h3></td>

               	  <td width="50" align="center"><h3>Fecha</h3></td>

                  <td width="65" align="center"><h3>Nomina</h3></td>

                  <td width="234" align="center"><h3>Empleado</h3></td>

                  <td width="186" align="center"><h3>Movimiento</h3></td>

                  <td width="90" align="center"><h3>Autorizado</h3></td>

                  <td colspan="4"><H3>&nbsp;</H3></td>

              </tr>

                <?

		/*

		  $qDelAl	=	"SELECT DAY(desde), DAY(hasta), MONTH(desde), MONTH(hasta), desde, hasta FROM pre_nomina_calificada WHERE semana = ".$_REQUEST['semana_reportes']." AND (YEAR(desde) = '".$aios."' || YEAR(hasta) = '".$aios."' )  GROUP BY semana";

		  $rDelAl	=	mysql_query($qDelAl);

		  $dDelAl	=	mysql_fetch_row($rDelAl);

		  $desdeFec	=	$dDelAl[4];

		  $hastaFec	=	$dDelAl[5];

		  */

			

				$q	=	"SELECT * FROM $tabla INNER JOIN operadores ON $tabla.id_operador = operadores.id_operador ";

				/*

				if(isset($_REQUEST['buscar']))

					$q	.=	" WHERE semana = ".$_REQUEST['semana_reportes']." AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."' ";

				if(!isset($_REQUEST['buscar']))

					$q	.=	" WHERE semana = ".$_REQUEST['semana_reportes']." AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."' ";

				*/

				$q	.=	" WHERE semana = ".$_REQUEST['semana_reportes']." AND YEAR(fecha) = '".$aios."' ";

					  if($_REQUEST['motivos'] != 0)

						$q	.=	"  AND movimiento = {$_POST[motivos]} ";

					  if($_REQUEST['almacen'] == 'almacen')

						$q	.=	"  AND ( area IN(8,9) OR almacen = 1 )";

					  if($_REQUEST['almacen'] != 'almacen')

						$q	.=	"  AND area NOT IN(8,9) AND almacen = 0";

						$q	.=	" ORDER BY area, fecha, numnomina DESC";

						$r		=	mysql_query($q);

						$numR	= 	mysql_num_rows($r);

						//echo $q;

				if($numR > 0){

				for($a = 0; $d = mysql_fetch_assoc($r); $a++){ ?>

                <tr <? if(bcmod($a,2)==0) echo "bgcolor='#DDDDDD'"; else echo "";?>>

                	<td align="center" class="style5"><?=$areasContra[$d['area']]?></td>

                	<td align="center" class="style5"><?=fecha($d['fecha'])?></td>

                    <td class="style5" align="center"><?=$d['numnomina']?></td>

                    <td class="style5"><?=$d['nombre']?></td>

                    <td align="left" class="style5"><?=$motivos[$d['movimiento']]?></td>

                    <td align="center" class="style7"><span style="color:<? if($d['autorizado'] == 0) echo "#000000"; else echo "#FF0000";?>"><b><?=$autorizado[$d['autorizado']]?></b></span></td>

					<? if( $modificar_movimientos || $d['autorizado'] == 0){?>

				    <td width="5"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=modificar&<?=$indice?>=<?=$d[$indice]?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>

            		<? } ?>

                    <td width="5"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=mostrar&<?=$indice?>=<?=$d[$indice]?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>

		          	<? if(($_SESSION['id_supervisor'] && $d['autorizado'] == 0)  || ($modificar_movimientos || $d['autorizado'] == 0 ))  { ?>

                    <td width="5"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=eliminar&<?=$indice?>=<?=$d[$indice]?>&semana_reportes=<?=$_REQUEST['semana_reportes']?>&anio_mov=<?=(isset($_REQUEST['anio_mov'])?$_REQUEST['anio_mov']:date('Y'))?>&almacen=<?=$_REQUEST['almacen']?>" onClick="javascript: return confirm('\u00BFRealmente deseas eliminar a este Movimiento de Personal?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>

			        <? } if($modificar_movimientos) { ?>

                    <td width="5"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=cambio_almacen&<?=$indice?>=<?=$d[$indice]?>&semana_reportes=<?=$_REQUEST['semana_reportes']?>&almacen=<?=$d['almacen']?>&anio_mov=<?=$_REQUEST['anio_mov']?>" onClick="javascript: return confirm('\u00BFDesea mover este movimiento?');">C</a></td>

                    <? } ?>

            </tr>

                <? } ?>

                

                

                

                <? } else {?>

                

                <tr>

                	<td colspan="7" align="center"><span class="style4">NO HAY REPORTES DE MOVIMIENTOS ESTA SEMANA</span></td>

                </tr>

               <? } ?>

            </table><br /><br /><br /><br />

         </form>

       </div>

</div>

<? } ?>



<? if($portada){ ?>

<div align="center">

   	 	<div class="tablaCentrada">

           <form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listar" method="post" >

        	<table width="80%" cellpadding="2" cellspacing="2">

             <tr>

               	<td align="center">

                <? if($movimientos_produccion || isset($_SESSION['id_supervisor'])){?>

                	<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&semana_reportes=<?=$_REQUEST['semana_reportes']?>&almacen=produccion&accion=listar&anio_mov=<?=date('Y')?>" ><img src="images/movproduccion.jpg" alt="MOVIMIENTOS PRODUCCION" border="0" /></a>

                <? } ?>

                </td>

                <td align="center">

                <? if($movimientos_almacen){?>

                	<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&semana_reportes=<?=$_REQUEST['semana_reportes']?>&almacen=almacen&accion=listar&anio_mov=<?=date('Y')?>" ><img src="images/movalmacen.jpg" alt="MOVIMIENTOS ALMACEN" border="0" /></a>

                <? } ?>

                </td>     

             </tr>            

            </table><br /><br /><br /><br />

         </form>

       </div>

</div>

<? } ?>