<body>
<?
if($_REQUEST['tipo'] == 46){ 
	$meMeta	=	$_REQUEST['mes_impr'];
	$anho	=	$_REQUEST['ano_impr'];
	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$meses	=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia = UltimoDia($anho,$meMeta);
	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;
///////////////////////////PROMEDIO META////////////////////////// 
$qMeta	=	"SELECT * FROM meta WHERE mes = '".$meses."' AND area = 2";
$rMeta	=	mysql_query($qMeta);
$nMeta	=	mysql_num_rows($rMeta);

if($nMeta > 0){
	$dMeta	=	mysql_fetch_assoc($rMeta);
	//$metaHora	=	$dMeta['total_dia']/24;
	$metaHora	=	$dMeta['prod_hora'];
	$mMat		=	$metaHora * 8;
	$mVes		=	$metaHora * 7;
	$mNoc		=	$metaHora * 9;
}
////////////////////////////TURNOS POR ROL////////////////////////
$qRol	=	"SELECT rol, COUNT(entrada_general.turno) AS turno, SUM(total_hd) AS total, SUM(desperdicio_hd) AS tira ".
			" FROM entrada_general ".
			" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
			" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."' ".
			" AND impresion = 1 AND repesada = 1".
			" GROUP BY entrada_general.turno, entrada_general.rol ORDER BY entrada_general.id_supervisor, entrada_general.turno, entrada_general.rol ASC ";
$rRol	=	mysql_query($qRol);

$qCambios	=	" SELECT SUM(cambio_impresion) AS cambios".
				" FROM entrada_general ".
				" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
				" LEFT JOIN tiempos_muertos ON tiempos_muertos.id_produccion = impresion.id_impresion".
				" LEFT JOIN maquina ON maquina.id_maquina = tiempos_muertos.id_maquina".
				" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."' ".
				" AND impresion = 1 AND tipo IN(2,3)".
				" GROUP BY entrada_general.rol ORDER BY entrada_general.rol ASC ";
$rCambios	=	mysql_query($qCambios);

for($a=0;$dCambios	=	mysql_fetch_assoc($rCambios); $a++){
	$cambios_turno[$a]	=	$dCambios['cambios'];
}


$nRol	=	mysql_num_rows($rRol);
if($nRol > 0){
	$qGrupo	=	"SELECT nombre FROM supervisor ".
				" WHERE area2 = 1  ORDER BY rol ASC";	
	$rGrupo	=	mysql_query($qGrupo);
		for($t = 0 ; $dGrupo	=	mysql_fetch_assoc($rGrupo); $t++)
		{
			$nombres	= 	explode(" ",$dGrupo['nombre']);
			$grupo[$t] = $nombres[0].' '.$nombres[1];
			$grupob[$t]	=	$nombres[0].' '.$nombres[1]{0};
		}
		
}


if($nRol == 0) 	$error = "NO HAY REGISTROS ";
if($nMeta == 0 && $nRol == 0) $error = " Y ";
if($nMeta == 0) $error = "NO HAY UNA META ";?>

<script type="text/javascript" language="javascript">
function genera()
{
document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&fecha_hist_f=<?=$_REQUEST['fecha_hist_f']?>&fecha_hist=<?=$_REQUEST['fecha_hist']?>&sup_h=<?=$_REQUEST['sup_h']?>&oper_h=<?=$_REQUEST['oper_h']?>&turnos_h=<?=$_REQUEST['turnos_h']?>";
document.form.submit();
document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";
}
</script>

<div align="center" id="tablaimpr">
   	 	<div class="tablaCentrada titulos_i" align="center" id="tabla_reporte" >
        <br /><br /><br /><p class="titulos_reportes" align="center">PRODUCCION IMPRESION POR GRUPO Y POR TURNO <br>DEL MES DE <?=strtoupper($mes[$meMeta])?> DE <?=$anho?></p><br /><br /><br />
        <form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&tipo=<?=$_REQUEST['tipo']?>&mes_impr=<?=$_REQUEST['mes_impr']?>&ano_impr=<?=$_REQUEST['ano_impr']?>&modificar" method="post">
      	<table width="800px" >
   	    <tr>
           	<td width="10%" align="center"><h3>Turno</h3></td>
           	<td width="10%" align="center"><h3>1</h3></td>
           	<td width="10%" align="center"><h3>2</h3></td>
           	<td width="9%"  align="center"><h3>3</h3></td>
           	<td width="13%" align="center"><h3>TOTAL</h3></td>
          	<td width="48%" colspan="10" rowspan="2"></td>
        </tr>        
		<tr align="center">
           	  <td><h3>META</h3></td>
           	  <td class="style5"><?=number_format($mMat)?></td>
           	  <td class="style5" bgcolor="#DDDDDD"><?=number_format($mVes)?></td>
           	  <td class="style5"><?=number_format($mNoc)?></td>
           	  <!--<td class="style5" bgcolor="#DDDDDD"><?=number_format($dMeta['total_dia'])?></td>-->
              <td class="style5" bgcolor="#DDDDDD"><?=number_format($dMeta['prod_hora']*24)?></td>
          </tr>
        </table>
      <br>
      <br>        
        <table width="800px" >
       	  <tr>
            <td width="21%" align="center"><h3>Nombre</h3></td>
       	    <td width="6%" align="center"><h3>T_1</h3></td>
       	    <td width="7%" align="center"><h3>Meta</h3></td>
   	        <td width="5%" align="center"><h3>T_2</h3></td>
       	    <td width="8%" align="center"><h3>Meta</h3></td>
       	    <td width="5%" align="center"><h3>T_3</h3></td>
       	    <td width="8%" align="center"><h3>Meta</h3></td>
       	    <td width="12%" align="center"><h3>META_TOTAL</h3></td>
       	    <td width="10%" align="center"><h3>PROD</h3></td>
       	    <td width="9%" align="center"><h3>DIF</h3></td>
       	    <td width="9%" align="center"><h3>Turnos</h3></td>
          </tr>
          <? 
		  	for($a = 0 ;$dRol	=	mysql_fetch_assoc($rRol);$a++){
				
				$turnos[$a]			=	array($dRol['turno']);
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
		   for( $b = 0 ; $b < 4 ; $b++){?>
          <tr <? if(bcmod($b,2) == 0) echo 'bgcolor="#DDDDDD"';?>>
          	<td align="left"><?=$grupo[$b]?></td>
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
            <td class="style5" align="center"> <?=$turnos[$meta][0]?></td>

            <td class="style5" align="center"><?=number_format($turno1 = $turnos[$meta][0] * $multi);
			$t_total[$b]	+=	$turnos[$meta][0];
			$total += $turno1;				
			$total_desp	+= $tira[$meta][0];
			}?></td>            
   			<td class="meta_totales" 	align="center"><?=number_format($total)?></td>
            <td class="prod_totales" 	align="center"><?=number_format($Total_pr[$b])?></td>
            <td class="dif_totales" 	align="center"><?=number_format($diferencia = $total - $Total_pr[$b])?></td>
            <td class="dif_totales" 	align="center"><?=$t_total[$b];?></td>
		  </tr>
          <? 
		  $total_m += $total; 
		  $total_des[$b] 	= array($total_desp);
		  $totalmetas[$b] 	= array($total);
		  $totalprod[$b] 	= array($Total_pr[$b]);
		  $total_p += $Total_pr[$b]; 
		  $total_d += $diferencia; 
		  } ?>                 
		<tr bgcolor="#DDDDDD">
            <td colspan="7" align="right"><h3>TOTALES: </h3></td>
            <td class="meta_totales" align="center"><?=number_format($total_m) ?></td>
       	  <td class="prod_totales" align="center"><?=number_format($total_p) ?></td>
       	  <td class="dif_totales" align="center"><?=number_format($total_d) ?></td>
	    <? for($a=0;$a<4;$a++){ $total_tt	+=	$t_total[$a];} ?>
            <td class="dif_totales" align="center"><? printf("%.2f",$total_tt/3); ?></td>            
          </tr>                     
        </table>
        <br>
        <br>
		<table width="800px">
    		<tr>
                  <td width="104" ></td>
                  <td width="41" align="center"><h3>67%</h3></td>
              <td width="64"  align="center"><h3>30%</h3></td>
              <td width="47"  align="center"><h3>3%</h3></td>
              <td width="71"  align="center"><h3>0%</h3></td>
              <td width="89"  align="center"><h3>100%</h3></td>
              <td colspan="3" align="center" class="titulos_de">KILOS</td>
                  <td colspan="3" align="center" class="titulos_de">DESPERDICIO</td>     
                </tr>
                <tr>
                  <td><h3>Nombre</h3></td>
                  <td align="center"><h3>Meta</h3></td>
                  <td align="center"><h3>Cambios</h3></td>
                  <td align="center"><h3>Desp.</h3></td>
                  <td align="center"><h3>Limp_Act.</h3></td>
				  <td align="center"><h3>Total</h3></td>
				  <td width="45" align="center" class="titulos_de">META</td>				  
				  <td width="54" align="center" class="titulos_de">REAL</td>
				  <td width="27" align="center" class="titulos_de">%</td>
				  <td width="51" align="center" class="titulos_de">METAS</td>
				  <td width="49" align="center" class="titulos_de">REAL</td>
				  <td width="36" align="center" class="titulos_de">%</td>
                  <td width="66" align="center" class="titulos_de">CAMBIOS</td>                             
   		  </tr>                
                <? 
				for($a = 0;$a < 4; $a++){ 
						$totales_cambio	+=	$cambios_turno[$a];
				}
					for($a = 0;$a < 4; $a++){ 
						
						@$meta_cambio[$a] 	= 	(($cambios_turno[$a]/$totales_cambio)*100)*.30;
						@$porcen_meta[$a] 	= 	((current($totalprod[$a])/current($totalmetas[$a]))*100)*.67;
						@$eaea22	=	((current($totalprod[$a])/current($totalmetas[$a]))*100);
						@$jeje[$a] = ($tiras[$a]  / ($Total_pr[$a]+$t_total[$a])  ) *100 ;
						@$por_desp_t[$a]	=	($dMeta['porcentaje_desp']/$jeje[$a])*100;
						@$tdp[$a]	=	$por_desp_t[$a]*.04;
				
				?>
                <input value="<?=round($tdp[$a]+$meta_cambio[$a]+$porcen_meta[$a],2)?>" type="hidden" id="pre_<?=$a?>" />
                <tr <? if(bcmod($a,2) == 0) echo 'bgcolor="#DDDDDD"';?>>
                   	<td><?=$grupob[$a]?></td>
                  	<td align="center" class="style5"><? printf( "%.2f", $porcen_meta[$a]);?>%</td>
                   	<td align="center"><? printf("%.2f", $meta_cambio[$a]) ?>%</td>
               	  <td align="center" class="style5"><? printf( "%.2f", $tdp[$a])?>%</td>
                  	<td  class="style5" align="center"><input type="text" class="style5" style=" text-align:right" id="porcentaje_<?=$a?>" name="porcentaje[]" align="right" size="4" value="<?=$_REQUEST['porcentaje'][$a]?>" onChange="suma_limp(<?=$a?>); document.form.submit();" />%</td>
                 	<td  class="style5" align="center"><input readonly="readonly" class="style5" type="text" style=" text-align:right; <? if(bcmod($a,2) == 0) echo "background-color:#dddddd" ?>; border: 0px;" size="4" value="<? (isset($_REQUEST['modificar']))? printf( "%.2f", $_REQUEST['resultado_'][$a]): printf( "%.2f", $tdp[$a]+$porcen_meta[$a])?>"  id="resultado_<?=$a?>" name="resultado_[]" />%</td>
					<td align="center" class="style5"><?=number_format(current($totalmetas[$a]))?></td>
				  <td align="center" class="style5"><?=number_format(current($totalprod[$a]))?></td>
				  <td align="center" class="style5"><? printf( "%.2f", $eaea22);?>%</td>
					<td align="center" class="style5"><? printf( "%.2f", $dMeta['porcentaje_desp'])?>%</td>
					<td align="center" class="style5"><? printf( "%.2f",$jeje[$a] )?>%</td>
					<td align="center" class="style5"><? printf( "%.2f", $por_desp_t[$a])?>%</td>
					<td align="center" class="style5"><? printf("%.2f",$cambios_turno[$a])?></td>
                </tr>
                <? } ?> 
                <tr>
                	<td colspan="12" align="right"><h3>Total de cambios</h3></td>
                	<td align="center" class="style5"><? printf("%.2f",$totales_cambio);?></td>
                </tr>       
          </table>  
         </form>
	</div>
</div>
<? } ?>

