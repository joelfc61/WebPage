<div id="container">

		<div id="content">

       <table width="100%"  cellpadding="0" cellspacing="0" align="center" border="0">

       <tr>

        <td width="28%" valign="top">

   

<table align="left" width="100%" cellpadding="0" cellspacing="0">

            	<tr>

                	<td colspan="3" valign="top"><h3>REPESADAS PENDIENTES</h3></td>

                </tr>

                             <?

							$qEntradas	=	"SELECT * FROM entrada_general INNER JOIN bolseo ON entrada_general.fecha	=	bolseo.fecha".

								" AND entrada_general.turno = bolseo.turno  ".

								" WHERE entrada_general.actualizado=0 AND bolseo.actualizado=0 AND entrada_general.impresion = 0 GROUP BY entrada_general.fecha, entrada_general.turno ORDER BY entrada_general.fecha, entrada_general.turno	ASC";							$rEntradas=mysql_query($qEntradas);

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

					$qRepeImpresion	=	"SELECT * FROM entrada_general WHERE impresion = 1 AND fecha ='".$dEntradas['fecha']."' AND turno = ".$dEntradas['turno']." ";

					$rRepeImpresion =	mysql_query($qRepeImpresion);

					$nRepeImpresion	=	mysql_num_rows($rRepeImpresion);

					if($nRepeImpresion > 0){

							?>

				<tr onMouseOver="this.style.backgroundColor='#CCC'"	onmouseout="this.style.backgroundColor='#EAEAEA'" style="cursor:default; background-color:#EAEAEA">

                      <td width="99"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dEntradas['fecha'])?></td>

                  <td width="81" align="center"><?=$dEntradas['turno']?></td>

                  <td  ><a href="<?=$_SERVER['HOST']?>	?seccion=7&accion=actualizarExtruder&id_extruder=<?=$dEntradas['id_entrada_general']?>&id_bolseo=<?=$dEntradas['id_bolseo']?>&accion=repesar&turno=<?=$dEntradas['turno']?>&fecha=<?=$dEntradas['fecha']?>">Reconteo</a></td>

              </tr>

                <?php } else { ?> 

                <tr>

                	<td colspan="3" align="center" class="style4">Falta reporte de Impresión</td>

                 </tr>

                <? } } 

				}  else { ?>

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

					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=40">VER REPORTE</a></td>

                </tr>

            	<tr>

                	<td colspan="3" valign="top" style="padding-top:15px; text-align:center;"><h4 style="color:#006699;"">|&nbsp;&nbsp; PAROS DE MENSUALES &nbsp;&nbsp;|</h4></td>

                </tr>

            	<tr>

					<td colspan="3" valign="top" align="center" style="padding-top:3px;"><a href="<?=$_SERVER['HOST']?>?seccion=41&area=area3">VER REPORTE</a></td>

                </tr>

                

            </table>

                    </td>

        

        <td width="2%" valign="top">&nbsp;</td>

        

        <td width="40%" valign="top">

        	<table width="100%" cellpadding="0" cellspacing="0">

 					<? 

					$qMensajes	=	"SELECT * FROM  mensajeria  WHERE checado = 0 AND para = '".$_SESSION['id_supervisor']."' ORDER BY fecha ASC LIMIT 10 ";

					$rMensajes	=	mysql_query($qMensajes);



					$qResto	=	"SELECT * FROM  mensajeria  WHERE checado = 0 AND para = '".$_SESSION['id_supervisor']."' ORDER BY fecha ASC";

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

                	<td><a href="<?=$_SERVER['PHP_SELF']?>?seccion=35&accion=ver&checado=1&id_mensaje=<?=$dMensajes['id_mensaje']?>&actualiza">Leer</a></td>

                </tr>

                 <? }  

				 }?>

        	</table>

        </td>

        <td width="2%">&nbsp;</td>



        <td width="28%" colspan="3" valign="top">

        <form action="<?=$_SERVER['PHP_SELF']?>?seccion=33&accion=listarNomina" method="post" >

       <table align="left" width="100%" cellpadding="0" cellspacing="0">

		<tr><? 		

		if(date('w') < 4)    // 0 a 3  domingo a miercoles

			$semanaRevisa	=	date('W')-1;

		else       // 4 jueves  , 5 viernes, 6 sabado

			$semanaRevisa	=	date('W');

		?>

               	  <td colspan="2" align="center"><br>

                  <h3><b>Asistencia Semana No.<?=$semanaRevisa?></b></h3><br>

                  A&Ntilde;O : <select name="anio_nom_as">

                  	<option <?=(date('Y') == '2008')?"selected":""?>>2008</option>

                  	<option <?=(date('Y') == '2009')?"selected":""?>>2009</option>

                  	<option <?=(date('Y') == '2010')?"selected":""?>>2010</option>

                  	<option <?=(date('Y') == '2011')?"selected":""?>>2011</option>

                  	<option <?=(date('Y') == '2012')?"selected":""?>>2012</option>
                    
                  	<option <?=(date('Y') == '2013')?"selected":""?>>2013</option>

                  	<option <?=(date('Y') == '2014')?"selected":""?>>2014</option>

                  	<option <?=(date('Y') == '2015')?"selected":""?>>2015</option>

                   </select> 

                  </td>

                </tr>

                	<?php

					 $qRevisaNom	=	"SELECT * FROM pre_nomina_calificada WHERE semana = '".$semanaRevisa."' AND YEAR(desde) = '".date('Y')."' ";

					 $rRevisaNom	=	mysql_query($qRevisaNom);

					 $nNumNom		=	mysql_num_rows($rRevisaNom);	

					if( $nNumNom < 1){

						// VALOR DEL JUEVES

						//$jueves	=	'2009-01-01';

						$jueves	= date('Y-m-d');	

						////**********************

						$anho		=	date('Y');	

						$mes		=	date('m');		

						

						$dia_ultimo	=	UltimoDia($anho,$mes);

						$comparar	=	date('d') + 6;

						

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

						





								$qOperadores	=	"SELECT id_operador, rol FROM operadores";

								$rOperadores	=	mysql_query($qOperadores);

							while($dOperadores	=	mysql_fetch_assoc($rOperadores)){

									$id_operador	=	$dOperadores['id_operador'];

									

									$qBusqueda	=	"SELECT id_operador FROM pre_nomina_calificada WHERE id_operador = ".$dOperadores['id_operador']." AND semana = ".$semanaRevisa." AND YEAR(desde) = '".date('Y')."'";

									$rBusqueda	=	mysql_query($qBusqueda);

									$nBusqueda	=	mysql_num_rows($rBusqueda);

								

					    			if($nBusqueda < 1 ){

								      $qPrenomina	=	"INSERT INTO pre_nomina_calificada (desde,hasta,primaDom,por_asist,punt,prod,id_operador,semana)".

												" VALUES('$jueves', '$miercoles','','10','','','$id_operador','$semanaRevisa')";

								      $rPrenomina	=	mysql_query($qPrenomina);

								    }

								

						    }		

						

					if($nBusqueda < 1 ){

						



								$qOperadores	=	"SELECT id_operador, rol FROM operadores";

								$rOperadores	=	mysql_query($qOperadores);

								

								while($dOperadores	=	mysql_fetch_assoc($rOperadores)){

									$id_operador	=	$dOperadores['id_operador'];

									$dias = array("J","V","S","D","L","M","Mi");

                    				for($dia = 0  ; $dia < 7; $dia++ ){
    
							           //	$fecha	=	// INSERT INTO asistencias 

								       $fecha	=	$jueves;

								
								       $nueva_fecha 	= explode("-", $fecha);

		    						   $ano_avanza = $nueva_fecha[0];

									   $mes_avanza = $nueva_fecha[1];

									   $dia_avanza = $nueva_fecha[2]+$dia;

									   if($dia_avanza > $dia_ultimo){

										  $dia_avanza	=	$dia_avanza - $dia_ultimo;

										  $mes_avanza	=	$mes_avanza + 1;	

											if($mes_avanza > 12){

												$mes_avanza	=	1;

												$ano_avanza	=	$ano_avanza + 1;

											}

									    } 

									   $fecha_ideal	=	$ano_avanza.'-'.$mes_avanza.'-'.$dia_avanza;

																	
                 						$qResumenAsistencias	=	"INSERT INTO asistencias (fecha, id_operador, asistencia, retardo, extra, turno, rol, motivos, semana, dia) ". //insert into asistencias

																"VALUES ('$fecha_ideal','$id_operador','','','','','','','$semanaRevisa', '$dias[$dia]')";

								         //	pDebug($qResumenAsistencias);	

									    $rResumenAsistencias	=	mysql_query($qResumenAsistencias);	

							

								    }	 //end for

							    } //end while	

						   } //end if r-361

					} //end if 269					

										

		?>

               <td width="60" align="center" valign="middle">

								<input type="hidden" value="<?=$semanaRevisa?>" name="semana" />

                                

   	            <img src="images/lista.jpg"  alt="ASISTENCIAS" border="0"/></td>                       	  

                            <td width="215" align="center" style="color:#FF0000" valign="middle">  <br />   

                        <select name="area" id="area" >

                               		<? if($_SESSION['area'] == 1){?>

                             		<option value="1">Extruder</option>

                                  	<? } if($_SESSION['area3'] == 1){?>

                             		<option value="2">Bolseo</option>

                                    <? } if($_SESSION['area2'] == 1){?>

                             		<option value="3">Impresion</option>

                                    <? } ?>

                             </select><br /><br />

                </td>

                </tr>

                <tr>

                <td colspan="2" align="center"><br />

                <input type="submit" name="ver" value="Ver" />

                <br /></td>

               	</tr>    

</table>        

</form>

         </td>

       </tr>

       </table>

         </div>

</div>