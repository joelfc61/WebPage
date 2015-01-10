<?php 
include('libs/conectar.php');
if(isset($_REQUEST['tipo']))
{
	
	if( $_REQUEST['tipo'] == "1" && isset($_REQUEST['id_reporte']) && is_numeric($_REQUEST['id_reporte']))
		include("reportes/extruder.php");
	if( $_REQUEST['tipo'] == "3" && isset($_REQUEST['id_reporte']) && is_numeric($_REQUEST['id_reporte']))
		include("reportes/impresion.php");
	if( $_REQUEST['tipo'] == "2" && isset($_REQUEST['id_reporte']) && is_numeric($_REQUEST['id_reporte']))
		include("reportes/bolseo.php");	
	else if(isset($_REQUEST['m']) && isset($_REQUEST['a']) && is_numeric($_REQUEST['m']) && is_numeric($_REQUEST['a']))
	{
		if( isset($_REQUEST['desperdicio']))
			include("reportes/desperdicios_generales.php");
		if( isset($_REQUEST['metas']) && !empty($_REQUEST['y']) )
			include("reportes/metas_generales.php");
	}
	exit();
}
if(!$_REQUEST['buscar'])
$busquedaURL	=	"&desdeB=".$_REQUEST['desde']."&hastaB=".$_REQUEST['hasta']."&turnoB=".$_REQUEST['turno']."&rolB=".$_REQUEST['rol']."&id_supervisorB=".$_REQUEST['id_supervisor']."&areaB=".$_REQUEST['area']."";
else
$busquedaURL	=	"&buscar=1&desdeB=".$_REQUEST['desde']."&hastaB=".$_REQUEST['hasta']."&turnoB=".$_REQUEST['turno']."&rolB=".$_REQUEST['rol']."&id_supervisorB=".$_REQUEST['id_supervisor']."&areaB=".$_REQUEST['area']."";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" version="-//W3C//DTD XHTML 1.1//EN" xml:lang="en">
<head>
	<script type="text/javascript" src="mootools.js"></script>
<script type="text/javascript" src="DatePicker.js"></script>

	<!--<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />-->
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Reportes</title>
<style type="text/css" media="screen">@import 'DatePicker.css';</style>
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

<body ><form id="buscar" action="admin.php?seccion=30" method="POST" >
<div id="container" >
	
		<div id="content">
                    <table width="100%"  border="0" cellpadding="0" cellspacing="0" align="center">
                      <tr>
                         <td width="224" class="contenidos">Desde: 
                        <div style="float:left;"><input id="desde" name="desde" type="text" class="fecha" alt="{
			dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 	'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<? if(isset($_REQUEST['desde'])) echo $_REQUEST['desde']; else echo date('d-m-Y')?>" /></div></td>
        
<td width="309" class="contenidos">Supervisor: <br />
            <select name="id_supervisor">
            <option value="0">Todos</option>
    	<? 
		 	$qSupervisor	=	"SELECT * FROM supervisor ORDER BY nombre";
			$rSupervisor	=	mysql_query($qSupervisor);
			while($dSupervisor	=	mysql_fetch_assoc($rSupervisor)){
		?>
    		<option value="<?=$dSupervisor['id_supervisor']?>" <? if($_REQUEST['id_supervisor'] == $dSupervisor['id_supervisor']) echo "selected";?>><?=$dSupervisor['nombre']?></option>
		<? } ?>
    </select></td>
                        <td width="132" class="copntenidos">Turno:<br />
                         <select name="turno">
    		<option value="0" <? if($_REQUEST['turno'] == 0 ) echo "selected";?>>Todos</option>
    		<option value="1" <? if($_REQUEST['turno'] == 1 ) echo "selected";?>>Matutino</option>
    		<option value="2" <? if($_REQUEST['turno'] == 2 ) echo "selected";?>>Vespertino</option>
    		<option value="3" <? if($_REQUEST['turno'] == 3 ) echo "selected";?>>Nocturno</option>
    </select></td>
                      </tr>
                      <tr>
                        <td>Hasta: <div style="float:left;">
  <input id="hasta" name="hasta" type="text" class="fecha"  alt="{
		dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<? if(isset($_REQUEST['hasta'])) echo $_REQUEST['hasta']; else echo date('d-m-Y');?>" /></div></td>
                        <td class="contenidos">Area:<br />
                          <select name="area">
                          	<option value="0" <? if($_REQUEST['area'] == 0 ) echo "selected";?>>Todas</option>
                            <option value="1" <? if($_REQUEST['area'] == 1 ) echo "selected";?>>Extruder</option>
                            <option value="2" <? if($_REQUEST['area'] == 2 ) echo "selected";?>>Bolseo</option>
                            <option value="3" <? if($_REQUEST['area'] == 3 ) echo "selected";?>>Impresion</option>
                            <option value="4" <? if($_REQUEST['area'] == 4 ) echo "selected";?>>Empaque</option>
                          </select></td>
         <td class="copntenidos">Rol:<br />
         <select name="rol" id="rol">
         <option value="0">Todos</option>
		<? for($a=1; $a <= 6 ; $a++){ ?>
        <option value="<?=$a?>" <? if($_REQUEST['rol']== $a) echo "selected";?> ><?=$a?></option>
        <? } ?>
        </select></td>
                        <td width="281" align="center">
                        <input type="submit" class="boton">
                        <input type="hidden" name="buscar" value="1" >                        </td>
                      </tr>
                    </table>
		</div>
	</div>
