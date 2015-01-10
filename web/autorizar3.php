<?php
include('libs/conectar.php');


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

$debug = false	;

if($debug)
	echo "<pre>";

	if(isset($_POST['extruder'])){
	
	$nueva_fecha	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

	if(isset($_SESSION['id_admin'])){
	$qGeneral	=	"UPDATE entrada_general  SET  fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno']." , autorizada ='1' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	}
	if(!isset($_SESSION['id_admin'])){
	$qGeneral	=	"UPDATE entrada_general  SET  fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno']." WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	}
	
	pDebug($qGeneral);
	$rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");

	$ID_GENERAL	=	mysql_insert_id();
	
	
	$qOrdenProduccion	=	"UPDATE orden_produccion  SET total = '{$_POST[total_extruder]}' , desperdicio_tira = '{$_POST[desperdicio_tira]}' , desperdicio_duro = '{$_POST[desperdicio_duro]}' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	
	$qOrdenID	=	"SELECT * orden_produccion WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	$rOrdenID	=	mysql_query($qOrdenID);
	$dOrdenID	=	mysql_fetch_assoc($rOrdenID);
	
	pDebug($qOrdenProduccion);
	$rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");
	$ID_ORDENPRODUCCION	=	mysql_insert_id();
	
	$nMaquinas	=	sizeof($_POST['codigos']);
	
	
	for($i=0;$i<$nMaquinas;$i++)
	{
		
		$sufijo				=	$_POST['codigos'][$i];
		$subtotal			=	floatval($_POST['subtotal_extruder'.$sufijo]);
		$id_maquina			=	intval($_POST['id_maquina'.$sufijo]);
	    $id_resumen_maquina_ex = intval($_POST['id_resumen_maquina_ex'.$sufijo]);
		$id_operador		=	intval($_POST['id_operador'.$sufijo]);
		$falta_personal		=	$_POST['fph_extruder'.$sufijo].':'.$_POST['fpm_extruder'.$sufijo].':'.$_POST['fps_extruder'.$sufijo];
		$fallo_electrico		=	$_POST['feh_extruder'.$sufijo].':'.$_POST['fem_extruder'.$sufijo].':'.$_POST['fes_extruder'.$sufijo];
		$mantenimiento		=	$_POST['mh_extruder'.$sufijo].':'.$_POST['mm_extruder'.$sufijo].':'.$_POST['ms_extruder'.$sufijo];

		$observacion		=	$_POST['ob_extruder'.$sufijo];

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
	
	pDebug("Terminado");
	pDebug("Comienza volcado de la petición");

	if($_REQUEST['menio'] == 0)
	echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion='.$_REQUEST['seccion'].'&accion=nuevaentrada\';</script>';

	if($_REQUEST['menio'] == 1 && isset($_SESSION['id_admin']))
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=30\';</script>'; 
 
 
	}
	/* -- Termina parte de Extruder -- */

	
	/* Comienza Impresión */
	if(isset($_POST['impresion'])){
	
	$nueva_fecha	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

	if(isset($_SESSION['id_admin'])){
	$qGeneral	=	"UPDATE entrada_general SET fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno']." , autorizada ='1' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	}
	if(!isset($_SESSION['id_admin'])){
	$qGeneral	=	"UPDATE entrada_general SET fecha = '$nueva_fecha', turno = ".$_REQUEST['id_turno']."  WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	}
	pDebug($qGeneral);
	$rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");
	
	$qImpresion		=	"UPDATE impresion  SET total_hd = '{$_POST[total_impr_hd]}', total_bd = '{$_POST[total_impr_bd]}', desperdicio_hd = '{$_POST[total_desperdicio_hd]}', desperdicio_bd = '{$_POST[total_desperdicio_bd]}' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	pDebug($qImpresion);
			
	$rImpresion		=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
	$ID_IMPRESION	=	mysql_insert_id();

	$nMaquinas	=	sizeof($_POST['codigos2']);	

	for($i=0;$i<$nMaquinas;$i++)
	{
		$sufijo				=	$_POST['codigos2'][$i];
		$subtotal			=	floatval($_POST['subtotal_impr'.$sufijo]);
		$id_maquina			=	intval($_POST['id_maquina2_'.$sufijo]);
	    $id_resumen_maquina_im = intval($_POST['id_resumen_maquina_im'.$sufijo]);
		$id_operador		=	intval($_POST['id_operador2_'.$sufijo]);

		
		$observacion		=	$_POST['ob_impr'.$sufijo];


		/* La segunda entrada es para impresión y mandamos linea_impresion = 0  */
			
		
		$qOrdenIM	=	"SELECT * impresion WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
		$rOrdenIM	=	mysql_query($qOrdenIM);
		$dOrdenIM	=	mysql_fetch_assoc($rOrdenIM);
		
				
	
		$qVerificaMaquinaIM	=	"SELECT * FROM resumen_maquina_im WHERE id_resumen_maquina_im = '".$id_resumen_maquina_im."'";
		$rVerificaMaquinaIM	=	mysql_query($qVerificaMaquinaIM);
		
		$nVerificaMaquinaIM	= 	mysql_num_rows($rVerificaMaquinaIM);
		
		if($nVerificaMaquinaIM > 0){	
		$qResumenMaquinaIM			=	"UPDATE resumen_maquina_im  SET  id_operador = '$id_operador',  subtotal = '$subtotal', observacion =  '$observacion' WHERE id_resumen_maquina_im = ".$id_resumen_maquina_im." ";
		}
		if($nVerificaMaquinaIM == 0)
		{
			$qResumenMaquinaIM	=	"INSERT INTO resumen_maquina_im (id_impresion, id_maquina, id_operador, subtotal, observacion) ".
									"VALUES ('".$dOrdenID['id_impresion']."','$id_maquina','$id_operador','$subtotal','$observacion')";
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
			$qDetalleResumen	= " UPDATE detalle_resumen_maquina_im SET orden_trabajo = '$ot_impresion', kilogramos = '$kg_impresion' WHERE id_detalle_resumen_maquina_im = '".$id_detalle_resumen_maquina_im."' ";
			}
			if($nVerificaMaquinaDIM == 0){
			$qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_im (id_resumen_maquina_im,orden_trabajo,kilogramos) VALUES ('$id_resumen_maquina_im','$ot_impresion','$kg_impresion')";
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
		$id_maquina			=	intval($_POST['id_maquina3_'.$sufijo]);
	    $id_resumen_maquina_lim = intval($_POST['id_resumen_maquina_lim'.$sufijo]);
		$id_operador		=	intval($_POST['id_operador3_'.$sufijo]);



		$qVerificaMaquinaLIM	=	"SELECT * FROM resumen_maquina_im WHERE id_resumen_maquina_im = '".$id_resumen_maquina_lim."'";
		$rVerificaMaquinaLIM	=	mysql_query($qVerificaMaquinaLIM);
		
		$nVerificaMaquinaLIM	= 	mysql_num_rows($rVerificaMaquinaLIM);
		
		if($nVerificaMaquinaLIM > 0){	
		$qResumenMaquinaLIM			=	"UPDATE resumen_maquina_im  SET   subtotal = '$subtotal', observacion =  '$observacion' WHERE id_resumen_maquina_im = ".$id_resumen_maquina_lim." ";
		}
		if($nVerificaMaquinaLIM == 0)
		{
			$qResumenMaquinaLIM	=	"INSERT INTO resumen_maquina_im (id_impresion, id_maquina, id_operador, subtotal, observacion) ".
									"VALUES ('".$dOrdenIM['id_impresion']."','$id_maquina','$id_operador','$subtotal','$observacion')";
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
			$qDetalleResumen	= " UPDATE detalle_resumen_maquina_im SET orden_trabajo = '$ot_impresion', kilogramos = '$kg_impresion' WHERE id_detalle_resumen_maquina_im = '".$id_detalle_resumen_maquina_lim."' ";
			}
			if($nVerificaMaquinaDLIM == 0){
			$qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_im (id_resumen_maquina_im,orden_trabajo,kilogramos) VALUES ('$id_resumen_maquina_lim','$ot_impresion','$kg_impresion')";
			}
			
		pDebug($qDetalleResumen);
		
		$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
				}
	
		}

	}
	



	/* -- Termina parte de Impresión */

	pDebug("Terminado");
	pDebug("Comienza volcado de la petición");

	if($_REQUEST['menio'] == 0)
	echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion='.$_REQUEST['seccion'].'&accion=nuevaentrada\';</script>';

	if($_REQUEST['menio'] == 1 && isset($_SESSION['id_admin']))
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=30\';</script>'; 
	
	}  
	

if(isset($_POST['bolseo'])){
	
	$debug = false;
	if($debug)
	{	
		echo "<pre>";
		print_r($_REQUEST);
	}

	$nMaquinas	=	sizeof($_POST['id_maquinas']);
	
	$nueva_fecha =	preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);
	
	if(isset($_SESSION['id_admin'])){
	$qBolseo	=	"UPDATE bolseo SET fecha = '$nueva_fecha' ,turno = ".$_REQUEST['id_turno']." , kilogramos = '{$_POST[tkg]}', millares = '{$_POST[tml]}',dtira = '{$_POST[tdt]}',dtroquel = '{$_POST[tdtr]}',segundas = '{$_POST[tse]}' , autorizada = 1, m_p = '".$_POST['m_p']."' WHERE id_bolseo = '".$_POST['id_bolseo']."'";
	}
	if(!isset($_SESSION['id_admin'])){
	$qBolseo	=	"UPDATE bolseo SET fecha = '$nueva_fecha' ,turno = ".$_REQUEST['id_turno']." , kilogramos = '{$_POST[tkg]}', millares = '{$_POST[tml]}',dtira = '{$_POST[tdt]}',dtroquel = '{$_POST[tdtr]}',segundas = '{$_POST[tse]}',  m_p = '".$_POST['m_p']."'  WHERE id_bolseo = '".$_POST['id_bolseo']."'";
	}
	pDebug($qBolseo);
	$rBolseo	=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");
	$ID_BOLSEO	=	mysql_insert_id();
		
		$qOrdenIM	=	"SELECT * bolseo WHERE id_bolseo = ".$_REQUEST['id_entrada_general']."";
		$rOrdenIM	=	mysql_query($qOrdenIM);
		$dOrdenIM	=	mysql_fetch_assoc($rOrdenIM);

	
	for($i=0; $i<$nMaquinas; $i++)
	{
		$sufijo			=	$_POST['id_maquinas'][$i];
		$sKilogramos	=	$_POST["kgs_$sufijo"];
		$sMillares		=	$_POST["mls_$sufijo"];
		$sTira			=	$_POST["dts_$sufijo"];
		$sTroquel		=	$_POST["dtrs_$sufijo"];
		$sSegundas		=	$_POST["ses_$sufijo"];
		$id_maquina			=	$sufijo;
	 	$id_operador		=	$_POST['id_operadores'][$i];
		$id_resumen_maquina_bs		=	$_POST['id_resumen_maquina_bs'][$i];


		$qVerificaMaquina	=	"SELECT * FROM resumen_maquina_bs WHERE id_resumen_maquina_bs = ".$id_resumen_maquina_bs."";
		$rVerificaMaquina	=	mysql_query($qVerificaMaquina);
		
		$nVerificaMaquina	= 	mysql_num_rows($rVerificaMaquina);
		
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

			$fDetalles	=	true;
			$kg			=	floatval($_POST["kg_$sufijo"][$j]);
			$ml			=	floatval($_POST["ml_$sufijo"][$j]);
			$dt			=	floatval($_POST["dt_$sufijo"][$j]);
			$dtr		=	floatval($_POST["dtr_$sufijo"][$j]);
			$se			=	floatval($_POST["se_$sufijo"][$j]);	
			$orden		=	floatval($_POST["ot_$sufijo"][$j]);		
   			$id_detalle_resumen_maquina_bs = intval($_POST["id_detalle_resumen_maquina_bs_$sufijo"][$j]);
			
			if( empty($kg) && empty($ml) && empty($dt) && empty($dtr) && empty($se) && empty($orden) && $id_detalle_resumen_maquina_bs != '0'){
				$qBorrar	=	"DELETE FROM detalle_resumen_maquina_bs WHERE (id_detalle_resumen_maquina_bs = ".$id_detalle_resumen_maquina_bs.") ";
				pDebug($qBorrar);	
				$rBorrar	=	mysql_query($qBorrar);
				
			}			
			
			if( empty($kg) && empty($ml) && empty($dt) && empty($dtr) && empty($se) && empty($orden) && $id_detalle_resumen_maquina_bs == '0'){
			 continue;
			}
				
			if( !empty($kg) && !empty($ml) && !empty($dt) && !empty($dtr) && !empty($se) && !empty($orden)){

				$qVerificaMaquinaDBS	=	"SELECT * FROM detalle_resumen_maquina_bs WHERE id_detalle_resumen_maquina_bs = ".$id_detalle_resumen_maquina_bs."";
				$rVerificaMaquinaDBS	=	mysql_query($qVerificaMaquinaDBS);
				$nVerificaMaquinaDBS	=	mysql_num_rows($rVerificaMaquinaDBS);			
			/* Esto sitácticamente puede ir en una línea, pero extrañamente PHP tiene pedos con las pilas con asignaciones contiguas */
				pDebug($nVerificaMaquinaDBS);
			//$qDetalleResumen	.=	($j>0)?",":"";
			
			if($nVerificaMaquinaDBS > 0){
			$qDetalleResumen	= " UPDATE detalle_resumen_maquina_bs SET orden = '$orden', kilogramos = '$kg', millares = '$ml', dtira = '$dt', dtroquel = '$dtr', segundas = '$se' WHERE id_detalle_resumen_maquina_bs = '".$id_detalle_resumen_maquina_bs."' ";
			}
			if($nVerificaMaquinaDBS == 0){
			$qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_bs (id_resumen_maquina_bs, kilogramos, millares, dtira, dtroquel, segundas, orden) VALUES ('$id_resumen_maquina_bs','$kg','$ml','$dt','$dtr','$se','$orden')";
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
		$falta_personal		=	$_POST['fph_bolseo'.$sufijo].':'.$_POST['fpm_bolseo'.$sufijo].':00'; //"08:00:00";
		}
		
		if($_POST['fp_bolseo'.$sufijo] != 1){
		$falta_personal		=	"00:00:00";
		}	
			
		$fallo_electrico	=	$_POST['feh_bolseo'.$sufijo].':'.$_POST['fem_bolseo'.$sufijo].':00';
		$mantenimiento		=	$_POST['mh_bolseo'.$sufijo].':'.$_POST['mm_bolseo'.$sufijo].':00';
		$observacion		=	$_POST['ob_bolseo'.$sufijo];

		$qActualizaTiemposBs			=	"UPDATE tiempos_muertos SET  falta_personal = '$falta_personal' , observaciones = '$observacion', fallo_electrico = '$fallo_electrico', mantenimiento = '$mantenimiento' ".
								" WHERE id_produccion = ".$ID_GENERAL." AND id_maquina = ".$id_maquina." ";
		pDebug($qActualizaTiemposBs);
		
		$rActualizaTiemposBs			=	mysql_query($qActualizaTiemposBs) OR die("<p>$qActualizaTiemposBs</p><p>".mysql_error()."</p>");
				
	}	
	
	/*
	if($_REQUEST['menio'] == 0)
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=6&accion=nuevaentrada\';</script>';

	if($_REQUEST['menio'] == 1)
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=30\';</script>';  */
 
		//exit();
}
	


if(!empty($_GET['accion']))
{
	$listar		=	($_GET['accion']=="listar")?true:false;
	$nuevaEntrada 	= ($_GET['accion']=="nuevaentrada")?true:false;

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
		$qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
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
		$qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
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
		while($dSeleccion	=	mysql_fetch_assoc($rSeleccion)){
			
			$qSeleccion2	=	"SELECT * FROM resumen_maquina_ex WHERE id_orden_produccion = ".$dSeleccion['id_orden_produccion']."";
			$rSeleccion2	=	mysql_query($qSeleccion2) OR die("<p>$qSeleccion2</p><p>".mysql_error()."</p>");
				while($dSeleccion2	=	mysql_fetch_assoc($rSeleccion2)){
				
					$qSeleccion3	=	"DELETE FROM detalle_resumen_maquina_ex	WHERE (id_resumen_maquina_ex = ".$dSeleccion2['id_resumen_maquina_ex'].") ";
					$rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");
		
				}
		
				
			$qSeleccion4	=	"DELETE FROM resumen_maquina_ex	WHERE  (id_orden_produccion = ".$dSeleccion['id_orden_produccion'].") ";
			$rSeleccion4	=	mysql_query($qSeleccion4) OR die("<p>$qSeleccion4</p><p>".mysql_error()."</p>");
			
	}		
			$qSeleccion3	=	"DELETE FROM orden_produccion WHERE  (id_entrada_general = ".$_REQUEST['id_reporte'].") ";
			$rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");
						
		$qEliminaExtr	=	"DELETE FROM entrada_general WHERE (id_entrada_general=".$_REQUEST['id_reporte'].")";
		$rEliminaExtr	=	mysql_query($qEliminaExtr) OR die("<p>$qEliminaExtr</p><p>".mysql_error()."</p>");
	
	
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin_supervisor.php?seccion={$_GET[seccion]}&accion=listar&extruder";
	}		
	
	
	if($eliminar_impresion)
	{	

	$qSeleccion 	=	"SELECT * FROM impresion WHERE id_entrada_general = '".$_REQUEST['id_reporte']."'";
	$rSeleccion		=	mysql_query($qSeleccion)  OR die("<p>$qSeleccion</p><p>".mysql_error()."</p>");
		while($dSeleccion	=	mysql_fetch_assoc($rSeleccion)){
			
			$qSeleccion2	=	"SELECT * FROM resumen_maquina_im WHERE id_impresion = ".$dSeleccion['id_impresion']."  ";
			$rSeleccion2	=	mysql_query($qSeleccion2) OR die("<p>$qSeleccion2</p><p>".mysql_error()."</p>");
				while($dSeleccion2	=	mysql_fetch_assoc($rSeleccion2)){
				
					$qSeleccion3	=	"DELETE FROM detalle_resumen_maquina_im WHERE (id_resumen_maquina_im = ".$dSeleccion2['id_resumen_maquina_im'].") ";
					$rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");
		
				}
		
				
			$qSeleccion4	=	"DELETE FROM resumen_maquina_im	WHERE  (id_impresion = ".$dSeleccion['id_impresion'].") ";
			$rSeleccion4	=	mysql_query($qSeleccion4) OR die("<p>$qSeleccion4</p><p>".mysql_error()."</p>");
	}		
			
			$qSeleccion3	=	"DELETE FROM impresion WHERE  (id_entrada_general =".$_REQUEST['id_reporte'].") ";
			$rSeleccion3	=	mysql_query($qSeleccion3) OR die("<p>$qSeleccion3</p><p>".mysql_error()."</p>");
						
		$qEliminaImpr	=	"DELETE FROM entrada_general WHERE (id_entrada_general=".$_REQUEST['id_reporte'].")";
		$rEliminaImpr	=	mysql_query($qEliminaImpr) OR die("<p>$qEliminaExtr</p><p>".mysql_error()."</p>");
	
	
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin_supervisor.php?seccion={$_GET[seccion]}&accion=listar&impresion";
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
	
	
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin_supervisor.php?seccion={$_GET[seccion]}&accion=listar&bolseo";
	}				
	
	

}
?>
<head>
	<script type="text/javascript" src="mootools.js"></script>
	<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />	
    <!--<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />-->
	<title>Reportes</title>
	<style type="text/css" media="screen">@import 'style.css';</style>
    <style type="text/css" media="screen">
	<!--
	ul.navlist
	{
		width: 558px;
		\width: 560px;
		w\idth: 558px;
		padding: 0px;
		border: 1px solid #808080;
		border-top: 0px;
		margin: 0px;
		font: bold 12px verdana,helvetica,arial,sans-serif;
		background: #FFFFFF;
	}
	
	ul.navlist li
	{
		list-style: none;
		margin: 0px;
		border: 0px;
		border-top: 1px solid #FFFFFF;
	}
	
	ul.navlist li a
	{
		display: block;
		width: 522px;
		\width: 558px;
		w\idth: 522px;
		padding: 4px 8px 4px 8px;
		border: 0px;
		border-left: 20px solid #aaaabb;
		background: #FFFFFF;
		text-decoration: none;
		text-align: right;
	}
	
	ul.navlist li a:link { color: #666677; }
	div.navcontainer li a:visited { color: #666677; }

	div.navcontainer { padding-left: 40px; }

	ul.navlist li a:hover
	{
		border-color:#0A4662;
		color: #ffffff;
		background: #000d33;
	}
	-->
	</style>
</head>
<body>
<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
<? if($autorizar_bolseo){ ?>
<script type="text/javascript" language="javascript1.1" >
function mh()
{
     var total;
     total		=	0;
	 total 		=  parseFloat(document.getElementById('tml').value) / <? if($dBolseo['turno'] == '1') echo '8'; if($dBolseo['turno'] == '2') echo '7'; if($dBolseo['turno'] == '3') echo '9';  ?>;
     document.getElementById('m_p').value = redondear(total,2);
}	
</script>

<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dBolseo['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dBolseo['fecha'])?>" id="fecha" class="datosgenerales" /><br />
                <label for="fecha">Turno</label>
                <select  name="id_turno" id="id_turno" class="datosgenerales">
                	<option value="1" <? if($dBolseo['turno'] == 1) echo "selected";?> >Matutino</option>
                	<option value="2" <? if($dBolseo['turno'] == 2) echo "selected";?>>Vespertino</option>
                	<option value="3" <? if($dBolseo['turno'] == 3) echo "selected";?>>Nocturno</option>
                </select> 
          <br />
                <input type="hidden" name="id_supervisor" value="<?=$dBolseo['id_supervisor']?>" />
                <input type="hidden" name="area" id="area" value="<?=$dBolseo['area']?>" />
                <input type="hidden" name="id_bolseo" id="id_bolseo" value="<?=$dBolseo['id_bolseo']?>" />
                <input type="hidden" name="menio" value="<? if(isset($_REQUEST['menio'])) echo $_REQUEST['menio']; else  echo '0'; ?>">
			</p>
			<table class="tablaCentrada">
				<colgroup>
					<col class="ordenTrabajo" />
					<col class="kilogramos" />
					<col class="millares" />
					<col class="desperdicioTira" />
					<col class="desperdicioTroquel" />
					<col class="segundas" />
				</colgroup>
				
			</table>
		</div>
		<br />
		<? 	
		$qResumenMaquinas	=	"SELECT * FROM resumen_maquina_bs INNER JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina ".
								" LEFT JOIN operadores ON resumen_maquina_bs.id_operador = operadores.id_operador WHERE (id_bolseo=".$dBolseo['id_bolseo'].") ORDER BY maquina.numero ASC";
				$rResumenMaquinas	=	mysql_query($qResumenMaquinas) OR die("<p>$qResumenMaquinas</p><p>".mysql_error()."</p>");
				while($dResumenMaquinas	=	mysql_fetch_assoc($rResumenMaquinas)){
		 ?>
		<input type="hidden" name="id_maquinas[]" value="<?=$dResumenMaquinas['id_maquina']?>" />
        <input type="hidden"  name="id_resumen_maquina_bs[]" value="<?=$dResumenMaquinas['id_resumen_maquina_bs'] ?>" />
 
		<h3 >EMBOLSADORA <?=$dResumenMaquinas['numero']?>: 
	    <select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:300px"   name="id_operadores[]" >
        <option value="0">No hay operador</option>
		<? 
		$qOperador = "SELECT * FROM operadores WHERE status = 0 AND area = 2 ORDER BY nombre";
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
				<colgroup>
					<col class="ordenTrabajo" />
					<col class="millares" />
					<col class="kilogramos" />
					<col class="desperdicioTira" />
					<col class="desperdicioTroquel" />
					<col class="segundas" />
				</colgroup>
				<thead>
					<tr>
						<td><b>O. T.</b></td>
						<td><b>Millares</b></td>
						<td><b>Kilogramos</b></td>
						<td><b>D. Tira</b></td>
						<td><b>D. Troquel</b></td>
						<td><b>Segundas</b></td>
					</tr>
				</thead>
				<tbody><? 
					$qDetalleResumen	=	"SELECT * FROM detalle_resumen_maquina_bs WHERE (id_resumen_maquina_bs = ".$dResumenMaquinas['id_resumen_maquina_bs'].")";
					$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
			$nDetalleResumen	=	mysql_num_rows($rDetalleResumen);
			
			if($nDetalleResumen > 0){					
					for($a = 1;$dDetalleResumen	= 	mysql_fetch_assoc($rDetalleResumen); $a++){	
					?>					
                    <tr>
						<td><input type="text" class="numeros" id="ot_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="ot_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['orden']?>" /></td>
						<td><input type="text" class="numeros" id="ml_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="ml_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['millares']?>" onKeyDown=" javascript: mh();"/></td>
						<td><input type="text" class="numeros" id="kg_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="kg_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['kilogramos']?>" onKeyDown="javascript: addTextBolseo(<?=$dResumenMaquinas['id_maquina']?>,<?=$a?>); mh(); "/></td>
                        <td><input type="text" class="numeros" id="dt_<?=$dResumenMaquinas['id_maquina'	]?>_<?=$a?>" name="dt_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['dtira']?>" /></td>
						<td><input type="text" class="numeros" id="dtr_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="dtr_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['dtroquel']?>" /></td>
						<td><input type="text" class="numeros" id="se_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="se_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['segundas']?>" />
                            <input type="text" name="id_detalle_resumen_maquina_bs_<?=$dResumenMaquinas['id_maquina']?>[]" id="<?=$a?>id_detalle_resumen_maquina_bs_<?=$dResumenMaquinas['id_maquina']?>" value="<?=$dDetalleResumen['id_detalle_resumen_maquina_bs']?>"> 

                        
                        </td>
					</tr>
				<?php 
				} 

					} else { ?>
				<?php for($b=1;$b<=1;$b++) { ?>
				<tr>
						<td><input type="text" class="numeros" id="ot_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="ot_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);"  /></td>
						<td><input type="text" class="numeros" id="ml_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="ml_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" onKeyDown=" javascript: mh();"  /></td>
						<td><input type="text" class="numeros" id="kg_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="kg_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);"  onKeyDown="javascript: addTextBolseo(<?=$dResumenMaquinas['id_maquina']?>,<?=$b?>); mh(); "/></td>
                        <td><input type="text" class="numeros" id="dt_<?=$dResumenMaquinas['id_maquina'	]?>_<?=$b?>" name="dt_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" /></td>
						<td><input type="text" class="numeros" id="dtr_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="dtr_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);"  /></td>
						<td><input type="text" class="numeros" id="se_<?=$dResumenMaquinas['id_maquina']?>_<?=$b?>" name="se_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" />
                            <input type="text" name="id_detalle_resumen_maquina_bs_<?=$dResumenMaquinas['id_maquina']?>[]" id="<?=$b?>id_detalle_resumen_maquina_bs_<?=$dResumenMaquinas['id_maquina']?>" value="0"> 

                        
                        </td>
                   </tr>
                 <? }
				  } ?>	
   				 <tr><td colspan="7"><table width="100%">
                  <div id="texto<?=$dResumenMaquinas['id_maquina']?>"></div></table>			
                  </td>
                  </tr>
					<tr>
						<td><strong>Subtotales</strong></td>
						<td><input type="text" id="mls_<?=$dResumenMaquinas['id_maquina']?>" name="mls_<?=$dResumenMaquinas['id_maquina']?>"  	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['millares']?>" readonly/></td>
						<td><input type="text" id="kgs_<?=$dResumenMaquinas['id_maquina']?>" name="kgs_<?=$dResumenMaquinas['id_maquina']?>" 	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['kilogramos']?>" readonly/></td>
                        <td><input type="text" id="dts_<?=$dResumenMaquinas['id_maquina']?>" name="dts_<?=$dResumenMaquinas['id_maquina']?>"  	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['dtira']?>" readonly/></td>
						<td><input type="text" id="dtrs_<?=$dResumenMaquinas['id_maquina']?>" name="dtrs_<?=$dResumenMaquinas['id_maquina']?>"	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['dtroquel']?>" readonly/></td>
						<td><input type="text" id="ses_<?=$dResumenMaquinas['id_maquina']?>" name="ses_<?=$dResumenMaquinas['id_maquina']?>" 	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['segundas']?>" readonly/></td>
					</tr>

				</tbody>
			</table>
		</div>
<?php } ?>
        <br /><br />
        <div id="datosgenerales" style="background-color:#FFFFFF;">
        <table align="center">
        <tbody>
					<tr>
						<td><strong>Totales</strong></td>
						<td><input type="text" id="tml" 	name="tml" value="<?=$dBolseo['millares']?>" 	class="total"  	<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>
						<td><input type="text" id="tkg" 	name="tkg" value="<?=$dBolseo['kilogramos']?>" class="total"	<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>
						<td><input type="text" id="tdt" 	name="tdt" value="<?=$dBolseo['dtira']?>" 		class="total"  	<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>
						<td><input type="text" id="tdtr" 	name="tdtr" value="<?=$dBolseo['dtroquel']?>" 	class="total"	<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>
						<td><input type="text" id="tse" 	name="tse" value="<?=$dBolseo['segundas']?>" 	class="total" 	<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>
					</tr>
                   	<tr>
                    	<td><strong>Millares por Hora:</strong></td>
                        <td colspan="5"><input type="text" id="m_p" name="m_p" value="<?=$dBolseo['m_p']?>" readonly="readonly" class="total" /></td>
                    </tr>
				</tbody>
        </table>	
        </div>
        
    		<p align="center"><strong><br>
   		      <br>
	          <br>
            <br />
   		    TIEMPOS MUERTOS</strong><br />
   		    <br />
    		</p>
            
            
      <p align="center">          
           
		<?  
		echo 	$sql_lic= "SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dBolseo['id_bolseo']."' AND tipo= 4 ";
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
				$fallo[$c]			=	$muertos['fallo_electrico'];
				$observaciones[$c]	=	$muertos['observaciones'];
				$c++;
			}
			$reg=0;
			for($i=0;$i<$cant;$i++)
{
?>
<table width="70%" align="center" style="border:#CCCCCC; border:thin">
<tr>
    <? for($x=1;$x<=1; $x++){?>
<td width="70%" align="center" valign="top"><br />
  <? if($reg<$cant_lic){ ?>		

		<?
		
		    $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";
			$rOperadorextr = mysql_query($qOperadorextr);
			$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);?>
        <input type="hidden" name="id_maquina2_<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />
        <input type="hidden" name="codigos2[]" value="<?=$codigo[$reg]?>" />
		<h3 align="left" style="color: rgb(255, 255, 255);">MAQUINA <?=$codigo[$reg]?>
		  <input type="checkbox" name="<?=$id[$reg]?>" id="<?=$id[$reg]?>" onClick="muestra('<?=$id[$reg]?>')" />
		</h3>
<div id="div_<?=$id[$reg]?>" style="display:none">
<table width="88%" height="137" align="left" >
<tr>
						<td height="39" align="left"><strong>Observaciones</strong></td>
				  <td colspan="3" align="left"><textarea name="ob_bolseo<?=$codigo[$reg]?>" id="ob_bolseo<?=$codigo[$reg]?>" cols="55" rows="2" /><?=$observaciones[$reg]?></textarea></td>
                    </tr>
                  	<tr>
           	  			<td align="left" class="style7">Falta de Personal :</td>
               		  <td width="460" align="left"><input type="checkbox" name="fp_bolseo<?=$codigo[$reg]?>" value="1" onClick="mostrar('<?=$id[$reg]?>')" /></td>
                </tr>
                   <tr align="left"> 
                    <td colspan="2" class="style7"><div id="tiempo_<?=$id[$reg]?>" style="display:<? if($observaciones[$reg] == ""){ echo "none"; } else { echo "block";} ?>">
                    <table width="100%">
                    <tr><td width="129" align="left" class="style7">Tiempo :</td>
                    <? $tiempo 	= explode(":" ,$falta[$reg]);  $horas	=	$tiempo[0]; $minutos =	$tiempo[1];?>
                     <td width="456" align="left"><input type="text" name="fph_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas?>" />:
               				  <input type="text" name="fpm_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos?>" /></td>
                    </tr></table></div>
					</td></tr>
                                   
                    <tr>
           	  			<td width="133" align="left" class="style7">Fallo E. Electrica :</td>
                    <? $tiempo2 	= explode(":" ,$fallo[$reg]);  $horas2	=	$tiempo2[0]; $minutos2 =	$tiempo2[1];?>
           		  	  <td width="460" align="left"><input type="text" name="feh_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas2?>" />:
                        				<input type="text" name="fem_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos2?>" /></td>
   			    </tr>
             		<tr>
           	  			<td align="left" class="style7">Mantenimiento :</td>
                    <? $tiempo3 	= explode(":" ,$mantenimiento[$reg]);  $horas3	=	$tiempo3[0]; $minutos3 =	$tiempo3[1];?>
               			<td width="460" align="left"><input type="text" name="mh_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$horas3?>" />:
                        				<input type="text" name="mm_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="<?=$minutos3?>" /></td>
                </tr>
			</table>
		  </div>

  <? $reg++;}?></td><? }?>