<? if($_REQUEST['tipo'] == 47){ 

	$meMeta	=	$_REQUEST['mes_imp'];
	$anho	=	$_REQUEST['ano_imp'];

	$mesMetacero=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$mesf		=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia = UltimoDia($anho,$meMeta);
	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;
///////////////////////////PROMEDIO META////////////////////////// 

$qMeta	=	"SELECT * FROM meta WHERE mes = '".$mesf."' AND area = 2";
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
$qRol1	=	"SELECT  DAY(entrada_general.fecha) AS fecha , entrada_general.turno AS turno, total_hd AS kilogramos, entrada_general.rol AS rol FROM entrada_general ".
			" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
			" WHERE (entrada_general.fecha BETWEEN '".$mesf."' AND '".$mesFinal."') ".
			" AND impresion = 1 AND entrada_general.rol = 1 AND entrada_general.repesada = 1 ".
			" GROUP BY entrada_general.fecha, entrada_general.turno, entrada_general.rol ".
			" ORDER BY entrada_general.fecha, entrada_general.rol,entrada_general.turno ASC ";
$rRol1	=	mysql_query($qRol1);
$nRol1	=	mysql_num_rows($rRol1);


$qRol2	=	"SELECT  DAY(entrada_general.fecha) AS fecha , entrada_general.turno AS turno, total_hd AS kilogramos, entrada_general.rol AS rol FROM entrada_general ".
			" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
			" WHERE (entrada_general.fecha BETWEEN '".$mesf."' AND '".$mesFinal."')".
			" AND impresion = 1 AND entrada_general.rol = 2 AND entrada_general.repesada = 1 ".
			" GROUP BY entrada_general.fecha, entrada_general.turno, entrada_general.rol ".
			" ORDER BY entrada_general.fecha, entrada_general.rol,entrada_general.turno ASC ";
$rRol2	=	mysql_query($qRol2);
$nRol2	=	mysql_num_rows($rRol2);

$qRol3	=	"SELECT  DAY(entrada_general.fecha) AS fecha, entrada_general.turno AS turno, SUM(total_hd) AS kilogramos, entrada_general.rol AS rol FROM entrada_general ".
			" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
			" WHERE (entrada_general.fecha BETWEEN '".$mesf."' AND '".$mesFinal."')".
			" AND impresion = 1 AND entrada_general.rol = 3 AND entrada_general.repesada = 1".
			" GROUP BY entrada_general.fecha, entrada_general.turno, entrada_general.rol ".
			" ORDER BY entrada_general.fecha, entrada_general.rol,entrada_general.turno ASC ";
$rRol3	=	mysql_query($qRol3);
$nRol3	=	mysql_num_rows($rRol3);

$qRol4	=	"SELECT  DAY(entrada_general.fecha) AS fecha, entrada_general.turno AS turno, SUM(total_hd) AS kilogramos, entrada_general.rol AS rol FROM entrada_general ".
			" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
			" WHERE (entrada_general.fecha BETWEEN '".$mesf."' AND '".$mesFinal."') ".
			" AND impresion = 1 AND entrada_general.rol = 4 AND entrada_general.repesada = 1".
			" GROUP BY entrada_general.fecha, entrada_general.turno, entrada_general.rol ".
			" ORDER BY entrada_general.fecha, entrada_general.rol,entrada_general.turno ASC ";
$rRol4	=	mysql_query($qRol4);
$nRol4	=	mysql_num_rows($rRol4);


if($nRol1 > 0 && $nRol2 > 0 && $nRol3 > 0  && $nRol4 > 0 ){
$qTurnos	=	"SELECT  DAY(entrada_general.fecha) AS fecha, SUM(total_hd) AS kilogramos, entrada_general.turno AS turno FROM entrada_general ".
			" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
			" WHERE (entrada_general.fecha BETWEEN '".$mesf."' AND '".$mesFinal."') ".
			" AND impresion = 1 AND entrada_general.repesada = 1".
			" GROUP BY entrada_general.fecha, entrada_general.turno ".
			" ORDER BY entrada_general.fecha, entrada_general.turno ASC ";
$rTurnos	=	mysql_query($qTurnos);
$nTurnos	=	mysql_num_rows($rTurnos);

///////////// DIAS LABORADOS ////////////////////////////////////
$qFecha	=	"SELECT DAY(fecha) AS dia , fecha FROM entrada_general ".
			" WHERE (entrada_general.fecha BETWEEN '".$mesf."' AND '".$mesFinal."') ".
			" AND impresion = 1 AND repesada = 1 ".
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
	<div class=" tablaCentrada titulos_i" id="tabla_reporte">
    <p align="center" class="titulos_reportes">IMPRESI&Oacute;N KGS. HORAS POR GRUPO Y POR TURNO <br />DE <?=$mes[$meMeta]?> DEL <?=$anho?></p><br /><br />
     <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">
<? if($nRol1 > 0 && $nRol2 > 0 && $nRol3 > 0  && $nRol4 > 0 ){?>
            <table width="80%" align="center">

                
          <tr>
                	<td width="65%">
                      <table cellpadding="1" cellspacing="1" width="105%" align="left">
			<tr>
                              <td width="16%"></td>
                              <? 
							  	$qS	=	"SELECT nombre FROM supervisor WHERE area = 1 ORDER BY rol ASC";
								$rS	=	mysql_query($qS);

								while($dS	=	mysql_fetch_assoc($rS)){ 
									$nombre			=	explode(" ",$dS['nombre'] );
									
							?>
                              <td colspan="2" align="center"><h3><? echo $nombre[0].' '.$nombre[1]{0}?></h3></td>
                            <? } ?>
                        </tr>
                        <tr>
                              <td align="center"><h3>Fecha</h3></td>
                              <td width="4%" align="center"><h3>&nbsp;</h3></td>
                              <td width="16%" align="center"><h3>A.D.</h3></td>
                              <td width="4%" align="center"><h3>&nbsp;</h3></td>
                              <td width="16%" align="center"><h3>A.D.</h3></td>
                              <td width="4%" align="center"><h3>&nbsp;</h3></td>
                              <td width="16%" align="center"><h3>A.D.</h3></td>
                              <td width="4%" align="center"><h3>&nbsp;</h3></td>
                              <td width="16%" align="center"><h3>A.D.</h3></td>
                          </tr>
                      <? for($t = 0 , $c = 0, $d = 0, $z = 0, $o = 0; $t < $nFecha ;$t++){ ?>
                            <tr>
                              <td align="center" class="style5"><?=fecha($fecha[$t])?></td>
                             <? if( $numFecha[$t] < $fecha1[$t-$c]){ ?>
                              <td width="4%"  align="right" class="style5" >0</td>
                              <td width="4%"  align="right" class="style5" bgcolor="#EEEEEE">0</td>
                             <?  $c = $c + 1;} else {  ?>
                              <td width="4%"  align="right" class="style5"><? echo $turno[$t-$c];?></td>
                              <td width="16%" align="right" class="style7" bgcolor="#EEEEEE"><? 
								if($turno[$t-$c] != 0 ){
									$promedio = $kilogramos[$t-$c]/$turno[$t-$c];
										echo number_format($promedio,1);
								 }   else {
										echo "0.0";	
								 }					   
                                ?></td> 
                              <? }?> 
                             <? if( $numFecha[$t] < $fecha2[$t-$d]){ ?>
                              <td width="4%"  align="right" class="style5" >0</td>
                              <td width="4%"  align="right" class="style7" bgcolor="#EEEEEE">0.0</td>
                             <?  $d = $d + 1;} else {  ?>
                              <td width="4%"  align="right" class="style5"><? echo $turno2[$t-$d];?></td>
                              <td width="16%" align="right" class="style7" bgcolor="#EEEEEE"><? 
								if($turno2[$t-$d] != 0 ){
									$promedio2 = $kilogramos2[$t-$d]/$turno2[$t-$d];
										echo number_format($promedio2,1);
								 }   else {
										echo "0.0";	
								 }					   
                                ?></td> 
                              <? }?> 
                             <? if( $numFecha[$t] < $fecha3[$t-$z]){ ?>
                              <td width="4%"  align="right" class="style5" >0</td>
                              <td width="4%"  align="right" class="style7" bgcolor="#EEEEEE">0.0</td>
                             <?  $z = $z + 1;} else {  ?>
                              <td width="4%"  align="right" class="style5"><? echo $turno3[$t-$z];?></td>
                              <td width="16%" align="right" class="style7" bgcolor="#EEEEEE"><? 
								if($turno3[$t-$z] != 0 ){
									$promedio3 = $kilogramos3[$t-$z]/$turno3[$t-$z];
										echo number_format($promedio3,1);
								 }   else {
										echo "0.0";	
								 }					   
                                ?></td> 
                              <? }?> 
                             <?  if( $numFecha[$t] < $fecha4[$t-$o]){ ?>
                              <td width="4%"  align="right" class="style5" >0</td>
                              <td width="4%"  align="right" class="style7" bgcolor="#EEEEEE">0.0</td>
                             <?  $o = $o + 1;} else {  ?>
                              <td width="4%"  align="right" class="style5"><? echo $turno4[$t-$o];?></td>
                              <td width="16%" align="right" class="style7" bgcolor="#EEEEEE"><? 
								if($turno4[$t-$o] != 0 ){
									$promedio4 = $kilogramos4[$t-$o]/$turno4[$t-$o];
										echo number_format($promedio4,1);
								 }   else {
										echo "0.0";	
								 }					   
                                ?></td> 
                              <? }?> 
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
                          	<td align="right" class="style7"bgcolor="#EEEEEE"><?=number_format($prom,1)?></td>
                          	<td align="right" class="style5"><?=$total_tn2?></td>
                          	<td align="right" class="style7"bgcolor="#EEEEEE"><?=number_format($prom2,1)?></td>
                          	<td align="right" class="style5"><?=$total_tn3?></td>
                          	<td align="right" class="style7"bgcolor="#EEEEEE"><?=number_format($prom3,1)?></td>
                          	<td align="right" class="style5"><?=$total_tn4?></td>
                          	<td align="right" class="style7"bgcolor="#EEEEEE"><?=number_format($prom4,1)?></td>
                          </tr>
                          <tr>
                          	<td><h3>X 24 hrs.</h3></td>
                          	<td align="right" class="style5"></td>
                          	<td align="right" class="style7"bgcolor="#EEEEEE"><?=number_format($prom*24,1)?></td>
                          	<td align="right" class="style5"></td>
                          	<td align="right" class="style7"bgcolor="#EEEEEE"><?=number_format($prom2*24,1)?></td>
                          	<td align="right" class="style5"></td>
                          	<td align="right" class="style7"bgcolor="#EEEEEE"><?=number_format($prom3*24,1)?></td>
                          	<td align="right" class="style5"></td>
                          	<td align="right" class="style7"bgcolor="#EEEEEE"><?=number_format($prom4*24,1)?></td>
                          </tr>                          
                        </table>
                  </td>
                  <td width="3%"></td>
 		    <td width="29%" valign="top">  
			<table width="100%" align="right" cellpadding="1" cellspacing="1">    
                            <tr>
                              <td width="28%" align="center"><h3>Turno 1</h3></td>
                              <td width="26%" align="center"><h3>Turno 2</h3></td>
                              <td width="25%" align="center"><h3>Turno 3</h3></td>
                             <!-- <td width="21%" align="center"><h3>PROM</h3></td> -->
</tr>
                            <tr>
                              <td colspan="4" align="center"><h3>META X HORA</h3></td>
                            </tr>
                            <? for($t = 0, $r = 0; $t < sizeof($pturnos) ;$t++, $r++){ ?>
                            <tr>
                   	      <? for($s = 0; $s < 3 ;$s++){  @$resul[$t]	=	$pkilo[$t]/$pturnos[$t];  ?>
                                <td  class="style7" align="right" <? 
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
                            	<td align="right" class="style7" style=" height:27px"><?=number_format($uno,1)?></td>
                            	<td align="right" class="style7" style=" height:27px"><?=number_format($dos,1)?></td>
                            	<td align="right" class="style7" style=" height:27px"><?=number_format($tres,1)?></td>
                            </tr> 
                            <tr bgcolor="#EEEEEE">
                            	<td align="right" class="style7" style=" height:27px"><?=number_format($uno/$nFecha,1)?></td>
                            	<td align="right" class="style7" style=" height:27px"><?=number_format($dos/$nFecha,1)?></td>
                            	<td align="right" class="style7" style=" height:27px"><?=number_format($tres/$nFecha,1)?></td>
                            </tr>                                                          
                        </table> 
                  </td>
              </tr> 
             </table>
         <? } else { ?>
	<table width="80%" align="center">
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
</form></div></div>
<? } ?>

<?  
///////////////PAROS//////////////////////////////////////////////////////////////
if($_REQUEST['tipo'] == 34 ){ 
 	$meMeta =	$_REQUEST['mes_pm'];
	$anho 	=	$_REQUEST['anho_pm'];
	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$mesMeta	=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia = UltimoDia($anho,$meMeta);
	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;
				
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
?>
<div align="center" id="tablaimpr">
	<div class=" tablaCentrada titulos_i" id="tabla_reporte">
    <p align="center" class="titulos_reportes">PAROS MENSUALES EN IMPRESION <br />DE <?=$mes[$meMeta]?> DEL <?=$anho?></p><br /><br />
	<table width="70%" align="center">
       
              <tr align="center">
                <td width="25%"><h3>Maquinas</h3></td>      
                <td width="20%"><h3>Mantenimiento</h3></td>      
                <td width="16%"><h3>Falta de Opr</h3></td>      
                
                <td width="12%"><h3>Otro</h3></td>      
                <td width="12%"><h3>Total</h3></td>
                <td width="15%" colspan="2"><h3>C._IMPR</h3></td>      
      </tr>
              	<? 	
						for($b++;$dSelectImpresion	=	mysql_fetch_assoc($rSelectImpresion); $b++){	
			  			$dSelectImpresionImpr	=	$dSelectImpresion['mantenimiento']+$dSelectImpresion['falta_personal']+$dSelectImpresion['fallo_electrico'];
			  			$TotalOI	=	($dSelectImpresion['fallo_electrico']+$dSelectImpresion['otras']);
				?>
              <tr <? cebra($b) ?>>
                <td ><?=$dSelectImpresion['numero']?> - <?=$dSelectImpresion['marca']?></td>      
                <td align="right" class="style5"><? printf("%.2f ",$dSelectImpresion['mantenimiento']/(24/3))?></td>      
                <td align="right" class="style5"><? printf("%.2f ",$dSelectImpresion['falta_personal']/(24/3))?></td> 
                <td align="right" class="style5"><? printf("%.2f ",$TotalOI/(24/3))?></td>      
                <td align="right" class="style5"><? printf("%.2f ",($TotalOI+$dSelectImpresion['falta_personal']+$dSelectImpresion['mantenimiento'])/(24/3))?></td>  
                <td colspan="2" align="right" class="style5"><?=$dSelectImpresion['cambio_impresion']?></td> 
			  </tr>
             <?	 
			 	   $turnos_manI		+= $dSelectImpresion['mantenimiento'];
			 	   $turnos_perI		+= $dSelectImpresion['falta_personal'];
				   $turnos_falloI	+= $TotalOI;	
				   $turnos_impreI	+= $dSelectImpresion['cambio_impresion'];					   		 
			 	   $Total_impresionI	+= $dSelectImpresionImpr;
				   $Total_impresionI	=	$Total_impresionI+$TotalO;
				   $turno_totalI	=	$turnos_manI + $turnos_perI + $turnos_falloI;
			 ?>
             <? } ?>  
              <tr>
                <td align="right"><h3>TURNOS PARADOS:</h3></td>      
                <td align="right" class="style4"><? printf("%.2f ",$turnos_manI/(24/3))?></td>      
                <td align="right" class="style4"><? printf("%.2f ",$turnos_perI/(24/3))?></td>  
                <td align="right" class="style4"><? printf("%.2f ",$turnos_falloI/(24/3))?></td>      
                <td align="right" class="style4"><? printf("%.2f ",$turno_totalI/(24/3))?></td>  
                <td colspan="2" align="right" class="style5"><?=$turnos_impreI?></td>  
			  </tr>
              <tr bgcolor="#EEEEEE">
                <td align="right" ><h3>DIAS:</h3></td>      
                <td align="right" class="style4"><? printf("%.2f ",($turnos_manI/24)/$nImpresion)?></td>      
                <td align="right" class="style4"><? printf("%.2f ",($turnos_perI/24)/$nImpresion)?></td>
                <td align="right" class="style4"><? printf("%.2f ",($turnos_falloI/24)/$nImpresion)?></td>      
                <td align="right" class="style4"><? printf("%.2f ",(($turnos_falloI+$turnos_perI+$turnos_manI)/24)/$nImpresion)?></td>      
			  </tr>    
</table>
	<br />
	<br />
</div>
</div>      
<? } ?>

  <?  
  if($_REQUEST['tipo'] == 6 ){
  
  		if($_REQUEST['tiempo'] == 0){
			$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
		if(!isset($_REQUEST['hasta']) || $_REQUEST['hasta'] == "")
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
		$mes_fecha = $_REQUEST['mes'];	
		$ano	=	$_REQUEST['anho'];
		$mes_c	=	num_mes_cero($_REQUEST['anho']."-".$mes_d."-01");
		$mesM	=	$_REQUEST['anho'].'-'.$mes_c.'-01';
		
		$ultimo_dia = UltimoDia($ano,$mes_d);	
		$desde	= 	$_REQUEST['anho']."-".$mes_c."-01";
		$hasta	= 	$_REQUEST['anho']."-".$mes_c."-".$ultimo_dia;
		}

				 
		$qMetasImp	=	"SELECT * FROM meta WHERE mes = '".$mesM."'  AND area = '2'";
		$rMetasImp	=	mysql_query($qMetasImp);
		$dMetasImp	=	mysql_fetch_assoc($rMetasImp);
		
		//IMPRESION
		$qEntrada2	=	" SELECT SUM(impresion.total_hd), fecha, SUM(impresion.desperdicio_hd) , SUM(impresion.k_h) ".
						" FROM entrada_general ".
						" INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
						" WHERE (fecha BETWEEN '".$desde."' AND '".$hasta."' )  AND impresion = 1 AND repesada = 1 ".
						" GROUP BY fecha ORDER BY fecha ASC";
		$rEntrada2	=	mysql_query($qEntrada2);
		
		$qEntradaMatutino2		=	" SELECT total_hd, desperdicio_hd,  entrada_general.id_entrada_general ".
									" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
									" WHERE ( fecha BETWEEN '".$desde."' AND '".$hasta."') AND impresion = 1 AND repesada = 1 ".
									" ORDER BY fecha, turno ASC";
									
		$rEntradaMatutino2		=	mysql_query($qEntradaMatutino2);	
		$nEntrada2	=	mysql_num_rows($rEntrada2); ?>

<script type="text/javascript" language="javascript">
function genera()
{
document.metas.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?><? if(isset($_REQUEST['tiempo'])){ ?>&tiempo=<?=$_REQUEST['tiempo']; } ?><? if(isset($_REQUEST['anho'])){ ?>&anho=<?=$_REQUEST['anho']; } ?><? if(isset($_REQUEST['mes'])){ ?>&mes=<?=$_REQUEST['mes']; } ?><? if(isset($_REQUEST['desde'])){ ?>&desde=<?=$_REQUEST['desde']; }?><? if(isset($_REQUEST['hasta'])){ ?>&hasta=<?=$_REQUEST['hasta']; }?>";
document.metas.submit();
}
</script>
<div align="center" id="tablaimpr">
	<div class="tablaCentrada titulos_i" id="tabla_reporte">
	<p class="titulos_reportes" align="center">PRODUCCI&Oacute;N DIARIA IMPRESI&Oacute;N <br /><?=$mes[$mes_fecha]?> DEL <?=$ano?><br /><br /></p>
	<form name="metas"  id="super" method="post" >
	<table width="80%" align="center">
    <? if($nEntrada2 == 1){ 
	$dEntrada2 =	mysql_fetch_row($rEntrada2); ?>
  <tr >
    <td align="center"><h3>Fecha</h3></td>
    <td align="center" class="style5"><?=fecha($dEntrada2[1]);?></td>
  </tr>
  
  <tr  bgcolor="#ff6633">
  	<td align="center" class="style7" style="color:#FFFFFF">Turno</td>
    <td align="center" class="style7" style="color:#FFFFFF">Producci&oacute;n</td>
    <td align="center"  class="style7" style="color:#FFFFFF">Meta</td>
    <td align="center"  class="style7" style="color:#FFFFFF">Dif.</td>
    <td align="center" class="style7" style="color:#FFFFFF">Producci&oacute;n</td>
    <td width="92" align="center" class="style7" style="color:#FFFFFF">Desp.</td>
    <td width="89" align="center" class="style7" style="color:#FFFFFF">Meta Desp. (<?=$dMetas['porcentaje_desp']?>%)</td>
    <td width="63" align="center" class="style7" style="color:#FFFFFF">Dif.</td>
    <td width="53" align="center" class="style7" style="color:#FFFFFF">Kg/H</td>
  </tr>   
  <? 
  for($a=0; $dEntradaMatutino2		=	mysql_fetch_row($rEntradaMatutino2); $a++){ 
  		if($a==0){
  			$turnos	=	"Matutino";
  			$horas	=	8;
  		}
  		if($a==1){
  			$turnos	=	"Vespertino";
  			$horas	=	7;
  		}
  		if($a==2){
  			$turnos	=	"Nocturno";
  			$horas	=	9;
  		}
  ?>
  <tr <? cebra($a+1); ?>>
    <td align="center"><h3><?=$turnos?></h3></td>
    <td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=impresion&id_entrada_general=<?=$dEntradaMatutino2[2]?>" 	style="text-decoration:none; color:#000000"><?=number_format($dEntradaMatutino2[0]);?></a></b></td>
    <!--<td align="right" class="style5"><? echo number_format($MetaMatu =($dMetasImp['total_dia']/24)*$horas);?></td>-->
    <td align="right" class="style5"><? echo number_format($MetaMatu =$dMetasImp['prod_hora']*$horas);?></td>
    <td align="right" class="style5"><? echo number_format($nuevoM =  $MetaMatu - $dEntradaMatutino2[0]);?></td>
    <td align="center"><? 
								 $pM = $MetaMatu *.20;
								 $nuevaM	=	$MetaMatu - $pM.'<br>';

								 if(floor($dEntradaMatutino2[0]) < floor($nuevaM) && $dEntradaMatutino2[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dEntradaMatutino2[0]) > floor($nuevaM+($pM*3))) echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dEntradaMatutino2[0]) == 0) echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?>    </td>
    <td align="right" class="style5"><b><? echo number_format($total_desperImprM = $dEntradaMatutino2[1])?></b></td>
    <!--<td align="right" class="style5"><? echo number_format($DiferenciaImprM = (($dMetasImp['desp_duro_hd']/$ultimo_dia)/24)*8);?></td>-->
    <td align="right" class="style5"><? echo number_format($DiferenciaImprM = (($dMetasImp['porcentaje_desp']*$dEntradaMatutino2[0]/100)/24)*8);?></td>
    <td align="right" class="style5"><? echo number_format($DiferenciaImprM - $total_desperImprM );?></td>
        <td align="right" class="style5"><?=number_format($dEntradaMatutino2[0]/8);?></td>
  </tr>
  <? } ?>
  
  
<tr>
    <td align="right" ><h3>Total</h3></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><b><?=number_format($dEntrada2[0]);?></b></td>
    <!--<td align="right" bgcolor="#DDDDDD" class="style5"><? echo number_format($metasss = $dMetasImp['total_dia']);?></td>-->
    <td align="right" bgcolor="#DDDDDD" class="style5"><? echo number_format($metasss = $dMetasImp['prod_hora']*24);?></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><? echo number_format($nuevo = $metasss - $dEntrada2[0]);?></td>
    <td align="center" bgcolor="#DDDDDD"><? 
								 $porciento2 = $metasss *.20;
								 $nueva2	=	$metasss - $porciento2;
								 if(floor($dEntrada2[0]) < floor($nueva2) && $dEntrada2[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dEntrada2[0]) > floor($nueva2+($porciento2*3))) echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dEntrada2[0]) == 0) echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?>    </td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><b><? echo number_format($total_desperImpr = $dEntrada2[2])?></b></td>
    <!--<td align="right" bgcolor="#DDDDDD" class="style5"><? echo number_format($DiferenciaImpr = ($dMetasImp['desp_duro_hd']/$ultimo_dia));?></td>-->
<td align="right" bgcolor="#DDDDDD" class="style5"><? echo number_format($DiferenciaImpr = $dMetasImp['porcentaje_desp']*$dEntrada2[0]/100);?></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><? echo number_format($DiferenciaImpr - $total_desperImpr);?></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dEntrada2[0]/24);?></td>
  </tr> 
  <? 
  } 
  ?>
 <? if($nEntrada2 > 1){ ?>
  <tr style="background-color:#FF6633">
    <td width="85" align="center" class="style7" style="color:#FFFFFF">Fecha</td>
    <td width="92" align="center" class="style7" style="color:#FFFFFF">Kg</td>
    <td width="81" align="center" class="style7" style="color:#FFFFFF">Meta</td>
    <td width="77" align="center" class="style7" style="color:#FFFFFF">Dif.</td>
    <td width="130" align="center" class="style7" style="color:#FFFFFF">Producci&oacute;n</td>
    <td align="center" class="style7" style="color:#FFFFFF">Desp.</td>
    <td align="center" class="style7" style="color:#FFFFFF">Meta Desp. (<?=$dMetasImp['porcentaje_desp']?>%)</td>
    <td align="center" class="style7" style="color:#FFFFFF">Dif.</td>
    <td align="center" class="style7" style="color:#FFFFFF">KG/H</td>
  </tr>
  <?	 	for($b=0; $dEntrada2 =	mysql_fetch_row($rEntrada2); $b++){ ?>
  <tr  <? cebra($b) ?> >
    <td align="center" class="style5"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dEntrada2[1]);?></td>
    <td align="right" class="style5"><b><?=number_format($dEntrada2[0]);?></b></td>
    <!--<td align="right" class="style5"><? echo number_format($metasss = $dMetasImp['total_dia']);?></td>-->
    <td align="right" class="style5"><? echo number_format($metasss = $dMetasImp['prod_hora']*24);?></td>
    <td align="right" class="style5"><? echo number_format($nuevo = $metasss - $dEntrada2[0]);?></td>
    <td align="center" ><? 
								 $porciento2 = $metasss *.20;
								 $nueva2	=	$metasss - $porciento2;
								 if(floor($dEntrada2[0]) < floor($nueva2) && $dEntrada2[0] > 0 ) echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dEntrada2[0]) > floor($nueva2+($porciento2*3))) echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dEntrada2[0]) == 0) echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?></td>
    <td align="right" class="style5"><b><? echo number_format($total_desperImpr = $dEntrada2[2])?></b></td>
    <!--<td align="right" class="style5"><? echo number_format($DiferenciaImpr = ($dMetasImp['desp_duro_hd']/$ultimo_dia));?></td>-->
    <td align="right" class="style5"><? echo number_format($DiferenciaImpr = $dEntrada2[0]*$dMetasImp['porcentaje_desp']/100);?></td>
    <td align="right" class="style5"><? echo number_format($TotalDifImpr = $DiferenciaImpr - $total_desperImpr);?></td>
    <td align="right" class="style5"><? echo number_format($khi = $dEntrada2['0']/24);?></td>
  </tr>
  <? 
 	$TOTALIKG 	+= $dEntrada2['0'];
	$TOTALIMETA	+= $metasss; 
	$TOTALIDIF	+= $nuevo;
	$TOTALIKGH	+= $khi;
	$TOTALIDESP	+= $total_desperImpr;
	$TOTALIDESPDIF	+= $TotalDifImpr;
	$TOTALIMETADESP	+= $DiferenciaImpr;
  
  } ?>
  <tr bgcolor="#DDDDDD">
    <td colspan="1" align="right" class="style5"><h3>TOTALES:</h3></td>
    <td align="right" class="style5"><b><?=number_format($TOTALIKG);?></b></td>
    <td align="right" class="style5"><? echo number_format($TOTALIMETA);?></td>
    <td align="right" class="style5"><? echo number_format($TOTALIDIF);?></td>
    <td align="center" ><? 
								 $porcientometa2 = $TOTALIMETA *.20;
								 $nuevameta2	=	$TOTALIMETA - $porcientometa2;
								 if(floor($TOTALIKG) < floor($nuevameta2) && $TOTALIKG > 0 ) echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($TOTALIKG) > floor($nuevameta2+($porcientometa2*3))) echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($TOTALIKG) == 0) echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?>	</td>
    <td align="right" class="style5"><b><? echo number_format($TOTALIDESP);?></b></td>
    <td align="right" class="style5"><? echo number_format($TOTALIMETADESP);?></td>
    <td align="right" class="style5"><? echo number_format($TOTALIDESPDIF);?></td>
    <td align="right" class="style5"><? echo number_format($TOTALIKG/24)?></td>
  </tr>
  <? } ?>
  <tr>
    <td colspan="11"></td>
  </tr>
