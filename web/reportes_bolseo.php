
<? if($_REQUEST['tipo'] == 16  ){  

$tipo = $_REQUEST['tipo'];

if($tipo == 16) $area = 4;

$fecha	=	fecha_tablaInv($_REQUEST['fecha_incidencia']);
$fechaFin	=	fecha_tablaInv($_REQUEST['fecha_incidencia_f']);


if($area == 4){
 $qReportes	=	"SELECT id_bolseo, turno, id_supervisor, observaciones, fecha FROM bolseo ".
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
	<div class="tablaCentrada titulos_b" align="center" id="tabla_reporte" >
	<p class="titulos_reportes" align="center">REPORTE DE INCIDENCIAS BOLSEO<br /></p><br />
        <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">
                  	<? for($b = 1 ; $dReportes	=	mysql_fetch_row($rReportes); $b++){
						if($dReportes[1] == 1) $turno = "MATUTINO";	
						if($dReportes[1] == 2) $turno = "VESPERTINO";	
						if($dReportes[1] == 3) $turno = "NOCTURNO";	
					 ?>			
		<table width="80%" style="background-color:#eee; padding:10px;">
        <tr>
        	<td>			
            <table width="100%" align="center" style="background-color:#FFFFFF; z-index:200">
            <tr>
            	<td align="left" width="14%"><h3>Fecha : </h3></td>
                <td ><?=fecha($dReportes['4'])?></td>
                <td colspan="2" align="center" class="style7"><h3>TURNO</h3></td>
                <td colspan="2"><?=$turno?></td>
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
				  <td width="14%" align="center"><h3>Maquina</h3></td>
				  <td width="12%" align="center"><h3>Fallo_Elec.</h3></td>
				  <td width="11%" align="center"><h3>Falta_Pers.</h3></td>
				  <td width="9%" align="center"><h3>Mantto.</h3></td>
				  <td width="10%" align="center"><h3>Otras</h3></td>
				  <td width="34%" align="center"><h3>Observaciones</h3></td>
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
								if($dTiempo[$opcion]		 	== '0' || $dTiempo[$opcion] == '') 		$mallas		 	= ""; else if($dTiempo[$opcion] != '0')  $mallas			= "SI";
								
								if($dTiempo['observaciones']	== '0') 		$observaciones	= "&nbsp;"; else $observaciones	=	$dTiempo['observaciones'];
								
								if($fallo == "" && $mantenimiento == "" && $falta_personal == "" && $otras == "" && $mallas == "" && $observaciones == ""){
								$a = $a + 1;
								}else {
								?> 
                                	
                                <tr <? if(bcmod($a,2)==0) echo  ''; else echo 'bgcolor="#DDDDDD"';  ?> >
                                  <td align="left" class="style7"><?=$dTiempo['marca'].'_'.$dTiempo['numero'];?></td>
                                  <td align="center"><?=$fallo?></td>
                                  <td align="center"><?=$falta_personal?></td>
                                  <td align="center"><?=$mantenimiento?></td>
                                  <td align="center"><?=$otras?></td>
                                  <td class="style5"><?=$observaciones?></td>
                                </tr>
                                <? } 
							}?> 
                   <tr>
                   		<td colspan="2"  align="right"><h3>Observaciones Generales:</h3></td>
                    	<td colspan="7" class="style5" align="justify"><?=$dReportes[3]?></td>
                  </tr>
            </table>
            </td></tr>
            </table>
            <br /><br />
	            <? } ?>    
            </form>
            <br /><p><input type="button" name="pfd" class="styleTabla" id="logo" value="Formato PDF" onClick="genera()"></p><br />
         
        </div>
</div>
<? } ?>


