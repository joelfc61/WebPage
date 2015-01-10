<body onLoad="myajax = new isiAJAX();"><? if($_REQUEST['tipo'] == 30 || $_REQUEST['tipo'] == 1 || $_REQUEST['tipo'] == 6 || $_REQUEST['tipo'] == 11  ){

if($_REQUEST['tiempo'] == 0){

//$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
 $desde ="2013-01-03";
if(!isset($_REQUEST['hasta']))

//$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);      Esta es la buena
$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $desde);
else

$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hasta']);


$revisa = explode("-", $desde);

$ano = intval($revisa[0]);

$mesM = intval($revisa[1]);

$mesf = intval($revisa[1]);

$ultimo_dia = UltimoDia($ano,$mesM);

$mesM = num_mes_cero($ano.'-'.$mesM.'-01');

$mesM = $ano.'-'.$mesM.'-01';

$dia = intval($revisa[2]);

}


 


 $qMetasBolseo = "SELECT * FROM meta WHERE mes = '".$mesM."'  AND area = '3'";



 $rMetasBolseo = mysql_query($qMetasBolseo);



 $dMetasBolseo = mysql_fetch_assoc($rMetasBolseo);



  

 $qMetas = "SELECT * FROM meta WHERE mes = '".$mesM."'  AND area = '1'";



 $rMetas = mysql_query($qMetas);



 $dMetas  = mysql_fetch_assoc($rMetas);	

 



 $qMetasImp = "SELECT * FROM meta WHERE mes = '".$mesM."'  AND area = '2'";



 $rMetasImp = mysql_query($qMetasImp);



 $dMetasImp = mysql_fetch_assoc($rMetasImp);




// BOLSEO

 



  


$qMaquinas2 = "SELECT SUM(kilogramos), SUM(millares), fecha, SUM(dtira), SUM(dtroquel), SUM(segundas), SUM(m_p) FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND autorizada = 1 GROUP BY fecha ORDER BY fecha ASC";
  
$rMaquinas2 = mysql_query($qMaquinas2);

$nMaquinas2 = mysql_num_rows($rMaquinas2);

$qMaquinasMatutino = "SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 1 AND autorizada = 1 ORDER BY fecha ASC";

$qMaquinasVespertino = "SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 2 AND autorizada = 1 ORDER BY fecha ASC";

$qMaquinasNocturno = "SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 3 AND autorizada = 1 ORDER BY fecha ASC";



$rMaquinasMatutino = mysql_query($qMaquinasMatutino);


$rMaquinasVespertino =mysql_query($qMaquinasVespertino);


$rMaquinasNocturno = mysql_query($qMaquinasNocturno);

$dMaquinasMatutino = mysql_fetch_row($rMaquinasMatutino);


$dMaquinasVespertino = mysql_fetch_row($rMaquinasVespertino);


$dMaquinasNocturno = mysql_fetch_row($rMaquinasNocturno);




$qMaquinas4 = "SELECT SUM(kilogramos), SUM(millares), fecha, SUM(dtira), SUM(dtroquel), SUM(segundas) FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND autorizada = 1 GROUP BY fecha ORDER BY fecha ASC";
  
$rMaquinas4 = mysql_query($qMaquinas4);




// EXTRUDER

$qEntrada = "SELECT SUM(orden_produccion.total), fecha, SUM(desperdicio_duro), SUM(desperdicio_tira), SUM(k_h) FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND autorizada = 1 GROUP BY fecha ORDER BY fecha ASC";

$rEntrada = mysql_query($qEntrada);


$qEntradaMatutino = "SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 1 AND autorizada = 1  ORDER BY fecha ASC";

$qEntradaVespertino = "SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 2 AND autorizada = 1  ORDER BY fecha ASC";

$qEntradaNocturno = "SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 3 AND autorizada = 1  ORDER BY fecha ASC";

$rEntradaMatutino =mysql_query($qEntradaMatutino);

$rEntradaVespertino =mysql_query($qEntradaVespertino);

$rEntradaNocturno =mysql_query($qEntradaNocturno);

$dEntradaMatutino = mysql_fetch_row($rEntradaMatutino);

$dEntradaVespertino = mysql_fetch_row($rEntradaVespertino);

$dEntradaNocturno = mysql_fetch_row($rEntradaNocturno);

$nEntrada = mysql_num_rows($rEntrada);


//IMPRESION

$qEntrada2 = "SELECT SUM(impresion.total_hd), fecha, SUM(impresion.desperdicio_hd) , SUM(impresion.k_h) FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND autorizada = 1  GROUP BY fecha ORDER BY fecha ASC";

$rEntrada2 = mysql_query($qEntrada2);

$qEntradaMatutino2 = "SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 1 AND autorizada = 1  ORDER BY fecha ASC";

$qEntradaVespertino2 = "SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 2 AND autorizada = 1  ORDER BY fecha ASC";

$qEntradaNocturno2 = "SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 3 AND autorizada = 1  ORDER BY fecha ASC";

$rEntradaMatutino2 =mysql_query($qEntradaMatutino2);

$rEntradaVespertino2=mysql_query($qEntradaVespertino2);

$rEntradaNocturno2=mysql_query($qEntradaNocturno2);

$dEntradaMatutino2=mysql_fetch_row($rEntradaMatutino2);

$dEntradaVespertino2=mysql_fetch_row($rEntradaVespertino2);

$dEntradaNocturno2=mysql_fetch_row($rEntradaNocturno2);

$nEntrada2=mysql_num_rows($rEntrada2); 
?>
<script type="text/javascript" language="javascript">function genera(){document.metas.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?><? if(isset($_REQUEST['tiempo'])){ ?>&tiempo=<?=$_REQUEST['tiempo']; } ?><? if(isset($_REQUEST['anho'])){ ?>&anho=<?=$_REQUEST['anho']; } ?><? if(isset($_REQUEST['mes'])){ ?>&mes=<?=$_REQUEST['mes']; } ?><? if(isset($desde)){ ?>&desde=<?=$desde; }?><? if(isset($_REQUEST['hasta'])){ ?>&hasta=<?=$_REQUEST['hasta']; }?>";document.metas.submit();}</script><div align="center" id="tablaimpr">   
 
<div class="tablaCentrada" id="tabla_reporte">        
<p class="titulos_reportes" align="center" >PRODUCCION DIARIO DE EXTRUDER, IMPRESION Y BOLSEO<br>        DEL <?=$dia?> DE <?=$mes[$mesf]?> DEL <?=$ano?></p><form name="metas" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&accion=nuevo" id="super" method="post" >  <table width="90%" align="center">    <tr>      
<td align="center"><? if($_REQUEST['tipo'] == 1 || $_REQUEST['tipo'] == 30){?>    <? 
if($nEntrada == 1 ){ 
$dEntrada= mysql_fetch_row($rEntrada);?>          
<table width="95%" align="center" class="titulos_e" style="margin: 5px 5px;" >            
<tr class="titulos_e">  <td colspan="11"><h3 align="center">PRODUCCION EXTRUDER ALTA DENSIDAD</h3></td></tr>            
<tr>             
 <td width="80" align="center" ><h3>Turno</h3></td> 
 <td width="120" align="center"><h3>SUPERVISOR</h3></td>             
 <td width="150" align="center" ><h3>PRODUCCION</h3></td>                    
 <td width="150" align="center" ><h3>DESPERDICIO</h3></td>
 <td width="100" align="center" ><h3>%DESP.</h3></td> 
 <td width="80" align="center"><h3>Kg/H</h3></td>
 <td width="80" align="center"><h3>T.MUERTOS</h3></td> 
</tr>            

<tr align="right">              
<td width="114" align="center"><h3>MATUTINO</h3></td><td class="style5">Supervisor Mat</td>
<td align="right" class="style5"> <b> 
<a href="<?=$_SERVER['HOST']?>?seccion=33&accion=extruder&id_entrada_general=<?=$dEntradaMatutino[3]?>" style="text-decoration:none; color:#000000">                
<?=number_format($dEntradaMatutino[0]);?> </a></b></td>                      
<td align="right" class="style5"><b><?=number_format($total_desper = $dEntradaMatutino[1] + $dEntradaMatutino[2])?></b></td>                      
<td align="center" class="style5"><?=number_format(($dEntradaMatutino[3])/($total_desper+$dEntradaMatutino[3]))?></td>
<td align="right" class="style5"><?=number_format($dEntradaMatutino[0]/8);?></td>           
<td align="right" class="style5">TMMAT</td>           
 </tr>           
 <tr align="right">             
 <td width="114" align="center"><h3>VESPERTINO</h3></td>  <td class="style5">Supervisor Vesp.</td>              
<td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=extruder&id_entrada_general=<?=$dEntradaVespertino[3]?>" 
style="text-decoration:none; color:#000000">  <?=number_format($dEntradaVespertino[0]);?>    </a></b></td>                      
<td align="right" class="style5"><b><?=number_format($total_desperVesper = $dEntradaVespertino[1] + $dEntradaVespertino[2]);?></b></td>                      
<td align="center" class="style5"><?=number_format($total_desperVesper = ($dEntradaVespertino[3])/($total_desperVesper + $dEntradaVespertino[3]));?></td>
<td align="right" class="style5"><?=number_format($dEntradaVespertino[0]/7);?></td>            
<td align="right" class="style5">TMVESP</td>
</tr>            
<tr align="right" >              
<td width="114" align="center"><H3>NOCTURNO</H3></td> <TD class="style5">Supervisor Noct.</TD>
<td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=extruder&id_entrada_general=<?=$dEntradaNocturno[3]?>" 
style="text-decoration:none; color:#000000"> <?=number_format($dEntradaNocturno[0]);?>   </a></b></td>                      
<td align="right" class="style5"><b><?=number_format($total_despeNocturno = $dEntradaNocturno[1] + $dEntradaNocturno[2])?></b></td> 
<td align="center" class="style5"> <?=number_format(100*($total_despeNocturno)/($total_despeNocturno+$dEntradaNocturno[0])) ?></td>
<td align="right" class="style5"><?=number_format($dEntradaNocturno[0]/9);?></td>  
<td align="right" class="style5">TMNOCT</td>
</tr>            
<tr align="right" >
<td width="114" align="center"><h3>Total</h3></td><td align="right" bgcolor="#DDDDDD" class="style5">...</td>              
<td align="right" bgcolor="#DDDDDD" class="style5"><b> <?=number_format($dEntrada[0])?> </b></td>                              
<td align="right" bgcolor="#DDDDDD" class="style5"><b><?=number_format($total_desper = $dEntrada[2] + $dEntrada[3])?></b></td>                              
<td  align="center" bgcolor="#DDDDDD" class="style5"><?=number_format(100*($total_desper)/($total_desper+$dEntrada[0])) ?></td>
<td align="right" class="style5" bgcolor="#DDDDDD"><?=number_format($dEntrada[0]/24);?></td>
<td align="right" class="style5" bgcolor="#DDDDDD">TMTOT</td>
</tr>          
</table>        
<? 
} 
?>          
<br><br>          
<?
 } 
 if($_REQUEST['tipo'] == 6 || $_REQUEST['tipo'] == 30){?>          
 <? if($nEntrada2 == 1){ $dEntrada2 = mysql_fetch_row($rEntrada2); ?>  
 <table width="95%" class=" titulos_i" style="margin: 15px 5px;" align="center"> 

<tr> <td colspan="11"><h3 align="center">PRODUCCION IMPRESION ALTA DENSIDAD</h3></td>       </tr>            
<tr>              
<td width="80" align="center" ><h3>Turno</h3></td>              
<td width="120" align="center" ><h3>SUPERVISOR</h3></td>              
<td width="150"align="center" ><h3>Produccion</h3></td>             
<td width="150" align="center" ><h3>Desperdicio</h3></td>              
<td width="100" align="center"><h3>% Desp.</td>
<td width="80" align="center" ><h3>Kg/H</h3></td>     
<td width="80" align="center" ><h3>T.MUERTOS</h3></td> 
</tr>    
<tr>             
 <td width="76" align="center"><h3>Matutino</h3></td> <td class="style5">Supervisor Mat.</td>             
 <td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=impresion&id_entrada_general=<?=$dEntradaMatutino2[2]?>" 
style="text-decoration:none; color:#000000">   <?=number_format($dEntradaMatutino2[0]);?>      </a></b></td>  
<td align="right" class="style5"><b><?=number_format($total_desperImprM = $dEntradaMatutino2[1])?></b></td>              
<td align="right" class="style5">Desp.Mat. </td>
<td align="right" class="style5"><?=number_format($dEntradaMatutino2[0]/8);?></td>            
<td align="right" class="style5">TMMAT</td>
</tr>            
<tr>              
<td width="76" align="center"> <h3>Vespertino</h3></td>  <TD class="style5">Supervisor Vesp.</TD>             
<td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=impresion&id_entrada_general=<?=$dEntradaVespertino2[2]?>" 
style="text-decoration:none; color:#000000"> <?=number_format($dEntradaVespertino2[0]);?> </a></b></td>              
<td align="right" class="style5"><b><?=number_format($total_desperImprV = $dEntradaVespertino2[1])?></b></td>              
<td align="right" class="style5">Desp.Vesp. </td>
 <td align="right" class="style5"><?=number_format($dEntradaVespertino2[0]/7);?></td>            
 <td align="right" class="style5">TMVESP</td>
 </tr>            
 <tr>             
 <td width="76" align="center"><h3>Nocturno</h3></td>   <TD class="style5">Supervisor Noct.</TD>             
 <td align="right" class="style5"><b>  <a href="<?=$_SERVER['HOST']?>?seccion=33&accion=impresion&id_entrada_general=<?=$dEntradaNocturno2[2]?>" style="text-decoration:none; color:#000000">  <?=number_format($dEntradaNocturno2[0]);?>              </a></b></td>                            
<td align="right" class="style5"><b><?=number_format($total_desperImprN = $dEntradaNocturno2[1])?></b></td>              
<td align="right" class="style5">Desp.Noct. </td>
<td align="right" class="style5"><?=number_format($dEntradaNocturno2[0]/9);?></td>
<td align="right" class="style5">TMNOCT</td>
</tr>    
<tr>              
<td width="76" align="center"><h3>Total</h3></td>  <td align="right" bgcolor="#DDDDDD" class="style5">...</td>     
<td align="right" bgcolor="#DDDDDD" class="style5"><b>  <?=number_format($dEntrada2[0]);?> </b></td>              
<td align="right" bgcolor="#DDDDDD" class="style5"><b><?=number_format($total_desperImpr = $dEntrada2[2])?></b></td>                        
<td align="right" bgcolor="#DDDDDD" class="style5">Tot.Desp. </td>
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dEntrada2[0]/24);?></td> 
<td align="right" class="style5" bgcolor="#DDDDDD">TMTOT</td>
</tr>          
</table>        
<?  
} 
?>          
<br>        <br>          
<?  } if($_REQUEST['tipo'] == 11 || $_REQUEST['tipo'] == 30){ ?>          <? if($nMaquinas2 == 1 ){ $dMaquinas2= mysql_fetch_row($rMaquinas2); ?>          
<table class="titulos_b" width="95%" style="margin: 15px 5px;" align="center">            <tr>              
<td colspan="11"><h3 align="center">PRODUCCION CAMISETA</h3></td>            </tr>            <tr>              
<td width="80" align="center"><h3>Turno</h3></td>              
<td width="120" align="center"><h3>SUPERVISOR</h3></td>              
<td width="200" align="center"><h3>Kg</h3></td>              
<td width="200" align="center"><h3>Mi</h3></td>              
<td width="200" align="center"><h3>Mi/H</h3></td>            
</tr>            
<tr>              
<td align="right"><h3>Matutino</h3></td>     
<td class="style5">Supervisor Mat.</td>              
<td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasMatutino[5]?>" style="text-decoration:none;  color:#000000"> 
<? 
if($real_milla) { 
 $kg_reales= $valor_ppm * $dMaquinasMatutino[1];
} else { $kg_reales = $dMaquinasMatutino[0]; 
} echo number_format($kg_reales);
?>              
</a></b>
</td>              

<td align="right" class="style5"><b><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasMatutino[5]?>" 
style="text-decoration:none;  color:#000000"><?=number_format($dMaquinasMatutino[1]);?>              </a></b></td>              

<td width="80" align="right" class="style5"><?=number_format($dMaquinasMatutino[1]/8);?></td>            </tr>            <tr>              

<td align="right"><h3>Vespertino</h3></td>              
<td class="style5">Supervisor Vesp.</td>              
<td align="right" class="style5"><b>
<a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasVespertino[5]?>"  style="text-decoration:none;  color:#000000">  
<? 
 if($real_milla) { 
 $kg_reales_vesp= $valor_ppm * $dMaquinasVespertino[1];
} else 
 { 
$kg_reales_vesp = $dMaquinasVespertino[0];
}

 echo number_format($kg_reales_vesp);
?>              
</a></b>
</td>

<td align="right"><a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasVespertino[5]?>" 
style="text-decoration:none;  color:#000000">                <?=number_format($dMaquinasVespertino[1])?>              </a></td>              
              
<td align="right" class="style5"><?=number_format($dMaquinasVespertino[1]/7)?></td>            
</tr>            
<tr>        
<td align="right"><h3>Nocturno</h3></td>              
<td class="style5">Supervisor Noct.</td>              
<td align="right" class="style5"><b>
<a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasNocturno[5]?>" style="text-decoration:none;  color:#000000">                
<? 
if($real_milla) { 
 $kg_reales_noc= $valor_ppm * $dMaquinasNocturno[1];
} else { $kg_reales_noc = $dMaquinasNocturno[0]; }

echo number_format($kg_reales_noc)?>              
</a></b></td>              


<td align="right">
<a href="<?=$_SERVER['HOST']?>?seccion=33&accion=bolseo&id_bolseo=<?=$dMaquinasNocturno[5]?>" style="text-decoration:none;  color:#000000">                <?=number_format($dMaquinasNocturno[1])?>              </a>
</td>              

<td align="right" class="style5"><?=number_format($dMaquinasNocturno[1]/9)?></td>            </tr>    
<? 
$totales = $kg_reales+$kg_reales_vesp+$kg_reales_noc; 
?>
<tr>
<td  align="right"><h3>Total</h3></td><td align="right" bgcolor="#DDDDDD" class="style5">...</td>
<td align="right" bgcolor="#DDDDDD" class="style5"><b><?=number_format($totales)?></b></td>
  
<td align="right" bgcolor="#DDDDDD" class="style5"><b><?=number_format($dMaquinas2[1]);?></b></td>


<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMaquinas2[1]/24);?></td>    </tr>   
 </table>    
 <? 
 }
 ?>    
 <br>    
 <? 
 if($nMaquinas2 == 1 ){ 
 ?>    
 <?
 while ($dMaquinas4 = mysql_fetch_row($rMaquinas4)){ ?>    <table class="titulos_b" style="margin: 15px 5px;" width="95%" align="center">    
<tr >      

<td width="80" align="center" ><h3>Turno</h3></td>      
<td width="200" align="center" ><h3>Tira</h3></td>      

<td width="200" align="center" ><h3>Troquel</h3></td>      
<td width="200" align="center" ><h3>Segundas</h3></td>      
 </tr>
<td align="right" ><h3>Matutino</h3></td>      
<td align="right" class="style5"><b><?=number_format($dMaquinasMatutino['2'])?></b></td>      
<td align="right" class="style5"><b><?=number_format($dMaquinasMatutino['3'])?></b></td>      
<td align="right" class="style5"><b><?=number_format($dMaquinasMatutino['4']+$vts)?></b></td>      
    </tr>    <tr>        
<td align="right" ><h3>Vespertino</h3></td>        
<td align="right" class="style5"><b><?=number_format($dMaquinasVespertino['2'])?></b></td>        
<td align="right" class="style5"><b><?=number_format($dMaquinasVespertino['3'])?></b></td>        
<td align="right" class="style5"><b><?=number_format($dMaquinasVespertino[4]+$vts)?></b></td>        

</tr>    <tr>     
<td align="right"><h3>Nocturno</h3></td>      
<td align="right" class="style5"><b><?=number_format($dMaquinasNocturno['2'])?></b></td>      

     
<td align="right" class="style5"><b><?=number_format($dMaquinasNocturno['3'])?></b></td>      

  
<td align="right" class="style5"><b><?=number_format($dMaquinasNocturno['4']+$vts)?></b></td>      
    
</tr>    
<?      $TOTAL1= $dMaquinas4['3'];      
$TOTAL2= ($dMetasBolseo['mes_tira']/$ultimo_dia);      
$TOTAL3=$TOTAL2 - $TOTAL1;      
$TOTAL4=$dMaquinas4['4'];
  $TOTAL5 = ($dMetasBolseo['mes_troquel']/$ultimo_dia);
  $TOTAL6 = $TOTAL5 - $TOTAL4 ;
  $TOTAL7 = ($dMaquinas4['5']+$vtsf);
  $TOTAL8 = ($dMetasBolseo['mes_segunda']/$ultimo_dia);
  $TOTAL9 = $TOTAL8 - $TOTAL7; 
}    
?>
<tr bgcolor="#DDDDDD">      
<td align="right"><h3>Total:</h3> </td>     
<td align="right" class="style5"><b><?=number_format($TOTAL1);?></b></td>      
<td align="right" class="style5"><b><?=number_format($TOTAL4);?></b></td>      
<td align="right" class="style5"><b><?=number_format($TOTAL7);?></b></td>            
 </tr>    </table>    <? } 
} ?><br><br>
<table align="center" class="styleTabla">    <tr>

<td  align="center"></td>
</tr>  
</table>    <br />    <br /></td></tr></table></form></div></div><?  }?><?  if($_REQUEST['tipo'] == 31  ){ ?><div align="center" style="width:700px"><div style="background-color:#FFFFFF;" class="navcontainer">
<h3><a style="color: rgb(255, 255, 255);" href="#extruder"><b>REPESADAS</b></a></h3>
<table width="100%"  cellpadding="0" cellspacing="0">
<?


$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desdeRepe']);

$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hastaRepe']);

$qEntradas="SELECT *, repesadas.id_supervisor, repesadas.id_admin  FROM repesadas INNER JOIN entrada_general ON repesadas.id_entrada_general = entrada_general.id_entrada_general"." LEFT JOIN bolseo ON entrada_general.fecha=bolseo.fecha AND entrada_general.turno=bolseo.turno ".
" WHERE entrada_general.actualizado=1 AND bolseo.actualizado=1 AND entrada_general.impresion = 0 ".
" AND entrada_general.fecha_repesada >= '".$desde."' AND entrada_general.fecha_repesada <= '".$hasta."'  ";

if($_REQUEST['id_responsable'] != 'x' && $_REQUEST['id_responsable'] != 'y' )
$qEntradas .=" AND repesadas.id_supervisor = ".$_REQUEST['id_responsable']."";

if($_REQUEST['id_administrator'] != 'x' && $_REQUEST['id_administrator'] != 'y' )

$qEntradas.=" AND repesadas.id_admin = ".$_REQUEST['id_administrator']." ";
    
$qEntradas.=" GROUP BY entrada_general.fecha, entrada_general.turno ORDER BY entrada_general.fecha, entrada_general.turno ASC";
$rEntradas=mysql_query($qEntradas);

$i=0;

$nEntradas=mysql_num_rows($rEntradas);

if($nEntradas> 0 ){

?>

<tr>                
<td style="color:#000000" width="170"><b>Fecha</b></td>       
  <td style="color:#000000" width="281"><b>Nombre</b></td>

  <td width="105" align="center" style="color:#000000"><b>Turno</b></td>          


<td width="71">&nbsp;</td>

    </tr>

                <?php



//Listar todas las entradas; que están pendientes de repesar (bolseo, impreison y extruder)



while($dEntradas
=
mysql_fetch_assoc($rEntradas))



{



?>



<tr onMouseOver="this.style.backgroundColor='#CCC'"
onmouseout="this.style.backgroundColor='#EAEAEA'" style="cursor:default; background-color:#EAEAEA">                      <td width="170"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dEntradas['fecha'])?></td>                  <td width="281" align="left"><?                   $qNombre
=
"SELECT nombre FROM supervisor WHERE id_supervisor = ".$dEntradas['id_supervisor']."";



  $rNombre
=
mysql_query($qNombre);



  $dNombre
=
mysql_fetch_assoc($rNombre);



  $nNombre
=
mysql_num_rows($rNombre);



  if($nNombre < 1 && $dEntradas['id_admin'] != 0){                  $qNombre2
=
"SELECT nombre FROM administrador WHERE id_admin = ".$dEntradas['id_admin']."";



  $rNombre2
=
mysql_query($qNombre2);



  $dNombre
=
mysql_fetch_assoc($rNombre2);



  }



  



  



 echo  $dNombre['nombre'];?></td>                  <td width="105" align="center"><?=$dEntradas['turno']?></td>                  <td align="center"  ><a href="<?=$_SERVER['PHP_SELF']?>
?seccion=7&id_extruder=<?=$dEntradas['id_entrada_general']?>&id_bolseo=<?=$dEntradas['id_bolseo']?>&accion=ver&turno=<?=$dEntradas['turno']?>">Ver</a></td>              </tr>                <?php } 



}  else { ?>                <tr>                
<td colspan="3" align="center"><b>NO HAY REPORTES PENDIENTES</b></td>              </tr> 



<? } if(isset($_GET['exito'])){?>                    <li><center><font color="#FF0000">Registro Actualizado.</font></center></li>                <? } ?>        


                <br />                             </table>            </div>
</div></div><? } ?>  <?  if($_REQUEST['tipo'] == 32 ){ ?><script language="javascript" src="js/isiAJAX.js"></script><script language="javascript">var last;function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;}function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)