</table>
</form></div></div>
  <? }?>

  <?  if($_REQUEST['tipo'] == 51 ){ ?>
<div align="center" id="tablaimpr">
    <div class="tablaCentrada titulos_i" id="tabla_reporte">
    <p class="titulos_reportes" align="center">REPORTE DE PRODUCCION POR MAQUINA EN IMPRESION<br /><?=$mes[$_REQUEST['mes_maq']]?> de <?=$_REQUEST['ano_maq']?><br /></p>
	<form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
	<table width="700px" align="center">
<? 	for($z=0; $z<sizeof($_REQUEST['maq_id']);$z++){

	$qMq	=	" SELECT  SUM(subtotal) AS total, resumen_maquina_im.id_maquina AS id_maquina, maquina.numero, fecha, maquina.area  FROM resumen_maquina_im "	.
				" INNER JOIN impresion 			ON 	resumen_maquina_im.id_impresion 	= impresion.id_impresion ".
				" INNER JOIN entrada_general 	ON 	entrada_general.id_entrada_general 	= impresion.id_entrada_general ".
				" INNER JOIN maquina 			ON resumen_maquina_im.id_maquina 		= maquina.id_maquina ".
				" WHERE ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." ) AND  impresion = 1  AND resumen_maquina_im.id_maquina = ".$_REQUEST['maq_id'][$z]." GROUP BY  DAY(fecha)".
				" ORDER BY fecha ASC";
	
	$qMqP	=	" SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_im.id_maquina, rol,  maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_im "	.
				" INNER JOIN impresion 	ON resumen_maquina_im.id_impresion 	= impresion.id_impresion ".
				" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= impresion.id_entrada_general ".
				" INNER JOIN maquina 	ON resumen_maquina_im.id_maquina 		= maquina.id_maquina ".
				" WHERE subtotal != 0 AND maquina.id_maquina = ".$_REQUEST['maq_id'][$z]." AND YEAR(fecha) = '".$_REQUEST['ano_maq']."' AND MONTH(fecha) = '".$_REQUEST['mes_maq']."' AND  impresion = 1 GROUP BY  maquina.numero".
				" ORDER BY maquina.numero ASC ";				
				
	$qMetas	=	" SELECT * FROM meta".
				" LEFT JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".
				" WHERE id_maquina = ".$_REQUEST['maq_id'][$z]." AND ( MONTH(mes) = ".$_REQUEST['mes_maq']."  AND ano = ".$_REQUEST['ano_maq']." )";

	$qAM	=	"SELECT area, numero FROM maquina WHERE id_maquina	=	".$_REQUEST['maq_id'][$z]."";
	$rAM	=	mysql_query($qAM);
	$dAM	=	mysql_fetch_assoc($rAM);
				
$query	=	" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
			" LEFT JOIN tiempos_muertos ON impresion.id_impresion = tiempos_muertos.id_produccion ";


$rMq	=	mysql_query($qMq);
$rMqP	=	mysql_query($qMqP);
$dMqp	=	mysql_fetch_assoc($rMqP);

$rMetas	=	mysql_query($qMetas);
$dMetas	=	mysql_fetch_assoc($rMetas);

$id_maquinas	=	$dMetas['id_maquina'];

?>

	<tr style="page-break-after:always;" >
		<td width="330px" align="right">
            <table align="center" width="315" style="margin:14px;">
            <tr>
				<td align="center" colspan="4" valign="middle" style="height:24px;" ><b>Maquina :  <?
					if($dAM['area'] == 2) echo "Impr. ";
					if($dAM['area'] == 3) echo "L. de Impr. ";
					echo $dAM['numero']?></b>
                 </td>
			</tr>
			<tr>
				<td width="82" align="center"><h3>Fecha</h3></td>
				<td width="89" align="center"><h3>Produccion</h3></td>
				<td width="41" align="center"><h3>Meta</h3></td>
				<td width="83" align="center"><h3>Diferencia</h3></td>
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
                                                 <td align="right" class="style5"><?=number_format($dMq['total'],1)?></td>
                                                 <td align="right" class="style5"><?=number_format($metas_maquinas,1)?></td>
                                                 <td align="right" class="style5"><?=number_format($diferencia,1)?></td>
                                            </tr>
                                            <? 	$a++;
												$Total_metas	+=	$metas_maquinas;
												$total_prod		+=	$dMq['total'];
												$total_dif		+=	$diferencia;
												} ?>
                                            <tr>
                                            	<td><h3>Totales: </h3></td>
                                                <td align="right"><?=number_format($total_prod,1)?></td>
                                                <td align="right"><?=number_format($Total_metas,1)?></td>
                                              	<td align="right"><?=number_format($total_dif,1)?></td>
                                            </tr>
                                            <tr>
                                            	<td><h3>Promedio: </h3></td>
                                                <td align="right"><?=@number_format($total_prod/$dMqp['paros'],1)?></td>
                                                <td align="right"></td>
                                              <td align="right"></td>
                                            </tr>                                          
                                          </table>
          </td>
                          <td width="10px"></td>
               	    	  <td width="330px" align="left" valign="middle"  >
                          	<table align="center" width="280px" style="margin:15px;" >
                            <tr>
                              <td colspan="4" align="center"><h3>Paros :: Motivos</h3></td>
                            </tr>
                            <tr>
                              <td width="83" align="center"><h3>Turnos</h3></td>
                              <td width="72" align="center"><h3>Mantto </h3></td>
                              <td width="58" align="center"><h3>Oper. </h3></td>
                              <td width="82" align="center"><h3>Otros </h3></td>
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
                              <td align="center" style="height:27px">Turnos
                                <?  @printf("%.2f" ,$tTotal)?>                              </td>
                              <td align="center" style="height:27px"><? @printf("%.2f" ,$tMantoTotal)?>                              </td>
                              <td align="center" style="height:27px"><? @printf("%.2f" ,$tMantoT)?>                              </td>
                              <td align="center" style="height:27px"><? @printf("%.2f" ,$tFallorT)?>                              </td>
                            </tr>
                            <tr align="center" height="25">
                              <td style="height:27px">Dias
                                <?  @printf("%.2f" ,$tTotal/3)?>                              </td>
                            </tr>
                            
                          </table>
         </td>
      </tr>
	<? } ?>

