
<? if($_REQUEST['tipo'] == 1 ){



		if($_REQUEST['tiempo'] == 0){

		

			$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);

			if(!isset($_REQUEST['hasta']))

			$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);

			else

			$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hasta']);

			

			$revisa 	= explode("-", $desde);

			$ano = intval($revisa[0]);

			$mesM = intval($revisa[1]);

			$ultimo_dia = UltimoDia($ano,$mesM);	

			$mesM	=	num_mes_cero($ano.'-'.$mesM.'-01');

			$mesM	=	$ano.'-'.$mesM.'-01';

			$dia = intval($revisa[2]);

			$mes_fecha = intval($revisa[1]);	

		}



		if($_REQUEST['tiempo'] == 1){

			$mes_d	=	$_REQUEST['mes'];

			$mes_fecha	=	$_REQUEST['mes'];

			$ano	=	$_REQUEST['anho'];

			$mes_c	=	num_mes_cero($_REQUEST['anho']."-".$mes_d."-01");

			$mesM	=	$_REQUEST['anho'].'-'.$mes_c.'-01';

			

			$ultimo_dia = UltimoDia($ano,$mes_d);	

			$desde	= 	$_REQUEST['anho']."-".$mes_c."-01";

			$hasta	= 	$_REQUEST['anho']."-".$mes_c."-".$ultimo_dia;

		}



	 			 $qMetasBolseo	=	"SELECT * FROM meta WHERE mes = '".$mesM."'  AND area = '3'";

				 $rMetasBolseo	=	mysql_query($qMetasBolseo);

				 $dMetasBolseo	=	mysql_fetch_assoc($rMetasBolseo);

				 

		 		 $qMetas	=	"SELECT * FROM meta WHERE mes = '".$mesM."'  AND area = '1'";

				 $rMetas	=	mysql_query($qMetas);

				 $dMetas	=	mysql_fetch_assoc($rMetas);

				 

	 			 $qMetasImp	=	"SELECT * FROM meta WHERE mes = '".$mesM."'  AND area = '2'";

				 $rMetasImp	=	mysql_query($qMetasImp);

				 $dMetasImp	=	mysql_fetch_assoc($rMetasImp);

				 

		// BOLSEO		 				  	

		$qMaquinas2	=	"SELECT SUM(kilogramos), SUM(millares), fecha, SUM(dtira), SUM(dtroquel), SUM(segundas), SUM(m_p) FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND repesada = 1 GROUP BY fecha ORDER BY fecha ASC";

	  	$rMaquinas2	=	mysql_query($qMaquinas2);



		$nMaquinas2	=	mysql_num_rows($rMaquinas2);



		$qMaquinasMatutino		=	"SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 1 AND repesada = 1 ORDER BY fecha ASC";

		$qMaquinasVespertino	=	"SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 2 AND repesada = 1 ORDER BY fecha ASC";

		$qMaquinasNocturno		=	"SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 3 AND repesada = 1 ORDER BY fecha ASC";

		

		$rMaquinasMatutino		=	mysql_query($qMaquinasMatutino);	

		$rMaquinasVespertino	=	mysql_query($qMaquinasVespertino);	

		$rMaquinasNocturno		=	mysql_query($qMaquinasNocturno);	

		

		$dMaquinasMatutino		=	mysql_fetch_row($rMaquinasMatutino);	

		$dMaquinasVespertino	=	mysql_fetch_row($rMaquinasVespertino);	

		$dMaquinasNocturno		=	mysql_fetch_row($rMaquinasNocturno);	

		

		$qMaquinas4	=	"SELECT SUM(kilogramos), SUM(millares), fecha, SUM(dtira), SUM(dtroquel), SUM(segundas) FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND repesada = 1 GROUP BY fecha ORDER BY fecha ASC";

	  	$rMaquinas4 =	mysql_query($qMaquinas4);	

			

		// EXTRUDER

		$qEntrada	=	"SELECT SUM(orden_produccion.total), fecha, SUM(desperdicio_duro), SUM(desperdicio_tira), SUM(k_h) FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND repesada = 1 GROUP BY fecha ORDER BY fecha ASC";

		$rEntrada	=	mysql_query($qEntrada);	



		$qEntradaMatutino	=	"SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 1 AND repesada = 1  ORDER BY fecha ASC";

		$qEntradaVespertino	=	"SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 2 AND repesada = 1  ORDER BY fecha ASC";

		$qEntradaNocturno	=	"SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 3 AND repesada = 1  ORDER BY fecha ASC";



		$rEntradaMatutino	=	mysql_query($qEntradaMatutino);	

		$rEntradaVespertino	=	mysql_query($qEntradaVespertino);	

		$rEntradaNocturno	=	mysql_query($qEntradaNocturno);	

		

		$dEntradaMatutino	=	mysql_fetch_row($rEntradaMatutino);	

		$dEntradaVespertino	=	mysql_fetch_row($rEntradaVespertino);	

		$dEntradaNocturno	=	mysql_fetch_row($rEntradaNocturno);	

				

		$nEntrada	=	mysql_num_rows($rEntrada);

		

		//IMPRESION

		$qEntrada2	=	"SELECT SUM(impresion.total_hd), fecha, SUM(impresion.desperdicio_hd) , SUM(impresion.k_h) FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND repesada = 1  GROUP BY fecha ORDER BY fecha ASC";

		$rEntrada2	=	mysql_query($qEntrada2);

		

		$qEntradaMatutino2		=	"SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 1 AND repesada = 1  ORDER BY fecha ASC";

		$qEntradaVespertino2	=	"SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 2 AND repesada = 1  ORDER BY fecha ASC";

		$qEntradaNocturno2		=	"SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 3 AND repesada = 1  ORDER BY fecha ASC";



		$rEntradaMatutino2		=	mysql_query($qEntradaMatutino2);	

		$rEntradaVespertino2	=	mysql_query($qEntradaVespertino2);	

		$rEntradaNocturno2		=	mysql_query($qEntradaNocturno2);	

		

		$dEntradaMatutino2		=	mysql_fetch_row($rEntradaMatutino2);	

		$dEntradaVespertino2	=	mysql_fetch_row($rEntradaVespertino2);	

		$dEntradaNocturno2		=	mysql_fetch_row($rEntradaNocturno2);	

		

		$nEntrada2	=	mysql_num_rows($rEntrada2); ?>



<script type="text/javascript" language="javascript">

function genera()

{

document.metas.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?><? if(isset($_REQUEST['tiempo'])){ ?>&tiempo=<?=$_REQUEST['tiempo']; } ?><? if(isset($_REQUEST['anho'])){ ?>&anho=<?=$_REQUEST['anho']; } ?><? if(isset($_REQUEST['mes'])){ ?>&mes=<?=$_REQUEST['mes']; } ?><? if(isset($_REQUEST['desde'])){ ?>&desde=<?=$_REQUEST['desde']; }?><? if(isset($_REQUEST['hasta'])){ ?>&hasta=<?=$_REQUEST['hasta']; }?>";

document.metas.submit();



}

</script>

<form name="metas" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=nuevo" id="super" method="post" >

