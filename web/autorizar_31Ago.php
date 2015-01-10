<style> @import 'style.css'; </style>



<?php

include('libs/conectar.php');





if(isset($_REQUEST['menio'])){



$areaB			=	$_REQUEST['areaB'];

$desdeB			=	$_REQUEST['desdeB'];

$hastaB			=	$_REQUEST['hastaB'];

$id_supervisorB	=	$_REQUEST['id_supervisorB'];

$turnoB			=	$_REQUEST['turnoB'];

$rolB			=	$_REQUEST['rolB'];

if(isset($_REQUEST['buscar']))

$buscar	=	"&buscar=1";



$url	=	"&area=".$areaB."&desde=".$desdeB."&hasta=".$hastaB."&id_supervisor=".$id_supervisorB."&turno=".$turnoB."&rol=".$rolB.$buscar;



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



if($debug)

  echo "<pre>";



  if(isset($_POST['extruder'])){

  $ID_ORDENPRODUCCION	=	$_REQUEST['id_extruder'];

  $nueva_fecha	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);



  if(isset($_SESSION['id_admin']) && $_REQUEST['repe'] == 0){//antes era autorizada = 0

	  $qGeneral	=	"UPDATE entrada_general  SET  fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno']." , autorizada = '1', repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  }

  if(isset($_SESSION['id_admin']) && $_REQUEST['repe'] == 1){

	  $qGeneral	=	"UPDATE entrada_general  SET  fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno']." , autorizada = '1', repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  }	

  if(isset($_SESSION['id_admin']) && $_REQUEST['pendiente'] == 1){

	  $qGeneral	=	"UPDATE entrada_general  SET  fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno'].", repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."'  WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  }	

  if(!isset($_SESSION['id_admin'])){

	  $qGeneral	=	"UPDATE entrada_general  SET  fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno'].", repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  }

  

  pDebug($qGeneral);

  $rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");



  $ID_GENERAL	=	mysql_insert_id();

  

  

  $qOrdenProduccion	=	"UPDATE orden_produccion  SET total = '{$_POST[total_extruder]}' , desperdicio_tira = '{$_POST[desperdicio_tira]}' , desperdicio_duro = '{$_POST[desperdicio_duro]}', observaciones = '{$_POST[observaciones_generales]}',  k_h = '{$_POST[k_h]}' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  

  $qOrdenID	=	"SELECT * FROM orden_produccion WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  $rOrdenID	=	mysql_query($qOrdenID);

  $dOrdenID	=	mysql_fetch_assoc($rOrdenID);

  

  pDebug($qOrdenProduccion);

  $rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");

//	$ID_ORDENPRODUCCION	=	mysql_insert_id();

  

  $nMaquinas	=	sizeof($_POST['codigos']);

  

  

  for($i=0;$i<$nMaquinas;$i++)

  {

	  

	  $sufijo				=	$_POST['codigos'][$i];

	  $subtotal			=	floatval($_POST['subtotal_extruder'.$sufijo]);

	  $id_maquina			=	intval($_POST['id_maquina'.$sufijo]);

	  $id_resumen_maquina_ex = intval($_POST['id_resumen_maquina_ex'.$sufijo]);

	  $id_operador		=	intval($_POST['id_operador'.$sufijo]);



	  $qVerificaMaquina	=	"SELECT * FROM resumen_maquina_ex WHERE id_resumen_maquina_ex = ".$id_resumen_maquina_ex."";

	  $rVerificaMaquina	=	mysql_query($qVerificaMaquina);

	  

	  $nVerificaMaquina	= 	mysql_num_rows($rVerificaMaquina);

	  

	  if($nVerificaMaquina > 0){	

		  $qResumenMaquinaEx	=	"UPDATE resumen_maquina_ex SET id_operador = '$id_operador',  subtotal = '$subtotal', observacion = '$observacion' WHERE id_resumen_maquina_ex = ".$id_resumen_maquina_ex." ";

	  }

	  if($nVerificaMaquina == 0)

	  {

		  $qResumenMaquinaEx	=	"INSERT INTO resumen_maquina_ex (id_orden_produccion, id_maquina, id_operador, subtotal, observacion) ".

								  "VALUES ('{".$dOrdenID['id_orden_produccion']."}','$id_maquina','$id_operador','$subtotal','$observacion')";

	  }

	  pDebug($qResumenMaquinaEx);

	  $rResumenMaquinaEx	=	mysql_query($qResumenMaquinaEx) OR die("<p>$qResumenMaquinaEx</p><p>".__LINE__."</p><p>".mysql_error()."</p>");



	  $qVerificaMaquinaD	=	"SELECT * FROM detalle_resumen_maquina_ex WHERE id_resumen_maquina_ex = ".$id_resumen_maquina_ex."";

	  //pDebug($qVerificaMaquinaD);

	  $rVerificaMaquinaD	=	mysql_query($qVerificaMaquinaD);

	  $nVerificaMaquinaD	=	mysql_num_rows($rVerificaMaquinaD);



	  $ID_RES_MAQUINAEX	=	mysql_insert_id();

	  

	  $nEntradas			=	sizeof($_POST["ot_extruder".$sufijo]); 

			  

	  $flag = false;

	  

	  for($j=0;$j<$nEntradas;$j++)

	  {

		  $ot_extruder		=	$_POST["ot_extruder".$sufijo][$j];

		  $kg_extruder		=	$_POST["kg_extruder".$sufijo][$j];

		  $id_detalle_resumen_maquina_ex = intval($_POST['id_detalle_resumen_maquina_ex'.$sufijo][$j]);

		  if((empty($ot_extruder) || empty($kg_extruder)) && $id_detalle_resumen_maquina_ex != '0'){

			  $dBorrar	=	"DELETE FROM detalle_resumen_maquina_ex WHERE (id_detalle_resumen_maquina_ex = ".$id_detalle_resumen_maquina_ex.") ";

			  $rBorrar	=	mysql_query($dBorrar);

			  

			  }

		  if((empty($ot_extruder) || empty($kg_extruder)) && $id_detalle_resumen_maquina_ex == '0'){

			  continue;

			  }

			  

			  

		  if(!empty($ot_extruder) || !empty($kg_extruder)){

		  /* Esto sitácticamente puede ir en una línea, pero extrañamente PHP tiene pedos con las pilas con asignaciones contiguas */

			  $qVerificaMaquinaDR	=	"SELECT * FROM detalle_resumen_maquina_ex WHERE id_detalle_resumen_maquina_ex = ".$id_detalle_resumen_maquina_ex."";

	  //pDebug($qVerificaMaquinaD);

			  $rVerificaMaquinaDR	=	mysql_query($qVerificaMaquinaDR);

			  $nVerificaMaquinaDR	=	mysql_num_rows($rVerificaMaquinaDR);

		  //$qDetalleResumen	.=	($j>0)?",":"";

		  

		  if($nVerificaMaquinaDR > 0){

		  $qDetalleResumen	= " UPDATE detalle_resumen_maquina_ex SET orden_trabajo = '$ot_extruder', kilogramos = '$kg_extruder' WHERE id_detalle_resumen_maquina_ex = '".$id_detalle_resumen_maquina_ex."' ";

		  }

		  if($nVerificaMaquinaDR == 0){

		  $qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_ex (id_resumen_maquina_ex,orden_trabajo,kilogramos) VALUES ('$id_resumen_maquina_ex','$ot_extruder','$kg_extruder')";

		  }

  

	  pDebug($qDetalleResumen);

	  

	  $rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

	  

		  }				

	  }

  }	

	  

  $id_pro	=	 $_POST['id_produccion'];

  $nMaquinas	=	sizeof($_POST['tiempos_m']);

  

  for($i=0;$i<$nMaquinas;$i++)

  {

	  $NOrden =   $dOrdenID['id_orden_produccion'];

	  $sufijo				=	$_POST['tiempos_m'][$i];

	  $id_maquina			=	intval($_POST['id_maquina'.$sufijo]);

	  $id_operador		=	intval($_POST['id_operador'.$sufijo]);

  

	  

	  if($_POST['fp_extruder'.$sufijo] == 1){

		  if($_REQUEST['id_turno'] == '1')  $falta_personal = "08:00:00"; 

		  if($_REQUEST['id_turno'] == '2')  $falta_personal =	"07:00:00"; 

		  if($_REQUEST['id_turno'] == '3')  $falta_personal =	"09:00:00";		

	  }

	  

	  if($_POST['fp_extruder'.$sufijo] != 1){

	  $falta_personal		=	"00:00:00";

	  }			

	  

	  if($_POST['mallas'.$sufijo] == 1){

	  $mallas		=	"1";

	  }	

	  if($_POST['mallas'.$sufijo] != 1){

	  $mallas		=	"0";

	  }		

	  

	  

	  $otras				=	$_POST['oh_extruder'.$sufijo].':'.$_POST['om_extruder'.$sufijo].':00';



	  if($_REQUEST['fallo_general']  == 1){

	  $horas 		=	$_POST['horas_fallo'];

	  $minutos	=	$_POST['minutos_fallo'];

	  $fallo_electrico 	=	$horas.':'.$minutos.':00';

	  } else {

	  $fallo_electrico	=	'00:00:00';

	  }



	  $mantenimiento		=	$_POST['mh_extruder'.$sufijo].':'.$_POST['mm_extruder'.$sufijo].':00';

	  

	  

	  $observacion		=	$_POST['ob_extruder'.$sufijo];



	  $qActualizaTiemposEx			=	"UPDATE tiempos_muertos".

										  " SET  falta_personal = '$falta_personal' , observaciones = '$observacion', fallo_electrico = '$fallo_electrico', mantenimiento = '$mantenimiento', mallas = '$mallas' , otras = '$otras' ".

										  " WHERE id_produccion = ".$NOrden." AND id_maquina = ".$id_maquina." ";

							  

  //	$qResumenMaquinaEx	=	"INSERT INTO tiempos_muertos (id_produccion, id_maquina, id_operador, falta_personal, observaciones, fallo_electrico, mantenimiento, tipo, mallas) ".

  //							"VALUES ('$ID_ORDENPRODUCCION','$id_maquina', '$id_operador', '$falta_personal', '$observacion', '$fallo_electrico','$mantenimiento', '1','$mallas')";

	  pDebug($qActualizaTiemposEx);

	  $rActualizaTiemposEx	=	mysql_query($qActualizaTiemposEx) OR die("<p>$qActualizaTiemposEx</p><p>".mysql_error()."</p>");

	  $ID_RES_MAQUINAEX	=	mysql_insert_id();



  }

		  

  

  pDebug("Terminado");

  pDebug("Comienza volcado de la petición");







  if($_REQUEST['menio'] == 0 && !isset($_REQUEST['pendiente']))

  echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion=2&accion=metas&id_entrada_general='.$ID_ORDENPRODUCCION.'&turno='.$_POST['id_turno'].'\';</script>';

  else if($_REQUEST['menio'] == 0 && isset($_REQUEST['pendiente']))

  echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion='.$_REQUEST['seccion'].'&accion=listarPendientes\';</script>';

  else if($_REQUEST['menio'] == 1 && isset($_SESSION['id_admin']))

  echo '<script laguaje="javascript">location.href=\'admin.php?seccion=30'.$_REQUEST['url'].'\';</script>';   



  }

  /* -- Termina parte de Extruder -- */



  

  /* Comienza Impresión */

  if(isset($_POST['impresion'])){

  

  $nueva_fecha	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

  $ID_IMPRESION	=	$_REQUEST['id_impresion'];



  if(isset($_SESSION['id_admin']) && $_REQUEST['repe'] == 0){//antes era autorizada = 0

  $qGeneral	=	"UPDATE entrada_general SET fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno']." , autorizada = '1', repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  }

  if(isset($_SESSION['id_admin']) && $_REQUEST['repe'] == 1){

  $qGeneral	=	"UPDATE entrada_general SET fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno']." , autorizada = '1', repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  }	

  if(isset($_SESSION['id_admin']) && $_REQUEST['pendiente']){

  $qGeneral	=	"UPDATE entrada_general SET fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno'].",  repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."'  WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  }

  if(!isset($_SESSION['id_admin'])){

  $qGeneral	=	"UPDATE entrada_general SET fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno'].", repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."'  WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  }

  pDebug($qGeneral);

  $rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");

  

  $qImpresion		=	"UPDATE impresion  SET total_hd = '{$_POST[total_impr_hd]}', total_bd = '{$_POST[total_impr_bd]}', desperdicio_hd = '{$_POST[total_desperdicio_hd]}', desperdicio_bd = '{$_POST[total_desperdicio_bd]}', observaciones = '{$_POST[observaciones_generales]}', k_h = '{$_POST[k_h]}' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

  pDebug($qImpresion);

		  

  $rImpresion		=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");

  //$ID_IMPRESION	=	mysql_insert_id();



  $nMaquinas	=	sizeof($_POST['codigos2']);	



  for($i=0;$i<$nMaquinas;$i++)

  {

	  $sufijo				=	$_POST['codigos2'][$i];

	  $subtotal			=	floatval($_POST['subtotal_impr'.$sufijo]);

	  $subtotalbd			=	floatval($_POST['subtotalbd_impr'.$sufijo]);

	  $id_maquina			=	intval($_POST['id_maquina2_'.$sufijo]);

	  $id_resumen_maquina_im = intval($_POST['id_resumen_maquina_im'.$sufijo]);

	  $id_operador		=	intval($_POST['id_operador2_'.$sufijo]);



	  

	  $observacion		=	$_POST['ob_impr'.$sufijo];





	  /* La segunda entrada es para impresión y mandamos linea_impresion = 0  */

		  

	  

	  $qOrdenIM	=	"SELECT * FROM impresion WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";

	  $rOrdenIM	=	mysql_query($qOrdenIM);

	  $dOrdenIM	=	mysql_fetch_assoc($rOrdenIM);

	  

			  

  

	  $qVerificaMaquinaIM	=	"SELECT * FROM resumen_maquina_im WHERE id_resumen_maquina_im = '".$id_resumen_maquina_im."'";

	  $rVerificaMaquinaIM	=	mysql_query($qVerificaMaquinaIM);

	  

	  $nVerificaMaquinaIM	= 	mysql_num_rows($rVerificaMaquinaIM);

	  

	  if($nVerificaMaquinaIM > 0){	

	  $qResumenMaquinaIM			=	"UPDATE resumen_maquina_im  SET  id_operador = '$id_operador',  subtotal = '$subtotal', subtotal_bd='$subtotalbd', observacion =  '$observacion' WHERE id_resumen_maquina_im = ".$id_resumen_maquina_im." ";

	  }

	  if($nVerificaMaquinaIM == 0)

	  {

		  $qResumenMaquinaIM	=	"INSERT INTO resumen_maquina_im (id_impresion, id_maquina, id_operador, subtotal, subtotal_bd, observacion) ".

								  "VALUES ('".$dOrdenID['id_impresion']."','$id_maquina','$id_operador','$subtotal','$subtotalbd', '$observacion')";

	  }

	  pDebug($qResumenMaquinaIM);

	  $rResumenMaquinaIM	=	mysql_query($qResumenMaquinaIM) OR die("<p>$qResumenMaquinaIM</p><p>".__LINE__."</p><p>".mysql_error()."</p>");



	  $qVerificaMaquinaDIM	=	"SELECT * FROM detalle_resumen_maquina_im WHERE id_resumen_maquina_im = '".$id_resumen_maquina_im."'";

	  //pDebug($qVerificaMaquinaD);

	  $rVerificaMaquinaDIM	=	mysql_query($qVerificaMaquinaDIM);

	  $nVerificaMaquinaDIM	=	mysql_num_rows($rVerificaMaquinaDIM);

			  



	  $ID_RES_MAQUINAIM	=	mysql_insert_id();

	  

	  $nEntradas			=	sizeof($_POST["ot_impresion".$sufijo]);

	  $qDetalleResumen	=	"UPDATE  detalle_resumen_maquina_im SET ";

	  

	  $flag = false;

	  for($j=0;$j<$nEntradas;$j++)

	  {

		  $ot_impresion		=	$_POST["ot_impresion".$sufijo][$j];

		  $kg_impresion		=	$_POST["kg_impresion".$sufijo][$j];

		  $bd_impresion		=	$_POST["bd_impresion".$sufijo][$j];

		  

		  $id_detalle_resumen_maquina_im = intval($_POST['id_detalle_resumen_maquina_im'.$sufijo][$j]);

		  if((empty($ot_impresion) || empty($kg_impresion)) && $id_detalle_resumen_maquina_im != '0'){

			  $dBorrar	=	"DELETE FROM detalle_resumen_maquina_im WHERE (id_detalle_resumen_maquina_im = ".$id_detalle_resumen_maquina_im.") ";

			  $rBorrar	=	mysql_query($dBorrar);

			  

			  }

		  if((empty($ot_impresion) || empty($kg_impresion)) && $id_detalle_resumen_maquina_im == '0'){

			  continue;

			  }

			  

			  

		  if(!empty($ot_impresion) || !empty($kg_impresion)){

			  $qVerificaMaquinaDIM	=	"SELECT * FROM detalle_resumen_maquina_im WHERE id_detalle_resumen_maquina_im = ".$id_detalle_resumen_maquina_im."";

			  $rVerificaMaquinaDIM	=	mysql_query($qVerificaMaquinaDIM);

			  $nVerificaMaquinaDIM	=	mysql_num_rows($rVerificaMaquinaDIM);

		  

		  if($nVerificaMaquinaDIM > 0){

		  $qDetalleResumen	= " UPDATE detalle_resumen_maquina_im SET orden_trabajo = '$ot_impresion', kilogramos = '$kg_impresion', bd = '$bd_impresion' WHERE id_detalle_resumen_maquina_im = '".$id_detalle_resumen_maquina_im."' ";

		  }

		  if($nVerificaMaquinaDIM == 0){

		  $qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_im (id_resumen_maquina_im,orden_trabajo,kilogramos,bd) VALUES ('$id_resumen_maquina_im','$ot_impresion','$kg_impresion','$bd_impresion')";

		  }

  

	  pDebug($qDetalleResumen);

	  

	  $rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

	  

	  }				

	  }

  }

	  

  /* -- Termina Impresión -- */

  /* Comienza parte líneas de impresión */

  $nMaquinas	=	sizeof($_POST['codigos3']);

  

  for($i=0;$i<$nMaquinas;$i++)

  {

	  $sufijo				=	$_POST['codigos3'][$i];

	  $subtotal			=	floatval($_POST['subtotal_limpr'.$sufijo]);

	  $subtotalbd			=	floatval($_POST['subtotalbd_limpr'.$sufijo]);

	  $id_maquina			=	intval($_POST['id_maquina3_'.$sufijo]);

	  $id_resumen_maquina_lim = intval($_POST['id_resumen_maquina_lim'.$sufijo]);

	  $id_operador		=	intval($_POST['id_operador3_'.$sufijo]);







	  $qVerificaMaquinaLIM	=	"SELECT * FROM resumen_maquina_im WHERE id_resumen_maquina_im = '".$id_resumen_maquina_lim."'";

	  $rVerificaMaquinaLIM	=	mysql_query($qVerificaMaquinaLIM);

	  

	  $nVerificaMaquinaLIM	= 	mysql_num_rows($rVerificaMaquinaLIM);

	  

	  if($nVerificaMaquinaLIM > 0){	

	  $qResumenMaquinaLIM			=	"UPDATE resumen_maquina_im  SET   subtotal = '$subtotal', subtotal_bd='$subtotalbd', observacion =  '$observacion' WHERE id_resumen_maquina_im = ".$id_resumen_maquina_lim." ";

	  }

	  if($nVerificaMaquinaLIM == 0)

	  {

		  $qResumenMaquinaLIM	=	"INSERT INTO resumen_maquina_im (id_impresion, id_maquina, id_operador, subtotal,subtotal_bd, observacion) ".

								  "VALUES ('".$dOrdenIM['id_impresion']."','$id_maquina','$id_operador','$subtotal','$subtotalbd', '$observacion')";

	  }

	  pDebug($qResumenMaquinaLIM);

		  $rResumenMaquinaLIM	=	mysql_query($qResumenMaquinaLIM) OR die("<p>$qResumenMaquinaLIM</p><p>".__LINE__."</p><p>".mysql_error()."</p>");



	  /* La segunda entrada es para líneas de impresión y mandamos linea_impresion = 1  */

	  $qImpresion2			=	"UPDATE resumen_maquina_im SET  subtotal = '$subtotal' WHERE id_resumen_maquina_im = ".$id_resumen_maquina_lim."";

	  pDebug($qImpresion2);

	  $rImpresion2			=	mysql_query($qImpresion2) OR die("<p>$qImpresion2</p><p>".mysql_error()."</p>");

	  $ID_RES_MAQUINAIM	=	mysql_insert_id();

	  

	  $nEntradas			=	sizeof($_POST["ot_limpresion".$sufijo]);

	  $qDetalleResumen	=	"UPDATE detalle_resumen_maquina_im  SET ";

	  

	  $flag = false;

	  for($j=0;$j<$nEntradas;$j++)

	  {

		  $ot_impresion		=	$_POST["ot_limpresion".$sufijo][$j];

		  $kg_impresion		=	$_POST["kg_limpresion".$sufijo][$j];

		  $bd_impresion		=	$_POST["bd_limpresion".$sufijo][$j];

		  $id_detalle_resumen_maquina_lim = intval($_POST['id_detalle_resumen_maquina_lim'.$sufijo][$j]);

		  if((empty($ot_impresion) || empty($kg_impresion)) && $id_detalle_resumen_maquina_lim != '0'){

			  $qBorrar	=	"DELETE FROM detalle_resumen_maquina_im WHERE (id_detalle_resumen_maquina_im = ".$id_detalle_resumen_maquina_lim.") ";

			  pDebug($qBorrar);	

			  $rBorrar	=	mysql_query($qBorrar);

			  

			  }

		  if((empty($ot_impresion) || empty($kg_impresion)) && $id_detalle_resumen_maquina_lim == '0'){

			  continue;

			  }

			  

			  

		  if(!empty($ot_impresion) || !empty($kg_impresion)){

			  $qVerificaMaquinaDLIM	=	"SELECT * FROM detalle_resumen_maquina_im WHERE id_detalle_resumen_maquina_im = ".$id_detalle_resumen_maquina_lim."";

			  $rVerificaMaquinaDLIM	=	mysql_query($qVerificaMaquinaDLIM);

			  $nVerificaMaquinaDLIM	=	mysql_num_rows($rVerificaMaquinaDLIM);			

		  /* Esto sitácticamente puede ir en una línea, pero extrañamente PHP tiene pedos con las pilas con asignaciones contiguas */

			  pDebug($nVerificaMaquinaDLIM);

		  //$qDetalleResumen	.=	($j>0)?",":"";

		  

		  if($nVerificaMaquinaDLIM > 0){

		  $qDetalleResumen	= " UPDATE detalle_resumen_maquina_im SET orden_trabajo = '$ot_impresion', kilogramos = '$kg_impresion', bd = '$bd_impresion'   WHERE id_detalle_resumen_maquina_im = '".$id_detalle_resumen_maquina_lim."' ";

		  }

		  if($nVerificaMaquinaDLIM == 0){

		  $qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_im (id_resumen_maquina_im,orden_trabajo,kilogramos,bd) VALUES ('$id_resumen_maquina_lim','$ot_impresion','$kg_impresion', '$bd_impresion')";

		  }

		  

	  pDebug($qDetalleResumen);

	  

	  $rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

			  }

  

	  }



  }

  

  $id_pro	=	 $_POST['id_produccion'];

  $nMaquinas	=	sizeof($_POST['tiempos_impr']);

  

  for($i=0;$i<$nMaquinas;$i++)

  {

	  $ID_GENERAL			=	$dOrdenIM['id_impresion'];

	  

	  $sufijo				=	$_POST['tiempos_impr'][$i];

	  $id_maquina			=	intval($_POST['id_maquina2m_'.$sufijo]);

	  

	  

	  if($_POST['fp_impr'.$sufijo] == 1){

		  if($_REQUEST['id_turno'] == '1') $falta_personal = "08:00:00"; 

		  if($_REQUEST['id_turno'] == '2') $falta_personal =	"07:00:00"; 

		  if($_REQUEST['id_turno'] == '3') $falta_personal =	"09:00:00";	

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



	  $otras				=	$_POST['oh_impr'.$sufijo].':'.$_POST['om_impr'.$sufijo].':00';



  if($_REQUEST['fallo_general']  == 1){

	  $horas 		=	$_POST['horas_fallo'];

	  $minutos	=	$_POST['minutos_fallo'];

	  $fallo_electrico 	=	$horas.':'.$minutos.':00';

	  } else {

	  $fallo_electrico	=	'00:00:00';

	  }



	  $mantenimiento		=	$_POST['mh_impr'.$sufijo].':'.$_POST['mm_impr'.$sufijo].':00';

	  $observacion		=	$_POST['ob_impr'.$sufijo];





	  $qActualizaTiemposImpr			=	"UPDATE tiempos_muertos".

										  " SET  falta_personal = '$falta_personal' , observaciones = '$observacion', fallo_electrico = '$fallo_electrico', mantenimiento = '$mantenimiento', cambio_impresion = '$cambio_impresion', otras = '$otras'  ".

										  " WHERE id_produccion = ".$ID_GENERAL." AND id_maquina = ".$id_maquina." ";



	  /* La segunda entrada es para impresión y mandamos linea_impresion = 0  */

	  //$qImpresion			=	"INSERT INTO tiempos_muertos (id_produccion, id_maquina, id_operador, falta_personal, observaciones, fallo_electrico, mantenimiento, cambio_impresion, tipo) VALUES ".

	  //						"('$ID_GENERAL','$id_maquina',  '$id_operador','$falta_personal', '$observacion','$fallo_electrico','$mantenimiento', '$cambio_impresion','2')";

	  pDebug($qActualizaTiemposImpr);

	  $rActualizaTiemposImpr			=	mysql_query($qActualizaTiemposImpr) OR die("<p>$qActualizaTiemposImrp</p><p>".mysql_error()."</p>");

	  //$ID_RES_MAQUINAIM	=	mysql_insert_id();

	  

  }



  /* -- Termina Impresión -- */

  /* Comienza parte líneas de impresión */

  $nMaquinas	=	sizeof($_POST['tiempos_limpr']);

  

  for($i=0;$i<$nMaquinas;$i++)

  {

	  $ID_GENERAL			=	$dOrdenIM['id_impresion'];

	  

	  $sufijo				=	$_POST['tiempos_limpr'][$i];

	  $id_maquina			=	intval($_POST['id_maquina3m_'.$sufijo]);



	  if($_POST['fp_limpr'.$sufijo] == 1){

		  if($_REQUEST['id_turno'] == '1') $falta_personal = 	"08:00:00"; 

		  if($_REQUEST['id_turno'] == '2') $falta_personal =	"07:00:00"; 

		  if($_REQUEST['id_turno'] == '3') $falta_personal =	"09:00:00";	

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

	  $otras					=	$_POST['oh_limpr'.$sufijo].':'.$_POST['om_limpr'.$sufijo].':00';



	  if($_REQUEST['fallo_general']  == 1){

	  $horas 		=	$_POST['horas_fallo'];

	  $minutos	=	$_POST['minutos_fallo'];

	  $fallo_electrico 	=	$horas.':'.$minutos.':00';

	  } else {

	  $fallo_electrico	=	'00:00:00';

	  }



	  $mantenimiento			=	$_POST['mh_limpr'.$sufijo].':'.$_POST['mm_limpr'.$sufijo].':00';

	  $observacion			=	$_POST['ob_limpr'.$sufijo];

	  

	  /* La segunda entrada es para líneas de impresión y mandamos linea_impresion = 1  */

	  $qActualizaTiemposLimpr			=	"UPDATE tiempos_muertos".

										  " SET  falta_personal = '$falta_personal' , observaciones = '$observacion', fallo_electrico = '$fallo_electrico', mantenimiento = '$mantenimiento', cambio_impresion = '$cambio_impresion', otras = '$otras'  ".

										  " WHERE id_produccion = ".$ID_GENERAL." AND id_maquina = ".$id_maquina." ";



	  pDebug($qActualizaTiemposLimpr);

	  $rActualizaTiemposLimpr			=	mysql_query($qActualizaTiemposLimpr) OR die("<p>$qActualizaTiemposLimpr</p><p>".mysql_error()."</p>");

  //	$ID_RES_MAQUINAIM	=	mysql_insert_id();

	  

  }

  

  



  /* -- Termina parte de Impresión */



  pDebug("Terminado");

  pDebug("Comienza volcado de la petición");



  if($_REQUEST['menio'] == 0 && !isset($_REQUEST['pendiente']) && isset($_REQUEST['id_admin']))

  echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion=20&accion=metas&id_entrada_general='.$ID_IMPRESION.'&turno='.$_REQUEST['id_turno'].'\';</script>';

  if($_REQUEST['menio'] == 0 && !isset($_REQUEST['pendiente']) && !isset($_REQUEST['id_admin']))

  echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion=20&accion=metas&id_entrada_general='.$ID_IMPRESION.'&turno='.$_REQUEST['id_turno'].'\';</script>';

  else if($_REQUEST['menio'] == 0 && isset($_REQUEST['pendiente']))

  echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion='.$_REQUEST['seccion'].'&accion=listarPendientes\';</script>';

  else if($_REQUEST['menio'] == 1 && isset($_SESSION['id_admin']))

  echo '<script laguaje="javascript">location.href=\'admin.php?seccion=30'.$_REQUEST['url'].'\';</script>';   

  

  }  

  



if(isset($_POST['bolseo'])){

  

  $debug = false;

  if($debug)

  {	

	  echo "<pre>";

	  print_r($_REQUEST);

  }



  $nMaquinas	=	sizeof($_POST['id_maquinas']);

  $ID_BOLSEO	=	$_REQUEST['id_bolseo'];

  $nueva_fecha =	preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

  

  if(isset($_SESSION['id_admin']) && !isset($_REQUEST['pendiente']) && $_REQUEST['repe'] == 1){

	  $qBolseo	=	"UPDATE bolseo SET fecha = '$nueva_fecha' ,turno = ".$_REQUEST['id_turno']." , kilogramos = '{$_POST[tkg]}', millares = '{$_POST[tml]}',dtira = '{$_POST[tdt]}',dtroquel = '{$_POST[tdtr]}',segundas = '{$_POST[tse]}' ,autorizada = '1', m_p = '".$_POST['m_p']."', observaciones = '{$_POST[observaciones_generales]}',  repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."' WHERE id_bolseo = '".$_POST['id_bolseo']."'";

  }

  if(isset($_SESSION['id_admin']) && !isset($_REQUEST['pendiente']) && $_REQUEST['repe'] == 0){//antes autorizada = '0'

	  $qBolseo	=	"UPDATE bolseo SET fecha = '$nueva_fecha' ,turno = ".$_REQUEST['id_turno']." , kilogramos = '{$_POST[tkg]}', millares = '{$_POST[tml]}',dtira = '{$_POST[tdt]}',dtroquel = '{$_POST[tdtr]}',segundas = '{$_POST[tse]}' ,autorizada = '1', m_p = '".$_POST['m_p']."', observaciones = '{$_POST[observaciones_generales]}',  repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."' WHERE id_bolseo = '".$_POST['id_bolseo']."'";

  }

  

  if(isset($_SESSION['id_admin']) && isset($_REQUEST['pendiente'])){

	  $qBolseo	=	"UPDATE bolseo SET fecha = '$nueva_fecha' ,turno = ".$_REQUEST['id_turno']." , kilogramos = '{$_POST[tkg]}', millares = '{$_POST[tml]}',dtira = '{$_POST[tdt]}',dtroquel = '{$_POST[tdtr]}',segundas = '{$_POST[tse]}' , m_p = '".$_POST['m_p']."', observaciones = '{$_POST[observaciones_generales]}',  repesada = '".$_REQUEST['repe']."', actualizado = '".$_REQUEST['repe']."' WHERE id_bolseo = '".$_POST['id_bolseo']."'";

  }

  

  if(!isset($_SESSION['id_admin'])){

  $qBolseo	=	"UPDATE bolseo SET fecha = '$nueva_fecha' ,turno = ".$_REQUEST['id_turno']." , kilogramos = '{$_POST[tkg]}', millares = '{$_POST[tml]}',dtira = '{$_POST[tdt]}',dtroquel = '{$_POST[tdtr]}',segundas = '{$_POST[tse]}',  m_p = '".$_POST['m_p']."', observaciones = '{$_POST[observaciones_generales]}'  WHERE id_bolseo = '".$_POST['id_bolseo']."'";

  }

  pDebug($qBolseo);

  $rBolseo	=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");

//	$ID_BOLSEO	=	mysql_insert_id();

	  

	  $qOrdenIM	=	"SELECT * FROM bolseo WHERE id_bolseo = ".$_REQUEST['id_bolseo']."";

	  $rOrdenIM	=	mysql_query($qOrdenIM);

	  $dOrdenIM	=	mysql_fetch_assoc($rOrdenIM);



  

  for($i=0; $i<$nMaquinas; $i++)

  {

	  $sufijo					=	$_POST['id_maquinas'][$i];

	  $sKilogramos			=	$_POST["kgs_$sufijo"];

	  $sMillares				=	$_POST["mls_$sufijo"];

	  $sTira					=	$_POST["dts_$sufijo"];

	  $sTroquel				=	$_POST["dtrs_$sufijo"];

	  $sSegundas				=	$_POST["ses_$sufijo"];

	  $id_maquina				=	$sufijo;

	  $id_operador			=	$_POST['id_operadores'][$i];

	  $id_resumen_maquina_bs	=	$_POST['id_resumen_maquina_bs'][$i];





	  $qVerificaMaquina	=	"SELECT * FROM resumen_maquina_bs WHERE id_resumen_maquina_bs = ".$id_resumen_maquina_bs."";

	  $rVerificaMaquina	=	mysql_query($qVerificaMaquina);

	  

	  $nVerificaMaquina	= 	mysql_num_rows($rVerificaMaquina);

	  pDebug($qVerificaMaquina);

	  if($nVerificaMaquina > 0){	

	  $qResumenMaquinaBs		=	"UPDATE resumen_maquina_bs SET id_operador = '$id_operador', kilogramos = '$sKilogramos',millares = '$sMillares' ,dtira = '$sTira' ,dtroquel ='$sTroquel',segundas = '$sSegundas' WHERE id_resumen_maquina_bs = '".$id_resumen_maquina_bs."'";

	  }

	  if($nVerificaMaquina == 0)

	  {

	   $qResumenMaquinaBs		=	"INSERT INTO resumen_maquina_bs (id_maquina, id_operador, kilogramos, millares,	 dtira, dtroquel, segundas) ".

								  "VALUES ('$id_maquina','$id_operador','$sKilogramos','$sMillares','$sTira','$sTroquel','$sSegundas')";

	  }

	  pDebug($qResumenMaquinaBs);

	  $rResumenMaquinaBs	=	mysql_query($qResumenMaquinaBs) OR die("<p>$qResumenMaquinaBs</p><p>".__LINE__."</p><p>".mysql_error()."</p>");



	  $qVerificaMaquinaBS	=	"SELECT * FROM detalle_resumen_maquina_bs WHERE id_resumen_maquina_bs = ".$id_resumen_maquina_bs."";

	  //pDebug($qVerificaMaquinaBS);

	  $rVerificaMaquinaBS	=	mysql_query($qVerificaMaquinaBS);

	  $nVerificaMaquinaBS	=	mysql_num_rows($rVerificaMaquinaBS);		

	  

	  $ID_RESUMEN		=	mysql_insert_id();

	  

	  

	  

	  

	  $nEntradas		=	sizeof($_POST["kg_$sufijo"]);

	  

	  $flag = false;

	  for($j=0;$j<$nEntradas;$j++)

	  {

	  



		  $kg			=	floatval($_POST["kg_$sufijo"][$j]);

		  $ml			=	floatval($_POST["ml_$sufijo"][$j]);

		  $dt			=	floatval($_POST["dt_$sufijo"][$j]);

		  $dtr		=	floatval($_POST["dtr_$sufijo"][$j]);

		  $se			=	floatval($_POST["se_$sufijo"][$j]);	

		  $orden		=	$_POST["ot_$sufijo"][$j];			

		  $kam 		= 	$_REQUEST["kam_$sufijo"][$j];

		  $factor		=	$_POST["factor_$sufijo"][$j];

			  

			  

		  $id_detalle_resumen_maquina_bs = $_POST["id_detalle_resumen_maquina_bs_$sufijo"][$j];

		  

		  if( empty($orden) && empty($kg) && empty($ml) && $id_detalle_resumen_maquina_bs != '0'){

			  $qBorrar	=	"DELETE FROM detalle_resumen_maquina_bs WHERE (id_detalle_resumen_maquina_bs = ".$id_detalle_resumen_maquina_bs.") ";

			  pDebug($qBorrar);	

			  $rBorrar	=	mysql_query($qBorrar);

			  

		  }			

		  

		  if( empty($orden) && empty($kg) && empty($ml) && $id_detalle_resumen_maquina_bs == '0'){

		   continue;

		  }

			  

		  if( (!empty($kg) && !empty($ml)) || !empty($orden) ){



			  $qVerificaMaquinaDBS	=	"SELECT * FROM detalle_resumen_maquina_bs WHERE id_detalle_resumen_maquina_bs = ".$id_detalle_resumen_maquina_bs."";

			  $rVerificaMaquinaDBS	=	mysql_query($qVerificaMaquinaDBS);

			  $nVerificaMaquinaDBS	=	mysql_num_rows($rVerificaMaquinaDBS);			

			  pDebug($qVerificaMaquinaDBS);

		  

		  if($nVerificaMaquinaDBS > 0){

		  $qDetalleResumen	= " UPDATE detalle_resumen_maquina_bs SET orden = '$orden', kilogramos = '$kg', millares = '$ml', dtira = '$dt', dtroquel = '$dtr', segundas = '$se', kam = '$kam', factor = $factor WHERE id_detalle_resumen_maquina_bs = '".$id_detalle_resumen_maquina_bs."' ";

		  }

		  if($nVerificaMaquinaDBS == 0){

		  $qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_bs (id_resumen_maquina_bs, kilogramos, millares, dtira, dtroquel, segundas, orden,kam,factor) VALUES ('$id_resumen_maquina_bs','$kg','$ml','$dt','$dtr','$se','$orden','$kam','$factor')";

		  }

		  

	  pDebug($qDetalleResumen);

	  

	  $rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

	  

	  }



	  }

	  

  }	

  

	  $nMaquinasTM	=	sizeof($_POST['codigos2']);

  

  for($i=0;$i<$nMaquinasTM;$i++)

  {

	  $ID_GENERAL			=	$_POST['id_bolseo'];

	  $sufijo				=	$_POST['codigos2'][$i];

	  $id_maquina			=	intval($_POST['id_maquina2_'.$sufijo]);

	  $id_operador		=	intval($_POST['id_operador2_'.$sufijo]);

	  

	  if($_POST['fp_bolseo'.$sufijo] == 1){

		  if($_REQUEST['id_turno'] == '1')  $falta_personal = "08:00:00"; 

		  if($_REQUEST['id_turno'] == '2')  $falta_personal =	"07:00:00"; 

		  if($_REQUEST['id_turno'] == '3')  $falta_personal =	"09:00:00";	

	  }

	  

	  if($_POST['fp_bolseo'.$sufijo] != 1){

	  $falta_personal		=	"00:00:00";

	  }	

  

	  $otras				=	$_POST['oh_bolseo'.$sufijo].":".$_POST['om_bolseo'.$sufijo].':00';



	  if($_REQUEST['fallo_general'] == 1){

	  $horas 	= $_POST['horas_fallo'];

	  $minutos	=	$_POST['minutos_fallo'];

	  $fallo_electrico 	=	$horas.':'.$minutos.':00';

	  } else {

	  $fallo_electrico	=	'00:00:00';

	  }





	  $mantenimiento		=	$_POST['mh_bolseo'.$sufijo].':'.$_POST['mm_bolseo'.$sufijo].':00';

	  $observacion		=	$_POST['ob_bolseo'.$sufijo];



	  $qActualizaTiemposBs			=	"UPDATE tiempos_muertos SET  falta_personal = '$falta_personal' , observaciones = '$observacion', fallo_electrico = '$fallo_electrico', mantenimiento = '$mantenimiento', otras = '$otras' ".

							  " WHERE id_produccion = ".$ID_GENERAL." AND id_maquina = ".$id_maquina." ";

	  pDebug($qActualizaTiemposBs);

	  

	  $rActualizaTiemposBs			=	mysql_query($qActualizaTiemposBs) OR die("<p>$qActualizaTiemposBs</p><p>".mysql_error()."</p>");

			  

  }	

  

  if($_REQUEST['menio'] == 0 && !isset($_REQUEST['pendiente']))

  echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion=3&accion=metas&id_entrada_general='.$ID_BOLSEO.'&turno='.$_POST['id_turno'].'\';</script>';

  if($_REQUEST['menio'] == 0 && isset($_REQUEST['pendiente']))

  echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion='.$_REQUEST['seccion'].'&accion=listarPendientes\';</script>';

  if($_REQUEST['menio'] == 1 && isset($_SESSION['id_admin']))

  echo '<script laguaje="javascript">location.href=\'admin.php?seccion=30'.$_REQUEST['url'].'\';</script>';    



	  //exit(); 

}

  





if(!empty($_GET['accion']))

{

  $listar					=	($_GET['accion']=="listar")?true:false;

  $listarPendientes		=	($_GET['accion']=="listarPendientes")?true:false;

  $nuevaEntrada 			= 	($_GET['accion']=="nuevaentrada")?true:false;



  if(!empty($_GET['id_reporte']) && is_numeric($_GET['id_reporte']) )

  {

	  $autorizar	=	($_GET['accion']=="autorizar")?true:false;

	  $impresion	=	($_GET['accion']=="impresion")?true:false;

	  $eliminar_extruder	=	($_GET['accion']=="eliminar_extruder")?true:false;

	  $eliminar_impresion	=	($_GET['accion']=="eliminar_impresion")?true:false;

  }

  if(!empty($_GET['id_bolseo']) && is_numeric($_GET['id_bolseo']) )

  {

	  $autorizar_bolseo	=	($_GET['accion']=="autorizar_bolseo")?true:false;

	  $eliminar_bolseo	=	($_GET['accion']=="eliminar_bolseo")?true:false;

  }

  

  if($autorizar)

  {	

	  $qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol, entrada_general.repesada FROM entrada_general INNER JOIN supervisor ".

							  "ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE (id_entrada_general={$_REQUEST[id_reporte]})";

	  $rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");

	  $dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral);

	  

	  $qOrdenProduccion	=	"SELECT * FROM orden_produccion WHERE (id_entrada_general = {$dEntradaGeneral[id_entrada_general]})";

	  $rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");

	  

	  $qActuImpresion	=	"SELECT * FROM impresion WHERE (id_impresion = {$dEntradaGeneral[id_entrada_general]})";

	  $rActuImpresion	=	mysql_query($qActuImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");		

  }

  if($impresion)

  {	

	  $qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol, entrada_general.repesada FROM entrada_general INNER JOIN supervisor ".

							  "ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE (id_entrada_general={$_REQUEST[id_reporte]})";

	  $rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");

	  $dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral);

			  

	  $qActuImpresion	=	"SELECT * FROM impresion WHERE (id_entrada_general = {$dEntradaGeneral[id_entrada_general]})";

	  $rActuImpresion	=	mysql_query($qActuImpresion) OR die("<p>$qActuImpresion</p><p>".mysql_error()."</p>");	

	  

	  

		  $num = mysql_num_rows($rActuImpresion);

		  $dImpresion	=	mysql_fetch_assoc($rActuImpresion);	

  }	

  if($autorizar_bolseo)

  {	



	  $qBolseo	=	"SELECT * FROM bolseo INNER JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor WHERE (id_bolseo={$_REQUEST[id_bolseo]})";

	  $rBolseo	=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");

	  $dBolseo	=	mysql_fetch_assoc($rBolseo);

  

  }	

  

  if($eliminar_extruder)

  {	



  $qSeleccion 	=	"SELECT * FROM orden_produccion WHERE id_entrada_general = '".$_REQUEST['id_reporte']."'";

  $rSeleccion		=	mysql_query($qSeleccion)  OR die("<p>$qSeleccion</p><p>".mysql_error()."</p>");

  $dSeleccion	=	mysql_fetch_assoc($rSeleccion);

		  

		  $qSeleccion2	=	"SELECT * FROM resumen_maquina_ex WHERE id_orden_produccion = ".$dSeleccion['id_orden_produccion']."";

		  $rSeleccion2	=	mysql_query($qSeleccion2) OR die("<p>$qSeleccion2</p><p>".mysql_error()."</p>");

			  while($dSeleccion2	=	mysql_fetch_assoc($rSeleccion2)){

			  

				  $qSeleccion3	=	"DELETE FROM detalle_resumen_maquina_ex	WHERE (id_resumen_maquina_ex = ".$dSeleccion2['id_resumen_maquina_ex'].") ";

				  $rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");

	  

			  }





			  

		  $qSeleccion4	=	"DELETE FROM resumen_maquina_ex	WHERE  (id_orden_produccion = ".$dSeleccion['id_orden_produccion'].") ";

		  $rSeleccion4	=	mysql_query($qSeleccion4) OR die("<p>$qSeleccion4</p><p>".mysql_error()."</p>");



	  $qEliminaTiempos	= "DELETE FROM tiempos_muertos WHERE id_produccion	= '".$dSeleccion['id_orden_produccion']."' AND tipo= 1";

	  $rEliminaTiempos	=	mysql_query($qEliminaTiempos);

				  

		  $qSeleccion3	=	"DELETE FROM orden_produccion WHERE  (id_entrada_general = ".$_REQUEST['id_reporte'].") ";

		  $rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");

					  

	  $qEliminaExtr	=	"DELETE FROM entrada_general WHERE (id_entrada_general=".$_REQUEST['id_reporte'].")";

	  $rEliminaExtr	=	mysql_query($qEliminaExtr) OR die("<p>$qEliminaExtr</p><p>".mysql_error()."</p>");

  

  

	  $redirecciona	=	true;

	  $ruta		=	"http://".$_SERVER['HTTP_HOST']."".$_SERVER['PHP_SELF']."?seccion={$_GET[seccion]}&accion={$_GET[accion2]}&extruder";

  }		

  

  

  if($eliminar_impresion)

  {	



  $qSeleccion 	=	"SELECT * FROM impresion WHERE id_entrada_general = '".$_REQUEST['id_reporte']."'";

  $rSeleccion		=	mysql_query($qSeleccion)  OR die("<p>$qSeleccion</p><p>".mysql_error()."</p>");

  $dSeleccion	=	mysql_fetch_assoc($rSeleccion);

		  

		  $qSeleccion2	=	"SELECT * FROM resumen_maquina_im WHERE id_impresion = ".$dSeleccion['id_impresion']."  ";

		  $rSeleccion2	=	mysql_query($qSeleccion2) OR die("<p>$qSeleccion2</p><p>".mysql_error()."</p>");

			  while($dSeleccion2	=	mysql_fetch_assoc($rSeleccion2)){

			  

				  $qSeleccion3	=	"DELETE FROM detalle_resumen_maquina_im WHERE (id_resumen_maquina_im = ".$dSeleccion2['id_resumen_maquina_im'].") ";

				  $rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");

	  

			  }

	  

			  

		  $qSeleccion4	=	"DELETE FROM resumen_maquina_im	WHERE  (id_impresion = ".$dSeleccion['id_impresion'].") ";

		  $rSeleccion4	=	mysql_query($qSeleccion4) OR die("<p>$qSeleccion4</p><p>".mysql_error()."</p>");		

		  

	  $qEliminaTiempos	= "DELETE FROM tiempos_muertos WHERE id_produccion	= '".$dSeleccion['id_impresion']."' AND tipo= 2";

	  $rEliminaTiempos	=	mysql_query($qEliminaTiempos);

	  

	  $qEliminaTiempos2	= "DELETE FROM tiempos_muertos WHERE id_produccion	= '".$dSeleccion['id_impresion']."' AND tipo= 3";

	  $rEliminaTiempos2	=	mysql_query($qEliminaTiempos2);

				  

		  $qSeleccion3	=	"DELETE FROM impresion WHERE  (id_entrada_general =".$_REQUEST['id_reporte'].") ";

		  $rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");

					  

	  $qEliminaImpr	=	"DELETE FROM entrada_general WHERE (id_entrada_general=".$_REQUEST['id_reporte'].")";

	  $rEliminaImpr	=	mysql_query($qEliminaImpr) OR die("<p>$qEliminaExtr</p><p>".mysql_error()."</p>");

  

	  $redirecciona	=	true;

	  $ruta		=	"http://".$_SERVER['HTTP_HOST']."".$_SERVER['PHP_SELF']."?seccion={$_GET[seccion]}&accion={$_GET[accion2]}&impresion";

  }		

  

  if($eliminar_bolseo)

  {	



  $qSeleccion 	=	"SELECT * FROM resumen_maquina_bs WHERE id_bolseo = '".$_REQUEST['id_bolseo']."'";

  $rSeleccion		=	mysql_query($qSeleccion)  OR die("<p>$qSeleccion</p><p>".mysql_error()."</p>");

	  while($dSeleccion	=	mysql_fetch_assoc($rSeleccion)){

		  

		  $qSeleccion2	=	"DELETE FROM detalle_resumen_maquina_bs	WHERE  (id_resumen_maquina_bs = ".$dSeleccion['id_resumen_maquina_bs'].") ";

		  $rSeleccion2	=	mysql_query($qSeleccion2) OR die("<p>$qSeleccion2</p><p>".mysql_error()."</p>");

	  

	  }

	  

		  $qSeleccion3	=	"DELETE FROM resumen_maquina_bs	WHERE  (id_bolseo = ".$_REQUEST['id_bolseo'].") ";

		  $rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");

		  

		  

	  $qEliminaBolseo	=	"DELETE FROM bolseo WHERE (id_bolseo={$_REQUEST[id_bolseo]})";

	  $rEliminaBolseo	=	mysql_query($qEliminaBolseo) OR die("<p>$qEliminaBolseo</p><p>".mysql_error()."</p>");



	  

	  $qEliminaTiempos	= "DELETE FROM tiempos_muertos WHERE id_produccion	= '".$_REQUEST['id_bolseo']."' AND tipo= 4";

	  $rEliminaTiempos	=	mysql_query($qEliminaTiempos);

		  

	  $redirecciona	=	true;

	  $ruta		=	"http://".$_SERVER['HTTP_HOST']."".$_SERVER['PHP_SELF']."?seccion={$_GET[seccion]}&accion={$_GET[accion2]}&bolseo";

  }				

  

  



}

?>

<script language="javascript" type="text/javascript">



		  function checar_tiempos(x){

		  if(document.getElementById('fallo_gen'+x).checked == true )

			  document.getElementById('fallo_gen'+x).value =' 1';

		  else 

			  document.getElementById('fallo_gen'+x).value = '0'	;

			  }





</script>

<? if($autorizar_bolseo){ ?>

<script type="text/javascript" language="javascript1.1" >



function checar(x,b){

  if(document.getElementById('sel_'+x+'_'+b).checked == true )

	  document.getElementById('kam_'+x+'_'+b).value =' 1';

  else 

	  document.getElementById('kam_'+x+'_'+b).value = '0'	;

}

	  

</script>

<div  id="container" style="margin:0px auto">

<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

  <div id="content">

	  <div id="datosgenerales" style="background-color:#FFFFFF;" align="left">

		  <p>

			  <label for="supervisor">Supervisor :</label><input type="text" id="supervisor" value="<?=$dBolseo['nombre']?>" readonly="readonly" class="datosgenerales"/><br />

			  <label for="fecha">Fecha :</label><input type="text" name="fecha" value="<?=fecha($dBolseo['fecha'])?>" id="fecha" class="datosgenerales fecha" /><br /><br />

			  <label for="id_turno">Turno :</label>

			  <select  name="id_turno" id="id_turno" class="datosgenerales">

				  <option value="1" <? if($dBolseo['turno'] == 1) echo "selected";?> >Matutino</option>

				  <option value="2" <? if($dBolseo['turno'] == 2) echo "selected";?>>Vespertino</option>

				  <option value="3" <? if($dBolseo['turno'] == 3) echo "selected";?>>Nocturno</option>

			  </select> 

		<br />

			  <h1> Este reporte esta repesado.<input type="checkbox" name="repe" id="repe" value="1" <? if($dBolseo['repesada'] == 1) echo "checked"; ?> />Si.</h1>

			  <input type="hidden" name="url" value="<?=$url?>">

			  <? if(isset($_REQUEST['pendiente'])){ ?>

			  <input type="hidden" name="pendiente" value="1" />

			  <? } ?>

		   

			  <input type="hidden" name="id_supervisor" value="<?=$dBolseo['id_supervisor']?>" />

			  <input type="hidden" name="area" id="area" value="<?=$dBolseo['area']?>" />

			  <input type="hidden" name="id_bolseo" id="id_bolseo" value="<?=$dBolseo['id_bolseo']?>" />

			  <input type="hidden" name="menio" value="<? if(isset($_REQUEST['menio'])) echo $_REQUEST['menio']; else  echo '0'; ?>">

		  </p>



	  </div>

	  <br /><br />

	  

  <div align="center" style="width:100%">

	  <? 	

	  $qResumenMaquinas	=	"SELECT * FROM resumen_maquina_bs INNER JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina ".

							  " LEFT JOIN operadores ON resumen_maquina_bs.id_operador = operadores.id_operador WHERE (id_bolseo=".$dBolseo['id_bolseo'].") ORDER BY maquina.numero ASC";

			  $rResumenMaquinas	=	mysql_query($qResumenMaquinas) OR die("<p>$qResumenMaquinas</p><p>".mysql_error()."</p>");

		  if(mysql_num_rows($rResumenMaquinas) < 1){

		  echo "<br><br><br><center><span style='color:#FF0000; text-align:center';>ERROR : NO SE DIO REPORTE DE PRODUCCION SOLO DE TIEMPOS MUERTOS<br>FAVOR DE REPETIR SU REPORTE</span></center><br><br><br>";

		  } else {				

			  while($dResumenMaquinas	=	mysql_fetch_assoc($rResumenMaquinas)){

	   ?>

  <div class="tablaCentrada" align="center" style="width:630px"> 

		  <input type="hidden" name="id_maquinas[]" value="<?=$dResumenMaquinas['id_maquina']?>" />

		  <input type="hidden"  name="id_resumen_maquina_bs[]" value="<?=$dResumenMaquinas['id_resumen_maquina_bs'] ?>" />

	  <h3 align="left" >EMBOLSADORA <?=$dResumenMaquinas['numero']?>: 

	  <select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:300px"   name="id_operadores[]" >

	  <option value="0">No hay operador</option>

	  <? 

	  $qOperador = "SELECT * FROM operadores WHERE status = 0 AND area = 2  AND activo = 0 ORDER BY nombre";

	  $rOperador = mysql_query($qOperador);

	  $nOperador	=	mysql_num_rows($rOperador);

	  while ($dOperador = mysql_fetch_assoc($rOperador))

	  { ?>

	  <option value="<?=$dOperador['id_operador'] ?>" <? if($dResumenMaquinas['id_operador'] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>

	  <? } ?>

	  </select>

	  </h3>



	  <div id="tabla_<?=$dResumenMaquinas['id_maquina']?>" style="background-color:#FFFFFF;">

	  <table class="tablaCentrada">

		  <tr>

			  <td width="80" align="center"><b>O._T.</b></td>

			  <td width="54" align="center"><b>Kg_a_Mi.</b></td>

			  <td width="50" align="center"><b>Factor</b></td>

			  <td width="84" align="center"><b>Millares</b></td>

			  <td width="86" align="center"><b>Kilogramos</b></td>

			  <td width="80" align="center"><b>D. Tira</b></td>

			  <td width="80" align="center"><b>D. Troquel</b></td>

			  <td width="80" align="center"><b>Segundas</b></td>

		  </tr>

		  <? 

		  $qDetalleResumen	=	"SELECT * FROM detalle_resumen_maquina_bs WHERE (id_resumen_maquina_bs = ".$dResumenMaquinas['id_resumen_maquina_bs'].")";

		  $rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

		  $nDetalleResumen	=	mysql_num_rows($rDetalleResumen);

			  

		  if($nDetalleResumen > 0){					

			  for($a = 1;$dDetalleResumen	= 	mysql_fetch_assoc($rDetalleResumen); $a++){	?>					

		  <tr>

			  <td align="center"><input type="text" class="numeros" id="ot_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="ot_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="this.select()" onBlur="convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>); sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['orden']?>" /></td>

			  <td align="center"><input type="checkbox" id="sel_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="sel_<?=$dResumenMaquinas['id_maquina']?>[]" value="1" onClick=" checar(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>); convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>);"  <? if( $dDetalleResumen['kam'] == 1 ) echo "checked";  ?>/></td>

			  <td align="center"><input type="text" size="4" id="factor_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" 	name="factor_<?=$dResumenMaquinas['id_maquina']?>[]"  onChange="convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>);" value="<?=$dDetalleResumen['factor']?>" /></td>

			  <td align="center"><input type="text" class="numeros" id="ml_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" 	name="ml_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="addTextBolseo2(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>,<?=$dBolseo['turno']?>); convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>); sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['millares']?>"   onChange="mh(<?=$dBolseo['turno']?>);"/></td>

			  <td align="center"><input type="text" class="numeros" id="kg_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" 	name="kg_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="addTextBolseo2(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>,<?=$dBolseo['turno']?>); convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>); sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['kilogramos']?>" onChange="mh(<?=$dBolseo['turno']?>); "/></td>

			  <td align="center"><input type="text" class="numeros" id="dt_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" 	name="dt_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['dtira']?>" /></td>

			  <td align="center"><input type="text" class="numeros" id="dtr_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="dtr_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['dtroquel']?>" /></td>

			  <td align="center"><input type="text" class="numeros" id="se_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" 	name="se_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['segundas']?>" />

				  <input type="hidden" name="id_detalle_resumen_maquina_bs_<?=$dResumenMaquinas['id_maquina']?>[]" id="id_detalle_resumen_maquina_bs_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" value="<?=$dDetalleResumen['id_detalle_resumen_maquina_bs']?>">

				  <input type="hidden"  id="kam_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="kam_<?=$dResumenMaquinas['id_maquina']?>[]"  <? if( $dDetalleResumen['kam'] == 1 ) echo "value='1'"; else echo "value='0'";  ?>    />                </td>

		  </tr>

		  <? 	} 

		  } else {

		  for($b=1;$b<=1;$b++) { ?>

		  <tr>

			  <td align="center"><input type="text" class="numeros" id="ot_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="ot_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="this.select()" onBlur="convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>);  sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);"  /></td>

			  <td align="center"><input type="checkbox" id="sel_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="sel_<?=$dResumenMaquinas['id_maquina']?>[]" value="1" onClick=" checar(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>); convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>);" /></td>						

			  <td align="center"><input type="text" size="4" id="factor_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="factor_<?=$dResumenMaquinas['id_maquina']?>[]"  onChange="convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>);" /></td>

			  <td align="center"><input type="text" class="numeros" id="ml_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" 	name="ml_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="addTextBolseo2(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>,<?=$dBolseo['turno']?>); convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>); sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" 	onChange="mh(<?=$dBolseo['turno']?>);"/></td>

			  <td align="center"><input type="text" class="numeros" id="kg_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" 	name="kg_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="addTextBolseo2(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>,<?=$dBolseo['turno']?>); convertir(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>);  sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);"  	onChange="mh(<?=$dBolseo['turno']?>);"/></td>

			  <td align="center"><input type="text" class="numeros" id="dt_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" 	name="dt_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" /></td>

			  <td align="center"><input type="text" class="numeros" id="dtr_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="dtr_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);"  /></td>

			  <td align="center"><input type="text" class="numeros" id="se_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" 	name="se_<?=$dResumenMaquinas['id_maquina']?>[]" 	onFocus="this.select()" onBlur="sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" />

				  <input type="hidden" value="0" id="kam_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="kam_<?=$dResumenMaquinas['id_maquina']?>[]"   />

				  <input type="hidden" name="id_detalle_resumen_maquina_bs_<?=$dResumenMaquinas['id_maquina']?>[]" id="id_detalle_resumen_maquina_bs_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" value="0"> </td>

		  </tr>

		  <? 		}

			  } 	?>	

		  <tr>

			  <td colspan="8" style="padding:0px; margin:0px;">

			  <div style="padding:0px; margin:0px;" id="texto<?=$dResumenMaquinas['id_maquina']?>"></div>

			  </td>

		  </tr>

		  <tr>

			  <td colspan="3" align="right"><strong>Subtotales</strong></td>

			  <td><input type="text" id="mls_<?=$dResumenMaquinas['id_maquina']?>" name="mls_<?=$dResumenMaquinas['id_maquina']?>"  	class="subtotal" onFocus="this.select()"  value="<?=$dResumenMaquinas['millares']?>" readonly/></td>

			  <td><input type="text" id="kgs_<?=$dResumenMaquinas['id_maquina']?>" name="kgs_<?=$dResumenMaquinas['id_maquina']?>" 	class="subtotal" onFocus="this.select()"  value="<?=$dResumenMaquinas['kilogramos']?>" readonly/></td>

			  <td><input type="text" id="dts_<?=$dResumenMaquinas['id_maquina']?>" name="dts_<?=$dResumenMaquinas['id_maquina']?>"  	class="subtotal" onFocus="this.select()"  value="<?=$dResumenMaquinas['dtira']?>" readonly/></td>

			  <td><input type="text" id="dtrs_<?=$dResumenMaquinas['id_maquina']?>" name="dtrs_<?=$dResumenMaquinas['id_maquina']?>"	class="subtotal" onFocus="this.select()"  value="<?=$dResumenMaquinas['dtroquel']?>" readonly/></td>

			  <td><input type="text" id="ses_<?=$dResumenMaquinas['id_maquina']?>" name="ses_<?=$dResumenMaquinas['id_maquina']?>" 	class="subtotal" onFocus="this.select()"  value="<?=$dResumenMaquinas['segundas']?>" readonly/></td>

		  </tr>

	  </table>

	  </div>

  </div>

	  <? }  

	  }?>

  </div>



  <div id="datosgenerales" style="background-color:#FFFFFF;">

	  <table align="center" width="60%">

	  <tr>

		  <td><strong>Totales</strong></td>

		  <td><input type="text" id="tml" name="tml" value="<?=$dBolseo['millares']?>" class="total"  	<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>

		  <td><input type="text" id="tkg" name="tkg" value="<?=$dBolseo['kilogramos']?>" class="total"	<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>

		  <td><input type="text" id="tdt" name="tdt" value="<?=$dBolseo['dtira']?>" class="total"  		/></td>

		  <td><input type="text" id="tdtr" name="tdtr" value="<?=$dBolseo['dtroquel']?>" class="total"	/></td>

		  <td><input type="text" id="tse" name="tse" value="<?=$dBolseo['segundas']?>" class="total" 	 	/></td>

	  </tr>

	  <tr>

		  <td><strong>Millares por Hora:</strong></td>

		  <td colspan="5"><input type="text" id="m_p" name="m_p" value="<?=$dBolseo['m_p']?>" readonly="readonly" class="total" /></td>

	  </tr>

	  <tr>

		  <td><strong>Observaciones</strong></td>

		  <td colspan="5"><textarea name="observaciones_generales" cols="40" rows="8"><?=$dBolseo['observaciones']?></textarea></td>

	  </tr>    

	  </table>	

  </div>



  <div id="datosgenerales" style="background-color:#FFFFFF;" align="left">

  <p align="center" class="titulos_reportes">TIEMPOS MUERTOS</p>

  <?  

	  $sql_lic= "SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dBolseo['id_bolseo']."' AND tipo= 4 ORDER BY numero ASC ";

	  $res_lic=mysql_query($sql_lic);

	  $cant_lic=mysql_num_rows($res_lic);

	  $cant=ceil($cant_lic/1);

	  $c=0;

	  while($muertos = mysql_fetch_assoc($res_lic))

	  {

		  $codigo[$c]		=	$muertos['numero'];

		  $id[$c] 		= 	$muertos['id_maquina'];

		  $falta[$c]			=	$muertos['falta_personal'];

		  $mantenimiento[$c]	=	$muertos['mantenimiento'];

		  $otras[$c]	=			$muertos['otras'];

		  $fallo[$c]			=	$muertos['fallo_electrico'];

		  $observaciones[$c]	=	$muertos['observaciones'];

		  $c++;

	  } 

	  $muertotiempo 	= explode(":" ,$fallo[1]);  $ho	=	$muertotiempo[0]; $mi =	$muertotiempo[1];	

  ?>

	 

  <h1><input type="checkbox" name="fallo_general" <? if($fallo[1] != '00:00:00') echo "checked"; ?> value="1" onclick="muestra(1001); checar_tiempos(1001);" id="fallo_gen1001" />Fallo General El&eacute;ctrico</h1>

  <div class="style5" id="div_1001" style="display:<? if($fallo[1] != '00:00:00') echo "block"; else echo "none"; ?>; width:350px; padding:15px 10px; ">

	  Tiempo de fallo: <input type="text" name="horas_fallo" value="<?=$ho?>" size="2" />:<input type="text" name="minutos_fallo" value="<?=$mi?>"  size="2" />Hrs.

  </div>   

  <? 

  $reg=0;

  for($i=0; $i<$cant; $i++)

  { ?>

  <table width="70%" align="center">

	  <tr>

	  <? for($x=1; $x<=1; $x++){?>

		  <td width="70%" align="center" valign="top"><br />

		  <? if($reg < $cant_lic){

		  

		  $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

		  $rOperadorextr = mysql_query($qOperadorextr);

		  $dAsignacionextr = mysql_fetch_assoc($rOperadorextr);?>

		  <input type="hidden" name="id_maquina2_<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />

		  <input type="hidden" name="codigos2[]" value="<?=$codigo[$reg]?>" />

		  <h3 align="left" style="color: rgb(255, 255, 255);"  class="Tips4" title="Click aqui para abrir o cerrar::" onClick="muestra('<?=$id[$reg]?>')">MAQUINA <?=$codigo[$reg]?></h3>

		  <div id="div_<?=$id[$reg]?>" style="display: <? if( $observaciones[$reg] != "" || $falta[$reg] != "00:00:00" || $mantenimiento[$reg] != "00:00:00" || $otras[$reg] != "00:00:00"  ) echo "block"; else echo "none" ?>">

		  <table width="100%" align="center" >

		  <tr>

			  <td width="109" height="39" align="right"><strong>Observaciones : </strong></td>

			  <td colspan="5" align="left"><textarea name="ob_bolseo<?=$codigo[$reg]?>" id="ob_bolseo<?=$codigo[$reg]?>" cols="40" rows="2" /><?=$observaciones[$reg]?></textarea></td>

		  </tr>

		  <tr>

<!--           	  	<td align="right" class="style7">Falta de Personal :</td>

			  <td width="26" align="left"><input type="checkbox" name="fp_bolseo<?=$codigo[$reg]?>" id="fp_bolseo<?=$codigo[$reg]?>" value="1" <? if($falta[$reg] != "00:00:00") echo "checked" ?> /></td>

-->				<td width="94" align="right" class="style7">Otras causas:</td>

			  <? $tiempo4 	= explode(":" ,$otras[$reg]);  $horas4	=	$tiempo4[0]; $minutos4 =	$tiempo4[1];?>

			  <td width="105" align="left"><input type="text" name="oh_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas4?>" />:

			  <input type="text" name="om_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos4?>" /></td>



			  <td width="95" align="right" class="style7">Mantenimiento :</td>

			  <? $tiempo3 	= explode(":" ,$mantenimiento[$reg]);  $horas3	=	$tiempo3[0]; $minutos3 =	$tiempo3[1];?>

			  <td width="88" align="left"><input type="text" name="mh_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas3?>" />:

			  <input type="text" name="mm_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos3?>" /></td>

		  </tr>

		  </table>

		  </div>

		  <? $reg++;}?></td>

	  <? }?>

	  </tr>

  </table>

<? }?>  

  </div>       

	  <div id="barraSubmit" style="background-color:#FFFFFF; text-align:right;">

					<input type="button" name="regresar" id="regresar" value="Regresar" onClick=" history.go(-1)">

		  <input type="submit" name="bolseo" value="Guardar" />

	  </div>

  </div>

