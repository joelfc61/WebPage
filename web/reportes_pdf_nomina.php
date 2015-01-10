<?php
define('FPDF_FONTPATH','libs/fpdf/font/');
require('libs/funciones.php');
require('libs/fpdf/fpdf.php');

define("ANCHO_MAXIMO",220);

define("FONDO_TITULO_TABLA", "#DDDDDD");
define("FONDO_TITULO_TABLA2", "#0A4662");

define("LETRA_TITULO_TABLA", "#FFFFFF");
define("LETRA_TITULO_TABLA2", "#000000");

define("LETRA_CAMPOS","#000000");
define("LETRA_TEXTO","#000000");
define("LETRA_FALTA","#FF0000");
define("LETRA_INCAPACIDAD","#0000FF");

class PDF extends FPDF
{
	function Header()
	{
		$this->Image('images/head_pdf.jpg',0,0,210);
		$this->Ln(1);
	}

	function Footer()
	{
		$this->SetY(-10);
	//	$this->Image('images/foot_pdf.jpg',0,273,210);
   		$this->SetFont('Arial','I',8);
		$dia = date('d');
		$mes	= 	array(	'',
								'Enero', 
								'Febrero', 
								'Marzo',
								'Abril',
								'Mayo',
								'Junio',
								'Julio',
								'Agosto',
								'Septiembre',
								'Octubre',
								'Noviembre',
								'Diciembre'
							); 	

		$anoActual = date('Y');
		$this->Cell('0','','Pagina no. '.$this->PageNo().'/{nb}',0,0,'R');	
		} 
		