</tr>
</table>
  <? }?>   <br>
<br>
<br>
<br>
      </p>
        
        
<div id="barraSubmit" style="background-color:#FFFFFF; text-align:center;">
                      <input type="button" name="regresar" id="regresar" value="Regresar" onClick=" javascript: history.go(-1)">
			<input type="submit" name="bolseo" value="Guardar" />
		</div>
		<?php }  if($autorizar){ ?>
        
<script language="javascript" type="text/javascript">       
function kh()
{
     var total;
     total		=	0;
	 total 		=  parseFloat(document.getElementById('total_extr').value) / <? if($dEntradaGeneral['turno'] == '1') echo '8'; if($dEntradaGeneral['turno'] == '2') echo '7'; if($dEntradaGeneral['turno'] == '3') echo '9';  ?>;
     document.getElementById('kilo_horas').value = redondear(total,2);
}	    
 </script>       
		<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dEntradaGeneral['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?>" id="fecha" class="datosgenerales" /><br />
                <label for="fecha">Turno</label>
                     <select  name="id_turno" id="id_turno" class="datosgenerales">
                	<option value="1" <? if($dEntradaGeneral['turno'] == 1) echo "selected";?> >Matutino</option>
                	<option value="2" <? if($dEntradaGeneral['turno'] == 2) echo "selected";?>>Vespertino</option>
                	<option value="3" <? if($dEntradaGeneral['turno'] == 3) echo "selected";?>>Nocturno</option>
                </select> 
                <input type="hidden" name="menio" value="<? if(isset($_REQUEST['menio'])) echo $_REQUEST['menio']; else '0'; ?>">
            <input type="hidden" name="id_entrada_general" id="id_entrada_general" value="<?=$dEntradaGeneral['id_entrada_general']?>">
             <br />
                <input type="hidden" name="id_supervisor" value="<?=$dEntradaGeneral['id_supervisor']?>" />
			</p><br>
          <div align="center">
      <b>Extruder de alta - Nave 2</b>
      <br>
      </div>
 <p align="center">          
		<?  
		
			$dOrdenProduccion	=	mysql_fetch_assoc($rOrdenProduccion);
			
			$sql_lic	=	"SELECT * FROM resumen_maquina_ex INNER JOIN maquina ON resumen_maquina_ex.id_maquina = maquina.id_maquina ".
								" LEFT JOIN operadores ON resumen_maquina_ex.id_operador = operadores.id_operador WHERE (id_orden_produccion=".$dOrdenProduccion['id_orden_produccion'].") ORDER BY maquina.numero ASC";
			$res_lic	=	mysql_query($sql_lic) OR die("<p>$qResumenMaquinas</p><p>".mysql_error()."</p>");
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
<table width="100%" >
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
		$qOperador = "SELECT * FROM operadores WHERE status = 0 AND area = 1 ORDER BY nombre";
		$rOperador = mysql_query($qOperador);
		$nOperador	=	mysql_num_rows($rOperador);
		while ($dOperador = mysql_fetch_assoc($rOperador))
		{ ?>
		<option value="<?=$dOperador['id_operador'] ?>" <? if($id_operador[$reg] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>
        <? } ?>
        </select>
         </h3>
<table width="190" height="59" cellpadding="0" cellspacing="0" >
<thead>
					<tr>
						<td width="67" height="18"><strong>O. T.</strong></td>
					  <td><strong>Kilogramos</strong></td>
			    </tr>
				</thead>
				<tbody>
				<?php 

		 	$qDetalleResumen	=	"SELECT orden_trabajo, kilogramos, id_detalle_resumen_maquina_ex FROM detalle_resumen_maquina_ex WHERE (id_resumen_maquina_ex = ".$id_resumen_maquina_ex[$reg].")";
			$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
			$nDetalleResumen	=	mysql_num_rows($rDetalleResumen);
			
			if($nDetalleResumen > 0){
			for($a = 1;$dDetalleResumen	= 	mysql_fetch_assoc($rDetalleResumen); $a++){	
					?>
					<tr>
						<td height="19"><input type="text"  class="numeros text" id="ot_extruder<?=$id_maquina[$reg]?>_<?=$a?>" name="ot_extruder<?=$numero[$reg]?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$numero[$reg]?>);" value="<?=$dDetalleResumen['orden_trabajo']?>" /></td>
					  <td><input type="text" class="numeros text2" id="<?=$a?>_extr<?=$id_maquina[$reg]?>" name="kg_extruder<?=$numero[$reg]?>[]" onKeyDown="javascript: addTextExtruder(<?=$numero[$reg]?>,<?=$id_maquina[$reg]?>,<?=$a?>);"  onFocus="javascript: this.select()"  onchange="javascript: sumar(<?=$id_maquina[$reg]?>);  sumarExtr();  kh();" value="<?=$dDetalleResumen['kilogramos']?>" />
                      <input type="hidden" name="id_detalle_resumen_maquina_ex<?=$numero[$reg]?>[]" id="<?=$a?>id_detalle_resumen_maquina_ex<?=$id_maquina[$reg]?>" value="<?=$dDetalleResumen['id_detalle_resumen_maquina_ex']?>"></td>
                    </tr>

        <?php  }  
					} else { ?>
				<?php for($b=1;$b<=1;$b++) { ?>
				<tr>
						<td height="19"><input type="text"  class="numeros text" id="ot_extruder<?=$id_maquina[$reg]?>_<?=$b?>" name="ot_extruder<?=$numero[$reg]?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$numero[$reg]?>);" value="<?=$dDetalleResumen['orden_trabajo']?>" /></td>
					  <td><input type="text" class="numeros text2" id="<?=$b?>_extr<?=$id_maquina[$reg]?>" name="kg_extruder<?=$numero[$reg]?>[]" onKeyDown="javascript: addTextExtruder(<?=$numero[$reg]?>,<?=$id_maquina[$reg]?>,<?=$b?>);"  onFocus="javascript: this.select()"  onchange="javascript: sumar(<?=$id_maquina[$reg]?>);  sumarExtr();  kh();" value="<?=$dDetalleResumen['kilogramos']?>" />
                      <input type="hidden" name="id_detalle_resumen_maquina_ex<?=$numero[$reg]?>[]" id="<?=$a?>id_detalle_resumen_maquina_ex<?=$id_maquina[$reg]?>" value="0"> </td>
                  </tr>
                 <? }
				  } ?>	
                  <tr><td colspan="2"><table width="100%">
                  <div id="texto<?=$id_maquina[$reg]?>"></div></table>			
                  </td>
                  </tr>                  
					<tr>
						<td height="20" style="font-size:9px"><strong>Subtotales</strong></td>
					  <td><input style="width:100px" type="text" name="subtotal_extruder<?=$numero[$reg]?>" id="subtotal_<?=$id_maquina[$reg]?>_extr_Total" onChange="sumarExtr();"  readonly="readonly" value="<?=$subtotales[$reg]?>" /></td>
					</tr>
				</tbody>
		  </table>
            
  <? $reg++;}?></td><? }?>
</tr>
</table>
  <? }?>
  </p>

      
      

          <br><br>
          <div id="datosgenerales" style="background-color:#FFFFFF;">
        <table align="center">
        <tbody>
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
						<td><input type="text" id="desperdicio_tira" name="desperdicio_tira" value="<?=$dOrdenProduccion['desperdicio_tira'];?>" class="total" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>
						<td><input type="text" id="desperdicio_duro" name="desperdicio_duro" value="<?=$dOrdenProduccion['desperdicio_duro'];?>" class="total" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>
			            <td><input type="text" size="20" name="kilo_horas" id="kilo_horas" class="total" value="<?=$dOrdenProduccion['k_h']?>" 					<? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?>/></td>
                    </tr>
			</tbody>
                    <tr>      
                      <td  align="right" colspan="7"><br>
                      <input type="button" name="Rgeresar" id="guardar" value="Regresar" onClick=" javascript: history.go(-1)">
                      <input type="submit" name="extruder" id="guardar" value="Guardar">
                      </td>
					</tr>
        </table>
        </div>          <br><br>
