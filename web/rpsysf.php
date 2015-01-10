<style> @import 'style.css'; </style>

<?php

	include("libs/conectar.php");



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






if(isset($_POST['submit']))

{



	$debug = false;

	if($debug)

	{	

		echo "<pre>";

		print_r($_REQUEST);

		

	}

	

	$nMaquinas	=	sizeof($_POST['id_maquinas']);

	

	$_POST['fecha']	=	preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$_POST['fecha']);

	

	$ID_BOLSEO	=	$_POST['id_bolseo'];



	$qBolseo	=	"UPDATE rpsysf SET id_supervisor = '{$_SESSION[id_supervisor]}', fecha = '{$_POST[fecha]}', kilogramos = '{$_POST[tkg]}' ,millares = '{$_POST[tml]}' ,dtira = '{$_POST[tdt]}' ,dtroquel = '{$_POST[tdtr]}',segundas = '{$_POST[tse]}' ,turno = '{$_POST[turno]}', /*autorizada = '0',*/ area = '{$_REQUEST[area]}',actualizado = '{$_REQUEST[repe]}', repesada = '{$_REQUEST[repe]}', rol =  '{$_REQUEST[rol]}' ,m_p = '{$_POST[m_p]}',observaciones = '{$_POST[observaciones]}' WHERE id_bolseo = '".$ID_BOLSEO."'";	

	pDebug($qBolseo);

//	$rBolseo	=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");







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

				



		$qResumen		=	"INSERT INTO resumen_maq_rpsysf (id_bolseo,kilogramos,millares,dtira,dtroquel,segundas,id_maquina,id_operador) VALUES ".

							"($ID_BOLSEO,$sKilogramos,$sMillares,$sTira,$sTroquel,$sSegundas,$id_maquina, $id_operador)";

		pDebug($qResumen);

		$rResumen		=	mysql_query($qResumen) OR die("<p>$qresumen</p><p>".mysql_error()."</p>");

		$ID_RESUMEN		=	mysql_insert_id();



		$nDetalles		=	sizeof($_POST["kg_$sufijo"]);



		$qDetalles		=	"INSERT INTO detalle_resumen_maq_rpsysf (id_resumen_maquina_bs,kilogramos,millares,dtira,dtroquel,segundas,orden,kam,factor) VALUES";

		$eDetalles		=	"";

		

		for($a=0,$b=1,$fDetalles=false;$a<$nDetalles;$a++,$b++)

		{

			// Evitamos entradas nulas

			if(

				empty($_POST["kd_$sufijo"][$a]) && 

				empty($_POST["ml_$sufijo"][$a]) &&

				empty($_POST["dt_$sufijo"][$a]) &&

				empty($_POST["dtdr_$sufijo"][$a]) &&

				empty($_POST["se_$sufijo"][$a]) &&

		        empty($_POST["ot_$sufijo"][$a])	&&

		        empty($_POST["kam_$sufijo"][$a]) &&

		        empty($_POST["factor_$sufijo"][$a])	

			) continue;

			

			$fDetalles	=	true;

			

			$kg			=	floatval($_POST["kg_$sufijo"][$a]);

			$ml			=	floatval($_POST["ml_$sufijo"][$a]);

			$dt			=	floatval($_POST["dt_$sufijo"][$a]);

			$dtr		=	floatval($_POST["dtr_$sufijo"][$a]);

			$se			=	floatval($_POST["se_$sufijo"][$a]);

			$orden		=	$_POST["ot_$sufijo"][$a];

			$kam 		=	$_REQUEST["kam_$sufijo"][$a];

			

			$factor		=	$_POST["factor_$sufijo"][$a];



			$eDetalles	.=	(!empty($eDetalles))?",":" ";

			$eDetalles	.=	"('$ID_RESUMEN','$kg','$ml','$dt','$dtr','$se','$orden','$kam','$factor')";

		}



		if($fDetalles)

		{

			$qDetalles	.=	$eDetalles;

			pDebug($qDetalles);

			$rDetalles	=	mysql_query($qDetalles) OR die("<p>$qDetalles</p><p>".mysql_error()."</p>");

		}

	}

	

	



	if(isset($_SESSION['admindolfra']) ){ 

		echo '<script language ="javascript">location.href=\'admin.php?seccion=3&accion=metas&id_entrada_general='.$ID_BOLSEO.'&turno='.$_POST['turno'].'\';</script>';

	} 

	if(!isset($_SESSION['admindolfra']) ){ 

		echo '<script language ="javascript">location.href=\'admin_supervisor.php?seccion=3&accion=metas&id_entrada_general='.$ID_BOLSEO.'&turno='.$_POST['turno'].'\';</script>';

	} 	  

	 

	

}



