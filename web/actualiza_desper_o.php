<?php

include('libs/conectar.php');

if(isset($_POST['guardar']) && ($_REQUEST['actualiza'] == 0 )  && (isset($_REQUEST['id_extruder']) && isset($_REQUEST['id_bolseo']) && isset($_REQUEST['id_impresion']) ))
{
  	$qActualizaExtruder = "UPDATE orden_produccion SET desperdicio_tira = '".$_REQUEST['desperdicio_tira']."' , desperdicio_duro = '".$_REQUEST['desperdicio_duro']."' WHERE id_entrada_general = '".$_REQUEST['id_extruder']."' ";
	$rActualizaExtruder = mysql_query($qActualizaExtruder);
	 $qOrden = "UPDATE entrada_general SET actualizado = '1', repesada=1,  fecha_repesada='".date('Y-m-d')."' WHERE id_entrada_general =  '".$_REQUEST['id_extruder']."' ";
	$rOrden = mysql_query($qOrden);

	//EN CASO DE QUE EL ADMINISTRADO DE UNA ENTRADA DE REPESADA.
	if(isset($_REQUEST['id_supervisor'])){
		$qRepesada	=	"INSERT INTO repesadas (id_admin, id_supervisor, turno, id_entrada_general, desperdicio1, desperdicio2, diferencia1, diferencia2) VALUES('0','{$_REQUEST[id_supervisor]}','{$_REQUEST[turno]}','{$_REQUEST[id_extruder]}','{$_POST[desperdicio_tira_s]}','{$_POST[desperdicio_duro_s]}' ,'{$_POST[diferencia_tira]}','{$_POST[diferencia_duro]}')";
 	}
	if(!isset($_REQUEST['id_supervisor'])){
	 $qRepesada	=	"INSERT INTO repesadas (id_admin, id_supervisor, turno, id_entrada_general, desperdicio1, desperdicio2, diferencia1, diferencia2) VALUES('{$_REQUEST[id_admin]}','{$_REQUEST[id_supervisor]}','{$_REQUEST[turno]}','{$_REQUEST[id_extruder]}','{$_POST[desperdicio_tira_s]}','{$_POST[desperdicio_duro_s]}' ,'{$_POST[diferencia_tira]}','{$_POST[diferencia_duro]}')";
	}
	$rRepesada	=	mysql_query($qRepesada);


	 $qActualizaImpresion = "UPDATE impresion SET desperdicio_hd = '".$_REQUEST['total_desperdicio_hd']."' , desperdicio_bd = '".$_REQUEST['total_desperdicio_bd']."' WHERE id_entrada_general = '".$_REQUEST['entrada_impresion']."' ";
	$rActualizaImpresion = mysql_query($qActualizaImpresion);
	
 	 $qOrden2 = "UPDATE entrada_general  SET actualizado = '1', repesada=1,  fecha_repesada='".date('Y-m-d')."' WHERE id_entrada_general =  '".$_REQUEST['entrada_impresion']."' ";
	$rOrden2 = mysql_query($qOrden2);
	
	//EN CASO DE QUE EL ADMINISTRADO DE UNA ENTRADA DE REPESADA.
	if(isset($_REQUEST['id_supervisor'])){
	 $qRepesada	=	"INSERT INTO repesadas (id_admin, id_supervisor, turno, id_entrada_general, desperdicio1, desperdicio2, diferencia1, diferencia2) VALUES('0','{$_REQUEST[id_supervisor]}','{$_REQUEST[turno]}','{$_REQUEST[entrada_impresion]}','{$_POST[total_desperdicio_hd_s]}','{$_POST[total_desperdicio_bd_s]}' ,'{$_POST[diferencia_hd]}','{$_POST[diferencia_bd]}')";
	} 
	if(!isset($_REQUEST['id_supervisor'])){
	 $qRepesada	=	"INSERT INTO repesadas (id_admin, id_supervisor, turno, id_entrada_general, desperdicio1, desperdicio2, diferencia1, diferencia2) VALUES('{$_REQUEST[id_admin]}','{$_REQUEST[id_supervisor]}','{$_REQUEST[turno]}','{$_REQUEST[entrada_impresion]}','{$_POST[total_desperdicio_hd_s]}','{$_POST[total_desperdicio_bd_s]}' ,'{$_POST[diferencia_hd]}','{$_POST[diferencia_bd]}')";
	}
	$rRepesada	=	mysql_query($qRepesada);

	$qActualizaBolseo = "UPDATE bolseo SET actualizado = '1', repesada=1,  fecha_repesada='".date('Y-m-d')."', dtira = '".$_REQUEST['tdt']."' , dtroquel = '".$_REQUEST['tdtr']."' , segundas = '".$_REQUEST['tse']."' WHERE id_bolseo = '".$_REQUEST['id_bolseo']."'";
	$rActualizaBolseo = mysql_query($qActualizaBolseo);	
	
//EN CASO DE QUE EL ADMINISTRADO DE UNA ENTRADA DE REPESADA.
	if(isset($_REQUEST['id_supervisor'])){
 	  $qRepesada	=	"INSERT INTO repesadas (id_admin, id_supervisor, turno, id_entrada_general, desperdicio1, desperdicio2,desperdicio3, diferencia1, diferencia2, diferencia3, bolseo) VALUES('0','{$_REQUEST[id_supervisor]}','{$_REQUEST[turno]}','{$_REQUEST[id_bolseo]}','{$_POST[tdt_s]}','{$_POST[tdtr_s]}','{$_POST[tse_s]}' ,'{$_POST[diferencia_tdt]}','{$_POST[diferencia_tdtr]}','{$_POST[diferencia_tse]}','1')";
	}
	if(!isset($_REQUEST['id_supervisor'])){
	 $qRepesada	=	"INSERT INTO repesadas (id_admin, id_supervisor, turno, id_entrada_general, desperdicio1, desperdicio2,desperdicio3, diferencia1, diferencia2, diferencia3, bolseo) VALUES('{$_REQUEST[id_admin]}','{$_REQUEST[id_supervisor]}','{$_REQUEST[turno]}','{$_REQUEST[id_bolseo]}','{$_POST[tdt_s]}','{$_POST[tdtr_s]}','{$_POST[tse_s]}' ,'{$_POST[diferencia_tdt]}','{$_POST[diferencia_tdtr]}','{$_POST[diferencia_tse]}','1')";
	}
	$rRepesada	=	mysql_query($qRepesada);

	echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=listar&exito\';</script>';
}