</div>

<? } if($impresion) { ?>

<script language="javascript" type="text/javascript">
function redondear(cantidad, decimales) {
	var cantidad = parseFloat(cantidad);
	var decimales = parseFloat(decimales);
	decimales = (!decimales ? 2 : decimales);
	return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
}

function kh()
{
     var total;
     total		=	0;
	 total 		= (parseFloat(document.getElementById('total_impr_hd').value) + parseFloat(document.getElementById('total_impr_bd').value )) / <? if($dEntradaGeneral['turno'] == '1') echo '8'; if($dEntradaGeneral['turno'] == '2') echo '7'; if($dEntradaGeneral['turno'] == '3') echo '9';  ?>;
     document.getElementById('kilo_horas').value = redondear(total,2);
}	
</script>
		<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dEntradaGeneral['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?>" id="fecha" class="datosgenerales" /><br />
                <label for="fecha">Turno</label>
                <select  name="id_turno" id="id_turno" class="datosgenerales">
                	<option value="1" <? if($dEntradaGeneral['turno'] == 1) echo "selected";?> >Matutino</option>
                	<option value="2" <? if($dEntradaGeneral['turno'] == 2) echo "selected";?>>Vespertino</option>
                	<option value="3" <? if($dEntradaGeneral['turno'] == 3) echo "selected";?>>Nocturno</option>
                </select> 
                
                          
            <input type="hidden" name="id_entrada_general" id="id_entrada_general" value="<?=$dEntradaGeneral['id_entrada_general']?>">
        <!--    <input type="hidden" name="turno" id="turno" value="<?=$dEntradaGeneral['turno']?>"> --->
             <br />
                <input type="hidden" name="id_supervisor" value="<?=$dEntradaGeneral['id_supervisor']?>" />
                <input type="hidden" name="menio" value="<? if(isset($_REQUEST['menio'])) echo $_REQUEST['menio']; else '0'; ?>">
			</p><br>
        <div align="center">
      <b>Impresi&oacute;n - Nave 2</b>
      <br>
      </div>
      
 <p align="center">          
		<?  
		 	$sql_lic= 	"SELECT maquina.numero, operadores.nombre, subtotal, maquina.id_maquina, id_resumen_maquina_im, resumen_maquina_im.id_operador FROM resumen_maquina_im  ".
						"LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".
						"LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador ". 
						"WHERE (id_impresion=".$dImpresion['id_impresion'].") AND linea_impresion = '0' ORDER BY maquina.numero";

			$res_lic=mysql_query($sql_lic);
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
       <!---  <input type="hidden"  name="id_operador2_<?=$numero[$reg]?>" value="<?=$id_operador[$reg] ?>" /> --->
        <input type="hidden"  name="id_resumen_maquina_im<?=$numero[$reg]?>" value="<?=$id_resumen_maquina_impr[$reg] ?>" />
        
     <h3 align="left" style="color: rgb(255, 255, 255);">FLEXO <?=$numero[$reg]?><br>
        <select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:175px"   name="id_operador2_<?=$numero[$reg]?>" >
        <? 
		$qOperador = "SELECT * FROM operadores WHERE status = 0 AND area = 3 ORDER BY id_operador";
		$rOperador = mysql_query($qOperador);
		while ($dOperador = mysql_fetch_assoc($rOperador))
		{ ?>
		<option value="<?=$dOperador['id_operador'] ?>" <? if($id_operador[$reg] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>
        <? } ?>
        </select>
     
     
       </h3>
<table width="190" height="73" >
<thead>
					<tr>
						<td width="65" height="20"><strong>O. T.</strong></td>
					  <td width="102"><strong>Kilogramos</strong></td>
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
                      <td align="left" colspan="3"><input size="25" class="text2" type="text" name="kg_impresion<?=$numero[$reg]?>[]" id="<?=$a3?>_impr<?=$id_maquina[$reg]?>" value="<?=$dDetalleResumenIM['kilogramos']?>"   onkeydown="javascript: addTextBox(<?=$numero[$reg]?>,<?=$id_maquina[$reg]?>,<?=$a3?>);" onChange="javascript: sumarImpr2(<?=$id_maquina[$reg]?>);  sumarImpr(); kh(); " />
                      <input type="hidden" name="id_detalle_resumen_maquina_im<?=$numero[$reg]?>[]" id="<?=$a3?>id_detalle_resumen_maquina_im<?=$id_maquina[$reg]?>" value="<?=$dDetalleResumenIM['id_detalle_resumen_maquina_im']?>"> 
                      </td>
				  </tr>
				<?php } 
				 
					} else { ?>
				<?php for($b=1;$b<=1;$b++) { ?>
				<tr>
                            <td height="19"><input type="text"  class="numeros text" id="ot_impresion<?=$id_maquina[$reg]?>_<?=$b?>" name="ot_impresion<?=$numero[$reg]?>[]" onFocus="javascript: this.select()" /></td>
					  <td><input type="text" class="numeros text2" id="<?=$b?>_impr<?=$id_maquina[$reg]?>" name="kg_impresion<?=$numero[$reg]?>[]" onKeyDown="javascript: addTextBox(<?=$numero[$reg]?>,<?=$id_maquina[$reg]?>,<?=$b?>);"  onFocus="javascript: this.select()"  onchange="javascript: sumarImpr2(<?=$id_maquina[$reg]?>);  sumarImpr(); kh();" value="<?=$dDetalleResumenIM['kilogramos']?>" />
                      <input type="hidden" name="id_detalle_resumen_maquina_im<?=$numero[$reg]?>[]" id="<?=$b?>id_detalle_resumen_maquina_im<?=$id_maquina[$reg]?>" value=""> 
                      
                      </td>
                  </tr>
                 <? }
				  } ?>	
                  <tr><td colspan="2"><table width="100%">
                  <div id="texto<?=$id_maquina[$reg]?>"></div></table>			
                  </td>
                  </tr>
<tr>
						<td height="22" style="font-size:9px"><strong>Subtotales</strong></td>
					<td colspan="3"><input style="width:100px" type="text" name="subtotal_impr<?=$numero[$reg]?>" id="subtotal_<?=$id_maquina[$reg]?>_impr_Total" value="<?=$subtotales[$reg]?>" onChange="sumarImpr();"  readonly="readonly" /></td>
				</tbody>
		  </table>
            
  <? $reg++;}?></td><? }?>
