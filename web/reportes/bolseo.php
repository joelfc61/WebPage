<?php
define('FPDF_FONTPATH','../libs/fpdf/font/');
require('../libs/funciones.php');
require('../libs/fpdf/fpdf.php');

define("ANCHO_MAXIMO",220);

/* Formato de tablas con OT y Kg */
define("MARGEN_DERECHO_TABLA",19);
define("MARGEN_IZQUIERDO_TABLA",3);
define("ANCHO_TABLA_C1",10);
define("ANCHO_TABLA_C2",15);
define("ANCHO_TABLA_C3",15);
define("ANCHO_TABLA_C4",15);
define("ANCHO_TABLA_C5",15);
define("ANCHO_TABLA_C6",15);

define("ANCHO_TABLA", MARGEN_DERECHO_TABLA + ANCHO_TABLA_C1 + ANCHO_TABLA_C2 + ANCHO_TABLA_C3 + ANCHO_TABLA_C4 + ANCHO_TABLA_C5 + ANCHO_TABLA_C6 );
define("ESPACIADO_ENTRE_TABLAS", 5);

define("FONDO_TITULO_TABLA", "#0A4662");
define("FONDO_TITULO_TABLA2", "#EEEEEE");
define("LETRA_TITULO_TABLA", "#FFFFE8");
define("LETRA_CAMPOS","#000000");
define("LETRA_TEXTO","#217A9E");


class PDF extends FPDF
{
	function Header()
	{
		$this->Image('../images/head_pdf.jpg',0,0,210);
		$this->Ln(12);
	}

	function Footer()
	{
		$this->SetY(-15);
		$this->Image('../images/foot_pdf.jpg',0,279,225);
	}
	
	function __escribeTabla($MySQL_resource = NULL, $subtotal = 0, $subtotal1 = 0, $subtotal2 = 0, $subtotal3 = 0, $subtotal4 = 0, $nombreOperador, $numero, $bolseo = 'Bolseadora :', $tituloA = 'O.T.',$tituloB='Kg', $tituloC='Mi', $tituloD='Tira', $tituloE='Troquel', $tituloF='Seg' )
	{
		global $TablaMayor;
		$h = 4;
		
		$this->SetFillColor(FONDO_TITULO_TABLA);
		$this->SetTextColor(LETRA_TITULO_TABLA);

		$tempX	=	$this->GetX();
		$tempY 	=	$this->GetY();
		
		
		$this->SetX($tempX);	
		$this->Cell(ANCHO_TABLA_C1+ANCHO_TABLA_C2,$h,$bolseo."  ". $numero ,1,0,'L',1);
		$this->SetTextColor(LETRA_CAMPOS);
		$this->SetFont("Arial","",6);

		$this->Cell(ANCHO_TABLA_C3+ANCHO_TABLA_C4+ANCHO_TABLA_C5+ANCHO_TABLA_C6,$h,"Opr. ". $nombreOperador,1,0,'L');
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
		$this->SetTextColor(LETRA_TITULO_TABLA);
		$this->SetFont("Arial","",8);

		
		$this->SetX($tempX);	
		$this->Cell(ANCHO_TABLA_C1,$h,$tituloA,1,0,'C',1);
		$this->Cell(ANCHO_TABLA_C2,$h,$tituloB,1,0,'C',1);
		$this->Cell(ANCHO_TABLA_C3,$h,$tituloC,1,0,'C',1);
		$this->Cell(ANCHO_TABLA_C4,$h,$tituloD,1,0,'C',1);
		$this->Cell(ANCHO_TABLA_C5,$h,$tituloE,1,0,'C',1);
		$this->Cell(ANCHO_TABLA_C6,$h,$tituloF,1,0,'C',1);
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);		
		
		$this->SetTextColor(LETRA_CAMPOS);
		$this->SetFillColor(FONDO_TITULO_TABLA2);
		for($a = 0; $row = mysql_fetch_row($MySQL_resource); $a++)
		{
			$this->SetX($tempX);
			$c = 0;
			$this->Cell(ANCHO_TABLA_C1,$h, $row[0],1,0,'C',$c);
			$this->Cell(ANCHO_TABLA_C2,$h, number_format($row[1],2,".",","),1,0,'R',$c);
			$this->Cell(ANCHO_TABLA_C3,$h, number_format($row[2],2,".",","),1,0,'R',$c);
			$this->Cell(ANCHO_TABLA_C4,$h, number_format($row[3],2,".",","),1,0,'R',$c);
			$this->Cell(ANCHO_TABLA_C5,$h, number_format($row[4],2,".",","),1,0,'R',$c);
			$this->Cell(ANCHO_TABLA_C6,$h, number_format($row[5],2,".",","),1,0,'R',$c);
			$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
		}
		
		$this->SetFillColor(FONDO_TITULO_TABLA2);
		$this->SetX($tempX);
		$this->SetTextColor(LETRA_CAMPOS);
		$this->Cell(ANCHO_TABLA_C1,$h,"Total",0,0,'R');
		$this->SetTextColor(LETRA_CAMPOS);
		$this->Cell(ANCHO_TABLA_C2,$h,"" . number_format($subtotal,2,".",","),1,0,'R',1);
		$this->Cell(ANCHO_TABLA_C3,$h,"" . number_format($subtotal1,2,".",","),1,0,'R',1);
		$this->Cell(ANCHO_TABLA_C4,$h,"" . number_format($subtotal2,2,".",","),1,0,'R',1);
		$this->Cell(ANCHO_TABLA_C5,$h,"" . number_format($subtotal3,2,".",","),1,0,'R',1);
		$this->Cell(ANCHO_TABLA_C6,$h,"" . number_format($subtotal4,2,".",","),1,0,'R',1);
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
		$this->SetFillColor(FONDO_TITULO_TABLA);
		$this->Ln(8);

