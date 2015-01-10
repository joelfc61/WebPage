<?php
include "../libs/conectar.php";
define('FPDF_FONTPATH','../libs/fpdf/font/');
require('../libs/funciones.php');
require('../libs/fpdf/fpdf.php');
define("BASE_X",30);
define("BASE_Y",20);

define("ALTURA_BARRA",15);

define("ANCHO_TOTAL",190);
define("ALTO_TOTAL",130);

define("ESCALA_ANCHO", 30);
define("ANCHO_ACOTACIONES",30);
$nombres	=	array("Prueba 1","Prueba 2","Prueba 3","Prueba 4");
define("NUMERO_ACOTACIONES",sizeof($nombres));
define("ANCHO_CELDA_ACOTACION",ESCALA_ANCHO/NUMERO_ACOTACIONES);

define("ANCHO_LINEA_1",10);
define("ANCHO_LINEA_2",10);
define("ANCHO_LINEA_3",10);
$ANCHO_LINEAS	=	array(ANCHO_LINEA_1,ANCHO_LINEA_2,ANCHO_LINEA_3);
define("TOTAL_ANCHO_LINEAS", array_sum($ANCHO_LINEAS) );

define("BASE_GRAFICO_X",BASE_X+ANCHO_ACOTACIONES);
define("BASE_GRAFICO_Y",ESCALA_ANCHO+BASE_Y);

class PDF extends FPDF
{
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

 $qGeneral		=	"SELECT SUM(kilogramos) FROM bolseo WHERE (MONTH(fecha) = '{$_REQUEST[m]}' AND YEAR(fecha) = '{$_REQUEST[a]}') ";
$rGeneral		=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");
list($totalKG)		=	mysql_fetch_row($rGeneral);

 $qDetalles		=	"SELECT bolseo.id_supervisor,supervisor.nombre,SUM(dtira),SUM(dtroquel),SUM(segundas) FROM bolseo LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor WHERE (MONTH(fecha) = '{$_REQUEST[m]}' AND YEAR(fecha) = '{$_REQUEST[a]}') GROUP BY id_supervisor";
$rDetalles		=	mysql_query($qDetalles) OR die("<p>$qDetalles</p><p>".mysql_error()."</p>");

$nSupervisores	=	array();
$dColumnas		=	array();
$nombres		=	array();
$vMaximo		=	0;

while( $dDetalles = mysql_fetch_row($rDetalles) )
{
	$nombres[]		=	$dDetalles[1];
	$dColumnas[]	=	array($dDetalles[2],$dDetalles[3],$dDetalles[4]);
	$vMaximo		=	($vMaximo < max($dDetalles[2],$dDetalles[3],$dDetalles[4]))?max($dDetalles[2],$dDetalles[3],$dDetalles[4]):$vMaximo;
}

$titulos		=	array("Segunda","Troquel","Tiro");

$nColumnas	=	sizeof($dColumnas);

$colores	=	array("#FF0000","#00FF00","#0000FF","#FFFF00","#8B00FF","#000000","#0000FF","#333333","#FF0000","#00FF00","#0000FF","#333333");
$imagenes	=	array("amar.jpg","azul.jpg","verde.jpg","gris.jpg");
//$bordes		=	array("amar1.jpg","azul1.jpg","verde1.jpg","gris1.jpg");
$bordes		=	array("amar1.png","azul1.png","verde1.png","gris1.png");

$pdf = & new PDF();
$pdf->SetFont("Arial","",8);
$pdf->AliasNbPages();
$pdf->AddPage();

/* Grafico */
$pdf->Rect(BASE_GRAFICO_X,BASE_GRAFICO_Y,ALTO_TOTAL,ESCALA_ANCHO+ANCHO_TOTAL);

$cienRelativo	=	round($vMaximo, intval(strlen($vMaximo)-2) * -1) + intval("1" . str_repeat("0",intval(strlen($vMaximo)-2)));
$anchoColumnas	=	((ANCHO_TOTAL+ESCALA_ANCHO) / $nColumnas);
$espaciado		=	($anchoColumnas - TOTAL_ANCHO_LINEAS)/2;

/* Escala */
$pdf->SetFillColor("#333333");
$pdf->Rect(BASE_X+ANCHO_ACOTACIONES,BASE_Y,ALTO_TOTAL,ESCALA_ANCHO,'FD');

$pdf->SetLineWidth(0.05);
$pdf->SetTextColor("#FFFFFF");
//for($i=1, $divisiones = ALTO_TOTAL / 10, $escala = round($vMaximo, intval(strlen($vMaximo)-2) * -1) / 10;$i<10;$i++)
for($i=1, $divisiones = (($vMaximo/$cienRelativo)*ALTO_TOTAL) / 10, $escala = round($vMaximo, intval(strlen($vMaximo)-2) * -1) / 10;$i<=10;$i++)
{
	//$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2,BASE_Y, " " . ceil($escala*$i),'D');
	$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2,BASE_Y, " " . ceil($escala*$i),'D');
	$pdf->Line(BASE_GRAFICO_X+($i*$divisiones),BASE_GRAFICO_Y,BASE_GRAFICO_X+($i*$divisiones),BASE_GRAFICO_Y+ESCALA_ANCHO+ANCHO_TOTAL);
}

