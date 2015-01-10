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

$debug = false;

if($debug)
	echo "<pre>";

	$_POST['fecha']	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);
 
	if(isset($_POST['extruder'])){
	

	$qOrdenExtruder	=	"UPDATE entrada_general  SET turno = '{$_POST[turno]}' , fecha = '{$_POST[fecha]}' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	pDebug($qOrdenExtruder);
	$rOrdenExtruder	=	mysql_query($qOrdenExtruder) OR die("<p>$qOrdenExtruder</p><p>".mysql_error()."</p>");
	
	pDebug($qGeneral);

	$ID_GENERAL	=	mysql_insert_id();
	
	
	$qOrdenProduccion	=	"UPDATE orden_produccion  SET total = '{$_POST[total_extruder]}' , desperdicio_tira = '{$_POST[desperdicio_tira]}' , desperdicio_duro = '{$_POST[desperdicio_duro]}' , observaciones = '{$_POST[observaciones_extruder]}' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
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

		$qResumenMaquinaEx	=	"UPDATE resumen_maquina_ex SET subtotal = '$subtotal', observacion = '$observacion' WHERE id_resumen_maquina_ex = ".$id_resumen_maquina_ex." ";
		pDebug($qResumenMaquinaEx);
		$rResumenMaquinaEx	=	mysql_query($qResumenMaquinaEx) OR die("<p>$qResumenMaquinaEx</p><p>".__LINE__."</p><p>".mysql_error()."</p>");
		$ID_RES_MAQUINAEX	=	mysql_insert_id();
		
		$nEntradas			=	sizeof($_POST["ot_extruder".$sufijo]);
		$qDetalleResumen	=	"UPDATE detalle_resumen_maquina_ex SET ";
		$flag = false;
		for($j=0;$j<$nEntradas;$j++)
		{

			$ot_extruder		=	$_POST["ot_extruder".$sufijo][$j];
			$kg_extruder		=	$_POST["kg_extruder".$sufijo][$j];
			if(empty($ot_extruder) || empty($kg_extruder))
				continue;
			/* Esto sit·cticamente puede ir en una lÌnea, pero extraÒamente PHP tiene pedos con las pilas con asignaciones contiguas */
			$qDetalleResumen	.=	($j>0)?",":"";
			$qDetalleResumen	.= " orden_trabajo = '$ot_extruder', kilogramos = '$kg_extruder' ";
			$flag = true; 
		}
		if($flag == true){
		$qDetalleResumen	.= "WHERE id_resumen_maquina_ex = ".$id_resumen_maquina_ex."";
		pDebug($qDetalleResumen);
		
		$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
		}
	}
	
	pDebug("Terminado");
	pDebug("Comienza volcado de la peticiÛn");

	echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion=31&accion=nuevaentrada\';</script>';

	}
	/* -- Termina parte de Extruder -- */

	
	/* Comienza ImpresiÛn */
	if(isset($_POST['impresion'])){

	$qOrdenExtruder	=	"UPDATE entrada_general  SET turno = '{$_POST[turno]}' , fecha = '{$_POST[fecha]}' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	pDebug($qOrdenExtruder);
	$rOrdenExtruder	=	mysql_query($qOrdenExtruder) OR die("<p>$qOrdenExtruder</p><p>".mysql_error()."</p>");
		
	$nMaquinas	=	sizeof($_POST['codigos2']);
	
	//pDebug($qGeneral);
	
	$qImpresion		=	"UPDATE impresion  SET total_hd = '{$_POST[total_impr_hd]}', total_bd = '{$_POST[total_impr_bd]}', desperdicio_hd = '{$_POST[total_desperdicio_hd]}', desperdicio_bd = '{$_POST[total_desperdicio_bd]}', observaciones = '{$_POST[observaciones_impr]}' WHERE id_entrada_general = ".$_REQUEST['id_entrada_general']."";
	pDebug($qImpresion);
	
 	$rImpresion		=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
	$ID_IMPRESION	=	mysql_insert_id();
	
	for($i=0;$i<$nMaquinas;$i++)
	{
		$sufijo				=	$_POST['codigos2'][$i];
		$subtotal			=	floatval($_POST['subtotal_impr'.$sufijo]);
		$id_maquina			=	intval($_POST['id_maquina2_'.$sufijo]);
	    $id_resumen_maquina_im = intval($_POST['id_resumen_maquina_im'.$sufijo]);
		$id_operador		=	intval($_POST['id_operador2_'.$sufijo]);
		$falta_personal			=	$_POST['fph_impr'.$sufijo].':'.$_POST['fpm_impr'.$sufijo].':'.$_POST['fps_impr'.$sufijo];
		$fallo_electrico		=	$_POST['feh_impr'.$sufijo].':'.$_POST['fem_impr'.$sufijo].':'.$_POST['fes_impr'.$sufijo];
		$mantenimiento			=	$_POST['mh_impr'.$sufijo].':'.$_POST['mm_impr'.$sufijo].':'.$_POST['ms_impr'.$sufijo];
		$cambio_impresion		=	$_POST['cih_impr'.$sufijo].':'.$_POST['cim_impr'.$sufijo].':'.$_POST['cis_impr'.$sufijo];
		
		$observacion		=	$_POST['ob_impr'.$sufijo];


		/* La segunda entrada es para impresiÛn y mandamos linea_impresion = 0  */
		$qImpresion			=	"UPDATE resumen_maquina_im  SET   subtotal = '$subtotal', observacion =  '$observacion' WHERE id_resumen_maquina_im = ".$id_resumen_maquina_im." ";
		pDebug($qImpresion);
		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
		$ID_RES_MAQUINAIM	=	mysql_insert_id();
		
		$nEntradas			=	sizeof($_POST["ot_impresion".$sufijo]);
		$qDetalleResumen	=	"UPDATE  detalle_resumen_maquina_im SET ";
		
		$flag = false;
		for($j=0;$j<$nEntradas;$j++)
		{
			$ot_impresion		=	$_POST["ot_impresion".$sufijo][$j];
			$kg_impresion		=	$_POST["kg_impresion".$sufijo][$j];
			if(empty($ot_impresion) || empty($kg_impresion))
				continue;
			/* Esto sit·cticamente puede ir en una lÌnea, pero extraÒamente PHP tiene pedos con las pilas con asignaciones contiguas */
			$qDetalleResumen	.=	($j>0)?",":"";
			$qDetalleResumen	.= "  orden_trabajo = '$ot_impresion',kilogramos = '$kg_impresion' ";
			$flag = true;
		}
		if($flag == true){
		$qDetalleResumen		.= "WHERE id_resumen_maquina_im = ".$id_resumen_maquina_im."";
		pDebug($qDetalleResumen);
		$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
		}

	}

	/* -- Termina ImpresiÛn -- */
	/* Comienza parte lÌneas de impresiÛn */
	$nMaquinas	=	sizeof($_POST['codigos3']);
	
	for($i=0;$i<$nMaquinas;$i++)
	{
		$sufijo				=	$_POST['codigos3'][$i];
		$subtotal			=	floatval($_POST['subtotal_limpr'.$sufijo]);
		$id_maquina			=	intval($_POST['id_maquina3_'.$sufijo]);
	    $id_resumen_maquina_im = intval($_POST['id_resumen_maquina_im'.$sufijo]);
		$id_operador		=	intval($_POST['id_operador3_'.$sufijo]);
		$falta_personal			=	$_POST['fph_limpr'.$sufijo].':'.$_POST['fpm_limpr'.$sufijo].':'.$_POST['fps_limpr'.$sufijo];
		$fallo_electrico		=	$_POST['feh_limpr'.$sufijo].':'.$_POST['fem_limpr'.$sufijo].':'.$_POST['fes_limpr'.$sufijo];
		$mantenimiento			=	$_POST['mh_limpr'.$sufijo].':'.$_POST['mm_limpr'.$sufijo].':'.$_POST['ms_limpr'.$sufijo];
		$cambio_impresion		=	$_POST['cih_limpr'.$sufijo].':'.$_POST['cim_limpr'.$sufijo].':'.$_POST['cis_limpr'.$sufijo];
		$observacion		=	$_POST['ob_limpr'.$sufijo];
		
		/* La segunda entrada es para lÌneas de impresiÛn y mandamos linea_impresion = 1  */
		$qImpresion			=	"UPDATE resumen_maquina_im SET  subtotal = '$subtotal', observacion = '$observacion' WHERE id_impresion = ".$id_resumen_maquina_im."  ".
		pDebug($qImpresion);
		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
		$ID_RES_MAQUINAIM	=	mysql_insert_id();
		
		$nEntradas			=	sizeof($_POST["ot_limpresion".$sufijo]);
		$qDetalleResumen	=	"UPDATE detalle_resumen_maquina_im  SET ";
		
		$flag = false;
		for($j=0;$j<$nEntradas;$j++)
		{
			$ot_impresion		=	$_POST["ot_limpresion".$sufijo][$j];
			$kg_impresion		=	$_POST["kg_limpresion".$sufijo][$j];
			/* Todos amamos a MeÒo ahora por mandar campos en blanco por... si seÒor por tercera vez Woohoo! */
			if(empty($ot_impresion) || empty($kg_impresion))
				continue;
			/* Esto sit·cticamente puede ir en una lÌnea, pero extraÒamente PHP tiene pedos con las pilas con asignaciones contiguas */
			$qDetalleResumen	.=	($j>0)?",":"";
			$qDetalleResumen	.= " orden_trabajo = '$ot_impresion', kilogramos = '$kg_impresion' ";
			$flag = true;
		}
		if($flag == true){
		pDebug($qDetalleResumen);
		$qDetalleResumen		.= "WHERE id_resumen_maquina_im = ".$id_resumen_maquina_im."";
		$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
		}

	}
	
	/* -- Termina parte de ImpresiÛn */

	pDebug("Terminado");
	pDebug("Comienza volcado de la peticiÛn");
	
	
	echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion=31&accion=nuevaentrada\';</script>';
	
}
	

