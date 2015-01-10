<?
define('FPDF_FONTPATH','../libs/fpdf/font/');
require('../libs/funciones.php');
require('../libs/fpdf/fpdf.php');
define("ANCHO_MAXIMO",220);

/* Formato de tablas con OT y Kg */
define("MARGEN_DERECHO_TABLA",3);
define("MARGEN_IZQUIERDO_TABLA",3);
define("ANCHO_TABLA_C1",20);
define("ANCHO_TABLA_C2",23);

define("ANCHO_TABLA", MARGEN_DERECHO_TABLA + ANCHO_TABLA_C1 + ANCHO_TABLA_C2 + MARGEN_IZQUIERDO_TABLA);
define("ESPACIADO_ENTRE_TABLAS", 10);

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

	function __escribeTabla($MySQL_resource = NULL, $subtotal = 0, $nombreOperador, $numero, $extruder = 'Impr ', $tituloA = 'O.T.',$tituloB='Kg')
	{
		global $TablaMayor;
		$h	=	5;
		$this->SetFillColor(FONDO_TITULO_TABLA);
		$this->SetTextColor(LETRA_TITULO_TABLA);

		$tempX	=	$this->GetX();
		$tempY 	=	$this->GetY();

		$this->SetX($tempX);		
		$this->Cell(ANCHO_TABLA_C1-10,$h, $extruder.$numero ,1,0,'C',1);
		$this->SetTextColor(LETRA_CAMPOS);
   		$this->SetFont('Arial','',5);
		$this->Cell(ANCHO_TABLA_C2+10,$h,$nombreOperador,1,0,'C');
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);		
		$this->SetTextColor(LETRA_TITULO_TABLA);
   		$this->SetFont('Arial','',8);
		$this->SetTextColor(LETRA_TITULO_TABLA);


		$this->SetX($tempX);		
		$this->Cell(ANCHO_TABLA_C1-10,$h,$tituloA,1,0,'C',1);
		$this->Cell(ANCHO_TABLA_C2+10,$h,$tituloB,1,0,'C',1);
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
		
		$this->SetTextColor(LETRA_CAMPOS);
		$this->SetFillColor(FONDO_TITULO_TABLA2);
		
		for($a = 0; $row = mysql_fetch_row($MySQL_resource); $a++)
		{
			$c= 0;
			$this->SetX($tempX);
			$this->Cell(ANCHO_TABLA_C1-10,$h,$row[0],1,0,'C',$c);
			$this->Cell(ANCHO_TABLA_C2+10,$h,number_format($row[1],2,".",","),1,0,'R', $c);
			$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
		}
		
   		$this->SetFont('Arial','',7);
		$this->SetFillColor(FONDO_TITULO_TABLA2);
		$this->SetX($tempX);
		$this->SetTextColor(LETRA_CAMPOS);
		$this->Cell(ANCHO_TABLA_C1-10,$h,"TOTAL",0,0,'R');
   		$this->SetFont('Arial','',8);
		$this->SetTextColor(LETRA_CAMPOS);
		$this->Cell(ANCHO_TABLA_C2+10,$h,"" . number_format($subtotal,2,".",","),1,0,'R',1);
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
		$this->SetFillColor(FONDO_TITULO_TABLA);
		$TablaMayor	=	($this->GetY()>$TablaMayor)?$this->GetY():$TablaMayor;
		

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

$qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre FROM entrada_general INNER JOIN supervisor ".
						"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE (id_entrada_general='{$_REQUEST[id_reporte]}')";
$rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");
if(mysql_num_rows($rEntradaGeneral) == 0)
{
	// No existe el id
	exit();
}

$dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral);

$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(190, 6, "IMPRESION", 0,1, 'C');
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

$pdf->Ln(5);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(190, 6, "IMPRESION", 0, 1, 'C');
$pdf->Ln(5);


/* IMPRESION */