</tr>
</table>
  <? }?>
  </p>    

        <br />
        <br>
        <br>
<br />
		    		<p align="center"><strong>Alta L&iacute;nea de Impresi&oacute;n - Nave 2</strong></p>	
                    
                   
         <p align="center">          
           
		<?  
		 	$sql_lic2= 	"SELECT * FROM resumen_maquina_im  ".
					 	" RIGHT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".
						" LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador ".
						" WHERE id_impresion = ".$dImpresion['id_impresion']." AND linea_impresion = '1' ".
						" ORDER BY maquina.numero ASC";
				$res_lic2=mysql_query($sql_lic2);
			$cant_lic2=mysql_num_rows($res_lic2);
			$cant2=ceil($cant_lic2/3);
			$a2=0;
			while($dResumenMaquinasLimpr = mysql_fetch_assoc($res_lic2))
			{
			
				$numero2[$a2]					= $dResumenMaquinasLimpr['numero'];
				$id_maquina2[$a2] 				= $dResumenMaquinasLimpr['id_maquina'];
				$id_operador2[$a2]				= $dResumenMaquinasLimpr['id_operador'];
				$id_resumen_maquina_limpr[$a2]	= $dResumenMaquinasLimpr['id_resumen_maquina_im'];
				$subtotales2[$a2]				= $dResumenMaquinasLimpr['subtotal'];

				$a2++;
			}
			$reg2=0;
			for($i2=0;$i2<$cant2;$i2++)
{
?>
<table  width="100%" align="center">
<tr>
    <? for($x2=1;$x2<=4; $x2++){?>
<td width="203" align="left" valign="top"><br />
  <? if($reg2<$cant_lic2){ ?>		

	 
		<?
		
		    $qOperadorlimp2 = 	"SELECT oper_maquina.id_operador, nombre FROM oper_maquina ".
								"INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador ".
								"WHERE id_maquina = ".$id_maquina2[$reg2]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";
			$rOperadorlimp2 = mysql_query($qOperadorlimp2);
			$dAsignacionlimp2 = mysql_fetch_assoc($rOperadorlimp2);?>
            
	 	<input type="hidden"  name="id_operador3_<?=$numero2[$reg2]?>" value="<?=$dAsignacionLimp['id_operador'] ?>" />
        <input type="hidden" name="codigos3[]" value="<?=$numero2[$reg2]?>" />
  		<input type="hidden" name="id_maquina3_<?=$numero2[$reg2]?>" value="<?=$id_maquina2[$reg2]?>" />
      <!---  <input type="hidden"  name="id_operador3_<?=$numero2[$reg2]?>" value="<?=$id_operador2[$reg2] ?>" /> --->
        <input type="hidden"  name="id_resumen_maquina_lim<?=$numero2[$reg2]?>" value="<?=$id_resumen_maquina_limpr[$reg2] ?>" />
		<h3 align="left" style="color: rgb(255, 255, 255);">Linea <?=$numero2[$reg2]?> <br />		
          
          <select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px;  width:175px"   name="id_operador3_<?=$numero[$reg]?>" >
        <? 
		$qOperador = "SELECT * FROM operadores WHERE status = 0 AND area = 3 ORDER BY nombre";
		$rOperador = mysql_query($qOperador);
		while ($dOperador = mysql_fetch_assoc($rOperador))
		{ ?>
		<option value="<?=$dOperador['id_operador'] ?>" <? if($id_operador2[$reg2] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>
        <? } ?>
        </select>
		
		 </h3>
<table width="206" height="71" >
<thead>
					<tr>
						<td width="69"><strong>O. T.</strong></td>
					  <td width="122"><strong>Kilogramos</strong></td>
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
                          <td align="left" colspan="3"><input class="text2" size="25" type="text" name="kg_limpresion<?=$numero2[$reg2]?>[]" id="<?=$a4?>_limpr<?=$id_maquina2[$reg2]?>" value="<?=$dDetalleResumenLIM['kilogramos']?>"   onkeydown="javascript: addTextBox2(<?=$numero2[$reg2]?>,<?=$id_maquina2[$reg2]?>,<?=$a4?>);"  onchange="javascript: sumarLimpr2(<?=$id_maquina2[$reg2]?>);  sumarImpr(); kh();" />
                          <input type="hidden" name="id_detalle_resumen_maquina_lim<?=$numero2[$reg2]?>[]" id="<?=$a4?>id_detalle_resumen_maquina_lim<?=$id_maquina2[$reg2]?>" value="<?=$dDetalleResumenLIM['id_detalle_resumen_maquina_im']?>"> 
                          
                          </td>
				  
                  </tr>
				<?php } 
				 
					} else { ?>
				<?php for($b2=1;$b2<=1;$b2++) { ?>
				<tr>
                            <td height="19"><input type="text"  class="numeros text" id="ot_limpresion<?=$id_maquina2[$reg2]?>_<?=$b2?>" name="ot_limpresion<?=$numero2[$reg2]?>[]" onFocus="javascript: this.select()" /></td>
					  <td><input type="text" class="numeros text2" id="<?=$b2?>_limpr<?=$id_maquina2[$reg2]?>" name="kg_limpresion<?=$numero2[$reg2]?>[]" onKeyDown="javascript: addTextBox2(<?=$numero2[$reg2]?>,<?=$id_maquina2[$reg2]?>,<?=$b2?>);"  onFocus="javascript: this.select()"  onchange="javascript: sumarLimpr2(<?=$id_maquina2[$reg2]?>);  sumarImpr(); kh();" value="<?=$dDetalleResumenLIM['kilogramos']?>" />
                      <input type="hidden" name="id_detalle_resumen_maquina_lim<?=$numero2[$reg2]?>[]" id="<?=$b2?>id_detalle_resumen_maquina_lim<?=$id_maquina2[$reg2]?>" value=""> 
                      
                      </td>
                  </tr>
                 <? }
				  } ?>	
                  <tr><td colspan="2"><table width="100%">
                  <div id="limprtexto<?=$id_maquina2[$reg2]?>"></div></table>			
                  </td>
                  </tr>
					<tr>
						<td style="font-size:9px"><strong>Subtotales</strong></td>
						<td colspan="3"><input style="width:100px"  type="text" name="subtotal_limpr<?=$numero2[$reg2]?>" id="subtotal_<?=$id_maquina2[$reg2]?>_limpr_Total" onChange="sumarImpr();"  readonly="readonly" value="<?=$subtotales2[$reg2]?>" /></td>
					</tr>
				</tbody>
		  </table>
            
  <? $reg2++;}?>
  <br /></td><? }?>