myajax.Link('actualiza.php?valor='+valor+'&campo='+campo+'&id='+id+'&tabla=detalle_resumen_maquina_bs&tipo=id_detalle_resumen_maquina_bs');}function genera(){document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&orden=<?=$_REQUEST['orden']?>&modelo=<?=$_REQUEST['modelo'];?>&desdeOrd=<?=$_REQUEST['desdeOrd'];?>&hastaOrd=<?=$_REQUEST['hastaOrd'];?>";document.form.submit();}</script> <? 
$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desdeOrd']);
$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hastaOrd']);
if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "") 
$qOrdenes
=
"SELECT detalle_resumen_maquina_bs.kilogramos AS kilogramos, detalle_resumen_maquina_bs.millares AS millares, bolseo.fecha, detalle_resumen_maquina_bs.orden, bolseo.turno, detalle_resumen_maquina_bs.id_detalle_resumen_maquina_bs, maquina.numero FROM bolseo ".




" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".




" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".




" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".




" WHERE bolseo.fecha BETWEEN '".$desde."' AND '".$hasta."' AND detalle_resumen_maquina_bs.orden LIKE '%".$_REQUEST['orden']."%'  GROUP BY resumen_maquina_bs.id_resumen_maquina_bs ORDER BY bolseo.fecha, bolseo.turno, maquina.id_maquina, detalle_resumen_maquina_bs.orden ASC ";
if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] == "") 
$qOrdenes
=
"SELECT  DAY(fecha) AS dia, SUM(detalle_resumen_maquina_bs.kilogramos) AS kilogramos, SUM(detalle_resumen_maquina_bs.millares) AS millares, fecha, detalle_resumen_maquina_bs.orden, detalle_resumen_maquina_bs.id_detalle_resumen_maquina_bs FROM bolseo ".




" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".




" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".




" WHERE bolseo.fecha >= '".$desde."' AND bolseo.fecha <= '".$hasta."' GROUP BY fecha,detalle_resumen_maquina_bs.orden ORDER BY fecha, detalle_resumen_maquina_bs.orden ASC ";
if($_REQUEST['modelo'] == 1 && $_REQUEST['orden'] != "") 
$qOrdenes
=
"SELECT  SUM(detalle_resumen_maquina_bs.kilogramos) AS kilogramos, SUM(detalle_resumen_maquina_bs.millares) AS millares, bolseo.fecha, detalle_resumen_maquina_bs.orden, bolseo.turno, detalle_resumen_maquina_bs.id_detalle_resumen_maquina_bs, maquina.numero FROM bolseo ".




" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".




" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".




" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".




" WHERE bolseo.fecha BETWEEN '".$desde."' AND '".$hasta."' AND detalle_resumen_maquina_bs.orden LIKE '%".$_REQUEST['orden']."%'  GROUP BY detalle_resumen_maquina_bs.orden ORDER BY fecha, bolseo.turno, maquina.id_maquina, detalle_resumen_maquina_bs.orden, detalle_resumen_maquina_bs.id_detalle_resumen_maquina_bs ASC ";
if($_REQUEST['modelo'] == 1 && $_REQUEST['orden'] == "") 
$qOrdenes
=
"SELECT  DAY(fecha) AS dia,SUM(detalle_resumen_maquina_bs.kilogramos) AS kilogramos, SUM(detalle_resumen_maquina_bs.millares) AS millares, detalle_resumen_maquina_bs.orden FROM bolseo ".




" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".




" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".




" WHERE bolseo.fecha >= '".$desde."' AND bolseo.fecha <= '".$hasta."' GROUP BY detalle_resumen_maquina_bs.orden ORDER BY detalle_resumen_maquina_bs.orden ASC ";


$rOrdenes
= 
mysql_query($qOrdenes);


$revisa 
= explode("-", $desde);



$ano = $revisa[0];

$mes1 = $revisa[1];

$dia = $revisa[2];




$revisa2 
= explode("-", $hasta);



$ano2 = $revisa2[0];

$mes2 = $revisa2[1];

$dia2 = $revisa2[2];




?> <div align="center" id="tablaimpr">   
 
<div class="tablaCentrada" id="tabla_reporte">        <p class="titulos_reportes" align="center">ORDENES TOTALIZADAS EN BOLSEO</p><br>        <form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">          <table width="650px" >                                  <tr>                       
  <td colspan="6" align="center"><h3><b>                            <? if($mes1 == $mes2){ ?>                            Del dia <?=$dia?> al <?=$dia2?> de <?=$mes[intval($mes2)]?> de <?=$ano2?>                            <? } ?>                            <? if($mes1 != $mes2 && $mes2 != 1){ ?>                            Del dia <?=$dia?> de <?=$mes[intval($mes1)]?> al <?=$dia2?> de <?=$mes[intval($mes2)]?> de <?=$ano2?>                            <? } ?>                            <? if($mes1 != $mes2 && $mes2 == 1){ ?>                            Del dia <?=$dia?> de <?=$mes[intval($mes1)]?> de <?=$ano?> al <?=$dia2?> de <?=$mes[intval($mes2)]?> de <?=$ano2?>                            <? } ?>                                                      </b></h3></td>                         </tr>          

<tr>                
<td valign="top" align="center" width="315px">                         <table style="margin:15px;">                          <tr>                            <? if($_REQUEST['modelo'] ==2 ){ ?>                            <td width="14%" align="center"><strong>Fecha</strong></td>                            <? if( $_REQUEST['orden'] != "" ){ ?>                            <td width="14%" align="center"><strong>Turno</strong></td>                            <td width="15%" align="center"><strong>Maquina</strong></td>                            <? } } ?>                            <td width="20%" align="center"><strong>No Orden</strong></td>                            <td width="19%" align="center"><strong>Total Mi.</strong></td>                            <td width="18%" align="center"><strong>Total Kg.</strong></td>                          </tr>                            <? 







for($z = 0; $dOrdenes
=
mysql_fetch_assoc($rOrdenes);$z++){                                 if($dOrdenes['orden'] ==  ""){ $z = $z+1; } else { ?>                            <tr <? 







if($_REQUEST['orden'] == ""  && $_REQUEST['modelo'] != 1 ) 







$color = $dOrdenes['dia'];







else if($_REQUEST['orden'] == ""  && $_REQUEST['modelo'] == 1 )







$color = $z;






else








$color = $z;














if(bcmod(intval($color),2) == 0){ 







$back 
= "#DDDDDD";  







$frente = "#B4C3EC";







echo "bgcolor='".$back."'";







}  else { 







$back 
= "#FFFFFF";  







$frente = "#B4C3EC";







echo "bgcolor='".$back."'";















}?> onMouseOver="this.bgColor='<?=$frente?>'" onMouseOut="this.bgColor='<?=$back?>'">                           
  <? if($_REQUEST['modelo'] == 2 ){ ?>                              <td align="center" class="style7"><b><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dOrdenes['fecha'])?></b></td>                               <? if( $_REQUEST['orden'] != "" ){ ?>                              <td align="center" class="style7"><b><?=$dOrdenes['turno'];?></b></td>                              <td align="center" class="style7"><b><?=$dOrdenes['numero'];?></b></td>                              <? }
 ?>                           
  <td align="center" class="style7">                              <? if($concentrado_ot_mod){?>






  <input size="8" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'orden', <?=$dOrdenes['id_detalle_resumen_maquina_bs'];?>)" class="inputoff" id="a<?=$dOrdenes['id_detalle_resumen_maquina_bs'];?>" value="<?=$dOrdenes['orden']?>" /></td>   






  <? } if(!$concentrado_ot_mod) {?>                              <?=$dOrdenes['orden']?>                              <? } ?>






  <? } else { ?>                           
  <td align="center" class="style7"><?=$dOrdenes['orden']?></td>                    






  <? }  ?>                               <td align="right" class="style5"><b><?=floor($dOrdenes['millares']);?></b></td>                              <td align="right" class="style5"><b><?=floor($dOrdenes['kilogramos']);?></b></td>                          </tr>                            <? 






$TotalMillares
+=  $dOrdenes['millares'];






$TotalKilos

+=
$dOrdenes['kilogramos'];













}  






}  if(($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "") || ($_REQUEST['modelo'] == 1 && $_REQUEST['orden'] == "" )){













if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "" ) $g = 4;






if($_REQUEST['modelo'] == 1 && $_REQUEST['orden'] == "" ) $g = 1;













?>                            <tr>                           
  <td colspan="<?=g?>" align="right">TOTAL:</td>                                <td align="right" class="style5"><b><?=floor($TotalMillares);?></b></td>                                <td align="right" class="style5"><b><?=floor($TotalKilos);?></b></td>                            </tr>                            <? } ?>          </table>          </td>          <?  if(isset($_REQUEST['segundas'])){ 

 $qSegundas
=
"SELECT turno, SUM(bolseo.segundas) AS segundas, fecha  FROM bolseo ".





" WHERE bolseo.fecha >= '".$desde."' AND bolseo.fecha <= '".$hasta."' GROUP BY turno, fecha ORDER BY fecha, turno ASC ";

$rSegundas
= 
mysql_query($qSegundas);


  ?>       <td valign="top" align="center" width="315px">          <table style=" margin:15px;">                          <tr>                            <td width="14%" align="center"><strong>Fecha</strong></td>                            <td width="14%" align="                                                                                                                                                                   center"><strong>Turno</strong></td>                            <td width="18%" align="center"><strong>Total Segundas</strong></td>                          </tr>                            <? 







for($z = 0; $dSegundas
=
mysql_fetch_assoc($rSegundas);$z++){ ?>                            <tr <? $color = $z;






if(bcmod(intval($color),2) == 0){ 







$back 
= "#DDDDDD";  







$frente = "#B4C3EC";







echo "bgcolor='".$back."'";







}  else { 







$back 
= "#FFFFFF";  







$frente = "#B4C3EC";







echo "bgcolor='".$back."'";







}?> onMouseOver="this.bgColor='<?=$frente?>'" onMouseOut="this.bgColor='<?=$back?>'">                              <td align="center" class="style7"><b><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dSegundas['fecha'])?></b></td>                              <td align="center" class="style7"><b><?=$dSegundas['turno'];?></b></td>                              <td align="right" class="style5"><b><?=floor($dSegundas['segundas']);?></b></td>                          </tr>                            <? 






$TotalSeg
+=  $dSegundas['segundas']; }






?>                            <tr>                           
  <td colspan="2" align="right">TOTAL:</td>                                <td align="right" class="style5"><b><?=floor($TotalSeg);?></b></td>                            </tr>          </table>         </td>           <?  } ?>         </tr>         </table>          <table width="100%" border="0" cellpadding="0" cellspacing="0">          
<tr>          

<td><br><br><br><br></td>


</tr> 


<tr>



<td colspan="8" align="center"><input type="button" class="button1" name="pfd" value="Formato PDF" onClick="genera()"></td>