$qImpresion	=	"SELECT * FROM impresion WHERE (id_entrada_general = {$dEntradaGeneral[id_entrada_general]})";
$rImpresion	=	mysql_query($qImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");

if(mysql_num_rows($rImpresion) == 0)
{
	$pdf->Output();
	exit();
}

$dImpresion	=	mysql_fetch_assoc($rImpresion);



$qResumenImpresion	=	"SELECT * FROM resumen_maquina_im LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".
						" LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador WHERE (id_impresion={$dImpresion[id_impresion]} AND linea_impresion = 0)";
$rResumenImpresion	=	mysql_query($qResumenImpresion) OR die("<p>$qResumenImpresion</p><p>".mysql_error()."</p>");

$X_Original	=	$pdf->GetX();
$Y_Original	=	$pdf->GetY();

$X_Actual	=	$pdf->GetX();
$Y_Actual	=	$pdf->GetY();

for(;$dResumenImpresion	=	mysql_fetch_assoc($rResumenImpresion);$X_Actual+= ANCHO_TABLA)
{
	if($X_Actual + ANCHO_TABLA > ANCHO_MAXIMO)
	{
		$Y_Actual	=	$TablaMayor + ESPACIADO_ENTRE_TABLAS;
		$X_Actual	=	$X_Original;
		$TablaMayor	=	0;
	}

	$qMaquina	=	"SELECT numero FROM maquina WHERE (id_maquina = {$dResumenImpresion[id_maquina]})";
	$rMaquina	=	mysql_query($qMaquina) OR die("<p>$qMaquina</p><p>".mysql_error()."</p>");	
	$dMaquina	=	mysql_fetch_assoc($rMaquina);


	$qDetalleResumen	=	"SELECT orden_trabajo, kilogramos FROM detalle_resumen_maquina_im WHERE (id_resumen_maquina_im = {$dResumenImpresion[id_resumen_maquina_im]})";
	$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

	$pdf->SetY($Y_Actual);
	$pdf->SetX($X_Actual);
	$pdf->__escribeTabla($rDetalleResumen,$dResumenImpresion['subtotal'],$dResumenImpresion['nombre'],  $dMaquina['numero']);
}

/* Linea Impresión */

$pdf->Ln(20);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(190, 6, "LINEA DE IMPRESION", 0, 1, 'C');
$pdf->Ln(5);

$qResumenLineaImpresion	=	"SELECT maquina.numero, operadores.nombre, subtotal, id_resumen_maquina_im, resumen_maquina_im.id_maquina FROM resumen_maquina_im LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".
							" LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador WHERE (id_impresion={$dImpresion[id_impresion]} AND linea_impresion = 1)";
$rResumenLineaImpresion	=	mysql_query($qResumenLineaImpresion) OR die("<p>$qResumenLineaImpresion</p><p>".mysql_error()."</p>");

$X_Original	=	$pdf->GetX();
$Y_Original	=	$pdf->GetY();

$X_Actual	=	$pdf->GetX();
$Y_Actual	=	$pdf->GetY();

for(;$dResumenLineaImpresion	=	mysql_fetch_assoc($rResumenLineaImpresion);$X_Actual+= ANCHO_TABLA)
{
	if($X_Actual + ANCHO_TABLA > ANCHO_MAXIMO)
	{
		$Y_Actual	=	$TablaMayor + ESPACIADO_ENTRE_TABLAS;
		$X_Actual	=	$X_Original;
		$TablaMayor	=	0;
	}


	if($Y_Actual >= 240)
	{
		$pdf->AddPage();
		$Y_Actual	=	$pdf->GetY();
		$X_Actual	=	$pdf->GetX();
	}


	$qMaquina	=	"SELECT numero FROM maquina WHERE (id_maquina = {$dResumenLineaImpresion[id_maquina]})";
	$rMaquina	=	mysql_query($qMaquina) OR die("<p>$qMaquina</p><p>".mysql_error()."</p>");	
	$dMaquina	=	mysql_fetch_assoc($rMaquina);

	$qDetalleResumen	=	"SELECT orden_trabajo, kilogramos FROM detalle_resumen_maquina_im WHERE (id_resumen_maquina_im = {$dResumenLineaImpresion[id_resumen_maquina_im]})";
	$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");

	$pdf->SetY($Y_Actual);
	$pdf->SetX($X_Actual);
	$pdf->__escribeTabla($rDetalleResumen,$dResumenLineaImpresion['subtotal'],$dResumenLineaImpresion['nombre'], $dMaquina['numero'], $ma = "Lin ");
}

$pdf->Ln(15);
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Total HD", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6,  number_format($dImpresion['total_hd'],2,"."," ") , 1, 0, 'C');
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Total BD", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6,  number_format($dImpresion['total_bd'],2,"."," ") , 1, 1, 'C');

$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Desperdicio HD", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6,number_format($dImpresion['desperdicio_hd'],2,"."," "), 1, 0, 'C');
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Desperdicio BD", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(47, 6,  number_format($dImpresion['desperdicio_bd'],2,"."," "), 1, 1, 'C');

$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(48, 6, "Observaciones", 1, 0, 'L',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->MultiCell(142, 6, $dImpresion['observaciones'], 1, 'L');
/* MultiCell(float w, float h, string txt [, mixed border [, string align [, int fill]]]) */


$pdf->Output();

?>