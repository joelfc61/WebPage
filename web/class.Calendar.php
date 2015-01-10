<?php

setlocale(LC_ALL, 'es_MX');

class Calendar {

	var $year;
	var $month;
	var $monthNameFull;
	var $monthNameBrief;
	var $startDay;
	var $endDay;
	var $primerdomingo;
	var $conexion;
	var $fechas_inicio;

	function Calendar ( $yr, $mo )
	{

		$this->encuentra[] = array();
		$dias_diferencia = '0';
	    //$turno = 3;
		if($_SESSION['rol'] == 1 ) $turno = '1' ;
		if($_SESSION['rol'] == 2 ) $turno = '2' ;
		if($_SESSION['rol'] == 3 ) $turno = '3' ;
		if($_SESSION['rol'] == 4 ) $turno = '4' ; 
		
		if(isset($_REQUEST['submit'])){
		$anho		= $_REQUEST['anho']; 
		$mes		= $_REQUEST['mes'];	
		}else{
		$anho		= date('Y'); 
		$mes		= date('m');			
		}
		if($mes==1){
		 $mes_anterior = 12;
		 $anho_anterior = $anho -1;
		}
		else{
		$mes_anterior = $mes - 1;
		$anho_anterior = $anho - 1;
		}
		
	
		
		function UltimoDiaAnterior($anho,$mes_anterior){ 
		   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
			   $dias_febrero = 29; 
		   } else { 
			   $dias_febrero = 28; 
		   } 
		   switch($mes_anterior) { 
			   case 1: return 31; break; 
			   case 2: return $dias_febrero; break; 
			   case 3: return 31; break; 
			   case 4: return 30; break; 
			   case 5: return 31; break; 
			   case 6: return 30; break; 
			   case 7: return 31; break; 
			   case 8: return 31; break; 
			   case 9: return 30; break; 
			   case 10: return 31; break; 
			   case 11: return 30; break; 
			   case 12: return 31; break; 
		   } 
		}		
		$ultimo_dia_mes = UltimoDia($anho,$mes);
		
		$ultimo_dia_mes_anterior = UltimoDiaAnterior($anho,$mes_anterior);

		
		
		$fecha 			= $ultimo_dia_mes .'/'. $mes .'/'. $anho;
	 	$fecha_inicial	= "00/11/2007";
		if($mes == 1)
		$fecha2			= $ultimo_dia_mes_anterior .'/'. $mes_anterior.'/' . $anho_anterior;
		
		else
		$fecha2			= $ultimo_dia_mes_anterior .'/'. $mes_anterior.'/' . $anho;
		$revisa 	= explode("/", $fecha);
		$compara 	= explode("/", $fecha_inicial);
		
		$ano1 = $revisa[2];
		$mes1 = $revisa[1];
		$dia1 = $revisa[0];
		
