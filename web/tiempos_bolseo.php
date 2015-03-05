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






$tabla	=	"maquina";

$tabla2 =	"supervisor";

$tabla3 =	"operador";



$indice		=	"id_maquina";

$indice2	=	"id_supervisor";

$indice3	=	"id_operador";



$debug = false;



if($debug)

	echo "<pre>";





if(isset($_POST['guardar']))

{





	$_POST['fecha']	=	preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

	$fallo_general	=	$_POST['fallo_general'];



	$qBolseo	=	"INSERT INTO bolseo (id_supervisor,fecha,kilogramos,millares,dtira,dtroquel,segundas,turno, autorizada,area,actualizado, repesada, rol,m_p,observaciones) VALUES ".

					"('{$_SESSION[id_supervisor]}','{$_POST[fecha]}','{$_POST[tkg]}','{$_POST[tml]}','{$_POST[tdt]}','{$_POST[tdtr]}','{$_POST[tse]}','{$_POST[turno]}','1', '{$_REQUEST[area]}','{$_REQUEST[repe]}','{$_REQUEST[repe]}' , '{$_REQUEST[rol]}','{$_POST[m_p]}', '{$_POST[observaciones]}' )";	

	

	

	pDebug($qBolseo);

	$rBolseo	=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");





	$qIdMaxOp	=	"SELECT MAX(id_bolseo) FROM bolseo";

	$rIdMaxOp	=	mysql_query($qIdMaxOp);

	$dIdMaxOp	=	mysql_fetch_row($rIdMaxOp);	

	$id_pro		=	$dIdMaxOp[0];

	

	$fecha	=	$_REQUEST['fecha'];

	$turno	=	$_REQUEST['turno'];	

	



	$nMaquinas	=	sizeof($_POST['codigos2']);

	

	for($i=0;$i<$nMaquinas;$i++)

	{

		$ID_GENERAL			=	$id_pro;

		

		$sufijo				=	$_POST['codigos2'][$i];

		$id_maquina			=	intval($_POST['id_maquina2_'.$sufijo]);

		$id_operador		=	intval($_POST['id_operador2_'.$sufijo]);

		

		if($_POST['fp_bolseo'.$sufijo] == 1){

			if($_REQUEST['turno'] == '1') $falta_personal = "08:00:00"; 

			if($_REQUEST['turno'] == '2') $falta_personal =	"07:00:00"; 

			if($_REQUEST['turno'] == '3') $falta_personal =	"09:00:00";	

		}

		

		if($_POST['fp_bolseo'.$sufijo] != 1){

		$falta_personal		=	"00:00:00";

		}	

			

			

		if($_REQUEST['fallo_general'] == 1){

		$horas 	= $_POST['horas_fallo'];

		$minutos	=	$_POST['minutos_fallo'];

		$fallo_electrico 	=	$horas.':'.$minutos.':00';

		} else {

		$fallo_electrico	=	'00:00:00';

		}

		$mantenimiento		=	$_POST['mh_bolseo'.$sufijo].':'.$_POST['mm_bolseo'.$sufijo].':00';

		$observacion		=	$_POST['ob_bolseo'.$sufijo];

		$otras				=	$_POST['oh_bolseo'.$sufijo].":".$_POST['om_bolseo'.$sufijo].':00';



		$qImpresion			=	"INSERT INTO tiempos_muertos (id_produccion, id_maquina, id_operador, falta_personal, observaciones, fallo_electrico, mantenimiento, cambio_impresion, tipo,otras) VALUES ".

								"('$ID_GENERAL','$id_maquina','$id_operador','$falta_personal', '$observacion', '$fallo_electrico','$mantenimiento', '0','4','$otras')";

		pDebug($qImpresion);

		$rImpresion			=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");

		$ID_RES_MAQUINAIM	=	mysql_insert_id();

		

	}





	pDebug("Terminado");

	pDebug("Comienza volcado de la petición");





	if(isset($_SESSION['admindolfra']) ){ 

	echo '<script laguaje="javascript">location.href=\'admin.php?seccion=3&id_bolseo='.$id_pro.'&accion=nuevo&turno='.$turno.'&fecha='.$fecha.'\';</script>';

	} 

	if(!isset($_SESSION['admindolfra']) ){ 

	echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion=3&id_bolseo='.$id_pro.'&accion=nuevo&turno='.$turno.'&fecha='.$fecha.'\';</script>';

	}  	

	

	

	



}



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

