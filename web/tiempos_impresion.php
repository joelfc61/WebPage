<?php



if(isset($_REQUEST['admindolfra']) && $_REQUEST['accion'] == 'nuevo'){

	$qSupervisor = "SELECT * FROM supervisor WHERE id_supervisor = ".$_POST['id_supervisor']." ORDER BY nombre ASC;";

	$rSupervisor = mysql_query($qSupervisor);

	

	$dSupervisor = mysql_fetch_assoc($rSupervisor); 

	

	$_SESSION['nombre'] 		= $dSupervisor['nombre'];	

	$_SESSION['rol']			= $dSupervisor['rol'];	

	$_SESSION['area'] 			= $dSupervisor['area'];

	$_SESSION['id_supervisor'] 	= $dSupervisor['id_supervisor'];

	$_SESSION['admindolfra'] 	= 'admin';



	

}



function pDebug($str)

{

	global $debug;

	if($debug)

		echo $str . "\n";

}



$tabla	=	"maquina";

$tabla2 =	"supervisor";

$tabla3 =	"operador";



$indice		=	"id_maquina";

$indice2	=	"id_supervisor";

$indice3	=	"id_operador";



$debug = false;



//if($debug)

//	echo "<pre>";



if(isset($_POST['guardar']))

{

	$_POST['fecha']	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

	$fallo_general	=	$_POST['fallo_general'];



 	$qGeneral	=	"INSERT INTO entrada_general (id_supervisor,fecha,turno,autorizada, area,actualizado,impresion,rol) VALUES ('{$_POST[id_supervisor]}','{$_POST[fecha]}','{$_POST[turno]}','1','{$_POST[area]}','0','1','{$_POST[rol]}')";

	pDebug($qGeneral);

	$rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");

	

	$qIdMaxEg	=	"SELECT MAX(id_entrada_general) FROM entrada_general";

	$rIdMaxEg	=	mysql_query($qIdMaxEg);

	$dIdMaxEg	=	mysql_fetch_row($rIdMaxEg);

 	$id_gen		=	 $dIdMaxEg[0];

	

 	$qImpresion		=	"INSERT INTO impresion (id_entrada_general, total_hd, total_bd, desperdicio_hd, desperdicio_bd, observaciones, k_h) VALUES ".

						"('$id_gen','{$_POST[total_impr_hd]}','{$_POST[total_impr_bd]}','{$_POST[total_desperdicio_hd]}','{$_POST[total_desperdicio_bd]}','{$_POST[observaciones_impr]}', '{$_POST[k_h]}')";

	pDebug($qImpresion);

	$rImpresion		=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");



	$qIdMaxOp	=	"SELECT MAX(id_impresion) FROM impresion";

	$rIdMaxOp	=	mysql_query($qIdMaxOp);

	$dIdMaxOp	=	mysql_fetch_row($rIdMaxOp);	

	$id_pro	=	 $dIdMaxOp[0];

	

	$fecha	=	$_REQUEST['fecha'];

	 $turno	=	$_REQUEST['turno'];	

	

	

	/* Comienza Impresión */

	$nMaquinas	=	sizeof($_POST['codigos2']);

	

	for($i=0;$i<$nMaquinas;$i++)

	{

		$ID_GENERAL			=	$id_pro;

		

		$sufijo				=	$_POST['codigos2'][$i];

		$id_maquina			=	intval($_POST['id_maquina2_'.$sufijo]);

		$id_operador		=	intval($_POST['id_operador2_'.$sufijo]);

		

		

		if($_POST['fp_impr'.$sufijo] == 1){

			if($_REQUEST['turno'] == '1') $falta_personal = "08:00:00"; 

			if($_REQUEST['turno'] == '2') $falta_personal =	"07:00:00"; 

			if($_REQUEST['turno'] == '3') $falta_personal =	"09:00:00";	

		}

				

		if($_POST['fp_impr'.$sufijo] != 1){

		 $falta_personal		=	"00:00:00";

		}	

		

		if($_POST['ci_impr'.$sufijo] == 1){

		$cambio_impresion	= 1;

		}	

		if($_POST['ci_impr'.$sufijo] != 1){

		$cambio_impresion	= 0;

		}	

		if($_REQUEST['fallo_general'] == 1){

		$horas 		= 	$_POST['horas_fallo'];

		$minutos	=	$_POST['minutos_fallo'];

		$fallo_electrico 	=	$horas.':'.$minutos.':00';

		} else {

									

	 	$fallo_electrico	=	'00:00:00';

		}

		$mantenimiento		=	$_POST['mh_impr'.$sufijo].':'.$_POST['mm_impr'.$sufijo].':00';

		$observacion		=	$_POST['ob_impr'.$sufijo];

		$otras				=	$_POST['oh_impr'.$sufijo].':'.$_POST['om_impr'.$sufijo].':00';



		/* La segunda entrada es para impresión y mandamos linea_impresion = 0  */

		$qImpresion			=	"INSERT INTO tiempos_muertos (id_produccion, id_maquina, id_operador, falta_personal, observaciones, fallo_electrico, mantenimiento, cambio_impresion, tipo,otras) VALUES ".

								"('$ID_GENERAL','$id_maquina',  '$id_operador','$falta_personal', '$observacion','$fallo_electrico','$mantenimiento', '$cambio_impresion','2','$otras')";

		pDebug($qImpresion);

		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");

		$ID_RES_MAQUINAIM	=	mysql_insert_id();

		

	}



	/* -- Termina Impresión -- */

	/* Comienza parte líneas de impresión */

	$nMaquinas	=	sizeof($_POST['codigos3']);

	

	for($i=0;$i<$nMaquinas;$i++)

	{

		$ID_GENERAL			=	$id_pro;

		

		$sufijo				=	$_POST['codigos3'][$i];

		$id_maquina			=	intval($_POST['id_maquina3_'.$sufijo]);

		$id_operador		=	intval($_POST['id_operador3_'.$sufijo]);



		if($_POST['fp_limpr'.$sufijo] == 1){

			if($_REQUEST['turno'] == '1') $falta_personal = "08:00:00"; 

			if($_REQUEST['turno'] == '2') $falta_personal =	"07:00:00"; 

			if($_REQUEST['turno'] == '3') $falta_personal =	"09:00:00";	

		}



		if($_POST['fp_limpr'.$sufijo] != 1){

		$falta_personal		=	"00:00:00";

		}		

		

		if($_POST['ci_limpr'.$sufijo] == 1){

		$cambio_impresion	= 1;

		}	

		if($_POST['ci_limpr'.$sufijo] != 1){

		$cambio_impresion	= 0;

		}			

		

		if($_REQUEST['fallo_general']  == 1){

		$horas 	= $_POST['horas_fallo'];

		$minutos	=	$_POST['minutos_fallo'];

		$fallo_electrico 	=	$horas.':'.$minutos.':00';

		} else {

				

		$fallo_electrico		=	'00:00:00';

		}

		$otras					=	$_POST['oh_limpr'.$sufijo].':'.$_POST['om_limpr'.$sufijo].':00';

		$mantenimiento			=	$_POST['mh_limpr'.$sufijo].':'.$_POST['mm_limpr'.$sufijo].':00';

		$observacion			=	$_POST['ob_limpr'.$sufijo];

		

		/* La segunda entrada es para líneas de impresión y mandamos linea_impresion = 1  */

		$qImpresion			=	"INSERT INTO tiempos_muertos (id_produccion, id_maquina, id_operador,falta_personal, observaciones, fallo_electrico, mantenimiento, cambio_impresion, tipo,otras) VALUES ".

								"('$ID_GENERAL','$id_maquina','$id_operador','$falta_personal', '$observacion', '$fallo_electrico','$mantenimiento', '$cambio_impresion','3','$otras')";

		pDebug($qImpresion);

		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");

		$ID_RES_MAQUINAIM	=	mysql_insert_id();

		

		$nEntradas			=	sizeof($_POST["ot_limpresion".$sufijo]);

		$qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_im (id_resumen_maquina_im,orden_trabajo,kilogramos) VALUES ";



	}

	

	/* -- Termina parte de Impresión */



	pDebug("Terminado");

	pDebug("Comienza volcado de la petición");



	 if(isset($_SESSION['admindolfra']) ){ 

	echo '<script language="javascript">location.href=\'admin.php?seccion=20&accion=nuevo&id_entrada_general='.$id_gen.'&id_impresion='.$id_pro.'&turno='.$turno.'&fecha='.$fecha.'\';</script>';

	} 

	if(!isset($_SESSION['admindolfra']) ){ 

		echo '<script language="javascript">location.href=\'admin_supervisor.php?seccion=21&accion=nuevo&id_entrada_general='.$id_gen.'&id_impresion='.$id_pro.'&turno='.$turno.'&fecha='.$fecha.'\';</script>';

	} 	   



}



