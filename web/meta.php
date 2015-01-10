<? 



$tabla	=	"meta";

$tabla2 =	"metas_maquinas";



$indice		=	"id_meta";

$indice2	=	"id_meta_diaria";



$debug = false;



if($debug)

	echo "<pre>";

// envío a Captura metas  produccion, accion que hace el boton

echo '<script language="javascript">location.href=\'admin.php?seccion=46\';</script>';


if(isset($_POST['guardar']))

{

		

 	$qGeneral	=	"INSERT INTO meta (area,mes,ano,total_hd,total_dia, desp_duro_hd, desp_duro_bd, troquel,porcentaje_desp,meta_mes_millar,meta_mes_kilo,mes_troquel,mes_tira,mes_segunda,porcentaje_troquel,porcentaje_segunda,id_genera_meta,1) ".

					"VALUES ('{$_POST[area]}','{$_POST[mes]}','{$_POST[ano]}','{$_POST[total_hd]}','{$_POST[meta_total]}', ".

					"'{$_POST[desp_duro_hd]}','{$_POST[desp_duro_bd]}','{$_POST[troquel]}','{$_POST[porcentaje_desp]}','{$_POST[meta_mes_millar]}','{$_POST[meta_mes_kilo]}','{$_POST[mes_troquel]}','{$_POST[mes_tira]}','{$_POST[mes_segunda]}','{$_POST[porcentaje_troquel]}','{$_POST[porcentaje_segunda]}')";

	pDebug($qGeneral);

	$rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");

	$ID_GENERAL	=	mysql_insert_id();

			

	$nMaquinas	=	sizeof($_POST['codigos']);

	

	for($i=0;$i<$nMaquinas;$i++)

	{



		$sufijo		=	$_POST['codigos'][$i];

		$diaria		=	floatval($_POST['nrmetap'.$sufijo]);

		$turnos		=	floatval($_POST['turnos_'.$sufijo]);

		$dias		=	floatval($_POST['dias_'.$sufijo]);

		$total		=	floatval($_POST['meta_mes_'.$sufijo]);

		$id_maquina	=	intval($_POST['id_maquina'.$sufijo]);

		$areas		= 	intval($_POST['areas'][$i]);

		

		$qImpresion			=	"INSERT INTO $tabla2 (id_meta, id_maquina, diaria, turnos, dias,total,area) VALUES ".

								"('$ID_GENERAL','$id_maquina','$diaria','$turnos','$dias','$total','$areas')";

								

		pDebug($qImpresion);

		

		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");

	}



	pDebug("Terminado");

	pDebug("Comienza volcado de la petición");



	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=15\';</script>';



}





if(isset($_POST['actualizar']))

{



 	$qGeneral	=	"UPDATE meta SET area = '$_POST[area]',mes = '$_POST[mes]' ,ano = '$_POST[ano]' ,total_hd = '$_POST[total_hd]'".

					" ,total_dia = '$_POST[meta_total]' , desp_duro_hd = '$_POST[desp_duro_hd]', desp_duro_bd = '$_POST[desp_duro_bd]' , troquel = '$_POST[troquel]' ,porcentaje_desp = '$_POST[porcentaje_desp]',meta_mes_millar='".$_POST['meta_mes_millar']."',meta_mes_kilo='".$_POST['meta_mes_kilo']."'".

					",mes_tira='".$_POST['mes_tira']."', mes_troquel='".$_POST['mes_troquel']."', mes_segunda='".$_POST['mes_segunda']."', porcentaje_troquel = '$_POST[porcentaje_troquel]' , porcentaje_segunda = '$_POST[porcentaje_segunda]' WHERE id_meta = ".$_REQUEST['id_meta']." ";

	pDebug($qGeneral);

	$rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");

	$ID_GENERAL	=	mysql_insert_id();

			

	$nMaquinas	=	sizeof($_POST['codigos']);

	

	for($i=0;$i<$nMaquinas;$i++)

	{



		$sufijo		=	$_POST['codigos'][$i];

		$id_meta_diaria = 	$_POST['id_meta_diaria'][$i];

		$diaria		=	floatval($_POST['nrmetap'.$sufijo]);

		$turnos		=	floatval($_POST['turnos_'.$sufijo]);

		$dias		=	floatval($_POST['dias_'.$sufijo]);

		$total		=	floatval($_POST['meta_mes_'.$sufijo]);

		$id_maquina	=	intval($_POST['id_maquina'.$sufijo]);

		$areas		= 	intval($_POST['areas'][$i]);

		

		$qImpresion			=	"UPDATE $tabla2 SET  id_maquina = '$id_maquina', diaria = '$diaria', turnos = '$turnos' , dias = '$dias' ,total = '$total' ,area = '$areas'".

								" WHERE id_meta_diaria = '".$id_meta_diaria."'";

		pDebug($qImpresion);

		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");

	}

	

	pDebug("Terminado");

	pDebug("Comienza volcado de la petición");



	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=15\';</script>';



}



if(isset($_POST['acepta_meta']))

{



	

 	$qGeneral	=	"UPDATE meta SET id_genera_meta = '1' WHERE id_meta = ".$_REQUEST['id_meta']." ";

	pDebug($qGeneral);

	$rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");

	$ID_GENERAL	=	mysql_insert_id();

			



	pDebug("Terminado");

	pDebug("Comienza volcado de la petición");



	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=15\';</script>';



	



}



$listar =	true;

if(!empty($_GET['accion']))