if(isset($_POST['bolseo'])){
	
	$debug = false;
	if($debug)
	{	
		echo "<pre>";
		print_r($_REQUEST);
	}

	$nMaquinas	=	sizeof($_POST['id_maquinas']);
	
	$_POST['fecha']	=	preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);
	
	$qBolseo	=	"UPDATE bolseo SET fecha = '$_POST[fecha]', turno = '{$_POST[turno]}' , kilogramos = '{$_POST[tkg]}', millares = '{$_POST[tml]}',dtira = '{$_POST[tdt]}',dtroquel = '{$_POST[tdtr]}',segundas = '{$_POST[tse]}' WHERE id_bolseo = '".$_POST['id_bolseo']."'";
	pDebug($qBolseo);
	$rBolseo	=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");
	$ID_BOLSEO	=	mysql_insert_id();
	
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
				
		$qResumen		=	"UPDATE resumen_maquina_bs SET kilogramos = '$sKilogramos',millares = '$sMillares' ,dtira = '$sTira' ,dtroquel ='$sTroquel',segundas = '$sSegundas' WHERE id_resumen_maquina_bs = '".$id_resumen_maquina_bs."'";
		pDebug($qResumen);
		$rResumen		=	mysql_query($qResumen) OR die("<p>$qresumen</p><p>".mysql_error()."</p>");
		
		
		$ID_RESUMEN		=	mysql_insert_id();
		
		$nEntradas			=	sizeof($_POST["kg_$sufijo"]);
		$qDetalles		=	"UPDATE detalle_resumen_maquina_bs SET ";
		
		$flag = false;
		for($j=0;$j<$nEntradas;$j++)
		{
			if(
				empty($_POST["kg_$sufijo"][$j]) && 
				empty($_POST["ml_$sufijo"][$j]) &&
				empty($_POST["dt_$sufijo"][$j]) &&
				empty($_POST["dtdr_$sufijo"][$j]) &&
				empty($_POST["se_$sufijo"][$j]) &&
				empty($_POST["ot_$sufijo"][$j])
			) continue;
			$fDetalles	=	true;
			$kg			=	floatval($_POST["kg_$sufijo"][$j]);
			$ml			=	floatval($_POST["ml_$sufijo"][$j]);
			$dt			=	floatval($_POST["dt_$sufijo"][$j]);
			$dtr		=	floatval($_POST["dtr_$sufijo"][$j]);
			$se			=	floatval($_POST["se_$sufijo"][$j]);	
			$orden		=	floatval($_POST["ot_$sufijo"][$j]);		
			
			if(empty($orden) || empty($kg))
				continue;

			$qDetalles	.=	($j>0)?",":"";
			$qDetalles	.= " kilogramos = '$kg', millares = '$ml',dtira = '$dt' ,dtroquel = $dtr ,segundas = '$se', orden = '$orden' ";
			$flag = true;
		}	
		
		if($flag == true){
		pDebug($qDetalles);
		$qDetalles	.=	"WHERE id_resumen_maquina_bs = '".$id_resumen_maquina_bs."'";
		$rDetalles	=	mysql_query($qDetalles) OR die("<p>$qDetalles</p><p>".mysql_error()."</p>");
		}
		
	}	
	 echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion=31&accion=nuevaentrada\';</script>';
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
<? if($autorizar_bolseo){ ?>
<body>
<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
		<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dBolseo['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=$dBolseo['fecha']?>" id="fecha" class="datosgenerales" /><br />
                <label for="fecha">Turno</label>
                <select  name="turno" id="turno" class="datosgenerales">
                	<option value="1" <? if($dBolseo['turno'] == 1) echo "selected";?>>Matutino</option>
                	<option value="2" <? if($dBolseo['turno'] == 2) echo "selected";?>>Vespertino</option>
                	<option value="3" <? if($dBolseo['turno'] == 3) echo "selected";?>>Nocturno</option>
			</select>
          <br />
                <input type="hidden" name="id_supervisor" value="<?=$dBolseo['id_supervisor']?>" />
                <input type="hidden" name="area" id="area" value="<?=$dBolseo['area']?>" />
                <input type="hidden" name="id_bolseo" id="id_bolseo" value="<?=$dBolseo['id_bolseo']?>" />
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
		<br /><br />
		<? 	
		$qResumenMaquinas	=	"SELECT * FROM resumen_maquina_bs INNER JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina ".
								" LEFT JOIN operadores ON resumen_maquina_bs.id_operador = operadores.id_operador WHERE (id_bolseo=".$dBolseo['id_bolseo'].") ORDER BY maquina.numero";
				$rResumenMaquinas	=	mysql_query($qResumenMaquinas) OR die("<p>$qResumenMaquinas</p><p>".mysql_error()."</p>");
				while($dResumenMaquinas	=	mysql_fetch_assoc($rResumenMaquinas)){
		 ?>
		<input type="hidden" name="id_maquinas[]" value="<?=$dResumenMaquinas['id_maquina']?>" />
        <input type="hidden"  name="id_operadores[]" value="<?=$dResumenMaquinas['id_operador'] ?>" />
        <input type="hidden"  name="id_resumen_maquina_bs[]" value="<?=$dResumenMaquinas['id_resumen_maquina_bs'] ?>" />
        
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#maquina<?=$dResumenMaquinas['numero']?>">BOlseadora <?=$dResumenMaquinas['numero']?>
	    -  
	    <?=$dResumenMaquinas['nombre'] ?> </a></h3>

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
					for($a = 1;$dDetalleResumen	= 	mysql_fetch_assoc($rDetalleResumen); $a++){	
					?>
                    <tr>
						<td><input type="text" class="numeros" id="ot_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="ot_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['orden']?>" /></td>
						<td><input type="text" class="numeros" id="ml_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="ml_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['millares']?>" /></td>
                        <td><input type="text" class="numeros" id="kg_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="kg_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['kilogramos']?>"/></td>
						<td><input type="text" class="numeros" id="dt_<?=$dResumenMaquinas['id_maquina'	]?>_<?=$a?>" name="dt_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['dtira']?>" /></td>
						<td><input type="text" class="numeros" id="dtr_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="dtr_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['dtroquel']?>" /></td>
						<td><input type="text" class="numeros" id="se_<?=$dResumenMaquinas['id_maquina']?>_<?=$a?>" name="se_<?=$dResumenMaquinas['id_maquina']?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$dResumenMaquinas['id_maquina']?>);" value="<?=$dDetalleResumen['segundas']?>" /></td>
					</tr>
				<?php } ?>
					<tr>
						<td><strong>Subtotales</strong></td>
						<td><input type="text" id="kgs_<?=$dResumenMaquinas['id_maquina']?>" name="kgs_<?=$dResumenMaquinas['id_maquina']?>" 	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['kilogramos']?>" readonly/></td>
						<td><input type="text" id="mls_<?=$dResumenMaquinas['id_maquina']?>" name="mls_<?=$dResumenMaquinas['id_maquina']?>"  	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['millares']?>" readonly/></td>
						<td><input type="text" id="dts_<?=$dResumenMaquinas['id_maquina']?>" name="dts_<?=$dResumenMaquinas['id_maquina']?>"  	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['dtira']?>" readonly/></td>
						<td><input type="text" id="dtrs_<?=$dResumenMaquinas['id_maquina']?>" name="dtrs_<?=$dResumenMaquinas['id_maquina']?>"	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['dtroquel']?>" readonly/></td>
						<td><input type="text" id="ses_<?=$dResumenMaquinas['id_maquina']?>" name="ses_<?=$dResumenMaquinas['id_maquina']?>" 	class="subtotal" onFocus="javascript: this.select()"  value="<?=$dResumenMaquinas['segundas']?>" readonly/></td>
					</tr>
					<tr>
						<td colspan="6">
                        	<input type="hidden" name="id_maquina_<?=$dResumenMaquinas['id_maquina']?>[]"  value="<?=$dResumenMaquinas['id_maquina']?>" />ç
        					<input type="hidden" name="id_operador_<?=$dResumenMaquinas['id_maquina']?>[]" value="<?=$dResumenMaquinas['id_operador'] ?>" />
                        </td>
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
						<td><input type="text" id="tkg" name="tkg" value="<?=$dBolseo['kilogramos']?>" class="total" readonly="readonly" /></td>
						<td><input type="text" id="tml" name="tml" value="<?=$dBolseo['millares']?>" class="total" readonly="readonly" /></td>
						<td><input type="text" id="tdt" name="tdt" value="<?=$dBolseo['dtira']?>" class="total" readonly="readonly" /></td>
						<td><input type="text" id="tdtr" name="tdtr" value="<?=$dBolseo['dtroquel']?>" class="total" readonly="readonly" /></td>
						<td><input type="text" id="tse" name="tse" value="<?=$dBolseo['segundas']?>" class="total" readonly="readonly" /></td>
					</tr>
				</tbody>
        </table>
        </div>
		<div id="barraSubmit" style="background-color:#FFFFFF; text-align:right;">
			<input type="submit" name="bolseo" id="guardar" value="Guardar" />
		</div>
		<?php }  if($autorizar){ ?>
<body>
<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
		<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dEntradaGeneral['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?>" id="fecha" class="datosgenerales" /><br />
                <label for="fecha">Turno</label>
               <select  name="turno" id="turno" class="datosgenerales">
                	<option value="1" <? if($dEntradaGeneral['turno'] == 1) echo "selected";?>>Matutino</option>
                	<option value="2" <? if($dEntradaGeneral['turno'] == 2) echo "selected";?>>Vespertino</option>
                	<option value="3" <? if($dEntradaGeneral['turno'] == 3) echo "selected";?>>Nocturno</option>
				</select>  
                
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
								" LEFT JOIN operadores ON resumen_maquina_ex.id_operador = operadores.id_operador WHERE (id_orden_produccion=".$dOrdenProduccion['id_orden_produccion'].") ORDER BY maquina.numero";
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
<table width="226">
<tr>
    <? for($x=1;$x<=4; $x++){?>
<td width="203" align="left" valign="top"><br />
  <? if($reg<$cant_lic){ ?>		

	 
		<?
		
		    $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id_maquina[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";
			$rOperadorextr = mysql_query($qOperadorextr);
			$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);?>
            
        <input type="hidden" name="codigos[]" value="<?=$numero[$reg]?>" />
		<input type="hidden" name="id_maquinas<?=$numero[$reg]?>" value="<?=$id_maquina[$reg]?>" />
       <!---- <input type="hidden"  name="id_operador<?=$numero[$reg]?>" value="<?=$id_operador[$reg] ?>" /> --->
        <input type="hidden"  name="id_resumen_maquina_ex<?=$numero[$reg]?>" value="<?=$id_resumen_maquina_ex[$reg] ?>" />
		<h3 align="left" style="color: rgb(255, 255, 255);">EXTRUDER <?=$numero[$reg]?><br>
        <select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:175px"   name="id_operador<?=$numero[$reg]?>" >
        <option value="0">No hay operador</option>
		<? 
		$qOperador = "SELECT * FROM operadores WHERE status = 0 ORDER BY nombre";
		$rOperador = mysql_query($qOperador);
		while ($dOperador = mysql_fetch_assoc($rOperador))
		{ ?>
		<option value="<?=$dOperador['id_operador'] ?>" <? if($id_operador[$reg] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>
        <? } ?>
        </select>
        
         </h3>
<table width="194" height="59"  cellpadding="0" cellspacing="0" >
<thead>
					<tr>
						<td width="67" height="18"><strong>O. T.</strong></td>
					  <td width="224"><strong>Kilogramos</strong></td>
			    </tr>
				</thead>
				<tbody>
				<?php 
					
					
		 	$qDetalleResumen	=	"SELECT orden_trabajo, kilogramos FROM detalle_resumen_maquina_ex WHERE (id_resumen_maquina_ex = ".$id_resumen_maquina_ex[$reg].")";
			$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
			for($a = 1;$dDetalleResumen	= 	mysql_fetch_assoc($rDetalleResumen); $a++){	
					?>
					<tr>
						<td height="19"><input type="text"  class="numeros text" id="ot_extruder<?=$numero[$reg]?>_<?=$a?>" name="ot_extruder<?=$numero[$reg]?>[]" onFocus="javascript: this.select()" onBlur="javascript: sumaLocal(<?=$numero[$reg]?>);" value="<?=$dDetalleResumen['orden_trabajo']?>" /></td>
					  <td><input type="text" class="numeros text2" id="<?=$a?>_extr<?=$numero[$reg]?>" name="kg_extruder<?=$numero[$reg]?>[]" onFocus="javascript: this.select()"  onchange="javascript: sumar(<?=$numero[$reg]?>);  sumarExtr();" value="<?=$dDetalleResumen['kilogramos']?>" /></td>
					</tr>
        <?php  }  ?>
					<tr>
						<td height="20" style="font-size:9px"><strong>Subtotales</strong></td>
					  <td><input style="width:100px" type="text" name="subtotal_extruder<?=$numero[$reg]?>" id="subtotal_<?=$numero[$reg]?>_extr_Total" onChange="sumarExtr();"  readonly="readonly" value="<?=$subtotales[$reg]?>" /></td>
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
					</tr>
					<tr>
						<td><strong>Totales</strong></td>
						<td><input type="text" id="total_extr" name="total_extruder" value="<?=$dOrdenProduccion['total']?>" class="total" /></td>
						<td><input type="text" id="desperdicio_tira" name="desperdicio_tira" value="<?=$dOrdenProduccion['desperdicio_tira']?>" class="total" /></td>
						<td><input type="text" id="desperdicio_duro" name="desperdicio_duro" value="<?=$dOrdenProduccion['desperdicio_duro']?>" class="total"  /></td>
					</tr>
			</tbody>
                    <tr>      
                      <td  align="right" colspan="7"><br>
                
                      <input type="submit" name="extruder" id="guardar" value="Guardar">
                      </td>
					</tr>
        </table>
        </div>          <br><br>
</div></div></div></form>

<? } if($impresion) { ?>
<body>
<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
		<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dEntradaGeneral['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?>" id="fecha" class="datosgenerales" /><br />
                <label for="fecha">Turno</label>
               <select  name="turno" id="turno" class="datosgenerales">
                	<option value="1" <? if($dEntradaGeneral['turno'] == 1) echo "selected";?>>Matutino</option>
                	<option value="2" <? if($dEntradaGeneral['turno'] == 2) echo "selected";?>>Vespertino</option>
                	<option value="3" <? if($dEntradaGeneral['turno'] == 3) echo "selected";?>>Nocturno</option>
				</select>               
            <input type="hidden" name="id_entrada_general" id="id_entrada_general" value="<?=$dEntradaGeneral['id_entrada_general']?>">
             <br />
                <input type="hidden" name="id_supervisor" value="<?=$dEntradaGeneral['id_supervisor']?>" />
			</p><br>
        <div align="center">
      <b>Impresi&oacute;n - Nave 2</b>
      <br>
      </div>
	
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
			
				$numero[$a]					= $dResumenMaquinasImpr['numero'];
				$id_maquina[$a] 			= $dResumenMaquinasImpr['id_maquina'];
				$id_operador[$a]			= $dResumenMaquinasImpr['id_operador'];
				$id_resumen_maquina_impr[$a]	= $dResumenMaquinasImpr['id_resumen_maquina_im'];
				$subtotales[$a]				= $dResumenMaquinasImpr['subtotal'];
			

				$a++;
			}
			$reg=0;
			for($i=0;$i<$cant;$i++)
{
?>
<table width="227">
<tr>
    <? for($x=1;$x<=4; $x++){?>
<td width="219" align="left" valign="top"><br />
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
		$qOperador = "SELECT * FROM operadores ORDER BY id_operador";
		$rOperador = mysql_query($qOperador);
		while ($dOperador = mysql_fetch_assoc($rOperador))
		{ ?>
		<option value="<?=$dOperador['id_operador'] ?>" <? if($id_operador[$reg] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>
        <? } ?>
        </select>
     
     
       </h3>
<table width="223" height="73" >
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
			for($a3 = 1;$dDetalleResumenIM	= 	mysql_fetch_assoc($rDetalleResumenIM); $a3++){					
		?>
					<tr>
                          <td height="21" align="left" ><input type="text" class="text" name="ot_impresion<?=$numero[$reg]?>[]" size="12" value="<?=$dDetalleResumenIM['orden_trabajo']?>" /></td>
                      <td align="left" colspan="3"><input size="25" class="text2" type="text" name="kg_impresion<?=$numero[$reg]?>[]" id="<?=$a3?>_impr<?=$numero[$reg]?>" value="<?=$dDetalleResumenIM['kilogramos']?>"  onchange="javascript: sumarImpr2(<?=$numero[$reg]?>);  sumarImpr();" /></td>
				  </tr>
				<?php } ?>
<tr>
						<td height="22" style="font-size:9px"><strong>Subtotales</strong></td>
					<td colspan="3"><input style="width:100px" type="text" name="subtotal_impr<?=$numero[$reg]?>" id="subtotal_<?=$numero[$reg]?>_impr_Total" value="<?=$subtotales[$reg]?>" onChange="sumarImpr();"  readonly="readonly" /></td>
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
					 	"RIGHT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".
						"LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador ".
						"WHERE id_impresion = ".$dImpresion['id_impresion']." AND linea_impresion = '1' ".
						"ORDER BY maquina.numero";
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
<table width="211">
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
        <input type="hidden"  name="id_resumen_maquina_im<?=$numero2[$reg2]?>" value="<?=$id_resumen_maquina_limpr[$reg2] ?>" />
		<h3 align="left" style="color: rgb(255, 255, 255);">Linea <?=$numero2[$reg2]?><br>
          <select class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px;  width:175px"   name="id_operador3_<?=$numero[$reg]?>" >
        <? 
		$qOperador = "SELECT * FROM operadores WHERE status = 0 ORDER BY nombre";
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
			for($a4 = 1;$dDetalleResumenLIM	= 	mysql_fetch_assoc($rDetalleResumenLIM); $a4++){	
				 ?>
					<tr>
                          <td align="left" ><input class="text" type="text" name="ot_limpresion<?=$numero2[$reg2]?>[]" size="12" value="<?=$dDetalleResumenLIM['orden_trabajo']?>" /></td>
                          <td align="left" colspan="3"><input class="text2" size="25" type="text" name="kg_limpresion<?=$numero2[$reg2]?>[]" id="<?=$a2?>_limpr<?=$numero2[$reg2]?>" value="<?=$dDetalleResumenLIM['kilogramos']?>"  onchange="javascript: sumarLimpr2(<?=$numero2[$reg2]?>);  sumarImpr();" /></td>
				  </tr>
				<?php } ?>
					<tr>
						<td style="font-size:9px"><strong>Subtotales</strong></td>
						<td colspan="3"><input style="width:100px"  type="text" name="subtotal_limpr<?=$numero2[$reg2]?>" id="subtotal_<?=$numero2[$reg2]?>_limpr_Total" onChange="sumarImpr();"  readonly="readonly" value="<?=$subtotales2[$reg2]?>" /></td>
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
					</tr>
					<tr>
						<td><strong>Totales</strong></td>
						<td><input name="total_impr_hd"  class="numeros" type="text" size="15" id="total_impr_hd"  value="<?=$dImpresion['total_hd']?>"/></td>
						<td><input name="total_impr_bd" type="text" class="numeros" size="15" id="total_impr_bd"  value="<?=$dImpresion['total_bd']?>"/></td>
						<td><input name="total_desperdicio_hd" class="numeros"  type="text" size="15" id="total_impr_tira" value="<?=$dImpresion['desperdicio_hd']?>" /></td>
                        <td><input name="total_desperdicio_bd" class="numeros" type="text" size="15" id="total_impr_duro" value="<?=$dImpresion['desperdicio_bd']?>" /></td>
					</tr>
                    <tr>      
                      <td  align="right" colspan="7"><br>
                      <input type="submit" name="impresion" id="impresion" value="Guardar" ></td>
					</tr>
				</tbody>
        </table>
        </div>          <br><br>
        
    </div>
    
    
    </div>
 </div>
</form>

<?  ?>

<? } if($listar) { ?>
<body>
<form id="form" action="admin.php?seccion=6" method="post">
<div id="container">
		<div id="content">
        
<? if( $_SESSION['area'] == 1  && isset($_GET['extruder'])){ ?>
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES EXTRUDER</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
              <table>
                <tr>
                  <td colspan="5">Listado de reportes de bolseo:</td>
                </tr>
                <tr>
                  <td width="124" style="color:#000000"><b>Fecha</b></td>
                  <td width="279" style="color:#000000"><b>Nombre</b></td>
                  <td width="39" style="color:#000000"><b>Turno</b></td>
                </tr>
                <?php
				$qGenerales		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno,id_entrada_general FROM entrada_general".
									" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."' /*AND autorizada = '0'*/ AND impresion = '0' ORDER BY fecha ASC";
				$rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");
				$sGenerales		=	array();
				while($dGenerales	=	mysql_fetch_assoc($rGenerales)){ ?>
                <tr onMouseOver="this.style.backgroundColor='#CCC'"
                    onMouseOut="this.style.backgroundColor='#EAEAEA'" 
                    style="cursor:default; background-color:#EAEAEA">
                  <td><?=$dGenerales['fecha']?></td>
                  <td><?=$dGenerales['nombre']?></td>
                  <td><?=$dGenerales['turno']?></td>
                  <td width="81"><a href="admin_supervisor.php?seccion=31&accion=autorizar&extruder&id_reporte=<?=$dGenerales['id_entrada_general']?>">Modificar</a></td>
                  <td width="245"><a href="admin_supervisor.php?seccion=31&accion=eliminar_extruder&id_reporte=<?=$dGenerales['id_entrada_general']?>" onClick="javascript: return confirm('Est· seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>
                </tr>
                <?php } ?>
                <br />
              </table>
            </div>

<? }		  if( $_SESSION['area2'] == 1  && isset($_GET['impresion'])){ ?>
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES IMPRESION</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
        <table>
                    <tr>
                        <td colspan="5">Listado de reportes de bolseo:</td>
                    </tr>
                    <tr>
                        <td width="124" style="color:#000000"><b>Fecha</b></td>
                      <td width="279" style="color:#000000"><b>Nombre</b></td>
                      <td width="39" style="color:#000000"><b>Turno</b></td>
               </tr>
                  
                <?php
				$qGenerales2		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno, entrada_general.id_entrada_general FROM entrada_general ".
									"LEFT JOIN impresion  ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
									"LEFT JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor ".
									"WHERE entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."' /*AND autorizada = '0'*/ AND impresion = '1' ORDER BY fecha ASC";
									
									
				$rGenerales2		=	mysql_query($qGenerales2) OR die("<p>$qGenerales2</p><p>".mysql_error()."</p>");
				$sGenerales2		=	array();
				while($dGenerales2	=	mysql_fetch_assoc($rGenerales2)){ ?>
                 <tr onMouseOver="this.style.backgroundColor='#CCC'"
                    onmouseout="this.style.backgroundColor='#EAEAEA'" 
                    style="cursor:default; background-color:#EAEAEA">                              
               		<td><?=$dGenerales2['fecha']?></td>
                    <td><?=$dGenerales2['nombre']?></td>
                    <td><?=$dGenerales2['turno']?></td>
                    <td width="81"><a href="admin_supervisor.php?seccion=31&accion=impresion&impresion&id_reporte=<?=$dGenerales2['id_entrada_general']?>">Modificar</a></td>
                  <td width="245"><a href="admin_supervisor.php?seccion=31&accion=eliminar_impresion&id_reporte=<?=$dGenerales2['id_entrada_general']?>" onClick="javascript: return confirm('Est· seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>
          		</tr>				
				<?php } ?>
                <br />
              </table>
		</div>
       
<? } if($_SESSION['area3'] == 1 && isset($_GET['bolseo'])){ ?>

		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#bolseo">REPORTES BOLSEO</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
          <table>
        		<tr>
            		<td colspan="5">Listado de reportes de bolseo:</td>
                </tr>
                <tr>
                	<td width="128" style="color:#000000"><b>Fecha</b></td>
               	  <td width="270" style="color:#000000"><b>Nombre</b></td>
               	  <td width="44" style="color:#000000"><b>Turno</b></td>
			  </tr>
 		        <?php
				$qBolseo		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno,id_bolseo FROM bolseo".
									" LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor  WHERE bolseo.id_supervisor = '".$_SESSION['id_supervisor']."' /*AND autorizada = '0'*/  ORDER BY fecha ASC";
				$rBolseo		=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");
				$sBolseo		=	array();
				while($dBolseo	=	mysql_fetch_assoc($rBolseo)){ ?>
                <tr onMouseOver="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#EAEAEA'" 
	style="cursor:default; background-color:#EAEAEA">
                	<td><?=$dBolseo['fecha']?></td>
                    <td><?=$dBolseo['nombre']?></td>
                    <td><?=$dBolseo['turno']?></td>
                    <td width="81"><a href="admin_supervisor.php?seccion=31&accion=autorizar_bolseo&bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>">Modificar</a></td>
                  <td width="245"><a href="admin_supervisor.php?seccion=31&accion=eliminar_bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>" onClick="javascript: return confirm('Est· seguro que desea eliminar este reporte del sistema ?');" >Eliminar</a></td>
          </tr>
                <?php } ?>               
                <br />
				</table>
            </div>
 <? } ?>
     
	</div>
	
</div>
</form>

<? } ?>
<?php if($nuevaEntrada) { ?>
<table align="center" width="100%">
<tr>
	<td align="center">
		<div class="tablaCentrada">
			<p><br>
			  <br>
		    Se a actualizado el registro  en el sistema.<br>
			<br>
			</p>
			
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