<style type="text/css">
<!--
.style1 {color: #136699}
-->
</style>
<?
 require "debugger.php";

?>
<div id="container">
		<div id="content">
       <table width="100%"  cellpadding="0" cellspacing="0" align="center" border="0">
       <tr>
       <? if($repesadas){ ?>   
        <td width="28%" valign="top">
			<table align="left" width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                <td colspan="3" valign="top"><h3>REPESADAS PENDIENTES</h3></td>
              </tr>
              <?
							$qEntradas	=	"SELECT * FROM entrada_general INNER JOIN bolseo ON entrada_general.fecha	=	bolseo.fecha".
								" AND entrada_general.turno = bolseo.turno  ".
								" WHERE entrada_general.actualizado=0 AND bolseo.actualizado=0 AND entrada_general.impresion = 0 ".
                "GROUP BY entrada_general.fecha, entrada_general.turno ORDER BY entrada_general.fecha, entrada_general.turno	ASC";							
							pDebug($qEntradas);
              $rEntradas	=	mysql_query($qEntradas);
							
							$i=0;
							
							$nEntradas	=	mysql_num_rows($rEntradas);
							if($nEntradas	> 0 ){
							?>
                <tr>
                	<td style="color:#000000" width="99"><b>Fecha</b></td>
               	  <td width="81" align="center" style="color:#000000"><b>Turno</b></td>
                  <td width="90">&nbsp;</td>
		            </tr>		
              <?php				
				       while($dEntradas	=	mysql_fetch_assoc($rEntradas))
				       {
					      $qRepeImpresion	=	"SELECT * FROM entrada_general WHERE impresion = 1 AND fecha ='".
                       $dEntradas['fecha']."' AND turno = ".$dEntradas['turno']." ";
                pDebug($qRepeImpresion);
					      $rRepeImpresion =	mysql_query($qRepeImpresion);
					      $nRepeImpresion	=	mysql_num_rows($rRepeImpresion);
                pDebug("No.- ". $nRepeImpresion);
					       if($nRepeImpresion > 0){
							    ?>
     				      <tr onMouseOver="this.style.backgroundColor='#CCC'"	onmouseout="this.style.backgroundColor='#EAEAEA'" style="cursor:default; background-color:#EAEAEA">
                  <td width="99"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dEntradas['fecha'])?></td>
                  <td width="81" align="center"><?=$dEntradas['turno']?></td>
                  <td  ><a href="<?=$_SERVER['HOST']?>	?seccion=7&accion=actualizarExtruder&id_extruder=<?=$dEntradas['id_entrada_general']?>&id_bolseo=<?=$dEntradas['id_bolseo']?>&accion=repesar&turno=<?=$dEntradas['turno']?>&fecha=<?=$dEntradas['fecha']?>">Reconteo</a></td>
                  </tr>
                 <?php } else { ?> 
                <tr>
                	<td colspan="3" align="center" class="style4">Falta reporte de Impresi&oacute;n</td>
                 </tr>
                <? } } 
				      } else { ?>
                <tr>
                	<td colspan="3" align="center"><b>NO HAY REPORTES PENDIENTES</b></td>
              </tr>
 				<? } if(isset($_GET['exito'])){?>
                    <li><center><font color="#FF0000">Registro Actualizado.</font></center></li>
                <? } ?>        				
                <br />
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:60px; text-align:center;"><h4 style="color:#006699;"">| REPORTE DE PROMEDIOS |</h4></td>
                </tr>
            	<tr>
					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=43">VER REPORTE</a></td>
                </tr>
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:15px; text-align:center;"><h4 style="color:#006699;"">| TOTAL POR SUPERVISOR |</h4></td>
                </tr>
            	<tr>
					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=44">VER REPORTE</a></td>
                </tr>
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:15px; text-align:center;"><h4 style="color:#006699;"">|&nbsp;&nbsp;&nbsp; PRODUCCION DIARIA  &nbsp;&nbsp;&nbsp;|</h4></td>
                </tr>
            	<tr>
					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=45">VER REPORTE</a></td>
                </tr>
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:15px; text-align:center;"><h4 style="color:#006699;"">|&nbsp;&nbsp; PAROS DE MENSUALES &nbsp;&nbsp;|</h4></td>
                </tr>
            	<tr>
					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=47&area=area3">VER REPORTE</a></td>
                </tr>                
                
            </table>
                    </td>
        
        <td width="2%" valign="top">&nbsp;</td>
		<? }
			else{ 
		?>
        <td width="28%" valign="top">
			<table align="left" width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:13px; text-align:center;"><h3>REPORTES</h3></td>
                </tr>
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:13px; text-align:center;"><h4 style="color:#006699;"">| REPORTE DE PROMEDIOS |</h4></td>
                </tr>
            	<tr>
					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=43">VER REPORTE</a></td>
                </tr>
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:15px; text-align:center;"><h4 style="color:#006699;"">| TOTAL POR SUPERVISOR |</h4></td>
                </tr>
            	<tr>
					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=44">VER REPORTE</a></td>
                </tr>
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:15px; text-align:center;"><h4 style="color:#006699;"">|&nbsp;&nbsp;&nbsp; PRODUCCION DIARIA  &nbsp;&nbsp;&nbsp;|</h4></td>
                </tr>
            	<tr>
					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=45">VER REPORTE</a></td>
                </tr>
            	<tr>
                	<td colspan="3" valign="top" style="padding-top:15px; text-align:center;"><h4 style="color:#006699;"">|&nbsp;&nbsp; PAROS DE MENSUALES &nbsp;&nbsp;|</h4></td>
                </tr>
            	<tr>
					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=47&area=area3">VER REPORTE</a></td>
                </tr>               
            </table>
		</td>
        <td>&nbsp;</td>
        <? } ?>
        
        <td width="40%" valign="top">
        	<table width="100%" cellpadding="0" cellspacing="0">
 					<? 
					$qMensajes	=	"SELECT * FROM  mensajeria  WHERE checado = 0 AND para = '".$_SESSION['id_admin']."' ORDER BY fecha ASC LIMIT 10 ";
					$rMensajes	=	mysql_query($qMensajes);

					$qResto	=	"SELECT * FROM  mensajeria  WHERE checado = 0 AND para = '".$_SESSION['id_admin']."' ORDER BY fecha ASC";
					$rResto	=	mysql_query($qResto);
										
					$nMensajes	=	mysql_num_rows($rMensajes);
					$nResto	=	mysql_num_rows($rResto);?>
        		<tr>
                	<td colspan="3"><br /><h3>Centro de Mensajes </h3></td>
        		</tr>
                <tr>
                	<td colspan="3" class="style5" align="right" style="font-size: 10px;">Mostrando <?=$nMensajes?> de <?=$nResto?> mensajes</td>
                </tr>
               <? if($nMensajes < 1){ ?>
              <tr>
              	<td colspan="3" align="center" style="color:#006699"><br /><br />Usted no tiene mensajes nuevos<br /><br /><br /></td>
              </tr>
              <? } else { ?>
  			  <tr>
                	<td width="20%"><strong>Fecha</strong></td>
              <td width="69%"><strong>Titulo</strong></td>
              <td width="11%">&nbsp;</td>
              </tr>
                <? for($a = 0; $dMensajes	=	mysql_fetch_assoc($rMensajes); $a++){?>
                <tr height="20" <? if (bcmod($a, 2) == 0) echo " bgcolor='#DDDDDD'"; else echo "";?>>
                	<td class="style5"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dMensajes['fecha'])?></td>
                	<td class="style5"><b><?=htmlspecialchars($dMensajes['titulo']);?></b></td>
                	<td><a href="<?=$_SERVER['PHP_SELF']?>?seccion=36&accion=ver&checado=1&id_mensaje=<?=$dMensajes['id_mensaje']?>&actualiza">Leer</a></td>
                </tr>
                 <? }  
				 }?>
        	</table>
        </td>
        <td width="2%">&nbsp;</td>