<? if($_REQUEST['tipo'] == 38){ 

	$meMeta	=	$_REQUEST['mes_bols'];
	$anho	=	$_REQUEST['ano_bols'];
	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$meses	=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia = UltimoDia($anho,$meMeta);
	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

///////////////////////////PROMEDIO META////////////////////////// 
$qMeta	=	"SELECT * FROM meta WHERE mes = '".$meses."' AND area = 3";
$rMeta	=	mysql_query($qMeta);
$nMeta	=	mysql_num_rows($rMeta);

if($nMeta > 0) { 
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
$qRol	=	"SELECT 
			COUNT(turno) 	AS turnos, 
			SUM(millares) 	AS millares, 
			SUM(kilogramos) AS kilogramos,
			SUM(dtira) 		AS tira,
			SUM(segundas) 	AS segunda,
			turno, rol
			FROM bolseo ".
			" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."' AND repesada = 1".
			" GROUP BY id_supervisor, turno, rol ORDER BY  rol, turno  ASC ";
				
$rRol	=	mysql_query($qRol);
$nRol	=	mysql_num_rows($rRol);

$qGrupo	=	"SELECT nombre, rol FROM supervisor ".
			" WHERE area3 = 1 ORDER BY rol ASC";
$rGrupo	=	mysql_query($qGrupo);



for($a=0 ;$dGrupo	=	mysql_fetch_assoc($rGrupo); $a++){
	$nombres		=	explode(" ",$dGrupo['nombre']);
	$sup_nom[$a]	=	$nombres[0];
	
	$qFaltas	=	" SELECT COUNT(asistencia) AS faltas FROM operadores INNER JOIN asistencias ON operadores.id_operador = asistencias.id_operador ".
					" WHERE asistencia = 'f' AND operadores.rol = '".$dGrupo['rol']."' AND area = 2 ORDER BY operadores.id_operador ASC ";	
    $rFaltas 	=	mysql_query($qFaltas);
	
	
	
	for($c = 0; $dFaltas = mysql_fetch_assoc($rFaltas); $c++){
		$faltas[$a]	=	$dFaltas['faltas'];
		
		$total_faltas += $faltas[$a];
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
<div align="center" id="tablaimpr">
	<div align="center" class="tablaCentrada titulos_b" id="tabla_reporte" style="width:700px;">
	<p class="titulos_reportes titulos_b" align="center">PRODUCCION-META POR TURNO Y GRUPO <?=strtoupper($mes[$meses])?> DEL <?=$ano?></p>
    <? if($nRol > 0 && $nMeta > 0 ){  ?>
    <form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&tipo=<?=$_REQUEST['tipo']?>&mes_bols=<?=$_REQUEST['mes_bols']?>&ano_bols=<?=$_REQUEST['ano_bols']?>&modificar" method="post">
    <br /><p align="center"><b>MILLARES</b></p><br />
    <p align="left">
    <table width="500px" align="center" >
   	    <tr>
          <td width="18%" align="center"><h3>Turno</h3></td>
       	  <td width="21%" align="center"><h3>1</h3></td>
       	  <td width="21%" align="center"><h3>2</h3></td>
       	  <td width="19%"  align="center"><h3>3</h3></td>
       	  <td width="21%" align="center"><h3>TOTAL</h3></td>
        </tr>        
        <tr align="center">
           	  <td><h3>META</h3></td>
           	  <td class="style5"><?=number_format($mMat)?></td>
           	  <td class="style5" bgcolor="#DDDDDD"><?=number_format($mVes)?></td>
           	  <td class="style5"><?=number_format($mNoc)?></td>
           	  <td class="style5" bgcolor="#DDDDDD"><?=number_format($metaDia)?></td>
          </tr> 
    </table>
    </p>
    <br />
    <p align="left">
    <table width="700px" >
  		<tr>
            <td width="10%" align="center"><h3>Nombre</h3></td>
            <td width="11%" align="center"><h3>T_1</h3></td>
            <td width="8%" align="center"><h3>Meta</h3></td>
            <td width="11%" align="center"><h3>T_2</h3></td>
            <td width="10%" align="center"><h3>Meta</h3></td>
            <td width="11%" align="center"><h3>T_3</h3></td>
            <td width="8%" align="center"><h3>Meta</h3></td>
            <td width="13%" align="center"><h3>META_TOTAL</h3></td>
            <td width="8%" align="center"><h3>PROD</h3></td>
            <td width="10%" align="center"><h3>DIF</h3></td>
            <td width="10%" align="center"><h3>Total_turnos</h3></td>
  		</tr>
  <? 
		  	for($a = 0 ;$dRol	=	mysql_fetch_assoc($rRol);$a++){
				$turnos[$a]			=	array($dRol['turnos']);
				$millares[$a]		=	array($dRol['millares']);
				$kilogramos[$a]		=	array($dRol['kilogramos']);
				$tira[$a]			=	array($dRol['tira']);
				$segunda[$a]		=	array($dRol['segunda']);
			}  
		
			for( $b = 0 ; $b < 4 ; $b++){?>
  		<tr <? if(bcmod($b,2) == 0) echo 'bgcolor="#DDDDDD"';?>>
    		<td align="left" class="style5"><b><?=$sup_nom[$b]?></b></td>
    <?
			$Total_pr	=	0;
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
    		<td class="style5" align="center"><?=$turnos[$meta][0]?></td>
   	 		<td class="style5" align="center"><?=number_format($turno1 = $turnos[$meta][0] * $multi);
			$t_total[$b]	+=	$turnos[$meta][0];
			$total += $turno1;			
			$Total_pr	+=	$millares[$meta][0];
			}?></td>
    		<td class="meta_totales" 	align="center"><?=number_format($total)?></td>
    		<td class="prod_totales" 	align="center"><?=number_format($Total_pr)?></td>
    		<td class="dif_totales" 	align="center"><?=number_format($diferencia = $total - $Total_pr)?></td>
     		<td class="dif_totales" 	align="center"><?=$t_total[$b]; ?></td>
  		</tr>
  <? 
		  $total_m += $total; 
		  $total_des[$b] 	= array($total_desp);
		  $totalmetas[$b] 	= array($total);
		  $totalprod[$b] 	= array($Total_pr);

		  $total_p += $Total_pr; 
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
	</p>
	<br /><p align="center"><b>KILOGRAMOS</b></p><br />
	<p align="left">
    <table width="500px">
        <tr>
            <td width="10%" align="center"><h3>Turno</h3></td>
            <td width="10%" align="center"><h3>1</h3></td>
            <td width="10%" align="center"><h3>2</h3></td>
            <td width="9%"  align="center"><h3>3</h3></td>
            <td width="13%" align="center"><h3>TOTAL</h3></td>
        </tr>        
        <tr align="center">
            <td><h3>META</h3></td>
            <td class="style5"><?=number_format($mMatk)?></td>
            <td class="style5" bgcolor="#DDDDDD"><?=number_format($mVesk)?></td>
            <td class="style5"><?=number_format($mNock)?></td>
            <td class="style5" bgcolor="#DDDDDD"><?=number_format($metaDiak)?></td>
        </tr> 
	</table>
	</p>
	<br />
	<p align="left">
    <table width="700px">
    	<tr>
            <td width="8%" align="center"><h3>Nombre</h3></td>
       	    <td width="5%" align="center"><h3>T_1</h3></td>
       	    <td width="9%" align="center"><h3>Meta</h3></td>
       	    <td width="6%" align="center"><h3>T_2</h3></td>
       	    <td width="9%" align="center"><h3>Meta</h3></td>
       	    <td width="5%" align="center"><h3>T_3</h3></td>
       	    <td width="10%" align="center"><h3>Meta</h3></td>
       	    <td width="12%" align="center"><h3>META_TOTAL</h3></td>
       	    <td width="12%" align="center"><h3>PROD</h3></td>
       	    <td width="10%" align="center"><h3>DIF</h3></td>
       	    <td width="14%" align="center"><h3>Total_turnos</h3></td>
		</tr>
          <? 
			for( $b = 0 ; $b < 4 ; $b++){?>
		<tr <? if(bcmod($b,2) == 0) echo 'bgcolor="#DDDDDD"';?>>
          	<td align="center" class="style5"><b><?=$sup_nom[$b]?></b></td>
            <?
			$Total_prk	=	0;
			$totalk 	= 	0;
			$Total_segk		=	0;
			$Total_tirak	=	0;
			for($c = 0; $c < 3; $c++){ 
				if($b == 0) $u = 0;
				if($b == 1) $u = 2;
				if($b == 2) $u = 4;
				if($b == 3) $u = 6;
				
				$metak = $b+$c+$u;
				if($c == 0) $multik = $mMatk;
				if($c == 1) $multik = $mVesk;
				if($c == 2) $multik = $mNock;
				
			?>
            <td class="style5" align="center"> <?=$turnos[$metak][0]?></td>
            <td class="style5" align="center"><?=number_format($turno1k = $turnos[$metak][0] * $multik);
			$totalk += $turno1k;			
			$t_totalk[$b]	+=	$turnos[$metak][0];
			$Total_prk		+=	$kilogramos[$metak][0];
			$Total_segk		+=	$segunda[$metak][0];
			$Total_tirak	+=	$tira[$metak][0];
			
			}?></td>                      
   			<td class="meta_totales" 	align="center"><?=number_format($totalk)?></td>
            <td class="prod_totales" 	align="center"><?=number_format($Total_prk)?></td>
            <td class="dif_totales" 	align="center"><?=number_format($diferenciak = $totalk - $Total_prk)?></td>
     		<td class="dif_totales" 	align="center"><?=$t_totalk[$b]; ?></td>
		</tr>
          <? 
		  $total_mk += $totalk; 
		  $total_desk[$b] 	= array($total_despk);
		  $totalmetask[$b] 	= array($totalk);
		  $totalprodk[$b] 	= array($Total_prk);
		  $totalsegk[$b] 	= array($Total_segk);
		  $totaltirak[$b] 	= array($Total_tirak);
		
		  $Total_tira_por	+= current($totaltirak[$b]);
		  $total_pk += $Total_prk; 
		  $total_dk += $diferenciak; 
		  
		  } ?>                 
		<tr bgcolor="#DDDDDD">
			<td colspan="7" align="right"><h3>TOTALES: </h3></td>
            <td class="meta_totales" 	align="center"><?=number_format($total_mk) ?></td>
       	  	<td class="prod_totales" 	align="center"><?=number_format($total_pk) ?></td>
       	  	<td class="dif_totales" 	align="center"><?=number_format($total_dk) ?></td>
   	    <? for($a=0;$a<4;$a++){ $total_ttk	+=	$t_totalk[$a];} ?>
    		<td class="dif_totales" 	align="center"><? printf("%.2f",$total_ttk/3); ?></td>    
        </tr>                     
	</table>     
    </p>
    <br />
    <p align="left">
	<table width="700px">    
		<tr>
			<td width="82"></td>
			<td width="89" align="center"><h3>MILLARES</h3></td>
			<td width="91" align="center"><h3>KILOGRAMOS	</h3></td>
			<td colspan="5" align="center"></td>
		</tr>
		<tr>
			<td><h3>&nbsp;<br>Nombre</h3></td>
			<td align="center"><h3>40%<br>Meta</h3></td>
			<td align="center"><h3>40%<br>Meta</h3></td>
			<td width="59" align="center"><h3>12%<br>Seg</h3></td>
			<td width="62" align="center"><h3>5%<br>Tira</h3></td>
			<td width="65" align="center"><h3>-3%<br>Faltas</h3></td>
			<td width="144" align="center"><h3>3%<br>Limpieza y Actitud</h3></td>
			<td width="72" align="center"><h3>100%<br>TOTAL</h3></td>
        </tr>                
                <? 
					for($a = 0;$a < 4; $a++){ 
					$Meta1	=	(((current($totalprod[$a])/current($totalmetas[$a]))*100)*.40);
					$Meta2	=	(((current($totalprodk[$a])/current($totalmetask[$a]))*100)*.40);
					$Seg_1	=	(($dMeta['porcentaje_segunda']/(current($totalsegk[$a])/current($totalprodk[$a])))*.12);
					$Tira1	=	(($dMeta['porcentaje_desp']/(current($totaltirak[$a])/current($totalprodk[$a])))*.05);	
					$falta	=	((($faltas[$a]/$total_faltas)*(-.03)) *100);
					
					$total_t	=	$Meta1+$Meta2+$Seg_1+$Tira1+$falta;
				?>
        <input value="<?=round($total_t,2)?>" type="hidden" id="pre_<?=$a?>" />
		<tr <? if(bcmod($a,2) == 0) echo 'bgcolor="#DDDDDD"';?>>
			<td class="style5"><b><?=$sup_nom[$a]?></b></td>
			<td class="style5" align="right" ><? printf("%.2f",$Meta1)?>%</td>
			<td class="style5" align="right" ><? printf("%.2f",$Meta2)?>%</td>
            <td class="style5" align="right" ><? printf("%.2f",$Seg_1)?>%</td>
            <td class="style5" align="right" ><? printf("%.2f",$Tira1)?>%</td>
            <td class="style5" align="right" ><? printf("%.2f",$falta)?>%</td>
            <td class="style5" align="center" ><input type="text" class="style5" style=" text-align:right" id="porcentaje_<?=$a?>" name="porcentaje[]" align="right" size="4" value="<?=$_REQUEST['porcentaje'][$a]?>" onChange="suma_limp(<?=$a?>); document.form.submit();" />%</td>
            <td class="style5" align="right" ><input readonly="readonly" class="style5" type="text" style=" text-align:right; <? if(bcmod($a,2) == 0) echo "background-color:#dddddd" ?>; border: 0px;" size="4" value="<? (isset($_REQUEST['modificar']))? printf( "%.2f", $_REQUEST['resultado_'][$a]): printf( "%.2f",$total_t)?>"  id="resultado_<?=$a?>" name="resultado_[]" />
			
			%</td>
		</tr>
                <? } ?>        
	</table>
    </p>
    <br />
    <p align="left">
    <table width="700px">    
        <tr>
            <td width="92"></td>
            <td colspan="3" align="center"><H3>MILLARES</H3></td>
            <td colspan="3" align="center" class="titulos_de">TIRA</td>
            <td colspan="3" align="center" class="titulos_de">SEGUNDA</td>
            <td width="62" rowspan="2"  align="center" valign="bottom"><h3>FALTAS</h3></td>
        </tr>
        <tr>
            <td align="center"><h3>Nombre</h3></td>    
            <td width="63" align="center"><h3>META</h3></td>
            <td width="63" align="center"><h3>REAL</h3></td>
            <td width="46" align="center"><h3>%</h3></td>                          
            <td width="64" align="center" class="titulos_de">META</td>
            <td width="65" align="center" class="titulos_de">REAL</td>
            <td width="44" align="center" class="titulos_de">%</td>
            <td width="58" align="center" class="titulos_de">META</td>
            <td width="55"  align="center" class="titulos_de">REAL</td>
            <td width="40" align="center" class="titulos_de">%</td>
        </tr>  
                    <? for($a=0;$a<4; $a++){ ?>
                        <tr <? if(bcmod($a,2) == 0) echo 'bgcolor="#DDDDDD"';?>>
                        	<td align="left" class="style5"><b><?=$sup_nom[$a]?></b></td>
                            <td align="center" class="style5"><?=number_format(current($totalmetas[$a]))?></td>
                            <td align="center" class="style5"><?=number_format(current($totalprod[$a]))?></td>
                            <td align="center" class="style5"><? printf("%.2f", ((current($totalprod[$a])/current($totalmetas[$a]))*100));?>%</td>
                            <td align="center" class="style5"><? printf("%.2f",$dMeta['porcentaje_desp'])?>%</td>
                            <td align="center" class="style5"><? printf("%.2f", ((current($totaltirak[$a])/current($totalprodk[$a]))*100))?>%</td>
                            <td align="center" class="style5"><? printf("%.2f", ($dMeta['porcentaje_desp']/(current($totaltirak[$a])/current($totalprodk[$a]))))?>%</td>
                            <td align="center" class="style5"><?=$dMeta['porcentaje_segunda']?>%</td>
                            <td align="center" class="style5"><? printf("%.2f", ((current($totalsegk[$a])/current($totalprodk[$a]))*100))?>%</td>
                            <td align="center" class="style5"><? printf("%.2f", ($dMeta['porcentaje_segunda']/(current($totalsegk[$a])/current($totalprodk[$a]))))?>%</td>
                            <td align="center" class="style5"><?=$faltas[$a]?></td>
						</tr>
						<?  } ?>
						<tr>
                        	<td colspan="10"></td>
                            <td align="center" class="style5" bgcolor="#DDDDDD"><?=$total_faltas?> </td>
                        </tr>
              </table>
 </p>
 <p align="left">             
<table width="350px">    
	<tr>
		<td width="90"></td>
        <td colspan="3" align="center"><H3>Kilogramos</H3></td>
	</tr>
	<tr>
    	<td><h3>Nombre</h3></td>
		<td width="87" align="center"><h3>META</h3></td>
		<td width="68" align="center"><h3>REAL</h3></td>
		<td width="85" align="center"><h3>%</h3></td> 
    </tr>
	<? for($a=0;$a<4; $a++){ ?>
	<tr <? if(bcmod($a,2) == 0) echo 'bgcolor="#DDDDDD"';?>>
    	<td align="left" class="style5"><b><?=$sup_nom[$a]?></b></td>
   		<td align="center" class="style5"><?=number_format(current($totalmetask[$a]))?></td>
		<td align="center" class="style5"><?=number_format(current($totalprodk[$a]))?></td>
		<td width="85" align="center" class="style5"><? printf( "%.2f", ((current($totalprodk[$a])/current($totalmetask[$a]))*100));?>%</td>
	<? } ?>
	</tr>
</table>
</p>
         </form>
         <? } else { ?>
<table width="100%" align="center">
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
        </div>
</div>
<? } ?>



 <?  if($_REQUEST['tipo'] == 34 ){ 
 
 
 
 	$meMeta =	$_REQUEST['mes_pm'];
	$anho 	=	$_REQUEST['anho_pm'];
	$mesMetacero	=	num_mes_cero($anho.'-'.$meMeta.'-01');
	$mesMeta	=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia = UltimoDia($anho,$meMeta);
	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;
 	
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
<div align="center" id="tablaimpr">
  <div class="tablaCentrada titulos_b" id="tabla_reporte">
	<p class="titulos_reportes titulos_b" align="center">PPAROS MENSUALES EN BOLSEO <br /><?=$mes[$meMeta]?> DEL <?=$anho?></p><br /><br />

       	  <table width="71%" align="center">
      <tr align="center">
                <td width="27%"><h3>Maquinas</h3></td>
            <td width="22%"><h3>Mantenimiento</h3></td>
            <td width="19%"><h3>Falta_de_Opr</h3></td>
            <td width="17%"><h3>Otra</h3></td>
            <td width="15%" colspan="2"><h3>Total</h3></td> 
		    </tr>
              <? 	for($c++; $dSelectBolseo	=	mysql_fetch_assoc($rSelectBolseo); $c++){	
			  			$Total_maquinaBolseo 	=	$dSelectBolseo['mantenimiento']+$dSelectBolseo['falta_personal']+$dSelectBolseo['fallo_electrico'];
			  			$TotalOb	=	($dSelectBolseo['fallo_electrico']+$dSelectBolseo['otras']);
						?>
              <tr <? cebra($c)?>>
                <td ><?=$dSelectBolseo['numero']?>_<?=$dSelectBolseo['marca']?></td>      
                <td align="center" class="style5"><? printf("%.2f ",$dSelectBolseo['mantenimiento']/(24/3))?></td>      
                <td align="center" class="style5"><? printf("%.2f ",$dSelectBolseo['falta_personal']/(24/3))?></td>      
                <td align="center" class="style5"><? printf("%.2f ",$TotalOb/(24/3),1)?></td>      
                <td colspan="2" align="center" class="style5"><? printf("%.2f ",($TotalOb+$dSelectBolseo['falta_personal']+$dSelectBolseo['mantenimiento'])/(24/3))?></td>      
			  </tr>
             <?	 
			 	   $turnos_manB		+= $dSelectBolseo['mantenimiento'];
			 	   $turnos_perB		+= $dSelectBolseo['falta_personal'];
				   $turnos_falloB	+= $TotalOb;			 
			 	   $Total_extruderB	+= $Total_maquinaBolseo;
				   $turno_totalB	=	$turnos_manB + $turnos_perB + $turnos_falloB;
			 ?>
             <? } ?>  
              <tr>
                <td align="right" ><h3>TURNOS PARADOS:</h3></td>      
                <td align="center" class="style4"><? printf("%.2f ",$turnos_manB/(24/3))?></td>      
                <td align="center" class="style4"><? printf("%.2f ",$turnos_perB/(24/3))?></td>      
                <td align="center" class="style4"><? printf("%.2f ",$turnos_falloB/(24/3))?></td>      
                <td colspan="2" align="center" class="style4"><? printf("%.2f ",$turno_totalB/(24/3))?></td>      
			  </tr>
              <tr bgcolor="#EEEEEE">
                <td align="right"><h3>DIAS:</h3></td>      
                <td align="center" class="style5"><? printf("%.2f ",($turnos_manB/24)/$nBolseo)?></td>      
                <td align="center" class="style5"><? printf("%.2f ",($turnos_perB/24)/$nBolseo)?></td>      
                <td align="center" class="style5"><? printf("%.2f ",($turnos_falloB/24)/$nBolseo)?></td>      
                <td colspan="2" align="center" class="style5"><? printf("%.2f ",($Total_extruderB/24)/$nBolseo)?></td>      
		    </tr>  
          </table><br /><br />
  </div>
</div><? }?>





<? if( $_REQUEST['tipo'] == 11  ){

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
		 ?>

<script type="text/javascript" language="javascript">
function genera()
{
document.metas.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?><? if(isset($_REQUEST['tiempo'])){ ?>&tiempo=<?=$_REQUEST['tiempo']; } ?><? if(isset($_REQUEST['anho'])){ ?>&anho=<?=$_REQUEST['anho']; } ?><? if(isset($_REQUEST['mes'])){ ?>&mes=<?=$_REQUEST['mes']; } ?><? if(isset($_REQUEST['desde'])){ ?>&desde=<?=$_REQUEST['desde']; }?><? if(isset($_REQUEST['hasta'])){ ?>&hasta=<?=$_REQUEST['hasta']; }?>";
document.metas.submit();

}
</script>

<div align="center" id="tablaimpr">
	<div class="tablaCentrada titulos_b" id="tabla_reporte">
	<p class="titulos_reportes titulos_b" align="center">PRODUCCION DIARIA BOLSEO <br /><?=$mes[$mes_fecha]?> DEL <?=$ano?></p>
<form name="metas" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=nuevo" id="super" method="post" >
<br />
<br />

    <? if($nMaquinas2 == 1 ){ 
	 $dMaquinas2	= mysql_fetch_row($rMaquinas2); ?>
  <table width="80%" align="center"> 
  <tr >
    <td width="85" align="center"  class="style7" style="color:#FFFFFF"><h3>Fecha</h3></td>
    <td width="81"  colspan="1" align="center" class="style5"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dMaquinas2[2]); ?></td>
  </tr>
  <tr>
    <td align="center"  class="style7" ><h3>Turno</h3></td>
    <td align="center"  class="style7" ><h3>Prod._Kg</h3></td>
    <td width="79" align="center"  class="style7" ><h3>Meta_Kg</h3></td>
    <td width="75" align="center"  class="style7" ><h3>Dif</h3></td>
    <td width="90" align="center"  class="style7" ><h3>Produccion</h3></td> 
    <td width="80" align="center"  class="style7" ><h3>Kg/H</h3></td>  
    <td width="90" align="center"  class="style7" ><h3>Prod._Mi</h3></td>
    <td width="87" align="center"  class="style7" ><h3>Meta_Mi</h3></td>
    <td width="93" align="center"  class="style7" ><h3>Dif.</h3></td> 
    <td width="94" align="center"  class="style7" ><h3>Produccion</h3></td>
    <td align="center"  class="style7" ><h3>Mi/H</h3></td>
  </tr>  

  <tr>
    <td width="85" align="right"  class="style7" ><h3>Matutino</h3></td>
<td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasMatutino[5]?>" 	style="text-decoration:none;  color:#000000">
										<? if($real_milla) { 
											 $kg_reales	= $valor_ppm * $dMaquinasMatutino[1];
											} else { $kg_reales = $dMaquinasMatutino[0]; } echo number_format($kg_reales);?></a></b></td>
    <td align="right" class="style5"><?=number_format($metasBolKilM = (($dMetasBolseo['meta_mes_kilo']/$ultimo_dia)/24)*8);?></td>
    <td align="right" class="style5"><? echo number_format($nuevasBMK =  ($metasBolKilM - $kg_reales)*-1 );?></td>
    <td align="center"><? 
							$pbmk = $metasBolKilM *.20;
								 $menorbmk	=	$metasBolKilM - $pbmk;
								 if((floor( $kg_reales) < floor($menorbmk)) && (floor( $kg_reales) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor( $kg_reales) > floor($menorbmk+($pbmk*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor( $kg_reales) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
	?></td>
    <td align="right" class="style5"><?=number_format($kg_reales/8);?></td>

    
    <td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasMatutino[5]?>" 	style="text-decoration:none;  color:#000000"><?=number_format($dMaquinasMatutino[1]);?></a></b></td>
    <td align="right" class="style5"><?=number_format($metasBolM = (($dMetasBolseo['meta_mes_millar']/$ultimo_dia)/24)*8);?></td>
    <td align="right" class="style5"><? echo number_format($nuevasBM =($metasBolM -  $dMaquinasMatutino[1])*-1);?></td>
    <td align="center">
	<?				$pbM 		= 	$metasBolM *.20;
					$menorBM	=	$metasBolM - $pbM;
					if((floor($dMaquinasMatutino[1]) < floor($menorBM)) && (floor($dMaquinasMatutino[1]) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dMaquinasMatutino[1]) > floor($menorBM+($pbM*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dMaquinasMatutino[1]) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?></td>
  <td width="100" align="right" class="style5"><?=number_format($dMaquinasMatutino[1]/8);?></td>
  </tr>
  
  
  <tr>
    <td width="85" align="right"  class="style7" ><h3>Vespertino</h3></td>
<td align="right" class="style5"><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasVespertino[5]?>" 	style="text-decoration:none;  color:#000000">
										<? if($real_milla) { 
											 $kg_reales_vesp	= $valor_ppm * $dMaquinasVespertino[1];
											} else { $kg_reales_vesp = $dMaquinasVespertino[0];}
											
											echo number_format($kg_reales_vesp);?>
    </a></td>
    <td align="right" class="style5"><?=number_format($metasBolKilBV = (($dMetasBolseo['meta_mes_kilo']/$ultimo_dia)/24)*7);?></td>
    <td align="right" class="style5"><? echo number_format($nuevasBV =( $metasBolKilBV - $kg_reales_vesp)*-1);?></td>
    <td align="center"><? 
							$pkbv = $metasBolKilBV *.20;
								 $menorbkv	=	$metasBolKilBV - $pkbv;
								 if((floor($kg_reales_vesp) < floor($menorbkv)) && (floor($kg_reales_vesp) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($kg_reales_vesp) > floor($menorbkv+($pkbv*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($kg_reales_vesp) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
	?><br />	</td>
    <td align="right" class="style5"><?=number_format($kg_reales_vesp/7);?></td>
    
  	<td align="right"><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasVespertino[5]?>" 	style="text-decoration:none;  color:#000000"><?=number_format($dMaquinasVespertino[1])?></a></td>
    <td align="right" class="style5"><?=number_format($metasBolMiV = (($dMetasBolseo['meta_mes_millar']/$ultimo_dia)/24)*7);?></td>
    <td align="right" class="style5"><?=number_format($nuevasBMV = ($metasBolMiV - $dMaquinasVespertino[1])*-1);?></td>
    <td align="center"><?
								 $pmv = $metasBolMiV *.20;
								 $menormv	=	$metasBolMiV - $pmv;
								 if((floor($dMaquinasVespertino[1]) < floor($menormv)) && (floor($dMaquinasVespertino[1]) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dMaquinasVespertino[1]) > floor($menormv+($pmv*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dMaquinasVespertino[1]) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?></td>
  	<td align="right" class="style5"><?=number_format($dMaquinasVespertino[1]/7)?></td>
  </tr>
  
  
  <tr>
    <td width="85" align="right"  class="style7" ><h3>Nocturno</h3></td>
<td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasNocturno[5]?>" 	style="text-decoration:none;  color:#000000">
									<? if($real_milla) { 
											 $kg_reales_noc	= $valor_ppm * $dMaquinasNocturno[1];
											} else { $kg_reales_noc = $dMaquinasNocturno[0]; }
											
										echo number_format($kg_reales_noc)?></a></b></td>
    <td align="right" class="style5"><?=number_format($metasBolKilN = (($dMetasBolseo['meta_mes_kilo']/$ultimo_dia)/24)*9);?></td>
    <td align="right" class="style5"><? echo number_format($nuevasBN =  ($metasBolKilN - $kg_reales_noc)*-1 );?></td>
    <td align="center"><? 
	
							$pbkn = $metasBolKilN *.20;
								 $menorbkn	=	$metasBolKilN - $pbkn;
								 if((floor($kg_reales_noc) < floor($menorbkn)) && (floor($kg_reales_noc) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($kg_reales_noc) > floor($menorbkn+($pbkn*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($kg_reales_noc) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
	
	?><br /></td>  
    <td align="right" class="style5"><?=number_format($kg_reales_noc/9);?></td>
    
	<td align="right"><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasNocturno[5]?>" 	style="text-decoration:none;  color:#000000"><?=number_format($dMaquinasNocturno[1])?></a></td>
    <td align="right" class="style5"><?=number_format($metasBolMN = (($dMetasBolseo['meta_mes_millar']/$ultimo_dia)/24)*9);?></td>
    <td align="right" class="style5"><? echo number_format($nuevas = ($metasBolMN - $dMaquinasNocturno[1])*-1);?></td>
    <td align="center">    <?
	
								 $pbmn = $metasBolMN *.20;
								 $menorbmn	=	$metasBolMN - $pbmn;
								 if((floor($dMaquinasNocturno[1]) < floor($menorbmn)) && (floor($dMaquinasNocturno[1]) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dMaquinasNocturno[1]) > floor($menorbmn+($pbmn*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dMaquinasNocturno[1]) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?></td>
  	<td align="right" class="style5"><?=number_format($dMaquinasNocturno[1]/9)?></td>
  </tr>
  <? $totales 	= $kg_reales+$kg_reales_vesp+$kg_reales_noc; ?>
  
  <tr>
    <td width="85" align="right"  class="style7" ><h3>Total</h3></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><b><?=number_format($totales)?>
    </b></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($metasBolKil = $dMetasBolseo['meta_mes_kilo']/$ultimo_dia);?></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><? echo number_format($nuevas2 = ($metasBolKil - $dMaquinas2[0])*-1);?></td>
    <td align="center" bgcolor="#DDDDDD"><? 
	
							$porciento4 = $metasBolKil *.20;
								 $menor2	=	$metasBolKil - $porciento4;
								 if((floor($totales) < floor($menor2)) && (floor($totales) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($totales) > floor($menor2+($porciento4*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($totales) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
	
	?></td>      
    <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($totales/24)?></td>
    
  	<td align="right" bgcolor="#DDDDDD" class="style5"><b><?=$dMaquinas2[1];?></b></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($metasBol = $dMetasBolseo['meta_mes_millar']/$ultimo_dia);?></td>
    <td align="right" bgcolor="#DDDDDD" class="style5"><? echo number_format($nuevas =($metasBol - $dMaquinas2[1])*-1);?></td>
    <td align="center" bgcolor="#DDDDDD">    <?
	
								 $porciento3 = $metasBol *.20;
								 $menor	=	$metasBol - $porciento3;
								 if((floor($dMaquinas2[1]) < floor($menor)) && (floor($dMaquinas2[1]) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dMaquinas2[1]) > floor($menor+($porciento3*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dMaquinas2[1]) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?></td>
  	<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMaquinas2[1]/24);?></td>
  </tr>
  </table>  <br /><br />
    <? }?>

  <? if($nMaquinas2 > 1 ){ ?>
  <table width="80%" align="center" > 
  <tr>
    <td width="76" align="center" class="style7" ><h3>Fecha</h3></td>
    <td width="78" align="center" class="style7" ><h3>Kg</h3></td>
    <td width="74" align="center" class="style7" ><h3>Meta_Kg</h3></td>
    <td width="96" align="center" class="style7" ><h3>Dif._Kg.</h3></td>
    <td width="76" align="center" class="style7" ><h3>Produccion</h3></td>
    <td width="86" align="center" class="titulos_de" >Millares</td>
    <td width="96" align="center" class="titulos_de" >Meta_Mi.</td>
    <td width="72" align="center" class="titulos_de" >Dif._Mi.</td>
    <td width="78" align="center" class="titulos_de" >Produccion</td>
    <td width="86" align="center" class="style7" ><h3>M/H</h3></td>
  </tr>
  <?	 while ($dMaquinas2	= mysql_fetch_row($rMaquinas2)){ ?>
  <tr>
    <td  colspan="1" align="center" class="style5"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dMaquinas2[2]); ?></td>
    <td align="right" class="style5"><b><? if($real_milla) { 
											 $kg_reales	= $valor_ppm * $dMaquinas2[1];
											 echo number_format($kg_reales);
											} else { 
											 $kg_reales	= $dMaquinas2[0];
											 echo number_format($kg_reales);
											}?></b></td>
    <td align="right" class="style5"><? echo number_format($juju = $dMetasBolseo['meta_mes_kilo']/$ultimo_dia);?></td>
    <td align="right"  class="style5"><? echo number_format( $TotalKgDIF = ($juju - $kg_reales)*-1);?></td>
    <td align="center"><? 
								 $porciento2 = $juju *.20;
								 $nueva2	=	$juju - $porciento2;
								 
								 if(floor($dMaquinas2[0]) < floor($nueva2) && $dMaquinas2[0] > 0 ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dMaquinas2[0]) > floor($nueva2+($porciento2*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dMaquinas2[0]) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?>    </td>
    <td align="right"  class="style5"><b><? echo number_format($dMaquinas2[1]);?></b></td>
    <td align="right"  class="style5"><? echo number_format($metasBol = $dMetasBolseo['meta_mes_millar']/$ultimo_dia);?></td>
    <td align="right"  class="style5"><? echo number_format($nuevas = ($metasBol - $dMaquinas2[1])*-1);?></td>
    <td align="center"  ><? 
								 $porciento3 = $metasBol *.20;
								 $menor	=	$metasBol - $porciento3.'<br>';
								 if((floor($dMaquinas2[1]) < floor($menor)) && (floor($dMaquinas2[1]) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($dMaquinas2[1]) > floor($menor+($porciento3*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($dMaquinas2[1]) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?></td>
    <td align="right"  class="style5"><?=number_format($mh 	= $dMaquinas2[1]/24);?></td>
  </tr>
    <? 
 	$TOTALBKG 	+= $kg_reales;
	$TOTALBMETA	+= $juju; 
	$TOTALBDIF	+= $TotalKgDIF;
 	$TOTALBM 		+= $dMaquinas2['1'];
	$TOTALBMETAM	+= $metasBol; 
	$TOTALBMDIF		+= $nuevas;
	$TOTALMP	+=	$mh;
  } ?>
  <tr bgcolor="#DDDDDD">
    <td colspan="1" align="right" class="style5">TOTALES:</td>
    <td align="right" class="style5"><b><?=number_format($TOTALBKG);?></b></td>
    <td align="right" class="style5"><? echo number_format($TOTALBMETA);?></td>
    <td align="right" class="style5"><? echo number_format($TOTALBDIF);?></td>
    <td align="center" ><? 
								 $porTotalMB = $TOTALBMETA *.20;
								 $nuevametaB2	=	$TOTALBMETA - $porTotalMB;
								 if(floor($TOTALBKG) < floor($nuevametaB2) && $TOTALBKG > 0 ) echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($TOTALBKG) > floor($nuevametaB2+($porTotalMB*3))) echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($TOTALBKG) == 0) echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?></td>
    <td align="right" class="style5"><b><? echo number_format($TOTALBM);?></b></td>
    <td align="right" class="style5"><? echo number_format($TOTALBMETAM);?></td>
    <td align="right" class="style5"><? echo number_format($TOTALBMDIF);?></td>
    <td align="center"  ><? 
								 $porcientoB3 = $TOTALBMETAM *.20;
								 $menorB	=	$TOTALBMETAM - $porcientoB3.'<br>';
								 if((floor($TOTALBM) < floor($menorB)) && (floor($TOTALBM) > 0) ) 
								 		echo '<span style="color:#FF0000">BAJA</span>';
								 else if(floor($TOTALBM) > floor($menorB+($porcientoB3*3))) 
								 		echo '<span style="color:#006600">ALTA</span>';
								 else if(floor($TOTALBM) == 0) 
								 		echo '<span style="color:#000000">SIN</span>';
								 else echo "NORMAL";
								 ?></td>    
	<td align="right" class="style5"><? echo number_format($TOTALMP)?></td>
  </tr>
  </table><br /><br />
  <? } ?>
  
  <? if($nMaquinas2 > 1 ){ ?>
  	<p class=" cabeceras titulos_reportes titulos_b" <? if($nMaquinas2>20){ $pagina	=  "Pagina 2"?> style="page-break-before:always;" <? } ?> align="center">PRODUCCION DIARIA BOLSEO (Desperdicios)<br /><?=$mes[$mes_fecha]?> DEL <?=$ano?></p><br /><br />
  <table width="80%" align="center" > 
  
  <tr >
  	<td align="center" class="style7" ><h3>Fecha</h3></td>
    <td width="78" align="center" ><h3>Tira</h3></td>
    <td width="74" align="center" class="style7" ><h3>Meta_Tira</h3></td>
    <td width="96" align="center" class="style7" ><h3>Dif._Tira</h3></td>
    <td width="76" align="center" class="titulos_de" >Troquel</td>
    <td width="86" align="center" class="titulos_de" >Meta_Tro.</td>
    <td width="96" align="center" class="titulos_de" >Dif_Tro.</td>
    <td width="72" align="center" class="style7" ><h3>Segundas</h3></td>
    <td width="78" align="center" class="style7" ><h3>Meta_Seg</h3></td>
    <td width="86" align="center" class="style7" ><h3>Dif._Seg</h3></td>
  </tr>
  <?	 while ($dMaquinas4	= mysql_fetch_row($rMaquinas4)){ ?>
  <tr>
    <td  colspan="1" align="center"  class="style5"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dMaquinas4[2]); ?></td>
     <td align="right" class="style5"><b><?=number_format($dMaquinas4['3'])?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_tira = ($dMetasBolseo['mes_tira']/$ultimo_dia));?></td>
     <td align="right" class="style5"><? echo number_format($TotalTira = ($resultado_tira - $dMaquinas4[3])*-1);?></td>
    
    
     <td align="right" class="style5"><b><?=number_format($dMaquinas4['4'])?></b></td>

     <td align="right" class="style5"><? echo number_format($resultado_troquel = ($dMetasBolseo['mes_troquel']/$ultimo_dia));?></td>
     <td align="right" class="style5"><? echo number_format($TotalTroquel = ($resultado_troquel - $dMaquinas4[4])*-1);?></td>
     
     <td align="right" class="style5"><b><?=number_format($dMaquinas4['5']+$vtsf)?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_seg = ($dMetasBolseo['mes_segunda']/$ultimo_dia));?></td>
     <td align="right" class="style5"><? echo number_format($TotalSeg	=	$resultado_seg - ($dMaquinas4[5]+$vtsf));?></td>
  </tr>     
   <?
   
      $TOTAL1	+=	$dMaquinas4['3'];
      $TOTAL2	+=	$resultado_tira;
      $TOTAL3	+=	$TotalTira;
      $TOTAL4	+=	$dMaquinas4['4'];
	  $TOTAL5	+=	$resultado_troquel;
	  $TOTAL6	+=	$TotalTroquel;
	  $TOTAL7	+=	($dMaquinas4['5']+$vtsf);
	  $TOTAL8	+=	$resultado_seg;
	  $TOTAL9	+=	$TotalSeg;

 } 
   ?>
  <tr bgcolor="#DDDDDD">
    <td  colspan="1" align="right" class="style5">TOTALES: </td>
     <td align="right" class="style5"><b><?=number_format($TOTAL1);?></b></td>
     <td align="right" class="style5"><?=number_format($TOTAL2);?></td>
     <td align="right" class="style5"><?=number_format($TOTAL3);?></td>
   
     <td align="right" class="style5"><b><?=number_format($TOTAL4);?></b></td>
     <td align="right" class="style5"><?=number_format($TOTAL5);?></td>
     <td align="right" class="style5"><?=number_format($TOTAL6);?></td>
     
     <td align="right" class="style5"><b><?=number_format($TOTAL7);?></b></td>
     <td align="right" class="style5"><?=number_format($TOTAL8);?></td>
     <td align="right" class="style5"><?=number_format($TOTAL9);?></td>
  </tr> 
  </table><br /><br /><? } ?>
	  
  <? if($nMaquinas2 == 1 ){ ?>
  <?	 while ($dMaquinas4	= mysql_fetch_row($rMaquinas4)){ ?>
  <table width="80%" align="center"> 
  <tr>
  	<td align="center"  class="style7"><h3>Turno</h3></td>
    <td width="78" align="center" ><h3>Tira</h3></td>
    <td width="74" align="center" class="style7" ><h3>Meta_Tira</h3></td>
    <td width="96" align="center" class="style7" ><h3>Dif_Tira</h3></td>
    <td width="76" align="center" class="titulos_de" >Troquel</td>
    <td width="86" align="center" class="titulos_de" >Meta_Tro.</td>
    <td width="96" align="center" class="titulos_de" >Dif_Tro.</td>
    <td width="72" align="center" class="style7" ><h3>Segundas</h3></td>
    <td width="78" align="center" class="style7" ><h3>Meta_Seg</h3></td>
    <td width="86" align="center" class="style7" ><h3>Dif_Seg</h3></td>
  </tr>
  <tr>
    <td  colspan="1" align="right"   class="style7" ><h3><b>Matutino</b></h3></td>
     <td align="right" class="style5"><b><?=number_format($dMaquinasMatutino['2'])?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_tiraM = (($dMetasBolseo['mes_tira']/$ultimo_dia)/24)*8);?></td>
     <td align="right" class="style5"><? echo number_format($TotalTiraM = ($dMaquinasMatutino[2] - $resultado_tiraM));?></td>
     <td align="right" class="style5"><b><?=number_format($dMaquinasMatutino['3'])?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_troquelM = (($dMetasBolseo['mes_troquel']/$ultimo_dia)/24)*8);?></td>
     <td align="right" class="style5"><? echo number_format($TotalTroquelM = $dMaquinasMatutino[3] -  $resultado_troquelM);?></td>
     <td align="right" class="style5"><b><?=number_format($dMaquinasMatutino['4']+$vts)?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_segM = (($dMetasBolseo['mes_segunda']/$ultimo_dia)/24)*8);?></td>
     <td align="right" class="style5"><? echo number_format($TotalSegM	=	($dMaquinasMatutino[4]+$vts) - $resultado_segM);?></td>
  </tr>
  
   <tr>
    <td  colspan="1" align="right"   class="style7" ><h3><b>Vespertino</b></h3></td>
     <td align="right" class="style5"><b><?=number_format($dMaquinasVespertino['2'])?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_tiraV = (($dMetasBolseo['mes_tira']/$ultimo_dia)/24)*7);?></td>
     <td align="right" class="style5"><? echo number_format($TotalTiraV = ($dMaquinasVespertino[2] - $resultado_tiraV));?></td>
    
    
     <td align="right" class="style5"><b><?=number_format($dMaquinasVespertino['3'])?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_troquelV = (($dMetasBolseo['mes_troquel']/$ultimo_dia)/24)*7);?></td>
     <td align="right" class="style5"><? echo number_format($TotalTroquelV = $dMaquinasVespertino[3] - $resultado_troquelV);?></td>
     
     <td align="right" class="style5"><b><?=number_format($dMaquinasVespertino[4]+$vts)?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_segV = (($dMetasBolseo['mes_segunda']/$ultimo_dia)/24)*7);?></td>
     <td align="right" class="style5"><? echo number_format($TotalSegV	= ($dMaquinasVespertino[4]+$vts) - $resultado_segV);?></td>
  </tr>     
  
   <tr>
    <td  colspan="1" align="right"   class="style7" ><h3><b>Nocturno</b></h3></td>
     <td align="right" class="style5"><b><?=number_format($dMaquinasNocturno['2'])?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_tiraN = (($dMetasBolseo['mes_tira']/$ultimo_dia)/24)*9);?></td>
     <td align="right" class="style5"><? echo number_format($TotalTiraN = ($dMaquinasNocturno[2] -  $resultado_tiraN ));?></td>
    
    
     <td align="right" class="style5"><b><?=number_format($dMaquinasNocturno['3'])?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_troquelN = (($dMetasBolseo['mes_troquel']/$ultimo_dia)/24)*9);?></td>
     <td align="right" class="style5"><? echo number_format($TotalTroquelN =  $dMaquinasNocturno[3] - $resultado_troquelN);?></td>
     
     <td align="right" class="style5"><b><?=number_format($dMaquinasNocturno['4']+$vts)?></b></td>
     <td align="right" class="style5"><? echo number_format($resultado_segN = (($dMetasBolseo['mes_segunda']/$ultimo_dia)/24)*9);?></td>
     <td align="right" class="style5"><? echo number_format($TotalSegN	= ($dMaquinasNocturno[4]+$vts) - $resultado_segN);?></td>
  </tr>          
   <?
   
      $TOTAL1	=	$dMaquinas4['3'];
      $TOTAL2	=	($dMetasBolseo['mes_tira']/$ultimo_dia);
      $TOTAL3	=	$TOTAL1 - ($dMetasBolseo['mes_tira']/$ultimo_dia);
      $TOTAL4	=	$dMaquinas4['4'];
	  $TOTAL5	=	($dMetasBolseo['mes_troquel']/$ultimo_dia);
	  $TOTAL6	=	$TOTAL4 - $TOTAL5;
	  $TOTAL7	=	($dMaquinas4['5']+$vtsf);
	  $TOTAL8	=	($dMetasBolseo['mes_segunda']/$ultimo_dia);
	  $TOTAL9	=	$TOTAL7 - $TOTAL8;

 } 
   ?>
  <tr bgcolor="#DDDDDD">
    <td  colspan="1" align="right"   class="style7" ><h3><b>Total:</b></h3></td>
    <td align="right" class="style5"><b><?=number_format($TOTAL1);?></b></td>
    <td align="right" class="style5"><?=number_format($TOTAL2);?></td>
    <td align="right" class="style5"><?=number_format($TOTAL3);?></td>
   
    <td align="right" class="style5"><b><?=number_format($TOTAL4);?></b></td>
    <td align="right" class="style5"><?=number_format($TOTAL5);?></td>
    <td align="right" class="style5"><?=number_format($TOTAL6);?></td>
     
     <td align="right" class="style5"><b><?=number_format($TOTAL7);?></b></td>
     <td align="right" class="style5"><?=number_format($TOTAL8);?></td>
     <td align="right" class="style5"><?=number_format($TOTAL9);?></td>
  </tr>  
  </table><br /><br /><? } ?>

	<div align="center"><input type="button" class="styleTabla" name="pfd" value="Formato PDF" onClick="genera()"></div>
<br />
<br />
</form>
</div></div>
<?  } ?>



 <?  if($_REQUEST['tipo'] == 37 ){  
	 
 	$q_millar 	=	"SELECT SUM(resumen_maquina_bs.millares) AS millares, SUM(resumen_maquina_bs.kilogramos) AS kilogramos, maquina.id_maquina FROM bolseo ".
					"INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo ".
					" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".
					" WHERE MONTH(fecha) = '".$_REQUEST['mes_grafica']."' AND YEAR(fecha) = '".$_REQUEST['ano_grafica']."' AND maquina.area = 1 GROUP BY resumen_maquina_bs.id_maquina ORDER BY numero  ";
	
	$r_millar	=	mysql_query($q_millar);
	$n_millar	=	mysql_num_rows($r_millar);
	if($n_millar > 0){
	
	for($a=0;$d_millar 	= 	mysql_fetch_assoc($r_millar);$a++){
		 $millares[$a] 	= 	$d_millar['millares'];
		 $kilo[$a]		=	$d_millar['kilogramos'];
	} 
	

	$q_turno 	=	"SELECT COUNT(fecha) AS num FROM bolseo ".
					"INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo ".
					" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".
					" WHERE MONTH(fecha) = '".$_REQUEST['mes_grafica']."' AND YEAR(fecha) = '".$_REQUEST['ano_grafica']."' AND maquina.area = 1  AND (resumen_maquina_bs.millares != 0 OR resumen_maquina_bs.millares != '') GROUP BY resumen_maquina_bs.id_maquina  ORDER BY numero  ";
	$r_turno		=	mysql_query($q_turno);
								   
	for($a=0;$d_turno = mysql_fetch_assoc($r_turno);$a++){
		$turnos[$a] = $d_turno['num'];
	} 


$q_supervisores	=	"SELECT * FROM supervisor WHERE area3 = 1";
$r_supervisores	= 	mysql_query($q_supervisores);
} 

  	$q_millar_superT 	=	"SELECT SUM(resumen_maquina_bs.millares) AS millares, SUM(resumen_maquina_bs.kilogramos) AS kilogramos, maquina.id_maquina FROM bolseo ".
						"INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo ".
						" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".
						" WHERE MONTH(fecha) = '".$_REQUEST['mes_grafica']."' AND YEAR(fecha) = '".$_REQUEST['ano_grafica']."' AND maquina.area = 1 GROUP BY resumen_maquina_bs.id_maquina ORDER BY numero  ";
	$r_millar_superT	=	mysql_query($q_millar_superT);
	
	for($a=0;$d_millar_superT 	= 	mysql_fetch_assoc($r_millar_superT);$a++){
		 $millares_supert[$a] 	= 	$d_millar_superT['millares'];
		 $kilo_supert[$a]		=	$d_millar_superT['kilogramos'];
	}	
	$q_turno_superT 	=	"SELECT COUNT(fecha) AS num FROM bolseo ".
					"INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo ".
					" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".
					" WHERE  MONTH(fecha) = '".$_REQUEST['mes_grafica']."' AND YEAR(fecha) = '".$_REQUEST['ano_grafica']."' AND maquina.area = 1  AND (resumen_maquina_bs.millares != 0 OR resumen_maquina_bs.millares != '') GROUP BY resumen_maquina_bs.id_maquina  ORDER BY numero  ";
	$r_turno_superT		=	mysql_query($q_turno_superT);
								   
	for($a=0;$d_turno_superT = mysql_fetch_assoc($r_turno_superT);$a++){
		$turnos_supert[$a] = $d_turno_superT['num'];
	} 
 
 ?>
<div align="center" id="tablaimpr">
<div class="tablaCentrada titulos_b" id="tabla_reporte">
	<p class="titulos_reportes titulos_b" align="center">CONCENTRADO DE BOLSEO POR SUPERVISOR<br />DE <?=strtoupper($mes[$_REQUEST['mes_grafica']])?> DE <?=$_REQUEST['ano_grafica']?></p>
<? 	if($n_millar > 0){ ?>
<table width="90%" align="center" >
  <tr>
    <td></td>
    <? $r_maquinas2	=	mysql_query("SELECT * FROM maquina WHERE area = 1 ORDER BY numero");
								   while($d_maquinas2 = mysql_fetch_assoc($r_maquinas2)){ ?>
    <td align="center" width="10%"><h3><?=$d_maquinas2['numero']?></h3></td>
    <? } ?>
    <td align="center" width="15%"><h3>Totales</h3></td>
  </tr>
  <tr>
    <td align="right">Millares:</td>
    <? for($z=0; $z < sizeof($millares_supert) ;$z++){?>
    <td align="right" width="10%" class="style5"><?=number_format($millares_supert[$z])?></td>
    <? $total_millar_supert[$c] += $millares_supert[$z]; ?>
    <? } ?>
    <td class="style4" align="right"><?=number_format($total_millar_supert[$c]);?></td>
  </tr>
  <tr>
    <td align="right">Turnos:</td>
    <? for($z=0; $z < sizeof($turnos_supert) ;$z++){?>
    <td align="right" width="10%" class="style5"><? printf("%.1f",$turnos_supert[$z])?></td>
    <? $total_turnos_st[$c] += $turnos_supert[$z];  ?>
    <? } ?>
    <td class="style4" align="right"><?  printf("%.1f",$total_turnos_st[$c])?></td>
  </tr>
  <tr>
    <td align="right">Dias:</td>
    <? for($z=0; $z < sizeof($turnos_supert) ;$z++){?>
    <td align="right" width="10%" class="style5"><? $total_dias_supert[$c]	= $turnos_supert[$z]/3; printf("%.1f", $total_dias_supert[$c])?></td>
    <? $total_dias_turnos_supert[$c] += $total_dias_supert[$c];  ?>
    <? }?>
    <td align="right" class="style4"><? printf("%.1f",$total_dias_turnos_supert[$c]/10)?></td>
  </tr>
  <tr>
    <td align="right">Prom._Diario:</td>
    <? for($z=0; $z < sizeof($turnos_supert) ;$z++){?>
    <td class="style5" align="right"><? $prom_d_supert[$c] = $millares_supert[$z]/($turnos_supert[$z]/3); printf("%.1f",$prom_d_supert[$c])?></td>
    <? $total_prom_d_supert[$c] += $prom_d_supert[$c]; ?>
    <? } ?>
    <td class="style4" align="right"><? printf("%.1f",$total_prom_d_supert[$c])?></td>
  </tr>
  <tr>
    <td align="right">Prom._x_turno:</td>
    <? for($z=0; $z < sizeof($turnos_supert) ;$z++){?>
    <td class="style5" align="right"><? $prom_t_supert[$c]	= $millares_supert[$z]/$turnos_supert[$z]; echo number_format($prom_t_supert[$c])?></td>
    <? $total_prom_t_supert[$c] += $prom_t_supert[$c]; ?>
    <? } ?>
    <td class="style4" align="right"><?=number_format($total_prom_t_supert[$c])?></td>
  </tr>
  <tr>
    <td colspan="3" align="right"><h3>Total Kg: </h3></td>
    <td class="style4" align="right"><?
								if($real_milla){ 
									$peso_promedio_millar_st[$c] = $valor_ppm; 
									
									$kilos_supert[$c] = $peso_promedio_millar_st[$c] * $total_millar_supert[$c];
								} else { 
 										for($z=0; $z < sizeof($turnos_supert) ;$z++){
											$kilos_supert[$c]	+=	$kilo_supert[$z];								
											}
									$peso_promedio_millar_st[$c] = $kilos_supert[$c]/$total_millar_supert[$c];
										}  echo number_format($kilos_supert[$c] ) ?></td>
    <td colspan="4" align="right"><h3>Peso prom.o millar: </h3></td>
    <td class="style4" align="right"><?=number_format($peso_promedio_millar_st[$c],2 )?></td>
  </tr>
</table>
<br>                  
              
<? 

for($c = 0, $g = 3; $d_supervisores = mysql_fetch_assoc($r_supervisores);$c++, $g = $g+5)	{
	

 	$q_millar_super 	=	"SELECT SUM(resumen_maquina_bs.millares) AS millares, SUM(resumen_maquina_bs.kilogramos) AS kilogramos, maquina.id_maquina FROM bolseo ".
						"INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo ".
						" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".
						" WHERE id_supervisor = ".$d_supervisores['id_supervisor']." AND MONTH(fecha) = '".$_REQUEST['mes_grafica']."' AND YEAR(fecha) = '".$_REQUEST['ano_grafica']."' AND maquina.area = 1 GROUP BY resumen_maquina_bs.id_maquina ORDER BY numero  ";
	$r_millar_super	=	mysql_query($q_millar_super);
	for($a=0;$d_millar_super 	= 	mysql_fetch_assoc($r_millar_super);$a++){
		 $millares_super[$a] 	= 	$d_millar_super['millares'];
		 $kilo_super[$a]		=	$d_millar_super['kilogramos'];
	} 	
	
	
	

	$q_turno_super 	=	"SELECT COUNT(fecha) AS num FROM bolseo ".
					"INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo ".
					" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".
					" WHERE  id_supervisor = ".$d_supervisores['id_supervisor']." AND MONTH(fecha) = '".$_REQUEST['mes_grafica']."' AND YEAR(fecha) = '".$_REQUEST['ano_grafica']."' AND maquina.area = 1  AND (resumen_maquina_bs.millares != 0 OR resumen_maquina_bs.millares != '') GROUP BY resumen_maquina_bs.id_maquina  ORDER BY numero  ";
	$r_turno_super		=	mysql_query($q_turno_super);
								   
	for($a=0;$d_turno_super = mysql_fetch_assoc($r_turno_super);$a++){
		$turnos_super[$a] = $d_turno_super['num'];
	}  ?>
<table width="90%" align="center" <? if($c == $g){  ?> style="page-break-after:always"<? } ?>>
  <tr>
    <td ><h3>SUPERVISOR:</h3></td>
    <td class="style4" colspan="6"><?=$d_supervisores['nombre']?></td>
  </tr>
  <tr>
    <td></td>
    <? $r_maquinas	=	mysql_query("SELECT * FROM maquina WHERE area = 1 ORDER BY numero");
								   while($d_maquinas = mysql_fetch_assoc($r_maquinas)){ ?>
    <td align="center" width="10%"><h3>
      <?=$d_maquinas['numero']?>
    </h3></td>
    <? } ?>
    <td align="center" width="15%"><h3>Totales</h3></td>
  </tr>
  <tr>
    <td align="right">Millares:</td>
    <? for($z=0; $z < sizeof($millares_super) ;$z++){?>
    <td align="right" width="10%" class="style5"><?=number_format($millares_super[$z])?></td>
    <? $total_millar_super[$c] += $millares_super[$z]; ?>
    <? } ?>
    <td class="style4" align="right"><?=number_format($total_millar_super[$c]);?></td>
  </tr>
  <tr>
    <td align="right">Turnos:</td>
    <strong></strong>
    <? for($z=0; $z < sizeof($turnos_super) ;$z++){?>
    <td align="right" width="10%" class="style5"><? printf("%.1f",$turnos_super[$z])?></td>
    <? $total_turnos_s[$c] += $turnos_super[$z];  ?>
    <? } ?>
    <td class="style4" align="right"><?  printf("%.1f",$total_turnos_s[$c])?></td>
  </tr>
  <tr>
    <td align="right">Dias:</td>
    <? for($z=0; $z < sizeof($turnos_super) ;$z++){?>
    <td align="right" width="10%" class="style5"><? $total_dias_super[$c]	= $turnos_super[$z]/3; printf("%.1f", $total_dias_super[$c])?></td>
    <? $total_dias_turnos_super[$c] += $total_dias_super[$c];  ?>
    <? }?>
    <td align="right" class="style4"><? printf("%.1f",$total_dias_turnos_super[$c]/10)?></td>
  </tr>
  <tr>
    <td align="right">Prom._Diario:</td>
    <? for($z=0; $z < sizeof($turnos_super) ;$z++){?>
    <td class="style5" align="right"><? $prom_d_super[$c] = $millares_super[$z]/($turnos_super[$z]/3); printf("%.1f",$prom_d_super[$c])?></td>
    <? $total_prom_d_super[$c] += $prom_d_super[$c]; ?>
    <? } ?>
    <td class="style4" align="right"><?=number_format($total_prom_d_super[$c])?></td>
  </tr>
  <tr>
    <td align="right">Prom._x_turno:</td>
    <? for($z=0; $z < sizeof($turnos_super) ;$z++){?>
    <td class="style5" align="right"><? $prom_t_super[$c]	= $millares_super[$z]/$turnos_super[$z]; printf("%.1f",$prom_t_super[$c])?></td>
    <? $total_prom_t_super[$c] += $prom_t_super[$c]; ?>
    <? } ?>
    <td class="style4" align="right"><?=number_format($total_prom_t_super[$c])?></td>
  </tr>
  <tr>
    <td colspan="3" align="right"><h3>Total Kg: </h3></td>
    <td class="style4" align="right"><?
								if($real_milla){ 
									$peso_promedio_millar_s[$c] = $valor_ppm; 
									
									$kilos_super[$c] = $peso_promedio_millar_s[$c] * $total_millar_super[$c];
								} else { 
 										for($z=0; $z < sizeof($turnos_super) ;$z++){
											$kilos_super[$c]	+=	$kilo_super[$z];								
											}
									$peso_promedio_millar_s[$c] = $kilos_super[$c]/$total_millar_super[$c];
										}  echo number_format($kilos_super[$c] ) ?></td>
    <td colspan="4" align="right"><h3>Peso prom.o millar: </h3></td>
    <td class="style4" align="right"><?=number_format($peso_promedio_millar_s[$c],2 )?></td>
  </tr>
  <tr></tr>
</table>

<br>                     
  <? } ?>                              

      <? } else { ?>
	<table width="100%" align="center">
    	<tr>
                	<td colspan="10" align="center">CONCENTRADO AREA DE CAMISETAS MENSUAL DE <?=strtoupper($mes[$_REQUEST['mes_grafica']])?> DE <?=$_REQUEST['ano_grafica']?></td>
        </tr>
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

<? } ?>



<?  if($_REQUEST['tipo'] == 45 ){?>
<div align="center" id="tablaimpr"  >
	<div class="tablaCentrada titulos_b" id="tabla_reporte" >
    <p align="center" class="titulos_reportes">REPORTE DE PRODUCCION POR MAQUINA EN BOLSEO<br /><?=$mes[$_REQUEST['mes_maq']]?> de <?=$_REQUEST['ano_maq']?></p><br />
    <form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
	<table width="80%" align="center" >
        <? for($z=0; $z<sizeof($_REQUEST['maq_id']);$z++){ 
		
	$qMq	=	" SELECT  SUM(resumen_maquina_bs.kilogramos) AS total,  SUM(resumen_maquina_bs.millares) AS total2, resumen_maquina_bs.id_maquina AS id_maquina, maquina.numero, fecha, maquina.area   FROM resumen_maquina_bs "	.
				" INNER JOIN bolseo 	ON resumen_maquina_bs.id_bolseo 	= bolseo.id_bolseo ".
				" INNER JOIN maquina 	ON resumen_maquina_bs.id_maquina 		= maquina.id_maquina ".
				" WHERE ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." )  AND resumen_maquina_bs.id_maquina = ".$_REQUEST['maq_id'][$z]." GROUP BY  DAY(fecha)".
				" ORDER BY fecha ASC";	
	
	$qMqP	=	" SELECT COUNT(*)/3 AS paros , fecha, resumen_maquina_bs.id_maquina, rol,  maquina.numero, MONTH(fecha) AS mes FROM resumen_maquina_bs "	.
				" INNER JOIN bolseo 	ON resumen_maquina_bs.id_bolseo 	= 	bolseo.id_bolseo ".
				" INNER JOIN maquina 	ON resumen_maquina_bs.id_maquina 	= 	maquina.id_maquina ".
				" WHERE resumen_maquina_bs.kilogramos !=0 AND ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." )  AND resumen_maquina_bs.id_maquina = ".$_REQUEST['maq_id'][$z]." GROUP BY  maquina.numero".
				" ORDER BY maquina.numero ASC";		
								
	$qMetas	=	" SELECT * FROM meta".
				" LEFT JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".
				" WHERE meta.area = 3 AND  ( MONTH(mes) = ".$_REQUEST['mes_maq']."  AND ano = ".$_REQUEST['ano_maq']." )";
	$qNuM	=	"SELECT * FROM maquina WHERE area = 1";
	$rNuM	=	mysql_query($qNuM);	
	$nNuM	=	mysql_num_rows($rNuM);
	
	$qAM	=	"SELECT area, numero FROM maquina WHERE id_maquina	=	".$_REQUEST['maq_id'][$z]."";
	$rAM	=	mysql_query($qAM);
	$dAM	=	mysql_fetch_assoc($rAM);
					
	$query	=	" FROM bolseo ".
				" LEFT JOIN tiempos_muertos ON bolseo.id_bolseo = tiempos_muertos.id_produccion ";


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
$nMqp	=	mysql_num_rows($rMqP);
$dMqp	=	mysql_fetch_assoc($rMqP);



$rMetas	=	mysql_query($qMetas);
$dMetas	=	mysql_fetch_assoc($rMetas);
$meta_kilos	=	$dMetas['meta_mes_kilo'];
$meta_millar=	$dMetas['meta_mes_millar'];

$id_maquinas	=	$dMetas['id_maquina'];
?>
<tr style="page-break-after:always" >
		<td width="67%" align="center" valign="top" ><table align="center" width="92%" style="margin:14px;">
          <tr>
            <td align="center" colspan="7" valign="middle" style="height:24px;" ><b>Maquina :
              <?
					if($dAM['area'] == 1) echo "Bol. ";
					echo $dAM['numero']?>
            </b> </td>
          </tr>
          <tr>
            <td width="98"  align="center"><h3>Fecha</h3></td>
            <td width="60" align="center"><h3>Mi</h3></td>
            <td width="63" align="center"><h3>Meta</h3></td>
            <td width="87" align="center"><h3>Dif</h3></td>
            <td width="83" align="center"><h3>kg</h3></td>
            <td width="63" align="center"><h3>Meta</h3></td>
            <td width="87" align="center"><h3>Dif</h3></td>
          </tr>
          <? $a = 0; $metas_maquinasK= 0; $metas_maquinasM = 0; while($dMq	=	mysql_fetch_assoc($rMq)){ 
													$metas_maquinasK	=	($meta_kilos/$dMqp['paros'])/$nNuM;
													$metas_maquinasM	=	($meta_millar/$dMqp['paros'])/$nNuM;
													
													$qMet	=	" SELECT fecha, meta_dia, turno_m, turno_v, turno_n FROM paros_maquinas ".
																" WHERE fecha = '".$dMq['fecha']."' AND id_maquina = ".$_REQUEST['maq_id'][$z]."";
													$rMet	=	mysql_query($qMet);
													$dMet	=	mysql_fetch_assoc($rMet);
									
													
													($dMq['fecha'] == $dMet['fecha'])? $metas_maquinasK = $dMet['meta_dia']:$metas_maquinasK = $metas_maquinasK;
													($dMq['fecha'] == $dMet['fecha'])? $metas_maquinasM = $dMet['meta_dia']:$metas_maquinasM = $metas_maquinasM;
													
													$turnos[$a]	=	$dMet['turno_m']+$dMet['turno_v']+$dMet['turno_n']; 
													$diferencia	=	$metas_maquinasK-$dMq['total'];
													$diferencia2=	$metas_maquinasM-$dMq['total2'];
													
													
													if($real_milla)
													$kilogramos = $dMq['total2'] * $valor_ppm;
													else
													$kilogramos	= $dMq['total']
													?>
          <tr <? cebra($a)?>>
            <td align="center"><? fecha($dMq['fecha'])?></td>
            <td align="center" class="style5"><?=number_format($dMq['total2'])?></td>
            <td align="center" class="style5"><?=number_format($metas_maquinasM)?></td>
            <td align="center" class="style4"><?=number_format($diferencia2)?></td>
            <td align="center" class="style5"><?=number_format($kilogramos)?></td>
            <td align="center" class="style5"><?=number_format($metas_maquinasK)?></td>
            <td align="center" class="style4"><?=number_format($diferencia)?></td>
          </tr>
          <? 	$a++;
												$Total_metas	+=	$metas_maquinasK;
												$Total_metas2	+=	$metas_maquinasM;
												$total_prod		+=	$kilogramos;
												$total_prod2	+=	$dMq['total2'];
												$total_dif		+=	$diferencia;
												$total_dif2		+=	$diferencia2;
												} ?>
          <tr>
            <td><h3>Totales: </h3></td>
            <td align="center"><?=number_format($total_prod2)?></td>
            <td align="center"><?=number_format($Total_metas2)?></td>
            <td align="center"><?=number_format($total_dif2)?></td>
            <td align="center"><?=number_format($total_prod)?></td>
            <td align="center"><?=number_format($Total_metas)?></td>
            <td align="center"><?=number_format($total_dif)?></td>
          </tr>
          <tr>
            <td><h3>Promedio: </h3></td>
            <td align="center"><?=@number_format($total_prod2/$dMqp['paros'])?></td>
            <td colspan="2"></td>
            <td align="center"><?=@number_format($total_prod/$dMqp['paros'])?></td>
            <td align="right"></td>
          </tr>
        </table></td>
		<td width="33%" align="center" valign="top"  >
		<table align="center"  width="64%" style="margin:15px 15px;">
			<tr>
				<td colspan="4" align="center"><h3>Paros :: Motivos</h3></td>
			</tr>
			<tr>
				<td width="23%" align="center"><h3>Turnos</h3></td>
				<td width="26%" align="center"><h3>Mantto </h3></td>
				<td width="21%" align="center"><h3>Oper. </h3></td>
				<td width="30%" align="center"><h3>Otros </h3></td>
			</tr>
			<? for($a=0;$a<sizeof($turnos);$a++){?>
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
				<td align="center"><?  @printf("%.2f" ,$tTotal)?></td>
				<td align="center"><? @printf("%.2f" ,$tMantoTotal)?></td>
				<td align="center"><? @printf("%.2f" ,$tMantoT)?></td>
				<td align="center"><? @printf("%.2f" ,$tFallorT)?></td>
			</tr>
			<tr height="25">
				<td align="right" colspan="4">Dias <?  @printf("%.2f" ,$tTotal/3)?></td>
			</tr>
		</table>        </td>
		</tr>
            <? } ?>  
		</table>
	</form>
</div>
</div>
<? } ?> 
 
 
 
 <? if($_REQUEST['tipo'] == 52){ 

		$meses		=	fecha_tablaInv($_REQUEST['fecha_1']);
		$date		=	explode('-',$meses);
		$mesFinal	=	fecha_tablaInv($_REQUEST['fecha_1f']);
		$meses		=	$date[0].'-'.$date[1].'-01';
		$meMeta 	=	fecha_tablaInv($_REQUEST['fecha_1']);
		$anho 		=	$date[0];
		$messMeta 	=	$date[1];
		
		
	//$meMeta	=	$_REQUEST['mes_grafica_1'];
	//$anho	=	$_REQUEST['ano_grafica_1'];

	$mesMetacero	=	$anho.'-'.$messMeta.'-01';
	//$meses	=	$anho.'-'.$mesMetacero.'-01';
	$ultimo_dia = UltimoDia($anho,$messMeta);
//	$mesFinal	=	$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

/////////////////////// PAROS POR DIA /////////////////////////
$qSelectTiemposExtr	=	"SELECT SUM(TIME_TO_SEC(mantenimiento)) AS mantenimiento ,".
						" SUM(TIME_TO_SEC(falta_personal)) AS falta_personal ,".
						" SUM(TIME_TO_SEC(fallo_electrico+otras)) AS otras ".
						" FROM bolseo ".
						" LEFT JOIN tiempos_muertos ON bolseo.id_bolseo = tiempos_muertos.id_produccion ".
						" WHERE tipo = 4 AND fecha BETWEEN '".$meses."' AND '".$mesFinal."' ".
						" GROUP BY bolseo.fecha ORDER BY bolseo.fecha ASC";
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
						" FROM bolseo ".
						" LEFT JOIN tiempos_muertos ON bolseo.id_bolseo = tiempos_muertos.id_produccion ".
						" WHERE tipo = 4 AND fecha BETWEEN '".$meses."' AND '".$mesFinal."'   GROUP BY tiempos_muertos.id_maquina ORDER BY tiempos_muertos.id_maquina ASC";
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
$qExtruderM	=	"SELECT SUM(resumen_maquina_bs.kilogramos) AS kilos, SUM(resumen_maquina_bs.millares) AS millares ".
 				" ,numero".
				" FROM bolseo ".
				" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".
				" INNER JOIN maquina ON maquina.id_maquina = resumen_maquina_bs.id_maquina".
				" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."'  ".
				" GROUP BY resumen_maquina_bs.id_maquina ".
				" ORDER BY maquina.numero ASC ";
$rExtruderM	=	mysql_query($qExtruderM);

/////////////////////// PRODUCCION  DIARIA /////////////////////////
$qExtruder	=	"SELECT SUM(kilogramos) AS kilos, SUM(millares) AS millares, ".
				"  COUNT(turno) AS turnos , fecha, ".
				" SUM(dtira) as tira, ".
				" SUM(dtroquel) as troquel, ".
				" SUM(segundas) as segundas FROM bolseo ".
				" WHERE fecha BETWEEN '".$meses."' AND '".$mesFinal."'  ".
				" GROUP BY fecha ".
				" ORDER BY fecha ASC ";
$rExtruder	=	mysql_query($qExtruder);

/////////////////////// METAS POR DIA ///////////////////////////////

$qMetasM	=	"SELECT * FROM meta WHERE mes = '".$mesMetacero."' AND meta.area = '3'";
$rMetasM	=	mysql_query($qMetasM);
$nMetasM	=	mysql_num_rows($rMetasM);




//////////////////////PRODUCCION META Y DIFERENCIA/////////////////////////
if($nMetas > 0){
	$dMetas		=	mysql_fetch_assoc($rMetas);
	$dMetasM	=	mysql_fetch_assoc($rMetasM);
}

$nMaquinas		=	mysql_num_rows($rExtruderM);
$dMetasM 		=	mysql_fetch_assoc($rMetasM);
$metaMaquina	=	$dMetasM['meta_mes_kilo']/$nMaquinas;
$metaMaquinaDia	=	($dMetasM['meta_mes_kilo']/$ultimo_dia);
$total_metaM	= 	$metaMaquina*$nMaquinas;
$total_metaMD	= 	$metaMaquinaDia*$ultimo_dia;

$metaMaquinaM	=	$dMetasM['meta_mes_millar']/$nMaquinas;
$metaMaquinaMDia	=	($dMetasM['meta_mes_millar']/$ultimo_dia);
$total_metaMM	= 	$metaMaquinaM*$nMaquinas;
$total_metaMMD	= 	$metaMaquinaMDia*$ultimo_dia;

for($t = 0; $dExtruder = mysql_fetch_assoc($rExtruder);$t++){
		$fecha[$t]		=	$dExtruder['fecha'];
		$produccion_mi[$t]	=	$dExtruder['millares'];
		$produccion_kg[$t]	=	$dExtruder['kilos'];
		$tira[$t]			= 	$dExtruder['tira'];
		$troquel[$t]		= 	$dExtruder['troquel'];
		$segundas[$t]		= 	$dExtruder['segundas'];
		
		$diferencia_mi[$t]	=	($metaMaquinaMDia - $produccion_mi[$t]);
		$diferencia_kg[$t]	=	($metaMaquinaDia - $produccion_kg[$t]);
		
		///////TOTALES/////		
		
		$turnos_total	+=	$dExtruder['turnos'];
		$dias			=	$turnos_total/3;
		
		$total_dif			+=	$diferencia[$t];
		$total_pro			+=	$produccion_mi[$t];
		
		
		if($real_milla)
		$total_pro_kg		+=	($produccion_mi[$t]*$valor_ppm);
		else
		$total_pro_kg		+=	$produccion_kg[$t];
		
		
		
		$total_meta			+= $dMetas['total_dia'];
		$Ttira				+= $dExtruder['tira'];
		$Ttroquel			+= $dExtruder['troquel'];
		$Tsegundas			+= ($dExtruder['segundas']+$vtsf);
		
		
}

for($t = 0; $dExtruderM = mysql_fetch_assoc($rExtruderM);$t++){
		if($real_milla)
		$produccionM[$t]	= ($dExtruderM['millares']*$valor_ppm);
		else
		$produccionM[$t]	=	$dExtruderM['kilos'];
		
		$produccionMM[$t]	=	$dExtruderM['millares'];
		$numero[$t]			=	$dExtruderM['numero'];
		
		$diferenciaMa[$t]	=	($metaMaquina - $produccionM[$t]);
		
		
		$diferenciaMM[$t]	=	($metaMaquinaM - $produccionMM[$t]);
		
		///////TOTALES/////		
		$total_difM			+=	$diferenciaMa[$t];
		
		$total_proM			+=	$produccionM[$t];
		
		$total_difMM		+=	$diferenciaMM[$t];
		$total_proMM		+=	$produccionMM[$t];}


//$total_desp	=	$duro + $tira;
	
$Total_produccionMi	=	$total_proMM/$dias;
$Total_produccionKg	=	$total_proM/$dias;

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
<div class="tablaCentrada titulos_b" id="tabla_reporte">
	<p class="titulos_reportes titulos_b" align="center">CREPORTE CONTRA META Y TURNOS EN BOLSEO<br />
    DE <?=strtoupper($mes[$_REQUEST['mes_grafica']])?> DE <?=$_REQUEST['ano_grafica']?></p><br /><br />
<form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">
<table width="700" align="center" >
<tr>
	<td colspan="2" style="padding:7px;">
    <p align="center">PRODUCCION</p>
    <table width="700" align="center" >
		<tr>
        	<td width="96"></td>
       	   	<td colspan="3" align="center"><h3>Millares</h3></td>
        	<td colspan="3" align="center"><h3>Kilogramos</h3></td>
        	<td colspan="3" align="center" class="titulos_de">Desperdicios</td>
        </tr>
            <tr>
                <td  align="center" valign="middle"><h3>Fecha</h3></td>
              <td width="69"  align="center"><h3>Mi.</h3></td>
              <td width="67"  align="center"><h3>Meta</h3></td>
              <td width="63"  align="center"><h3>Dif.</h3></td>
              <td width="64"  align="center"><h3>Kg.</h3></td>
              <td width="73"  align="center"><h3>Kg.</h3></td>                
              <td width="69"  align="center"><h3>Dif.</h3></td>
                <td width="63"  align="center" class="titulos_de">Tira</td>
                <td width="75"  align="center" class="titulos_de">Troq.</td>
                <td width="40"  align="center" class="titulos_de">Seg.</td>
          </tr>
            <? for($t = 0; $t < sizeof($produccion_mi);$t++){
			$tSegundas_b	=	$segundas[$t]+$vtsf;
			if($real_milla)
			$produccion_kilos	=	$produccion_mi[$t]*$valor_ppm;
			else
			$produccion_kilos	=	$produccion_kg[$t];
			?>
            <tr <?=cebra($t)?>>
                <td class="" align="center"><?=fecha($fecha[$t])?></td>
                <td class="style5" align="center"><?=number_format($produccion_mi[$t])?></td>
                <td class="style5" align="center"><?=number_format($metaMaquinaMDia);?></td>
                <td class="style5" align="center"><?=number_format($diferencia_mi[$t])?></td>
                <td class="style5" align="center"><?=number_format($produccion_kilos)?></td>
                <td class="style5" align="center"><?=number_format($metaMaquinaDia)?></td>
                <td class="style5" align="center"><?=number_format($diferencia_kg[$t])?></td>                
                <td class="style5" align="center"><?=number_format($tira[$t])?></td>                
                <td class="style5" align="center"><?=number_format($troquel[$t])?></td>                
                <td class="style5" align="center"><?=number_format($tSegundas_b)?></td>                
            </tr> 
            <? } ?>
            <tr style="background-color:#EEEEEE">
                <td class="style5" align="center"><h3>SUMAS</h3></td>
                <td class="style4" align="center"><?=number_format($total_pro)?></td>
                <td class="style4" align="center"><?=number_format($total_metaMMD)?></td>
                <td class="style4" align="center"><?=number_format($total_difMM)?></td>
                <td class="style4" align="center"><?=number_format($total_pro_kg)?></td>
                <td class="style4" align="center"><?=number_format($total_metaMD)?></td>
                <td class="style4" align="center"><?=number_format($total_difM)?></td>
                <td class="style4" align="center"><?=number_format($Ttira)?></td>
                <td class="style4" align="center"><?=number_format($Ttroquel)?></td>
                <td class="style4" align="center"><?=number_format($Tsegundas)?></td>
            </tr> 
        </table>	
        <br /><br /></td>
</tr>
<tr style="page-break-before:always;">
	<td style=" padding:12px;" >
        <p align="center">PAROS(motivos)</p>
        <table width="357" align="center">
            <tr>
                <td  align="center" valign="middle"><h3>Fecha</h3></td>
                <td width="27%" align="center" valign="middle"><h3>Total_turnos</h3></td>
                <td width="26%"  align="center"><h3>Mantto.</h3></td>
                <td width="23%"  align="center"><h3>Oper.</h3></td>
                <td width="24%"  align="center"><h3>Otro</h3></td>
            </tr>
            <? for($t = 0; $t < sizeof($produccion_mi);$t++){?>
            <tr <?=cebra($t)?>>
                <td class="" align="center"><?=fecha($fecha[$t])?></td>
                <td class="style5" align="center"><? printf("%.1f",(($turnos[$t]/60)/60)/(24/3))?></td>
                <td class="style5" align="center"><? printf("%.1f",(($mantto[$t]/60)/60)/(24/3))?></td>
                <td class="style5" align="center"><? printf("%.1f",(($falta[$t]/60)/60)/(24/3))?></td>
                <td class="style5" align="center"><? printf("%.1f",(($otras[$t]/60)/60)/(24/3))?></td>
            </tr> 
            <? } ?>
            <tr style="background-color:#EEEEEE">
            	<td></td>
                <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_turnos/60)/60)/(24/3))?></td>
                <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_mannto/60)/60)/(24/3))?></td>
                <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_falta/60)/60)/(24/3))?></td>
                <td class="style4" style="height:25px;" align="center"><? printf("%.1f",(($total_otras/60)/60)/(24/3))?></td>
            </tr>            
        </table><br />
    </td>
    <td style=" padding:14px;" >
		<table width="82%" align="center">
          <tr>
            <td width="60%"><h3>Total_prod._Mi: </h3></td>
            <td width="40%" align="right"><?=number_format($Total_produccionMi*$dias)?></td>
          </tr>
          <tr>
            <td width="60%"><h3>Promedio_Mi: </h3></td>
            <td width="40%" align="right"><?=number_format($Total_produccionMi)?></td>
          </tr>
          <tr>
            <td width="60%"><h3>Total_prod._Kg: </h3></td>
            <td width="40%" align="right"><?=number_format($Total_produccionKg*$dias)?></td>
          </tr>
          <tr>
            <td><h3>Promedio_Kg: </h3></td>
            <td align="right"><?=number_format($Total_produccionKg)?></td>
          </tr>
          <tr>
            <td><h3>Dias_trabajados: </h3></td>
            <td align="right"><?=$dias?></td>
          </tr>
        </table> 
     </td> 
   </tr>
	<tr style="">
	<td style="padding:14px;">
        <p align="center">PRODUCCION POR MAQUINA</p>
        <table width="391" align="center">
		<tr>
        	<td width="52"></td>
       	   	<td colspan="3" align="center"><h3>Millares</h3></td>
        	<td colspan="3" align="center"><h3>Kilogramos</h3></td>
        </tr>        
          <tr>
            <td width="52" valign="middle"><h3>Maq.</h3></td>
            <td width="54" ><h3>Prod. </h3></td>
            <td width="50" ><h3>Meta</h3></td>
            <td width="48" ><h3>Dif.</h3></td>
            <td width="51" ><h3>Prod</h3></td>
            <td width="49" ><h3>Meta</h3></td>
            <td width="55" ><h3>Dif.</h3></td>
          </tr>
          <? for($t = 0; $t < sizeof($produccionM);$t++){?>
          <tr <?=cebra($t)?>>
            <td class="style5" align="center"><?=$numero[$t]?></td>
            <td class="style5" align="center"><?=number_format($produccionMM[$t])?></td>
            <td class="style5" align="center"><?=number_format($metaMaquinaM)?></td>
            <td class="style5" align="center"><?=number_format($diferenciaMM[$t])?></td>
            <td class="style5" align="center"><?=number_format($produccionM[$t])?></td>
            <td class="style5" align="center"><?=number_format($metaMaquina)?></td>
            <td class="style5" align="center"><?=number_format($diferenciaMa[$t])?></td>
          </tr>
          <? } ?>
          <tr style="background-color:#EEEEEE">
            <td class="style5" align="center"><h3>SUMAS</h3></td>
            <td class="style4" align="center"><?=number_format($total_proMM)?></td>
            <td class="style4" align="center"><?=number_format($total_metaMM)?></td>
            <td class="style4" align="center"><?=number_format($total_difMM)?></td>
            <td class="style4" align="center"><?=number_format($total_proM)?></td>
            <td class="style4" align="center"><?=number_format($total_metaM)?></td>
            <td class="style4" align="center"><?=number_format($total_difM)?></td>
          </tr>
        </table><br /><br />
    </td>
	<td style=" padding:7px" align="center">
    <p align="center">TURNOS PARADOS x MAQUINA</p>
        <table width="83%" align="center">
        	<tr>
            	<td colspan="4"><h3>&nbsp;</h3></td>
            </tr>
            <tr>
                <td width="34%" valign="middle" ><h3>T._Turnos</h3></td>
                <td width="27%" ><h3>Mantto.</h3></td>
                <td width="20%" ><h3>Oper</h3></td>
                <td width="19%" ><h3>Otro</h3></td>
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
		</table><br /><br />
     </td>
</tr>    
</table>
</form>
</div>
</div>
<? } ?>

<? if($_REQUEST['tipo'] == 57){ 
$fIni	=	fecha_tablaInv($_REQUEST['fecha_mpb']);
$ffin	=	fecha_tablaInv($_REQUEST['fecha_mpfb']);


$fi	=	explode('-',$_REQUEST['fecha_mpb']);
$fn	=	explode('-',$_REQUEST['fecha_mpfb']);

$numero_dias	=	$fn[0]-$fi[0]+1;
$numero_turnos	=	$numero_dias*3;

if($real_milla)
	$numero_turnos	=	$valor_seg/$numero_turnos;
else
	$numero_turnos 	=	0;

$qSuper	=	
			" SELECT ".
			" COUNT(DISTINCT fecha)/3 AS fecha, ".
			" COUNT(resumen_maquina_bs.id_resumen_maquina_bs) AS turnos, ".
			" SUM(resumen_maquina_bs.millares) AS millares,".
			" SUM(resumen_maquina_bs.kilogramos) AS kilogramos,".
			" SUM(bolseo.dtira) AS tira,".
			" SUM(bolseo.dtroquel) AS troquel,".
			" supervisor.nombre ,".
			" SUM(bolseo.segundas) AS segundas".
			" FROM bolseo ".
			" LEFT JOIN supervisor 			ON bolseo.id_supervisor 		= supervisor.id_supervisor ".				
			" LEFT JOIN resumen_maquina_bs 	ON resumen_maquina_bs.id_bolseo = bolseo.id_bolseo ".				
			" WHERE supervisor.area3 = 1 AND fecha BETWEEN '".$fIni."' AND '".$ffin."' AND resumen_maquina_bs.millares != 0 AND resumen_maquina_bs.kilogramos != 0".
			" GROUP BY supervisor.id_supervisor ORDER BY supervisor.id_supervisor DESC";

			
$rSuper	=	mysql_query($qSuper);
$nSuper	=	mysql_num_rows($rSuper);
for($a=0;$dSuper	=	mysql_fetch_assoc($rSuper);$a++){
	$super			=	explode(" ", $dSuper['nombre']);
	$nombre[$a]		=	$super[0].' '.$super[1]{0};
	$millares[$a]	=	$dSuper['millares'];
	$kilogramos[$a]	=	$dSuper['kilogramos'];
	$tira[$a]		=	$dSuper['tira']/$nSuper	;
	$troquel[$a]	=	$dSuper['troquel']/$nSuper	;
	$segundas[$a]	=	$dSuper['segundas']/$nSuper	;
	$turnos[$a]		=	$dSuper['turnos'];
	$fecha[$a]		=	$dSuper['fecha'];
}



$qSuper2	=	
			" SELECT ".
			" SUM(bolseo.dtira) AS tira,".
			" SUM(bolseo.dtroquel) AS troquel,".
			" SUM(bolseo.segundas) AS segundas".
			" FROM bolseo ".
			" WHERE fecha BETWEEN '".$fIni."' AND '".$ffin."' ".
			" GROUP BY bolseo.id_supervisor ORDER BY bolseo.id_supervisor DESC";
$rSuper2	=	mysql_query($qSuper2);
$nSuper2	=	mysql_num_rows($rSuper2);
for($a=0;$dSuper2	=	mysql_fetch_assoc($rSuper2);$a++){
	$tira[$a]		=	$dSuper2['tira'];
	$troquel[$a]	=	$dSuper2['troquel'];
	$segundas[$a]	=	$dSuper2['segundas'];
}

	$qSuperT	=	
				" SELECT ".
				" COUNT(DISTINCT fecha)/3 AS fecha, ".
				" COUNT(id_resumen_maquina_bs) AS turnos, ".
				" supervisor.nombre ,".
				" supervisor.id_supervisor AS id_super".
				" FROM bolseo ".
				" LEFT JOIN supervisor 			ON bolseo.id_supervisor 		= supervisor.id_supervisor ".				
				" LEFT JOIN resumen_maquina_bs 	ON resumen_maquina_bs.id_bolseo = bolseo.id_bolseo ".				
				" WHERE supervisor.area3 = 1 AND fecha BETWEEN '".$fIni."' AND '".$ffin."' AND resumen_maquina_bs.millares = 0 AND resumen_maquina_bs.kilogramos = 0".
				" GROUP BY supervisor.id_supervisor ORDER BY supervisor.id_supervisor DESC";
	//echo $qSuperT;
	$rSuperT	=	mysql_query($qSuperT);
	$nSuperT	=	mysql_num_rows($rSuperT);
	
	for($a=0;$dSuperT	=	mysql_fetch_assoc($rSuperT);$a++){
		$turnosT[$a]	=	$dSuperT['turnos'];
		$fechaT[$a]		=	$dSuperT['fecha'];
		$id_superT[$a]	=	$dSuperT['id_super'];
	}	
	
?>
<div align="center" id="tablaimpr">
	<div class="tablaCentrada titulos_b" id="tabla_reporte">
	<p class="titulos_reportes titulos_b" align="center">Concentrado de Bolseo por Supervisor. <br />DEL 
	<?=fecha($fIni)?> al <?=fecha($ffin);?></p><br /><br />
<table align="center" width="750px" >
        <tr>
            <td></td>
            <td colspan="2"><h3>Trabajados</h3></td>
            <td colspan="2"><h3>Parados</h3></td>
            <td colspan="2"><h3>Total</h3></td>
            <td ><h3>Total</h3></td>
            <td><h3>Promedio</h3></td>
            <td><h3>Total</h3></td>
            <td><h3>Promedio</h3></td>
            <td><h3>Promedio</h3></td>
        </tr>
        <tr>
            <td><h3>Supervisor</h3></td>
            <td><h3>Turnos</h3></td>
            <td><h3>Dias</h3></td>
            <td><h3>Turnos</h3></td>
            <td><h3>Dias</h3></td>
            <td><h3>Turnos</h3></td>
            <td><h3>Dias</h3></td>        
            <td><h3>Millares</h3></td>
            <td><h3>Diario</h3></td>
            <td><h3>Kilos</h3></td>
            <td><h3>Kg x Dia</h3></td>
            <td><h3>Kgs/mill</h3></td>
        </tr>
        <? 
		for($a=0;$a<sizeof($nombre);$a++){
			if($real_milla)
				$total_kilos_t	=	$millares[$a]*$valor_ppm;
			else
				$total_kilos_t	=	$kilogramos[$a]
		?>
        <tr <?=cebra($a+1)?>>
           	<td class="style5"><?=$nombre[$a]?></td>
			<td align="center"  class="style5"><?=$turnos[$a]?></td>
          	<td align="center" class="style5"><?=number_format($fecha[$a],2)?></td>
          	<td align="center" class="style5"><?=$turnosT[$a]?></td>
          	<td align="center" class="style5"><?=number_format($fechaT[$a],2)?></td>
          	<td align="center" class="style5"><? printf("%.2f",$turnos[$a]+$turnosT[$a])?></td>
          	<td align="center" class="style5"><? printf("%.2f",$fechaT[$a]+$fecha[$a])?></td>
          	<td align="center" class="style5"><?=number_format($millares[$a],2)?></td>
          	<td align="center" class="style5"><?=number_format($millares[$a]/$fecha[$a],2)?></td>
          	<td align="center" class="style5"><?=number_format($total_kilos_t,2)?></td>
          	<td align="center" class="style5"><?=number_format($total_kilos_t/$fecha[$a],2)?></td>
          	<td align="center" class="style5"><?=number_format($total_kilos_t/$millares[$a],2)?></td>
            <? 	$Tturnos	+=	$turnos[$a];	
				$Tfecha		+=	$fecha[$a];	
				$TturnosT	+=	$turnosT[$a];
				$TfechaT	+=	$fechaT[$a];	
				$TturnosTT	+=	$turnos[$a]+$turnosT[$a];
				$TfechaTT	+=	$fechaT[$a]+$fecha[$a];
				$Tmilla		+=	$millares[$a];
				$Tkilo		+=	$total_kilos_t;
							
				
            	$TM		=	$Tkilo/$Tmilla;?>
      	</tr>    
        <? } 	$TMP		=	($millares[0]/$fecha[0])+($millares[1]/$fecha[1]);	
				$TKP		=	$Tkilo/$Tfecha;?>
         <tr >
           	<td class="style5"></td>
			<td align="center"><?=$Tturnos?></td>
          	<td align="center"><?=number_format($Tfecha,2)?></td>
          	<td align="center"><?=number_format($TturnosT)?></td>
          	<td align="center"><?=number_format($TfechaT,2)?></td>
          	<td align="center"><?=number_format($TturnosTT)?></td>
          	<td align="center"><?=number_format($TfechaTT,2)?></td>
          	<td align="center"><?=number_format($Tmilla,2)?></td>
          	<td align="center"><?=number_format($TMP/sizeof($nombre),2)?></td>
          	<td align="center"><?=number_format($Tkilo,2)?></td>
          	<td align="center"><?=number_format($TKP,2)?></td>
          	<td align="center"><?=number_format($TM,2)?></td>
      	</tr>           
    </table>
<br />
<br />
    <table width="750px" align="center" >
    <tr>
            <td width="107"></td>
        <td colspan="6"  class="titulos_de">Desperdicio</td>
        </tr>        
        <tr>
          <td  class="titulos_de">Supervisor</td>
          <td width="109" align="center"  class="titulos_de">Tira</td>
          <td width="91" align="center"  class="titulos_de">Tira</td>
          <td width="118" align="center"  class="titulos_de">Troquel</td>
          <td width="94" align="center"  class="titulos_de">Troquel</td>
          <td width="100" align="center"  class="titulos_de">Segunda</td>
          <td width="87" align="center"  class="titulos_de">Segunda</td>
      </tr>
        <? for($a=0;$a<sizeof($nombre);$a++){ 
			if($real_milla)
				$total_kilos_t	=	$millares[$a]*$valor_ppm;
			else
				$total_kilos_t	=	$kilogramos[$a];
		
		$Total_tira[$a]	=	$tira[$a]/($total_kilos_t+$troquel[$a]+$tira[$a])*100;
		$Total_kilo[$a]	=	$troquel[$a]/($total_kilos_t+$troquel[$a]+$tira[$a])*100;
		?>
      <tr <?=cebra($a+1)?>>
            <td class="style5"><?=$nombre[$a]?></td>
        <td align="center" class="style5"><?=number_format($tira[$a],2)?></td>
        <td align="center" class="style5"><? printf("%.2f", $Total_tira[$a])?> %</td>
        <td align="center" class="style5"><?=number_format($troquel[$a],2)?></td>
        <td align="center" class="style5"><? printf("%.2f", $Total_kilo[$a])?> %</td>
        <td align="center" class="style5"><?=number_format($segundas[$a]+$numero_turnos,2)?></td>
        <td align="center" class="style5"><? printf("%.2f", (($segundas[$a]+$numero_turnos)/$total_kilos_t)*100)?> %</td>
      </tr>	<? 
	  $tTira	+=	$tira[$a];
	  $tTroquel	+=	$troquel[$a];
	  $tSegundas	+=	$segundas[$a]+$numero_turnos;
	  
	  } 
	  $tpTira	 = 	$Tkilo + $tTira + $tTroquel ;
	  $tTSegundas	=	$tSegundas/$Tkilo;
	  ?>
      
      <tr >
        <td class="style5"></td>
        <td align="center"><?=number_format($tTira,2)?></td>
        <td align="center"><?=number_format(($tTira/$tpTira)*100,2)?> %</td>
        <td align="center"><?=number_format($tTroquel,2)?></td>
        <td align="center"><?=number_format(($tTroquel/$tpTira)*100,2)?> %</td>
        <td align="center"><?=number_format($tSegundas,2)?></td>
        <td align="center"><? printf("%.2f",$tTSegundas*100)?> %</td>
      </tr>      
    </table><br /><br />
</div></div>
<? } ?>
<tr>
	<td align="center">
    <div id="mensajes"></div>
    <p><input type="button" value="Imprimir" class="link button1" /><br /><br /><br /></p></td>
</tr>