</table>    
	</form>
</div>
</div>
<? }?> 
<? if($_REQUEST['tipo'] == 18){  

$tipo = $_REQUEST['tipo'];
$numero_maq		=	"";
for($z=0; $z<sizeof($_REQUEST['maq_id_impre']);$z++){
	$numero_maq	.= $_REQUEST['maq_id_impre'][$z];
	
	if(sizeof($_REQUEST['maq_id_impre'])-1 > $z)
	$numero_maq	.= " ,";
	
}

if($tipo == 18)
{ 
	$area 	= 	2; 
	$WH 	=  	"area IN (2,3) AND id_maquina IN ($numero_maq)";
	
	$qQuery	=	"SELECT * FROM entrada_general ".
				" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general";
	$cambio	=	"cambio_impresion";
	$compara =	" impresion.id_impresion = tiempos_muertos.id_produccion";
	$titulo	=	" DE IMPRESION";
	$orden	=	"id_impresion";

	 
}

$fecha	=	fecha_tablaInv($_REQUEST['fecha_hist']);
$fecha_f=	fecha_tablaInv($_REQUEST['fecha_hist_f']);


	$qMaquinas 	= 	"SELECT numero, id_maquina FROM maquina  WHERE $WH  ORDER BY area, numero ASC";
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
    <div class="tablaCentrada titulos_i" id="tabla_reporte">
    <p class="titulos_reportes" align="center">CAMBIO DE IMPRESION <br>DEL <?=$_REQUEST['fecha_hist']?> AL <?=$_REQUEST['fecha_hist_f']?></p><br>
        <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">
<? 	for($i=0;$i<$cant;$i++) {  ?>
<table width="350"  align="center" <? if($i==2 || $i==5 || $i==8) { ?> style="page-break-after: always;" <? } ?> >
<tr>
<?		 for($x=1;$x<=2; $x++){
		  if($reg<$cant_lic){
		  
			 	$qReportes	=	$qQuery. " LEFT JOIN tiempos_muertos ON $compara ".
								" LEFT JOIN oper_maquina ON tiempos_muertos.id_maquina = oper_maquina.id_maquina".
								" WHERE fecha BETWEEN '".$fecha."' AND '".$fecha_f."' ".
								" AND tiempos_muertos.id_maquina = ".$id[$reg]." ".
								" AND $cambio = 1 AND entrada_general.repesada = 1 ";
						//		" AND  id_maquina IN ($numero_maq) AND entrada_general.repesada = 1 ";
	

				$qReportes	.=	" GROUP BY $orden ORDER BY fecha ASC";	
				
				$rReportes	=	mysql_query($qReportes);
				$nReportes	=	mysql_num_rows($rReportes);
				if($nReportes > 0){
	  
		 ?>
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
						<?	                            
                                for($a=0; $dTiempo	=	mysql_fetch_assoc($rReportes); $a++){
									?>								
                                	
                                <tr <? cebra($a) ?> >
                                  <td align="left" class="style7"><?=fecha($dTiempo['fecha'])?></td>
                                  <td align="center"><?=$dTiempo['turno']?></td>
                                 <td  align="left" class="style5" style="font-size:9px"><?
								  $qS	=	"SELECT nombre FROM supervisor WHERE id_supervisor = ".$dTiempo['id_supervisor']."";
								  $rS	=	mysql_query($qS);
								  $dS	=	mysql_fetch_row($rS); 
								  
								  $nombre = explode(" ", $dS[0]);
								  echo $nombre[0].' '.$nombre[1] ;?></td>
				</tr>
				<? } ?>
			</table> <? } else {  $x = $x - 1 ;
				}
			}?>
			</td>
		<? $reg++;} ?> 
      </tr><br /><br />
	</table><?  } ?>
			  <table width="100%" align="center">    
                            <tr><td colspan="5" align="center"><input type="button" name="pfd" id="logo"  value="Formato de impresion" onClick="genera()"></td></tr>
			</table>            
            </form>
        </div>
</div>
<? } ?>


