<form action="<?=$_SERVER['PHP_SELF']?>?seccion=44" method="post">
<?	
	if(isset($_REQUEST['mes']) && isset($_REQUEST['ano'])){
		$mes	=	$_REQUEST['mes'];
		$ano	=	$_REQUEST['ano'];
	}
	else{
		$mes 	= 	date("m");
		$ano 	=	date("Y");
	}
	
	$mes_array	= 	array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	echo "<span style='color:#6E6E6E; font-weight:bold; font-size:16px; text-align:center;'>PRODUCCI&Oacute;N DE ".strtoupper($mes_array[(int)$mes])." / ".$ano."</span><br/><br/>";
		
?>
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
<br/><br/><br/>
<!-- ******************************************* EXTRUDER ************************************************ -->
<table width="850" align="center" cellpadding="2" cellspacing="2" border="0">
	<tr>
    	<td colspan="10" style="color:#003; font-weight:bold; font-size:14px; text-align:center;">EXTRUDER</td>
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
	//echo $qryTotal;
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
<br /><br />

<!-- ******************************************* IMPRESION ************************************************ -->
<table width="850" align="center" cellpadding="2" cellspacing="2">
	<tr>
    	<td colspan="10" style="color:#003; font-weight:bold; font-size:14px; text-align:center;">IMPRESI&Oacute;N</td>
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
<br/><br/>

<!-- ******************************************* BOLSEO ************************************************ -->
<table width="900" align="center" cellpadding="2" cellspacing="2" border="0">
	<tr>
    	<td colspan="12" style="color:#003; font-weight:bold; font-size:14px; text-align:center;">BOLSEO</td>
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
            <td align="center"><?=@number_format($rowSuper['millares']/$rowH['horas_reales_trabajadas'])?></td>
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
            <td align="center"><?=@number_format($rowTotal['millares']/$rowH['horas_reales_trabajadas'])?></td>
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