<?php
include('libs/conectar.php');


if(isset($_POST['guardar'])  && (isset($_REQUEST['id_entrada_general']) && isset($_REQUEST['extruder']) ))
{
 	$qActualizaExtruder = "UPDATE orden_produccion SET desperdicio_tira = '".$_REQUEST['desperdicio_tira']."' , desperdicio_duro = '".$_REQUEST['desperdicio_duro']."' WHERE id_orden_produccion = '".$_REQUEST['id_entrada_general']."' ";
	$rActualizaExtruder = mysql_query($qActualizaExtruder);
		
	$qOrden = "UPDATE entrada_general SET actualizado = '1' WHERE id_entrada_general =  '".$_REQUEST['id_entrada_general']."' ";
	$rOrden = mysql_query($qOrden);
	
	$qRepesada	=	"INSERT INTO repesadas (id_entrada_general, desperdicio1, desperdicio2, diferencia1, diferencia2) VALUES('{$_REQUEST[id_entrada_general]}','{$_POST[desperdicio_tira_s]}','{$_POST[desperdicio_duro_s]}' ,'{$_POST[diferencia_tira]}','{$_POST[diferencia_duro]}')";
	$rRepesada	=	mysql_query($qRepesada);
	
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=listar&exito\';</script>';

}



if(isset($_POST['guardar'])  && (isset($_REQUEST['id_entrada_general']) && isset($_REQUEST['impresion']) ))
{
	
 	$qActualizaImpresion = "UPDATE impresion SET desperdicio_hd = '".$_REQUEST['total_desperdicio_hd']."' , desperdicio_bd = '".$_REQUEST['total_desperdicio_bd']."' WHERE id_impresion = '".$_REQUEST['id_impresion']."' ";
	$rActualizaImpresion = mysql_query($qActualizaImpresion);
	
	$qOrden = "UPDATE entrada_general SET actualizado = '1' WHERE id_entrada_general =  '".$_REQUEST['id_entrada_general']."' ";
	$rOrden = mysql_query($qOrden);
	
		
	$qRepesada	=	"INSERT INTO repesadas (id_entrada_general, desperdicio1, desperdicio2, diferencia1, diferencia2) VALUES('{$_REQUEST[id_entrada_general]}','{$_POST[total_desperdicio_hd_s]}','{$_POST[total_desperdicio_bd_s]}' ,'{$_POST[diferencia_hd]}','{$_POST[diferencia_bd]}')";
	$rRepesada	=	mysql_query($qRepesada);
	
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=listar&exito\';</script>';

}



if(isset($_POST['guardar'])  && isset($_REQUEST['id_bolseo']))
{
 	$qActualizaBolseo = "UPDATE bolseo SET dtira = '".$_REQUEST['tdt']."' , dtroquel = '".$_REQUEST['tdtr']."' , segundas = '".$_REQUEST['tse']."', actualizado = '1' WHERE id_bolseo = '".$_REQUEST['id_bolseo']."'";
	$rActualizaBolseo = mysql_query($qActualizaBolseo);	
	
		$qRepesada	=	"INSERT INTO repesadas (id_entrada_general, desperdicio1, desperdicio2,desperdicio3, diferencia1, diferencia2, diferencia3, bolseo) VALUES('{$_REQUEST[id_bolseo]}','{$_POST[tdt_s]}','{$_POST[tdtr_s]}','{$_POST[tse_s]}' ,'{$_POST[diferencia_tdt]}','{$_POST[diferencia_tdtr]}','{$_POST[diferencia_tse]}','1')";
	$rRepesada	=	mysql_query($qRepesada);
	
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=listar&exito\';</script>';
	
}