</tr>
</table>
  <? }?>

</p>    
    

                 <br><br>
<div id="datosgenerales" style="background-color:#FFFFFF;">
        <table align="center">
        <tbody>
        			<tr>
						<td></td>
						<td>Total HD.</td>
						<td>Total BD.</td>
						<td>D. HD.</td>
						<td>D. BD.</td>
						<td>K/H</td>
					</tr>
					<tr>
						<td><strong>Totales</strong></td>
						<td><input name="total_impr_hd"  class="numeros" type="text" size="15" id="total_impr_hd"  value="<?=$dImpresion['total_hd']?>" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?>/></td>
						<td><input name="total_impr_bd" type="text" class="numeros" size="15" id="total_impr_bd"  value="<?=$dImpresion['total_bd']?>" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?>/></td>
						<td><input name="total_desperdicio_hd" class="numeros"  type="text" size="15" id="total_impr_tira" value="<?=$dImpresion['desperdicio_hd']?>" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?>/></td>
                        <td><input name="total_desperdicio_bd" class="numeros" type="text" size="15" id="total_impr_duro" value="<?=$dImpresion['desperdicio_bd']?>" <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?>/></td>
                        <td><input type="text" name="kilo_horas" id="kilo_horas" size="20" value="<?=$dImpresion['k_h']?>"  <? if(!isset($_SESSION['id_admin'])){ ?>readonly<? } ?> /></td>

					</tr>
                    <tr>      
                      <td  align="right" colspan="7"><br>
                      <input type="button" name="regresar" id="regresar" value="Regresar" onClick=" javascript: history.go(-1)">
                      <input type="submit" name="impresion" id="impresion" value="Guardar" ></td>
					</tr>
			</tbody>
        </table>
        </div>          <br><br>
        
    </div>
    

