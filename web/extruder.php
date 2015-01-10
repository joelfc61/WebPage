<style> @import 'style.css'; </style>


<?php



if(isset($_REQUEST['admindolfra']) && $_REQUEST['accion'] == 'nuevo'	){

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
     

}




$tabla	=	"maquina";

$tabla2 =	"supervisor";

$tabla3 =	"operador";



$indice		=	"id_maquina";

$indice2	=	"id_supervisor";

$indice3	=	"id_operador";



$debug = true;



if($debugx)

	echo "<pre>";



if(isset($_POST['guardar']))

{

	//REPESADA Y DIFERENCIAS.

	

	/* Entrada general para referencias */

	

	$_POST['fecha']	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);



	$qGeneral	=	"UPDATE  entrada_general SET id_supervisor = '{$_POST[id_supervisor]}', fecha = '{$_POST[fecha]}', turno = '{$_POST[turno]}', area = '{$_POST[area]}', /*autorizada = '0',*/ impresion = '0', rol = '{$_POST[rol]}', actualizado = '{$_POST[repe]}' ,repesada = '{$_POST[repe]}' WHERE id_entrada_general = '{$_POST[id_entrada_general]}' ";


	pDebug($qGeneral);

	$rGeneral	=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");

	/*

		Usaremos este ID para referenciar en las tres tablas:

		- Orden_produccion = extruder

		- Impresion / Línea Impresión

		  (Estas últimas se diferencían en el campo linea_impresión (1 = verdadero, 0 = falso)

	*/

	 	$ID_GENERAL			=	$_POST['id_entrada_general'];

	 	$ID_ORDENPRODUCCION	=	$_POST['id_orden_produccion'];

	/* Entradas de Extruder */

	

	$qOrdenProduccion	=	"UPDATE  orden_produccion SET id_entrada_general = '$ID_GENERAL', 
	total = '{$_POST[total_extruder]}',total_bd = '{$_POST[total_extruder_bd]}', 
	desperdicio_tira = '{$_POST[desperdicio_tira_extruder]}',desperdicio_tira_bd = '{$_POST[desperdicio_tira_extruder_bd]}', 
	desperdicio_duro = '{$_POST[desperdicio_duro_extruder]}', desperdicio_duro_bd = '{$_POST[desperdicio_duro_extruder_bd]}',
	k_h = '{$_POST[k_h]}',k_h_bd = '{$_POST[k_h_bd]}',observaciones = '{$_POST[observaciones_extruder]}' WHERE id_orden_produccion = '".$ID_ORDENPRODUCCION."'";

	pDebug($qOrdenProduccion);

	$rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");

	

	$nMaquinas	=	sizeof($_POST['codigos']);

   // para cada maquina  de HD, los subtotales

	for($i=0;$i<$nMaquinas;$i++)

	{

	
		$sufijo				=	$_POST['codigos'][$i];

		$subtotal			=	floatval($_POST['subtotal_extruder'.$sufijo]);

		$id_maquina			=	intval($_POST['id_maquina'.$sufijo]);

		$id_operador		=	intval($_POST['id_operador'.$sufijo]);



		$observacion		=	$_POST['ob_extruder'.$sufijo];


         //Se necesia agregar densidad
		$qResumenMaquinaEx	=	"INSERT INTO resumen_maquina_ex (id_orden_produccion, id_maquina, id_operador, subtotal, observacion,densidad) ".
"VALUES ('$ID_ORDENPRODUCCION','$id_maquina','$id_operador','$subtotal','$observacion',1)";

		pDebug($qResumenMaquinaEx);

		$rResumenMaquinaEx	=	mysql_query($qResumenMaquinaEx) OR die("<p>$qResumenMaquinaEx</p><p>".mysql_error()."</p>");

		$ID_RES_MAQUINAEX	=	mysql_insert_id();

		

		/*

			Para mayor seguridad usar:

			$nEntradas			=	sizeof($_POST["ot_extruder".$sufijo]) & sizeof($_POST["kg_extruder".$sufijo]);

			Así nos encargamos de que el número de elementos por lista coincida.

		*/

		

		$nEntradas			=	sizeof($_POST["ot_extruder".$sufijo]);

		$qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_ex (id_resumen_maquina_ex,orden_trabajo,kilogramos) VALUES";


        // para cada orden de trabajo de la maquina
		$flag = false;

		for($j=0;$j<$nEntradas;$j++)

		{

			$ot_extruder		=	$_POST["ot_extruder".$sufijo][$j];

			$kg_extruder		=	$_POST["kg_extruder".$sufijo][$j];

			if(empty($ot_extruder) && empty($kg_extruder))

				continue;

			/* Esto sintácticamente puede ir en una línea, pero extrañamente PHP tiene problema con las pilas con asignaciones contiguas */

			$qDetalleResumen	.=	($j>0)?",":" ";

			$qDetalleResumen	.= "('$ID_RES_MAQUINAEX','$ot_extruder','$kg_extruder')";

			$flag = true; 

		}

		if($flag == true){

		pDebug($qDetalleResumen);

		$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

		}

	}

	

	/* -- Termina parte de Extruder HD-- */

		$nMaquinas	=	sizeof($_POST['codigos_bd']);

   	 pDebug($_POST['codigos_bd']);

	for($i=0;$i<$nMaquinas;$i++)

	{

	
		$sufijo				=	$_POST['codigos_bd'][$i];

		$subtotal			=	floatval($_POST['subtotal_extruder_bd'.$sufijo]);

		$id_maquina			=	intval($_POST['id_maquina_bd'.$sufijo]);

		$id_operador		=	intval($_POST['id_operador_bd'.$sufijo]);



		//$observacion		=	$_POST['ob_extruder'.$sufijo];


         //Se necesia agregar densidad
		$qResumenMaquinaEx	=	"INSERT INTO resumen_maquina_ex (id_orden_produccion, id_maquina, id_operador, subtotal, observacion,densidad) ".
"VALUES ('$ID_ORDENPRODUCCION','$id_maquina','$id_operador','$subtotal','$observacion',2)";

		pDebug($qResumenMaquinaEx);

		$rResumenMaquinaEx	=	mysql_query($qResumenMaquinaEx) OR die("<p>$qResumenMaquinaEx</p><p>".mysql_error()."</p>");

		$ID_RES_MAQUINAEX	=	mysql_insert_id();

		

		/*

			Para mayor seguridad usar:

			$nEntradas			=	sizeof($_POST["ot_extruder".$sufijo]) & sizeof($_POST["kg_extruder".$sufijo]);

			Así nos encargamos de que el número de elementos por lista coincida.

		*/

		

		$nEntradas			=	sizeof($_POST["ot_extruder_bd".$sufijo]);
		$nombre = "ot_extruder_bd".$sufijo;
         
         $cad = "La maquina $nombre tiene $nEntradas entradas,";
         pDebug($cad);
		$qDetalleResumen	=	"INSERT INTO detalle_resumen_maquina_ex (id_resumen_maquina_ex,orden_trabajo,kilogramos) VALUES";


        // para cada OT de la maquina
		$flag = false;

		for($j=0;$j<$nEntradas;$j++)

		{

			$ot_extruder		=	$_POST["ot_extruder_bd".$sufijo][$j];

			$kg_extruder		=	$_POST["kg_extruder_bd".$sufijo][$j];

			if(empty($ot_extruder) && empty($kg_extruder))

				continue;

			/* Esto sintácticamente puede ir en una línea, pero extrañamente PHP tiene problema con las pilas con asignaciones contiguas */

			$qDetalleResumen	.=	($j>0)?",":" ";

			$qDetalleResumen	.= "('$ID_RES_MAQUINAEX','$ot_extruder','$kg_extruder')";

			$flag = true; 

		}

		if($flag == true){

		pDebug($qDetalleResumen);

		$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

		}

	}


	/* -- Termina parte de Extruder BD-- */

	/* Comienza Impresión */

	



	/* -- Termina parte de Impresión */



	

	/*	if(isset($_SESSION['admindolfra']) ){ 

	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=25&id_produccion='.$ID_ORDENPRODUCCION.'&accion=nuevo&turno='.$_POST['turno'].'\';</script>';

	} 

	if(!isset($_SESSION['admindolfra']) ){ 

	echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion=23&id_produccion='.$ID_ORDENPRODUCCION.'&accion=nuevo&turno='.$_POST['turno'].'\';</script>';

	}   */

   	
  echo '<script language="javascript">location.href=\'admin.php\';</script>';		
    
	if(isset($_SESSION['admindolfra']) ){ 

	//echo '<script language="javascript">location.href=\'admin.php?seccion=2&accion=metas&id_entrada_general='.$ID_ORDENPRODUCCION.'&turno='.$_POST['turno'].'\';</script>';
    //echo '<script language="javascript">location.href=\'admin.php</script>';		

	} 

	if(!isset($_SESSION['admindolfra']) ){ 

	//echo '<script language="javascript">location.href=\'admin_supervisor.php?seccion=2&accion=metas&id_entrada_general='.$ID_ORDENPRODUCCION.'&turno='.$_POST['turno'].'\';</script>';
     //echo '<script language="javascript">location.href=\'admin_supervisor.php</script>';		
     		
	} 	 

	

}



