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
    if(gettype($str)=="array"){
       $x="";
       foreach ($str as $key => $value) {
       	  $x=$x."[".$key."]=>".$value.",";
       }
       $str=$x;
    }
	$fp = fopen('debug_log.txt','a');
   if($fp)
   {
     //echo "se creó con éxito";
     fwrite($fp,$str."\r\n");
    
     fclose($fp);
    }
     
		//echo $str . "\n";
}



$tabla	=	"maquina";

$tabla2 =	"supervisor";

$tabla3 =	"operador";

$indice		=	"id_maquina";

$indice2	=	"id_supervisor";

$indice3	=	"id_operador";

$debug = true;
//pDebug("Hola Amigos");

if($debugx)
	echo "<pre>";
pDebug("Grabo La Entrada General");

if(isset($_POST['guardar'])) // cuando se oprime el boton de Guardar
{
    //echo "Se va a guardar";

	$_POST['fecha']	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

	$fallo_general	=	$_POST['fallo_general'];

	$qGeneral	=	"INSERT INTO entrada_general (id_supervisor,fecha,turno,area,autorizada,impresion,rol,actualizado,repesada) VALUES ('{$_POST[id_supervisor]}','{$_POST[fecha]}','{$_POST[turno]}','{$_POST[area]}','1','0','{$_POST[rol]}','0','0')";

	pDebug($qGeneral);

	 $rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");
	

	$qIdMaxEg	=	"SELECT MAX(id_entrada_general) FROM entrada_general";

	$rIdMaxEg	=	mysql_query($qIdMaxEg);

	$dIdMaxEg	=	mysql_fetch_row($rIdMaxEg);

	$id_gen		=	 $dIdMaxEg[0];

	pDebug("Grabo La Orden de Produccion");

	$qOrdenProduccion	=	"INSERT INTO orden_produccion (id_entrada_general, total, desperdicio_tira, desperdicio_duro, observaciones, k_h) VALUES ".

							"('$id_gen','{$_POST[total_extruder]}','{$_POST[desperdicio_tira_extruder]}','{$_POST[desperdicio_duro_extruder]}','{$_POST[observaciones_extruder]}', '{$_POST[kilo_horas]}')";



	pDebug($qOrdenProduccion);

	 $rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");

	
	$qIdMaxOp	=	"SELECT MAX(id_orden_produccion) FROM orden_produccion";

	$rIdMaxOp	=	mysql_query($qIdMaxOp);

	$dIdMaxOp	=	mysql_fetch_row($rIdMaxOp);	

	$id_pro		=	 $dIdMaxOp[0];

	$fecha	=	$_REQUEST['fecha'];

	$turno	=	$_REQUEST['turno'];
	
	$nMaquinas	=	sizeof($_POST['codigos']);
    	
    pDebug("Son ".$nMaquinas." Maquinas");

    pDebug($_POST['codigos']);

    pDebug("inicio Tiempos Muertos");

	for($i=0;$i<$nMaquinas;$i++)

	{

		$ID_ORDENPRODUCCION =   $id_pro;

		$sufijo				=	$_POST['codigos'][$i];

		$id_maquina			=	intval($_POST['id_maquina'.$sufijo]);

		$id_operador		=	intval($_POST['id_operador'.$sufijo]);

	
		
		if($_POST['fp_extruder'.$sufijo] == 1){

			if($_REQUEST['turno'] == '1') $falta_personal = "08:00:00"; 

			if($_REQUEST['turno'] == '2') $falta_personal =	"07:00:00"; 

			if($_REQUEST['turno'] == '3') $falta_personal =	"09:00:00";		

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


		if($_REQUEST['fallo_general']  == 1){
		$horas 		=	$_POST['horas_fallo'];

		$minutos	=	$_POST['minutos_fallo'];

		$fallo_electrico 	=	$horas.':'.$minutos.':00';

		} else {

		$fallo_electrico	=	'00:00:00';

		}



		$observacion		=	$_POST['ob_extruder'.$sufijo];
		$mantenimiento		=	$_POST['mh_extruder'.$sufijo].':'.$_POST['mm_extruder'.$sufijo].':00';
		$otras				=	$_POST['oh_extruder'.$sufijo].':'.$_POST['om_extruder'.$sufijo].':00';


		$qResumenMaquinaEx	=	"INSERT INTO tiempos_muertos (id_produccion, id_maquina, id_operador, falta_personal, observaciones, fallo_electrico, mantenimiento, tipo, mallas, otras) ".

								"VALUES ('$ID_ORDENPRODUCCION','$id_maquina', '$id_operador', '$falta_personal', '$observacion', '$fallo_electrico','$mantenimiento', '1','$mallas','$otras')";

		pDebug($qResumenMaquinaEx);

		$rResumenMaquinaEx	=	mysql_query($qResumenMaquinaEx) OR die("<p>$qResumenMaquinaEx</p><p>".mysql_error()."</p>");

		$ID_RES_MAQUINAEX	=	mysql_insert_id();


	}

	/* -- Termina parte de Extruder -- */

	/* Comienza Impresión */

	/* -- Termina parte de Impresión */



	pDebug("Terminado ");
     //pDebug("Fin Tiempos muertos");

	//pDebug("Comienza volcado de la petición");


	if(isset($_SESSION['admindolfra']) ){ 

	echo '<script language="javascript">location.href=\'admin.php?seccion=2&accion=nuevo&id_entrada_general='.$id_gen.'&id_orden_produccion='.$id_pro.'&turno='.$turno.'&fecha='.$fecha.'\';</script>';

	} 

	if(!isset($_SESSION['admindolfra']) ){ 

	echo '<script language="javascript">location.href=\'admin_supervisor.php?seccion=2&accion=nuevo&id_entrada_general='.$id_gen.'&id_orden_produccion='.$id_pro.'&turno='.$turno.'&fecha='.$fecha.'\';</script>';

	}  





}   // Fin de if(isset($_POST['guardar']))



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

<html xmlns="http://www.w3.org/1999/xhtml" version="-//W3C//DTD XHTML 1.1//EN" xml:lang="en">

<head>

	<script type="text/javascript" src="mootools.js"></script>

	<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />

	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>Bolseo</title>

	<style type="text/css" media="screen">@import 'style.css';</style>

</head>

<body>

<? if($supervisor){?>

<form name="super" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=nuevo" id="super" method="post" >

<table width="404" align="center" border="0">

<? if(isset($_SESSION['id_admin']))   
 { ?>
  
  <tr>

		<td width="157" align="right">Elija un Supervisor:</td>

		<td width="235" align="left"><select name="id_supervisor" id="supervisor">

   	<? 
     //filtra los supervisores del area de Extruder y que estén activos (0)
	$qSupervisor = "SELECT * FROM supervisor WHERE area = 1 AND activo = 0 ORDER BY nombre ASC;";

	$rSupervisor = mysql_query($qSupervisor);

while($dSupervisor = mysql_fetch_assoc($rSupervisor)){?>

	<option value="<?=$dSupervisor['id_supervisor']?>"><?=$dSupervisor['nombre']?></option>

            <? } ?>

       	</select></td>

	  <input type="hidden" name="admindolfra">

                       

	</tr>

   <?  }	?>

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
		<td  align="right"><input type="button" value="Cancelar" onclick="window.document.location.href = 'admin.php';"></td>
		<td><input type="submit" value="Aceptar" /></td>

    </tr>

</table>

 <br />

<br />

</form>

<?php } 

if($nuevo) { 


	$fecha	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

  //verifica si ya existe un registro de esa fecha y turno de Extruder en entrada_general
  // si ya existe, llama a extruder.php (secc 2)con  accion 0registrado
   $qValidacion = "SELECT * FROM entrada_general WHERE fecha = '".$fecha."' AND turno = '".$_REQUEST['turno']."' AND area=1";

   $rValidacion = mysql_query($qValidacion);

   $nValidacion = mysql_num_rows($rValidacion);


//if (!isset($_SESSION['admindolfra'])){

	if($nValidacion  >= 1  && isset($_SESSION['admindolfra'])){
		echo '<script language="javascript">location.href=\'admin.php?seccion=2&accion=registrado\';</script>';
	}

	if($nValidacion  >= 1  && !isset($_SESSION['admindolfra'])){
		echo '<script language="javascript">location.href=\'admin_supervisor.php?seccion=2&accion=registrado\';</script>';
	}

//}

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

        <input type="text" name="turno_bla" id="turno_bla" class="datosgenerales" value="<? 
            if($_REQUEST['turno'] == '1') echo "Matutino";
			if($_REQUEST['turno'] == '2') echo "Vespertino";
	 		if($_REQUEST['turno'] == '3') echo "Nocturno";?>" readonly="readonly">

       <br /><br /><br />

		<h1><input type="checkbox" name="fallo_general" value="1" onclick="javascript: muestra(100);" />Fallo General Electrico</h1>

        <div class="style5" id="div_100" style="display:none; width:300px"><br>Tiempo de fallo: <input type="text" name="horas_fallo" size="2" />:<input type="text" name="minutos_fallo"  size="2" />Hrs.<br />

        </div>

        <input type="hidden" name="turno" value="<?=$_REQUEST['turno']?>" id="turno" />

        <input type="hidden" name="id_supervisor"  value="<?=$_SESSION['id_supervisor']?>" />

        <input type="hidden" name="area" id="area" value="1" />

        <input type="hidden" name="rol" value="<?=$_SESSION['rol']?>">

	  </p></div>

   		<br /><br />

   		<p align="center" class="titulos_reportes">TIEMPOS MUERTOS HD</p>

  		<p align="center">          

<?  

		$sql_lic= "  SELECT * FROM maquina WHERE maquina.area = 4 AND TIPO_D =1 ORDER BY numero ASC  ";
		$sql_lic_bd= "  SELECT * FROM maquina WHERE maquina.area = 4 AND TIPO_D =2 ORDER BY numero ASC  ";

		$res_lic=mysql_query($sql_lic);
		$res_lic_bd= mysql_query($sql_lic_bd);

		$cant_lic=mysql_num_rows($res_lic);
		$cant_lic_bd=mysql_num_rows($res_lic_bd);

		$cant=ceil($cant_lic/3);
		$cant_bd=ceil($cant_lic_bd/3);

		$a=0;
		// crea 2 arreglos paralelos, $codigo con los numeros y $id con los id_maquina

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

    <table border="0" width="100%" align="center">
    <tr>

    <? 
     for($x=1;$x<=4; $x++){
    ?>

      <td width="190px" align="left" valign="top">
      <? if($reg<$cant_lic){ 

		$qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

		$rOperadorextr = mysql_query($qOperadorextr);

		$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);
	  ?>

      <input type="hidden" name="id_maquina<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />

      <input type="hidden" name="codigos[]" value="<?=$codigo[$reg]?>" />

	  <h3 align="left" onclick="muestra_extr('<?=$codigo[$reg]?>');"  class="Tips4" title="Click aqui para abrir o cerrar::"> EXTRUDER <?=$codigo[$reg]?>--<?echo"(".$id[$reg].")" ?> </h3>

	  <div id="extruder<?=$codigo[$reg]?>" style="display: none  ; border:1px solid #ccc; height:100%; padding:8px 0px">

        <div style="width:70px; float:left; padding-left:8px;">Observ.</div>

        <div style="float:left"><textarea  name="ob_extruder<?=$codigo[$reg]?>" id="ob_extruder<?=$codigo[$reg]?>" cols="10" rows="3" ></textarea></div>

        <div style="width:110px; float:left; padding-left:8px;">C. de mallas:</div>                    
 
        <div style="float:left"><input type="checkbox" name="mallas<?=$codigo[$reg]?>" value="1" /></div>

        <!--        <div style="width:110px; float:left; padding-left:8px;" class="style7">Personal:</div>

        <div style="float:left; height:22px"><input type="checkbox" name="fp_extruder<?=$codigo[$reg]?>" value="1" onclick="mostrar('<?=$codigo[$reg]?>')" /></div> 
        -->        
        <div style="width:90px; float:left; padding-left:8px;" class="style7">Mantto:</div>
 
        <div style="float:left"><input type="text" name="mh_extruder<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" />:<input type="text" name="mm_extruder<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" /></div>

        <div style="width:90px; float:left; padding-left:8px;" class="style7">Otros:</div>

        <div style="float:left"><input type="text" name="oh_extruder<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" />:<input type="text" name="om_extruder<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" /></div>

  	  </div>        

      <br /><br />

  <?  $reg++; } //Fin de if ?>

  <br /></td><? } 
  // aqui cierra el for
  ?>



</tr>

</table>

 
<?  
   //Aqui termina el proceso y comienza el de BD

  }

  ?>