<table width="90%" id="tablaimpr"  align="center" >

        <tr>

		<td align="center" >

        <table width="100%" class="titulos_e"  id="tabla_reporte" align="center" >

			<p class="titulos_reportes" align="center" style="text-transform:uppercase">PRODUCCI&Oacute;N DIARIA EXTRUDER <?=$mes[$mes_fecha]?> DEL <?=$ano?><br /><br /></p>



  		<? if($nEntrada == 1 ){ $dEntrada	=	mysql_fetch_row($rEntrada);?>



  		<tr >

            <td align="right"><h3>Fecha:</h3> </td>

            <td colspan="1" align="center" class="style5"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dEntrada[1]);?></td>

		</tr>

		<tr >

            <td align="center" ><h3>Turno</h3></td>

            <td align="center" ><h3>Produccion</h3></td>

            <td width="74" align="center" ><h3>Meta</h3></td>

            <td width="96" align="center" ><h3>Dif.</h3></td>

            <td align="center" ><h3>Produccion</h3></td>

            <td align="center" ><h3>Desp.</h3></td>

            <td align="center" ><h3>Meta Desp.</h3></td>

            <td width="72" align="center" ><h3>Dif.</h3></td>

            <td width="72" align="center" ><h3>Kg/H</h3></td>

  		</tr>

  		<tr align="right">

    		<td width="76"><h3>Matutino</h3></td>

            <td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=extruder&id_entrada_general=<?=$dEntradaMatutino[3]?>" 		style="text-decoration:none; color:#000000"><?=number_format($dEntradaMatutino[0]);?></a></b></td>

            <!--<td align="right" class="style5"><?=number_format($metasMatutino = ($dMetas['total_dia']/24)*8);?></td>-->

            <td align="right" class="style5"><?=number_format($metasMatutino = $dMetas['prod_hora']*8);?></td>

 			<td align="right" class="style5"><?=number_format($dEntradaMatutino[0]-$metasMatutino);?></td>

            <td align="center" ><? 

								 $porcientoMatutino = $metasMatutino *.20;

								 $nueva2	=	$metasMatutino - $porcientoMatutino;

								 if(floor($dEntradaMatutino[0]) < floor($nueva2) && $dEntradaMatutino[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dEntradaMatutino[0]) > floor($nueva2+($porcientoMatutino*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dEntradaMatutino[0]) == 0) echo '<span style="color:#000000">SIN</span>';

								 else echo "NORMAL";

								 ?></td>

            <td align="right" class="style5"><b><?=number_format($total_desper = $dEntradaMatutino[1] + $dEntradaMatutino[2])?></b></td>

            <!--<td align="right" class="style5"><?=number_format($Diferencia = (($dMetas['desp_duro_hd']/$ultimo_dia)/24)*8);?></td>-->

            <td align="right" class="style5"><?=number_format($Diferencia = (($dMetas['porcentaje_desp']*$dEntradaMatutino[0]/100)/24)*8);?></td>

            <td align="right" class="style5"><?=number_format($total_desper-$Diferencia);?></td>

            <td align="right" class="style5"><?= number_format($dEntradaMatutino[0]/8);?></td>

         </tr>

 		 <tr align="right"> 

            <td width="76" ><h3>Vepertino</h3></td>

            <td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=extruder&id_entrada_general=<?=$dEntradaVespertino[3]?>" 	style="text-decoration:none; color:#000000"><?= number_format($dEntradaVespertino[0]);?></a></b></td>

            <!--<td align="right" class="style5"><?=number_format($metaVespertino = ($dMetas['total_dia']/24)*7);?></td>-->

            <td align="right" class="style5"><?=number_format($metaVespertino = $dMetas['prod_hora']*7);?></td>

            <td align="right" class="style5"><?=number_format($nuevo = $metaVespertino - $dEntradaVespertino[0]);?></td>

    		<td align="center" ><? 

								 $porcientoVesper = $metaVespertino *.20;

								 $nueva2	=	$metaVespertino - $porcientoVesper;

								 if(floor($dEntradaVespertino[0]) < floor($nueva2) && $dEntradaVespertino[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dEntradaVespertino[0]) > floor($nueva2+($porcientoVesper*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dEntradaVespertino[0]) == 0) echo '<span style="color:#000000">SIN</span>';

								 else echo "NORMAL";

								 ?></td>  

            <td align="right" class="style5"><b><?=number_format($total_desperVesper = $dEntradaVespertino[1] + $dEntradaVespertino[2]);?></b></td>

            <!--<td align="right" class="style5"><?=number_format($DiferenciaVesper = (($dMetas['desp_duro_hd']/$ultimo_dia)/24)*7);?></td>-->

            <td align="right" class="style5"><?=number_format($DiferenciaVesper = (($dMetas['porcentaje_desp']*$dEntradaVespertino[0]/100)/24)*7);?></td>

            <td align="right" class="style5"><?=number_format( $DiferenciaVesper - $total_desperVesper);?></td>   

            <td align="right" class="style5"><?= number_format($dEntradaVespertino[0]/7);?></td>

  		</tr>

  		<tr align="right" > 

            <td width="76"><h3>Nocturno</h3></td>

            <td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=extruder&id_entrada_general=<?=$dEntradaNocturno[3]?>"	 	style="text-decoration:none; color:#000000"><?= number_format($dEntradaNocturno[0]);?></a></b></td>

            <!--<td align="right" class="style5"><?=number_format($metaNocturno = ($dMetas['total_dia']/24)*9);?></td>-->

            <td align="right" class="style5"><?=number_format($metaNocturno = $dMetas['prod_hora']*9);?></td>

            <td align="right" class="style5"><?=number_format($nuevo = $metaNocturno - $dEntradaNocturno[0]);?></td>

            <td align="center" ><? 

								 $porcientoNocturno = $metaNocturno *.20;

								 $nueva2	=	$metaNocturno - $porcientoNocturno;

								 if(floor($dEntradaNocturno[0]) < floor($nueva2) && $dEntradaNocturno[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dEntradaNocturno[0]) > floor($nueva2+($porcientoNocturno*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dEntradaNocturno[0]) == 0) echo '<span style="color:#000000">SIN</span>';

								 else echo "NORMAL";

								 ?></td>  

            <td align="right" class="style5"><b><?=number_format($total_despeNocturno = $dEntradaNocturno[1] + $dEntradaNocturno[2])?></b></td>

            <!--<td align="right" class="style5"><?=number_format($DiferenciaNocturno = (($dMetas['desp_duro_hd']/$ultimo_dia)/24)*9);?></td>-->

			<td align="right" class="style5"><?=number_format($DiferenciaNocturno = (($dMetas['porcentaje_desp']*$dEntradaNocturno[0]/100)/24)*9);?></td>

            <td align="right" class="style5"><?=number_format($DiferenciaNocturno - $total_despeNocturno);?></td> 

            <td align="right" class="style5"><?= number_format($dEntradaNocturno[0]/9);?></td>

  		</tr>

  		<tr align="right" > 

            <td width="76" ><h3>Total</h3></td>

            <td align="right" bgcolor="#DDDDDD" class="style5"><b><?= number_format($dEntrada[0])?></b></td>

            <!--<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($metasss = $dMetas['total_dia']);?></td>-->

            <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($metasss = $dMetas['prod_hora']*24);?></td>

            <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($nuevo = $metasss - $dEntrada[0]);?></td>

            <td align="center" bgcolor="#DDDDDD" ><? 

								 $porciento2 = $metasss *.20;

								 $nueva2	=	$metasss - $porciento2;

								 if(floor($dEntrada[0]) < floor($nueva2) && $dEntrada[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dEntrada[0]) > floor($nueva2+($porciento2*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dEntrada[0]) == 0) echo '<span style="color:#000000">SIN</span>';

								 else echo "NORMAL";

								 ?></td>  

            <td align="right" bgcolor="#DDDDDD" class="style5"><b><?=number_format($total_desper = $dEntrada[2] + $dEntrada[3])?></b></td>

           <!--<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($Diferencia = ($dMetas['desp_duro_hd']/$ultimo_dia));?></td>-->

           <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($Diferencia = $dMetas['porcentaje_desp']*$dEntrada[0]/100);?></td>

            <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($Diferencia - $total_desper);?></td>  

            <td align="right" class="style5" bgcolor="#DDDDDD"><?= number_format($dEntrada[0]/24);?></td>

  		</tr>    

<? } if($nEntrada > 1 ){ ?>

   		<tr >

            <td width="76" align="center" ><h3>Fecha</h3></td>

            <td width="93" align="center" ><h3>Kg.</h3></td>

            <td width="74" align="center" ><h3>Meta</h3></td>

            <td width="96" align="center" ><h3>Dif.</h3></td>

            <td width="76" align="center" ><h3>Produccion</h3></td>

            <td align="center" ><h3>Desp.</h3></td>

            <td align="center" ><h3>Meta Desp. (<?=$dMetas['porcentaje_desp']?>%)</h3></td>

            <td align="center" ><h3>Dif.</h3></td>

            <td align="center" ><h3>KG/H</h3></td>

  		</tr>

  <?   	while($dEntrada	=	mysql_fetch_row($rEntrada)){ ?>

  		<tr>

            <td colspan="1" align="center" class="style5"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dEntrada[1]);?></td>

            <td align="right" class="style5"><b><?=number_format($dEntrada[0]);?></b></td>

            <!-- ***************************** SE CAMBIO LA META PRODUCCION DIARIA **************************** -->

            <!--<td align="right" class="style5"><?=number_format($metasss = $dMetas['total_dia']);?></td>-->

            <td align="right" class="style5"><?=number_format($metasss = $dMetas['prod_hora']*24);?></td>

            <!-- ********************************************************************************************** -->

            <td align="right" class="style5"><?=number_format($nuevo = $dEntrada[0]-$metasss);?></td>

            <td align="center" ><? 

								 $porciento2 = $metasss *.20;

								 $nueva2	=	$metasss - $porciento2;

								 if(floor($dEntrada[0]) < floor($nueva2) && $dEntrada[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($dEntrada[0]) > floor($nueva2+($porciento2*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($dEntrada[0]) == 0) echo '<span style="color:#000000">SIN</span>';

								 else echo "NORMAL";

								 ?></td>

            <td align="right" class="style5"><b><?=number_format($total_desper = $dEntrada[2] + $dEntrada[3])?></b></td>

            <!-- ***************************** SE CAMBIO LA META DESPERDICIO **************************** -->

            <!--<td align="right" class="style5"><?=number_format($Diferencia = ($dMetas['desp_duro_hd']/$ultimo_dia));?></td>-->

            <td align="right" class="style5"><?=number_format($Diferencia = $dMetas['porcentaje_desp']*$dEntrada[0]/100);?></td>

            <!-- ***************************************************************************************** -->

            <td align="right" class="style5"><?=number_format($TotalDif	= $total_desper-$Diferencia);?></td>

            <td align="right" class="style5"><?=number_format($dEntrada['0']/24);

            $TOTALKG 	+= $dEntrada['0'];

            $TOTALMETA	+= $metasss; 

            $TOTALDIF	+= $nuevo;

            $TOTALKGH	+= $dEntrada[3];

            $TOTALDESP	+= $total_desper;

            $TOTALDESPDIF	+= $TotalDif;

            $TOTALMETADESP	+= $Diferencia;?></td>

  		</tr>

  <? } ?>

  		<tr bgcolor="#DDDDDD">

            <td colspan="1" align="right" class="style5"><h3>TOTALES:</h3></td>

            <td align="right" class="style5"><b><?=number_format($TOTALKG);?></b></td>

            <td align="right" class="style5"><?=number_format($TOTALMETA);?></td>

            <td align="right" class="style5"><?=number_format($TOTALDIF);?></td>

            <td align="center" ><? 

								 $porcientometa2 = $TOTALMETA *.20;

								 $nuevameta2	=	$TOTALMETA - $porcientometa2;

								 if(floor($TOTALKG) < floor($nuevameta2) && $TOTALKG > 0 ) echo '<span style="color:#FF0000">BAJA</span>';

								 else if(floor($TOTALKG) > floor($nuevameta2+($porcientometa2*3))) echo '<span style="color:#006600">ALTA</span>';

								 else if(floor($TOTALKG) == 0) echo '<span style="color:#000000">SIN</span>';

								 else echo "NORMAL";

								 ?></td>

            <td align="right" class="style5"><b><?=number_format($TOTALDESP);?></b></td>

            <td align="right" class="style5"><?=number_format($TOTALMETADESP);?></td>

            <td align="right" class="style5"><?=number_format($TOTALDESPDIF);?></td>

            <td align="right" class="style5"><?=number_format($TOTALKG/24)?></td>

  		</tr>

 <? } ?>

 </table></td></tr>

    <tr>

    	<td colspan="11"><p>&nbsp;<br /><br /></p></td>

    </tr>

</table>

</form>

<? }?>



 <?  if($_REQUEST['tipo'] == 34 ){ 



 	$meMeta =	$_REQUEST['mes_pm'];

	$anho 	=	$_REQUEST['anho_pm'];

	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');

	$mesMeta	=	$anho.'-'.$mesMetacero.'-01';

	$ultimo_dia = UltimoDia($anho,$meMeta);

	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

 

		 			 

	 $qSelectTiemposExtr	=	"SELECT TIME(SUM(mantenimiento)) AS mantenimiento ,".

							" TIME(SUM(falta_personal)) AS falta_personal ,".

							" TIME(SUM(fallo_electrico)) AS fallo_electrico ,".

							" TIME(SUM(otras)) AS otras ,".

							" SUM(mallas) AS mallas ,".

							" maquina.marca, maquina.numero ".

							" FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general ".

							" LEFT JOIN tiempos_muertos ON orden_produccion.id_orden_produccion = tiempos_muertos.id_produccion ".

							" LEFT JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina ".						

							" WHERE tipo = 1 AND fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 0 GROUP BY tiempos_muertos.id_maquina ORDER BY maquina.numero, maquina.area ASC";

	$rSelectTiemposExtr	=	mysql_query($qSelectTiemposExtr);

	$nExtruder			=	mysql_num_rows($rSelectTiemposExtr);

				

	$qSelectImpresion	=	"SELECT TIME(SUM(mantenimiento)) AS mantenimiento ,".

							" TIME(SUM(falta_personal)) AS falta_personal ,".

							" TIME(SUM(fallo_electrico)) AS fallo_electrico ,".

							" SUM(cambio_impresion) AS cambio_impresion ,".

							" TIME(SUM(otras)) AS otras ,".

							" maquina.marca, maquina.numero ".

							" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".

							" LEFT JOIN tiempos_muertos ON impresion.id_impresion = tiempos_muertos.id_produccion ".

							" LEFT JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina ".						

							" WHERE (tipo = 2  OR tipo = 3) AND fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 1 GROUP BY tiempos_muertos.id_maquina ORDER BY maquina.area,  maquina.numero ASC";

	

	$rSelectImpresion	=	mysql_query($qSelectImpresion);

	$nImpresion			=	mysql_num_rows($rSelectImpresion);



	

 	$qSelectBolseo		=	"SELECT TIME(SUM(mantenimiento)) AS mantenimiento ,".

							" TIME(SUM(falta_personal)) AS falta_personal ,".

							" TIME(SUM(fallo_electrico)) AS fallo_electrico ,".

							" TIME(SUM(otras)) AS otras ,".

							" maquina.marca, maquina.numero ".

							" FROM bolseo ".

							" LEFT JOIN tiempos_muertos ON bolseo.id_bolseo = tiempos_muertos.id_produccion ".

							" LEFT JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina ".						

							" WHERE tipo = 4 AND  fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' GROUP BY tiempos_muertos.id_maquina ORDER BY maquina.area,  maquina.numero ASC";

							

	$rSelectBolseo	=	mysql_query($qSelectBolseo);

	$nBolseo			=	mysql_num_rows($rSelectBolseo);

	?>

<div align="center" id="tablaimpr" style="width:100%">

   	 	<div class="tablaCentrada titulos_e" id="tabla_reporte" align="center">

        <p class="titulos_reportes" align="center" >PAROS REPORTADOS EN EXTRUDER DE <?=$mes[$meMeta]?> DEL <?=$anho?></p><br />

        	<table width="90%">  

              <tr align="center">

              	<td width="30%" ><h3>Maquina</h3></td>

                <td width="18%" ><h3>Mantenimiento</h3></td>    

                <td width="19%" ><h3>Falta de Opr</h3></td>      

                <td width="13%" ><h3>Otro</h3></td>      

                <td ><h3>Total</h3></td>

                <td ><h3>MALLAS</h3></td>      

              </tr> 

              <? 	for($a++; $dSelectTiemposExtr	=	mysql_fetch_assoc($rSelectTiemposExtr); $a++){	

			  			$Total_maquinaExtr 	=	$dSelectTiemposExtr['mantenimiento']+$dSelectTiemposExtr['falta_personal']+$dSelectTiemposExtr['fallo_electrico'];

			  			$TotalO	=	$dSelectTiemposExtr['fallo_electrico']+$dSelectTiemposExtr['otras'];

						?>

              <tr <? cebra($a) ?>>

                <td ><?=$dSelectTiemposExtr['numero']?> - <?=$dSelectTiemposExtr['marca']?></td>      

                <td align="right" class="style5"><? printf("%.2f ",$dSelectTiemposExtr['mantenimiento']/(24/3))?></td>      

                <td align="right" class="style5"><? printf("%.2f ",$dSelectTiemposExtr['falta_personal']/(24/3))?></td>      

                <td align="right" class="style5"><? printf("%.2f ",$TotalO/(24/3))?></td>      

                <td align="right" class="style5"><? printf("%.2f ",($TotalO+$dSelectTiemposExtr['falta_personal']+$dSelectTiemposExtr['mantenimiento'])/(24/3))?></td>

                <td align="right" class="style5"><? printf("%.2f ",$dSelectTiemposExtr['mallas'])?></td>                            

			  </tr>

             <?	 

			 	   $turnos_manE		+= $dSelectTiemposExtr['mantenimiento'];

			 	   $turnos_perE		+= $dSelectTiemposExtr['falta_personal'];

				   $turnos_falloE	+= $TotalO;	

				   $turnos_mallaE	+= $dSelectTiemposExtr['mallas'];

			 	   $Total_extruderE	+= $Total_maquinaExtr;

				   $turno_totalE	=	$turnos_manE + $turnos_perE + $turnos_falloE;

			 ?>

             <? } ?>  

              <tr>

                <td align="right" ><h3>TURNOS PARADOS:</h3></td>      

                <td align="right" class="style4"><? printf("%.2f ",$turnos_manE/(24/3))?></td>      

                <td align="right" class="style4"><? printf("%.2f ",$turnos_perE/(24/3))?></td>      

                <td align="right" class="style4"><? printf("%.2f ",$turnos_falloE/(24/3))?></td>      

                <td align="right" class="style4"><? printf("%.2f ",($turnos_falloE+$turnos_perE+$turnos_manE)/(24/3))?></td>  

                <td align="right" class="style4"><?=$turnos_mallaE?></td>      

			  </tr>

              <tr bgcolor="EEEEEE">

                <td align="right" ><h3>DIAS:</h3></td>      

                <td align="right" class="style4"><? printf("%.2f ",($turnos_manE/24)/$nExtruder)?></td>      

                <td align="right" class="style4"><? printf("%.2f ",($turnos_perE/24)/$nExtruder)?></td>      

                <td align="right" class="style4"><? printf("%.2f ",($turnos_falloE/24)/$nExtruder)?></td>      

                <td align="right" class="style4"><? printf("%.2f ",(($turnos_falloE+$turnos_perE+$turnos_manE)/24)/$nExtruder)?></td>      

			  </tr>

          </table>

          <br /><br />

      </div>

   </div>

          

<? } ?>





<? if($_REQUEST['tipo'] == 39){ 



	$meMeta	=	$_REQUEST['mes_extr'];

	$anho	=	$_REQUEST['ano_extr'];

	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');

	$meses	=	$anho.'-'.$mesMetacero.'-01';

	$ultimo_dia = UltimoDia($anho,$meMeta);

	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;





///////////////////////////PROMEDIO META////////////////////////// 

$qMeta	=	"SELECT * FROM meta WHERE mes = '".$meses."' AND area = 1";

$rMeta	=	mysql_query($qMeta);

$nMeta	=	mysql_num_rows($rMeta);

if($nMeta > 0){

$dMeta	=	mysql_fetch_assoc($rMeta);

//$metaHora	=	round($dMeta['total_dia']/24,2);

$metaHora	=	round($dMeta['prod_hora'],2);

$mMat		=	$metaHora * 8;

$mVes		=	$metaHora * 7;

$mNoc		=	$metaHora * 9;

}

////////////////////////////TURNOS POR ROL////////////////////////

$qRol	=	"SELECT rol, COUNT(entrada_general.turno) AS turno, SUM(total) AS total, SUM(desperdicio_tira+desperdicio_duro) AS tira FROM entrada_general ".

			" LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

			" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."' ".

			" AND impresion = 0  AND repesada = 1".

			" GROUP BY entrada_general.turno, entrada_general.rol ORDER BY entrada_general.id_supervisor, entrada_general.turno,entrada_general.rol ASC ";

				

$rRol	=	mysql_query($qRol);

$nRol	=	mysql_num_rows($rRol);

if($nRol > 0){

$qGrupo	=	" SELECT nombre FROM supervisor ".

			" WHERE area = 1 

			 ORDER BY rol ASC";

					

$rGrupo	=	mysql_query($qGrupo);



	for($t = 0 ; $dGrupo	=	mysql_fetch_assoc($rGrupo); $t++){

	

	$grupo[$t] = $dGrupo['nombre'];

	

	}

}



if($nRol == 0) 	$error = "NO HAY REGISTROS ";

if($nMeta == 0 && $nRol == 0) $error = " Y ";

if($nMeta == 0) $error = "NO HAY UNA META ";

?>

<script type="text/javascript" language="javascript">

function genera()

{

document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&fecha_hist_f=<?=$_REQUEST['fecha_hist_f']?>&fecha_hist=<?=$_REQUEST['fecha_hist']?>&sup_h=<?=$_REQUEST['sup_h']?>&oper_h=<?=$_REQUEST['oper_h']?>&turnos_h=<?=$_REQUEST['turnos_h']?>";

document.form.submit();

document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";

}

</script>

<div align="center"  id="tablaimpr">

   	 	<div class="tablaCentrada titulos_e" align="center" id="tabla_reporte"  >

        <p class="titulos_reportes" align="center">PRODUCCION EXTRUDER POR GRUPO Y POR TURNO DEL MES DE <?=strtoupper($mes[$meMeta])?> DE <?=$anho?></p><br />



<? if($nRol>0 && $nMeta > 0){?>

        <form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&tipo=<?=$_REQUEST['tipo']?>&mes_extr=<?=$_REQUEST['mes_extr']?>&ano_extr=<?=$_REQUEST['ano_extr']?>&modificar" method="post">

          <table width="90%" align="center" id="titulos_e" >

            <tr >

                  <td width="10%" align="center"><h3>Turno</h3></td>

                  <td width="10%" align="center"><h3>1</h3></td>

                  <td width="10%" align="center"><h3>2</h3></td>

                  <td width="9%"  align="center"><h3>3</h3></td>

                  <td width="13%" align="center"><h3>TOTAL</h3></td>

              </tr>        

                <tr align="center">

                  <td><h3>META</h3></td>

                  <td class="style5"><?= number_format($mMat)?></td>

                  <td class="style5" bgcolor="#DDDDDD"><?= number_format($mVes)?></td>

                  <td class="style5"><?= number_format($mNoc)?></td>

                  <!--<td class="style5" bgcolor="#DDDDDD"><?= number_format($dMeta['total_dia'])?></td>-->

                  <td class="style5" bgcolor="#DDDDDD"><?= number_format($dMeta['prod_hora']*24)?></td>

              </tr>

            </table>

		<br /><br />

        <table  width="90%" align="center" id="titulos_e" >

		<? for ($f = 0; $f < 4 ; $f++){ ?>

        <tr>

	        <td width="15%" align="left" style="font-size:9px"><h3>Grupo_<?=$f+1?></h3></td>

            <td width="85%" align="left" class="style5" style="font-size:9px"><?=$grupo[$f]?></td>

        </tr>

        <? } ?>

        </table>

		<br /><br />



        <table width="90%" align="center" id="titulos_e">

       	  <tr>

            <td width="9%" align="center"><h3>Nombre</h3></td>

       	    <td width="6%" align="center"><h3>T_1</h3></td>

       	    <td width="8%" align="center"><h3>Meta</h3></td>

       	    <td width="6%" align="center"><h3>T_2</h3></td>

       	    <td width="8%" align="center"><h3>Meta</h3></td>

       	    <td width="7%" align="center"><h3>T_3</h3></td>

       	    <td width="9%" align="center"><h3>Meta</h3></td>

       	    <td width="16%" align="center"><h3>META_TOTAL</h3></td>

       	    <td width="12%" align="center"><h3>PROD</h3></td>

       	    <td width="10%" align="center"><h3>DIF</h3></td>

       	    <td width="9%" align="center"><h3>Total_T.</h3></td>

          </tr>

          <? 

		  	for($a = 0 ;$dRol	=	mysql_fetch_assoc($rRol);$a++){

				

				$turnos[$a]			=	$dRol['turno'];

				$Totales[$a]		=	$dRol['total'];

				$tira[$a]			=	$dRol['tira'];

			}  

		

							

			for($f=0,$g=0; $f<=3 ; $f++,$g++){	

			if($f==0){

			$g=0;

			}

			else if($f<3){

			$g=$g-12;

			}

			else if($f==3){

			$g=3;

			}

				for(;$g<12;$g=$g+4){

					$tiras[$f]		+=	$tira[$g];

					$Total_pr[$f]	+=	$Totales[$g];

				}

		

								

			}

		$total_m  = 0;

		$turno1	= 0;

			for( $b = 0 ; $b < 4 ; $b++){?>

          <tr <? if(bcmod($b,2) == 0) echo 'bgcolor="#DDDDDD"';?>>

          	<td align="center">GRUPO_<?=$b+1?></td>

            <?

		//	$Total_pr	=	0;

			

			$total 		= 	0;

			for($c = 0; $c < 3; $c++){ 

				if($b == 0) $u = 0;

				if($b == 1) $u = 2;

				if($b == 2) $u = 4;

				if($b == 3) $u = 6;

				

				

				$meta = $b+$c+$u;

				

				if($c == 0) $multi = $mMat;

				if($c == 1) $multi = $mVes;

				if($c == 2) $multi = $mNoc;

				

			?>

            <td class="style5" align="center"><?=$turnos[$meta]; $turno1 = $turnos[$meta][0] * $multi?></td>

            <td class="style5" align="center"><?= number_format($turno1);

			$t_total[$b]	+=	$turnos[$meta][0];

			$total += $turno1;	

			$total_desp	+= $tira[$meta][0];

			}?></td>            

   			<td class="meta_totales"  	align="right"><?= number_format($total)?></td>

            <td class="prod_totales" 	align="right"><?= number_format($Total_pr[$b])?></td>

            <td class="dif_totales" 	align="right"><?= number_format($diferencia = $total - $Total_pr[$b])?></td>

            <td class="dif_totales" 	align="right"><?=$t_total[$b]; ?></td>

		  </tr>

          <? 

		  $total_m += $total; 

		  $total_des[$b] 	= array($total_desp);

		  $totalmetas[$b] 	= array($total);

		  $totalprod[$b] 	= array($Total_pr[$b]);

			

		  $total_p += $Total_pr[$b]; 

		  

		  $total_d += $diferencia; 

		  

		  } ?>                 

		<tr bgcolor="#DDDDDD" id="titulos_e">

            <td colspan="7" align="right"><h3>TOTALES: </h3></td>

            <td class="meta_totales" align="right"><?= number_format($total_m) ?></td>

           	<td class="prod_totales" align="right"><?= number_format($total_p) ?></td>

           	<td class="dif_totales" align="right"><?= number_format($total_d) ?></td> 

            <? for($a=0;$a<4;$a++){ $total_tt	+=	$t_total[$a];} ?>

            <td class="dif_totales" 	align="right"><? printf("%.2f",$total_tt/3); ?></td>

          </tr>                     

        </table>

        <br><br />

	<table width="90%" align="center" id="titulos_e" >



                <tr>

                  <td rowspan="2" >&nbsp;</td>

                  <td align="center"><h3>94%</h3></td>

                  <td  align="center"><h3>4%</h3></td>

                  <td  align="center"><h3>2%</h3></td>

                  <td  align="center"><h3>100%</h3></td>

                  <td colspan="3" align="center"><H3>KILOS</H3></td>

                  <td colspan="3" class="titulos_de" align="center">DESPERDICIO</td>               

                </tr>

                <tr>

                  <td align="center"><h3>Meta</h3></td>

                  <td align="center"><h3>DESP</h3></td>

                  <td align="center"><h3>Limp._Y_Act.</h3></td>

                  <td align="center"><h3>Total</h3></td>

				  <td align="center"><h3>METAS</h3></td>

				  <td align="center"><h3>REAL</h3></td>

				  <td align="center"><h3>%</h3></td>

				  <td align="center" class="titulos_de">METAS</td>

				  <td align="center" class="titulos_de">REAL</td>

				  <td align="center" class="titulos_de">%</td>

          		</tr>                

                <? 

					for($a = 0;$a < 4; $a++){ 

						@$porcen_meta[$a] = ((current($totalprod[$a])/current($totalmetas[$a]))*100)*.94;

						@$porcen_Desp[$a] = ((current($totalprod[$a])/current($totalmetas[$a]))*100)*.94;

						@$eaea22	=	((current($totalprod[$a])/current($totalmetas[$a]))*100);

				@$jeje[$a] = ($tiras[$a]  / ($Total_pr[$a]+$t_total[$a])  ) *100 ;

				@$por_desp_t[$a]	=	($jeje[$a]/$dMeta['porcentaje_desp'])*100;

				@$tdp[$a]	=	$por_desp_t[$a]*.04;

				

				?>



                <input value="<?=round($tdp[$a]+$porcen_meta[$a],2)?>" type="hidden" id="pre_<?=$a?>" />

                <tr <? if(bcmod($a,2) == 0) echo 'bgcolor="#DDDDDD"';?>>

                   <td>GRUPO_<?=$a+1?></td>

                   <td align="center" class="style5"><? printf( "%.2f", $porcen_meta[$a]);?>%</td>

                   <td align="center" class="style5"><? printf( "%.2f", $tdp[$a])?>%</td>

                   <td align="center" class="style5"><input type="text" class="style5" style=" text-align:right" id="porcentaje_<?=$a?>" name="porcentaje[]" align="right" size="6" value="<?=$_REQUEST['porcentaje'][$a]?>" onchange="suma_limp(<?=$a?>); document.form.submit();" />%</td>

                   <td align="center" class="style5"><input readonly="readonly" class="style5" type="text" style=" text-align:right; <? if(bcmod($a,2) == 0) echo "background-color:#dddddd" ?>; border: 0px;" size="5" value="<? (isset($_REQUEST['modificar']))? printf( "%.2f", $_REQUEST['resultado_'][$a]): printf( "%.2f", $tdp[$a]+$porcen_meta[$a])?>" id="resultado_<?=$a?>" name="resultado_[]" />%</td>

					<td align="right" class="style5"><?= number_format(current($totalmetas[$a]))?></td>

					<td align="center" class="style5"><?=number_format(current($totalprod[$a]))?></td>

					<td align="center" class="style5"><? printf( "%.2f", $eaea22);?>%</td>

					<td align="center" class="style5"><?=$dMeta['porcentaje_desp']?>%</td>

					<td align="center" class="style5"><? printf( "%.2f",$jeje[$a] )?>%</td>

					<td align="center" class="style5"><? printf( "%.2f", $por_desp_t[$a])?>%</td>

                </tr>

                <? } ?>        



     </table>

      

         </form>

  <? } else { ?>

	<table width="100%" align="center">

    	<tr>

        	<td align="center">PRODUCCION EXTRUDER POR GRUPO Y POR TURNO DEL MES DE <?=strtoupper($mes[$meses])?> DE <?=$ano?> <br><br></td>

        </tr>

    	<tr>

        	<td class="style4" align="center">

            <br><br><br>

            NO ES POSIBLE GENERAR ESTE REPORTE POR QUE 

            <br>

            <?=$error?> PARA LA FECHA SELECCIONADA

            <br><br><br><br><br>

			</td>

        </tr>

   </table>

   

<? } ?>

</div></div>

 <table width="100%" align="center">    

            <tr>

                <td colspan="5" align="center">                  <br>

                  <input style="display:none;" type="button" name="pfd" value="Formato de impresion" onClick="genera()" id="logo">

                </td>

          </tr>

        </table>       

<? } ?>







<? if($_REQUEST['tipo'] == 17 ){  



$tipo = $_REQUEST['tipo'];

$numero_maq		=	"";

for($z=0; $z<sizeof($_REQUEST['maq_id_mallas']);$z++){

	$numero_maq	.= $_REQUEST['maq_id_mallas'][$z];

	

	if(sizeof($_REQUEST['maq_id_mallas'])-1 > $z)

	$numero_maq	.= " ,";

	

}

if($tipo == 17)

{ 

	$area 	= 	1; 

	$WH 	=  	"area IN (4) AND id_maquina IN ($numero_maq)";

	$qQuery	= 	"SELECT *  FROM entrada_general ". 

			 	" INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general";

	$cambio	=	"mallas";

	$compara	=	" orden_produccion.id_orden_produccion = tiempos_muertos.id_produccion ";

	$titulo	=	"DE MALLAS";

	$orden	=	"id_orden_produccion";

}



$fecha	=	fecha_tablaInv($_REQUEST['fecha_hist']);

$fecha_f=	fecha_tablaInv($_REQUEST['fecha_hist_f']);



	$qMaquinas 	= 	"SELECT numero, id_maquina FROM maquina  WHERE $WH  ORDER BY numero ASC";

	$rMaquinas=mysql_query($qMaquinas);

	$cant_lic=mysql_num_rows($rMaquinas);

	$cant=ceil($cant_lic/2);

	$a=0;

	while($dMaquinas = mysql_fetch_assoc($rMaquinas))

	{

				$codigo[$a]	=	$dMaquinas['numero'];

				$id[$a] 	= 	$dMaquinas['id_maquina'];

				$a++;

	}

			$reg=0;

?>

<script type="text/javascript" language="javascript">

function genera()

{

document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&fecha_hist_f=<?=$_REQUEST['fecha_hist_f']?>&fecha_hist=<?=$_REQUEST['fecha_hist']?>&id_maquina=<?=$numero_maq?>";

document.form.submit();

document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";

}

</script>

<div align="center" id="tablaimpr">

   	 	<div class="tablaCentrada titulos_e" align="center" id="tabla_reporte" >

        <p class="titulos_reportes" align="center">CAMBIO DE MALLAS <br>DEL <?=$_REQUEST['fecha_hist']?> AL <?=$_REQUEST['fecha_hist_f']?></p>

        <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">

   		<table  width="100%" align="center" >

		<tr>

			<td colspan="2" align="center" width="100%">

            <p align="left">

					<? if($_REQUEST['sup_h'] != 0){ 

							$qS	=	"SELECT nombre FROM supervisor WHERE id_supervisor = ".$_REQUEST['sup_h']."";

							$rS	=	mysql_query($qS);

							$dS	=	mysql_fetch_row($rS); echo "<label style=' width:90px'>SUPERVISOR : </label>" .$dS[0] ;?><br><? } ?>

                    <? if($_REQUEST['oper_h'] != 0){ 

							$qO	=	"SELECT nombre FROM operadores WHERE id_operador = ".$_REQUEST['oper_h']."";

							$rO	=	mysql_query($qO);

							$dO	=	mysql_fetch_row($rO); echo "<label style=' width:90px'>OPERADOR : </label> " .$dO[0] ;

					

					?><? } ?>

			</p>

			<? if($_REQUEST['turnos_h'] == 0){ ?><? } else if($_REQUEST['turnos_h'] == 1){ ?> TURNO MATUTINO<? } else if($_REQUEST['turnos_h'] == 2){ ?>TURNO VESPERTINO<? } else if($_REQUEST['turnos_h'] == 3){ ?> TURNO NOCTURNO<? } ?>

			</td>

		</tr>

   		</table>

		<? 	for($i=0;$i<$cant;$i++) {  ?>

		<table width="350px" <? if($i==2 || $i==5 || $i==8 || $i == 11) { ?>  style="page-break-after: always;" <? } ?> >

        <tr>

		<? for($x=1;$x<=2; $x++){

			if($reg<$cant_lic){

		  

			 	$qReportes	=	$qQuery. " INNER JOIN tiempos_muertos ON $compara ".

								" INNER JOIN oper_maquina ON tiempos_muertos.id_maquina = oper_maquina.id_maquina".

								" WHERE fecha BETWEEN '".$fecha."' AND '".$fecha_f."' AND tiempos_muertos.id_maquina = ".$id[$reg]." AND $cambio = 1 AND entrada_general.repesada = 1 ";

				if($_REQUEST['sup_h'] != 0)

				$qReportes	.=	" AND id_supervisor = ".$_REQUEST['sup_h']."";

				if($_REQUEST['turnos_h'] != 0)

				$qReportes	.=	" AND turno = ".$_REQUEST['turnos_h']."";

				if($_REQUEST['oper_h'] != 0)

				$qReportes	.=	" AND oper_maquina.id_operador = ".$_REQUEST['oper_h']."";



				$qReportes	.=	" GROUP BY $orden ORDER BY fecha ASC";	

				$rReportes	=	mysql_query($qReportes);

				$nReportes	=	mysql_num_rows($rReportes);

				if($nReportes > 0){ ?>

      <td valign="top" align="center" width="350"  >

            <table  width="320" align="center"  style="margin:15px;" >

				<tr>

                	<td colspan="4" align="center"><h3>Maquina No. <?=$codigo[$reg]?> </h3></td>

                </tr>

                <tr>

               	  	<td width="10%" align="center"><h3>Fecha</h3></td>

               	  	<td width="10%" align="center"><h3>Turno</h3></td>

                  	<td width="35%" align="center"><h3>Supervisor</h3></td>

                </tr>

				<? for($a=0; $dTiempo	=	mysql_fetch_assoc($rReportes); $a++){ ?>								

				<tr <? cebra($a)?> >

					<td align="left" class="style7"><?=fecha($dTiempo['fecha'])?></td>

					<td align="center"><?=$dTiempo['turno']?></td>

					<? if($_REQUEST['sup_h'] == 0){ ?> 

                    <td  align="left" class="style5" style="font-size:9px"><?

						$qS	=	"SELECT nombre FROM supervisor WHERE id_supervisor = ".$dTiempo['id_supervisor']."";

						$rS	=	mysql_query($qS);

						$dS	=	mysql_fetch_row($rS); 

						$nombre	= explode(" ", $dS[0]);

						echo $nombre[0].' '.$nombre[1] ;?>

                    </td><? } ?>

				</tr>

				<? } ?>

			</table> <? } else {  $x = $x - 1 ;

				}

			}?>

			</td>

		<? $reg++;} ?> 

      </tr><br /><br />

	</table><?  } ?>

	<div align="center" style="padding: 25px;"><input type="button" name="pfd" class="styleTabla" value="Formato PDF" onClick="genera()"></div>         

	</form>

	</div>

</div>

<? } ?>



<? if($_REQUEST['tipo'] == 14 ){  



$tipo = $_REQUEST['tipo'];

if($tipo == 14) $area = 1;



$fecha	=	fecha_tablaInv($_REQUEST['fecha_incidencia']);

$fechaFin	=	fecha_tablaInv($_REQUEST['fecha_incidencia_f']);



if($area == 1){

  $qReportes	=	"SELECT id_orden_produccion, turno, id_supervisor, orden_produccion.observaciones, fecha FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

					" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."' ORDER BY fecha, turno ASC";

}

$rReportes	=	mysql_query($qReportes); ?>

<script type="text/javascript" language="javascript">

function genera()

{

document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&fecha_incidencia=<?=$_REQUEST['fecha_incidencia']?>&fecha_incidencia_f=<?=$_REQUEST['fecha_incidencia_f']?>";

document.form.submit();

document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";

}

</script>

<div align="center" id="tablaimpr">

	<div class="tablaCentrada titulos_e" id="tabla_reporte" align="center" >

	<p class="titulos_reportes" align="center">REPORTE DE INCIDENCIAS EXTRUDER<br /></p><br />

	<form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">

<? 

	for($b = 1 ; $dReportes	=	mysql_fetch_row($rReportes); $b++){

		if($dReportes[1] == 1) $turno = "MATUTINO";	

		if($dReportes[1] == 2) $turno = "VESPERTINO";	

		if($dReportes[1] == 3) $turno = "NOCTURNO";	?>

		<table width="80%" style="background-color:#eee; padding:10px;">

        <tr>

        	<td>			

            <table width="100%" align="center" style="background-color:#FFFFFF; z-index:200">

            <tr>

                <td align="right" width="11%"><h3>Fecha:</h3></td>

                <td ><?=fecha($dReportes['4'])?></td>

                <td ><h3>TURNO:</h3></td>

                <td><?=$turno?></td>

            </tr>

            <tr >

                <td align="right"><h3>Supervisor:</h3></td>

                <td colspan="7" class="style7">

                        <?   $qSuper	=	"SELECT nombre FROM supervisor WHERE id_supervisor = ".$dReportes[2]."";

                                                $rSuper	=	mysql_query($qSuper);

                                                $dSuper =	mysql_fetch_row($rSuper);

                                                echo $dSuper[0];

                            ?></td>

            </tr>

            <tr id="titulos_e">

                <td width="15%" align="center"><h3>Maquina</h3></td>

                <td width="12%" align="center"><h3>Fallo_Elec.</h3></td>

                <td width="12%" align="center"><h3>Falta_Pers.</h3></td>

                <td width="8%" align="center"><h3>Mantto.</h3></td>

                <td width="8%" align="center"><h3>Otras</h3></td>

                <td width="10%" align="center"><h3>Mallas</h3></td>

                <td width="35%" align="center"><h3>Observaciones</h3></td>

            </tr>                

                            <?	$qTiempos	=	"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina WHERE tipo = $area AND id_produccion = ".$dReportes[0]." ORDER BY maquina.numero ASC";

                                $rTiempos	=	mysql_query($qTiempos);

                                

                                    for($a = 0; $dTiempo	=	mysql_fetch_assoc($rTiempos); $a++){

                                    if($dTiempo['fallo_electrico'] 	== '00:00:00') 	$fallo 			= ""; else $fallo 			= $dTiempo['fallo_electrico'];

                                    if($dTiempo['mantenimiento'] 	== '00:00:00') 	$mantenimiento 	= ""; else $mantenimiento 	= $dTiempo['mantenimiento'];

                                    if($dTiempo['falta_personal'] 	== '00:00:00') 	$falta_personal = ""; else $falta_personal 	= $dTiempo['falta_personal'];

                                    if($dTiempo['otras'] 			== '00:00:00') 	$otras 			= ""; else $otras 			= $dTiempo['otras'];

                                    if($area == 1) $opcion = "mallas";

                                    if($area == 2) $opcion = "cambio_impresion";

                                    if($dTiempo[$opcion]		 	== '0' || $dTiempo[$opcion] == '') 		$mallas		 	= ""; else if($dTiempo[$opcion] != '0')  $mallas			= "SI";

                                    

                                    if($dTiempo['observaciones']	== '0') 		$observaciones	= ""; else $observaciones	=	$dTiempo['observaciones'];

                                    

                                    if($fallo == "" && $mantenimiento == "" && $falta_personal == "" && $otras == "" && $mallas == "" && $observaciones == ""){

                                    $a = $a + 1;

                                    }else {

                                    ?> 

                                        

                                    <tr <? if(bcmod($a,2)==0) echo  ''; else echo 'bgcolor="#DDDDDD"';  ?> >

                                      <td align="left" class="style7"><?=$dTiempo['marca'].' - '.$dTiempo['numero'];?></td>

                                      <td align="center"><?=($fallo != "")?$fallo:"&nbsp;";?></td>

                                      <td align="center"><?=($falta_personal != "")?$falta_personal:"&nbsp;";?></td>

                                      <td align="center"><?=($mantenimiento != "")?$mantenimiento:"&nbsp;";?></td>

                                      <td align="center"><?=($otras != "")?$otras:"&nbsp;";?></td>

                                      <td align="center"><?=($mallas != "")?$mallas:"&nbsp;";?></td>

                                      <td class="style5"><?=($observaciones != "")?$observaciones:"&nbsp;";?></td>

                                    </tr>

                                    <? } 

                                }?>                            

                       <tr>

                            <td colspan="2"  align="right"><h3>Observaciones_Generales:</h3></td>

                            <td colspan="7" class="style5"><?=($dReportes[3] != "")?$dReportes[3]:"&nbsp;";?></td>

                      </tr>

                </table>

            </td>

        	</tr>

        	</table><br /><br />

	        <? } ?>   

 			

            </form>

            <p><br /><input type="button" name="pfd" id="logo"  value="Formato de impresion" onClick="genera()"><br /><br /></p>

        </div>

</div>

<? } ?>







<? if($_REQUEST['tipo'] == 40){ 



	$meMeta	=	$_REQUEST['mes_kgs'];

	$anho	=	$_REQUEST['ano_kgs'];



	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');

	$mes_f	=	$anho.'-'.$mesMetacero.'-01';

	$ultimo_dia = UltimoDia($anho,$meMeta);

	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

///////////////////////SUPERVISORES///////////////////////////////



///////////////////////////PROMEDIO META////////////////////////// 



$qMeta	=	"SELECT * FROM meta WHERE mes = '".$mes_f."' AND area = 3";

$rMeta	=	mysql_query($qMeta);

$nMeta	=	mysql_num_rows($rMeta);

if($nMeta > 0){

$dMeta	=	mysql_fetch_assoc($rMeta);



$metaDia	=	($dMeta['meta_mes_millar']/$ultimo_dia);

$metaHora	=	$metaDia/24;

$mMat		=	$metaHora * 8;

$mVes		=	$metaHora * 7;

$mNoc		=	$metaHora * 9;



$metaDiak	=	($dMeta['meta_mes_kilo']/$ultimo_dia);

$metaHorak	=	$metaDiak/24;

$mMatk		=	$metaHorak * 8;

$mVesk		=	$metaHorak * 7;

$mNock		=	$metaHorak * 9;

}

////////////////////////////TURNOS POR ROL////////////////////////

$qRol1	=	"SELECT  DAY(entrada_general.fecha) AS fecha , entrada_general.turno AS turno, total AS kilogramos, entrada_general.rol AS rol FROM entrada_general ".

			" LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

			" WHERE (entrada_general.fecha BETWEEN '".$mes_f."' AND '".$mesFinal."') ".

			" AND impresion = 0 AND entrada_general.rol = 1 AND entrada_general.repesada = 1 ".

			" GROUP BY entrada_general.fecha, entrada_general.turno, entrada_general.rol ".

			" ORDER BY entrada_general.fecha, entrada_general.rol,entrada_general.turno ASC ";

$rRol1	=	mysql_query($qRol1);

$nRol1	=	mysql_num_rows($rRol1);





$qRol2	=	"SELECT  DAY(entrada_general.fecha) AS fecha , entrada_general.turno AS turno, total AS kilogramos, entrada_general.rol AS rol FROM entrada_general ".

			" LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

			" WHERE (entrada_general.fecha BETWEEN '".$mes_f."' AND '".$mesFinal."')".

			" AND impresion = 0 AND entrada_general.rol = 2 AND entrada_general.repesada = 1 ".

			" GROUP BY entrada_general.fecha, entrada_general.turno, entrada_general.rol ".

			" ORDER BY entrada_general.fecha, entrada_general.rol,entrada_general.turno ASC ";

$rRol2	=	mysql_query($qRol2);

$nRol2	=	mysql_num_rows($rRol2);



$qRol3	=	"SELECT  DAY(entrada_general.fecha) AS fecha, entrada_general.turno AS turno, SUM(total) AS kilogramos, entrada_general.rol AS rol FROM entrada_general ".

			" LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

			" WHERE (entrada_general.fecha BETWEEN '".$mes_f."' AND '".$mesFinal."')".

			" AND impresion = 0 AND entrada_general.rol = 3 AND entrada_general.repesada = 1".

			" GROUP BY entrada_general.fecha, entrada_general.turno, entrada_general.rol ".

			" ORDER BY entrada_general.fecha, entrada_general.rol,entrada_general.turno ASC ";

$rRol3	=	mysql_query($qRol3);

$nRol3	=	mysql_num_rows($rRol3);



$qRol4	=	"SELECT  DAY(entrada_general.fecha) AS fecha, entrada_general.turno AS turno, SUM(total) AS kilogramos, entrada_general.rol AS rol FROM entrada_general ".

			" LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

			" WHERE (entrada_general.fecha BETWEEN '".$mes_f."' AND '".$mesFinal."') ".

			" AND impresion = 0 AND entrada_general.rol = 4 AND entrada_general.repesada = 1".

			" GROUP BY entrada_general.fecha, entrada_general.turno, entrada_general.rol ".

			" ORDER BY entrada_general.fecha, entrada_general.rol,entrada_general.turno ASC ";

$rRol4	=	mysql_query($qRol4);

$nRol4	=	mysql_num_rows($rRol4);





if($nRol1 > 0 && $nRol2 > 0 && $nRol3 > 0  && $nRol4 > 0 ){

$qTurnos	=	"SELECT  DAY(entrada_general.fecha) AS fecha, SUM(total) AS kilogramos, entrada_general.turno AS turno FROM entrada_general ".

			" LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

			" WHERE (entrada_general.fecha BETWEEN '".$mes_f."' AND '".$mesFinal."') ".

			" AND impresion = 0 AND entrada_general.repesada = 1".

			" GROUP BY entrada_general.fecha, entrada_general.turno ".

			" ORDER BY entrada_general.fecha, entrada_general.turno ASC ";

$rTurnos	=	mysql_query($qTurnos);

$nTurnos	=	mysql_num_rows($rTurnos);



///////////// DIAS LABORADOS ////////////////////////////////////

$qFecha	=	"SELECT DAY(fecha) AS dia , fecha FROM entrada_general ".

			" WHERE (entrada_general.fecha BETWEEN '".$mes_f."' AND '".$mesFinal."') ".

			" AND impresion = 0 AND repesada = 1 ".

			" GROUP BY entrada_general.fecha ".

			" ORDER BY entrada_general.fecha ASC ";	

$rFecha	=	mysql_query($qFecha);

$nFecha	=	mysql_num_rows($rFecha);







for($t = 0 ; $dFecha	=	mysql_fetch_assoc($rFecha); $t++ ){

	$fecha[$t]		=	$dFecha['fecha'];

	$numFecha[$t]	=	$dFecha['dia'];

}





//////////////// ASIGNACION POR ROLES DE KG POR HORAS /////////////////////////////



for($t = 0 ; $dTurnos	=	mysql_fetch_assoc($rTurnos); $t++ ){

	$pkilo[$t]	=	$dTurnos['kilogramos'];

	if($dTurnos['turno'] == 1) $turnos = 8;

	if($dTurnos['turno'] == 2) $turnos = 7;

	if($dTurnos['turno'] == 3) $turnos = 9;

	$pturnos[$t]	=	$turnos;

}



for($t = 0 ; $dGrupo	=	mysql_fetch_assoc($rRol1); $t++ ){



	  $numero = $t;

	  $b = $t - 1;

	

	if($dGrupo['turno'] == 1) $hora = 8;

	if($dGrupo['turno'] == 2) $hora = 7;

	if($dGrupo['turno'] == 3) $hora = 9;

								 

	 		if(($dGrupo['kilogramos'] == 0 || $dGrupo['kilogramos'] == '' ) ){

				$kilogramos[$t]  	= 	0;

				$turno[$t]  		= 	0;						

				$rol[$t]  			= 	0;

				$fecha1[$t]  		= $dGrupo['fecha'];

			}

			else 

			{ 						

					if( ( $dGrupo['turno'] == 3 && $turno[$b] == 9 ) )

					{		

									

							$kilogramos[$t] 	=	$dGrupo['kilogramos'];

							$turno[$t] 			=	$hora;

							$rol[$t]  			= 	$dGrupo['rol'];

							$fecha1[$t]  		= $dGrupo['fecha'];

							

							for($a = 1; $a < 3; ){

								$t = $t + $a;

								$a;

								if($a == 2) $t = $t - 1;

								$kilogramos[$t]  	= 	0;

								$turno[$t]  		= 	0;						

								$rol[$t]  			= 	0;

								$fecha1[$t]  	= $dGrupo['fecha'] + $a;

								$a  = $a + 1;

							}

							

					}			

						else 

					{

							$kilogramos[$t] = $dGrupo['kilogramos'];

							$turno[$t]  	= $hora;

							$rol[$t]  		= $dGrupo['rol'];

							$fecha1[$t]  	= $dGrupo['fecha'];

					}

			}

}



for($t = 0 ; $dGrupo2	=	mysql_fetch_assoc($rRol2); $t++ ){

	  $numero = $t;

	  $b = $t - 1;

	

	if($dGrupo2['turno'] == 1) $hora2 = 8;

	if($dGrupo2['turno'] == 2) $hora2 = 7;

	if($dGrupo2['turno'] == 3) $hora2 = 9;

								 

	 		if(($dGrupo2['kilogramos'] == 0 || $dGrupo2['kilogramos'] == '' ) ){

				$kilogramos2[$t]  	= 	0;

				$turno2[$t]  		= 	$hora2;						

				$rol2[$t]  			= 	0;

				$fecha2[$t]  		= 	$dGrupo2['fecha'];

									

			}

			else 

			{ 						

					if( ( $dGrupo2['turno'] == 3 && $turno2[$b] == 9 )  )

					{		

									

							$kilogramos2[$t] 	=	$dGrupo2['kilogramos'];

							$turno2[$t] 		=	$hora2;

							$rol2[$t]  			= 	$dGrupo2['rol'];

							$fecha2[$t]  		=   $dGrupo2['fecha'];

							

							for($a = 1; $a < 3; ){

								$t = $t + $a;

								$a;

								if($a == 2) $t = $t - 1;

								$kilogramos2[$t]  	= 	0;

								$turno2[$t]  		= 	0;						

								$rol2[$t]  			= 	0;

								$fecha2[$t]  	= $dGrupo2['fecha'] + $a;

								$a  = $a + 1;

							}

							

					}			

						else 

					{

							$kilogramos2[$t] = $dGrupo2['kilogramos'];

							$turno2[$t]  	= $hora2;

							$rol2[$t]  		= $dGrupo2['rol'];

							$fecha2[$t]  	= $dGrupo2['fecha'];

					}

			}

}





for($t = 0 ; $dGrupo3	=	mysql_fetch_assoc($rRol3); $t++ ){

	  $numero = $t;

	  $b = $t - 1;

	

	if($dGrupo3['turno'] == 1) $hora3 = 8;

	if($dGrupo3['turno'] == 2) $hora3 = 7;

	if($dGrupo3['turno'] == 3) $hora3 = 9;

								 

	 		if(($dGrupo3['kilogramos'] == 0 || $dGrupo3['kilogramos'] == '' ) ){

				$kilogramos3[$t]  	= 	0;

				$turno3[$t]  		= 	0;						

				$rol3[$t]  			= 	0;

				$fecha3[$t]  		= $dGrupo3['fecha'];

									

			}

			else 

			{ 						

					if( ( $dGrupo3['turno'] == 3 && $turno3[$b] == 9 )  )

					{		

									

							$kilogramos3[$t] 	=	$dGrupo3['kilogramos'];

							$turno3[$t] 		=	$hora3;

							$rol3[$t]  			= 	$dGrupo3['rol'];

							$fecha3[$t]  		= 	$dGrupo3['fecha'];

							

							for($a = 1; $a < 3; ){

								$t = $t + $a;

								$a;

								if($a == 2) $t = $t - 1;

								$kilogramos3[$t]  	= 	0;

								$turno3[$t]  		= 	0;						

								$rol3[$t]  			= 	0;

								$fecha3[$t]  	= $dGrupo3['fecha'] + $a;

								$a  = $a + 1;

							}

							

					}			

						else 

					{

							$kilogramos3[$t] = $dGrupo3['kilogramos'];

							$turno3[$t]  	 = $hora3;

							$rol3[$t]  		 = $dGrupo3['rol'];

							$fecha3[$t]  	 = $dGrupo3['fecha'];

					}

			}

}





for($t = 0 ; $dGrupo4	=	mysql_fetch_assoc($rRol4); $t++ ){

	  $numero = $t;

	  $b = $t - 1;

	

	if($dGrupo4['turno'] == 1) $hora4 = 8;

	if($dGrupo4['turno'] == 2) $hora4 = 7;

	if($dGrupo4['turno'] == 3) $hora4 = 9;

								 

	 		if(($dGrupo4['kilogramos'] == 0 || $dGrupo4['kilogramos'] == '' ) ){

				$kilogramos4[$t]  	= 	0;

				$turno4[$t]  		= 	$hora4;						

				$rol4[$t]  			= 	0;

				$fecha4[$t]  		= 	$dGrupo4['fecha'];

									

			}

			else 

			{ 						

					if( ( $dGrupo4['turno'] == 3 && $turno4[$b] == 9 )  )

					{		

									

							$kilogramos4[$t] 	=	$dGrupo4['kilogramos'];

							$turno4[$t] 		=	$hora4;

							$rol4[$t]  			= 	$dGrupo4['rol'];

							$fecha4[$t]  		= 	$dGrupo4['fecha'];

							

							for($a = 1; $a < 3; ){

								$t = $t + $a;

								$a;

								if($a == 2) $t = $t - 1;

								$kilogramos4[$t]  	= 	0;

								$turno4[$t]  		= 	0;						

								$rol4[$t]  			= 	0;

								$fecha4[$t]  	= $dGrupo4['fecha'] + $a;

								$a  = $a + 1;

							}

							

					}			

						else 

					{

							$kilogramos4[$t] = $dGrupo4['kilogramos'];

							$turno4[$t]  	 = $hora4;

							$rol4[$t]  		 = $dGrupo4['rol'];

							$fecha4[$t]  	 = $dGrupo4['fecha'];

					}

			} 

			

}

}

?>

<script type="text/javascript" language="javascript">

function genera()

{

document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&fecha_hist_f=<?=$_REQUEST['fecha_hist_f']?>&fecha_hist=<?=$_REQUEST['fecha_hist']?>&sup_h=<?=$_REQUEST['sup_h']?>&oper_h=<?=$_REQUEST['oper_h']?>&turnos_h=<?=$_REQUEST['turnos_h']?>";

document.form.submit();

document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";

}

</script>

<div align="center" id="tablaimpr">

<div id="tabla_reporte" class=" tablaCentrada titulos_e" >

<p align="center" class="titulos_reportes">KGS. HORAS POR GRUPO Y POR TURNO EN EXTRUDER <br /><?=$mes[$meMeta]?> DEL <?=$anho?></p><br />

<? if($nRol1 > 0 && $nRol2 > 0 && $nRol3 > 0  && $nRol4 > 0 ){?>

<form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">

  <table width="700" >

    <tr>

      <td width="467" align="center" valign="top">

      	<table  width="450" align="center" style="margin:15px 2px;" >

          <tr>

            <td width="80">&nbsp;</td>

            <? 

							  	$qS	=	"SELECT nombre FROM supervisor WHERE area = 1 and activo = 0 ORDER BY rol ASC";

								$rS	=	mysql_query($qS);



								while($dS	=	mysql_fetch_assoc($rS)){ 

									$nombre			=	explode(" ",$dS['nombre'] );

									

							?>

            <td colspan="2" align="center"><h3>

              <?=$nombre[0].'_'.$nombre[1]{0}.'.'; ?>

            </h3></td>

            <? } ?>

          </tr>

          <tr>

            <td align="center"><h3>Fecha</h3></td>

            <td width="3%" align="center"><h3>&nbsp;</h3></td>

            <td width="10%" align="center"><h3>A.D.</h3></td>

            <td width="7%" align="center"><h3>&nbsp;</h3></td>

            <td width="9%" align="center"><h3>A.D.</h3></td>

            <td width="3%" align="center"><h3>&nbsp;</h3></td>

            <td width="9%" align="center"><h3>A.D.</h3></td>

            <td width="3%" align="center"><h3>&nbsp;</h3></td>

            <td width="9%" align="center"><h3>A.D.</h3></td>

          </tr>

          <? for($t = 0 , $c = 0, $d = 0, $z = 0, $o = 0; $t < $nFecha ;$t++){ ?>

          <tr>

            <td align="center" class="style5"><?=fecha($fecha[$t])?></td>

            <? 

			if( $numFecha[$t] < $fecha1[$t-$c])

			{ 

				$valorA1	=	"0";

				$valorA2 	= 	"0.0";

				$c = $c + 1;

			} 

			else 

			{ 

				$valorA1	=	$turno[$t-$c];

				if($turno[$t-$c] != 0 )

				{

					$promedio = $kilogramos[$t-$c]/$turno[$t-$c];

					$valorA2 = $promedio;

				}   

				else 

				{

					$valorA2	= "0.0";	

				}			

			}?>

            <td width="3%"  align="right" class="style5" ><?=$valorA1?></td>

            <td width="10%"  align="right" class="style5" bgcolor="#EEEEEE"><?=number_format($valorA2,1)?></td>            

			<? if( $numFecha[$t] < $fecha2[$t-$d])

			{ 	

				$valorB1	=	"0";

				$valorB2 = 	"0.0";

				$d = $d + 1;

			 } 

			 else 

			 {	

			 	$valorB1 = $turno2[$t-$d];

				if($turno2[$t-$d] != 0 )

				{

					$promedio2 = $kilogramos2[$t-$d]/$turno2[$t-$d];

					$valorB2 = $promedio2;

				}   

				else 

				{

					$valorB2 =  "0.0";	

				}		

			}

			?>

            <td width="3%"  align="right" class="style5" ><?=$valorB1?></td>

            <td width="10%"  align="right" class="style5" bgcolor="#EEEEEE"><?=number_format($valorB2,1)?></td>            

			<? if( $numFecha[$t] < $fecha3[$t-$z])

			{	

				$valorC1	=	"0";

				$valorC2 	= 	"0.0";

				$z = $z + 1;

			} 

			else 

			{ 

				$valorC1	=	$turno3[$t-$z];

				if($turno3[$t-$z] != 0 )

				{

					$promedio3 	= $kilogramos3[$t-$z]/$turno3[$t-$z];

					$valorC2		=	$promedio3;

				}   

				else 

				{

					$valorC2 =  "0.0";	

				}

			} ?>

            <td width="3%"  align="right" class="style5" ><?=$valorC1?></td>

            <td width="10%"  align="right" class="style5" bgcolor="#EEEEEE"><?=number_format($valorC2,1)?></td>  

            <? if( $numFecha[$t] < $fecha4[$t-$o])

			{				

				$valorD1	=	"0";

				$valorD2 = 	"0.0";

				$o = $o + 1;	

			} 

			else 

			{		 

				$valorD1	=	$turno4[$t-$o];

				if($turno4[$t-$o] != 0 )

				{

					$promedio4 = $kilogramos4[$t-$o]/$turno4[$t-$o];

					$valorD2	=	$promedio4;

				}   

				else 

				{

					$valorD2 =  "0.0";	

				}	

			}

			?>

            

            <td width="3%"  align="right" class="style5" ><?=$valorD1?></td>

            <td width="10%"  align="right" class="style5" bgcolor="#EEEEEE"><?=number_format($valorD2,1)?></td>            

       	</tr>

          <? 

						  $total_kg[$t] = $kilogramos[$t]+$kilogramos2[$t]+$kilogramos3[$t]+$kilogramos4[$t];

						  $kg_p		+=	$promedio;

						  $kg_p2	+=	$promedio2;

						  $kg_p3	+=	$promedio3;

						  $kg_p4	+=	$promedio4;

						  

						  $total_tn1	+= 	$turno[$t];

						  $total_tn2	+= 	$turno2[$t];

						  $total_tn3	+= 	$turno3[$t];

						  $total_tn4	+= 	$turno4[$t];

						  } 

						  $prom		=	$kg_p/$nFecha;

						  $prom2	=	$kg_p2/$nFecha;

						  $prom3	=	$kg_p3/$nFecha;

						  $prom4	=	$kg_p4/$nFecha; 

						  

						  ?>

          <tr>

            <td><h3>PROMEDIO</h3></td>

            <td align="right" class="style5"><?=$total_tn1?></td>

            <td align="right" class="style7"bgcolor="#EEEEEE"><? printf("%.1f",$prom)?></td>

            <td align="right" class="style5"><?=$total_tn2?></td>

            <td align="right" class="style7"bgcolor="#EEEEEE"><? printf("%.1f",$prom2)?></td>

            <td align="right" class="style5"><?=$total_tn3?></td>

            <td align="right" class="style7"bgcolor="#EEEEEE"><? printf("%.1f",$prom3)?></td>

            <td align="right" class="style5"><?=$total_tn4?></td>

            <td align="right" class="style7"bgcolor="#EEEEEE"><? printf("%.1f",$prom4)?></td>

          </tr>

          <tr>

            <td><h3>X 24 hrs.</h3></td>

            <td align="right" class="style5"></td>

            <td align="right" class="style7"bgcolor="#EEEEEE"><? printf("%.1f",$prom*24)?></td>

            <td align="right" class="style5"></td>

            <td align="right" class="style7"bgcolor="#EEEEEE"><? printf("%.1f",$prom2*24)?></td>

            <td align="right" class="style5"></td>

            <td align="right" class="style7"bgcolor="#EEEEEE"><? printf("%.1f",$prom3*24)?></td>

            <td align="right" class="style5"></td>

            <td align="right" class="style7"bgcolor="#EEEEEE"><? printf("%.1f",$prom4*24)?></td>

          </tr>

      </table></td>

 

      <td width="221" valign="top" align="center">

      	<table width="90%" align="center"  style="margin:15px 2px;">

          <tr>

            <td width="33%" align="center"><h3>Turno_1</h3></td>

            <td width="33%" align="center"><h3>Turno_2</h3></td>

            <td width="34%" align="center"><h3>Turno_3</h3></td>

            <!-- <td width="21%" align="center"><h3>PROM</h3></td> -->

          </tr>

          <tr>

            <td colspan="4" align="center"><h3>META X HORA</h3></td>

          </tr>

          <? for($t = 0, $r = 0; $t < sizeof($pturnos) ;$t++, $r++){ ?>

          <tr>

            <? for($s = 0; $s < 3 ;$s++){  @$resul[$t]	=	$pkilo[$t]/$pturnos[$t];  ?>

            <td  class="style5" align="right" <? 

								if($s == 1) echo "bgcolor='#EEEEEE'";?>><? printf("%.1f",$resul[$t])?></td>

            <? 	$pro[$r]	+=	$resul[$t];

							  		$t = $t + 1;  

							  } 	$t = $t - 1;  ?>

            <!--    <td align="right" style="color:#FF0000" <? if(bcmod($t,2) == 0) echo "bgcolor='#EEEEEE'";?>><? printf("%.1f",$pro[$r]/3)?></td> -->

          </tr>

          <? } 

							for($a = 0; $a <= $nTurnos; $a = $a + 3){

									$uno	+= $resul[$a];

							}

							for($a = 1; $a <=  $nTurnos; $a = $a + 3){

									$dos	+=	$resul[$a];

							}

							for($a = 2; $a <= $nTurnos; $a = $a + 3){

									$tres	+=	$resul[$a];

							}							

							?>

          <tr>

            <td align="right" class="style7" style="height:25px;" ><? printf("%.1f",$uno)?></td>

            <td align="right" class="style7" style="height:25px;" ><? printf("%.1f",$dos)?></td>

            <td align="right" class="style7" style="height:25px;" ><? printf("%.1f",$tres)?></td>

          </tr>

          <tr>

            <td align="right" class="style7" style="height:25px;" ><? printf("%.1f",$uno/$nFecha)?></td>

            <td align="right" class="style7" style="height:25px;" ><? printf("%.1f",$dos/$nFecha)?></td>

            <td align="right" class="style7" style="height:25px;" ><? printf("%.1f",$tres/$nFecha)?></td>

          </tr>

      </table></td>

    </tr>



  </table>

</form><? } else { ?>

	<table width="100%" align="center">

  <tr>

        	<td class="style4" align="center">

            <br><br><br>

            NO ES POSIBLE GENERAR ESTE REPORTE POR QUE 

            <br>

            NO HAY DATOS REGISTRADOS PARA LA FECHA SELECCIONADA

            <br><br><br><br><br>

	</td>

        </tr>

   </table>

<? } ?>

</div>

</div>

<? } ?>



<? if($_REQUEST['tipo'] == 41){ 



///////////////////////////SELECCION DE MAQUINAS ///////////////////////////

$qMaquinas	=	"SELECT * FROM maquina WHERE area = 4 ORDER BY numero ASC ";

$rMaquinas	=	mysql_query($qMaquinas);

$nMaquinas	=	mysql_num_rows($rMaquinas);



for($t = 0; $dMaquinas = mysql_fetch_assoc($rMaquinas);$t++){

	$numero_maq[$t] = $dMaquinas['numero'];

}

//////////////////////////SELECCION DE OPERADORES///////////////////////////

/*$qMaquinas	=	' SELECT GROUP_CONCAT(nombre ORDER BY nombre ASC SEPARATOR "<br>" ) AS nombre  FROM operadores  '.

				" WHERE puesto LIKE '%oper%' AND operadores.area = 1 GROUP BY operadores.rol ORDER BY operadores.rol DESC ";

*/

$qMaquinas	=	' SELECT nombre FROM supervisor  '.

				" WHERE area = 1 AND activo = 0  ORDER BY rol ASC ";



$rMaquinas	=	mysql_query($qMaquinas);

$nMaquinas	=	mysql_num_rows($rMaquinas);

for($t = 0 ; $dMaquinas = mysql_fetch_assoc($rMaquinas) ;$t++){

	$nombres	=	explode(" ",$dMaquinas['nombre']);

 	$grupos[$t] = $nombres[0].' '.$nombres[1]{0}.'.';

	

	

}



///////////////////////CARGAMOS LAS MAQUINAS CON SU PRODUCCION//////////////////////

/*

$qProd	=	' SELECT SUM(subtotal) AS total, rol, resumen_maquina_ex.id_maquina  FROM resumen_maquina_ex '.

			' INNER JOIN orden_produccion ON resumen_maquina_ex.id_orden_produccion = orden_produccion.id_orden_produccion '.

			' INNER JOIN entrada_general ON orden_produccion.id_entrada_general = entrada_general.id_entrada_general '.

			' WHERE YEAR(fecha) = "'.$_REQUEST['ano_rpr'].'" AND MONTH(fecha) = "'.$_REQUEST['mes_rpr'].'" GROUP BY id_maquina, rol ORDER BY rol,id_maquina ASC';

*/

$qProd = 'SELECT SUM(subtotal) AS total, rol, resumen_maquina_ex.id_maquina

		  FROM resumen_maquina_ex, orden_produccion, entrada_general, maquina

		  WHERE resumen_maquina_ex.id_orden_produccion = orden_produccion.id_orden_produccion

		  AND orden_produccion.id_entrada_general = entrada_general.id_entrada_general

		  AND YEAR(fecha) = "'.$_REQUEST['ano_rpr'].'"

		  AND MONTH(fecha) = "'.$_REQUEST['mes_rpr'].'"

		  AND resumen_maquina_ex.id_maquina = maquina.id_maquina

		  GROUP BY id_maquina, rol

		  ORDER BY rol, numero ASC';

$rProd	=	mysql_query($qProd);

$nProd	=	mysql_num_rows($rProd);



$qDias =	" SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_ex.id_maquina, rol,  maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_ex "	.

			" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".

			" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".

			" INNER JOIN maquina 			ON resumen_maquina_ex.id_maquina 			= maquina.id_maquina ".

			" WHERE subtotal != 0 AND YEAR(fecha) = '".$_REQUEST['ano_rpr']."' AND MONTH(fecha) = '".$_REQUEST['mes_rpr']."' AND  impresion = 0 GROUP BY  maquina.numero".

			" ORDER BY maquina.numero ASC ";

$rDias	=	mysql_query($qDias);



	$qId	=	"SELECT id_maquina FROM maquina WHERE area = 4 ORDER BY numero ASC";

	$rId	=	mysql_query($qId);

	for($r=0;$mId	=	mysql_fetch_assoc($rId);$r++){

			$maqID[$r]	=	$mId['id_maquina'];

	}



	for($t = 0 ; $dDias = mysql_fetch_assoc($rDias) ; $t++ ){

			$pParos[$t] = $dDias['paros'];

			$pId[$t]	= $dDias['id_maquina'];

	}



	for($c=0;$c<sizeof($maqID);$c++){

		$temporal[$c]		=	$pParos[$c];

		$temporal_id[$c]	=	$maqID[$c];
	}

$r = 0;

for($t=0;$t<sizeof($maqID);$t++){

	
	if($maqID[$t] != $pId[$t]){

		$pId[$t] 	= 	$maqID[$t];

			for($a=$t;$a<sizeof($maqID);$a++){

			$pId[$a+1]		=	$maqID[$a+1];

			$pParos[$a+1]	=	$temporal[$a];

		}

		$pParos[$t]	=	'0.0';

		

	}

 $pId[$t].'-'.$pParos[$t].'<br>';

}





if($nProd > 0 ) { 

	for($t = 0,$r = 0 ,$y = 0 ,$u = 0 ; $dProd = mysql_fetch_assoc($rProd) ; ){

	

		if($dProd['rol'] == 1){

		$produc[0][$t] = $dProd['total'];

		$t = $t+1;

		

		}

		

		if($dProd['rol'] == 2){

		$produc[1][$r] = $dProd['total'];

		$r = $r+1;

		}

	

		if($dProd['rol'] == 3){

		$produc[2][$y] = $dProd['total'];

		$y = $y+1;

		}

		

		if($dProd['rol'] == 4){

		$produc[3][$u] = $dProd['total'];

		$u = $u+1;

		}

	}

	

	/////////////////////SUMA DE TOTALES POR MAQUINAS/////////////////////////////////////////

	for($r=0;$r<21;$r++){

		for($t=0;$t<4;$t++){

			$total[$r] += $produc[$t][$r];

		}

	}

	/////////////////////SACAMOS LOS DESPERDICIOS/////////////////////////////////

	$qDesp	=	' SELECT SUM(desperdicio_tira) AS tira, rol, SUM(desperdicio_duro) AS duro, COUNT(turno) as turnos  FROM orden_produccion '.

				' INNER JOIN entrada_general ON orden_produccion.id_entrada_general = entrada_general.id_entrada_general '.

				' WHERE YEAR(fecha) = "'.$_REQUEST['ano_rpr'].'" AND MONTH(fecha) = "'.$_REQUEST['mes_rpr'].'" GROUP BY rol ORDER BY rol ASC';

	$rDesp	=	mysql_query($qDesp);

	for($t=0; $dDesp = mysql_fetch_assoc($rDesp);$t++){

		$desp[$t] 	= 	$dDesp['tira'] + $dDesp['duro'] ;

		$turnos[$t]	=	$dDesp['turnos'];

		$Total_turnos +=	$turnos[$t]; 

		$total_dias	   =	$Total_turnos/3;

		$Desp_totales	+=	$desp[$t];

	}

	

	/////////////////////SUMA TOTAL DE PRODUCCION POR GRUPO/////////////////////////////////////////

	for($t=0;$t<4;$t++){

		for($r=0;$r<21;$r++){

			$total_prod[$t] += $produc[$t][$r];

		}

		$total_prom_d[$t] = $total_prod[$t]/$turnos[$t];

	}

	/////////////////////PORCENTAJES DE DESPERDICIO/PROD///////////////////////////////

	for($t=0;$t<4;$t++){

	$Total_desp[$t]	=	($desp[$t]/$total_prod[$t])*100;

	$Total_total_d	+= $desp[$t];

	}

}

?>

<script type="text/javascript" language="javascript">

function genera()

{

document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>";

document.form.submit();

document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";

}

</script>

<div align="center" id="tablaimpr">

	<div class="titulos_e tablaCentrada" id="tabla_reporte">

    <p align="center" class="titulos_reportes">PRODUCCION EXTRUDER CORRESPONDIENTE AL MES DE <?=strtoupper($mes[$_REQUEST['mes_rpr']])?> DE <?=$_REQUEST['ano_rpr'] ?></p><br>      
     
<? if($nProd > 0){ ?>

        <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">

                    <? for($limite = 12, $t=0,$a=0,$c=$limite,$d=0, $e=0; $e < sizeof($numero_maq)/$limite;  $c=$c+$limite, $e++, $a=$a-4 ){ ?>

                    <table  align="center" width="85%">

						<tr>

                            <td width="208" align="center"><h3>Supervisor</h3></td>

	            		  	<? for(;$d < $c ;$d++){

							if($numero_maq[$d] != ""){ ?>

                            <td width="110" align="center"><h3>Ext_<?=$numero_maq[$d]?></h3></td>

						  	<? } } ?>

                        </tr>

                          <? for(;$a<sizeof($grupos); $a++){ 

						   	if($t < sizeof($numero_maq)){?>

                          <tr >

                            <td  class="style5" align="left" style="font-size:9px"><?=$grupos[$a]?></td>

                            <? for(;$t < $c; $t++){

							if($produc[$a][$t] != ""){ ?>

                            <td height="25%" valign="middle" align="center"><?=number_format($produc[$a][$t]);?></td>

                            <?  } } ?>

                          </tr>

                          <? $t = $t-$limite;} }?>

                      <tr>

                        <td align="right"><h3>Totales:</h3></td>

                        <? for(;$t < $c; $t++){

							if( $t< sizeof($numero_maq)){ ?>

                        <td align="center" bgcolor="#EEEEEE" class="style5"><?=number_format($total[$t])?></td>

                        <? } } $t = $t-$limite; ?>

                      </tr>

                      <tr>

                        <td align="right"><h3>Promedio:</h3></td>

                        <? for(;$t < $c ;$t++){

							if( $t< sizeof($numero_maq)){ ?>

                        <td align="center" class="style5"><b><?=@number_format($total[$t]/$pParos[$t]);?></b></td>

                        <? }  }?>

                      </tr>

                      </table><br /><br />

                      <? } ?>



                    <table  align="center" width="85%">

                    <tr>

                        <td width="190" align="center" class="titulos_de">Supervisor</td>

                        <td width="122" align="center" class="titulos_de">Total</td>

                        <td width="131" align="center" class="titulos_de">Desperdicio</td>

                        <td width="150" align="center" class="titulos_de">Porcentaje_desp</td>

                        <td width="107" align="center" class="titulos_de">Turnos</td>

                        <td width="124" align="center" class="titulos_de">Promedio</td>

                    </tr>

               <? for($a=0; $a < sizeof($grupos); $a++){ ?>

                        <tr bgcolor="#EEEEEE">

                        <td width="190" class="style5" align="left" style="font-size:9px"><?=$grupos[$a]?></td>

                        <td width="122" align="center" bgcolor="#F0F0F0" ><?=number_format($total_prod[$a])?></td>

                        <td width="131" align="center" bgcolor="#F0F0F0" ><?=number_format($desp[$a])?></td>

                        <td width="150" align="center" bgcolor="#F0F0F0" ><? printf("%.1f", $Total_desp[$a])?> %</td>

                        <td width="107" align="center" bgcolor="#F0F0F0" ><?=$turnos[$a]?></td>

                        <td width="124" align="center" bgcolor="#F0F0F0"  ><?=number_format($total_prom_d[$a])?></td>

					  </tr>

				<?  $total_total += $total_prod[$a];}	?>

 						<tr>

                        <td align="right"  class="titulos_de">Totales:</td>

                        <td align="center" bgcolor="#F0F0F0" class="style4"><?=number_format($total_total)?></td>

                        <td align="center" bgcolor="#F0F0F0" class="style4"><?=number_format($Desp_totales)?></td>

                        <td align="center" bgcolor="#F0F0F0" class="style4"><? printf("%.1f", ($Total_total_d/$total_total)*100)?> %</td>

                        <td align="center" bgcolor="#F0F0F0" class="style4"><? printf("%.1f", $total_dias)?></td>

                        <td  align="center" bgcolor="#F0F0F0" class="style4" ><?=number_format($total_total/$total_dias)?></td>

                      </tr>                        

					<tr>

                        <td align="right" class="titulos_de">Promedio:</td>

                        <td align="center" bgcolor="#F0F0F0" class="style4"><?=number_format($total_total/UltimoDia($_REQUEST['ano_rpr'],$_REQUEST['mes_rpr']))?></td>

                   	</tr>                           

				</table>



    </form>

<? } else { ?>

	<table width="100%" align="center">



    	<tr>

        	<td class="style4" align="center">

            <br><br><br>

            NO ES POSIBLE GENERAR ESTE REPORTE POR QUE 

            <br>

            NO HAY DATOS REGISTRADOS PARA LA FECHA SELECCIONADA

            <br><br><br><br><br>

	</td>

        </tr>

   </table>

<? } ?>

	</div>

</div>

<? } ?>









<? if($_REQUEST['tipo'] == 42){ 

/*

$meMeta	=	$_REQUEST['mes_grafica'];

$anho	=	$_REQUEST['ano_grafica'];



	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');

	$meses	=	$anho.'-'.$mesMetacero.'-01';

	$ultimo_dia = UltimoDia($anho,$meMeta);

	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

*/

	$mes_f		=	fecha_tablaInv($_REQUEST['fecha_mp']);

	$date		=	explode('-',$mes_f);

	$mesFinal	=	fecha_tablaInv($_REQUEST['fecha_mpf']);

	$meses		=	$date[0].'-'.$date[1].'-01';

/////////////////////// PAROS POR DIA /////////////////////////

 $qSelectTiemposExtr	=	"SELECT SUM(TIME_TO_SEC(mantenimiento)) AS mantenimiento ,".

						" SUM(TIME_TO_SEC(falta_personal)) AS falta_personal ,".

						" SUM(TIME_TO_SEC(fallo_electrico+otras)) AS otras ".

						" FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general ".

						" LEFT JOIN tiempos_muertos ON orden_produccion.id_orden_produccion = tiempos_muertos.id_produccion ".

						" WHERE tipo = 1 AND fecha BETWEEN '".$mes_f."' AND '".$mesFinal."' ".

						" AND entrada_general.impresion = 0 ".

						" GROUP BY entrada_general.fecha ORDER BY entrada_general.fecha ASC";

$rSelectTiemposExtr	=	mysql_query($qSelectTiemposExtr);



for($t = 0; $dSelectTiemposExtr = mysql_fetch_assoc($rSelectTiemposExtr);$t++){

		$falta[$t]		=	$dSelectTiemposExtr['falta_personal'];

		$mantto[$t]		=	$dSelectTiemposExtr['mantenimiento'];

		$otras[$t]		=	$dSelectTiemposExtr['otras'];

		$turnos[$t]		=	$falta[$t] + $mantto[$t] + $otras[$t];

		

		///////TOTALES/////

		$total_otras	+=	$otras[$t];

		$total_mannto	+=	$mantto[$t];

		$total_falta	+=	$falta[$t];

		$total_turnos	+=	$turnos[$t];	

}

/////////////////////// PAROS POR MAQUINA /////////////////////////

$qSelectTiemposExtrM	="SELECT  SUM(TIME_TO_SEC(mantenimiento)) AS mantenimiento ,".

						" SUM(TIME_TO_SEC(falta_personal)) AS falta_personal ,".

						" SUM(TIME_TO_SEC(fallo_electrico+otras)) AS otras ".

						" FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general ".

						" LEFT JOIN tiempos_muertos ON orden_produccion.id_orden_produccion = tiempos_muertos.id_produccion ".

						" WHERE tipo = 1 AND fecha BETWEEN '".$mes_f."' AND '".$mesFinal."'   AND entrada_general.impresion = 0 GROUP BY tiempos_muertos.id_maquina ORDER BY tiempos_muertos.id_maquina ASC";

$rSelectTiemposExtrM	=	mysql_query($qSelectTiemposExtrM);





for($t = 0; $dSelectTiemposExtrM = mysql_fetch_assoc($rSelectTiemposExtrM);$t++){

		$faltaM[$t]		=	$dSelectTiemposExtrM['falta_personal'];

		$manttoM[$t]	=	$dSelectTiemposExtrM['mantenimiento'];

		$otrasM[$t]		=	$dSelectTiemposExtrM['otras'];

		$turnosM[$t]	=	$faltaM[$t] + $manttoM[$t] + $otrasM[$t];

		///////TOTALES/////

		$total_otrasM	+=	$otrasM[$t];

		$total_manntoM	+=	$manttoM[$t];		

		$total_faltaM	+=	$faltaM[$t]	;	

		$total_turnosM	+=	$turnosM[$t];



		

			

}



/////////////////////// PRODUCCION  x MAQUINA DIARIA /////////////////////////

$qExtruderM	=	"SELECT SUM(subtotal) as produccion ".

 				" ,numero".

				" FROM entrada_general ".

				" INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

				" INNER JOIN resumen_maquina_ex ON orden_produccion.id_orden_produccion = resumen_maquina_ex.id_orden_produccion".

				" INNER JOIN maquina ON maquina.id_maquina = resumen_maquina_ex.id_maquina".

				" WHERE fecha BETWEEN '".$mes_f."' AND '".$mesFinal."'  ".

				" GROUP BY resumen_maquina_ex.id_maquina ".

				" ORDER BY maquina.numero ASC ";

$rExtruderM	=	mysql_query($qExtruderM);



/////////////////////// PRODUCCION  DIARIA /////////////////////////

$qExtruder	=	"SELECT SUM(total) as produccion, ".

				"  COUNT(turno) AS turnos , fecha, ".

				" SUM(desperdicio_tira) as desperdicio1, ".

				" SUM(desperdicio_duro) as desperdicio2 FROM entrada_general ".

				" INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

				" WHERE fecha BETWEEN '".$mes_f."' AND '".$mesFinal."'  ".

				" GROUP BY fecha ".

				" ORDER BY fecha ASC ";

$rExtruder	=	mysql_query($qExtruder);



/////////////////////// METAS POR DIA ///////////////////////////////

$qMetas	=	"SELECT * FROM meta WHERE mes = '".$meses."'  AND area = '1'";

$rMetas	=	mysql_query($qMetas);

$nMetas	=	mysql_num_rows($rMetas);



 $qMetasM	=	"SELECT total FROM meta ".

			" INNER JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".

			" INNER JOIN maquina ON metas_maquinas.id_maquina = maquina.id_maquina ". 

			" WHERE mes = '".$mes_f."' AND meta.area = '1' ORDER BY numero ASC";

$rMetasM	=	mysql_query($qMetasM);

$nMetasM	=	mysql_num_rows($rMetasM);



for($t=0;$dMetasM = mysql_fetch_assoc($rMetasM);$t++){

	$metaMaquina[$t]	=	$dMetasM['total'];

}

//////////////////////PRODUCCION META Y DIFERENCIA/////////////////////////

if($nMetas > 0){

	$dMetas		=	mysql_fetch_assoc($rMetas);

	$dMetasM	=	mysql_fetch_assoc($rMetasM);

}



for($t = 0; $dExtruder = mysql_fetch_assoc($rExtruder);$t++){

		$fecha[$t]		=	$dExtruder['fecha'];

		$produccion[$t]	=	$dExtruder['produccion'];

		$diferencia[$t]	=	( ($dMetas['prod_hora']*24) - $produccion[$t]);

		

		///////TOTALES/////		

		

		$turnos_total	+=	$dExtruder['turnos'];

		$dias			=	$turnos_total/3;

		

		$total_dif			+=	$diferencia[$t];

		$total_pro			+=	$produccion[$t];

		$total_meta			+= $dMetas['prod_hora']*24;

		$duro				+= $dExtruder['desperdicio2'];

		$tira				+= $dExtruder['desperdicio1'];

}



for($t = 0; $dExtruderM = mysql_fetch_assoc($rExtruderM);$t++){

		$produccionM[$t]	=	$dExtruderM['produccion'];

		$numero[$t]			=	$dExtruderM['numero'];

		$diferenciaM[$t]	=	($metaMaquina[$t] - $produccionM[$t]);

		

		///////TOTALES/////		

		$total_difM			+=	$diferenciaM[$t];

		$total_proM			+=	$produccionM[$t];

		$total_metaM		+= 	$metaMaquina[$t];

}



$total_desp	=	$duro + $tira;

$por		=	($total_desp/$total_proM)*100;	

$Total_produccion	=	$total_pro/$dias;

$fecha_mes = num_mes($mes_f);

?>

<script type="text/javascript" language="javascript">

function genera()

{

document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>";

document.form.submit();

document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";

}

</script>

<div align="center" id="tablaimpr">

    <div  id="tabla_reporte" class="titulos_e" >

    <p class="titulos_reportes" align="center">PRODUCCIÓN DIARIA CONTRA META Y PAROS <?=$mes[$fecha_mes]?> de <?=$date[0]?></p><br >

    <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">

    <table align="center" width="90%" >

    <tr>

        <td width="45%" align="center">

            <p align="center">TODO EXTRUDER</p>

            <table width="80%" align="center" style="margin:15px 15px" >

                <tr>

                    <td width="18%" align="center" valign="middle" ><h3 style="height:25px;">Fecha</h3></td>

                    <td width="30%" align="center"><h3 style="height:25px;">Prod._Diaria</h3></td>

                    <td width="29%" align="center"><h3 style="height:25px;">Meta_Diaria</h3></td>

                    <td width="23%" align="center"><h3 style="height:25px;">Dif.</h3></td>

                </tr>

                <? for($t = 0; $t < sizeof($produccion);$t++){?>

                <tr <?=cebra($t)?>>

                    <td class="style5" align="center"><?=fecha($fecha[$t])?></td>

                    <td class="style5" align="center"><?=number_format($produccion[$t])?></td>

                    <!--<td class="style5" align="center"><?=number_format($dMetas['total_dia']);?></td>-->

                    <td class="style5" align="center"><?=number_format($dMetas['prod_hora']*24);?></td>

                    <td class="style5" align="center"><?=number_format($diferencia[$t])?></td>

                </tr> 

                <? } ?>

                <tr style="background-color:#EEEEEE">

                    <td class="style5" align="center"><h3>SUMAS</h3></td>

                    <td class="style4" align="center"><?=number_format($total_pro)?></td>

                    <td class="style4" align="center"><?=number_format($total_meta)?></td>

                    <td class="style4" align="center"><?=number_format($total_dif)?></td>

                </tr> 

            </table>

        </td>

        <td width="45%" valign="top" align="center">

            <p align="center">PAROS(motivos)</p>

            <table width="80%" align="center"   style="margin:15px 15px">

                <tr>

                    <td width="32%" align="center" valign="middle" ><h3 style="height:25px;">Turnos_parados</h3></td>

                    <td width="25%" align="center"><h3 style="height:25px;">Mantto.</h3></td>

                    <td width="22%" align="center"><h3 style="height:25px;">Oper.</h3></td>

                    <td width="21%" align="center"><h3 style="height:25px;">Otro</h3></td>

                </tr>

                <? for($t = 0; $t < sizeof($produccion);$t++){?>

                <tr <?=cebra($t)?>>

                    <td class="style5" align="center"><? printf("%.1f",(($turnos[$t]/60)/60)/(24/3))?></td>

                    <td class="style5" align="center"><? printf("%.1f",(($mantto[$t]/60)/60)/(24/3))?></td>

                    <td class="style5" align="center"><? printf("%.1f",(($falta[$t]/60)/60)/(24/3))?></td>

                    <td class="style5" align="center"><? printf("%.1f",(($otras[$t]/60)/60)/(24/3))?></td>

                </tr> 

                <? } ?>

                <tr style="background-color:#EEEEEE">

                    <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_turnos/60)/60)/(24/3))?></td>

                    <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_mannto/60)/60)/(24/3))?></td>

                    <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_falta/60)/60)/(24/3))?></td>

                    <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_otras/60)/60)/(24/3))?></td>

                </tr>            

            </table>

        </td>

    </tr> 

    <tr style="page-break-after:always;">

        <td colspan="6">

        <p>&nbsp;</p>

            <table width="90%" align="center" style="margin:30px 15px">        

                <tr>

                    <td width="25%"><h3>Total_produccion: </h3></td>

                    <td width="25%"><?=number_format($Total_produccion*$dias)?></td>

                    <td width="25%"><h3>Porcentaje_Desperdicio: </h3></td>

                    <td width="25%"><? printf("%.2f",$por)?></td>

                </tr>

                <tr>

                    <td><h3>Promedio: </h3></td>

                    <td><?=number_format($Total_produccion)?></td>

                    <td><h3>Dias_trabajados: </h3></td>

                    <td><?=$dias?></td>

                </tr> 

            </table>

        </td>

    </tr>

    

    <tr >

        <td width="45%" valign="top">

            <p align="center">PRODUCCION POR MAQUINA</p>

            <table width="80%" align="center"   style="margin:15px 15px">

                <tr>

                    <td width="18%" valign="middle" ><h3 style="height:25px;">Maquina</h3></td>

                    <td width="30%" ><h3 style="height:25px;">Prod._Total</h3></td>

                    <td width="29%" ><h3 style="height:25px;">Meta_Mensual</h3></td>

                    <td width="23%" ><h3 style="height:25px;">Dif.</h3></td>

                </tr>

                <? for($t = 0; $t < sizeof($produccionM);$t++){?>

                <tr <?=cebra($t)?>>

                    <td class="style5" align="center"><?=$numero[$t]?></td>

                    <td class="style5" align="right"><?=number_format($produccionM[$t])?></td>

                    <td class="style5" align="right"><?=number_format($metaMaquina[$t])?></td>

                    <td class="style5" align="right"><?=number_format($diferenciaM[$t])?></td>

                </tr> 

                <? } ?>

                <tr style="background-color:#EEEEEE">

                    <td class="style5" align="center"><h3>SUMAS</h3></td>

                    <td class="style4" align="right"><?=number_format($total_proM)?></td>

                    <td class="style4" align="right"><?=number_format($total_metaM)?></td>

                    <td class="style4" align="right"><?=number_format($total_difM)?></td>

                </tr>                     

            </table>

        </td>

        <td width="45%" align="center" valign="top" >

            <p align="center">TURNOS PARADOS x MAQUINA</p>

            <table width="80%" align="center" style="margin:15px 15px">

                <tr>

                    <td width="18%" valign="middle" ><h3 style="height:25px;">Turnos_parados</h3></td>

                    <td width="30%" ><h3 style="height:25px;">Mantto.</h3></td>

                    <td width="29%" ><h3 style="height:25px;">Oper</h3></td>

                    <td width="23%" ><h3 style="height:25px;">Otro</h3></td>

                </tr>

                <? for($t = 0; $t < sizeof($produccionM);$t++){?>

                <tr <?=cebra($t)?>>

                    <td class="style5" align="center"><?=redondeado((($turnosM[$t]/60)/60)/(24/3),1)?></td>

                    <td class="style5" align="center"><?=redondeado((($manttoM[$t]/60)/60)/(24/3),1)?></td>

                    <td class="style5" align="center"><?=redondeado((($faltaM[$t]/60)/60)/(24/3),1)?></td>

                    <td class="style5" align="center"><?=redondeado((($otrasM[$t]/60)/60)/(24/3),1)?></td>

                </tr> 

                <? } ?>

                <tr style="background-color:#EEEEEE">

                    <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_turnosM/60)/60)/(24/3))?></td>

                    <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_manntoM/60)/60)/(24/3))?></td>

                    <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_faltaM/60)/60)/(24/3))?></td>

                    <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_otrasM/60)/60)/(24/3))?></td>

                </tr>                    

            </table>

        </td>

    </tr>    

    </table>

    </form>

	</div>