if(!empty($_GET['accion']))

{

	$nuevo			= ($_GET['accion']=="nuevo"	)?true:false;

	$metas			= ($_GET['accion']=="metas"	)?true:false;

	$listar			= ($_GET['accion']=="listar")?true:false;

	$supervisor		= ($_GET['accion']=="supervisor")?true:false;

	$registrado		= ($_GET['accion']=="registrado")?true:false;

	$nuevaEntrada 	= ($_GET['accion']=="nuevaentrada")?true:false;

	

	if(!empty($_GET['id_util']) && is_numeric($_GET['id_util']) )

	{

		$mostrar	= ($_GET['accion']=="mostrar"	)?true:false;

		$modificar	= ($_GET['accion']=="modificar"	)?true:false;

		$eliminar	= ($_GET['accion']=="eliminar" && valida_root())?true:false;

		$traduccion	= ($_GET['accion']=="traduccion")?true:false;

		//$registrado	= ($_GET['accion']=="registrado")?true:false;

	}



}

if($nuevo)

{

	$nEntradas	=	1;

	

}



?>

<script language="javascript" type="text/javascript">

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

<form name="super" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=nuevo" id="super" method="post" >

<br />

<br />

<table width="404" align="center">

<? if(isset($_SESSION['id_admin'])){?>

	<tr>

		<td width="157" align="right">Elija un Supervisor:</td>

		<td width="235"><select name="id_supervisor" id="supervisor">

    		<? 

						$qSupervisor = "SELECT * FROM supervisor WHERE area = 1 ORDER BY nombre ASC";

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

									 	$encuentra[$a+$d]  = $turno;	

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



$qValidacion = "SELECT * FROM entrada_general WHERE fecha = '".$fecha."' AND turno = '".$_REQUEST['turno']."' AND area=1";

$rValidacion = mysql_query($qValidacion);

$nValidacion = mysql_num_rows($rValidacion);









//if (!isset($_SESSION['admindolfra'])){

	if($nValidacion  >= 1  && isset($_SESSION['admindolfra'])){

	

		echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=registrado\';</script>';



	}

	if($nValidacion  >= 1  && !isset($_SESSION['admindolfra'])){

	

		echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion='.$_REQUEST['seccion'].'&accion=registrado\';</script>';



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

				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=$_GET['fecha']?>" id="fecha" class="datosgenerales" readonly="readonly" /><br />

                <label for="fecha">Turno</label>

                <input type="text" name="turno_bla" id="turno_bla" class="datosgenerales" value="<? if($_REQUEST['turno'] == '1') echo "Matutino";
		 		if($_REQUEST['turno'] == '2') echo "Vespertino";
			 if($_REQUEST['turno'] == '3') echo "Nocturno";?>" readonly="readonly">

       <br />

       			<input type="hidden" name="turno" value="<?=$_REQUEST['turno']?>" id="turno" />

                <input type="hidden" name="id_supervisor"  value="<?=$_SESSION['id_supervisor']?>" />

                <input type="hidden" name="area" id="area" value="1" />

                <input type="hidden" name="rol" value="<?=$_SESSION['rol']?>">

                <input type="hidden" name="id_entrada_general" value="<?=$_REQUEST['id_entrada_general']?>">

                <input type="hidden" name="id_orden_produccion" value="<?=$_REQUEST['id_orden_produccion']?>">

        <h1>Este reporte esta repesado.<input type="checkbox" name="repe" value="1" />Si.</h1> <br>
        <CENTER>
        <h1 width="100%" align="right" style="color:#000;">PRODUCCION HD</h1>
        </CENTER>
                

	  </p></div>

	  <p align="left">          
           

<?  

		 	$sql_lic    = "SELECT * FROM maquina WHERE area = 4 and tipo_d=1 ORDER BY numero ASC  ";
            $sql_lic_bd = "SELECT * FROM maquina WHERE area = 4 and tipo_d=2 ORDER BY numero ASC  ";

			$res_lic=mysql_query($sql_lic);
			$res_lic_bd=mysql_query($sql_lic_bd);

			$cant_lic=mysql_num_rows($res_lic);
			$cant_lic_bd=mysql_num_rows($res_lic_bd);

			$cant=ceil($cant_lic/3);
			$cant_bd=ceil($cant_lic_bd/3);

			$a=0;

while($dat_extr = mysql_fetch_assoc($res_lic))
	{

				$codigo[$a] = $dat_extr['numero'];

				$id[$a] 	= $dat_extr['id_maquina'];

				$a++;

	}

	$reg=0;

//Este  ciclo es por  cada renglon de maquinas
for($i=0;$i<$cant;$i++)
{

 ?>

 <table width="204" >

 <tr>

   <? for($x=1;$x<=4; $x++){  //Este es por cada maquina del renglon  ?>

    <td width="220" align="left" valign="top"><br />

     <? if($reg<$cant_lic){ ?>		

		<?

		    $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."' AND operadores.status = '0' and operadores.activo='0'";

		    //echo $qOperadorextr;

			$rOperadorextr = mysql_query($qOperadorextr);

			$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);  ?>
    

	 		<input type="hidden"  name="id_operador<?=$codigo[$reg]?>" value="<?=$dAsignacionextr['id_operador'] ?>" />

            <input type="hidden" name="id_maquina<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />

            <input type="hidden" name="codigos[]" value="<?=$codigo[$reg]?>" />

  		   <h3 align="left" style="color: rgb(255, 255, 255);">Extruder <?=$codigo[$reg]; echo ' ('.$id[$reg].')'?><br />

		   <select style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:175px"   name="id_operador<?=$codigo[$reg]?>" >

           <option value="0">No hay operador</option>

		  <? 
         // llena el combo con los operadores activos de la tabla
		  $qOperador = "SELECT * FROM operadores WHERE status = '0' AND area ='1'  AND activo = 0  ORDER BY nombre ASC";
         
		  $rOperador = mysql_query($qOperador);

		while ($dOperador = mysql_fetch_assoc($rOperador))
		{ ?>

		<option value="<?=$dOperador['id_operador'] ?>" <? if($dAsignacionextr['id_operador'] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>

        <? } ?>

        </select>

         </h3>

		<table width="203" height="65" cellpadding="0" cellspacing="0" >

        <tr>

			<td width="75"><strong>O. T.</strong></td>

			  <td width="126" ><strong>Kilogramos</strong></td>

		      </tr>

				<?php for($a=1;$a<=$nEntradas;$a++) { ?>

					<tr>

                          <td align="left" ><input class="text" type="text" name="ot_extruder<?=$codigo[$reg]?>[]"/></td>

                          <td align="left"><input class="text2" type="text" name="kg_extruder<?=$codigo[$reg]?>[]" id="<?=$a?>_extr<?=$id[$reg]?>" onkeyup="addTextExtruder(<?=$codigo[$reg]?>,<?=$id[$reg]?>,<?=$a?>,<?=$_REQUEST['turno']?>);" onchange=" sumar('<?=$id[$reg]?>');  sumarExtr(); kh(<?=$_REQUEST['turno']?>);"/></td>

				  </tr>

                  <tr>

                  	<td colspan="2"style="padding:0px; margin:0px;">

                  	<div style="padding:0px; margin:0px;" id="texto<?=$id[$reg]?>"></div>		

                  </td>

                  </tr>

				<?php } ?>

					<tr>

						<td height="20" style="font-size:10px"><strong>Subtotales</strong></td>

						<td><input style="width:100px" type="text" name="subtotal_extruder<?=$codigo[$reg]?>" id="subtotal_<?=$id[$reg]?>_extr_Totalh" onChange="sumarExtr();"  readonly="readonly" /></td>

					</tr>

		  </table>

            

  <? $reg++;}?></td><? }?>

</tr>

<tr>

  <td align="left" valign="top">&nbsp;</td>

</tr>

</table>

  <? }      ?>

  </p>

  <br />

    </div>

 <CENTER>
        <h1 width="100%"  style="color:#000;">PRODUCCION BD</h1>
        <HR>
        	
<?
$a=0;
while($dat_extr_bd = mysql_fetch_assoc($res_lic_bd))
	{

				$codigo_bd[$a]=$dat_extr_bd['numero'];

				$id_bd[$a] 	= $dat_extr_bd['id_maquina'];

				$a++;

	}
   $reg=0;

  // print_r ($codigo_bd);print_r ($id_bd);

?>

<table border="0" width="100%">
<tr>
<? for($x=1;$x<=4; $x++){?>

    <td width="220" align="left" valign="top"><br />

     <? if($reg<$cant_lic_bd){ ?>		

		<?

		    $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."' AND operadores.status = '0'  and operadores.activo='0' ";

			$rOperadorextr = mysql_query($qOperadorextr);

			$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);  ?>
    

	 		<input type="hidden"  name="id_operador_bd<?=$codigo_bd[$reg]?>" value="<?=$dAsignacionextr['id_operador'] ?>" />

            <input type="hidden" name="id_maquina_bd<?=$codigo_bd[$reg]?>" value="<?=$id_bd[$reg]?>" />

            <input type="hidden" name="codigos_bd[]" value="<?=$codigo_bd[$reg]?>" />

		<h3 align="left"   style="background-color:#FE642E;color: rgb(255, 255, 255);">Extruder <?=$codigo_bd[$reg];echo ' ('.$id_bd[$reg].')';?><br />

		<select style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; width:175px"   name="id_operador<?=$codigo_bd[$reg]?>" >

        <option value="0">No hay operador</option>

		<? 
         // llena el combo con los operadores activos de la tabla
		$qOperador = "SELECT * FROM operadores WHERE status = '0' AND area ='1'  AND activo = 0 ORDER BY nombre ASC";

		$rOperador = mysql_query($qOperador);

		while ($dOperador = mysql_fetch_assoc($rOperador))
		{ ?>

		<option value="<?=$dOperador['id_operador'] ?>" <? if($dAsignacionextr['id_operador'] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>

        <? } ?>

        </select>

         </h3>

		<table width="203" height="65" cellpadding="0" cellspacing="0" >

        <tr>

			<td width="75"><strong>O. T.</strong></td>

			  <td width="126" ><strong>Kilogramos</strong></td>

		      </tr>

				<?php for($a=1;$a<=$nEntradas;$a++) { ?>

					<tr>

                          <td align="left" ><input class="text" type="text" name="ot_extruder_bd<?=$codigo_bd[$reg]?>[]"/></td>
                          <!--
                          <td align="left"><input class="text2" type="text" name="kg_extruder<?=$codigo_bd[$reg]?>[]" id="<?=$a?>_extr_bd<?=$id_bd[$reg]?>" onkeyup="addTextExtruder(<?=$codigo_bd[$reg]?>,<?=$id_bd[$reg]?>,<?=$a?>,<?=$_REQUEST['turno']?>);" onchange="sumar('<?=$id_bd[$reg]?>');  sumarExtr(); kh(<?=$_REQUEST['turno']?>);"/></td>

                          <td align="left"><input class="text2" type="text" name="kg_extruder<?=$codigo[$reg]?>[]"    id="<?=$a?>_extr<?=$id_bd[$reg]?>"  onkeyup="addTextExtruder(<?=$codigo_bd[$reg]?>,<?=$id_bd[$reg]?>,<?=$a?>,<?=$_REQUEST['turno']?>);"  onchange=" sumar('<?=$id[$reg]?>');  sumarExtr(); kh(<?=$_REQUEST['turno']?>);"/></td>
                           -->
                           <td align="left"><input class="text2" type="text" name="kg_extruder_bd<?=$codigo_bd[$reg]?>[]"    id="<?=$a?>_extr_bd<?=$id_bd[$reg]?>"  onkeyup="addTextExtruder_bd(<?=$codigo_bd[$reg]?>,<?=$id_bd[$reg]?>,<?=$a?>,<?=$_REQUEST['turno']?>);"  onchange=" sumar_bd('<?=$id_bd[$reg]?>');  sumarExtr_bd(); kh_bd(<?=$_REQUEST['turno']?>);"/></td>
				  </tr>

                  <tr>

                  	<td colspan="2"style="padding:0px; margin:0px;">

                  	<div style="padding:0px; margin:0px;" id="texto<?=$id_bd[$reg]?>"></div>		

                  </td>

                  </tr>

				<?php } ?>

					<tr>

						<td height="20" style="font-size:10px"><strong>Subtotales</strong></td>

						<td><input style="width:100px" type="text" name="subtotal_extruder_bd<?=$codigo_bd[$reg]?>" id="subtotal_<?=$id_bd[$reg]?>_extr_Total_bd" onChange="sumarExtr_bd();"  readonly="readonly" /></td>

					      </tr>

		  </table>           

  <? $reg++;}?></td><? }?>

</tr>

<tr>

  <td align="lef   t" valign="top">&nbsp;</td>

</t      r>


</tr>	
</table>        		
        <HR>
        </CENTER>
  <br />

  		<div id="datosgenerales" style="background-color:#FFFFFF;">

  		  <table  align="center">

                    <tr>

                      <td align="right" style="color:#0000ff;"><strong>Total  produccion HD:</strong></td>

                      <td style="color:#0000ff;"><input name="total_extruder" readonly type="text" size="20" id="total_extr"  value="" /></td>

                      <td align="right" style="color:#0000ff;"><strong>Kilogramos/Hora HD</strong></td>

                      <td style="color:#0000ff;"><input type="text" readonly="readonly" size="20" name="k_h" id="k_h" value=""	 />

                </tr>
                    <tr>

                      <td align="right" ><strong>Desperdicio Tira HD:</strong></td>

                      <td width="162" "><input name="desperdicio_tira_extruder" type="text" size="20" id="desperdicio_tira_extruder" /></td>

                      <td width="132" align="right" class="style7"><strong>Desperdicio Duro HD:</strong></td>

                      <td width="162" ><input name="desperdicio_duro_extruder" type="text" size="20" id="desperdicio_duro_extruder" /></td>

            	</tr>
            	
                    <tr>

                      <td align="right" class="style7"><strong>Total  produccion BD:</strong></td>

                      <td class="style7"><input name="total_extruder_bd" readonly type="text" size="20" id="total_extr_bd"  value="" /></td>

                      <td align="right" class="style7"><strong>Kilogramos/Hora BD</strong></td>

                      <td class="7"><input type="text" readonly="readonly" size="20" name="k_h_bd" id="k_h_bd" value=""	 />

                </tr>

                    <tr>

                      <td align="right" class="style7"><strong>Desperdicio Tira BD:</strong></td>

                      <td width="162" class="style7"><input name="desperdicio_tira_extruder_bd" type="text" size="20" id="desperdicio_tira_extruder_bd" /></td>

                      <td width="132" align="right" class="style7"><strong>Desperdicio Duro BD:</strong></td>

                      <td width="162" class="style7"><input name="desperdicio_duro_extruder_bd" type="text" size="20" id="desperdicio_duro_extruder_bd" /></td>

            	</tr>
            	

				<tr>

					<td width="149" align="right" class="style7"><strong>Observaciones:</strong></td>

					<td class="style7" colspan="3"><textarea class="style5" name="observaciones_extruder" id="observaciones_extr" rows="4" cols="40"></textarea></td>

				</tr>

          </table>

        <br />

      </div>            

		  </div>


   <div id="barraSubmit" style="background-color:#FFFFFF; text-align:center;">

	<input type="submit" name="guardar" value="Guardar" onClick="javascript: return confirm('Usted está a punto de pasar a la siguiente \netapa de registro de produccion, si desea realizar cambios seleccione cancelar,\npara continuar dar click en aceptar.');" />

  </div>

		<?php } ?>



  </div>

	</div>
	  </div>

</form>

<?php } if($nuevo) { ?>

<script type="text/javascript">
<!--



// -->	

</script>

<?php } if($registrado){ ?>

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

	  <input type="button" value="Aceptar" onClick="javascript: history.go(-2); " />

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

<? if($metasX){?>

<form name="metas" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=nuevo" id="super" method="post" >

<table width="706" align="center" cellpadding="2" cellspacing="2">

<tr>

    	<td width="142"></td>

    </tr>

     <tr style="background-color:#FF6633">

     	<td align="center" class="style7" style="color:#FFFFFF">M&aacute;quina</td>

       <td width="163" align="center" class="style7" style="color:#FFFFFF">Producci&oacute;n</td>

       <td width="78" align="center" class="style7" style="color:#FFFFFF">Meta</td>

	   <td width="89" align="center" class="style7" style="color:#FFFFFF">Diferencia</td>

       <td width="210" align="center">&nbsp;</td>

	</tr>

    <?

	

	

	$ID_ORDENPRODUCCION	=	$_GET['id_entrada_general'];



	$qFecha	=	"SELECT fecha FROM orden_produccion LEFT JOIN entrada_general ON orden_produccion.id_entrada_general = entrada_general.id_entrada_general WHERE id_orden_produccion = ".$ID_ORDENPRODUCCION."";

	$rFecha = 	mysql_query($qFecha);

	$dFecha	=	mysql_fetch_assoc($rFecha);

	

	$fechass	=	fecha_tablaInv($dFecha['fecha']);

	$fecha 	= explode("-", $dFecha['fecha']);

		

		$mes 	= 	$fecha[1];

		$ano 	=	$fecha[0];	



	 			 $qMetas	=	"SELECT * FROM meta WHERE mes = '".$ano."-".$mes."-01' AND ano =".$fecha[0]." AND area = '1'";

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

	

		 

	 $qMaquinas	=	"SELECT * FROM resumen_maquina_ex INNER JOIN metas_maquinas ON resumen_maquina_ex.id_maquina = metas_maquinas.id_maquina LEFT JOIN maquina ON resumen_maquina_ex.id_maquina = maquina.id_maquina  WHERE metas_maquinas.id_meta = ".$dMetas['id_meta']." AND resumen_maquina_ex.id_orden_produccion = '".$ID_ORDENPRODUCCION."' ORDER BY maquina.numero ASC";

	 $rMaquinas	=	mysql_query($qMaquinas);

	 	for($z = 0; $dMaquinas	=	mysql_fetch_assoc($rMaquinas); $z++){

		

			

					?>

							<tr <? if(bcmod($z,2)==0) echo "bgcolor='#DDDDDD'"; else echo ""; ?>>

                            	<td align="left"><STRONG><? echo $dMaquinas['numero']." ".$dMaquinas['marca']  ?></STRONG></td>

                                <td align="right"><STRONG><?=$dMaquinas['subtotal']?></STRONG></td>

                              <td align="right"><STRONG><? 

				 if($_REQUEST['turno'] == '1') $turno = 8; if($_REQUEST['turno'] == '2') $turno = 7; if($_REQUEST['turno'] == '3') $turno =  9; 										 $jaja = ($dMaquinas['diaria']/24)*

											 	

												$meta = ($dMaquinas['diaria']/24)*$turno;

											echo floor($meta) ?></STRONG></td>

                              <td align="right"><STRONG><?  $tada = ($dMaquinas['subtotal'] - $meta);

								echo floor($tada);?></STRONG></td>

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



  <tr>

    	<td colspan="1">

     <?  $qMaquinas2	=	"SELECT SUM(subtotal) FROM resumen_maquina_ex INNER JOIN metas_maquinas ON resumen_maquina_ex.id_maquina = metas_maquinas.id_maquina LEFT JOIN maquina ON resumen_maquina_ex.id_maquina = maquina.id_maquina  WHERE metas_maquinas.id_meta = ".$dMetas['id_meta']." AND resumen_maquina_ex.id_orden_produccion = '".$ID_ORDENPRODUCCION."' ORDER BY resumen_maquina_ex.id_maquina ASC";

	 	 $rMaquinas2	=	mysql_query($qMaquinas2);

		 $dMaquinas2	= mysql_fetch_row($rMaquinas2);

		 ?>PRODUCCION TOTAL</td>

         <td align="right"><?=$dMaquinas2[0];?></td>

      <td align="right"><? echo floor($metasss = ($dMetas['total_dia']/24)*$turno);?></td>

      <td align="right"><? echo floor($nuevo = $dMaquinas2[0] -  $metasss);

	  				?></td>

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

	

	$qFecha	=	"SELECT fecha FROM orden_produccion LEFT JOIN entrada_general ON orden_produccion.id_entrada_general = entrada_general.id_entrada_general WHERE id_orden_produccion = ".$ID_ORDENPRODUCCION."";

	$rFecha = 	mysql_query($qFecha);

	$dFecha	=	mysql_fetch_assoc($rFecha);

	

	$fechass	=	fecha_tablaInv($dFecha['fecha']);

	$fecha 	= explode("-", $dFecha['fecha']);

	

	$mes 	= 	$fecha[1];

	$ano 	=	$fecha[0];

	

	$mes_array	= 	array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

?>

<table width="850" align="center" cellpadding="2" cellspacing="2" border="0">

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

				 AND area = '1'";

	 $rstMeta	=	mysql_query($qryMeta);

	 $rowMeta	=	mysql_fetch_assoc($rstMeta);

	 if(is_null($rowMeta['prod_hora'])){

		 echo "No se ha capturado la PRODUCCION POR HORA para EXTRUDER del mes de ".strtoupper($mes_array[(int)$mes])." en la secci&oacute;n de metas";

		 exit();

	 }

	 /**/

	 $qrySuper	=	"SELECT s.nombre, 

	 						SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as horas_trabajadas,

							ROUND(AVG(p.k_h),0) as kgh,

							SUM(p.total) as produccion,

							".$rowMeta['prod_hora']."*SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as meta_prod,

							SUM(p.total)-".$rowMeta['prod_hora']."*SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as prod_contra_meta,

							SUM(p.desperdicio_tira)+SUM(p.desperdicio_duro) as desperdicio,

							(".$rowMeta['porcentaje_desp']."*SUM(p.total))/100 as meta_desp,

							(SUM(p.desperdicio_tira)+SUM(p.desperdicio_duro))-((".$rowMeta['porcentaje_desp']."*SUM(p.total))/100 ) as desp_contra_meta

					FROM entrada_general e, orden_produccion p, supervisor s

					WHERE e.id_entrada_general = p.id_entrada_general

					AND e.id_supervisor = s.id_supervisor

					AND fecha BETWEEN '$ano-$mes-01' AND '$ano-$mes-".UltimoDia($ano,$mes)."'

					AND e.impresion = '0'

					AND e.repesada = '1'

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

	 						COUNT(e.turno) as num_turnos,

	 						SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as horas_trabajadas,

							ROUND(AVG(p.k_h),0) as kgh,

							SUM(p.total) as produccion,

							".$rowMeta['prod_hora']."*SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as meta_prod,

							SUM(p.total)-".$rowMeta['prod_hora']."*SUM(IF(e.turno=1,8,IF(e.turno=2,7,9))) as prod_contra_meta,

							SUM(p.desperdicio_tira)+SUM(p.desperdicio_duro) as desperdicio,

							(".$rowMeta['porcentaje_desp']."*SUM(p.total))/100 as meta_desp,

							(SUM(p.desperdicio_tira)+SUM(p.desperdicio_duro))-((".$rowMeta['porcentaje_desp']."*SUM(p.total))/100 ) as desp_contra_meta

					FROM entrada_general e, orden_produccion p, supervisor s

					WHERE e.id_entrada_general = p.id_entrada_general

					AND e.id_supervisor = s.id_supervisor

					AND fecha BETWEEN '$ano-$mes-01' AND '$ano-$mes-".UltimoDia($ano,$mes)."'

					AND e.impresion = '0'

					AND e.repesada = '1'

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

        <td colspan="10" valign="top" style="text-align:center; padding-top:15px;"><a href="<?=$_SERVER['HOST']?>?seccion=<?=isset($_SESSION['id_admin'])?'43':'40'?>&area=area&mes=<?=$mes?>&ano=<?=$ano?>">| VER REPORTE DE PROMEDIOS |</a></td>

    </tr>

</table>

 <br />

<br />

</form>

<? } ?>