</tr>             </table>                  </form>   
</div></div> <? } ?>  <?  if($_REQUEST['tipo'] == 33 ){    
$meMeta =
$_REQUEST['mes_mp'];

$anho =
$_REQUEST['anho_mp'];
$mesMetacero
=
num_mes_cero($anho.'-'.$meMeta.'-01');
$mesMeta
=
$anho.'-'.$mesMetacero.'-01';
$ultimo_dia = UltimoDia($anho,$meMeta);
$mesFinal
=
$anho.'-'.$mesMetacero.'-'.$ultimo_dia;

 


 
 


 $qMetasBolseo
=
"SELECT * FROM meta WHERE mes = '".$mesMeta."' AND area = '3'";



 $rMetasBolseo
=
mysql_query($qMetasBolseo);



 $dMetasBolseo
=
mysql_fetch_assoc($rMetasBolseo);



 $nMetasBolseo
=
mysql_num_rows($rMetasBolseo);



 if($nMetasBolseo > 0){



 $pmbt


=
(($dMetasBolseo['mes_tira']*100)/$dMetasBolseo['meta_mes_kilo']);



 $pmbtr


=
(($dMetasBolseo['mes_troquel']*100)/$dMetasBolseo['meta_mes_kilo']);



 }



 
 


 $qMetas
=
"SELECT * FROM meta WHERE mes = '".$mesMeta."'  AND area = '1'";



 $rMetas
=
mysql_query($qMetas);



 $dMetas
=
mysql_fetch_assoc($rMetas);



 $nMetas
=
mysql_num_rows($rMetas);



 if($nMetas > 0){



 $pme
=
(($dMetas['desp_duro_hd']*100)/$dMetas['total_hd']);



 }



 
 


 $qMetasImp
=
"SELECT * FROM meta WHERE mes = '".$mesMeta."'  AND area = '2'";



 $rMetasImp
=
mysql_query($qMetasImp);



 $dMetasImp
=
mysql_fetch_assoc($rMetasImp);



 $nMetasImp
=
mysql_num_rows($rMetasImp);



  if($nMetasImp > 0){



 $pmi

=
(($dMetasImp['desp_duro_hd']*100)/$dMetasImp['total_hd']);



 }




$qSelectExtruder
=
"SELECT SUM(orden_produccion.total) AS total, ".






" SUM(orden_produccion.desperdicio_tira) AS desperdicio1, ".






" SUM(orden_produccion.desperdicio_duro) AS desperdicio2 ".






" FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".






" WHERE entrada_general.fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 0 GROUP BY MONTH(entrada_general.fecha) ORDER BY fecha";
$rSelectExtruder
=
mysql_query($qSelectExtruder);
$nExtruder


=
mysql_num_rows($rSelectExtruder);
if($nExtruder > 0){
$dSelectExtruder
=
mysql_fetch_assoc($rSelectExtruder);

$DesperdiciosExtruder
=
$dSelectExtruder['desperdicio1']+$dSelectExtruder['desperdicio2'];
$PorcentajeExtr

=
(($DesperdiciosExtruder*100)/$dSelectExtruder['total']);
}





$qSelectImpresion
=
"SELECT SUM(impresion.total_hd) AS total, ".






" SUM(impresion.desperdicio_hd) AS desperdicio1 ".






" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".






" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 1 GROUP BY MONTH(fecha) ORDER BY fecha";
$rSelectImpresion
=
mysql_query($qSelectImpresion);
$nImpresion


=
mysql_num_rows($rSelectImpresion);
if($nImpresion > 0){
$dSelectImpresion
=
mysql_fetch_assoc($rSelectImpresion);
$DesperdiciosImpresion
=
$dSelectImpresion['desperdicio1'];
$porcentajeImpr

=
(($DesperdiciosImpresion*100)/$dSelectImpresion['total']);
}
$qSelectBolseo
=
"SELECT SUM(kilogramos) AS kilogramos, ".






" SUM(millares) AS millares ,".






" SUM(dtira) AS tira, ".






" SUM(dtroquel) AS troquel ".






" FROM bolseo ".






" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' GROUP BY MONTH(fecha) ORDER BY fecha";
$rSelectBolseo
=
mysql_query($qSelectBolseo);
$nBolseo

=
mysql_num_rows($rSelectBolseo);
if($nBolseo){
$dSelectBolseo
=
mysql_fetch_assoc($rSelectBolseo);
$tira
=
$dSelectBolseo['tira'];

$troquel
=
$dSelectBolseo['troquel'];

$porcentajeTira
=
(($tira*100)/$dSelectBolseo['kilogramos']);
$porcentajeTroquel
=
(($troquel*100)/$dSelectBolseo['kilogramos']);

}
?> <div align="center" id="tablaimpr">   
 
<div class="tablaCentrada" id="tabla_reporte">        <p class="titulos_reportes" align="center">CONCENTRADO MENSUAL POR AREAS <br>DE <?=$mes[$meMeta]?> DEL <?=$anho?></p><br><br><br>        
<table width="100%">             
<? if($nMetasBolseo < 1 || $nMetasImp < 1 || $nMetasBolseo < 1 ){?>                <tr>                
<td colspan="13"><p align="center" style="color:#FF0000">NO SE HAN ESTABLECIDO METAS PARA HACER COMPARACIONES CON SU PRODUCCION EN ESTE MES.<br />                    FAVOR DE INFORMARLE AL ADMINISTRADOR DEL SISTEMA.</p><br /><br /><br />                    <p align="left">GRACIAS.</p>                    </td>                 </tr>                <? } else { ?>                <tr align="center">                
<td width="75" rowspan="3"></td>               
  <td colspan="3" class="titulos_e"><h3>Extruder</h3></td>                      

    <td colspan="3" class="titulos_i"><h3>Impresion</h3></td>                    <td colspan="6" class="titulos_b"><h3>Camisetas</h3></td>               </tr>                <tr>                  <td width="100" rowspan="2" align="center" valign="bottom" class="total">Producci&oacute;n</td>           
      <td colspan="2" align="center" valign="bottom" bgcolor="#DDDDDD" class="total">Desperdicio</td>               
  <td width="100" rowspan="2" align="center" valign="bottom" class="total">Producci&oacute;n</td>           
      <td colspan="2" align="center" valign="bottom" bgcolor="#DDDDDD" class="total">Desperdicio</td>               
  <td width="100" rowspan="2" align="center" valign="bottom" class="total">Producci&oacute;n Kg</td>               
  <td width="100" rowspan="2" align="center" valign="bottom" class="total">Producci&oacute;n Mi</td>           
      <td colspan="4" align="center" valign="bottom" bgcolor="#DDDDDD" class="total">Desperdicio</td>              </tr>                <tr>                
<td width="50" align="center" valign="bottom">Tira</td>                  <td width="50" align="center" valign="bottom">%</td>               
  <td width="50" align="center" valign="bottom">Tira</td>                  <td width="50" align="center" valign="bottom">%</td>                 
  <td width="50" align="center" valign="bottom">Tira</td>                  <td width="50" align="center" valign="bottom">%</td>               
  <td width="50" align="center" valign="bottom">Troquel</td>                  <td width="50" align="center" valign="bottom">%</td>                                  </tr>



<tr>                
<td align="right" bgcolor="#DDDDDD" class="total">Meta: </td>               
    <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMetas['total_hd'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMetas['desp_duro_hd'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$dMetas['porcentaje_desp'])?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMetasImp['total_hd'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMetasImp['desp_duro_hd']);?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$dMetasImp['porcentaje_desp'])?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMetasBolseo['meta_mes_kilo'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMetasBolseo['meta_mes_millar'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMetasBolseo['mes_tira'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$dMetasBolseo['porcentaje_desp'])?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dMetasBolseo['mes_troquel'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$dMetasBolseo['porcentaje_troquel'])?>%</td>



</tr> 



<tr>                
<td align="right" class="total">Real: </td>               
    <td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($dSelectExtruder['total'])?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($DesperdiciosExtruder)?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=redondeado($PorcentajeExtr,2)?>%</td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($dSelectImpresion['total'])?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($dSelectImpresion['desperdicio1'])?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=redondeado($porcentajeImpr,2)?>%</td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><? if($real_milla){ $kilos_real = $dSelectBolseo['millares'] * $valor_ppm; } else { $kilos_real = $dSelectBolseo['kilogramos']; } echo number_format($kilos_real);?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($dSelectBolseo['millares'])?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($tira)?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=redondeado($porcentajeTira,2)?>%</td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($troquel)?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=redondeado($porcentajeTroquel,2)?>%</td>



</tr>



<tr>                
<td align="right" bgcolor="#DDDDDD" class="total">Diferencia: </td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dSelectExtruder['total']-$dMetas['total_hd'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($DesperdiciosExtruder-$dMetas['desp_duro_hd'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=redondeado($PorcentajeExtr -$pme,2 )?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dSelectImpresion['total']-$dMetasImp['total_hd']);?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dSelectImpresion['desperdicio1']-$dMetasImp['desp_duro_hd']);?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=redondeado($porcentajeImpr-$pmi,2);?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($kilos_real-$dMetasBolseo['meta_mes_kilo'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dSelectBolseo['millares']-$dMetasBolseo['meta_mes_millar'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($tira-$dMetasBolseo['mes_tira'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=redondeado($porcentajeTira-$pmbt,2)?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($troquel-$dMetasBolseo['mes_troquel'])?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=redondeado($porcentajeTroquel-$pmbtr,2)?>%</td>



</tr>                      </table><br><br>        </div></div><? }}?> <?  if($_REQUEST['tipo'] == 34 ){     
$meMeta =
$_REQUEST['mes_pm'];
$anho =
$_REQUEST['anho_pm'];
$mesMetacero = num_mes_cero($anho.'-'.$meMeta.'-01');
$mesMeta = $anho.'-'.$mesMetacero.'-01';
$ultimo_dia = UltimoDia($anho,$meMeta);
$mesFinal = $anho.'-'.$mesMetacero.'-'.$ultimo_dia; 

 


 
 $qSelectTiemposExtr="SELECT TIME(SUM(mantenimiento)) AS mantenimiento ,".  " TIME(SUM(falta_personal)) AS falta_personal ,". " TIME(SUM(fallo_electrico)) AS fallo_electrico ,". " TIME(SUM(otras)) AS otras ,"." SUM(mallas) AS mallas ,". " maquina.marca, maquina.numero ". " FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general ". " LEFT JOIN tiempos_muertos ON orden_produccion.id_orden_produccion = tiempos_muertos.id_produccion "." LEFT JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina "." WHERE tipo = 1 AND fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 0 GROUP BY tiempos_muertos.id_maquina ORDER BY maquina.numero, maquina.area ASC";
$rSelectTiemposExtr=mysql_query($qSelectTiemposExtr);
$nExtruder = mysql_num_rows($rSelectTiemposExtr);
$qSelectImpresion =
"SELECT TIME(SUM(mantenimiento)) AS mantenimiento ,"." TIME(SUM(falta_personal)) AS falta_personal ,"." TIME(SUM(fallo_electrico)) AS fallo_electrico ,"." SUM(cambio_impresion) AS cambio_impresion ,".
" TIME(SUM(otras)) AS otras ,"." maquina.marca, maquina.numero ". " FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general "." LEFT JOIN tiempos_muertos ON impresion.id_impresion = tiempos_muertos.id_produccion "." LEFT JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina ".
" WHERE (tipo = 2  OR tipo = 3) AND fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 1 GROUP BY tiempos_muertos.id_maquina ORDER BY maquina.area,  maquina.numero ASC";

$rSelectImpresion = mysql_query($qSelectImpresion);
$nImpresion=mysql_num_rows($rSelectImpresion);
 
$qSelectBolseo

=
"SELECT TIME(SUM(mantenimiento)) AS mantenimiento ,".






" TIME(SUM(falta_personal)) AS falta_personal ,".






" TIME(SUM(fallo_electrico)) AS fallo_electrico ,".






" TIME(SUM(otras)) AS otras ,".






" maquina.marca, maquina.numero ".






" FROM bolseo ".






" LEFT JOIN tiempos_muertos ON bolseo.id_bolseo = tiempos_muertos.id_produccion ".






" LEFT JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina ".












" WHERE tipo = 4 AND  fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' GROUP BY tiempos_muertos.id_maquina ORDER BY maquina.area,  maquina.numero ASC";

$rSelectBolseo=mysql_query($qSelectBolseo);
$nBolseo=mysql_num_rows($rSelectBolseo);
?><div align="center" id="tablaimpr">
<div class="tablaCentrada" id="tabla_reporte">    <p class=" titulos_reportes" align="center">PAROS REPORTADOS EN TODAS LAS AREAS <br>DE <?=$mes[$meMeta]?> DEL <?=$anho?></p><br>            <? if(isset($_REQUEST['par_extr'])){?>    <p align="center" class="subtitulos">Extruder</p> <br>        <table width="75%" align="center" class="titulos_e">               <tr align="center">              
<td width="30%"><H3>Maquina</H3></td>                <td width="16%"><h3>Mantenimiento</h3></td>                    <td width="17%"><h3>Falta_de_Opr</h3></td>                      <td width="12%"><h3>Otro</h3></td>                      <td width="13%" ><h3>Total</h3></td>                <td width="12%"><h3>MALLAS</h3></td>             </tr>               <? 
for($a++; $dSelectTiemposExtr
=
mysql_fetch_assoc($rSelectTiemposExtr); $a++){
$Total_maquinaExtr = $dSelectTiemposExtr['mantenimiento']+$dSelectTiemposExtr['falta_personal']+$dSelectTiemposExtr['fallo_electrico'];

$TotalO=$dSelectTiemposExtr['fallo_electrico']+$dSelectTiemposExtr['otras'];

?>              <tr <? cebra($a) ?>>                <td ><?=$dSelectTiemposExtr['numero']?> - <?=$dSelectTiemposExtr['marca']?></td>                      <td align="right" class="style5"><? printf("%.2f ",$dSelectTiemposExtr['mantenimiento']/(24/3))?></td>                      <td align="right" class="style5"><? printf("%.2f ",$dSelectTiemposExtr['falta_personal']/(24/3))?></td>                      <td align="right" class="style5"><? printf("%.2f ",$TotalO/(24/3))?></td>                      <td align="right" class="style5"><? printf("%.2f ",($TotalO+$dSelectTiemposExtr['falta_personal']+$dSelectTiemposExtr['mantenimiento'])/(24/3))?></td>                <td align="right" class="style5"><? printf("%.2f ",$dSelectTiemposExtr['mallas'])?></td>                            


  </tr>             <?
 
   $turnos_manE += $dSelectTiemposExtr['mantenimiento'];
   $turnos_perE+= $dSelectTiemposExtr['falta_personal'];
 $turnos_falloE += $TotalO;

   $turnos_mallaE += $dSelectTiemposExtr['mallas'];

 $Total_extruderE += $Total_maquinaExtr;

 $turno_totalE =$turnos_manE + $turnos_perE + $turnos_falloE;

 ?>             <? } ?>                <tr>                <td align="right" ><h3>TURNOS_PARADOS:</h3></td>                      <td align="right" class="style4"><? printf("%.2f ",$turnos_manE/(24/3))?></td>                      <td align="right" class="style4"><? printf("%.2f ",$turnos_perE/(24/3))?></td>                      <td align="right" class="style4"><? printf("%.2f ",$turnos_falloE/(24/3))?></td>                      <td align="right" class="style4"><? printf("%.2f ",($turnos_falloE+$turnos_perE+$turnos_manE)/(24/3))?></td>                  <td align="right" class="style4"><?=$turnos_mallaE?></td>      


  </tr>              <tr bgcolor="EEEEEE">                <td align="right" ><h3>DIAS:</h3></td>                      <td align="right" class="style4"><? printf("%.2f ",($turnos_manE/24)/$nExtruder)?></td>                      <td align="right" class="style4"><? printf("%.2f ",($turnos_perE/24)/$nExtruder)?></td>                      <td align="right" class="style4"><? printf("%.2f ",($turnos_falloE/24)/$nExtruder)?></td>                      <td align="right" class="style4"><? printf("%.2f ",(($turnos_falloE+$turnos_perE+$turnos_manE)/24)/$nExtruder)?></td>      


  </tr>            </table>                         <? } if(isset($_REQUEST['par_impr'])){?>    <br><p align="center" class="subtitulos">Impresi&oacute;n</p><br>       <table width="75%" align="center" class="titulos_i" style="page-break-after:always">               <tr align="center">                <td width="25%"><h3>Maquinas</h3></td>                      <td width="18%"><h3>Mantenimiento</h3></td>                      <td width="16%"><h3>Falta_de_Opr</h3></td>                      <td width="14%"><h3>Otro</h3></td>                      <td width="12%"><h3>Total</h3></td>                <td width="15%" colspan="2"><h3>C._IMPR</h3></td>             </tr>              
<? 

for($b++;$dSelectImpresion= mysql_fetch_assoc($rSelectImpresion); $b++){

$dSelectImpresionImpr=
$dSelectImpresion['mantenimiento']+$dSelectImpresion['falta_personal']+$dSelectImpresion['fallo_electrico'];
$TotalOI = ($dSelectImpresion['fallo_electrico']+$dSelectImpresion['otras']);

?>              <tr <? cebra($b) ?>>                <td ><?=$dSelectImpresion['numero']?> - <?=$dSelectImpresion['marca']?></td>                      <td align="right" class="style5"><? printf("%.2f ",$dSelectImpresion['mantenimiento']/(24/3))?></td>                      <td align="right" class="style5"><? printf("%.2f ",$dSelectImpresion['falta_personal']/(24/3))?></td>                 <td align="right" class="style5"><? printf("%.2f ",$TotalOI/(24/3))?></td>                      <td align="right" class="style5"><? printf("%.2f ",($TotalOI+$dSelectImpresion['falta_personal']+$dSelectImpresion['mantenimiento'])/(24/3))?></td>                  <td colspan="2" align="right" class="style5"><?=$dSelectImpresion['cambio_impresion']?></td> 
  </tr>             
  <?
 
   $turnos_manI  += $dSelectImpresion['mantenimiento'];

   $turnos_perI  += $dSelectImpresion['falta_personal'];

   $turnos_falloI += $TotalOI;

   $turnos_impreI += $dSelectImpresion['cambio_impresion'];
 
   $Total_impresionI += $dSelectImpresionImpr;

   $Total_impresionI= $Total_impresionI+$TotalO;

   $turno_totalI= $turnos_manI + $turnos_perI + $turnos_falloI;

 ?>                          <? } ?>                <tr>                <td align="right"><h3>TURNOS_PARADOS:</h3></td>                      <td align="right" class="style4"><? printf("%.2f ",$turnos_manI/(24/3))?></td>                      <td align="right" class="style4"><? printf("%.2f ",$turnos_perI/(24/3))?></td>                  <td align="right" class="style4"><? printf("%.2f ",$turnos_falloI/(24/3))?></td>                      <td align="right" class="style4"><? printf("%.2f ",$turno_totalI/(24/3))?></td>                  <td colspan="2" align="right" class="style5"><?=$turnos_impreI?></td>  


  </tr>              <tr bgcolor="#EEEEEE">                <td align="right" ><h3>DIAS:</h3></td>                      <td align="right" class="style4"><? printf("%.2f ",($turnos_manI/24)/$nImpresion)?></td>                      <td align="right" class="style4"><? printf("%.2f ",($turnos_perI/24)/$nImpresion)?></td>                <td align="right" class="style4"><? printf("%.2f ",($turnos_falloI/24)/$nImpresion)?></td>                      <td align="right" class="style4"><? printf("%.2f ",(($turnos_falloI+$turnos_perI+$turnos_manI)/24)/$nImpresion)?></td>      


  </tr>            </table>                       <? } if(isset($_REQUEST['par_bol'])){?>    <p class="cabeceras titulos_reportes" align="center">PAROS REPORTADOS EN TODAS LAS AREAS (pagina 2)<br>DE <?=$mes[$meMeta]?> DEL <?=$anho?></p><br>            <br><p align="center" class="subtitulos">Camisetas (Bolseo)</p> <br>         <table width="75%" align="center">                <tr align="center">                <td width="29%"><h3>Maquinas</h3></td>                <td width="20%"><h3>Mantenimiento</h3></td>                <td width="22%"><h3>Falta_de_Opr</h3></td>                <td width="13%"><h3>Otra</h3></td>                <td width="16%" colspan="2"><h3>Total</h3></td> 
   </tr>              <? 
for($c++; $dSelectBolseo= mysql_fetch_assoc($rSelectBolseo); $c++){
$Total_maquinaBolseo  = $dSelectBolseo['mantenimiento']+$dSelectBolseo['falta_personal']+$dSelectBolseo['fallo_electrico'];

$TotalOb = ($dSelectBolseo['fallo_electrico']+$dSelectBolseo['otras']);





?>              <tr <? cebra($c)?>>                <td ><?=$dSelectBolseo['numero']?> - <?=$dSelectBolseo['marca']?></td>                      <td align="right" class="style5"><? printf("%.2f ",$dSelectBolseo['mantenimiento']/(24/3))?></td>                      <td align="right" class="style5"><? printf("%.2f ",$dSelectBolseo['falta_personal']/(24/3))?></td>                      <td align="right" class="style5"><? printf("%.2f ",$TotalOb/(24/3),1)?></td>                      <td colspan="2" align="right" class="style5"><? printf("%.2f ",($TotalOb+$dSelectBolseo['falta_personal']+$dSelectBolseo['mantenimiento'])/(24/3))?></td>      


  </tr>             <?
  
   $turnos_manB  += $dSelectBolseo['mantenimiento'];
 
   $turnos_perB  += $dSelectBolseo['falta_personal'];

   $turnos_falloB  += $TotalOb;

   $Total_extruderB  += $Total_maquinaBolseo;


   $turno_totalB = $turnos_manB + $turnos_perB + $turnos_falloB;


 ?>             <? } ?>                <tr>                <td align="right" ><h3>TURNOS_PARADOS:</h3></td>                      <td align="right" class="style4"><? printf("%.2f ",$turnos_manB/(24/3))?></td>                      <td align="right" class="style4"><? printf("%.2f ",$turnos_perB/(24/3))?></td>                      <td align="right" class="style4"><? printf("%.2f ",$turnos_falloB/(24/3))?></td>                      <td colspan="2" align="right" class="style4"><? printf("%.2f ",$turno_totalB/(24/3))?></td>      


  </tr>              <tr bgcolor="#EEEEEE">                <td align="right"><h3>DIAS:</h3></td>                      <td align="right" class="style5"><? printf("%.2f ",($turnos_manB/24)/$nBolseo)?></td>                      <td align="right" class="style5"><? printf("%.2f ",($turnos_perB/24)/$nBolseo)?></td>                      <td align="right" class="style5"><? printf("%.2f ",($turnos_falloB/24)/$nBolseo)?></td>                      <td colspan="2" align="right" class="style5"><? printf("%.2f ",($Total_extruderB/24)/$nBolseo)?></td>      


  </tr>          </table>              <? } ?>  </div></div><? }?> <?  if($_REQUEST['tipo'] == 35 ){  

$meMeta =
$_REQUEST['mes_da'];
$anho 
=
$_REQUEST['anho_da'];
$mesMetacero
=
num_mes_cero($anho.'-'.$meMeta.'-01');
$mesMeta
=
$anho.'-'.$mesMetacero.'-01';
$ultimo_dia = UltimoDia($anho,$meMeta);
$mesFinal = $anho.'-'.$mesMetacero.'-'.$ultimo_dia;

 $qMetasBolseo ="SELECT * FROM meta WHERE mes = '".$mesMeta."' AND area = '3'";



 $rMetasBolseo = mysql_query($qMetasBolseo);



 $dMetasBolseo = mysql_fetch_assoc($rMetasBolseo);



 $nMetasBolseo = mysql_num_rows($rMetasBolseo);



 $pmbt = (($dMetasBolseo['mes_tira']*100)/$dMetasBolseo['meta_mes_kilo']);



 $pmbtr =  (($dMetasBolseo['mes_troquel']*100)/$dMetasBolseo['meta_mes_kilo']);

 $qMetas = "SELECT * FROM meta WHERE mes = '".$mesMeta."' AND area = '1'";


 $rMetas
=
mysql_query($qMetas);



 $dMetas
=
mysql_fetch_assoc($rMetas);



 $nMetas=mysql_num_rows($rMetas);



 $pme=(($dMetas['desp_duro_hd']*100)/$dMetas['total_hd']);

 $qMetasImp="SELECT * FROM meta WHERE mes = '".$mesMeta."' AND area = '2'";



 $rMetasImp=mysql_query($qMetasImp);



 $dMetasImp=mysql_fetch_assoc($rMetasImp);



 $nMetasImp=mysql_num_rows($rMetasImp);



 $pmi=(($dMetasImp['desp_duro_hd']*100)/$dMetasImp['total_hd']);




$qSelectExtruder="SELECT SUM(orden_produccion.total) AS total, "." SUM(orden_produccion.desperdicio_tira) AS desperdicio1, "." SUM(orden_produccion.desperdicio_duro) AS desperdicio2 ". " FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general"." WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 0 GROUP BY MONTH(fecha) ORDER BY fecha";

$rSelectExtruder=mysql_query($qSelectExtruder);
$nExtruder = mysql_num_rows($rSelectExtruder);
$dSelectExtruder= mysql_fetch_assoc($rSelectExtruder);

$DesperdiciosExtruder= $dSelectExtruder['desperdicio1']+$dSelectExtruder['desperdicio2'];
$PorcentajeExtr=(($DesperdiciosExtruder*100)/$dSelectExtruder['total']);
$Total_pe= redondeado( $dMetas['porcentaje_desp'] - $PorcentajeExtr,2);




$qSelectImpresion
=
"SELECT SUM(impresion.total_hd) AS total, ".






" SUM(impresion.desperdicio_hd) AS desperdicio1 ".






" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".






" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 1 GROUP BY MONTH(fecha) ORDER BY fecha";
$rSelectImpresion
=
mysql_query($qSelectImpresion);
$nImpresion


=
mysql_num_rows($rSelectImpresion);
$dSelectImpresion
=
mysql_fetch_assoc($rSelectImpresion);
$DesperdiciosImpresion
=
$dSelectImpresion['desperdicio1'];
$porcentajeImpr

=
(($DesperdiciosImpresion*100)/$dSelectImpresion['total']);
$Total_pi
=
redondeado(  $dMetasImp['porcentaje_desp'] - $porcentajeImpr,2);

$qSelectBolseo
=
"SELECT SUM(kilogramos) AS kilogramos, ".






" SUM(millares) AS millares ,".






" SUM(dtira) AS tira, ".






" SUM(dtroquel) AS troquel ".






" FROM bolseo ".






" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' GROUP BY MONTH(fecha) ORDER BY fecha";
$rSelectBolseo
=
mysql_query($qSelectBolseo);
$nBolseo

=
mysql_num_rows($rSelectBolseo);
$dSelectBolseo
=
mysql_fetch_assoc($rSelectBolseo);
$tira
=
$dSelectBolseo['tira'];

$troquel
=
$dSelectBolseo['troquel'];

$porcentajeTira
=
(($tira*100)/$dSelectBolseo['kilogramos']);
$porcentajeTroquel
=
(($troquel*100)/$dSelectBolseo['kilogramos']);
$Total_pb
=
redondeado( $dMetasBolseo['porcentaje_desp'] - $porcentajeTira ,2);
$Total_pbt
=
redondeado( $porcentajeTroquel - $dMetasBolseo['porcentaje_troquel'],2);
$porcentajeTroquel
=
(($troquel*100)/$dSelectBolseo['kilogramos']);



$total1



= 
redondeado(($dSelectExtruder['total'] * $dMetas['porcentaje_desp'])/100,2);
$total2



=
redondeado(($dSelectImpresion['total'] *$dMetasImp['porcentaje_desp']) /100,2);
$total3



=
redondeado(($dSelectBolseo['kilogramos'] * $dMetasBolseo['porcentaje_desp'] )/100,2);
$total4



=
redondeado(($dSelectBolseo['kilogramos'] * $dMetasBolseo['porcentaje_troquel'] )/100,2);

$Diferencia_TiraE

=
$total1 - $DesperdiciosExtruder;
$Diferencia_TiraI

=
$total2-$DesperdiciosImpresion;
$Diferencia_TiraB

=
$total3-$tira;
$Diferencia_TroquelB
=
$total4-$troquel;    if($real_milla) $produc_kilos
=
$dSelectBolseo['millares'] * $valor_ppm;  else $produc_kilos
=
$dSelectBolseo['kilogramos'] ;
$Total_produccion
=
$dSelectExtruder['total'] + $dSelectImpresion['total']+ $produc_kilos ;
$Total_tira_meta
=
$total1 + $total2 + $total3;
$Total_tira_por

=
$dMetas['porcentaje_desp']+$dMetasImp['porcentaje_desp']+$dMetasBolseo['porcentaje_desp'];
$Total_Tira_real
=
$DesperdiciosExtruder + $DesperdiciosImpresion + $tira;
$Total_tira_porR
=
$PorcentajeExtr + $porcentajeImpr + $porcentajeTira;
$Total_dif_tira

=
$Diferencia_TiraE + $Diferencia_TiraI +$Diferencia_TiraB;
$Total_dif_p 

= 
$Total_pe + $Total_pi + $Total_pb;
?><div align="center" id="tablaimpr">   
 
<div class="tablaCentrada" id="tabla_reporte">        <br><br><br><p class="titulos_reportes" align="center">DESPERDICIOS CONTRA META POR AREAS DE <?=$mes[$meMeta]?> DEL <?=$anho?></p><br><br><br><br>                    
<? if($nMetasBolseo < 1 || $nMetasImp < 1 || $nMetasBolseo < 1 ){?>         
<table width="650px">                 <tr>                
<td colspan="13"><p align="center" style="color:#FF0000">NO SE HAN ESTABLECIDO METAS PARA HACER COMPARACIONES CON SU PRODUCCION EN ESTE MES.<br />                    FAVOR DE INFORMARLE AL ADMINISTRADOR DEL SISTEMA.</p><br /><br /><br />                    <p align="left">GRACIAS.</p>                    </td>              </tr>          </table>                       <? } else { ?>


<table width="90%" align="center">                <tr align="center">                
<td width="130" rowspan="3"></td>              </tr>                <tr>                  <td width="170" align="center" valign="bottom" class="total"></td>           
      <td colspan="2" align="center" valign="bottom" bgcolor="#DDDDDD" class="total"><h3>META</h3></td>           
      <td colspan="2" align="center" valign="bottom" bgcolor="#DDDDDD" class="total"><h3>REAL</h3></td>           
      <td colspan="4" align="center" valign="bottom" bgcolor="#DDDDDD" class="total"><h3>DIFERENCIA</h3></td>              </tr>                <tr>                  <td align="center" valign="bottom" class="total"><h3>Producci&oacute;n</h3></td>                  <td width="91" align="center" valign="middle">Tira</td>                  <td width="88" align="center" valign="middle">%</td>               
  <td width="86" align="center" valign="middle">Tira</td>                  <td width="88" align="center" valign="middle">%</td>                 
  <td width="94" align="center" valign="middle">Tira</td>                  <td width="99" align="center" valign="middle">%</td>              </tr>



<tr>               
  <td align="left" bgcolor="#DDDDDD" class="titulos_e"><h3>Extruder</h3></td>               
    <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($dSelectExtruder['total'],1)?></td>               
    <td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($total1,1);?></td>               
    <td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$dMetas['porcentaje_desp'])?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($DesperdiciosExtruder,1)?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f", $PorcentajeExtr);?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($Diferencia_TiraE,1)?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$Total_pe)?>% </td>



</tr>                



<tr>               
  <td align="left" class="titulos_i"><h3>Impresi&Oacute;n</h3></td>               
    <td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($dSelectImpresion['total'],1)?></td>               
  <td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($total2,1);?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><? printf("%.2f",$dMetasImp['porcentaje_desp'])?>%</td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($DesperdiciosImpresion,1)?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><? printf("%.2f",$porcentajeImpr)?>%</td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($Diferencia_TiraI,1)?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><? printf("%.2f", $Total_pi)?>%</td>



</tr>



<tr>               
  <td align="left" bgcolor="#DDDDDD" class="titulos_b"><h3>Bolseo</h3></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($produc_kilos,1)?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($total3,1)?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$dMetasBolseo['porcentaje_desp'])?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($tira,1);?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$porcentajeTira);?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($Diferencia_TiraB,1)?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$Total_pb)?>%</td>



</tr> 



<tr>               
  <td align="left" bgcolor="#FFFFFF" class="titulos_b" height="24px">Totales:</td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($Total_produccion,1)?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($Total_tira_meta,1)?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><? printf("%.2f",$Total_tira_por)?>%</td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($Total_Tira_real,1);?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><? printf("%.2f",$Total_tira_porR);?>%</td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><?=number_format($Total_dif_tira,1)?></td>                
<td align="right" bgcolor="#FFFFFF" class="style5"><? printf("%.2f",$Total_dif_p)?>%</td>



</tr>                           </table>          <p>&nbsp;</p><br>


<table width="90%" align="center">                 <tr align="center">               
  <td width="130" rowspan="3"></td>              </tr>                <tr>                  <td width="170" align="center" valign="bottom" class="total"></td>           
      <td colspan="2" align="center" valign="bottom" bgcolor="#DDDDDD" class="total"><h3>META</h3></td>           
      <td colspan="2" align="center" valign="bottom" bgcolor="#DDDDDD" class="total"><h3>REAL</h3></td>           
      <td colspan="4" align="center" valign="bottom" bgcolor="#DDDDDD" class="total"><h3>DIFERENCIA</h3></td>              </tr>                <tr>                  <td width="170" align="center" valign="bottom" class="total"><h3>Producci&oacute;n</h3></td>               
  <td width="91" align="center" valign="middle">Suaje</td>                  <td width="88" align="center" valign="middle">%</td>               
  <td width="88" align="center" valign="middle">Suaje</td>                  <td width="88" align="center" valign="middle">%</td>                 
  <td width="96" align="center" valign="middle">Suaje</td>                  <td width="97" align="center" valign="middle">%</td>              </tr>                



<tr>               
  <td align="left" bgcolor="#DDDDDD" class="titulos_b"><h3>Bolseo</h3></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($produc_kilos,1)?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($total4,1)?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$dMetasBolseo['porcentaje_troquel'])?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($troquel,1);?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$porcentajeTroquel);?>%</td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><?=number_format($Diferencia_TroquelB,1)?></td>                
<td align="right" bgcolor="#DDDDDD" class="style5"><? printf("%.2f",$Total_pbt)?>%</td>



</tr>                                      </table>          <br><br>  </div></div><? }}?> <?  if($_REQUEST['tipo'] == 36 ){  
$meMeta =
$_REQUEST['mes_d'];
$anho 
=
$_REQUEST['anho_d'];
$mesMetacero
=
num_mes_cero($anho.'-'.$meMeta.'-01');
$mesMeta
=
$anho.'-'.$mesMetacero.'-01';
$ultimo_dia = UltimoDia($anho,$meMeta);
$mesFinal
=
$anho.'-'.$mesMetacero.'-'.$ultimo_dia;



 $qMetasBolseo
=
"SELECT * FROM meta WHERE mes = '".$mesMeta."' AND area = '3'";



 $rMetasBolseo
=
mysql_query($qMetasBolseo);



 $dMetasBolseo
=
mysql_fetch_assoc($rMetasBolseo);



 $nMetasBolseo
=
mysql_num_rows($rMetasBolseo);



 if($nMetasBolseo > 0 ){




 $pmbt


=
(($dMetasBolseo['mes_tira']*100)/$dMetasBolseo['meta_mes_kilo']);




 $pmbtr


=
(($dMetasBolseo['mes_troquel']*100)/$dMetasBolseo['meta_mes_kilo']);



 }



 
 


 $qMetas
=
"SELECT * FROM meta WHERE mes = '".$mesMeta."' AND area = '1'";



 $rMetas
=
mysql_query($qMetas);



 $dMetas
=
mysql_fetch_assoc($rMetas);



 $nMetas
=
mysql_num_rows($rMetas);



 if($$nMetas > 0 )



 $pme
=
(($dMetas['desp_duro_hd']*100)/$dMetas['total_hd']);




 


 $qMetasImp
=
"SELECT * FROM meta WHERE mes = '".$mesMeta."' AND area = '2'";



 $rMetasImp
=
mysql_query($qMetasImp);



 $dMetasImp
=
mysql_fetch_assoc($rMetasImp);



 $nMetasImp
=
mysql_num_rows($rMetasImp);



 if($$nMetasImp > 0 )



 $pmi

=
(($dMetasImp['desp_duro_hd']*100)/$dMetasImp['total_hd']);




$qSelectExtruder
=
"SELECT SUM(orden_produccion.total) AS total, ".






" SUM(orden_produccion.desperdicio_tira) AS desperdicio1, ".






" SUM(orden_produccion.desperdicio_duro) AS desperdicio2 ".






" FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".






" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 0 GROUP BY MONTH(fecha) ORDER BY fecha";
$rSelectExtruder
=
mysql_query($qSelectExtruder);
$nExtruder


=
mysql_num_rows($rSelectExtruder);
if($nExtruder > 0 ){
$dSelectExtruder
=
mysql_fetch_assoc($rSelectExtruder);

$DesperdiciosExtruder
=
$dSelectExtruder['desperdicio1']+$dSelectExtruder['desperdicio2'];
}


$qSelectImpresion
=
"SELECT SUM(impresion.total_hd) AS total, ".






" SUM(impresion.desperdicio_hd) AS desperdicio1 ".






" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".






" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' AND entrada_general.impresion = 1 GROUP BY MONTH(fecha) ORDER BY fecha";
$rSelectImpresion
=
mysql_query($qSelectImpresion);
$nImpresion


=
mysql_num_rows($rSelectImpresion);
if($nImpresion > 0){
$dSelectImpresion
=
mysql_fetch_assoc($rSelectImpresion);
$DesperdiciosImpresion
=
$dSelectImpresion['desperdicio1'];
}

$qSelectBolseo
=
"SELECT SUM(kilogramos) AS kilogramos, ".






" SUM(millares) AS millares ,".






" SUM(dtira) AS tira, ".






" SUM(dtroquel) AS troquel, ".






" SUM(segundas) AS segundas ".






" FROM bolseo ".






" WHERE fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."' GROUP BY MONTH(fecha) ORDER BY fecha";
$rSelectBolseo
=
mysql_query($qSelectBolseo);
$nBolseo

=
mysql_num_rows($rSelectBolseo);
if($nBolseo > 0){
$dSelectBolseo
=
mysql_fetch_assoc($rSelectBolseo);
$tira
=
$dSelectBolseo['tira'];

$troquel
=
$dSelectBolseo['troquel'];


$TOTAL = $DesperdiciosExtruder + $DesperdiciosImpresion + $tira + $troquel;
$temporal 
= $TOTAL - $DesperdiciosExtruder;
$Consumo
=
($temporal)/($dSelectExtruder['total'] + $DesperdiciosExtruder);
}

?><div align="center" id="tablaimpr">
<div class="tablaCentrada" align="center" id="tabla_reporte">    <br><br><br><p align="center" class="titulos_reportes">DESPERDICIOS MENSUALES<br>DE <?=$mes[$meMeta]?> DEL <?=$anho?> </p><br><br><br><br>        
<table width="50%">             
<? if($nMetasBolseo < 1 || $nMetasImp < 1 || $nMetasBolseo < 1 ){?>                <tr>                
<td colspan="5"><p align="center" style="color:#FF0000">NO SE HAN ESTABLECIDO METAS PARA HACER COMPARACIONES CON SU PRODUCCION EN ESTE MES.<br />                    FAVOR DE INFORMARLE AL ADMINISTRADOR DEL SISTEMA.</p><br /><br /><br />                    <p align="left">GRACIAS.</p>                    </td>                 </tr>                <? } else { ?>              <tr>                  <td width="21%" align="center"><h3>Producci&oacute;n</h3></td>           
      <td width="24%" align="center" ><h3>Kilos</h3></td>           
      <td width="11%" align="center" ></td>           
      <td colspan="2" rowspan="4" align="center" ></td>       
      </tr>



<tr>                
<td align="center" bgcolor="#DDDDDD" class="style7" height="30">Extuder</td>               
    <td align="right" bgcolor="#DDDDDD" class="style5" ><?=number_format($dSelectExtruder['desperdicio1']+$dSelectExtruder['desperdicio2'])?></td>               
    <td ></td>           
    </tr>                



<tr>                
<td align="center" class="style7" height="30">Impresion</td>               
    <td align="right" class="style5" ><?=number_format($dSelectImpresion['desperdicio1'])?></td>               
    <td ></td>           
  </tr>



<tr>                
<td align="center" bgcolor="#DDDDDD" class="style7" height="30" >Troquel</td>                
<td align="right" bgcolor="#DDDDDD" class="style5" ><?=number_format($troquel)?></td>                
<td ></td>           
  </tr> 



<tr>                
<td align="center" class="style7" height="30">Camisetas: </td>                
<td align="right" class="style5" ><?=number_format($tira)?></td>                
<td ></td>



</tr> 



<tr>                
<td align="center" bgcolor="#DDDDDD" class="style7" >Totales: </td>                
<td align="right" bgcolor="#DDDDDD" class="style5" ><b><?=number_format($TOTAL)?></b></td>                
<td ></td>           
        <td width="27%" align="right" ><h3>Segundas : </h3></td>                
<td align="right" bgcolor="#DDDDDD" class="style5" ><b><?=number_format($dSelectBolseo['segundas']+($vts*($ultimo_dia*3)))?></b></td>



</tr>                 <tr>                
<td colspan="3"></td>               
    <td align="right" ><h3>Kgs. Duro : </h3></td>                
<td align="right"  class="style5" ><b><?=number_format($dSelectExtruder['desperdicio2']);?></b></td>                </tr>



<tr>                
<td align="center" class="style7"> </td>                
<td colspan="3" align="left" ><h3>Desperdicio contra consumo: </h3></td>                
<td align="right" bgcolor="#DDDDDD" class="style5" ><b><? printf("%.2f",$Consumo*100);?>%</b></td>


  </tr>                           </table>        </div></div><? }}?> <?  if($_REQUEST['tipo'] == 100 ){   
$semana =
$_REQUEST['semana_reportes']; 
$anio_as =
$_REQUEST['anio_nom'];

/*
$qDelAl
=
"SELECT DAY(desde), DAY(hasta), MONTH(desde), MONTH(hasta), desde, hasta FROM pre_nomina_calificada WHERE semana = ".$semana." AND (YEAR(desde) = '".$anio_as."' || YEAR(hasta) = '".$anio_as."' )  GROUP BY semana";
$rDelAl
=
mysql_query($qDelAl);
$dDelAl
=
mysql_fetch_row($rDelAl);

  
$desdeFec
=
$dDelAl[4];
$hastaFec
=
$dDelAl[5];
*/
if( $_REQUEST['movimiento'] == 1 || $_REQUEST['movimiento'] == 3 ){
$qMovimientoExtruder

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 1 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoImpresion 

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 3 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoBolseo


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 2 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoEmpaque


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 5 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoEmpaqueB

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 7 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoMantenimiento
=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 4 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoMantenimientoB
=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 6 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoAlmacen


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND ( area = 8 OR almacen = 1 ) AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoAlmacenB

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND ( area = 9 ) AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
}


if($_REQUEST['movimiento'] == 2){
$qMovimientoExtruder

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 1 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoImpresion

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 3 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoBolseo


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 2 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoEmpaque


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 5 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoEmpaqueB

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 7 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoMantenimiento
=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 4 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoMantenimientoB
=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 6 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoAlmacen


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND ( area = 8 OR almacen = 1 ) AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoAlmacenB

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND ( area = 9 ) AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
}



$rMovimientoExtruder


=
mysql_query($qMovimientoExtruder);
$rMovimientoImpresion


=
mysql_query($qMovimientoImpresion);
$rMovimientoBolseo



=
mysql_query($qMovimientoBolseo);
$rMovimientoEmpaque



=
mysql_query($qMovimientoEmpaque);
$rMovimientoEmpaqueB


=
mysql_query($qMovimientoEmpaqueB);
$rMovimientoMantenimiento

=
mysql_query($qMovimientoMantenimiento);
$rMovimientoMantenimientoB

=
mysql_query($qMovimientoMantenimientoB);
$rMovimientoAlmacen



=
mysql_query($qMovimientoAlmacen);
$rMovimientoAlmacenB


=
mysql_query($qMovimientoAlmacenB);
$numExtruder


=
mysql_num_rows($rMovimientoExtruder);
$numImpresion


=
mysql_num_rows($rMovimientoImpresion);
$numBolseo



=
mysql_num_rows($rMovimientoBolseo);
$numEmp




=
mysql_num_rows($rMovimientoEmpaque);
$numEmpB



=
mysql_num_rows($rMovimientoEmpaqueB);
$numMantenimiento

=
mysql_num_rows($rMovimientoMantenimiento);
$numMantenimientoB

=
mysql_num_rows($rMovimientoMantenimientoB);
$numAlmacen



=
mysql_num_rows($rMovimientoAlmacen);
$numAlmacenB


=
mysql_num_rows($rMovimientoAlmacenB);

?><script type="text/javascript" language="javascript">function genera(){document.form.action="reportes_pdf_nomina.php?tipo=movimiento&area=<?=$_REQUEST['area']?>&movimiento=<?=$_REQUEST['movimiento']?>&semana_reportes=<?=$_REQUEST['semana_reportes']?>&anio_nom=<?=$anio_as?><? if(isset($_REQUEST['extruder'])) echo "&extruder=1"; ?><? if(isset($_REQUEST['impresion'])) echo "&impresion=1"; ?><? if(isset($_REQUEST['bolseo'])) echo "&bolseo=1"; ?><? if(isset($_REQUEST['emp'])) echo "&emp=1"; ?><? if(isset($_REQUEST['empb']))  echo "&empb=1"; ?><? if(isset($_REQUEST['mantto'])) echo "&mantto=1"; ?><? if(isset($_REQUEST['manttob'])) echo "&manttob=1"; ?><? if(isset($_REQUEST['alm'])) echo "&alm=1"; ?><? if(isset($_REQUEST['almb'])) echo "&almb=1"; ?>";document.form.submit();}</script>   
 
<div class="tablaCentrada" align="center">        <form name="form" action="reportes_pdf_nomina.php" method="post">        
<table width="70%" border="0" cellpadding="1" cellspacing="1" align="center">             <tr>            
<td colspan="10" align="center" style="font-size:12px"><? if($_REQUEST['movimiento'] == 1) echo "MOVIMIENTOS NORMALES"; else if($_REQUEST['movimiento'] == 2) echo "MOVIMIENTOS ECONOMICOS"; else if($_REQUEST['movimiento'] == 3) echo "MOVIMIENTOS NORMALES";?></td>            </tr>            <? if( $numExtruder > 0 &&  $_REQUEST['extruder'] == 1 ){?>       
  <tr>                
<td colspan="5" align="center"><h3>EXTRUDER</h3></td>                </tr> 



<tr>                  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Motivo</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoExtruder = mysql_fetch_assoc($rMovimientoExtruder) ;$a++){








$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoExtruder['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);












 ?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoExtruder['fecha'])?></td>               
  <td align="center" class="style5"><?=$dMovimientoExtruder['numnomina']?></td>               
  <td align="left" class="style5"><?=$dMovimientoExtruder['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoExtruder['movimiento']]?></td>               
  <td align="justify" class="style5"  style="text-align:justify">                  <table width="96%" style="background-color:transparent;">                    <tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? if($dMovimientoExtruder['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoExtruder['nuevo_movimiento'].".<br>";







if($dMovimientoExtruder['dia'] != "" && $dMovimientoExtruder['dia'] != "0000-00-00" ){ echo "Fecha a cubrir: "; fecha($dMovimientoExtruder['dia']); echo ".<br>"; }







if($dMovimientoExtruder['horas'] != ""  && $dMovimientoExtruder['horas'] != 0) echo "Tiempo: ".$dMovimientoExtruder['horas'].".<br>";







if($dMovimientoExtruder['cantidad'] != "" && $dMovimientoExtruder['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoExtruder['cantidad']."<br>";







if($dMovimientoExtruder['desde'] != "" && $dMovimientoExtruder['desde'] != "0000-00-00" && ($dMovimientoExtruder['movimiento'] == 9 || $dMovimientoExtruder['movimiento'] == 20 )){ echo "Del "; fecha($dMovimientoExtruder['desde']); echo " al ";}







if($dMovimientoExtruder['hasta'] != "" && $dMovimientoExtruder['hasta'] != "0000-00-00" && ($dMovimientoExtruder['movimiento'] == 9 || $dMovimientoExtruder['movimiento'] == 20 )){ fecha($dMovimientoExtruder['hasta']); echo ".<br>";}







if($dMovimientoExtruder['desde_tiempo'] != "" && $dMovimientoExtruder['desde_tiempo'] != "00:00" && ($dMovimientoExtruder['movimiento'] == 23 || $dMovimientoExtruder['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoExtruder['desde_tiempo']; echo " al ";}







if($dMovimientoExtruder['hasta_tiempo'] != "" && $dMovimientoExtruder['hasta_tiempo'] != "00:00" && ($dMovimientoExtruder['movimiento'] == 23 || $dMovimientoExtruder['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoExtruder['hasta_tiempo']; echo ".<br>";}







if($dMovimientoExtruder['turno'] != "" && $dMovimientoExtruder['turno'] != 0 && ($dMovimientoExtruder['movimiento'] == 4 || $dMovimientoExtruder['movimiento'] == 20 )) echo "Cambio al turno: ".
$dMovimientoExtruder['turno']. ".<br>";







if($dMovimientoExtruder['movimientos.rol'] != ""  &&  $dMovimientoExtruder['movimientos.rol'] != 0 && ($dMovimientoExtruder['movimiento'] == 5 || $dMovimientoExtruder['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoExtruder['movimientos.rol'].".<br>";







if($dMovimientoExtruder['horario'] != ""  && $dMovimientoExtruder['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoExtruder['horario']."<br>";







if($dMovimientoExtruder['premio'] != ""  && $dMovimientoExtruder['premio'] != 0 ) echo "Lo que proceda con premios.<br>"; 







if($dMovimientoExtruder['puntualidad'] != ""  && $dMovimientoExtruder['puntualidad'] != 0 ) echo "Afecta puntualidad.<br>"; 







if($dMovimientoExtruder['productividad'] != ""  && $dMovimientoExtruder['productividad'] != 0 ) echo "Afecta productividad.<br>";







if($dMovimientoExtruder['no_premio'] != ""  && $dMovimientoExtruder['no_premio'] != 0 ) echo "No afectar premio.<br>";







if($dMovimientoExtruder['id_operador2'] != "" && $dMovimientoExtruder['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoExtruder['motivo'] ?></td></tr></table></td>              </tr>                <? } ?>

              <? } if($numImpresion > 0 && $_REQUEST['impresion'] == 1  ){?> 
       
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>IMPRESION</h3></td>                </tr> 



<tr>                
<td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoImpresion = mysql_fetch_assoc($rMovimientoImpresion) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoImpresion['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoImpresion['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoImpresion['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoImpresion['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoImpresion['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? if($dMovimientoImpresion['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoImpresion['nuevo_movimiento'].".<br>";







if($dMovimientoImpresion['dia'] != "" && $dMovimientoImpresion['dia'] != "0000-00-00" && $dMovimientoImpresion['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoImpresion['dia']); echo ".<br>"; }







if($dMovimientoImpresion['horas'] != ""  && $dMovimientoImpresion['horas'] != 0) echo "Tiempo: ".$dMovimientoImpresion['horas'].".<br>";







if($dMovimientoImpresion['cantidad'] != "" && $dMovimientoImpresion['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoImpresion['cantidad'].".<br>";







if($dMovimientoImpresion['desde'] != "" && $dMovimientoImpresion['desde'] != "0000-00-00" && ($dMovimientoImpresion['movimiento'] == 9 || $dMovimientoImpresion['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoImpresion['desde']); echo " al ";}







if($dMovimientoImpresion['hasta'] != "" && $dMovimientoImpresion['hasta'] != "0000-00-00" && ($dMovimientoImpresion['movimiento'] == 9 || $dMovimientoImpresion['movimiento'] == 20 ) ){ fecha($dMovimientoImpresion['hasta']); echo ".<br>";}







if($dMovimientoImpresion['desde_tiempo'] != "" && $dMovimientoImpresion['desde_tiempo'] != "00:00" && ($dMovimientoImpresion['movimiento'] == 23 || $dMovimientoImpresion['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoImpresion['desde_tiempo']; echo " al ";}







if($dMovimientoImpresion['hasta_tiempo'] != "" && $dMovimientoImpresion['hasta_tiempo'] != "00:00" && ($dMovimientoImpresion['movimiento'] == 23 || $dMovimientoImpresion['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoImpresion['hasta_tiempo']; echo ".<br>";}







if($dMovimientoImpresion['turno'] != "" && $dMovimientoImpresion['turno'] != 0 && ($dMovimientoImpresion['movimiento'] == 4 || $dMovimientoImpresion['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoImpresion['turno']. ".<br>";







if($dMovimientoImpresion['movimientos.rol'] != ""  &&  $dMovimientoImpresion['movimientos.rol'] != 0  && ($dMovimientoImpresion['movimiento'] == 5 || $dMovimientoImpresion['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoImpresion['movimientos.rol'].".<br>";







if($dMovimientoImpresion['horario'] != ""  && $dMovimientoImpresion['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoImpresion['horario'].".<br>";







if($dMovimientoImpresion['premio'] != ""  && $dMovimientoImpresion['premio'] != 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoImpresion['puntualidad'] != ""    && $dMovimientoImpresion['puntualidad'] != 0 ) echo "Afecta puntualidad..<br>"; 







if($dMovimientoImpresion['productividad'] != ""  && $dMovimientoImpresion['productividad'] != 0 ) echo "Afecta productividad.<br>";







if($dMovimientoImpresion['no_premio'] != ""  && $dMovimientoImpresion['no_premio'] != 0 ) echo "No afectar premio.<br>";







if($dMovimientoImpresion['id_operador2'] != ""   && $dMovimientoImpresion['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoImpresion['motivo'] ?><br></td></tr></table></td>              </tr>                <? } ?>
              <? } if($numBolseo > 0  && $_REQUEST['bolseo'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>BOLSEO</h3></td>                </tr> 



<tr>                  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Motivo</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoBolseo = mysql_fetch_assoc($rMovimientoBolseo) ;$a++){ 




$qE2
=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoBolseo['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);









?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoBolseo['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoBolseo['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoBolseo['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoBolseo['movimiento']]?></td>               
  <td align="justify"  class="style5"  style="text-align:justify">



  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? if($dMovimientoBolseo['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoBolseo['nuevo_movimiento'].".<br>";







if($dMovimientoBolseo['dia'] != "" && $dMovimientoBolseo['dia'] != "0000-00-00" ){ echo "Dia: "; fecha($dMovimientoBolseo['dia']); echo ".<br>"; }







if($dMovimientoBolseo['horas'] != ""  && $dMovimientoBolseo['horas'] != 0) echo "Tiempo: ".$dMovimientoBolseo['horas'].".<br>";







if($dMovimientoBolseo['cantidad'] != "" && $dMovimientoBolseo['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoBolseo['cantidad'].".<br>";







if($dMovimientoBolseo['desde'] != "" && $dMovimientoBolseo['desde'] != "0000-00-00" && ($dMovimientoBolseo['movimiento'] == 9 || $dMovimientoBolseo['movimiento'] == 20 ) ){ echo "Del: "; fecha($dMovimientoBolseo['desde']); echo " al ";}







if($dMovimientoBolseo['hasta'] != "" && $dMovimientoBolseo['hasta'] != "0000-00-00" && ($dMovimientoBolseo['movimiento'] == 9 || $dMovimientoBolseo['movimiento'] == 20 )){  fecha($dMovimientoBolseo['hasta']); echo ".<br>";}







if($dMovimientoBolseo['desde_tiempo'] != "" && $dMovimientoBolseo['desde_tiempo'] != "00:00" && ($dMovimientoBolseo['movimiento'] == 23 || $dMovimientoBolseo['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoBolseo['desde_tiempo']; echo " al ";}







if($dMovimientoBolseo['hasta_tiempo'] != "" && $dMovimientoBolseo['hasta_tiempo'] != "00:00" && ($dMovimientoBolseo['movimiento'] == 23 || $dMovimientoBolseo['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoBolseo['hasta_tiempo']; echo ".<br>";}







if($dMovimientoBolseo['turno'] != "" && $dMovimientoBolseo['turno'] != 0 && ($dMovimientoBolseo['movimiento'] == 4 || $dMovimientoBolseo['movimiento'] == 20 )) echo "Cambio al turno: ".
$dMovimientoBolseo['turno']. ".<br>";







if($dMovimientoBolseo['movimientos.rol'] != ""  &&  $dMovimientoBolseo['movimientos.rol'] != 0 && ($dMovimientoBolseo['movimiento'] == 5 || $dMovimientoBolseo['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoBolseo['movimientos.rol'].".<br>";







if($dMovimientoBolseo['horario'] != ""  && $dMovimientoBolseo['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoBolseo['horario']."<br>";







if($dMovimientoBolseo['premio'] != ""  && $dMovimientoBolseo['premio'] != 0 ) echo "Lo que porceda con premios.<br>"; 







if($dMovimientoBolseo['puntualidad'] != ""  && $dMovimientoBolseo['puntualidad'] != 0 ) echo "Afecta puntualidad.<br>"; 







if($dMovimientoBolseo['productividad'] != ""  && $dMovimientoBolseo['productividad'] != 0 ) echo "Afecta productividad.<br>"; 







if($dMovimientoBolseo['no_premio'] != ""  && $dMovimientoBolseo['no_premio'] != 0 ) echo "No afectar premio.<br>"; 







if($dMovimientoBolseo['id_operador2'] != "" && $dMovimientoBolseo['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoBolseo['motivo'] ?><br>                                    </td></tr></table></td>              </tr>              <? } 


  } if($numEmp > 0  && $_REQUEST['emp'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>Empaque</h3></td>                </tr> 



<tr>                
<td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoEmpaque = mysql_fetch_assoc($rMovimientoEmpaque) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoEmpaque['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoEmpaque['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoEmpaque['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoEmpaque['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoEmpaque['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? 


if($dMovimientoEmpaque['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoEmpaque['nuevo_movimiento'].".<br>";







if($dMovimientoEmpaque['dia'] != "" && $dMovimientoEmpaque['dia'] != "0000-00-00" && $dMovimientoEmpaque['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoEmpaque['dia']); echo ".<br>"; }







if($dMovimientoEmpaque['horas'] != ""  && $dMovimientoEmpaque['horas'] != 0) echo "Tiempo: ".$dMovimientoEmpaque['horas'].".<br>";







if($dMovimientoEmpaque['cantidad'] != "" && $dMovimientoEmpaque['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoEmpaque['cantidad'].".<br>";







if($dMovimientoEmpaque['desde'] != "" && $dMovimientoEmpaque['desde'] != "0000-00-00" && ($dMovimientoEmpaque['movimiento'] == 9 || $dMovimientoEmpaque['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoEmpaque['desde']); echo " al ";}







if($dMovimientoEmpaque['hasta'] != "" && $dMovimientoEmpaque['hasta'] != "0000-00-00" && ($dMovimientoEmpaque['movimiento'] == 9 || $dMovimientoEmpaque['movimiento'] == 20 ) ){ fecha($dMovimientoEmpaque['hasta']); echo ".<br>";}







if($dMovimientoEmpaque['desde_tiempo'] != "" && $dMovimientoEmpaque['desde_tiempo'] != "00:00" && ($dMovimientoEmpaque['movimiento'] == 23 || $dMovimientoEmpaque['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoEmpaque['desde_tiempo']; echo " al ";}







if($dMovimientoEmpaque['hasta_tiempo'] != "" && $dMovimientoEmpaque['hasta_tiempo'] != "00:00" && ($dMovimientoEmpaque['movimiento'] == 23 || $dMovimientoEmpaque['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoEmpaque['hasta_tiempo']; echo ".<br>";}







if($dMovimientoEmpaque['turno'] != "" && $dMovimientoEmpaque['turno'] != 0 && ($dMovimientoEmpaque['movimiento'] == 4 || $dMovimientoEmpaque['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoEmpaque['turno']. ".<br>";







if($dMovimientoEmpaque['movimientos.rol'] != ""  &&  $dMovimientoEmpaque['movimientos.rol'] != 0  && ($dMovimientoEmpaque['movimiento'] == 5 || $dMovimientoEmpaque['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoEmpaque['movimientos.rol'].".<br>";







if($dMovimientoEmpaque['horario'] != ""  && $dMovimientoEmpaque['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoEmpaque['horario'].".<br>";







if($dMovimientoEmpaque['premio'] != ""  && $dMovimientoEmpaque['premio'] != 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoEmpaque['puntualidad'] != ""  && $dMovimientoEmpaque['puntualidad'] != 0 ) echo "Afecta puntualidad.<br>";







if($dMovimientoEmpaque['productividad'] != ""  && $dMovimientoEmpaque['productividad'] != 0 ) echo "Afecta productividad.<br>";







if($dMovimientoEmpaque['no_premio'] != ""  && $dMovimientoEmpaque['no_premio'] != 0 ) echo "No afectar premio.<br>";







if($dMovimientoEmpaque['id_operador2'] != "" && $dMovimientoEmpaque['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoEmpaque['motivo'] ?><br></td></tr></table></td>              </tr>                <? } ?>
              <? } if($numEmpB > 0   && $_REQUEST['empb'] == 1 ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>Empaque B</h3></td>                </tr> 



<tr>                
<td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoEmpaqueB = mysql_fetch_assoc($rMovimientoEmpaqueB) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoEmpaqueB['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoEmpaqueB['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoEmpaqueB['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoEmpaqueB['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoEmpaqueB['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? 


if($dMovimientoEmpaqueB['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoEmpaqueB['nuevo_movimiento'].".<br>";







if($dMovimientoEmpaqueB['dia'] 



!= "" 
&& $dMovimientoEmpaqueB['dia'] 


!= "0000-00-00" && $dMovimientoEmpaqueB['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoEmpaqueB['dia']); echo ".<br>"; }







if($dMovimientoEmpaqueB['horas'] 


!= "" 
&& $dMovimientoEmpaqueB['horas'] 

!= 0) echo "Tiempo: ".$dMovimientoEmpaqueB['horas'].".<br>";







if($dMovimientoEmpaqueB['cantidad'] 

!= "" 
&& $dMovimientoEmpaqueB['cantidad'] 
!= 0) echo "Cantidad: $".$dMovimientoEmpaqueB['cantidad'].".<br>";







if($dMovimientoEmpaqueB['desde'] 


!= "" 
&& $dMovimientoEmpaqueB['desde'] 

!= "0000-00-00" && ($dMovimientoEmpaqueB['movimiento'] == 9 || $dMovimientoEmpaqueB['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoEmpaqueB['desde']); echo " al ";}







if($dMovimientoEmpaqueB['hasta'] 


!= "" 
&& $dMovimientoEmpaqueB['hasta'] 

!= "0000-00-00" && ($dMovimientoEmpaqueB['movimiento'] == 9 || $dMovimientoEmpaqueB['movimiento'] == 20 ) ){ fecha($dMovimientoEmpaqueB['hasta']); echo ".<br>";}







if($dMovimientoEmpaqueB['desde_tiempo'] 
!= "" 
&& $dMovimientoEmpaqueB['desde_tiempo'] != "00:00" 

&& ($dMovimientoEmpaqueB['movimiento'] == 23 || $dMovimientoEmpaqueB['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoEmpaqueB['desde_tiempo']; echo " al ";}







if($dMovimientoEmpaqueB['hasta_tiempo'] 
!= "" 
&& $dMovimientoEmpaqueB['hasta_tiempo'] != "00:00" 

&& ($dMovimientoEmpaqueB['movimiento'] == 23 || $dMovimientoEmpaqueB['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoEmpaqueB['hasta_tiempo']; echo ".<br>";}







if($dMovimientoEmpaqueB['turno'] 


!= "" 
&& $dMovimientoEmpaqueB['turno'] 

!= 0 


&& ($dMovimientoEmpaqueB['movimiento'] == 4 || $dMovimientoEmpaqueB['movimiento'] == 20 )) echo "Cambio al turno: ".$dMovimientoEmpaqueB['turno']. ".<br>";







if($dMovimientoEmpaqueB['movimientos.rol'] 



!= ""  
&& $dMovimientoEmpaqueB['movimientos.rol'] 


!= 0  


&& ($dMovimientoEmpaqueB['movimiento'] == 5 || $dMovimientoEmpaqueB['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoEmpaqueB['movimientos.rol'].".<br>";







if($dMovimientoEmpaqueB['horario'] 


!= ""  
&& $dMovimientoEmpaqueB['horario'] 

!= 0 ) echo "Cambio de horario: ".$dMovimientoEmpaqueB['horario'].".<br>";







if($dMovimientoEmpaqueB['premio'] 


!= "" 
&& $dMovimientoEmpaqueB['premio'] 

!= 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoEmpaqueB['puntualidad'] 

!= "" 
&& $dMovimientoEmpaqueB['puntualidad'] 
!= 0 ) echo "Afecta puntualidad.<br>"; 







if($dMovimientoEmpaqueB['productividad'] 
!= "" 
&& $dMovimientoEmpaqueB['productividad'] != 0 ) echo "Afecta productividad.<br>";







if($dMovimientoEmpaqueB['no_premio'] 
!= "" 
&& $dMovimientoEmpaqueB['no_premio'] != 0 ) echo "No afectar premio.<br>";







if($dMovimientoEmpaqueB['id_operador2'] 
!= "" 
&& $dMovimientoEmpaqueB['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoEmpaqueB['motivo'] ?><br></td></tr></table></td>              </tr>                <? } ?>                              <? }  if($numMantenimiento > 0  && $_REQUEST['mantto'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>MANTENIMIENTO</h3></td>                </tr> 



<tr>               
  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoMantenimiento = mysql_fetch_assoc($rMovimientoMantenimiento) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoMantenimiento['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoMantenimiento['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoMantenimiento['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoMantenimiento['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoMantenimiento['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? if($dMovimientoMantenimiento['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoMantenimiento['nuevo_movimiento'].".<br>";







if($dMovimientoMantenimiento['dia'] != "" && $dMovimientoMantenimiento['dia'] != "0000-00-00" && $dMovimientoMantenimiento['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoMantenimiento['dia']); echo ".<br>"; }







if($dMovimientoMantenimiento['horas'] != ""  && $dMovimientoMantenimiento['horas'] != 0) echo "Tiempo: ".$dMovimientoMantenimiento['horas'].".<br>";







if($dMovimientoMantenimiento['cantidad'] != "" && $dMovimientoMantenimiento['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoMantenimiento['cantidad'].".<br>";







if($dMovimientoMantenimiento['desde'] != "" && $dMovimientoMantenimiento['desde'] != "0000-00-00" && ($dMovimientoMantenimiento['movimiento'] == 9 || $dMovimientoMantenimiento['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoMantenimiento['desde']); echo " al ";}







if($dMovimientoMantenimiento['hasta'] != "" && $dMovimientoMantenimiento['hasta'] != "0000-00-00" && ($dMovimientoMantenimiento['movimiento'] == 9 || $dMovimientoMantenimiento['movimiento'] == 20 ) ){ fecha($dMovimientoMantenimiento['hasta']); echo ".<br>";}







if($dMovimientoMantenimiento['desde_tiempo'] != "" && $dMovimientoMantenimiento['desde_tiempo'] != "00:00" && ($dMovimientoMantenimiento['movimiento'] == 23 || $dMovimientoMantenimiento['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoMantenimiento['desde_tiempo']; echo " al ";}







if($dMovimientoMantenimiento['hasta_tiempo'] != "" && $dMovimientoMantenimiento['hasta_tiempo'] != "00:00" && ($dMovimientoMantenimiento['movimiento'] == 23 || $dMovimientoMantenimiento['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoMantenimiento['hasta_tiempo']; echo ".<br>";}







if($dMovimientoMantenimiento['turno'] != "" && $dMovimientoMantenimiento['turno'] != 0 && ($dMovimientoMantenimiento['movimiento'] == 4 || $dMovimientoMantenimiento['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoMantenimiento['turno']. ".<br>";







if($dMovimientoMantenimiento['movimientos.rol'] != ""  &&  $dMovimientoMantenimiento['movimientos.rol'] != 0  && ($dMovimientoMantenimiento['movimiento'] == 5 || $dMovimientoMantenimiento['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoMantenimiento['movimientos.rol'].".<br>";







if($dMovimientoMantenimiento['horario'] != ""  && $dMovimientoMantenimiento['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoMantenimiento['horario'].".<br>";







if($dMovimientoMantenimiento['premio'] != ""  && $dMovimientoMantenimiento['premio'] != 0 ) echo "Lo que proceda con premio.<br>";







if($dMovimientoMantenimiento['puntualidad'] != ""  && $dMovimientoMantenimiento['puntualidad'] != 0 ) echo "Afecta puntualidad.<br>";







if($dMovimientoMantenimiento['productividad'] != ""  && $dMovimientoMantenimiento['productividad'] != 0 ) echo "Afecta productividad.<br>"; 







if($dMovimientoMantenimiento['no_premio'] != ""  && $dMovimientoMantenimiento['no_premio'] != 0 ) echo "No afecta productividad.<br>"; 







if($dMovimientoMantenimiento['id_operador2'] != "" && $dMovimientoMantenimiento['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoMantenimiento['motivo'] ?><br></td></tr></table></td>              </tr>                <? } ?>
                            <? }  if($numMantenimientoB > 0  && $_REQUEST['manttob'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>MANTENIMIENTO B</h3></td>                </tr> 



<tr>               
  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoMantenimientoB = mysql_fetch_assoc($rMovimientoMantenimientoB) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoMantenimientoB['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoMantenimientoB['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoMantenimientoB['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoMantenimientoB['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoMantenimientoB['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? 


if($dMovimientoMantenimientoB['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoMantenimientoB['nuevo_movimiento'].".<br>";







if($dMovimientoMantenimientoB['dia'] != "" && $dMovimientoMantenimientoB['dia'] != "0000-00-00" && $dMovimientoMantenimientoB['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoMantenimientoB['dia']); echo ".<br>"; }







if($dMovimientoMantenimientoB['horas'] != ""  && $dMovimientoMantenimientoB['horas'] != 0) echo "Tiempo: ".$dMovimientoMantenimientoB['horas'].".<br>";







if($dMovimientoMantenimientoB['cantidad'] != "" && $dMovimientoMantenimientoB['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoMantenimientoB['cantidad'].".<br>";







if($dMovimientoMantenimientoB['desde'] != "" && $dMovimientoMantenimientoB['desde'] != "0000-00-00" && ($dMovimientoMantenimientoB['movimiento'] == 9 || $dMovimientoMantenimientoB['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoMantenimientoB['desde']); echo " al ";}







if($dMovimientoMantenimientoB['hasta'] != "" && $dMovimientoMantenimientoB['hasta'] != "0000-00-00" && ($dMovimientoMantenimientoB['movimiento'] == 9 || $dMovimientoMantenimientoB['movimiento'] == 20 ) ){ fecha($dMovimientoMantenimientoB['hasta']); echo ".<br>";}







if($dMovimientoMantenimientoB['desde_tiempo'] != "" && $dMovimientoMantenimientoB['desde_tiempo'] != "00:00" && ($dMovimientoMantenimientoB['movimiento'] == 23 || $dMovimientoMantenimientoB['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoMantenimientoB['desde_tiempo']; echo " al ";}







if($dMovimientoMantenimientoB['hasta_tiempo'] != "" && $dMovimientoMantenimientoB['hasta_tiempo'] != "00:00" && ($dMovimientoMantenimientoB['movimiento'] == 23 || $dMovimientoMantenimientoB['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoMantenimientoB['hasta_tiempo']; echo ".<br>";}







if($dMovimientoMantenimientoB['turno'] != "" && $dMovimientoMantenimientoB['turno'] != 0 && ($dMovimientoMantenimientoB['movimiento'] == 4 || $dMovimientoMantenimientoB['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoMantenimientoB['turno']. ".<br>";







if($dMovimientoMantenimientoB['movimientos.rol'] != ""  &&  $dMovimientoMantenimientoB['movimientos.rol'] != 0  && ($dMovimientoMantenimientoB['movimiento'] == 5 || $dMovimientoMantenimientoB['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoMantenimientoB['movimientos.rol'].".<br>";







if($dMovimientoMantenimientoB['horario'] != ""  && $dMovimientoMantenimientoB['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoMantenimientoB['horario'].".<br>";







if($dMovimientoMantenimientoB['premio'] != ""  && $dMovimientoMantenimientoB['premio'] != 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoMantenimientoB['puntualidad'] != ""  && $dMovimientoMantenimientoB['puntualidad'] != 0 ) echo "Afecta puntualidad : SI.<br>"; 







if($dMovimientoMantenimientoB['productividad'] != ""  && $dMovimientoMantenimientoB['productividad'] != 0 ) echo "Afecta productividad <br>"; 







if($dMovimientoMantenimientoB['no_premio'] != ""  && $dMovimientoMantenimientoB['no_premio'] != 0 ) echo "No afecta productividad <br>"; 







if($dMovimientoMantenimientoB['id_operador2'] != "" && $dMovimientoMantenimientoB['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoMantenimientoB['motivo'] ?><br></td></tr></table></td>              </tr>                <? }  } if($numAlmacen > 0  && $_REQUEST['alm'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>ALMACEN </h3></td>                </tr> 



<tr>               
  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoAlmacen = mysql_fetch_assoc($rMovimientoAlmacen) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoAlmacen['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoAlmacen['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoAlmacen['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoAlmacen['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoAlmacen['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? 


if($dMovimientoAlmacen['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoAlmacen['nuevo_movimiento'].".<br>";







if($dMovimientoAlmacen['dia'] != "" && $dMovimientoAlmacen['dia'] != "0000-00-00" && $dMovimientoAlmacen['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoAlmacen['dia']); echo ".<br>"; }







if($dMovimientoAlmacen['horas'] != ""  && $dMovimientoAlmacen['horas'] != 0) echo "Tiempo: ".$dMovimientoAlmacen['horas'].".<br>";







if($dMovimientoAlmacen['cantidad'] != "" && $dMovimientoAlmacen['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoAlmacen['cantidad'].".<br>";







if($dMovimientoAlmacen['desde'] != "" && $dMovimientoAlmacen['desde'] != "0000-00-00" && ($dMovimientoAlmacen['movimiento'] == 9 || $dMovimientoAlmacen['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoAlmacen['desde']); echo " al ";}







if($dMovimientoAlmacen['hasta'] != "" && $dMovimientoAlmacen['hasta'] != "0000-00-00" && ($dMovimientoAlmacen['movimiento'] == 9 || $dMovimientoAlmacen['movimiento'] == 20 ) ){ fecha($dMovimientoAlmacen['hasta']); echo ".<br>";}







if($dMovimientoAlmacen['desde_tiempo'] != "" && $dMovimientoAlmacen['desde_tiempo'] != "00:00" && ($dMovimientoAlmacen['movimiento'] == 23 || $dMovimientoAlmacen['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoAlmacen['desde_tiempo']; echo " al ";}







if($dMovimientoAlmacen['hasta_tiempo'] != "" && $dMovimientoAlmacen['hasta_tiempo'] != "00:00" && ($dMovimientoAlmacen['movimiento'] == 23 || $dMovimientoAlmacen['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoAlmacen['hasta_tiempo']; echo ".<br>";}







if($dMovimientoAlmacen['turno'] != "" && $dMovimientoAlmacen['turno'] != 0 && ($dMovimientoAlmacen['movimiento'] == 4 || $dMovimientoAlmacen['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoAlmacen['turno']. ".<br>";







if($dMovimientoAlmacen['movimientos.rol'] != ""  &&  $dMovimientoAlmacen['movimientos.rol'] != 0  && ($dMovimientoAlmacen['movimiento'] == 5 || $dMovimientoAlmacen['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoAlmacen['movimientos.rol'].".<br>";







if($dMovimientoAlmacen['horario'] != ""  && $dMovimientoAlmacen['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoAlmacen['horario'].".<br>";







if($dMovimientoAlmacen['premio'] != ""  && $dMovimientoAlmacen['premio'] != 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoAlmacen['puntualidad'] != ""  && $dMovimientoAlmacen['puntualidad'] != 0 ) echo "Afecta puntualidad : SI.<br>"; 







if($dMovimientoAlmacen['productividad'] != ""  && $dMovimientoAlmacen['productividad'] != 0 ) echo "Afecta productividad <br>"; 







if($dMovimientoAlmacen['no_premio'] != ""  && $dMovimientoAlmacen['no_premio'] != 0 ) echo "No afecta productividad <br>"; 







if($dMovimientoAlmacen['id_operador2'] != "" && $dMovimientoAlmacen['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoAlmacen['motivo'] ?><br></td></tr></table></td>              </tr>                <? } }  if($numAlmacenB > 0  && $_REQUEST['almb'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>ALMACEN B</h3></td>                </tr> 



<tr>               
  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoAlmacenB = mysql_fetch_assoc($rMovimientoAlmacenB) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoAlmacenB['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoAlmacenB['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoAlmacenB['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoAlmacenB['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoAlmacenB['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? 


if($dMovimientoAlmacenB['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoAlmacenB['nuevo_movimiento'].".<br>";







if($dMovimientoAlmacenB['dia'] != "" && $dMovimientoAlmacenB['dia'] != "0000-00-00" && $dMovimientoAlmacenB['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoAlmacenB['dia']); echo ".<br>"; }







if($dMovimientoAlmacenB['horas'] != ""  && $dMovimientoAlmacenB['horas'] != 0) echo "Tiempo: ".$dMovimientoAlmacenB['horas'].".<br>";







if($dMovimientoAlmacenB['cantidad'] != "" && $dMovimientoAlmacenB['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoAlmacenB['cantidad'].".<br>";







if($dMovimientoAlmacenB['desde'] != "" && $dMovimientoAlmacenB['desde'] != "0000-00-00" && ($dMovimientoAlmacenB['movimiento'] == 9 || $dMovimientoAlmacenB['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoAlmacenB['desde']); echo " al ";}







if($dMovimientoAlmacenB['hasta'] != "" && $dMovimientoAlmacenB['hasta'] != "0000-00-00" && ($dMovimientoAlmacenB['movimiento'] == 9 || $dMovimientoAlmacenB['movimiento'] == 20 ) ){ fecha($dMovimientoAlmacenB['hasta']); echo ".<br>";}







if($dMovimientoAlmacenB['desde_tiempo'] != "" && $dMovimientoAlmacenB['desde_tiempo'] != "00:00" && ($dMovimientoAlmacenB['movimiento'] == 23 || $dMovimientoAlmacenB['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoAlmacenB['desde_tiempo']; echo " al ";}







if($dMovimientoAlmacenB['hasta_tiempo'] != "" && $dMovimientoAlmacenB['hasta_tiempo'] != "00:00" && ($dMovimientoAlmacenB['movimiento'] == 23 || $dMovimientoAlmacenB['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoAlmacenB['hasta_tiempo']; echo ".<br>";}







if($dMovimientoAlmacenB['turno'] != "" && $dMovimientoAlmacenB['turno'] != 0 && ($dMovimientoAlmacenB['movimiento'] == 4 || $dMovimientoAlmacenB['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoAlmacenB['turno']. ".<br>";







if($dMovimientoAlmacenB['movimientos.rol'] != ""  &&  $dMovimientoAlmacenB['movimientos.rol'] != 0  && ($dMovimientoAlmacenB['movimiento'] == 5 || $dMovimientoAlmacenB['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoAlmacenB['movimientos.rol'].".<br>";







if($dMovimientoAlmacenB['horario'] != ""  && $dMovimientoAlmacenB['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoAlmacenB['horario'].".<br>";







if($dMovimientoAlmacenB['premio'] != ""  && $dMovimientoAlmacenB['premio'] != 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoAlmacenB['puntualidad'] != ""  && $dMovimientoAlmacenB['puntualidad'] != 0 ) echo "Afecta puntualidad : SI.<br>"; 







if($dMovimientoAlmacenB['productividad'] != ""  && $dMovimientoAlmacenB['productividad'] != 0 ) echo "Afecta productividad <br>"; 







if($dMovimientoAlmacenB['no_premio'] != ""  && $dMovimientoAlmacenB['no_premio'] != 0 ) echo "No afecta productividad <br>"; 







if($dMovimientoAlmacenB['id_operador2'] != "" && $dMovimientoAlmacenB['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoAlmacenB['motivo'] ?><br></td></tr></table></td>              </tr>                <? }   ?>                                                <? }  if($_REQUEST['movimiento'] == 3){ 
$qMovimientoExtruder

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 1 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoImpresion

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 3 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoBolseo


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 2 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoEmpaque


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 5 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoEmpaqueB

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 7 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoMantenimiento
=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 4 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoMantenimientoB
=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND area = 6 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoAlmacen


=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND ( area = 8 OR almacen = 1 ) AND ( movimiento = 10 AND movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
$qMovimientoAlamcenB

=
"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '".$anio_as."' AND ( area = 9  ) AND ( movimiento = 10 AND movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";

$rMovimientoExtruder


=
mysql_query($qMovimientoExtruder);
$rMovimientoImpresion


=
mysql_query($qMovimientoImpresion);
$rMovimientoBolseo



=
mysql_query($qMovimientoBolseo);
$rMovimientoEmpaque



=
mysql_query($qMovimientoEmpaque);
$rMovimientoEmpaqueB


=
mysql_query($qMovimientoEmpaqueB);
$rMovimientoMantenimiento

=
mysql_query($qMovimientoMantenimiento);
$rMovimientoMantenimientoB

=
mysql_query($qMovimientoMantenimientoB);
$rMovimientoAlmacen



=
mysql_query($qMovimientoAlmacen);
$rMovimientoAlmacenB


=
mysql_query($qMovimientoAlmacenB);
$numExtruder


=
mysql_num_rows($rMovimientoExtruder);
$numImpresion


=
mysql_num_rows($rMovimientoImpresion);
$numBolseo



=
mysql_num_rows($rMovimientoBolseo);
$numEmp




=
mysql_num_rows($rMovimientoEmpaque);
$numEmpB



=
mysql_num_rows($rMovimientoEmpaqueB);
$numMantenimiento

=
mysql_num_rows($rMovimientoMantenimiento);
$numMantenimientoB

=
mysql_num_rows($rMovimientoMantenimientoB);
$numAlmacen



=
mysql_num_rows($rMovimientoAlmacen);
$numAlmacenB


=
mysql_num_rows($rMovimientoAlmacenB);
?><? if( $numExtruder > 0 || $numImpresion > 0 || $numBolseo > 0 ||$numEmp > 0 || $numEmpB > 0 ||$numMantenimiento > 0 ||$numMantenimientoB  > 0 || $numAlmacen > 0 || $numAlmacenB > 0){ ?>            <tr>            
<td colspan="10" align="center" style="font-size:12px"><br><br><br>MOVIMIENTOS ECONOMICOS</td>            </tr>       <? } ?>            <? if( $numExtruder > 0 &&  $_REQUEST['extruder'] == 1 ){?>       
  <tr>                
<td colspan="5" align="center"><h3>EXTRUDER</h3></td>                </tr> 



<tr>                  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Motivo</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoExtruder = mysql_fetch_assoc($rMovimientoExtruder) ;$a++){








$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoExtruder['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);












 ?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoExtruder['fecha'])?></td>               
  <td align="center" class="style5"><?=$dMovimientoExtruder['numnomina']?></td>               
  <td align="left" class="style5"><?=$dMovimientoExtruder['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoExtruder['movimiento']]?></td>               
  <td align="justify" class="style5"  style="text-align:justify">                  <table width="96%" style="background-color:transparent;">                    <tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? if($dMovimientoExtruder['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoExtruder['nuevo_movimiento'].".<br>";







if($dMovimientoExtruder['dia'] != "" && $dMovimientoExtruder['dia'] != "0000-00-00" ){ echo "Fecha a cubrir: "; fecha($dMovimientoExtruder['dia']); echo ".<br>"; }







if($dMovimientoExtruder['horas'] != ""  && $dMovimientoExtruder['horas'] != 0) echo "Tiempo: ".$dMovimientoExtruder['horas'].".<br>";







if($dMovimientoExtruder['cantidad'] != "" && $dMovimientoExtruder['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoExtruder['cantidad']."<br>";







if($dMovimientoExtruder['desde'] != "" && $dMovimientoExtruder['desde'] != "0000-00-00" && ($dMovimientoExtruder['movimiento'] == 9 || $dMovimientoExtruder['movimiento'] == 20 )){ echo "Del "; fecha($dMovimientoExtruder['desde']); echo " al ";}







if($dMovimientoExtruder['hasta'] != "" && $dMovimientoExtruder['hasta'] != "0000-00-00" && ($dMovimientoExtruder['movimiento'] == 9 || $dMovimientoExtruder['movimiento'] == 20 )){ fecha($dMovimientoExtruder['hasta']); echo ".<br>";}







if($dMovimientoExtruder['desde_tiempo'] != "" && $dMovimientoExtruder['desde_tiempo'] != "00:00" && ($dMovimientoExtruder['movimiento'] == 23 || $dMovimientoExtruder['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoExtruder['desde_tiempo']; echo " al ";}







if($dMovimientoExtruder['hasta_tiempo'] != "" && $dMovimientoExtruder['hasta_tiempo'] != "00:00" && ($dMovimientoExtruder['movimiento'] == 23 || $dMovimientoExtruder['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoExtruder['hasta_tiempo']; echo ".<br>";}







if($dMovimientoExtruder['turno'] != "" && $dMovimientoExtruder['turno'] != 0 && ($dMovimientoExtruder['movimiento'] == 4 || $dMovimientoExtruder['movimiento'] == 20 )) echo "Cambio al turno: ".
$dMovimientoExtruder['turno']. ".<br>";







if($dMovimientoExtruder['movimientos.rol'] != ""  &&  $dMovimientoExtruder['movimientos.rol'] != 0 && ($dMovimientoExtruder['movimiento'] == 5 || $dMovimientoExtruder['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoExtruder['movimientos.rol'].".<br>";







if($dMovimientoExtruder['horario'] != ""  && $dMovimientoExtruder['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoExtruder['horario']."<br>";







if($dMovimientoExtruder['premio'] != ""  && $dMovimientoExtruder['premio'] != 0 ) echo "Lo que proceda con premios.<br>"; 







if($dMovimientoExtruder['puntualidad'] != ""  && $dMovimientoExtruder['puntualidad'] != 0 ) echo "Afecta puntualidad.<br>"; 







if($dMovimientoExtruder['productividad'] != ""  && $dMovimientoExtruder['productividad'] != 0 ) echo "Afecta productividad.<br>";







if($dMovimientoExtruder['no_premio'] != ""  && $dMovimientoExtruder['no_premio'] != 0 ) echo "No afectar premio.<br>";







if($dMovimientoExtruder['id_operador2'] != "" && $dMovimientoExtruder['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoExtruder['motivo'] ?></td></tr></table></td>              </tr>                <? } ?>

              <? } if($numImpresion > 0 && $_REQUEST['impresion'] == 1  ){?> 
       
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>IMPRESION</h3></td>                </tr> 



<tr>                
<td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoImpresion = mysql_fetch_assoc($rMovimientoImpresion) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoImpresion['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoImpresion['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoImpresion['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoImpresion['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoImpresion['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? if($dMovimientoImpresion['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoImpresion['nuevo_movimiento'].".<br>";







if($dMovimientoImpresion['dia'] != "" && $dMovimientoImpresion['dia'] != "0000-00-00" && $dMovimientoImpresion['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoImpresion['dia']); echo ".<br>"; }







if($dMovimientoImpresion['horas'] != ""  && $dMovimientoImpresion['horas'] != 0) echo "Tiempo: ".$dMovimientoImpresion['horas'].".<br>";







if($dMovimientoImpresion['cantidad'] != "" && $dMovimientoImpresion['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoImpresion['cantidad'].".<br>";







if($dMovimientoImpresion['desde'] != "" && $dMovimientoImpresion['desde'] != "0000-00-00" && ($dMovimientoImpresion['movimiento'] == 9 || $dMovimientoImpresion['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoImpresion['desde']); echo " al ";}







if($dMovimientoImpresion['hasta'] != "" && $dMovimientoImpresion['hasta'] != "0000-00-00" && ($dMovimientoImpresion['movimiento'] == 9 || $dMovimientoImpresion['movimiento'] == 20 ) ){ fecha($dMovimientoImpresion['hasta']); echo ".<br>";}







if($dMovimientoImpresion['desde_tiempo'] != "" && $dMovimientoImpresion['desde_tiempo'] != "00:00" && ($dMovimientoImpresion['movimiento'] == 23 || $dMovimientoImpresion['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoImpresion['desde_tiempo']; echo " al ";}







if($dMovimientoImpresion['hasta_tiempo'] != "" && $dMovimientoImpresion['hasta_tiempo'] != "00:00" && ($dMovimientoImpresion['movimiento'] == 23 || $dMovimientoImpresion['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoImpresion['hasta_tiempo']; echo ".<br>";}







if($dMovimientoImpresion['turno'] != "" && $dMovimientoImpresion['turno'] != 0 && ($dMovimientoImpresion['movimiento'] == 4 || $dMovimientoImpresion['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoImpresion['turno']. ".<br>";







if($dMovimientoImpresion['movimientos.rol'] != ""  &&  $dMovimientoImpresion['movimientos.rol'] != 0  && ($dMovimientoImpresion['movimiento'] == 5 || $dMovimientoImpresion['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoImpresion['movimientos.rol'].".<br>";







if($dMovimientoImpresion['horario'] != ""  && $dMovimientoImpresion['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoImpresion['horario'].".<br>";







if($dMovimientoImpresion['premio'] != ""  && $dMovimientoImpresion['premio'] != 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoImpresion['puntualidad'] != ""    && $dMovimientoImpresion['puntualidad'] != 0 ) echo "Afecta puntualidad..<br>"; 







if($dMovimientoImpresion['productividad'] != ""  && $dMovimientoImpresion['productividad'] != 0 ) echo "Afecta productividad.<br>";







if($dMovimientoImpresion['no_premio'] != ""  && $dMovimientoImpresion['no_premio'] != 0 ) echo "No afectar premio.<br>";







if($dMovimientoImpresion['id_operador2'] != ""   && $dMovimientoImpresion['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoImpresion['motivo'] ?><br></td></tr></table></td>              </tr>                <? } ?>
              <? } if($numBolseo > 0  && $_REQUEST['bolseo'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>BOLSEO</h3></td>                </tr> 



<tr>                  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Motivo</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoBolseo = mysql_fetch_assoc($rMovimientoBolseo) ;$a++){ 




$qE2
=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoBolseo['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);









?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoBolseo['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoBolseo['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoBolseo['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoBolseo['movimiento']]?></td>               
  <td align="justify"  class="style5"  style="text-align:justify">



  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? if($dMovimientoBolseo['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoBolseo['nuevo_movimiento'].".<br>";







if($dMovimientoBolseo['dia'] != "" && $dMovimientoBolseo['dia'] != "0000-00-00" ){ echo "Dia: "; fecha($dMovimientoBolseo['dia']); echo ".<br>"; }







if($dMovimientoBolseo['horas'] != ""  && $dMovimientoBolseo['horas'] != 0) echo "Tiempo: ".$dMovimientoBolseo['horas'].".<br>";







if($dMovimientoBolseo['cantidad'] != "" && $dMovimientoBolseo['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoBolseo['cantidad'].".<br>";







if($dMovimientoBolseo['desde'] != "" && $dMovimientoBolseo['desde'] != "0000-00-00" && ($dMovimientoBolseo['movimiento'] == 9 || $dMovimientoBolseo['movimiento'] == 20 ) ){ echo "Del: "; fecha($dMovimientoBolseo['desde']); echo " al ";}







if($dMovimientoBolseo['hasta'] != "" && $dMovimientoBolseo['hasta'] != "0000-00-00" && ($dMovimientoBolseo['movimiento'] == 9 || $dMovimientoBolseo['movimiento'] == 20 )){  fecha($dMovimientoBolseo['hasta']); echo ".<br>";}







if($dMovimientoBolseo['desde_tiempo'] != "" && $dMovimientoBolseo['desde_tiempo'] != "00:00" && ($dMovimientoBolseo['movimiento'] == 23 || $dMovimientoBolseo['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoBolseo['desde_tiempo']; echo " al ";}







if($dMovimientoBolseo['hasta_tiempo'] != "" && $dMovimientoBolseo['hasta_tiempo'] != "00:00" && ($dMovimientoBolseo['movimiento'] == 23 || $dMovimientoBolseo['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoBolseo['hasta_tiempo']; echo ".<br>";}







if($dMovimientoBolseo['turno'] != "" && $dMovimientoBolseo['turno'] != 0 && ($dMovimientoBolseo['movimiento'] == 4 || $dMovimientoBolseo['movimiento'] == 20 )) echo "Cambio al turno: ".
$dMovimientoBolseo['turno']. ".<br>";







if($dMovimientoBolseo['movimientos.rol'] != ""  &&  $dMovimientoBolseo['movimientos.rol'] != 0 && ($dMovimientoBolseo['movimiento'] == 5 || $dMovimientoBolseo['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoBolseo['movimientos.rol'].".<br>";







if($dMovimientoBolseo['horario'] != ""  && $dMovimientoBolseo['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoBolseo['horario']."<br>";







if($dMovimientoBolseo['premio'] != ""  && $dMovimientoBolseo['premio'] != 0 ) echo "Lo que porceda con premios.<br>"; 







if($dMovimientoBolseo['puntualidad'] != ""  && $dMovimientoBolseo['puntualidad'] != 0 ) echo "Afecta puntualidad.<br>"; 







if($dMovimientoBolseo['productividad'] != ""  && $dMovimientoBolseo['productividad'] != 0 ) echo "Afecta productividad.<br>"; 







if($dMovimientoBolseo['no_premio'] != ""  && $dMovimientoBolseo['no_premio'] != 0 ) echo "No afectar premio.<br>"; 







if($dMovimientoBolseo['id_operador2'] != "" && $dMovimientoBolseo['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoBolseo['motivo'] ?><br>                                    </td></tr></table></td>              </tr>              <? } 


  } if($numEmp > 0  && $_REQUEST['emp'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>Empaque</h3></td>                </tr> 



<tr>                
<td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoEmpaque = mysql_fetch_assoc($rMovimientoEmpaque) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoEmpaque['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoEmpaque['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoEmpaque['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoEmpaque['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoEmpaque['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? 


if($dMovimientoEmpaque['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoEmpaque['nuevo_movimiento'].".<br>";







if($dMovimientoEmpaque['dia'] != "" && $dMovimientoEmpaque['dia'] != "0000-00-00" && $dMovimientoEmpaque['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoEmpaque['dia']); echo ".<br>"; }







if($dMovimientoEmpaque['horas'] != ""  && $dMovimientoEmpaque['horas'] != 0) echo "Tiempo: ".$dMovimientoEmpaque['horas'].".<br>";







if($dMovimientoEmpaque['cantidad'] != "" && $dMovimientoEmpaque['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoEmpaque['cantidad'].".<br>";







if($dMovimientoEmpaque['desde'] != "" && $dMovimientoEmpaque['desde'] != "0000-00-00" && ($dMovimientoEmpaque['movimiento'] == 9 || $dMovimientoEmpaque['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoEmpaque['desde']); echo " al ";}







if($dMovimientoEmpaque['hasta'] != "" && $dMovimientoEmpaque['hasta'] != "0000-00-00" && ($dMovimientoEmpaque['movimiento'] == 9 || $dMovimientoEmpaque['movimiento'] == 20 ) ){ fecha($dMovimientoEmpaque['hasta']); echo ".<br>";}







if($dMovimientoEmpaque['desde_tiempo'] != "" && $dMovimientoEmpaque['desde_tiempo'] != "00:00" && ($dMovimientoEmpaque['movimiento'] == 23 || $dMovimientoEmpaque['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoEmpaque['desde_tiempo']; echo " al ";}







if($dMovimientoEmpaque['hasta_tiempo'] != "" && $dMovimientoEmpaque['hasta_tiempo'] != "00:00" && ($dMovimientoEmpaque['movimiento'] == 23 || $dMovimientoEmpaque['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoEmpaque['hasta_tiempo']; echo ".<br>";}







if($dMovimientoEmpaque['turno'] != "" && $dMovimientoEmpaque['turno'] != 0 && ($dMovimientoEmpaque['movimiento'] == 4 || $dMovimientoEmpaque['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoEmpaque['turno']. ".<br>";







if($dMovimientoEmpaque['movimientos.rol'] != ""  &&  $dMovimientoEmpaque['movimientos.rol'] != 0  && ($dMovimientoEmpaque['movimiento'] == 5 || $dMovimientoEmpaque['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoEmpaque['movimientos.rol'].".<br>";







if($dMovimientoEmpaque['horario'] != ""  && $dMovimientoEmpaque['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoEmpaque['horario'].".<br>";







if($dMovimientoEmpaque['premio'] != ""  && $dMovimientoEmpaque['premio'] != 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoEmpaque['puntualidad'] != ""  && $dMovimientoEmpaque['puntualidad'] != 0 ) echo "Afecta puntualidad.<br>";







if($dMovimientoEmpaque['productividad'] != ""  && $dMovimientoEmpaque['productividad'] != 0 ) echo "Afecta productividad.<br>";







if($dMovimientoEmpaque['no_premio'] != ""  && $dMovimientoEmpaque['no_premio'] != 0 ) echo "No afectar premio.<br>";







if($dMovimientoEmpaque['id_operador2'] != "" && $dMovimientoEmpaque['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoEmpaque['motivo'] ?><br></td></tr></table></td>              </tr>                <? } ?>
              <? } if($numEmpB > 0   && $_REQUEST['empb'] == 1 ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>Empaque B</h3></td>                </tr> 



<tr>                
<td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoEmpaqueB = mysql_fetch_assoc($rMovimientoEmpaqueB) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoEmpaqueB['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoEmpaqueB['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoEmpaqueB['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoEmpaqueB['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoEmpaqueB['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? 


if($dMovimientoEmpaqueB['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoEmpaqueB['nuevo_movimiento'].".<br>";







if($dMovimientoEmpaqueB['dia'] 



!= "" 
&& $dMovimientoEmpaqueB['dia'] 


!= "0000-00-00" && $dMovimientoEmpaqueB['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoEmpaqueB['dia']); echo ".<br>"; }







if($dMovimientoEmpaqueB['horas'] 


!= "" 
&& $dMovimientoEmpaqueB['horas'] 

!= 0) echo "Tiempo: ".$dMovimientoEmpaqueB['horas'].".<br>";







if($dMovimientoEmpaqueB['cantidad'] 

!= "" 
&& $dMovimientoEmpaqueB['cantidad'] 
!= 0) echo "Cantidad: $".$dMovimientoEmpaqueB['cantidad'].".<br>";







if($dMovimientoEmpaqueB['desde'] 


!= "" 
&& $dMovimientoEmpaqueB['desde'] 

!= "0000-00-00" && ($dMovimientoEmpaqueB['movimiento'] == 9 || $dMovimientoEmpaqueB['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoEmpaqueB['desde']); echo " al ";}







if($dMovimientoEmpaqueB['hasta'] 


!= "" 
&& $dMovimientoEmpaqueB['hasta'] 

!= "0000-00-00" && ($dMovimientoEmpaqueB['movimiento'] == 9 || $dMovimientoEmpaqueB['movimiento'] == 20 ) ){ fecha($dMovimientoEmpaqueB['hasta']); echo ".<br>";}







if($dMovimientoEmpaqueB['desde_tiempo'] 
!= "" 
&& $dMovimientoEmpaqueB['desde_tiempo'] != "00:00" 

&& ($dMovimientoEmpaqueB['movimiento'] == 23 || $dMovimientoEmpaqueB['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoEmpaqueB['desde_tiempo']; echo " al ";}







if($dMovimientoEmpaqueB['hasta_tiempo'] 
!= "" 
&& $dMovimientoEmpaqueB['hasta_tiempo'] != "00:00" 

&& ($dMovimientoEmpaqueB['movimiento'] == 23 || $dMovimientoEmpaqueB['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoEmpaqueB['hasta_tiempo']; echo ".<br>";}







if($dMovimientoEmpaqueB['turno'] 


!= "" 
&& $dMovimientoEmpaqueB['turno'] 

!= 0 


&& ($dMovimientoEmpaqueB['movimiento'] == 4 || $dMovimientoEmpaqueB['movimiento'] == 20 )) echo "Cambio al turno: ".$dMovimientoEmpaqueB['turno']. ".<br>";







if($dMovimientoEmpaqueB['movimientos.rol'] 



!= ""  
&& $dMovimientoEmpaqueB['movimientos.rol'] 


!= 0  


&& ($dMovimientoEmpaqueB['movimiento'] == 5 || $dMovimientoEmpaqueB['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoEmpaqueB['movimientos.rol'].".<br>";







if($dMovimientoEmpaqueB['horario'] 


!= ""  
&& $dMovimientoEmpaqueB['horario'] 

!= 0 ) echo "Cambio de horario: ".$dMovimientoEmpaqueB['horario'].".<br>";







if($dMovimientoEmpaqueB['premio'] 


!= "" 
&& $dMovimientoEmpaqueB['premio'] 

!= 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoEmpaqueB['puntualidad'] 

!= "" 
&& $dMovimientoEmpaqueB['puntualidad'] 
!= 0 ) echo "Afecta puntualidad.<br>"; 







if($dMovimientoEmpaqueB['productividad'] 
!= "" 
&& $dMovimientoEmpaqueB['productividad'] != 0 ) echo "Afecta productividad.<br>";







if($dMovimientoEmpaqueB['no_premio'] 
!= "" 
&& $dMovimientoEmpaqueB['no_premio'] != 0 ) echo "No afectar premio.<br>";







if($dMovimientoEmpaqueB['id_operador2'] 
!= "" 
&& $dMovimientoEmpaqueB['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoEmpaqueB['motivo'] ?><br></td></tr></table></td>              </tr>                <? } ?>                              <? }  if($numMantenimiento > 0  && $_REQUEST['mantto'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>MANTENIMIENTO</h3></td>                </tr> 



<tr>               
  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoMantenimiento = mysql_fetch_assoc($rMovimientoMantenimiento) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoMantenimiento['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoMantenimiento['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoMantenimiento['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoMantenimiento['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoMantenimiento['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? if($dMovimientoMantenimiento['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoMantenimiento['nuevo_movimiento'].".<br>";







if($dMovimientoMantenimiento['dia'] != "" && $dMovimientoMantenimiento['dia'] != "0000-00-00" && $dMovimientoMantenimiento['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoMantenimiento['dia']); echo ".<br>"; }







if($dMovimientoMantenimiento['horas'] != ""  && $dMovimientoMantenimiento['horas'] != 0) echo "Tiempo: ".$dMovimientoMantenimiento['horas'].".<br>";







if($dMovimientoMantenimiento['cantidad'] != "" && $dMovimientoMantenimiento['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoMantenimiento['cantidad'].".<br>";







if($dMovimientoMantenimiento['desde'] != "" && $dMovimientoMantenimiento['desde'] != "0000-00-00" && ($dMovimientoMantenimiento['movimiento'] == 9 || $dMovimientoMantenimiento['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoMantenimiento['desde']); echo " al ";}







if($dMovimientoMantenimiento['hasta'] != "" && $dMovimientoMantenimiento['hasta'] != "0000-00-00" && ($dMovimientoMantenimiento['movimiento'] == 9 || $dMovimientoMantenimiento['movimiento'] == 20 ) ){ fecha($dMovimientoMantenimiento['hasta']); echo ".<br>";}







if($dMovimientoMantenimiento['desde_tiempo'] != "" && $dMovimientoMantenimiento['desde_tiempo'] != "00:00" && ($dMovimientoMantenimiento['movimiento'] == 23 || $dMovimientoMantenimiento['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoMantenimiento['desde_tiempo']; echo " al ";}







if($dMovimientoMantenimiento['hasta_tiempo'] != "" && $dMovimientoMantenimiento['hasta_tiempo'] != "00:00" && ($dMovimientoMantenimiento['movimiento'] == 23 || $dMovimientoMantenimiento['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoMantenimiento['hasta_tiempo']; echo ".<br>";}







if($dMovimientoMantenimiento['turno'] != "" && $dMovimientoMantenimiento['turno'] != 0 && ($dMovimientoMantenimiento['movimiento'] == 4 || $dMovimientoMantenimiento['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoMantenimiento['turno']. ".<br>";







if($dMovimientoMantenimiento['movimientos.rol'] != ""  &&  $dMovimientoMantenimiento['movimientos.rol'] != 0  && ($dMovimientoMantenimiento['movimiento'] == 5 || $dMovimientoMantenimiento['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoMantenimiento['movimientos.rol'].".<br>";







if($dMovimientoMantenimiento['horario'] != ""  && $dMovimientoMantenimiento['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoMantenimiento['horario'].".<br>";







if($dMovimientoMantenimiento['premio'] != ""  && $dMovimientoMantenimiento['premio'] != 0 ) echo "Lo que proceda con premio.<br>";







if($dMovimientoMantenimiento['puntualidad'] != ""  && $dMovimientoMantenimiento['puntualidad'] != 0 ) echo "Afecta puntualidad.<br>";







if($dMovimientoMantenimiento['productividad'] != ""  && $dMovimientoMantenimiento['productividad'] != 0 ) echo "Afecta productividad.<br>"; 







if($dMovimientoMantenimiento['no_premio'] != ""  && $dMovimientoMantenimiento['no_premio'] != 0 ) echo "No afecta productividad.<br>"; 







if($dMovimientoMantenimiento['id_operador2'] != "" && $dMovimientoMantenimiento['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoMantenimiento['motivo'] ?><br></td></tr></table></td>              </tr>                <? } ?>
                            <? }  if($numMantenimientoB > 0  && $_REQUEST['manttob'] == 1  ){?>        
  <tr>                
<td colspan="5" align="center"><br><br><br><h3>MANTENIMIENTO B</h3></td>                </tr> 



<tr>               
  <td width="8%" align="center"><h3>Fecha</h3></td>               
  <td width="9%" align="center"><h3>Nomina</h3></td>               
  <td width="27%" align="center"><h3>Nombre</h3></td>               
  <td width="15%" align="center"><h3>Movimiento</h3></td>               
  <td width="41%" align="center"><h3>Caracteristicas</h3></td>              </tr>                                                <? for($a = 0; $dMovimientoMantenimientoB = mysql_fetch_assoc($rMovimientoMantenimientoB) ;$a++){ 




$qE2=
"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoMantenimientoB['id_operador2']."";




$rE2
=
mysql_query($qE2);




$dE2
=
mysql_fetch_assoc($rE2);







?> 



<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#DDDDDD'"; else echo "";?>>                
<td align="center" class="style5"><?=fecha($dMovimientoMantenimientoB['fecha'])?></td>               
  <td align="center" class="style5"><?=fecha($dMovimientoMantenimientoB['numnomina'])?></td>               
  <td align="left" class="style5"><?=$dMovimientoMantenimientoB['nombre']?></td>               
  <td align="left" class="style5"><?=$motivos[$dMovimientoMantenimientoB['movimiento']]?></td>               
  <td  align="justify" width="41%"  class="style5"  style="text-align:justify">                  <table width="95%" style="background-color:transparent;"><tr style="background-color:transparent;"><td align="justify" class="style5"  style="text-align:justify">                  <? 


if($dMovimientoMantenimientoB['nuevo_movimiento'] != "" ) echo "Otro movimiento:" .$dMovimientoMantenimientoB['nuevo_movimiento'].".<br>";







if($dMovimientoMantenimientoB['dia'] != "" && $dMovimientoMantenimientoB['dia'] != "0000-00-00" && $dMovimientoMantenimientoB['movimiento'] != 9){ echo "Fecha a cubrir: "; fecha($dMovimientoMantenimientoB['dia']); echo ".<br>"; }







if($dMovimientoMantenimientoB['horas'] != ""  && $dMovimientoMantenimientoB['horas'] != 0) echo "Tiempo: ".$dMovimientoMantenimientoB['horas'].".<br>";







if($dMovimientoMantenimientoB['cantidad'] != "" && $dMovimientoMantenimientoB['cantidad'] != 0 ) echo "Cantidad: $".$dMovimientoMantenimientoB['cantidad'].".<br>";







if($dMovimientoMantenimientoB['desde'] != "" && $dMovimientoMantenimientoB['desde'] != "0000-00-00" && ($dMovimientoMantenimientoB['movimiento'] == 9 || $dMovimientoMantenimientoB['movimiento'] == 20 )){ echo "Del: "; fecha($dMovimientoMantenimientoB['desde']); echo " al ";}







if($dMovimientoMantenimientoB['hasta'] != "" && $dMovimientoMantenimientoB['hasta'] != "0000-00-00" && ($dMovimientoMantenimientoB['movimiento'] == 9 || $dMovimientoMantenimientoB['movimiento'] == 20 ) ){ fecha($dMovimientoMantenimientoB['hasta']); echo ".<br>";}







if($dMovimientoMantenimientoB['desde_tiempo'] != "" && $dMovimientoMantenimientoB['desde_tiempo'] != "00:00" && ($dMovimientoMantenimientoB['movimiento'] == 23 || $dMovimientoMantenimientoB['movimiento'] == 20 )){ echo "De la hora :"; $dMovimientoMantenimientoB['desde_tiempo']; echo " al ";}







if($dMovimientoMantenimientoB['hasta_tiempo'] != "" && $dMovimientoMantenimientoB['hasta_tiempo'] != "00:00" && ($dMovimientoMantenimientoB['movimiento'] == 23 || $dMovimientoMantenimientoB['movimiento'] == 20 )){ echo "hasta la hora :"; $dMovimientoMantenimientoB['hasta_tiempo']; echo ".<br>";}







if($dMovimientoMantenimientoB['turno'] != "" && $dMovimientoMantenimientoB['turno'] != 0 && ($dMovimientoMantenimientoB['movimiento'] == 4 || $dMovimientoMantenimientoB['movimiento'] == 20 )  ) echo "Cambio al turno: ".
$dMovimientoMantenimientoB['turno']. ".<br>";







if($dMovimientoMantenimientoB['movimientos.rol'] != ""  &&  $dMovimientoMantenimientoB['movimientos.rol'] != 0  && ($dMovimientoMantenimientoB['movimiento'] == 5 || $dMovimientoMantenimientoB['movimiento'] == 20 )) echo "Cambio al Rol: ".$dMovimientoMantenimientoB['movimientos.rol'].".<br>";







if($dMovimientoMantenimientoB['horario'] != ""  && $dMovimientoMantenimientoB['horario'] != 0 ) echo "Cambio de horario: ".$dMovimientoMantenimientoB['horario'].".<br>";







if($dMovimientoMantenimientoB['premio'] != ""  && $dMovimientoMantenimientoB['premio'] != 0 ) echo "Lo que proceda con premios.<br>";







if($dMovimientoMantenimientoB['puntualidad'] != ""  && $dMovimientoMantenimientoB['puntualidad'] != 0 ) echo "Afecta puntualidad : SI.<br>"; 







if($dMovimientoMantenimientoB['productividad'] != ""  && $dMovimientoMantenimientoB['productividad'] != 0 ) echo "Afecta productividad <br>"; 







if($dMovimientoMantenimientoB['no_premio'] != ""  && $dMovimientoMantenimientoB['no_premio'] != 0 ) echo "No afecta productividad <br>"; 







if($dMovimientoMantenimientoB['id_operador2'] != "" && $dMovimientoMantenimientoB['id_operador2'] != 0 ) echo "Se arreglo con: <span style='color:#FF0000'>" .$dE2['numnomina']." - " .$dE2['nombre'].".</span><br>";                  ?>                  <label>Motivo:</label><?=$dMovimientoMantenimientoB['motivo'] ?><br></td></tr></table></td>              </tr>                <? }   ?><? }  } if($numExtruder < 1 || $numImpresion < 1 || $numBolseo < 1 || $numEmp < 1 || $numEmpB < 1 || $numMantenimiento < 1 || $numMantenimientoB < 1){ ?>


<tr>            
<td colspan="5" class="style4" align="center"><br><br><br>
</td>            </tr>                                 <? } ?>    


<tr>            
<td colspan="5"><br></td>            </tr>            <tr><td colspan="5" align="center"><input type="button" name="pfd" value="Formato PDF" class="button1" onClick="genera()"></td></tr>           </table></form><?}?><? if($_REQUEST['tipo'] == 14 || $_REQUEST['tipo'] == 15 ||  $_REQUEST['tipo'] == 16  ){  $tipo = $_REQUEST['tipo'];if($tipo == 14) $area = 1;if($tipo == 15) $area = 2;if($tipo == 16) $area = 4;$fecha
=
fecha_tablaInv($_REQUEST['fecha_incidencia']);$fechaFin
=
fecha_tablaInv($_REQUEST['fecha_incidencia_f']);if($area == 1){echo  $qReportes
=
"SELECT id_orden_produccion, turno, id_supervisor, orden_produccion.observaciones, fecha FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".




" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."' ORDER BY fecha, turno ASC";}if($area == 2){ $qReportes
=
"SELECT id_impresion, turno, id_supervisor, impresion.observaciones FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".




" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."' ORDER BY turno ASC";}if($area == 4){echo  $qReportes
=
"SELECT id_bolseo, turno, id_supervisor, observaciones FROM bolseo ".




" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."' ORDER BY turno ASC";}$rReportes
=
mysql_query($qReportes);

?><script type="text/javascript" language="javascript">function genera(){document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&fecha_incidencia=<?=$_REQUEST['fecha_incidencia']?>";document.form.submit();document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";}</script><div align="center">   
 
<div class="tablaCentrada" align="center" >        <form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">   <table border="0" width="100%" align="center" cellpadding="1" cellspacing="1">   



<tr>                    <td colspan="2" align="center" width="100%"><? if($_REQUEST['tipo'] == 14) echo " EXTRUDER"; else if($_REQUEST['tipo'] == 15) echo " IMPRESION"; else if($_REQUEST['tipo'] == 16) echo " BOLSEO"; ?></td> 



 </tr>                 <tr>               
   <td align="left" width="11%"><h3>Fecha : </h3></td><td width="79%"><?=$_REQUEST['fecha_incidencia']?></td>                </tr>   </table>                  
<? for($b = 1 ; $dReportes
=
mysql_fetch_row($rReportes); $b++){





if($b == 1) $turno = "MATUTINO";






if($b == 2) $turno = "VESPERTINO";






if($b == 3) $turno = "NOCTURNO";





 ?>


            <table border="0" width="100%" align="center" cellpadding="1" cellspacing="1">  



<tr>                
<td colspan="7" align="center" class="style7">REPORTE TURNO <?=$turno?></td>                </tr>                <tr>                
<td align="right"><h3>Supervisor:</h3></td>                    <td colspan="7" class="style7">




<?   $qSuper
=
"SELECT nombre FROM supervisor WHERE id_supervisor = ".$dReportes[2]."";










$rSuper
=
mysql_query($qSuper);










$dSuper =
mysql_fetch_row($rSuper);










echo $dSuper[0];





?></td>                </tr>                <tr>



  <td width="15%" align="center"><h3>Maquina</h3></td>



  <td width="12%" align="center"><h3>Fallo Elec.</h3></td>



  <td width="12%" align="center"><h3>Falta Pers.</h3></td>



  <td width="8%" align="center"><h3>Mantto.</h3></td>



  <td width="8%" align="center"><h3>Otras</h3></td>



  <? if($area == 1 || $area ==2) { ?><td width="10%" align="center"><h3><? if($area == 1) echo "Mallas"; else if ($area == 2 ) echo "C. Impr.";?></h3></td><? } ?>



  <td width="35%" align="center"><h3>Observaciones</h3></td>              </tr>                





<?
$qTiempos
=
"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina WHERE tipo = $area AND id_produccion = ".$dReportes[0]." ORDER BY maquina.numero ASC";                            $rTiempos
=
mysql_query($qTiempos);                                                            for($a = 0; $dTiempo
=
mysql_fetch_assoc($rTiempos); $a++){







if($dTiempo['fallo_electrico'] 
== '00:00:00') 
$fallo 


= ""; else $fallo 


= $dTiempo['fallo_electrico'];







if($dTiempo['mantenimiento'] 
== '00:00:00') 
$mantenimiento 
= ""; else $mantenimiento 
= $dTiempo['mantenimiento'];







if($dTiempo['falta_personal'] 
== '00:00:00') 
$falta_personal = ""; else $falta_personal 
= $dTiempo['falta_personal'];







if($dTiempo['otras'] 


== '00:00:00') 
$otras 


= ""; else $otras 


= $dTiempo['otras'];







if($area == 1) $opcion = "mallas";







if($area == 2) $opcion = "cambio_impresion";







if($dTiempo[$opcion]

 
== '0' || $dTiempo[$opcion] == '') 

$mallas

 
= ""; else if($dTiempo[$opcion] != '0')  $mallas


= "SI";















if($dTiempo['observaciones']
== '0') 

$observaciones
= ""; else $observaciones
=
$dTiempo['observaciones'];















if($fallo == "" && $mantenimiento == "" && $falta_personal == "" && $otras == "" && $mallas == "" && $observaciones == ""){







$a = $a + 1;







}else {







?>                                 
                                <tr <? if(bcmod($a,2)==0) echo  ''; else echo 'bgcolor="#DDDDDD"';  ?> >                                  <td align="left" class="style7"><?=$dTiempo['marca'].' - '.$dTiempo['numero'];?></td>                                  <td align="center"><?=$fallo?></td>                                  <td align="center"><?=$falta_personal?></td>                                  <td align="center"><?=$mantenimiento?></td>                                  <td align="center"><?=$otras?></td>                                  <? if($area == 1 || $area ==2) { ?><td align="center"><?=$mallas?></td><? } ?>                                  <td class="style5"><?=$observaciones?></td>                                </tr>                                <? } 






}?>                                                




<? if($area == 2){ ?>                   <tr>                   
<td colspan="8" align="left" ><br><label>Lineas de impresion</label><br></td>                   </tr>                     




<?









$qTiempos1
=
"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina WHERE tipo = 3 AND id_produccion = ".$dReportes[0]." ORDER BY maquina.numero ASC";                            $rTiempos1
=
mysql_query($qTiempos1);                                                            for($a1 = 0; $dTiempo1
=
mysql_fetch_assoc($rTiempos1); $a1++){







if($dTiempo1['fallo_electrico'] 
== '00:00:00') 
$fallo1 


= ""; else $fallo1 


= $dTiempo1['fallo_electrico'];







if($dTiempo1['mantenimiento'] 
== '00:00:00') 
$mantenimiento1 
= ""; else $mantenimiento1 
= $dTiempo1['mantenimiento'];







if($dTiempo1['falta_personal'] 
== '00:00:00') 
$falta_personal1 = ""; else $falta_personal1
= $dTiempo1['falta_personal'];







if($dTiempo1['otras'] 


== '00:00:00') 
$otras1 


= ""; else $otras1 


= $dTiempo1['otras'];







if($dTiempo1['cambio_impresion']
== '0' || $dTiempo1['cambio_impresion']
== '') 

$mallas1

 
= ""; else $mallas1


= "SI";







if($dTiempo1['observaciones']
== '0') 

$observaciones1
= ""; else $observaciones1
=
$dTiempo1['observaciones'];















if($fallo1 == "" && $mantenimiento1 == "" && $falta_personal1 == "" && $otras1 == "" && $mallas1 == "" && $observaciones1 == ""){







$a1 = $a1 + 1;







}else {







?>                                                                 <tr <? if(bcmod($a1,2)==0) echo  ''; else echo 'bgcolor="#DDDDDD"';  ?> >                                  <td align="left" class="style7"><?=$dTiempo1['marca'].' - '.$dTiempo1['numero'];?></td>                                  <td align="center"><?=$fallo1?></td>                                  <td align="center"><?=$falta_personal?></td>                                  <td align="center"><?=$mantenimiento1?></td>                                  <td align="center"><?=$otras1?></td>                                  <td align="center"><?=$mallas1?></td>                                  <td class="style5"><?=$observaciones1?></td>                                </tr>                                <? } 






}





}?>                                                <tr>                   

<td colspan="2"  align="right"><h3>Observaciones Generales:</h3></td>                    
<td colspan="7" class="style5" align="justify"><?=$dReportes[3]?></td>                  </tr>                                            <tr>                
<td colspan="7" align="center"><br><br><bR></td>                </tr>                                            </table>
            <? } ?>                </form>            <table width="100%" align="center">                                <tr><td colspan="5" align="center"><input type="button" name="pfd" value="Formato PDF" class="button1" onClick="genera()"></td></tr>


</table>        </div></div><? } ?><? if($_REQUEST['tipo'] == 43){ $meses
=
$_REQUEST['mes_t_des'];$anio
=
$_REQUEST['ano_t_des'];
/////////////////////// PRODUCCION  DIARIA /////////////////////////
$qExtruder="SELECT SUM(total) as produccion, ".



" COUNT(turno) AS turnos , fecha, ".



" SUM(desperdicio_tira) as desperdicio1, ".



" SUM(desperdicio_duro) as desperdicio2 FROM entrada_general ".



" INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".



" WHERE MONTH(fecha) = '$meses' AND YEAR(fecha) = '$anio' ".



" GROUP BY fecha ".



" ORDER BY fecha ASC ";$rExtruder
=
mysql_query($qExtruder);for($t = 0; $dExtruder = mysql_fetch_assoc($rExtruder);$t++){

$fecha[$t]

=
$dExtruder['fecha'];

$produccion[$t]
=
$dExtruder['produccion'];

$diferencia[$t]
=
($produccion[$t] - $dMetas['total_dia']);



///////TOTALES/////





$turnos_total
+=
$dExtruder['turnos'];

$dias


=
$turnos_total/3;



$total_dif


+=
$diferencia[$t];

$total_pro


+=
$produccion[$t];

$total_meta


+= $dMetas['total_dia'];

$duro



+= $dExtruder['desperdicio2'];

$tira



+= $dExtruder['desperdicio1'];}for($t = 0; $dExtruderM = mysql_fetch_assoc($rExtruderM);$t++){

$produccionM[$t]
=
$dExtruderM['produccion'];

$numero[$t]


=
$dExtruderM['numero'];

$diferenciaM[$t]
=
( $metaMaquina[$t] - $produccionM[$t]);



///////TOTALES/////



$total_difM


+=
$diferenciaM[$t];

$total_proM


+=
$produccionM[$t];

$total_metaM

+= 
$metaMaquina[$t];}$total_desp
=
$duro + $tira;$por

=
($total_desp/$total_proM)*100;
$Total_produccion
=
$total_pro/$dias;?><script type="text/javascript" language="javascript">function genera(){document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>";document.form.submit();document.form.action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>";}</script><div align="center" id="tablaimpr">
<div class="tablaCentrada" align="center" id="tabla_reporte">    <br><br><br><p align="center" class="titulos_reportes">Desperdicios Total Turno <?=$mes[$meMeta]?> DEL <?=$anho?> </p><br><br><br><br><form name="form" action="<?=$_SERVER['PHP_SELF']?>&seccion=<?=$_REQUEST['seccion']?>" method="post">        <table width="80%" align="center" >            <tr>                <td width="18%" align="center" valign="middle" ><h3 style="height:25px;">Fecha</h3></td>                <td width="30%" height="60px" align="center"><h3 style="height:25px;">Prod. Diaria</h3></td>                <td width="29%" height="60px" align="center"><h3 style="height:25px;">Meta Diaria</h3></td>                <td width="23%" height="60px" align="center"><h3 style="height:25px;">Dif.</h3></td>            </tr>            <? for($t = 0; $t < sizeof($produccion);$t++){?>            <tr <?=cebra($t)?>>                <td class="style5" align="center"><?=fecha($fecha[$t])?></td>                <td class="style5" align="right"><?=$produccion[$t]?></td>                <td class="style5" align="right"><?=$dMetas['total_dia'];?></td>                <td class="style5" align="right"><?=$diferencia[$t]?></td>            </tr>             <? } ?>            <tr style="background-color:#EEEEEE">                <td class="style5" align="center"><h3>SUMAS</h3></td>                <td class="style4" align="right"><?=number_format($total_pro)?></td>                <td class="style4" align="right"><?=number_format($total_meta)?></td>                <td class="style4" align="right"><?=number_format($total_dif)?></td>            </tr>             <tr>            
<td></td>            </tr>         </table></form></div></div><? } ?> <?  if($_REQUEST['tipo'] == 44 ){ ?><style type="text/css"></style><script language="javascript" src="js/isiAJAX.js"></script><? if($_REQUEST['formaR'] == 1) $opc = 'tabla=detalle_resumen_maquina_ex&tipo=id_detalle_resumen_maquina_ex';   if($_REQUEST['formaR'] == 2) $opc = 'tabla=detalle_resumen_maquina_im&tipo=id_detalle_resumen_maquina_im';?><script language="javascript">var last;function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;}function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)

myajax.Link('actualiza.php?valor='+valor+'&campo='+campo+'&id='+id+'&<?=$opc?>');}function genera(){document.form.action="reportes_pdf.php?tipo=<?=$_REQUEST['tipo']?>&orden=<?=$_REQUEST['orden']?>&modelo=<?=$_REQUEST['modelo'];?>&desdeOrd=<?=$_REQUEST['desdeOrd'];?>&hastaOrd=<?=$_REQUEST['hastaOrd'];?>";document.form.submit();}</script><!-- <body onLoad="myajax = new isiAJAX();"> --> <? 
$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desdeOrd_ext']);
$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hastaOrd_ext']);
($_REQUEST['formaR'] == 1)?$titulacho
=
"EXTRUDER":"";
($_REQUEST['formaR'] == 2)?$titulacho
=
"IMPRESION":"";
($_REQUEST['formaR'] == 3)?$titulacho
=
"COMPARATIVO EXTRUDER E IMPRESION":"";


if( ($_REQUEST['modeloExt'] == 2 && $_REQUEST['ordenExt'] != "" && $_REQUEST['formaR'] == 1) ||  ($_REQUEST['modeloExt'] == 2 && $_REQUEST['ordenExt'] != "" && $_REQUEST['formaR'] == 3) ){ 
$qOrdenes
=
" SELECT SUM(detalle_resumen_maquina_ex.kilogramos) AS kilogramos, entrada_general.fecha, detalle_resumen_maquina_ex.orden_trabajo, entrada_general.turno, detalle_resumen_maquina_ex.id_detalle_resumen_maquina_ex, maquina.numero FROM orden_produccion ".




" INNER JOIN resumen_maquina_ex ON orden_produccion.id_orden_produccion = resumen_maquina_ex.id_orden_produccion".




" INNER JOIN entrada_general ON orden_produccion.id_entrada_general = entrada_general.id_entrada_general ".




" LEFT JOIN maquina ON resumen_maquina_ex.id_maquina = maquina.id_maquina".




" LEFT JOIN detalle_resumen_maquina_ex ON resumen_maquina_ex.id_resumen_maquina_ex = detalle_resumen_maquina_ex.id_resumen_maquina_ex".




" WHERE impresion = 0 AND entrada_general.fecha BETWEEN '".$desde."' AND '".$hasta."' AND detalle_resumen_maquina_ex.orden_trabajo LIKE '%".$_REQUEST['ordenExt']."%'  ".




" GROUP BY fecha,turno ".




" ORDER BY entrada_general.fecha, entrada_general.turno, maquina.id_maquina, detalle_resumen_maquina_ex.orden_trabajo ASC "; 
if($_REQUEST['formaR'] == 3)
{

$width
=
'250';

$align
=
'left';
}
else
{

$width
=
'500';

$align
=
'center';
}

$rOrdenes
= 
mysql_query($qOrdenes);


}





if( ($_REQUEST['modeloExt'] == 2 && $_REQUEST['ordenExt'] != "" && $_REQUEST['formaR'] == 2) ||  ($_REQUEST['modeloExt'] == 2 && $_REQUEST['ordenExt'] != "" && $_REQUEST['formaR'] == 3) ){ 
$qOrdenesI
=
" SELECT SUM(detalle_resumen_maquina_im.kilogramos) AS kilogramos, entrada_general.fecha, detalle_resumen_maquina_im.orden_trabajo, entrada_general.turno, detalle_resumen_maquina_im.id_detalle_resumen_maquina_im, maquina.numero FROM impresion ".




" INNER JOIN resumen_maquina_im ON impresion.id_impresion = resumen_maquina_im.id_impresion".




" INNER JOIN entrada_general ON impresion.id_entrada_general = entrada_general.id_entrada_general ".




" LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina".




" LEFT JOIN detalle_resumen_maquina_im ON resumen_maquina_im.id_resumen_maquina_im = detalle_resumen_maquina_im.id_resumen_maquina_im".




" WHERE impresion = 1 AND entrada_general.fecha BETWEEN '".$desde."' AND '".$hasta."' AND detalle_resumen_maquina_im.orden_trabajo LIKE '%".$_REQUEST['ordenExt']."%'  GROUP BY fecha,turno ORDER BY entrada_general.fecha, entrada_general.turno, maquina.id_maquina, detalle_resumen_maquina_im.orden_trabajo ASC ";

if($_REQUEST['formaR'] == 3)

{


$width1
=
'250';


$align1
=
'right';

}

else

{


$width1
=
'500';


$align1
=
'center';

}
$rOrdenesI
= 
mysql_query($qOrdenesI);

}

$revisa 
= explode("-", $desde);

$ano = $revisa[0];

$mes1 = $revisa[1];

$dia = $revisa[2];




$revisa2 
= explode("-", $hasta);

$ano2 = $revisa2[0];

$mes2 = $revisa2[1];

$dia2 = $revisa2[2];
$fechas
=
"";
  
if($mes1 == $mes2)
{   

$fechas
.= "Del dia ".$dia." al ".$dia2." de ".$mes[intval($mes2)]." de ".$ano2;  
} 
if($mes1 != $mes2 && $mes2 != 1)
{         $fechas
.= "Del dia ".$dia." de ".$mes[intval($mes1)]." al ".$dia2." de ".$mes[intval($mes2)]." de ".$ano2;    }     if($mes1 != $mes2 && $mes2 == 1)
{         $fechas
.= "Del dia ".$dia." de ".$mes[intval($mes1)]." de ".$ano."<br /> al ".$dia2." de ".$mes[intval($mes2)]." de ".$ano2;    } 



?><div align="center" id="tablaimpr">
<div class="tablaCentrada" id="tabla_reporte">    <p align="center" class="titulos_reportes">ORDENES TOTALIZADAS EN EXTRUDER Y/O IMPRESI&oacute;N<br></p>
<form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<table width="700" align="center">
<tr>

<td align="center"><h3><b><?=$fechas?></b></h3></td>
</tr>
<tr>

<td width="100%" align="center"><? if( $_REQUEST['formaR'] == 3 ||  $_REQUEST['formaR'] == 1 ){ ?> 

<table width="<?=$width?>" class="titulos_e" align="<?=$align?>">

<tr>


<td align="center" colspan="5"><h3>Kilogramos en Extruder</h3></td>

</tr>

<tr>


<td width="14%" align="center"><strong>Fecha</strong></td>


<td width="14%" align="center"><strong>Turno</strong></td>


<td width="15%" align="center"><strong>Maquina</strong></td>


<td width="20%" align="center"><strong>No_Orden</strong></td>


<td width="18%" align="center"><strong>Total_Kg</strong></td>

</tr>                            <? 







for($z = 0; $dOrdenes
=
mysql_fetch_assoc($rOrdenes);$z++){ 








$id_ma = $dOrdenes['id_detalle_resumen_maquina_ex'];                                 
if($dOrdenes['orden_trabajo'] ==  ""){ 









$z = $z+1; 








} else { ?>

<tr <? 







if($_REQUEST['ordenExt'] == ""  && $_REQUEST['modeloExt'] != 1 ) 








$color = $dOrdenes['dia'];







else if($_REQUEST['ordenExt'] == ""  && $_REQUEST['modeloExt'] == 1 )








$color = $z;







else









$color = $z;














if(bcmod(intval($color),2) == 0){ 







$back 
= "#DDDDDD";  







$frente = "#B4C3EC";







echo "bgcolor='".$back."'";







}  else { 







$back 
= "#FFFFFF";  







$frente = "#B4C3EC";







echo "bgcolor='".$back."'";















}?> onMouseOver="this.bgColor='<?=$frente?>'" onMouseOut="this.bgColor='<?=$back?>'">                           
  <? if($_REQUEST['modeloExt'] == 2 ){ ?>


<td align="center" class="style7"><b><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dOrdenes['fecha'])?></b></td>                               <? } if( $_REQUEST['ordenExt'] != "" ){ ?>


<td align="center" class="style7"><b><?=$dOrdenes['turno'];?></b></td>


<td align="center" class="style7"><b><?=$dOrdenes['numero'];?></b></td>                              <? } ?>


<td align="center" class="style7">                              <? if($concentrado_ot_mod){?>






  
<input size="8" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'orden_trabajo', <?=$id_ma;?>)" class="inputoff" id="a<?=$id_ma;?>" value="<?=$dOrdenes['orden_trabajo']?>" /></td>   






  <? } if(!$concentrado_ot_mod) {?>                              

<?=$dOrdenes['orden_trabajo']?>                              <? } ?>


<td align="center" class="style7">                             <? if($concentrado_ot_mod){?>






  <input size="8" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'kilogramos', <?=$id_ma;?>)" class="inputoff" id="b<?=$id_ma;?>" value="<?=floor($dOrdenes['kilogramos']);?>" />






  <? } else {  ?><?=floor($dOrdenes['kilogramos']);?><? } ?>


</td>

</tr>                            <? 






$TotalMillares
+=  $dOrdenes['millares'];






$TotalKilos

+=
$dOrdenes['kilogramos'];













}  






}  if(($_REQUEST['modeloExt'] == 2 && $_REQUEST['ordenExt'] != "") || ($_REQUEST['modeloExt'] == 1 && $_REQUEST['ordenExt'] == "" )){













if($_REQUEST['modeloExt'] == 2 && $_REQUEST['ordenExt'] != "" ) $g = 4;






if($_REQUEST['modeloExt'] == 1 && $_REQUEST['ordenExt'] == "" ) $g = 1;













?>

<tr>


<td colspan="<?=$g?>" align="right">TOTAL:</td>


<td align="right" class="style5"><b><?=floor($TotalKilos);?></b></td>

</tr>


<? } ?>

</table><?  } ?><? } if( $_REQUEST['formaR'] == 2 ||  $_REQUEST['formaR'] == 3){?>        <table width="<?=$width1?>"  align="<?=$align1?>" class="titulos_i">        <tr>            <td colspan="5" align="center"><h3>Kilogramos en Impresi&Oacute;n</h3></td>        </tr>        <tr>


<td width="14%" align="center"><strong>Fecha</strong></td>


<td width="14%" align="center"><strong>Turno</strong></td>                            <td width="15%" align="center"><strong>Maquina</strong></td>                            <td width="20%" align="center"><strong>No_Orden</strong></td>                            <td width="18%" align="center"><strong>Total_Kg</strong></td>                      </tr>                            <? 







for($z = 0; $dOrdenesI
=
mysql_fetch_assoc($rOrdenesI);$z++){ 







$id_ma = $dOrdenesI['id_detalle_resumen_maquina_im'];                                 if($dOrdenesI['orden_trabajo'] ==  ""){ $z = $z+1; } else { ?>                            <tr <? 







if($_REQUEST['ordenExt'] == ""  && $_REQUEST['modeloExt'] != 1 ) 







$color = $dOrdenesI['dia'];







else if($_REQUEST['ordenExt'] == ""  && $_REQUEST['modeloExt'] == 1 )







$color = $z;






else








$color = $z;














if(bcmod(intval($color),2) == 0){ 







$back 
= "#DDDDDD";  







$frente = "#B4C3EC";







echo "bgcolor='".$back."'";







}  else { 







$back 
= "#FFFFFF";  







$frente = "#B4C3EC";







echo "bgcolor='".$back."'";















}?> onMouseOver="this.bgColor='<?=$frente?>'" onMouseOut="this.bgColor='<?=$back?>'">                           
  <? if($_REQUEST['modeloExt'] == 2 ){ ?>                              <td align="center" class="style7"><b><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dOrdenesI['fecha'])?></b></td>                               <? if( $_REQUEST['ordenExt'] != "" ){ ?>                              <td align="center" class="style7"><b><?=$dOrdenesI['turno'];?></b></td>                              <td align="center" class="style7"><b><?=$dOrdenesI['numero'];?></b></td>                              <? }
 ?>                           
  <td align="center" class="style7">                              <? if($concentrado_ot_mod){?>






  <input size="8" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'orden_trabajo', <?=$id_ma;?>)" class="inputoff" id="a<?=$id_ma;?>" value="<?=$dOrdenesI['orden_trabajo']?>" />  






  <? } if(!$concentrado_ot_mod) {?>                              
<?=$dOrdenesI['orden_trabajo']?>                              <? } ?>






  <? } else { ?>                           
  <?=$dOrdenesI['orden_trabajo']?>                   






  <? }  ?> </td>                               <td align="right" class="style5"><b>                              <? if($concentrado_ot_mod){?>






  <input size="8" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'kilogramos', <?=$id_ma;?>)" class="inputoff" id="b<?=$id_ma;?>" value="<?=floor($dOrdenesI['kilogramos']);?>" />






  <? } else {  ?><?=floor($dOrdenesI['kilogramos']);?><? } ?>






  </b></td>                          </tr>                            <? 






$TotalKilosI

+=
$dOrdenesI['kilogramos'];













}  






}  if(($_REQUEST['modeloExt'] == 2 && $_REQUEST['ordenExt'] != "") || ($_REQUEST['modeloExt'] == 1 && $_REQUEST['ordenExt'] == "" )){













if($_REQUEST['modeloExt'] == 2 && $_REQUEST['ordenExt'] != "" ) $g = 4;






if($_REQUEST['modeloExt'] == 1 && $_REQUEST['ordenExt'] == "" ) $g = 1;













?>                            <tr>                           
  <td colspan="<?=$g?>" align="right">TOTAL:</td>                                <td align="right" class="style5"><b><?=floor($TotalKilosI);?></b></td>                            </tr>


</table> <? } ?>        

</td>    </tr>
</table>     </form>   
</div></div> <? } ?>   <?  if($_REQUEST['tipo'] == 45 ){ if($_REQUEST['area_maq'] == 1){
$qMq
=
" SELECT  SUM(subtotal) AS total, resumen_maquina_ex.id_maquina AS id_maquina, maquina.numero, fecha, maquina.area   FROM resumen_maquina_ex "
.



" INNER JOIN orden_produccion 
ON resumen_maquina_ex.id_orden_produccion 
= orden_produccion.id_orden_produccion ".



" INNER JOIN entrada_general 
ON entrada_general.id_entrada_general 

= orden_produccion.id_entrada_general ".



" INNER JOIN maquina 
ON resumen_maquina_ex.id_maquina 

= maquina.id_maquina ".



" WHERE ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." ) AND  impresion = 0  AND resumen_maquina_ex.id_maquina = ".$_REQUEST['maq_id']." GROUP BY  DAY(fecha)".



" ORDER BY fecha ASC";
/*echo
$qMqP
=
" SELECT * FROM resumen_maquina_ex "
.



" INNER JOIN orden_produccion 
ON resumen_maquina_ex.id_orden_produccion 
= orden_produccion.id_orden_produccion ".



" INNER JOIN entrada_general 
ON entrada_general.id_entrada_general 

= orden_produccion.id_entrada_general ".



" INNER JOIN maquina 
ON resumen_maquina_ex.id_maquina 

= maquina.id_maquina ".



" WHERE subtotal !=0 AND ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." ) AND  impresion = 0  AND resumen_maquina_ex.id_maquina = ".$_REQUEST['maq_id']." GROUP BY  fecha".



" "; */    $qMqP
= " SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_ex.id_maquina, rol,  maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_ex "
.


" INNER JOIN orden_produccion 
ON resumen_maquina_ex.id_orden_produccion 
= orden_produccion.id_orden_produccion ".


" INNER JOIN entrada_general 
ON entrada_general.id_entrada_general 

= orden_produccion.id_entrada_general ".


" INNER JOIN maquina 


ON resumen_maquina_ex.id_maquina 


= maquina.id_maquina ".


" WHERE subtotal != 0 AND maquina.id_maquina = ".$_REQUEST['maq_id']." AND YEAR(fecha) = '".$_REQUEST['ano_maq']."' AND MONTH(fecha) = '".$_REQUEST['mes_maq']."' AND  impresion = 0 GROUP BY  maquina.numero".


" ORDER BY maquina.numero ASC ";












$qAM
=
"SELECT area, numero FROM maquina WHERE id_maquina
=
".$_REQUEST['maq_id']."";
$rAM
=
mysql_query($qAM);
$dAM
=
mysql_fetch_assoc($rAM);




$qMetas
=
" SELECT * FROM meta".



" LEFT JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".



" WHERE id_maquina = ".$_REQUEST['maq_id']." AND ( MONTH(mes) = ".$_REQUEST['mes_maq']."  AND ano = ".$_REQUEST['ano_maq']." )";$query
=
" FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general ".


" LEFT JOIN tiempos_muertos ON orden_produccion.id_orden_produccion = tiempos_muertos.id_produccion ";}









if($_REQUEST['area_maq'] == 2){
$qMq
=
" SELECT  SUM(subtotal) AS total, resumen_maquina_im.id_maquina AS id_maquina, maquina.numero, fecha, maquina.area  FROM resumen_maquina_im "
.



" INNER JOIN impresion 


ON 
resumen_maquina_im.id_impresion 
= impresion.id_impresion ".



" INNER JOIN entrada_general 
ON 
entrada_general.id_entrada_general 
= impresion.id_entrada_general ".



" INNER JOIN maquina 


ON resumen_maquina_im.id_maquina 

= maquina.id_maquina ".



" WHERE ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." ) AND  impresion = 1  AND resumen_maquina_im.id_maquina = ".$_REQUEST['maq_id']." GROUP BY  DAY(fecha)".



" ORDER BY fecha ASC";

$qMqP
=
" SELECT * FROM resumen_maquina_im "
.



" INNER JOIN impresion 
ON resumen_maquina_im.id_impresion 
= impresion.id_impresion ".



" INNER JOIN entrada_general 
ON entrada_general.id_entrada_general 

= impresion.id_entrada_general ".



" INNER JOIN maquina 
ON resumen_maquina_im.id_maquina 

= maquina.id_maquina ".



" WHERE subtotal !=0 AND ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." ) AND  impresion = 1  AND resumen_maquina_im.id_maquina = ".$_REQUEST['maq_id']." GROUP BY  fecha".



" ";






$qMetas
=
" SELECT * FROM meta".



" LEFT JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".



" WHERE id_maquina = ".$_REQUEST['maq_id']." AND ( MONTH(mes) = ".$_REQUEST['mes_maq']."  AND ano = ".$_REQUEST['ano_maq']." )";
$qAM
=
"SELECT area, numero FROM maquina WHERE id_maquina
=
".$_REQUEST['maq_id']."";
$rAM
=
mysql_query($qAM);
$dAM
=
mysql_fetch_assoc($rAM);



$query
=
" FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".


" LEFT JOIN tiempos_muertos ON impresion.id_impresion = tiempos_muertos.id_produccion ";}if($_REQUEST['area_maq'] == 3){

$qMq
=
" SELECT  SUM(resumen_maquina_bs.kilogramos) AS total, resumen_maquina_bs.id_maquina AS id_maquina, maquina.numero, fecha, maquina.area   FROM resumen_maquina_bs "
.



" INNER JOIN bolseo 
ON resumen_maquina_bs.id_bolseo 
= bolseo.id_bolseo ".



" INNER JOIN maquina 
ON resumen_maquina_bs.id_maquina 

= maquina.id_maquina ".



" WHERE ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." )  AND resumen_maquina_bs.id_maquina = ".$_REQUEST['maq_id']." GROUP BY  DAY(fecha)".



" ORDER BY fecha ASC";


$qMqP
=
" SELECT * FROM resumen_maquina_bs "
.



" INNER JOIN bolseo 
ON resumen_maquina_bs.id_bolseo 
= 
bolseo.id_bolseo ".



" INNER JOIN maquina 
ON resumen_maquina_bs.id_maquina 
= 
maquina.id_maquina ".



" WHERE resumen_maquina_bs.kilogramos !=0 AND ( MONTH(fecha) = ".$_REQUEST['mes_maq']."  AND YEAR(fecha) = ".$_REQUEST['ano_maq']." )  AND resumen_maquina_bs.id_maquina = ".$_REQUEST['maq_id']." GROUP BY  fecha".



" ";






$qMetas
=
" SELECT * FROM meta".



" LEFT JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".



" WHERE meta.area = 3 AND  ( MONTH(mes) = ".$_REQUEST['mes_maq']."  AND ano = ".$_REQUEST['ano_maq']." )";
$qNuM
=
"SELECT * FROM maquina WHERE area = 1";
$rNuM
=
mysql_query($qNuM);

$nNuM
=
mysql_num_rows($rNuM);

$qAM
=
"SELECT area, numero FROM maquina WHERE id_maquina
=
".$_REQUEST['maq_id']."";
$rAM
=
mysql_query($qAM);
$dAM
=
mysql_fetch_assoc($rAM);





$query
=
" FROM bolseo ".



" LEFT JOIN tiempos_muertos ON bolseo.id_bolseo = tiempos_muertos.id_produccion ";} 
$meMeta =
$_REQUEST['mes_maq'];
$anho 
=
$_REQUEST['ano_maq'];
$mesMetacero
=
num_mes_cero($anho.'-'.$meMeta.'-01');
$mesMeta
=
$anho.'-'.$mesMetacero.'-01';
$ultimo_dia = UltimoDia($anho,$meMeta);
$mesFinal
=
$anho.'-'.$mesMetacero.'-'.$ultimo_dia; 
$qTiemposMaq
=
"SELECT TIME(SUM(mantenimiento)) 
AS mantenimiento ,".





" TIME(SUM(falta_personal))

AS falta_personal ,".





" TIME(SUM(fallo_electrico))

AS fallo_electrico ,".





" TIME(SUM(otras)) AS otras ,".





" fecha ,".





" maquina.marca, maquina.numero ".





$query.





" LEFT JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina ".











" WHERE maquina.id_maquina = ".$_REQUEST['maq_id']." AND fecha BETWEEN '".$mesMeta."' AND '".$mesFinal."'  ".





" GROUP BY fecha ".





" ORDER BY fecha ASC";
$rTiemposMaq
=
mysql_query($qTiemposMaq);
for($a=0;$dTiemposMaq
=
mysql_fetch_assoc($rTiemposMaq);$a++){

$manto[$a]
=
$dTiemposMaq['mantenimiento'];

$fp[$a]

=
$dTiemposMaq['falta_personal'];

$fallo[$a]
=
$dTiemposMaq['fallo_electrico']+$dTiemposMaq['otras'];

$Tfallo[$a]

+=
$fallo[$a]/(24/3);

$Tfp[$a]

+=
$fp[$a]/(24/3);

$Tmanto[$a]

+=
$manto[$a]/(24/3);




$TurnosTotal[$a]
=
$Tmanto[$a]
+ $Tfp[$a] + $Tfallo[$a];

}$rMq
=
mysql_query($qMq);$rMqP
=
mysql_query($qMqP);$dMqp
=
mysql_fetch_assoc($rMqP);$rMetas
=
mysql_query($qMetas);$dMetas
=
mysql_fetch_assoc($rMetas);$id_maquinas
=
$dMetas['id_maquina'];?><div align="center">
<div class="tablaCentrada">    
<form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">

  <table width="80%" cellpadding="0" cellspacing="0">              <tr valign="top">                  <div id="xsnazzy">                     <td colspan="5">                            <b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>                                <div class="xboxcontent" style="background-color: #ffffff">                           
  <table width="100%" align="center">                                            <tr valign="top">                                       
  

<td width="52%" align="left"><table align="left" width="99%">                                            <tr>                                              <td height="28" colspan="2" align="center"><h3><?=$mes[$_REQUEST['mes_maq']]?> de <?=$_REQUEST['ano_maq']?></h3></td>                                              <td colspan="2" height="28"><h3>Maq : <?










  if($dAM['area'] == 1) echo "Bol. ";










  if($dAM['area'] == 2) echo "Impr. ";










  if($dAM['area'] == 3) echo "L. de Impr. ";










  if($dAM['area'] == 4) echo "Extr. ";










   echo $dAM['numero']?>                                              </h3></td>                                            </tr>                                            <tr>                                              <td width="98"  align="center"><h3>Fecha</h3></td>                                              <td width="105" align="center"><h3>Produccion</h3></td>                                              <td width="68" align="center"><h3>Meta</h3></td>                                              <td width="94" align="center"><h3>Diferencia</h3></td>                                            </tr>                                            <? $a = 0; $metas_maquinas= 0; while($dMq
=
mysql_fetch_assoc($rMq)){ 












$metas_maquinas
=
$dMetas['diaria'];

























$qMet
=
" SELECT fecha, meta_dia, turno_m, turno_v, turno_n FROM paros_maquinas ".















" WHERE fecha = '".$dMq['fecha']."' AND id_maquina = ".$_REQUEST['maq_id']."";












$rMet
=
mysql_query($qMet);












$dMet
=
mysql_fetch_assoc($rMet);





















if($_REQUEST['area_maq'] == 3)













$metas_maquinas
=
($dMetas['meta_mes_kilo']/$nMqp)/$nNuM;

























($dMq['fecha'] == $dMet['fecha'])? $metas_maquinas = $dMet['meta_dia']:$metas_maquinas = $metas_maquinas;

























$turnos[$a]
=
$dMet['turno_m']+$dMet['turno_v']+$dMet['turno_n']; 












$diferencia
=
$dMq['total']-$metas_maquinas;












?>                                            <tr <? cebra($a)?>>                                                 <td align="center"><? fecha($dMq['fecha'])?></td>                                                 <td align="right" class="style5"><? printf("%.2f" ,$dMq['total'])?></td>                                                 <td align="right" class="style5"><? printf("%.2f" ,$metas_maquinas)?></td>                                                 <td align="right" class="style5"><? printf("%.2f" ,$diferencia)?></td>                                            </tr>                                            <? 
$a++;











$Total_metas
+=
$metas_maquinas;











$total_prod

+=
$dMq['total'];











$total_dif

+=
$diferencia;











} ?>                                            <tr>                                            
<td><h3>Totales: </h3></td>                                                <td align="right"><? printf("%.2f" ,$total_prod)?></td>                                                <td align="right"><? printf("%.2f" ,$Total_metas)?></td>                                              
<td align="right"><? printf("%.2f" ,$total_dif)?></td>                                            </tr>                                            <tr>                                            
<td><h3>Promedio: </h3></td>                                                <td align="right"><? @printf("%.2f" ,$total_prod/$dMqp['paros'])?></td>                                                <td align="right"></td>                                              <td align="right"></td>                                            </tr>                                                                                    </table>                                    
</td>                                    
<td width="1%">&nbsp;</td>               
    
  <td width="47%" align="left" ><table align="left" width="100%"><tr height="25"><td align="center"><table align="left" width="100%">                            <tr>                              <td colspan="4" align="center"><h3>Paros :: Motivos</h3></td>                            </tr>                            <tr>                              <td align="center"><h3>Turnos</h3></td>                              <td align="center"><h3>Mantto </h3></td>                              <td align="center"><h3>Oper. </h3></td>                              <td align="center"><h3>Otros </h3></td>                            </tr>                            <? 











for($a=0;$a<sizeof($turnos);$a++){?>                            <tr <? cebra($a)?>>                              <td align="center" class="style4"><? printf("%.2f" ,$TurnosTotal[$a])
?></td>                              <td align="center" class="style5"><? printf("%.2f" ,$Tmanto[$a]) 

?></td>                              <td align="center" class="style5"><? printf("%.2f" ,$Tfp[$a]) 


?></td>                              <td align="center" class="style5"><? printf("%.2f" ,$Tfallo[$a]) 

?></td>                            </tr>                            <? 


$tTotal+=$TurnosTotal[$a];


$tMantoTotal+=$Tmanto[$a];

$tMantoT+=$Tfp[$a];


$tFallorT+=$Tfallo[$a];

}
 ?>                            <tr height="22">                              <td align="right">Turnos                                <?  @printf("%.2f" ,$tTotal)?>                              </td>                              <td align="center"><? @printf("%.2f" ,$tMantoTotal)?>                              </td>                              <td align="center"><? @printf("%.2f" ,$tMantoT)?>                              </td>                              <td align="center"><? @printf("%.2f" ,$tFallorT)?>                              </td>                            </tr>                            <tr height="25">                              <td align="right">Dias                                
<?  @printf("%.2f" ,$tTotal/3)?>                              </td>                            </tr>                          
</table></td>                                                </tr>               
    
  </table>                                           

</td>                                </tr>                              
</table>

</div>


<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>                    </td>            
</div>           
</tr>   

</table>
</form></div></div><? } ?> <tr>
<td colspan="11" align="center">    <div id="mensajes"></div>    <br><br>      <input type="button" value="Imprimir" class="link button1" />&nbsp;&nbsp;      <input type="button" value="Regresar" class="styleTabla button1" onClick="history.go(-1)" />    </td></tr> <tr>
    <td colspan="11" align="center"><br /><br />        </td>    </tr></div></body>