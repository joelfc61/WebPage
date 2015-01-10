
<?php 
include "conectar.php";
include "funciones2.php";
include "debugger.php";

set_time_limit(120);

/*		if(date('w') < 4) 
			$semanaRevisa	=	date('W');
		else 
		 	$semanaRevisa	=	date('W')+1;
*/


		$semanaRevisa = $_GET['semana'];
		$nDia=$_GET['dia'];
		$nMes=$_GET['mes'];
		$nAnio=$_GET['anio'];


		echo "<br> Generando semana ".$semanaRevisa." Del anio ".$nAnio."<br>";
		echo "Inicia el ".$nDia." del mes ".$nMes." <br>";

		
		$fecha_desde= mktime(0,0,0,$nMes,$nDia,$nAnio);

        echo "fecha Desde: ". date('Y-m-d',$fecha_desde)."<br>";		       
                	
				 	 $qRevisaNom	=	"SELECT * FROM pre_nomina_calificada WHERE semana = '".$semanaRevisa."' AND YEAR(desde) = '".date('Y',$fecha_desde)."' AND MONTH(desde) =  '".date('m',$fecha_desde)."' ";
					 $rRevisaNom	=	mysql_query($qRevisaNom);
					 $nNumNom		=	mysql_num_rows($rRevisaNom);	
					if( $nNumNom < 1){
						 // VALOR DEL JUEVES
					   //	$jueves	=	'2011-12-22';
						 $jueves	= date('Y-m-d',$fecha_desde);	
						 ////**********************
						 $anho		=	date('Y',$fecha_desde);	
						 $mes		=	date('m',$fecha_desde);		
						
						 $dia_ultimo	=	UltimoDia($anho,$mes);
						 $comparar	=	date('d',$fecha_desde) + 6;
						 //$comparar	=	22 + 6;
						
						 if($comparar > $dia_ultimo){
						  	$temporal	=	$comparar - $dia_ultimo;
								
							  $mes_temp	=	date('m',$fecha_desde) + 1;
							  $miercoles		=	date('Y',$fecha_desde).'-'.$mes_temp.'-'.$temporal;
						  }
						else if($comparar <= $dia_ultimo){
						  // VALOR DEL MIERCOLES
						  $miercoles	=	date('Y-m-',$fecha_desde).$comparar;
						  //*********************************
						}
						
						$qNomina	=	"SELECT * FROM pre_nomina_calificada";
						$rNomina	=	mysql_query($qNomina);
						$nNomina	=	mysql_num_rows($rNomina);
						


						$qOperadores	=	"SELECT id_operador, rol FROM operadores";
						$rOperadores	=	mysql_query($qOperadores);
            // A cada operador generar un registro en pre_nomina_calificada
						while($dOperadores	=	mysql_fetch_assoc($rOperadores)){
									$id_operador	=	$dOperadores['id_operador'];
									
									$qBusqueda	=	"SELECT id_operador FROM pre_nomina_calificada WHERE id_operador = ".$dOperadores['id_operador']." AND semana = ".$semanaRevisa." AND YEAR(desde) = '".date('Y',$fecha_desde)."' AND MONTH(desde) = '".date('m',$fecha_desde)."'";
                                  //Busca si el operador ya existe para esa semana y mes/anio
									$rBusqueda	=	mysql_query($qBusqueda) or die("No se encuentra Operador para semana, mes anio");
									
									  $nBusqueda	=	mysql_num_rows($rBusqueda);
								//si no tiene registro en pre_nomina
								if($nBusqueda < 1 ){
						  		$qPrenomina	=	"INSERT INTO pre_nomina_calificada (desde,hasta,primaDom,por_asist,punt,prod,id_operador,semana)".
												" VALUES('$jueves', '$miercoles','','10','','','$id_operador','$semanaRevisa')";
							  	//pDebug($qPrenomina);
								echo "P-".$id_operador." ";
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
									try{
								 $fecha_ideal	=	$ano_avanza.'-'.$mes_avanza.'-'.$dia_avanza;
																	
									$qResumenAsistencias	=	"INSERT INTO asistencias (fecha, id_operador, asistencia, retardo, extra, turno, rol, motivos, semana, dia) ".
																"VALUES ('$fecha_ideal','$id_operador','','','','','','','$semanaRevisa', '$dias[$dia]')";
								//pDebug($qResumenAsistencias);
								  echo "A-".$id_operador." ";
									$rResumenAsistencias	=	mysql_query($qResumenAsistencias);	
							    }
                  catch(Exception $evt){
                    echo "Hubo un error: ". $evt->getMessage();

                  }
								}	 // end for r-263
							}	
						}  //end if r-252
					}	  //end if r-202
		

       echo "<br><br> Fin del proceso!!!" ;                 
?>