<? if( $_REQUEST['tipo'] == 15  ){  

$tipo = $_REQUEST['tipo'];

if($tipo == 15) $area = 2;


$fecha	=	fecha_tablaInv($_REQUEST['fecha_incidencia']);
$fechaFin=	fecha_tablaInv($_REQUEST['fecha_incidencia_f']);

if($area == 2){
 $qReportes	=	"SELECT id_impresion, turno, id_supervisor, impresion.observaciones, fecha FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
					" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."' ORDER BY fecha, turno ASC";
}



$rReportes	=	mysql_query($qReportes);
	
	
?>
<script type="text/javascript" language="javascript">
function genera()
{
document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&fecha_incidencia=<?=$_REQUEST['fecha_incidencia']?>&fecha_incidencia_f=<?=$_REQUEST['fecha_incidencia_f']?>";
document.form.submit();
document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";
}
</script>
<div align="center" id="tablaimpr">
	<div class="tablaCentrada titulos_i" id="tabla_reporte" align="center" >
	<p class="titulos_reportes" align="center">REPORTE DE INCIDENCIAS IMPRESION	</p>
        <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">
<? for($b = 1, $g = 2  ; $dReportes	=	mysql_fetch_row($rReportes); $b++, $g = $g+2){
						if($dReportes[1] == 1) $turno = "MATUTINO";	
						if($dReportes[1] == 2) $turno = "VESPERTINO";	
						if($dReportes[1] == 3) $turno = "NOCTURNO";?>			 
            <table border="0" width="80%" align="center" <? if($b==$g || $b==$g  || $b==$g ) { ?> style="page-break-after: always;" <? } ?> >
  				<tr>
                    <td align="right" ><h3>Fecha : </h3></td>
                    <td ><?=fecha($dReportes['4'])?></td>
                    <td  align="center" class="style7"><h3>TURNO: </h3></td>
					<td colspan="3"><?=$turno?></td>
                </tr>
                <tr>
                	<td align="right"><h3>Supervisor:</h3></td>
                    <td colspan="7" class="style7"><?   $qSuper	=	"SELECT nombre FROM supervisor WHERE id_supervisor = ".$dReportes[2]."";
											$rSuper	=	mysql_query($qSuper);
											$dSuper =	mysql_fetch_row($rSuper);
											echo $dSuper[0];
						?></td>
                </tr>
                <tr>
				  <td width="15%" align="center"><h3>Maquina</h3></td>
				  <td width="12%" align="center"><h3>Fallo_Elec.</h3></td>
				  <td width="12%" align="center"><h3>Falta_Pers.</h3></td>
				  <td width="8%" align="center"><h3>Mantto.</h3></td>
				  <td width="8%" align="center"><h3>Otras</h3></td>
				  <td width="10%" align="center"><h3>C._Impr.</h3></td>
				  <td width="35%" align="center"><h3>Observaciones</h3></td>
              </tr>                
						<?	$qTiempos	=	"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina WHERE tipo = $area AND id_produccion = ".$dReportes[0]." ORDER BY maquina.numero ASC";
                            $rTiempos	=	mysql_query($qTiempos);
                            
                                for($a = 0; $dTiempo	=	mysql_fetch_assoc($rTiempos); $a++){
								if($dTiempo['fallo_electrico'] 	== '00:00:00') 	$fallo 			= "&nbsp;"; else $fallo 			= $dTiempo['fallo_electrico'];
								if($dTiempo['mantenimiento'] 	== '00:00:00') 	$mantenimiento 	= "&nbsp;"; else $mantenimiento 	= $dTiempo['mantenimiento'];
								if($dTiempo['falta_personal'] 	== '00:00:00') 	$falta_personal = "&nbsp;"; else $falta_personal 	= $dTiempo['falta_personal'];
								if($dTiempo['otras'] 			== '00:00:00') 	$otras 			= "&nbsp;"; else $otras 			= $dTiempo['otras'];
								if($area == 1) $opcion = "mallas";
								if($area == 2) $opcion = "cambio_impresion";
								if($dTiempo[$opcion]		 	== '0' || $dTiempo[$opcion] == '') 		$mallas		 	= "&nbsp;"; else if($dTiempo[$opcion] != '0')  $mallas			= "SI";
								
								if($dTiempo['observaciones']	== '0') $observaciones	= "&nbsp;"; else $observaciones	=	$dTiempo['observaciones'];
								
								if($fallo == "&nbsp;" && $mantenimiento == "&nbsp;" && $falta_personal == "&nbsp;" && $otras == "&nbsp;" && $mallas == "&nbsp;" && $observaciones == "&nbsp;"){
								$a = $a + 1;
								}else {
								?> 
                                	
                                <tr <? if(bcmod($a,2)==0) echo  ''; else echo 'bgcolor="#DDDDDD"';  ?> >
                                  <td align="left" class="style7"><?=$dTiempo['marca'].' - '.$dTiempo['numero'];?></td>
                                  <td align="center"><?=$fallo?></td>
                                  <td align="center"><?=$falta_personal?></td>
                                  <td align="center"><?=$mantenimiento?></td>
                                  <td align="center"><?=$otras?></td>
                                  <? if($area == 1 || $area ==2) { ?><td align="center"><?=$mallas?></td><? } ?>
                                  <td class="style5"><?=$observaciones?>&nbsp;</td>
                                </tr>
                                <? } 
							}?> 
                                               
					<? if($area == 2){ ?>
                   <tr>
                   	<td colspan="11" align="center" class="subtitulos" >Lineas de impresion</td>
                   </tr> 
					<?			
							$qTiempos1	=	"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina WHERE tipo = 3 AND id_produccion = ".$dReportes[0]." ORDER BY maquina.numero ASC";
                            $rTiempos1	=	mysql_query($qTiempos1);
                            
                                for($a1 = 0; $dTiempo1	=	mysql_fetch_assoc($rTiempos1); $a1++){
								if($dTiempo1['fallo_electrico'] 	== '00:00:00') 	$fallo1 			= "&nbsp;"; else $fallo1 			= $dTiempo1['fallo_electrico'];
								if($dTiempo1['mantenimiento'] 	== '00:00:00') 	$mantenimiento1 	= "&nbsp;"; else $mantenimiento1 	= $dTiempo1['mantenimiento'];
								if($dTiempo1['falta_personal'] 	== '00:00:00') 	$falta_personal1 = "&nbsp;"; else $falta_personal1	= $dTiempo1['falta_personal'];
								if($dTiempo1['otras'] 			== '00:00:00') 	$otras1 			= "&nbsp;"; else $otras1 			= $dTiempo1['otras'];
								if($dTiempo1['cambio_impresion']	== '0' || $dTiempo1['cambio_impresion']	== '') 		$mallas1		 	= "&nbsp;"; else $mallas1			= "SI";
								if($dTiempo1['observaciones']	== '0') $observaciones1	= "&nbsp;"; else $observaciones1	=	$dTiempo1['observaciones'];
								
								if($fallo1 == "&nbsp;" && $mantenimiento1 == "&nbsp;" && $falta_personal1 == "&nbsp;" && $otras1 == "&nbsp;" && $mallas1 == "&nbsp;" && $observaciones1 == "&nbsp;"){
								$a1 = $a1 + 1;
								}else {
								?> 
                                <tr <? if(bcmod($a1,2)==0) echo  ''; else echo 'bgcolor="#DDDDDD"';  ?> >
                                      <td align="left" class="style7"><?=$dTiempo1['marca'].' - '.$dTiempo1['numero'];?></td>
                                      <td align="center"><?=$fallo1?></td>
                                      <td align="center"><?=$falta_personal?></td>
                                      <td align="center"><?=$mantenimiento1?></td>
                                      <td align="center"><?=$otras1?></td>
                                      <td align="center"><?=$mallas1?></td>
                                      <td class="style5"><?=$observaciones1?>&nbsp;</td>
                                </tr>
                                <? } 
							}
						}?>                             
                   <tr>
                   		<td colspan="2"  align="right"><h3>Observaciones Generales:</h3></td>
                    	<td colspan="7" class="style5" align="justify"><?=$dReportes[3]?></td>
                  </tr>
                <br>
            </table>
	            <? } ?>    
           
            <table width="100%" align="center">    
                            <tr><td colspan="5" align="center"><input type="button" name="pfd" id="logo"  value="Formato de impresion" onClick="genera()"></td></tr>
			</table>
         </form>
        </div>
</div>
<? } ?>

