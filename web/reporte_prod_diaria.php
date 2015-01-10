<? 

	//var_dump($_SESSION);

	

	if(isset($_REQUEST['mes']) && isset($_REQUEST['ano'])){

		$mes	=	$_REQUEST['mes'];

		$ano	=	$_REQUEST['ano'];

		

		$ultimo_dia = UltimoDia($ano,$mes);

		$desde	= 	$_REQUEST['ano']."-".$mes."-01";

		$hasta	= 	$_REQUEST['ano']."-".$mes."-".$ultimo_dia;

	}

	else{

		$mes	=	date("m");

		$ano	=	date("Y");

		$ultimo_dia = UltimoDia($ano,$mes);

		$desde	= 	date("Y-m-01");

		$hasta	= 	date("Y-m-").$ultimo_dia;

	}

	

	$mes_array	= 	array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

	$mes_array2	= 	array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');

	

	$areas = array("EXTRUDER","IMPRESION","BOLSEO");

?>



<table width="90%" id="tablaimpr"  align="center" border="0">

	<tr>

		<td align="center" >

        <table width="100%" class="titulos_e"  id="tabla_reporte" align="center" border="0">

			<p class="titulos_reportes" align="center" style="text-transform:uppercase">PRODUCCI&Oacute;N DIARIA - <?=$mes_array[(int)$mes]." / ".$ano?><br/><br/></p>

            <form name="metas" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" id="super" method="post">

            	Mes: <select name="mes">

            	<?

					for($j=1; $j<count($mes_array); $j++){

						if($j==$mes)

							echo "<option value='".(strlen($j)<2?"0".$j:$j)."' selected='selected'>{$mes_array[$j]}</option>";

						else

							echo "<option value='".(strlen($j)<2?"0".$j:$j)."'>{$mes_array[$j]}</option>";

					}

				?>

                	</select>

                &nbsp;&nbsp;

            	A&ntilde;o: <select name="ano">

            	<?

					for($a=2010; $a<2015; $a++){

						if($a==$ano)

							echo "<option value='$a' selected='selected'>$a</option>";

						else

							echo "<option value='$a'>$a</option>";

					}

				?>

                	</select>

                &nbsp;&nbsp;

                <input type="submit" value="Enviar"/><PRE>     </PRE><input type = "button" value = "SIGUIENTE">

            </form>

            <br/><br/>

        <tr>

        	<td style="border:none;">&nbsp;</td>

        	<?

				foreach($areas as $area){

					if($area =='EXTRUDER'){//EXTRUDER E IMPRESION

						$color_celda = "style='text-align:center; color:#2E2E2E; font-size:12px; background-color:#E6E6E6'";
                        $color_celdabd = "style='text-align:center; color:#0066FF; font-size:12px; background-color:#66FFFF'";
						echo "<td colspan='2' class='style7' $color_celda>$area HD</td><td  colspan='2' class='style7' $color_celdabd>$area BD</td>";

					}

					else if($area =='IMPRESION'){

						$color_celda = "style='text-align:center; color:#2E2E2E; font-size:12px; background-color:#F6E3CE'";

						echo "<td colspan='2' class='style7' $color_celda>$area</td>";

					}

					else if($area =='BOLSEO'){//BOLSEO

						$color_celda = "style='text-align:center; color:#2E2E2E; font-size:12px; background-color:#E0ECF8'";

						echo "<td colspan='5' class='style7' $color_celda>$area</td>";

					}

				}

			?>

        </tr>

   		<tr>

        	<? $head_style = "style='text-align:center; background-color:#006699;'"; ?>

            <td><h3 <?=$head_style?>>Fecha</h3></td>

        	<?

				foreach($areas as $area){

					if($area =='EXTRUDER' ){//EXTRUDER 

						echo "<td class='style7'><h3 $head_style>KGS.</h3></td>";
						echo "<td class='style7'><h3 $head_style>DESP.</h3></td>";
                        echo "<td class='style7'><h3 $head_style>KGS.</h3></td>";
						echo "<td class='style7'><h3 $head_style>DESP.</h3></td>";

					}
					if($area =='IMPRESION'){//IMPRESION

						echo "<td class='style7'><h3 $head_style>KGS.</h3></td>";

						echo "<td class='style7'><h3 $head_style>DESP.</h3></td>";

					}

					else if($area == 'BOLSEO'){//BOLSEO

						echo "<td class='style7'><h3 $head_style>Millares</h3></td>";

						echo "<td class='style7'><h3 $head_style>KGS.</h3></td>";

						echo "<td class='style7'><h3 $head_style>2DAS</h3></td>";

						echo "<td class='style7'><h3 $head_style>TIRA</h3></td>";

						echo "<td class='style7'><h3 $head_style>TROQUEL</h3></td>";

					}

				}

			?> 

  		</tr>

        	<?

				for($i=1; $i<=$ultimo_dia; $i++){

					$dia = strlen($i)<2 ? "0".$i : $i;

			?>

            		<tr>

                    	<td colspan="1" align="center" class="style5"><?=$dia."-".$mes_array2[(int)$mes]."-".$ano?></td>

						<?

                           foreach($areas as $area){

								switch($area){

									case 'EXTRUDER':

										$color_celda = "style='background-color:#E6E6E6'";

										$qry1 = "SELECT SUM(p.total) as produccion, 
														SUM(p.desperdicio_tira+p.desperdicio_duro) as desperdicio,
														sum(p.total_bd) as produccionbd,
														SUM(p.desperdicio_tira_bd+p.desperdicio_duro_bd) as desperdiciobd

												 FROM entrada_general e, orden_produccion p

												 WHERE e.id_entrada_general = p.id_entrada_general

												 AND fecha = '$ano-$mes-$dia'

												 AND e.impresion = '0'

												 AND repesada = '1'";

										$rst1 = mysql_query($qry1);

										$row1 = mysql_fetch_assoc($rst1);

										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['produccion'])?"-":number_format($row1['produccion'],1))."</td>";
										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desperdicio'])?"-":number_format($row1['desperdicio'],1))."</td>";
                                        echo "<td align='right' class='style5' $color_celda>".(is_null($row1['produccionbd'])?"-":number_format($row1['produccionbd'],1))."</td>";
										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desperdiciobd'])?"-":number_format($row1['desperdiciobd'],1))."</td>";
										

										break;

									case 'IMPRESION':

										$color_celda = "style='background-color:#F6E3CE'";

										$qry1 = "SELECT SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)) as produccion,

														SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)) as desperdicio

												 FROM entrada_general e, impresion i

												 WHERE e.id_entrada_general = i.id_entrada_general

												 AND fecha = '$ano-$mes-$dia'

												 AND e.impresion = '1'

												 AND repesada = '1'";

										$rst1 = mysql_query($qry1);

										$row1 = mysql_fetch_assoc($rst1);

										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['produccion'])?"-":number_format($row1['produccion'],1))."</td>";

										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desperdicio'])?"-":number_format($row1['desperdicio'],1))."</td>";

										break;

									case 'BOLSEO':

										$color_celda = "style='background-color:#E0ECF8'";

										$qry1 = "SELECT SUM(b.millares) as prod_millares,

														SUM(b.kilogramos) as prod_kilos,

														SUM(b.segundas) as desp_segundas,

														SUM(b.dtira) as desp_tira,

														SUM(b.dtroquel) as desp_troquel

												 FROM bolseo b

												 WHERE b.fecha = '$ano-$mes-$dia'

												 AND repesada = '1'";

										$rst1 = mysql_query($qry1);

										$row1 = mysql_fetch_assoc($rst1);

										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['prod_millares'])?"-":number_format($row1['prod_millares'],1))."</td>";

										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['prod_kilos'])?"-":number_format($row1['prod_kilos'],1))."</td>";

										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desp_segundas'])?"-":number_format($row1['desp_segundas'],1))."</td>";

										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desp_tira'])?"-":number_format($row1['desp_tira'],1))."</td>";

										echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desp_troquel'])?"-":number_format($row1['desp_troquel'],1))."</td>";

										break;

								}

							}  

						?>

                    </tr>

            <? } ?>

        <!-- ************** TOTALES ************** -->

  		<tr>

            <td colspan="1" align="center"><h3 <?=$head_style?>>TOTALES:</h3></td>

				<?

                    foreach($areas as $area){

						switch($area){

							case 'EXTRUDER'://EXTRUDER

								$color_celda = "style='font-weight:bold; background-color:#E6E6E6'";

								$qry2 = "SELECT SUM(p.total) as produccion, 

												SUM(p.desperdicio_tira+p.desperdicio_duro) as desperdicio,
												SUM(p.total_bd) as produccionbd, 

												SUM(p.desperdicio_tira_bd+p.desperdicio_duro_bd) as desperdiciobd

										 FROM entrada_general e, orden_produccion p

										 WHERE e.id_entrada_general = p.id_entrada_general

										 AND fecha BETWEEN '$desde' AND '$hasta'

										 AND e.impresion = '0'

										 AND repesada = '1'";

								$rst2 = mysql_query($qry2);

								$row2 = mysql_fetch_assoc($rst2);

								echo "<td align='right' class='style5' $color_celda>".number_format($row2['produccion'],1)."</td>";
                                echo "<td align='right' class='style5' $color_celda>".number_format($row2['desperdicio'],1)."</td>";
								echo "<td align='right' class='style5' $color_celda>".number_format($row2['produccionbd'],1)."</td>";
                                echo "<td align='right' class='style5' $color_celda>".number_format($row2['desperdiciobd'],1)."</td>";
								

								break;

							case 'IMPRESION'://IMPRESION

								$color_celda = "style='font-weight:bold; background-color:#F6E3CE'";

								$qry2 = "SELECT SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)) as produccion,

												SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)) as desperdicio

										 FROM entrada_general e, impresion i

										 WHERE e.id_entrada_general = i.id_entrada_general

										 AND fecha BETWEEN '$desde' AND '$hasta'

										 AND e.impresion = '1'

										 AND repesada = '1'";

								$rst2 = mysql_query($qry2);

								$row2 = mysql_fetch_assoc($rst2);

								echo "<td align='right' class='style5' $color_celda>".number_format($row2['produccion'],1)."</td>";

								echo "<td align='right' class='style5' $color_celda>".number_format($row2['desperdicio'],1)."</td>";

								break;

							case 'BOLSEO'://BOLSEO

								$color_celda = "style='font-weight:bold; background-color:#E0ECF8'";

								$qry2 = "SELECT SUM(b.millares) as prod_millares,

												SUM(b.kilogramos) as prod_kilos,

												SUM(b.segundas) as desp_segundas,

												SUM(b.dtira) as desp_tira,

												SUM(b.dtroquel) as desp_troquel

										 FROM bolseo b

										 WHERE b.fecha BETWEEN '$desde' AND '$hasta'

										 AND repesada = '1'";

								$rst2 = mysql_query($qry2);

								$row2 = mysql_fetch_assoc($rst2);

								echo "<td align='right' class='style5' $color_celda>".number_format($row2['prod_millares'],1)."</td>";

								echo "<td align='right' class='style5' $color_celda>".number_format($row2['prod_kilos'],1)."</td>";

								echo "<td align='right' class='style5' $color_celda>".number_format($row2['desp_segundas'],1)."</td>";

								echo "<td align='right' class='style5' $color_celda>".number_format($row2['desp_tira'],1)."</td>";

								echo "<td align='right' class='style5' $color_celda>".number_format($row2['desp_troquel'],1)."</td>";

								break;

						}

			   		}

				?>

  		</tr>

	</table>

 </td>

 </tr>

</table>

<br/>

<input type="button" value="Formato PDF" onclick="window.open('reporte_prod_diaria_pdf.php?&anho=<?=$ano?>&mes=<?=$mes?>&desde=<?=$desde?>&hasta=<?=$hasta?>')"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Imprimir" class="link button1"/>