if(!empty($_GET['accion']))

{

	$nuevo		= ($_GET['accion']=="nuevo"	)?true:false;

	$listar		= ($_GET['accion']=="listar")?true:false;

	$supervisor	= ($_GET['accion']=="supervisor")?true:false;

	

	if(!empty($_GET['id_util']) && is_numeric($_GET['id_util']) )

	{

		$mostrar	= ($_GET['accion']=="mostrar"	)?true:false;

		$modificar	= ($_GET['accion']=="modificar"	)?true:false;

		$eliminar	= ($_GET['accion']=="eliminar" && valida_root())?true:false;

		$traduccion	= ($_GET['accion']=="traduccion")?true:false;

	}



}

if($nuevo)

{

	$nEntradas	=	4;

	

}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" version=

"-//W3C//DTD XHTML 1.1//EN" xml:lang="en">

<head>

	<script type="text/javascript" src="mootools.js"></script>

	<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />

	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>Bolseo</title>

	<style type="text/css" media="screen">@import 'style.css';</style>

</head>

<body>

<? if($supervisor){?>

<form name="super" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=nuevo&id=<?=$_REQUEST['id']?>" id="super" method="post" >

<table width="404"  align="center">

	<? if(isset($_SESSION['id_admin'])){?>

  <tr>

		<td width="157" align="right">Elija un Supervisor:</td>

		<td width="235" align="left"><select name="id_supervisor" id="supervisor">

    		<? 

						$qSupervisor = "SELECT * FROM supervisor WHERE area2 = 1 AND activo = 0 ORDER BY nombre ASC;";

						$rSupervisor = mysql_query($qSupervisor);

				

						while($dSupervisor = mysql_fetch_assoc($rSupervisor)){?>

    						<option value="<?=$dSupervisor['id_supervisor']?>"><?=$dSupervisor['nombre']?></option>

            <? } ?>

       					</select></td>

	  <input type="hidden" name="admindolfra">

                       

	</tr>

   <?  }			 ?>

    <tr>

    	<td align="right">Turno</td>

<?		

 $dias_diferencia = '0';



 $fecha			= date('d/m/Y');	

 $turno 		= $_SESSION['rol'];



$revisa 	= explode("/",$fecha);

$compara 	= explode("/","01/12/2007");



$ano1 = $revisa[2];

$mes1 = $revisa[1];

$dia1 = $revisa[0];



$ano2 = $compara[2];

$mes2 = $compara[1];

$dia2 = $compara[0];



 $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);

 $timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2);





 $segundos_diferencia = $timestamp1 - $timestamp2;

 $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);



		$a = 0;

		do{



				if($a == 0)

				{ 

				    $encuentra[$a]  = $turno ;

					$a = $a ;

				}

										

								

							if($a <= $dias_diferencia && $turno == 1 ){	

									for($b = 0 ; $b < 2 ; $b++){

										$encuentra[$a+$b]  = $turno+1 ;

										$a = $a + $b ;		

									}

									$turno =  $turno + 1 ;	

									$a = $a+1;

							}  

						

							if( $a <= $dias_diferencia && $turno == 2 ){	

									for($c = 0 ; $c < 2 ; $c++){

										$encuentra[$a+$c]  = $turno +1 ;

										$a = $a + $c ;		

									}

									$turno =  $turno + 1 ;	

									$a = $a+1;

							}

								

							if($a <= $dias_diferencia && $turno == 3 ){	

									for($d = 0 ; $d < 2	 ; $d++){

									 	$encuentra[$a+$d]  = $turno+1 ;	

										$a = $a + $d ;										



									}

								$turno =  $turno +1 ;	

								$a = $a+1;

							}

							

							if($a <= $dias_diferencia && 	$turno == 4 ){	

								$turno = 0;

									for($e = 0 ; $e < 2	 ; $e++){

									 	$encuentra[$a+$e]  = $turno ;

										$a = $a + $e ;									

									}

								$turno =  $turno +1;

								$a = $a+0 ;		

							}						

				$a++;

			

			}  while($a < $dias_diferencia)	;	

			

			

		

	//	echo array_values($encuentra);

   $dias_diferencia;

   $dias_diferencia = $dias_diferencia - 2;

   $encuentra[$dias_diferencia];