<? if($prenomina){ ?>
    <td width="28%" colspan="3" valign="top">
    <form action="<?=$_SERVER['PHP_SELF']?>?seccion=34&accion=listarNomina" method="post" >
    <table align="left" width="100%" cellpadding="0" cellspacing="0">
	  <tr> 
    <?php 

		if(date('w') < 4) 
			$semanaRevisa	=	date('W')-1;
		else 
		 	$semanaRevisa	=	date('W');
		?>
        <td colspan="2" align="center"><br>
        <h3><b>Asistencia Semana No. <?=$semanaRevisa?></b></h3><br>
            A&Ntilde;O : 
          <select name="anio_nom_as">
            <option <?=(date('Y') == '2008')?"selected":""?>>2008</option>
            <option <?=(date('Y') == '2009')?"selected":""?>>2009</option>
            <option <?=(date('Y') == '2010')?"selected":""?>>2010</option>
            <option <?=(date('Y') == '2011')?"selected":""?>>2011</option>
            <option <?=(date('Y') == '2012')?"selected":""?>>2012</option>
            <option <?=(date('Y') == '2013')?"selected":""?>>2013</option>
            <option <?=(date('Y') == '2014')?"selected":""?>>2014</option>
            <option <?=(date('Y') == '2015')?"selected":""?>>2015</option>
            <option <?=(date('Y') == '2016')?"selected":""?>>2016</option>
            <option <?=(date('Y') == '2017')?"selected":""?>>2017</option>
            </select> 
            </td>
            </tr>
          <?
				 	 $qRevisaNom	=	"SELECT * FROM pre_nomina_calificada WHERE semana = '".$semanaRevisa."' AND YEAR(desde) = '".date('Y')."' AND MONTH(desde) =  '".date('m')."' ";
					 $rRevisaNom	=	mysql_query($qRevisaNom);
					 $nNumNom		=	mysql_num_rows($rRevisaNom);	
					if( $nNumNom < 1){     //si no hay registros en pre nomina calificada de la semana actual,año/mes de $desde
						 // VALOR DEL JUEVES
					   //	$jueves	=	'2011-12-22';
						 $jueves	= date('Y-m-d');	  //error pues supone que el dia de inicio es jueves y es posible que no
						 ////**********************
						 $anho		=	date('Y');	
						 $mes		=	date('m');		
						
						 $dia_ultimo	=	UltimoDia($anho,$mes);
						 $comparar	=	date('d') + 6;
						 //$comparar	=	22 + 6;
						
						 if($comparar > $dia_ultimo){
						  	$temporal	=	$comparar - $dia_ultimo;
								
							  $mes_temp	=	date('m') + 1;
							  $miercoles		=	date('Y').'-'.$mes_temp.'-'.$temporal;
						  }
						  else if($comparar < $dia_ultimo){
						  // VALOR DEL MIERCOLES
						  $miercoles	=	date('Y-m-').$comparar;
						  //*********************************
						  }
						
						$qNomina	=	"SELECT * FROM pre_nomina_calificada";
						$rNomina	=	mysql_query($qNomina);
						$nNomina	=	mysql_num_rows($rNomina);
             // Lo anterior no es utilizado


						$qOperadores	=	"SELECT id_operador, rol FROM operadores";
						$rOperadores	=	mysql_query($qOperadores);
            // Verifica que cada operador tenga  un registro en pre_nomina_calificada, si no,lo genera
						while($dOperadores	=	mysql_fetch_assoc($rOperadores)){
									$id_operador	=	$dOperadores['id_operador'];
									$qBusqueda	=	"SELECT id_operador FROM pre_nomina_calificada WHERE id_operador = ".$dOperadores['id_operador']." AND semana = ".$semanaRevisa." AND YEAR(desde) = '".date('Y')."' AND MONTH(desde) = '".date('m')."'";
                  //Busca si el operador ya existe para esa semana y mes/anio
									$rBusqueda	=	mysql_query($qBusqueda);
									$nBusqueda	=	mysql_num_rows($rBusqueda);
								  //si no tiene registro en pre_nomina
								if($nBusqueda < 1 ){    //si no hay registro de ese operador de esa semana, anio,mes 
						  		$qPrenomina	=	"INSERT INTO pre_nomina_calificada (desde,hasta,primaDom,por_asist,punt,prod,id_operador,semana)".
												" VALUES('$jueves', '$miercoles','','10','','','$id_operador','$semanaRevisa')";
							  	//$qPrenomina.'<br>';	
								  $rPrenomina	=	mysql_query($qPrenomina);
								}
								
						}		
						
	          if($nBusqueda < 1 ){   //aqui se tiene si el ultimo operador tiene un registro de esa semana, mes/anio
    						

		           $qOperadores	=	"SELECT id_operador, rol FROM operadores";
		           $rOperadores	=	mysql_query($qOperadores);
			
		        while($dOperadores	=	mysql_fetch_assoc($rOperadores)){
				      $id_operador	=	$dOperadores['id_operador'];
				      $dias = array("J","V","S","D","L","M","Mi");

				      for($dia = 0  ; $dia < 7; $dia++ ){
							//	$fecha	=	// INSERT INTO asistencias 
						    $fecha	=	$jueves;  //$jueves puede no tener el dato correcto, so no se abrió el sistema un jueves
								
						    $nueva_fecha 	= explode("-", $fecha);  // yyyy-mm-dd  separa por guiones
		
						    $ano_avanza = $nueva_fecha[0];       //yyyy
						    $mes_avanza = $nueva_fecha[1];       //mm
						    $dia_avanza = $nueva_fecha[2]+$dia;  //dd + $dia
						    if($dia_avanza > $dia_ultimo){
								  $dia_avanza	=	$dia_avanza - $dia_ultimo;
								  $mes_avanza	=	$mes_avanza + 1;	
								  if($mes_avanza > 12){
									  $mes_avanza	=	1;
									  $ano_avanza	=	$ano_avanza + 1;
								  }
						    } 
						try{
								  $fecha_ideal	=	$ano_avanza.'-'.$mes_avanza.'-'.$dia_avanza;							
									 $qResumenAsistencias	=	"INSERT INTO asistencias (fecha, id_operador, asistencia, retardo, extra, turno, rol, motivos, semana, dia) ".
																"VALUES ('$fecha_ideal','$id_operador','','','','','','','$semanaRevisa', '$dias[$dia]')";
								  //	echo $qResumenAsistencias.'<br>';	
									  $rResumenAsistencias	=	mysql_query($qResumenAsistencias);	
							 }
            catch(Exception $evt){
                    echo "Hubo un error: ". $evt->getMessage();
            }
				  }	 // end   for  r-263  de cada dia de la semana 
				}   //end de while  de cada 	 operador
			 }   //end if r-252 si no existe un registro de
			}	  //end if r-202  si no existe un registro en prenomina de esa semana, anio y mes de  desde
										
			?>
                  
        <tr>
							 <td width="60" align="center" valign="middle">
								<input type="hidden" value="<?=$semanaRevisa?>" name="semana" />
                                
   	            <img src="images/lista.jpg"  alt="ASISTENCIAS" border="0"/></td>                       	  
                            <td width="215" align="center" style="color:#FF0000" valign="middle">  <br />                           
                            <select name="area" id="area" >
                               		<? if($prenominaExt){?>
                             		<option value="1">Extruder</option>
                                  	<? } if($prenominaBol){?>
                             		<option value="2">Bolseo</option>
                                    <? } if($prenominaImpr){?>
                             		<option value="3">Impresion</option>
 									                  <? } if($prenominaMtto){?>                                    
                             		<option value="4">Mantenimiento</option>
          							            <? } if($prenominaMttob){?>
                             		<option value="6">R.P.S.</option>
          							            <? } if($prenominaEmp){?>
                             		<option value="5">Empaque</option>
                                    <? } if($prenominaEmpb){?>
                             		<option value="7">S.F.</option>
                                    <? } if($prenominaAlm){?>
                             		<option value="8">Almacen</option>
                                    <? } if($prenominaAlmb){?>
                             		<option value="9">Calidad</option>
                                    <? } ?>
                             </select><br /><br />
                <input type="submit" name="ver" value="Ver" /></td>
            </tr>
  
		</table>    
  </form>          

      </td><? } ?>
      </tr>
        <tr>
        	<td colspan="6" style="padding-top:20px">&nbsp;</td>
        </tr>