if(!empty($_GET['accion']))

{

	$metas			= ($_GET['accion']=="metas"	)?true:false;

	$supervisor	= ($_GET['accion']=="supervisor")?true:false;

	$nuevo			=	($_GET['accion']=="nuevo"		)?true:false;

	$registrado		= ($_GET['accion']=="registrado")?true:false;

	$nuevaEntrada	=	($_GET['accion']=="nuevaentrada")?true:false;

}



if($nuevo)

{

	$nEntradas	=	1;

	

 	 $sql_lic= " SELECT * FROM maquina WHERE area = 5 or area = 6 ORDER BY area,numero ASC  ";

	$res_lic=mysql_query($sql_lic);

	

}



?>



<script type="text/javascript" language="javascript" >







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

<script language="javascript" type="text/javascript">

			function checar(x,b){

			if(document.getElementById('sel_'+x+'_'+b).checked == true )

				document.getElementById('kam_'+x+'_'+b).value =' 1';

			else 

				document.getElementById('kam_'+x+'_'+b).value = '0';

				}

		

</script>

<?php if($nuevo){ ?>

<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post" >

<div  id="container" style="margin:0px auto">

	<div id="content">

		<?php if($nuevo) { ?>

		<div id="datosgenerales" style=" width:790px" align="left">

			<p>

				<label for="supervisor">Supervisor</label><input type="text" id="supervisor" value="<?=$_SESSION['nombre']?>" readonly="readonly" class="datosgenerales"/><br />

				<label for="fecha">Fecha</label><input type="text" name="fecha" value="<?=$_REQUEST['fecha']?>" id="fecha" class="datosgenerales" readonly="readonly"  /><br />

                <label for="fecha">Turno</label>

                <input type="text" name="turno_bla" id="turno_bla" class="datosgenerales" value="<? if($_REQUEST['turno'] == '1') echo "Matutino";

				if($_REQUEST['turno'] == '2') echo "Vespertino";

				if($_REQUEST['turno'] == '3') echo "Nocturno";?>" readonly="readonly">

       			<br />

       			<input type="hidden" name="turno" value="<?=$_REQUEST['turno']?>" id="turno" />

                <input type="hidden" name="area" id="area" value="2" />

                <input type="hidden" name="rol" value="<?=$_SESSION['rol']?>">

                <input type="hidden" name="id_bolseo" value="<?=$_REQUEST['id_bolseo']?>" />

                <br />

                <h1>Este reporte esta repesado.<input type="checkbox" name="repe" id="repe" value="1" />Si.</h1>

			</p>

		</div>

		<br /><br />

	<div align="center" style="width:100%">

    		<?php while($dat_lic=mysql_fetch_assoc($res_lic)){ ?>

        <div class="tablaCentrada" align="center" style=" width:730px" >

		<?



			$qOperador 	= 	" SELECT oper_maquina.id_operador, nombre FROM oper_maquina ".

							" INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador ".

							" WHERE id_maquina = ".$dat_lic['id_maquina']."  AND oper_maquina.rol = '".$_SESSION['rol']."' ".

							" AND operadores.status = '0' AND activo = 0 ";

			$rOperador = mysql_query($qOperador);

			$dAsignacion = mysql_fetch_assoc($rOperador);

		

		 ?>

		<input type="hidden" name="id_maquinas[]" value="<?=$dat_lic['id_maquina']?>" />

        <input type="hidden"  value="<?=$dAsignacion['id_operador'] ?>" />

		<h3 align="left" ><?if($dat_lic['area'] ==5) echo 'RPS';else echo 'SF';?> <?=$dat_lic['numero']?>:		

		<select class="datosgenerales" name="id_operadores[]"  style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px"  >

         <option value="0">No hay operador</option>

		<? 

		$qOperador = "SELECT * FROM operadores WHERE status = '0' AND area = '6' or area='7'  AND activo = 0 ORDER BY nombre ASC";

		$rOperador = mysql_query($qOperador);

		while ($dOperador = mysql_fetch_assoc($rOperador))

		{ ?>

		<option value="<?=$dOperador['id_operador'] ?>" <? if($dAsignacion['id_operador'] == $dOperador['id_operador'] ) echo "Selected"; ?> ><?=$dOperador['nombre'] ?></option>

        <? } ?>

        </select></h3>



		<div align="center" id="tabla_<?=$dat_lic['id_maquina']?>">

			<table width="730" align="center" border="0" class="tablaCentrada">

				<tr>	

					<td width="85" align="center"><b>O. T.</b></td>

              		<td width="54" align="center"><b>Kg a Mi.</b></td>

                  	<td width="56" align="center"><b>Factor</b></td>

                    <td width="80" align="center" ><b>Millares</b></td>

				  	<td width="80" align="center" ><b>Kilogramos</b></td>

			  	  	<td width="81" align="center" ><b>D. Tira</b></td>

				    <td width="81" align="center" ><b>Dens.</b> </td>

			  	</tr>

				<?php for($a=1;$a<=$nEntradas;$a++) { ?>

					<tr >

					  <td ><input type="text" class="numeros" id="ot_<?=$dat_lic['id_maquina']?>_<?=$a?>" name="ot_<?=$dat_lic['id_maquina']?>[]" onFocus="this.select()" onBlur="convertir(<?=$dat_lic['id_maquina']?>,<?=$a?>);" onChange="mh(<?=$_REQUEST['turno']?>);"  /></td>

					  <td align="center" >

                      <input type="checkbox" id="sel_<?=$dat_lic['id_maquina']?>_<?=$a?>" name="sel_<?=$dat_lic['id_maquina']?>[]" value="1" onClick="checar(<?=$dat_lic['id_maquina']?>,<?=$a?>)" />

                      <input type="hidden" value="0" id="kam_<?=$dat_lic['id_maquina']?>_<?=$a?>" name="kam_<?=$dat_lic['id_maquina']?>[]"   /></td>

					  <td align="center"><input type="text" size="4" id="factor_<?=$dat_lic['id_maquina']?>_<?=$a?>" name="factor_<?=$dat_lic['id_maquina']?>[]"  onChange="convertir(<?=$dat_lic['id_maquina']?>,<?=$a?>);" /></td>

   					  <td align="center" ><input type="text" class="numeros" id="ml_<?=$dat_lic['id_maquina']?>_<?=$a?>" 	name="ml_<?=$dat_lic['id_maquina']?>[]" 	onFocus="this.select()" 	onBlur="addTextBolseo2r(<?=$dat_lic['id_maquina']?>,<?=$a?>,<?=$_REQUEST['turno']?>); convertir(<?=$dat_lic['id_maquina']?>,<?=$a?>); sumaLocal(<?=$dat_lic['id_maquina']?>);" onChange="mh(<?=$_REQUEST['turno']?>);" /></td>

					  <td align="center"><input type="text" class="numeros" id="kg_<?=$dat_lic['id_maquina']?>_<?=$a?>" 	name="kg_<?=$dat_lic['id_maquina']?>[]" 	onFocus="this.select()"		onBlur="addTextBolseo2r(<?=$dat_lic['id_maquina']?>,<?=$a?>,<?=$_REQUEST['turno']?>); convertir(<?=$dat_lic['id_maquina']?>,<?=$a?>); sumaLocal(<?=$dat_lic['id_maquina']?>);" onChange="mh(<?=$_REQUEST['turno']?>);" /></td>

					  <td align="center"><input type="text" class="numeros" id="dt_<?=$dat_lic['id_maquina']?>_<?=$a?>" 	name="dt_<?=$dat_lic['id_maquina']?>[]" 	onFocus="this.select()" 	onBlur="sumaLocal(<?=$dat_lic['id_maquina']?>);" /></td>

                      <td align="center"> <input type="checkbox" id="sel_" name="x[]" value="1" onClick="" /> </td>



				  </tr>

                  <tr>

                  		<td colspan="8" style="padding:0px; margin:0px;">

                  			<div style="padding:0px; margin:0px;" id="texto<?=$dat_lic['id_maquina']?>"></div>

                  		</td>

                  </tr>

				<?php } ?>

					<tr>

						<td colspan="3" align="right"><strong>Subtotal HD</strong></td>

						<td align="center"><input type="text" id="mls_<?=$dat_lic['id_maquina']?>" 	name="mls_<?=$dat_lic['id_maquina']?>" 	value="0" class="subtotal" onFocus="this.select()" size="6"  /></td>

						<td align="center"><input type="text" id="kgs_<?=$dat_lic['id_maquina']?>" 	name="kgs_<?=$dat_lic['id_maquina']?>" 	value="0" class="subtotal" onFocus="this.select()" /></td>

						<td align="center"><input type="text" id="dts_<?=$dat_lic['id_maquina']?>" 	name="dts_<?=$dat_lic['id_maquina']?>" 	value="0" class="subtotal" onFocus="this.select()" /></td>


                    </tr>

					<tr>

						<td colspan="3" align="right"><strong>Subtotal BD</strong></td>

						<td align="center"><input type="text" id="mls_<?=$dat_lic['id_maquina']?>" 	name="mls_<?=$dat_lic['id_maquina']?>" 	value="0" class="subtotal" onFocus="this.select()" size="6"  /></td>

						<td align="center"><input type="text" id="kgs_<?=$dat_lic['id_maquina']?>" 	name="kgs_<?=$dat_lic['id_maquina']?>" 	value="0" class="subtotal" onFocus="this.select()" /></td>

						<td align="center"><input type="text" id="dts_<?=$dat_lic['id_maquina']?>" 	name="dts_<?=$dat_lic['id_maquina']?>" 	value="0" class="subtotal" onFocus="this.select()" /></td>


                    </tr>

			</table>

			</div>

        </div>

		<?php } ?>

        </div>

        <br /><br />

        <div id="datosgenerales" style="background-color:#FFFFFF;">

        <table align="center" border="0">

					<tr>

						<td align="right"><strong>Totales</strong>

						<td align="right"><input type="text" id="tml"  name="tml"  value="" class="total" readonly="readonly" /></td>

						<td align="center"><input type="text" id="tkg"  name="tkg"  value="" class="total" readonly="readonly" /></td>

						<td><input type="text" id="tdt"  name="tdt"  value="" class="total" readonly="readonly" /></td>


					</tr>

                    <tr>

                    	<td><strong>Millares por Hora:</strong></td>
                        <td align="right"><input type="text" id="m_p" name="m_p" value="" readonly="readonly" class="total" /></td>
                        <td></td><td></td>
                    </tr>

                    <tr>

                    	<td valign="top"><strong>Observaciones</strong>:</td>

                    	<td colspan="6" valign="top"><textarea name="observaciones" id="observaciones" cols="50" rows="6" ></textarea></td>

                    </tr>

        </table>

        <br />

        </div>

		<div id="barraSubmit" style="background-color:#FFFFFF; text-align:center;">

			<input type="submit" name="submit" value="Guardar" onClick="javascript: return confirm('Usted está a punto de pasar a la siguiente \netapa de registro de produccion, si desea realizar cambios seleccione cancelar,\npara continuar dar click en aceptar.');" />

		</div>

		<?php } ?>

		</div>

	</div>

</form>

<?php } if($registrado){ ?>

<table width="404" align="center">

	<tr>

		<td align="center" style="color:#CC3333"><br />

		  Lo sentimos pero ya se realizo un reporte con la fecha y turno indicado.<br />

		  <br />

</td>

	</tr>

	<tr>

	  <td colspan="2" align="center"><br />

	  <input type="submit" value="Aceptar" onClick="javascript: history.go(-2); " />

      <br /></td>

    </tr>

</table>

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

<table width="80%" border="0" cellpadding="2" cellspacing="2" align="center">



  <tr style="background-color:#FF6633">

    <td width="128" align="center" class="style7" style="color:#FFFFFF">Maquina</td>

    <td width="72" align="center" class="style7" style="color:#FFFFFF">Kilogramos</td>

    <td width="72" align="center" class="style7" style="color:#FFFFFF">Meta Kg</td>

    <td width="71" align="center" class="style7" style="color:#FFFFFF">Diferencia</td>

    <td width="86" class="style7" align="center" style="color:#FFFFFF">PRODUCCION </td>

    <td width="74" align="center" class="style7" style="color:#FFFFFF">Millares</td>	

    <td width="80" align="center" class="style7" style="color:#FFFFFF">Meta Millares</td>

    <td width="67" align="center" class="style7" style="color:#FFFFFF">Diferencia</td>

    <td width="86" class="style7" align="center" style="color:#FFFFFF">PRODUCCION </td>

  </tr>

  <?



		$anho 	= 	date('Y');

		$mes	=	date('m');

		

		$ultimo_dia = UltimoDia($anho,$mes);



	

	 $ID_ORDENPRODUCCION	=	$_GET['id_entrada_general'];

	 

	 			 $qMetas	=	"SELECT * FROM meta WHERE mes = '".date('Y-m-')."01' and area = '3'";

				 $rMetas	=	mysql_query($qMetas);

				 $dMetas	=	mysql_fetch_assoc($rMetas);

				 $nMetas	=	mysql_num_rows($rMetas);

				 

if( $nMetas < 1) { ?>

    <tr>

    	<td colspan="9"><br /><br /><br /><p style="color:#FF0000" align="center">NO HA SE HA ESPECIFICADO LA META PARA EL MES ACTUAL,<br />

         POR LO TANTO NO SE PUEDE GENERAR UN RESUMEN DE SUS ALTAS DE PRODUCCION, 

         <br />FAVOR DE COMUNICARLO AL ADMINISTRADOR DEL SISTEMA.</p>

         <br /><br /><br />

         <p>GRACIAS</p></td>

    </tr>

    

    

    <? } else { 

		



	$qnumM	=	"SELECT * FROM maquina WHERE area = '5' or area='6'";

	$rnumM	=	mysql_query($qnumM);

	$nnumM	=	mysql_num_rows($rnumM);



	 

	 $qMaquinas	=	"SELECT * FROM resumen_maquina_bs INNER JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina   WHERE resumen_maquina_bs.id_bolseo = '".$ID_ORDENPRODUCCION."' ORDER BY resumen_maquina_bs.id_maquina ASC";

	 $rMaquinas	=	mysql_query($qMaquinas);

	

	 	for($z = 0 ; $dMaquinas	=	mysql_fetch_assoc($rMaquinas); $z++){

			

					?>

  <tr <? if(bcmod($z,2) == 0) echo "bgcolor='#DDDDDD'"; else echo ""; ?>>

    <td><strong><? echo $dMaquinas['numero']." ".$dMaquinas['marca']  ?></strong></td>

    <td align="right"><strong>

      <?=$dMaquinas['kilogramos']?>

    </strong></td>

    <td align="right"><strong>

  <?

							  	if($_REQUEST['turno'] == '1') $turno = 8; 

								if($_REQUEST['turno'] == '2') $turno = 7; 

								if($_REQUEST['turno'] == '3') $turno = 9; 										 

									$metas = ((($dMetas['meta_mes_kilo']/$nnumM)/$ultimo_dia)/24)*$turno;

									echo floor($metas); ?>    </strong></td>

<td align="right"><strong>

   <?  $tada = ($metas - $dMaquinas['kilogramos']);

								echo floor($tada);?>

    </strong></td>

    <td align="center" style="font-size:10px"><? 

								 $porciento = $metas*.20;

								 $nueva	=	$metas - $porciento;

								 if(floor($dMaquinas['kilogramos']) < floor($nueva) && $dMaquinas['kilogramos'] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dMaquinas['kilogramos']) > floor($nueva+($porciento*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dMaquinas['kilogramos']) == 0) echo '<span style="color:#000000">SIN </span>';

								 else echo "NORMAL";

								 ?></td>        

  <td align="right"><strong>

          <?=$dMaquinas['millares']?>



   

    </strong></td>

    <td align="right">  <?

							  	if($_REQUEST['turno'] == '1') $turno = 8; 

								if($_REQUEST['turno'] == '2') $turno = 7; 

								if($_REQUEST['turno'] == '3') $turno = 9; 										 

									

									$meta = ((($dMetas['meta_mes_millar']/$nnumM)/$ultimo_dia)/24)*$turno;

									echo floor($meta); ?></td>

    <td align="right" 	>   <?  $tada = ($dMaquinas['millares'] - $meta);

								echo floor($tada);?></td>

    <td align="center" style="font-size:10px"><? 

								 $porciento2 = $meta*.20;

								 $nueva2	=	$meta - $porciento2;

								 if(floor($dMaquinas['millares']) < floor($nueva2) && $dMaquinas['millares'] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dMaquinas['millares']) > floor($nueva+($porciento2*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dMaquinas['millares']) == 0) echo '<span style="color:#000000">SIN </span>';

								 else echo "NORMAL";

								 ?></td>      

  </tr>

  <? 	

		} ?>

  <tr bgcolor="#DDDDDD	">

    <td height="21" colspan="1" align="right"><?  $qMaquinas2	=	"SELECT SUM(kilogramos), SUM(millares) FROM resumen_maquina_bs LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina  WHERE resumen_maquina_bs.id_bolseo = '".$ID_ORDENPRODUCCION."' ORDER BY resumen_maquina_bs.id_maquina ASC";

	 	 $rMaquinas2	=	mysql_query($qMaquinas2);

		 $dMaquinas2	= mysql_fetch_row($rMaquinas2);

		 ?>

      TOTALES</td>

    <td align="right"><?=$dMaquinas2[0];?></td>

    <td align="right"><? echo floor($juju = (($dMetas['meta_mes_kilo']/$ultimo_dia)/24)*$turno);?></td>

    <td align="right"><? echo floor( $nuevo = $dMaquinas2[0] - $juju);

	  				?></td>

    <td align="center"><? 

								 $porciento2 = $juju *.20;

								 $nueva2	=	$juju - $porciento2;

								 if(floor($dMaquinas2[0]) < floor($nueva2) && $dMaquinas2[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dMaquinas2[0]) > floor($nueva2+($porciento2*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dMaquinas2[0]) == 0) echo '<span style="color:#000000">SIN</span>';

								 else echo "NORMAL";

								 ?></td>



    <td align="right"><?=$dMaquinas2[1];?></td>

    <td align="right"><? echo floor($metass = (($dMetas['meta_mes_millar']/$ultimo_dia)/24)*$turno);?></td>

    <td align="right"><? echo floor( $nuevos = $dMaquinas2[1] -  $metass);

	  				?></td>

    <td align="center"><? 

								 $porciento3 = $metass *.20;

								 $nuevas2	=	$metass - $porciento3;

								 if(floor($dMaquinas2[1]) < floor($nuevas2) && $dMaquinas2[1] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dMaquinas2[1]) > floor($nuevas2+($porciento3*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dMaquinas2[1]) == 0) echo '<span style="color:#000000">SIN</span>';

								 else echo "NORMAL";

								 ?></td>

  </tr><? }	 ?>

</table>

<br />

<br />

</form>

<? } ?>



<? if($_REQUEST['accion'] != 'nuevo' && $_REQUEST['accion'] != 'registrado'){ ?>

<form>

<?

	$ID_ORDENPRODUCCION	=	$_GET['id_entrada_general'];

	

	$qFecha	=	"SELECT fecha FROM rpsysf WHERE id_rpsysf = ".$ID_ORDENPRODUCCION."";

	$rFecha = 	mysql_query($qFecha);

	$dFecha	=	mysql_fetch_assoc($rFecha);

	

	$fechass	=	fecha_tablaInv($dFecha['fecha']);

	$fecha 	= explode("-", $dFecha['fecha']);

	

	$mes 	= 	$fecha[1];

	$ano 	=	$fecha[0];

	

	$mes_array	= 	array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

?>

<table width="900" align="center" cellpadding="2" cellspacing="2" border="0">

	<tr>

    	<td colspan="12" style="color:#003; font-weight:bold; font-size:14px; text-align:center;">PRODUCCI&Oacute;N DE <?=strtoupper($mes_array[(int)$mes]);?></td>

    </tr>

     <tr style="background-color:#006699">

     	<td class="style7" style="color:#FFFFFF; text-align:center; width:29%;">Supervisor</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:5%;">Hrs Reales Trabajadas</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:7%">M/H (Promedio)</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:8%;">Millares</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:8%;">Meta Millares</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:8%;">Millares C/Meta</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:7%;">Desp. Tira</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:7%;">Desp. Segundas</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:7%;">Meta Desperdicio</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:7%;">Tira C/Meta</td>

        <td class="style7" style="color:#FFFFFF; text-align:center; width:7%;">Segundas C/Meta</td>

	</tr>

    <?

	/* META */

	 $qryMeta = "SELECT prod_hora, porcentaje_tira, porcentaje_troquel, porcentaje_segunda

	 			 FROM meta

	 			 WHERE ano = '$ano'

				 AND mes = '$ano-$mes-01'

				 AND area = '3'";

	 $rstMeta	=	mysql_query($qryMeta);

	 $rowMeta	=	mysql_fetch_assoc($rstMeta);

	 if(is_null($rowMeta['prod_hora'])){

		 echo "No se ha capturado la PRODUCCION POR HORA para BOLSEO del mes de ".strtoupper($mes_array[(int)$mes])." en la secci&oacute;n de metas";

		 exit();

	 }

	 /**/

	$qrySuper	=	"SELECT s.nombre,

							s.id_supervisor,

							SUM(b.millares) as millares, 

							SUM(b.dtira) as tira,

							SUM(b.segundas) as segundas,

							(SUM(b.kilogramos)*".$rowMeta['porcentaje_segunda'].")/100 as meta_desp,

							SUM(b.dtira)-((SUM(b.kilogramos)*".$rowMeta['porcentaje_tira'].")/100) as tira_contra_meta,

							SUM(b.segundas)-((SUM(b.kilogramos)*".$rowMeta['porcentaje_segunda'].")/100) as segundas_contra_meta

					FROM bolseo b, supervisor s

					WHERE b.id_supervisor = s.id_supervisor

					AND fecha BETWEEN '$ano-$mes-01' AND '$ano-$mes-".UltimoDia($ano,$mes)."'

					AND b.repesada = '1'

					GROUP BY b.id_supervisor

					ORDER BY segundas_contra_meta, tira_contra_meta";

	 $rstSuper	=	mysql_query($qrySuper);

	 //echo $qrySuper;

	 for($z = 0; $rowSuper	=	mysql_fetch_assoc($rstSuper); $z++){

		$qryH = "SELECT FLOOR((SUM(IF(b.turno=1,8,IF(b.turno=2,7,9)))-((SUM(TIME_TO_SEC(mantenimiento))+SUM(TIME_TO_SEC(otras)))/3600))/COUNT(DISTINCT m.id_maquina)) as horas_reales_trabajadas

				 FROM bolseo b

				 LEFT JOIN tiempos_muertos t ON b.id_bolseo = t.id_produccion

				 LEFT JOIN maquina m ON t.id_maquina = m.id_maquina

				 WHERE fecha BETWEEN '$ano-$mes-01' AND '$ano-$mes-".UltimoDia($ano,$mes)."'

				 AND b.id_supervisor = '$rowSuper[id_supervisor]'

				 AND tipo = '4'";

		$rstH = mysql_query($qryH);

		$rowH = mysql_fetch_assoc($rstH);

		//echo $qryH;

	?>

		<tr <? if(bcmod($z,2)==0) echo "bgcolor='#F2F2F2'"; else echo ""; ?>>

        	<td align="left"><?=$rowSuper['nombre']?></td>

            <td align="center"><?=$rowH['horas_reales_trabajadas']?></td>

            <td align="center"><?=number_format($rowSuper['millares']/$rowH['horas_reales_trabajadas'])?></td>

            <td align="right"><?=number_format($rowSuper['millares'])?></td>

            <? 

				$meta_millares = $rowH['horas_reales_trabajadas']*$rowMeta['prod_hora'];

			?>

            <td align="right"><?=number_format($meta_millares)?></td>

            <? 

				$millares_contra_meta = $rowSuper['millares']-$meta_millares //CALCULO DE MILLARES CONTRA META

			?>

        	<td align="right" <?=$millares_contra_meta < 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($millares_contra_meta)?></td>

            <td align="right"><?=number_format($rowSuper['tira'])?></td>

            <td align="right"><?=number_format($rowSuper['segundas'])?></td>

            <td align="right"><?=number_format($rowSuper['meta_desp'])?></td>

        	<td align="right" <?=$rowSuper['tira_contra_meta'] > 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($rowSuper['tira_contra_meta'])?></td>

        	<td align="right" <?=$rowSuper['segundas_contra_meta'] > 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($rowSuper['segundas_contra_meta'])?></td>

		</tr>

	<? } ?>

  		<tr bgcolor="#F6E3CE">

    	<td colspan="1" align="right" >

     <? 

	$qryTotal	=	"SELECT s.nombre, 

							SUM(b.millares) as millares, 

							SUM(b.dtira) as tira,

							SUM(b.segundas) as segundas,

							(SUM(b.kilogramos)*".$rowMeta['porcentaje_segunda'].")/100 as meta_desp,

							SUM(b.dtira)-((SUM(b.kilogramos)*".$rowMeta['porcentaje_tira'].")/100) as tira_contra_meta,

							SUM(b.segundas)-((SUM(b.kilogramos)*".$rowMeta['porcentaje_segunda'].")/100) as segundas_contra_meta

					FROM bolseo b, supervisor s

					WHERE b.id_supervisor = s.id_supervisor

					AND fecha BETWEEN '$ano-$mes-01' AND '$ano-$mes-".UltimoDia($ano,$mes)."'

					AND b.repesada = '1'

					GROUP BY repesada";

	$rstTotal	=	mysql_query($qryTotal);

	$rowTotal	= 	mysql_fetch_assoc($rstTotal);

	$qryH = "SELECT FLOOR((SUM(IF(b.turno=1,8,IF(b.turno=2,7,9)))-((SUM(TIME_TO_SEC(mantenimiento))+SUM(TIME_TO_SEC(otras)))/3600))/COUNT(DISTINCT m.id_maquina)) as horas_reales_trabajadas

			 FROM bolseo b

			 LEFT JOIN tiempos_muertos t ON b.id_bolseo = t.id_produccion

			 LEFT JOIN maquina m ON t.id_maquina = m.id_maquina

			 WHERE fecha BETWEEN '$ano-$mes-01' AND '$ano-$mes-".UltimoDia($ano,$mes)."'

			 AND tipo = '4'";

	$rstH = mysql_query($qryH);

	$rowH = mysql_fetch_assoc($rstH);

	?><strong><i>TOTAL:</i></strong></td>

            <td align="center"><?=$rowH['horas_reales_trabajadas']?></td>

            <td align="center"><?=number_format($rowTotal['millares']/$rowH['horas_reales_trabajadas'])?></td>

            <td align="right"><?=number_format($rowTotal['millares'])?></td>

            <? 

				$meta_millares = $rowH['horas_reales_trabajadas']*$rowMeta['prod_hora'];

			?>

            <td align="right"><?=number_format($meta_millares)?></td>

            <? 

				$millares_contra_meta = $rowTotal['millares']-$meta_millares //CALCULO DE MILLARES CONTRA META

			?>

        	<td align="right" <?=$millares_contra_meta < 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($millares_contra_meta)?></td>

            <td align="right"><?=number_format($rowTotal['tira'])?></td>

            <td align="right"><?=number_format($rowTotal['segundas'])?></td>

            <td align="right"><?=number_format($rowTotal['meta_desp'])?></td>

        	<td align="right" <?=$rowTotal['tira_contra_meta'] > 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($rowTotal['tira_contra_meta'])?></td>

        	<td align="right" <?=$rowTotal['segundas_contra_meta'] > 0 ? "style=\"color:#DF0101;\"" : "style=\"color:#045FB4;\"";?>><?=number_format($rowTotal['segundas_contra_meta'])?></td>

	</tr>

    <tr>

        <td colspan="12" valign="top" style="text-align:center; padding-top:15px;"><a href="<?=$_SERVER['HOST']?>?seccion=<?=isset($_SESSION['id_admin'])?'43':'40'?>&area=area3&mes=<?=$mes?>&ano=<?=$ano?>">| VER REPORTE DE PROMEDIOS |</a></td>

    </tr>

</table>

 <br />

<br />

</form>

<? } ?>