<? if($_REQUEST['tipo'] == 62){ 
/*
$meMeta	=	$_REQUEST['mes_grafica'];
$anho	=	$_REQUEST['ano_grafica'];

	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$meses	=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia = UltimoDia($anho,$meMeta);
	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;
*/
	$mes		=	fecha_tablaInv($_REQUEST['fecha_mp']);
	$date		=	explode('-',$mes);
	$mesFinal	=	fecha_tablaInv($_REQUEST['fecha_mpf']);
	$meses		=	$date[0].'-'.$date[1].'-01';
/////////////////////// PAROS POR DIA /////////////////////////
$qSelectTiemposExtr	=	"SELECT SUM(TIME_TO_SEC(mantenimiento)) AS mantenimiento ,".
						" SUM(TIME_TO_SEC(falta_personal)) AS falta_personal ,".
						" SUM(TIME_TO_SEC(fallo_electrico+otras)) AS otras ".
						" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
						" LEFT JOIN tiempos_muertos ON impresion.id_impresion = tiempos_muertos.id_produccion ".
						" WHERE tipo = 1 AND fecha BETWEEN '".$mes."' AND '".$mesFinal."' ".
						" AND entrada_general.impresion = 1 ".
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
						" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
						" LEFT JOIN tiempos_muertos ON impresion.id_impresion = tiempos_muertos.id_produccion ".
						" WHERE tipo = 1 AND fecha BETWEEN '".$mes."' AND '".$mesFinal."'   AND entrada_general.impresion = 1 GROUP BY tiempos_muertos.id_maquina ORDER BY tiempos_muertos.id_maquina ASC";
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
				" INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
				" INNER JOIN resumen_maquina_im ON impresion.id_impresion = resumen_maquina_im.id_impresion".
				" INNER JOIN maquina ON maquina.id_maquina = resumen_maquina_im.id_maquina".
				" WHERE fecha BETWEEN '".$mes."' AND '".$mesFinal."'  ".
				" GROUP BY resumen_maquina_im.id_maquina ".
				" ORDER BY maquina.area, maquina.numero ASC ";
$rExtruderM	=	mysql_query($qExtruderM);

/////////////////////// PRODUCCION  DIARIA /////////////////////////
$qExtruder	=	"SELECT SUM(total_hd) as produccion, ".
				"  COUNT(turno) AS turnos , fecha, ".
				" SUM(desperdicio_hd) as desperdicio1".
				" FROM entrada_general ".
				" INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
				" WHERE fecha BETWEEN '".$mes."' AND '".$mesFinal."'  ".
				" GROUP BY fecha ".
				" ORDER BY fecha ASC ";
$rExtruder	=	mysql_query($qExtruder);

/////////////////////// METAS POR DIA ///////////////////////////////
$qMetas	=	"SELECT * FROM meta WHERE mes = '".$meses."'  AND area = '2'";
$rMetas	=	mysql_query($qMetas);
$nMetas	=	mysql_num_rows($rMetas);

$qMetasM	=	"SELECT total FROM meta ".
			" INNER JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".
			" INNER JOIN maquina ON metas_maquinas.id_maquina = maquina.id_maquina ". 
			" WHERE mes = '".$mes."' AND meta.area IN(2) ORDER BY numero ASC";
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
		//$diferencia[$t]	=	( $dMetas['total_dia'] - $produccion[$t]);
		$diferencia[$t]	=	( ($dMetas['prod_hora']*24) - $produccion[$t]);
		
		///////TOTALES/////		
		
		$turnos_total	+=	$dExtruder['turnos'];
		$dias			=	$turnos_total/3;
		
		$total_dif			+=	$diferencia[$t];
		$total_pro			+=	$produccion[$t];
		//$total_meta			+= $dMetas['total_dia'];
		$total_meta			+= $dMetas['prod_hora']*24;
		$duro				+= $dExtruder['desperdicio2'];
		$tira				+= $dExtruder['desperdicio1'];
}

