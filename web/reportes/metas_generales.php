<?php
include "../libs/conectar.php";
define('FPDF_FONTPATH','../libs/fpdf/font/');
require('../libs/funciones.php');
require('../libs/fpdf/fpdf.php');

switch(intval($_REQUEST['area']))
{
	case 1:
		$qTotalKG	=	"SELECT SUM(total) FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general ".
						" WHERE (MONTH(entrada_general.fecha) = '{$_REQUEST[m]}' AND YEAR(entrada_general.fecha)  = '{$_REQUEST[a]}') GROUP BY orden_produccion.id_entrada_general";
	 	$qMetas		=	"SELECT total_hd,desp_duro_hd FROM meta WHERE area = 1 ORDER BY mes, ano ASC";
		break;
	case 2:
		$qTotalKG	=	"SELECT SUM(total_hd) FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
						" WHERE (MONTH(entrada_general.fecha) = '{$_REQUEST[m]}' AND YEAR(entrada_general.fecha)  = '{$_REQUEST[a]}') GROUP BY impresion.id_entrada_general";
		$qMetas		=	"SELECT total_hd,desp_duro_hd FROM meta WHERE area = 2 ORDER BY mes, ano ASC";
		break;
	case 3:
		$qTotalKG	=	"SELECT SUM(millares) FROM bolseo WHERE (MONTH(fecha) = '{$_REQUEST[m]}' AND YEAR(fecha) = '{$_REQUEST[a]}')";
		$qMetas		=	"SELECT total_hd,desp_duro_hd FROM meta WHERE area = 3 ORDER BY mes, ano ASC";
		break;
}

if(!empty($qTotalKG))
{
	$rTotalKG	=	mysql_query($qTotalKG) OR die("<p>$qTotalKG</p><p>".mysql_error()."</p>");
	list($totalKG)	=	mysql_fetch_row($rTotalKG);

	$rMetas		=	mysql_query($qMetas) OR die("<p>$qMetas</p><p>".mysql_error()."</p>");
	$Metas		=	mysql_num_rows($rMetas);
}


define("BASE_X",30);
define("BASE_Y",20);

define("ALTURA_BARRA",15);

define("ANCHO_TOTAL",100);
define("ALTO_TOTAL",210);

define("ESCALA_ANCHO", 30);
define("ANCHO_ACOTACIONES",30);
$nombres	=	array("Prueba 1","Prueba 2","Prueba 3","Prueba 4","Prueba 5");
define("NUMERO_ACOTACIONES",sizeof($nombres));
define("ANCHO_CELDA_ACOTACION",ESCALA_ANCHO/NUMERO_ACOTACIONES);

define("ANCHO_LINEA_1",40);
$ANCHO_LINEAS	=	array(ANCHO_LINEA_1);
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

	$pdf = & new PDF("L");
	$pdf->SetFont("Arial","",8);
	$pdf->AliasNbPages();
	$pdf->AddPage();