// ACTUALIZA LAS MODIFICACIONES
if(isset($_POST['guardar']) && ($_REQUEST['actualiza'] == 1 )  && (isset($_REQUEST['id_extruder']) && isset($_REQUEST['id_bolseo']) && isset($_REQUEST['id_impresion']) ))
{
	$qBuscarE	=	"SELECT * FROM repesadas WHERE id_entrada_general = '{$_REQUEST[id_extruder]}'";
	$rBuscarE 	= mysql_query($qBuscarE);
	$nBuscarE	= mysql_num_rows($rBuscarE);	
	
 	$qActualizaExtruder = "UPDATE orden_produccion SET desperdicio_tira = '".$_REQUEST['desperdicio_tira']."' , desperdicio_duro = '".$_REQUEST['desperdicio_duro']."' WHERE id_entrada_general = '".$_REQUEST['id_extruder']."' ";
	$rActualizaExtruder = mysql_query($qActualizaExtruder);

	$qOrden = "UPDATE entrada_general SET actualizado = '1', fecha_repesada='".$_REQUEST['fecha']."' WHERE id_entrada_general =  '".$_REQUEST['id_extruder']."' ";
	$rOrden = mysql_query($qOrden);

	if($nBuscarE < 1)
	 $qRepesada	=	"INSERT INTO repesadas  (id_admin, id_entrada_general, desperdicio1, desperdicio2, diferencia1 ,diferencia2) VALUES('{$_POST[id_admin]}', '{$_REQUEST[id_extruder]}' ,'{$_POST[desperdicio_tira_s]}','{$_POST[desperdicio_duro_s]}','{$_POST[diferencia_tira]}', '{$_POST[diferencia_duro]}' )  ";
	if($nBuscarE > 0)
	 $qRepesada	=	"UPDATE repesadas SET id_admin = '{$_POST[id_admin]}',  desperdicio1 = '{$_POST[desperdicio_tira_s]}' , desperdicio2 = '{$_POST[desperdicio_duro_s]}',  diferencia1 = '{$_POST[diferencia_tira]}', diferencia2 = '{$_POST[diferencia_duro]}'  WHERE id_entrada_general = '{$_REQUEST[id_extruder]}' ";
	$rRepesada	=	mysql_query($qRepesada);
	
//ESTE ID ESTA BIEN.

	$qBuscarI	=	"SELECT * FROM repesadas WHERE id_entrada_general = '{$_REQUEST[entrada_impresion]}'";
	$rBuscarI 	= mysql_query($qBuscarI);
	$nBuscarI	= mysql_num_rows($rBuscarI);

	$qActualizaImpresion = "UPDATE impresion SET  desperdicio_hd = '".$_REQUEST['total_desperdicio_hd']."' , desperdicio_bd = '".$_REQUEST['total_desperdicio_bd']."' WHERE id_entrada_general = '".$_REQUEST['entrada_impresion']."' ";
	$rActualizaImpresion = mysql_query($qActualizaImpresion);
	$qOrden = "UPDATE entrada_general  SET actualizado = '1', fecha_repesada='".$_REQUEST['fecha']."' WHERE id_entrada_general =  '".$_REQUEST['entrada_impresion']."' ";
	$rOrden = mysql_query($qOrden);
	
	
	if($nBuscarI < 1)
	 $qRepesada	=	"INSERT INTO repesadas  (id_admin, id_entrada_general, desperdicio1, desperdicio2, diferencia1 ,diferencia2) VALUES('{$_POST[id_admin]}', '{$_REQUEST[entrada_impresion]}', '{$_POST[total_desperdicio_hd_s]}','{$_POST[total_desperdicio_bd_s]}','{$_POST[diferencia_hd]}', '{$_POST[diferencia_bd]}')  ";	
	if($nBuscarI > 1)
	 $qRepesada	=	"UPDATE  repesadas SET id_admin = '{$_POST[id_admin]}', desperdicio1 = '{$_POST[total_desperdicio_hd_s]}' , desperdicio2 = '{$_POST[total_desperdicio_bd_s]}' , diferencia1 = '{$_POST[diferencia_hd]}', diferencia2 = '{$_POST[diferencia_bd]}'  WHERE id_entrada_general = '{$_REQUEST[entrada_impresion]}'";
	$rRepesada	=	mysql_query($qRepesada);

	 $qBuscar	=	"SELECT * FROM repesadas WHERE id_entrada_general = '{$_REQUEST[id_bolseo]}'";
	$rBuscar 	= mysql_query($qBuscar);
	$nBuscar	= mysql_num_rows($rBuscar);		

 	$qActualizaBolseo = "UPDATE bolseo SET actualizado = '1', fecha_repesada='".$_REQUEST['fecha']."', dtira = '".$_REQUEST['tdt']."' , dtroquel = '".$_REQUEST['tdtr']."' , segundas = '".$_REQUEST['tse']."' WHERE id_bolseo = '".$_REQUEST['id_bolseo']."'";
	$rActualizaBolseo = mysql_query($qActualizaBolseo);	
	
	if($nBuscar < 1)
	  $qRepesada	=	"INSERT INTO repesadas (id_admin, id_supervisor, turno, id_entrada_general, desperdicio1, desperdicio2, desperdicio3, diferencia1, diferencia2, diferencia3 ) VALUES('{$_POST[id_admin]}','{$_REQUEST[id_supervisor]}','{$_REQUEST[turno]}','{$_REQUEST[id_bolseo]}', '{$_POST[tdt_s]}','{$_POST[tdtr_s]}' ,'{$_POST[tse_s]}','{$_POST[diferencia_tdt]}','{$_POST[diferencia_tdtr]}', '{$_POST[diferencia_tse]}') ";
	if($nBuscar > 0)
	  $qRepesada	=	"UPDATE  repesadas SET id_admin = '{$_POST[id_admin]}', desperdicio1 = '{$_POST[tdt_s]}', desperdicio2 = '{$_POST[tdtr_s]}' ,desperdicio3 = '{$_POST[tse_s]}', diferencia1 = '{$_POST[diferencia_tdt]}', diferencia2 = '{$_POST[diferencia_tdtr]}', diferencia3 = '{$_POST[diferencia_tse]}' WHERE id_entrada_general = '{$_REQUEST[id_bolseo]}'";

	$rRepesada	=	mysql_query($qRepesada);

echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=edicion&exito\';</script>';
	
}

