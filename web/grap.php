<? 
 if($_REQUEST['tipo'] == 35){ 

	$meMeta 		=	$_REQUEST['mes_da'];
	$anho 			=	$_REQUEST['anho_da'];
	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$mesMeta		=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia 	= 	UltimoDia($anho,$meMeta);
	$mesFinal		=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

 	$qSelectBolseo	=	" SELECT SUM(kilogramos) AS kilogramos, ".
						" SUM(millares) AS millares ,".
						" SUM(dtira)*100/SUM(kilogramos) AS porcentajeTira, ".
						" SUM(dtroquel)*100/SUM(kilogramos)  AS porcentajeTroquel, ".
						" SUM(segundas)*100/SUM(kilogramos)  AS porcentajeSeg , id_supervisor".
						" FROM bolseo ".
						" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'".
						" GROUP BY id_supervisor ".
						" ORDER BY id_supervisor DESC";
	$rSelectBolseo	=	mysql_query($qSelectBolseo);

	$qSup	=	"SELECT * FROM supervisor WHERE area3 = 1 ORDER BY id_supervisor DESC";
	$rSup	=	mysql_query($qSup);
 
?> 
<table  align="center" width="700px">
	<tr>
    	<td colspan="4" align="center" class="style5">Correspondiente al mes de <?=$mes[$meMeta]?> del <?=$anho?></td>
    </tr>
	<tr>
    	<? while($dSup	=	mysql_fetch_assoc($rSup)){ 
		$nombre	=	explode(' ',$dSup['nombre']) ?>
        <td align="center"><h3><?=$nombre[0].' '.$nombre[1]?></h3></td>
        <? } ?>
    </tr>
	<tr>
    <? for($a=0;$dSelectBolseo	=	mysql_fetch_assoc($rSelectBolseo);$a++){
	$tira	=	$dSelectBolseo['tira'];	
	$troquel	=	$dSelectBolseo['troquel'];	
	$porcentajeTira		=	$dSelectBolseo['porcentajeTira'];
	$porcentajeTroquel	=	$dSelectBolseo['porcentajeTroquel'];
	$porcentajeSeg		=	$dSelectBolseo['porcentajeSeg'];
	
		$varia_x	=	array("$porcentajeTira","$porcentajeTroquel","$porcentajeSeg");
 		$varia_y	=	array("","","");
 		$varia_z	=	array("Tira","Troquel","Segunda");
	?>
    	<td valign="bottom">
			<?=grafica_barras($varia_x,$varia_y,'%',1,$varia_z,3);?>
        </td>
     <? } ?>
     </tr>     
</table>
<? } ?>