<?  ?>

<? } if($listar) { ?>
        
<? if(($_SESSION['area'] == 1  && isset($_GET['extruder'])) || isset($_SESSION['id_admin'])){ ?>
		<h3 ><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES EXTRUDER</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
              <table>
                <tr>
                  <td width="124" style="color:#000000"><b>Fecha</b></td>
                  <td width="279" style="color:#000000"><b>Nombre</b></td>
                  <td width="39" style="color:#000000"><b>Turno</b></td>
                </tr>
                <?php
				$qGenerales		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno,id_entrada_general FROM entrada_general".
									" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE  ";
									if(isset($_SESSION['id_admin'])){
				$qGenerales		.=	" repesada = 1 AND ";					
									}
									if(!isset($_SESSION['id_admin'])){
				$qGenerales		.=	" entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."' AND ";
									}
				$qGenerales		.=	" /*autorizada = '0' AND*/ impresion = '0'  ORDER BY fecha ASC";
				$rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");
				$sGenerales		=	array();
				while($dGenerales	=	mysql_fetch_assoc($rGenerales)){ ?>
                <tr onMouseOver="this.style.backgroundColor='#CCC'"
                    onMouseOut="this.style.backgroundColor='#EAEAEA'" 
                    style="cursor:default; background-color:#EAEAEA">
                  <td><?=$dGenerales['fecha']?></td>
                  <td><?=$dGenerales['nombre']?></td>
                  <td><?=$dGenerales['turno']?></td>
                  <td width="81"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=autorizar&extruder&id_reporte=<?=$dGenerales['id_entrada_general']?>"><? if(isset($_SESSION['id_admin'])) { ?>Autorizar <? } if(!isset($_SESSION['id_admin'])){ ?>Modificar<? } ?></a></td>
                  <td width="245"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_extruder&id_reporte=<?=$dGenerales['id_entrada_general']?>" onClick="javascript: return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>
                </tr>
                <?php } ?>
                <br />
              </table>
            </div><br><br>

<? }		  if(( $_SESSION['area2'] == 1  && isset($_GET['impresion'])) || isset($_SESSION['id_admin'])){ ?>
		<h3 ><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES IMPRESION</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
        <table>

                    <tr>
                        <td width="124" style="color:#000000"><b>Fecha</b></td>
                      <td width="279" style="color:#000000"><b>Nombre</b></td>
                      <td width="39" style="color:#000000"><b>Turno</b></td>
               </tr>
                  
                <?php
				$qGenerales2		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno, entrada_general.id_entrada_general FROM entrada_general ".
									"LEFT JOIN impresion  ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
									"LEFT JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE ";
									if(isset($_SESSION['id_admin'])){
				$qGenerales2		.=	"repesada = 1 AND ";
									}
									if(!isset($_SESSION['id_admin'])){
				$qGenerales2		.=	" entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."' AND ";
									}
				$qGenerales2		.=	" /*autorizada = '0' AND*/ impresion = '1' ORDER BY fecha ASC";
									
				$rGenerales2		=	mysql_query($qGenerales2) OR die("<p>$qGenerales2</p><p>".mysql_error()."</p>");
				$sGenerales2		=	array();
				while($dGenerales2	=	mysql_fetch_assoc($rGenerales2)){ ?>
                 <tr onMouseOver="this.style.backgroundColor='#CCC'"
                    onmouseout="this.style.backgroundColor='#EAEAEA'" 
                    style="cursor:default; background-color:#EAEAEA">                              
               		<td><?=$dGenerales2['fecha']?></td>
                    <td><?=$dGenerales2['nombre']?></td>
                    <td><?=$dGenerales2['turno']?></td>
                    <td width="81"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=impresion&impresion&id_reporte=<?=$dGenerales2['id_entrada_general']?>"><? if(isset($_SESSION['id_admin'])) { ?>Autorizar <? } if(!isset($_SESSION['id_admin'])){ ?>Modificar<? } ?></a></td>
                  <td width="245"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_impresion&id_reporte=<?=$dGenerales2['id_entrada_general']?>" onClick="javascript: return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>
          		</tr>				
				<?php } ?>
                <br />
              </table>
		</div><br><br>
       
<? } if(($_SESSION['area3'] == 1 && isset($_GET['bolseo'])) || isset($_SESSION['id_admin'])){ ?>

		<h3><a style="color: rgb(255, 255, 255);" href="#bolseo">REPORTES BOLSEO</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
          <table>
                <tr>
                	<td width="128" style="color:#000000"><b>Fecha</b></td>
               	  <td width="270" style="color:#000000"><b>Nombre</b></td>
               	  <td width="44" style="color:#000000"><b>Turno</b></td>
			  </tr>
 		        <?php
				$qBolseo		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno,id_bolseo FROM bolseo".
									" LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor  WHERE ";
									if(isset($_SESSION['id_admin'])){
				//$qBolseo		.=	" repesada = 0 AND ";
									}
									if(!isset($_SESSION['id_admin'])){
				$qBolseo		.=	" bolseo.id_supervisor = '".$_SESSION['id_supervisor']."' /*AND ";
									}
				$qBolseo		.=	" autorizada = '0'*/ ORDER BY fecha ASC";
								
				$rBolseo		=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");
				$sBolseo		=	array();
				while($dBolseo	=	mysql_fetch_assoc($rBolseo)){ ?>
                <tr onMouseOver="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#EAEAEA'" 
	style="cursor:default; background-color:#EAEAEA">
                	<td><?=$dBolseo['fecha']?></td>
                    <td><?=$dBolseo['nombre']?></td>
                    <td><?=$dBolseo['turno']?></td>
                    <td width="81"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=autorizar_bolseo&bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>"><? if(isset($_SESSION['id_admin'])) { ?>Autorizar <? } if(!isset($_SESSION['id_admin'])){ ?>Modificar<? } ?></a></td>
                  <td width="245"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=eliminar_bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>" onClick="javascript: return confirm('Está seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>
          </tr>
                <?php } ?>               
                <br />
			  </table>
            </div><br><br>
 <? } ?>
    
<? } ?>
<?php if($nuevaEntrada) { ?>
<SCRIPT LANGUAGE="JavaScript">
redirTime = "4000";
redirURL = "<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listar";
function redirTimer() { self.setTimeout("self.location.href = redirURL;",redirTime); }
</script>

<body onLoad="redirTimer()">
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
<script type="text/javascript">
<!--

	var stretchers = $$('div.accordion');
	var togglers = $$('h3.toggler');

	stretchers.setStyles({'height': '0', 'overflow': 'hidden'});

	window.addEvent('load', function(){
		togglers.each(function(toggler, i){
			toggler.color = toggler.getStyle('background-color');
			toggler.$tmp.first = toggler.getFirst();
			toggler.$tmp.fx = new Fx.Style(toggler, 'background-color', {'wait': false, 'transition': Fx.Transitions.Quart.easeOut});
		});

		var myAccordion = new Accordion(togglers, stretchers, {
			'opacity': false,
			'start': false,
			'transition': Fx.Transitions.Quad.easeOut,
			onActive: function(toggler){
				toggler.$tmp.fx.start('#e0542f');
				toggler.$tmp.first.setStyle('color', '#fff');
			},
			onBackground: function(toggler){
				toggler.$tmp.fx.stop();
				toggler.setStyle('background-color', toggler.color).$tmp.first.setStyle('color', '#222');
			}
		});

		var found = 0;
		$$('h3.toggler a').each(function(link, i){
			if (window.location.hash.test(link.hash)) found = i;
		});
		myAccordion.display(found);

	});

//-->	
</script>
<script type="text/javascript">
<!--
var numRegex = /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/;
	var stretchers = $$('div.accordion');
	var togglers = $$('h3.toggler');

	stretchers.setStyles({'height': '0', 'overflow': 'hidden'});

	window.addEvent('load', function(){
		togglers.each(function(toggler, i){
			toggler.color = toggler.getStyle('background-color');
			toggler.$tmp.first = toggler.getFirst();
			toggler.$tmp.fx = new Fx.Style(toggler, 'background-color', {'wait': false, 'transition': Fx.Transitions.Quart.easeOut});
		});

		var myAccordion = new Accordion(togglers, stretchers, {
			'opacity': false,
			'start': false,
			'transition': Fx.Transitions.Quad.easeOut,
			onActive: function(toggler){
				toggler.$tmp.fx.start('#e0542f');
				toggler.$tmp.first.setStyle('color', '#fff');
			},
			onBackground: function(toggler){
				toggler.$tmp.fx.stop();
				toggler.setStyle('background-color', toggler.color).$tmp.first.setStyle('color', '#222');
			}
		});

		var found = 0;
		$$('h3.toggler a').each(function(link, i){
			if (window.location.hash.test(link.hash)) found = i;
		});
		myAccordion.display(found);

	});
	
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
				obj[i].value = '';
				continue;
			}
			if( obj[i].id.indexOf('kg_'+id) != -1 )
				skg += tmp;
			else if( obj[i].id.indexOf('ml_'+id) != -1 )
				sml += tmp;
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
			else if( obj[i].id.indexOf('dts_') != -1 )
				tdt += tmp;
			else if( obj[i].id.indexOf('dtrs_') != -1 )
				tdtr +=tmp;
			else if( obj[i].id.indexOf('ses_') != -1 )
				tse += tmp;
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
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].id.indexOf('_impr_Total')>0   )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }
				if (IsNumeric(i[a].value) && i[a].id.indexOf('_limpr_Total')>0   )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }

        }
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
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].id.indexOf('_impr'+x)>0 )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }
        }
        document.getElementById("subtotal_"+x+"_impr_Total").value = total;
}

function sumarLimpr2(x)
{
        var a,total, i;
        i = document.getElementsByTagName('INPUT');
        total=0;
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].id.indexOf('_limpr'+x)>0 )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }
        }
				
        document.getElementById("subtotal_"+x+"_limpr_Total").value = total;
}

	

//-->	
</script>

	</div>
	
</div>
</form>