if( $_REQUEST['y'] == 1 || $_REQUEST['y'] == 2 )
{
	$dColumnas[]	=	array($totalKG);
	$dColumnas[]	=	array($Metas[0]);
	$dColumnas[]	=	array($totalKG - $Metas[0]);
	//$dColumnas[]	=	array(150);
	
	$nColumnas	=	sizeof($dColumnas);
	
	$colores	=	array("#FF0000","#00FF00","#0000FF","#FFFF00","#8B00FF","#000000","#0000FF","#333333","#FF0000","#00FF00","#0000FF","#333333");
	$imagenes	=	array("amar.jpg","azul.jpg","verde.jpg","gris.jpg");
	$bordes		=	array("amar1.png","azul1.png","verde1.png","gris1.png");
	$titulos	=	array("Diferencia","Meta Kilos","Producción Kilos");
	
	/* Grafico */
	$pdf->Rect(BASE_GRAFICO_X,BASE_GRAFICO_Y,ALTO_TOTAL,ESCALA_ANCHO+ANCHO_TOTAL);
	
	$vMaximo	=	0;
	
	foreach($dColumnas as $columna)
	{
		 $t	=	max($columna);
		 $vMaximo	=	($t > $vMaximo)?$t:$vMaximo;
	}
		
 	//$cienRelativo	=	round($vMaximo, intval(strlen($vMaximo)-2) * -1) + intval("1" . str_repeat("0",intval(strlen($vMaximo) -2)));
 	$cienRelativo	=	round($vMaximo, intval(strlen($vMaximo)-2) * -1) + intval("1" . str_repeat("0",intval(strlen($vMaximo) -2)));	
	$anchoColumnas	=	((ANCHO_TOTAL+ESCALA_ANCHO) / $nColumnas);
	$espaciado		=	($anchoColumnas - TOTAL_ANCHO_LINEAS)/2;
	
	/* Escala */
	$pdf->SetFillColor("#333333");
	$pdf->Rect(BASE_X+ANCHO_ACOTACIONES,BASE_Y,ALTO_TOTAL,ESCALA_ANCHO,'FD');
	
	$pdf->SetLineWidth(0.05);
	$pdf->SetTextColor("#FFFFFF");
	for($i=1, $divisiones = (($vMaximo/$cienRelativo)*ALTO_TOTAL) / 10, $escala = round($vMaximo, intval(strlen($vMaximo)-2) * -1) / 10;$i<=10;$i++)
	{
		//$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2,BASE_Y, " " . ceil($escala*$i),'D');
		$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2,BASE_Y, " " . ceil($escala*$i),'D');
		$pdf->Line(BASE_GRAFICO_X+($i*$divisiones),BASE_GRAFICO_Y,BASE_GRAFICO_X+($i*$divisiones),BASE_GRAFICO_Y+ESCALA_ANCHO+ANCHO_TOTAL);
	}
	
	$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2.5,BASE_Y,ceil($escala*$i),'D');
	$pdf->SetLineWidth(0.2);
	//$maskImg = $pdf->Image("reportes/imgs/trans.png", 0,0,0,0, '', '', true);
	
	for($i=0, $colActual = BASE_GRAFICO_Y ;$i<$nColumnas;$i++, $colActual+=$anchoColumnas )
	{
		$pdf->SetFillColor($colores[$i]);
		for($a=0,$Y	= $colActual+$espaciado ; $a<sizeof($ANCHO_LINEAS); $a++,$Y+=$ANCHO_LINEAS[$a])
		{
			$alturaBarra	=	($dColumnas[$i][$a] / $cienRelativo) * ALTO_TOTAL;
			$pdf->Image("reportes/imgs/" . $imagenes[$i],BASE_GRAFICO_X,$Y,$alturaBarra-ALTURA_BARRA,$ANCHO_LINEAS[$a]);
			$pdf->Image("reportes/imgs/" . $bordes[$i],BASE_GRAFICO_X+$alturaBarra-ALTURA_BARRA,$Y,ALTURA_BARRA,$ANCHO_LINEAS[$a]); 
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
	{
		/*
		$pdf->TextWithDirection($i-ANCHO_CELDA_ACOTACION+ANCHO_CELDA_ACOTACION/2,BASE_Y,"  TITULO",'D');
		for($a=0, $colActual = BASE_GRAFICO_Y;$a<NUMERO_ACOTACIONES;$a++,$colActual+=$anchoColumnas )
			$pdf->TextWithDirection($i-ANCHO_CELDA_ACOTACION+(ANCHO_CELDA_ACOTACION/2)-1,$colActual,"  ".$nombres[$a],'D');
		*/
		$pdf->Line($i,BASE_Y,$i,BASE_Y+ANCHO_TOTAL+ESCALA_ANCHO*2);
	}
	
	for($i=0, $Y=BASE_GRAFICO_Y;$i<=$nColumnas;$i++,$Y+=$anchoColumnas)
		$pdf->Line(BASE_X,$Y,BASE_X+ANCHO_ACOTACIONES,$Y);
	
	// Titulos de columnas
	for($i=BASE_X + ANCHO_CELDA_ACOTACION, $j=0 ;$j<=sizeof($dColumnas); $i+=ANCHO_CELDA_ACOTACION, $j++)
		$pdf->TextWithDirection($i-ANCHO_CELDA_ACOTACION+ANCHO_CELDA_ACOTACION/2,BASE_Y,"  ". $titulos[$j],'D');
	// Datos de las gráficas
	for($colActual = BASE_GRAFICO_Y, $j=0 ;$j<=sizeof($dColumnas);$j++, $colActual+= $anchoColumnas)
		//for($a=;$a<sizeof($dColumnas[$j]);$a++)
		for($a=sizeof($dColumnas[$j])-1,$k=0;$a>=0;$a--,$k++)
			$pdf->TextWithDirection(BASE_X + ($k * ANCHO_CELDA_ACOTACION) + (ANCHO_CELDA_ACOTACION / 2)-1,$colActual,"  ".round($dColumnas[$j][$a] / $totalKG * 100,2)." %",'D');
	
	$pdf->AddPage();

} else if ($_REQUEST['y'] == 3) {

	/* Segunda hoja */
	
	$dColumnas2[]	=	array($totalKG,$Metas[0], $totalKG - $Metas[0]);
	/*
	$dColumnas2[]	=	array(100);
	$dColumnas2[]	=	array(150);
	$dColumnas2[]	=	array(80);
	*/
	$nColumnas2	=	sizeof($dColumnas2);
	
	$pdf->Rect(BASE_GRAFICO_X,BASE_GRAFICO_Y,ALTO_TOTAL,ESCALA_ANCHO+ANCHO_TOTAL);
	
	$vMaximo	=	0;
	foreach($dColumnas2 as $columna)
	{
		$t	=	max($columna);
		$vMaximo	=	($t > $vMaximo)?$t:$vMaximo;
	}
	

	
 	$cienRelativo	=	round($vMaximo, intval(strlen($vMaximo)-2) * -1) + intval("1" . str_repeat("0",intval(strlen($vMaximo)-2)));
	$anchoColumnas	=	((ANCHO_TOTAL+ESCALA_ANCHO) / $nColumnas2);
	$espaciado		=	($anchoColumnas - TOTAL_ANCHO_LINEAS)/2;
	
	/* Escala */
	$pdf->SetFillColor("#333333");
	$pdf->Rect(BASE_X+ANCHO_ACOTACIONES,BASE_Y,ALTO_TOTAL,ESCALA_ANCHO,'FD');
	
	$pdf->SetLineWidth(0.05);
	$pdf->SetTextColor("#FFFFFF");
	for($i=1, $divisiones = (($vMaximo/$cienRelativo)*ALTO_TOTAL) / 10, $escala = round($vMaximo, intval(strlen($vMaximo)-2) * -1) / 10;$i<=10;$i++)
	{
		//$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2,BASE_Y, " " . ceil($escala*$i),'D');
		$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2,BASE_Y, " " . ceil($escala*$i),'D');
		$pdf->Line(BASE_GRAFICO_X+($i*$divisiones),BASE_GRAFICO_Y,BASE_GRAFICO_X+($i*$divisiones),BASE_GRAFICO_Y+ESCALA_ANCHO+ANCHO_TOTAL);
	}
	
	$pdf->TextWithDirection(BASE_GRAFICO_X+($i*$divisiones)-2.5,BASE_Y,ceil($escala*$i),'D');
	$pdf->SetLineWidth(0.2);
	
	for($i=0, $colActual = BASE_GRAFICO_Y ;$i<$nColumnas2;$i++, $colActual+=$anchoColumnas )
	{
		$pdf->SetFillColor($colores[$i]);
		for($a=0,$Y	= $colActual+$espaciado ; $a<sizeof($ANCHO_LINEAS); $a++,$Y+=$ANCHO_LINEAS[$a])
		{
			$alturaBarra	=	($dColumnas2[$i][$a] / $cienRelativo) * ALTO_TOTAL;
			$pdf->Image("reportes/imgs/" . $imagenes[$i],BASE_GRAFICO_X,$Y,$alturaBarra,$ANCHO_LINEAS[$a]);
			$pdf->Image("reportes/imgs/" . $bordes[$i],BASE_GRAFICO_X+$alturaBarra-ALTURA_BARRA,$Y,ALTURA_BARRA,$ANCHO_LINEAS[$a]);
			//$pdf->Rect(BASE_GRAFICO_X,$Y,$alturaBarra,$ANCHO_LINEAS[$a],'FD');
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
	{
		$pdf->TextWithDirection($i-ANCHO_CELDA_ACOTACION+ANCHO_CELDA_ACOTACION/2,BASE_Y,"  TITULO",'D');
		for($a=0, $colActual = BASE_GRAFICO_Y;$a<NUMERO_ACOTACIONES;$a++,$colActual+=$anchoColumnas )
			$pdf->TextWithDirection($i-ANCHO_CELDA_ACOTACION+(ANCHO_CELDA_ACOTACION/2)-1,$colActual,"  ".$nombres[$a],'D');
		$pdf->Line($i,BASE_Y,$i,BASE_Y+ANCHO_TOTAL+ESCALA_ANCHO*2);
	}
	
	for($i=0, $Y=BASE_GRAFICO_Y;$i<=$nColumnas2;$i++,$Y+=$anchoColumnas)
		$pdf->Line(BASE_X,$Y,BASE_X+ANCHO_ACOTACIONES,$Y);
		
	// Titulos de columnas
	for($i=BASE_X + ANCHO_CELDA_ACOTACION, $j=0 ;$j<=sizeof($dColumnas); $i+=ANCHO_CELDA_ACOTACION, $j++)
		$pdf->TextWithDirection($i-ANCHO_CELDA_ACOTACION+ANCHO_CELDA_ACOTACION/2,BASE_Y,"  ". $titulos[$j],'D');
	// Datos de las gráficas
	for($colActual = BASE_GRAFICO_Y, $j=0 ;$j<=sizeof($dColumnas);$j++, $colActual+= $anchoColumnas)
		for($a=sizeof($dColumnas[$j])-1,$k=0;$a>=0;$a--,$k++)
			$pdf->TextWithDirection(BASE_X + ($k * ANCHO_CELDA_ACOTACION) + (ANCHO_CELDA_ACOTACION / 2)-1,$colActual,"  ".round($dColumnas[$j][$a] / $totalKG * 100,2)." %",'D');

}

$pdf->Output();
?>