<? if($_REQUEST['tipo'] == 42 || $_REQUEST['tipo'] == 62 || $_REQUEST['tipo'] == 52 ){ 

	if($_REQUEST['tipo'] == 42){
		$mes1		=	fecha_tablaInv($_REQUEST['fecha_mp']);
		$date		=	explode('-',$mes1);
		$mesFinal	=	fecha_tablaInv($_REQUEST['fecha_mpf']);
		$meses		=	$date[0].'-'.$date[1].'-01';
		$mesMeta 		=	fecha_tablaInv($_REQUEST['fecha_mp']);
		$anho 			=	$date[0];
		$mes_fecha 		=	num_mes($mes1);
		$area 			=	1;
		$titulo	= " PRODUCCIÓN DIARIA CONTRA META Y PAROS EXTRUDER";
		$clase	=	"titulos_e";		
	}
	else if($_REQUEST['tipo'] == 62){

		$mes1		=	fecha_tablaInv($_REQUEST['fecha_mp']);
		$date		=	explode('-',$mes1);
		$mesFinal	=	fecha_tablaInv($_REQUEST['fecha_mpf']);
		$meses		=	$date[0].'-'.$date[1].'-01';
		$mesMeta 		=	fecha_tablaInv($_REQUEST['fecha_mp']);
		$anho 			=	$date[0];	
		$mes_fecha 		=	$date[1];
		$area 			=	2;
		$titulo	= "PRODUCCIÓN DIARIA CONTRA META Y PAROS IMPRESION";
		$clase	=	"titulos_i";		
	}	
	
	else if($_REQUEST['tipo'] == 52){
		$mes1		=	fecha_tablaInv($_REQUEST['fecha_1']);
		$date		=	explode('-',$mes1);
		$mesFinal	=	fecha_tablaInv($_REQUEST['fecha_1f']);
		$meses		=	$date[0].'-'.$date[1].'-01';
		$mesMeta 	=	fecha_tablaInv($_REQUEST['fecha_1']);
		$anho 		=	$date[0];
		$area 		=	3;
		$mes_fecha 		=	$date[1];
		$titulo	= "REPORTE CONTRA META Y TURNOS";
		$clase	=	"titulos_b";		
	}
	
	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
//	$mesMeta		=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia 	= 	UltimoDia($anho,$meMeta);
	//$mesFinal		=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

	$qMetasBolseo	=	"SELECT * FROM meta WHERE mes = '".$meses."' AND area = '".$area."'";
 	$rMetasBolseo	=	mysql_query($qMetasBolseo);
 	$dMetasBolseo	=	mysql_fetch_assoc($rMetasBolseo);


	if($_REQUEST['tipo'] == 52){
 	$qSelectBolseo	=	" SELECT SUM(kilogramos) AS kilogramos, ".
						" SUM(millares) AS millares ".
						" FROM bolseo ".
						" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'".
						" ORDER BY id_supervisor DESC";
	}
	if($_REQUEST['tipo'] == 42){
 	$qSelectBolseo	=	" SELECT SUM(total) AS kilogramos".
						" FROM orden_produccion INNER JOIN entrada_general ON orden_produccion.id_entrada_general = entrada_general.id_entrada_general ".
						" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'".
						" AND impresion = 0".
						" ORDER BY id_supervisor DESC";
	}
	if($_REQUEST['tipo'] == 62){
 	$qSelectBolseo	=	" SELECT SUM(total_hd) AS kilogramos ".
						" FROM impresion INNER JOIN entrada_general ON impresion.id_entrada_general = entrada_general.id_entrada_general ".
						" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'".
						" AND impresion = 1".
						" ORDER BY id_supervisor DESC";
	}	
	
	
	$rSelectBolseo	=	mysql_query($qSelectBolseo);

 
?> 
<div align="center" id="tablaimpr">
	<div class="tablaCentrada <?=$clase?>" >
	<p class="titulos_reportes " align="center"><?=$titulo?><br />
	del <?=fecha($mes1)?> al <?=fecha($mesFinal)?></p><br />
<table border="0" align="center" width="700px">
  	<tr>
    <? 
	$dSelectBolseo	=	mysql_fetch_assoc($rSelectBolseo);
	if($_REQUEST['tipo'] == 62 )
		$kilos			=	$dSelectBolseo['total_hd'];	
	if($_REQUEST['tipo'] == 42 )	
		$kilos			=	$dSelectBolseo['kilogramos'];	
	else
		$kilos			=	$dSelectBolseo['kilogramos'];	
	$millares		=	ceil($dSelectBolseo['millares']);	
	if($_REQUEST['tipo'] == 62 || $_REQUEST['tipo'] == 42)
		$meta			=	$dMetasBolseo['total_hd'];
	if($_REQUEST['tipo'] == 52)
		$meta			=	$dMetasBolseo['meta_mes_kilo'];	
		
	$meta_m			=	$dMetasBolseo['meta_mes_millar'];
	
	$diferencia		=	ceil($kilos - $meta);
	$diferencia2	=	round($millares - $meta_m,-1);
	
		$varia_x	=	array("$kilos", "$meta");
 		$varia_y	=	array("","");
 		$varia_z	=	array("Prod. Kilos ","Meta");
		
		$varia_x2	=	array("$diferencia");
 		$varia_y2	=	array("");
 		$varia_z2	=	array("Diferencia");		
		
		$varia_x3	=	array("$millares", "$meta_m");
 		$varia_y3	=	array("","");
 		$varia_z3	=	array("Prod. Millares ","Meta");
		
		$varia_x4	=	array("$diferencia2");
 		$varia_y4	=	array("");
 		$varia_z4	=	array("Diferencia");		
	 ?>
    	<td width="45%" align="center" valign="top">
			<table width="100%" align="center">
            	<tr>
                	<td colspan="2" align="center"><h3>Kilogramos</H3></td>
               	</tr>
                <tr>
                	<td valign="top" align="right" width="350"><?=grafica_barras($varia_x,$varia_y,'kg',1,$varia_z,$area);?></td>
                    <td valign="top" align="left"  width="350"><?=grafica_barras($varia_x2,$varia_y2,'kg',1,$varia_z2,$area);?></td>
    			</tr>
            </table>
         </td>
        <? if($_REQUEST['tipo'] == 52) {  ?> 
    	<td width="45%" align="center" valign="top">
			<table width="100%" align="center">
            	<tr>
                	<td colspan="2" align="center"><H3>Millares</H3></td>
               	</tr>
                <tr>
                	<td valign="top" align="center"><?=grafica_barras($varia_x3,$varia_y3,'Mi',1,$varia_z3,$area);?></td>
					<td valign="top" align="center"><?=grafica_barras($varia_x4,$varia_y4,'Mi',1,$varia_z4,$area);?></td>
				</tr>
            </table>
        </td>
        <? } ?>        
     </tr>        
</table>
<? } ?>