?>        

        <td align="left"><select name="turno" id="turno" class="datosgenerales" >

                <option value="1" <? if($encuentra[$dias_diferencia] == 1) echo "Selected";?> >Matutino</option>

                <option value="2" <? if($encuentra[$dias_diferencia] == 2) echo "Selected";?> >Vespertino</option>

                <option value="3" <? if($encuentra[$dias_diferencia] == 3) echo "Selected";?> >Nocturno</option>

              </select> <br /></td>

    </tr>

    <tr>

    	<td align="right">Fecha:</td>

        <td align="left"><input type="text" name="fecha" class="fecha" value="<?=date('d/m/Y');?>" /></td>

	</tr>

	<tr>
        <td> </td>   
		<td colspan="2" align="right"><input type="submit" value="Aceptar" /></td>

    </tr>

</table>

<br />

<br />

</form>

<?php } if($nuevo) { 



	$fecha	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);





$qValidacion = "SELECT * FROM entrada_general WHERE fecha = '".$fecha."' AND turno = '".$_REQUEST['turno']."' AND area=3";

$rValidacion = mysql_query($qValidacion);

$nValidacion = mysql_num_rows($rValidacion);





	if($nValidacion  >= 1  && isset($_SESSION['admindolfra'])){

	

		echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=registrado\';</script>';



	}

	if($nValidacion  >= 1  && !isset($_SESSION['admindolfra'])){

	

		echo '<script languaje="javascript">location.href=\'admin_supervisor.php?seccion=21&accion=registrado\';</script>';



	}

	