		function firmas()
		{

			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(60, 7, "", 0, 1, 'C',0);			
				
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(40, 3, "", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(60, 3, "_________________________________________", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(60, 3, "_________________________________________", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(60, 3, "_________________________________________", 0, 1, 'C',0);
			
			
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(40, 3, "", 0, 0, 'C',0);			
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(60, 3, "JEFE DE PLANTA", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(60, 3,"GERENTE DE PLANTA", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(60, 3,"RECURSOS HUMANOS", 0, 1, 'C',0);

		}
		
				function firmasP()
		{

			$this->SetX(35);

			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(60, 25, "", 0, 1, 'C',0);
						
			$this->SetX(35);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(50, 3, "_____________________________________", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(50, 3, "_____________________________________", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(50, 3, "_____________________________________", 0, 1, 'C',0);
			
			
			$this->SetX(35);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(50, 3, "JEFE DE PLANTA", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(50, 3,"GERENTE DE PLANTA", 0, 0, 'C',0);
			$this->SetTextColor(LETRA_CAMPOS);
			$this->Cell(50, 3,"RECURSOS HUMANOS", 0, 1, 'C',0);

		}
		
		
		function prenomina_tabla($h,$semana,$dias,$dNumDia,$desde,$hasta,$area ){
		
	
	$this->SetFont("Arial","B",6);
	$this->SetTextColor(LETRA_CAMPOS);
	$this->Cell(75, $h, "Reporte de prenomina semana no. ".$semana.", del ".fecha_tabla($desde)." al ".fecha_tabla($hasta), 1, 0, 'L',0);			
	$this->SetFont("Arial","",6);
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(28, $h, "ASISTENCIA", 1, 0, 'C',1);
	$this->SetTextColor(LETRA_CAMPOS);
	$this->Cell(28, $h, "RETARDOS", 1, 0, 'C',0);
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(28, $h, "EXTRAS", 1, 0, 'C',1);
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(20, $h, "", 0, 0, 'C',0);
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(45, $h, "", 0, 0, 'C',0);	
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(45, $h, "", 0, 1, 'C',0);	
	


			   
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(10, $h, "CODIGO", 1, 0, 'C',1);	
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(65, $h, "NOMBRE", 1, 0, 'C',1);    
			   
			 for($z = 0; $z < 7; $z++){
             	$this->SetTextColor(LETRA_TITULO_TABLA);
				$this->Cell(4, $h, $dias[$z], 1, 0, 'C',1);
           } 
			 for($z = 0; $z < 7; $z++){
             	$this->SetTextColor(LETRA_CAMPOS);
				$this->Cell(4, $h, $dias[$z], 1, 0, 'C',0);
           } 
			 for($z = 0; $z < 7; $z++){
             	$this->SetTextColor(LETRA_TITULO_TABLA);
				$this->Cell(4, $h, $dias[$z], 1, 0, 'C',1);
           } 
		   
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(5, $h, "", 0, 0, 'C',0);
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(5, $h, "A", 1, 0, 'C',1);
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(5, $h, "P", 1, 0, 'C',1);
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(5, $h, "Pr.", 1, 0, 'C',1);	
			   
	$this->SetTextColor(LETRA_TITULO_TABLA);
	$this->Cell(140, $h, "", 0, 1, 'C',0);			   
			   			   

		
		}

}


if($_REQUEST['tipo'] == 'movimiento'){

$semana = 	$_REQUEST['semana_reportes'];
$anio_as = 	$_REQUEST['anio_nom'];

$qpre	=	"SELECT * FROM pre_nomina_calificada WHERE semana = $semana";
$rpre	=	mysql_query($qpre);
$dpre	=	mysql_fetch_assoc($rpre);

	if($_REQUEST['movimiento'] == 1 || $_REQUEST['movimiento'] == 3 ){
	$qMovimientoExtruder		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 1 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoImpresion 		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 3 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoBolseo			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 2 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoEmpaque			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 5 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoEmpaqueB		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 7 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoMantenimiento	=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 4 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoMantenimientoB	=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 6 AND almacen = 0 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoAlmacen			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND ( area = 8 OR almacen = 1 ) AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoAlmacenB		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 9 AND ( movimiento != 10 AND movimiento != 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	}
	
	if($_REQUEST['movimiento'] == 2){
	$qMovimientoExtruder		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 1 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoImpresion		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 3 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoBolseo			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 2 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoEmpaque			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 5 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoEmpaqueB		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 7 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoMantenimiento	=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 4 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoMantenimientoB	=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 6 AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoAlmacen			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND ( area = 8 OR almacen = 1 ) AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoAlmacenB		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 9 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	}
	

	$rMovimientoExtruder			=	mysql_query($qMovimientoExtruder);
	$rMovimientoImpresion			=	mysql_query($qMovimientoImpresion);
	$rMovimientoBolseo				=	mysql_query($qMovimientoBolseo);
	$rMovimientoEmpaque				=	mysql_query($qMovimientoEmpaque);
	$rMovimientoEmpaqueB			=	mysql_query($qMovimientoEmpaqueB);
	$rMovimientoMantenimiento		=	mysql_query($qMovimientoMantenimiento);
	$rMovimientoMantenimientoB		=	mysql_query($qMovimientoMantenimientoB);
	$rMovimientoAlmacen				=	mysql_query($qMovimientoAlmacen);
	$rMovimientoAlmacenB			=	mysql_query($qMovimientoAlmacenB);


	if($_REQUEST['extruder'] 	== 1)	{ $numExtruder			=	mysql_num_rows($rMovimientoExtruder); 		} 	else { $numExtruder 		= 0; }
	if($_REQUEST['impresion'] 	== 1) 	{ $numImpresion			=	mysql_num_rows($rMovimientoImpresion);		} 	else { $numImpresion 		= 0; }
	if($_REQUEST['bolseo'] 		== 1) 	{ $numBolseo			=	mysql_num_rows($rMovimientoBolseo);			} 	else { $numBolseo 			= 0; }
	if($_REQUEST['emp'] 		== 1) 	{ $numEmpaque			=	mysql_num_rows($rMovimientoEmpaque);		} 	else { $numEmpaque			= 0; }
	if($_REQUEST['empb'] 		== 1) 	{ $numEmpaqueB			=	mysql_num_rows($rMovimientoEmpaqueB);		} 	else { $numEmpaqueB 		= 0; }
	if($_REQUEST['mantto'] 		== 1) 	{ $numMantenimiento		=	mysql_num_rows($rMovimientoMantenimiento);	} 	else { $numMantenimiento	= 0; }
	if($_REQUEST['manttob'] 	== 1) 	{ $numMantenimientoB	=	mysql_num_rows($rMovimientoMantenimientoB);	} 	else { $numMantenimientoB 	= 0; }
	if($_REQUEST['alm'] 		== 1) 	{ $numAlmacen			=	mysql_num_rows($rMovimientoAlmacen);		} 	else { $numAlmacen 			= 0; }
	if($_REQUEST['almb']	 	== 1) 	{ $numAlmacenB			=	mysql_num_rows($rMovimientoAlmacenB);		} 	else { $numAlmacenB 		= 0; }

	
$medidaY = 200;
$pdf=&new PDF();
$pdf->FPDF('L','mm','LETTER');
$pdf->SetFont("Arial","",6);
$pdf->AliasNbPages();

$pdf->AddPage();

$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);
$pdf->SetY(10);
$pdf->SetFont("Arial","B",6);
$pdf->Cell(260, 5, "REPORTE DE MOVIMIENTOS DE PERSONAL", 0, 1, 'C');
$pdf->SetY(14);
$pdf->SetFont("Arial","B",6);
if($_REQUEST['movimiento'] == 1 || $_REQUEST['movimiento'] == 3){
		$pdf->Cell(260, 5	, "MOVIMIENTOS NORMALES", 0, 1, 'C');
}
if($_REQUEST['movimiento'] == 2){
		$pdf->Cell(260, 5	, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
}
$pdf->SetFont("Arial","",6);
$pdf->SetXY(190,10);
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(20, 4, "Semana No:", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(20, 4, $semana, 1, 1, 'C');
$pdf->SetXY(190,14);
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(20, 4, "Del", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(20, 4, fecha_tabla($dpre['desde']) , 1, 0,'C');
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(20, 4, "Al :", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(20, 4, fecha_tabla($dpre['hasta']), 1, 1, 'C');

if($numExtruder > 0 && $_REQUEST['extruder'] == 1){
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->Cell(260, 5, "EXTRUDER", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);


     for($a = 0; $dMovimientoExtruder = mysql_fetch_assoc($rMovimientoExtruder) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoExtruder['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);

			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoExtruder['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoExtruder['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoExtruder['dia'] 				!= "" 	&& $dMovimientoExtruder['dia'] 			!= "0000-00-00" && $dMovimientoExtruder['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoExtruder['dia']).", "; $b = $b +1; }
			if($dMovimientoExtruder['horas'] 			!= "" 	&& $dMovimientoExtruder['horas'] 		!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoExtruder['horas'].", "; $b = $b +1; }
			if($dMovimientoExtruder['cantidad'] 		!= "" 	&& $dMovimientoExtruder['cantidad'] 	!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoExtruder['cantidad'].", "; $b = $b +1; }
			if($dMovimientoExtruder['desde'] 			!= "" 	&& $dMovimientoExtruder['desde'] 		!= "0000-00-00" && ($dMovimientoExtruder['movimiento'] == 9 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .=  "Del: ". fecha_tabla($dMovimientoExtruder['desde'])." al "; $b = $b +1; }
			if($dMovimientoExtruder['hasta'] 			!= "" 	&& $dMovimientoExtruder['hasta'] 		!= "0000-00-00" && ($dMovimientoExtruder['movimiento'] == 9 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoExtruder['hasta']). ", "; $b = $b +1; }
			if($dMovimientoExtruder['numero_dias']	 	!= "" 	&& $dMovimientoExtruder['numero_dias'] 	!= "0" 			&& ($dMovimientoExtruder['movimiento'] == 9 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoExtruder['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoExtruder['desde_tiempo'] 	!= "" 	&& $dMovimientoExtruder['desde_tiempo'] != "00:00" 		&& ($dMovimientoExtruder['movimiento'] == 23 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoExtruder['desde_tiempo']; $motivo .= " al "; }
			if($dMovimientoExtruder['hasta_tiempo'] 	!= "" 	&& $dMovimientoExtruder['hasta_tiempo'] != "00:00" 		&& ($dMovimientoExtruder['movimiento'] == 23 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoExtruder['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoExtruder['turno'] 			!= "" 	&& $dMovimientoExtruder['turno'] 		!= 0 			&& ($dMovimientoExtruder['movimiento'] == 4 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoExtruder['turno']. ", "; $b = $b +1; }
			if($dMovimientoExtruder['movimientos.rol'] 	!= ""  	&& $dMovimientoExtruder['movimientos.rol'] 			!= 0  			&& ($dMovimientoExtruder['movimiento'] == 5 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoExtruder['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoExtruder['horario'] 			!= ""  	&& $dMovimientoExtruder['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoExtruder['horario'].", "; $b = $b +1; }
			if($dMovimientoExtruder['premio'] 			!= ""  	&& $dMovimientoExtruder['premio'] 		!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoExtruder['puntualidad'] 		!= ""  	&& $dMovimientoExtruder['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoExtruder['productividad'] 	!= ""  	&& $dMovimientoExtruder['productividad']!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoExtruder['no_premio'] 		!= ""  	&& $dMovimientoExtruder['no_premio'] 	!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoExtruder['id_operador2'] 	!= "" 	&& $dMovimientoExtruder['id_operador2'] != 0 ){ 
				$motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoExtruder['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoExtruder['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoExtruder['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoExtruder['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoExtruder['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);			
	}
	if( $_REQUEST['movimiento'] != 3 && $numImpresion < 1 && $numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1)	
		$pdf->firmas();
	else
		$pdf->Ln(2);		
}

if($numImpresion > 0 && $_REQUEST['impresion'] == 1 ){

	$imp 	= 	($numImpresion * 6) + 30 ;
	if($numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 && $_REQUEST['movimiento'] != 3)
	$comparaImp =	$pdf->GetY() + $imp+30;
	else
	$comparaImp =	$pdf->GetY() + $imp;
	if($comparaImp >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}

	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "IMPRESION", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);


						
     for($a = 0; $dMovimientoImpresion = mysql_fetch_assoc($rMovimientoImpresion) ;$a++){
				
		$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoImpresion['id_operador2']."";
		$rE2	=	mysql_query($qE2);
		$dE2	=	mysql_fetch_assoc($rE2);
		
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoImpresion['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoImpresion['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoImpresion['dia'] 			!= "" 	&& $dMovimientoImpresion['dia'] 			!= "0000-00-00" && $dMovimientoImpresion['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoImpresion['dia']).", "; $b = $b +1; }
			if($dMovimientoImpresion['horas'] 			!= "" 	&& $dMovimientoImpresion['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoImpresion['horas'].", "; $b = $b +1; }
			if($dMovimientoImpresion['cantidad'] 		!= "" 	&& $dMovimientoImpresion['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoImpresion['cantidad'].", "; $b = $b +1; }
			if($dMovimientoImpresion['desde'] 			!= "" 	&& $dMovimientoImpresion['desde'] 			!= "0000-00-00" && ($dMovimientoImpresion['movimiento'] == 9 || $dMovimientoImpresion['movimiento'] == 8 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoImpresion['desde'])." al "; $b = $b +1; }
			if($dMovimientoImpresion['hasta'] 			!= "" 	&& $dMovimientoImpresion['hasta'] 			!= "0000-00-00" && ($dMovimientoImpresion['movimiento'] == 9 || $dMovimientoImpresion['movimiento'] == 8  || $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .=	 fecha_tabla($dMovimientoImpresion['hasta']).", "; $b = $b +1;}
			if($dMovimientoImpresion['numero_dias']	 	!= "" 	&& $dMovimientoImpresion['numero_dias'] 	!= "0" 			&& ($dMovimientoImpresion['movimiento'] == 9 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoImpresion['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoImpresion['desde_tiempo'] 	!= "" 	&& $dMovimientoImpresion['desde_tiempo']	!= "00:00" 		&& ($dMovimientoImpresion['movimiento'] == 23 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoImpresion['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoImpresion['hasta_tiempo'] 	!= "" 	&& $dMovimientoImpresion['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoImpresion['movimiento'] == 23 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoImpresion['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoImpresion['turno'] 			!= "" 	&& $dMovimientoImpresion['turno'] 			!= 0 			&& ($dMovimientoImpresion['movimiento'] == 4 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoImpresion['turno']. ", "; $b = $b +1; }
			if($dMovimientoImpresion['movimientos.rol'] 			!= ""  	&& $dMovimientoImpresion['movimientos.rol'] 			!= 0  			&& ($dMovimientoImpresion['movimiento'] == 5 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoImpresion['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoImpresion['horario'] 		!= ""  	&& $dMovimientoImpresion['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoImpresion['horario'].", "; $b = $b +1; }
			if($dMovimientoImpresion['premio'] 			!= ""  	&& $dMovimientoImpresion['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoImpresion['puntualidad'] 	!= ""  	&& $dMovimientoImpresion['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoImpresion['productividad'] 	!= ""  	&& $dMovimientoImpresion['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoImpresion['no_premio'] 		!= ""  	&& $dMovimientoImpresion['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoImpresion['id_operador2'] 	!= "" 	&& $dMovimientoImpresion['id_operador2'] 	!= 0 ){ 
				$motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoImpresion['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoImpresion['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoImpresion['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoImpresion['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoImpresion['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);
		}
		
	if( $_REQUEST['movimiento'] != 3 && $numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1)	
		$pdf->firmas();	
	else
		$pdf->Ln(2);
	
}


if($numBolseo > 0 &&  $_REQUEST['bolseo'] == 1 ){

	$bol 	= 	($numBolseo * 6) + 30 ;
	if( $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 && $_REQUEST['movimiento'] != 3 )
	$compara =	$pdf->GetY() + $bol + 30;
	else
	$compara =	$pdf->GetY() + $bol;
	if($compara >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(18);
	}
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "BOLSEO", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoBolseo = mysql_fetch_assoc($rMovimientoBolseo) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoBolseo['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoBolseo['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoBolseo['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoBolseo['dia'] 			!= "" 	&& $dMovimientoBolseo['dia'] 			!= "0000-00-00" && $dMovimientoBolseo['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoBolseo['dia']).", "; $b = $b +1; }
			if($dMovimientoBolseo['horas'] 			!= "" 	&& $dMovimientoBolseo['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoBolseo['horas'].", "; $b = $b +1; }
			if($dMovimientoBolseo['cantidad'] 		!= "" 	&& $dMovimientoBolseo['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoBolseo['cantidad'].", "; $b = $b +1; }
			if($dMovimientoBolseo['desde'] 			!= "" 	&& $dMovimientoBolseo['desde'] 			!= "0000-00-00" && ($dMovimientoBolseo['movimiento'] == 9 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoBolseo['desde'])." al "; $b = $b +1; }
			if($dMovimientoBolseo['hasta'] 			!= "" 	&& $dMovimientoBolseo['hasta'] 			!= "0000-00-00" && ($dMovimientoBolseo['movimiento'] == 9 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoBolseo['hasta']).", "; $b = $b +1;}
			if($dMovimientoBolseo['numero_dias']	!= "" 	&& $dMovimientoBolseo['numero_dias'] 	!= "0" 			&& ($dMovimientoBolseo['movimiento'] == 9 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoBolseo['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoBolseo['desde_tiempo'] 	!= "" 	&& $dMovimientoBolseo['desde_tiempo']	!= "00:00" 		&& ($dMovimientoBolseo['movimiento'] == 23 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoBolseo['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoBolseo['hasta_tiempo'] 	!= "" 	&& $dMovimientoBolseo['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoBolseo['movimiento'] == 23 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoBolseo['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoBolseo['turno'] 			!= "" 	&& $dMovimientoBolseo['turno'] 			!= 0 			&& ($dMovimientoBolseo['movimiento'] == 4 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoBolseo['turno']. ", "; $b = $b +1; }
			if($dMovimientoBolseo['movimientos.rol'] 	!= ""  	&& $dMovimientoBolseo['movimientos.rol'] 			!= 0  			&& ($dMovimientoBolseo['movimiento'] == 5 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoBolseo['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoBolseo['horario'] 		!= ""  	&& $dMovimientoBolseo['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoBolseo['horario'].", "; $b = $b +1; }
			if($dMovimientoBolseo['premio'] 		!= ""  	&& $dMovimientoBolseo['premio'] 		!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoBolseo['puntualidad'] 	!= ""  	&& $dMovimientoBolseo['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoBolseo['productividad'] 	!= ""  	&& $dMovimientoBolseo['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoBolseo['no_premio'] 		!= ""  	&& $dMovimientoBolseo['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoBolseo['id_operador2'] 	!= "" 	&& $dMovimientoBolseo['id_operador2'] 	!= 0 ){ 
			$motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoBolseo['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoBolseo['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoBolseo['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoBolseo['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoBolseo['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);		

	if( $_REQUEST['movimiento'] != 3 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1)	
	{
		if($pdf->GetY() >= $medidaY){
	
		$pdf->AddPage();
		$pdf->SetY(20);
		$pdf->SetFillColor(FONDO_TITULO_TABLA2);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);
		$pdf->Cell(260, 5, "BOLSEO", 0, 1, 'C');
		
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
		$pdf->SetFillColor(FONDO_TITULO_TABLA);
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
	
			
		}
			
	}	
		
	}
	
	if( $_REQUEST['movimiento'] != 3 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1)	
		$pdf->firmas();	
	else
		$pdf->Ln(2);
}

if($numEmpaque > 0 && $_REQUEST['emp'] == 1 ){
	
	$emp 	= 	($numEmpaque * 6) + 30 ;
	if($numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1   && $numAlmacen < 1 && $numAlmacenB < 1 && $_REQUEST['movimiento'] != 3)
	$comparaEmp =	$pdf->GetY() + $emp+30;
	else
	$comparaEmp =	$pdf->GetY() + $emp;
	if($comparaEmp >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "EMPAQUE", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoEmpaque = mysql_fetch_assoc($rMovimientoEmpaque) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoEmpaque['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoEmpaque['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoEmpaque['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['dia'] 				!= "" 	&& $dMovimientoEmpaque['dia'] 			!= "0000-00-00" && $dMovimientoEmpaque['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoEmpaque['dia']).", "; $b = $b +1; }
			if($dMovimientoEmpaque['horas'] 			!= "" 	&& $dMovimientoEmpaque['horas'] 		!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoEmpaque['horas'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['cantidad'] 			!= "" 	&& $dMovimientoEmpaque['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoEmpaque['cantidad'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['desde'] 			!= "" 	&& $dMovimientoEmpaque['desde'] 		!= "0000-00-00" && ($dMovimientoEmpaque['movimiento'] == 9 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoEmpaque['desde'])." al "; $b = $b +1; }
			if($dMovimientoEmpaque['hasta'] 			!= "" 	&& $dMovimientoEmpaque['hasta'] 		!= "0000-00-00" && ($dMovimientoEmpaque['movimiento'] == 9 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoEmpaque['hasta']).", "; $b = $b +1;}
			if($dMovimientoEmpaque['numero_dias']		!= "" 	&& $dMovimientoEmpaque['numero_dias'] 	!= "0" 			&& ($dMovimientoEmpaque['movimiento'] == 9 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoEmpaque['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoEmpaque['desde_tiempo'] 		!= "" 	&& $dMovimientoEmpaque['desde_tiempo']	!= "00:00" 		&& ($dMovimientoEmpaque['movimiento'] == 23 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoEmpaque['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoEmpaque['hasta_tiempo'] 		!= "" 	&& $dMovimientoEmpaque['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoEmpaque['movimiento'] == 23 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoEmpaque['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoEmpaque['turno'] 			!= "" 	&& $dMovimientoEmpaque['turno'] 		!= 0 			&& ($dMovimientoEmpaque['movimiento'] == 4 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoEmpaque['turno']. ", "; $b = $b +1; }
			if($dMovimientoEmpaque['movimientos.rol'] 				!= ""  	&& $dMovimientoEmpaque['movimientos.rol'] 			!= 0  			&& ($dMovimientoEmpaque['movimiento'] == 5 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoEmpaque['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['horario'] 			!= ""  	&& $dMovimientoEmpaque['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoEmpaque['horario'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['premio'] 			!= ""  	&& $dMovimientoEmpaque['premio'] 		!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoEmpaque['puntualidad'] 		!= ""  	&& $dMovimientoEmpaque['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoEmpaque['productividad'] 	!= ""  	&& $dMovimientoEmpaque['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoEmpaque['no_premio'] 		!= ""  	&& $dMovimientoEmpaque['no_premio'] 	!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoEmpaque['id_operador2'] 		!= "" 	&& $dMovimientoEmpaque['id_operador2'] 	!= 0 ){ 
			$motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoEmpaque['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoEmpaque['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoEmpaque['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoEmpaque['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoEmpaque['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	
		}
	if( $_REQUEST['movimiento'] != 3 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1)	
	$pdf->firmas();	
	else
		$pdf->Ln(2);	
	
}

if($numEmpaqueB > 0 && $_REQUEST['empb'] == 1 ){

	$empb 	= 	($numEmpaqueB * 6) + 30;
	if($numMantenimiento < 1 && $numMantenimientoB < 1  && $numAlmacen < 1 && $numAlmacenB < 1 && $_REQUEST['movimiento'] != 3)
	$comparaEmpb =	$pdf->GetY() + $empb + 30;
	else
	$comparaEmpb =	$pdf->GetY() + $empb;
	if($comparaEmpb >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}


	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "EMPAQUE B", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoEmpaqueB = mysql_fetch_assoc($rMovimientoEmpaqueB) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoEmpaqueB['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoEmpaqueB['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoEmpaqueB['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['dia'] 			!= "" 	&& $dMovimientoEmpaqueB['dia'] 			!= "0000-00-00" && $dMovimientoEmpaqueB['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoEmpaqueB['dia']).", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['horas'] 			!= "" 	&& $dMovimientoEmpaqueB['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoEmpaqueB['horas'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['cantidad'] 		!= "" 	&& $dMovimientoEmpaqueB['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoEmpaqueB['cantidad'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['desde'] 			!= "" 	&& $dMovimientoEmpaqueB['desde'] 			!= "0000-00-00" && ($dMovimientoEmpaqueB['movimiento'] == 9 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoEmpaqueB['desde'])." al "; $b = $b +1; }
			if($dMovimientoEmpaqueB['hasta'] 			!= "" 	&& $dMovimientoEmpaqueB['hasta'] 			!= "0000-00-00" && ($dMovimientoEmpaqueB['movimiento'] == 9 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoEmpaqueB['hasta']).", "; $b = $b +1;}
			if($dMovimientoEmpaqueB['numero_dias']		!= "" 	&& $dMovimientoEmpaqueB['numero_dias'] 	 	!= "0" 			&& ($dMovimientoEmpaqueB['movimiento'] == 9 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoEmpaqueB['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoEmpaqueB['desde_tiempo'] 	!= "" 	&& $dMovimientoEmpaqueB['desde_tiempo']	  	!= "00:00" 		&& ($dMovimientoEmpaqueB['movimiento'] == 23 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoEmpaqueB['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoEmpaqueB['hasta_tiempo'] 	!= "" 	&& $dMovimientoEmpaqueB['hasta_tiempo']	 	!= "00:00" 		&& ($dMovimientoEmpaqueB['movimiento'] == 23 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoEmpaqueB['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['turno'] 			!= "" 	&& $dMovimientoEmpaqueB['turno'] 			!= 0 			&& ($dMovimientoEmpaqueB['movimiento'] == 4 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoEmpaqueB['turno']. ", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['movimientos.rol'] 	!= ""  	&& $dMovimientoEmpaqueB['movimientos.rol'] 	!= 0  			&& ($dMovimientoEmpaqueB['movimiento'] == 5 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoEmpaqueB['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['horario'] 			!= ""  	&& $dMovimientoEmpaqueB['horario'] 			!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoEmpaqueB['horario'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['premio'] 			!= ""  	&& $dMovimientoEmpaqueB['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoEmpaqueB['puntualidad'] 		!= ""  	&& $dMovimientoEmpaqueB['puntualidad'] 		!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoEmpaqueB['productividad'] 	!= ""  	&& $dMovimientoEmpaqueB['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoEmpaqueB['no_premio'] 		!= ""  	&& $dMovimientoEmpaqueB['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoEmpaqueB['id_operador2'] 	!= "" 	&& $dMovimientoEmpaqueB['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoEmpaqueB['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoEmpaqueB['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoEmpaqueB['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoEmpaqueB['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoEmpaqueB['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	
		}
	if( $_REQUEST['movimiento'] != 3  && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1)
		$pdf->firmas();		
	else
		$pdf->Ln(2);		
}



if($numMantenimiento > 0 && $_REQUEST['mantto'] == 1 ){

	$mantto 	= 	($numMantenimiento * 6) + 30;
	if( $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 && $_REQUEST['movimiento'] != 3 ){
	$comparaMantto =	$pdf->GetY() + $mantto;
	}
	else {
	$comparaMantto =	$pdf->GetY() + $mantto;
	}
	if($comparaMantto >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}

	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "MANTENIMIENTO", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoMantenimiento = mysql_fetch_assoc($rMovimientoMantenimiento) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoMantenimiento['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoMantenimiento['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoMantenimiento['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['dia'] 			!= "" 	&& $dMovimientoMantenimiento['dia'] 			!= "0000-00-00" && $dMovimientoMantenimiento['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoMantenimiento['dia']).", "; $b = $b +1; }
			if($dMovimientoMantenimiento['horas'] 			!= "" 	&& $dMovimientoMantenimiento['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoMantenimiento['horas'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['cantidad'] 		!= "" 	&& $dMovimientoMantenimiento['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoMantenimiento['cantidad'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['desde'] 			!= "" 	&& $dMovimientoMantenimiento['desde'] 			!= "0000-00-00" && ($dMovimientoMantenimiento['movimiento'] == 9 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoMantenimiento['desde'])." al "; $b = $b +1; }
			if($dMovimientoMantenimiento['hasta'] 			!= "" 	&& $dMovimientoMantenimiento['hasta'] 			!= "0000-00-00" && ($dMovimientoMantenimiento['movimiento'] == 9 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoMantenimiento['hasta']).", "; $b = $b +1;}
			if($dMovimientoMantenimiento['numero_dias'] 	!= "" 	&& $dMovimientoMantenimiento['numero_dias'] 	!= "0" 			&& ($dMovimientoMantenimiento['movimiento'] == 9 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoMantenimiento['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoMantenimiento['desde_tiempo'] 	!= "" 	&& $dMovimientoMantenimiento['desde_tiempo']	!= "00:00" 		&& ($dMovimientoMantenimiento['movimiento'] == 23 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoMantenimiento['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoMantenimiento['hasta_tiempo'] 	!= "" 	&& $dMovimientoMantenimiento['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoMantenimiento['movimiento'] == 23 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoMantenimiento['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoMantenimiento['turno'] 			!= "" 	&& $dMovimientoMantenimiento['turno'] 			!= 0 			&& ($dMovimientoMantenimiento['movimiento'] == 4 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoMantenimiento['turno']. ", "; $b = $b +1; }
			if($dMovimientoMantenimiento['movimientos.rol'] != ""  	&& $dMovimientoMantenimiento['movimientos.rol'] 			!= 0  			&& ($dMovimientoMantenimiento['movimiento'] == 5 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoMantenimiento['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['horario'] 		!= ""  	&& $dMovimientoMantenimiento['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoMantenimiento['horario'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['premio'] 			!= ""  	&& $dMovimientoMantenimiento['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoMantenimiento['puntualidad'] 	!= ""  	&& $dMovimientoMantenimiento['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoMantenimiento['productividad'] 	!= ""  	&& $dMovimientoMantenimiento['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoMantenimiento['no_premio'] 		!= ""  	&& $dMovimientoMantenimiento['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoMantenimiento['id_operador2'] 	!= "" 	&& $dMovimientoMantenimiento['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoMantenimiento['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoMantenimiento['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoMantenimiento['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoMantenimiento['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoMantenimiento['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	
		}
	$pdf->Ln(2);
	if( $_REQUEST['movimiento'] != 3  &&  $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1)
		$pdf->firmas();	
 
}

if($numMantenimientoB > 0 && $_REQUEST['manttob'] == 1 ){

	$manttob 	= 	($numMantenimientoB * 6) + 40 ;
	if( $numAlmacen < 1 && $numAlmacenB < 1 && $_REQUEST['movimiento'] != 3)
	$comparaManttob =	$pdf->GetY() + $manttob + 30;
	else
	$comparaManttob =	$pdf->GetY() + $manttob;
	if($comparaManttob >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "MANTENIMIENTO B" , 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoMantenimientoB = mysql_fetch_assoc($rMovimientoMantenimientoB) ;$a++){
				
					$qE2    =	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoMantenimientoB['id_operador2']."";
					$rE2	=	mysql_query($qE2);
					$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoMantenimientoB['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoMantenimientoB['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['dia'] 			!= "" 	&& $dMovimientoMantenimientoB['dia'] 			!= "0000-00-00" && $dMovimientoMantenimientoB['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoMantenimientoB['dia']).", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['horas'] 			!= "" 	&& $dMovimientoMantenimientoB['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoMantenimientoB['horas'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['cantidad'] 		!= "" 	&& $dMovimientoMantenimientoB['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoMantenimientoB['cantidad'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['desde'] 			!= "" 	&& $dMovimientoMantenimientoB['desde'] 			!= "0000-00-00" && ($dMovimientoMantenimientoB['movimiento'] == 9 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoMantenimientoB['desde'])." al "; $b = $b +1; }
			if($dMovimientoMantenimientoB['hasta'] 			!= "" 	&& $dMovimientoMantenimientoB['hasta'] 			!= "0000-00-00" && ($dMovimientoMantenimientoB['movimiento'] == 9 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoMantenimientoB['hasta']).", "; $b = $b +1;}
			if($dMovimientoMantenimientoB['numero_dias'] 	!= "" 	&& $dMovimientoMantenimientoB['numero_dias'] 	!= "0" 			&& ($dMovimientoMantenimientoB['movimiento'] == 9 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoMantenimientoB['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoMantenimientoB['desde_tiempo'] 	!= "" 	&& $dMovimientoMantenimientoB['desde_tiempo']	!= "00:00" 		&& ($dMovimientoMantenimientoB['movimiento'] == 23 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoMantenimientoB['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoMantenimientoB['hasta_tiempo'] 	!= "" 	&& $dMovimientoMantenimientoB['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoMantenimientoB['movimiento'] == 23 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoMantenimientoB['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['turno'] 			!= "" 	&& $dMovimientoMantenimientoB['turno'] 			!= 0 			&& ($dMovimientoMantenimientoB['movimiento'] == 4 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoMantenimientoB['turno']. ", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['movimientos.rol'] 			!= ""  	&& $dMovimientoMantenimientoB['movimientos.rol'] 			!= 0  			&& ($dMovimientoMantenimientoB['movimiento'] == 5 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoMantenimientoB['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['horario'] 		!= ""  	&& $dMovimientoMantenimientoB['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoMantenimientoB['horario'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['premio'] 		!= ""  	&& $dMovimientoMantenimientoB['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoMantenimientoB['puntualidad'] 	!= ""  	&& $dMovimientoMantenimientoB['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoMantenimientoB['productividad'] 	!= ""  	&& $dMovimientoMantenimientoB['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoMantenimientoB['no_premio'] 		!= ""  	&& $dMovimientoMantenimientoB['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoMantenimientoB['id_operador2'] 	!= "" 	&& $dMovimientoMantenimientoB['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoMantenimientoB['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoMantenimientoB['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoMantenimientoB['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoMantenimientoB['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoMantenimientoB['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	
		}
		
	if( $_REQUEST['movimiento'] != 3  &&  $numAlmacen < 1 && $numAlmacenB < 1  )
		$pdf->firmas();	
	else
		$pdf->Ln(2);		
}

if($numAlmacen > 0 && $_REQUEST['alm'] == 1 ){

	$alm 	= 	($numAlmacen * 6) + 30 ;
	if( $numAlmacenB < 1 && $_REQUEST['movimiento'] != 3)
	$comparaAlm =	$pdf->GetY() + $alm+30;
	else
	$comparaAlm =	$pdf->GetY() + $alm;
	if($comparaAlm >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "ALMACEN", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoAlmacen = mysql_fetch_assoc($rMovimientoAlmacen) ;$a++){
				
					$qE2    =	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoAlmacen['id_operador2']."";
					$rE2	=	mysql_query($qE2);
					$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoAlmacen['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoAlmacen['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['dia'] 			!= "" 	&& $dMovimientoAlmacen['dia'] 			!= "0000-00-00" && $dMovimientoAlmacen['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoAlmacen['dia']).", "; $b = $b +1; }
			if($dMovimientoAlmacen['horas'] 			!= "" 	&& $dMovimientoAlmacen['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoAlmacen['horas'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['cantidad'] 		!= "" 	&& $dMovimientoAlmacen['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoAlmacen['cantidad'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['desde'] 			!= "" 	&& $dMovimientoAlmacen['desde'] 			!= "0000-00-00" && ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoAlmacen['desde'])." al "; $b = $b +1; }
			if($dMovimientoAlmacen['hasta'] 			!= "" 	&& $dMovimientoAlmacen['hasta'] 			!= "0000-00-00" && ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoAlmacen['hasta']).", "; $b = $b +1;}
			if($dMovimientoAlmacen['numero_dias'] 	!= "" 	&& $dMovimientoAlmacen['numero_dias'] 	!= "0" 			&& ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoAlmacen['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoAlmacen['desde_tiempo'] 	!= "" 	&& $dMovimientoAlmacen['desde_tiempo']	!= "00:00" 		&& ($dMovimientoAlmacen['movimiento'] == 23 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoAlmacen['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoAlmacen['hasta_tiempo'] 	!= "" 	&& $dMovimientoAlmacen['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoAlmacen['movimiento'] == 23 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoAlmacen['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoAlmacen['turno'] 			!= "" 	&& $dMovimientoAlmacen['turno'] 			!= 0 			&& ($dMovimientoAlmacen['movimiento'] == 4 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoAlmacen['turno']. ", "; $b = $b +1; }
			if($dMovimientoAlmacen['movimientos.rol'] 			!= ""  	&& $dMovimientoAlmacen['movimientos.rol'] 			!= 0  			&& ($dMovimientoAlmacen['movimiento'] == 5 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoAlmacen['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['horario'] 		!= ""  	&& $dMovimientoAlmacen['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoAlmacen['horario'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['premio'] 		!= ""  	&& $dMovimientoAlmacen['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoAlmacen['puntualidad'] 	!= ""  	&& $dMovimientoAlmacen['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoAlmacen['productividad'] 	!= ""  	&& $dMovimientoAlmacen['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoAlmacen['no_premio'] 		!= ""  	&& $dMovimientoAlmacen['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoAlmacen['id_operador2'] 	!= "" 	&& $dMovimientoAlmacen['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoAlmacen['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoAlmacen['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoAlmacen['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoAlmacen['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoAlmacen['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	}
		
	if( $_REQUEST['movimiento'] != 3  && $numAlmacenB < 1 )
		$pdf->firmas();	
	else
		$pdf->Ln(2);	
}
	
if($numAlmacenB > 0 && $_REQUEST['almb'] == 1 ){

	$almb 	= 	($numAlmacenB * 6) + 30 ;
	$comparaAlmb =	$pdf->GetY() + $almb;
	if($comparaAlmb >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "ALMACEN B", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoAlmacenB = mysql_fetch_assoc($rMovimientoAlmacenB) ;$a++){
				
					$qE2    =	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoAlmacenB['id_operador2']."";
					$rE2	=	mysql_query($qE2);
					$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoAlmacenB['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoAlmacenB['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['dia'] 			!= "" 	&& $dMovimientoAlmacenB['dia'] 			!= "0000-00-00" && $dMovimientoAlmacenB['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoAlmacenB['dia']).", "; $b = $b +1; }
			if($dMovimientoAlmacenB['horas'] 			!= "" 	&& $dMovimientoAlmacenB['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoAlmacenB['horas'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['cantidad'] 		!= "" 	&& $dMovimientoAlmacenB['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoAlmacenB['cantidad'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['desde'] 			!= "" 	&& $dMovimientoAlmacenB['desde'] 			!= "0000-00-00" && ($dMovimientoAlmacenB['movimiento'] == 9 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoAlmacenB['desde'])." al "; $b = $b +1; }
			if($dMovimientoAlmacenB['hasta'] 			!= "" 	&& $dMovimientoAlmacenB['hasta'] 			!= "0000-00-00" && ($dMovimientoAlmacenB['movimiento'] == 9 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoAlmacenB['hasta']).", "; $b = $b +1;}
			if($dMovimientoAlmacenB['numero_dias'] 	!= "" 	&& $dMovimientoAlmacenB['numero_dias'] 	!= "0" 			&& ($dMovimientoAlmacenB['movimiento'] == 9 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoAlmacenB['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoAlmacenB['desde_tiempo'] 	!= "" 	&& $dMovimientoAlmacenB['desde_tiempo']	!= "00:00" 		&& ($dMovimientoAlmacenB['movimiento'] == 23 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoAlmacenB['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoAlmacenB['hasta_tiempo'] 	!= "" 	&& $dMovimientoAlmacenB['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoAlmacenB['movimiento'] == 23 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoAlmacenB['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoAlmacenB['turno'] 			!= "" 	&& $dMovimientoAlmacenB['turno'] 			!= 0 			&& ($dMovimientoAlmacenB['movimiento'] == 4 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoAlmacenB['turno']. ", "; $b = $b +1; }
			if($dMovimientoAlmacenB['movimientos.rol'] 			!= ""  	&& $dMovimientoAlmacenB['movimientos.rol'] 			!= 0  			&& ($dMovimientoAlmacenB['movimiento'] == 5 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoAlmacenB['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['horario'] 		!= ""  	&& $dMovimientoAlmacenB['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoAlmacenB['horario'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['premio'] 		!= ""  	&& $dMovimientoAlmacenB['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoAlmacenB['puntualidad'] 	!= ""  	&& $dMovimientoAlmacenB['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoAlmacenB['productividad'] 	!= ""  	&& $dMovimientoAlmacenB['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoAlmacenB['no_premio'] 		!= ""  	&& $dMovimientoAlmacenB['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoAlmacenB['id_operador2'] 	!= "" 	&& $dMovimientoAlmacenB['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoAlmacenB['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoAlmacenB['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoAlmacenB['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoAlmacenB['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoAlmacenB['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	}
		
	if( $_REQUEST['movimiento'] != 3){
		$pdf->firmas();	
	}
		else{
		$pdf->Ln(2);
	}
}
	

if($_REQUEST['movimiento'] == 3){


	$qMovimientoExtruder		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 1  AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoImpresion		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 3  AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoBolseo			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 2  AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoEmpaque			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 5  AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoEmpaqueB		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 7  AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoMantenimiento	=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 4  AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoMantenimientoB	=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 6  AND almacen = 0 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoAlmacen			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND ( area = 8 OR almacen = 1 ) AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$qMovimientoAlmacenB		=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE semana = $semana AND YEAR(fecha) = '$anio_as' AND area = 9 AND ( movimiento = 10 OR movimiento = 11) AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	
	$rMovimientoExtruder			=	mysql_query($qMovimientoExtruder);
	$rMovimientoImpresion			=	mysql_query($qMovimientoImpresion);
	$rMovimientoBolseo				=	mysql_query($qMovimientoBolseo);
	$rMovimientoEmpaque				=	mysql_query($qMovimientoEmpaque);
	$rMovimientoEmpaqueB			=	mysql_query($qMovimientoEmpaqueB);
	$rMovimientoMantenimiento		=	mysql_query($qMovimientoMantenimiento);
	$rMovimientoMantenimientoB		=	mysql_query($qMovimientoMantenimientoB);
	$rMovimientoAlmacen				=	mysql_query($qMovimientoAlmacen);
	$rMovimientoAlmacenB			=	mysql_query($qMovimientoAlmacenB);

	if($_REQUEST['extruder'] 	== 1)	{ $numExtruder			=	mysql_num_rows($rMovimientoExtruder); 		} 	else { $numExtruder 		= 0; }
	if($_REQUEST['impresion'] 	== 1) 	{ $numImpresion			=	mysql_num_rows($rMovimientoImpresion);		} 	else { $numImpresion 		= 0; }
	if($_REQUEST['bolseo'] 		== 1) 	{ $numBolseo			=	mysql_num_rows($rMovimientoBolseo);			} 	else { $numBolseo 			= 0; }
	if($_REQUEST['emp'] 		== 1) 	{ $numEmpaque			=	mysql_num_rows($rMovimientoEmpaque);		} 	else { $numEmpaque			= 0; }
	if($_REQUEST['empb'] 		== 1) 	{ $numEmpaqueB			=	mysql_num_rows($rMovimientoEmpaqueB);		} 	else { $numEmpaqueB 		= 0; }
	if($_REQUEST['mantto'] 		== 1) 	{ $numMantenimiento		=	mysql_num_rows($rMovimientoMantenimiento);	} 	else { $numMantenimiento	= 0; }
	if($_REQUEST['manttob'] 	== 1) 	{ $numMantenimientoB	=	mysql_num_rows($rMovimientoMantenimientoB);	} 	else { $numMantenimientoB 	= 0; }
	if($_REQUEST['alm'] 		== 1) 	{ $numAlmacen			=	mysql_num_rows($rMovimientoAlmacen);		} 	else { $numAlmacen 			= 0; }
	if($_REQUEST['almb']	 	== 1) 	{ $numAlmacenB			=	mysql_num_rows($rMovimientoAlmacenB);		} 	else { $numAlmacenB 		= 0; }

if($numExtruder < 1 && $numImpresion < 1 && $numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1  && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 )
{	
	$pdf->firmas();
}

if($numExtruder > 0 && $_REQUEST['extruder'] == 1){

	$ext 	= 	($numExtruder * 6) + 30  + 3;
	$comparaext =	$pdf->GetY() + $ext;
	if($comparaext >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}


	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Ln(3);

				$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->Cell(260, 5, "EXTRUDER", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);


     for($a = 0; $dMovimientoExtruder = mysql_fetch_assoc($rMovimientoExtruder) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoExtruder['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);

			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoExtruder['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoExtruder['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoExtruder['dia'] 				!= "" 	&& $dMovimientoExtruder['dia'] 			!= "0000-00-00" && $dMovimientoExtruder['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoExtruder['dia']).", "; $b = $b +1; }
			if($dMovimientoExtruder['horas'] 			!= "" 	&& $dMovimientoExtruder['horas'] 		!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoExtruder['horas'].", "; $b = $b +1; }
			if($dMovimientoExtruder['cantidad'] 		!= "" 	&& $dMovimientoExtruder['cantidad'] 	!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoExtruder['cantidad'].", "; $b = $b +1; }
			if($dMovimientoExtruder['desde'] 			!= "" 	&& $dMovimientoExtruder['desde'] 		!= "0000-00-00" && ($dMovimientoExtruder['movimiento'] == 9 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .=  "Del: ". fecha_tabla($dMovimientoExtruder['desde'])." al "; $b = $b +1; }
			if($dMovimientoExtruder['hasta'] 			!= "" 	&& $dMovimientoExtruder['hasta'] 		!= "0000-00-00" && ($dMovimientoExtruder['movimiento'] == 9 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoExtruder['hasta']). ", "; $b = $b +1; }
			if($dMovimientoExtruder['numero_dias']	 	!= "" 	&& $dMovimientoExtruder['numero_dias'] 	!= "0" 			&& ($dMovimientoExtruder['movimiento'] == 9 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoExtruder['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoExtruder['desde_tiempo'] 	!= "" 	&& $dMovimientoExtruder['desde_tiempo'] != "00:00" 		&& ($dMovimientoExtruder['movimiento'] == 23 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoExtruder['desde_tiempo']; $motivo .= " al "; }
			if($dMovimientoExtruder['hasta_tiempo'] 	!= "" 	&& $dMovimientoExtruder['hasta_tiempo'] != "00:00" 		&& ($dMovimientoExtruder['movimiento'] == 23 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoExtruder['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoExtruder['turno'] 			!= "" 	&& $dMovimientoExtruder['turno'] 		!= 0 			&& ($dMovimientoExtruder['movimiento'] == 4 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoExtruder['turno']. ", "; $b = $b +1; }
			if($dMovimientoExtruder['movimientos.rol'] 	!= ""  	&& $dMovimientoExtruder['movimientos.rol'] 			!= 0  			&& ($dMovimientoExtruder['movimiento'] == 5 	|| $dMovimientoExtruder['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoExtruder['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoExtruder['horario'] 			!= ""  	&& $dMovimientoExtruder['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoExtruder['horario'].", "; $b = $b +1; }
			if($dMovimientoExtruder['premio'] 			!= ""  	&& $dMovimientoExtruder['premio'] 		!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoExtruder['puntualidad'] 		!= ""  	&& $dMovimientoExtruder['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoExtruder['productividad'] 	!= ""  	&& $dMovimientoExtruder['productividad']!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoExtruder['no_premio'] 		!= ""  	&& $dMovimientoExtruder['no_premio'] 	!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoExtruder['id_operador2'] 	!= "" 	&& $dMovimientoExtruder['id_operador2'] != 0 ){ 
				$motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoExtruder['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoExtruder['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoExtruder['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoExtruder['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoExtruder['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	}
	$pdf->Ln(2);
if($numImpresion < 1 && $numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1  && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 )
		$pdf->firmas();
}

if($numImpresion > 0 && $_REQUEST['impresion'] == 1 ){

	if($numExtruder < 1){
		$pdf->SetFillColor(FONDO_TITULO_TABLA2);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);
		$pdf->Ln(3);
	
					$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
		$imp 	= 	($numImpresion * 6) + 45 ;
	}
	else{
		$imp 	= 	($numImpresion * 6) + 30 ;
	}
	
	$comparaImp =	$pdf->GetY() + $imp;
	if($comparaImp >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}


	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "IMPRESION", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);


						
     for($a = 0; $dMovimientoImpresion = mysql_fetch_assoc($rMovimientoImpresion) ;$a++){
				
		$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoImpresion['id_operador2']."";
		$rE2	=	mysql_query($qE2);
		$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoImpresion['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoImpresion['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoImpresion['dia'] 			!= "" 	&& $dMovimientoImpresion['dia'] 			!= "0000-00-00" && $dMovimientoImpresion['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoImpresion['dia']).", "; $b = $b +1; }
			if($dMovimientoImpresion['horas'] 			!= "" 	&& $dMovimientoImpresion['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoImpresion['horas'].", "; $b = $b +1; }
			if($dMovimientoImpresion['cantidad'] 		!= "" 	&& $dMovimientoImpresion['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoImpresion['cantidad'].", "; $b = $b +1; }
			if($dMovimientoImpresion['desde'] 			!= "" 	&& $dMovimientoImpresion['desde'] 			!= "0000-00-00" && ($dMovimientoImpresion['movimiento'] == 9 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoImpresion['desde'])." al "; $b = $b +1; }
			if($dMovimientoImpresion['hasta'] 			!= "" 	&& $dMovimientoImpresion['hasta'] 			!= "0000-00-00" && ($dMovimientoImpresion['movimiento'] == 9 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .=	 fecha_tabla($dMovimientoImpresion['hasta']).", "; $b = $b +1;}
			if($dMovimientoImpresion['numero_dias']	 	!= "" 	&& $dMovimientoImpresion['numero_dias'] 	!= "0" 			&& ($dMovimientoImpresion['movimiento'] == 9 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoImpresion['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoImpresion['desde_tiempo'] 	!= "" 	&& $dMovimientoImpresion['desde_tiempo']	!= "00:00" 		&& ($dMovimientoImpresion['movimiento'] == 23 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoImpresion['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoImpresion['hasta_tiempo'] 	!= "" 	&& $dMovimientoImpresion['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoImpresion['movimiento'] == 23 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoImpresion['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoImpresion['turno'] 			!= "" 	&& $dMovimientoImpresion['turno'] 			!= 0 			&& ($dMovimientoImpresion['movimiento'] == 4 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoImpresion['turno']. ", "; $b = $b +1; }
			if($dMovimientoImpresion['movimientos.rol'] 			!= ""  	&& $dMovimientoImpresion['movimientos.rol'] 			!= 0  			&& ($dMovimientoImpresion['movimiento'] == 5 	|| $dMovimientoImpresion['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoImpresion['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoImpresion['horario'] 		!= ""  	&& $dMovimientoImpresion['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoImpresion['horario'].", "; $b = $b +1; }
			if($dMovimientoImpresion['premio'] 			!= ""  	&& $dMovimientoImpresion['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoImpresion['puntualidad'] 	!= ""  	&& $dMovimientoImpresion['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoImpresion['productividad'] 	!= ""  	&& $dMovimientoImpresion['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoImpresion['no_premio'] 		!= ""  	&& $dMovimientoImpresion['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoImpresion['id_operador2'] 	!= "" 	&& $dMovimientoImpresion['id_operador2'] 	!= 0 ){ 
				$motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoImpresion['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoImpresion['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoImpresion['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoImpresion['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoImpresion['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);		}
	$pdf->Ln(2);
	if($numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1  && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 )
		$pdf->firmas();		
}


if($numBolseo > 0 &&  $_REQUEST['bolseo'] == 1 ){

if($numExtruder < 1 && $numImpresion < 1){
		$pdf->SetFillColor(FONDO_TITULO_TABLA2);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);
		$pdf->Ln(3);
	
					$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
		$bol 	= 	($numBolseo * 6) + 45 ;
	}
	else{
		$bol 	= 	($numBolseo * 6) + 30 ;
	}
	
	$compara =	$pdf->GetY() + $bol;
	if($compara >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "BOLSEO", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoBolseo = mysql_fetch_assoc($rMovimientoBolseo) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoBolseo['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoBolseo['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoBolseo['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoBolseo['dia'] 			!= "" 	&& $dMovimientoBolseo['dia'] 			!= "0000-00-00" && $dMovimientoBolseo['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoBolseo['dia']).", "; $b = $b +1; }
			if($dMovimientoBolseo['horas'] 			!= "" 	&& $dMovimientoBolseo['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoBolseo['horas'].", "; $b = $b +1; }
			if($dMovimientoBolseo['cantidad'] 		!= "" 	&& $dMovimientoBolseo['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoBolseo['cantidad'].", "; $b = $b +1; }
			if($dMovimientoBolseo['desde'] 			!= "" 	&& $dMovimientoBolseo['desde'] 			!= "0000-00-00" && ($dMovimientoBolseo['movimiento'] == 9 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoBolseo['desde'])." al "; $b = $b +1; }
			if($dMovimientoBolseo['hasta'] 			!= "" 	&& $dMovimientoBolseo['hasta'] 			!= "0000-00-00" && ($dMovimientoBolseo['movimiento'] == 9 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoBolseo['hasta']).", "; $b = $b +1;}
			if($dMovimientoBolseo['numero_dias']	!= "" 	&& $dMovimientoBolseo['numero_dias'] 	!= "0" 			&& ($dMovimientoBolseo['movimiento'] == 9 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoBolseo['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoBolseo['desde_tiempo'] 	!= "" 	&& $dMovimientoBolseo['desde_tiempo']	!= "00:00" 		&& ($dMovimientoBolseo['movimiento'] == 23 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoBolseo['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoBolseo['hasta_tiempo'] 	!= "" 	&& $dMovimientoBolseo['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoBolseo['movimiento'] == 23 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoBolseo['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoBolseo['turno'] 			!= "" 	&& $dMovimientoBolseo['turno'] 			!= 0 			&& ($dMovimientoBolseo['movimiento'] == 4 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoBolseo['turno']. ", "; $b = $b +1; }
			if($dMovimientoBolseo['movimientos.rol'] 			!= ""  	&& $dMovimientoBolseo['movimientos.rol'] 			!= 0  			&& ($dMovimientoBolseo['movimiento'] == 5 	|| $dMovimientoBolseo['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoBolseo['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoBolseo['horario'] 		!= ""  	&& $dMovimientoBolseo['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoBolseo['horario'].", "; $b = $b +1; }
			if($dMovimientoBolseo['premio'] 		!= ""  	&& $dMovimientoBolseo['premio'] 		!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoBolseo['puntualidad'] 	!= ""  	&& $dMovimientoBolseo['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoBolseo['productividad'] 	!= ""  	&& $dMovimientoBolseo['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoBolseo['no_premio'] 		!= ""  	&& $dMovimientoBolseo['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoBolseo['id_operador2'] 	!= "" 	&& $dMovimientoBolseo['id_operador2'] 	!= 0 ){ 
			$motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoBolseo['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoBolseo['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoBolseo['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoBolseo['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoBolseo['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);		
		
	}

	$pdf->Ln(2);
	if($numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1  && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 )
		$pdf->firmas();	
}

if($numEmpaque > 0 && $_REQUEST['emp'] == 1 ){
	
if($numExtruder < 1 && $numImpresion < 1 && $numBolseo < 1){
		$pdf->SetFillColor(FONDO_TITULO_TABLA2);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);
		$pdf->Ln(3);
	
					$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
		$emp 	= 	($numEmpaque * 6) + 45 ;
	}
	else{
		$emp 	= 	($numEmpaque * 6) + 30 ;
	}	
	
	$comparaEmp =	$pdf->GetY() + $emp;
	if($comparaEmp >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "EMPAQUE", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoEmpaque = mysql_fetch_assoc($rMovimientoEmpaque) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoEmpaque['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoEmpaque['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoEmpaque['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['dia'] 				!= "" 	&& $dMovimientoEmpaque['dia'] 			!= "0000-00-00" && $dMovimientoEmpaque['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoEmpaque['dia']).", "; $b = $b +1; }
			if($dMovimientoEmpaque['horas'] 			!= "" 	&& $dMovimientoEmpaque['horas'] 		!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoEmpaque['horas'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['cantidad'] 			!= "" 	&& $dMovimientoEmpaque['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoEmpaque['cantidad'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['desde'] 			!= "" 	&& $dMovimientoEmpaque['desde'] 		!= "0000-00-00" && ($dMovimientoEmpaque['movimiento'] == 9 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoEmpaque['desde'])." al "; $b = $b +1; }
			if($dMovimientoEmpaque['hasta'] 			!= "" 	&& $dMovimientoEmpaque['hasta'] 		!= "0000-00-00" && ($dMovimientoEmpaque['movimiento'] == 9 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoEmpaque['hasta']).", "; $b = $b +1;}
			if($dMovimientoEmpaque['numero_dias']		!= "" 	&& $dMovimientoEmpaque['numero_dias'] 	!= "0" 			&& ($dMovimientoEmpaque['movimiento'] == 9 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoEmpaque['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoEmpaque['desde_tiempo'] 		!= "" 	&& $dMovimientoEmpaque['desde_tiempo']	!= "00:00" 		&& ($dMovimientoEmpaque['movimiento'] == 23 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoEmpaque['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoEmpaque['hasta_tiempo'] 		!= "" 	&& $dMovimientoEmpaque['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoEmpaque['movimiento'] == 23 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoEmpaque['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoEmpaque['turno'] 			!= "" 	&& $dMovimientoEmpaque['turno'] 		!= 0 			&& ($dMovimientoEmpaque['movimiento'] == 4 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoEmpaque['turno']. ", "; $b = $b +1; }
			if($dMovimientoEmpaque['movimientos.rol'] 				!= ""  	&& $dMovimientoEmpaque['movimientos.rol'] 			!= 0  			&& ($dMovimientoEmpaque['movimiento'] == 5 	|| $dMovimientoEmpaque['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoEmpaque['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['horario'] 			!= ""  	&& $dMovimientoEmpaque['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoEmpaque['horario'].", "; $b = $b +1; }
			if($dMovimientoEmpaque['premio'] 			!= ""  	&& $dMovimientoEmpaque['premio'] 		!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoEmpaque['puntualidad'] 		!= ""  	&& $dMovimientoEmpaque['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoEmpaque['productividad'] 	!= ""  	&& $dMovimientoEmpaque['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoEmpaque['no_premio'] 		!= ""  	&& $dMovimientoEmpaque['no_premio'] 	!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoEmpaque['id_operador2'] 		!= "" 	&& $dMovimientoEmpaque['id_operador2'] 	!= 0 ){ 
			$motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoEmpaque['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoEmpaque['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoEmpaque['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoEmpaque['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoEmpaque['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	}
	$pdf->Ln(2);
	if($numEmpaqueB < 1 && $numMantenimiento < 1  && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 )
		$pdf->firmas();	
}

if($numEmpaqueB > 0 && $_REQUEST['empb'] == 1 ){

	if($numExtruder < 1 && $numImpresion < 1 && $numBolseo < 1 && $numEmpaque < 1){
		$pdf->SetFillColor(FONDO_TITULO_TABLA2);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);
		$pdf->Ln(3);
	
					$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
		$empb 	= 	($numEmpaqueB * 6) + 45 ;
	}
	else{
		$empb 	= 	($numEmpaqueB * 6) + 30;
	}	


	$comparaEmpb =	$pdf->GetY() + $empb;
	if($comparaEmpb >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}


	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "EMPAQUE B", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoEmpaqueB = mysql_fetch_assoc($rMovimientoEmpaqueB) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoEmpaqueB['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoEmpaqueB['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoEmpaqueB['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['dia'] 			!= "" 	&& $dMovimientoEmpaqueB['dia'] 			!= "0000-00-00" && $dMovimientoEmpaqueB['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoEmpaqueB['dia']).", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['horas'] 			!= "" 	&& $dMovimientoEmpaqueB['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoEmpaqueB['horas'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['cantidad'] 		!= "" 	&& $dMovimientoEmpaqueB['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoEmpaqueB['cantidad'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['desde'] 			!= "" 	&& $dMovimientoEmpaqueB['desde'] 			!= "0000-00-00" && ($dMovimientoEmpaqueB['movimiento'] == 9 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoEmpaqueB['desde'])." al "; $b = $b +1; }
			if($dMovimientoEmpaqueB['hasta'] 			!= "" 	&& $dMovimientoEmpaqueB['hasta'] 			!= "0000-00-00" && ($dMovimientoEmpaqueB['movimiento'] == 9 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoEmpaqueB['hasta']).", "; $b = $b +1;}
			if($dMovimientoEmpaqueB['numero_dias']		!= "" 	&& $dMovimientoEmpaqueB['numero_dias'] 	 	!= "0" 			&& ($dMovimientoEmpaqueB['movimiento'] == 9 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoEmpaqueB['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoEmpaqueB['desde_tiempo'] 	!= "" 	&& $dMovimientoEmpaqueB['desde_tiempo']	  	!= "00:00" 		&& ($dMovimientoEmpaqueB['movimiento'] == 23 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoEmpaqueB['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoEmpaqueB['hasta_tiempo'] 	!= "" 	&& $dMovimientoEmpaqueB['hasta_tiempo']	 	!= "00:00" 		&& ($dMovimientoEmpaqueB['movimiento'] == 23 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoEmpaqueB['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['turno'] 			!= "" 	&& $dMovimientoEmpaqueB['turno'] 			!= 0 			&& ($dMovimientoEmpaqueB['movimiento'] == 4 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoEmpaqueB['turno']. ", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['movimientos.rol'] 	!= ""  	&& $dMovimientoEmpaqueB['movimientos.rol'] 	!= 0  			&& ($dMovimientoEmpaqueB['movimiento'] == 5 	|| $dMovimientoEmpaqueB['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoEmpaqueB['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['horario'] 			!= ""  	&& $dMovimientoEmpaqueB['horario'] 			!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoEmpaqueB['horario'].", "; $b = $b +1; }
			if($dMovimientoEmpaqueB['premio'] 			!= ""  	&& $dMovimientoEmpaqueB['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoEmpaqueB['puntualidad'] 		!= ""  	&& $dMovimientoEmpaqueB['puntualidad'] 		!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoEmpaqueB['productividad'] 	!= ""  	&& $dMovimientoEmpaqueB['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoEmpaqueB['no_premio'] 		!= ""  	&& $dMovimientoEmpaqueB['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoEmpaqueB['id_operador2'] 	!= "" 	&& $dMovimientoEmpaqueB['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoEmpaqueB['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoEmpaqueB['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoEmpaqueB['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoEmpaqueB['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoEmpaqueB['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	}
	$pdf->Ln(2);
	if($numMantenimiento < 1  && $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 )
		$pdf->firmas();		
}



if($numMantenimiento > 0 && $_REQUEST['mantto'] == 1 ){

if($numExtruder < 1 && $numImpresion < 1 && $numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1){
	$mantto 	= 	($numMantenimiento * 6) + 45;	
} else {

	$mantto 	= 	($numMantenimiento * 6) + 30;	

}

	$comparaMantto =	$pdf->GetY() + $mantto;
	if($comparaMantto >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
		
		if($numExtruder == 0 && $numImpresion == 0 && $numBolseo == 0 && $numEmpaque == 0 && $numEmpaqueB == 0){
			$pdf->SetFillColor(FONDO_TITULO_TABLA2);
			$pdf->SetTextColor(LETRA_TITULO_TABLA2);
			$pdf->Ln(3);
		
			$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
		}		
		
	}
	else
	{
	
			$pdf->SetFillColor(FONDO_TITULO_TABLA2);
			$pdf->SetTextColor(LETRA_TITULO_TABLA2);
			$pdf->Ln(3);
		
			$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
	}

	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "MANTENIMIENTO", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoMantenimiento = mysql_fetch_assoc($rMovimientoMantenimiento) ;$a++){
				
			$qE2=	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoMantenimiento['id_operador2']."";
			$rE2	=	mysql_query($qE2);
			$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoMantenimiento['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoMantenimiento['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['dia'] 			!= "" 	&& $dMovimientoMantenimiento['dia'] 			!= "0000-00-00" && $dMovimientoMantenimiento['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoMantenimiento['dia']).", "; $b = $b +1; }
			if($dMovimientoMantenimiento['horas'] 			!= "" 	&& $dMovimientoMantenimiento['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoMantenimiento['horas'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['cantidad'] 		!= "" 	&& $dMovimientoMantenimiento['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoMantenimiento['cantidad'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['desde'] 			!= "" 	&& $dMovimientoMantenimiento['desde'] 			!= "0000-00-00" && ($dMovimientoMantenimiento['movimiento'] == 9 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoMantenimiento['desde'])." al "; $b = $b +1; }
			if($dMovimientoMantenimiento['hasta'] 			!= "" 	&& $dMovimientoMantenimiento['hasta'] 			!= "0000-00-00" && ($dMovimientoMantenimiento['movimiento'] == 9 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoMantenimiento['hasta']).", "; $b = $b +1;}
			if($dMovimientoMantenimiento['numero_dias'] 	!= "" 	&& $dMovimientoMantenimiento['numero_dias'] 	!= "0" 			&& ($dMovimientoMantenimiento['movimiento'] == 9 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoMantenimiento['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoMantenimiento['desde_tiempo'] 	!= "" 	&& $dMovimientoMantenimiento['desde_tiempo']	!= "00:00" 		&& ($dMovimientoMantenimiento['movimiento'] == 23 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoMantenimiento['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoMantenimiento['hasta_tiempo'] 	!= "" 	&& $dMovimientoMantenimiento['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoMantenimiento['movimiento'] == 23 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoMantenimiento['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoMantenimiento['turno'] 			!= "" 	&& $dMovimientoMantenimiento['turno'] 			!= 0 			&& ($dMovimientoMantenimiento['movimiento'] == 4 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoMantenimiento['turno']. ", "; $b = $b +1; }
			if($dMovimientoMantenimiento['movimientos.rol'] != ""  	&& $dMovimientoMantenimiento['movimientos.rol'] 			!= 0  			&& ($dMovimientoMantenimiento['movimiento'] == 5 	|| $dMovimientoMantenimiento['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoMantenimiento['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['horario'] 		!= ""  	&& $dMovimientoMantenimiento['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoMantenimiento['horario'].", "; $b = $b +1; }
			if($dMovimientoMantenimiento['premio'] 			!= ""  	&& $dMovimientoMantenimiento['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoMantenimiento['puntualidad'] 	!= ""  	&& $dMovimientoMantenimiento['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoMantenimiento['productividad'] 	!= ""  	&& $dMovimientoMantenimiento['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoMantenimiento['no_premio'] 		!= ""  	&& $dMovimientoMantenimiento['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoMantenimiento['id_operador2'] 	!= "" 	&& $dMovimientoMantenimiento['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoMantenimiento['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoMantenimiento['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoMantenimiento['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoMantenimiento['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoMantenimiento['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	}
	$pdf->Ln(2);
	if( $numMantenimientoB < 1 && $numAlmacen < 1 && $numAlmacenB < 1 )
		$pdf->firmas();	
}

if($numMantenimientoB > 0 && $_REQUEST['manttob'] == 1 ){

	if($numExtruder < 1 && $numImpresion < 1 && $numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1){
		$pdf->SetFillColor(FONDO_TITULO_TABLA2);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);
		$pdf->Ln(3);
	
					$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
	$manttob 	= 	($numMantenimientoB * 6) + 45 ;
	}
	else{
	$manttob 	= 	($numMantenimientoB * 6) + 30 ;
	}

	$comparaManttob =	$pdf->GetY() + $manttob;
	if($comparaManttob >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "MANTENIMIENTO B", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoMantenimientoB = mysql_fetch_assoc($rMovimientoMantenimientoB) ;$a++){
				
					$qE2    =	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoMantenimientoB['id_operador2']."";
					$rE2	=	mysql_query($qE2);
					$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoMantenimientoB['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoMantenimientoB['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['dia'] 			!= "" 	&& $dMovimientoMantenimientoB['dia'] 			!= "0000-00-00" && $dMovimientoMantenimientoB['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoMantenimientoB['dia']).", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['horas'] 			!= "" 	&& $dMovimientoMantenimientoB['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoMantenimientoB['horas'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['cantidad'] 		!= "" 	&& $dMovimientoMantenimientoB['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoMantenimientoB['cantidad'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['desde'] 			!= "" 	&& $dMovimientoMantenimientoB['desde'] 			!= "0000-00-00" && ($dMovimientoMantenimientoB['movimiento'] == 9 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoMantenimientoB['desde'])." al "; $b = $b +1; }
			if($dMovimientoMantenimientoB['hasta'] 			!= "" 	&& $dMovimientoMantenimientoB['hasta'] 			!= "0000-00-00" && ($dMovimientoMantenimientoB['movimiento'] == 9 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoMantenimientoB['hasta']).", "; $b = $b +1;}
			if($dMovimientoMantenimientoB['numero_dias'] 	!= "" 	&& $dMovimientoMantenimientoB['numero_dias'] 	!= "0" 			&& ($dMovimientoMantenimientoB['movimiento'] == 9 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoMantenimientoB['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoMantenimientoB['desde_tiempo'] 	!= "" 	&& $dMovimientoMantenimientoB['desde_tiempo']	!= "00:00" 		&& ($dMovimientoMantenimientoB['movimiento'] == 23 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoMantenimientoB['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoMantenimientoB['hasta_tiempo'] 	!= "" 	&& $dMovimientoMantenimientoB['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoMantenimientoB['movimiento'] == 23 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoMantenimientoB['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['turno'] 			!= "" 	&& $dMovimientoMantenimientoB['turno'] 			!= 0 			&& ($dMovimientoMantenimientoB['movimiento'] == 4 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoMantenimientoB['turno']. ", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['movimientos.rol'] 			!= ""  	&& $dMovimientoMantenimientoB['movimientos.rol'] 			!= 0  			&& ($dMovimientoMantenimientoB['movimiento'] == 5 	|| $dMovimientoMantenimientoB['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoMantenimientoB['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['horario'] 		!= ""  	&& $dMovimientoMantenimientoB['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoMantenimientoB['horario'].", "; $b = $b +1; }
			if($dMovimientoMantenimientoB['premio'] 		!= ""  	&& $dMovimientoMantenimientoB['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoMantenimientoB['puntualidad'] 	!= ""  	&& $dMovimientoMantenimientoB['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoMantenimientoB['productividad'] 	!= ""  	&& $dMovimientoMantenimientoB['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoMantenimientoB['no_premio'] 		!= ""  	&& $dMovimientoMantenimientoB['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoMantenimientoB['id_operador2'] 	!= "" 	&& $dMovimientoMantenimientoB['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoMantenimientoB['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoMantenimientoB['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoMantenimientoB['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoMantenimientoB['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoMantenimientoB['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	}
	
	$pdf->Ln(2);
	if( $numAlmacen < 1 && $numAlmacenB < 1 )
		$pdf->firmas();	

}


if($numAlmacen > 0 && $_REQUEST['alm'] == 1 ){

	if($numExtruder < 1 && $numImpresion < 1 && $numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1){
		$pdf->SetFillColor(FONDO_TITULO_TABLA2);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);
		$pdf->Ln(3);
	
					$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
	$alm 	= 	($numAlmacen * 6) + 45 ;
	}
	else{
	$alm 	= 	($numAlmacen * 6) + 30 ;
	}


	$comparaAlm =	$pdf->GetY() + $alm;
	if($comparaAlm >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "ALMACEN", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoAlmacen = mysql_fetch_assoc($rMovimientoAlmacen) ;$a++){
				
					$qE2    =	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoAlmacen['id_operador2']."";
					$rE2	=	mysql_query($qE2);
					$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoAlmacen['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoAlmacen['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['dia'] 			!= "" 	&& $dMovimientoAlmacen['dia'] 			!= "0000-00-00" && $dMovimientoAlmacen['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoAlmacen['dia']).", "; $b = $b +1; }
			if($dMovimientoAlmacen['horas'] 			!= "" 	&& $dMovimientoAlmacen['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoAlmacen['horas'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['cantidad'] 		!= "" 	&& $dMovimientoAlmacen['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoAlmacen['cantidad'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['desde'] 			!= "" 	&& $dMovimientoAlmacen['desde'] 			!= "0000-00-00" && ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoAlmacen['desde'])." al "; $b = $b +1; }
			if($dMovimientoAlmacen['hasta'] 			!= "" 	&& $dMovimientoAlmacen['hasta'] 			!= "0000-00-00" && ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoAlmacen['hasta']).", "; $b = $b +1;}
			if($dMovimientoAlmacen['numero_dias'] 	!= "" 	&& $dMovimientoAlmacen['numero_dias'] 	!= "0" 			&& ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoAlmacen['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoAlmacen['desde_tiempo'] 	!= "" 	&& $dMovimientoAlmacen['desde_tiempo']	!= "00:00" 		&& ($dMovimientoAlmacen['movimiento'] == 23 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoAlmacen['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoAlmacen['hasta_tiempo'] 	!= "" 	&& $dMovimientoAlmacen['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoAlmacen['movimiento'] == 23 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoAlmacen['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoAlmacen['turno'] 			!= "" 	&& $dMovimientoAlmacen['turno'] 			!= 0 			&& ($dMovimientoAlmacen['movimiento'] == 4 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoAlmacen['turno']. ", "; $b = $b +1; }
			if($dMovimientoAlmacen['movimientos.rol'] 			!= ""  	&& $dMovimientoAlmacen['movimientos.rol'] 			!= 0  			&& ($dMovimientoAlmacen['movimiento'] == 5 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoAlmacen['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['horario'] 		!= ""  	&& $dMovimientoAlmacen['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoAlmacen['horario'].", "; $b = $b +1; }
			if($dMovimientoAlmacen['premio'] 		!= ""  	&& $dMovimientoAlmacen['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoAlmacen['puntualidad'] 	!= ""  	&& $dMovimientoAlmacen['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoAlmacen['productividad'] 	!= ""  	&& $dMovimientoAlmacen['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoAlmacen['no_premio'] 		!= ""  	&& $dMovimientoAlmacen['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoAlmacen['id_operador2'] 	!= "" 	&& $dMovimientoAlmacen['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoAlmacen['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoAlmacen['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoAlmacen['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoAlmacen['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoAlmacen['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	
			}
		
	if( $numAlmacenB < 1 )
		$pdf->firmas();	
	
	
}
	
if($numAlmacenB > 0 && $_REQUEST['almb'] == 1 ){

	if($numExtruder < 1 && $numImpresion < 1 && $numBolseo < 1 && $numEmpaque < 1 && $numEmpaqueB < 1 && $numMantenimiento < 1 && $numMantenimientoB < 1 && $numAlmacen < 1){
		$pdf->SetFillColor(FONDO_TITULO_TABLA2);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);
		$pdf->Ln(3);
	
					$pdf->SetFont("Arial","B",6);
			$pdf->Cell(260, 5, "MOVIMIENTOS ECONOMICOS", 0, 1, 'C');
			$pdf->SetFont("Arial","",6);
	$almb 	= 	($numAlmacenB * 6) + 45 ;
	}
	else{
	$almb 	= 	($numAlmacenB * 6) + 30 ;
	}

	$comparaAlmb =	$pdf->GetY() + $almb;
	if($comparaAlmb >= $medidaY){
		$pdf->AddPage();
		$pdf->SetY(20);
	}
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(260, 5, "ALMACEN B", 0, 1, 'C');
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "FECHA:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(10, 6, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(70, 6, "NOMBRE:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(40, 6, "MOVIMIENTO:", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(125, 6, "MOTIVO:", 1, 1, 'C',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);

     for($a = 0; $dMovimientoAlmacenB = mysql_fetch_assoc($rMovimientoAlmacenB) ;$a++){
				
					$qE2    =	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoAlmacenB['id_operador2']."";
					$rE2	=	mysql_query($qE2);
					$dE2	=	mysql_fetch_assoc($rE2);
									
			if(bcmod($a,2) == 0)
			$numero = "1";
			else
			$numero = "0";
	
			$b = 0;
			$motivo = "";
			if($dMovimientoAlmacenB['nuevo_movimiento'] != "" ){ $motivo .= "Otro movimiento:" .$dMovimientoAlmacenB['nuevo_movimiento'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['dia'] 			!= "" 	&& $dMovimientoAlmacenB['dia'] 			!= "0000-00-00" && $dMovimientoAlmacenB['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoAlmacenB['dia']).", "; $b = $b +1; }
			if($dMovimientoAlmacenB['horas'] 			!= "" 	&& $dMovimientoAlmacenB['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoAlmacenB['horas'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['cantidad'] 		!= "" 	&& $dMovimientoAlmacenB['cantidad'] 		!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoAlmacenB['cantidad'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['desde'] 			!= "" 	&& $dMovimientoAlmacenB['desde'] 			!= "0000-00-00" && ($dMovimientoAlmacenB['movimiento'] == 9 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoAlmacenB['desde'])." al "; $b = $b +1; }
			if($dMovimientoAlmacenB['hasta'] 			!= "" 	&& $dMovimientoAlmacenB['hasta'] 			!= "0000-00-00" && ($dMovimientoAlmacenB['movimiento'] == 9 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoAlmacenB['hasta']).", "; $b = $b +1;}
			if($dMovimientoAlmacenB['numero_dias'] 	!= "" 	&& $dMovimientoAlmacenB['numero_dias'] 	!= "0" 			&& ($dMovimientoAlmacenB['movimiento'] == 9 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoAlmacenB['numero_dias'].", "; $b = $b +1;}
			if($dMovimientoAlmacenB['desde_tiempo'] 	!= "" 	&& $dMovimientoAlmacenB['desde_tiempo']	!= "00:00" 		&& ($dMovimientoAlmacenB['movimiento'] == 23 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoAlmacenB['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoAlmacenB['hasta_tiempo'] 	!= "" 	&& $dMovimientoAlmacenB['hasta_tiempo']	!= "00:00" 		&& ($dMovimientoAlmacenB['movimiento'] == 23 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoAlmacenB['hasta_tiempo']; $motivo .= ", "; $b = $b +1; }
			if($dMovimientoAlmacenB['turno'] 			!= "" 	&& $dMovimientoAlmacenB['turno'] 			!= 0 			&& ($dMovimientoAlmacenB['movimiento'] == 4 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoAlmacenB['turno']. ", "; $b = $b +1; }
			if($dMovimientoAlmacenB['movimientos.rol'] 			!= ""  	&& $dMovimientoAlmacenB['movimientos.rol'] 			!= 0  			&& ($dMovimientoAlmacenB['movimiento'] == 5 	|| $dMovimientoAlmacenB['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoAlmacenB['movimientos.rol'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['horario'] 		!= ""  	&& $dMovimientoAlmacenB['horario'] 		!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoAlmacenB['horario'].", "; $b = $b +1; }
			if($dMovimientoAlmacenB['premio'] 		!= ""  	&& $dMovimientoAlmacenB['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios, ";$b = $b +1; }
			if($dMovimientoAlmacenB['puntualidad'] 	!= ""  	&& $dMovimientoAlmacenB['puntualidad'] 	!= 0 ){ $motivo .= "Afecta puntualidad, ";$b = $b +1; }
			if($dMovimientoAlmacenB['productividad'] 	!= ""  	&& $dMovimientoAlmacenB['productividad']	!= 0 ){ $motivo .= "Afecta productividad, ";$b = $b +1; }
			if($dMovimientoAlmacenB['no_premio'] 		!= ""  	&& $dMovimientoAlmacenB['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio, ";$b = $b +1; }
			if($dMovimientoAlmacenB['id_operador2'] 	!= "" 	&& $dMovimientoAlmacenB['id_operador2'] 	!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre'].", ";$b = $b +1; }
			
			$motivo .= "\nMotivo: ".$titulo_m.$dMovimientoAlmacenB['motivo'];
			$h = 4*2;

			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(15, $h, fecha_tabla($dMovimientoAlmacenB['fecha']), 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10,$h, $dMovimientoAlmacenB['numnomina'], 1, 0, 'C',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(70, $h, $dMovimientoAlmacenB['nombre'], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(40, $h, $motivos[$dMovimientoAlmacenB['movimiento']], 1, 0, 'L',$numero);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->SetFont("Arial","",5.5);
			$pdf->MultiCell(125, 4, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	}
		
		$pdf->firmas();		
	
}

}






$pdf->Close();
$pdf->Output('Movimientos_semana_no_'.$semana.'.pdf','I');

}


if($_REQUEST['tipo'] == 'prenomina'){

	$h = 3.4;
	$dias = array("J","V","S","D","L","M","Mi");
	if($_REQUEST['todas'] == 0 ){
	$areaFor =  $_REQUEST['area'];
	$tamano = $_REQUEST['area'];
		if($areaFor == 6)
		$areaFor = 4;
		if($areaFor == 7)
		$areaFor = 5;
		if($areaFor == 9)
		$areaFor = 8;
	}
	if($_REQUEST['todas'] == 1 ){
	$areaFor = 1;	
	$tamano	= (sizeof($areasEmpleado) - 1);
	}	

		  $qDelAl2	=	"SELECT desde, hasta FROM pre_nomina_calificada WHERE semana =	".$_REQUEST['semana']." AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' )";
		  $rDelAl2	=	mysql_query($qDelAl2);
		  $dDelAl2	=	mysql_fetch_assoc($rDelAl2);


		  $qDelAl	=	"SELECT DAY(desde), DAY(hasta), MONTH(desde), MONTH(hasta), desde, hasta FROM pre_nomina_calificada WHERE semana =	".$_REQUEST['semana']." AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) ".
		  				"AND MONTH(desde) = (SELECT MONTH(desde) FROM pre_nomina_calificada WHERE semana = ".$_REQUEST['semana']." GROUP BY semana ORDER BY desde DESC ) GROUP BY semana";

		  $rDelAl	=	mysql_query($qDelAl);
		  $dDelAl	=	mysql_fetch_row($rDelAl);

		$desdeFec	=	$dDelAl[4];
		$hastaFec	=	$dDelAl[5];
		
		$semana	=	$_REQUEST['semana'];

								$qNumDia	=	"SELECT DAY(fecha),MONTH(fecha), YEAR(fecha) FROM asistencias ".
//													" WHERE semana = '".$_REQUEST['semana']."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."'  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC";
													" WHERE semana = '".$_REQUEST['semana']."'   AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC";
								$rNumDia	=	mysql_query($qNumDia);
								$dNumDia	=	mysql_fetch_row($rNumDia);
								$u_dia	= UltimoDia($dNumDia[2], $dNumDia[1]);
								$dNumDia[0];
								

		  	
$medidaY = 200;
$pdf=&new PDF();
$pdf->FPDF('L','mm','Letter');
$pdf->SetFont("Arial","",8);
$pdf->AliasNbPages();

$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);
$pdf->Cell(340, 5, "", 0, 1, 'C');
$pdf->Ln(3);

				
for($areaFor; $areaFor <= $tamano; $areaFor++){
 
 	if($_REQUEST['todas'] == 1){
		if($areaFor == 6) $areaFor = 8;
		
	}

			$qAsistencias	=	"SELECT * FROM pre_nomina_calificada INNER JOIN operadores ON pre_nomina_calificada.id_operador = operadores.id_operador".
										" WHERE semana = '".$_REQUEST['semana']."' AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND operadores.area = ".$areaFor." 			AND activo = 0  AND pre_nomina_calificada.desde = '".$desdeFec."' AND pre_nomina_calificada.hasta = '".$hastaFec."'  ORDER BY numnomina ASC";
					
			$rAsistencias	=	mysql_query($qAsistencias);
			$nAsistencias	=	mysql_num_rows($rAsistencias);
				
	if($nAsistencias > 0){	
	

		$pdf->AddPage();
		$pdf->SetY(21);
	
					
		$pdf->prenomina_tabla($h,$semana,$dias,$dNumDia[0],$dDelAl2['desde'],$dDelAl2['hasta'],$areasEmpleado[$areaFor]);
		$pdf->SetFont("Arial","",7);
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(75, $h, "Área de ". $areasEmpleado[$areaFor], 1, 0, 'C',1);
			 for($z = 0; $z < 7; $z++){
			 
             		$el_dia		=	$dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} ;
             	$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(4, $h, $el_dia, 1, 0, 'C',1);			  
			  }	
			 for($z = 0; $z < 7; $z++){
			 
             		$el_dia		=	$dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} ;
             	$pdf->SetTextColor(LETRA_CAMPOS);
				$pdf->Cell(4, $h, $el_dia, 1, 0, 'C',0);			  
			  }	
			 for($z = 0; $z < 7; $z++){
			 
             		$el_dia		=	$dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} ;
             	$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(4, $h, $el_dia, 1, 0, 'C',1);			  
			  }				  
      	         	
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(5, $h, "PD", 1, 0, 'C',1);	
				
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(5, $h, "%", 1, 0, 'C',1);						
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(5, $h, "%", 1, 0, 'C',1);	
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(5, $h, "%", 1, 0, 'C',1);	
				
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(55, $h, "CAUSAS PERDIDAS", 1, 0, 'C',1);	
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(25, $h, "OBSERVACIONES", 1, 1, 'C',1);			
			
	

				
	
				for( $k = 0;  $dAsistencias	=	mysql_fetch_assoc($rAsistencias); $k++){ 
						
						$qFaltas	=	"SELECT asistencia, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'   ORDER BY fecha ASC LIMIT 7";
						$rFaltas	=	mysql_query($qFaltas);
						
						$qRetardos	=	"SELECT retardo, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC LIMIT 7";
						$rRetardos	=	mysql_query($qRetardos);
						
						$qExtras	=	"SELECT extra, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC LIMIT 7";
						$rExtras	=	mysql_query($qExtras);
						
											
						
			$pdf->SetFont("Arial","",7);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10, $h, $dAsistencias['numnomina'], 1, 0, 'C',0);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(65, $h, $dAsistencias['nombre'], 1, 0, 'L',0);			
			
			for($z = 0; $dFaltas	=	mysql_fetch_assoc($rFaltas); $z++){
			
			
							if($dFaltas['asistencia'] == '' || $dFaltas['asistencia'] == '0'){
								$pdf->SetTextColor(LETRA_CAMPOS);
								$pdf->Cell(4, $h,"", 1, 0, 'C',0); 
							}
							else if($dFaltas['asistencia'] != '' || $dFaltas['asistencia'] != '0'){
							
								if($dFaltas['asistencia'] == 'F' || $dFaltas['asistencia'] == 'f'){
									$pdf->SetTextColor(LETRA_FALTA);
									$pdf->Cell(4, $h,$dFaltas['asistencia'], 1, 0, 'C',0); 
								}
								else if($dFaltas['asistencia'] == 'I' || $dFaltas['asistencia'] == 'i'){
									$pdf->SetTextColor(LETRA_INCAPACIDAD);
									$pdf->Cell(4, $h,$dFaltas['asistencia'], 1, 0, 'C',0); 
								}
								else{
									$pdf->SetTextColor(LETRA_CAMPOS);
									$pdf->Cell(4, $h,$dFaltas['asistencia'], 1, 0, 'C',0);
								}
							}
				
				}
			for($z = 0; $dRetardos	=	mysql_fetch_assoc($rRetardos); $z++){
							$pdf->SetTextColor(LETRA_CAMPOS);
							if($dRetardos['retardo'] == '' || $dRetardos['retardo'] == '0')
								$pdf->Cell(4, $h,"", 1, 0, 'C',0); 
							else 
							$pdf->Cell(4, $h,  $dRetardos['retardo'], 1, 0, 'C',0);
				
				}
			for($z = 0; $dExtras	=	mysql_fetch_assoc($rExtras); $z++){
							$pdf->SetTextColor(LETRA_CAMPOS);
							if($dExtras['extra'] == '' || $dExtras['extra'] == '0')
								$pdf->Cell(4, $h,"", 1, 0, 'C',0); 
							else 
							$pdf->Cell(4, $h,  $dExtras['extra'], 1, 0, 'C',0);
				
				}
				
			 	$qPrima	=	"SELECT * FROM asistencias WHERE dia = 'D' AND asistencia IN ('1','2','3','m')  AND semana = ".$semana." AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."'  AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ";
				$rPrima	=	mysql_query($qPrima);
				$nPrima	=	mysql_num_rows($rPrima);
					if($nPrima > 0){
						$pdf->SetTextColor(LETRA_CAMPOS);
						$pdf->Cell(5, $h, "SI", 1, 0, 'C',0);	
					}
					else {
						$pdf->SetTextColor(LETRA_CAMPOS);
						$pdf->Cell(5, $h, "", 1, 0, 'C',0);					
					}
					
					$pdf->SetTextColor(LETRA_CAMPOS);
					if($dAsistencias['por_asist'] == "")
					$pdf->Cell(5, $h, '5', 1, 0, 'C',0);
					else
					$pdf->Cell(5, $h, $dAsistencias['por_asist'], 1, 0, 'C',0);		
					
									
					$pdf->SetTextColor(LETRA_CAMPOS);
					if($dAsistencias['punt'] == "")
					$pdf->Cell(5, $h, '5', 1, 0, 'C',0);	
					else
					$pdf->Cell(5, $h, $dAsistencias['punt'], 1, 0, 'C',0);	
					
					
					$pdf->SetTextColor(LETRA_CAMPOS);
					if(	$dAsistencias['prod'] == "")
					$pdf->Cell(5, $h, '10', 1, 0, 'C',0);	
					else
					$pdf->Cell(5, $h, $dAsistencias['prod'], 1, 0, 'C',0);	
					
					//if($dAsistencias['por_asist'] == '' || $dAsistencias['por_asist'] == '0')
			
			// echo "5"; else echo $dAsistencias['por_asist'];
					$pdf->SetFont("Arial","",5);

					$pdf->SetTextColor(LETRA_CAMPOS);
					$pdf->Cell(55, $h, $dAsistencias['causas'], 1, 0,'L',0); 															
			
					$pdf->SetTextColor(LETRA_CAMPOS);
					$pdf->Cell(25, $h, $dAsistencias['observaciones'], 1, 1,'L',0); 			
			}
	}

if( ($areaFor == 4  || $areaFor == 5 || $areaFor == 8)  ){

if( $areaFor == 4)
$areaFor = 6;
else if($areaFor == 5)
$areaFor = 7;
else if($areaFor == 8)
$areaFor = 9;



			$qAsistencias	=	"SELECT * FROM pre_nomina_calificada INNER JOIN operadores ON pre_nomina_calificada.id_operador = operadores.id_operador".
										" WHERE semana = '".$_REQUEST['semana']."' AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND operadores.area = ".$areaFor." AND activo = 0   AND pre_nomina_calificada.desde = '".$desdeFec."' AND pre_nomina_calificada.hasta = '".$hastaFec."'  ORDER BY numnomina ASC";
					
			$rAsistencias	=	mysql_query($qAsistencias);
			$nAsistencias	=	mysql_num_rows($rAsistencias);
				
	if($nAsistencias > 0){	
	
	
					
		$pdf->SetFont("Arial","",7);
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(75, $h, "Área de ". $areasEmpleado[$areaFor], 1, 0, 'C',1);
			 for($z = 0; $z < 7; $z++){
			 
             		$el_dia		=	$dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} ;
             	$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(4, $h, $el_dia, 1, 0, 'C',1);			  
			  }	
			 for($z = 0; $z < 7; $z++){
			 
             		$el_dia		=	$dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} ;
             	$pdf->SetTextColor(LETRA_CAMPOS);
				$pdf->Cell(4, $h, $el_dia, 1, 0, 'C',0);			  
			  }	
			 for($z = 0; $z < 7; $z++){
			 
             		$el_dia		=	$dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} ;
             	$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(4, $h, $el_dia, 1, 0, 'C',1);			  
			  }				  
      	         	
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(5, $h, "PD", 1, 0, 'C',1);	
				
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(5, $h, "%", 1, 0, 'C',1);						
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(5, $h, "%", 1, 0, 'C',1);	
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(5, $h, "%", 1, 0, 'C',1);	
				
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(55, $h, "CAUSAS PERDIDAS", 1, 0, 'C',1);	
				$pdf->SetTextColor(LETRA_TITULO_TABLA);
				$pdf->Cell(25, $h, "OBSERVACIONES", 1, 1, 'C',1);			
			
	

				
	
				for( $k = 0;  $dAsistencias	=	mysql_fetch_assoc($rAsistencias); $k++){ 
						
						$qFaltas	=	"SELECT asistencia, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC";
						$rFaltas	=	mysql_query($qFaltas);
						
						$qRetardos	=	"SELECT retardo, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'   ORDER BY fecha ASC";
						$rRetardos	=	mysql_query($qRetardos);
						
						$qExtras	=	"SELECT extra, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC";
						$rExtras	=	mysql_query($qExtras);
						
											
						
			$pdf->SetFont("Arial","",7);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(10, $h, $dAsistencias['numnomina'], 1, 0, 'C',0);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(65, $h, $dAsistencias['nombre'], 1, 0, 'L',0);			
			
			for($z = 0; $dFaltas	=	mysql_fetch_assoc($rFaltas); $z++){
			
			
							if($dFaltas['asistencia'] == '' || $dFaltas['asistencia'] == '0'){
								$pdf->SetTextColor(LETRA_CAMPOS);
								$pdf->Cell(4, $h,"", 1, 0, 'C',0); 
							}
							else if($dFaltas['asistencia'] != '' || $dFaltas['asistencia'] != '0'){
							
								if($dFaltas['asistencia'] == 'F' || $dFaltas['asistencia'] == 'f'){
									$pdf->SetTextColor(LETRA_FALTA);
									$pdf->Cell(4, $h,$dFaltas['asistencia'], 1, 0, 'C',0); 
								}
								else if($dFaltas['asistencia'] == 'I' || $dFaltas['asistencia'] == 'i'){
									$pdf->SetTextColor(LETRA_INCAPACIDAD);
									$pdf->Cell(4, $h,$dFaltas['asistencia'], 1, 0, 'C',0); 
								}
								else{
									$pdf->SetTextColor(LETRA_CAMPOS);
									$pdf->Cell(4, $h,$dFaltas['asistencia'], 1, 0, 'C',0);
								}
							}
				
				}
			for($z = 0; $dRetardos	=	mysql_fetch_assoc($rRetardos); $z++){
							$pdf->SetTextColor(LETRA_CAMPOS);
							if($dRetardos['retardo'] == '' || $dRetardos['retardo'] == '0')
								$pdf->Cell(4, $h,"", 1, 0, 'C',0); 
							else 
							$pdf->Cell(4, $h,  $dRetardos['retardo'], 1, 0, 'C',0);
				
				}
			for($z = 0; $dExtras	=	mysql_fetch_assoc($rExtras); $z++){
							$pdf->SetTextColor(LETRA_CAMPOS);
							if($dExtras['extra'] == '' || $dExtras['extra'] == '0')
								$pdf->Cell(4, $h,"", 1, 0, 'C',0); 
							else 
							$pdf->Cell(4, $h,  $dExtras['extra'], 1, 0, 'C',0);
				
				}
				
				//$qPrima	=	"SELECT * FROM asistencias WHERE  (asistencia != 'd' AND asistencia != '' AND asistencia != 'v' AND asistencia != 'b' AND asistencia != 'a' AND asistencia != 'j' AND asistencia != 'x' ) AND semana = ".$semana." AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']."  ";
			 	$qPrima	=	"SELECT * FROM asistencias WHERE dia = 'D' AND asistencia IN ('1','2','3','m')  AND semana = ".$semana." AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."'  AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'";
				$rPrima	=	mysql_query($qPrima);
				$nPrima	=	mysql_num_rows($rPrima);
				if($nPrima > 0){
					$pdf->SetTextColor(LETRA_CAMPOS);
					$pdf->Cell(5, $h, "SI", 1, 0, 'C',0);	
				}
				else{
					$pdf->SetTextColor(LETRA_CAMPOS);
					$pdf->Cell(5, $h, "", 1, 0, 'C',0);					
				}
					
					$pdf->SetTextColor(LETRA_CAMPOS);
					if($dAsistencias['por_asist'] == "")
					$pdf->Cell(5, $h, '5', 1, 0, 'C',0);
					else
					$pdf->Cell(5, $h, $dAsistencias['por_asist'], 1, 0, 'C',0);		
					
									
					$pdf->SetTextColor(LETRA_CAMPOS);
					if($dAsistencias['punt'] == "")
					$pdf->Cell(5, $h, '5', 1, 0, 'C',0);	
					else
					$pdf->Cell(5, $h, $dAsistencias['punt'], 1, 0, 'C',0);	
					
					
					$pdf->SetTextColor(LETRA_CAMPOS);
					if(	$dAsistencias['prod'] == "")
					$pdf->Cell(5, $h, '10', 1, 0, 'C',0);	
					else
					$pdf->Cell(5, $h, $dAsistencias['prod'], 1, 0, 'C',0);	
					
					//if($dAsistencias['por_asist'] == '' || $dAsistencias['por_asist'] == '0')
			
			// echo "5"; else echo $dAsistencias['por_asist'];
					$pdf->SetFont("Arial","",5);

					$pdf->SetTextColor(LETRA_CAMPOS);
					$pdf->Cell(55, $h, $dAsistencias['causas'], 1, 0,'L',0); 															
			
					$pdf->SetTextColor(LETRA_CAMPOS);
					$pdf->Cell(25, $h, $dAsistencias['observaciones'], 1, 1,'L',0); 			
			}
	}
	
if($areaFor == 6)
$areaFor = 4;
else if($areaFor == 7)
$areaFor = 5;
else if($areaFor == 9)
$areaFor = 8;

}




}
				
								
				$pdf->Close();
$pdf->Output('Prenomina_semana_no_'.$semana.'.pdf','I');

}




if($_REQUEST['tipo'] == 'asistencias'){

$h = 5;
$pdf=&new PDF();
$pdf->FPDF('P','mm','Legal');
$pdf->SetFont("Arial","",6);
$pdf->AliasNbPages();

$pdf->AddPage();
$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);
$pdf->Cell(210, 10	, "REPORTE DE ASISTENCIAS DE ".$_REQUEST['fecha'], 0, 1, 'C');

	$pdf->SetX(40);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, $h, "AREA", 1, 0, 'L',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, $h, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(60, $h, "NOMBRE", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, $h, "TURNO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, $h, "HORAS", 1, 1, 'C',1);
			
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
			
			$fecha = 	fecha_tablaInv($_REQUEST['fecha']);
			$qBusqueda	=	"SELECT * FROM operadores INNER JOIN asistencias ON operadores.id_operador = asistencias.id_operador WHERE fecha = '".$fecha."' AND  (asistencia = '1' OR asistencia = '2' OR asistencia = '3' OR asistencia = 'M'  ) ORDER BY operadores.area, operadores.numnomina ASC";
			$rBusqueda	=	mysql_query($qBusqueda);
			
			for($a = 0; $dBusqueda = mysql_fetch_assoc($rBusqueda); $a++){ 
			if(bcmod($a,2) == 0)
			$color = "1";
			else
			$color = "0";
				$pdf->SetX(40);
				$pdf->SetTextColor(LETRA_CAMPOS);
				$pdf->Cell(25, $h,  $areasEmpleado[$dBusqueda['area']], 1, 0, 'L',$color);
				$pdf->SetTextColor(LETRA_CAMPOS);
				$pdf->Cell(15, $h, $dBusqueda['numnomina'], 1, 0, 'C',$color);
				$pdf->SetTextColor(LETRA_CAMPOS);
				$pdf->Cell(60, $h,  $dBusqueda['nombre'], 1, 0, 'L',$color);
				$pdf->SetTextColor(LETRA_CAMPOS);
				if($dBusqueda['asistencia'] >= 1 &&  $dBusqueda['asistencia'] <= 3 ) {
				$pdf->Cell(15, $h, $turnos[$dBusqueda['asistencia']], 1, 0, 'C',$color);
				}
				else if($dBusqueda['asistencia'] == 'M') {
				$pdf->Cell(15, $h, 'MIXTO', 1, 0, 'C',$color);
				}				
				$pdf->SetTextColor(LETRA_CAMPOS);

					if($dBusqueda['asistencia'] == 1 ) 
						$pdf->Cell(15, $h, "8", 1, 1, 'C',$color);			
					else if($dBusqueda['asistencia'] == 2 )
						$pdf->Cell(15, $h, "7", 1, 1, 'C',$color);			
					else if($dBusqueda['asistencia'] == 3 )
						$pdf->Cell(15, $h, "9", 1, 1, 'C',$color);	
					else if($dBusqueda['asistencia'] == 'M' )
						$pdf->Cell(15, $h, "MIXTO", 1, 1, 'C',$color);										
	
	if($pdf->GetY() >= 320){
		$pdf->AddPage();
		
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetY(25);		
	$pdf->SetX(40);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, $h, "AREA", 1, 0, 'L',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, $h, "NOMINA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(60, $h, "NOMBRE", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, $h, "TURNO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, $h, "HORAS", 1, 1, 'C',1);		
		
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);		
	}
			
			}
	
	
	
	$pdf->Close();
$pdf->Output('Asistencias del '.$_REQUEST['fecha'].'.pdf','I');

}



if($_REQUEST['tipo'] == 'personal'){

$semana 		= 	$_REQUEST['semana'];
$id_movimiento	=	$_REQUEST['id_movimiento'];


	
	$qMovimientoAlmacen			=	"SELECT * FROM movimientos INNER JOIN operadores ON movimientos.id_operador = operadores.id_operador WHERE id_movimiento = '$id_movimiento' AND autorizado = 1 ORDER BY fecha, numnomina ASC";
	$rMovimientoAlmacen				=	mysql_query($qMovimientoAlmacen);


	if($_REQUEST['alm'] 		== 1) 	{ $numAlmacen			=	mysql_num_rows($rMovimientoAlmacen);		} 	else { $numAlmacen 			= 0; }

	
$medidaY = 200;
$pdf=&new PDF();
$pdf->FPDF('P','mm','LETTER');
$pdf->SetFont("Arial","",6);
$pdf->AliasNbPages();

$pdf->AddPage();

$pdf->Ln(5);
$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);
$pdf->SetFont("Arial","B",6);
$pdf->Cell(210, 5, "REPORTE DE MOVIMIENTO DE PERSONAL", 0, 1, 'C');


$pdf->Ln(8);
	
	

   		 $dMovimientoAlmacen = mysql_fetch_assoc($rMovimientoAlmacen);
				
					$qE2    =	"SELECT * FROM operadores WHERE id_operador = ".$dMovimientoAlmacen['id_operador2']."";
					//print_r($qE2);
					$rE2	=	mysql_query($qE2);
					$dE2	=	mysql_fetch_assoc($rE2);
									
			$b = 0;
			$motivo = "";
			if($dMovimientoAlmacen['nuevo_movimiento'] 	!= "" ){ $motivo .= "Otro movimiento:" .$dMovimientoAlmacen['nuevo_movimiento']."\n "; $b = $b +1; }
			if($dMovimientoAlmacen['dia'] 				!= "" 	&& $dMovimientoAlmacen['dia'] 				!= "0000-00-00" && $dMovimientoAlmacen['movimiento'] != 9){	$motivo .= "Fecha a cubrir: ". fecha_tabla($dMovimientoAlmacen['dia'])."\n"; $b = $b +1; }
			if($dMovimientoAlmacen['horas'] 			!= "" 	&& $dMovimientoAlmacen['horas'] 			!= '0'){ $motivo .=  "Tiempo: ".$dMovimientoAlmacen['horas']."\n"; $b = $b +1; }
			if($dMovimientoAlmacen['cantidad'] 			!= "" 	&& $dMovimientoAlmacen['cantidad'] 			!= '0'){ $motivo .=  "Cantidad: $".$dMovimientoAlmacen['cantidad']."\n"; $b = $b +1; }
			if($dMovimientoAlmacen['desde'] 			!= "" 	&& $dMovimientoAlmacen['desde'] 			!= "0000-00-00" && ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .=  "Del: ".fecha_tabla($dMovimientoAlmacen['desde'])." al "; }
			if($dMovimientoAlmacen['hasta'] 			!= "" 	&& $dMovimientoAlmacen['hasta'] 			!= "0000-00-00" && ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .=  fecha_tabla($dMovimientoAlmacen['hasta'])."\n"; $b = $b +1;}
			if($dMovimientoAlmacen['numero_dias'] 		!= "" 	&& $dMovimientoAlmacen['numero_dias'] 		!= "0" 			&& ($dMovimientoAlmacen['movimiento'] == 9 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "No. de dias: ". $dMovimientoAlmacen['numero_dias']."\n"; $b = $b +1;}
			if($dMovimientoAlmacen['desde_tiempo'] 		!= "" 	&& $dMovimientoAlmacen['desde_tiempo']		!= "00:00" 		&& ($dMovimientoAlmacen['movimiento'] == 23 || $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "De la hora :"; $dMovimientoAlmacen['desde_tiempo']; $motivo .= " al ";}
			if($dMovimientoAlmacen['hasta_tiempo'] 		!= "" 	&& $dMovimientoAlmacen['hasta_tiempo']		!= "00:00" 		&& ($dMovimientoAlmacen['movimiento'] == 23 || $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "hasta la hora :"; $dMovimientoAlmacen['hasta_tiempo']; $motivo .= "\n"; $b = $b +1; }
			if($dMovimientoAlmacen['turno'] 			!= "" 	&& $dMovimientoAlmacen['turno'] 			!= 0 			&& ($dMovimientoAlmacen['movimiento'] == 4 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "Cambio al turno: ".	$dMovimientoAlmacen['turno']. "\n"; $b = $b +1; }
			if($dMovimientoAlmacen['movimientos.rol'] 	!= ""  	&& $dMovimientoAlmacen['movimientos.rol']   != 0  			&& ($dMovimientoAlmacen['movimiento'] == 5 	|| $dMovimientoAlmacen['movimiento'] == 20 )){ $motivo .= "Cambio al rol: ".$dMovimientoAlmacen['movimientos.rol']."\n"; $b = $b +1; }
			if($dMovimientoAlmacen['horario'] 			!= ""  	&& $dMovimientoAlmacen['horario'] 			!= '0' ){ $motivo .= "Cambio de horario: ".$dMovimientoAlmacen['horario']."\n"; $b = $b +1; }
			if($dMovimientoAlmacen['premio'] 			!= ""  	&& $dMovimientoAlmacen['premio'] 			!= 0 ){ $motivo .= "Lo que proceda con premios\n";$b = $b +1; }
			if($dMovimientoAlmacen['puntualidad'] 		!= ""  	&& $dMovimientoAlmacen['puntualidad']	 	!= 0 ){ $motivo .= "Afecta puntualidad\n";$b = $b +1; }
			if($dMovimientoAlmacen['productividad'] 	!= ""  	&& $dMovimientoAlmacen['productividad']		!= 0 ){ $motivo .= "Afecta productividad\n";$b = $b +1; }
			if($dMovimientoAlmacen['no_premio'] 		!= ""  	&& $dMovimientoAlmacen['no_premio'] 		!= 0 ){ $motivo .= "No afectar premio\n";$b = $b +1; }
			if($dMovimientoAlmacen['id_operador2'] 		!= "" 	&& $dMovimientoAlmacen['id_operador2'] 		!= 0 ){ $motivo .= "Se arreglo con: " .$dE2['numnomina']." - " .$dE2['nombre']."\n";$b = $b +1; }
			
			
			$h = $b * 6;
			$pdf->SetXY(40,30);
			$pdf->SetFont("Arial","",6);
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(25, 6, "FECHA DE EMISIÓN:", 1, 0, 'R',1);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(30, 6, fecha_tabla($dMovimientoAlmacen['fecha']), 1, 0, 'C');
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(20, 6, "SEMAN NO:", 1, 0, 'R',1);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(65, 6, $semana, 1, 1, 'L');
			

			$pdf->SetX(40);
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(25, 6, "NOMINA:", 1, 0, 'R',1);			
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(30,6, $dMovimientoAlmacen['numnomina'], 1, 0, 'C');
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(20, 6, "NOMBRE:", 1, 0, 'R',1);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(65, 6, $dMovimientoAlmacen['nombre'], 1, 1, 'L');


			
			$pdf->SetX(40);
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(25, 6, "DEPARTAMENTO: ", 1, 0, 'R',1);			
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(30,6, $areasEmpleado[$dMovimientoAlmacen['area']], 1, 0, 'C');
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(20, 6, "PUESTO:", 1, 0, 'R',1);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(65, 6, $dMovimientoAlmacen['puesto'], 1, 1, 'L');
			
			$pdf->SetX(40);
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(25, 6, "MOVIMIENTO:", 1, 0, 'R',1);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->Cell(115, 6, $motivos[$dMovimientoAlmacen['movimiento']], 1, 1, 'L');
									
			$pdf->SetX(40);
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(25, $h, "CARACTERISTICAS:", 1, 0, 'R',1);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->MultiCell(115, 6, $motivo, 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	
					
			$pdf->SetX(40);
			$pdf->SetTextColor(LETRA_TITULO_TABLA);
			$pdf->Cell(25, 6, "MOTIVO:", 1, 0, 'R',1);
			$pdf->SetTextColor(LETRA_CAMPOS);
			$pdf->MultiCell(115, 6, $dMovimientoAlmacen['motivo'], 1, 'L',$numero);
			$pdf->SetFont("Arial","",6);	
			
				
			$pdf->firmasP();

	$pdf->Close();
$pdf->Output('Asistencias del '.$_REQUEST['fecha'].'.pdf','I');

}




?>