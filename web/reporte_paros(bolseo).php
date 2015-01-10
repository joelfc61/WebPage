<? 

	//var_dump($_SESSION);

	

	if(isset($_REQUEST['mes']) && isset($_REQUEST['ano'])){

		$mes	=	$_REQUEST['mes'];

		$ano	=	$_REQUEST['ano'];

		//$mes_c	=	num_mes_cero($_REQUEST['anho']."-".$mes_d."-01");

		

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

	

	if(isset($_REQUEST['area']))

		$area = $_REQUEST['area'];

	else if($_SESSION['area']=='1')//EXTRUDER

		$area = "area";

	else if($_SESSION['area2']=='1')//IMPRESION

		$area = "area2";

	else if($_SESSION['area3']=='1')//BOLSEO

		$area = "area3";

	else

		$area = "area";

		

	switch($area){

		case 'area':

			$area_desc = 'EXTRUDER';

			break;

		case 'area2':

			$area_desc = 'IMPRESION';

			break;

		case 'area3':

			$area_desc = 'BOLSEO';

			break;

	}

	

	$mes_array	= 	array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

	$mes_array2	= 	array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');

	

	//echo $area;

?>



<table width="90%" id="tablaimpr"  align="center">

	<tr>

		<td align="center" >

        <table width="100%" class="titulos_e"  id="tabla_reporte" align="center" border="0">

			<p class="titulos_reportes" align="center" style="text-transform:uppercase">PAROS MENSUALES POR SUPERVISOR - <?=$mes_array[(int)$mes]." / ".$ano?><br/><br/></p>

            <form name="promedios" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" id="super" method="post">

            	&Aacute;rea: <select name="area">

                <option value='area3'>BOLSEO</option>
                <option value='area1'>EXTRUDER</option>
                <option value='area2'>IMPRESION</option>
                <option value='area4'>RPSySF</option>
            	<?

                /*

					switch($area){

						case 'area':

							echo "<option value='area' selected='selected'>EXTRUDER</option>";

							echo "<option value='area2'>IMPRESION</option>";

							echo "<option value='area3'>BOLSEO</option>";

							break;

						case 'area2':

							echo "<option value='area'>EXTRUDER</option>";

							echo "<option value='area2' selected='selected'>IMPRESION</option>";

							echo "<option value='area3'>BOLSEO</option>";

							break;

						case 'area3':

							echo "<option value='area'>EXTRUDER</option>";

							echo "<option value='area2'>IMPRESION</option>";

							echo "<option value='area3' selected='selected'>BOLSEO</option>";

							break;

					}

				*/

				?>

                	</select>

                &nbsp;&nbsp;

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

                <input type="submit" value="Enviar"/>

            </form>

            <br/><br/>

        <tr>

        	<td style="border:none;">&nbsp;</td>

        	<?

				$qryS = "SELECT nombre, id_supervisor

						 FROM supervisor

						 WHERE activo = '0'

						 AND $area = '1'

						 ORDER BY nombre";

				$rstS = mysql_query($qryS);

				$k=0;

				while($rowS = mysql_fetch_assoc($rstS)){

					if($k%2==0)

						$color_celda = "style='text-align:center; background-color:#F6E3CE; color:#2E2E2E; font-size:12px;'";

					else

						$color_celda = "style='text-align:center; background-color:#E6E6E6; color:#2E2E2E; font-size:12px;'";

					$nom_array = explode(" ",$rowS['nombre']);

					if($area =='area' || $area =='area2')//EXTRUDER E IMPRESION

						echo "<td colspan='2' $color_celda>$nom_array[0]</td>";

					else if($area =='area3')//BOLSEO

						echo "<td colspan='2' $color_celda>$nom_array[0]</td>";

					$k++;

				}

			?>

        </tr>

   		<tr>

        	<? $head_style = "style='text-align:center; background-color:#006699;'"; ?>

            <td><h3 <?=$head_style?>>Fecha</h3></td>

        	<?

				mysql_data_seek($rstS,0);

				while($rowS = mysql_fetch_assoc($rstS)){

					if($area == 'area' || $area == 'area2'){//EXTRUDER E IMPRESION

						echo "<td><h3 $head_style>MTTO.</h3></td>";

						echo "<td><h3 $head_style>OTRO</h3></td>";

					}

					else if($area == 'area3'){//BOLSEO

						echo "<td><h3 $head_style>MTTO.</h3></td>";

						echo "<td><h3 $head_style>OTRO</h3></td>";

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

                            mysql_data_seek($rstS,0);

							$k=0;

                            while($rowS = mysql_fetch_assoc($rstS)){

								switch($area){

									case 'area':

										break;

									case 'area2':

										break;

									case 'area3':

										$qryH = "SELECT SUM(TIME_TO_SEC(mantenimiento))/3600 as mantenimiento,

														SUM(TIME_TO_SEC(otras))/3600 as otras

												 FROM bolseo b

												 LEFT JOIN tiempos_muertos t ON b.id_bolseo = t.id_produccion

												 WHERE t.tipo = '4'

												 AND b.fecha = '$ano-$mes-$dia'

												 AND b.id_supervisor = '$rowS[id_supervisor]'

												 AND b.repesada = '1'";

										//$qryH = "SELECT 7 as mantenimiento, 10 as otras";

										break;

								}

								//echo $qryH;

								$rstH = mysql_query($qryH);

								$rowH = mysql_fetch_assoc($rstH);

								if($k%2==0)

									$color_celda = "style='background-color:#F6E3CE'";

								else

									$color_celda = "style='background-color:#E6E6E6'";

						?>

								<td align="right" class="style5" <?=$color_celda?>><?=is_null($rowH['mantenimiento'])?"&nbsp;":number_format($rowH['mantenimiento'],1)?></td>

								<td align="right" class="style5" <?=$color_celda?>><?=is_null($rowH['otras'])?"&nbsp;":number_format($rowH['otras'],1)?></td>

                        <?	

								$k++;

							}  

						?>

                    </tr>

            <? } ?>

        <!-- ************** TOTALES ************** -->

  		<tr>

            <td colspan="1"class="style5"><h3 <?=$head_style?>>TOTALES:</h3></td>

				<?

                	mysql_data_seek($rstS,0);

					$k=0;

                    while($rowS = mysql_fetch_assoc($rstS)){

						switch($area){

							case 'area'://EXTRUDER

								break;

							case 'area2'://IMPRESION

								break;

							case 'area3'://BOLSEO

								$qryT = "SELECT SUM(TIME_TO_SEC(mantenimiento))/3600 as mantenimiento,

												SUM(TIME_TO_SEC(otras))/3600 as otras

										 FROM bolseo b

										 LEFT JOIN tiempos_muertos t ON b.id_bolseo = t.id_produccion

										 WHERE t.tipo = '4'

										 AND b.fecha BETWEEN '$desde' AND '$hasta'

										 AND b.id_supervisor = '$rowS[id_supervisor]'

										 AND b.repesada = '1'";

								$rstT = mysql_query($qryT);

								break;

						}

						echo $qryt;

						$rstT = mysql_query($qryT);

						$rowT = mysql_fetch_assoc($rstT);

						if($k%2==0)

							$color_celda = "style='font-weight:bold; background-color:#F6E3CE'";

						else

							$color_celda = "style='font-weight:bold; background-color:#E6E6E6'";

				?>

                        <td align="right" class="style5" <?=$color_celda?>><?=number_format($rowT['mantenimiento'],1)?></td>

                        <td align="right" class="style5" <?=$color_celda?>><?=number_format($rowT['otras'],1)?></td>

               <?  

						$k++;

			   		}

				?>

  		</tr>

       	<?

		/* SE COLOCAN LAS FILAS DESPUÉS DE LOS TOTALES QUE SON DIFERENTES DEPENDIENDO DEL ÁREA*/

		switch($area){

			case 'area'://EXTRUDER

				break;

			case 'area2'://IMPRESION

				break;

			case 'area3'://BOLSEO

				echo "<tr>";

					echo "<td style='color:#2E2E2E; text-align:center;'>TOTAL GRAL.</td>";

					mysql_data_seek($rstS,0);

					while($rowS = mysql_fetch_assoc($rstS)){

						$qryT = "SELECT (SUM(TIME_TO_SEC(mantenimiento))+SUM(TIME_TO_SEC(otras)))/3600 as total

										FROM bolseo b

										LEFT JOIN tiempos_muertos t ON b.id_bolseo = t.id_produccion

										WHERE t.tipo = '4'

										AND b.fecha BETWEEN '$desde' AND '$hasta'

										AND b.id_supervisor = '$rowS[id_supervisor]'

										AND b.repesada = '1'";

						$rstT = mysql_query($qryT);

						$rowT = mysql_fetch_assoc($rstT);

						echo "<td colspan='2' style='text-align:center; color:blue;'>".number_format($rowT['total'],1)."</td>";

					}

				echo "</tr>";

				break;

		}

		?>

	</table>

 </td>

 </tr>

</table>

<br/>

<input type="button" value="Imprimir" class="link button1"/>