<? 
$mes	=	date('m');
//$mes	=	7;
$anio 	=	date('Y');
$ultimo_dia_mes = UltimoDia(date('Y'),date('m'));

$mes_inicial	=	$anio."-".$mes."-01";
$mes_final		=	$anio."-".$mes."-".$ultimo_dia_mes;

$qRMes	=	"SELECT * FROM meta WHERE mes BETWEEN '".$mes_inicial."' AND '".$mes_final."' AND area = 1";
$rRMes	=	mysql_query($qRMes);
$nRMes	=	mysql_num_rows($rRMes);

$qParos	=	"SELECT * FROM paros_general WHERE ( fecha BETWEEN '$mes_inicial' AND '$mes_final' ) ";
$rParos	=	mysql_query($qParos);
$nParos	=	mysql_num_rows($rParos);

///////////////////////////////////////EXTRUDER ///////////////////////////////////////////////////////////////////////////
if($nRMes < 1){

	 $ultimo_dia 	= 	UltimoDia($anio,$mes);
	 if($nParos > 0)
	 $ultimo_dia	=	$ultimo_dia - $nParos;
		
		
	 $turnos		=	$ultimo_dia*3;	

	for($e = 3; $e > (-1); $e--){
		$anio_meta	=	$anio;
		$mes_meta 	= 	$mes-$e;
		
		if($mes_meta == 0){
			$mes_meta 	= 	12;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01";
		}
		else if($mes_meta == (-1)){
			$mes_meta 	= 	11;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01";
		}
		else if($mes_meta == (-2)){
			$mes_meta 	= 	10;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01"; 
		}
		else if($mes_meta < 10)   
		 	$fecha[$e] 	= 	$anio_meta."-"."0".$mes_meta."-01";
		else 
		 	$fecha[$e] 	= 	$anio_meta."-".$mes_meta."-01";
		
	}
	
 $m1 = num_mes($fecha[1]); 
 $m2 = num_mes($fecha[2]); 
 $m3 = num_mes($fecha[3]); 
	
	$e	=	explode('-',$fecha[1]);
	$mes_ultimo	=	UltimoDia($e[0],$e[1]);
	$fecha[1]	=	$e[0].'-'.$e[1].'-'.$mes_ultimo;
		
	  /*    $qExt	=	" SELECT SUM(diaria) AS diaria,  SUM(metas_maquinas.total) AS total,id_maquina FROM meta INNER JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".
					" WHERE meta.mes IN('$fecha[3]','$fecha[2]','$fecha[1]')  AND meta.area = 1".
					" GROUP BY id_maquina ORDER BY id_maquina ASC"; */
		  
	/*	  $qExt	=	" SELECT  SUM(diaria) AS total, id_maquina FROM resumen_maquina_ex "	.
		  			" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".
					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".
					" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND   impresion = 0".
					" GROUP BY id_maquina ORDER BY id_maquina ASC"; */
					
		  $qExt	=	" SELECT  SUM(subtotal) AS total, resumen_maquina_ex.id_maquina AS id_maquina, maquina.numero, MONTH(fecha) AS mes FROM resumen_maquina_ex "	.
		  			" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".
					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".
					" INNER JOIN maquina 	ON resumen_maquina_ex.id_maquina 		= maquina.id_maquina ".
					" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND  impresion = 0 GROUP BY  MONTH(fecha), resumen_maquina_ex.id_maquina ".
					" ORDER BY resumen_maquina_ex.id_maquina, fecha ASC";
		  
	  	$qDias =	" SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_ex.id_maquina, maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_ex "	.
		  			" INNER JOIN orden_produccion 	ON resumen_maquina_ex.id_orden_produccion 	= orden_produccion.id_orden_produccion ".
					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= orden_produccion.id_entrada_general ".
					" INNER JOIN maquina 			ON resumen_maquina_ex.id_maquina 			= maquina.id_maquina ".
					" WHERE subtotal != 0 AND ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND  impresion = 0 GROUP BY  MONTH(fecha), resumen_maquina_ex.id_maquina ".
					" ORDER BY resumen_maquina_ex.id_maquina, fecha ASC";				
					
		  $rExt	=	mysql_query($qExt);
		  $rDias	=	mysql_query($qDias);
		  
		  for($t=0; $dExt	=	mysql_fetch_assoc($rExt);$t++){	
		  
		  		  	$nuevo_turno	=	$turnos;
		  		$qP_M	=	"SELECT * FROM paros_maquinas WHERE ( fecha BETWEEN '$mes_inicial' AND '$mes_final') AND id_maquina = ".$dExt['id_maquina']."";
				$rP_M	=	mysql_query($qP_M);
				$nP_M	=	mysql_num_rows($rP_M);
				if($nP_M>0){
					
					while($dP_M	=	mysql_fetch_assoc($rP_M)){
	
						if($dP_M['turno'] == 0){
							$nuevo_turno	=	$nuevo_turno - 3;						
						}
						else if($dP_M['turno'] > 0){
							$nuevo_turno	=	$nuevo_turno - 1;
						}				
					}
				}
			  $ultimos_dias	=	$nuevo_turno/3;

				
				$tuuurnos[$t]	=	$nuevo_turno;
				$ul_di[$t]		=	$ultimos_dias;
				$nuevo_turno	=	$turnos;
		  	  
		  		$diaria[$t]	=	$dExt['total'];	
				$id[$t]		=	$dExt['numero'];
				$maquina[$t]=	$dExt['id_maquina'];
				$mes_o[$t]	=	$dExt['mes'];
				

		  }

		  for($t=0; $dDias	=	mysql_fetch_assoc($rDias);$t++){		  
		  		$Dias[$t]	=	$dDias['paros'];
				$id_d[$t]	=	$dDias['numero'];
				$mes_d[$t]	=	$dDias['mes'];

		  }

		  	$Total_dia 	= 	0;
			$Total_mes	=	0;
			$porcentaje	=	0;
		  
		  for($t=0,$b=0; $t < sizeof($diaria);$t++){		

				if($id_d[$b] == $id[$t]){ 
					$diaria_d[$t]	=	ceil($diaria[$t]/$Dias[$b]); 
					$maquinas_d		=	$maquina[$t];
					$b=$b+1;
				}
				else
				$diaria_d[$t]	=	0;
				
				if($mes_o[$t] == $m1)
				$total_1	+= 	ceil($diaria_d[$t]);
				if($mes_o[$t] == $m2)
				$total_2	+= 	ceil($diaria_d[$t]);
				if($mes_o[$t] == $m3)
				$total_3	+= 	ceil($diaria_d[$t]);	
				
				$Total_dia = ($total_1+$total_2+$total_3)/3;
				
			//$Total_dia	+=	$diaria_d[$t];
				//$Total_dia	=	$Total_dia/3;

				}

				for($t = 0, $c=0 ; $t < sizeof($diaria);$t++){
					
					for($b = 0 ;$b < 3 ;$b++){
					 	$diarioE[$c]	+=	$diaria_d[$t+$b];
						$maqui[$c]		=	$maquina[$t];
						$ul_dias[$c]	=	$ul_di[$t+$b];
						$tuuurnos2[$c]	=	$tuuurnos[$t+$b];

					}
	
						$diarioE[$c]			= 	$diarioE[$c]/3 ;
						
						if($diarioE[$c] == 0){
							
							$qMTE	=	" SELECT * FROM metas_maquinas ".
										" LEFT JOIN meta ON	metas_maquinas.id_meta = meta.id_meta WHERE mes = '".$fecha[2]."'  AND id_maquina = ".$maqui[$c]."";
							$rMTE	=	mysql_query($qMTE);						
							$dMTE	=	mysql_fetch_assoc($rMTE);
							
							$diarioE[$c]	=	$dMTE['diaria'];
						
						}
					 	$Total_maquina_dia[$c]	=	$ul_dias[$c]*$diarioE[$c];
						$Total_mes				+=	$Total_maquina_dia[$c];			

					$t = $t+2;
					$c=$c+1;
				
				}
					
					
		  $rExt	=	mysql_query($qExt);



	      $qExtD	=	" SELECT SUM(desperdicio_duro+desperdicio_tira)/3 AS duro_hd FROM  orden_produccion 	".
							" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 	=	orden_produccion.id_entrada_general ".
							" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND impresion = 0".
							" GROUP BY impresion";
		  
		  $rExtD	=	mysql_query($qExtD);
		  
		  for($t=0; $dExtD	=	mysql_fetch_assoc($rExtD);$t++){
		  	if($dExtD['duro_bd'] == 0)
			   	$duro_bd	=	0;
		  	else
				  $duro_bd	=	ceil($dExtD['duro_bd']/3);
				
				$duro_hd	=	ceil($dExtD['duro_hd']);

		  }		

     try{
		    $porcentaje = 10;// ($Total_mes/$duro_hd)/100;
		  //".redondeado($porcentaje,2)."
       // pDebug("Valor de Porcentaje: ".$porcentaje);
		  }
      catch(Exception $mivar)
       {
      //   pDebug("Error: ".$mivar.getMessage());
        $porcentaje=10;  //agregado para probar
      }

		 $qME	=	"INSERT INTO meta (total_hd, total_dia, desp_duro_hd, desp_duro_bd, porcentaje_desp, area, ano, mes)".
						" values ('".ceil($Total_mes)."','".ceil($Total_dia)."','".$duro_hd."','$duro_bd','1.2','1','".date('Y')."','".$mes_inicial."')";
		 $rME	=	mysql_query($qME);
		 $ultimo_id = mysql_insert_id(); 
		
				
		  for($t=0; $t < sizeof($diarioE) ;$t++){
		  	$qMME	=	" INSERT INTO metas_maquinas (id_meta,id_maquina,diaria,turnos,dias,total,area)".
						" VALUES ('$ultimo_id','$maqui[$t]','".ceil($diarioE[$t])."','".$tuuurnos2[$t]."','".redondeado($ul_dias[$t],2)."','".redondeado($Total_maquina_dia[$t],2)."','1')";
			$rMME	=	mysql_query($qMME);
	}	  
	
}
/////////////////////////////////////////////////////////////////////////////////

		$qRMesI	=	"SELECT * FROM meta WHERE mes BETWEEN '".$mes_inicial."' AND '".$mes_final."' AND area = 2";
		$rRMesI	=	mysql_query($qRMesI);
		$nRMesI	=	mysql_num_rows($rRMesI);