if(!empty($_GET['accion']))
{
	$listar		=	($_GET['accion']=="listar")?true:false;
	$opcion		=	($_GET['accion']=="opcion")?true:false;
	
	if((!empty($_GET['id_reporte']) && is_numeric($_GET['id_reporte'])) || (!empty($_GET['id_bolseo']) && is_numeric($_GET['id_bolseo'])) )
	{
		$actualizarExtruder	=	($_GET['accion']=="actualizarExtruder")?true:false;
		$actualizarImpresion	=	($_GET['accion']=="actualizarImpresion")?true:false;
		$actualizarbolseo	=	($_GET['accion']=="actualizarbolseo")?true:false;
	}
	if($actualizarExtruder)
	{	
		$qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
								"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE (id_entrada_general={$_REQUEST[id_reporte]})";

		$rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");
		$dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral);

		$qOrdenProduccion	=	"SELECT * FROM orden_produccion WHERE (id_entrada_general = {$dEntradaGeneral[id_entrada_general]})";
		$rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");
		$dOrdenProduccion	=	mysql_fetch_assoc($rOrdenProduccion);

		$qImpresion	= "SELECT * FROM impresion WHERE (id_entrada_general = {$dEntradaGeneral[id_entrada_general]})";
		$rImpresion = mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
		$dImpresion = mysql_fetch_assoc($rImpresion);
	}
	if($actualizarImpresion)
	{	
		$qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
								"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE (id_entrada_general={$_REQUEST[id_reporte]})";

		$rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");
		$dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral);

		$qImpresion	= "SELECT * FROM impresion WHERE (id_entrada_general = {$dEntradaGeneral[id_entrada_general]})";
		$rImpresion = mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
		$dImpresion = mysql_fetch_assoc($rImpresion);
	}

	if($actualizarbolseo)
	{	
		
		$qActualizaBolseo	=	"SELECT * FROM bolseo INNER JOIN supervisor ".
								"ON bolseo.id_supervisor = supervisor.id_supervisor WHERE (id_bolseo={$_REQUEST[id_bolseo]})";
		$rActualizaBolseo	=	mysql_query($qActualizaBolseo) OR die("<p>$qActualizaBolseo</p><p>".mysql_error()."</p>");
		$dActualizaBolseo	=	mysql_fetch_assoc($rActualizaBolseo);
		
	}

}
?>
	<script type="text/javascript" src="mootools.js"></script>
    <script type="text/javascript">
	
	function diferencia(){
	
	Var1 = parseFloat(document.form.desperdicio_tira_s.value) - parseFloat(document.form.desperdicio_tira.value);
	document.form.diferencia_tira.value	=	parseFloat(Var1);
		
	}

	function diferencia2(){
	
	Var1 = parseFloat(document.form.desperdicio_duro_s.value) - parseFloat(document.form.desperdicio_duro.value);
	document.form.diferencia_duro.value	=	parseFloat(Var1);
		
	}
		function diferencia_impr(){
	
	Var1 = parseFloat(document.form.total_desperdicio_hd_s.value) - parseFloat(document.form.total_desperdicio_hd.value);
	document.form.diferencia_hd.value	=	parseFloat(Var1);
		
	}

	function diferencia_impr2(){
	
	Var1 = parseFloat(document.form.total_desperdicio_bd_s.value) - parseFloat(document.form.total_desperdicio_bd.value);
	document.form.diferencia_bd.value	=	parseFloat(Var1);
		
	}
	
	function diferenciaB(){
	
	Var1 = parseFloat(document.form.tdt_s.value) - parseFloat(document.form.tdt.value);
	document.form.diferencia_tdt.value	=	parseFloat(Var1);

	}

	function diferenciaB2(){
	
	Var1 = parseFloat(document.form.tdtr_s.value) - parseFloat(document.form.tdtr.value);
	document.form.diferencia_tdtr.value	=	parseFloat(Var1);
		
	}

	function diferenciaB3(){
	
	Var1 = parseFloat(document.form.tse_s.value) - parseFloat(document.form.tse.value);
	document.form.diferencia_tse.value	=	parseFloat(Var1);
		
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
<? if($actualizarExtruder){ ?>

<form name="form" id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
	<div id="datosgenerales" style="background-color:#FFFFFF;">
		<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dEntradaGeneral['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?>" id="fecha" class="datosgenerales" readonly /><br />
                <label for="fecha">Turno</label>
                <input type="text" class="datosgenerales"  value="<? 
			if($dEntradaGeneral['turno'] == 1) echo "Matutino";
			if($dEntradaGeneral['turno'] == 2) echo "Vespertino";
			if($dEntradaGeneral['turno'] == 3) echo "Nocturno";
			?>" readonly	/>
            <input type="hidden" name="turno" id="turno" value="<?=$dEntradaGeneral['turno']?>">
             <br />
             <input type="hidden" name="id_supervisor"       value="<?=$dEntradaGeneral['id_supervisor']?>" />
             <input type="hidden" name="id_entrada_general"  value="<?=$dEntradaGeneral['id_entrada_general']?>" />
             <input type="hidden" name="id_impresion"        value="<?=$dImpresion['id_impresion']?>" />
		</p>
			<br>      </div><br>

                <div align="center">
      <b>EXTRUDER DE ALTA - NAVE 2</b>
      <br><br>
      </div>
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
						<td><strong>Totales sin repesar</strong></td>
						<td><input type="text" id="total" name="total" value="<?=number_format($dOrdenProduccion['total'],2,"."," ");?>" class="total" readonly /></td>
						<td><input type="text" id="desperdicio_tira_s" name="desperdicio_tira_s" value="<?=number_format($dOrdenProduccion['desperdicio_tira'],2,"."," ");?>" class="total" readonly /></td>
						<td><input type="text" id="desperdicio_duro_s" name="desperdicio_duro_s" value="<?=number_format($dOrdenProduccion['desperdicio_duro'],2,"."," ")?>" class="total" readonly /></td>
					</tr>
					<tr>
						<td><strong>Repesada</strong></td>
						<td>&nbsp;</td>
						<td><input type="text" id="desperdicio_tira" name="desperdicio_tira" value="" onChange="javascript: diferencia();" class="total" /></td>
						<td><input type="text" id="desperdicio_duro" name="desperdicio_duro" value="" onChange="javascript: diferencia2();" class="total"  /></td>
					</tr>			
					<tr>
						<td><strong>DIFERENCIA</strong></td>
						<td>&nbsp;</td>
						<td><input type="text" id="diferencia_tira" name="diferencia_tira" value="" class="total" /></td>
						<td><input type="text" id="diferencia_duro" name="diferencia_duro" value="" class="total"  /></td>
					</tr>           
            </tbody>
        </table>
        </div>
      <br>
      <div align="center"></div>    
      <div id="datosgenerales" style="background-color:#FFFFFF;">
        <table align="center">
        <tbody>
	       

<tr>
           		  <input type="hidden" name="extruder" id="extruder" value="extruder" >
                  <td width="214" align="right" colspan="5"><input type="submit" name="guardar" id="Guardar" value="Guardar" /></td>
                </tr>
				</tbody>
        </table>
        </div>   
             
    </div>    
 </div>
 </div>
</form>

<? }  if($actualizarImpresion){ ?>

<form name="form" id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
	<div id="datosgenerales" style="background-color:#FFFFFF;">
		<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dEntradaGeneral['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?>" id="fecha" class="datosgenerales" readonly /><br />
                <label for="fecha">Turno</label>
                <input type="text" class="datosgenerales"  value="<? 
			if($dEntradaGeneral['turno'] == 1) echo "Matutino";
			if($dEntradaGeneral['turno'] == 2) echo "Vespertino";
			if($dEntradaGeneral['turno'] == 3) echo "Nocturno";
			?>" readonly	/>
            <input type="hidden" name="turno" id="turno" value="<?=$dEntradaGeneral['turno']?>">
             <br />
             <input type="hidden" name="id_supervisor"       value="<?=$dEntradaGeneral['id_supervisor']?>" />
             <input type="hidden" name="id_entrada_general"  value="<?=$dEntradaGeneral['id_entrada_general']?>" />
             <input type="hidden" name="id_impresion"        value="<?=$dImpresion['id_impresion']?>" />
		</p>
			<br>      </div><br>

                <div align="center"><br>
      </div>
      <div align="center">
      <b>IMPRESION Y LINEA DE IMPRESION - NAVE 2</b><br>
      <br>
      </div>    <div id="datosgenerales" style="background-color:#FFFFFF;">
        <table align="center">
        <tbody>
					<tr>
                    	<td></td>
						<td>Total HD</td>
						<td>Total BD</td>
						<td>D. Hd</td>
						<td>D. Bd</td>
					</tr>        
					<tr>
						<td><strong>Totales</strong></td>
						<td><input type="text" id="total_impr_hd" name="total_impr_hd" value="<?=$dImpresion['total_hd']?>" class="total"  readonly /></td>
						<td><input type="text" id="total_impr_bd" name="total_impr_bd" value="<?=$dImpresion['total_bd']?>" class="total"  readonly /></td>
						<td><input type="text" id="total_impr_tira" name="total_desperdicio_hd_s" value="<?=$dImpresion['desperdicio_hd']?>" class="total" readonly /></td>
						<td><input type="text" id="total_impr_duro" name="total_desperdicio_bd_s" value="<?=$dImpresion['desperdicio_bd']?>" class="total" readonly /></td>
					</tr>
					<tr>
						<td><strong>Repesada</strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><input type="text" id="total_desperdicio_hd" name="total_desperdicio_hd" value="" onChange="javascript: diferencia_impr();" class="total" /></td>
						<td><input type="text" id="total_desperdicio_bd" name="total_desperdicio_bd" value="" onChange="javascript: diferencia_impr2();" class="total"  /></td>
					</tr>			
					<tr>
						<td><strong>DIFERENCIA</strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><input type="text" id="diferencia_tira" name="diferencia_hd" value="" class="total" /></td>
						<td><input type="text" id="diferencia_duro" name="diferencia_bd" value="" class="total"  /></td>
					</tr>       
                  <tr>
           		  <input type="hidden" name="impresion" id="impresion" value="impresion" >
                  <td width="214" align="right" colspan="5"><input type="submit" name="guardar" id="Guardar" value="Guardar" /></td>
                </tr>
		  </tbody>
        </table>
        </div>   
             
    </div>    
 </div>
 </div>
</form>
<? } ?>

<? if($actualizarbolseo){ ?>
<form name="form" id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
	<div id="datosgenerales" style="background-color:#FFFFFF;">
		<p>
				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$dActualizaBolseo['nombre']?>" readonly="readonly" class="datosgenerales"/><br />
				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dActualizaBolseo['fecha'])?>" id="fecha" class="datosgenerales" readonly /><br />
                <label for="fecha">Turno</label>
                <input type="text" class="datosgenerales"  value="<? 
				if($dActualizaBolseo['turno'] == 1) echo "Matutino";
				if($dActualizaBolseo['turno'] == 2) echo "Vespertino";
				if($dActualizaBolseo['turno'] == 3) echo "Nocturno";
				?>" readonly	/>
				<input type="hidden" name="turno" id="turno" value="<?=$dActualizaBolseo['turno']?>">
				 <br />
				 <input type="hidden" name="id_supervisor" value="<?=$dActualizaBolseo['id_supervisor']?>" />
				 <input type="hidden" name="id_bolseo"     value="<?=$dActualizaBolseo['id_bolseo']?>" />
                </p>
                <br>      </div><br>

                <div align="center">
      <b>BOLSEO</b>
      <br><br>
      </div>            
       
             <div id="datosgenerales" style="background-color:#FFFFFF;">
         <table align="center">
         
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
						<td><input type="text" id="tkg"  name="tkg"  value="<?=$dActualizaBolseo['kilogramos']?>" class="total" readonly="readonly" /></td>
						<td><input type="text" id="tml"  name="tml"  value="<?=$dActualizaBolseo['millares']?>"   class="total" readonly="readonly" /></td>
						<td><input type="text" id="tdt_s"  name="tdt_s"  value="<?=$dActualizaBolseo['dtira']?>"      class="total" readonly  /></td>
						<td><input type="text" id="tdtr_s" name="tdtr_s" value="<?=$dActualizaBolseo['dtroquel']?>"   class="total" readonly /></td>
						<td><input type="text" id="tse_s"  name="tse_s"  value="<?=$dActualizaBolseo['segundas']?>"   class="total" readonly  /></td>
					</tr>
					<tr>
						<td><strong>Repesada</strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><input type="text" id="tdt"  name="tdt"  value=""   class="total" onChange="javascript: diferenciaB();"  /></td>
						<td><input type="text" id="tdtr" name="tdtr" value=""   class="total" onChange="javascript: diferenciaB2();"  /></td>
						<td><input type="text" id="tse"  name="tse"  value=""   class="total" onChange="javascript: diferenciaB3();"  /></td>
					</tr>
					<tr>
						<td><strong>DIFERENCIA</strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><input type="text" id="diferencia_tdt"  name="diferencia_tdt"  value=""   class="total" readonly  /></td>
						<td><input type="text" id="diferencia_tdtr" name="diferencia_tdtr" value=""   class="total" readonly /></td>
						<td><input type="text" id="diferencia_tse"  name="diferencia_tse"  value=""   class="total" readonly  /></td>
					</tr>                  
                    <tr>
           		  <input type="hidden" name="extruder" id="extruder" value="extruder" >
                  <td width="214" align="right" colspan="6"><input type="submit" name="guardar" id="Guardar" value="Guardar" /></td>
                </tr>
				
				</tbody>
        </table>                    
                  
        </div>   
             
    </div>    
 </div>
 </div>
</form>
<? }if($opcion){

?>
<script language="javascript">
function cambia(valor)
{
	location.href='admin_supervisor.php?seccion=<?=$_REQUEST['seccion']?>&accion=listar&'+valor;
}
</script>
<table width="435" border="0" align="center" cellpadding="2" cellspacing="4">
  <tr>
    <td colspan="2"><table width="311" border="0" align="center" cellpadding="2" cellspacing="4">
      <tr>
        <td width="109">Elija una opcion</td>
        <td width="182">
        <select name="tipo_repesada" class="observaciones" id="tipo_repesada" onChange="cambia(this.value);">
          <option value=""></option>	
          <option value="extruder">Extruder</option>
          <option value="impresion">Impresion</option>
          <option value="bolseo">Bolseo</option>
        </select></td>
      </tr>

    </table></td>
  </tr>
</table>
<? } if($listar) { ?>
<form id="form" action="admin.php?seccion=6" method="post">
<div id="container">
		<div id="content">
        <? if( $_SESSION['area'] == 1  && isset($_GET['extruder'])){ ?>
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#extruder">REPESADA DE SEGUNDAS Y DESPERDICIOS DE EXTRUDER</a></h3>
			<div>

            <div style="background-color:#FFFFFF;" class="navcontainer">
            <table>
        		<tr>
            		<td colspan="5">
            	<p>Listado de segundas y desperdicios de Extruder.</p>
               		</td>
                </tr>
                <tr>
                	<td style="color:#000000"><b>Fecha</b></td>
                	<td style="color:#000000"><b>Nombre</b></td>
                	<td style="color:#000000"><b>Rol</b></td>
				</tr>		
                <?php
				$qGenerales		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno,id_entrada_general FROM entrada_general".
									" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE actualizado = '0' AND impresion = '0' AND entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."' ORDER BY fecha ASC";
								
				$rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");
				$sGenerales		=	array();
								
				while($dGenerales	=	mysql_fetch_assoc($rGenerales)){ ?>
				<tr onMouseOver="this.style.backgroundColor='#CCC'"	onmouseout="this.style.backgroundColor='#EAEAEA'" style="cursor:default; background-color:#EAEAEA">
                      <td width="123"><?=$dGenerales['fecha']?></td>
                      <td width="252"><?=$dGenerales['nombre']?></td>
                      <td width="290"><?=$dGenerales['turno']?></td>
                      <td ><a href="admin_supervisor.php?seccion=7&accion=actualizarExtruder&id_reporte=<?=$dGenerales['id_entrada_general']?>&extrimpres">Reconteo</a></td>
              </tr>
                <?php } ?>
 				<? if(isset($_GET['exito'])){?>
                    <li><center><font color="#FF0000">Registro Actualizado.</font></center></li>
                <? } ?>        				</ul>
                <br />
              </table>
            </div>
		</div>
        
        <? }
		
		  if( $_SESSION['area2'] == 1  && isset($_GET['impresion'])){ ?>
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#extruder">REPESADA DE SEGUNDAS Y DESPERDICIOS DE IMPRESION</a></h3>
		<div>
            <div style="background-color:#FFFFFF;" class="navcontainer">
            <table>
        		<tr>
            		<td colspan="5">
            	<p>Listado de segundas y desperdicios de impresi&oacute;n.</p>
               		</td>
                </tr>
                <tr>
                	<td style="color:#000000"><b>Fecha</b></td>
                	<td style="color:#000000"><b>Nombre</b></td>
                	<td style="color:#000000"><b>Rol</b></td>
				</tr>		
                <?php

				$qGenerales		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno,id_entrada_general FROM entrada_general".
									" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE actualizado = '0' AND impresion = '1' ORDER BY entrada_general.fecha, entrada_general.turno DESC";
								
				$rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");
				$sGenerales		=	array();
								
				while($dGenerales	=	mysql_fetch_assoc($rGenerales)){ ?>
                    <tr onMouseOver="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#EAEAEA'" 
	style="cursor:default; background-color:#EAEAEA">
                      <td width="123"><?=$dGenerales['fecha']?></td>
                      <td width="252"><?=$dGenerales['nombre']?></td>
                      <td width="290"><?=$dGenerales['turno']?></td>
                      <td width="116"><a href="admin_supervisor.php?seccion=7&accion=actualizarImpresion&id_reporte=<?=$dGenerales['id_entrada_general']?>&extrimpres">Reconteo</a></td>
              </tr>    
                <?php } ?>
 				<? if(isset($_GET['exito'])){?>
                    <li><center><font color="#FF0000">Registro Actualizado.</font></center></li>
                <? } ?>        				</ul>
                <br />
              </table>
            </div>
     
		</div>
        
        <? } if($_SESSION['area3'] == 1 || isset($_GET['bolseo'])){ ?>
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#bolseo">REPESADA DE SEGUNDAS Y DESPERDICIOS DE	 BOLSEO</a></h3>
		<div>
            <div style="background-color:#FFFFFF;" class="navcontainer">
            <table>
        		<tr>
            		<td colspan="5">
            	<p>Listado de segundas y desperdicios de Bolseo.</p>
               		</td>
                </tr>
                <tr>
                	<td style="color:#000000"><b>Fecha</b></td>
                	<td style="color:#000000"><b>Nombre</b></td>
                	<td style="color:#000000"><b>Rol</b></td>
				</tr>		
 		        <?php	
				$qBolseo		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)) AS fecha, supervisor.nombre, turno,id_bolseo FROM bolseo".
									" LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor WHERE actualizado = '0'  ORDER BY fecha ASC";
				$rBolseo		=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");
				$sBolseo		=	array();
				
				while($dBolseo	=	mysql_fetch_assoc($rBolseo)){ ?>
                <tr onMouseOver="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#EAEAEA'" 
	style="cursor:default; background-color:#EAEAEA">
                	<td><?=$dBolseo['fecha']?></td>
                    <td><?=$dBolseo['nombre']?></td>
                    <td><?=$dBolseo['turno']?></td>
                    <td><a href="admin_supervisor.php?seccion=7&accion=actualizarbolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>">Reconteo</a></td>
                </tr>
				<?php } ?>      
                <? if(isset($_GET['exito'])){?>
                    <li>Registro Actualizado.</li>
                <? } ?>         
                </ul>
                <br />
              </table>
            </div>
		</div>
        <? } ?>
    </div>
</div>
</form>
<? } ?>

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