</div>

<? } ?>





<?  if($_REQUEST['tipo'] == 45 ){ ?>

<div align="center" id="tablaimpr"  >

	<div class="tablaCentrada titulos_e" id="tabla_reporte" >

    <p align="center" class="titulos_reportes">REPORTE DE PRODUCCION  POR MAQUINA EN EXTRUDER<br /><?=$mes[$_REQUEST['mes_maq']]?> de <?=$_REQUEST['ano_maq']?></p><br />

	<form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

	<table width="80%" align="center" >

<? 
//Esto lo realiza por cada maquina  y que pasa para que no de nada? 
//echo $_REQUEST['mes_maq']." /".$_REQUEST['ano_maq']."<br>";
 echo "joel ";
 print_r($_REQUEST['maq_id']); 

for($z=0; $z<sizeof($_REQUEST['maq_id']);$z++){
       echo "Hola<br>";

	$qMq	=	" SELECT  SUM(subtotal) AS total, resumen_maquina_ex.id_maquina AS id_maquina, maquina.numero, fecha, maquina.area   FROM resumen_maquina_ex "	.

				" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".

				" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".

				" INNER JOIN maquina 	ON resumen_maquina_ex.id_maquina 		= maquina.id_maquina ".

				" WHERE ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." ) AND  impresion = 0  AND resumen_maquina_ex.id_maquina = ".$_REQUEST['maq_id'][$z]." GROUP BY  DAY(fecha)".

				" ORDER BY fecha ASC";

	
 echo "<br>qMq--- ".$qMq."<br><br>";

    $qMqP	= " SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_ex.id_maquina, rol,  maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_ex "	.

			" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".

			" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".

			" INNER JOIN maquina 			ON resumen_maquina_ex.id_maquina 			= maquina.id_maquina ".

			" WHERE subtotal != 0 AND maquina.id_maquina = ".$_REQUEST['maq_id'][$z]." AND YEAR(fecha) = '".$_REQUEST['ano_maq']."' AND MONTH(fecha) = '".$_REQUEST['mes_maq']."' AND  impresion = 0 GROUP BY  maquina.numero".

			" ORDER BY maquina.numero ASC ";				

				
echo "qMqP--- ".$qMqP."<br><br>";
				

	$qAM	=	"SELECT area, numero FROM maquina WHERE id_maquina	=	".$_REQUEST['maq_id'][$z]."";

	$rAM	=	mysql_query($qAM);

	$dAM	=	mysql_fetch_assoc($rAM);

echo "qAM--- ".$qAM."<br><br>";			

	$qMetas	=	" SELECT * FROM meta".

				" LEFT JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".

				" WHERE id_maquina = ".$_REQUEST['maq_id'][$z]." AND ( MONTH(mes) = ".$_REQUEST['mes_maq']."  AND ano = ".$_REQUEST['ano_maq']." )";





$query	=	" FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general ".

			" LEFT JOIN tiempos_muertos ON orden_produccion.id_orden_produccion = tiempos_muertos.id_produccion ";



 	$meMeta =	$_REQUEST['mes_maq'];

	$anho 	=	$_REQUEST['ano_maq'];

	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');

	$mesMeta	=	$anho.'-'.$mesMetacero.'-01';

	$ultimo_dia = UltimoDia($anho,$meMeta);

	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

 





	$qTiemposMaq	=	"SELECT TIME(SUM(mantenimiento)) 	AS mantenimiento ,".

						" TIME(SUM(falta_personal))		AS falta_personal ,".

						" TIME(SUM(fallo_electrico))		AS fallo_electrico ,".

						" TIME(SUM(otras)) AS otras ,".

						" fecha ,".

						" maquina.marca, maquina.numero ".

						$query.

						" LEFT JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina ".						

						" WHERE maquina.id_maquina = ".$_REQUEST['maq_id'][$z]." AND fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'  ".

						" GROUP BY fecha ".

						" ORDER BY fecha ASC";

    echo "qTiemposMaq--- ".$qTiemposMaq."<br><br>";


	$rTiemposMaq	=	mysql_query($qTiemposMaq);

	for($a=0;$dTiemposMaq	=	mysql_fetch_assoc($rTiemposMaq);$a++){

		$manto[$a]	=	$dTiemposMaq['mantenimiento'];

		$fp[$a]		=	$dTiemposMaq['falta_personal'];

		$fallo[$a]	=	$dTiemposMaq['fallo_electrico']+$dTiemposMaq['otras'];

		$Tfallo[$a]		+=	$fallo[$a]/(24/3);

		$Tfp[$a]		+=	$fp[$a]/(24/3);

		$Tmanto[$a]		+=	$manto[$a]/(24/3);

			

		$TurnosTotal[$a]	=	$Tmanto[$a]	+ $Tfp[$a] + $Tfallo[$a];	

	}







$rMq	=	mysql_query($qMq);

$rMqP	=	mysql_query($qMqP);

$dMqp	=	mysql_fetch_assoc($rMqP);



$rMetas	=	mysql_query($qMetas);

$dMetas	=	mysql_fetch_assoc($rMetas);



$id_maquinas	=	$dMetas['id_maquina'];





?>

	<tr style="page-break-after:always" >

		<td width="45%" align="center" valign="top" >

			<table align="center" width="83%" style="margin:14px;">

            <tr>

				<td align="center" colspan="4" valign="middle" style="height:24px;" ><b>Maquina :  <?

					if($dAM['area'] == 2) echo "Impr. ";

					if($dAM['area'] == 3) echo "L. de Impr. ";

					echo $dAM['numero']?></b>

                 </td>

			</tr>            

            <tr>

				<td width="27%" align="center"><h3>Fecha</h3></td>

				<td width="24%" align="center"><h3>Produccion</h3></td>

				<td width="25%" align="center"><h3>Meta</h3></td>

				<td width="24%" align="center"><h3>Diferencia</h3></td>

			</tr>

			<? 

			$Total_metas 	= 	0;

			$total_prod		=	0;

			$total_dif		=	0;

			$diferencia		=	0;

			$metas_maquinas	=	0;

											

			$a = 0; $metas_maquinas= 0; while($dMq	=	mysql_fetch_assoc($rMq)){ 

													$metas_maquinas	=	$dMetas['diaria'];

													

													$qMet	=	" SELECT fecha, meta_dia, turno_m, turno_v, turno_n FROM paros_maquinas ".

																" WHERE fecha = '".$dMq['fecha']."' AND id_maquina = ".$_REQUEST['maq_id'][$z]."";

													$rMet	=	mysql_query($qMet);

													$dMet	=	mysql_fetch_assoc($rMet);

									

													if($_REQUEST['area_maq'] == 3)

														$metas_maquinas	=	($dMetas['meta_mes_kilo']/$nMqp)/$nNuM;

													

													($dMq['fecha'] == $dMet['fecha'])? $metas_maquinas = $dMet['meta_dia']:$metas_maquinas = $metas_maquinas;

													

													$turnos[$a]	=	$dMet['turno_m']+$dMet['turno_v']+$dMet['turno_n']; 

													$diferencia	=	$metas_maquinas - $dMq['total'];

													?>

			<tr <? cebra($a)?>>

				<td align="center"><? fecha($dMq['fecha'])?></td>

				<td align="right" class="style5"><?=number_format($dMq['total'])?></td>

				<td align="right" class="style5"><?=number_format($metas_maquinas)?></td>

				<td align="right" class="style5"><?=number_format($diferencia)?></td>

			</tr>

				<? 	$a++;

				$Total_metas	+=	$metas_maquinas;

				$total_prod		+=	$dMq['total'];

				$total_dif		+=	$diferencia;} ?>

			<tr>

				<td><h3>Totales: </h3></td>

				<td align="right"><?=number_format($total_prod)?></td>

				<td align="right"><?=number_format($Total_metas)?></td>

				<td align="right"><?=number_format($total_dif)?></td>

			</tr>

			<tr>

				<td><h3>Promedio: </h3></td>

				<td align="right"><? @printf("%.2f" ,$total_prod/$dMqp['paros'])?></td>

				<td align="right"></td>

				<td align="right"></td>

			</tr>                                          

			</table>

		</td>

		<td width="45%" align="center" valign="top" >

			<table align="center"  width="83%" style="margin:15px 15px;">

            <tr>

              <td colspan="4" align="center"><h3>Paros :: Motivos</h3></td>

            </tr>

            <tr>

              <td align="center"><h3>Turnos</h3></td>

              <td align="center"><h3>Mantto </h3></td>

              <td align="center"><h3>Oper. </h3></td>

              <td align="center"><h3>Otros </h3></td>

            </tr>

            <? 

							$tTotal			=	0;

							$tMantoTotal	=	0;

							$tMantoT		=	0;

							$tFallorT		=	0;

							

							for($a=0;$a<sizeof($turnos);$a++){?>

            <tr <? cebra($a)?>>

              <td align="center" class="style4"><? printf("%.2f" ,$TurnosTotal[$a])	?></td>

              <td align="center" class="style5"><? printf("%.2f" ,$Tmanto[$a]) 		?></td>

              <td align="center" class="style5"><? printf("%.2f" ,$Tfp[$a]) 			?></td>

              <td align="center" class="style5"><? printf("%.2f" ,$Tfallo[$a]) 		?></td>

            </tr>

            <? 

												$tTotal	+=	$TurnosTotal[$a];

												$tMantoTotal	+=	$Tmanto[$a];

												$tMantoT	+=	$Tfp[$a];

												$tFallorT	+=	$Tfallo[$a];

												} ?>

            <tr height="22">

              <td align="right" style="height:25px;" >Turnos <?  @printf("%.2f" ,$tTotal)?></td>

              <td align="center" style="height:25px;" ><? @printf("%.2f" ,$tMantoTotal)?></td>

              <td align="center" style="height:25px;" ><? @printf("%.2f" ,$tMantoT)?></td>

              <td align="center" style="height:25px;" ><? @printf("%.2f" ,$tFallorT)?></td>

            </tr>

            <tr height="25">

              <td align="right">Dias

                <?  @printf("%.2f" ,$tTotal/3)?></td>

            </tr>

          </table>

          </td>

		</tr>

    <? } ?>

	</table>

	</form>

	</div>