		$TablaMayor	=	($this->GetY()>$TablaMayor)?$this->GetY():$TablaMayor;
		
		if($tempY > '250')
		{ 
			$this->AddPage();
		}
	}

	function TextWithDirection($x,$y,$txt,$direction='R')
	{
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

if(!isset($_REQUEST['id_reporte']))
{
	exit();
}


$pdf=&new PDF();
$pdf->SetFont("Arial","",8);
$pdf->AliasNbPages();

$pdf->AddPage();

$qEntradaGeneral	=	"SELECT id_bolseo, fecha, turno, supervisor.nombre FROM bolseo INNER JOIN supervisor ".
						"ON bolseo.id_supervisor = supervisor.id_supervisor WHERE (id_bolseo='{$_REQUEST[id_reporte]}')";
$rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");
if(mysql_num_rows($rEntradaGeneral) == 0)
{
	// No existe el id
	exit();
}

$dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral);

$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(190, 6, "REPORTE DE BOLSEO", 0, 1, 'C');
$pdf->Ln(5);


$pdf->SetFillColor(FONDO_TITULO_TABLA);

$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Fecha", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6, preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha']), 1, 0, 'C');
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Turno", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6, $dEntradaGeneral['turno'], 1, 1, 'C');

$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Supervisor", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(142, 6, $dEntradaGeneral['nombre'], 1, 1, 'C');

/*		*/

$pdf->Ln(10);


$qOrdenProduccion	=	"SELECT * FROM bolseo WHERE (id_bolseo = '{$dEntradaGeneral[id_bolseo]}')";
$rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");

if(mysql_num_rows($rOrdenProduccion) == 0)
{
	$pdf->Output();
	exit();
}

$dOrdenProduccion	=	mysql_fetch_assoc($rOrdenProduccion);



$qResumenMaquinas	=	"SELECT maquina.numero, operadores.nombre, kilogramos, millares, dtira, dtroquel, segundas,id_resumen_maquina_bs, resumen_maquina_bs.id_maquina FROM resumen_maquina_bs INNER JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina ".
						" LEFT JOIN operadores ON resumen_maquina_bs.id_operador = operadores.id_operador WHERE (id_bolseo={$dOrdenProduccion[id_bolseo]})";
$rResumenMaquinas	=	mysql_query($qResumenMaquinas) OR die("<p>$qResumenMaquinas</p><p>".mysql_error()."</p>");

$X_Original	=	$pdf->GetX();
$Y_Original	=	$pdf->GetY();

$X_Actual	=	$pdf->GetX();
$Y_Actual	=	$pdf->GetY();

for(;$dResumenMaquinas	=	mysql_fetch_assoc($rResumenMaquinas);$X_Actual+= ANCHO_TABLA)
{
	if($X_Actual + ANCHO_TABLA > ANCHO_MAXIMO)
	{
		$Y_Actual	=	$TablaMayor + ESPACIADO_ENTRE_TABLAS;
		$X_Actual	=	$X_Original;
		$TablaMayor	=	0;
	}	
	
	
		if($Y_Actual >= 250)
	{
		$pdf->AddPage();
		$Y_Actual	=	$pdf->GetY();
		$X_Actual	=	$pdf->GetX();
	}
	
	$qDetalleResumen	=	"SELECT orden, kilogramos, millares, dtira, dtroquel, segundas FROM detalle_resumen_maquina_bs WHERE (id_resumen_maquina_bs = {$dResumenMaquinas[id_resumen_maquina_bs]})";
	$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

	$qMaquina	=	"SELECT numero FROM maquina WHERE (id_maquina = {$dResumenMaquinas[id_maquina]})";
	$rMaquina	=	mysql_query($qMaquina) OR die("<p>$qMaquina</p><p>".mysql_error()."</p>");	
	$dMaquina	=	mysql_fetch_assoc($rMaquina);


	$pdf->SetY($Y_Actual);
	$pdf->SetX($X_Actual);
	
	$pdf->__escribeTabla($rDetalleResumen,$dResumenMaquinas['kilogramos'] ,$dResumenMaquinas['millares'], $dResumenMaquinas['dtira'],$dResumenMaquinas['dtroquel'],$dResumenMaquinas['segundas'],  $dResumenMaquinas['nombre'], $dMaquina['numero']);
}

$pdf->Ln(18);

$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Kilogramos Totales", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6, "" . number_format($dOrdenProduccion['kilogramos'],2,"."," ") , 1, 0, 'C');

$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Millares Totales", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6, "" . number_format($dOrdenProduccion['millares'],2,"."," ") , 1, 1, 'C');


$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Desperdicio Tira", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6, number_format($dOrdenProduccion['dtira'],2,"."," "), 1, 0, 'C');
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Troquel", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6,  number_format($dOrdenProduccion['dtroquel'],2,"."," "), 1, 1, 'C');
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Segundas", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6,  number_format($dOrdenProduccion['segundas'],2,"."," "), 1, 1, 'C');


$pdf->Output();



?>