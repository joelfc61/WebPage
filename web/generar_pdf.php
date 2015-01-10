<?php
    include "libs/conectar.php";
	require('libs/funciones.php');
	//require("fpdf.php");
	require('libs/fpdf/fpdf.php');

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
define("MARGEN_DERECHO_TABLA",6);
define("MARGEN_IZQUIERDO_TABLA",6);
define("ANCHO_TABLA_C1",25);
define("ANCHO_TABLA_C2",26);

define("ANCHO_TABLA", MARGEN_DERECHO_TABLA + ANCHO_TABLA_C1 + ANCHO_TABLA_C2 + MARGEN_IZQUIERDO_TABLA);
define("ESPACIADO_ENTRE_TABLAS", 10);

define("FONDO_TITULO_TABLA", "#0A4662");
define("LETRA_TITULO_TABLA", "#FFFFE8");
define("LETRA_CAMPOS","#000000");
define("LETRA_TEXTO","#217A9E");


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


if( $_REQUEST['id_reporte'] < 7){
	class PDF extends FPDF
	{
	function Header()
	{
		$this->Image('img/head.jpg',0,0,210);
		$this->Ln(50);
	}

	function Footer()
	{
		$this->SetY(-25);
		$this->Image('img/foot.jpg',0,273,210);
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
}

if($_REQUEST['id_reporte']==1)
{
		
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	if($_REQUEST['area'] > 0)
	$query = "SELECT * FROM operadores INNER JOIN puestos ON operadores.id_puesto = puestos.id_puesto WHERE operadores.area = ".$_REQUEST['area']." ORDER BY operadores.rol, operadores.area ASC ";
	if($_REQUEST['area'] == 0)
	$query = "SELECT * FROM operadores INNER JOIN puestos ON operadores.id_puesto = puestos.id_puesto ORDER BY operadores.rol, operadores.area ASC ";

	$resultado = mysql_query($query);
	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetXY(60,45);
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(90,10,'Reporte Total de Empleados', 0,0,'C');
	$pdf->SetY(60);
	$pdf->SetX(15);
	$pdf->SetLineWidth(.5);
	$pdf->Cell(75,5,'Nombre', 1,0,'C');
	$pdf->Cell(10,5,'Rol', 1,0,'C');
	$pdf->Cell(35,5,'Area', 1,0,'C');
	$pdf->Cell(55,5,'Maquinas Operadas', 1,0,'C');

	$pdf->Ln();

	$pdf->SetFont('Arial', '', 9);

while($registro = mysql_fetch_array($resultado)) 
	{
		$pdf->SetX(15);
		$pdf->Cell(75,5,$registro['nombre'], 1,0,'L');
		$pdf->Cell(10,5,$registro['rol'], 1,0,'L');
		if($registro['area'] == 1)
			$pdf->Cell(35,5,'Extruder', 1,0,'L');
		if($registro['area'] == 2)
			$pdf->Cell(35,5,'Bolseo', 1,0,'L');
		if($registro['area'] == 3)
			$pdf->Cell(35,5,'Impresion', 1,0,'L');		
		if($registro['area'] == 4)
			$pdf->Cell(35,5,'Mantenimiento', 1,0,'L');	
		
		$qMaquinas	=	"SELECT * FROM oper_maquina INNER JOIN maquina ON oper_maquina.id_maquina = maquina.id_maquina WHERE id_operador = ".$registro['id_operador']."";
		$rMaquinas	=	mysql_query($qMaquinas);
		while($dMaquinas	=	mysql_fetch_assoc($rMaquinas)){
		$pdf->Cell(8,5,$dMaquinas['numero'],1,0,'C');
		}
		$pdf->Ln();
				 if($pdf->GetY()>=250)
			$pdf->AddPage();
	}

	$pdf->Output();
}


if($_REQUEST['id_reporte']==3)
{


	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();	
	
	$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
	$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hasta']);
	
	
	$resultado = mysql_query($query);

	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetXY(60,40);
	$pdf->Cell(90,10,'Reporte Total de Produccion por Maquina',0,0,'C');
	$pdf->SetY(50);
	$pdf->SetLineWidth(.3);		
	$pdf->SetTextColor( 255 , 120 , 0 );
	$pdf->SetX(18);
	$pdf->Ln();
	
	$qNumero	=	"SELECT * FROM maquina WHERE id_maquina = ".$_REQUEST['id_maquina']."";
	$rNumero	=	mysql_query($qNumero);
	$dNumero	=	mysql_fetch_assoc($rNumero);


$pdf->SetFillColor(FONDO_TITULO_TABLA);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(48, 6, "Maquina No:", 1, 0, 'L',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->Cell(47, 6, $dNumero['numero'], 1, 0, 'C');
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(48, 6, "Area:", 1, 0, 'L',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	if($_REQUEST['area'] == 1)
		$pdf->Cell(47, 6, "Bolseo", 1, 1, 'C');
	if($_REQUEST['area'] == 2)
		$pdf->Cell(47, 6, "impresion", 1, 1, 'C');
	if($_REQUEST['area'] == 3)
		$pdf->Cell(47, 6, "Linea de Impresion", 1, 1, 'C');
	if($_REQUEST['area'] == 4)
		$pdf->Cell(47, 6, "Extruder", 1, 1, 'C');
		
	
	$pdf->SetFont('Arial', '', 8);

	if($_REQUEST['area'] == '1')
 	$qMaquina = "SELECT * FROM bolseo INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo WHERE ( id_maquina = '".$_REQUEST['id_maquina']."' AND fecha >= '".$desde."') AND fecha <=  '".$hasta."' ORDER BY fecha ASC";
	
	if($_REQUEST['area'] == '2')
	 $qMaquina = "SELECT * FROM entrada_general LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general LEFT JOIN resumen_maquina_im ON impresion.id_impresion = resumen_maquina_im.id_impresion WHERE ( id_maquina = '".$_REQUEST['id_maquina']."' AND fecha >= '".$desde."') AND fecha <=  '".$hasta."' ORDER BY fecha ASC";
	
	if($_REQUEST['area'] == '3')
 $qMaquina = "SELECT * FROM entrada_general LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general LEFT JOIN resumen_maquina_im ON impresion.id_impresion = resumen_maquina_im.id_impresion WHERE ( id_maquina = '".$_REQUEST['id_maquina']."' AND fecha >= '".$desde."') AND fecha <=  '".$hasta."' ORDER BY fecha ASC";
 
	if($_REQUEST['area'] == '4')
	 $qMaquina = "SELECT * FROM entrada_general LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general LEFT JOIN resumen_maquina_ex ON orden_produccion.id_orden_produccion = resumen_maquina_ex.id_orden_produccion  WHERE ( id_maquina = '".$_REQUEST['id_maquina']."' AND fecha >= '".$desde."' ) AND fecha <=  '".$hasta."' ORDER BY fecha ASC";	

		
	$rMaquina		= 	mysql_query($qMaquina);
	$nTurnosMaquina	=	mysql_num_rows($rMaquina);
	$turnos			=	$nTurnosMaquina * 3;
	
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(95, 6, "Turnos laborados desde el dia ".$_REQUEST['desde']." hasta el dia ".$_REQUEST['hasta']."", 1, 0, 'L',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->Cell(48, 6, "$turnos" , 1, 1, 'C');
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(95, 6, "Dias laborados: ", 1, 0, 'L',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->Cell(48, 6, "$nTurnosMaquina" , 1, 1, 'C');
	
	$pdf->SetY(85);
	
	while($dMaquina = mysql_fetch_array($rMaquina)){
		if($_REQUEST['area'] == 1){ 
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(25, 6, "Fecha :" , 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(25, 6, $dMaquina['fecha'] , 1, 1, 'L');
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(25, 6, "Kilogramos :" , 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(25, 6, $dMaquina['kilogramos'] , 1, 0, 'L');	
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(25, 6, "Millares :" , 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(25, 6, $dMaquina['millares'] , 1, 1, 'L');
			
		}
		else {
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(25, 6, "Fecha :" , 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(25, 6, $dMaquina['fecha'] , 1, 0, 'L');
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(15, 6, "Turno :" , 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(15, 6, $dMaquina['turno'] , 1, 0, 'L');
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(25, 6, "Kilogramos :" , 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(25, 6, $dMaquina['subtotal'] , 1, 1, 'L');	
		}
		//$pdf->Ln();
				 if($pdf->GetY()>=250)
			$pdf->AddPage();
		 //impresion de datos en el pdf
		
		
	}
	
	
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(25, 6, "TOTAL :" , 1, 0, 'L',1);
		
	 	$qMaquinaTotal = "SELECT SUM(subtotal) FROM entrada_general LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general LEFT JOIN resumen_maquina_ex ON orden_produccion.id_orden_produccion = resumen_maquina_ex.id_orden_produccion  WHERE ( id_maquina = '".$_REQUEST['id_maquina']."' AND fecha >= '".$desde."' ) AND fecha <=  '".$hasta."' ORDER BY fecha ASC";	
		$rMaquinaTotal	=	mysql_query($qMaquinaTotal);
		$dMaquinaTotal	=	mysql_fetch_row($rMaquinaTotal);
		
		
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(25, 6, $dMaquinaTotal['0'] , 1, 1, 'L');


	$pdf->Output();
}





if($_REQUEST['id_reporte']==4)
{

	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
	$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hasta']);

			   $id_emp = $_REQUEST['id_empleado'];

	$qNombre 	= "SELECT * FROM supervisor ORDER BY nombre DESC ";
	$rNombre	= mysql_query($qNombre);
	
	$dNombre = mysql_fetch_assoc($rNombre);
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(48, 6, "Nombre:", 1, 0, 'L',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->Cell(47, 6, $dNombre['nombre'], 1, 0, 'C');
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(48, 6, "Rol:", 1, 0, 'L',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->Cell(47, 6, $dNombre['rol'], 1, 0, 'C');
	
	$pdf->Ln();

	$pdf->SetFont('Arial', '', 11);

	if($dNombre['area'] == '1')
 	$qMaquina = "SELECT * FROM bolseo INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo WHERE ( id_supervisor = '".$dNombre['id_supervisor']."' AND fecha >= '".$desde."') AND fecha <=  '".$hasta."' ORDER BY fecha ASC";
	
	if($dNombre['area'] == '2')
	 $qMaquina = "SELECT * FROM entrada_general LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general LEFT JOIN resumen_maquina_im ON impresion.id_impresion = resumen_maquina_im.id_impresion WHERE ( id_maquina = '".$dNombre['id_supervisor']."' AND fecha >= '".$desde."') AND fecha <=  '".$hasta."' ORDER BY fecha ASC";
	
	if($dNombre['area'] == '3')
 $qMaquina = "SELECT * FROM entrada_general LEFT JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general LEFT JOIN resumen_maquina_im ON impresion.id_impresion = resumen_maquina_im.id_impresion WHERE ( id_maquina = '".$_REQUEST['id_maquina']."' AND fecha >= '".$desde."') AND fecha <=  '".$hasta."' ORDER BY fecha ASC";
 
	if($dNombre['area'] == '4')
	 $qMaquina = "SELECT * FROM entrada_general LEFT JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general LEFT JOIN resumen_maquina_ex ON orden_produccion.id_orden_produccion = resumen_maquina_ex.id_orden_produccion  WHERE ( id_maquina = '".$_REQUEST['id_maquina']."' AND fecha >= '".$desde."' ) AND fecha <=  '".$hasta."' ORDER BY fecha ASC";	
	 
	 
    while($registro = mysql_fetch_array($resultado)) 
	{   			$pdf->SetX(29);

		$pdf->SetTextColor( 0 , 0 , 0 );
		$pdf->Cell(80,5,$registro['nombre'], 1,0,'L');
		$pdf->SetTextColor( 0 , 0 , 0 );
		$pdf->Cell(25,5,preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$registro['fecha']), 1,0,'C');
		$pdf->Cell(22,5,$registro['entrada'], 1,0,'C');
		if($registro['salida'] == '0'){
		$pdf->SetTextColor( 255 , 0 , 0 );
		$pdf->Cell(22,5,'Sin checar', 1,0,'C');
		}else{
		$pdf->SetTextColor( 0 , 0 , 0 );
		$pdf->Cell(22,5,$registro['salida'], 1,0,'C');
		}

		$pdf->Ln();
	}

	$pdf->Output();
}


if($_REQUEST['id_reporte']==5)
{
	



echo $qGeneral		=	"SELECT SUM(kilogramos) FROM bolseo WHERE (MONTH(fecha) = '{$_REQUEST[m]}' AND YEAR(fecha) = '{$_REQUEST[a]}') ";
$rGeneral		=	mysql_query($qGeneral) OR die("<p>$qGeneral</p><p>".mysql_error()."</p>");
list($totalKG)		=	mysql_fetch_row($rGeneral);

echo $qDetalles		=	"SELECT bolseo.id_supervisor,supervisor.nombre,SUM(dtira),SUM(dtroquel),SUM(segundas) FROM bolseo LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor WHERE (MONTH(fecha) = '{$_REQUEST[m]}' AND YEAR(fecha) = '{$_REQUEST[a]}') GROUP BY id_supervisor";
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
		$pdf->Image("reportes/imgs/" . $imagenes[$a],BASE_GRAFICO_X,$Y,$alturaBarra-ALTURA_BARRA,$ANCHO_LINEAS[$a]);
		$pdf->Image("reportes/imgs/" . $bordes[$a],BASE_GRAFICO_X+$alturaBarra-ALTURA_BARRA,$Y,ALTURA_BARRA,$ANCHO_LINEAS[$a]);
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

}



if($_REQUEST['id_reporte']==6)
{
		
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	


	$query = "SELECT * FROM  empleados WHERE (1) AND id_activo = 1 ORDER BY nombre ASC ";
	$resultado = mysql_query($query);

	$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
	$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hasta']);
	
	$array=explode("/",$_REQUEST['desde']);
	$array2=explode("/",$_REQUEST['hasta']);

	 
	 $dia1 = $array['0'];
	 $dia2 = $array2['0'];
	 $mes  = $array['1'];
	 $anhio = $array['2'];
	 
	 if($mes == 1) $letrames = "ENERO";
	 if($mes == 2) $letrames = "FEBRERO";
	 if($mes == 3) $letrames = "MARZO";
	 if($mes == 4) $letrames = "ABRIL";
	 if($mes == 5) $letrames = "MAYO";
	 if($mes == 6) $letrames = "JUNIO";
	 if($mes == 7) $letrames = "JULIO";
	 if($mes == 8) $letrames = "AGOSTO";
	 if($mes == 9) $letrames = "SEPTIEMBRE";
	 if($mes == 10) $letrames = "OCTUBRE";
	 if($mes == 11) $letrames = "NOVIEMBRE";
	 if($mes == 12) $letrames = "DICIEMBRE";
	 
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetXY(60,60);
	$pdf->Cell(90,10,'Reporte de Transferencia Bancaria', 0,0,'C');
	
	$pdf->SetXY(33,75);
	$pdf->SetLineWidth(.5);
	$pdf->Cell(65,5,'Nombre', 1,0,'C');
	$pdf->Cell(50,5,'Cuenta Bancaria', 1,0,'C');
	$pdf->Cell(30,5,'Importe Neto', 1,0,'C');
	$pdf->Ln();

	$pdf->SetFont('Arial', '', 9);
	
  while($registro = mysql_fetch_array($resultado)) 
	{   		
	  $TOTAL_HORAS = 0;
	  $resultado_hora_extra = 0;
	  $dias = 0;
	  $id =	$registro['id_empleado'];
	  $excedente_vales = 0.00;
	
	$qHorarios 		= "SELECT * FROM horarios WHERE (1)";
	$rHorarios 		= mysql_query($qHorarios);
	$dHorarios 		= mysql_fetch_assoc($rHorarios);
	
	$qDias 			="SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta' AND permiso = 0 ORDER BY fecha DESC ";
	$rDias 			= mysql_query($qDias);
	$dDias 			= mysql_num_rows($rDias);
	
	$limite_bono = $dHorarios['hora_bono'];
 	$factor_imss = $registro['factor'];

 	$retencion	 = $dHorarios['retencion'];

	if($registro['sueldo'] < '125.55' ){
	$excedente_imss = 0;
	} else {
 	$excedente_imss = (((($registro['sueldo'] * $factor_imss) - 125.55) * 0.012) * $dDias);
	}
	if($registro['sueldo'] == $dHorarios['salario_minimo']){
	$imss = 0;
	} else { 
 	$imss = ($dDias * $registro['sueldo'])* $factor_imss * $retencion + $excedente_imss;
	}

	/////////SALARIO MINIMO????/////////////
	
	if($registro['sueldo'] <= $dHorarios['salario_minimo']){
	$salario_minimo1 = 1.00;
	} else {
	$salario_minimo1 = 0.00;
	}

	/////////////////////////////////////	
	
	
	$Sueldo_por_hora_extra = (($registro['sueldo'] / 8) * 2);
	
	$qBono		="SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta' AND entrada > '$limite_bono' AND permiso = 0  ORDER BY fecha DESC ";
	$rBono 		= mysql_query($qBono);
  	$dBono 		= mysql_num_rows($rBono);	
	
	$qRetardo = "SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta'  AND salida = '00:00:00' AND permiso = 0 ORDER BY fecha DESC ";
	$rRetardo = mysql_query($qRetardo);
	$dRetardo = mysql_num_rows($rRetardo);
	
	$qJustificantes = "SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta'  AND justificante = '1' ORDER BY fecha DESC";
	$rJustificantes = mysql_query($qJustificantes);
	$dJustificantes = mysql_num_rows($rJustificantes);
	
	$tiempo_hora_extra = 0;
	
	$qSalida 			="SELECT * FROM historial WHERE id_emp = '$id' AND fecha >='$desde' AND fecha <='$hasta' AND permiso = 0 ORDER BY fecha DESC ";
	$rSalida 			= mysql_query($qSalida);
	while($dSalida 		= mysql_fetch_assoc($rSalida)){
	
	$tiempo_hora_extra = $dSalida['salida'] -  $dHorarios['hora_salida'];
	
	if($tiempo_hora_extra > 0 )
		$TOTAL_HORAS +=  	$tiempo_hora_extra;
		$resultado_hora_extra = $TOTAL_HORAS * $Sueldo_por_hora_extra;	
}	

	//////////// HORAS A APLICAR/////////////
	
	if($salario_minimo1 > 0.00){
	$horas_aplicadas = $resultado_hora_extra - 	(($registro['sueldo']/8)*18);
	} else {
	$horas_aplicadas = $resultado_hora_extra - ((($registro['sueldo']/8)*18)/2);
	}
	
	/////////////////////////////////////////
	
	
	
	$fecha_actual 	= date('Y-m-d');
	
	
	
	$qGratificaciones 	="SELECT SUM(monto) FROM gratificaciones WHERE id_empleado = '$id' AND fecha_final >= '$fecha_actual' ORDER BY fecha_inicial DESC ";
	$rGratificaciones 	= mysql_query($qGratificaciones);
	$dGratificaciones 	= mysql_fetch_assoc($rGratificaciones);
	
	$a =  date('Y-m-d' ,mktime(0, 0, 0, date('m')+7  , date('d') , date('Y')));
	
	$porcentaje 	= ".".$dHorarios['bono'];
	
	$qPermisos = "SELECT * FROM historial  WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta' AND permiso = 1  ";
	$rPermisos = mysql_query($qPermisos);
	$dPermisos = mysql_num_rows($rPermisos);
	
	
	$array_dias=explode("/",$_REQUEST['desde']);
	$array2=explode("/",$_REQUEST['hasta']);
	$array3=explode("-",$registro['fecha_ini']);
	
	$diaert = $array3['2'];
	$mes2 = $array3['1'];
 	$anho2= $array3['0'];
		
	  $dia1 = $array['0'];
	  $mes  = $array['1'];
	  $dia2 = $array2['0'];
	  $anhiosss = $array['2'];
	  $anhioss  = $array2['2'];
	// echo $registro['fecha_ini'];
	
 	if( ($mes2 == $mes) && ($anho2 == $anhiosss)){
	if( ( $diaert > $dia1 && $diaert < $dia2 ) ){
	$array_ini=explode("-",$registro['fecha_ini']);
    $numero_dias = $dia2 - $array_ini['2']+1;
	}} else {
	
	if($dia2 == 15)
		$numero_dias = 15;
	if($dia1 == 16 && $dia2 == 28)
		$numero_dias = 13;
	if($dia1 == 16 && $dia2 == 29)
		$numero_dias = 14;		
	if($dia1 == 16 && $dia2 == 30)
		$numero_dias = 15;
	if($dia1 == 16 && $dia2 == 31)
		$numero_dias = 16;
	}
	
		
	if(((($dBono + $dRetardo) - $dJustificantes) <  $numero_dias) && ($dDias + $dPermisos) == $numero_dias){
	 $premio =( ($registro['sueldo'] * $porcentaje )*$dDias);
	} else {
	 $premio = "0";
	}
	
	///////////////// CANTIDAD VALID ///////////////////
	
	if($horas_aplicadas > 0 ){
	$cantidad_val = $horas_aplicadas + ($dDias * $registro['sueldo']) + $premio;
	} else {
	$cantidad_val = (($dDias * $registro['sueldo']) + $premio);
	}
	////// CRED_SAL //////////
		
	$qCred_sal = "SELECT * FROM credito_al_salario WHERE hasta <= $cantidad_val ORDER BY hasta DESC";
	$rCred_sal = mysql_query($qCred_sal);
	$dCred_sal = mysql_fetch_assoc($rCred_sal);
	if($dDias < $dHorarios['numero_dias']){
	$credito_salarial = ($dCred_sal['valor']/$dHorarios['numero_dias']) * $dDias ;
	
	} else {
	$credito_salarial = $dCred_sal['valor'];
	}
	/////////// CUOTA FIJA //////////////
	
	$qCuota_fija = "SELECT * FROM limites WHERE tipo = 1 AND limite <= $cantidad_val ORDER BY limite DESC LIMIT 1 ";
	$rCuota_fija = mysql_query($qCuota_fija);
	$dCuota_fija = mysql_fetch_assoc($rCuota_fija);
	$qCuota_fija2 = "SELECT * FROM limites WHERE tipo = 2 AND limite <= $cantidad_val ORDER BY limite DESC LIMIT 1 ";
	$rCuota_fija2 = mysql_query($qCuota_fija2);
	$dCuota_fija2 = mysql_fetch_assoc($rCuota_fija2);
	
	$dCuota_fija2['cm'];

	$cuota_fija = $dCuota_fija['cf'];
	/////////// CONTRIBUCION MARGINAL BASE ////////////
	
	$contribucion = $cantidad_val -  $dCuota_fija['limite'];
	
	////////// PORCENTAJE //////////////////
	$dCuota_fija['porcentaje'];
    $porcentaje = ($contribucion * $dCuota_fija['porcentaje'])/100;
	/////////////// C. MARG /////////////////
	
	$c_marg = ($porcentaje * $dCuota_fija2['cm'])/100;
	/////////////// SUBCIDIO FISCAL COUTA FIJA ///////////
	
	$subsidio_fiscal = ($dCuota_fija2['cf'] - $cuota_fija) * (-1);
	/////////// SUBCIDIO ACREDITADO ///////////////////
	$subsidio_porciento = $dHorarios['subsidio'] / 100; 
	$subsidio_acreditado = ($subsidio_fiscal + $c_marg)* $subsidio_porciento;
	//////////// IMPUESTOS //////////////
	
	$impuestos = (( $cuota_fija + $porcentaje) - ($subsidio_acreditado))- $credito_salarial;
	$cuota_fija;
	$porcentaje;
	$subsidio_acreditado;
	$credito_salarial;
	//////////// ISPT /////////////////
	
	if($registro['sueldo'] > $dHorarios['salario_minimo']){
	$u = $impuestos;
	} else {
	$u = $impuestos;
	}
	
	if($u > 0){
	$ispt = $u + $excedente_vales; 
	} else { 
	$ispt = 0 + $excedente_vales; 
	}
	
	////////////////CREDITO SALARIAL////////////////////
	if($impuestos > 0){
	 $credsal = 0 ;
	} else {
	 $credsal = $impuestos * (-1) ;
	}
	
	$qDeducciones 	="SELECT SUM(pago_quincena) FROM deducciones WHERE id_empleado = '$id' AND fecha_final >= '$fecha_actual' ORDER BY fecha_inicial DESC ";
	$rDeducciones 	= mysql_query($qDeducciones);
	$dDeducciones 	= mysql_fetch_assoc($rDeducciones);
	
	$dDeducciones['SUM(pago_quincena)'];
	$total_deducciones = ($dDeducciones['SUM(pago_quincena)']+ $ispt + $imss +$registro['infonavit']);
	
	
	$Dtotal= ($registro['infonavit']+ $imss +$ispt);
	$SueldoQ = (( $registro['sueldo'] * $dDias)+$premio + resultado_hora_extra+$dGratificaciones['SUM(monto)']);
	$impo_neto	= ($SueldoQ - $total_deducciones) + $credsal;
	
	$total_infonavit		 += $registro['infonavit'];
	$total_credsal			 += $credsal;
	$total_ispt				 += $ispt;
	$total_imss				 += $imss;
	$total_quincena			 += $SueldoQ;
	$total_gratificaciones 	 += $dGratificaciones['SUM(monto)'];
	$total_otras_deducciones += $dDeducciones['SUM(pago_quincena)'];
	$total_horas_extras		 += $resultado_hora_extra;
	$deducciones 		 	 += $total_deducciones;
	$total_premio 			 += $premio;
	$total_neto 		 	 += $impo_neto;	
		
	
		$pdf->SetX(33);
		$pdf->Cell(65,5,$registro['nombre'], 1,0,'L');
		$pdf->Cell(50,5,$registro['cuenta'], 1,0,'C');
		$pdf->Cell(30,5,sprintf("%.2f",$impo_neto), 1,0,'C');

		$pdf->Ln();
	}

	$pdf->Output();
}




if($_REQUEST['id_reporte']==7)
{

class PDF extends FPDF
	{
	 function Header()
   {
       $this->Image('images/lobo_head_lat.jpg',0,0,280);
       $this->Ln(38);
   }

   function Footer()
   {
       //$this->SetY(-35);
       $this->Image('images/lobo_foot_lat.jpg',0,187,280);
   }
	}
	
	$pdf=new PDF();
	$pdf->FPDF('L','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	
	$query = "SELECT * FROM  empleados WHERE (1) AND id_activo = 1 ORDER BY nombre ASC ";
	$resultado = mysql_query($query);

	$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
	$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hasta']);
	
	$array=explode("/",$_REQUEST['desde']);
	$array2=explode("/",$_REQUEST['hasta']);

	 
	 $dia1 = $array['0'];
	 $dia2 = $array2['0'];
	 $mes  = $array['1'];
	 $anhio = $array['2'];
	 
	 if($mes == 1) $letrames = "ENERO";
	 if($mes == 2) $letrames = "FEBRERO";
	 if($mes == 3) $letrames = "MARZO";
	 if($mes == 4) $letrames = "ABRIL";
	 if($mes == 5) $letrames = "MAYO";
	 if($mes == 6) $letrames = "JUNIO";
	 if($mes == 7) $letrames = "JULIO";
	 if($mes == 8) $letrames = "AGOSTO";
	 if($mes == 9) $letrames = "SEPTIEMBRE";
	 if($mes == 10) $letrames = "OCTUBRE";
	 if($mes == 11) $letrames = "NOVIEMBRE";
	 if($mes == 12) $letrames = "DICIEMBRE";

	
	$pdf->SetFont('Arial', 'B', 9);
	
	$pdf->SetXY(90,50);
	$pdf->Cell(110,10,'NOMINA DEL '.$dia1.' AL '.$dia2.' DE '.$letrames.' DE '.$anhio.'' ,0,0,'C');
	
	
	$pdf->SetY(45);
	$pdf->SetLineWidth(.3);		
	$pdf->SetTextColor( 0 , 0 , 255 );
	$pdf->Ln();
	$pdf->SetXY(4,60);

	$pdf->Cell(56,5,'Empleado', 1,0,'C');
	$pdf->Cell(9,5,'Dias', 1,0,'C');
	$pdf->Cell(13,5,'S. Diario', 1,0,'C');
	$pdf->Cell(18,5,'Premio P.', 1,0,'C');
	$pdf->Cell(15,5,'H. extras', 1,0,'C');
	$pdf->Cell(16,5,'Grat.', 1,0,'C');
	$pdf->Cell(21,5,'S. Quincenal', 1,0,'C');
	$pdf->Cell(15,5,'Infonavit', 1,0,'C');
	$pdf->Cell(13,5,'ISPT', 1,0,'C');
	$pdf->Cell(13,5,'IMSS', 1,0,'C');
	$pdf->Cell(20,5,'Otras Deduc.', 1,0,'C');
	$pdf->Cell(20,5,'Total Deduc.', 1,0,'C');
	$pdf->Cell(20,5,'C. Salarial', 1,0,'C');
	$pdf->Cell(22,5,'Importe Neto', 1,0,'C');

	$pdf->Ln();

	$pdf->SetFont('Arial', '', 9);

  	
    while($registro = mysql_fetch_array($resultado)) 
	{   		
	  $TOTAL_HORAS = 0;
	  $resultado_hora_extra = 0;
	  $dias = 0;
	  $id =	$registro['id_empleado'];
	  $excedente_vales = 0.00;
	
	$qHorarios 		= "SELECT * FROM horarios WHERE (1)";
	$rHorarios 		= mysql_query($qHorarios);
	$dHorarios 		= mysql_fetch_assoc($rHorarios);
	
	$qDias 			="SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta' AND permiso = 0 ORDER BY fecha DESC ";
	$rDias 			= mysql_query($qDias);
	$dDias 			= mysql_num_rows($rDias);
	
	$limite_bono = $dHorarios['hora_bono'];
 	$factor_imss = $registro['factor'];

 	$retencion	 = $dHorarios['retencion'];

	if($registro['sueldo'] < '125.55' ){
	$excedente_imss = 0;
	} else {
 	$excedente_imss = (((($registro['sueldo'] * $factor_imss) - 125.55) * 0.012) * $dDias);
	}
	if($registro['sueldo'] == $dHorarios['salario_minimo']){
	$imss = 0;
	} else { 
 	$imss = ($dDias * $registro['sueldo'])* $factor_imss * $retencion + $excedente_imss;
	}

	/////////SALARIO MINIMO????/////////////
	
	if($registro['sueldo'] <= $dHorarios['salario_minimo']){
	$salario_minimo1 = 1.00;
	} else {
	$salario_minimo1 = 0.00;
	}

	/////////////////////////////////////	
	
	
	$Sueldo_por_hora_extra = (($registro['sueldo'] / 8) * 2);
	
	$qBono		="SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta' AND entrada > '$limite_bono' AND permiso = 0  ORDER BY fecha DESC ";
	$rBono 		= mysql_query($qBono);
  	$dBono 		= mysql_num_rows($rBono);	
	
	$qRetardo = "SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta'  AND salida = '00:00:00' AND permiso = 0 ORDER BY fecha DESC ";
	$rRetardo = mysql_query($qRetardo);
	$dRetardo = mysql_num_rows($rRetardo);
	
	$qJustificantes = "SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta'  AND justificante = '1' ORDER BY fecha DESC";
	$rJustificantes = mysql_query($qJustificantes);
	$dJustificantes = mysql_num_rows($rJustificantes);
	
	$tiempo_hora_extra = 0;
	
	$qSalida 			="SELECT * FROM historial WHERE id_emp = '$id' AND fecha >='$desde' AND fecha <='$hasta' AND permiso = 0 ORDER BY fecha DESC ";
	$rSalida 			= mysql_query($qSalida);
	while($dSalida 		= mysql_fetch_assoc($rSalida)){
	
	$tiempo_hora_extra = $dSalida['salida'] -  $dHorarios['hora_salida'];
	
	if($tiempo_hora_extra > 0 )
		$TOTAL_HORAS +=  	$tiempo_hora_extra;
		$resultado_hora_extra = $TOTAL_HORAS * $Sueldo_por_hora_extra;	
}	

	//////////// HORAS A APLICAR/////////////
	
	if($salario_minimo1 > 0.00){
	$horas_aplicadas = $resultado_hora_extra - 	(($registro['sueldo']/8)*18);
	} else {
	$horas_aplicadas = $resultado_hora_extra - ((($registro['sueldo']/8)*18)/2);
	}
	
	/////////////////////////////////////////
	
	
	
	$fecha_actual 	= date('Y-m-d');
	
	
	
	$qGratificaciones 	="SELECT SUM(monto) FROM gratificaciones WHERE id_empleado = '$id' AND fecha_final >= '$fecha_actual' ORDER BY fecha_inicial DESC ";
	$rGratificaciones 	= mysql_query($qGratificaciones);
	$dGratificaciones 	= mysql_fetch_assoc($rGratificaciones);
	
	$a =  date('Y-m-d' ,mktime(0, 0, 0, date('m')+7  , date('d') , date('Y')));
	
	$porcentaje 	= ".".$dHorarios['bono'];
	
	$qPermisos = "SELECT * FROM historial  WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta' AND permiso = 1  ";
	$rPermisos = mysql_query($qPermisos);
	$dPermisos = mysql_num_rows($rPermisos);
	
$array_dias=explode("/",$_REQUEST['desde']);
	$array2=explode("/",$_REQUEST['hasta']);
	$array3=explode("-",$registro['fecha_ini']);
	
	$diaert = $array3['2'];
	$mes2 = $array3['1'];
 	$anho2= $array3['0'];
		
	  $dia1 = $array_dias['0'];
	  $mes  = $array_dias['1'];
	  $dia2 = $array2['0'];
	  $anhiosss = $array_dias['2'];
	  $anhioss  = $array2['2'];
	// echo $registro['fecha_ini'];
	
 	if( ($mes2 == $mes) && ($anho2 == $anhiosss)){
	
	if( ( $diaert > $dia1 && $diaert < $dia2 ) ){
	$array_ini=explode("-",$registro['fecha_ini']);
    $numero_dias = $dia2 - $array_ini['2']+1;
	}
	
	} else {
	
	if($dia2 == 15)
		$numero_dias = 15;
	if($dia1 == 16 && $dia2 == 28)
		$numero_dias = 13;
	if($dia1 == 16 && $dia2 == 29)
		$numero_dias = 14;		
	if($dia1 == 16 && $dia2 == 30)
		$numero_dias = 15;
	if($dia1 == 16 && $dia2 == 31)
		$numero_dias = 16;
	}
	
						
	if(((($dBono + $dRetardo) - $dJustificantes) < $numero_dias) && ($dDias + $dPermisos) == $numero_dias){
	 $premio =( ($registro['sueldo'] * $porcentaje )*$dDias);
	} else {
	 $premio = "0";
	}
	
	///////////////// CANTIDAD VALID ///////////////////
	
	if($horas_aplicadas > 0 ){
	$cantidad_val = $horas_aplicadas + ($dDias * $registro['sueldo']) + $premio;
	} else {
	$cantidad_val = (($dDias * $registro['sueldo']) + $premio);
	}
	////// CRED_SAL //////////
		
 	$qCred_sal = "SELECT * FROM credito_al_salario WHERE hasta <= $cantidad_val ORDER BY hasta DESC";
	$rCred_sal = mysql_query($qCred_sal);
	$dCred_sal = mysql_fetch_assoc($rCred_sal);
	if($dDias < $dHorarios['numero_dias']){
	$credito_salarial = ($dCred_sal['valor']/$dHorarios['numero_dias']) * $dDias ;
	
	} else {
	$credito_salarial = $dCred_sal['valor'];
	}
	/////////// CUOTA FIJA //////////////
	
	$qCuota_fija = "SELECT * FROM limites WHERE tipo = 1 AND limite <= $cantidad_val ORDER BY limite DESC LIMIT 1 ";
	$rCuota_fija = mysql_query($qCuota_fija);
	$dCuota_fija = mysql_fetch_assoc($rCuota_fija);
	$qCuota_fija2 = "SELECT * FROM limites WHERE tipo = 2 AND limite <= $cantidad_val ORDER BY limite DESC LIMIT 1 ";
	$rCuota_fija2 = mysql_query($qCuota_fija2);
	$dCuota_fija2 = mysql_fetch_assoc($rCuota_fija2);
	
	$dCuota_fija2['cm'];

	$cuota_fija = $dCuota_fija['cf'];
	/////////// CONTRIBUCION MARGINAL BASE ////////////
	
	$contribucion = $cantidad_val -  $dCuota_fija['limite'];
	
	////////// PORCENTAJE //////////////////
	$dCuota_fija['porcentaje'];
    $porcentaje = ($contribucion * $dCuota_fija['porcentaje'])/100;
	/////////////// C. MARG /////////////////
	
	$c_marg = ($porcentaje * $dCuota_fija2['cm'])/100;
	/////////////// SUBCIDIO FISCAL COUTA FIJA ///////////
	
	$subsidio_fiscal = ($dCuota_fija2['cf'] - $cuota_fija) * (-1);
	/////////// SUBCIDIO ACREDITADO ///////////////////
	$subsidio_porciento = $dHorarios['subsidio'] / 100; 
	$subsidio_acreditado = ($subsidio_fiscal + $c_marg)* $subsidio_porciento;
	//////////// IMPUESTOS //////////////
	
	$impuestos = (( $cuota_fija + $porcentaje) - ($subsidio_acreditado))- $credito_salarial;
	$cuota_fija;
	$porcentaje;
	$subsidio_acreditado;
	$credito_salarial;
	//////////// ISPT /////////////////
	
	if($registro['sueldo'] > $dHorarios['salario_minimo']){
	$u = $impuestos;
	} else {
	$u = $impuestos;
	}
	
	if($u > 0){
	$ispt = $u + $excedente_vales; 
	} else { 
	$ispt = 0 + $excedente_vales; 
	}
	
	////////////////CREDITO SALARIAL////////////////////
	if($impuestos > 0){
	 $credsal = 0 ;
	} else {
	 $credsal = $impuestos * (-1) ;
	}
	
	$qDeducciones 	="SELECT SUM(pago_quincena) FROM deducciones WHERE id_empleado = '$id' AND fecha_final >= '$fecha_actual' ORDER BY fecha_inicial DESC ";
	$rDeducciones 	= mysql_query($qDeducciones);
	$dDeducciones 	= mysql_fetch_assoc($rDeducciones);
	
	$dDeducciones['SUM(pago_quincena)'];
	$total_deducciones = ($dDeducciones['SUM(pago_quincena)']+ $ispt + $imss +$registro['infonavit']);
	
	
	$Dtotal= ($registro['infonavit']+ $imss +$ispt);
	$SueldoQ = (( $registro['sueldo'] * $dDias)+$premio + resultado_hora_extra+$dGratificaciones['SUM(monto)']);
	$impo_neto	= ($SueldoQ - $total_deducciones) + $credsal;
	
	$total_infonavit		 += $registro['infonavit'];
	$total_credsal			 += $credsal;
	$total_ispt				 += $ispt;
	$total_imss				 += $imss;
	$total_quincena			 += $SueldoQ;
	$total_gratificaciones 	 += $dGratificaciones['SUM(monto)'];
	$total_otras_deducciones += $dDeducciones['SUM(pago_quincena)'];
	$total_horas_extras		 += $resultado_hora_extra;
	$deducciones 		 	 += $total_deducciones;
	$total_premio 			 += $premio;
	$total_neto 		 	 += $impo_neto;	
		
			$pdf->SetX(4);

		$pdf->SetTextColor( 0 , 0 , 0 );
		$pdf->Cell(56,5,$registro['nombre'], 1,0,'L');
		$pdf->SetTextColor( 0 , 0 , 0 );
		
		$pdf->Cell(9,5,$dDias, 1,0,'C');
		$pdf->Cell(13,5,sprintf("%.2f" ,$registro['sueldo']), 1,0,'C');
		$pdf->Cell(18,5,sprintf("%.2f" ,$premio),1,0,'C');
		$pdf->SetTextColor( 0 , 0 , 0 );
		$pdf->Cell(15,5,sprintf("%.2f" ,$resultado_hora_extra), 1,0,'C');
		$pdf->Cell(16,5,sprintf("%.2f" ,$dGratificaciones['SUM(monto)']),1,0,'C');
		$pdf->Cell(21,5,sprintf("%.2f" ,$SueldoQ), 1,0,'C');
		$pdf->Cell(15,5,sprintf("%.2f" ,$registro['infonavit']), 1,0,'C');
		
		$pdf->Cell(13,5,sprintf("%.2f" ,$ispt), 1,0,'C');
		$pdf->Cell(13,5,sprintf("%.2f" ,$imss), 1,0,'C');
		$pdf->Cell(20,5,sprintf("%.2f" ,$dDeducciones['SUM(pago_quincena)']), 1,0,'C');		
		
		$pdf->Cell(20,5,sprintf("%.2f" ,$total_deducciones), 1,0,'C');
		$pdf->Cell(20,5,sprintf("%.2f" ,$credsal), 1,0,'C');
		$pdf->Cell(22,5,sprintf("%.2f" ,$impo_neto), 1,0,'C');
		$pdf->Ln();
	}
		$pdf->SetX(4);
		$pdf->Cell(56,5,"TOTAL : ", 1,0,'C');
		$pdf->Cell(9,5,"", 1,0,'C');
		$pdf->Cell(13,5,"", 1,0,'C');
        $pdf->Cell(18,5,sprintf("%.2f",$total_premio), 1,0,'C');
        $pdf->Cell(15,5,sprintf("%.2f",$total_horas_extras), 1,0,'C');
        $pdf->Cell(16,5,sprintf("%.2f",$total_gratificaciones), 1,0,'C');
        $pdf->Cell(21,5,sprintf("%.2f",$total_quincena), 1,0,'C');
		
        $pdf->Cell(15,5,sprintf("%.2f",$total_infonavit), 1,0,'C');
        $pdf->Cell(13,5,sprintf("%.2f",$total_ispt), 1,0,'C');
        $pdf->Cell(13,5,sprintf("%.2f",$total_imss), 1,0,'C');
        $pdf->Cell(20,5,sprintf("%.2f",$total_otras_deducciones), 1,0,'C');
        $pdf->Cell(20,5,sprintf("%.2f",$deducciones), 1,0,'C');
        $pdf->Cell(20,5,sprintf("%.2f",$total_credsal), 1,0,'C');
        $pdf->Cell(22,5,sprintf("%.2f",$total_neto), 1,0,'C');	

	$pdf->Output();
}



if($_REQUEST['id_reporte']==8)
{

class PDF extends FPDF
	{
	 function Header()
	   {
		   $this->Image('images/lobo_head_recibo.jpg',0,0,210);
		   $this->Ln(38);
	   }
	
	   function Footer()
	   {
		   //$this->SetY(-35);
		   $this->Image('images/lobo_foot_recibo.jpg',0,257,210);
	   }
	}
	
	$pdf=new PDF();
//	$pdf->FPDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();	
	
	//$pdf->Image('images/lobo_head.jpg',0,0,210);

	
	if($_REQUEST['id_empleado'] == '0'){
	$query = "SELECT * FROM  empleados WHERE id_activo = 1 ORDER BY nombre ASC ";
	} else {
	$query = "SELECT * FROM  empleados WHERE id_activo = 1 AND id_empleado = ".$_REQUEST['id_empleado']." ORDER BY nombre ASC ";
	}
	
	$resultado = mysql_query($query);
	
	$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
	$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hasta']);
	
	$array=explode("/",$_REQUEST['desde']);
	$array2=explode("/",$_REQUEST['hasta']);

	 
	 $dia1 = $array['0'];
	 $dia2 = $array2['0'];
	 $mes  = $array['1'];
	 $anhio = $array['2'];
	 
	 if($mes == 1) $letrames = "ENERO";
	 if($mes == 2) $letrames = "FEBRERO";
	 if($mes == 3) $letrames = "MARZO";
	 if($mes == 4) $letrames = "ABRIL";
	 if($mes == 5) $letrames = "MAYO";
	 if($mes == 6) $letrames = "JUNIO";
	 if($mes == 7) $letrames = "JULIO";
	 if($mes == 8) $letrames = "AGOSTO";
	 if($mes == 9) $letrames = "SEPTIEMBRE";
	 if($mes == 10) $letrames = "OCTUBRE";
	 if($mes == 11) $letrames = "NOVIEMBRE";
	 if($mes == 12) $letrames = "DICIEMBRE";

		$pdf->SetFont('Arial', 'B', 9);
	
	
 	$premio = "0";  	
    while($registro = mysql_fetch_assoc($resultado)) 
	{   		
	
	for($numeross = 1 ; $numeross < 3 ; $numeross++)	{
	
	  $qDepa = "SELECT * FROM departamento WHERE id_departamento = ".$registro['id_depa']." ";
	  $rDepa = mysql_query($qDepa);
	  $dDepa = mysql_fetch_assoc($rDepa);

	  $TOTAL_HORAS = 0;
	  $resultado_hora_extra = 0;
	  $dias = 0;
	  $id =	$registro['id_empleado'];
	  $excedente_vales = 0.00;
	
	$qHorarios 		= "SELECT * FROM horarios WHERE (1)";
	$rHorarios 		= mysql_query($qHorarios);
	$dHorarios 		= mysql_fetch_assoc($rHorarios);
	
	$qDias 			="SELECT * FROM historial WHERE id_emp = '$id' AND fecha>='$desde' AND fecha<='$hasta' AND permiso = 0 ORDER BY fecha DESC ";
	$rDias 			= mysql_query($qDias);
	$dDias 			= mysql_num_rows($rDias);
	
	$limite_bono = $dHorarios['hora_bono'];
 	$factor_imss = $registro['factor'];

 	$retencion	 = $dHorarios['retencion'];

	if($registro['sueldo'] < '125.55' ){
	$excedente_imss = 0;
	} else {
 	$excedente_imss = (((($registro['sueldo'] * $factor_imss) - 125.55) * 0.012) * $dDias);
	}
	if($registro['sueldo'] == $dHorarios['salario_minimo']){
	$imss = 0;
	} else { 
 	$imss = ($dDias * $registro['sueldo'])* $factor_imss * $retencion + $excedente_imss;
	}

	/////////SALARIO MINIMO????/////////////
	
	if($registro['sueldo'] <= $dHorarios['salario_minimo']){
	$salario_minimo1 = 1.00;
	} else {
	$salario_minimo1 = 0.00;
	}

	/////////////////////////////////////	
	
	
	$Sueldo_por_hora_extra = (($registro['sueldo'] / 8) * 2);
	
	$qBono		="SELECT * FROM historial WHERE id_emp = ".$id." AND fecha>='$desde' AND fecha<='$hasta' AND entrada > '$limite_bono' AND permiso = 0  ORDER BY fecha DESC ";
	$rBono 		= mysql_query($qBono);
  	$dBono 		= mysql_num_rows($rBono);	
	
	$qRetardo = "SELECT * FROM historial WHERE id_emp = ".$id." AND fecha>='$desde' AND fecha<='$hasta'  AND salida = '00:00:00' AND permiso = 0 ORDER BY fecha DESC ";
	$rRetardo = mysql_query($qRetardo);
	$dRetardo = mysql_num_rows($rRetardo);
	
	$qJustificantes = "SELECT * FROM historial WHERE id_emp = ".$id." AND fecha>='$desde' AND fecha<='$hasta'  AND justificante = '1' ORDER BY fecha DESC";
	$rJustificantes = mysql_query($qJustificantes);
	$dJustificantes = mysql_num_rows($rJustificantes);
	
	$tiempo_hora_extra = 0;
	
	$qSalida 			="SELECT * FROM historial WHERE id_emp = ".$id." AND fecha >='$desde' AND fecha <='$hasta' AND permiso = 0 ORDER BY fecha DESC ";
	$rSalida 			= mysql_query($qSalida);
	while($dSalida 		= mysql_fetch_assoc($rSalida)){
	
	$tiempo_hora_extra = $dSalida['salida'] -  $dHorarios['hora_salida'];
	
	if($tiempo_hora_extra > 0 )
		$TOTAL_HORAS +=  	$tiempo_hora_extra;
		$resultado_hora_extra = $TOTAL_HORAS * $Sueldo_por_hora_extra;	
}	

	//////////// HORAS A APLICAR/////////////
	
	if($salario_minimo1 > 0.00){
	$horas_aplicadas = $resultado_hora_extra - 	(($registro['sueldo']/8)*18);
	} else {
	$horas_aplicadas = $resultado_hora_extra - ((($registro['sueldo']/8)*18)/2);
	}
	
	/////////////////////////////////////////
	
	
	
	$fecha_actual 	= date('Y-m-d');
	
	
	
	$qGratificaciones 	="SELECT SUM(monto) FROM gratificaciones WHERE id_empleado = ".$id." AND fecha_final >= '$fecha_actual' ORDER BY fecha_inicial DESC ";
	$rGratificaciones 	= mysql_query($qGratificaciones);
	$dGratificaciones 	= mysql_fetch_assoc($rGratificaciones);
	
	$a =  date('Y-m-d' ,mktime(0, 0, 0, date('m')+7  , date('d') , date('Y')));
	
	$porcentaje 	= ".".$dHorarios['bono'];
	
	$qPermisos = "SELECT * FROM historial  WHERE id_emp = ".$id." AND fecha>='$desde' AND fecha<='$hasta' AND permiso = 1  ";
	$rPermisos = mysql_query($qPermisos);
	$dPermisos = mysql_num_rows($rPermisos);
	
$array_dias=explode("/",$_REQUEST['desde']);
	$array2=explode("/",$_REQUEST['hasta']);
	$array3=explode("-",$registro['fecha_ini']);
	
	$diaert = $array3['2'];
	$mes2 = $array3['1'];
 	$anho2= $array3['0'];
		
	  $dia1 = $array_dias['0'];
	  $mes  = $array_dias['1'];
	  $dia2 = $array2['0'];
	  $anhiosss = $array_dias['2'];
	  $anhioss  = $array2['2'];
	// echo $registro['fecha_ini'];
	
 	if( ($mes2 == $mes) && ($anho2 == $anhiosss)){
	
	if( ( $diaert > $dia1 && $diaert < $dia2 ) ){
	$array_ini=explode("-",$registro['fecha_ini']);
    $numero_dias = $dia2 - $array_ini['2']+1;
	}
	
	} else {
	
	if($dia2 == 15)
		$numero_dias = 15;
	if($dia1 == 16 && $dia2 == 28)
		$numero_dias = 13;
	if($dia1 == 16 && $dia2 == 29)
		$numero_dias = 14;		
	if($dia1 == 16 && $dia2 == 30)
		$numero_dias = 15;
	if($dia1 == 16 && $dia2 == 31)
		$numero_dias = 16;
	}
	
	
					
	// $premio = "0";	
	if(((($dBono + $dRetardo) - $dJustificantes) <   $numero_dias) && ($dDias + $dPermisos) == $numero_dias ){
 	 $premio =( ($registro['sueldo'] * $porcentaje )*$dDias);
	} else {
	 $premio = "0";
	}
	
	///////////////// CANTIDAD VALID ///////////////////
	
	if($horas_aplicadas > 0 ){
	$cantidad_val = $horas_aplicadas + ($dDias * $registro['sueldo']) + $premio;
	} else {
	$cantidad_val = (($dDias * $registro['sueldo']) + $premio);
	}
	////// CRED_SAL //////////
		
	$qCred_sal = "SELECT * FROM credito_al_salario WHERE hasta <= $cantidad_val ORDER BY hasta DESC";
	$rCred_sal = mysql_query($qCred_sal);
	$dCred_sal = mysql_fetch_assoc($rCred_sal);
	if($dDias < $dHorarios['numero_dias']){
	$credito_salarial = ($dCred_sal['valor']/$dHorarios['numero_dias']) * $dDias ;
	
	} else {
	$credito_salarial = $dCred_sal['valor'];
	}
	/////////// CUOTA FIJA //////////////
	
	$qCuota_fija = "SELECT * FROM limites WHERE tipo = 1 AND limite <= $cantidad_val ORDER BY limite DESC LIMIT 1 ";
	$rCuota_fija = mysql_query($qCuota_fija);
	$dCuota_fija = mysql_fetch_assoc($rCuota_fija);
	$qCuota_fija2 = "SELECT * FROM limites WHERE tipo = 2 AND limite <= $cantidad_val ORDER BY limite DESC LIMIT 1 ";
	$rCuota_fija2 = mysql_query($qCuota_fija2);
	$dCuota_fija2 = mysql_fetch_assoc($rCuota_fija2);
	
	$dCuota_fija2['cm'];

	$cuota_fija = $dCuota_fija['cf'];
	/////////// CONTRIBUCION MARGINAL BASE ////////////
	
	$contribucion = $cantidad_val -  $dCuota_fija['limite'];
	////////// PORCENTAJE //////////////////
	$dCuota_fija['porcentaje'];
    $porcentaje = ($contribucion * $dCuota_fija['porcentaje'])/100;
	/////////////// C. MARG /////////////////
	
	$c_marg = ($porcentaje * $dCuota_fija2['cm'])/100;
	/////////////// SUBCIDIO FISCAL COUTA FIJA ///////////
	
	$subsidio_fiscal = ($dCuota_fija2['cf'] - $cuota_fija) * (-1);
	/////////// SUBCIDIO ACREDITADO ///////////////////
	$subsidio_porciento = $dHorarios['subsidio'] / 100; 
	$subsidio_acreditado = ($subsidio_fiscal + $c_marg)* $subsidio_porciento;
	//////////// IMPUESTOS //////////////
	
	$impuestos = (( $cuota_fija + $porcentaje) - ($subsidio_acreditado))- $credito_salarial;
	$cuota_fija;
	$porcentaje;
	$subsidio_acreditado;
	$credito_salarial;
	//////////// ISPT /////////////////
	
	if($registro['sueldo'] > $dHorarios['salario_minimo']){
	$u = $impuestos;
	} else {
	$u = $impuestos;
	}
	
	if($u > 0){
	$ispt = $u + $excedente_vales; 
	} else { 
	$ispt = 0 + $excedente_vales; 
	}
	
	////////////////CREDITO SALARIAL////////////////////
	if($impuestos > 0){
	 $credsal = 0 ;
	} else {
	 $credsal = $impuestos * (-1) ;
	}
	
	$qDeducciones 	="SELECT SUM(pago_quincena) FROM deducciones WHERE id_empleado = ".$id." AND fecha_final >= '$fecha_actual' ORDER BY fecha_inicial DESC ";
	$rDeducciones 	= mysql_query($qDeducciones);
	$dDeducciones 	= mysql_fetch_assoc($rDeducciones);
	
	$dDeducciones['SUM(pago_quincena)'];
	$total_deducciones = ($dDeducciones['SUM(pago_quincena)']+ $ispt + $imss +$registro['infonavit']);
	
	
	$Dtotal= ($registro['infonavit']+ $imss +$ispt);
	$SueldoQ = (( $registro['sueldo'] * $dDias)+$premio + resultado_hora_extra+$dGratificaciones['SUM(monto)']);
	$impo_neto	= ($SueldoQ - $total_deducciones) + $credsal;
	
	$total_infonavit		 += $registro['infonavit'];
	$total_credsal			 += $credsal;
	$total_ispt				 += $ispt;
	$total_imss				 += $imss;
	$total_quincena			 += $SueldoQ;
	$total_gratificaciones 	 += $dGratificaciones['SUM(monto)'];
	$total_otras_deducciones += $dDeducciones['SUM(pago_quincena)'];
	$total_horas_extras		 += $resultado_hora_extra;
	$deducciones 		 	 += $total_deducciones;
	$total_premio 			 += $premio;
	$total_neto 		 	 += $impo_neto;	
		
		
		if($numeross == 1){
		$y1 = 39;
		$y2 = 39;
		$y3 = 70;
		} if($numeross == 2){
		$y1 = 184;
		$y2 = 184;
		$y3 = 215;	
		}
		
		$pdf->Image('images/lobo_foot_recibo.jpg',0,111,210);
		$pdf->Image('images/lobo_head_recibo.jpg',0,144,210);		
		
		$pdf->SetY($y1);
		$pdf->SetLineWidth(.3);		
		$pdf->SetTextColor( 0 , 0 , 255 );
		$pdf->Ln();
		$pdf->SetXY(3.5,$y1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(22,5,'Depto', 1,0,'C');
		$pdf->Cell(22,5,'No. Empleado', 1,0,'C');
		$pdf->Cell(55,5,'Nombre', 1,0,'C');
		$pdf->Cell(30,5,'RFC', 1,0,'C');
		$pdf->Cell(28,5,'Afiliacion I.M.S.S.', 1,0,'C');
		$pdf->Cell(12,5,'G', 1,0,'C');
		$pdf->Cell(17,5,'DEL', 1,0,'C');
		$pdf->Cell(17,5,'AL', 1,0,'C');
		$pdf->Ln();
		
		$pdf->SetX(3.5);
		$pdf->SetTextColor( 0 , 0 , 0 );
		$pdf->Cell(22,5,$dDepa['departamento'], 1,0,'C');
		$pdf->SetTextColor( 0 , 0 , 0 );
		$pdf->Cell(22,5,$registro['id_empleado'], 1,0,'C');
		$pdf->Cell(55,5,$registro['nombre'],1,0,'C');
		$pdf->Cell(30,5,$registro['rfc'],1,0,'C');
		$pdf->Cell(28,5,$registro['imss'],1,0,'C');
		$pdf->Cell(12,5,'',1,0,'C');
		$pdf->Cell(17,5,$_REQUEST['desde'],1,0,'C');
		$pdf->Cell(17,5,$_REQUEST['hasta'],1,0,'C');
		$pdf->Ln();

		$pdf->SetX(3.5);
		$pdf->Cell(203,5,'',1,0,'C');
		$pdf->Ln();
		
		$pdf->SetX(3.5);
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(22,4,'Percepcion Gravable',1,0,'C');
		$pdf->Cell(22,4,'Percepcion Exenta',1,0,'C');
		$pdf->Cell(26,4,'I.S.P.T. retenido',1,0,'C');
		$pdf->Cell(29,4,'Fondo de ahorro',1,0,'C');
		$pdf->Cell(23,4,'Dias trabajados',1,0,'C');
		$pdf->Cell(23,4,'Dias no Trab.',1,0,'C');
		$pdf->Cell(24,4,'Dias Incap.',1,0,'C');
		$pdf->Cell(34,4,'Sueldo Diario',1,0,'C');
		$pdf->Ln();

		$pdf->SetX(3.5);
		$pdf->SetFont('Arial','', 7);
		$pdf->Cell(22,4,'',1,0,'C');
		$pdf->Cell(22,4,'',1,0,'C');
		$pdf->Cell(26,4,'',1,0,'C');
		$pdf->Cell(29,4,'',1,0,'C');
		$pdf->Cell(23,4,$dDias,1,0,'C');
		$Dias_no = 15 - $dDias - $dPermisos - $dJustificantes ;
		$pdf->Cell(23,4,$Dias_no,1,0,'C');
		$Dias_inc = $dPermisos + $dJustificantes;
		$pdf->Cell(24,4,$Dias_inc,1,0,'C');
		$pdf->Cell(34,4,$registro['sueldo'],1,0,'C');
		$pdf->Ln();
		
		$pdf->SetX(3.5);
		$pdf->Cell(44,4,'',1,0,'C');
		$pdf->Cell(9,4,'CVE',1,0,'C');
		$pdf->Cell(46,4,'PERCEPCIONES',1,0,'C');
		$pdf->Cell(15,4,'IMPORTE',1,0,'C');
		$pdf->Cell(8,4,'CVE',1,0,'C');
		$pdf->Cell(47,4,'DEDUCCIONES',1,0,'C');
		$pdf->Cell(17,4,'IMPORTE',1,0,'C');
		$pdf->Cell(17,4,'SALDO',1,0,'C');
		$pdf->Ln();
		
		$pdf->SetX(3.5);
		$pdf->SetFont('Arial','', 5);
		$pdf->Cell(44,4,'RECIBI DE: RENO COMERCIAL, S.A. DE C.V.',1,0,'L');
		$pdf->Cell(9,4,'',1,0,'C');
		$pdf->SetFont('Arial','', 7);
		$pdf->Cell(46,4,'PERCEPCION NORMAL',1,0,'L');
		$percepcionN = ( $registro['sueldo'] * $dDias);
		$pdf->Cell(15,4,'$ '.sprintf("%.2f", $percepcionN),1,0,'R');
		$pdf->Cell(8,4,'',1,0,'C');
		$pdf->Cell(47,4,'IMPUESTO',1,0,'L');
		$pdf->Cell(17,4,'$ '.sprintf("%.2f" ,$ispt),1,0,'R');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Ln();
		
		$pdf->SetX(3.5);
		$pdf->SetFont('Arial','', 5);
		$pdf->MultiCell(44,4,'La cantidad anotada por concepto de sueldo, incluyendo el 7º dia de descanzo, tiempo extra y demas prestaciones correspondientes al periodo que termina hoy, sin que se me adeude a la fecha ninguna cantidad por otro concepto, habiendoseme hecho los descuentos de ley y los de caracter privado.',1,'1','C');
		
		$pdf->SetY($y3);
		$pdf->SetFont('Arial','', 7);
		$pdf->SetX(47.5);
		$pdf->Cell(9,4,'',1,0,'C');
		$pdf->Cell(46,4,'PREMIO POR PUNTUALIDAD',1,0,'L');
		$pdf->Cell(15,4,'$ '.sprintf("%.2f" ,$premio),1,0,'R');
		$pdf->Cell(8,4,'',1,0,'C');
		$pdf->Cell(47,4,'SEGURO SOCIAL',1,0,'L');
		$pdf->Cell(17,4,'$ '.sprintf("%.2f" , $imss),1,0,'R');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Ln();		
		
		
		$pdf->SetX(3.5);
		$pdf->Cell(44,4,'',0,0,'C');
		$pdf->Cell(9,4,'',1,0,'C');
		$pdf->Cell(46,4,'TIEMPO EXTRA',1,0,'L');
		$pdf->Cell(15,4,'$ '.sprintf("%.2f", $resultado_hora_extra),1,0,'R');
		$pdf->Cell(8,4,'',1,0,'C');
		$pdf->Cell(47,4,'INFONAVIT',1,0,'L');
		$pdf->Cell(17,4,'$ ' .sprintf("%.2f", $registro['infonavit']),1,0,'R');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Ln();			
		
		$pdf->SetX(3.5);
		$pdf->Cell(44,4,'',0,0,'C');
		$pdf->Cell(9,4,'',1,0,'C');
		$pdf->Cell(46,4,'GRATIFICACIONES',1,0,'L');
		$pdf->Cell(15,4,'$ '. sprintf("%.2f"	 ,$dGratificaciones['SUM(monto)']),1,0,'R');
		$pdf->Cell(8,4,'',1,0,'C');
		$pdf->Cell(47,4,'PERSONALES',1,0,'L');
		$pdf->Cell(17,4,'$ '.sprintf("%.2f" , $dDeducciones['SUM(pago_quincena)'] ),1,0,'R');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Ln();		
			
		for($i = 0 ; $i<3; $i++){	
		$pdf->SetX(3.5);
		$pdf->Cell(44,4,'',0,0,'C');
		$pdf->Cell(9,4,'',1,0,'C');
		$pdf->Cell(46,4,'',1,0,'L');
		$pdf->Cell(15,4,'',1,0,'C');
		$pdf->Cell(8,4,'',1,0,'C');
		$pdf->Cell(47,4,'',1,0,'L');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Ln();
		}
		
		$pdf->SetX(3.5);
		$pdf->Cell(44,4,'',0,0,'C');
		$pdf->Cell(9,4,'',1,0,'C');
		$pdf->Cell(46,4,'CREDITO AL SALARIO',1,0,'L');
		$pdf->Cell(15,4,'$ '. sprintf("%.2f" ,$credsal),1,0,'R');
		$pdf->Cell(8,4,'',1,0,'C');
		$pdf->Cell(47,4,'',1,0,'L');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Ln();	
		
		$pdf->SetX(3.5);
		$pdf->Cell(44,4,'',1,0,'C');
		$pdf->Cell(9,4,'',1,0,'C');
		$pdf->Cell(46,4,'',1,0,'L');
		$pdf->Cell(15,4,'',1,0,'R');
		$pdf->Cell(8,4,'',1,0,'C');
		$pdf->Cell(47,4,'',1,0,'L');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Cell(17,4,'',1,0,'C');		
		$pdf->Ln();	
		
		$pdf->SetX(3.5);
		$pdf->Cell(44,4,'',1,0,'C');
		$pdf->Cell(9,4,'',1,0,'C');
		$pdf->Cell(46,4,'TOTAL PERCEPCIONES',1,0,'L');
		$pdf->Cell(15,4,'$ '.sprintf("%.2f" ,$SueldoQ + $credsal),1,0,'R');
		$pdf->Cell(8,4,'',1,0,'C');
		$pdf->Cell(47,4,'TOTAL DEDUCCIONES',1,0,'L');
		$pdf->Cell(17,4,'$ '.sprintf("%.2f" ,$total_deducciones),1,0,'R');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Ln();
		
		$pdf->SetX(3.5);
		$pdf->Cell(44,4,'FIRMA',1,0,'C');
		$pdf->Cell(125,4,'NETO A PAGAR',1,0,'R');
		$pdf->Cell(17,4,'$ '.sprintf("%.2f" ,$impo_neto), 1,0,'R');
		$pdf->Cell(17,4,'',1,0,'C');
		$pdf->Ln();	
		}
		
	
		$pdf->AddPage();
		

	}
		
	$pdf->Output();
}


?>