///////////////////////////////////////IMPRESION//////////////////////////////////////////////////////////////////////////

if($nRMesI < 1){

	$ultimo_dia = UltimoDia($anio,$mes);
	if($nParos > 0)
		$ultimo_dia	=	$ultimo_dia - $nParos;
	$turnos	=	$ultimo_dia*3;	


	for($e = 3; $e > (-1); $e--){
		$anio_meta	=	$anio;
		$mes_meta 	= 	$mes-$e;
		
		if($mes_meta == 0){
			$mes_meta 	= 	12;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01";
		}
		else if($mes_meta == (-1)){
			$mes_meta 	= 	11;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01";
		}
		else if($mes_meta == (-2)){
			$mes_meta 	= 	10;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01"; 
		}
		else if($mes_meta < 10)   
		 	$fecha[$e] 	= 	$anio_meta."-"."0".$mes_meta."-01";
		else 
		 	$fecha[$e] 	= 	$anio_meta."-".$mes_meta."-01";
	}
	
 $m1 = num_mes($fecha[1]); 
 $m2 = num_mes($fecha[2]); 
 $m3 = num_mes($fecha[3]);
	
	$e	=	explode('-',$fecha[1]);
	$mes_ultimo	=	UltimoDia($e[0],$e[1]);
	$fecha[1]	=	$e[0].'-'.$e[1].'-'.$mes_ultimo;

	/*     $qExt	=	" SELECT SUM(metas_maquinas.diaria) AS diaria, SUM(metas_maquinas.total) AS total, id_maquina, metas_maquinas.area FROM meta INNER JOIN metas_maquinas ON meta.id_meta = metas_maquinas.id_meta".
					" WHERE meta.mes IN('$fecha[3]','$fecha[2]', '$fecha[1]') AND meta.area IN (2,3) ".
					" GROUP BY id_maquina ORDER BY meta.area ASC";
		  $rExt	=	mysql_query($qExt);

	      $qExtD	=	" SELECT SUM(desp_duro_bd) AS duro_bd, SUM(desp_duro_hd) AS duro_hd, SUM(porcentaje_desp) AS ps  ".
		  				" FROM meta".
						" WHERE meta.mes IN('$fecha[3]','$fecha[2]', '$fecha[1]')  AND meta.area IN (2,3)";
		  $rExtD	=	mysql_query($qExtD); */
		  
	/*	  $qExtI	=	" SELECT  SUM(diaria) AS total, resumen_maquina_im.id_maquina, maquina.area FROM resumen_maquina_im "	.
		  				" INNER JOIN impresion 	ON resumen_maquina_im.id_impresion 	= impresion.id_impresion ".
						" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 		= impresion.id_entrada_general ".
						" LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina".
						" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND   impresion = 1".
						" GROUP BY resumen_maquina_im.id_maquina ORDER BY maquina.area ASC"; */
						
		 $qExtI	=	" SELECT  SUM(subtotal) AS total, resumen_maquina_im.id_maquina, numero, MONTH(fecha) AS mes, maquina.area FROM resumen_maquina_im "	.
		  			" INNER JOIN impresion 			ON resumen_maquina_im.id_impresion 		= impresion.id_impresion ".
					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 	= impresion.id_entrada_general ".
					" LEFT JOIN maquina 			ON resumen_maquina_im.id_maquina 		= maquina.id_maquina ".
					" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND   impresion = 1 GROUP BY  MONTH(fecha), resumen_maquina_im.id_maquina ".
					" ORDER BY maquina.area, maquina.numero, fecha ASC";
											
						
		  	$qDias =	" SELECT COUNT(*)/3 AS paros  , fecha, resumen_maquina_im.id_maquina, maquina.numero, MONTH(fecha) AS mes  FROM resumen_maquina_im "	.
		  			" INNER JOIN impresion 			ON resumen_maquina_im.id_impresion 		= impresion.id_impresion ".
					" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 	= impresion.id_entrada_general ".
					" INNER JOIN maquina 			ON resumen_maquina_im.id_maquina 			= maquina.id_maquina ".
					" WHERE subtotal != 0 AND ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND  impresion = 1 GROUP BY  MONTH(fecha), resumen_maquina_im.id_maquina ".
					" ORDER BY maquina.area, maquina.numero, fecha ASC";							
						
						
						
	      $qExtDI	=	" SELECT SUM(desperdicio_hd+desperdicio_bd)/3 AS duro_hd FROM  impresion 	".
						" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 	=	impresion.id_entrada_general ".
						" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND impresion = 1";
									  
		  $rExtI	=	mysql_query($qExtI);
		  $rExtDI	=	mysql_query($qExtDI);
		  $rDias	=	mysql_query($qDias);
		  
		  for($t=0; $dExtI	=	mysql_fetch_assoc($rExtI);$t++){	
		  
		  		  	$nuevo_turno	=	$turnos;
		  		$qP_M	=	"SELECT * FROM paros_maquinas WHERE ( fecha BETWEEN '$mes_inicial' AND '$mes_final') AND id_maquina = ".$dExtI['id_maquina']."";
				$rP_M	=	mysql_query($qP_M);
				$nP_M	=	mysql_num_rows($rP_M);
				if($nP_M>0){
					
					while($dP_M	=	mysql_fetch_assoc($rP_M)){
	
						if($dP_M['turno'] == 0){
							$nuevo_turno	=	$nuevo_turno - 3;						
						}
						else if($dP_M['turno'] > 0){
							$nuevo_turno	=	$nuevo_turno - 1;
						}				
					}
				}
			  $ultimos_dias	=	$nuevo_turno/3;

				
				$tuuurnosI[$t]	=	$nuevo_turno;
				$ul_diI[$t]		=	$ultimos_dias;
				$nuevo_turno	=	$turnos;
				
		  	  	$area_maquinasI[$t]		=	$dExtI['area'];
		  		$diarias[$t]			=	$dExtI['total'];	
				$id[$t]					=	$dExtI['numero'];
				$maquinasI[$t]			=	$dExtI['id_maquina'];
				$mes_o[$t]				=	$dExtI['mes'];
			//	$Total_maquina_diaI[$t]	=	$diariaI[$t]*$ultimos_dias;

		  }

		  for($t=0; $dDias	=	mysql_fetch_assoc($rDias);$t++){		  
		  		$Dias[$t]	=	$dDias['paros'];
				$id_d[$t]	=	$dDias['numero'];
				$mes_d[$t]	=	$dDias['mes'];

		  }

		$Total_diaI 	= 	0;
		$Total_mesI		=	0;
		$porcentajeI	=	0;
		  
		  for($t=0,$b=0; $t < sizeof($diarias);$t++){		

				if($id_d[$b] == $id[$t]){ 
					$diaria_d[$t]	=	ceil($diarias[$t]/$Dias[$b]); 
					$maquinas_dI[$t]		=	$maquinasI[$t];
					$b=$b+1;
				}
				else{
				$diaria_d[$t]	=	0;
				$maquinas_dI[$t]		=	$maquinasI[$t];				
				}
				
				if($mes_o[$t] == $m1)
				$total_11	+= 	ceil($diaria_d[$t]);
				if($mes_o[$t] == $m2)
				$total_22	+= 	ceil($diaria_d[$t]);
				if($mes_o[$t] == $m3)
				$total_32	+= 	ceil($diaria_d[$t]);	
				
				$Total_diaI = ($total_11+$total_22+$total_32)/3;
				
		//	$Total_dia	+=	$diaria_d[$t];
		//	$Total_dia	=	$Total_dia/3;

				}

				for($t = 0, $c=0 ; $t < sizeof($diarias);$t++){
					
					for($b = 0 ;$b < 3 ;$b++){
					 	$diario[$c]	+=	$diaria_d[$t+$b];
						$maquiI[$c]	=	$maquinas_dI[$t];
					    $areas[$c]	=	$area_maquinasI[$t];
						$ul_diasI[$c]	=	$ul_diI[$t+$b];
						$tuuurnosI2[$c]	=	$tuuurnosI[$t+$b];

					}
	
						$diario[$c]				= 	$diario[$c]/3 ;
						if($diario[$c] == 0){
							
							$qMTE	=	" SELECT * FROM metas_maquinas ".
										" LEFT JOIN meta ON	metas_maquinas.id_meta = meta.id_meta WHERE mes = '".$fecha[2]."'  AND id_maquina = ".$maquiI[$c]."";
							$rMTE	=	mysql_query($qMTE);						
							$dMTE	=	mysql_fetch_assoc($rMTE);
							
							$diario[$c]	=	$dMTE['diaria'];
						
						}
						
					 	$Total_maquina_diaI[$c]	=	$ul_diasI[$c]*$diario[$c];
						$Total_mesI				+=	$Total_maquina_diaI[$c];	

					$t = $t+2;
					$c=$c+1;
				
				}


		  for($t=0; $dExtDI	=	mysql_fetch_assoc($rExtDI);$t++){
		  	if($dExtDI['duro_bd'] == 0)
				$duro_bdI	=	0;
		  	else
				$duro_bdI	=	ceil($dExtDI['duro_bd']/3);
				
			 	$duro_hdI	=	ceil($dExtDI['duro_hd']);
		 }		


		  
		 $porcentajeI =  ($Total_mesI/$duro_hdI)/100;		  
		  //".redondeado($porcentajeI,2)."
			 $qME	=	"INSERT INTO meta (total_hd, total_dia, desp_duro_hd, desp_duro_bd, porcentaje_desp, area, ano, mes)".
						" values ('".ceil($Total_mesI)."','".ceil($Total_diaI)."','".$duro_hdI."','$duro_bdI','1.00','2','".date('Y')."','".$mes_inicial."')";
		  	 $rME	=	mysql_query($qME);
			$ultimo_id = mysql_insert_id(); 
		

			$nuevo_turno	=	$turnos;
			$contador		=	0;
			
		  for($t=0; $t < sizeof($diario) ;$t++){
		  
			$qP_Mi	=	"SELECT * FROM paros_maquinas WHERE ( fecha BETWEEN '$mes_inicial' AND '$mes_final') AND id_maquina = '".$maquiI[$t]."'";
			$rP_Mi	=	mysql_query($qP_Mi);
			$nP_Mi	=	mysql_num_rows($rP_Mi);
			
		  	if($nP_Mi>0){
				
				while($dP_M	=	mysql_fetch_assoc($rP_Mi)){

					if($dP_M['turno'] == 0){
						$nuevo_turno	=	$nuevo_turno - 3;
						
					}
					else if($dP_M['turno'] > 0){
						$nuevo_turno	=	$nuevo_turno - 1;
					}				
				}
			}
		  $ultimo_dia	=	$nuevo_turno/3;
		  
		  		($areas[$t] == 3)?$area_realI=3:$area_realI=$areas[$t];
		 $qMME	=	" INSERT INTO metas_maquinas (id_meta,id_maquina,diaria,turnos,dias,total,area)".
						" VALUES ('$ultimo_id','$maquiI[$t]','".ceil($diario[$t])."','".$tuuurnosI2[$t]."','".redondeado($ul_diI[$t],2)."','".redondeado($Total_maquina_diaI[$t],2)."','".$area_realI."')";
	  	 $rMME	=	mysql_query($qMME);
		$nuevo_turno	=	$turnos;
		$contador	= 0;
	}	  
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////

$qRMesB	=	"SELECT * FROM meta WHERE mes BETWEEN '".$mes_inicial."' AND '".$mes_final."' AND area = 3";
$rRMesB	=	mysql_query($qRMesB);
$nRMesB=	mysql_num_rows($rRMesB);

///////////////////////////////////////BOLSEO//////////////////////////////////////////////////////////////////////////
if($nRMesB < 1){

	 $ultimo_dia = UltimoDia($anio,$mes);
		if($nParos > 0)
		$ultimo_dia	=	$ultimo_dia - $nParos;
	 
	 $turnos	=	$ultimo_dia*3;	

	for($e = 3; $e > (-1); $e--){
		$anio_meta	=	$anio;
		$mes_meta 	= 	$mes-$e;
		
		if($mes_meta == 0){
			$mes_meta 	= 	12;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01";
		}
		else if($mes_meta == (-1)){
			$mes_meta 	= 	11;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01";
		}
		else if($mes_meta == (-2)){
			$mes_meta 	= 	10;
			$anio_meta	= 	$anio-1;
			$fecha[$e]	=	$anio_meta."-".$mes_meta."-01"; 
		}
		else if($mes_meta < 10)   
		 	$fecha[$e] 	= 	$anio_meta."-"."0".$mes_meta."-01";
		else 
		 	$fecha[$e] 	= 	$anio_meta."-".$mes_meta."-01";
		
	}
		$e	=	explode('-',$fecha[1]);
		$mes_ultimo	=	UltimoDia($e[0],$e[1]);
		$fecha[1]	=	$e[0].'-'.$e[1].'-'.$mes_ultimo;
	
		   $qExtDI	=	" SELECT SUM(desperdicio_bd)/3 AS duro_hd FROM  impresion 	".
							" INNER JOIN entrada_general 	ON entrada_general.id_entrada_general 	=	impresion.id_entrada_general ".
							" WHERE ( fecha BETWEEN '$fecha[3]' AND '$fecha[1]' )  AND impresion = 1".
							" GROUP BY impresion";
			
		  $rExtDI	=	mysql_query($qExtDI);
	

	      $qExtDB	=" SELECT ".
						" SUM(millares)/3 AS meta_mes_millar, ".
						" SUM(kilogramos)/3 AS meta_mes_kilo, ".
						" SUM(dtira)/3 AS mes_tira, ".
						" SUM(dtroquel)/3 AS mes_troquel, ".
						" SUM(segundas)/3 AS mes_segunda ".
						" FROM bolseo".
						" WHERE fecha  BETWEEN '$fecha[3]' AND '$fecha[1]'";
		  $rExtDB	=	mysql_query($qExtDB);


		  for($t=0; $dExtDB	=	mysql_fetch_assoc($rExtDB);$t++){
		  

		  	if($dExtDB['meta_mes_millar'] == 0)
				$mmm	=	0;
		  	else
				$mmm	=	$dExtDB['meta_mes_millar'];

		  	if($dExtDB['meta_mes_kilo'] == 0)
				$mmk	=	0;
		  	else
				$mmk	=	$dExtDB['meta_mes_kilo'];

		  	if($dExtDB['mes_tira'] == 0)
				$mtira	=	0;
		  	else
				$mtira	=	$dExtDB['mes_tira'];

		  	if($dExtDB['mes_troquel'] == 0)
				$mtroq	=	0;
		  	else
				$mtroq	=	$dExtDB['mes_troquel'];
				
		  	if($dExtDB['mes_segunda'] == 0)
				$mseg	=	0;
		  	else
				$mseg	=	$dExtDB['mes_segunda'];			
		  }		
		  

		  
		  
		  		$seg 	=  	($mmk/$mseg)/100;		  
				$trl	=	($mmk/$mtroq)/100;
		  		$desp	=	($mmk/$mtira)/100;
		    //".redondeado($desp,2)."
			//".redondeado($trl,2)."
			//".redondeado($seg,2)."
			  $qMB	=	"INSERT INTO meta (	porcentaje_desp,area,ano,mes,troquel,meta_mes_millar,meta_mes_kilo,mes_tira,mes_troquel,mes_segunda,porcentaje_troquel,porcentaje_segunda)".
						" values ('2.0','3','".date('Y')."','".$mes_inicial."','".redondeado($mtrql,2)."','".redondeado($mmm,2)."','".redondeado($mmk,2)."','".redondeado($mtira,2)."','".redondeado($mtroq,2)."','".redondeado($mseg,2)."','11.0','1.5')";
		  		$rMB	=	mysql_query($qMB);


}

/////////////////////////////////////////////////////////////////////////////////////////////////////////

$qG	=	"SELECT * FROM meta WHERE id_genera_meta = 0 AND mes = '".date('Y-m-01')."' ORDER BY mes ASC";
$rG	=	mysql_query($qG);
$nG	=	mysql_num_rows($rG);

/*joel 04 Ago 2014
if($nG>0){
while($dG	=	mysql_fetch_assoc($rG)){  ?>
		<tr>
        <td colspan="6" class="style1" style="padding-top:8px;">El sistema ha generado una meta para este mes en: <? if($dG['area']==1) echo "Extruder"; else if($dG['area']==2) echo "Impresion"; else if($dG['area']==3) echo "Bolseo";?>, <a href="admin.php?seccion=15&accion=desgloze&metas_viejas=0&id_meta=<?=$dG['id_meta']?>" class="style4" >click aqui</a> para verla ahora.</td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
<? }  
 } */
?>
        <tr>
        	<td>&nbsp;</td>
        </tr>
       </table>
         </div>
</div>