<? if(!isset($_REQUEST['buscar']) ){ 
    
    



			$anho 	= 	date('Y');
				$mes	=	date('m');
				
			 	$ultimo_dia = UltimoDia($anho,$mes);
	
				$hasta	= 	date('Y-m-d');
				$nMes	=	date('m');
				$nAnho	=	date('Y');
				$dia	=	date('d');
				$verificaDia	=	$dia - 7;
				
					if($verificaDia < 1 ){
					$temporal	=	$dia - 7;
					$dia		=	$ultimo_dia + $temporal;
						if($nMes	==	1){
							$nuevoMes	=	7;
							$nAnho	=	$nAnho - 1;
						}	
						else{
							$nuevoMes	=	$nMes - 1;
							$nAnho	=	$nAnho;
						}


					$desde	=	$nAnho.'-'.$nuevoMes.'-'.$dia;
					} if($verificaDia >= 1 ){
					$desde	=	date('Y-m-').$verificaDia;
					
					}

		$qGenerales1		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general, supervisor.rol FROM entrada_general".
							" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor  WHERE entrada_general.fecha >= '".$desde."' AND entrada_general.fecha <= '".$hasta."' AND impresion = '0' AND autorizada = '1' ORDER BY entrada_general.fecha, entrada_general.turno ASC";
		$qGenerales2		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general, supervisor.rol FROM entrada_general".
							" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor  WHERE  entrada_general.fecha >= '".$desde."' AND entrada_general.fecha <= '".$hasta."'  AND impresion = '1' AND autorizada = 1 ORDER BY fecha ASC";

		 $qGenerales3		=	"SELECT fecha, supervisor.nombre, turno,id_bolseo, supervisor.rol FROM bolseo".
							" LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor WHERE  bolseo.fecha >= '".$desde."' AND bolseo.fecha <= '".$hasta."'  AND bolseo.autorizada = 1 ORDER BY fecha, turno ASC";		

$rGenerales1		=	mysql_query($qGenerales1) OR die("<p>$qGenerales1</p><p>".mysql_error()."</p>");
$rGenerales2		=	mysql_query($qGenerales2) OR die("<p>$qGenerales2</p><p>".mysql_error()."</p>");
$rGenerales3		=	mysql_query($qGenerales3) OR die("<p>$qGenerales3</p><p>".mysql_error()."</p>");    
    
 } if(isset($_REQUEST['buscar'])) {

    function FWhere($w) {
	return ($w == '') ? ' WHERE ' : "$w AND ";
}
		
		if($_REQUEST['area'] == 0)
				$tabla = "entrada_general";
				$tabla2 = "bolseo";		

		if($_REQUEST['area'] == 1)
				$tabla = "entrada_general";
		if($_REQUEST['area'] == 3)
				$tabla = "entrada_general";
		if($_REQUEST['area'] == 2)
				$tabla = "bolseo";		
		
		if ($_REQUEST['id_supervisor'] != 0 && $_REQUEST['area'] != 0) 
			$WHERE = Fwhere($WHERE) . " $tabla.id_supervisor =  '".$_REQUEST['id_supervisor']."'";
			
		if ($_REQUEST['id_supervisor'] != 0 && $_REQUEST['area'] == 0) {
			$WHERE = Fwhere($WHERE) . " $tabla.id_supervisor =  '".$_REQUEST['id_supervisor']."'";
			$WHERE2 = Fwhere($WHERE2) . " bolseo.id_supervisor =  '".$_REQUEST['id_supervisor']."'";
			}
			
		if ($_REQUEST['id_supervisor'] == 0 && $_REQUEST['area'] == 0) {
			$WHERE = Fwhere($WHERE) . " $tabla.id_supervisor >  '0'";
			$WHERE2 = Fwhere($WHERE2) . " bolseo.id_supervisor >=  '0'"	;
			}			
			
		if ($_REQUEST['desde'] != '' && $_REQUEST['area'] != 0){
				$desde	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" , $_REQUEST['desde'] );
				$WHERE = Fwhere($WHERE) . " $tabla.fecha >= '".$desde."'";
				
			}
		if ($_REQUEST['hasta'] != '' && $_REQUEST['area'] != 0){ 
			$hasta	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" , $_REQUEST['hasta'] );
			$WHERE = Fwhere($WHERE) . " $tabla.fecha <= '".$hasta."'";
			}
			
			
		if ($_REQUEST['turno'] > 0 && $_REQUEST['area'] != 0){
			$WHERE = Fwhere($WHERE) . " $tabla.turno = '".$_REQUEST['turno']."'";
		}
		if ($_REQUEST['turno'] > 0 && $_REQUEST['area'] == 0){
			$WHERE = Fwhere($WHERE) . " $tabla.turno = '".$_REQUEST['turno']."'";
			$WHERE2 = Fwhere($WHER2E) . " bolseo.turno = '".$_REQUEST['turno']."'";
		}		
		
		if ($_REQUEST['area'] != 0 && $_REQUEST['area'] != 0){ 
			$WHERE = Fwhere($WHERE) . " $tabla.area = '".$_REQUEST['area']."'";
			}
			
			
		if ($_REQUEST['rol'] != 0 && $_REQUEST['area'] != 0){ 
			$WHERE = Fwhere($WHERE) . " $tabla.rol = '".$_REQUEST['rol']."'";
			}
		if ($_REQUEST['rol'] != 0 && $_REQUEST['area'] == 0){ 
			$WHERE = Fwhere($WHERE) . " $tabla.rol = '".$_REQUEST['rol']."'";
			$WHERE2 = Fwhere($WHERE2) . " bolseo.rol = '".$_REQUEST['rol']."'";
			}
			
		if($_REQUEST['area'] == '0'){
		$desde	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" , $_REQUEST['desde'] );
	 	$hasta	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" , $_REQUEST['hasta'] );
		
		$qGenerales1		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general, supervisor.rol FROM entrada_general".
							" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor  $WHERE AND entrada_general.fecha >= '".$desde."' AND entrada_general.fecha <= '".$hasta."' AND impresion = '0' AND autorizada = '1' ORDER BY entrada_general.fecha, entrada_general.turno ASC";
		$qGenerales2		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general, supervisor.rol FROM entrada_general".
							" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor  $WHERE AND entrada_general.fecha >= '".$desde."' AND entrada_general.fecha <= '".$hasta."'  AND impresion = '1' AND autorizada = 1 ORDER BY fecha ASC";

		$qGenerales3		=	"SELECT fecha, supervisor.nombre, turno,id_bolseo, supervisor.rol FROM bolseo".
							" LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor $WHERE2  AND bolseo.fecha >= '".$desde."' AND bolseo.fecha <= '".$hasta."'  AND bolseo.autorizada = 1 ORDER BY fecha, turno ASC";

		}
		
		
		
		if($_REQUEST['area'] == '1' )
		$qGenerales		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general, supervisor.rol FROM entrada_general".
							" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor  $WHERE AND impresion = '0' AND autorizada = 1  ORDER BY fecha, turno ASC";
		if($_REQUEST['area'] == '2')
		$qGenerales		=	"SELECT fecha, supervisor.nombre, turno,id_bolseo, supervisor.rol FROM bolseo".
							" LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor $WHERE AND bolseo.autorizada = 1 AND autorizada = 1 ORDER BY fecha, turno ASC";
		if($_REQUEST['area'] == '3')
		$qGenerales		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general, supervisor.rol FROM entrada_general".
							" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor $WHERE AND impresion = '1' AND autorizada = 1 ORDER BY fecha, turno ASC";

?>

<? 
if($_REQUEST['area'] != '0'){
$rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");
}
if($_REQUEST['area'] == '0' ){
$rGenerales1		=	mysql_query($qGenerales1) OR die("<p>$qGenerales1</p><p>".mysql_error()."</p>");
$rGenerales2		=	mysql_query($qGenerales2) OR die("<p>$qGenerales2</p><p>".mysql_error()."</p>");
$rGenerales3		=	mysql_query($qGenerales3) OR die("<p>$qGenerales3</p><p>".mysql_error()."</p>");

}
		?>
<? } ?>
<table width="100%" border="0" cellpadding="0" cellspacing="3">
  <tr>
    <td colspan="4" style="color:#666666"><b>
      <? if($_REQUEST['area'] != '0' && isset($_REQUEST['buscar']) ){ echo mysql_num_rows($rGenerales);}if($_REQUEST['area'] == '0'  || !isset($_REQUEST['buscar'])){ echo  "Extruder: ".mysql_num_rows($rGenerales1).", "; echo " Impresion: ".mysql_num_rows($rGenerales2).", "; echo "Bolseo: ".mysql_num_rows($rGenerales3).", ";}?>
    </b>&nbsp; Registros encontrados</td>
  </tr>
  <tr >
    <? if($_REQUEST['area'] == 0 || !isset($_REQUEST['buscar'])){ ?><td width="5%"><h3>Area:</h3></td>
    <? } ?>
    <td width="13%"  align="center" ><h3>Fecha</h3></td>
    <td width="28%"  align="center" ><h3>Supervisor</h3></td>
    <td width="7%"  align="center" ><h3>Rol</h3></td>
    <td width="7%"  align="center"><h3>Turno</h3></td>
    <td width="7%" align="center"><h3>&nbsp;</td>
    <td width="9%" colspan="2" ><h3>&nbsp;</h3></td>
  </tr>
  
  <? if($_REQUEST['area'] != 0){
while ($listado = mysql_fetch_row($rGenerales)) {
?>
  <tr
  	class="td"
	onmouseover="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#FFFFFF'" 
	style="cursor:default; background-color:#FFFFFF"
  >
    <td align="center"><b>
     <?=fecha($listado['0']);?>
    </b></td>
    <td><b>
      <?=$listado['1']?>
    </b></td>
    <td align="center"><b>
      <?=$listado['4'];?>
    </b></td>
    <td align="center"><b>
      <?=$listado['2'];?>
    </b></td>
    <td align="center">
<? if($historialMod){ ?>
    <? if($_REQUEST['area'] == '0'  || !isset($_REQUEST['buscar'])){ ?>
    <a href="admin.php?seccion=6&accion=autorizar&extruder&id_reporte=<?=$listado['3']?>&menio=1<?=$busquedaURL?>">Modificar</a>
    <? } ?>
    <? if($_REQUEST['area'] == '1'){ ?>
    <a href="admin.php?seccion=6&accion=autorizar&extruder&id_reporte=<?=$listado['3']?>&menio=1<?=$busquedaURL?>">Modificar</a>
    <? } ?>
    <? if($_REQUEST['area'] == '3'){ ?>
    <a href="admin.php?seccion=6&accion=impresion&id_reporte=<?=$listado['3']?>&menio=1<?=$busquedaURL?>">Modificar</a>
	<? } ?>
    <? if($_REQUEST['area'] == '2'){ ?>
  <a href="admin.php?seccion=6&accion=autorizar_bolseo&id_bolseo=<?=$listado['3']?>&menio=1<?=$busquedaURL?>">Modificar</a>
	<? } ?>
<? } ?>
    </td>  
     <td align="center">
    <? if($_REQUEST['area'] == '1'){ ?>
    	<a href="reportes/extruder.php?tipo=<?=$_REQUEST['area']?>&amp;id_reporte=<?=$listado['3']?>&amp;st00ph=<?=microtime()?>" target="_blank">Imprimir</a>
	<? } ?>
    <? if($_REQUEST['area'] == '3'){ ?>
    	<a href="reportes/impresion.php?tipo=<?=$_REQUEST['area']?>&amp;id_reporte=<?=$listado['3']?>&amp;st00ph=<?=microtime()?>" target="_blank">Imprimir</a>
	<? } ?>
    <? if($_REQUEST['area'] == '2'){ ?>
    	<a href="reportes/bolseo.php?tipo=<?=$_REQUEST['area']?>&amp;id_reporte=<?=$listado['3']?>&amp;st00ph=<?=microtime()?>" target="_blank">Imprimir</a>
	<? } ?>
</td>
  </tr>
  <?
} }
?>


 
<? if($_REQUEST['area'] == 0  || !isset($_REQUEST['buscar'])){ ?>  
  <? 
while ($listado = mysql_fetch_row($rGenerales1)) {
?>
  <tr
  	class="td"
	onmouseover="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#FFFFFF'" 
	style="cursor:default; background-color:#FFFFFF"
  >
  <td align="center">Extr.</td>
    <td align="center"><b>
     <?=fecha($listado['0']);?>
    </b></td>
    <td><b>
      <?=$listado['1']?>
    </b></td>
    <td align="center"><b>
      <?=$listado['4'];?>
    </b></td>
    <td align="center"><b>
      <?=$listado['2'];?>
    </b></td>
    <td align="center">
<? if($historialMod){ ?>
    <a href="admin.php?seccion=6&accion=autorizar&extruder&id_reporte=<?=$listado['3']?>&menio=1<?=$busquedaURL;?>">Modificar</a>
<? } ?>    
    </td>  
    <td align="center">
    	<a href="reportes/extruder.php?tipo=<?=$_REQUEST['area']?>&amp;id_reporte=<?=$listado['3']?>&amp;st00ph=<?=microtime()?>" target="_blank">Imprimir</a>
</td>
  </tr>
  <?
}
?>
  <?
while ($listado1 = mysql_fetch_row($rGenerales2)) {
?>
  <tr
  	class="td"
	onmouseover="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#EAEAEA'" 
	style="cursor:default; background-color:#EAEAEA"
  >
  	<td align="center">Impr.</td>
    <td align="center"><b>
     <?=fecha($listado1['0']);?>
    </b></td>
    <td><b>
      <?=$listado1['1']?>
    </b></td>
    <td align="center"><b>
      <?=$listado1['4'];?>
    </b></td>
    <td align="center"><b>
      <?=$listado1['2'];?>
    </b></td>
    <td align="center">
<? if($historialMod){ ?>
    <a href="admin.php?seccion=6&accion=impresion&id_reporte=<?=$listado1['3']?>&menio=1<?=$busquedaURL?>">Modificar</a>
<? } ?></td>  

     <td align="center">
    	<a href="reportes/impresion.php?tipo=<?=$_REQUEST['area']?>&amp;id_reporte=<?=$listado1['3']?>&amp;st00ph=<?=microtime()?>" target="_blank">Imprimir</a>
</td>
  </tr>
  <?
}
?>
  <?
while ($listado2 = mysql_fetch_row($rGenerales3)) {
?>
  <tr
  	class="td"
	onmouseover="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#FFFFFF'" 
	style="cursor:default; background-color:#FFFFFF"
  >
  	<td align="center">Bol.</td>
    <td align="center"><b>
     <?=fecha($listado2['0']);?>
    </b></td>
    <td><b>
      <?=$listado2['1']?>
    </b></td>
    <td align="center"><b>
      <?=$listado2['4'];?>
    </b></td>
    <td align="center"><b>
      <?=$listado2['2'];?>
    </b></td>
    <td align="center">
<? if($historialMod){?>
  <a href="admin.php?seccion=6&accion=autorizar_bolseo&bolseo&id_bolseo=<?=$listado2['3']?>&menio=1<?=$busquedaURL?>">Modificar</a>
<? } ?>
</td>  
     <td align="center">
    	<a href="reportes/bolseo.php?tipo=<?=$_REQUEST['area']?>&amp;id_reporte=<?=$listado2['3']?>&amp;st00ph=<?=microtime()?>" target="_blank">Imprimir</a>
</td>
  </tr>
  <?
}
?>



<tr style="background-color:#FF9900"></tr>
</table>
<? } ?>
</div>
</form>
</body></html>