if(!empty($_GET['accion']))
{
	$listar		=	($_GET['accion']=="listar")?true:false;
	$opcion		=	($_GET['accion']=="opcion")?true:false;
	$supervisor		= ($_GET['accion']=="supervisor")?true:false;
	$repesar	=	($_GET['accion']=="repesar")?true:false;
	$modificar	=	($_GET['accion']=="modificar")?true:false;
	$ver	=	($_GET['accion']=="ver")?true:false;
	$edicion	=	($_GET['accion']=="edicion")?true:false;
	if($repesar || $modificar || $ver)
	{	
	
		//PARA EXTRUDER
		$qExtruder	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
								"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE impresion = 0 AND (fecha='{$_REQUEST[fecha]}') AND turno = {$_REQUEST[turno]}";
		$rExtruder	=	mysql_query($qExtruder) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");
		$dExtruder	=	mysql_fetch_assoc($rExtruder);
		
		$qOrdenProduccion	=	"SELECT  total, desperdicio_tira, desperdicio_duro FROM orden_produccion WHERE (id_entrada_general = {$dExtruder[id_entrada_general]})";
		$rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");
		$dOrdenProduccion	=	mysql_fetch_assoc($rOrdenProduccion);

	
		//PARA IMPRESION
	 	$qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
								"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE impresion = 1 AND fecha='".$_REQUEST['fecha']."' AND turno = ".$_REQUEST['turno']."  ";
		$rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");
		$dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral); 

	 	$qImpresion	= "SELECT * FROM impresion WHERE (id_entrada_general = '".$dEntradaGeneral['id_entrada_general']."') ";
		$rImpresion = mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
		$dImpresion = mysql_fetch_assoc($rImpresion);

		//PARA BOLSEO
	 	$qActualizaBolseo	=	"SELECT * FROM bolseo  WHERE (fecha='{$_REQUEST[fecha]}') AND turno = {$_REQUEST[turno]}";
		$rActualizaBolseo	=	mysql_query($qActualizaBolseo) OR die("<p>$qActualizaBolseo</p><p>".mysql_error()."</p>");
		$dActualizaBolseo	=	mysql_fetch_assoc($rActualizaBolseo);
		
		
  	
 		if($modificar || $ver) {
  		$qImpresion2	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
								"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE impresion = 1 AND fecha='".$_REQUEST['fecha']."' AND turno = ".$_REQUEST['turno']."  ";
		$rImpresion2	=	mysql_query($qImpresion2) OR die("<p>$qImpresion2</p><p>".mysql_error()."</p>");
		$dImpresion2	=	mysql_fetch_assoc($rImpresion2);
		
		$qRepedasaImp	=	"SELECT * FROM repesadas WHERE id_entrada_general = ".$dImpresion2['id_entrada_general'].""; 	
		$rRepedasaImp	=	mysql_query($qRepedasaImp) OR die("<p>$qRepedasaImp</p><p>".mysql_error()."</p>");
		$dRepedasaImp	=	mysql_fetch_assoc($rRepedasaImp);	
		
 		$qExtruder	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
								"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE impresion = 0 AND fecha='".$_REQUEST['fecha']."' AND turno = ".$_REQUEST['turno']."  ";
		$rExtruder	=	mysql_query($qExtruder) OR die("<p>$qExtruder</p><p>".mysql_error()."</p>");
		$dExtruder	=	mysql_fetch_assoc($rExtruder);	
		
		$qRepedasaExtr	=	"SELECT * FROM repesadas WHERE id_entrada_general = ".$dExtruder['id_entrada_general'].""; 	
		$rRepedasaExtr	=	mysql_query($qRepedasaExtr) OR die("<p>$qRepedasaExtr</p><p>".mysql_error()."</p>");
		$dRepedasaExtr	=	mysql_fetch_assoc($rRepedasaExtr);			
		
		$qRepedasaBol	=	"SELECT * FROM repesadas WHERE id_entrada_general = ".$dActualizaBolseo['id_bolseo']." AND bolseo = 1"; 	
		$rRepedasaBol	=	mysql_query($qRepedasaBol) OR die("<p>$qRepedasaBol</p><p>".mysql_error()."</p>");
		$dRepedasaBol	=	mysql_fetch_assoc($rRepedasaBol);	

		}		
	}

}
?>
	<script type="text/javascript" src="mootools.js"></script>
    <script type="text/javascript">
	
	function diferencia(valor1,valor2,valorTotal)
	{
		var a=document.getElementById(valor1);
		var b=document.getElementById(valor2);
		var total=document.getElementById(valorTotal);		
		total.value=parseFloat(a.value)-parseFloat(b.value);
	}


	</script>
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