?>

<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

<div id="container">

  <div id="content">

		<?php if($nuevo) { ?>

		<div id="datosgenerales" style="background-color:#FFFFFF;" align="left">

			<p>

				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$_SESSION['nombre']?>" readonly="readonly" class="datosgenerales"/><br />

				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=$_REQUEST['fecha']?>" id="fecha" class="datosgenerales" readonly="readonly" /><br />

 <label for="turno_bla">Turno</label>

                 <input type="text" name="turno_bla" id="turno_bla" class="datosgenerales" value="<? if($_REQUEST['turno'] == '1') echo "Matutino";

																							 if($_REQUEST['turno'] == '2') echo "Vespertino";

																							 if($_REQUEST['turno'] == '3') echo "Nocturno";?>" readonly="readonly">

       <br /><br /><br />

       			<h1><input type="checkbox" name="fallo_general" value="1" onclick="javascript: muestra(100);" />Fallo General Electrico</h1>

                <div class="style5" id="div_100" style="display:none; float:left">Tiempo de fallo: <input type="text" name="horas_fallo" size="2" />:<input type="text" name="minutos_fallo" size="2" />Hrs.<br />

                </div>

       			<input type="hidden" name="turno" value="<?=$_REQUEST['turno']?>" id="turno" />

                

                <input type="hidden" name="id_supervisor"  value="<?=$_SESSION['id_supervisor']?>" />

                <input type="hidden" name="area" id="area" value="3" />

                <input type="hidden" name="rol" id="rol" value="<?=$_SESSION['rol']?>">

               



		  </p>

    	<br />

    		<p class=" titulos_reportes">IMPRESI&Oacute;N</p>

   		  <p align="center">          

           

		<?  

		 	$sql_lic= "  SELECT * FROM maquina  WHERE maquina.area = 2  ORDER BY numero ASC  ";

			$res_lic=mysql_query($sql_lic);

			$cant_lic=mysql_num_rows($res_lic);

			$cant=ceil($cant_lic/3);

			$a=0;

			while($dat_extr = mysql_fetch_assoc($res_lic))

			{

				$codigo[$a]=$dat_extr['numero'];

				$id[$a] 	= $dat_extr['id_maquina'];

				$a++;

			}

			$reg=0;

			for($i=0;$i<$cant;$i++)

{

?>

<table width="100%" align="center">

<tr>

    <? for($x=1;$x<=4; $x++){?>

<td width="190px" align="left" valign="top">

  <? if($reg<$cant_lic){ ?>			 

		<?

		

		    $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

			$rOperadorextr = mysql_query($qOperadorextr);

			$dAsignacionImpr = mysql_fetch_assoc($rOperadorextr);?>

            

		<input type="hidden"  name="id_operador2_<?=$codigo[$reg]?>" value="<?=$dAsignacionImpr['id_operador'] ?>" />

        <input type="hidden" name="id_maquina2_<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />

        <input type="hidden" name="codigos2[]" value="<?=$codigo[$reg]?>" />

		

    <h3 align="left" style="color: rgb(255, 255, 255);" onclick="muestra_impr('<?=$codigo[$reg]?>','impresion');"  class="Tips4" title="Click aqui para abrir o cerrar::">FLEXO <?=$codigo[$reg]?> </h3>

	<div id="impresion<?=$codigo[$reg]?>" style="display:  ; border:1px solid #ccc; height:100%; padding:8px 0px">

	<table width="120" border="1">

					<tr>

						<td><strong>Obser.</strong></td>

						<td colspan="3"><textarea name="ob_impr<?=$codigo[$reg]?>" id="ob_impr<?=$codigo[$reg]?>" cols="10" rows="3" /></textarea></td>

                    </tr>

                    <tr>

                        <td align="left" class="style7">Cambio de impresi&oacute;n:</td>

                        <td><input type="checkbox" value="1" name="ci_impr<?=$codigo[$reg]?>" /></td>

				  	</tr>

<!--                   	<tr>

           	  			<td align="left" class="style7">Falta de Personal:</td>

                		<td width="114"><input type="checkbox" name="fp_impr<?=$codigo[$reg]?>" value="1" /></td>

                  </tr>

-->                    <tr>

                        <td width="103" align="left" class="style7">Otras causas:</td>

                        <td width="163"><input type="text" name="oh_impr<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" />:

                        				<input type="text" name="om_impr<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" /></td>

            		</tr>                  

             		<tr>

           	  			<td align="left" class="style7">Mannto.:</td>

               			<td width="114"><input type="text" name="mh_impr<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" />:

                        				<input type="text" name="mm_impr<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" /></td>

                  </tr>

		  </table>

          </div>

            

  <? $reg++;}?></td><? }?>

</tr>

</table>

  <? }?>

  </p>    



        <br /><br /><br />

		    		<p class="titulos_reportes">LINEA DE IMPRESI&Oacute;N</p>

   		  <p align="center">          

           

		<?  

		 	$sql_lic2= "  SELECT * FROM maquina  WHERE maquina.area = 3  ORDER BY numero ASC  ";

			$res_lic2=mysql_query($sql_lic2);

			$cant_lic2=mysql_num_rows($res_lic2);

			$cant2=ceil($cant_lic2/3);

			$a2=0;

			while($dat_extr2 = mysql_fetch_assoc($res_lic2))

			{

				$codigo2[$a2]=$dat_extr2['numero'];

				$id2[$a2] 	= $dat_extr2['id_maquina'];

				$a2++;

			}

			$reg2=0;

			for($i2=0;$i2<$cant2;$i2++)

{

?>

<table width="100%">

<tr>

    <? for($x2=1;$x2<=4; $x2++){?>

<td width="25%" align="left" valign="top"><br />

  <? if($reg2<$cant_lic2){ 

  

		    $qOperadorextr2 = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id2[$reg2]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

			$rOperadorextr2 = mysql_query($qOperadorextr2);

			$dAsignacionLimp = mysql_fetch_assoc($rOperadorextr2);?>

            

	 	<input type="hidden"  name="id_operador3_<?=$codigo2[$reg2]?>" value="<?=$dAsignacionLimp['id_operador'] ?>" />

        <input type="hidden" name="id_maquina3_<?=$codigo2[$reg2]?>" value="<?=$id2[$reg2]?>" />

        <input type="hidden" name="codigos3[]" value="<?=$codigo2[$reg2]?>" />

		<h3 align="left" style="color: rgb(255, 255, 255);"  onclick="muestra_impr('<?=$codigo2[$reg2]?>','linea');">LINEA <?=$codigo2[$reg2]?> 

		  <br />

		</h3>

<div id="linea<?=$codigo2[$reg2]?>" style="display:none">

<table width="120" height="174" >

<thead>

					<tr>

					<tr>

						<td><strong>Observaciones</strong></td>

						<td colspan="3"><textarea  class="observaciones" name="ob_limpr<?=$codigo2[$reg2]?>" id="ob_limpr<?=$codigo2[$reg2]?>" cols="12" rows="3"  /></textarea></td>

                    </tr>

                    <tr>

           	  			<td align="left" class="style7">Cambio de impresi&oacute;n:</td>

                        <td width="163"><input type="checkbox" name="ci_limpr<?=$codigo2[$reg2]?>" value="1"/></td>

				  </tr>

<!--                  <tr>

                        <td align="left" class="style7">Falta de Personal:</td>

                        <td width="185"><input type="checkbox" name="fp_limpr<?=$codigo2[$reg2]?>" value="1" onclick="mostrar2('<?=$codigo2[$reg2]?>')" /></td>

                    </tr>

-->                    <tr>

                        <td width="103" align="left" class="style7">Otras causas:</td>

                        <td width="163"><input type="text" name="oh_limpr<?=$codigo2[$reg2]?>" size="2" maxlength="2" value="00" />:

                        				<input type="text" name="om_limpr<?=$codigo2[$reg2]?>" size="2" maxlength="2" value="00" /></td>

            		</tr>

             		<tr>

           	  			<td align="left" class="style7">Mantenimiento:</td>

                		<td width="185"><input type="text" name="mh_limpr<?=$codigo2[$reg2]?>" size="2" maxlength="2" value="00" />:

                        				<input type="text" name="mm_limpr<?=$codigo2[$reg2]?>" size="2" maxlength="2" value="00" /></td>

                    </tr>

				</thead>

		  </table>

  </div>          

  <? $reg2++;}?>

  <br /></td><? }?>

</tr>

</table>

  <? }?>



</p>

              

                  

        <div id="datosgenerales" style="background-color:#FFFFFF;">

          <table width="642" align="center">

<tbody>

		  </tbody>

        </table>

        <br />

        <br />

  </div>

<div id="barraSubmit" style="background-color:#FFFFFF; text-align:right;">

<input type="submit" name="guardar" value="Guardar" onclick="javascript: return confirm('Usted está a punto de pasar a la siguiente \netapa de registro de produccion, si desea realizar cambios seleccione cancelar,\npara continuar dar click en aceptar.');" />

  </div>

		<?php } ?>

		<?php if($nuevaEntrada) { ?>

		<div class="tablaCentrada">

			<p>Se registraron los datos en el sistema.</p>

		</div>

		<?php } ?>

  </div>

		</div></div>

</form>

<?php }  ?>

<?php if($nuevaEntrada) { ?>

<table align="center" width="100%">

<tr>

	<td align="center">

		<div class="tablaCentrada">

			<p><br />

			  <br />

		    Se registraron los datos en el sistema.<br />

			<br />

			<br />

			</p>

			

		</div>

    </td>

</tr>

</table>

<?php } ?>

</body></html>