<? if($_REQUEST['tipo'] == '45' || $_REQUEST['tipo'] == '51'  ){ 

		$meMeta 		=	$_REQUEST['mes_maq'];
		$anho 			=	$_REQUEST['ano_maq'];

	
	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$mesMeta		=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia 	= 	UltimoDia($anho,$meMeta);
	$mesFinal		=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;
	
	if($_REQUEST['area_maq'] == 1)
	{
	$area	=	1;
	$area_1	=	'area = 1';
  	$qSelectBolseo	=	" SELECT SUM(subtotal) AS kilogramos, maquina.numero ".
						" FROM entrada_general ".
						" INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general ".
						" INNER JOIN resumen_maquina_ex ON resumen_maquina_ex.id_orden_produccion = orden_produccion.id_orden_produccion ".
						" INNER JOIN maquina ON resumen_maquina_ex.id_maquina = maquina.id_maquina ".
						" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'".
						" GROUP BY maquina.numero".
						" ORDER BY maquina.numero ASC";		
		$titulo	= "REPORTE DE PRODUCCION POR MAQUINA EXTRUDER";
		$clase	=	"titulos_e";			
	}
	else if($_REQUEST['area_maq'] == 2)
	{
	$area = 2;
	$area_1	=	'area = 2';
   	$qSelectBolseo	=	" SELECT SUM(subtotal) AS kilogramos, maquina.numero , maquina.area".
						" FROM entrada_general ".
						" INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
						" INNER JOIN resumen_maquina_im ON resumen_maquina_im.id_impresion = impresion.id_impresion ".
						" INNER JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".
						" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'".
						" GROUP BY maquina.id_maquina".
						" ORDER BY maquina.area, maquina.numero ASC";
		$titulo	= "REPORTE DE PRODUCCION POR MAQUINA IMPRESION";
		$clase	=	"titulos_i";	
	}
	if($_REQUEST['area_maq'] == 3){
	$area = 3;
	$area_1	=	'area = 3';
  	$qSelectBolseo	=	" SELECT SUM(resumen_maquina_bs.kilogramos) AS kilogramos, maquina.numero, ".
						" SUM(resumen_maquina_bs.millares) AS millares".
						" FROM bolseo ".
						" INNER JOIN resumen_maquina_bs ON resumen_maquina_bs.id_bolseo = bolseo.id_bolseo ".
						" INNER JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina ".
						" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'".
						" GROUP BY maquina.id_maquina".
						" ORDER BY maquina.numero ASC";	
						
	}
	
	
	$qMetas	=	"SELECT * FROM meta WHERE mes = '".$mesMeta."' AND $area_1";
 	$rMetas	=	mysql_query($qMetas);
 	$dMetas	=	mysql_fetch_assoc($rMetas);


						
	$rSelectBolseo	=	mysql_query($qSelectBolseo); 
	
	$kilos			=	$dMetas['kilogramos'];	
	$millares		=	ceil($dSelectBolseo['millares']);	
	$meta			=	$dMetas['meta_mes_kilo'];
	$meta_m			=	$dMetas['meta_mes_millar'];
	$diferencia		=	round($kilos - $meta,-1);
	$diferencia2	=	round($millares - $meta_m,-1);
	
	 	$varia_x	=	array();
 		$varia_y	=	array();
 		$varia_z	=	array();
	

	for($a=0;$dSelectBolseo	=	mysql_fetch_assoc($rSelectBolseo);$a++){
		
		if($_REQUEST['area_maq'] == 3){
	 		$varia_x2[$a]	=	ceil($dSelectBolseo['millares']); 
		}
	 	$varia_x[$a]	=	$dSelectBolseo['kilogramos']; 
 		$varia_y[$a]	=	$dSelectBolseo['numero'];
		
		
		
		if($_REQUEST['area_maq'] == 2){
			if($dSelectBolseo['area'] == '2')
				$nombre	=	'Flexo ';
			else
				$nombre	=	'Linea ';
		}
		
		$varia_z[$a]	=	$nombre.$dSelectBolseo['numero'];
		
		}	
		
		 sizeof($varia_x);	
	 ?>
<div align="center" id="tablaimpr">
	<div class="tablaCentrada <?=$clase?>" >
	<p class="titulos_reportes " align="center"><?=$titulo?><br />
	de <?=$mes[$meMeta]?> de <?=$anho?></p><br />
<table width="700px" align="center">
	<tr>
		<td valign="top" align="center">
		<h3>KILOGRAMOS</h3><br />
		<?=grafica_barras($varia_x,$varia_y,'kg',1,$varia_z,$area);?></td>
	</tr>
	<? if($_REQUEST['area_maq'] == 3){ ?>
	<tr>
		<td valign="top" align="center">
		<h3>MILLARES</h3><br />
		<?=grafica_barras($varia_x2,$varia_y,'mi',1,$varia_z,$area);?></td>
	</tr>                
	<? } ?>
</table>

<? } ?>