<? if($supervisor){?>

<form name="super" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=nuevo&id=<?=$_REQUEST['id']?>" id="super" method="post" >

<table width="404"  align="center">

	<? if(isset($_SESSION['id_admin'])){?>

  <tr>

		<td width="157" align="right">Elija un Supervisor:</td>

		<td width="235" align="left"><select name="id_supervisor" id="supervisor">

    		<? 

						$qSupervisor = "SELECT * FROM supervisor WHERE area3 = 1 ORDER BY nombre ASC;";

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
		<td colspan="2" align="right"><input type="submit" value="Aceptar" class="button1" /></td>

    </tr>

</table>

<br />

<br />

</form>

<?php } if($nuevo) {





	$fecha	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);



$qValidacion = "SELECT * FROM bolseo WHERE fecha = '".$fecha."' AND turno = '".$_REQUEST['turno']."'";

$rValidacion = mysql_query($qValidacion);

$nValidacion = mysql_num_rows($rValidacion);







//if (!isset($_SESSION['admindolfra'])){

	if($nValidacion  >= 1  && isset($_SESSION['admindolfra'])){

	

		echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=registrado\';</script>';



	}

	if($nValidacion  >= 1  && !isset($_SESSION['admindolfra'])){

	

		echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion=3&accion=registrado\';</script>';



	}

	

//}

 ?>

<script language="javascript">



</script>

<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

<div id="container">

  <div id="content">

		<div id="datosgenerales" style="background-color:#FFFFFF;" align="left">

			<p>

				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$_SESSION['nombre']?>" readonly="readonly" class="datosgenerales"/><br />

				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=$_REQUEST['fecha']?>" id="fecha" class="datosgenerales" readonly="readonly"  /><br />

                <label for="fecha">Turno</label>

                              <input type="text" name="turno_bla" id="turno_bla" class="datosgenerales" value="<? if($_REQUEST['turno'] == '1') echo "Matutino";

																							 if($_REQUEST['turno'] == '2') echo "Vespertino";

																							 if($_REQUEST['turno'] == '3') echo "Nocturno";?>" readonly="readonly">

       <br /><br /><br />

       			<h1><input type="checkbox" name="fallo_general" value="1" onclick="javascript: muestra(1001);" />

       			Fallo General El&eacute;ctrico </h1>

                <div class="style5" id="div_1001" style="display:none; float:left">Tiempo de fallo: <input type="text" name="horas_fallo" size="2" />:<input type="text" name="minutos_fallo"  size="2" />Hrs.<br />

                </div><br /><br />

       			<input type="hidden" name="turno" value="<?=$_REQUEST['turno']?>" id="turno" />

                <input type="hidden" name="area" id="area" value="2" />

                 <input type="hidden" name="rol" value="<?=$_SESSION['rol']?>">

			</p>

    	

    		<p align="center"><strong><br />

   		    TIEMPOS MUERTOS DE BOLSEO - Nave 2</strong><br />

   		    <br />

    		</p>

            

            

         <p align="center">          

           

		<?  

		 	$sql_lic= "SELECT * FROM maquina  WHERE area = 1  ORDER BY numero ASC  ";

			$res_lic=mysql_query($sql_lic);

			$cant_lic=mysql_num_rows($res_lic);

			$cant=ceil($cant_lic/1);

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

<table width="760" align="center" style="border:#CCCCCC; border:thin">

<tr>

    <? for($x=1;$x<=1; $x++){?>

<td width="750" align="center" valign="top"><br />

  <? if($reg<$cant_lic){ ?>		



  <?

		

		    $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";

			$rOperadorextr = mysql_query($qOperadorextr);

			$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);?>

		<input type="hidden"  name="id_operador2_<?=$codigo[$reg]?>" value="<?=$dAsignacionextr['id_operador'] ?>" />

        <input type="hidden" name="id_maquina2_<?=$codigo[$reg]?>" value="<?=$id[$reg]?>" />

        <input type="hidden" name="codigos2[]" value="<?=$codigo[$reg]?>" />

		<h3 align="left" style="color: rgb(255, 255, 255);"// background-color: rgb(127,127,127);"  onclick="muestra('<?=$id[$reg]?>')"  class="Tips4" title="Click aqui para abrir o cerrar::">MAQUINA <?=$codigo[$reg]?></h3>

<div id="div_<?=$id[$reg]?>" style="display:none">

<table width="733" height="137" align="left" >

					<tr>

						<td height="39" align="right"><strong>Observaciones</strong></td>

					  <td colspan="3" align="left"><textarea name="ob_bolseo<?=$codigo[$reg]?>" id="ob_bolseo<?=$codigo[$reg]?>" cols="55" rows="2" /></textarea></td>

                    </tr>

<!--                  	<tr>

           	  			<td align="right" class="style7">Falta de Personal :</td>

                		<td width="592" align="left"><input type="checkbox" name="fp_bolseo<?=$codigo[$reg]?>" value="1"/></td>

                   </tr>                              

-->                    <tr>

           	  			<td width="129" align="right" class="style7">Otras causas :</td>

               		  	<td width="592" align="left"><input type="text" name="oh_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" />:

                        				<input type="text" name="om_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" /></td>

       			  </tr>

             		<tr>

           	  			<td align="right" class="style7">Mantenimiento :</td>

               			<td width="592" align="left"><input type="text" name="mh_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" />:

                        				<input type="text" name="mm_bolseo<?=$codigo[$reg]?>" size="2" maxlength="2" value="00" /></td>

                  </tr>

		  </table></div>



  <? $reg++;}?></td><? }?>

</tr>

</table>

  <? }?>

  </p>    

        <br /><br />

           <p align="center">                             

          </p>

<div id="barraSubmit" style="background-color:#FFFFFF; text-align:right;">

			<input type="submit" name="guardar" value="Guardar" onclick="javascript: return confirm('Usted está a punto de pasar a la siguiente \netapa de registro de produccion, si desea realizar cambios seleccione cancelar,\npara continuar dar click en aceptar.');" />

  </div>

  </div>

		</div></div>

</form>

<?php } ?>

<?php if($nuevaEntrada) { ?>

<table align="center" width="100%">

<tr>

	<td align="center">

		<div class="tablaCentrada">

			<p><br />

			  <br />

		    Se registraron los datos en el sistema.<br />

			<br />

			<br />

			</p>

		</div>

    </td>

</tr>

</table>

<?php } ?>