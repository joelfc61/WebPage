<?php
define('FPDF_FONTPATH','libs/fpdf/font/');
require('libs/funciones.php');
require('libs/fpdf/fpdf.php');

define("ANCHO_MAXIMO",220);
define("ALTURA_MAXIMA",237);

/* Formato de tablas con OT y Kg */
define("MARGEN_DERECHO_TABLA",8);
define("MARGEN_IZQUIERDO_TABLA",3);
define("ANCHO_TABLA_C1",15);
define("ANCHO_TABLA_C2",10);
define("ANCHO_TABLA_C3",30);
define("ANCHO_TABLA_C4",35);

define("ANCHO_TABLA", MARGEN_DERECHO_TABLA + ANCHO_TABLA_C1 + ANCHO_TABLA_C2 + ANCHO_TABLA_C3 + ANCHO_TABLA_C4 );
define("ESPACIADO_ENTRE_TABLAS", 4);

define("FONDO_TITULO_TABLA", 		"#DDDDDD");
define("FONDO_TITULO_TABLA_2", 		"#EEEEEE");
define("FONDO_TITULO_TABLA2", 		"#0A4662");
define("FONDO_TITULO_TABLA3", 		"#FFFFFF");
define("FONDO_TITULO_TABLA_TOTAL", 	"#FF6633");

define("LETRA_TITULO_TABLA", 	"#FFFFFF");
define("LETRA_TITULO_TABLA2", 	"#000000");

define("LETRA_CAMPOS",		"#000000");
define("LETRA_TEXTO",		"#000000");
define("LETRA_FALTA",		"#FF0000");
define("LETRA_INCAPACIDAD",	"#0000FF");

class PDF extends FPDF{
	
	function Header(){
		$this->Image('images/head_pdf.jpg',0,0,210);
		$this->Ln(10);
	
	}

	function Footer(){
		$this->SetY(-10);
   		$this->SetFont('Arial','I',8);
		$this->Cell('0','','Pagina no. '.$this->PageNo().'/{nb}',0,0,'R');	
	} 
		
	function __escribeTabla($MySQL_resource = NULL, $id_maquina, $nMaquina	,  $numero = 'Maquina no '){
		global $TablaMayor;

		$tempX	=	$this->GetX();
		$tempY 	=	$this->GetY();
							
		$h	=	3.5;
	
		$this->SetFont('Arial','',6);
		$this->SetFillColor(FONDO_TITULO_TABLA2);
		$this->SetTextColor(LETRA_TITULO_TABLA);

		$this->SetX($tempX);
		$this->Cell(ANCHO_TABLA_C1+ANCHO_TABLA_C2+ANCHO_TABLA_C3+ ANCHO_TABLA_C4, $h , $numero. $nMaquina, 1, 0, 'C',1);
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
		
		$this->SetX($tempX);
		$this->Cell(ANCHO_TABLA_C1, $h , "Fecha", 1, 0, 'C',1);
		$this->Cell(ANCHO_TABLA_C2, $h , "Turno", 1, 0, 'C',1);
		if($_REQUEST['sup_h'] == 0){ 
			$ancho = ANCHO_TABLA_C3 + ANCHO_TABLA_C4;
			$this->Cell($ancho, $h , "Supervisor ", 1, 0, 'C',1);
		} 
			
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
			
		$this->SetTextColor(LETRA_CAMPOS);	
		for($a = 0; $dTiempo = mysql_fetch_assoc($MySQL_resource); $a++){
			$this->SetX($tempX);
			$this->Cell(ANCHO_TABLA_C1, $h , fecha_tabla($dTiempo['fecha']) , 1, 0, 'C');
			$this->Cell(ANCHO_TABLA_C2, $h , $dTiempo['turno'] , 1, 0, 'C');
			if($_REQUEST['sup_h'] == 0){
				$ancho = ANCHO_TABLA_C3 + ANCHO_TABLA_C4;
				$qS		=	"SELECT nombre FROM supervisor WHERE id_supervisor = ".$dTiempo['id_supervisor']."";
				$rS		=	mysql_query($qS);
				$dS		=	mysql_fetch_row($rS);
   			$this->SetFont('Arial','',4.5);
			$this->Cell($ancho, $h , $dS[0] , 1, 0, 'C');
   			$this->SetFont('Arial','',6);
			}

			$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
   			$this->SetFont('Arial','',6);
			$this->SetX($tempX);
		}						
	
		$TablaMayor	=	($this->GetY()>=$TablaMayor)?$this->GetY():$TablaMayor;
		
	}

	function TextWithDirection($x,$y,$txt,$direction='R'){
		$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
		if ($direction=='R')
			$s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$txt);
		elseif ($direction=='L')
			$s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$txt);
		elseif ($direction=='U')
			$s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$txt);
		elseif ($direction=='D')
			$s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$txt);
		else
			$s=sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$txt);
		if ($this->ColorFlag)
			$s='q '.$this->TextColor.' '.$s.' Q';
		$this->_out($s);
	}
}

