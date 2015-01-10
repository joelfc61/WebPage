<script language="javascript" type="text/javascript">
	function guardar(){
		var area = document.getElementById("area").value;
		var ano = document.getElementById("ano").value;
		var tbl = document.getElementById("valores_tbl");
		var num_rows = tbl.rows.length;
		var datos = new Array();
		for(var i=2; i<num_rows; i++){
			if(area == 'area' || area == 'area2')
				datos.push(tbl.rows[i].cells[1].firstChild.value+"@"+tbl.rows[i].cells[2].firstChild.value);
			else if(area == 'area3')
				datos.push(tbl.rows[i].cells[1].firstChild.value+"@"+tbl.rows[i].cells[2].firstChild.value+"@"+tbl.rows[i].cells[3].firstChild.value+"@"+tbl.rows[i].cells[4].firstChild.value);
		}
		//alert(datos);
		location.href = "<?=$_SERVER['PHP_SELF']?>?seccion=46&area="+area+"&ano="+ano+"&datos="+encodeURIComponent(datos);
	}
</script>

<form action="<?=$_SERVER['PHP_SELF']?>?seccion=46" method="post">
<?	
	if(isset($_REQUEST['ano']))
		$ano	=	$_REQUEST['ano'];
	else
		$ano 	=	date("Y");
	
	echo "<input type='hidden' id='ano' value='$ano'/>";
	
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
	
	echo "<input type='hidden' id='area' value='$area'/>";
	
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
	
	$mes_array	= 	array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	echo "<span style='color:#6E6E6E; font-weight:bold; font-size:16px; text-align:center;'>CAPTURA DE METAS DE PRODUCCI&Oacute;N</span><br/><br/>";
?>
	<br/>
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
	<input type="submit" value="Ver"/>