<br />
<?
		$a=0;
		// crea 2 arreglos paralelos, $codigo con los numeros y $id con los id_maquina

    while($dat_extr = mysql_fetch_assoc($res_lic_bd))
	{
		$codigo_bd[$a]=$dat_extr['numero'];
		$id_bd[$a] 	= $dat_extr['id_maquina'];
		$a++;
	}
   $reg=0;
?>
   <p align="center" class="titulos_reportes">TIEMPOS MUERTOS BD</p>

<table border="0" width="100%" align="center">
<tr>
 <? 
     for($x=1;$x<=4; $x++){
    ?>	
<td width="190px" align="left" valign="top"> 
     <? 
     if($reg<$cant_lic_bd){ 

		$qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id_bd[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

		$rOperadorextr = mysql_query($qOperadorextr);

		$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);
	?>
      <input type="hidden" name="id_maquina<?=$codigo_bd[$reg]?>" value="<?=$id_bd[$reg]?>" />

      <input type="hidden" name="codigos[]" value="<?=$codigo_bd[$reg]?>" />

	  <h3 align="left" style="background-color:#FE642E;" onclick="muestra_extr('<?=$codigo_bd[$reg]?>');"  class="Tips4" title="Click aqui para abrir o cerrar::"> EXTRUDER <?=$codigo_bd[$reg]?>--<? echo "(".$id_bd[$reg].")"; ?> </h3>

	  <div id="extruder<?=$codigo_bd[$reg]?>" style="display: none  ; border:1px solid #ccc; height:100%; padding:8px 0px">

        <div style="width:70px; float:left; padding-left:8px;">Obser.</div>

        <div style="float:left"><textarea  name="ob_extruder<?=$codigo_bd[$reg]?>" id="ob_extruder<?=$codigo_bd[$reg]?>" cols=10" rows="3" ></textarea></div>

        <div style="width:110px; float:left; padding-left:8px;">C. de mallas:</div>                    
 
        <div style="float:left"><input type="checkbox" name="mallas<?=$codigo_bd[$reg]?>" value="1" /></div>

        <!--        
        <div style="width:110px; float:left; padding-left:8px;" class="style7">Personal:</div>

        <div style="float:left; height:22px"><input type="checkbox" name="fp_extruder<?=$codigo[$reg]?>" value="1" onclick="mostrar('<?=$codigo[$reg]?>')" /></div> 
        -->        
        <div style="width:90px; float:left; padding-left:8px;" class="style7">Mantto:</div>
 
        <div style="float:left"><input type="text" name="mh_extruder<?=$codigo_bd[$reg]?>" size="2" maxlength="2" value="00" />:<input type="text" name="mm_extruder<?=$codigo_bd[$reg]?>" size="2" maxlength="2" value="00" /></div>

        <div style="width:90px; float:left; padding-left:8px;" class="style7">Otros:</div>

        <div style="float:left"><input type="text" name="oh_extruder<?=$codigo_bd[$reg]?>" size="2" maxlength="2" value="00" />:<input type="text" name="om_extruder<?=$codigo_bd[$reg]?>" size="2" maxlength="2" value="00" /></div>

  	  </div>
  	<? }  $reg++;?>  

<br /></td><? } // aqui cierra el for?>
</tr>	
</table>    	
<div id="barraSubmit" style="background-color:#FFFFFF; text-align:center;">
 <input type="submit" name="guardar" value="Guardar" onclick="javascript: return confirm('Usted está a punto de pasar a la siguiente \netapa de registro de produccion, si desea realizar cambios seleccione cancelar,\npara continuar dar click en aceptar.');"/>
  </div>

<? } 
if($nuevaEntrada) {
 ?>

	<div class="tablaCentrada">

	<p>Se registraron los datos en el sistema.</p>

	<p>Click <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&amp;accion=nuevo">aquí</a> para agregar una nueva entrada.</p>

	</div>

<? } ?>

</div></div>

</form>

<? } ?>

</body>
</html>