</form>

</div>



	  <?php }  if($autorizar){ ?>

<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

<div id="container">

  <div id="content">

	  <div id="datosgenerales" style="background-color:#FFFFFF;" align="left">

		  <p>

			  <label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dEntradaGeneral['nombre']?>" readonly="readonly" class="datosgenerales"/><br />

			  <label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=fecha($dEntradaGeneral['fecha'])?>" id="fecha" class="fecha datosgenerales" /><br />

			  <br /><label for="fecha">Turno</label>

				   <select  name="id_turno" id="id_turno" class="datosgenerales">

				  <option value="1" <? if($dEntradaGeneral['turno'] == 1) echo "selected";?> >Matutino</option>

				  <option value="2" <? if($dEntradaGeneral['turno'] == 2) echo "selected";?>>Vespertino</option>

				  <option value="3" <? if($dEntradaGeneral['turno'] == 3) echo "selected";?>>Nocturno</option>

			  </select> 

			  <br>

			  <h1>Este reporte esta repesado.<input type="checkbox" name="repe" value="1" <? if($dEntradaGeneral['repesada'] == 1) echo "checked"; ?> />Si.</h1>



			  <input type="hidden" name="menio" value="<? if(isset($_REQUEST['menio'])) echo $_REQUEST['menio']; else '0'; ?>">

			  <input type="hidden" name="id_entrada_general" id="id_entrada_general" value="<?=$dEntradaGeneral['id_entrada_general']?>">

			  <input type="hidden" name="url" value="<?=$url?>">

		   <br />

			  <? if(isset($_REQUEST['pendiente'])){ ?>

			  <input type="hidden" name="pendiente" value="1" />

			  <? } ?>             

			  <input type="hidden" name="id_supervisor" value="<?=$dEntradaGeneral['id_supervisor']?>" />

		  </p><br>

		  </div>

   <p align="center">          

	  <?  

	  

		  $dOrdenProduccion	=	mysql_fetch_assoc($rOrdenProduccion); ?>

		  <input type="hidden" name="id_extruder" value="<?=$dOrdenProduccion['id_orden_produccion']?>"> 

		  <?

		  $sql_lic	=	"SELECT * FROM resumen_maquina_ex INNER JOIN maquina ON resumen_maquina_ex.id_maquina = maquina.id_maquina ".

							  " LEFT JOIN operadores ON resumen_maquina_ex.id_operador = operadores.id_operador WHERE (id_orden_produccion=".$dOrdenProduccion['id_orden_produccion'].") ORDER BY maquina.numero ASC";

		  $res_lic	=	mysql_query($sql_lic) OR die("<p>$qResumenMaquinas</p><p>".mysql_error()."</p>");

		  if(mysql_num_rows($res_lic) < 1){

		  echo "<br><br><br><span style='color:#FF0000;'>ERROR : NO SE DIO REPORTE DE PRODUCCION SOLO DE TIEMPOS MUERTOS<br>FAVOR DE REPETIR SU REPORTE</span><br><br><br>";

		  } else {

		  $cant_lic=mysql_num_rows($res_lic);

		  $cant=ceil($cant_lic/3);

		  $a=0;

		  while($dResumenMaquinas = mysql_fetch_assoc($res_lic))

		  {

			  $numero[$a]					= $dResumenMaquinas['numero'];

			  $id_maquina[$a] 			= $dResumenMaquinas['id_maquina'];

			  $id_operador[$a]			= $dResumenMaquinas['id_operador'];

			  $id_resumen_maquina_ex[$a]	= $dResumenMaquinas['id_resumen_maquina_ex'];

			  $subtotales[$a]				= $dResumenMaquinas['subtotal'];

			  $a++;

		  }

		  $reg=0;

		  for($i=0;$i<$cant;$i++)

{

?>

<table align="center">

<tr>

  <? for($x=1;$x<=4; $x++){?>

<td width="206"  align="left" valign="top"><br />

<? if($reg<$cant_lic){ ?>		



	  <?

	  

		  $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id_maquina[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

		  $rOperadorextr = mysql_query($qOperadorextr);

		  $dAsignacionextr = mysql_fetch_assoc($rOperadorextr);?>

		  

	  <input type="hidden" name="codigos[]" value="<?=$numero[$reg]?>" />

	  <input type="hidden" name="id_maquinas<?=$numero[$reg]?>" value="<?=$id_maquina[$reg]?>" />

	  <input type="hidden"  name="id_resumen_maquina_ex<?=$numero[$reg]?>" value="<?=$id_resumen_maquina_ex[$reg] ?>" />

	  <h3 align="left" style="color: rgb(255, 255, 255);">EXTRUDER <?=$numero[$reg]?><br>

	  <select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:175px"   name="id_operador<?=$numero[$reg]?>" >

	  <option value="0">No hay operador</option>

	  <? 

	  $qOperador = "SELECT * FROM operadores WHERE status = 0 AND area = 1  AND activo = 0 ORDER BY nombre";

	  $rOperador = mysql_query($qOperador);

	  $nOperador	=	mysql_num_rows($rOperador);

	  while ($dOperador = mysql_fetch_assoc($rOperador))

	  { ?>

	  <option value="<?=$dOperador['id_operador'] ?>" <? if($id_operador[$reg] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>

	  <? } ?>

	  </select>

	   </h3>

<table width="190" height="59" cellpadding="0" border="0" cellspacing="0" >

  <tr>

	  <td width="75"><strong>O. T.</strong></td>

	  <td width="126" ><strong>Kilogramos</strong></td>

  </tr>

  <?php 

		  $qDetalleResumen	=	"SELECT orden_trabajo, kilogramos, id_detalle_resumen_maquina_ex FROM detalle_resumen_maquina_ex WHERE (id_resumen_maquina_ex = ".$id_resumen_maquina_ex[$reg].")";

		  $rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

		  $nDetalleResumen	=	mysql_num_rows($rDetalleResumen);

		  

		  if($nDetalleResumen > 0){

		  for($a = 1;$dDetalleResumen	= 	mysql_fetch_assoc($rDetalleResumen); $a++){	

				  ?>

				  <tr>

					  <td height="19px" ><input type="text"  class="text" id="ot_extruder<?=$id_maquina[$reg]?>_<?=$a?>" name="ot_extruder<?=$numero[$reg]?>[]" onFocus="this.select()"  value="<?=$dDetalleResumen['orden_trabajo']?>" /></td>

					<td ><input type="text" class="text2" id="<?=$a?>_extr<?=$id_maquina[$reg]?>" name="kg_extruder<?=$numero[$reg]?>[]"  onkeydown="addTextExtruder(<?=$numero[$reg]?>,<?=$id_maquina[$reg]?>,<?=$a?>,<?=$dEntradaGeneral['turno']?>);" onchange="sumar(<?=$id_maquina[$reg]?>);  sumarExtr();  kh(<?=$dEntradaGeneral['turno']?>);" value="<?=$dDetalleResumen['kilogramos']?>" />

					<input type="hidden" name="id_detalle_resumen_maquina_ex<?=$numero[$reg]?>[]" id="<?=$a?>id_detalle_resumen_maquina_ex<?=$id_maquina[$reg]?>" value="<?=$dDetalleResumen['id_detalle_resumen_maquina_ex']?>"></td>

				  </tr>



	  <?php  }  

				  } else { ?>

			  <?php for($b=1;$b<=1;$b++) { ?>

			  <tr>

					  <td ><input type="text"  class="numeros text" id="ot_extruder<?=$id_maquina[$reg]?>_<?=$b?>" name="ot_extruder<?=$numero[$reg]?>[]" onFocus="this.select()" value="<?=$dDetalleResumen['orden_trabajo']?>" /></td>

					<td><input type="text" class="numeros text2" id="<?=$b?>_extr<?=$id_maquina[$reg]?>" name="kg_extruder<?=$numero[$reg]?>[]" onKeyDown="addTextExtruder(<?=$numero[$reg]?>,<?=$id_maquina[$reg]?>,<?=$b?>,<?=$dEntradaGeneral['turno']?>);" onchange="sumar(<?=$id_maquina[$reg]?>);  sumarExtr();  kh(<?=$dEntradaGeneral['turno']?>);" value="<?=$dDetalleResumen['kilogramos']?>" />

					<input type="hidden" name="id_detalle_resumen_maquina_ex<?=$numero[$reg]?>[]" id="<?=$a?>id_detalle_resumen_maquina_ex<?=$id_maquina[$reg]?>" value="0"> </td>

				 </tr>

			   <? }

				} ?>	

				<tr>

				<td colspan="2" style="margin:0px; padding:0px;">

				<div  style="margin:0px; padding:0px;" id="texto<?=$id_maquina[$reg]?>"></div>		

				</td>

				</tr>                  

				  <tr>

					  <td height="20" style="font-size:9px"><strong>Subtotales</strong></td>

					<td><input style="width:100px" type="text" name="subtotal_extruder<?=$numero[$reg]?>" id="subtotal_<?=$id_maquina[$reg]?>_extr_Total" onChange="sumarExtr();"  readonly="readonly" value="<?=$subtotales[$reg]?>" /></td>

				  </tr>

		</table>

<? $reg++;}?></td><? } }?>

</tr>

</table>

<? }?>

</p>

 <div id="datosgenerales" style="background-color:#FFFFFF;">

	  <table align="center">

				  <tr>

					  <td></td>

					  <td>TOTAL</td>

					  <td>D. Tira</td>

					  <td>D. Duro</td>

					  <td>K/H</td>	

				  </tr>

				  <tr>

					  <td><strong>Totales</strong></td>

					  <td><input type="text" id="total_extr" name="total_extruder" value="<?=$dOrdenProduccion['total']?>" class="total" 						<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>

					  <td><input type="text" id="desperdicio_tira" name="desperdicio_tira" value="<?=$dOrdenProduccion['desperdicio_tira'];?>" class="total"  /></td>

					  <td><input type="text" id="desperdicio_duro" name="desperdicio_duro" value="<?=$dOrdenProduccion['desperdicio_duro'];?>" class="total"  /></td>

					  <td><input type="text" size="20" name="k_h" id="k_h" class="total" value="<?=$dOrdenProduccion['k_h']?>" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?>/></td>

				  </tr>

				  <tr>

					  <td valign="top">Observaciones:</td>

					  <td colspan="4" valign="top"><textarea cols="50" rows="6" name="observaciones_generales"><?=$dOrdenProduccion['observaciones'];?></textarea></td>

				  </tr>

	  </table>

	  </div>    



	  <div id="datosgenerales" style="background-color:#FFFFFF;" align="left">

	  <p align="center" class="titulos_reportes"><br />TIEMPOS MUERTOS</p>         

		 

	  <?  

		  $sql_lic= "SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dOrdenProduccion['id_orden_produccion']."' AND tipo= 1 ORDER BY maquina.numero ASC ";

		  $res_lic=mysql_query($sql_lic);

		  $cant_lic=mysql_num_rows($res_lic);

		  $cant=ceil($cant_lic/3);

		  $c=0;

		  while($muertos = mysql_fetch_assoc($res_lic))

		  {

			  $codigo[$c]		=	$muertos['numero'];

			  $id[$c] 		= 	$muertos['id_maquina'];

			  $falta[$c]			=	$muertos['falta_personal'];

			  $mantenimiento[$c]	=	$muertos['mantenimiento'];

			  $mallas[$c]			=	$muertos['mallas'];

			  $otras[$c]			=	$muertos['otras'];

			  $fallo[$c]			=	$muertos['fallo_electrico'];

			  $observaciones[$c]	=	$muertos['observaciones'];

			  $c++;

		  }

		  

				   $muertotiempo 	= explode(":" ,$fallo[1]);  $ho	=	$muertotiempo[0]; $mi =	$muertotiempo[1];	



	  ?>

 <h1><input type="checkbox" name="fallo_general" <? if($fallo[1] != '00:00:00') echo "checked"; ?> value="1"  onclick="muestra(1001); checar_tiempos(1010);" id="fallo_gen1001" />Fallo General El&eacute;ctrico</h1>

 <div class="style5" id="div_1001" style="display:<? if($fallo[1] != '00:00:00') echo "block"; else echo "none"; ?>;width:350px; padding:15px 10px;">

			  <br>Tiempo de fallo: <input type="text" name="horas_fallo" value="<?=$ho?>" size="2" />:<input type="text" name="minutos_fallo" value="<?=$mi?>"  size="2" />Hrs.<br />

 </div> <br><br>      

	<?	

		  

		  $reg=0;

		  for($i=0;$i<$cant;$i++)

{

?>



<table width="100%" align="center">
<tr><td align="center"><p class="titulos_reportes">REPORTE EXTRUDER</p></td></tr>
<tr>

  <? for($x=1;$x<=4; $x++){?>

<td width="190px" align="left" valign="top">

<? if($reg<$cant_lic){ ?>		



		  <input type="hidden" name="id_maquina<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />

		  <input type="hidden" name="tiempos_m[]" value="<?=$codigo[$reg]?>" />

	  <h3 align="left" style="color: rgb(255, 255, 255);"  class="Tips4" title="Click aqui para abrir o cerrar:: &nbsp;" onClick="muestra_extr('<?=$codigo[$reg]?>');">EXTRUDER <?=$codigo[$reg]?> </h3>

	  <div id="extruder<?=$codigo[$reg]?>" style="display:<? if( $observaciones[$reg] != "" || $falta[$reg] != "00:00:00" || $mantenimiento[$reg] != "00:00:00" || $mallas[$reg] == '1'  ||  $otras[$reg] != "00:00:00") echo "block"; else echo "none" ?>; border:1px solid #ccc; height:100%; padding:8px 0px">

	  <div style="width:70px; float:left; padding-left:8px;">Obser.</div>

	  <div style="float:left"><textarea  name="ob_extruder<?=$codigo[$reg]?>" id="ob_extruder<?=$codigo[$reg]?>" cols="10" rows="3" ><?=$observaciones[$reg]?></textarea></div>

	  <div style="width:110px; float:left; padding-left:8px;">C. de mallas:</div>                    

	  <div style="float:left"><input type="checkbox" name="mallas<?=$codigo[$reg]?>" value="1" <? if($mallas[$reg] == 1) echo "checked"?> /></div>

<!--        <div style="width:110px; float:left; padding-left:8px;" class="style7">Personal:</div>

	  <div style="float:left"><input type="checkbox" name="fp_extruder<?=$codigo[$reg]?>" value="1"  <? if($falta[$reg] != "00:00:00") echo "checked"?> /></div>

-->        <div style="width:90px; float:left; padding-left:8px;" class="style7">Mannto:</div>

	  <? $tiempo4 = explode(":" ,$otras[$reg]);  $horas4	=	$tiempo4[0]; $minutos4 =	$tiempo4[1];?>

	  <div style="float:left"><input type="text" name="oh_extruder<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas4?>" />:<input type="text" name="om_extruder<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos4?>" /></div>

	  <div style="width:90px; float:left; padding-left:8px;" class="style7">Otros:</div>

	  <? $tiempo3 = explode(":" ,$mantenimiento[$reg]);  $horas3	=	$tiempo3[0]; $minutos3 =	$tiempo3[1];?>

	  <div style="float:left"><input type="text" name="mh_extruder<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas3?>" />:<input type="text" name="mm_extruder<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos3?>" /></div>

</div>        

	

<? $reg++;}?>

<br /></td><? }?>

</tr>

</table>

<? }?>

</div>

  <div id="barraSubmit" style="background-color:#FFFFFF; text-align:right;">

  <p><input type="button" name="regresar" id="regresar" value="Regresar" onClick=" history.go(-1)">&nbsp;

	  <input type="submit" name="extruder" value="Guardar" /></p>

  </div>            

<br><br>

</div></div></form>



<? } if($impresion) { ?>



<script language="javascript" type="text/javascript">

function kh()

{

   var total;

   total		=	0;

   total 		= (parseFloat(document.getElementById('total_impr_hd').value) + parseFloat(document.getElementById('total_impr_bd').value )) / <? if($dEntradaGeneral['turno'] == '1') echo '8'; if($dEntradaGeneral['turno'] == '2') echo '7'; if($dEntradaGeneral['turno'] == '3') echo '9';  ?>;

   document.getElementById('k_h').value = redondear(total,2);

}	



</script>

<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

<div id="container">

  <br />

  <div id="content">

	  <div id="datosgenerales" style="background-color:#FFFFFF;" align="left">

		  <p>

			  <label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dEntradaGeneral['nombre']?>" readonly="readonly" class="datosgenerales"/><br />

			  <label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?>" id="fecha" class="datosgenerales" /><br />

			  <label for="fecha">Turno</label>

			  <select  name="id_turno" id="id_turno" class="datosgenerales">

				  <option value="1" <? if($dEntradaGeneral['turno'] == 1) echo "selected";?> >Matutino</option>

				  <option value="2" <? if($dEntradaGeneral['turno'] == 2) echo "selected";?>>Vespertino</option>

				  <option value="3" <? if($dEntradaGeneral['turno'] == 3) echo "selected";?>>Nocturno</option>

			  </select> 

			 

		   <br>     

		  <h1>Este reporte esta repesado.<input type="checkbox" name="repe" value="1" <? if($dEntradaGeneral['repesada'] == 1) echo "checked";?> />Si. </h1>       

		  <input type="hidden" name="id_entrada_general" id="id_entrada_general" value="<?=$dEntradaGeneral['id_entrada_general']?>">

			  <input type="hidden" name="url" value="<?=$url?>">

			  <input type="hidden" name="id_impresion" value="<?=$dImpresion['id_impresion']?>">

			  

		  <br />

			  <? if(isset($_REQUEST['pendiente'])){ ?>

			  <input type="hidden" name="pendiente" value="1" />

			  <? } ?>

			  <input type="hidden" name="id_supervisor" value="<?=$dEntradaGeneral['id_supervisor']?>" /><br>

			  <input type="hidden" name="menio" value="<? if(isset($_REQUEST['menio'])) echo $_REQUEST['menio']; else '0'; ?>">

		  </p><br>

	  <div align="center">

	<p class="titulos_reportes">REPORTE Impresi&oacute;n </p>

	<br>

	</div>

	

<p align="center">          

	  <?  			

		  

		  $sql_lic= 	"SELECT maquina.numero, operadores.nombre, subtotal, maquina.id_maquina, id_resumen_maquina_im, resumen_maquina_im.id_operador, subtotal_bd FROM resumen_maquina_im  ".

					  "LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".

					  "LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador ". 

					  "WHERE (id_impresion=".$dImpresion['id_impresion'].") AND linea_impresion = '0' ORDER BY maquina.numero";

		  $res_lic=mysql_query($sql_lic);

		  if(mysql_num_rows($res_lic) < 1){

		  echo "<br><br><br><span style='color:#FF0000;'>ERROR : NO SE DIO REPORTE DE PRODUCCION SOLO DE TIEMPOS MUERTOS<br>FAVOR DE REPETIR SU REPORTE</span><br><br><br>";

		  } else {			

		  $cant_lic=mysql_num_rows($res_lic);

		  $cant=ceil($cant_lic/3);

		  $a=0;

		  while($dResumenMaquinasImpr = mysql_fetch_assoc($res_lic))

		  {

		  

			  $numero[$a]						= $dResumenMaquinasImpr['numero'];

			  $id_maquina[$a] 				= $dResumenMaquinasImpr['id_maquina'];

			  $id_operador[$a]				= $dResumenMaquinasImpr['id_operador'];

			  $id_resumen_maquina_impr[$a]	= $dResumenMaquinasImpr['id_resumen_maquina_im'];

			  $subtotales[$a]					= $dResumenMaquinasImpr['subtotal'];

			  $subtotalesbd[$a]				= $dResumenMaquinasImpr['subtotal_bd'];

		  



			  $a++;

		  }

		  $reg=0;

		  for($i=0;$i<$cant;$i++)

{

?>

<table  width="100%" align="center"  >

<tr>

  <? for($x=1;$x<=4; $x++){?>

<td width="190" align="left" valign="top"><br />

<? if($reg<$cant_lic){ ?>		



   

	  <?

	  

		  $qOperadorimpr = 	"SELECT oper_maquina.id_operador, nombre FROM oper_maquina ".

							  "INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador ".

							  "WHERE id_maquina = ".$id_maquina[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

		  $rOperadorimpr = mysql_query($qOperadorimpr);

		  $dAsignacionimpr = mysql_fetch_assoc($rOperadorimpr);?>

			

	  <input type="hidden" name="codigos2[]" value="<?=$numero[$reg]?>" />

	  <input type="hidden" name="id_maquinas2_<?=$numero[$reg]?>" value="<?=$id_maquina[$reg]?>" />

	  <input type="hidden"  name="id_resumen_maquina_im<?=$numero[$reg]?>" value="<?=$id_resumen_maquina_impr[$reg] ?>" />

	  

   <h3 align="left" style="color: rgb(255, 255, 255);"><?=$numero[$reg]?><br>

	  <select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:175px"  name="id_operador2_<?=$numero[$reg]?>" >

	  <option value="0">No hay Operador</option>

	  <? 

	  $qOperador = "SELECT * FROM operadores WHERE status = 0 AND area = 3 AND activo = 0 ORDER BY id_operador";

	  $rOperador = mysql_query($qOperador);

	  while ($dOperador = mysql_fetch_assoc($rOperador))

	  { ?>

	  <option value="<?=$dOperador['id_operador'] ?>" <? if($id_operador[$reg] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>

	  <? } ?>

	  </select>

   

   

	 </h3>

<table width="190" height="59" cellpadding="0" cellspacing="0" >

<thead>

				  <tr>

					  <td width="59" height="20"><strong>O. T.</strong></td>

					<td width="84"><strong>Kilogramos</strong></td>

			  </tr>

			  </thead>

			  <tbody>

			  <?php 

		  $qDetalleResumenIM	=	"SELECT * FROM detalle_resumen_maquina_im WHERE (id_resumen_maquina_im = '".$id_resumen_maquina_impr[$reg]."')";

		  $rDetalleResumenIM	=	mysql_query($qDetalleResumenIM) OR die("<p>$qDetalleResumenIM</p><p>".mysql_error()."</p>");

		  

		  $nDetalleResumenIM	=	mysql_num_rows($rDetalleResumenIM);

		  

		  if($nDetalleResumenIM > 0){			

		  for($a3 = 1;$dDetalleResumenIM	= 	mysql_fetch_assoc($rDetalleResumenIM); $a3++){					

	  ?>

				  <tr>

						<td align="left" ><input type="text" class="text" name="ot_impresion<?=$numero[$reg]?>[]" size="12" value="<?=$dDetalleResumenIM['orden_trabajo']?>" /></td>

					<td align="left" colspan="3"><input size="25" class="text2" type="text" name="kg_impresion<?=$numero[$reg]?>[]" id="<?=$a3?>_impr<?=$id_maquina[$reg]?>" value="<?=$dDetalleResumenIM['kilogramos']?>"   onkeydown="addTextBox(<?=$numero[$reg]?>,<?=$id_maquina[$reg]?>,<?=$a3?>,<?=$dEntradaGeneral['turno']?>);" onChange="sumarImpr2(<?=$id_maquina[$reg]?>);  sumarImpr(); kh(<?=$dEntradaGeneral['turno']?>); " />

					<input type="checkbox" name="bd_impresion<?=$numero[$reg]?>[]" id="<?=$a3?>_impr_bd<?=$id_maquina[$reg]?>" value="1" onClick="sumarImpr2(<?=$id_maquina[$reg]?>,<?=$a3?>); sumarImpr(); kh();" <?=($dDetalleResumenIM['bd'] == 1)?"checked='checked'":"";?> />BD

					<input type="hidden" name="id_detalle_resumen_maquina_im<?=$numero[$reg]?>[]" id="<?=$a3?>id_detalle_resumen_maquina_im<?=$id_maquina[$reg]?>" value="<?=$dDetalleResumenIM['id_detalle_resumen_maquina_im']?>" > 

					

					</td>

				</tr>

			  <?php } 

			   

				  } else { ?>

			  <?php for($b=1;$b<=1;$b++) { ?>

			  <tr>

						  <td height="19"><input type="text"  class="numeros text" id="ot_impresion<?=$id_maquina[$reg]?>_<?=$b?>" name="ot_impresion<?=$numero[$reg]?>[]" onFocus="this.select()" /></td>

					<td><input type="text" class="numeros text2" id="<?=$b?>_impr<?=$id_maquina[$reg]?>" name="kg_impresion<?=$numero[$reg]?>[]" onKeyDown="addTextBox(<?=$numero[$reg]?>,<?=$id_maquina[$reg]?>,<?=$b?>,<?=$dEntradaGeneral['turno']?>);"  onFocus="this.select()"  onchange="sumarImpr2(<?=$id_maquina[$reg]?>);  sumarImpr(); kh(<?=$dEntradaGeneral['turno']?>);" value="<?=$dDetalleResumenIM['kilogramos']?>" />

					<input type="checkbox" name="bd_impresion<?=$numero[$reg]?>[]" id="<?=$b?>_impr_bd<?=$id_maquina[$reg]?>" value="1" onClick="sumarImpr2(<?=$id_maquina[$reg]?>,<?=$b?>); sumarImpr(); kh(<?=$dEntradaGeneral['turno']?>);" />BD

					<input type="hidden" name="id_detalle_resumen_maquina_im<?=$numero[$reg]?>[]" id="<?=$b?>id_detalle_resumen_maquina_im<?=$id_maquina[$reg]?>" value=""> 

					

					</td>

				 </tr>

			   <? }

				} ?>	

				<tr><td colspan="2">

				<div id="texto<?=$id_maquina[$reg]?>"></div>	

				</td>

				</tr>

				  <tr>

					  <td style="font-size:9px"><strong>Subtotal AD</strong><br /><strong>Subtotal BD</strong></td>

					  <td colspan="3">

					  <input size="25" style="width:100px" type="text" name="subtotal_impr<?=$numero[$reg]?>" id="subtotal_<?=$id_maquina[$reg]?>_impr_Total" onChange="sumarImpr();"  readonly="readonly" value="<?=$subtotales[$reg]?>" /><br />

					  <input size="25" style="width:100px" type="text" name="subtotalbd_impr<?=$numero[$reg]?>" id="subtotal<?=$id_maquina[$reg]?>_impr_bdTotal" onChange="sumarImpr();"  readonly="readonly" value="<?=$subtotalesbd[$reg]?>" /></td>

			  </tbody>

		</table>

		  

<? $reg++;}?></td><? } } ?>

</tr>

</table>

<? }?>

</p>   

	  <br>

<br />

				  <p class="titulos_reportes">L&iacute;nea de Impresi&oacute;n - Nave 2</p>	

				  

				 

	   <p align="center">          

		 

	  <?  

		  $sql_lic2= 	"SELECT * FROM resumen_maquina_im  ".

					  " RIGHT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".

					  " LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador ".

					  " WHERE id_impresion = ".$dImpresion['id_impresion']." AND linea_impresion = '1' ".

					  " ORDER BY maquina.numero ASC";

			  $res_lic2=mysql_query($sql_lic2);

		  if(mysql_num_rows($res_lic2) < 1){

		  echo "<br><br><br><span style='color:#FF0000;'>ERROR : NO SE DIO REPORTE DE PRODUCCION SOLO DE TIEMPOS MUERTOS<br>FAVOR DE REPETIR SU REPORTE</span><br><br><br>";

		  } else {				

		  $cant_lic2=mysql_num_rows($res_lic2);

		  $cant2=ceil($cant_lic2/4);

		  $a2=0;

		  while($dResumenMaquinasLimpr = mysql_fetch_assoc($res_lic2))

		  {

		  

			  $numero2[$a2]					= $dResumenMaquinasLimpr['numero'];

			  $id_maquina2[$a2] 				= $dResumenMaquinasLimpr['id_maquina'];

			  $id_operador2[$a2]				= $dResumenMaquinasLimpr['id_operador'];

			  $id_resumen_maquina_limpr[$a2]	= $dResumenMaquinasLimpr['id_resumen_maquina_im'];

			  $subtotales2[$a2]				= $dResumenMaquinasLimpr['subtotal'];

			  $subtotalesbd2[$a2]				= $dResumenMaquinasLimpr['subtotal_bd'];



			  $a2++;

		  }

		  $reg2=0;

		  for($i2=0;$i2<$cant2;$i2++)

{

?>

<table  width="100%" align="center">

<tr>

  <? for($x2=1;$x2<=4; $x2++){?>

<td width="190" align="left" valign="top"><br />

<? if($reg2<$cant_lic2){ ?>		



   

	  <?

	  

		  $qOperadorlimp2 = 	"SELECT oper_maquina.id_operador, nombre FROM oper_maquina ".

							  "INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador ".

							  "WHERE id_maquina = ".$id_maquina2[$reg2]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

		  $rOperadorlimp2 = mysql_query($qOperadorlimp2);

		  $dAsignacionlimp2 = mysql_fetch_assoc($rOperadorlimp2);?>

		  

	  <input type="hidden" name="codigos3[]" value="<?=$numero2[$reg2]?>" />

	  <input type="hidden" name="id_maquina3_<?=$numero2[$reg2]?>" value="<?=$id_maquina2[$reg2]?>" />

	  <input type="hidden"  name="id_resumen_maquina_lim<?=$numero2[$reg2]?>" value="<?=$id_resumen_maquina_limpr[$reg2] ?>" />

	  <h3 align="left" style="color: rgb(255, 255, 255);">Linea <?=$numero2[$reg2]?> <br />		

		

		<select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px;  width:175px"   name="id_operador3_<?=$numero[$reg]?>" >

	 <option value="0">No hay operador</option>

	  <? 

	  $qOperador = "SELECT * FROM operadores WHERE status = 0 AND area = 3  AND activo = 0 ORDER BY nombre";

	  $rOperador = mysql_query($qOperador);

	  while ($dOperador = mysql_fetch_assoc($rOperador))

	  { ?>

	  <option value="<?=$dOperador['id_operador'] ?>" <? if($id_operador2[$reg2] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>

	  <? } ?>

	  </select>

	  

	   </h3>

<table width="190" height="59" cellpadding="0" cellspacing="0" >

<thead>

				  <tr>

					  <td width="59"><strong>O. T.</strong></td>

					<td width="84"><strong>Kilogramos</strong></td>

			  </tr>

			  </thead>

			  <tbody>

			  <?php 

			  

			  

		  $qDetalleResumenLIM	=	"SELECT * FROM detalle_resumen_maquina_im WHERE (id_resumen_maquina_im = '".$id_resumen_maquina_limpr[$reg2]."')";

		  $rDetalleResumenLIM	=	mysql_query($qDetalleResumenLIM) OR die("<p>$qDetalleResumenIM</p><p>".mysql_error()."</p>");

		  $nDetalleResumenLIM	=	mysql_num_rows($rDetalleResumenLIM);

		  if($nDetalleResumenLIM > 0){			

		  for($a4 = 1;$dDetalleResumenLIM	= 	mysql_fetch_assoc($rDetalleResumenLIM); $a4++){	

			   ?>

				  <tr>

						<td align="left" ><input class="text" type="text" name="ot_limpresion<?=$numero2[$reg2]?>[]" size="12" value="<?=$dDetalleResumenLIM['orden_trabajo']?>" /></td>

						<td align="left" ><input class="text2" size="25" type="text" name="kg_limpresion<?=$numero2[$reg2]?>[]" id="<?=$a4?>_limpr<?=$id_maquina2[$reg2]?>" value="<?=$dDetalleResumenLIM['kilogramos']?>"   onkeydown="addTextBox2(<?=$numero2[$reg2]?>,<?=$id_maquina2[$reg2]?>,<?=$a4?>,<?=$dEntradaGeneral['turno']?>);"  onchange="sumarLimpr2(<?=$id_maquina2[$reg2]?>);  sumarImpr(); kh(<?=$dEntradaGeneral['turno']?>);" />

						  <input type="checkbox" name="bd_limpresion<?=$numero2[$reg2]?>[]" id="<?=$a4?>_limpr_bd<?=$id_maquina2[$reg2]?>" value="1" onClick="sumarLimpr2(<?=$id_maquina2[$reg2]?>,<?=$a4?>); sumarImpr(); kh(<?=$dEntradaGeneral['turno']?>);" <?=($dDetalleResumenLIM['bd']==1)?"checked=checked":"";?> />BD

						<input type="hidden" name="id_detalle_resumen_maquina_lim<?=$numero2[$reg2]?>[]" id="<?=$a4?>id_detalle_resumen_maquina_lim<?=$id_maquina2[$reg2]?>" value="<?=$dDetalleResumenLIM['id_detalle_resumen_maquina_im']?>"> 

						

						</td>

				

				</tr>

			  <?php } 

			   

				  } else { ?>

			  <?php for($b2=1;$b2<=1;$b2++) { ?>

			  <tr>

						  <td height="19"><input type="text"  class="numeros text" id="ot_limpresion<?=$id_maquina2[$reg2]?>_<?=$b2?>" name="ot_limpresion<?=$numero2[$reg2]?>[]" onFocus="this.select()" /></td>

					<td><input type="text" class="numeros text2" id="<?=$b2?>_limpr<?=$id_maquina2[$reg2]?>" name="kg_limpresion<?=$numero2[$reg2]?>[]" onKeyDown="addTextBox2(<?=$numero2[$reg2]?>,<?=$id_maquina2[$reg2]?>,<?=$b2?>,<?=$dEntradaGeneral['turno']?>);"  onFocus="this.select()"  onchange="sumarLimpr2(<?=$id_maquina2[$reg2]?>);  sumarImpr(); kh(<?=$dEntradaGeneral['turno']?>);" value="<?=$dDetalleResumenLIM['kilogramos']?>" />

					<input type="checkbox" name="bd_limpresion<?=$numero2[$reg2]?>[]" id="<?=$b2?>_limpr_bd<?=$id_maquina2[$reg2]?>" value="1" onClick="sumarLimpr2(<?=$id_maquina2[$reg2]?>,<?=$b2?>); sumarImpr(); kh(<?=$dEntradaGeneral['turno']?>);"  />BD

					<input type="hidden" name="id_detalle_resumen_maquina_lim<?=$numero2[$reg2]?>[]" id="<?=$b2?>id_detalle_resumen_maquina_lim<?=$id_maquina2[$reg2]?>" value=""> 

					</td>

				 </tr>

			   <? }

				} ?>	

				<tr><td colspan="2">

				<div id="limprtexto<?=$id_maquina2[$reg2]?>"></div>	

				</td>

				</tr>

				

				  <tr>

					  <td style="font-size:9px"><strong>Subtotal AD</strong><br /><strong>Subtotal BD</strong></td>

					  <td colspan="3">

					  <input size="25" style="width:100px" type="text" name="subtotal_limpr<?=$numero2[$reg2]?>" id="subtotal_<?=$id_maquina2[$reg2]?>_limpr_Total" onChange="sumarImpr();"  readonly="readonly" value="<?=$subtotales2[$reg2]?>" /><br />

					  <input size="25" style="width:100px" type="text" name="subtotalbd_limpr<?=$numero2[$reg2]?>" id="subtotal<?=$id_maquina2[$reg2]?>_limpr_bdTotal" onChange="sumarImpr();"  readonly="readonly" value="<?=$subtotalesbd2[$reg2]?>" />                  

					  </td>

				  </tr>

			  </tbody>

		</table>

		  

<? $reg2++;}?>

<br /></td><? } } ?>

</tr>

</table>

<? }?>



</p>    

			   <br><br>

<div id="datosgenerales" style="background-color:#FFFFFF;">

	  <table  align="center">

<tbody>

				  <tr>

					  <td width="94"></td>

					  <td width="82">Total HD.</td>

					  <td width="82">Total BD.</td>

					  <td width="82">D. HD.</td>

					  <td width="82">D. BD.</td>

					  <td width="82">K/H</td>

			</tr>

				  <tr>

					  <td><strong>Totales</strong></td>

					  <td><input name="total_impr_hd"  class="numeros" type="text" size="15" id="total_impr_hd"  value="<?=$dImpresion['total_hd']?>" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?>/></td>

					  <td><input name="total_impr_bd" type="text" class="numeros" size="15" id="total_impr_bd"  value="<?=$dImpresion['total_bd']?>" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?>/></td>

					  <td><input name="total_desperdicio_hd" class="numeros"  type="text" size="15" id="total_impr_tira" value="<?=$dImpresion['desperdicio_hd']?>" /></td>

					  <td><input name="total_desperdicio_bd" class="numeros" type="text" size="15" id="total_impr_duro" value="<?=$dImpresion['desperdicio_bd']?>" /></td>

					  <td><input type="text" name="k_h" id="k_h" size="10" value="<?=$dImpresion['k_h']?>"  <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>



				  </tr>

<tr>

					  <td valign="top">Observaciones:</td>

					  <td colspan="5" valign="top"><textarea cols="50" rows="6" name="observaciones_generales"><?=$dImpresion['observaciones'];?></textarea></td>

				  </tr>				

				  </tbody>

	  </table>

	  </div>          

	  <br><br><br><br>

	  

		  <p class="titulos_reportes">TIEMPOS MUERTOS</p>

		  

		  

<p align="center">          

		 

	  <?  

		  $sql_lic= "SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dImpresion['id_impresion']."' AND tipo = 2 ORDER BY maquina.numero ASC";

		  $res_lic=mysql_query($sql_lic);

		  $cant_lic=mysql_num_rows($res_lic);

		  $cant=ceil($cant_lic/3);

		  $d=0;

		  while($muertos = mysql_fetch_assoc($res_lic))

		  {

			  $codigo[$d]			=	$muertos['numero'];

			  $id[$d] 			= 	$muertos['id_maquina'];

			  $falta[$d]			=	$muertos['falta_personal'];

			  $mantenimiento[$d]	=	$muertos['mantenimiento'];

			  $mallas[$d]			=	$muertos['mallas'];

			  $fallo[$d]			=	$muertos['fallo_electrico'];

			  $otras[$d]			=	$muertos['otras'];

			  $observaciones[$d]	=	$muertos['observaciones'];

			  $cambio[$d]			=	$muertos['cambio_impresion'];

			  $d++;

		  }

		  

							   $muertotiempo 	= explode(":" ,$fallo[1]);  $ho	=	$muertotiempo[0]; $mi =	$muertotiempo[1];	

?>





 <h1><input type="checkbox" name="fallo_general" <? if($fallo[1] != '00:00:00') echo "checked"; ?> value="1"  onclick="muestra(1001); checar_tiempos(1001);" id="fallo_gen1001" />Fallo General El&eacute;ctrico</h1><br />

 <div class="style5" id="div_1001" style="display:<? if($fallo[1] != '00:00:00') echo "block"; else echo "none"; ?>; width:350px; padding:15px 10px;">

 Tiempo de fallo: <input type="text" name="horas_fallo" value="<?=$ho?>" size="2" />:<input type="text" name="minutos_fallo" value="<?=$mi?>"  size="2" />Hrs.<br />

			  </div> <br><br>   



<?

		  $reg=0;

		  for($i=0;$i<$cant;$i++)

{

?>

<table width="100%" align="center">

<tr>

  <? for($x=1;$x<=4; $x++){?>

<td width="190px" align="left" valign="top">

<? if($reg<$cant_lic){ ?>			             

	  <input type="hidden" name="id_maquina2m_<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />

	  <input type="hidden" name="tiempos_impr[]" value="<?=$codigo[$reg]?>" />

	  

	  <h3 align="left" style="color: rgb(255, 255, 255);"  class="Tips4" title="Click aqui para abrir o cerrar::"  onClick="muestra_impr('<?=$codigo[$reg]?>','impresion');" >FLEXO <?=$codigo[$reg]?> </h3>

<div id="impresion<?=$codigo[$reg]?>" style="display:<? if( $observaciones[$reg] != "" || $falta[$reg] != "00:00:00" || $mantenimiento[$reg] != "00:00:00" || $cambio[$reg] == 1 ||  $otras[$reg] != "00:00:00" ) echo "block"; else echo "none"; ?>; border:1px solid #ccc; height:100%; padding:8px 0px"> 

  <table width="120" >

	<thead>

				  <tr>

					  <td><strong>Obser.</strong></td>

					  <td colspan="3"><textarea name="ob_impr<?=$codigo[$reg]?>" id="ob_impr<?=$codigo[$reg]?>" cols="10" rows="3" /><?=$observaciones[$reg]?></textarea></td>

				  </tr>

				  <tr>

					  <td align="left"><strong>Cambio de impresi&oacute;n:</strong></td>

					  <td><input type="checkbox" value="1" name="ci_impr<?=$codigo[$reg]?>" <? if($cambio[$reg] == 1 ) echo "checked"; ?>  /></td>

				  </tr>

<!--                   	<tr>

					  <td align="left" class="style7">Falta de Personal:</td>

					  <td width="114"><input type="checkbox" name="fp_impr<?=$codigo[$reg]?>" value="1"  <? if($falta[$reg] != "00:00:00" ) echo "checked"; ?>  /></td>

				</tr>

-->				

				<tr>

					  <td width="102" align="left" class="style7">Otras causas:</td>

								  <? $tiempo4 	= explode(":" ,$otras[$reg]);  $horas4	=	$tiempo4[0]; $minutos4 =	$tiempo4[1];?>

					  <td width="114"><input type="text" name="oh_impr<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas4?>" />:

									  <input type="text" name="om_impr<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos4?>" /></td>

				</tr>                  

				  <tr>

					  <td align="left" class="style7">Mannto:</td>

								  <? $tiempo3 	= explode(":" ,$mantenimiento[$reg]);  $horas3	=	$tiempo3[0]; $minutos3	=	$tiempo3[1];?>

					  <td width="114"><input type="text" name="mh_impr<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas3?>" />:

									  <input type="text" name="mm_impr<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos3?>" /></td>

				</tr>

			  </thead>

		</table>

		</div>

		  

<? $reg++;}?></td><? }?>

</tr>

</table>

<? }?>

</p>    



	  <br /><br />

				 

	   <p align="center">          

		 

	  <?  

		  $sql_lic2	= 	"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dImpresion['id_impresion']."' AND tipo= 3  ORDER BY maquina.numero ASC";

		  $res_lic2	=	mysql_query($sql_lic2);

		  $cant_lic2	=	mysql_num_rows($res_lic2);

		  $cant2=ceil($cant_lic2/3);

		  $c=0;

		  while($muertos2 = mysql_fetch_assoc($res_lic2))

		  {

			  $codigo2[$c]		=	$muertos2['numero'];

			  $id2[$c] 			= 	$muertos2['id_maquina'];

			  $falta2[$c]			=	$muertos2['falta_personal'];

			  $mantenimiento2[$c]	=	$muertos2['mantenimiento'];

			  $mallas2[$c]		=	$muertos2['mallas'];

			  $fallo2[$c]			=	$muertos2['fallo_electrico'];

			  $otras2[$c]			=	$muertos2['otras'];

			  $observaciones2[$c]	=	$muertos2['observaciones'];

			  $cambio2[$c]		=	$muertos2['cambio_impresion'];

			  $c++;

		  } 

		  $reg2=0;

		  for($i2=0;$i2<$cant2;$i2++)

{

?>

<table width="100%" align="center">

<tr>

  <? for($x2=1;$x2<=4; $x2++){?>

<td width="190px" align="left" valign="top">  

<? if($reg2<$cant_lic2){ ?>			             

	  <input type="hidden" name="id_maquina3m_<?=$codigo2[$reg2]?>" value="<?=$id2[$reg2]?>" />

	  <input type="hidden" name="tiempos_limpr[]" value="<?=$codigo2[$reg2]?>" />

	  <h3 align="left" style="color: rgb(255, 255, 255);"  class="Tips4" title="Click aqui para abrir o cerrar::" onClick="muestra_impr('<?=$codigo2[$reg2]?>','linea');" >LINEA <?=$codigo2[$reg2]?></h3>

<div id="linea<?=$codigo2[$reg2]?>" style="display:<? if( $observaciones2[$reg2] != "" || $falta2[$reg2] != "00:00:00"  || $mantenimiento2[$reg2] != "00:00:00" || $cambio2[$reg2] == 1 ) echo "block"; else echo "none"; ?>; border:1px solid #ccc; height:100%; padding:8px 0px"> 

  <table width="183" >

  <thead>

				  <tr>

					  <td><strong>Obser.</strong></td>

					  <td colspan="3"><textarea  class="observaciones" name="ob_limpr<?=$codigo2[$reg2]?>" id="ob_limpr<?=$id2[$reg2]?>" cols="10" rows="3"  /><?=$observaciones2[$reg2]?></textarea></td>

				  </tr>

				  <tr>

					  <td align="left" class="style7">Cambio de impresi&oacute;n:</td>

					  <td width="88"><input type="checkbox" name="ci_limpr<?=$codigo2[$reg2]?>" value="1"  <? if($cambio2[$reg2] == 1 ) echo "checked"; ?> /></td>

				</tr>

<!--                  <tr>

					  <td align="left" class="style7">Falta de Personal:</td>

					  <td width="88"><input type="checkbox" name="fp_limpr<?=$codigo2[$reg2]?>" value="1" <? if($falta2[$reg2] != "00:00:00" ) echo "checked"; ?>/></td>

				  </tr>

-->                 

				  <tr>

					  <td width="83" align="left" class="style7">Otras causas:</td>

				<? $tiempo5 	= explode(":" ,$otras2[$reg2]);  $horas5	=	$tiempo5[0]; $minutos5 =	$tiempo5[1];?>

					  <td width="88"><input type="text" name="oh_limpr<?=$codigo2[$reg2]?>" size="2" maxlength="2" value="<?=$horas5?>" />:

									  <input type="text" name="om_limpr<?=$codigo2[$reg2]?>" size="2" maxlength="2" value="<?=$minutos5?>" /></td>

				  </tr>                    

				  <tr>

					  <td align="left" class="style7">Mannto:</td>

								  <? $tiempo5 	= explode(":" ,$mantenimiento2[$reg2]);  $horas5	=	$tiempo5[0]; $minutos5 =	$tiempo5[1];?>

					  <td width="88"><input type="text" name="mh_limpr<?=$codigo2[$reg2]?>" size="2" maxlength="2" value="<?=$horas5?>" />:

									  <input type="text" name="mm_limpr<?=$codigo2[$reg2]?>" size="2" maxlength="2" value="<?=$minutos5?>" /></td>

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

	  

	  

	  <br><br>

<div id="barraSubmit" style="background-color:#FFFFFF; text-align:right;">

					<p>

					  <input type="button" name="regresar" id="regresar" value="Regresar" onClick=" history.go(-1)">

					  <input type="submit" name="impresion" value="Guardar" />

								  </p>

  </div></div></div>

</div>

</form>





<? } if($listar) { ?>

<form id="form" action="admin.php?seccion=6" method="post">

<div id="container">

	  <div id="content">

	  

<? if(($_SESSION['area'] == 1  && isset($_GET['extruder'])) || isset($_SESSION['id_admin'])){ ?>

	  <h3 ><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES EXTRUDER ALTA DENSIDAD</a></h3>

		  <div style="background-color:#FFFFFF;" class="navcontainer">

			<table>

			  <tr>

				<td width="124" style="color:#000000"><b>Fecha</b></td>

				<td width="279" style="color:#000000"><b>Nombre</b></td>

				<td width="39" style="color:#000000"><b>Turno</b></td>

			  </tr>

			  <?php

			  $qGenerales		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general FROM entrada_general".

								  " INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE  ";

								  if(isset($_SESSION['id_admin'])){

			  $qGenerales		.=	" repesada = 1 AND ";

								  }

								  if(!isset($_SESSION['id_admin'])){

			  $qGenerales		.=	" entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."' AND ";

								  }

			  $qGenerales		.=	"DATEDIFF(CURRENT_DATE(),fecha) <= 2"; //Que no tenga más de 3 días de la creación

			  $qGenerales		.=	"/*AND autorizada = '0'*/ AND impresion = '0' ORDER BY fecha, turno ASC";

			  $rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");

			  $sGenerales		=	array();

			  while($dGenerales	=	mysql_fetch_assoc($rGenerales)){ ?>

			  <tr onMouseOver="this.style.backgroundColor='#CCC'"

				  onMouseOut="this.style.backgroundColor='#EAEAEA'" 

				  style="cursor:default; background-color:#EAEAEA">

				<td align="left"><?=fecha($dGenerales['fecha'])?></td>

				<td align="left"><?=$dGenerales['nombre']?></td>

				<td align="left"><?=$dGenerales['turno']?></td>

				<td width="81" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=autorizar&extruder&id_reporte=<?=$dGenerales['id_entrada_general']?>"><? if(isset($_SESSION['id_admin'])) { /*echo "Autorizar";*/ echo "Modificar"; } else if(!isset($_SESSION['id_admin'])){ echo "Modificar"; } ?></a></td>

				<td width="245" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_extruder&id_reporte=<?=$dGenerales['id_entrada_general']?>&accion2=listar" onClick="return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>

			  </tr>

			  <?php } ?>

			  <br />

			</table>

		</div><br><br>



<? }		  if(( $_SESSION['area2'] == 1  && isset($_GET['impresion'])) || isset($_SESSION['id_admin'])){ ?>

	  <h3 ><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES IMPRESION ALTA DENSIDAD</a></h3>

		  <div style="background-color:#FFFFFF;" class="navcontainer">

	  <table>



<tr>

					  <td width="124" style="color:#000000"><b>Fecha</b></td>

					<td width="279" style="color:#000000"><b>Nombre</b></td>

					<td width="39" style="color:#000000"><b>Turno</b></td>

			 </tr>

				

			  <?php

			  $qGenerales2		=	"SELECT fecha, supervisor.nombre, turno, entrada_general.id_entrada_general FROM entrada_general ".

								  "LEFT JOIN impresion  ON entrada_general.id_entrada_general = impresion.id_entrada_general ".

								  "LEFT JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE ";

								  if(isset($_SESSION['id_admin'])){

			  $qGenerales2		.=	"repesada = 1 AND ";

								  }

								  if(!isset($_SESSION['id_admin'])){

			  $qGenerales2		.=	" entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."' AND ";

								  }

			  $qGenerales2		.=	"DATEDIFF(CURRENT_DATE(),fecha) <= 2"; //Que no tenga más de 3 días de la creación

			  $qGenerales2		.=	"/*AND autorizada = '0'*/ AND impresion = '1' ORDER BY fecha, turno ASC";									

			  //$qGenerales2	.=	" autorizada = '0' AND impresion = '1' ORDER BY fecha,turno ASC";

			  $rGenerales2	=	mysql_query($qGenerales2) OR die("<p>$qGenerales2</p><p>".mysql_error()."</p>");

			  $sGenerales2	=	array();

			  while($dGenerales2	=	mysql_fetch_assoc($rGenerales2)){ ?>

			   <tr onMouseOver="this.style.backgroundColor='#CCC'"

				  onmouseout="this.style.backgroundColor='#EAEAEA'" 

				  style="cursor:default; background-color:#EAEAEA">                              

				  <td align="left"><?=fecha($dGenerales2['fecha'])?></td>

				 <td align="left"><?=$dGenerales2['nombre']?></td>

				 <td align="left"><?=$dGenerales2['turno']?></td>

				 <td width="81" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=impresion&impresion&id_reporte=<?=$dGenerales2['id_entrada_general']?>"><? if(isset($_SESSION['id_admin'])) { /*echo "Autorizar";*/ echo "Modificar"; } else if(!isset($_SESSION['id_admin'])){ echo "Modificar"; } ?></a></td>

				<td width="245" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_impresion&id_reporte=<?=$dGenerales2['id_entrada_general']?>&accion2=listar" onClick="return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>

		</tr>				

			  <?php } ?>

			  <br />

			</table>

	  </div><br><br>

	 

<? } if(($_SESSION['area3'] == 1 && isset($_GET['bolseo'])) || isset($_SESSION['id_admin'])){ ?>



	  <h3><a style="color: rgb(255, 255, 255);" href="#bolseo">REPORTES CAMISETA</a></h3>

		  <div style="background-color:#FFFFFF;" class="navcontainer">

		<table>

	<tr>

				  <td width="128" style="color:#000000"><b>Fecha</b></td>

				<td width="270" style="color:#000000"><b>Nombre</b></td>

				<td width="44" style="color:#000000"><b>Turno</b></td>

			</tr>

			  <?php

			  $qBolseo		=	"SELECT fecha, supervisor.nombre, turno,id_bolseo FROM bolseo".

								  " LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor  WHERE ";

								  if(isset($_SESSION['id_admin'])){

			  $qBolseo		.=	" repesada = 1 AND ";

								  }

								  if(!isset($_SESSION['id_admin'])){

			  $qBolseo		.=	" bolseo.id_supervisor = '".$_SESSION['id_supervisor']."' AND ";

								  }

			  $qBolseo		.=	"DATEDIFF(CURRENT_DATE(),fecha) <= 2"; //Que no tenga más de 3 días de la creación

			  $qBolseo		.=	" /*AND autorizada = '0'*/ ORDER BY fecha, turno ASC";

			  $rBolseo		=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");

			  $sBolseo		=	array();

			  while($dBolseo	=	mysql_fetch_assoc($rBolseo)){ ?>

			  <tr onMouseOver="this.style.backgroundColor='#CCC'"

  onmouseout="this.style.backgroundColor='#EAEAEA'" 

  style="cursor:default; background-color:#EAEAEA">

				  <td align="left"><?=fecha($dBolseo['fecha'])?></td>

				<td align="left"><?=$dBolseo['nombre']?></td>

				<td align="left"><?=$dBolseo['turno']?></td>

				<td width="81" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=autorizar_bolseo&bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>"><? if(isset($_SESSION['id_admin'])) { /*echo "Autorizar";*/ echo "Modificar"; } else if(!isset($_SESSION['id_admin'])){ echo "Modificar"; } ?></a></td>

				<td width="245" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>&accion2=listar" onClick="return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>

		</tr>

			  <?php } ?>               

			  <br />

			  </table>

		</div><br><br>

<? } ?>

   

  </div>

  

</div>

</form>



<? } ?>

<?php if($nuevaEntrada) { ?>

<SCRIPT LANGUAGE="JavaScript">

redirTime = "4000";

redirURL = "<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listar";

function redirTimer() { self.setTimeout("self.location.href = redirURL;",redirTime); }

//  FIN -->

</script>

<table align="center" width="100%">

<tr>

  <td align="center">

	  <div class="tablaCentrada">

		  <p><br>

			<br>

		  Se a actualizado el registro  en el sistema.<br>

		  <br>

		  </p>

		  <p align="center" style="color:#FF0000">Espere 4 segundos para redireccionarlo al listado nuevamente</p><br><br>

	  </div>

  </td>

</tr>

</table>

<?php } ?>





<? if($listarPendientes) { ?>

<form id="form" action="admin.php?seccion=6" method="post">

<div id="container">

	  <div id="content">

	  

<? if(($_SESSION['area'] == 1  && isset($_GET['extruder'])) || isset($_SESSION['id_admin'])){ ?>

	  <h3 ><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES EXTRUDER alta densisad</a></h3>

		  <div style="background-color:#FFFFFF;" class="navcontainer">

			<table>

			  <tr>

				<td width="124" style="color:#000000"><b>Fecha</b></td>

				<td width="279" style="color:#000000"><b>Nombre</b></td>

				<td width="39" style="color:#000000"><b>Turno</b></td>

			  </tr>

			  <?php

			  $qGenerales		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general FROM entrada_general".

								  " INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE  ".

								  " repesada = 0 /*AND autorizada = '0'*/ AND impresion = '0' ORDER BY fecha, turno ASC";

			  $rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");

			  $sGenerales		=	array();

			  while($dGenerales	=	mysql_fetch_assoc($rGenerales)){ ?>

			  <tr onMouseOver="this.style.backgroundColor='#CCC'"

				  onMouseOut="this.style.backgroundColor='#EAEAEA'" 

				  style="cursor:default; background-color:#EAEAEA">

				<td align="left"><?=fecha($dGenerales['fecha'])?></td>

				<td align="left"><?=$dGenerales['nombre']?></td>

				<td align="left"><?=$dGenerales['turno']?></td>

				<td width="81" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=autorizar&extruder&id_reporte=<?=$dGenerales['id_entrada_general']?>&pendiente=1">Modificar</a></td>

				<td width="245" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_extruder&id_reporte=<?=$dGenerales['id_entrada_general']?>&accion2=listarPendientes" onClick="return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>

			  </tr>

			  <?php } ?>

			  <br />

			</table>

		</div><br><br>



<? }		  if(( $_SESSION['area2'] == 1  && isset($_GET['impresion'])) || isset($_SESSION['id_admin'])){ ?>

	  <h3 ><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES IMPRESION alta densidad</a></h3>

		  <div style="background-color:#FFFFFF;" class="navcontainer">

	  <table>



<tr>

					  <td width="124" style="color:#000000"><b>Fecha</b></td>

					<td width="279" style="color:#000000"><b>Nombre</b></td>

					<td width="39" style="color:#000000"><b>Turno</b></td>

			 </tr>

				

			  <?php

			  $qGenerales2	=	"SELECT fecha, supervisor.nombre, turno, entrada_general.id_entrada_general FROM entrada_general ".

								  "LEFT JOIN impresion  ON entrada_general.id_entrada_general = impresion.id_entrada_general ".

								  "LEFT JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE ".

								  " repesada = 0 /*AND autorizada = '0'*/ AND impresion = '1' ORDER BY fecha, turno ASC";

			  $rGenerales2	=	mysql_query($qGenerales2) OR die("<p>$qGenerales2</p><p>".mysql_error()."</p>");

			  $sGenerales2	=	array();

			  while($dGenerales2	=	mysql_fetch_assoc($rGenerales2)){ ?>

			   <tr onMouseOver="this.style.backgroundColor='#CCC'"

				  onmouseout="this.style.backgroundColor='#EAEAEA'" 

				  style="cursor:default; background-color:#EAEAEA">                              

				  <td align="left"><?=fecha($dGenerales2['fecha'])?></td>

				 <td align="left"><?=$dGenerales2['nombre']?></td>

				 <td align="left"><?=$dGenerales2['turno']?></td>

				 <td width="81" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=impresion&impresion&id_reporte=<?=$dGenerales2['id_entrada_general']?>&pendiente=1">Modificar</a></td>

				<td width="245" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_impresion&id_reporte=<?=$dGenerales2['id_entrada_general']?>&accion2=listarPendientes" onClick="return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>

		</tr>				

			  <?php } ?>

			  <br />

			</table>

	  </div><br><br>

	 

<? } if(($_SESSION['area3'] == 1 && isset($_GET['bolseo'])) || isset($_SESSION['id_admin'])){ ?>



	  <h3><a style="color: rgb(255, 255, 255);" href="#bolseo">REPORTES camiseta</a></h3>

		  <div style="background-color:#FFFFFF;" class="navcontainer">

		<table>

	<tr>

				  <td width="128" style="color:#000000"><b>Fecha</b></td>

				<td width="270" style="color:#000000"><b>Nombre</b></td>

				<td width="44" style="color:#000000"><b>Turno</b></td>

			</tr>

			  <?php

			  $qBolseo		=	"SELECT fecha, supervisor.nombre, turno,id_bolseo FROM bolseo".

								  " LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor  WHERE ".

								  " repesada = 0 /*AND autorizada = '0'*/ ORDER BY fecha, turno ASC";

			  $rBolseo		=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");

			  while($dBolseo	=	mysql_fetch_assoc($rBolseo)){ ?>

			  <tr onMouseOver="this.style.backgroundColor='#CCC'"

  onmouseout="this.style.backgroundColor='#EAEAEA'" 

  style="cursor:default; background-color:#EAEAEA">

				  <td align="left"><?=fecha($dBolseo['fecha'])?></td>

				<td align="left"><?=$dBolseo['nombre']?></td>

				<td align="left"><?=$dBolseo['turno']?></td>

				<td width="81" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=autorizar_bolseo&bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>&pendiente=1">Modificar</a></td>

				<td width="245" align="left"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>&accion2=listarPendientes" onClick="return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>

		</tr>

			  <?php } ?>               

			  <br />

			  </table>

		</div><br><br>

<? } ?>

   

  </div>

  

</div>

</form>



<? } ?>