<br/><br/><br/>
<table width="300" align="center" cellpadding="2" cellspacing="2" border="0" id="valores_tbl">
<?
	/****************************************************** GUARDAR ******************************************************/
	if(isset($_REQUEST['datos'])){
		
		$datos = split(",",$_REQUEST['datos']);
		for($i=0; $i<count($datos); $i++){
			switch($area){
				case 'area': case 'area2'://EXTRUDER E IMPRESION
					//var_dump($datos[$i]);
					list($x_hora,$porcentaje) = split("@",$datos[$i]);
					$area_num = $area == 'area' ? 1 : 2;
					$qry3 = "UPDATE meta
							 SET prod_hora = '$x_hora',
							 	 porcentaje_desp = '$porcentaje'
							 WHERE ano = '$ano'
							 AND mes = '$ano-".($i+1)."-01'
							 AND area = '$area_num'";
					$rst3 = mysql_query($qry3);
					/*
					$qry1 = "SELECT *
							 FROM meta
							 WHERE ano = '$ano'
							 AND mes = '$ano-".($i+1)."-01'
							 AND area = '$area_num'";
					$rst1 = mysql_query($qry1);
					if(mysql_num_rows($rst1) > 0){
						$qry3 = "UPDATE meta
								 SET prod_hora = '$x_hora',
								 	 porcentaje_desp = '$porcentaje'
								 WHERE ano = '$ano'
								 AND mes = '$ano-".($i+1)."-01'
								 AND area = '$area_num'";
						$rst3 = mysql_query($qry3);
					}
					elseif($x_hora != 0 || $porcentaje !=0){
						$qry3 = "INSERT INTO meta (area, ano, mes, prod_hora, porcentaje_desp)
								 VALUES ('$area_num', '$ano', '$ano-".($i+1)."-01', '$x_hora','$porcentaje')";
						$rst3 = mysql_query($qry3);
					}
					*/
				break;
				case 'area3'://BOLSEO
					list($x_hora,$tira,$troquel,$segunda) = split("@",$datos[$i]);
					$area_num = 3;
					$qry3 = "UPDATE meta
							 SET prod_hora = '$x_hora',
							 	 porcentaje_tira = '$tira',
								 porcentaje_troquel = '$troquel',
								 porcentaje_segunda = '$segunda'
							 WHERE ano = '$ano'
							 AND mes = '$ano-".($i+1)."-01'
							 AND area = '$area_num'";
					$rst3 = mysql_query($qry3);
					/*
					$qry1 = "SELECT *
							 FROM meta
							 WHERE ano = '$ano'
							 AND mes = '$ano-".($i+1)."-01'
							 AND area = '$area_num'";
					$rst1 = mysql_query($qry1);
					if(mysql_num_rows($rst1) > 0){
						$qry3 = "UPDATE meta
								 SET prod_hora = '$x_hora',
								 	 porcentaje_tira = '$tira',
									 porcentaje_troquel = '$troquel',
									 porcentaje_segunda = '$segunda'
								 WHERE ano = '$ano'
								 AND mes = '$ano-".($i+1)."-01'
								 AND area = '$area_num'";
						$rst3 = mysql_query($qry3);
					}
					elseif($x_hora != 0 || $tira !=0 || $troquel !=0 || $segunda !=0){
						$qry3 = "INSERT INTO meta (area, ano, mes, prod_hora, porcentaje_tira, porcentaje_troquel, porcentaje_segunda)
								 VALUES ('$area_num', '$ano', '$ano-".($i+1)."-01', '$x_hora', '$tira', '$troquel', '$segunda')";
						$rst3 = mysql_query($qry3);
					}
					*/
				break;
			}
		}
?>
        <tr>
            <td colspan="3" style="color:#00F; font-weight:bold; font-size:12px; text-align:center;">Los datos han sido guardados</td>
        </tr>
        </table>
        </form>
<?
	}/*************************************************************************************************************/
	else{
?>
        <tr>
            <td colspan="5" style="color:#003; font-weight:bold; font-size:14px; text-align:center;"><?=$area_desc." - A&Ntilde;O ".$ano?></td>
        </tr>
         <tr style="background-color:#006699">
            <td class="style7" style="color:#FFFFFF; text-align:center;">Mes</td>
            <? if($area == 'area' || $area == 'area2'){ ?>
                <td class="style7" style="color:#FFFFFF; text-align:center;">KG/H</td>
                <td class="style7" style="color:#FFFFFF; text-align:center;">Desp. %</td>
            <? }else if($area == 'area3'){ ?>
                <td class="style7" style="color:#FFFFFF; text-align:center;">MI/H</td>
                <td class="style7" style="color:#FFFFFF; text-align:center;">Tira %</td>
                <td class="style7" style="color:#FFFFFF; text-align:center;">Troquel %</td>
                <td class="style7" style="color:#FFFFFF; text-align:center;">Segunda %</td>
            <? } ?>
        </tr>
	<?
		$mes_i = 1;
		foreach($mes_array as $mes){
			switch($area){
				case 'area': case 'area2'://EXTRUDER E IMPRESION
					$area_num = $area == 'area' ? 1 : 2;
					$qry2 = "SELECT prod_hora,
									porcentaje_desp
							 FROM meta
							 WHERE ano = '$ano'
							 AND mes = '$ano-$mes_i-01'
							 AND area = '$area_num'";
					$rst2 = mysql_query($qry2);
					if(!mysql_num_rows($rst2))
						$disabled = "disabled='disabled'";
					$row2 = mysql_fetch_assoc($rst2);
					$x_hora = is_null($row2['prod_hora']) ? 0 : $row2['prod_hora'];
					$perc = is_null($row2['porcentaje_desp']) ? 0 : $row2['porcentaje_desp'];
					?>
					<tr bgcolor='#F2F2F2'>
						<td align="left"><?=$mes?></td>
						<td align="center"><input type="text" <?=$disabled?> size="10" maxlength="10" onfocus="this.select();" style="text-align:right;" value="<?=$x_hora?>"/></td>
						<td align="center"><input type="text" <?=$disabled?> size="10" maxlength="10" onfocus="this.select();" style="text-align:right;" value="<?=$perc?>"/></td>
					</tr>
					<?
					break;
				case 'area3'://BOLSEO
					$area_num = 3;
					$qry2 = "SELECT prod_hora, 
									porcentaje_tira,
									porcentaje_troquel,
									porcentaje_segunda
							 FROM meta
							 WHERE ano = '$ano'
							 AND mes = '$ano-$mes_i-01'
							 AND area = '$area_num'";
					$rst2 = mysql_query($qry2);
					if(!mysql_num_rows($rst2))
						$disabled = "disabled='disabled'";
					$row2 = mysql_fetch_assoc($rst2);
					$x_hora = is_null($row2['prod_hora']) ? 0 : $row2['prod_hora'];
					$perc_tira = is_null($row2['porcentaje_tira']) ? 0 : $row2['porcentaje_tira'];
					$perc_troquel = is_null($row2['porcentaje_troquel']) ? 0 : $row2['porcentaje_troquel'];
					$perc_segunda = is_null($row2['porcentaje_segunda']) ? 0 : $row2['porcentaje_segunda'];
					?>
					<tr bgcolor='#F2F2F2'>
						<td align="left"><?=$mes?></td>
						<td align="center"><input type="text" <?=$disabled?> size="10" maxlength="10" onfocus="this.select();" style="text-align:right;" value="<?=$x_hora?>"/></td>
						<td align="center"><input type="text" <?=$disabled?> size="10" maxlength="10" onfocus="this.select();" style="text-align:right;" value="<?=$perc_tira?>"/></td>
						<td align="center"><input type="text" <?=$disabled?> size="10" maxlength="10" onfocus="this.select();" style="text-align:right;" value="<?=$perc_troquel?>"/></td>
						<td align="center"><input type="text" <?=$disabled?> size="10" maxlength="10" onfocus="this.select();" style="text-align:right;" value="<?=$perc_segunda?>"/></td>
					</tr>
					<?
					break;
			}
		$mes_i++;
		}
	?>
        </table>
        </form>
        <button onclick="guardar();" class="button1">Guardar</button>
<?
	}
?>