</div>    

<? } ?> 





<?  if($_REQUEST['tipo'] == 46 ){ 



$fecha	=	fecha_tablaInv($_REQUEST['fecha_des']);

$fechaFin	=	fecha_tablaInv($_REQUEST['fecha_des_f']);



$qReporte	=	"SELECT SUM(desperdicio_tira) AS tira, SUM(desperdicio_duro) AS duro, fecha, COUNT(DISTINCT turno) AS turnos, SUM(total) AS total ".

				" FROM entrada_general ".

				" LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".

				" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."'".

				" GROUP BY fecha ORDER BY fecha ASC";

$rReporte	=	mysql_query($qReporte);

	for($a=0;$dReporte	=	mysql_fetch_assoc($rReporte);$a++){

		$total[$a]	=	$dReporte['total'];

		$tira[$a]	=	$dReporte['tira'];

		$duro[$a]	=	$dReporte['duro'];

		$fecha_re[$a]	=	$dReporte['fecha'];

		$turnos[$a]	=	$dReporte['turnos'];

	}										





?>

<div align="center"  id="tablaimpr">

   	 	<div class="tablaCentrada titulos_e" align="center" id="tabla_reporte" >

        <p class="titulos_reportes" align="center">DESPERDICIOS EXTRUDER DIARIOS<br /><?=$mes[$_REQUEST['mes_maq']]?> de <?=$_REQUEST['ano_maq']?><br /></p><br /><br />

          <form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

		  <table width="80%">

          <tr valign="top">

          	 <td colspan="5" style="padding:10px;"><table align="left" width="96%">

               <tr>

                 <td  colspan="2" align="center"><h3>Desperdicios</h3></td>

               </tr>

               <tr>

                 <td width="81"  align="center"><h3>Fecha</h3></td>

                 <td width="98" align="center"><h3>Desperdicio</h3></td>

                 <td width="65" align="center"><h3>Turnos</h3></td>

                 <td width="78"  align="center"><h3>%</h3></td>

               </tr>

               <? for($a=0;$a < sizeof($tira);$a++){?>

               <tr <? cebra($a)?>>

                 <td align="center"><? fecha($fecha_re[$a])?></td>

                 <td align="right" class="style5"><?=number_format($tira[$a])?></td>

                 <td align="right" class="style5"><?=number_format($turnos[$a])?></td>

                 <td align="right" class="style5"><? printf("%.2f" ,($tira[$a]/$total[$a])*100)?>%</td>

               </tr>

               <? $tiraTotal	+= $tira[$a];

					$turnosTotal	+= $turnos[$a];

					} ?>

               <tr>

                 <td><h3>Totales: </h3></td>

                 <td align="right"><?=number_format($tiraTotal)?></td>

                 <td align="right"><?=number_format($turnosTotal/3)?></td>

               </tr>

             </table></td>

			<td width="27%" align="left" style="padding:10px;">

              <table align="left" width="96%">

<tr>

					<td colspan="4" align="center"><h3>DURO</h3></td>

				</tr>

				<tr>

					<td width="55%" align="center"><h3>KG_DURO</h3></td>

				  <td width="45%" align="center"><h3>% </h3></td>

				</tr>

				<? for($a=0;$a<sizeof($turnos);$a++){?>

				<tr <? cebra($a)?>>

					<td align="center" class="style5"><?=number_format($duro[$a])	?></td>

					<td align="center" class="style5"><? printf("%.2f" ,($duro[$a]/$tira[$a])*100)?>%</td>

				</tr>

                <? $tTotal	+=	$duro[$a];

					$tMantoTotal	+=	$Tmanto[$a];

					} ?>

				<tr height="22">

					<td align="center" style="height:25px;" ><?=@number_format($tTotal)?></td>

				</tr>

                </table>			</td>

            

			<td width="33%" align="left" style="padding:10px;" >

              <table align="left" width="90%">

<tr>

					<td colspan="4" align="center"><h3>TOTALES</h3></td>

				</tr>

				<tr>

					<td width="70%" align="center"><h3> DESPERDICIOS+DURO</h3></td>

				  <td width="30%" align="center"><h3>% </h3></td>

				</tr>

				<? for($a=0;$a<sizeof($turnos);$a++){

				$total_desp	=	$tira[$a]+$duro[$a];?>

				<tr <? cebra($a)?>>

					<td align="center" class="style4"><?=number_format($total_desp)	?></td>

					<td align="center" class="style5"><? printf("%.2f" ,($total_desp/$total[$a])*100)?>%</td>

				</tr>

                <? $tTotal	+=	$duro[$a];

					$tMantoTotal	+=	$Tmanto[$a];

					} ?>

				<tr height="22">

					<td align="center" style="height:25px;" ><?=@number_format($tTotal)?></td>

				</tr>

                </table>			</td>

		</tr>

		</table>                                           

		</td>            

            

		</tr>

		</table>                                           

		</td>

        

        

        

    </tr>   

	</table>

	</form>

	</div>

</div>

<? } ?> 



<tr>

	<td align="center">

    <div id="mensajes"></div>

    <p><input type="button" value="Imprimir" class="link button1" /> <input type="button" value= "Regresar" class="link_ button1 value="Regresar" onClick="javascript: history.go(-1)">
    	<br /><br /><br /></p></td>

</tr>