$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2.5,BASE_Y,ceil($escala*$i),'D');
$pdf->SetLineWidth(0.2);

$pdf->SetTextColor("#FF0000");
for($i=0, $colActual = BASE_GRAFICO_Y  ;$i<$nColumnas;$i++, $colActual+=$anchoColumnas )
{
	$pdf->SetFillColor($colores[$i]);
	for($a=0,$Y	= $colActual+$espaciado ; $a<sizeof($ANCHO_LINEAS); $a++,$Y+=$ANCHO_LINEAS[$a])
	{
		//echo "dColumnas[$i][$a] = ".$dColumnas[$i][$a] . "<br />";
		$alturaBarra	=	($dColumnas[$i][$a] / $cienRelativo) * ALTO_TOTAL;
		$pdf->Image("imgs/" . $imagenes[$a],BASE_GRAFICO_X,$Y,$alturaBarra-ALTURA_BARRA,$ANCHO_LINEAS[$a]);
		$pdf->Image("imgs/" . $bordes[$a],BASE_GRAFICO_X+$alturaBarra-ALTURA_BARRA,$Y,ALTURA_BARRA,$ANCHO_LINEAS[$a]);
	}
}

/* Definición de Colores */
$pdf->SetFillColor("#666666");
$pdf->Rect(BASE_X,BASE_Y,ESCALA_ANCHO,ANCHO_ACOTACIONES,'FD');

/* Tabla de acotaciones */
$pdf->SetFillColor("#999999");
$pdf->Rect(BASE_X,BASE_Y+ESCALA_ANCHO,ANCHO_ACOTACIONES,ESCALA_ANCHO+ANCHO_TOTAL,'FD');

/* Líneas de división de la tabla */
$pdf->SetTextColor("#FFFFFF");
for($i=BASE_X + ANCHO_CELDA_ACOTACION, $a=0 ;$i<=BASE_X+ESCALA_ANCHO; $i+=ANCHO_CELDA_ACOTACION, $a++)
	$pdf->Line($i,BASE_Y,$i,BASE_Y+ANCHO_TOTAL+ESCALA_ANCHO*2);

// Titulos de columnas
for($i=BASE_X + ANCHO_CELDA_ACOTACION, $j=0 ;$j<=sizeof($dColumnas); $i+=ANCHO_CELDA_ACOTACION, $j++)
	$pdf->TextWithDirection($i-ANCHO_CELDA_ACOTACION+ANCHO_CELDA_ACOTACION/2,BASE_Y,"  ". $titulos[$j],'D');
// Datos de las gráficas
for($colActual = BASE_GRAFICO_Y, $j=0 ;$j<=sizeof($dColumnas);$j++, $colActual+= $anchoColumnas)
	for($a=sizeof($dColumnas[$j])-1,$k=0;$a>=0;$a--,$k++)
		$pdf->TextWithDirection(BASE_X + ($k * ANCHO_CELDA_ACOTACION) + (ANCHO_CELDA_ACOTACION / 2)-1,$colActual,"  ".round($dColumnas[$j][$a] / $totalKG * 100,2)." %",'D');

for($i=0, $Y=BASE_GRAFICO_Y;$i<=$nColumnas;$i++,$Y+=$anchoColumnas)
	$pdf->Line(BASE_X,$Y,BASE_X+ANCHO_ACOTACIONES,$Y);
$pdf->Output();
?>