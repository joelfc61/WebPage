<? 		

//include 'libs/valida_sesion.php';
//include 'libs/funciones.php'; 

$tabla	=	"operadores";
$indice	=	"id_operador";

$tabla2	=	"oper_maquina";
$indice2	=	"id_opr_maquina";

$campos	=	describeTabla($tabla,$indice);
$campos2	=	describeTabla($tabla2,$indice2);

for($r = 0; $r < 8 ; $r++){

$id_operador = 179 + $r;

		$qSemana	=	"SELECT semana, desde, hasta FROM pre_nomina_calificada WHERE semana = 7 GROUP BY semana";
		
		$rSemana	=	mysql_query($qSemana);
		$dSemana	=	mysql_fetch_assoc($rSemana);
		
		$desde		=	$dSemana['desde'];	
		$hasta		=	$dSemana['hasta'];
		$semana2	=	$dSemana['semana'];
		
			echo	$qNomina	=	"INSERT INTO pre_nomina_calificada (desde, hasta, por_asist,punt,prod, id_operador, semana)".
								" VALUES('$desde','$hasta','$por_asist','5','10','$id_operador','$semana2')";
				$rNomina	=	mysql_query($qNomina);
				$dias = array("J","V","S","D","L","M","Mi");
				
						echo '<br>';	
	
				for($dia = 0  ; $dia < 7 ; $dia++ ){
					
								$fecha	=	$desde;
								$nueva_fecha 	= explode("-", $fecha);
		
									$ano_avanza = $nueva_fecha[0];
									$mes_avanza = $nueva_fecha[1];
									$dia_avanza = $nueva_fecha[2]+$dia;
									$dia_ultimo	=	UltimoDia($ano_avanza,$mes_avanza);
									
									if($dia_avanza > $dia_ultimo){
										$dia_avanza	=	$dia_avanza - $dia_ultimo;
										$mes_avanza	=	$mes_avanza + 1;	
											if($mes_avanza > 12){
												$mes_avanza	=	1;
												$ano_avanza	=	$ano_avanza + 1;
											}
									} 
									
								 $fecha_ideal	=	$ano_avanza.'-'.$mes_avanza.'-'.$dia_avanza;	

					
			echo	$qResumenAsistencias	=	"INSERT INTO asistencias (fecha, id_operador, semana, dia) ".
											"VALUES ('$fecha_ideal','$id_operador','$semana2', '$dias[$dia]')";
											
			echo '<br>';	
				$rResumenAsistencias	=	mysql_query($qResumenAsistencias);	
			}
	}
?>