<? if($repesar || $modificar){ ?>
<script language="javascript" type="text/javascript">
function validar()
{
  if(document.form.desperdicio_tira.value==""){
	  alert("Debe ingresar un valor");
	  document.form.desperdicio_tira.focus();
    return (false);
 	 }
  if(document.form.desperdicio_duro.value==""){
	  alert("Debe ingresar un valor");
	  document.form.desperdicio_duro.focus();
    return (false);
 	 }
	 
	 
  if(document.form.total_desperdicio_hd.value==""){
	  alert("Debe ingresar un valor");
	  document.form.total_desperdicio_hd.focus();
    return (false);
 	 }
  if(document.form.total_desperdicio_bd.value==""){
	  alert("Debe ingresar un valor");
	  document.form.total_desperdicio_bd.focus();
    return (false);
 	 }
	 
	 
  if(document.form.tdt.value==""){
	  alert("Debe ingresar un valor");
	  document.form.tdt.focus();
    return (false);
 	 }

	 
	 
  if(document.form.tdtr.value==""){
	  alert("Debe ingresar un valor");
	  document.form.tdtr.focus();
    return (false);
 	 }

  if(document.form.tse.value==""){
	  alert("Debe ingresar un valor");
	  document.form.tse.focus();
    return (false);
 	 }
	
  
 else
 	{ 
  	  document.form.submit();
 	}

}
</script>
<form name="form" id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post" onSubmit="return validar();">
<div id="container">
	<br />
	<div id="content"><br>

                <div align="center"><br>
      </div>
      <div id="datosgenerales" style="background-color:#FFFFFF;">
        <table width="520" align="center" >
            <tbody>
        			<tr>
        			  <td colspan="4"><div id="datosgenerales2" style="background-color:#FFFFFF;">
                        <p>
                          <label for="supervisor">Supervisor</label>
                          
                          <? 
						  if($modificar){
						  	if($dRepedasaExtr['id_supervisor'] != "") $supervisores = $dRepedasaExtr['id_supervisor'];
						  	if($dRepedasaImp['id_supervisor'] != "") $supervisores = $dRepedasaImp['id_supervisor'];
						  	if($dRepedasaBol['id_supervisor'] != "") $supervisores = $dRepedasaBol['id_supervisor'];

						  	$qNombres	= "SELECT nombre FROM supervisor WHERE id_supervisor = ".$supervisores."  ";
							$rNombres	= mysql_query($qNombres);
							$dNombres	= mysql_fetch_assoc($rNombres);	
							}
						  
						  ?>
                          <input type="text" id="supervisor" value="<? if($modificar){ echo $dNombres['nombre'];}  else { if($_SESSION['nombre'] == ""){ echo $_SESSION['nombre_admin']; } else { echo $_SESSION['nombre'];} }?>" readonly="readonly" class="datosgenerales"/>
                          <? if($modificar){?>
                          <br /><label for="modificado">Modificado Por:</label>
                          <input type="text" id="administrador" value="<? if($_SESSION['nombre'] == ""){ echo $_SESSION['nombre_admin']; } else { echo $_SESSION['nombre'];}  ?>" readonly="readonly" />
                          <? } ?>                          <br />
                          <label for="fecha">Fecha</label>
                          <input type="text" name="fecha" value="<? if($modificar) echo preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" , $dExtruder['fecha']);
						  else 
						  echo preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,date('Y-m-d')) ?>" id="fecha" class="datosgenerales" readonly="readonly" />
                          <br />
                          <label for="fecha">Turno</label>
                          <input type="text" class="datosgenerales"  value="<? 
			if($_REQUEST['turno'] == 1) echo "Vespertino";
			if($_REQUEST['turno'] == 2) echo "Nocturno";
			if($_REQUEST['turno'] == 3) echo "Matutino";
			?>" readonly="readonly"	/>
                          <input type="hidden" name="turno" id="turno" value="<? 
						  if( $_REQUEST['turno']+1  == 2 ) echo "2";
						  if( $_REQUEST['turno']+1  == 3 ) echo "3";
						  if( $_REQUEST['turno']+1  == 4 ) echo "1";
						  ?>" />
                          <br />
                          <? if($_SESSION['id_supervisor'] != "" || $_SESSION['id_supervisor'] != 0){?>
                          <input type="hidden" name="id_supervisor"       value="<?=$_SESSION['id_supervisor']?>" />
                          <input type="hidden" name="id_admin"       value="0" />
                          <? } if($_SESSION['id_supervisor'] == "" || $_SESSION['id_supervisor'] == 0) { ?>
                          <input type="hidden" name="id_admin"       value="<?=$_SESSION['id_admin']?>" />
                          <? if($modificar){?>
                          <input type="hidden" name="id_supervisor" value="<?=$dRepedasaImp['id_supervisor']?>" />
                          <? } ?>
                          <? } ?>

                          <input type="hidden" name="id_entrada_general"  value="<?=$dEntradaGeneral['id_entrada_general']?>" />
                          <input type="hidden" name="id_impresion"        value="<?=$dImpresion['id_impresion']?>" />
                        </p>
        			    <br />
                      </div></td>
      			  </tr>
        			<tr>
        			  <td colspan="4"><h3>EXTRUDER</h3></td>
       			  </tr>
        			<tr>
						<td>&nbsp;</td>
						<td>TOTAL</td>
						<td>D. Tira</td>
						<td>D. Duro</td>
					</tr>
					<tr>
						<td><strong>Totales sin repesar</strong></td>
						<td><input type="text" id="total" name="total" value="<?=$dOrdenProduccion['total'];?>" class="total" readonly /></td>
						<td><input type="text" id="desperdicio_tira_s" name="desperdicio_tira_s" value="<? if($modificar){ echo  $dRepedasaExtr['desperdicio1'];} if($repesar){ echo  $dOrdenProduccion['desperdicio_tira']; }  ?>" class="total"  onChange="javascript: diferencia(this.id,'desperdicio_tira','diferencia_tira');"/></td>
						<td><input type="text" id="desperdicio_duro_s" name="desperdicio_duro_s" value="<? if($modificar){ echo  $dRepedasaExtr['desperdicio2'];} if($repesar){ echo  $dOrdenProduccion['desperdicio_duro']; }   ?>" class="total" onChange="javascript: diferencia(this.id,'desperdicio_duro','diferencia_duro');"  /></td>
					</tr>
					<tr>
						<td><strong>Repesada</strong></td>	
						<td>&nbsp;</td>
						<td><input type="text" id="desperdicio_tira" name="desperdicio_tira" value="<? if($modificar){ echo  $dOrdenProduccion['desperdicio_tira']; } ?>" onChange="javascript: diferencia('desperdicio_tira_s',this.id,'diferencia_tira');" class="total" /></td>
						<td><input type="text" id="desperdicio_duro" name="desperdicio_duro" value="<? if($modificar){ echo	 $dOrdenProduccion['desperdicio_duro']; } ?>" onChange="javascript: diferencia('desperdicio_duro_s',this.id,'diferencia_duro');" class="total"  /></td>
					</tr>			
					<tr>
						<td><strong>DIFERENCIA</strong></td>
						<td>&nbsp;</td>
						<td><input type="text" id="diferencia_tira" name="diferencia_tira" value="<? if($modificar){ echo $dRepedasaExtr['diferencia1'];} ?>" class="total" readonly="readonly" /></td>
						<td><input type="text" id="diferencia_duro" name="diferencia_duro" value="<? if($modificar){ echo $dRepedasaExtr['diferencia2'];} ?>" class="total" readonly="readonly"  /></td>
					</tr>           
            </tbody>
        </table>
        <table width="520" align="center" >
          <tbody>
            <tr>
              <td colspan="5"><h3>IMPRESION</h3></td>
            </tr>
            <tr>
              <td colspan="5"></td>
            </tr>
            <tr>
              <td width="104"></td>
              <td width="84">Total HD</td>
              <td width="87">Total BD</td>
              <td width="104">D. Hd</td>
              <td width="106">D. Bd</td>
            </tr>
            <tr>
              <td><strong>Totales</strong></td>
              <td><input type="text" id="total_impr_hd" name="total_impr_hd" value="<?=$dImpresion['total_hd']?>" class="total"  readonly="readonly" /></td>
              <td><input type="text" id="total_impr_bd" name="total_impr_bd" value="<?=$dImpresion['total_bd']?>" class="total"  readonly="readonly" /></td>
              <td><input type="text" id="total_desperdicio_hd_s" name="total_desperdicio_hd_s" value="<?  if($modificar){ echo  $dRepedasaImp['desperdicio1']; } if($repesar){ echo $dImpresion['desperdicio_hd']; }?>" class="total"  onchange="javascript: diferencia(this.id,'total_desperdicio_hd','diferencia_hd');"  /></td>
              <td><input type="text" id="total_desperdicio_bd_s" name="total_desperdicio_bd_s" value="<?  if($modificar){ echo  $dRepedasaImp['desperdicio2']; } if($repesar){ echo $dImpresion['desperdicio_bd']; }?>" class="total" onchange="javascript: diferencia('total_desperdicio_bd',this.id,'diferencia_bd');" /></td>
            </tr>
            <tr>
              <td><strong>Repesada</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="text" id="total_desperdicio_hd" name="total_desperdicio_hd" value="<? if($modificar){ echo $dImpresion['desperdicio_hd']; } ?>" onchange="javascript: diferencia('total_desperdicio_hd_s',this.id,'diferencia_hd');" class="total" /></td>
              <td><input type="text" id="total_desperdicio_bd" name="total_desperdicio_bd" value="<? if($modificar){ echo $dImpresion['desperdicio_bd']; } ?>" onchange="javascript: diferencia('total_desperdicio_bd_s',this.id,'diferencia_bd');" class="total"  /></td>
            </tr>
            <tr>
              <td><strong>DIFERENCIA</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="text" id="diferencia_hd" name="diferencia_hd" value="<? if($modificar){ echo $dRepedasaImp['diferencia1'];} ?>" class="total" /></td>
              <td><input type="text" id="diferencia_bd" name="diferencia_bd" value="<? if($modificar){ echo $dRepedasaImp['diferencia2'];} ?>" class="total"  /></td>
            </tr>
            <tr>
              <input type="hidden" name="impresion" id="impresion" value="impresion" />
              <td align="right" colspan="5">&nbsp;</td>
            </tr>
          </tbody>
        </table>
        <table width="521" align="center" >
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
              <td colspan="6"><h3>BOLSEO</h3></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><b>Kilogramos</b></td>
              <td><b>Millares</b></td>
              <td><b>D. Tira</b></td>
              <td><b>D. Troquel</b></td>
              <td><b>Segundas</b></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Totales</strong></td>
              <td><input type="text" id="tml"  name="tml"  value="<?=$dActualizaBolseo['millares'];?>"   class="total" readonly="readonly" /></td>
              <td><input type="text" id="tkg"  name="tkg"  value="<?=$dActualizaBolseo['kilogramos']?>" class="total" readonly="readonly" /></td>
              <td><input type="text" id="tdt_s"  name="tdt_s"  value="<? if($modificar){ echo $dRepedasaBol['desperdicio1'];} if($repesar){ echo $dActualizaBolseo['dtira']; 	}  ?>"   class="total" onchange="javascript: diferencia(this.id,'tdt','diferencia_tdt');"  /></td>
              <td><input type="text" id="tdtr_s" name="tdtr_s" value="<? if($modificar){ echo $dRepedasaBol['desperdicio2'];} if($repesar){ echo $dActualizaBolseo['dtroquel'];	} ?>"   class="total"  onchange="javascript: diferencia(this.id,'tdtr','diferencia_tdtr');"/></td>
              <td><input type="text" id="tse_s"  name="tse_s"  value="<? if($modificar){ echo $dRepedasaBol['desperdicio3'];} if($repesar){ echo $dActualizaBolseo['segundas'];	} ?>"   class="total" onchange="javascript: diferencia(this.id,'tse','diferencia_tse');"/></td>
            </tr>
            <tr>
              <td><strong>Repesada</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="text" id="tdt"  name="tdt"  value="<? if($modificar){ echo $dActualizaBolseo['dtira']; 	} ?>"   class="total" onchange="javascript: diferencia('tdt_s',this.id,'diferencia_tdt');"  /></td>
              <td><input type="text" id="tdtr" name="tdtr" value="<? if($modificar){ echo $dActualizaBolseo['dtroquel']; 	} ?>"   class="total" onchange="javascript: diferencia('tdtr_s',this.id,'diferencia_tdtr');"  /></td>
              <td><input type="text" id="tse"  name="tse"  value="<? if($modificar){ echo $dActualizaBolseo['segundas'];	} ?>"   class="total" onchange="javascript: diferencia('tse_s',this.id,'diferencia_tse');"  /></td>
            </tr>
            <tr>
              <td><strong>DIFERENCIA</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="text" id="diferencia_tdt"  name="diferencia_tdt"  value="<? if($modificar){ echo $dRepedasaBol['diferencia1'];} ?>"   class="total" readonly="readonly"  /></td>
              <td><input type="text" id="diferencia_tdtr" name="diferencia_tdtr" value="<? if($modificar){ echo $dRepedasaBol['diferencia2'];} ?>"   class="total" readonly="readonly" /></td>
              <td><input type="text" id="diferencia_tse"  name="diferencia_tse"  value="<? if($modificar){ echo $dRepedasaBol['diferencia3'];} ?>"   class="total" readonly="readonly"  /></td>
            </tr>
            <tr>
              <input type="hidden" name="extruder" id="extruder" value="extruder" />
              <td width="214" align="center" colspan="6">
               <input type="submit" name="guardar" id="Guardar" value="Guardar"  />
               <input type="hidden" name="id_extruder"  id="id_extruder" value="<?=$dExtruder['id_entrada_general']; ?>" />
               <input type="hidden" name="id_impresion" id="id_impresion" value="<?=$dImpresion['id_impresion'];?>" />
               <input type="hidden" name="entrada_impresion" id="entrada_impresion" value="<?=$dImpresion['id_entrada_general']?>" />
               <input type="hidden" name="id_bolseo" id="id_bolseo" value="<?=$_REQUEST['id_bolseo'];?>"/>
    		   <input type="hidden" name="actualiza" value="<? if($repesada) echo '0'; if($modificar) echo '1';?>" />
    		  </td>
          	</tr>
            
          </tbody>
        </table>
      </div>
      <br>
      <div align="center"></div>    
      <div id="datosgenerales" style="background-color:#FFFFFF;"></div>   
             
    </div>    
 </div>
 </div>