$mes_d	=	(int)$_REQUEST['mes'];
$ano	=	$_REQUEST['anho'];
$mes_c	=	num_mes_cero($_REQUEST['anho']."-".$mes_d."-01");
$mesM	=	$_REQUEST['anho'].'-'.$mes_c.'-01';

$ultimo_dia = UltimoDia($ano,$mes_d);
$desde	= 	$_REQUEST['anho']."-".$mes_c."-01";
$hasta	= 	$_REQUEST['anho']."-".$mes_c."-".$ultimo_dia;

$pdf=&new PDF();
$pdf->FPDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->SetDisplayMode('real','continuous');

$pdf->AddPage();
$pdf->SetFont("Arial","",8);
$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);
$pdf->SetXY($pdf->getX()+6,23.5);
$pdf->SetFontSize(12);
$pdf->Cell(195, 4, "PRODUCCION DIARIA EXTRUDER, IMPRESION y BOLSEO", 0, 1, 'C');

$pdf->SetFontSize(9);
$pdf->SetXY(150,15);
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(12, 4, "MES : ", 1, 0, 'R',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(40, 4, $mes[$mes_d].' del '.$ano , 1, 1,'C');

/*********************************************************************************************************************************/
	$mes_array2	= 	array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
	
	$areas = array("EXTRUDER","IMPRESION","BOLSEO");
	
	$pdf->SetFontSize(8);
	$pdf->SetXY(31,30);
	
	foreach($areas as $area){
		if($area =='EXTRUDER'){//EXTRUDER E IMPRESION
			$pdf->SetFillColor("#E6E6E6");
			$pdf->Cell(38, 6, $area, 1, 0, 'C',1);
		}
		else if($area =='IMPRESION'){
			$pdf->SetFillColor("#F6E3CE");
			$pdf->Cell(38, 6, $area, 1, 0, 'C',1);
		}
		else if($area =='BOLSEO'){//BOLSEO
			$pdf->SetFillColor("#E0ECF8");
			$pdf->Cell(95, 6, $area, 1, 0, 'C',1);
		}
	}
	
	$pdf->Ln(6);
	$pdf->SetFillColor("#006699");
	$pdf->SetTextColor("#FFFFFF");
	$pdf->setX($pdf->getX()+2);
	$pdf->Cell(19, 6, "FECHA", 1, 0, 'C',1);

	foreach($areas as $area){
		if($area =='EXTRUDER' || $area =='IMPRESION'){//EXTRUDER E IMPRESION
			$pdf->Cell(19, 6, "KGS", 1, 0, 'C',1);
			$pdf->Cell(19, 6, "DESP.", 1, 0, 'C',1);
		}
		else if($area == 'BOLSEO'){//BOLSEO
			$pdf->Cell(19, 6, "MILLARES", 1, 0, 'C',1);
			$pdf->Cell(19, 6, "KGS", 1, 0, 'C',1);
			$pdf->Cell(19, 6, "2DAS", 1, 0, 'C',1);
			$pdf->Cell(19, 6, "TIRA", 1, 0, 'C',1);
			$pdf->Cell(19, 6, "TROQUEL", 1, 0, 'C',1);
		}
	}
	
	$pdf->Ln(6);
	for($i=1; $i<=$ultimo_dia; $i++){
		$pdf->setX($pdf->getX()+2);
		$dia = strlen($i)<2 ? "0".$i : $i;
		$pdf->SetFillColor("#FFFFFF");
		$pdf->SetTextColor("#000000");
		$pdf->Cell(19, 6, $dia."-".$mes_array2[$mes_d]."-".$ano, 1, 0, 'C',1);
		foreach($areas as $area){
			switch($area){
				case 'EXTRUDER':
					$pdf->SetFillColor("#E6E6E6");
					$qry1 = "SELECT SUM(p.total) as produccion, 
									SUM(p.desperdicio_tira+p.desperdicio_duro) as desperdicio
							 FROM entrada_general e, orden_produccion p
							 WHERE e.id_entrada_general = p.id_entrada_general
							 AND fecha = '$ano-$mes_c-$dia'
							 AND e.impresion = '0'
							 AND repesada = '1'";
					$rst1 = mysql_query($qry1);
					$row1 = mysql_fetch_assoc($rst1);
					$pdf->Cell(19, 6, (is_null($row1['produccion'])?"-":number_format($row1['produccion'],1)), 1, 0, 'C',1);
					$pdf->Cell(19, 6, (is_null($row1['produccion'])?"-":number_format($row1['desperdicio'],1)), 1, 0, 'C',1);
					break;
				case 'IMPRESION':
					$pdf->SetFillColor("#F6E3CE");
					$qry1 = "SELECT SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)) as produccion,
									SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)) as desperdicio
							 FROM entrada_general e, impresion i
							 WHERE e.id_entrada_general = i.id_entrada_general
							 AND fecha = '$ano-$mes_c-$dia'
							 AND e.impresion = '1'
							 AND repesada = '1'";
					$rst1 = mysql_query($qry1);
					$row1 = mysql_fetch_assoc($rst1);
					$pdf->Cell(19, 6, (is_null($row1['produccion'])?"-":number_format($row1['produccion'],1)), 1, 0, 'C',1);
					$pdf->Cell(19, 6, (is_null($row1['produccion'])?"-":number_format($row1['desperdicio'],1)), 1, 0, 'C',1);
					break;
				case 'BOLSEO':
					$pdf->SetFillColor("#E0ECF8");
					$qry1 = "SELECT SUM(b.millares) as prod_millares,
									SUM(b.kilogramos) as prod_kilos,
									SUM(b.segundas) as desp_segundas,
									SUM(b.dtira) as desp_tira,
									SUM(b.dtroquel) as desp_troquel
							 FROM bolseo b
							 WHERE b.fecha = '$ano-$mes_c-$dia'
							 AND repesada = '1'";
					$rst1 = mysql_query($qry1);
					$row1 = mysql_fetch_assoc($rst1);
					$pdf->Cell(19, 6, (is_null($row1['prod_millares'])?"-":number_format($row1['prod_millares'],1)), 1, 0, 'C',1);
					$pdf->Cell(19, 6, (is_null($row1['prod_kilos'])?"-":number_format($row1['prod_kilos'],1)), 1, 0, 'C',1);
					$pdf->Cell(19, 6, (is_null($row1['desp_segundas'])?"-":number_format($row1['desp_segundas'],1)), 1, 0, 'C',1);
					$pdf->Cell(19, 6, (is_null($row1['desp_tira'])?"-":number_format($row1['desp_tira'],1)), 1, 0, 'C',1);
					$pdf->Cell(19, 6, (is_null($row1['desp_troquel'])?"-":number_format($row1['desp_troquel'],1)), 1, 0, 'C',1);
					break;
			}
		}
		$pdf->Ln(6);
	}
    /* ****************************************** TOTALES ****************************************** */
	$pdf->setX($pdf->getX()+2);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor("#006699");
	$pdf->SetTextColor("#FFFFFF");
	$pdf->Cell(19, 6, "TOTALES:", 1, 0, 'C',1);
	$pdf->SetTextColor("#000000");
	foreach($areas as $area){
		switch($area){
			case 'EXTRUDER'://EXTRUDER
				$pdf->SetFillColor("#E6E6E6");
				$qry2 = "SELECT SUM(p.total) as produccion, 
								SUM(p.desperdicio_tira+p.desperdicio_duro) as desperdicio
						 FROM entrada_general e, orden_produccion p
						 WHERE e.id_entrada_general = p.id_entrada_general
						 AND fecha BETWEEN '$desde' AND '$hasta'
						 AND e.impresion = '0'
						 AND repesada = '1'";
				$rst2 = mysql_query($qry2);
				$row2 = mysql_fetch_assoc($rst2);
				$pdf->Cell(19, 6, number_format($row2['produccion'],1), 1, 0, 'C',1);
				$pdf->Cell(19, 6, number_format($row2['desperdicio'],1), 1, 0, 'C',1);
				break;
			case 'IMPRESION'://IMPRESION
				$pdf->SetFillColor("#F6E3CE");
				$qry2 = "SELECT SUM(IF(i.total_hd=0,i.total_bd,i.total_hd)) as produccion,
								SUM(IF(i.desperdicio_hd=0,i.desperdicio_bd,i.desperdicio_hd)) as desperdicio
						 FROM entrada_general e, impresion i
						 WHERE e.id_entrada_general = i.id_entrada_general
						 AND fecha BETWEEN '$desde' AND '$hasta'
						 AND e.impresion = '1'
						 AND repesada = '1'";
				$rst2 = mysql_query($qry2);
				$row2 = mysql_fetch_assoc($rst2);
				$pdf->Cell(19, 6, number_format($row2['produccion'],1), 1, 0, 'C',1);
				$pdf->Cell(19, 6, number_format($row2['desperdicio'],1), 1, 0, 'C',1);
				break;
				case 'BOLSEO'://BOLSEO
				$pdf->SetFillColor("#E0ECF8");
				$qry2 = "SELECT SUM(b.millares) as prod_millares,
								SUM(b.kilogramos) as prod_kilos,
								SUM(b.segundas) as desp_segundas,
								SUM(b.dtira) as desp_tira,
								SUM(b.dtroquel) as desp_troquel
						 FROM bolseo b
						 WHERE b.fecha BETWEEN '$desde' AND '$hasta'
						 AND repesada = '1'";
				$rst2 = mysql_query($qry2);
				$row2 = mysql_fetch_assoc($rst2);
				$pdf->Cell(19, 6, number_format($row2['prod_millares'],1), 1, 0, 'C',1);
				$pdf->Cell(19, 6, number_format($row2['prod_kilos'],1), 1, 0, 'C',1);
				$pdf->Cell(19, 6, number_format($row2['desp_segundas'],1), 1, 0, 'C',1);
				$pdf->Cell(19, 6, number_format($row2['desp_tira'],1), 1, 0, 'C',1);
				$pdf->Cell(19, 6, number_format($row2['desp_troquel'],1), 1, 0, 'C',1);
				break;
		}
	}

/*********************************************************************************************************************************/

//$pdf->Close();
$pdf->Output('Reporte_de_produccion_diaria'.$fecha.'.pdf','I');

?>