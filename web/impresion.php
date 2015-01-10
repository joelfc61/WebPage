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

function pDebug($str){
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

if(isset($_POST['guardar'])){
	
	/* Entrada general para referencias */
	
	$_POST['fecha']	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);
	
	
	$qGeneral	=	"UPDATE entrada_general SET id_supervisor = '{$_POST[id_supervisor]}', fecha = '{$_POST[fecha]}', turno = '{$_POST[turno]}', /*autorizada = '0',*/ area = '{$_POST[area]}' , actualizado = '{$_POST[repe]}', repesada = '{$_POST[repe]}' ,impresion = '1', rol = '{$_POST[rol]}' WHERE id_entrada_general = '{$_POST[id_entrada_general]}'";	
	pDebug($qGeneral);
	$rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");
	/*
		Usaremos este ID para referenciar en las tres tablas:
		- Orden_produccion = extruder
		- Impresion / Línea Impresión
		  (Estas últimas se diferencían en el campo linea_impresión (1 = verdadero, 0 = falso)
	*/
	$ID_GENERAL		=	$_POST['id_entrada_general'];
	$ID_IMPRESION	=	$_POST['id_impresion'];
	/* Comienza Impresión */
	
	$nMaquinas	=	sizeof($_POST['codigos2']);
	
	$qImpresion		=	"UPDATE impresion SET id_entrada_general = '$ID_GENERAL' , total_hd = '{$_POST[total_impr_hd]}' , total_bd = '{$_POST[total_impr_bd]}', desperdicio_hd = '{$_POST[total_desperdicio_hd]}', desperdicio_bd = '{$_POST[total_desperdicio_bd]}', observaciones = '{$_POST[observaciones_impr]}', k_h = '{$_POST[k_h]}' WHERE id_impresion = '".$ID_IMPRESION."' ";	
	pDebug($qImpresion);
	$rImpresion		=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
	
	for($i=0;$i<$nMaquinas;$i++)
	{
		$sufijo				=	$_POST['codigos2'][$i];
		$subtotal			=	floatval($_POST['subtotal_impr'.$sufijo]);
		$subtotalbd			=	floatval($_POST['subtotalbd_impr'.$sufijo]);
		$id_maquina			=	intval($_POST['id_maquina2_'.$sufijo]);
		$id_operador		=	intval($_POST['id_operador2_'.$sufijo]);

		/* La segunda entrada es para impresión y mandamos linea_impresion = 0  */
		$qImpresion			=	"INSERT INTO resumen_maquina_im (id_impresion, id_maquina, id_operador, linea_impresion, subtotal, subtotal_bd) VALUES ".
								"('$ID_IMPRESION','$id_maquina','$id_operador','0','$subtotal','$subtotalbd	')";
		pDebug($qImpresion);
		
		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
		$ID_RES_MAQUINAIM	=	mysql_insert_id();
		
		$nEntradas			=	sizeof($_POST["ot_impresion".$sufijo]);
		$qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_im (id_resumen_maquina_im,orden_trabajo,kilogramos,bd) VALUES ";
		
		$flag = false;
		for($j=0;$j<$nEntradas;$j++)
		{
			$ot_impresion		=	$_POST["ot_impresion".$sufijo][$j];
			$kg_impresion		=	$_POST["kg_impresion".$sufijo][$j];
			$bd_impresion		=	$_POST["bd_impresion".$sufijo][$j];
			/* Todos amamos a Meño ahora por mandar campos en blanco por segunda vez Woohoo! */
			if(empty($ot_impresion) && empty($kg_impresion))
				continue;
			/* Esto sitácticamente puede ir en una línea, pero extrañamente PHP tiene pedos con las pilas con asignaciones contiguas */
			$qDetalleResumen	.=	($j>0)?",":"";
			$qDetalleResumen	.= "('$ID_RES_MAQUINAIM','$ot_impresion','$kg_impresion','$bd_impresion')";
			$flag = true;
		}
		if($flag == true){
		pDebug($qDetalleResumen);
		$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
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
		$id_operador		=	intval($_POST['id_operador3_'.$sufijo]);

		
		/* La segunda entrada es para líneas de impresión y mandamos linea_impresion = 1  */
		$qImpresion			=	"INSERT INTO resumen_maquina_im (id_impresion, id_maquina, id_operador, linea_impresion, subtotal, subtotal_bd) VALUES ".
								"('$ID_IMPRESION','$id_maquina','$id_operador','1','$subtotal','$subtotalbd')";
		pDebug($qImpresion);
		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");
		$ID_RES_MAQUINAIM	=	mysql_insert_id();
		
		$nEntradas			=	sizeof($_POST["ot_limpresion".$sufijo]);
		$qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_im (id_resumen_maquina_im,orden_trabajo,kilogramos,bd) VALUES ";
		
		$flag = false;
		for($j=0;$j<$nEntradas;$j++)
		{
			$ot_impresion		=	$_POST["ot_limpresion".$sufijo][$j];
			$kg_impresion		=	$_POST["kg_limpresion".$sufijo][$j];
			$bd_impresion		=	$_POST["bd_limpresion".$sufijo][$j];
			if(empty($ot_impresion) && empty($kg_impresion))
				continue;
			/* Esto sitácticamente puede ir en una línea, pero extrañamente PHP tiene pedos con las pilas con asignaciones contiguas */
			$qDetalleResumen	.=	($j>0)?",":" ";
			$qDetalleResumen	.= "('$ID_RES_MAQUINAIM','$ot_impresion','$kg_impresion','$bd_impresion')";
			$flag = true;
		}
		if($flag == true){
		pDebug($qDetalleResumen);
		$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
		}

	}
	
	// -- Termina parte de Impresión 

	pDebug("Terminado");
	pDebug("Comienza volcado de la petición");
	
 	if(isset($_SESSION['admindolfra']) ){ 
		echo '<script language="javascript">location.href=\'admin.php?seccion=20&accion=metas&id_entrada_general='.$ID_IMPRESION.'&turno='.$_POST['turno'].'\';</script>';
	}
	if(!isset($_SESSION['admindolfra']) ){ 
		echo '<script language="javascript">location.href=\'admin_supervisor.php?seccion=21&accion=metas&id_entrada_general='.$ID_IMPRESION.'&turno='.$_POST['turno'].'\';</script>';
	}    
	
}


if(!empty($_GET['accion']))
{
	$metas			= ($_GET['accion']=="metas"	)?true:false;
	$nuevo		= ($_GET['accion']=="nuevo"	)?true:false;
	$listar		= ($_GET['accion']=="listar")?true:false;
	$registrado		= ($_GET['accion']=="registrado")?true:false;
	$supervisor	= ($_GET['accion']=="supervisor")?true:false;
	$nuevaEntrada 	= ($_GET['accion']=="nuevaentrada")?true:false;
	
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
	$nEntradas	=	1;
	
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
<script language="javascript" type="text/javascript">
function borrarElemento(x)
    {
        var obj = document.getElementById("limprtext"+x);
        obj.removeChild(obj.lastChild);
    }
	
function mostrar(f,id)
{
    
	check=document.getElementById(id);
	div=document.getElementById(f);
	if(check.checked==true)
	 div.style.display="block";
	else
	 div.style.display="none";
}

</script>	
<? if($supervisor){?>
<form name="super" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=nuevo&id=<?=$_REQUEST['id']?>" id="super" method="post" >
<br />
<br />
<table width="404"  align="center">
	<? if(isset($_SESSION['id_admin'])){?>
	<tr>
		<td width="157" align="right">Elija un Supervisor:</td>
		<td width="235"><select name="id_supervisor" id="supervisor">
    		<? 
						$qSupervisor = "SELECT * FROM supervisor WHERE area2 = 1 ORDER BY nombre ASC;";
						$rSupervisor = mysql_query($qSupervisor);
				
						while($dSupervisor = mysql_fetch_assoc($rSupervisor)){?>
    						<option value="<?=$dSupervisor['id_supervisor']?>"><?=$dSupervisor['nombre']?></option>
            <? } ?>
       					</select></td>
    					<input type="hidden" name="admindolfra">
                       
	</tr>
   <?  } ?>
    <tr>
    	<td align="right">Turno</td>
<?		
 $dias_diferencia = '0';

 $fecha			= date('d/m/Y');	
 $turno 		= $_SESSION['rol'];
//$turno_anterior	= $_POST['turno_anterior']; 

/*$dia_a	= date('d')-2;
$revisa 	= explode("/", date('d/m/Y'));
$compara 	= explode("/",  $dia_a.date('/m/Y'));
*/
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


	//	$temp = 0;
		//$turno = $_SESSION['rol'];
		$a = 0;
		do{

				if($a == 0)
				{ 
				    $encuentra[$a]  = $turno ;
					$a = $a ;
				}
										
								
							if($a <= $dias_diferencia && $turno == 1 ){	
									for($b = 0 ; $b < 2 ; $b++){
										$encuentra[$a+$b]  = $turno ;
										$a = $a + $b ;		
									}
									$turno =  $turno + 1 ;	
									$a = $a+1;
							}  
						
							if( $a <= $dias_diferencia && $turno == 2 ){	
									for($c = 0 ; $c < 2 ; $c++){
										$encuentra[$a+$c]  = $turno ;
										$a = $a + $c ;		
									}
									$turno =  $turno + 1 ;	
									$a = $a+1;
							}
								
							if($a <= $dias_diferencia && $turno == 3 ){	
									for($d = 0 ; $d < 2	 ; $d++){
									 	$encuentra[$a+$d]  = $turno ;	
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
   $dias_diferencia = $dias_diferencia - 1;
   $horario = $encuentra[$dias_diferencia];
   if($horario == 4) $horario = 3;

?>        
        <td><select name="turno" id="turno" class="datosgenerales" >
                <option value="1" <? if($horario == 1) echo "Selected";?> >Matutino</option>
                <option value="2" <? if($horario == 2) echo "Selected";?> >Vepertino</option>
                <option value="3" <? if($horario == 3) echo "Selected";?> >Nocturno</option>
              </select> <br /></td>
    </tr>
    <tr>
    	<td align="right">Fecha:</td>
        <td><input type="text" name="fecha" value="<?=date('d/m/Y');?>" /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Aceptar" /></td>
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
	
		echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion=21&accion=registrado\';</script>';

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
                <label for="fecha">Turno</label>
                 <input type="text" name="turno_bla" id="turno_bla" class="datosgenerales" value="<? if($_REQUEST['turno'] == '1') echo "Matutino";
																							 if($_REQUEST['turno'] == '2') echo "Vespertino";
																							 if($_REQUEST['turno'] == '3') echo "Nocturno";?>" readonly="readonly">
       <br />
       			<input type="hidden" name="turno" value="<?=$_REQUEST['turno']?>" id="turno" />
                
                <input type="hidden" name="id_supervisor"  value="<?=$_SESSION['id_supervisor']?>" />
                <input type="hidden" name="area" id="area" value="3" />
                <input type="hidden" name="rol" id="rol" value="<?=$_SESSION['rol']?>">
                <input type="hidden" name="id_entrada_general" value="<?=$_REQUEST['id_entrada_general']?>">
                <input type="hidden" name="id_impresion" value="<?=$_REQUEST['id_impresion']?>">                
                <h1>Este reporte esta repesado.<input type="checkbox" name="repe" value="1" />Si.</h1>
			</p>
    		<br /><br />
    		<p class="titulos_reportes">Alta Impresi&oacute;n - Nave 2</p>
         <p align="center">          
           
		<?  
		 	$sql_lic= "  SELECT * FROM maquina  WHERE area = 2 ORDER BY numero ASC";
			$res_lic=mysql_query($sql_lic);
			$cant_lic=mysql_num_rows($res_lic);
			$cant=ceil($cant_lic/4);
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
<table >
<tr>
    <? for($x=1;$x<=4; $x++){?>
<td width="220" align="left" valign="top"><br />
  <? if($reg<$cant_lic){ ?>		
		<?	
		    $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."' AND operadores.status = '0'   ";
			$rOperadorextr = mysql_query($qOperadorextr);
			$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);?>
            
        <input type="hidden" name="id_maquina2_<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />
        <input type="hidden" name="codigos2[]" value="<?=$codigo[$reg]?>" />
		<h3 align="left" style="color: rgb(255, 255, 255);">FLEXO 
		  <?=$codigo[$reg]?>
		  <br />
		<select name="id_operador2_<?=$codigo[$reg]?>" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:175px" >
        <option value="0">No hay operador</option>
		<? 
		echo $qOperador = "SELECT * FROM operadores WHERE status = '0' AND area = '3'  AND activo = 0 ORDER BY nombre  ASC";
		$rOperador = mysql_query($qOperador);
		while ($dOperador = mysql_fetch_assoc($rOperador))
		{ ?>
		<option value="<?=$dOperador['id_operador'] ?>" <? if($dAsignacionextr['id_operador'] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>
        <? } ?>
        </select>
		</h3>
<table width="177" height="80"  cellpadding="0" cellspacing="0" >
    <thead>
					<tr>
						<td width="59"><strong>O. T.</strong></td>
					  <td width="84"><strong>Kilogramos</strong></td>
			    </tr>
				</thead>
				<tbody>
				<?php for($a=1;$a<=$nEntradas;$a++) { ?>
					<tr>
                          <td height="21" align="left"><input class="text" type="text" name="ot_impresion<?=$codigo[$reg]?>[]" size="12" /></td>
                          <td align="left" colspan="1">
                          <input size="25"  class="text2" type="text" name="kg_impresion<?=$codigo[$reg]?>[]" id="<?=$a?>_impr<?=$id[$reg]?>" onkeyup="addTextBox(<?=$codigo[$reg]?>,<?=$id[$reg]?>,<?=$a?>,<?=$_REQUEST['turno']?>);"  onchange="sumarImpr2(<?=$id[$reg]?>,<?=$a?>);  sumarImpr();  kh(<?=$_REQUEST['turno']?>); " />
				  		  <input type="checkbox" name="bd_impresion<?=$codigo[$reg]?>[]" id="<?=$a?>_impr_bd<?=$id[$reg]?>" value="1" onclick="sumarImpr2(<?=$id[$reg]?>,<?=$a?>); sumarImpr(); kh(<?=$_REQUEST['turno']?>);" />BD</td>
                  </tr>
                  <tr><td colspan="2">
                  <div id="texto<?=$id[$reg]?>"></div>
                  </td>
                  </tr>
				<?php } ?>
<tr>
						<td style="font-size:9px"><strong>Subtotal AD</strong><br /><strong>Subtotal BD</strong></td>
						<td colspan="3">
                        <input size="25" style="width:100px" type="text" name="subtotal_impr<?=$codigo[$reg]?>" id="subtotal_<?=$id[$reg]?>_impr_Total" onchange="sumarImpr();"  readonly="readonly" value="0" /><br />
                        <input size="25" style="width:100px" type="text" name="subtotalbd_impr<?=$codigo[$reg]?>" id="subtotal<?=$id[$reg]?>_impr_bdTotal" onchange="sumarImpr();"  readonly="readonly" value="0" /></td>
                </tbody>
		  </table>
            
  <? $reg++;}?></td><? }?>
</tr>
</table>
  <? }?>
  </p>    
        <br />
<br />
		    		<p class="titulos_reportes">Alta L&iacute;nea de Impresi&oacute;n - Nave 2</p>	
                    
                   
         <p align="center">          
           
		<?  
		 	$sql_lic2= "  SELECT * FROM maquina WHERE area = 3 ORDER BY numero ASC  ";
			$res_lic2=mysql_query($sql_lic2);
			$cant_lic2=mysql_num_rows($res_lic2);
			$cant2=ceil($cant_lic2/4);
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
<table >
<tr>
    <? for($x2=1;$x2<=4; $x2++){?>
<td width="220" height="202" align="left" valign="top">
  <? if($reg2<$cant_lic2){ ?>		
		<?
		
		    $qOperadorextr2 = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id2[$reg2]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  AND operadores.status = '0'  ";
			$rOperadorextr2 = mysql_query($qOperadorextr2);
			$dAsignacionextr2 = mysql_fetch_assoc($rOperadorextr2);?>
            
	 <!---	<input type="hidden"  name="id_operador3_<?=$codigo2[$reg2]?>" value="<?=$dAsignacionLimp['id_operador'] ?>" /> --->
        <input type="hidden" name="id_maquina3_<?=$codigo2[$reg2]?>" value="<?=$id2[$reg2]?>" />
        <input type="hidden" name="codigos3[]" value="<?=$codigo2[$reg2]?>" />
		<h3 align="left" style="color: rgb(255, 255, 255);">LINEA <?=$codigo2[$reg2]?><br />
		  <select name="id_operador3_<?=$codigo2[$reg2]?>" class="datosgenerales" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; width:175px" >
		<option value="0">No hay operador</option>		<? 
		echo $qOperador2 = "SELECT * FROM operadores WHERE status = '0' AND area = '3'  AND activo = 0  ORDER BY id_operador";
		$rOperador2 = mysql_query($qOperador2);
		while ($dOperador2 = mysql_fetch_assoc($rOperador2))
		{ ?>
		<option value="<?=$dOperador2['id_operador'] ?>" <? if($dAsignacionextr2['id_operador'] == $dOperador2['id_operador'] ) echo "Selected"; ?> ><?=$dOperador2['nombre'] ?></option>
        <? } ?>
        </select> </h3>
	<table width="176" height="71"  cellpadding="0" cellspacing="0" >
					<tr>
					  <td width="59"><strong>O. T.</strong></td>
					  <td width="84"><strong>Kilogramos</strong></td>
			    </tr>
				<?php for($a2=1;$a2<=$nEntradas;$a2++) { ?>
					<tr>
                          <td align="left" height="21" ><input class="text" type="text" name="ot_limpresion<?=$codigo2[$reg2]?>[]" size="12" /></td>
                          <td align="left" colspan="1"><input class="text2" size="25" type="text" name="kg_limpresion<?=$codigo2[$reg2]?>[]" id="<?=$a2?>_limpr<?=$id2[$reg2]?>" onkeydown="addTextBox2(<?=$codigo2[$reg2]?>,<?=$id2[$reg2]?>,<?=$a2?>,<?=$_REQUEST['turno']?>);"  onchange="javascript:   sumarLimpr2(<?=$id2[$reg2]?>,<?=$a2?>);  sumarImpr(); kh(<?=$_REQUEST['turno']?>); "/>
							<input type="checkbox" name="bd_limpresion<?=$codigo2[$reg2]?>[]" id="<?=$a2?>_limpr_bd<?=$id2[$reg2]?>" value="1" onclick="sumarLimpr2(<?=$id2[$reg2]?>,<?=$a2?>); sumarImpr(); kh(<?=$_REQUEST['turno']?>);" />BD</td>
				  </tr>
				<tr><td colspan="2">
                  <div id="limprtexto<?=$id2[$reg2]?>"></div>
                  </td>
                  </tr>
				<?php } ?>
					<tr>
						<td style="font-size:9px"><strong>Subtotal AD</strong><br /><strong>Subtotal BD</strong></td>
						<td colspan="3">
                        <input size="25" style="width:100px" type="text" name="subtotal_limpr<?=$codigo2[$reg2]?>" id="subtotal_<?=$id2[$reg2]?>_limpr_Total" onchange="sumarImpr();"  readonly="readonly" value="0" /><br />
                        <input size="25" style="width:100px" type="text" name="subtotalbd_limpr<?=$codigo2[$reg2]?>" id="subtotal<?=$id2[$reg2]?>_limpr_bdTotal" onchange="sumarImpr();"  readonly="readonly" value="0" />                  
						</td>
					</tr>
		  </table>
  <? $reg2++;}?>
 </td><? }?>
</tr>
</table>
  <? }?>
        <div id="datosgenerales" style="background-color:#FFFFFF;">
          <table width="642" align="center">
<tbody>
                <tr>
                  <td align="right" class="style7"><strong>Total  produccion HD:</strong></td>
                  <td width="162" class="style7"><input name="total_impr_hd" readonly type="text" size="20" id="total_impr_hd"  value=""></td>
                  <td width="148" align="right" class="style7"><strong>Total  produccion BD:</strong></td>
                  <td width="162" class="style7"><input name="total_impr_bd" type="text" size="20" id="total_impr_bd"  value=""></td>
          </tr>
                <tr>
                  <td align="right" class="style7"><strong>Desperdicio HD:</strong></td>
                  <td class="style7"><input name="total_desperdicio_hd" type="text" size="20" id="total_desperdicio_hd"></td>
                  <td align="right" class="style7"><strong>Desperdicio BD:</strong></td>
                  <td class="style7"><input name="total_desperdicio_bd" type="text" size="20" id="total_desperdicio_bd"></td>
                </tr>
                <tr>
                	<td colspan="3" align="right"><strong>Kilogramos / Hora:</strong></td>
                    <td colspan="1"><input type="text" name="k_h" id="k_h" size="20" /></td>
                <tr>
                  <td width="150" align="right" class="style7"><strong>Observaciones:</strong></td>
                  <td class="style7" colspan="3"><textarea class="style5" name="observaciones_impr" id="observaciones_impr_tira" rows="2" cols="40"></textarea></td>
              </tr>
                 <tr>
                  	<td align="right" class="style7">&nbsp;</td>
                  	<td>&nbsp;</td>
              </tr>
                  <tr>
                  	<td colspan="4"><div id="repes" style=" display: none;">
                    	<table width="576">
                        	
<tr>
                            	<td>Turno : <select name="turn_repe" id="turn_repe" class="datosgenerales" >
                <? for($p = 1; $p <= 3; $p++){ ?>
                <option value="<?=$p?>" >
            <?
			if($p == 1) echo "Matutino";
			if($p == 2) echo "Vespertino";
			if($p == 3) echo "Nocturno";
			?>
                </option>
                <? } ?>
              </select></td> <br />
                  				<td>Fecha : <input type="text" name="fecha_repesada" id="fecha_repesada" value="<?=date('d/m/Y');?>" /></td>
                          </tr>
                        </table></div>                    </td>
                  </tr>
		  </tbody>
        </table>
        <br />
  </div>
<div id="barraSubmit" style="background-color:#FFFFFF; text-align:center;">
			<input type="submit" name="guardar" value="Guardar" onclick="javascript: return confirm('Usted está a punto de pasar a la siguiente \netapa de registro de produccion, si desea realizar cambios seleccione cancelar,\npara continuar dar click en aceptar.');"  />
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
<?php } if($nuevo) { ?>
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

function kh()
{
     var total;
     total		=	0;
	 total 		= (( parseFloat(document.getElementById('total_impr_hd').value) + parseFloat(document.getElementById('total_impr_bd').value) ) / <? if($_REQUEST['turno'] == '1') echo '8'; if($_REQUEST['turno'] == '2') echo '7'; if($_REQUEST['turno'] == '3') echo '9';  ?>);
     document.getElementById('k_h').value = redondear(total,2);
}	

function sumarExtr()
{
        var a,total, i;
        i = document.getElementsByTagName('INPUT');
        total=0;
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].id.indexOf('_extr_Total')>0  )
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
        document.getElementById("total_impr_hd").value = parseFloat(total);
        document.getElementById("total_impr_bd").value = parseFloat(total2);
}




function sumar(x)
{
        var a,total,i;
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

function sumarImpr2(x,f)
{
        var a,total,total2,i,o;
        i 		= 	document.getElementsByTagName('INPUT');
        total	=	0;
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


function sumarLimpr2(x,f)
{
        var a,total,total2,i,o;
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

//-->	
</script>
<?php }  if($registrado){ ?>
<br />
<br />
<table width="404" align="center">
	<tr>
		<td align="center" style="color:#CC3333"><br />
		  Lo sentimos pero ya se realizo un reporte con la fecha y turno indicado.<br />
		  <br />
</td>
	</tr>
    
	<tr>
	  <td colspan="2" align="center"><br />
	  <input type="submit" value="Aceptar" onclick="javascript: history.go(-2);" />
      <br /></td>
    </tr>
</table>
<br />
<br />
<? } ?>
<?php if($nuevaEntrada) { ?>
<table align="center" width="100%">
<tr>
	<td align="center">
		<div class="tablaCentrada">
			<p>Se registraron los datos en el sistema.</p>
			
		</div>
    </td>
</tr>
</table>
<?php } ?>

<? if($metas){?>
<form name="metas" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=nuevo" id="super" method="post" >
<table width="707" align="center">
<tr>
    	<td width="152"></td>
    </tr>
     <tr style="background-color:#FF6633">
     	<td class="style7" style="color:#FFFFFF" align="center">Maquina</td>
        <td width="119" align="center" class="style7" style="color:#FFFFFF">Kilogramos</td>
       <td width="115" align="center" class="style7" style="color:#FFFFFF">Meta:</td>
	   <td width="112" align="center" class="style7" style="color:#FFFFFF">Diferencia</td>
       <td width="185" style="color:#FFFFFF" align="center">PRODUCCION</td>
	</tr>
    <?
	
	$ID_ORDENPRODUCCION	=	$_GET['id_entrada_general'];
	
	$qFecha	=	"SELECT fecha FROM impresion LEFT JOIN entrada_general ON impresion.id_entrada_general = entrada_general.id_entrada_general WHERE id_impresion = ".$ID_ORDENPRODUCCION."";
	$rFecha = 	mysql_query($qFecha);
	$dFecha	=	mysql_fetch_assoc($rFecha);
	
	$fechass	=	fecha_tablaInv($dFecha['fecha']);
	$fecha 	= explode("-", $dFecha['fecha']);
		
		$mes 	= 	$fecha[1];
		$ano 	=	$fecha[0];
		
	 			 $qMetas	=	"SELECT * FROM meta WHERE mes = '".$ano."-".$mes."-01' AND ano =".$fecha[0]." AND area = '2'";
				 $rMetas	=	mysql_query($qMetas);
				 $dMetas	=	mysql_fetch_assoc($rMetas);
				 $nMetas	=	mysql_num_rows($rMetas);
				 
	if( $nMetas < 1) { ?>
    <tr>
    	<td colspan="6" align="left"><br /><br /><br /><p style="color:#FF0000" align="center">NO HA SE HA ESPECIFICADO LA META PARA EL MES ACTUAL,<br />
         POR LO TANTO NO SE PUEDE GENERAR UN RESUMEN DE SUS ALTAS DE PRODUCCION, 
         <br />FAVOR DE COMUNICARLO AL ADMINISTRADOR DEL SISTEMA.</p>
         <br /><br /><br />
         <p>GRACIAS</p></td>
    </tr>
    
    
    <? } else { 

	 
	 $qMaquinas	=	"SELECT * FROM resumen_maquina_im INNER JOIN metas_maquinas ON resumen_maquina_im.id_maquina = metas_maquinas.id_maquina LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina  WHERE metas_maquinas.id_meta = ".$dMetas['id_meta']." AND resumen_maquina_im.id_impresion = '".$ID_ORDENPRODUCCION."' ORDER BY maquina.area, maquina.numero ASC";
	 $rMaquinas	=	mysql_query($qMaquinas);
	 	for($z = 0; $dMaquinas	=	mysql_fetch_assoc($rMaquinas); $z++){
		
			
					?>
							<tr <? if(bcmod($z,2) == 0) echo 'bgcolor="#DDDDDD"'; else echo "";?>>
                            	<td align="left" class="style5"><b><? echo $dMaquinas['numero']." ".$dMaquinas['marca']  ?></b></td>
                              <td align="right" class="styl5"><b><?=$dMaquinas['subtotal']?></b></td>
                                <td align="right" class="style5"><b><? 
				 if($_REQUEST['turno'] == '1') $turno = 8; if($_REQUEST['turno'] == '2') $turno = 7; if($_REQUEST['turno'] == '3') $turno =  9; 										 $jaja = ($dMaquinas['diaria']/24)*
											 	
												$meta = ($dMaquinas['diaria']/24)*$turno;
											echo floor($meta) ?></b></td>
                              <td align="right" class="style5"><b><?=floor($tada = ($dMaquinas['subtotal'] - $meta));?></b></td>
                                <td align="center"><? 
								 $porciento = $meta*.20;
								 $nueva	=	$meta - $porciento;
								 if(floor($dMaquinas['subtotal']) < floor($nueva) && $dMaquinas['subtotal'] > 0 ) echo '<span style="color:#FF0000">BAJA PRODUCCION</span>';
								 else if(floor($dMaquinas['subtotal']) > floor($nueva+($porciento*3))) echo '<span style="color:#006600">PRODUCCION ALTA</span>';
								 else if(floor($dMaquinas['subtotal']) == 0) echo '<span style="color:#000000">SIN PRODUCCION</span>';
								 else echo "PRODUCCION NORMAL";
								 ?></td>
    </tr>
					<? 	
		} ?>

  <tr bgcolor="#DDDDDD">
    	<td colspan="1" class="style5">
     <?  $qMaquinas2	=	"SELECT SUM(subtotal) FROM resumen_maquina_im INNER JOIN metas_maquinas ON resumen_maquina_im.id_maquina = metas_maquinas.id_maquina LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina  WHERE metas_maquinas.id_meta = ".$dMetas['id_meta']." AND resumen_maquina_im.id_impresion = '".$ID_ORDENPRODUCCION."' ORDER BY resumen_maquina_im.id_maquina ASC";
	 	 $rMaquinas2	=	mysql_query($qMaquinas2);
		 $dMaquinas2	= mysql_fetch_row($rMaquinas2);
		 ?>PRODUCCION TOTAL</td>
         <td align="right" class="style5"><b><?=$dMaquinas2[0];?></b></td>
         <td align="right" class="style5"><b><? echo floor($metasss = ($dMetas['total_dia']/24)*$turno);?></b></td>
      <td align="right" class="style5"><b><?=floor( $nuevo =$dMaquinas2[0] -  $metasss);?></b></td>
		 <td align="center"><? 
								 $porciento2 = $metasss *.20;
								 $nueva2	=	$metasss - $porciento2;
								 if(floor($dMaquinas2[0]) < floor($nueva2) && $dMaquinas2[0] > 0 ) echo '<span style="color:#FF0000">BAJA PRODUCCION</span>';
								 else if(floor($dMaquinas2[0]) > floor($nueva2+($porciento2*3))) echo '<span style="color:#006600">PRODUCCION ALTA</span>';
								 else if(floor($dMaquinas2[0]) == 0) echo '<span style="color:#000000">SIN PRODUCCION</span>';
								 else echo "PRODUCCION NORMAL";
								 ?></td>         
    </tr><? } ?>
</table>
 <br />
<br />
</form>
<? } ?>

<? if($_REQUEST['accion'] != 'nuevo' && $_REQUEST['accion'] != 'registrado'){ ?>
<form>
<?
	$ID_ORDENPRODUCCION	=	$_GET['id_entrada_general'];
	
	$qFecha	=	"SELECT fecha FROM impresion LEFT JOIN entrada_general ON impresion.id_entrada_general = entrada_general.id_entrada_general WHERE id_impresion = ".$ID_ORDENPRODUCCION."";
	$rFecha = 	mysql_query($qFecha);
	$dFecha	=	mysql_fetch_assoc($rFecha);
	
	$fechass	=	fecha_tablaInv($dFecha['fecha']);
	$fecha 	= explode("-", $dFecha['fecha']);
	
	$mes 	= 	$fecha[1];
	$ano 	=	$fecha[0];	
	
	$mes_array	= 	array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

?>
<table width="850" align="center" cellpadding="2" cellspacing="2">
	<tr>
    	<td colspan="10" style="color:#003; font-weight:bold; font-size:14px; text-align:center;">PRODUCCI&Oacute;N DE <?=strtoupper($mes_array[(int)$mes]);?></td>
    </tr>
     <tr style="background-color:#006699">
     	<td class="style7" style="color:#FFFFFF; text-align:center; width:27%;">Supervisor</td>
        <td class="style7" style="color:#FFFFFF; text-align:center; width:7%;">Horas Trabajadas</td>
        <td class="style7" style="color:#FFFFFF; text-align:center; width:9%">Kg/h (Promedio)</td>
        <td class="style7" style="color:#FFFFFF; text-align:center; width:10%;">Producci&oacute;n</td>
        <td class="style7" style="color:#FFFFFF; text-align:center; width:10%">Meta Producci&oacute;n</td>
        <td class="style7" style="color:#FFFFFF; text-align:center; width:10%">Prod. C/Meta</td>
        <td class="style7" style="color:#FFFFFF; text-align:center; width:9%;">Desperdicio</td>
        <td class="style7" style="color:#FFFFFF; text-align:center; width:9%;">Meta Desperdicio</td>
        <td class="style7" style="color:#FFFFFF; text-align:center; width:9%;">Desp. C/Meta</td>
	</tr>
    <?
	/* META */
	 $qryMeta = "SELECT prod_hora, porcentaje_desp
	 			 FROM meta
	 			 WHERE ano = '$ano'
				 AND mes = '$ano-$mes-01'
				 AND area = '2'";
	 $rstMeta	=	mysql_query($qryMeta);
	 $rowMeta	=	mysql_fetch_assoc($rstMeta);
	 if(is_null($rowMeta['prod_hora'])){
		 echo "No se ha capturado la PRODUCCION POR HORA para IMPRESION del mes de ".strtoupper($mes_array[(int)$mes])." en la secci&oacute;n de metas";
		 exit();
	 }
	 /**/
	 $qrySuper	=	"SELECT s.nombre,
							SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as horas_trabajadas, 
							ROUND(AVG(i.k_h),0) as kgh,
							SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)) as produccion,
							".$rowMeta['prod_hora']."*SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as meta_prod,
							SUM(IF(i.total_hd=0,i.total_bd,i.total_hd))-".$rowMeta['prod_hora']."*SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as prod_contra_meta,
							SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)) as desperdicio,
							(".$rowMeta['porcentaje_desp']."*SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)))/100 as meta_desp, 
							(SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)))-((".$rowMeta['porcentaje_desp']."*SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)))/100 ) as desp_contra_meta
					FROM entrada_general e, impresion i, supervisor s
					WHERE e.id_entrada_general = i.id_entrada_general
					AND fecha BETWEEN '$ano-$mes-01' AND '$ano-$mes-".UltimoDia($ano,$mes)."'
					AND e.impresion = '1'
					AND e.repesada = '1'
					AND e.id_supervisor = s.id_supervisor
					GROUP BY e.id_supervisor
					ORDER BY desp_contra_meta";
	 $rstSuper	=	mysql_query($qrySuper);
	 for($z = 0; $rowSuper	=	mysql_fetch_assoc($rstSuper); $z++){
	?>
		<tr <? if(bcmod($z,2)==0) echo "bgcolor='#F2F2F2'"; else echo ""; ?>>
        	<td align="left"><?=$rowSuper['nombre']?></td>
            <td align="center"><?=number_format($rowSuper['horas_trabajadas'])?></td>
            <td align="center"><?=number_format($rowSuper['kgh'])?></td>
            <td align="right"><?=number_format($rowSuper['produccion'])?></td>
            <td align="right"><?=number_format($rowSuper['meta_prod'])?></td>
        	<td align="right" <?=$rowSuper['prod_contra_meta'] < 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($rowSuper['prod_contra_meta'])?></td>
            <td align="right"><?=number_format($rowSuper['desperdicio'])?></td>
            <td align="right"><?=number_format($rowSuper['meta_desp'])?></td>
        <td align="right" <?=$rowSuper['desp_contra_meta'] > 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($rowSuper['desp_contra_meta'])?></td>
		</tr>
	<? } ?>
  		<tr bgcolor="#F6E3CE">
    	<td colspan="1" align="right" >
     <? 
	$qryTotal	=	"SELECT s.nombre,
							SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as horas_trabajadas, 
							ROUND(AVG(i.k_h),0) as kgh,
							SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)) as produccion,
							".$rowMeta['prod_hora']."*SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as meta_prod,
							SUM(IF(i.total_hd=0,i.total_bd,i.total_hd))-".$rowMeta['prod_hora']."*SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as prod_contra_meta,
							SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)) as desperdicio,
							(".$rowMeta['porcentaje_desp']."*SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)))/100 as meta_desp, 
				(SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)))-((".$rowMeta['porcentaje_desp']."*SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)))/100 ) as desp_contra_meta
					FROM entrada_general e, impresion i, supervisor s
					WHERE e.id_entrada_general = i.id_entrada_general
					AND fecha BETWEEN '$ano-$mes-01' AND '$ano-$mes-".UltimoDia($ano,$mes)."'
					AND e.impresion = '1'
					AND e.repesada = '1'
					AND e.id_supervisor = s.id_supervisor
					GROUP BY repesada";
	$rstTotal	=	mysql_query($qryTotal);
	$rowTotal	= 	mysql_fetch_assoc($rstTotal);
	?><strong><i>TOTAL:</i></strong></td>
        <td align="center"><?=number_format($rowTotal['horas_trabajadas'])?></td>
        <td align="center"><?=number_format($rowTotal['kgh'])?></td>
        <td align="right"><?=number_format($rowTotal['produccion'])?></td>
        <td align="right"><?=number_format($rowTotal['meta_prod'])?></td>
        <td align="right" <?=$rowTotal['prod_contra_meta'] < 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($rowTotal['prod_contra_meta'])?></td>
        <td align="right"><?=number_format($rowTotal['desperdicio'])?></td>
        <td align="right"><?=number_format($rowTotal['meta_desp'])?></td>
        <td align="right" <?=$rowTotal['desp_contra_meta'] > 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($rowTotal['desp_contra_meta'])?></td>
	</tr>
    <tr>
        <td colspan="10" valign="top" style="text-align:center; padding-top:15px;"><a href="<?=$_SERVER['HOST']?>?seccion=<?=isset($_SESSION['id_admin'])?'43':'40'?>&area=area2&mes=<?=$mes?>&ano=<?=$ano?>">| VER REPORTE DE PROMEDIOS |</a></td>
    </tr>
</table>
 <br />
<br />
</form>
<? } ?>