</form>

<script language="javascript">
function cambia(valor)
{
	location.href='admin_supervisor.php?seccion=<?=$_REQUEST['seccion']?>&accion=listar&'+valor;
}
</script>
<? } if($listar) { ?>
<form id="form" action="admin.php?seccion=6" method="post">
<div id="container">
		<div id="content">
		  <h3><a style="color: rgb(255, 255, 255);" href="#extruder"><b>REPORTES PENDIENTES PARA REPESAR</b></a></h3>
		  <div>

            <div style="background-color:#FFFFFF;" class="navcontainer">
            <table  cellpadding="0" cellspacing="0">
            <?
				$qEntradas	=	"SELECT * FROM entrada_general INNER JOIN bolseo ON entrada_general.fecha	=	bolseo.fecha".
								" AND entrada_general.turno = bolseo.turno  ".
								" WHERE entrada_general.actualizado=0 AND bolseo.actualizado=0 AND entrada_general.impresion = 0 GROUP BY entrada_general.fecha, entrada_general.turno ORDER BY entrada_general.fecha, entrada_general.turno	ASC";
			
			//	$qEntradas="SELECT * FROM entrada_general INNER JOIN bolseo ON entrada_general.fecha=bolseo.fecha AND entrada_general.turno=bolseo.turno WHERE entrada_general.actualizado=0 AND bolseo.actualizado=0 AND entrada_general.impresion = 0 GROUP BY entrada_general.fecha, entrada_general.turno ORDER BY entrada_general.fecha, entrada_general.turno	 ASC";				
				
				$rEntradas=mysql_query($qEntradas);
				$i=0;
				$nEntradas	=	mysql_num_rows($rEntradas);
				if($nEntradas	> 0 ){
				?>
                <tr>
                	<td style="color:#000000" width="129"><b>Fecha</b></td>
               	  <td width="80" align="center" style="color:#000000"><b>Turno</b></td>
                  <td>&nbsp;</td>
				</tr>		
                <?php
				//Listar todas las entradas; que están pendientes de repesar (bolseo, impreison y extruder)
				
				
				while($dEntradas	=	mysql_fetch_assoc($rEntradas))
				{
					$qRepeImpresion	=	"SELECT * FROM entrada_general WHERE impresion = 1 AND fecha ='".$dEntradas['fecha']."' AND turno = ".$dEntradas['turno']." ";
					$rRepeImpresion =	mysql_query($qRepeImpresion);
					$nRepeImpresion	=	mysql_num_rows($rRepeImpresion);
					if($nRepeImpresion > 0){
				?>
				<tr onMouseOver="this.style.backgroundColor='#CCC'"	onmouseout="this.style.backgroundColor='#EAEAEA'" style="cursor:default; background-color:#EAEAEA">
                      <td width="129"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dEntradas['fecha'])?></td>
                  <td width="80" align="center"><?=$dEntradas['turno']?></td>
                  <td  ><a href="<?=$_SERVER['PHP_SELF']?>	?seccion=7&id_extruder=<?=$dEntradas['id_entrada_general']?>&id_bolseo=<?=$dEntradas['id_bolseo']?>&accion=repesar&turno=<?=$dEntradas['turno']?>&fecha=<?=$dEntradas['fecha']?>">Reconteo</a></td>
              </tr>
                <?php } else { ?> 
                <tr>
                	<td colspan="3" align="center" class="style4">Falta reporte de Impresión</td>
                 </tr>
                <? } } 
				}  else { ?>
                <tr>
                	<td colspan="3" align="center"><b>NO HAY REPORTES PENDIENTES</b></td>
              </tr>
 				<? } if(isset($_GET['exito'])){?>
                    <li><center><font color="#FF0000">Registro Actualizado.</font></center></li>
                <? } ?>        				</ul>
                <br />
              </table>
            </div>
		</div>
        
        <div>
          <div style="background-color:#FFFFFF;" class="navcontainer"></div>
     
		</div>
        
        <div>
            <div style="background-color:#FFFFFF;" class="navcontainer"></div>
		</div>
        </div>