for($t = 0; $dExtruderM = mysql_fetch_assoc($rExtruderM);$t++){
		$produccionM[$t]	=	$dExtruderM['produccion'];
		$numero[$t]			=	$dExtruderM['numero'];
		$diferenciaM[$t]	=	( $metaMaquina[$t] - $produccionM[$t]);
		
		///////TOTALES/////		
		$total_difM			+=	$diferenciaM[$t];
		$total_proM			+=	$produccionM[$t];
		$total_metaM		+= 	$metaMaquina[$t];
}

$total_desp	=	$duro + $tira;
$por		=	($total_desp/$total_proM)*100;	
$Total_produccion	=	$total_pro/$dias;

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
	<div class="tablaCentrada titulos_i" id="tabla_reporte" align="center" >
	<p class="titulos_reportes" align="center">PRODUCCIÓN DIARIA CONTRA META Y PAROS<br> DEL AL</p><br>
<form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">
<table align="center" width="712" >
<tr>
	<td width="372" align="center" valign="top">
        <p align="center">TODO IMPRESION</p>
        <table width="320" align="center" style="margin:15px;">
            <tr>
                <td width="18%" align="center" valign="middle" ><h3>Fecha</h3></td>
                <td width="30%" align="center"><h3>Prod._Diaria</h3></td>
                <td width="29%" align="center"><h3>Meta_Diaria</h3></td>
                <td width="23%" align="center"><h3>Dif.</h3></td>
            </tr>
            <? for($t = 0; $t < sizeof($produccion);$t++){?>
            <tr <?=cebra($t)?>>
                <td class="style5" align="center"><?=fecha($fecha[$t])?></td>
                <td class="style5" align="right"><?=number_format($produccion[$t])?></td>
                <!--<td class="style5" align="right"><?=number_format($dMetas['total_dia']);?></td>-->
                <td class="style5" align="right"><?=number_format($dMetas['prod_hora']*24);?></td>
                <td class="style5" align="right"><?=number_format($diferencia[$t])?></td>
            </tr> 
            <? } ?>
            <tr style="background-color:#EEEEEE">
                <td class="style5" align="center"><h3>SUMAS</h3></td>
                <td class="style4" align="right"><?=number_format($total_pro)?></td>
                <td class="style4" align="right"><?=number_format($total_meta)?></td>
                <td class="style4" align="right"><?=number_format($total_dif)?></td>
            </tr> 
        </table>
	</td>
	<td width="322" valign="top">
        <p align="center">PAROS(motivos)</p>
        <table width="288" align="center" style="margin:15px;">
            <tr>
                <td width="38%" align="center" valign="middle" ><h3>Turnos_parados</h3></td>
                <td width="21%" align="center"><h3>Mantto.</h3></td>
                <td width="16%" align="center"><h3>Oper.</h3></td>
                <td width="25%" align="center"><h3>Otro</h3></td>
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
                <td class="style4" align="center" style="height:28px;"><? printf("%.1f",(($total_turnos/60)/60)/(24/3))?></td>
                <td class="style4" align="center"><? printf("%.1f",(($total_mannto/60)/60)/(24/3))?></td>
                <td class="style4" align="center"><? printf("%.1f",(($total_falta/60)/60)/(24/3))?></td>
                <td class="style4" align="center"><? printf("%.1f",(($total_otras/60)/60)/(24/3))?></td>
            </tr>             
        </table>
	</td>
</tr> 

<tr style="page-break-after:always;">
	<td colspan="6">
        <p>&nbsp;</p>
        <table width="90%" align="center" style="margin:30px 15px;">        
        	<tr>
            	<td width="25%"><h3>Total produccion: </h3></td>
            	<td width="25%"><? printf("%.2f",$Total_produccion*$dias)?></td>
            	<td width="25%"><h3>Porcentaje Desperdicio: </h3></td>
            	<td width="25%"><? printf("%.2f",$por)?></td>
            </tr>
        	<tr>
            	<td><h3>Promedio: </h3></td>
            	<td><? printf("%.2f" ,$Total_produccion)?></td>
            	<td><h3>Dias trabajados: </h3></td>
            	<td><?=$dias?></td>
            </tr>
        </table>
	</td>
</tr>
<tr>
	<td width="372" valign="top">
        <p align="center">PRODUCCION POR MAQUINA</p>
        <table width="340" align="center"  style="margin:15px;">
            <tr>
                <td width="18%" valign="middle" ><h3>Maquina</h3></td>
                <td width="30%" ><h3>Prod._Total</h3></td>
                <td width="29%" ><h3>Meta_Mensual</h3></td>
                <td width="23%" ><h3>Dif.</h3></td>
            </tr>
            <? for($t = 0; $t < sizeof($produccionM);$t++){?>
            <tr <?=cebra($t)?>>
                <td class="style5" align="center"><?=$numero[$t]?></td>
                <td class="style5" align="right"><?=$produccionM[$t]?></td>
                <td class="style5" align="right"><?=$metaMaquina[$t]?></td>
                <td class="style5" align="right"><?=$diferenciaM[$t]?></td>
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
	<td width="322" align="center" valign="top">
        <p align="center">TURNOS PARADOS x MAQUINA</p>
        <table width="286" align="center" style="margin:15px;" >
            <tr>
                <td width="37%" valign="middle" ><h3>Turnos_parados</h3></td>
                <td width="20%" ><h3>Mantto.</h3></td>
                <td width="14%" ><h3>Oper</h3></td>
                <td width="29%" ><h3>Otro</h3></td>
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
                <td class="style4" align="center" style="height:28px;"><? printf("%.1f",(($total_turnosM/60)/60)/(24/3))?></td>
                <td class="style4" align="center"><? printf("%.1f",(($total_manntoM/60)/60)/(24/3))?></td>
                <td class="style4" align="center"><? printf("%.1f",(($total_faltaM/60)/60)/(24/3))?></td>
                <td class="style4" align="center"><? printf("%.1f",(($total_otrasM/60)/60)/(24/3))?></td>
            </tr>                      
        </table>
	</td>
</tr>    
</table>
</form>
</div>
</div>
<? } ?>


