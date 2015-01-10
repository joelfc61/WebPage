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

	$qConfig	=	"SELECT * FROM configuracion_bolseo WHERE activado = 1 ORDER BY hasta ASC LIMIT 1 ";
	$rConfig	=	mysql_query($qConfig);
	$dConfig	=	mysql_fetch_assoc($rConfig);
	$nConfig	=	mysql_num_rows($rConfig);
	
	 if ( $nConfig > 0 ){
		 $real_milla = 1;
		 $valor_ppm	=	$dConfig['ppm'];
		 $valor_seg	=	$dConfig['seg']; 
		 
		 $ultimo_Seg = UltimoDia(date('Y'),date('m'));
		 $turnos_totale_mes	=	$ultimo_Seg*3;
		 $vts	=	$valor_seg	/ $turnos_totale_mes;
		 $vtsf	=	$vts*3;
	 }


class PDF extends FPDF
{
	function Header()
	{
		$this->Image('images/head_pdf.jpg',0,0,210);
		$this->Ln(10);
	
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
		
		
	function __escribeTabla($MySQL_resource = NULL, $id_maquina, $nMaquina	,  $numero = 'Maquina no ')
	{
		global $TablaMayor;
					//	$this->Ln(5);
					//	$this->SetTextColor(LETRA_TITULO_TABLA2);                            
				 		
					
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
		if($_REQUEST['sup_h'] == 0)
		{ 
			$ancho = ANCHO_TABLA_C3 + ANCHO_TABLA_C4;
			$this->Cell($ancho, $h , "Supervisor ", 1, 0, 'C',1);
		} 
			
		$this->Cell(MARGEN_DERECHO_TABLA,$h,'',0,1);
			
		$this->SetTextColor(LETRA_CAMPOS);	
		for($a = 0; $dTiempo = mysql_fetch_assoc($MySQL_resource); $a++)
		{
			$this->SetX($tempX);
			$this->Cell(ANCHO_TABLA_C1, $h , fecha_tabla($dTiempo['fecha']) , 1, 0, 'C');
			$this->Cell(ANCHO_TABLA_C2, $h , $dTiempo['turno'] , 1, 0, 'C');
			if($_REQUEST['sup_h'] == 0)
			{
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


 		$qConfig	=	"SELECT * FROM configuracion_bolseo WHERE  desde <= '".date('Y-m-d')."' AND hasta >= '".date('Y-m-d')."' AND activado = 1 ORDER BY hasta ASC LIMIT 1 ";
	   	$rConfig	=	mysql_query($qConfig);
	   	$dConfig	=	mysql_fetch_assoc($rConfig);
	   	$nConfig	=	mysql_num_rows($rConfig);
	   
 if ( $nConfig > 0 ){
 
 $real_milla = 1;
 $valor_ppm	=	$dConfig['ppm'];
 $valor_seg	=	$dConfig['seg'];
 }
 

 if($_REQUEST['tipo'] == 30 || $_REQUEST['tipo'] == 1 || $_REQUEST['tipo'] == 6 || $_REQUEST['tipo'] == 11  ){


		if($_REQUEST['tiempo'] == 0){
		$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
		if(!isset($_REQUEST['hasta']))
		$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desde']);
		else
		$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hasta']);
		
		$revisa 	= explode("-", $desde);
		
		$ano = intval($revisa[0]);
		$mes_d = intval($revisa[1]);
		$mes_d = intval($revisa[1]);
		$ultimo_dia = UltimoDia($ano,$mes_d);	
		$mes_d	=	num_mes_cero($ano.'-'.$mes_d.'-01');
		$mesM	=	$ano.'-'.$mes_d.'-01';
		$dia = intval($revisa[2]);	
		
		$fecha = 'del_'.$dia.'/'.$mesM.'/'.$ano;
		
		}

		if($_REQUEST['tiempo'] == 1){
		$mes_d	=	$_REQUEST['mes'];
		$ano	=	$_REQUEST['anho'];
		$mes_c	=	num_mes_cero($_REQUEST['anho']."-".$mes_d."-01");
		$mesM	=	$_REQUEST['anho'].'-'.$mes_c.'-01';
		
		$ultimo_dia = UltimoDia($ano,$mes_d);	
		$desde	= 	$_REQUEST['anho']."-".$mes_c."-01";
		$hasta	= 	$_REQUEST['anho']."-".$mes_c."-".$ultimo_dia;

		$fecha = 'Concentrado_mes_'.$mes[$mes_d].'/'.$ano;
		}
 
	 			 $qMetasBolseo	=	"SELECT * FROM meta WHERE mes = '".$mesM."' AND ano = '".$ano."' AND area = '3'";
				 $rMetasBolseo	=	mysql_query($qMetasBolseo);
				 $dMetasBolseo	=	mysql_fetch_assoc($rMetasBolseo);
				 
	 			 $qMetas	=	"SELECT * FROM meta WHERE mes = '".$mesM."' AND ano = '".$ano."' AND area = '1'";
				 $rMetas	=	mysql_query($qMetas);
				 $dMetas	=	mysql_fetch_assoc($rMetas);
				 
	 			 $qMetasImp	=	"SELECT * FROM meta WHERE mes = '".$mesM."' AND ano = '".$ano."' AND area = '2'";
				 $rMetasImp	=	mysql_query($qMetasImp);
				 $dMetasImp	=	mysql_fetch_assoc($rMetasImp);
				 
		// BOLSEO		 				  	
		$qMaquinas2	=	"SELECT SUM(kilogramos), SUM(millares), fecha, SUM(dtira), SUM(dtroquel), SUM(segundas), SUM(m_p) FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND autorizada = 1 GROUP BY fecha ORDER BY fecha ASC";
	  	$rMaquinas2	=	mysql_query($qMaquinas2);

		$nMaquinas2	=	mysql_num_rows($rMaquinas2);

		$qMaquinasMatutino		=	"SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 1 AND autorizada = 1 ORDER BY fecha ASC";
		$qMaquinasVespertino	=	"SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 2 AND autorizada = 1 ORDER BY fecha ASC";
		$qMaquinasNocturno		=	"SELECT kilogramos, millares, dtira, dtroquel, segundas, id_bolseo FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND turno = 3 AND autorizada = 1 ORDER BY fecha ASC";
		
		$rMaquinasMatutino		=	mysql_query($qMaquinasMatutino);	
		$rMaquinasVespertino	=	mysql_query($qMaquinasVespertino);	
		$rMaquinasNocturno		=	mysql_query($qMaquinasNocturno);	
		
		$dMaquinasMatutino		=	mysql_fetch_row($rMaquinasMatutino);	
		$dMaquinasVespertino	=	mysql_fetch_row($rMaquinasVespertino);	
		$dMaquinasNocturno		=	mysql_fetch_row($rMaquinasNocturno);	
		
		
		$qMaquinas4	=	"SELECT SUM(kilogramos), SUM(millares), fecha, SUM(dtira), SUM(dtroquel), SUM(segundas) FROM bolseo WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND autorizada = 1 GROUP BY fecha ORDER BY fecha ASC";
	  	$rMaquinas4 =	mysql_query($qMaquinas4);	
			
		// EXTRUDER
		$qEntrada	=	"SELECT SUM(orden_produccion.total), fecha, SUM(desperdicio_duro), SUM(desperdicio_tira), SUM(k_h) FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND autorizada = 1 GROUP BY fecha ORDER BY fecha ASC";
		$rEntrada	=	mysql_query($qEntrada);	

		
		$qEntradaMatutino	=	"SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 1 AND autorizada = 1  ORDER BY fecha ASC";
		$qEntradaVespertino	=	"SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 2 AND autorizada = 1  ORDER BY fecha ASC";
		$qEntradaNocturno	=	"SELECT orden_produccion.total, desperdicio_duro, desperdicio_tira,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 0 AND entrada_general.turno = 3 AND autorizada = 1  ORDER BY fecha ASC";

		$rEntradaMatutino	=	mysql_query($qEntradaMatutino);	
		$rEntradaVespertino	=	mysql_query($qEntradaVespertino);	
		$rEntradaNocturno	=	mysql_query($qEntradaNocturno);	
		
		$dEntradaMatutino	=	mysql_fetch_row($rEntradaMatutino);	
		$dEntradaVespertino	=	mysql_fetch_row($rEntradaVespertino);	
		$dEntradaNocturno	=	mysql_fetch_row($rEntradaNocturno);	
				
		$nEntrada	=	mysql_num_rows($rEntrada);
		
		//IMPRESION
		$qEntrada2	=	"SELECT SUM(impresion.total_hd), fecha, SUM(impresion.desperdicio_hd) , SUM(impresion.k_h) FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND autorizada = 1  GROUP BY fecha ORDER BY fecha ASC";
		$rEntrada2	=	mysql_query($qEntrada2);
		
		$qEntradaMatutino2		=	"SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 1 AND autorizada = 1  ORDER BY fecha ASC";
		$qEntradaVespertino2	=	"SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 2 AND autorizada = 1  ORDER BY fecha ASC";
		$qEntradaNocturno2		=	"SELECT impresion.total_hd, impresion.desperdicio_hd,  entrada_general.id_entrada_general FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general WHERE fecha >= '".$desde."' AND fecha <= '".$hasta."' AND impresion = 1 AND entrada_general.turno = 3 AND autorizada = 1  ORDER BY fecha ASC";

		$rEntradaMatutino2		=	mysql_query($qEntradaMatutino2);	
		$rEntradaVespertino2	=	mysql_query($qEntradaVespertino2);	
		$rEntradaNocturno2		=	mysql_query($qEntradaNocturno2);	
		
		$dEntradaMatutino2		=	mysql_fetch_row($rEntradaMatutino2);	
		$dEntradaVespertino2	=	mysql_fetch_row($rEntradaVespertino2);	
		$dEntradaNocturno2		=	mysql_fetch_row($rEntradaNocturno2);	
		
		$nEntrada2	=	mysql_num_rows($rEntrada2);




$medidaY = 200;
$h = 6;
$pdf=&new PDF();
$pdf->FPDF('P','mm','LETTER');
$pdf->SetFont("Arial","",8);
$pdf->AliasNbPages();

$pdf->AddPage();
$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);
$pdf->SetY(15);
if($_REQUEST['tipo'] == 30 &&  $nEntrada == 1 && $nEntrada2 == 1 && $nMaquinas2 == 1)
$pdf->Cell(195, 4	, "PRODUCCION DIARIA EXTRUDER, IMPRESION y BOLSEO", 0, 1, 'C');
else if(($_REQUEST['tipo'] == 1 ) && $nEntrada > 1)
$pdf->Cell(195, 4, "PRODUCCION CONCENTRADO EXTRUDER", 0, 1, 'C');
else if( ( $_REQUEST['tipo'] == 6 ) && $nEntrada2 > 1)
$pdf->Cell(195, 4, "PRODUCCION CONCENTRADO IMPRESION", 0, 1, 'C');
else if( ( $_REQUEST['tipo'] == 11 ) && $nMaquinas2 > 1)
$pdf->Cell(195, 4, "PRODUCCION CONCENTRADO BOLSEO", 0, 1, 'C');
else if( $_REQUEST['tipo'] == 1 && $nEntrada == 1)
$pdf->Cell(195, 4, "PRODUCCION DIARIA EXTRUDER", 0, 1, 'C');
else if( $_REQUEST['tipo'] == 6 && $nEntrada2 == 1)
$pdf->Cell(195, 4, "PRODUCCION DIARIA IMPRESION", 0, 1, 'C');
else if( $_REQUEST['tipo'] == 11 && $nMaquinas2 == 1)
$pdf->Cell(195, 4, "PRODUCCION DIARIA BOLSEO", 0, 1, 'C');



if($_REQUEST['tiempo'] == 1){
$pdf->SetXY(145,15);
$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Cell(12, 4, "MES : ", 1, 0, 'R',1);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(48, 4, $mes[$mes_d].' del '.$ano , 1, 1,'C');

}
if($_REQUEST['tiempo'] == 0){
	if(!isset($_REQUEST['hasta'])){
		$pdf->SetXY(170,15);
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(15, 4, "FECHA: ", 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(20, 4, fecha_tabla($desde) , 1, 1,'C');
	} else {
		$pdf->SetXY(143,15);
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(13, 4, "DESDE: ", 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(18, 4, fecha_tabla($desde) , 1, 0,'C');
		$pdf->SetTextColor(LETRA_TITULO_TABLA);
		$pdf->Cell(13, 4, "HASTA: ", 1, 0, 'L',1);
		$pdf->SetTextColor(LETRA_CAMPOS);
		$pdf->Cell(18, 4, fecha_tabla($hasta) , 1, 1,'C');		
	}


}
$pdf->Ln(4);
if($_REQUEST['tipo'] == 1 || $_REQUEST['tipo'] == 30){
if($nEntrada == 1 ){  

    $dEntrada	=	mysql_fetch_row($rEntrada);
	
	$pdf->Ln(8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Ln(5);
	$pdf->Cell(195, 5, "Producción de Extruder ".$mes[$mesM]." del ".$ano, 1, 1, 'C',1);
		
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "TURNO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "DIFERENCIA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);	
	$pdf->Cell(25, 6, "DESPERDICIO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META DESP.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "DIFERENCIA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "KG/H", 1, 1, 'C',1);	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);


	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "MATUTINO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($dEntradaMatutino[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($metasMatutino = ($dMetas['total_dia']/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($dEntradaMatutino[0] - $metasMatutino), 1, 0, 'R',1);

		$porcientoMatutino = $metasMatutino *.20;
		$nueva2	=	$metasMatutino - $porcientoMatutino;
		
		if(floor($dEntradaMatutino[0]) < floor($nueva2) && $dEntradaMatutino[0] > 0 )
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntradaMatutino[0]) > floor($nueva2+($porcientoMatutino*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntradaMatutino[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($total_desper = $dEntradaMatutino[1] + $dEntradaMatutino[2]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($Diferencia = (($dMetas['desp_duro_hd']/$ultimo_dia)/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($total_desper - $Diferencia), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($dEntradaMatutino[0]/8), 1, 1, 'R',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "VESPERTINO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($dEntradaVespertino[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($metaVespertino = ($dMetas['total_dia']/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($nuevo = $dEntradaVespertino[0] - $metaVespertino), 1, 0, 'R',1);

		$porcientoVesper = $metaVespertino *.20;
		$nueva2	=	$metaVespertino - $porcientoVesper;
		if(floor($dEntradaVespertino[0]) < floor($nueva2) && $dEntradaVespertino[0] > 0 ) 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntradaVespertino[0]) > floor($nueva2+($porcientoVesper*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntradaVespertino[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($total_desperVesper = $dEntradaVespertino[1] + $dEntradaVespertino[2]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($DiferenciaVesper = (($dMetas['desp_duro_hd']/$ultimo_dia)/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($total_desperVesper - $DiferenciaVesper), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($dEntradaVespertino[0]/7), 1, 1, 'R',1);
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "NOCTURNO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h,floor($dEntradaNocturno[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($metaNocturno = ($dMetas['total_dia']/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($nuevo = $dEntradaNocturno[0] -  $metaNocturno), 1, 0, 'R',1);

		$porcientoNocturno = $metaNocturno *.20;
		$nueva2	=	$metaNocturno - $porcientoNocturno;
		if(floor($dEntradaNocturno[0]) < floor($nueva2) && $dEntradaNocturno[0] > 0 )
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntradaNocturno[0]) > floor($nueva2+($porcientoNocturno*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntradaNocturno[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($total_despeNocturno = $dEntradaNocturno[1] + $dEntradaNocturno[2]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($DiferenciaNocturno = (($dMetas['desp_duro_hd']/$ultimo_dia)/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,  floor($total_despeNocturno - $DiferenciaNocturno), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($dEntradaNocturno[0]/9), 1, 1, 'R',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "TOTAL", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($dEntrada[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($metasss = $dMetas['total_dia']), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($nuevo = $dEntrada[0] - $metasss), 1, 0, 'R',1);

		$porciento2 = $metasss *.20;
		$nueva2	=	$metasss - $porciento2;
		if(floor($dEntrada[0]) < floor($nueva2) && $dEntrada[0] > 0 )  
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntrada[0]) > floor($nueva2+($porciento2*3))) 
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntrada[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h,  floor($total_desper = $dEntrada[2] + $dEntrada[3]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($Diferencia = ($dMetas['desp_duro_hd']/$ultimo_dia)), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($total_desper - $Diferencia), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($dEntrada[0]/24), 1, 1, 'R',1);
	$pdf->Ln(15);

	}
	
if($nEntrada > 1 ){ 	
	
	$h  = 4;

	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "FECHA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "DIFERENCIA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);	
	$pdf->Cell(25, 6, "DESPERDICIO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META DESP.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "DIFERENCIA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "KG/H", 1, 1, 'C',1);	
	
	while($dEntrada	=	mysql_fetch_row($rEntrada)){
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, fecha_tabla($dEntrada[1]), 1, 0, 'C',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(25, $h,floor($dEntrada[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h,floor($metasss = $dMetas['total_dia']), 1, 0, 'R',1);
	$pdf->Cell(20, $h,floor($nuevo = $metasss - $dEntrada[0]), 1, 0, 'R',1);

	$porciento2 = $metasss *.20;
	$nueva2	=	$metasss - $porciento2;
	if(floor($dEntrada[0]) < floor($nueva2) && $dEntrada[0] > 0 ) 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntrada[0]) > floor($nueva2+($porciento2*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntrada[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($total_desper = $dEntrada[2] + $dEntrada[3]), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($Diferencia = ($dMetas['desp_duro_hd']/$ultimo_dia)), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($TotalDif	= $Diferencia - $total_desper), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($dEntrada[0]/24), 1, 1, 'R',1);
	
	
	$TOTALKG 	+= $dEntrada['0'];
	$TOTALMETA	+= $metasss; 
	$TOTALDIF	+= $nuevo;
	$TOTALKGH	+= $dEntrada[3];
	$TOTALDESP	+= $total_desper;
	$TOTALDESPDIF	+= $TotalDif;
	$TOTALMETADESP	+= $Diferencia;
	}
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, "TOTAL", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(25, $h, floor($TOTALKG), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h,floor($TOTALMETA), 1, 0, 'R',1);
	$pdf->Cell(20, $h,floor($TOTALDIF), 1, 0, 'R',1);

	$porcientometa2 = $TOTALMETA *.20;
	$nuevameta2	=	$TOTALMETA - $porcientometa2;
	if(floor($TOTALKG) < floor($nuevameta2) && $TOTALKG > 0 )
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($TOTALKG) > floor($nuevameta2+($porcientometa2*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($TOTALKG) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($TOTALDESP), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($TOTALMETADESP), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($TOTALDESPDIF), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($TOTALKG/24), 1, 1, 'R',1);
	$pdf->Ln(15);



	
	
}
	
	
}
if($_REQUEST['tipo'] == 6 || $_REQUEST['tipo'] == 30){	
	if($nEntrada2 == 1){ 
	$dEntrada2 =	mysql_fetch_row($rEntrada2);

	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->Ln(2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(195, 5, "Producción de Impresión ".$mes[$mesM]." del ".$ano, 0, 1, 'C',1);
		
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "TURNO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "DIFERENCIA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);	
	$pdf->Cell(25, 6, "DESPERDICIO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META DESP.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "DIFERENCIA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "KG/H", 1, 1, 'C',1);	
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "MATUTINO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($dEntradaMatutino2[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($MetaMatu =($dMetasImp['total_dia']/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($nuevoM = $dEntradaMatutino2[0] - $MetaMatu), 1, 0, 'R',1);

	$pM = $MetaMatu *.20;
	$nuevaM	=	$MetaMatu - $pM;
	if(floor($dEntradaMatutino2[0]) < floor($nuevaM) && $dEntradaMatutino2[0] > 0 ) 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntradaMatutino2[0]) > floor($nuevaM+($pM*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntradaMatutino2[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($total_desperImprM = $dEntradaMatutino2[1]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($DiferenciaImprM = (($dMetasImp['desp_duro_hd']/$ultimo_dia)/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($total_desperImprM - $DiferenciaImprM), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($dEntradaMatutino2[0]/8), 1, 1, 'R',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "VESPERTINO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($dEntradaVespertino2[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($MetaVesp = ($dMetasImp['total_dia']/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($nuevoV = $dEntradaVespertino2[0] - $MetaVesp), 1, 0, 'R',1);

	$pV = $MetaVesp *.20;
	$nuevaV	=	$MetaVesp - $pV;
	if(floor($dEntradaVespertino2[0]) < floor($nuevaV) && $dEntradaVespertino2[0] > 0 ) 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntradaVespertino2[0]) > floor($nuevaV+($pV*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntradaVespertino2[0]) == 0) 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($total_desperImprV = $dEntradaVespertino2[1]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($DiferenciaImprV = (($dMetasImp['desp_duro_hd']/$ultimo_dia)/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor( $total_desperImprV - $DiferenciaImprV), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($dEntradaVespertino2[0]/7), 1, 1, 'R',1);
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "NOCTURNO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h,floor($dEntradaNocturno2[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($MetaNoc = ($dMetasImp['total_dia']/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($nuevoN = $dEntradaNocturno2[0] -  $MetaNoc), 1, 0, 'R',1);

		$pN = $metasss *.20;
		$nuevaN	=	$MetaNoc - $pN;
		if(floor($dEntradaNocturno2[0]) < floor($nuevaN) && $dEntradaNocturno2[0] > 0 ) 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntradaNocturno2[0]) > floor($nuevaN+($pN*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntradaNocturno2[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($total_desperImprN = $dEntradaNocturno2[1]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($DiferenciaImprN = (($dMetasImp['desp_duro_hd']/$ultimo_dia)/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($total_desperImprN - $DiferenciaImprN), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($dEntradaNocturno2[0]/9), 1, 1, 'R',1);
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "TOTAL", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($dEntrada2[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($metasss = $dMetasImp['total_dia']), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($nuevo = $dEntrada2[0] -  $metasss), 1, 0, 'R',1);

	$porciento2 = $metasss *.20;
	$nueva2	=	$metasss - $porciento2;
	if(floor($dEntrada2[0]) < floor($nueva2) && $dEntrada2[0] > 0 )
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntrada2[0]) > floor($nueva2+($porciento2*3))) 
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntrada2[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h,  floor($total_desperImpr = $dEntrada2[2]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($DiferenciaImpr = ($dMetasImp['desp_duro_hd']/$ultimo_dia)), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,  floor($DiferenciaImpr - $total_desperImpr), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h,floor($dEntrada2[0]/24), 1, 1, 'R',1);
	$pdf->Ln(15);
	
	}
	
	if($nEntrada2 > 1){
	

	$h  = 4;

	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "FECHA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "DIFERENCIA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);	
	$pdf->Cell(25, 6, "DESPERDICIO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META DESP.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "DIFERENCIA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "KG/H", 1, 1, 'C',1);	
	
	while($dEntrada2	=	mysql_fetch_row($rEntrada2)){
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, fecha_tabla($dEntrada2[1]), 1, 0, 'C',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(25, $h,floor($dEntrada2[0]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h,floor($metasss = $dMetasImp['total_dia']), 1, 0, 'R',1);
	$pdf->Cell(20, $h,floor($nuevo = $metasss - $dEntrada2[0]), 1, 0, 'R',1);

	$porciento2 = $metasss *.20;
	$nueva2	=	$metasss - $porciento2;
	if(floor($dEntrada2[0]) < floor($nueva2) && $dEntrada2[0] > 0 )
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dEntrada2[0]) > floor($nueva2+($porciento2*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dEntrada2[0]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($total_desperImpr = $dEntrada2[2]), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($DiferenciaImpr = ($dMetasImp['desp_duro_hd']/$ultimo_dia)), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($TotalDifImpr = $DiferenciaImpr - $total_desperImpr), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($khi = $dEntrada2['0']/24), 1, 1, 'R',1);
	
	
 	$TOTALIKG 	+= $dEntrada2['0'];
	$TOTALIMETA	+= $metasss; 
	$TOTALIDIF	+= $nuevo;
	$TOTALIKGH	+= $khi;
	$TOTALIDESP	+= $total_desperImpr;
	$TOTALIDESPDIF	+= $TotalDifImpr;
	$TOTALIMETADESP	+= $DiferenciaImpr;
	}
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, "TOTAL", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(25, $h, floor($TOTALIKG), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h,floor($TOTALIMETA), 1, 0, 'R',1);
	$pdf->Cell(20, $h,floor($TOTALIDIF), 1, 0, 'R',1);

	$porcientometa2 = $TOTALIMETA *.20;
	$nuevameta2	=	$TOTALIMETA - $porcientometa2;
	if(floor($TOTALIKG) < floor($nuevameta2) && $TOTALIKG > 0 )
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($TOTALIKG) > floor($nuevameta2+($porcientometa2*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($TOTALIKG) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(25, $h, floor($TOTALIDESP), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($TOTALIMETADESP), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($TOTALIDESPDIF), 1, 0, 'R',1);
	$pdf->Cell(20, $h, floor($TOTALIKG/24), 1, 1, 'R',1);
	$pdf->Ln(15);
	
	
	
	
	}
	
	}
	if($_REQUEST['tipo'] == 11 || $_REQUEST['tipo'] == 30){ 
	if($nMaquinas2 == 1 ){ 
	
	 $dMaquinas2	= mysql_fetch_row($rMaquinas2); 

	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Ln(2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(195, 5, "Producción de Bolseo ".$mes[$mesM]." del ".$ano, 1, 1, 'C',1);
		

	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "TURNO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 6, "PROD. KG", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 6, "META KG.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "DIF.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(12, 6, "KG/H", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 6, "PROD. Mi", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 6, "META Mi.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "Dif", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(11, 6, "Mi/H", 1, 1, 'C',1);
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "MATUTINO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	
	
		if($real_milla) 
			{ 
				$kg_reales	= $valor_ppm * $dMaquinasMatutino[1];
			} 
		else 
			{ 
				$kg_reales = $dMaquinasMatutino[0];
			 } 
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($kg_reales), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBolKilM = (($dMetasBolseo['meta_mes_kilo']/$ultimo_dia)/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevasBMK =  $kg_reales - $metasBolKilM), 1, 0, 'R',1);



	$pbmk = $metasBolKilM *.20;
	$menorbmk	=	$metasBolKilM - $pbmk;
	if((floor($kg_reales) < floor($menorbmk)) && (floor($kg_reales) > 0) ) 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($kg_reales) > floor($menorbmk+($pbmk*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($kg_reales) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(12, $h, floor($kg_reales/8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($dMaquinasMatutino[1]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBolM = (($dMetasBolseo['meta_mes_millar']/$ultimo_dia)/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevasBM = $dMaquinasMatutino[1] - $metasBolM), 1, 0, 'R',1);
		
	$pbM = $metasBolM *.20;
	$menorBM =	$metasBolM - $pbM;
	
		if((floor($dMaquinasMatutino[1]) < floor($menorBM)) && (floor($dMaquinasMatutino[1]) > 0) ) 		
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dMaquinasMatutino[1]) > floor($menorBM+($pbM*3))) 
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dMaquinasMatutino[1]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(11, $h, floor($dMaquinasMatutino[1]/8), 1, 1, 'R',1);
			
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "VESPERTINO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	
	
	if($real_milla) 
	{ 
		$kg_reales_vesp	= $valor_ppm * $dMaquinasVespertino[1];
	} 
	else 
	{ 
		$kg_reales_vesp = $dMaquinasVespertino[0];
	}
											
											
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($kg_reales_vesp), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBolKilBV = (($dMetasBolseo['meta_mes_kilo']/$ultimo_dia)/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevasBV = $kg_reales_vesp - $metasBolKilBV), 1, 0, 'R',1);



	$pkbv = $metasBolKilBV *.20;
	$menorbkv	=	$metasBolKilBV - $pkbv;
								 
								

		if((floor($kg_reales_vesp) < floor($menorbkv)) && (floor($kg_reales_vesp) > 0) ) 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
	 	else if(floor($kg_reales_vesp) > floor($menorbkv+($pkbv*3))) 
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($kg_reales_vesp) == 0) 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(12, $h, floor($kg_reales_vesp/7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($dMaquinasVespertino[1]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBolMiV = (($dMetasBolseo['meta_mes_millar']/$ultimo_dia)/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevasBMV = $dMaquinasVespertino[1] - $metasBolMiV), 1, 0, 'R',1);
	
	
	
	$pmv = $metasBolMiV *.20;
	$menormv	=	$metasBolMiV - $pmv;
	
		if((floor($dMaquinasVespertino[1]) < floor($menormv)) && (floor($dMaquinasVespertino[1]) > 0) ) 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dMaquinasVespertino[1]) > floor($menormv+($pmv*3))) 
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dMaquinasVespertino[1]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(11, $h, floor($dMaquinasVespertino[1]/7), 1, 1, 'R',1);
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
		$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "NOCTURNO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	
	
		if($real_milla) 
			{ 
				$kg_reales_noc	= $valor_ppm * $dMaquinasNocturno[1];
			} 
		else 
			{ 
				$kg_reales_noc = $dMaquinasNocturno[0];
			 } 
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($kg_reales_noc), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBolKilN = (($dMetasBolseo['meta_mes_kilo']/$ultimo_dia)/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevasBN =  $kg_reales_noc - $metasBolKilN), 1, 0, 'R',1);



	$pbkn = $metasBolKilN *.20;
	$menorbkn	=	$metasBolKilN - $pbkn;
	 if((floor($kg_reales_noc) < floor($menorbkn)) && (floor($kg_reales_noc) > 0) )
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($kg_reales_noc) > floor($menorbkn+($pbkn*3)))
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($kg_reales_noc) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(12, $h, floor($kg_reales_noc/9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($dMaquinasNocturno[1]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBolMN = (($dMetasBolseo['meta_mes_millar']/$ultimo_dia)/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevas = $dMaquinasNocturno[1] - $metasBolMN ), 1, 0, 'R',1);
	
	
	
	$pbmn = $metasBolMN *.20;
	$menorbmn	=	$metasBolMN - $pbmn;
	if((floor($dMaquinasNocturno[1]) < floor($menorbmn)) && (floor($dMaquinasNocturno[1]) > 0) ) 	
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dMaquinasNocturno[1]) > floor($menorbmn+($pbmn*3))) 
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dMaquinasNocturno[1]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(11, $h, floor($dMaquinasNocturno[1]/9), 1, 1, 'R',1);



	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "TOTAL:", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA);	
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($kg_reales+$kg_reales_vesp+$kg_reales_noc), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBolKil = $dMetasBolseo['meta_mes_kilo']/$ultimo_dia), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevas2 = ($kg_reales+$kg_reales_vesp+$kg_reales_noc) - $metasBolKil), 1, 0, 'R',1);



		$porciento4 = $metasBolKil *.20;
		$menor2	=	$metasBolKil - $porciento4;
		if((floor(($kg_reales+$kg_reales_vesp+$kg_reales_noc)) < floor($menor2)) && (floor(($kg_reales+$kg_reales_vesp+$kg_reales_noc)) > 0) ) 										
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
	 	else if(floor(($kg_reales+$kg_reales_vesp+$kg_reales_noc)) > floor($menor2+($porciento4*3))) 
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor(($kg_reales+$kg_reales_vesp+$kg_reales_noc)) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
		
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(12, $h, floor(($kg_reales+$kg_reales_vesp+$kg_reales_noc)/24), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($dMaquinas2[1]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBol = $dMetasBolseo['meta_mes_millar']/$ultimo_dia), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevas =$dMaquinas2[1] -  $metasBol), 1, 0, 'R',1);
	
	
	
	$porciento3 = $metasBol *.20;
	$menor	=	$metasBol - $porciento3;
	if((floor($dMaquinas2[1]) < floor($menor)) && (floor($dMaquinas2[1]) > 0) ) 					 
			{
				$pdf->SetTextColor('#FF0000');
				$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
			}			
		else if(floor($dMaquinas2[1]) > floor($menor+($porciento3*3))) 
			{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
			}
		else if(floor($dMaquinas2[1]) == 0)
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
		} 		
		else 
		{
				$pdf->SetTextColor('#006600');
				$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
		}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(11, $h, floor($dMaquinas2[1]/24), 1, 1, 'R',1);
	$pdf->Ln(5);	
	}
	
	
	
	 if($nMaquinas2 == 1 ){ 
	 $pdf->Cell(195, 4, "DESPERDICIOS DIARIOS BOLSEO", 0, 1, 'C');
	 $pdf->Ln(4);
  $dMaquinas4	= mysql_fetch_row($rMaquinas4); 
	
	
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "TURNO", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "TIRA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META TIRA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 6, "DIF. TIRA", 1, 0, 'C',1);
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 6, "TROQUEL", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 6, "META TROQUEL", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(24, 6, "DIF. TROQUEL", 1, 0, 'C',1);
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "SEGUNDAS", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 6, "META SEG", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 6, "DIF. SEG.", 1, 1, 'C',1);
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "MATUTINO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($dMaquinasMatutino['2']), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($resultado_tiraM = (($dMetasBolseo['mes_tira']/$ultimo_dia)/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($TotalTiraM = $dMaquinasMatutino[2] - $resultado_tiraM), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(18, $h, $dMaquinasMatutino['3'], 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(25, $h, floor($resultado_troquelM = (($dMetasBolseo['mes_troquel']/$ultimo_dia)/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(24, $h, floor($TotalTroquelM = $dMaquinasMatutino[3] -  $resultado_troquelM), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(20, $h, floor($dMaquinasMatutino['4']+$vts), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h, floor($resultado_segM = (($dMetasBolseo['mes_segunda']/$ultimo_dia)/24)*8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($TotalSegM	=	($dMaquinasMatutino[4]+$vts) - $resultado_segM), 1, 1, 'R',1);

			
			
//VESPERTINO/////////////////////			
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "VESPERTINO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($dMaquinasVespertino['2']), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($resultado_tiraV = (($dMetasBolseo['mes_tira']/$ultimo_dia)/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($TotalTiraV = $dMaquinasVespertino[2] - $resultado_tiraV), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(18, $h, $dMaquinasVespertino['3'], 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(25, $h, floor($resultado_troquelV = (($dMetasBolseo['mes_troquel']/$ultimo_dia)/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(24, $h, floor($TotalTroquelV = $dMaquinasVespertino[3] - $resultado_troquelV), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(20, $h, floor($dMaquinasVespertino['4']+$vts), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h, floor($resultado_segV = (($dMetasBolseo['mes_segunda']/$ultimo_dia)/24)*7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($TotalSegV	= ($dMaquinasVespertino[4]+$vts) - $resultado_segV), 1, 1, 'R',1);

	
	
//// NOCTURNO


		
	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "NOCTURNO", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($dMaquinasNocturno['2']), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($resultado_tiraN = (($dMetasBolseo['mes_tira']/$ultimo_dia)/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($TotalTiraN = $dMaquinasNocturno[2] -  $resultado_tiraN), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(18, $h, $dMaquinasNocturno['3'], 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(25, $h, floor($resultado_troquelN = (($dMetasBolseo['mes_troquel']/$ultimo_dia)/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(24, $h, floor($TotalTroquelN =  $dMaquinasNocturno[3] - $resultado_troquelN), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(20, $h, floor($dMaquinasNocturno['4']+$vts), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h, floor($resultado_segN = (($dMetasBolseo['mes_segunda']/$ultimo_dia)/24)*9), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($TotalSegN	= ($dMaquinasNocturno[4]+$vts) - $resultado_segN), 1, 1, 'R',1);


      $TOTAL1	=	$dMaquinas4['3'];
      $TOTAL2	=	($dMetasBolseo['mes_tira']/$ultimo_dia);
      $TOTAL3	=	$TOTAL1 - ($dMetasBolseo['mes_tira']/$ultimo_dia);
      $TOTAL4	=	$dMaquinas4['4'];
	  $TOTAL5	=	($dMetasBolseo['mes_troquel']/$ultimo_dia);
	  $TOTAL6	=	$TOTAL4 - $TOTAL5;
	  $TOTAL7	=	($dMaquinas4['5']+$vtsf);
	  $TOTAL8	=	($dMetasBolseo['mes_segunda']/$ultimo_dia);
	  $TOTAL9	=	$TOTAL7 - $TOTAL8;
	  

	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);

	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, $h, "TOTAL", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($TOTAL1), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, floor($TOTAL2), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($TOTAL3), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(18, $h, floor($TOTAL4), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(25, $h, floor($TOTAL5), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(24, $h, floor($TOTAL6), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(20, $h, floor($TOTAL7), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h, floor($TOTAL8), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($TOTAL9), 1, 1, 'R',1);

	$pdf->Ln(5);		
	
	}
	
	
	
//POR FECHAS //////////////////////// BOLSEO ////////////	
	 if($nMaquinas2 > 1 ){
	 
	
	$h = 3.4;
   	$pdf->Ln(2);
  	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 4, "FECHA: ", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 4, "KG", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 4, "META KG.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 4, "DIF. Kg.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 4, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(12, 4, "KG/H", 1, 0, 'C',1);
  	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 4, "MILLARES", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 4, "META Mi.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(15, 4, "DIF Mi.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 4, "PRODUCCION", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(11, 4, "Mi/H", 1, 1, 'C',1);

  	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
  	 while ($dMaquinas2	= mysql_fetch_row($rMaquinas2)){ 
	 
	 
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, fecha_tabla($dMaquinas2[2]), 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	
	if($real_milla) { 
		$kg_reales	= $valor_ppm * $dMaquinas2[1];
	} else { 
		$kg_reales	= floor($dMaquinas2[0]);
	}
											
	$pdf->Cell(18, $h, floor($kg_reales), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($juju = $dMetasBolseo['meta_mes_kilo']/$ultimo_dia), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor( $TotalKgDIF = $juju - $dMaquinas2[0]), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	
	$porciento2 = $juju *.20;
	$nueva2	=	$juju - $porciento2;
	if(floor($dMaquinas2[0]) < floor($nueva2) && $dMaquinas2[0] > 0 )
	{	 
			$pdf->SetTextColor('#FF0000');
			$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
	}			
		 else if(floor($dMaquinas2[0]) > floor($nueva2+($porciento2*3))) 
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
	}
		else if(floor($dMaquinas2[0]) == 0)
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
	} 		
		else 
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
	}
			
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(12, $h, floor($kh 	= $kg_reales/24), 1, 0, 'R',1);
							 
								 
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(18, $h, floor($dMaquinas2[1]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($metasBol = $dMetasBolseo['meta_mes_millar']/$ultimo_dia), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($nuevas = $metasBol - $dMaquinas2[1]), 1, 0, 'R',1);

	$porciento3 = $metasBol *.20;
	$menor	=	$metasBol - $porciento3.'<br>';
	if((floor($dMaquinas2[1]) < floor($menor)) && (floor($dMaquinas2[1]) > 0) ) 
	{	 
			$pdf->SetTextColor('#FF0000');
			$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
	}			
		 else if(floor($dMaquinas2[1]) > floor($menor+($porciento3*3))) 
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
	}
		else if(floor($dMaquinas2[1]) == 0)
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
	} 		
		else 
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
	}
			
				
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(11	, $h, floor($mh = $dMaquinas2[1]/24), 1, 1, 'R',1);
	
	
	
	$TOTALBKG 	+= $kg_reales;
	$TOTALBMETA	+= $juju; 
	$TOTALBDIF	+= $TotalKgDIF;
 	$TOTALBM 		+= $dMaquinas2['1'];
	$TOTALBMETAM	+= $metasBol; 
	$TOTALBMDIF		+= $nuevas;
	$TOTALMP	+=	$mh;
	$TOTALKP	+=	$kh;

}
	

  	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, "TOTAL: ", 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(18, $h, floor($TOTALBKG), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($TOTALBMETA), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor( $TOTALBDIF), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	
	$porTotalMB = $TOTALBMETA *.20;
	$nuevametaB2	=	$TOTALBMETA - $porTotalMB;
	if(floor($TOTALBKG) < floor($nuevametaB2) && $TOTALBKG > 0 )
	{	 
			$pdf->SetTextColor('#FF0000');
			$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
	}			
		 else if(floor($TOTALBKG) > floor($nuevametaB2+($porTotalMB*3))) 
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
	}
		else if(floor($TOTALBKG) == 0)
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
	} 		
		else 
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
	}
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(12, $h, floor($TOTALKP), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(18, $h, floor($TOTALBM), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(18, $h, floor($TOTALBMETAM), 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(15, $h, floor($TOTALBMDIF), 1, 0, 'R',1);
	
 	$porcientoB3 = $TOTALBMETAM *.20;
	$menorB	=	$TOTALBMETAM - $porcientoB3.'<br>';
	if((floor($TOTALBM) < floor($menorB)) && (floor($TOTALBM) > 0) )
	{	 
			$pdf->SetTextColor('#FF0000');
			$pdf->Cell(25, $h, "BAJA", 1, 0, 'C',1);
	}			
		  else if(floor($TOTALBM) > floor($menorB+($porcientoB3*3))) 
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "ALTA", 1, 0, 'C',1);
	}
		else if(floor($TOTALBM) == 0)
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "SIN", 1, 0, 'C',1);
	} 		
		else 
	{
			$pdf->SetTextColor('#006600');
			$pdf->Cell(25, $h, "NORMAL", 1, 0, 'C',1);
	}
			
				
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(11	, $h, floor($TOTALMP), 1, 1, 'R',1);
   	$pdf->Ln(2);

	$pdf->Cell(195, 4, "DESPERDICIOS CONCENTRADO BOLSEO", 0, 1, 'C');
	$pdf->Ln(2);


  	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 4, "FECHA ", 1, 0, 'C',1);
  	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(16, 4, "TIRA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 4, "META TIRA.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 4, "DIF. TIRA.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->Cell(17, 4, "TROQUEL", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(25, 4, "META TROQUEL", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(23, 4, "DIF. TROQUEL", 1, 0, 'C',1);
  	$pdf->SetFillColor(FONDO_TITULO_TABLA_TOTAL);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(19, 4, "SEGUNDAS", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(19, 4, "METAS SEG.", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(18, 4, "DIF SEG", 1, 1, 'C',1);


  	$pdf->SetFillColor(FONDO_TITULO_TABLA3);
	
	while ($dMaquinas4	= mysql_fetch_row($rMaquinas4)){
	
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, fecha_tabla($dMaquinas4[2]), 1, 0, 'C',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(16, $h,floor($dMaquinas4['3']), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h, floor($resultado_tira = ($dMetasBolseo['mes_tira']/$ultimo_dia)), 1, 0, 'R',1);
	$pdf->Cell(18, $h, floor($TotalTira = $resultado_tira - $dMaquinas4[3]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(17, $h, floor($dMaquinas4['4']), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(25, $h, floor($resultado_troquel = ($dMetasBolseo['mes_troquel']/$ultimo_dia)), 1, 0, 'R',1);
	$pdf->Cell(23, $h, floor($TotalTroquel = $resultado_troquel - $dMaquinas4[4]), 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(19, $h, floor($dMaquinas4['5']+$vts), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(19, $h, floor($resultado_seg = ($dMetasBolseo['mes_segunda']/$ultimo_dia)), 1, 0, 'R',1);
	$pdf->Cell(18, $h, floor($TotalSeg	=	$resultado_seg - ($dMaquinas4[5]+$vts)), 1, 1, 'R',1);

   
      $TOTAL1	+=	$dMaquinas4['3'];
      $TOTAL2	+=	$resultado_tira;
      $TOTAL3	+=	$TotalTira;
      $TOTAL4	+=	$dMaquinas4['4'];
	  $TOTAL5	+=	$resultado_troquel;
	  $TOTAL6	+=	$TotalTroquel;
	  $TOTAL7	+=	($dMaquinas4['5']+$vts);
	  $TOTAL8	+=	$resultado_seg;
	  $TOTAL9	+=	$TotalSeg;
	  
	  
}
  	$pdf->SetFillColor(FONDO_TITULO_TABLA);
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);
	$pdf->Cell(20, $h, "TOTAL:", 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(16, $h, floor($TOTAL1), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(20, $h, floor($TOTAL2), 1, 0, 'R',1);
	$pdf->Cell(18, $h, floor($TOTAL3), 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(17, $h, floor($TOTAL4), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(25, $h, floor($TOTAL5), 1, 0, 'R',1);
	$pdf->Cell(23, $h, floor($TOTAL6), 1, 0, 'R',1);
	$pdf->SetFont("Arial","B",8);
	$pdf->Cell(19, $h, floor($TOTAL7), 1, 0, 'R',1);
	$pdf->SetFont("Arial","",8);
	$pdf->Cell(19, $h, floor($TOTAL8), 1, 0, 'R',1);
	$pdf->Cell(18, $h,floor($TOTAL9), 1, 1, 'R',1);

}
	
}
			

$pdf->Close();
$pdf->Output('Reporte_de_produccion_'.$fecha.'.pdf','I');

}



if($_REQUEST['tipo'] == 32 ){ 
 

	$desde= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['desdeOrd']);
	$hasta= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1", $_REQUEST['hastaOrd']);

	if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "")
 	$qOrdenes	=	"SELECT detalle_resumen_maquina_bs.kilogramos AS kilogramos, detalle_resumen_maquina_bs.millares AS millares, bolseo.fecha, detalle_resumen_maquina_bs.orden, bolseo.turno, detalle_resumen_maquina_bs.id_detalle_resumen_maquina_bs, maquina.numero FROM bolseo ".
					" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".
					" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".
					" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".
					" WHERE bolseo.fecha BETWEEN '".$desde."' AND '".$hasta."' AND detalle_resumen_maquina_bs.orden LIKE '%".$_REQUEST['orden']."%'  GROUP BY resumen_maquina_bs.id_resumen_maquina_bs ORDER BY bolseo.fecha, bolseo.turno, maquina.id_maquina, detalle_resumen_maquina_bs.orden ASC ";
	if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] == "")
 	$qOrdenes	=	"SELECT  DAY(fecha) AS dia, SUM(detalle_resumen_maquina_bs.kilogramos) AS kilogramos, SUM(detalle_resumen_maquina_bs.millares) AS millares, fecha, detalle_resumen_maquina_bs.orden, detalle_resumen_maquina_bs.id_detalle_resumen_maquina_bs FROM bolseo ".
					" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".
					" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".
					" WHERE bolseo.fecha >= '".$desde."' AND bolseo.fecha <= '".$hasta."' GROUP BY fecha,detalle_resumen_maquina_bs.orden ORDER BY fecha, detalle_resumen_maquina_bs.orden ASC ";


	if($_REQUEST['modelo'] == 1 && $_REQUEST['orden'] != "")
 	$qOrdenes	=	"SELECT  SUM(detalle_resumen_maquina_bs.kilogramos) AS kilogramos, SUM(detalle_resumen_maquina_bs.millares) AS millares, bolseo.fecha, detalle_resumen_maquina_bs.orden, bolseo.turno, detalle_resumen_maquina_bs.id_detalle_resumen_maquina_bs, maquina.numero FROM bolseo ".
					" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".
					" LEFT JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina".
					" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".
					" WHERE bolseo.fecha BETWEEN '".$desde."' AND '".$hasta."' AND detalle_resumen_maquina_bs.orden LIKE '%".$_REQUEST['orden']."%'  GROUP BY detalle_resumen_maquina_bs.orden ORDER BY fecha, bolseo.turno, maquina.id_maquina, detalle_resumen_maquina_bs.orden, detalle_resumen_maquina_bs.id_detalle_resumen_maquina_bs ASC ";
	if($_REQUEST['modelo'] == 1 && $_REQUEST['orden'] == "")
 	$qOrdenes	=	"SELECT  DAY(fecha) AS dia,SUM(detalle_resumen_maquina_bs.kilogramos) AS kilogramos, SUM(detalle_resumen_maquina_bs.millares) AS millares, detalle_resumen_maquina_bs.orden FROM bolseo ".
					" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".
					" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".
					" WHERE bolseo.fecha >= '".$desde."' AND bolseo.fecha <= '".$hasta."' GROUP BY detalle_resumen_maquina_bs.orden ORDER BY detalle_resumen_maquina_bs.orden ASC ";
		
	$rOrdenes	= 	mysql_query($qOrdenes);	


		$revisa 	= explode("-", $desde);
		
		$ano = $revisa[0];
		$mes1 = $revisa[1];
		$dia = $revisa[2];	
		
		$revisa2 	= explode("-", $hasta);
		
		$ano2 = $revisa2[0];
		$mes2 = $revisa2[1];
		$dia2 = $revisa2[2];	
	
$medidaY = 250;
$h = 3.5;
$pdf=&new PDF();
$pdf->FPDF('P','mm','LETTER');
$pdf->SetFont("Arial","",8);
$pdf->AliasNbPages();

$pdf->AddPage();
$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);
$pdf->SetY(13);
$pdf->Cell(195, 4, "", 0, 1, 'C');
	
if($mes1 == $mes2){ 
$pdf->Cell(195, 4, "Reporte de Ordenes del ".$dia." al ".$dia2." de ".$mes[intval($mes2)]." de ".$ano2 , 0, 1, 'C');
} 
if($mes1 != $mes2 && $mes2 != 1){
$pdf->Cell(195, 4, "Reporte de Ordenes del ".$dia." de ".$mes[intval($mes1)]." al ".$dia2." de ".$mes[intval($mes2)]." de ".$ano2 , 0, 1, 'C');
}
if($mes1 != $mes2 && $mes2 == 1){ 
$pdf->Cell(195, 4, "Reporte de Ordenes del ".$dia." de ".$mes[intval($mes1)]." de ".$ano." al ".$dia2." de ".$mes[intval($mes2)]." de ".$ano2 , 0, 1, 'C');
}

$pdf->SetTextColor(LETRA_TITULO_TABLA);
$pdf->Ln(5);

if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "")
$pdf->SetX(48);
else if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] == "")
$pdf->SetX(68);
else
$pdf->SetX(78);
if($_REQUEST['modelo'] ==2 ){
$pdf->Cell(20, 4, "FECHA", 1, 0, 'C',1);
	if( $_REQUEST['orden'] != "" ){
		$pdf->Cell(20, 4, "TURNO", 1, 0, 'C',1);
		$pdf->Cell(20, 4, "MAQUINA", 1, 0, 'C',1);
	}
}
$pdf->Cell(20, 4, "NO ORDEN", 1, 0, 'C',1);
$pdf->Cell(20, 4, "TOTAL Mi", 1, 0, 'C',1);
$pdf->Cell(20, 4, "TOTAL Kg", 1, 1, 'C',1);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);

for($z = 0; $dOrdenes	=	mysql_fetch_assoc($rOrdenes);$z++){ 
if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "")
$pdf->SetX(48);
else if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] == "")
$pdf->SetX(68);
else
$pdf->SetX(78);
    if($dOrdenes['orden'] ==  ""){ $z = $z+1; } else {
	if($_REQUEST['orden'] == "") 
								$color = $dOrdenes['dia'];
							else	
								$color = $z;
								
							if(bcmod(intval($color),2) == 0){ 
								$back 	= "#DDDDDD";  
								$frente = "#B4C3EC";
								}  else { 
								$back 	= "#FFFFFF";  
								$frente = "#B4C3EC";
								}
								if($_REQUEST['modelo'] == 2 ){
									$pdf->Cell(20, $h, fecha_tabla($dOrdenes['fecha']), 1, 0, 'C',0);
									if( $_REQUEST['orden'] != "" ){
										$pdf->Cell(20, $h, $dOrdenes['turno'], 1, 0, 'C',0);
										$pdf->Cell(20, $h, $dOrdenes['numero'], 1, 0, 'C',0);
									}
								}
$pdf->Cell(20, $h, $dOrdenes['orden'], 1, 0, 'C',0);
$pdf->Cell(20, $h, floor($dOrdenes['millares']), 1,0 , 'R',0);
$pdf->Cell(20, $h, floor($dOrdenes['kilogramos']), 1, 1, 'R',0);
	
							$TotalMillares	+=  $dOrdenes['millares'];
							$TotalKilos		+=	$dOrdenes['kilogramos'];
							
if($pdf->GetY() > $medidaY){
$pdf->AddPage();
$pdf->SetY(25);


if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "")
$pdf->SetX(48);
else if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] == "")
$pdf->SetX(68);
else
$pdf->SetX(78);


$pdf->SetTextColor(LETRA_TITULO_TABLA);
if($_REQUEST['modelo'] ==2 ){

$pdf->Cell(20, 4, "FECHA", 1, 0, 'C',1);
	if( $_REQUEST['orden'] != "" ){
		$pdf->Cell(20, 4, "TURNO", 1, 0, 'C',1);
		$pdf->Cell(20, 4, "MAQUINA", 1, 0, 'C',1);
	}
}
$pdf->Cell(20, 4, "NO ORDEN", 1, 0, 'C',1);
$pdf->Cell(20, 4, "TOTAL Mi", 1, 0, 'C',1);
$pdf->Cell(20, 4, "TOTAL Kg", 1, 1, 'C',1);
$pdf->SetTextColor(LETRA_TITULO_TABLA2);

			
			
			}				
							}  
							
							
							}  
						if(($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "")  ||   ($_REQUEST['modelo'] == 1 && $_REQUEST['orden'] == "") ){							
						if($_REQUEST['modelo'] == 2 && $_REQUEST['orden'] != "") $pdf->SetX(108);
						if($_REQUEST['modelo'] == 1 && $_REQUEST['orden'] == "") $pdf->SetX(78);
						$pdf->SetTextColor(LETRA_TITULO_TABLA);
						$pdf->Cell(20, $h, "TOTAL: ", 1, 0, 'C',1);
						$pdf->SetTextColor(LETRA_TITULO_TABLA2);
						$pdf->Cell(20, $h, floor($TotalMillares), 1,0 , 'R',0);
						$pdf->Cell(20, $h, floor($TotalKilos), 1, 1, 'R',0);

}

$pdf->Close();
$pdf->Output('Reporte_de_produccion_'.$fecha.'.pdf','I');

}



if($_REQUEST['tipo'] == 14 || $_REQUEST['tipo'] == 15 ||  $_REQUEST['tipo'] == 16  ){ 

$tipo = $_REQUEST['tipo'];

if($tipo == 14) $area = 1;
if($tipo == 15) $area = 2;
if($tipo == 16) $area = 4;

$fecha		=	fecha_tablaInv($_REQUEST['fecha_incidencia']);
$fechaFin	=	fecha_tablaInv($_REQUEST['fecha_incidencia_f']);



if($area == 1){
  $qReportes	=	"SELECT id_orden_produccion, turno, id_supervisor, orden_produccion.observaciones, fecha FROM entrada_general INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general".
					" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."' ORDER BY fecha, turno ASC";
					}

if($area == 2){
 $qReportes		=	"SELECT id_impresion, turno, id_supervisor, impresion.observaciones, fecha FROM entrada_general INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general".
					" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."' ORDER BY fecha, turno ASC";
}

if($area == 4){
 $qReportes	=	"SELECT id_bolseo, turno, id_supervisor, observaciones, fecha FROM bolseo ".
					" WHERE fecha BETWEEN '".$fecha."' AND '".$fechaFin."' ORDER BY fecha, turno ASC";
}

$rReportes	=	mysql_query($qReportes);
$nReportes	=	mysql_num_rows($rReportes);

$medidaY = 200;
$h = 6;
$pdf=&new PDF();
$pdf->FPDF('P','mm','LETTER');
$pdf->SetFont("Arial","",8);
$pdf->AliasNbPages();

$pdf->AddPage();
$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_TITULO_TABLA);


if($_REQUEST['tipo'] == 14){	$pdf->Cell(195, 4	, "REPORTE DE INCIDENCIAS DIARIAS EXTRUDER", 0, 1, 'C'); 	$ancho = 81; }
if($_REQUEST['tipo'] == 15){	$pdf->Cell(195, 4	, "REPORTE DE INCIDENCIAS DIARIAS IMPRESION", 0, 1, 'C');	$ancho = 81; }
if($_REQUEST['tipo'] == 16){	$pdf->Cell(195, 4	, "REPORTE DE INCIDENCIAS DIARIAS BOLSEO", 0, 1, 'C');		$ancho = 97; }
	
		
	for($t = 1 ; $dReportes	=	mysql_fetch_row($rReportes); $t++){
	
	if($dReportes[1] == 1) $turno = "MATUTINO";	
	if($dReportes[1] == 2) $turno = "VESPERTINO";	
	if($dReportes[1] == 3) $turno = "NOCTURNO";	

	$pdf->Ln(5);
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(20, 4, "FECHA", 1, 0, 'C',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->Cell(20, 4, fecha_tabla($dReportes[4]), 1, 1, 'C');
	$pdf->SetTextColor(LETRA_TITULO_TABLA2);	
	$pdf->Cell(195, 4 , "TURNO ".$turno, 0, 1, 'C');


	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(23, 4	, "SUPERVISOR:" , 1, 0, 'R',1);

 	$qSuper	=	"SELECT nombre FROM supervisor WHERE id_supervisor = ".$dReportes[2]."";
	$rSuper	=	mysql_query($qSuper);
	$dSuper =	mysql_fetch_row($rSuper);

	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->Cell(172, 4,  $dSuper[0]  , 0, 1, 'L',0);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	
	$pdf->Cell(23, 4	, "MAQUINA" , 1, 0, 'C',1);
	$pdf->Cell(22, 4, "F. ELECTR." , 1, 0, 'C',1);
	$pdf->Cell(22, 4, "F. PERSONAL" , 1, 0, 'C',1);
	$pdf->Cell(16, 4, "MANTTO." , 1, 0, 'C',1);
	if($area == 1 || $area ==2) 
	{ 
		 if($area == 1) $pdf->Cell(16, 4	, "C. MALLAS" , 1, 0, 'C',1); 
		 if($area == 2) $pdf->Cell(16, 4	, "C. IMPR." , 1, 0, 'C',1); 
	}
	$pdf->Cell(15, 4	, "OTRA" , 1, 0, 'C',1);
	$pdf->Cell($ancho, 4	, "OBSERVACIONES" , 1, 1, 'C',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->SetFillColor(FONDO_TITULO_TABLA_2);
	$a = 0;
	$e = 0;
	$d = 0;
	
	$qTiempos	=	"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina WHERE tipo = $area AND id_produccion = ".$dReportes[0]." ORDER BY maquina.numero ASC";
	$rTiempos	=	mysql_query($qTiempos);
                            
                            for($a = 0; $dTiempo	=	mysql_fetch_assoc($rTiempos); $a++){
							if(bcmod(intval($a),2) == 0)
									$d = 0;
							else
									$d = 1;
								if($dTiempo['fallo_electrico'] 	== '00:00:00') 	$fallo 			= ""; else $fallo 			= $dTiempo['fallo_electrico'];
								if($dTiempo['mantenimiento'] 	== '00:00:00') 	$mantenimiento 	= ""; else $mantenimiento 	= $dTiempo['mantenimiento'];
								if($dTiempo['falta_personal'] 	== '00:00:00') 	$falta_personal = ""; else $falta_personal 	= $dTiempo['falta_personal'];
								if($dTiempo['otras'] 			== '00:00:00') 	$otras 			= ""; else $otras 			= $dTiempo['otras'];
								if($area == 1) $opcion = "mallas";
								if($area == 2) $opcion = "cambio_impresion";
								if($dTiempo[$opcion]		 	== '0' || $dTiempo[$opcion] == '') 		$mallas		 	= ""; else if($dTiempo[$opcion] != '0')  $mallas			= "SI";
								
								if($dTiempo['observaciones']	== '0') 	$observaciones	= ""; else $observaciones	=	$dTiempo['observaciones'];
								
								if($fallo == "" && $mantenimiento == "" && $falta_personal == "" && $otras == "" && $mallas == "" && $observaciones == ""){
								$a = $a + 1;
								}	
								else
								{
			
									$pdf->Cell(23, 4, $dTiempo['numero'] .' -'. ucfirst(strtolower($dTiempo['marca'])), 1, 0, 'L',$d);
									$pdf->Cell(22, 4, $fallo , 1, 0, 'C',$d);
									$pdf->Cell(22, 4, $falta_personal , 1, 0, 'C',$d);
									$pdf->Cell(16, 4, $mantenimiento , 1, 0, 'C',$d);
									if($area == 1 || $area == 2)  
										$pdf->Cell(16, 4	, $mallas , 1, 0, 'C',$d);									
									$pdf->Cell(15,4	, $otras , 1, 0, 'C',$d);
									
									$pdf->MultiCell($ancho, 4	,ucfirst(strtolower($dTiempo['observaciones'])),1, 'J',$d);
			
								}
							}
				if($area == 2)
				{ 
				$pdf->Ln(5);
							$qTiempos	=	"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina WHERE tipo = 3 AND id_produccion = ".$dReportes[0]." ORDER BY maquina.numero ASC";
                            $rTiempos	=	mysql_query($qTiempos);
                            
                            for($e = 0; $dTiempo	=	mysql_fetch_assoc($rTiempos); $e++){
							if(bcmod(intval($e),2) == 0)
									$r = 0;
							else
									$r = 1;
									
								if($dTiempo['fallo_electrico'] 	== '00:00:00') 	$fallo 			= ""; else $fallo 			= $dTiempo['fallo_electrico'];
								if($dTiempo['mantenimiento'] 	== '00:00:00') 	$mantenimiento 	= ""; else $mantenimiento 	= $dTiempo['mantenimiento'];
								if($dTiempo['falta_personal'] 	== '00:00:00') 	$falta_personal = ""; else $falta_personal 	= $dTiempo['falta_personal'];
								if($dTiempo['otras'] 			== '00:00:00') 	$otras 			= ""; else $otras 			= $dTiempo['otras'];
								if($dTiempo['cambio_impresion']	== '0' || $dTiempo['cambio_impresion']	== '') 		$mallas		 	= ""; else $mallas			= "SI";
								if($dTiempo['observaciones']	== '0') 		$observaciones	= ""; else $observaciones	=	$dTiempo['observaciones'];
								
								if($fallo == "" && $mantenimiento == "" && $falta_personal == "" && $otras == "" && $mallas == "" && $observaciones == ""){
								$e = $e + 1;
								}else {	
								
									$pdf->Cell(23, 4, ucfirst(strtolower($dTiempo['marca'])).' -'.$dTiempo['numero'] , 1, 0, 'L',$r);
									$pdf->Cell(22, 4, $fallo , 1, 0, 'C',$r);
									$pdf->Cell(22, 4, $falta_personal , 1, 0, 'C',$r);
									$pdf->Cell(16, 4, $mantenimiento , 1, 0, 'C',$r);
									$pdf->Cell(16, 4, $mallas , 1, 0, 'C',$r);
									$pdf->Cell(15, 4, $otras , 1, 0, 'C',$r);
									$pdf->MultiCell($ancho	, 4	, ucfirst(strtolower($dTiempo['observaciones'])), 1, 'J',$r);								
								
								}
							}						
	
				}
				
	$pdf->SetFillColor(FONDO_TITULO_TABLA2);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);
	$pdf->Cell(45, 4, "OBSERVACIONES GENERALES:" , 1, 0, 'R',1);
	$pdf->SetTextColor(LETRA_CAMPOS);
	$pdf->MultiCell(150, 4,ucfirst(strtolower( $dReportes[3] )), 1, 1, 'J',0);
	$pdf->SetTextColor(LETRA_TITULO_TABLA);				
	$pdf->Ln(2);
	if($dReportes[1] == 3 && $t != $nReportes)
	$pdf->AddPage();
	}
	
	
$pdf->Close();
$pdf->Output('Reporte_de_incidencias_'.$fecha.'.pdf','I');

}



if($_REQUEST['tipo'] == 17 || $_REQUEST['tipo'] == 18 ){ 

$tipo = $_REQUEST['tipo'];
	$numero_maq	=	$_REQUEST['id_maquina'];

if($tipo == 17)
{ 
	$area 		= 	1; 
	$WH 		=  	"area IN (4) AND id_maquina IN ($numero_maq) "; 
	$qQuery		= 	"SELECT *  FROM entrada_general ". 
			 		" INNER JOIN orden_produccion ON entrada_general.id_entrada_general = orden_produccion.id_entrada_general";
	$cambio		=	"mallas";
	$compara	=	" orden_produccion.id_orden_produccion = tiempos_muertos.id_produccion ";
	$titulo		=	"DE MALLAS";
	$orden		=	"id_orden_produccion";
}

if($tipo == 18)
{ 
	$area 	= 	2; 
	$WH 	=  	"area IN (2,3)  AND id_maquina IN ($numero_maq) ";
	$qQuery	=	"SELECT * FROM entrada_general ".
				" INNER JOIN impresion ON entrada_general.id_entrada_general = impresion.id_entrada_general";
	$cambio	=	"cambio_impresion";
	$compara =	" impresion.id_impresion = tiempos_muertos.id_produccion";
	$titulo	=	" DE IMPRESION";
	$orden	=	"id_impresion";


	 
}

$fecha	=	fecha_tablaInv($_REQUEST['fecha_hist']);
$fecha_f=	fecha_tablaInv($_REQUEST['fecha_hist_f']);


	$qMaquinas 	= 	"SELECT numero, id_maquina, area FROM maquina  WHERE $WH  ORDER BY area ,numero ASC";
	$rMaquinas	=	mysql_query($qMaquinas);
	
			
			
			

$medidaY = 200;
$h = 6;
$pdf=&new PDF();
$pdf->FPDF('P','mm','LETTER');
$pdf->SetFont("Arial","",8);
$pdf->AliasNbPages();

$pdf->AddPage();
$pdf->SetFillColor(FONDO_TITULO_TABLA2);
$pdf->SetTextColor(LETRA_CAMPOS);
$pdf->Cell(195, 4	, "HISTORIAL DE CAMBIO ". $titulo, 0, 1, 'C'); 

if($_REQUEST['sup_h'] != 0)
{ 
	$qS	=	"SELECT nombre FROM supervisor WHERE id_supervisor = ".$_REQUEST['sup_h']."";
	$rS	=	mysql_query($qS);
	$dS	=	mysql_fetch_row($rS); 
	
	$pdf->Cell(195, 4	, "SUPERVISOR : " .$dS[0], 0, 1, 'L'); 
} 
if($_REQUEST['oper_h'] != 0)
{ 
	$qO	=	"SELECT nombre FROM operadores WHERE id_operador = ".$_REQUEST['oper_h']."";
	$rO	=	mysql_query($qO);
	$dO	=	mysql_fetch_row($rO);
	$pdf->Cell(195, 4	, "OPERADOR : " .$dO[0], 0, 1, 'L'); 
} 
	
$pdf->Cell(195, 4	, "FECHA DEL ".$_REQUEST['fecha_hist']." AL ".$_REQUEST['fecha_hist_f'], 0, 1, 'L');	

if($_REQUEST['turnos_h'] != 0)
{  
	if($_REQUEST['turnos_h'] == 1)		$hturno	=	"MATUTINO";
	else if($_REQUEST['turnos_h'] == 2) $hturno	=	"VESPERTINO";
	else if($_REQUEST['turnos_h'] == 3) $hturno	=	"NOCTURNO";
	$pdf->Cell(195, 4, "TURNO ".  $hturno, 0, 1, 'L');
}

$pdf->Ln(5);

$X_Original	=	$pdf->GetX();
$Y_Original	=	$pdf->GetY();

$X_Actual	=	$pdf->GetX();
$Y_Actual	=	$pdf->GetY();
		
while($dMaquinas = mysql_fetch_assoc($rMaquinas)){
	
	$qReportes	=	$qQuery. " INNER JOIN tiempos_muertos ON $compara ".
					" INNER JOIN oper_maquina ON tiempos_muertos.id_maquina = oper_maquina.id_maquina".
					" WHERE fecha BETWEEN '".$fecha."' AND '".$fecha_f."' AND tiempos_muertos.id_maquina = ".$dMaquinas['id_maquina']." AND $cambio = 1 AND entrada_general.autorizada = 1 ";
					

	$qReportes	.=	" GROUP BY $orden ORDER BY fecha ASC";	
				
	$rReportes	=	mysql_query($qReportes);		
	$nReportes	=	mysql_num_rows($rReportes);	
		
	$totales	=	$nReportes * 3.5;
	
	if($X_Actual + ANCHO_TABLA > ANCHO_MAXIMO)
	{
		$Y_Actual	=	$TablaMayor + ESPACIADO_ENTRE_TABLAS;
		$X_Actual	=	$X_Original;
		$TablaMayor	=	0;
			if( ($Y_Actual + $totales) >= 250)
			{
				$pdf-> AddPage();
				$Y_Actual 	=	$pdf->GetY();
				$X_Actual	=	$pdf->GetX();
			}
	}
	if($Y_Actual >= 240)
	{
		$pdf->AddPage();
		$Y_Actual	=	$pdf->GetY();
		$X_Actual	=	$pdf->GetX();
	}
	$pdf->SetY($Y_Actual);
	$pdf->SetX($X_Actual);
	
	if($nReportes > 0){	
		$pdf->__escribeTabla($rReportes, $dMaquinas['id_maquina'], $dMaquinas['numero']);
	}
else
{ 
$X_Actual	-= ANCHO_TABLA;
}
$X_Actual	+= ANCHO_TABLA;
}
		
		  
$pdf->Close();
$pdf->Output('Reporte_de_incidencias_'.$fecha.'.pdf','I');

}





?>