</div>
<? } if($edicion){?>
<br /><br>	
<? 

				$qEntradasN="SELECT * FROM entrada_general INNER JOIN bolseo ON entrada_general.fecha=bolseo.fecha AND entrada_general.turno=bolseo.turno WHERE entrada_general.actualizado=0 AND bolseo.actualizado=0 AND entrada_general.impresion = 0 GROUP BY entrada_general.fecha, entrada_general.turno ORDER BY entrada_general.fecha, entrada_general.turno	 ASC";				
				$rEntradasN=mysql_query($qEntradasN);
				$nEntradasN	=	mysql_num_rows($rEntradasN);

 if($nEntradasN){?>

  <div align="center">
    <p align="center">
       <div style="background-color:#FFFFFF;"><a href="admin.php?seccion=<?=$_REQUEST['seccion']?>&accion=listar"><span style="color:#FF0000">Existen repesadas pendientes</span></a></div>
            </p>
  </div>
     <? } ?>
        <br />
           <h3><a style="color: rgb(255, 255, 255);" href="#extruder"><b>REPORTES REPESADAS ANTERIORES</b></a></h3>
		  <div>

            <div style="background-color:#FFFFFF;" class="navcontainer">
            <table  cellpadding="0" cellspacing="0">
            
            
            <?	
			
				$anho 	= 	date('Y');
				$mes	=	date('m');
				
			 	$ultimo_dia = UltimoDia($anho,$mes);
	
				$fin	= 	date('Y-m-d');
				$nMes	=	date('m');
				$nAnho	=	date('Y');
				$dia	=	date('d');
				$verificaDia	=	$dia - 12;
				
					if($verificaDia < 1 ){
					$temporal	=	$dia - 12;
					$dia		=	$ultimo_dia + $temporal;
						if($nMes	==	1){
							$nuevoMes	=	12;
							$nAnho	=	$nAnho - 1;
						}	
						else{
							$nuevoMes	=	$nMes - 1;
							$nAnho	=	$nAnho;
						}


					$inicio	=	$nAnho.'-'.$nuevoMes.'-'.$dia;
					} if($verificaDia >= 1 ){
					$inicio	=	date('Y-m-').$verificaDia;
					
					}

				
				
				$qEntradas	=	"SELECT * FROM repesadas INNER JOIN entrada_general ON repesadas.id_entrada_general = entrada_general.id_entrada_general".
								" LEFT JOIN bolseo ON entrada_general.fecha=bolseo.fecha AND entrada_general.turno=bolseo.turno ".
								" WHERE entrada_general.actualizado=1 AND bolseo.actualizado=1 AND entrada_general.impresion = 0 ".
								" AND entrada_general.fecha >= '".$inicio."' AND entrada_general.fecha <= '".$fin."' ".
								" GROUP BY entrada_general.fecha, entrada_general.turno ORDER BY entrada_general.fecha, entrada_general.turno ASC";				
				$rEntradas	=	mysql_query($qEntradas);
				$i=0;
				$nEntradas	=	mysql_num_rows($rEntradas);
				if($nEntradas	> 0 ){ ?>
                <tr>
                	<td style="color:#000000" width="129"><b>Fecha</b></td>
               	  <td width="80" align="center" style="color:#000000"><b>Turno</b></td>
                  <td>&nbsp;</td>
				</tr>		
                <?php
				//Listar todas las entradas; que están pendientes de repesar (bolseo, impreison y extruder)
				
				
				while($dEntradas	=	mysql_fetch_assoc($rEntradas))
				{

				?>
				<tr onMouseOver="this.style.backgroundColor='#CCC'"	onmouseout="this.style.backgroundColor='#EAEAEA'" style="cursor:default; background-color:#EAEAEA">
                      <td width="129"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dEntradas['fecha'])?></td>
                  <td width="80" align="center"><?=$dEntradas['turno']?></td>
                  <td  ><a href="<?=$_SERVER['PHP_SELF']?>	?seccion=7&id_extruder=<?=$dEntradas['id_entrada_general']?>&id_bolseo=<?=$dEntradas['id_bolseo']?>&accion=modificar&turno=<?=$dEntradas['turno']?>&fecha=<?=$dEntradas['fecha']?>">Modificar</a></td>
              </tr>
                <?php } 
				}  else { ?>
                <tr>
                	<td colspan="3" align="center"><b>NO HAY REPORTES PENDIENTES</b></td>
              </tr>
 				<? } if(isset($_GET['exito'])){?>
                    <li><center><font color="#FF0000">Registro Actualizado.</font></center></li>
                <? } ?>        				</ul>
                <br />
              </table>
            </div>
		</div>
        
        <div>
          <div style="background-color:#FFFFFF;" class="navcontainer"></div>
		</div>
        
        <div>
            <div style="background-color:#FFFFFF;" class="navcontainer"></div>
		</div>
        </div>