<? if($_REQUEST['tipo'] == 43){ 

$fecha		=	fecha_tablaInv($_REQUEST['fecha_bd']);
$fecha_f	=	fecha_tablaInv($_REQUEST['fecha_bdf']);

	$qSm	=	" SELECT maquina.area, numero, maquina.id_maquina ". 
				" FROM entrada_general  ".
				" INNER JOIN impresion 					ON entrada_general.id_entrada_general 				= 	impresion.id_entrada_general ".
				" INNER JOIN resumen_maquina_im 		ON impresion.id_impresion 							= 	resumen_maquina_im.id_impresion ".
				" INNER JOIN detalle_resumen_maquina_im ON detalle_resumen_maquina_im.id_resumen_maquina_im = 	resumen_maquina_im.id_resumen_maquina_im".
				" INNER JOIN maquina 					ON resumen_maquina_im.id_maquina 					= 	maquina.id_maquina ".
				" WHERE fecha BETWEEN '".$fecha."' AND '".$fecha_f."' AND bd != 0 ".
				" GROUP BY resumen_maquina_im.id_maquina ORDER BY maquina.area ,maquina.numero ASC ";
	$rSM	=	mysql_query($qSm);
	$nSM	=	mysql_num_rows($rSM);
?>
<div align="center" id="tablaimpr">
	<div class="tablaCentrada titulos_i" id="tabla_reporte" align="center" >
	<p class="titulos_reportes" align="center">REPORTE DE BAJA DENSIDAD<br> DEL <?=fecha($fecha)?> AL <?=fecha($fecha_f)?></p><br>
    <? for($a=0;$dSM	=	mysql_fetch_assoc($rSM);$a++){		?>
    	<table width="400px" align="center">
            <tr>
                <td colspan="3" align="center"><h3><? if($dSM['area'] == 2) echo "IMPRESORA"; else echo "LINEA DE IMPRESION" ?> <?=$dSM['numero']?></h3></td>
            </tr>
            <tr>
                <td width="33%"><h3>Fecha</h3></td>
              <td width="35%"><h3>Orden</h3></td>
              <td><h3>Kg</h3></td>
            </tr>
            <?	 $qK	=	" SELECT fecha, resumen_maquina_im.id_maquina, orden_trabajo, detalle_resumen_maquina_im.kilogramos AS kilos ".
						" FROM resumen_maquina_im ".
						" INNER JOIN impresion 					ON impresion.id_impresion 						= 	resumen_maquina_im.id_impresion ".
						" INNER JOIN entrada_general 			ON entrada_general.id_entrada_general 			= 	impresion.id_entrada_general".
						" INNER JOIN detalle_resumen_maquina_im 	ON	resumen_maquina_im.id_resumen_maquina_im 	= 	detalle_resumen_maquina_im.id_resumen_maquina_im ".
						" WHERE resumen_maquina_im.id_maquina = ".$dSM['id_maquina']."  AND bd = 1 ORDER BY fecha DESC";
				$rK	=	mysql_query($qK);
				for($b=0;$dk	=	mysql_fetch_assoc($rK);$b++){?>
            <tr <?=cebra($b)?>>
                <td><?=fecha($dk['fecha'])?></td>
                <td align="center"><?=$dk['orden_trabajo']?></td>
                <td align="right"><?=number_format($dk['kilos'],2)?></td>
            </tr>
            <? } ?>
		</table>
        <br><br>
    <? } ?>
	</div>
</div>
<? } ?>


<? if($_REQUEST['tipo'] == 49){ 

///////////////////////////SELECCION DE MAQUINAS ///////////////////////////
$qMaquinas	=	"SELECT * FROM maquina WHERE area IN(2,3) ORDER BY area, numero ASC ";
$rMaquinas	=	mysql_query($qMaquinas);
$nMaquinas	=	mysql_num_rows($rMaquinas);

for($t = 0; $dMaquinas = mysql_fetch_assoc($rMaquinas);$t++){
	$numero_maq[$t] = $dMaquinas['numero'];
}
//////////////////////////SELECCION DE OPERADORES///////////////////////////
/*$qMaquinas	=	' SELECT GROUP_CONCAT(nombre ORDER BY nombre ASC SEPARATOR "<br>" ) AS nombre  FROM operadores  '.
				" WHERE puesto LIKE '%oper%' AND operadores.area = 1 GROUP BY operadores.rol ORDER BY operadores.rol DESC ";
*/
$qMaquinas	=	" SELECT nombre FROM supervisor  WHERE area2= 1  AND activo = 0 ORDER BY rol ASC ";

$rMaquinas	=	mysql_query($qMaquinas);
$nMaquinas	=	mysql_num_rows($rMaquinas);
for($t = 0 ; $dMaquinas = mysql_fetch_assoc($rMaquinas) ;$t++){
	$nombre 		= 	explode(" ",$dMaquinas['nombre']);
	$grupos[$t]		=	$nombre[0].' '.$nombre[1]{0}.'.';	
}

///////////////////////CARGAMOS LAS MAQUINAS CON SU PRODUCCION//////////////////////
$qProd	=	' SELECT SUM(subtotal) AS total, rol, resumen_maquina_im.id_maquina  FROM resumen_maquina_im '.
			' INNER JOIN impresion ON resumen_maquina_im.id_impresion = impresion.id_impresion '.
			' INNER JOIN entrada_general ON impresion.id_entrada_general = entrada_general.id_entrada_general '.
			' LEFT JOIN maquina ON maquina.id_maquina = resumen_maquina_im.id_maquina '.
			' WHERE impresion = 1 AND  YEAR(fecha) = "'.$_REQUEST['ano_rpr'].'" AND MONTH(fecha) = "'.$_REQUEST['mes_rpr'].'" '.
			' GROUP BY id_maquina, rol ORDER BY rol, maquina.area, numero ASC';

$rProd	=	mysql_query($qProd);
$nProd	=	mysql_num_rows($rProd);

$qDias =	" SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_im.id_maquina, rol,  maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_im "	.
			' INNER JOIN impresion 			ON resumen_maquina_im.id_impresion 		= impresion.id_impresion '.
			" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 	= impresion.id_entrada_general ".
			" INNER JOIN maquina 			ON resumen_maquina_im.id_maquina 		= maquina.id_maquina ".
			" WHERE subtotal != 0 AND YEAR(fecha) = '".$_REQUEST['ano_rpr']."' AND MONTH(fecha) = '".$_REQUEST['mes_rpr']."' AND  impresion = 1 ".
			" GROUP BY maquina.id_maquina".
			" ORDER BY maquina.area, maquina.numero ASC ";
$rDias	=	mysql_query($qDias);

	$qId	=	"SELECT id_maquina, area FROM maquina WHERE area in (2,3) ORDER BY area, numero ASC";
	$rId	=	mysql_query($qId);
	for($r=0;$mId	=	mysql_fetch_assoc($rId);$r++){
			$maqID[$r]	=	$mId['id_maquina'];
			$areaMaq[$r]	=	$mId['area'];
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
	<div class="tablaCentrada titulos_i" id="tabla_reporte" align="center" >
	<p class="titulos_reportes" align="center">PRODUCCION IMPRESION CORRESPONDIENTE AL MES DE <?=strtoupper($mes[$_REQUEST['mes_rpr']])?> DE <?=$_REQUEST['ano_rpr'] ?></p><br><br>
<? if($nProd > 0){ ?>
        <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">
                    <? for($limite = 12, $t=0,$a=0,$c=$limite,$d=0, $e=0; $e < sizeof($numero_maq)/$limite;  $c=$c+$limite, $e++, $a=$a-4 ){ ?>
                    <table  align="center" width="85%">
						<tr>
                            <td width="208" align="center"><h3>Supervisor</h3></td>
	            		  	<? for(;$d < $c ;$d++){
							if($numero_maq[$d] != ""){ ?>
                            <td width="110" align="center"><h3><?=($areaMaq[$d] == 2)?"Imp_":"Ln_"?><?=$numero_maq[$d]?></h3></td>
						  	<? } } ?>
                        </tr>
                          <? for(;$a<sizeof($grupos); $a++){ 
						   	if($t < sizeof($numero_maq)){?>
                          <tr >
                            <td  class="style5" align="left" style="font-size:9px"><?=$grupos[$a]?></td>
                            <? for(;$t < $c; $t++){
							if($produc[$a][$t] != ""){ ?>
                            <td height="25%" valign="middle"  align="center"><?=number_format($produc[$a][$t]);?></td>
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
<? } ?>
</div></div>
<? } ?>

<?  if($_REQUEST['tipo'] == 55 ){ 

$fecha	=	fecha_tablaInv($_REQUEST['fecha_des']);
$fechaFin	=	fecha_tablaInv($_REQUEST['fecha_des_f']);

$qReporte	=	"SELECT SUM(desperdicio_hd) AS tira, SUM(desperdicio_bd) AS duro, fecha, COUNT(DISTINCT turno) AS turnos, 
				SUM(total_hd) AS total, SUM(total_bd) AS total2 ".
				" FROM entrada_general ".
				" LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
				" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."'".
				" GROUP BY fecha ORDER BY fecha ASC";
$rReporte	=	mysql_query($qReporte);
	for($a=0;$dReporte	=	mysql_fetch_assoc($rReporte);$a++){
		$total[$a]	=	$dReporte['total'];
		$total2[$a]	=	$dReporte['total2'];
		$tira[$a]	=	$dReporte['tira'];
		$duro[$a]	=	$dReporte['duro'];
		$fecha_re[$a]	=	$dReporte['fecha'];
		$turnos[$a]	=	$dReporte['turnos'];
	}										

?>
<div align="center"  id="tablaimpr">
   	 	<div class="tablaCentrada titulos_i" align="center" id="tabla_reporte" >
        <p class="titulos_reportes" align="center">DESPERDICIOS IMPRESION DIARIOS<br /><?=$mes[$_REQUEST['mes_maq']]?> de <?=$_REQUEST['ano_maq']?><br /></p>
          <form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
		  <table width="80%">
          <tr valign="top">
          	 <td colspan="5">
				<table align="left" width="99%">
					<tr>
						<td  colspan="2" align="center"><h3>Desperdicios</h3></td>
					</tr>
					<tr>
						<td width="99"  align="center"><h3>Fecha</h3></td>
                    	<td width="109" align="center"><h3>Desperdicio</h3></td>
                        <td width="97" align="center"><h3>Turnos</h3></td>
                        <td width="77" align="center"><h3>%</h3></td>
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
				</table>
			</td>
			<td width="50%" align="center" >
                <table align="center" width="84%">
		  <tr>
					<td colspan="4" align="center"><h3>Baja DENSIDAD</h3></td>
				</tr>
				<tr>
				  <td width="88"  align="center"><h3>Fecha</h3></td>
               	  <td width="93" align="center"><h3>Desperdicio</h3></td>
                  <td width="75" align="center"><h3>Turnos</h3></td>
                  <td width="57"  align="center"><h3>%</h3></td>
				</tr>
				<? 
				$tiraTotal2 = "";
				for($a=0;$a<sizeof($turnos);$a++){?>
				<tr <? cebra($a)?>>
                        <td align="center"><? fecha($fecha_re[$a])?></td>
                        <td align="right" class="style5"><?=number_format($duro[$a])?></td>
                 		<td align="right" class="style5"><?=number_format($turnos[$a])?></td>
                        <td align="right" class="style5"><? @printf("%.2f" ,($duro[$a]/$total2[$a])*100)?>%</td>
               </tr>                
                    <? $tiraTotal2	+= $duro[$a];
					} ?>
                    <tr>
                    	<td><h3>Totales: </h3></td>
						<td align="right"><?=number_format($tiraTotal2)?></td>
						<td align="right"><?=number_format($turnosTotal/3)?></td>
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
    <p><input type="button" value="Imprimir" class="link button1" /><br /><br /><br /></p></td>
</tr>

</body>