<? if($_REQUEST['tipo'] == '39' || $_REQUEST['tipo'] == '46' || $_REQUEST['tipo'] == '38' ){ 
	if( $_REQUEST['tipo'] == '39' ) { 
		$meMeta	=	$_REQUEST['mes_extr'];
		$anho	=	$_REQUEST['ano_extr'];
		$area 	= 	'area = 1';
		$area1	=	1;
		$titulo	= " PRODUCCION EXTRUDER POR GRUPO Y TURNO";
		$clase	=	"titulos_e";
	}
	else if($_REQUEST['tipo'] == '46'){
		$meMeta	=	$_REQUEST['mes_impr'];
		$anho	=	$_REQUEST['ano_impr'];
		$area 	= 	'area2 = 1';
		$area1	=	2;
		$titulo	= "PRODUCCION IMPRESION POR GRUPO Y TURNO";
		$clase	=	"titulos_i";
	}
	else if($_REQUEST['tipo'] == '38'){
		$meMeta	=	$_REQUEST['mes_bols'];
		$anho	=	$_REQUEST['ano_bols'];
		$area 	= 	'area3 = 1';
		$area1	=	3;
		$titulo	= " PRODUCCION-META DE BOLSEO POR GRUPO Y TURNO";
		$clase	=	"titulos_b";		
	}
		
	
	
	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$meses	=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia = UltimoDia($anho,$meMeta);
	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

////////////////////////////TURNOS POR ROL////////////////////////
if( $_REQUEST['tipo'] == '39' ) 
$qRol	=	" SELECT supervisor.nombre, rol, COUNT(entrada_general.turno) AS turno, SUM(total) AS total, SUM(desperdicio_tira+desperdicio_duro) AS tira FROM entrada_general ".
			" LEFT JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor ".
			" LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".
			" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."' ".
			" AND impresion = 0 ".
			" GROUP BY entrada_general.rol ORDER BY entrada_general.turno, entrada_general.rol ASC ";
				
if( $_REQUEST['tipo'] == '46' ) 
$qRol	=	"SELECT supervisor.nombre, rol, COUNT(entrada_general.turno) AS turno, SUM(total_hd) AS total, SUM(desperdicio_hd) AS tira FROM entrada_general ".
			" LEFT JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor ".
			" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
			" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."' ".
			" AND impresion = 1 ".
			" GROUP BY entrada_general.id_supervisor ORDER BY entrada_general.turno, entrada_general.rol ASC ";
				
if( $_REQUEST['tipo'] == '38' ) 
$qRol	=	"SELECT rol, COUNT(turno) AS turno, SUM(kilogramos) AS total, SUM(dtira+dtroquel+(segundas+(($ultimo_dia*3)*round($vts)))) AS tira, ".
				" SUM(millares) AS millares FROM bolseo ".
			" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."' ".
			" GROUP BY rol ORDER BY turno, rol ASC ";
				
$rRol	=	mysql_query($qRol);

$qGrupo	=	"SELECT nombre FROM supervisor ".
			" WHERE $area ORDER BY rol ASC";
					
$rGrupo	=	mysql_query($qGrupo);	

for($t = 0 ; $dGrupo	=	mysql_fetch_assoc($rGrupo); $t++){
//	$nombres	=	explode(" ",$dGrupo['nombre']);
//	$varia_z[$t]	=	$nombres[0];
}	

for($t = 0 ; $dRol	=	mysql_fetch_assoc($rRol); $t++){
	$nombres	=	explode(" ",$dRol['nombre']);
	$varia_z[$t]	=	$nombres[0];

	if($_REQUEST['tipo'] == 38){
		$varia_x6[$t]	= ceil($dRol['millares']);
		if($real_milla) { 
		$kg_reales	= $valor_ppm *  ceil($dRol['millares']);
		} else { 
		$kg_reales = $dRol['total']; 
		}
	$varia_x[$t]	=	ceil($kg_reales);
	} else {	
	$varia_x[$t]	=	ceil($dRol['total']);
	}
	$varia_x2[$t] 	= 	$dRol['tira'];
	$varia_x3[$t] 	= 	($varia_x2[$t]/$varia_x[$t])*100;
	$varia_x4[$t] 	= 	$dRol['turno'];
	$varia_x5[$t]	=	ceil($varia_x[$t]/$varia_x4[$t]);
	
}	

$varia_z2 =	array("","","","");
$varia_z3 =	array("","","","");
$varia_z4 =	array("","","","");
$varia_z5 =	array("","","","");
?> 
<div align="center" id="tablaimpr">
	<div class="tablaCentrada <?=$clase?>" >
	<p class="titulos_reportes " align="center"><?=$titulo?><br />
	de <?=$mes[$meMeta]?> del <?=$anho?></p><br />
<table border="0" align="center" >
  	<tr>
    <?		$varia_y	=	array("","","","");	 ?>
    	<td width="700px"  align="center">
			<table width="700px" valign="bottom" align="center">
                <tr>
                	<td valign="top" align="center">
                    <h3>PRODUCCION Kg</h3><br />
					<?=grafica_barras($varia_x,$varia_y,'kg',1,$varia_z,$area1);?></td>
                    <? if($_REQUEST['tipo'] == 38) {?>
                	<td valign="top" align="center">
                    <h3>PRODUCCION Mi</h3><br />
					<?=grafica_barras($varia_x6,$varia_y,'mi',1,$varia_z2,$area1);?></td>
                    <? } ?>
                	<td valign="top" align="center">
					<h3>DESPERDICIOS</h3><br />
					<?=grafica_barras($varia_x2,$varia_y,'kg',1,$varia_z2,$area1);?></td>
                	<td valign="top" align="center">
                    <h3>%</h3><br />
					<?=grafica_barras($varia_x3,$varia_y,'%',1,$varia_z3,$area1);?></td>
                	<td valign="top" align="center">
                    <h3>TURNOS</h3><br />
					<?=grafica_barras($varia_x4,$varia_y,'Turnos',1,$varia_z4,$area1);?></td>
                	<td valign="top" align="center">
                    <h3>PROMEDIO</h3><br />
					<?=grafica_barras($varia_x5,$varia_y,'kg',1,$varia_z5,$area1);?></td>
    			</tr>
            </table>
         </td>
     </tr>        
</table>
</div></div>
<? } ?>

<tr>
	<td align="center"><br /><br />
    <div id="mensajes"></div>
    <p><input type="button" value="Imprimir" class="link button1" /><br /><br /><br /></p></td>
</tr>