</div>
</form>
<?  }  ?>
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
			if( obj[i].id.indexOf('kg_extruder'+id) != -1 )
				skg += tmp;
		}
		document.getElementById('kgs_'+id).value = skg;
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
		
		}
		document.getElementById('tkg').value = tkg;
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
	
	//-->	
</script>
<? if($ver){ ?>
<div id="container">
	<div id="content">
      <div id="datosgenerales" style="background-color:#FFFFFF;">
        <table width="520" align="center">
<tbody>
        			<tr>
        			  <td colspan="4"><div id="datosgenerales2" style="background-color:#FFFFFF;">
                        <p>
                          <? 
						  	$qNombres	= "SELECT nombre FROM supervisor WHERE id_supervisor = ".$dRepedasaImp['id_supervisor']."";
							$rNombres	= mysql_query($qNombres);
							$dNombres	= mysql_fetch_assoc($rNombres);	
						  
  
						  	$qNombres2	= "SELECT nombre FROM administrador WHERE id_admin = ".$dRepedasaImp['id_admin']."";
							$rNombres2	= mysql_query($qNombres2);
							$dNombres2	= mysql_fetch_assoc($rNombres2);
							$nNombres2	=	mysql_num_rows($rNombres2);	
						  
						  ?>						  
						 
                          <label for="modificado">Supervisor : </label>
                      	  <?=$dNombres['nombre']; ?>
                          
                          <? if($nNombres2 > 0){ ?>
                          <br /><label for="modificado">Modificado Por:</label>
                          <?=$dNombres2['nombre']?>
                          <? } ?>
                           <br />
                     
                          
                          
                          <label for="fecha">Fecha</label>
                          <?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,date('Y-m-d'))?>
                          <br />
                          <label for="fecha">Turno</label>
                          <? 
			if($dExtruder['turno'] == 1) echo "Vespertino";
			if($dExtruder['turno'] == 2) echo "Nocturno";
			if($dExtruder['turno'] == 3) echo "Matutino";
			?>
                          <br />
                        </p>
        			    <br />
                      </div></td>
      			  </tr>
        			<tr>
        			  <td colspan="4"><h3>EXTRUDER</h3></td>
       			  </tr>
        			<tr>
						<td width="127">&nbsp;</td>
					  <td width="116" align="center">TOTAL</td>
					  <td width="135" align="center">D. Tira</td>
					  <td width="122" align="center">D. Duro</td>
		  </tr>
					<tr>
						<td ><strong>Totales sin repesar</strong></td>
						<td align="center" class="style5"><?=$dOrdenProduccion['total']?></td>
						<td align="center" class="style5"><?=$dRepedasaExtr['desperdicio1']?></td>
						<td align="center" class="style5"><?=$dRepedasaExtr['desperdicio2']?></td>
					</tr>
					<tr>
						<td><strong>Repesada</strong></td>	
						<td>&nbsp;</td>
						<td class="style5" align="center"><?=$dOrdenProduccion['desperdicio_tira']?></td>
						<td class="style5" align="center"><?=$dOrdenProduccion['desperdicio_duro']?></td>
					</tr>			
					<tr>
						<td><strong>DIFERENCIA</strong></td>
						<td class="style5" align="center">&nbsp;</td>
						<td class="style5" align="center"><?=$dRepedasaExtr['diferencia1']?></td>
						<td class="style5" align="center"><?=$dRepedasaExtr['diferencia2']?></td>
					</tr>           
            </tbody>
        </table>
        <table width="520" align="center">
          <tbody>
            <tr>
              <td colspan="5"><h3>IMPRESION</h3></td>
            </tr>
            <tr>
              <td colspan="5"></td>
            </tr>
            <tr>
              <td width="104"></td>
              <td width="84" align="center">Total HD</td>
              <td width="87" align="center">Total BD</td>
              <td width="104" align="center">D. Hd</td>
              <td width="106" align="center">D. Bd</td>
            </tr>
            <tr>
              <td><strong>Totales</strong></td>
              <td class="style5" align="center"><?=$dImpresion['total_hd']?></td>
              <td class="style5" align="center"><?=$dImpresion['total_bd']?></td>
              <td class="style5" align="center"><?=$dRepedasaImp['desperdicio1']?></td>
              <td class="style5" align="center"><?=$dRepedasaImp['desperdicio2']?></td>
            </tr>
            <tr>
              <td><strong>Repesada</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="style5" align="center"><?=$dImpresion['desperdicio_hd']?></td>
              <td class="style5" align="center"><?=$dImpresion['desperdicio_bd']?></td>
            </tr>
            <tr>
              <td><strong>DIFERENCIA</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="style5" align="center"><?=$dRepedasaImp['diferencia1']?></td>
              <td class="style5" align="center"><?=$dRepedasaImp['diferencia2']?></td>
            </tr>
            <tr>
              <input type="hidden" name="impresion" id="impresion" value="impresion" />
              <td align="right" colspan="5">&nbsp;</td>
            </tr>
          </tbody>
        </table>
        <table width="521" align="center" >
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
              <td colspan="6"><h3>BOLSEO</h3></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="center"><b>Kilogramos</b></td>
              <td align="center"><b>Millares</b></td>
              <td align="center"><b>D. Tira</b></td>
              <td align="center"><b>D. Troquel</b></td>
              <td align="center"><b>Segundas</b></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Totales</strong></td>
              <td class="style5" align="center"><?=$dActualizaBolseo['millares'];?></td>
              <td class="style5" align="center"><?=$dActualizaBolseo['kilogramos']?></td>
              <td class="style5" align="center"><?=$dRepedasaBol['desperdicio1']?></td>
              <td class="style5" align="center"><?=$dRepedasaBol['desperdicio2']?></td>
              <td class="style5" align="center"><?=$dRepedasaBol['desperdicio3']?></td>
            </tr>
            <tr>
              <td><strong>Repesada</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="style5" align="center"><?=$dActualizaBolseo['dtira']?></td>
              <td class="style5" align="center"><?=$dActualizaBolseo['dtroquel']?></td>
              <td class="style5" align="center"><?=$dActualizaBolseo['segundas']?></td>
            </tr>
            <tr>
              <td><strong>DIFERENCIA</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="style5" align="center"><?=$dRepedasaBol['diferencia1']?></td>
              <td class="style5" align="center"><?=$dRepedasaBol['diferencia2']?></td>
              <td class="style5" align="center"><?=$dRepedasaBol['diferencia3']?></td>
            </tr>
  <tr>
  	<td colspan="10" align="right"><br /><br /><input type="button" value="REGRESAR" onclick="history.go(-1)" /><input type="button" name="impresion" value="Imprimir" onclick="window.print();" /></td>
  </tr>
            
          </tbody>
        </table>
      </div>
      <br>
      <div align="center"></div>    
      <div id="datosgenerales" style="background-color:#FFFFFF;"></div>   
             
    </div>    
 </div>
 </div>
 <? } ?>