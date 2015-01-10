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
			<p class="titulos_reportes" align="center" style="text-transform:uppercase">PROMEDIOS DE <?=$area_desc?> - <?=$mes_array[(int)$mes]." / ".$ano?><br/><br/></p>
            <form name="promedios" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" id="super" method="post">
            	&Aacute;rea: <select name="area">
            	<?
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
						echo "<td colspan='3' $color_celda>$nom_array[0]</td>";
					else if($area =='area3')//BOLSEO
						echo "<td colspan='4' $color_celda>$nom_array[0]</td>";
					$k++;
				}
			?>
        </tr>
        <!-- ****************************************** PROMEDIOS ****************************************** -->
  		<tr>
            <td colspan="1" align="center" style="color:#006699; width:200px;">PROMEDIO:</td>
				<?
                	mysql_data_seek($rstS,0);
					$k=0;
                    while($rowS = mysql_fetch_assoc($rstS)){
						switch($area){
							case 'area'://EXTRUDER
								$qry5 = "SELECT AVG(p.total) as produccion, 
												AVG(p.k_h) as prod_hora, 
												AVG(p.desperdicio_tira+p.desperdicio_duro) as desperdicio
										 FROM entrada_general e, orden_produccion p, supervisor s
										 WHERE e.id_entrada_general = p.id_entrada_general
										 AND e.id_supervisor = s.id_supervisor
										 AND fecha BETWEEN '$desde' AND '$hasta'
										 AND s.id_supervisor = '$rowS[id_supervisor]'
										 AND e.impresion = '0'
										 AND repesada = '1'";
								break;
							case 'area2'://IMPRESION
								$qry5 = "SELECT AVG(IF(i.total_hd=0,i.total_bd,i.total_hd)) as produccion,
												AVG(i.k_h) as prod_hora,
												AVG(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)) as desperdicio
										 FROM entrada_general e, impresion i, supervisor s
										 WHERE e.id_entrada_general = i.id_entrada_general
										 AND e.id_supervisor = s.id_supervisor
										 AND fecha BETWEEN '$desde' AND '$hasta'
										 AND s.id_supervisor = '$rowS[id_supervisor]'
										 AND e.impresion = '1'
										 AND repesada = '1'";
								break;
							case 'area3'://BOLSEO
								$qry5 = "SELECT AVG(b.millares) as produccion,
												AVG(IF(b.turno=1,b.millares/8,IF(b.turno=2,b.millares/7,b.millares/9))) as prod_hora,
												AVG(b.segundas) as desp_segundas,
												AVG(b.dtira) as desp_tira
										 FROM bolseo b, supervisor s
										 WHERE b.id_supervisor = s.id_supervisor
										 AND b.fecha BETWEEN '$desde' AND '$hasta'
										 AND s.id_supervisor = '$rowS[id_supervisor]'
										 AND repesada = '1'";
								break;
						}
						//echo $qry2;
						$rst5 = mysql_query($qry5);
						$row5 = mysql_fetch_assoc($rst5);
						if($k%2==0)
							$color_celda = "style='color:#006699; background-color:#F6E3CE;'";
						else
							$color_celda = "style='color:#006699; background-color:#E6E6E6;'";
				?>
                        <td align="right" <?=$color_celda?>><?=number_format($row5['produccion'],1)?></td>
                        <td align="right" <?=$color_celda?>><?=number_format($row5['prod_hora'],1)?></td>
                        
               <?  
			   			if($area == 'area' || $area == 'area2')//EXTRUDER E IMPRESION
							echo "<td align='right' $color_celda>".number_format($row5['desperdicio'],1)."</td>";
			   			else if($area == 'area3'){//BOLSEO
							echo "<td align='right' $color_celda>".number_format($row5['desp_segundas'],1)."</td>";
							echo "<td align='right' $color_celda>".number_format($row5['desp_tira'],1)."</td>";
						}
						$k++;
			   		}
				?>
  		</tr>
   		<tr>
            <td align="center"><h3>Fecha</h3></td>
        	<?
				mysql_data_seek($rstS,0);
				while($rowS = mysql_fetch_assoc($rstS)){
					if($area == 'area' || $area == 'area2'){//EXTRUDER E IMPRESION
						echo "<td align='center'><h3>PROD._KG</h3></td>";
						echo "<td align='center'><h3>KG/HR.</h3></td>";
						echo "<td align='center'><h3>Desp.</h3></td>";
					}
					else if($area == 'area3'){//BOLSEO
						echo "<td align='center'><h3>PROD._MI</h3></td>";
						echo "<td align='center'><h3>MI/H</h3></td>";
						echo "<td align='center'><h3>2das.</h3></td>";
						echo "<td align='center'><h3>Tira</h3></td>";
					}
				}
			?> 
  		</tr>
        	<?
				/*
				$qry = "SELECT e.fecha
						FROM entrada_general e, orden_produccion p, supervisor s
						WHERE fecha BETWEEN '2011-03-01' AND '2011-03-31'
						AND e.id_entrada_general = p.id_entrada_general
						AND e.impresion = '0'
						AND e.id_supervisor = s.id_supervisor
						GROUP BY e.fecha
						ORDER BY e.fecha";
				$rst = mysql_query($qry);
				while($row = mysql_fetch_assoc($rst)){
				*/
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
										$qry1 = "SELECT p.total as produccion, 
														p.k_h as prod_hora,
														p.desperdicio_tira+p.desperdicio_duro as desperdicio
												 FROM entrada_general e, orden_produccion p, supervisor s
												 WHERE e.id_supervisor = s.id_supervisor
												 AND e.id_entrada_general = p.id_entrada_general
												 AND fecha = '$ano-$mes-$dia'
												 AND s.id_supervisor = '$rowS[id_supervisor]'
												 AND e.impresion = '0'
												 AND repesada = '1'
												 ORDER BY e.fecha";
										break;
									case 'area2':
										$qry1 = "SELECT IF(i.total_hd=0,i.total_bd,i.total_hd) as produccion,
														i.k_h as prod_hora,
														IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd) as desperdicio
												 FROM entrada_general e, impresion i, supervisor s
												 WHERE e.id_entrada_general = i.id_entrada_general
												 AND e.id_supervisor = s.id_supervisor
												 AND fecha = '$ano-$mes-$dia'
												 AND s.id_supervisor = '$rowS[id_supervisor]'
												 AND e.impresion = '1'
												 AND repesada = '1'
												 ORDER BY e.fecha";
										break;
									case 'area3':
										$qry1 = "SELECT b.millares as produccion, 
														IF(b.turno=1,b.millares/8,IF(b.turno=2,b.millares/7,b.millares/9)) as prod_hora,
														b.segundas as desp_segundas,
														b.dtira as desp_tira
												 FROM bolseo b, supervisor s
												 WHERE b.id_supervisor = s.id_supervisor
												 AND b.fecha = '$ano-$mes-$dia'
												 AND s.id_supervisor = '$rowS[id_supervisor]'
												 AND repesada = '1'
												 ORDER BY b.fecha";
										break;
								}
								//echo $qry1;
								$rst1 = mysql_query($qry1);
								$row1 = mysql_fetch_assoc($rst1);
								if($k%2==0)
									$color_celda = "style='background-color:#F6E3CE'";
								else
									$color_celda = "style='background-color:#E6E6E6'";
						?>
								<td align="right" class="style5" <?=$color_celda?>><?=is_null($row1['produccion'])?"-":number_format($row1['produccion'],1)?></td>
								<td align="right" class="style5" <?=$color_celda?>><?=is_null($row1['prod_hora'])?"-":number_format($row1['prod_hora'],1)?></td>
                        <?	
								if($area == 'area' || $area == 'area2')//EXTRUDER E IMPRESION
									echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desperdicio'])?"-":number_format($row1['desperdicio'],1))."</td>";
								if($area == 'area3'){//BOLSEO
									echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desp_segundas'])?"-":number_format($row1['desp_segundas'],1))."</td>";
									echo "<td align='right' class='style5' $color_celda>".(is_null($row1['desp_tira'])?"-":number_format($row1['desp_tira'],1))."</td>";
								}
								$k++;
							}  
						?>
                    </tr>
            <? } ?>
        <!-- ************** TOTALES ************** -->
  		<tr>
            <td colspan="1" align="center" class="style5"><h3>TOTALES:</h3></td>
				<?
                	mysql_data_seek($rstS,0);
					$k=0;
                    while($rowS = mysql_fetch_assoc($rstS)){
						switch($area){
							case 'area'://EXTRUDER
								$qry2 = "SELECT SUM(p.total) as produccion, 
												AVG(p.k_h) as prod_hora, 
												SUM(p.desperdicio_tira+p.desperdicio_duro) as desperdicio
										 FROM entrada_general e, orden_produccion p, supervisor s
										 WHERE e.id_entrada_general = p.id_entrada_general
										 AND e.id_supervisor = s.id_supervisor
										 AND fecha BETWEEN '$desde' AND '$hasta'
										 AND s.id_supervisor = '$rowS[id_supervisor]'
										 AND e.impresion = '0'
										 AND repesada = '1'";
								break;
							case 'area2'://IMPRESION
								$qry2 = "SELECT SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)) as produccion,
												AVG(i.k_h) as prod_hora,
												SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)) as desperdicio
										 FROM entrada_general e, impresion i, supervisor s
										 WHERE e.id_entrada_general = i.id_entrada_general
										 AND e.id_supervisor = s.id_supervisor
										 AND fecha BETWEEN '$desde' AND '$hasta'
										 AND s.id_supervisor = '$rowS[id_supervisor]'
										 AND e.impresion = '1'
										 AND repesada = '1'";
								break;
							case 'area3'://BOLSEO
								$qry2 = "SELECT SUM(b.millares) as produccion,
												AVG(IF(b.turno=1,b.millares/8,IF(b.turno=2,b.millares/7,b.millares/9))) as prod_hora,
												SUM(b.segundas) as desp_segundas,
												SUM(b.dtira) as desp_tira
										 FROM bolseo b, supervisor s
										 WHERE b.id_supervisor = s.id_supervisor
										 AND b.fecha BETWEEN '$desde' AND '$hasta'
										 AND s.id_supervisor = '$rowS[id_supervisor]'
										 AND repesada = '1'";
								break;
						}
						//echo $qry2;
						$rst2 = mysql_query($qry2);
						$row2 = mysql_fetch_assoc($rst2);
						if($k%2==0)
							$color_celda = "style='font-weight:bold; background-color:#F6E3CE'";
						else
							$color_celda = "style='font-weight:bold; background-color:#E6E6E6'";
				?>
                        <td align="right" class="style5" <?=$color_celda?>><?=number_format($row2['produccion'],1)?></td>
                        <td align="right" class="style5" <?=$color_celda?>><?=number_format($row2['prod_hora'],1)?></td>
               <?  
			   			if($area == 'area' || $area == 'area2')//EXTRUDER E IMPRESION
							echo "<td align='right' class='style5' $color_celda>".number_format($row2['desperdicio'],1)."</td>";
			   			if($area == 'area3'){//BOLSEO
							echo "<td align='right' class='style5' $color_celda>".number_format($row2['desp_segundas'],1)."</td>";
							echo "<td align='right' class='style5' $color_celda>".number_format($row2['desp_tira'],1)."</td>";
						}
						$k++;
			   		}
				?>
  		</tr>
       	<?
		/* SE COLOCAN LAS FILAS DESPUÉS DE LOS TOTALES QUE SON DIFERENTES DEPENDIENDO DEL ÁREA*/
		switch($area){
			case 'area'://EXTRUDER
				echo "<tr>";
					echo "<td style='border:none;'>&nbsp;</td>";
					mysql_data_seek($rstS,0);
					while($rowS = mysql_fetch_assoc($rstS)){
						$qry3 = "SELECT ROUND(((SUM(p.desperdicio_tira)+SUM(p.desperdicio_duro))/SUM(p.total))*100,2) as porcentaje
								 FROM entrada_general e, orden_produccion p, supervisor s
								 WHERE e.id_entrada_general = p.id_entrada_general
								 AND e.id_supervisor = s.id_supervisor
								 AND e.fecha BETWEEN '$desde' AND '$hasta'
								 AND s.id_supervisor = '$rowS[id_supervisor]'
								 AND e.impresion = '0'
								 AND repesada = '1'";
						$rst3 = mysql_query($qry3);
						$row3 = mysql_fetch_assoc($rst3);
						echo "<td colspan='3' style='text-align:right; border:none; color:red;'>$row3[porcentaje]%</td>";
					}
				echo "</tr>";
				break;
			case 'area2'://IMPRESION
				echo "<tr>";
					echo "<td style='border:none;'>&nbsp;</td>";
					mysql_data_seek($rstS,0);
					while($rowS = mysql_fetch_assoc($rstS)){
						$qry3 = "SELECT ROUND(((SUM(i.desperdicio_hd)+SUM(i.desperdicio_bd))/SUM(i.total_hd)+SUM(i.total_bd))*100,2) as porcentaje						 FROM entrada_general e, impresion i, supervisor s
								 WHERE e.id_entrada_general = i.id_entrada_general
								 AND e.id_supervisor = s.id_supervisor
								 AND e.fecha BETWEEN '$desde' AND '$hasta'
								 AND s.id_supervisor = '$rowS[id_supervisor]'
								 AND e.impresion = '1'
								 AND repesada = '1'";
						$rst3 = mysql_query($qry3);
						$row3 = mysql_fetch_assoc($rst3);
						echo "<td colspan='3' style='text-align:right; border:none; color:red;'>$row3[porcentaje]%</td>";
					}
				echo "</tr>";
				break;
			case 'area3'://BOLSEO
				echo "<tr>";
					echo "<td style='color:#2E2E2E; text-align:center;'>PROMEDIO CON PAROS:</td>";
					mysql_data_seek($rstS,0);
					while($rowS = mysql_fetch_assoc($rstS)){
						$qryH = "SELECT FLOOR((SUM(IF(b.turno=1,8,IF(b.turno=2,7,9)))-((SUM(TIME_TO_SEC(mantenimiento))+SUM(TIME_TO_SEC(otras)))/3600))/COUNT(DISTINCT m.id_maquina)) as horas_reales_trabajadas
							 FROM bolseo b
							 LEFT JOIN tiempos_muertos t ON b.id_bolseo = t.id_produccion
							 LEFT JOIN maquina m ON t.id_maquina = m.id_maquina
							 WHERE tipo = '4'
							 AND b.fecha BETWEEN '$desde' AND '$hasta'
							 AND b.id_supervisor = '$rowS[id_supervisor]'
							 AND repesada = '1'";
						$rstH = mysql_query($qryH);
						$rowH = mysql_fetch_assoc($rstH);
						$qryP = "SELECT SUM(b.millares) as produccion
								 FROM bolseo b, supervisor s
								 WHERE b.id_supervisor = s.id_supervisor
								 AND b.fecha BETWEEN '$desde' AND '$hasta'
								 AND s.id_supervisor = '$rowS[id_supervisor]'
								 AND repesada = '1'";
						$rstP = mysql_query($qryP);
						$rowP = mysql_fetch_assoc($rstP);
						echo "<td colspan='4' style='text-align:center; color:blue;'>".@(number_format($rowP['produccion']/$rowH['horas_reales_trabajadas'],1))." MI/HR</td>";
					}
				echo "</tr>";
				echo "<tr>";
					echo "<td style='border:none;'>&nbsp;</td>";
					mysql_data_seek($rstS,0);
					while($rowS = mysql_fetch_assoc($rstS)){
						$qry4 = "SELECT #SUM(b.millares)*5.29 as kilos,
										#ROUND(SUM(b.segundas)/(SUM(b.millares)*5.29)*100,2) as porcentaje
										SUM(b.kilogramos) as kilos,
										ROUND(SUM(b.segundas)/SUM(b.kilogramos)*100,2) as porcentaje_segundas,
										ROUND(SUM(b.dtira)/SUM(b.kilogramos)*100,2) as porcentaje_tira
								 FROM bolseo b, supervisor s
								 WHERE b.id_supervisor = s.id_supervisor
								 AND b.fecha BETWEEN '$desde' AND '$hasta'
								 AND s.id_supervisor = '$rowS[id_supervisor]'
								 AND repesada = '1'";
						$rst4 = mysql_query($qry4);
						$row4 = mysql_fetch_assoc($rst4);
						//echo $qry4;
						echo "<td style='color:#2E2E2E; text-align:center; border:none;'>".number_format($row4['kilos'],1)."</td>";
						echo "<td style='border:none;'>&nbsp;</td>";
						echo "<td style='text-align:center; color:red; border-style:none solid none none;'>$row4[porcentaje_segundas]%</td>";
						echo "<td style='text-align:center; color:red; border-style:none none none solid;'>$row4[porcentaje_tira]%</td>";
					}
				echo "</tr>";
				echo "<tr>";
					echo "<td style='border:none;'>&nbsp;</td>";
					mysql_data_seek($rstS,0);
					while($rowS = mysql_fetch_assoc($rstS)){
						echo "<td style='color:#2E2E2E; text-align:center; border:none;'>KGS.</td>";
						echo "<td style='border:none;'>&nbsp;</td>";
						echo "<td style='border:none;'>&nbsp;</td>";
						echo "<td style='border:none;'>&nbsp;</td>";
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