{

	$listar		=	($_GET['accion']=="listar")?true:false;

	$nuevo		=	($_GET['accion']=="nuevo")?true:false;

	$area_s		= 	($_GET['accion']=="area")?true:false;

	if(!empty($_GET[$indice]) && is_numeric($_GET[$indice]) )

	{

		$desgloze	=	($_GET['accion']=="desgloze")?true:false;

		$mostrar	=	($_GET['accion']=="mostrar")?true:false;

		$eliminar	=	($_GET['accion']=="eliminar")?true:false;

		$modificar	=	($_GET['accion']=="modificar")?true:false;

				

	}

	

	if($mostrar || $modificar)

	{

		$query_dato	=	"SELECT * FROM $tabla WHERE ($indice={$_GET[$indice]})";

		$res_dato	=	mysql_query($query_dato) OR die("<p>$query_dato</p><p>".mysql_error()."</p>");

		$dato		=	mysql_fetch_assoc($res_dato);

		

	}

	

	if($eliminar)

	{

		$q_eliminar	=	"DELETE FROM $tabla WHERE ($indice={$_GET[$indice]})";

		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");

		$redirecciona	=	true;

		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";

	

	}

	if($nuevo)

	{



		$query = 'SELECT MAX(id_meta) AS last_id FROM meta WHERE area = '.$_POST['area'].'';

   		$result = mysql_query($query);

   		$result = mysql_fetch_array($result);

   		$id = $result[last_id];

		

		if($_POST['area'] == 2){

		$querylimp = 'SELECT MAX(id_meta) AS last_id FROM meta WHERE area = 3';

   		$resultlimp = mysql_query($querylimp);

   		$resultlimp = mysql_fetch_array($resultlimp);

   		$id_limp = $resultlimp[last_id];		

		

		

		}

	



		if($id > 0){

	 	$query_dato	=	"SELECT * FROM $tabla WHERE ($indice={$id})";

		$res_dato	=	mysql_query($query_dato) OR die("<p>$query_dato</p><p>".mysql_error()."</p>");

		$dato		=	mysql_fetch_assoc($res_dato);	

		

		}

		else

		{

		

		}

	}

	

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

	<style type="text/css" media="screen">

	@import 'style.css';

	</style>

</head>



<body <? if($nuevo || $modificar) { ?>onload="multiplika_todo();" <? } ?> >

<div id="container">

  <div id="content">

<? if($area_s){ ?>

<script language="javascript">

function valida()

{

 if(document.form.mes.value<<?=date('m');?> && document.form.ano.value<=<? echo date('Y');?>)

 {

   document.form.mes.value == <?=date('n')?>;

  alert("La fecha asignada a la meta debe ser igual o mayor a la fecha de hoy");

  return false;

 }

 else

  document.form.submit();

}

</script>

<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=nuevo" name="form" onsubmit=" return valida(this);" method="post" >

<div id="datosgenerales" style="background-color:#FFFFFF;"> <br />

<table width="368" class="tablaCentrada">

<tr>

	<td width="87" align="left" class="style7">Area :</td>

    <td width="269" colspan="2" align="left" class="style5">

    	<select name="area" class="style5">

        	<? for($o = 1 ; $o <= 3; $o++){ ?>

        	<option value="<?=$o?>" <? if($dato['area'] == $o ) echo "selected" ?> >

			<? 

			if($o == 1) echo "Extruder";

			if($o == 2) echo "Impresion";

			if($o == 3) echo "Bolseo";

			

			?></option>

            <? } ?>

   </select>

   </td>

   </tr>

   <tr>

	<td class="style7" align="left">Mes :</td>

	<td colspan="2" align="left" class="titulos">

	<select name="mes" class="style5" id="mes" >

	<option value="1" 	<? if(date('m') == 1)echo "selected"; ?>>Enero</option>

	<option value="2" 	<? if(date('m') == 2)echo "selected"; ?>>Febrero</option>

	<option value="3" 	<? if(date('m') == 3)echo "selected"; ?>>Marzo</option>

	<option value="4" 	<? if(date('m') == 4)echo "selected"; ?>>Abril</option>

	<option value="5" 	<? if(date('m') == 5)echo "selected"; ?>>Mayo</option>

	<option value="6" 	<? if(date('m') == 6)echo "selected"; ?>>Junio</option>

	<option value="7" 	<? if(date('m') == 7)echo "selected"; ?>>Julio</option>

	<option value="8" 	<? if(date('m') == 8)echo "selected"; ?>>Agosto</option>

	<option value="9" 	<? if(date('m') == 9)echo "selected"; ?>>Septiempre</option>

	<option value="10" 	<? if(date('m') == 10) echo "selected"; ?>>Octubre</option>

	<option value="11" 	<? if(date('m') == 11) echo "selected"; ?>>Noviembre</option>

	<option value="12" 	<? if(date('m') == 12) echo "selected"; ?>>Diciembre</option>

    </select></td>

</tr>

<tr>

  <td class="style7" align="left">Ano :</td>

  <td colspan="2" align="left" class="titulos">

  <select name="ano" class="style5" id="ano">

    <? for($i=date('Y');$i<=date('Y')+2 ;$i++) { ?>

    <option value="<? echo $i?>"><? echo $i?></option>

	<?  }?>

  </select></td>

</tr>

   <tr>

   		<td colspan="2" align="right"><input type="submit" name="enviar" value="Siguiente" /><br /><br /></td>

   </tr>

   

</table>

  <br />

</div>

</form><? }  ?>

<script language="javascript"> 

function fncActualizar() 

{ 

var f = document.forms['formulario']; 

f.total.value = f.lista.value * f.precio.value; 

}; 





</script> 

<?php if ($nuevo || $modificar ) { ?>

<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" name="form" onsubmit="return revisa_paros(this);" method="post" >

  		<div id="datosgenerales" style="background-color:#FFFFFF;">

<br />

<br />

<table  width="616" align="center" cellpadding="2" cellspacing="4">

<? 



if($nuevo){ 

$qMetas = "SELECT * FROM meta WHERE   mes = '".date('Y')."-".$_POST['mes']."-01' AND area = ".$_POST['area']."";

$rMetas = mysql_query($qMetas);

$nMetas = mysql_num_rows($rMetas);

} 



if($modificar)

	$nMetas = '0';



if($nMetas > 0 ) { 

?>

<tr>

	<td align="center" colspan="5" class="style5"><font color="#FF0000"> <br />

    No pueden existir 2 metas ó más de la misma area en un mes.<br />

  <br />

</font></td>

</tr>

<tr>

	<td align="center"><input type="button" name="regresar" id="regresar" value="Regresar" onclick="javascript: history.back();" /></td>

</tr>

<? } else { ?>

<tr>

	<td class="style7" >Area:</td>

    <td colspan="4"><input size="20" readonly="readonly" type="text" value="<? 

	if($_POST['area'] == 1 ){ 

			echo "Extruder";  

			$area = "4";  

			$opcion = "";

		} 

	if($modificar && $dato['area'] == 1 ){ 

		 	echo "Extruder";  

			$area = "4";   

		}  

	if($_POST['area'] == 2 )

		{ 

			echo "Impresion"; 

			$area = "2"; 

			$area2 = "3"; 	

			$opcion = ", 3";

		} 

	if($modificar && $dato['area'] == 2)

		{

			echo "Impresion"; 

			$area = "2"; 

			$area2 = "3";

			$opcion = ", 3";		

		}

		

	if($_POST['area'] == 3)

		{ 

			echo "Bolseo"; 	

			$area = "1";  

		}

	if($modificar && $dato['area'] == 3)

		{

			echo "Bolseo"; 	

			$area = "1";  

		}	

		?>" />

    <input type="hidden" name="area" id="area" value="<? if($modificar){ echo $dato['area']; } else { echo $_POST['area']; }?>" /> </td>

</tr>

<tr>

  <td class="style7">Fecha: </td>

  <td colspan="4"><input type="hidden" name="mes" readonly="readonly" value="<? if($modificar){ echo $dato['mes']; } else { echo date('Y-m-01'); } ?>" />

    <input type="text" readonly="readonly" size="20" value="<?

	 if($modificar)

	 {

	 	$mes_num=explode("-",$dato['mes']);

	    	$m 	=  	$mes_num[1];

			$an	=	$mes_num[0];

			

			$posicion = strpos($m,'0');

			

			if($posicion === false || $posicion == 1){

			

					$m =  $meses_m[1];

			echo	$mes[$m];

			} else{

				$dias_d	=	explode(0,$m);

				$m =  $dias_d[1];

				

			echo	$mes[$m];

			}

	 }	

	 else

	 {

	 

	 	$m=$_POST['mes'];

		echo $mes[$_POST['mes']];	

	 }	

	 

	 if($modificar){ $u_d	= UltimoDia($dato['ano'],$m); } else { $u_d	=  UltimoDia($_REQUEST['ano'],$_REQUEST['mes']); } 

  ?>" />

    <input type="text"  size="10" readonly="readonly" value="<? if($modificar){ echo $dato['ano']; } else { echo $_REQUEST['ano']; }?>"  />

    <input type="hidden" name="ano" size="10" readonly="readonly" value="<? if($modificar){ echo $dato['ano']; } else { echo $_REQUEST['ano']; }?>"  /></td>

</tr>

<tr>

	<td class="style7">Numero de d&iacute;as en el mes:</td>

	<td colspan="4"><input readonly="readonly" type="text" name="nDias" id="nDias" value=" <? if($modificar){ echo UltimoDia($dato['ano'],$m); } else { echo UltimoDia($_REQUEST['ano'],$_REQUEST['mes']); } ?>"/></td>

</tr>

<tr>

	<td colspan="7"><h3>Metas</h3></td>

</tr>

<?

	if($area!=1)

	{

?>

<tr>

	<td>Maquinas: </td>

    <td>Meta Diaria:</td>

    <td>Turnos:</td>

    <td width="38">Dias:</td>

    <td width="72">Meta Mes:</td>

    <td colspan="2">&nbsp;</td>

</tr>

<?  

	if($modificar){

	$nuevo_mes	= 	$dato['mes'];

	$nuevo_ano	=	$dato['ano'];

	}

	if($nuevo){

	$nuevo_mes	=  $_REQUEST['mes']	;

	$nuevo_ano	=  $_REQUEST['ano']	;

	}



	

	$qRestaParosGenerales	=	"SELECT * FROM paros_general WHERE MONTH(fecha) = ".$nuevo_mes." AND YEAR(fecha) = ".$nuevo_ano."";

	$rRestaParosGenerales	=	mysql_query($qRestaParosGenerales);

	$nRestaParosGenerales	=	mysql_num_rows($rRestaParosGenerales);

		

	if($nuevo)

	$ultimo_dia_mes = UltimoDia($_POST['ano'],$_POST['mes']);

	if($modificar)

	$ultimo_dia_mes = UltimoDia($dato['ano'],$dato['mes']);

	

	

	if($nuevo){ 

		$qMaquinas = "SELECT * FROM maquina "; 

		if($id > 0){

		$qMaquinas .= " INNER JOIN $tabla2 ON $tabla2.id_maquina = maquina.id_maquina WHERE ($tabla2.$indice={$dato[$indice]}) AND $tabla2.area IN( ".$dato['area']." $opcion ) ORDER BY $tabla2.area,maquina.numero";

		} if($id == 0){ 

		$qMaquinas .= " WHERE area = ".$area."";

		 }

	} 

	if($a){

		$qMaquinas	=	" SELECT $tabla2.diaria, $tabla2.area, $tabla2.id_meta_diaria, $tabla2.dias, $tabla2.turnos, $tabla2.total, ".

								" maquina.id_maquina, maquina.numero, maquina.marca  ".

								" FROM $tabla2 INNER JOIN maquina ON $tabla2.id_maquina = maquina.id_maquina WHERE ($tabla2.$indice={$dato[$indice]}) AND $tabla2.area IN( ".$dato['area']." $opcion ) ORDER BY maquina.area,maquina.numero";

		}



		$rMaquinas	=	mysql_query($qMaquinas) OR die("<p>$qMaquinas</p><p>".mysql_error()."</p>");

		$nMaquin = mysql_num_rows($rMaquinas);

	if($modificar){

		$g			=	13;

		$temporal	= 	9;

	}

	if($nuevo){

		$g 			=	12;

		$temporal	=	8;

	}

	for($a,$g;$dMaquinas = mysql_fetch_assoc($rMaquinas);$a++,$g = $g+$temporal){ 

	

	

	

	 $qMet	=	" SELECT * FROM paros_maquinas ".

				" WHERE (MONTH(fecha) = '".$m."' AND YEAR(fecha) = '".$an."')".

				" AND id_maquina = ".$dMaquinas['id_maquina']."";

				

	$rMet	=	mysql_query($qMet);

	$contador = 0;

	$nMet	=	mysql_num_rows($rMet);

	if( $nMet > 0) {

		while($dMet	=	mysql_fetch_assoc($rMet)){

		

			if($dMet['turno_m'] == 1)

				$contador = $contador + 0.32;	

		

			if($dMet['turno_v'] == 1)

				$contador = $contador + 0.33;	

			

			if($dMet['turno_n'] == 1)

				$contador = $contador + 0.35;	

		}

	}

		$contador = $nRestaParosGenerales + $contador;

	

	?>

<tr <? cebra($a)?> id="me_<?=$g?>">

	<td width="240" align="left" class="style7">

	<? 

	   if($dMaquinas['area'] == 1 ) echo "Extruder";

	   if($dMaquinas['area'] == 2 ) echo "Impresion";

	   if($dMaquinas['area'] == 3 ) echo "L. de Impresion";

	?>

    <? if($modificar){  ?>

    	<input type="hidden" name="id_meta_diaria[]" value="<?=$dMaquinas['id_meta_diaria']?>" />

	<? } ?>

    <input type="hidden" name="codigos[]" value="<?=$dMaquinas['id_maquina']?>" />

    <input type="hidden" name="areas[]" value="<?=$dMaquinas['area']?>" />

    <input type="hidden" name="id_maquina<?=$dMaquinas['id_maquina']?>" value="<?=$dMaquinas['id_maquina']?>" />

	<?=$dMaquinas['numero']?>&nbsp; <?=$dMaquinas['marca']?> </td>



    

    <td width="90" align="right"><input style="text-align:right" name="nrmetap<?=$dMaquinas['id_maquina']?>" type="text" class="style1" id="<?=$dMaquinas['id_maquina']?>_nrmetap" value="<? if($modificar || $nuevo){  echo $dMaquinas['diaria']; } else { echo '0'; }?>" size="10" maxlength="60"  onchange="javascript: sumaMeta();  multiplika(<?=$dMaquinas['id_maquina']?>);  total(); " /></td>

    <td width="49" align="center" class="style7"><input style="text-align:right" type="text" size="3" name="turnos_<?=$dMaquinas['id_maquina']?>" id="<?=$dMaquinas['id_maquina']?>_turnos"  value="<? if($modificar){ echo $dMaquinas['turnos']; } else { echo $num = $ultimo_dia_mes*3;} ?>"   /></td>

    <td width="38" align="left" class="style7"><input style="text-align:right" type="text" size="3" name="dias_<?=$dMaquinas['id_maquina']?>" id="<?=$dMaquinas['id_maquina']?>_dias" value="<? if($modificar){ echo $dMaquinas['dias']; } else { echo $resultadoParo = $ultimo_dia_mes - $nRestaParosGenerales; } ?>" onchange="javascript: multiplika(<?=$dMaquinas['id_maquina']?>); turnos(<?=$dMaquinas['id_maquina']?>);  total();  sumaMeta(); "/>

    <input style="text-align:right" type="hidden" size="3" name="paros_<?=$dMaquinas['id_maquina']?>" id="<?=$dMaquinas['id_maquina']?>_paro" value="<? if($modificar){ echo $contador; } else { echo "0"; } ?>" onchange="javascript: multiplika(<?=$dMaquinas['id_maquina']?>); turnos(<?=$dMaquinas['id_maquina']?>); sumaMeta();  total();  "/></td>

    <td width="72" align="left" class="style7"><input style="text-align:right" type="text" name="meta_mes_<?=$dMaquinas['id_maquina']?>" id="<?=$dMaquinas['id_maquina']?>_meta_mes" size="12" value="<? if($modificar || $nuevo){ echo $dMaquinas['total']; } else { echo '0'; } ?>" onchange="javascript: total(); dividir(<?=$dMaquinas['id_maquina']?>); sumaMeta();  " /></td>

    <td width="32" ><a style="cursor:pointer" onClick="window.open('paros_maquinas.php?accion=listar_paros&amp;id_maquina=<?=$dMaquinas['id_maquina']?>&mes=<?=$m?>&anio=<?=$dato['ano']?>', this.target, 'width=400,height=400,scrollbars =yes');"><img src="<?=IMAGEN_MOSTRAR?>" border="0" title=" Mostrar paros de esta maquina"></a></td>

    <td width="33" ><a style="cursor:pointer" onClick="window.open('paros_maquinas.php?accion=mostrar_paros&amp;id_maquina=<?=$dMaquinas['id_maquina']?>&area=<?=$area?>&mes=<?=$m?>&anio=<?=$dato['ano']?>', this.target, 'width=800,height=800,scrollbars =yes');"><img src="<?=IMAGEN_MODIFICAR?>" border="0" title=" Agregar paros a esta maquina"></a></td>



</tr>

<? } ?> 

<tr>

	<td colspan="4" align="right">Totales H.D.</td>	

	<td><input style="text-align:right" type="text" name="total_hd" id="total_hd" size="12" value="<? if($modificar || $nuevo){  echo round($dato['total_hd']); } else { echo "0"; }?>"/></td>

</tr>

<tr>

	<td colspan="4" align="right">Meta x Dia</td>	

	<td><input style="text-align:right" size="12" type="text" name="meta_total"  id="meta_total" value="<? if($modificar || $nuevo){  round($dato['total_dia']); } else { echo "0"; }?>"/></td>

</tr>

<? } if($area==1){?>

<tr>

  <td align="right">Meta mensual: Millares</td>

  <td><input name="meta_mes_millar" type="text"  id="meta_mes_millar" style="text-align:right" value="<? if($modificar || $nuevo){ echo $dato['meta_mes_millar']; } else { echo "0"; }?>" size="15"/></td>

  <td colspan="3" align="left">&nbsp;</td>

  </tr>

<tr>

  <td align="right">Meta mensual: Kilos</td>

  <td><input style="text-align:right" type="text" name="meta_mes_kilo" id="meta_mes_kilo" size="15" value="<? if($modificar || $nuevo){ echo $dato['meta_mes_kilo']; } else { echo "0"; }?>"/></td>

  <td colspan="3" align="left">&nbsp;</td>

  </tr><? }?>

<tr>

	<td colspan="5"><h3>Desperdicios</h3></td>	

</tr>

<?

	if($area==1)

	{

?>

<tr>

  <td align="right">Tira </td>

  <td><input type="text" size="15" name="mes_tira" id="mes_tira" value="<? if($modificar || $nuevo){ echo $dato['mes_tira']; } else { echo "0"; }?>"/></td>

  <td align="right">%</td>	

  <td><input type="text" size="5" name="porcentaje_desp" id="porcentaje_desp" value="<? if($modificar || $nuevo){ echo  $dato['porcentaje_desp']; } else { echo '0'; }  ?>" /></td>

</tr>

<tr>

  <td align="right">Troquel</td>

  <td><input type="text" size="15" name="mes_troquel" id="mes_troquel" value="<? if($modificar || $nuevo){  echo$dato['mes_troquel']; } else { echo "0"; }?>"/></td>

  <td align="right">%</td>	

  <td><input type="text" size="5" name="porcentaje_troquel" id="porcentaje_troquel" value="<? if($modificar || $nuevo){ echo $dato['porcentaje_troquel']; } else { echo '0'; }  ?>" /></td>

</tr>

<tr>

  <td align="right">Segunda</td>

  <td><input type="text" size="15" name="mes_segunda" id="mes_segunda" value="<? if($modificar || $nuevo){ echo $dato['mes_segunda']; } else { echo "0"; }?>"/></td>

  <td align="right">%</td>	

  <td><input type="text" size="5" name="porcentaje_segunda" id="porcentaje_segunda" value="<? if($modificar || $nuevo){ echo $dato['porcentaje_segunda']; } else { echo '0'; }  ?>" /></td>

</tr>

<?

	}if($area!=1)

	{

?>

<tr>

	<td align="right">Tira - Duro H.D.</td>	

	<td><input style="text-align:right"  size="15" type="text" name="desp_duro_hd"  id="desp_duro_hd" value="<? if($modificar || $nuevo){ echo $dato['desp_duro_hd']; } else { echo '0'; }  ?>"/></td>	

	<td colspan="2" align="right">%</td>	

	<td><input type="text" size="5" name="porcentaje_desp" id="porcentaje_desp" value="<? if($modificar || $nuevo){ echo $dato['porcentaje_desp']; } else { echo '0'; }  ?>" /></td>

</tr>

<tr>

	<td align="right">Tira - Duro B.D.</td>	

	<td><input style="text-align:right" size="15"  type="text" name="desp_duro_bd"  id="desp_duro_bd" value="<? if($modificar || $nuevo){ echo $dato['desp_duro_bd']; } else { echo '0'; } ?>"/></td>	

	<td colspan="2" align="right"></td>	

	<td>&nbsp;</td>

</tr>

<tr>

	<td align="right">Troquel H.D</td>	

	<td><input style="text-align:right" size="15"  type="text" name="troquel"  id="troquel" value="<? if($modificar || $nuevo){ echo $dato['troquel']; } else { echo '0'; } ?>"/></td>	

	<td colspan="2" align="right"></td>	

	<td>&nbsp;</td>

</tr>

<? }?>

<tr>

	<td class="contenidos" align="right" colspan="5">

	<?

    	if($area!=1)

		{

	?>

    <input type="button" name="calcular" id="calcular" value="Volver a Calcular" onclick="javascript:

	<? 

	($area == 4)?$where = " area = 4 ":"";

	($area == 2)?$where = " area IN (2,3) ":"";



	$qMaquinasI	=	"SELECT id_maquina FROM maquina WHERE $where ORDER BY id_maquina";

	$rMaquinasI	=	mysql_query($qMaquinasI);

	while($dMaquinasI	=	mysql_fetch_row($rMaquinasI)){?>

        multiplika(<?=$dMaquinasI[0]?>);

	<? } ?>

    total(); " />

    <? }?>

	<br />

	<br />

	<?php if($modificar) { ?>

		<input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>">

	<?php } ?>

	                                                                       

	<?php if($nuevo) { ?>

		<input type="hidden" name="status" value="0">

	<?php } if($modificar){ ?>	

		<input type="submit" name="actualizar" id="actualizar" value="Modificar" />

    <? } if($nuevo){ ?>

    <input type="submit" name="guardar" value="Guardar" onclick="javascript: return confirm('Usted está a punto de confirmar una nueva meta, \n Si desea realizar algun cambio haga click en cancelar, \n si esta satisfecho con la informacion, haga click en Aceptar. ');"/>

    <? } ?> </td> 

</tr>

<? } ?>

</table>

<br />

<br />

</div>

</form>

        

<script type="text/javascript">

<!--

function revisa_paros() 

{ 

var f 			= 	document.forms['form']; 

var	Dias		=	parseInt(f.nDias.value);

var contador 	= 	0;

<? if($nuevo){?>

	var a	= 	12;

	var t	=	8;

<? } if ($modificar){?>

	var a	= 13;

	var t	=	9;

<? } ?>

	for(a; a < f.length; a = a+t){

		var resultado 	= 0;

		resultado	=	parseFloat(f.elements[a].value) + parseFloat(f.elements[a+1].value );

		

			if(f.elements[a].value == 0)

				contador	=	contador+1;

				

			else if( parseFloat(resultado) >= parseFloat(Dias) ){

				document.getElementById('me_'+a).style.background = '#ffffff';

		//		alert(f.elements[a].value);

				contador	=	contador+1;

			  }

			else if( parseFloat(resultado) < parseFloat(Dias)  ){

				alert('Debe especificar el numero de paros a esta maquina.');

				document.getElementById('me_'+a).style.background = '#FF7979';

			}

	}

	

	if(contador == <?=$nMaquin?>){

		if(!confirm('Esta apunto de guardar esta informacion en su sistema. \nEsta seguro de continuar ?'))

			return false;

		else

			return true;

		

	}

	else

		return false;

}; 





function redondear(cantidad, decimales) {

	var cantidad = parseFloat(cantidad);

	var decimales = parseFloat(decimales);

	decimales = (!decimales ? 2 : decimales);

	return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);

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



function total()

{

        var a,total, i;

        i = document.getElementsByTagName('INPUT');

        total=0;

        for(a=0; a<i.length; a++)

        {

                if (IsNumeric(i[a].value) && i[a].id.indexOf('_meta_mes')>0 )

                {

                        if (i[a].value.length>0) 

                        {

                                total = parseFloat(total) + parseFloat(i[a].value);

                        }

                }

		}		

        document.getElementById("total_hd").value = redondear(total,2);

}



function sumaMeta()

{

  

        document.getElementById("meta_total").value = redondear(document.getElementById("total_hd").value/<?=$u_d?>);

}



function multiplika(x)

{

     var total;

     total		=	0;

	 total 		=  document.getElementById(x+'_dias').value * document.getElementById(x+'_nrmetap').value;

     document.getElementById(x+"_meta_mes").value = redondear(total,2);

	 

}



function multiplika_todo()

{



var f 			= 	document.forms['form']; 

<? if($nuevo){?>

var a	= 	12;

var t	=	8;

<? } if ($modificar){?>

var a	= 13;

var t	=	9;

<? } ?>	

	

    var total_todo;

	for(a; a < f.length; a = a+t){

		 total_todo		=	0;

		 total_todo 		=  f.elements[a].value * f.elements[a-2].value;

		 f.elements[a+2].value = redondear(total_todo,2);

	}

	

		document.getElementById('actualizar').value =	'Modificar';



	

	total();

	

	

}







function dividir(x)

{

     var total;

     total		=	0;

	 total 		=  document.getElementById(x+'_meta_mes').value / document.getElementById(x+'_dias').value;

     document.getElementById(x+"_nrmetap").value = redondear(total,2);

}





function turnosLimp(x)

{

     var a,totalLimp, i;

     totalLimp	=	0;

     totalLimp 	=  	document.getElementById(x+'_diasLimp').value * 3;

     document.getElementById(x+"_turnosLimp").value = redondear(totalLimp,2);

}



function turnos(x)

{

     var a,total, i;

     total=0;

     total =  document.getElementById(x+'_dias').value * 3;

     document.getElementById(x+"_turnos").value = redondear(total,2);

}



//-->	

</script>

<?php } ?>



<?php if($mostrar) { 

if($dato['area'] == 1) $areaParo = 1;

if($dato['area'] == 2) $areaParo = 1;

if($dato['area'] == 3) $areaParo = 1;

if($dato['area'] == 4) $areaParo = 1;



$qDiasNoLab	=	"SELECT * FROM paros_general WHERE MONTH(fecha) = '".$dato['mes']."' AND YEAR(fecha) = '".$dato['ano']."' AND (bolseo = ".$areaParo." OR impresion = ".$areaParo." OR extruder = ".$areaParo." OR lineas = ".$areaParo.")";

$rDiasNoLab	=	mysql_query($qDiasNoLab);	





?>

<div id="datosgenerales" style="background-color:#FFFFFF;"><br />

<table>

	<tr>

    	<td valign="top">

            <table  width="250" align="left" border="0" class="navcontainer" style="border-spacing:inherit; border-color:#006666">

            	<tr >

                	<td colspan="2" class="style7" >&nbsp;</td>

                </tr>

                <tr >

                	<td colspan="2" class="style7" >&nbsp;</td>

                </tr>		

                <tr>

                    <td colspan="2" class="style7" align="left"><h3>Fechas de paro General</h3></td>

                 </tr>

                 <? for($a=0;$dDiasNoLab = mysql_fetch_assoc($rDiasNoLab);$a++){ ?>

                 <tr <? if(bcmod($a,2) == 0 ) echo "bgcolor='#DDDDDD'"; else ""; ?>>

                    <td width="75" class="style5"><?=fecha($dDiasNoLab['fecha'])?></td>

                    <td width="158" class="style5"><?=$dDiasNoLab['motivo']?></td>

                 </tr>

                 <? } ?>

            </table>

        </td>

        <td width="2%">&nbsp;</td>

		<? if($dato['area'] == 3){?>

        <td valign="top" ><table width="400"><tr><td width="34" align="left" class="style7"><table width="400">

          <tr>

            <td colspan="4">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="4">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="4"><h3>META BOLSEO

              <?

			  				 	$mes_num=explode("-",$dato['mes']);

	    	$m =  $mes_num[1];

			$posicion = strpos($m,'0');

			

			if($posicion === false || $posicion == 1){

			

					$m =  $meses_m[1];

			echo	$mes[$m];

			} else{

				$dias_d	=	explode(0,$m);

				$m =  $dias_d[1];

				echo	$mes[$m];

			}



			  

			  ?>

              del

              <?=$dato['ano']?>

            </h3></td>

          </tr>

          <tr>

            <td width="79" align="left" class="style7">Meta mensual: </td>

            <td colspan="3" class="style5"><? printf("%.2f",$dato['meta_mes_millar']);?> Millares</td>

          </tr>

          <tr>

            <td align="left" class="style7">Meta mensual: </td>

            <td class="style5" colspan="3"><? printf("%.2f",$dato['meta_mes_kilo']);?> Kilos</td>

          </tr>

          <tr>

            <td colspan="4"><h3>Desperdicios</h3></td>

          </tr>

          <tr>

            <td align="left" class="style7">Tira :</td>

            <td width="127" class="style5"><? printf("%.2f",$dato['mes_tira']);?></td>

            <td width="34" align="left" class="style7">% :</td>

            <td width="78" class="style5"><? printf("%.2f",$dato['porcentaje_desp']);?></td>

          </tr>

          <tr>

            <td align="left" class="style7">Troquel :</td>

            <td class="style5"><? printf("%.2f",$dato['mes_troquel']);?></td>

            <td align="left" class="style7">% :</td>

            <td class="style5"><? printf("%.2f",$dato['porcentaje_troquel']);?></td>

          </tr>

          <tr>

            <td align="left" class="style7">Segunda :</td>

            <td class="style5"><? printf("%.2f",$dato['mes_segunda']);?></td>

            <td align="left" class="style7">% :</td>

            <td class="style5"><? printf("%.2f",$dato['porcentaje_segunda']);?></td>

          </tr>

          <tr>

            <td colspan="2"><br />

                <br />

                <br />

              &nbsp;</td>

          </tr>

          <tr>

            <? if(isset($_SESSION['id_supervisor']) && !isset($_SESSION['id_admin']))  {?>

            <td class="style7" colspan="5" align="center"><span class="style4"><br />

              &nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=listar" class="style7">Listado</a> &nbsp;<strong>|</strong>&nbsp; <br />

              <br />

            </span></td>

            <? } ?>

            <?  if($dato['mes'] >= date('m') && $dato['ano'] >= date('Y') && isset($_SESSION['id_admin'])) {?>

            <td width="57" colspan="5" align="center" class="style7"><span class="style4">&nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&amp;<?=$indice?>=<?=$_REQUEST[$indice]?>" class="contenidos">Modificar</a> &nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=listar" class="contenidos">Listado</a> &nbsp;<strong>|</strong>&nbsp; </span></td>

            <? }//fin if ?>

          </tr>

        </table></td>

            </tr>

        </table>

        </td>

		<? } else { ?>

		<td>   

            <table  width="507" align="rigth" border="0" class="navcontainer" style="border-spacing:inherit; border-color:#006666"><tr>

              <td width="78" align="right" class="mostrar" >

              	<table  width="507" align="center" border="0" class="navcontainer" style="border-spacing:inherit; border-color:#006666">

                <tr>

                  <td class="style7" >Area:</td>

                  <td class="style7" colspan="4" align="left">&nbsp;

                      <?

                    if($dato['area'] == 1 ) echo "Extruder"; 

                    if($dato['area'] == 2 ) echo "Impresion"; 

                    if($dato['area'] == 3 ) echo "Bolseo"; 	

                ?></td>

                </tr>

                <tr>

                  <td class="style7">Fecha: </td>

                  <td class="style7" colspan="4" align="left">&nbsp;

                      <?

				 	$mes_num=explode("-",$dato['mes']);

	    	$m =  $mes_num[1];

			$posicion = strpos($m,'0');

			

			if($posicion === false || $posicion == 1){

			

					$m =  $meses_m[1];

			echo	$mes[$m];

			} else{

				$dias_d	=	explode(0,$m);

				$m =  $dias_d[1];

				echo	$mes[$m];

			}



             echo "&nbsp;&nbsp;";  echo $dato['ano'];

             ?></td>

                </tr>

                <tr>

                  <td colspan="6" class="style7"><h3>Metas</h3></td>

                </tr>

                <tr>

                  <td class="style7">Maquinas: </td>

                  <td align="right" class="style7">Meta Diaria:</td>

                  <td align="right" class="style7">Turnos:</td>

                  <td width="54" align="right" class="style7">Dias:</td>

                  <td width="92" align="right" class="style7">Meta Mes:</td>

                </tr>

                <?  

				

				

				

            

                    $qMaquinas	=	"SELECT * FROM $tabla2 INNER JOIN maquina ON $tabla2.id_maquina = maquina.id_maquina WHERE ($tabla2.$indice={$dato[$indice]}) AND $tabla2.area = ".$dato['area']." ORDER BY maquina.numero";

                    $rMaquinas	=	mysql_query($qMaquinas) OR die("<p>$qMaquinas</p><p>".mysql_error()."</p>");

                for($b=0;$dMaquinas = mysql_fetch_assoc($rMaquinas); $b++){ 

            

                ?>

                <tr <? if(bcmod($b,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>

                  <td width="134" align="left" class="mostrar"><?=$dMaquinas['numero']?>

                    &nbsp;

                    <?=$dMaquinas['marca']?>      </td>

                  <td  alingn"right" align="right" class="mostrar"><? printf("%.2f",$dMaquinas['diaria']);?></td>

                  <td  align="right" class="mostrar"><?=$dMaquinas['turnos']?></td>

                  <td  align="right" class="mostrar"><?=$dMaquinas['dias']?></td>

                  <td align="right" class="mostrar"><? printf("%.2f",$dMaquinas['total']);?></td>

                </tr>

                <? } if($dato['area'] == 2){ 

            ?>

                <tr>

                  <td colspan="5" class="style7" align="left">Linea de Impresion</td>

                </tr>

                <? 

                $qMaquinasLimp	=	"SELECT * FROM $tabla2 INNER JOIN maquina ON $tabla2.id_maquina = maquina.id_maquina WHERE ($tabla2.$indice={$dato[$indice]}) AND $tabla2.area = 3";

                $rMaquinasLimp = mysql_query($qMaquinasLimp);

                for($a = 0; $dMaquinasLimp = mysql_fetch_assoc($rMaquinasLimp); $a++){ 

                

                ?>

                <tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>

                  <td width="134" align="left" class="mostrar"><?=$dMaquinasLimp['numero']?>

                    &nbsp;

                    <?=$dMaquinasLimp['marca']?>      </td>

                  <td align="right" class="mostrar" ><? printf("%.2f",$dMaquinasLimp['diaria']); ?></td>

                  <td align="right" class="mostrar"><?=$dMaquinasLimp['turnos']; ?></td>

                  <td align="right" class="mostrar"><?=$dMaquinasLimp['dias']; ?></td>

                  <td   align="right" class="mostrar" ><? printf("%.2f",$dMaquinasLimp['total']);?></td>

                </tr>

                <? 

                }

             } ?>

                <tr>

                  <td class="style7" align="right">Meta Diaria Total:</td>

                  <td class="mostrar" align="right"><? printf("%.2f",$dato['total_dia']);?></td>

                  <td colspan="2" align="right" class="style7">Totales H.D.</td>

                  <td class="mostrar" align="right"><? printf("%.2f",$dato['total_hd'])?></td>

                </tr>

                <tr>

                  <td colspan="5"><h3>Desperdicios</h3></td>

                </tr>

                <tr bgcolor="#DDDDDD">

                  <td class="style7" align="right">Tira - Duro H.D.</td>

                  <td align="right" class="mostrar"><? printf("%.2f",$dato['desp_duro_hd'])?></td>

                  <td class="style7" colspan="2" align="right">%</td>

                  <td align="right" class="mostrar"><? printf("%.2f",$dato['porcentaje_desp'])?></td>

                </tr>

                <tr>

                  <td class="style7" align="right">Tira - Duro B.D.</td>

                  <td align="right" class="mostrar"><? printf("%.2f",$dato['desp_duro_bd'])?></td>

                  <td class="style7" colspan="2" align="right">&nbsp;</td>

                  <td align="right" class="mostrar"></td>

                </tr>

                <tr bgcolor="#DDDDDD">

                  <td class="style7" align="right" >Troquel H.D</td>

                  <td align="right" class="mostrar"><? printf("%.2f",$dato['troquel'])?></td>

                  <td class="style7" colspan="2" align="right">&nbsp;</td>

                  <td align="right" class="mostrar">&nbsp;</td>

                </tr>

                <tr>

                  <td class="style7" align="right" colspan="5"><br />

                      <br />      </td>

                </tr>

                <tr>

                  <? if( isset($_SESSION['id_supervisor']) && !isset($_SESSION['id_admin'])) {?>

                  <td class="style7" colspan="5" align="center"><span class="style4"><br />

                    &nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=listar" class="style7">Listado</a> &nbsp;<strong>|</strong>&nbsp; <br />

                    <br />

                  </span></td>

                  <? } ?>

                  <?  if($dato['mes'] >= date('m') && $dato['ano'] >= date('Y') && isset($_SESSION['id_admin'])) {?>

                  <td colspan="5" align="center" class="style7"><span class="style4">&nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&amp;<?=$indice?>=<?=$_REQUEST[$indice]?>" class="contenidos">Modificar</a> &nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=listar" class="contenidos">Listado</a> &nbsp;<strong>|</strong>&nbsp; </span></td>

                  <? }//fin if ?>

                </tr>

              </table></td>

            </tr>

            </table>

         </td><? } ?>

      </tr>

 </table>

</div>

<?php } // Mostrar ?>



<?php if($listar) { ?>

<div id="datosgenerales" style="background-color:#FFFFFF;" align="center">

<table width="70%" cellpadding="2" cellspacing="2" align="center" border="0">



<? 

if(isset($_SESSION['id_admin']) && $_REQUEST['metas_viejas'] == 0) $mes1 = "mes = '".date('Y')."-".date('m')."-01'";



if(isset($_SESSION['id_admin']) && $_REQUEST['metas_viejas'] == 1) $mes1 = " mes < '".date('Y')."-".date('m')."-01'"  ;



if(!isset($_SESSION['id_admin'])) $mes1 = "mes = '".date('Y')."-".date('m')."-01'";



 $qListar	=	"SELECT * FROM $tabla WHERE $mes1 ORDER BY mes DESC";

$rListar	=	mysql_query($qListar) OR die("<p>$qListar</p><p>".mysql_error()."</p>");





if(mysql_num_rows($rListar) > 0) {  ?>

<? if(isset($_SESSION['id_admin'])){ ?>

 <tr>

 	<td colspan="9" style="text-align:center; padding-bottom:15px;"><button onclick="location.href='<?=$_SERVER['PHP_SELF']?>?seccion=46'" class="button2">VER PRODUCCION POR HORA Y DESP. %</button></td>

 </tr>

 <tr>

	<td colspan="10" align="center">

	    <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=area" class="style7"><strong>Nueva Meta</strong></a>

        <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span> 

        <? if($_REQUEST['metas_viejas'] == 0){?>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&metas_viejas=1" class="style7"><strong>Ver Metas Anteriores</strong></a>

        <? } if($_REQUEST['metas_viejas'] == 1){?>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&metas_viejas=0" class="style7"><strong>Volver a las Metas Actuales</strong></a>

        <? } ?>

        <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span> 

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=35" class="style7"><strong>Paros produccion</strong></a>

        <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span> 

     </td>

 </tr>

<? } ?>	       

<tr>

	<td colspan="9" align="left" class="style7"><br/></td> 

</tr>



<? for($flag=true;$d	=	mysql_fetch_assoc($rListar);$flag=!$flag) { ?>

<? 





$m = num_mes($d['mes']);

   

   ?>

<tr>

	<td align="left" colspan="9" class="style7"><h3>Aplica para: <?=$mes[$m]; ?> del <?=$d['ano']?> <?

	if($d['area'] == 1) echo "Extruder";

	if($d['area'] == 2) echo "Impresion";

	if($d['area'] == 3) echo "Bolseo";?></h3> </td>

</tr>

<? if($d['area']!=3){?>

<tr>

<td width="84" class="style7" align="left">Total del mes:</td>

<td width="95" align="left" class="style5" ><? printf("%.2f",$d['total_hd'])?></td>

<td width="78" class="style7" align="left">Total por dia:</td>

<td width="70" align="left" class="style5" ><? printf("%.2f",$d['total_dia'])?></td>





<? if(isset($_SESSION['id_admin'])){ ?>

<?php if($d['mes'] < date('m') && $d['ano'] < date('Y')) { ?>

<td width="35"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&<?=$indice?>=<?=$d['id_meta']?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>

<? }  ?>



<?php if( $d['mes'] >= date('m') || $d['ano'] >= date('Y')) { ?>

<td width="35"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&<?=$indice?>=<?=$d['id_meta']?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>

<td width="35"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&<?=$indice?>=<?=$d['id_meta']?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>

<td width="35"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&<?=$indice?>=<?=$d['id_meta']?>" onclick="javascript: return confirm('Realmente deseas eliminar a este supervisor?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>

<? 

	} 

	

	} if(isset($_SESSION['id_supervisor']) && !isset($_SESSION['id_admin'])){ ?>

	

<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&<?=$indice?>=<?=$d['id_meta']?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>    

	<? 

   }  ?>

</tr>

<tr><td colspan="7"></td></tr>

<? } 

	if($d['area']==3)

	{

?>

<tr>

  <td colspan="9" align="left" class="style7" >

  	<table width="100%" border="0" cellpadding="2" cellspacing="4">

    <tr>

      <td colspan="7">Meta de produccion</td>

      </tr>

    <tr>

      <td width="69">Millares:</td>

      <td width="73"><span class="style5"><? printf("%.2f",$d['meta_mes_millar'])?></span></td>

      <td width="66">Kilos</td>

      <td width="89"><span class="style5"><? printf("%.2f",$d['meta_mes_kilo'])?></span></td>

      <td colspan="2" align="right">

      <table><tr>

	  <td ><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&<?=$indice?>=<?=$d['id_meta']?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>

      <td ><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&amp;<?=$indice?>=<?=$d['id_meta']?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0" /></a></td>

      <td><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&<?=$indice?>=<?=$d['id_meta']?>" onclick="javascript: return confirm('Realmente deseas eliminar a este supervisor?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>

		</tr>

     </table></td>

    </tr>

    <tr>

      <td colspan="7">Meta de desperdicio</td>

      </tr>

    <tr>

      <td>Tira:</td>

      <td><span class="style5"><? printf("%.2f",$d['mes_tira'])?></span></td>

      <td>Troquel:</td>

      <td><span class="style5"><? printf("%.2f",$d['mes_troquel'])?></span></td>

      <td width="140">Segunda:</td>

      <td width="42"><span class="style5"><? printf("%.2f",$d['mes_segunda'])?></span></td>

    </tr>

    <tr>

      <td colspan="7">&nbsp;</td>

    </tr>

</table></td></tr>

  <? }?>

<tr>

<td align="left" colspan="9" class="style7" >&nbsp;</td>

</tr><? }?>



<? if(isset($_SESSION['id_admin'])){ ?>



 <tr>

	<td align="center" colspan="10">

	  <p>	    <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=area" class="style7"><strong>Nueva Meta</strong></a>

        <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span> 

        <? if($_REQUEST['metas_viejas'] == 0){?>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&metas_viejas=1" class="style7"><strong>Ver Metas Anteriores</strong></a>

        <? } if($_REQUEST['metas_viejas'] == 1){?>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&metas_viejas=0" class="style7"><strong>Volver a las Metas Actuales</strong></a>

        <? } ?>

        <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span> 

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=35" class="style7"><strong>Paros produccion</strong></a>

        <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span>



      <br />

        <br />

        <br />

	  </p>

    </td>

 </tr>

<? } ?>	

    <?php } else { ?>

<tr>

		<td class="style7" align="center"><br />

	      <br />

	      <br />

	      Aun no hay metas registradas en la base de datos<br />

	    <br /></td>

  </tr>

  <tr>

	  <td align="center">

		  <p>	    <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=area" class="style7"><strong>Nueva Meta</strong></a>

        <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span> 

        <? if($_REQUEST['metas_viejas'] == 0){?>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&metas_viejas=1" class="style7"><strong>Ver Metas Anteriores</strong></a>

        <? } if($_REQUEST['metas_viejas'] == 1){?>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&metas_viejas=0" class="style7"><strong>Volver a las Metas Actuales</strong></a>

        

		<? } ?>

        <a href="<?=$_SERVER['PHP_SELF']?>?seccion=35" class="style7"><strong>Paros produccion</strong></a>

        <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span> 

</p></td>

  </tr>

	      <?php } ?></table></div>

      <?php } ?>

<? if($desgloze){ 



$qMes_act	=	"SELECT * FROM meta WHERE meta.id_meta=".$_REQUEST['id_meta']."";

$rMes_act	=	mysql_query($qMes_act);

$dMes_act	=	mysql_fetch_assoc($rMes_act);



$dMes_act['area'];

if($dMes_act['area'] < 3){

$qM	=	"SELECT * FROM maquina WHERE area = ".$dMes_act['area']."";

$rM	=	mysql_query($qM);

$nM	=	mysql_num_rows($rM);





($dMes_act['area'] == 1)?$impresion="impresion = 0":$impresion="impresion = 1";



$m = num_mes($dMes_act['mes']);



 $qMes_act2	=	" SELECT * FROM meta ".

				" LEFT JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta ".

				" LEFT JOIN maquina ON metas_maquinas.id_maquina = maquina.id_maquina".

				" WHERE meta.id_meta=".$_REQUEST['id_meta']." ORDER BY maquina.area, maquina.numero ASC";

				

$rMes_act2	=	mysql_query($qMes_act2);

for($a=0;$dMes_act2	=	mysql_fetch_assoc($rMes_act2);$a++){

	$nueva_meta[$a]	=	$dMes_act2['diaria'];

	$id_maq[$a]		=	$dMes_act2['numero'];

}



$mes_actual = explode('-',$dMes_act['mes']);

$anio	=	$mes_actual[0];

	for($e = 3; $e > (-1); $e--){

	

		$anio_meta	=	$anio;

		$mes_meta 	= 	$mes_actual[1]-$e;

		

		if($mes_meta == 0){

			$mes_meta 	= 	12;

			$anio_meta	= 	$anio-1;

			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01";

		}

		else if($mes_meta == (-1)){

			$mes_meta 	= 	11;

			$anio_meta	= 	$anio-1;

			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01";

		}

		else if($mes_meta == (-2)){

			$mes_meta 	= 	10;

			$anio_meta	= 	$anio-1;

			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01"; 

		}

		else if($mes_meta < 10){   

		 	$fecha[$e] 	= 	$anio_meta."-"."0".$mes_meta."-01";



		}else{ 

		 	$fecha[$e] 	= 	$anio_meta."-".$mes_meta."-01";

		}

	}



	

 $m1 = num_mes($fecha[1]); 

 $m2 = num_mes($fecha[2]); 

 $m3 = num_mes($fecha[3]); 



      $qExtN	=		" SELECT numero, marca, metas_maquinas.total AS total, metas_maquinas.id_maquina, metas_maquinas.area AS area FROM meta  ".

							" INNER JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".

							" LEFT JOIN maquina ON metas_maquinas.id_maquina = maquina.id_maquina ".

							" WHERE meta.mes IN('$fecha[3]','$fecha[2]','$fecha[1]')  AND meta.area = ".$dMes_act['area']."".

							" GROUP BY metas_maquinas.id_maquina".

							" ORDER BY metas_maquinas.area, numero, mes ASC";

					

		  $rExtN	=	mysql_query($qExtN);

		  $Nmaq		= 	mysql_num_rows($rExtN);

		  for($t=0; $dExtN	=	mysql_fetch_assoc($rExtN);$t++){		  

		  		$num[$t]	=	$dExtN['numero'];	

		  		$marca[$t]	=	$dExtN['marca'];

		  		$area[$t]	=	$dExtN['area'];

		  }		  





	$es	=	explode('-',$fecha[1]);

	$mes_ultimos	=	UltimoDia($es[0],$es[1]);

	$fecha[1]	=	$es[0].'-'.$es[1].'-'.$mes_ultimos;	

		

	     /* $qExt	=		" SELECT numero , metas_maquinas.total AS total, metas_maquinas.id_maquina FROM meta  ".

							" INNER JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".

							" LEFT JOIN maquina ON metas_maquinas.id_maquina = maquina.id_maquina ".

							" WHERE meta.mes IN('$fecha[3]','$fecha[2]','$fecha[1]')  AND meta.area = ".$dMes_act['area']."".

							" ORDER BY metas_maquinas.area, numero, mes ASC";*/

						

		  if($dMes_act['area']	 == 1){				

  			  	$qExt	=	" SELECT  SUM(subtotal) AS total, resumen_maquina_ex.id_maquina, maquina.numero, MONTH(fecha) AS mes FROM resumen_maquina_ex "	.

		  			" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".

					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".

					" INNER JOIN maquina 	ON resumen_maquina_ex.id_maquina 		= maquina.id_maquina ".

					" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND   $impresion GROUP BY  MONTH(fecha), resumen_maquina_ex.id_maquina ".

					" ORDER BY resumen_maquina_ex.id_maquina, fecha ASC";

		  

	  			$qDias =	" SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_ex.id_maquina, maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_ex "	.

		  			" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".

					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".

					" INNER JOIN maquina 			ON resumen_maquina_ex.id_maquina 			= maquina.id_maquina ".

					" WHERE subtotal != 0 AND ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND   $impresion GROUP BY  MONTH(fecha), resumen_maquina_ex.id_maquina ".

					" ORDER BY resumen_maquina_ex.id_maquina, fecha ASC";	

		 }

		 else

		 {

		 $qExt	=	" SELECT  SUM(subtotal) AS total, resumen_maquina_im.id_maquina, numero, MONTH(fecha) AS mes FROM resumen_maquina_im "	.

		  			" INNER JOIN impresion 			ON resumen_maquina_im.id_impresion 		= impresion.id_impresion ".

					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 	= impresion.id_entrada_general ".

					" INNER JOIN maquina 			ON resumen_maquina_im.id_maquina 		= maquina.id_maquina ".

					" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND   $impresion GROUP BY  MONTH(fecha), resumen_maquina_im.id_maquina ".

					" ORDER BY maquina.area, maquina.numero, fecha ASC";

					

	  	$qDias =	" SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_im.id_maquina, maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_im "	.

		  			" INNER JOIN impresion 			ON resumen_maquina_im.id_impresion 		= impresion.id_impresion ".

					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 	= impresion.id_entrada_general ".

					" INNER JOIN maquina 			ON resumen_maquina_im.id_maquina 		= maquina.id_maquina ".

					" WHERE subtotal != 0 AND ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND   $impresion GROUP BY  MONTH(fecha), resumen_maquina_im.id_maquina ".

					" ORDER BY maquina.area, maquina.numero, fecha ASC";						

					

					

					

		 }

					

		  $rExt	=	mysql_query($qExt);

		  $rDias	=	mysql_query($qDias);

		  

		  for($t=0; $dExt	=	mysql_fetch_assoc($rExt);$t++){		  

		  		$diaria[$t]	=	$dExt['total'];	

				$id[$t]		=	$dExt['numero'];

				$mes_o[$t]	=	$dExt['mes'];

		  }



		  for($t=0; $dDias	=	mysql_fetch_assoc($rDias);$t++){		  

		  		$Dias[$t]	=	$dDias['paros'];

				$id_d[$t]	=	$dDias['numero'];

				$mes_d[$t]	=	$dDias['mes'];



		  }



		  

		  for($t=0,$b=0; $t < sizeof($diaria);$t++){		



				if($id_d[$b] == $id[$t]){ 

					$diaria_d[$t]	=	ceil($diaria[$t]/$Dias[$b]);

					$b=$b+1;

				}

				else

				$diaria_d[$t]	=	0;

				

				if($mes_o[$t] == $m1)

				$total_1	+= 	ceil($diaria_d[$t]);

				if($mes_o[$t] == $m2)

				$total_2	+= 	ceil($diaria_d[$t]);

				if($mes_o[$t] == $m3)

				$total_3	+= 	ceil($diaria_d[$t]);				

				}

		  



		  if($dMes_act['area']	 == 1){		

  		  $qExtD	=	" SELECT  SUM(subtotal) AS total, id_maquina FROM resumen_maquina_ex "	.

		  				" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".

						" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".

						" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND $impresion GROUP BY  MONTH(fecha) ".

						" ORDER BY id_maquina, fecha ASC";

		  }

		  else 

		  {

  		  $qExtD	=	" SELECT  SUM(subtotal) AS total, id_maquina FROM resumen_maquina_im "	.

		  				" INNER JOIN impresion 	ON resumen_maquina_im.id_impresion 	= impresion.id_impresion ".

						" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= impresion.id_entrada_general ".

						" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND $impresion GROUP BY  MONTH(fecha) ".

						" ORDER BY id_maquina, fecha ASC";		  

		  }		

		  $rExtD	=	mysql_query($qExtD);



		  for($t=0; $dExtD	=	mysql_fetch_assoc($rExtD);$t++){		  

		  	//	$total[$t]	=	$dExtD['total'];

		  }





?>

<form name="aceptar_meta" method="post" action="">

<table align="center" width="75%">

	<tr>

    	<td width="186"><h3>Maquina</h3></td>

        <td width="125"><h3><?=$mes[$m3]?></h3></td>

        <td width="140"><h3><?=$mes[$m2]?></h3></td>

        <td width="128"><h3><?=$mes[$m1]?></h3></td>

        <td width="137"><h3><?=$mes[$m]?></h3></td>

    </tr>

 	<? for($a=0,$b=0;$a< $Nmaq;$a++){  ?>

	<tr>

    	<td align="left" class="style5"><?=($area[$a]==3)?"Linea de Impr. ".$num[$a].' - '.$marca[$a]:$num[$a].' - '.$marca[$a]; ?></td>

		<? for($t=0; $t<3;$t++){ ?>

        	 

        <td align="right" class="style5"><?=$diaria_d[$b]?></td>

	    <?  $b++;} ?>

        <td class="style4" align="right"><?=$nueva_meta[$a]?></td>

    </tr>

    <? } ?>

    <tr>

        <td align="right"><h3>Total H.D.</h3></td>

        <td align="right" class="style5"><?=$total_1?></td>

        <td align="right" class="style5"><?=$total_2?></td>

        <td align="right" class="style5"><?=$total_3?></td>

        <td align="right" class="style4"><?=$dMes_act['total_dia']?></td>

    </tr>    

</table>



<?



} if($dMes_act['area'] == 3) { ?>

<form name="aceptar_meta" method="post" action="">

  <table width="75%" align="center">

    <tr>

      <td colspan="6">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="6">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="6"><h3>META BOLSEO

        <?=$mes[$dato['mes']];?>

        del

        <?=$dato['ano']?>

      </h3></td>

    </tr>

    <tr>

      <td width="113" align="left" class="style7">Meta mensual: </td>

      <td colspan="3" class="style5"><? printf("%.2f",$dMes_act['meta_mes_millar']);?> Millares</td>

    </tr>

    <tr>

      <td align="left" class="style7">Meta mensual: </td>

      <td class="style5" colspan="3"><? printf("%.2f",$dMes_act['meta_mes_kilo']);?> Kilos</td>

    </tr>

    <tr>

      <td colspan="4"><h3>Desperdicios</h3></td>

    </tr>

    <tr>

      <td align="left" class="style7">Tira :</td>

      <td width="23" class="style5"><? printf("%.2f",$dMes_act['mes_tira']);?></td>

      <td width="24" align="left" class="style7">% :</td>

      <td width="25" class="style5"><? printf("%.2f",$dMes_act['porcentaje_desp']);?></td>

    </tr>

    <tr>

      <td align="left" class="style7">Troquel :</td>

      <td class="style5"><? printf("%.2f",$dMes_act['mes_troquel']);?></td>

      <td align="left" class="style7">% :</td>

      <td class="style5"><? printf("%.2f",$dMes_act['porcentaje_troquel']);?></td>

    </tr>

    <tr>

      <td align="left" class="style7">Segunda :</td>

      <td class="style5"><? printf("%.2f",$dMes_act['mes_segunda']);?></td>

      <td align="left" class="style7">% :</td>

      <td class="style5"><? printf("%.2f",$dMes_act['porcentaje_segunda']);?></td>

    </tr>

    <tr>

      <td colspan="2"><br />

          <br />

          <br />

        &nbsp;</td>

    </tr>

    <tr>



    </tr>

  </table>

<? } ?>

<table width="75%" align="center">

	<tr>

    	<td colspan="6" align="center"><input type="submit" name="acepta_meta" value="Aceptar Meta" onclick="javascript: return confirm('Usted está a punto de confirmar una nueva meta, \n Si desea realizar algun cambio, regrese y haga clic en modificar, \n si esta satisfecho con la informacion, haga click en Aceptar. ');"/></td>

    </tr>

    <tr>

    	<td>&nbsp;<input type="hidden" name="id_meta" value="<?=$_REQUEST['id_meta']?>" /></td>

    </tr>

	<tr>

      <? if(isset($_SESSION['id_supervisor']) && !isset($_SESSION['id_admin']))  {?>

      <td class="style7" colspan="5" align="center"><span class="style4"><br />

        &nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=listar" class="style7">Listado</a> &nbsp;<strong>|</strong>&nbsp; <br />

        <br />

      </span></td>

      <? } ?>

      <?  ?>

      <td width="571" colspan="6" align="center" class="style7"><span class="style4">&nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&amp;<?=$indice?>=<?=$_REQUEST[$indice]?>" class="contenidos">Modificar Meta</a> &nbsp;<strong>|</strong>&nbsp; <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=listar" class="contenidos">Listado</a> &nbsp;<strong>|</strong>&nbsp; </span></td>

      <?  ?> 

	  </tr></table></form></table> <? }?>

</div></div></body>