		$ano2 = $compara[2];
		$mes2 = $compara[1];
		$dia2 = $compara[0];
		
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
		$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2);
		
	 	$segundos_diferencia = $timestamp1 - $timestamp2;
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
		
		$revisa2 	= explode("/", $fecha2);
		
		
		$ano12 = $revisa2[2]; 
		$mes12 = $revisa2[1];
		$dia12 = $revisa2[0];
		
		
		$timestamp12 = mktime(0,0,0,$mes12,$dia12,$ano12);
		$timestamp22 = mktime(0,0,0,$mes2,$dia2,$ano2);
		
	 	$segundos_diferencia2 = $timestamp12 - $timestamp22;
	 	$dias_diferencia2 = $segundos_diferencia2 / (60 * 60 * 24);
		
		
		$temp = $turno;
		$turno = $turno;

			for($a = 0 ; $a < $dias_diferencia; $a++){	
			
				if($a == 0)
				{ 
				    $this->encuentra[$a]  = $temp ;
					$a = $a ;
				}
				
				if($a <= $dias_diferencia && $temp == 1 )
				{	
				for($b = 0 ; $b < 2 ; $b++)
					{
					$this->encuentra[$a+$b]  = $temp+1 ;
					$a = $a + $b ;		
					}
				$temp =  $temp + 1 ;	
				$a = $a+1;
				}  
							
				if( $a < $dias_diferencia && $temp == 2 )
				{	
				for($c = 0 ; $c < 2 ; $c++)
					{
					$this->encuentra[$a+$c]  = $temp+1 ;
					$a = $a + $c ;		
					}
				$temp =  $temp + 1 ;	
				$a = $a+1;
				}
									
				if($a <= $dias_diferencia && $temp == 3 )
				{	
				for($d = 0 ; $d < 2	 ; $d++)
					{
				    $this->encuentra[$a+$d]  = $temp+1 ;	
					$a = $a + $d ;									
					}
				$temp =  $temp +1 ;	
				$a = $a+1;
				}
								
				if($a < $dias_diferencia && $temp == 4 )
				{	
				$temp = 0;
				for($e = 0 ; $e < 2	 ; $e++)
					{
					$this->encuentra[$a+$e]  = $temp+1 ;
					$a = $a + $e ;									
					}
				$temp =  $temp +1;
				$a = $a+0 ;								
			}
			
		}  
			
				  
		
		if(isset($_REQUEST['submit'])){
		$this->num		= $dias_diferencia2;
		} else
		$this->num = 0;
		 
		
		$this->year    = $yr;
		$this->month   = (int) $mo;
		$this->startTime = strtotime( "$yr-$mo-01 00:00" );
		$this->endDay = date( 't', $this->startTime );
		$this->endTime   = strtotime( "$yr-$mo-".$this->endDay." 23:59" );
		$this->startDay    = date( 'D', $this->startTime );
		$this->startOffset = date( 'w', $this->startTime ) - 1;
		if ( $this->startOffset < 0 )
		{
			$this->startOffset = 6;
		}
		$this->monthNameFull = strftime( '%B', $this->startTime );
		$this->monthNameBrief= strftime( '%b', $this->startTime );
		$this->dayNameFmt = '%a';
		$this->tblWidth="*";
	}

	function getStartTime()
	{
		return $this->startTime;
	}

	function getEndTime()
	{
		return $this->endTime;
	}
	
	function getYear()
	{
		return $this->year;
	}
	
	function getMonth()
	{
		return $this->month;
	}
	
	function getFullMonthName()
	{
		return $this->monthNameFull;
	}
	
	function getBriefMonthName()
	{
		return $this->monthNameBrief;
	}
	
	function getPrimerDomingo()
	{
		return $this->primerdomingo;
	}
	function setTableWidth( $w )
	{
		$this->tblWidth = $w;
	}

	function setYear( $year )
	{
		$this->year = $year;
	}
	
	function setMonth( $month )
	{
		$this->month = $month;
	}
	
	function setPrimerDomingo($contador)
	{
		$this->primerdomingo = $contador;
	}

	function setDayNameFormat( $f )
	{
		$this->dayNameFmt = $f;
	}

	function display ( )
	{
		ob_start();
		?>
		<table align="center" border="0" cellspacing="0" cellpadding="0" id="calendar" width="100%">
			<?=$this->dspDayNames()?>
			<?=$this->dspDayCells()?>
	    </table>
		<?php
		$c = ob_get_contents();
		ob_end_clean();
		return $c;
	}

	function dspDayNames ( )
	{
		$names = array('L','M','M','J','V','S','D');
		ob_start();
		echo "<tr bgcolor=\"#1E3956\">\n\t";
		for( $i=0; $i<7; $i++ ) {
			echo '<th width="25" class="style5" ><font color="#FFFFFF" size="1">'. $names[$i] .'</font></th>';
		}
		echo "</tr>\n";
		$c = ob_get_contents();
		ob_end_clean();
		return $c;
	}

	function dspDayCells ( )
	{
		$i = 0;
		$temp = 0;
		ob_start();
		echo "<tr>\n";
		$flag = false;
		for( $c=0; $c<$this->startOffset; $c++)
		{
			$i++;
			$flag = ($i % 7 == 0)?false:!$flag;
			echo "<td class=\"notInMonth\">&nbsp;</td>\n\t";
		}
		$temp = $i;

		for( $d=1; $d<=$this->endDay ; $d++ )
		{
			$i++;
			$temp++;
			$flag = $temp % 2;
			$this->dspDayCell( $d, $flag , $this->num);
			if ( $i % 7 == 0 )
			{
				echo "\t</tr>";
				$temp=0;
			}
			if ( $d < $this->endDay && $i%7 == 0 )
			{
				echo "\t<tr>";
			}
		}
		$left = 7 - ( $i%7 );
		if ( $left < 7)
		{
			for ( $c=0; $c<$left; $c++ )
			{
			  echo '<td class="notInMonth">&nbsp;</td>';
			}
			echo "\n\t</tr>";
		}
	
		$c = ob_get_contents();
		ob_end_clean();
		return $c;
	}

	function dspDayCell ( $day, $foo, $num )
	{	
	
		$eldia = ($day<10)?"0".$day:$day;
		$elmes = ($this->getMonth()<10)?"0".$this->getMonth():$this->getMonth();
		$fecha_actual = $this->getYear() . $elmes . $eldia;
		$hoy = date("d");
		$mes = date('m');
	//	$total = count($this->encuentra);
		$salida = "";
		$salida .= "<p class='style4' align='center'>";
	
					if( $this->encuentra[$num] == 1){
					$salida .= "<span class='style4' align='center'>Matutino</span>";
			}
					if( $this->encuentra[$num] == 2){
					$salida .= "<span class='style4' align='center'>Vespertino</span>";
			}
					if( $this->encuentra[$num] == 3){
					$salida .= "<span class='style4' align='center'>Nocturno</span>";
			}
					if( $this->encuentra[$num] == 4){
					$salida .= "<span class='style4' align='center'>Descanso</span>";
			}
		
		$salida .= "</p>";
		if( $hoy == $day)
		{
			$contenido2 = "<td bgcolor=\"#99AAFF\" class=\"hoy\" height=\"55\" align=\"top\" valign=\"top\"><div><span class=\"style7\">$day</span></div>$salida</td>";
		}
		else
		{
			if($foo)
			{
				$contenido2 = "<td bgcolor=\"#F0F2F4\" height=\"55\" align=\"top\" valign=\"top\"><div><span class=\"style5\">$day</span></div>$salida</td>";
			}
			else
			{
				$contenido2 = "<td bgcolor=\"#FFFFFF\" height=\"55\" align=\"top\" valign=\"top\"><div><span class=\"style5\">$day</span></div>$salida</td>";
			}
		}
		echo $contenido2;
		$this->num = $this->num +1;
	//	return $this->num;
	}

}
?>
