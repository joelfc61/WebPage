<script language="javascript" type="text/javascript">
var win=null;
function NewWindow(mypage,myname,w,h,scroll,pos){
   if(pos=="random"){
      LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;
	  TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;
	  }

   if(pos=="center"){
      LeftPosition=(screen.width)?(screen.width-w)/2:100;
	  TopPosition=(screen.height)?(screen.height-h)/2:100;
	  }

   else if((pos!="center" && pos!="random") || pos==null){
      LeftPosition=0;TopPosition=20
	  }

   settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
   win=window.open(mypage,myname,settings);

}
</script>
<form name="revisa" method="post" action="revisa1.php" onsubmit="return validar_forma();">
<br>
<br>
<table cellpadding="3" cellspacing="0" border="0" align="center">
<tr>
	<td width="99" class="style7">Fecha a revisar:</td>
    <td width="236" class="style5"><input type="text" name="fecha" readonly="readonly" id="fecha" value="<?=$_REQUEST['fecha']?>"  />
    <a href="#" onclick="NewWindow('minical.php?destino=fecha','Calendario',300,250,false,'center')"><img border="0" alt="Calendario" src="images/calendario.jpg" /></a>

    </td>
</tr>
<tr>
	<td class="style7">Turno actual:</td>
    <td class="style5"><select name="turno" id="turno">
    <? for($z = 1; 	$z < 5 ; $z++){ ?>
    	<option value="<?=$z?>" <? if($_REQUEST['turno'] == $z) echo "selected";?>><?
		
		if($z == 1) echo "Matutino"; 
		if($z == 2) echo "Vespertino";
		if($z == 3) echo "Nocturno";
		if($z == 4) echo "Descanzo";

		
		 ?></option>
    <? } ?>    
    </select></td>
</tr>
<tr>
	<td colspan="2" align="right"><input name="submit" value="Enviar" type="submit">
	  <br>
	  <br></td>
</tr>


<? 
if(isset($_POST['submit'])){ ?>
<tr>
	<td colspan="2">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
<? 
$dias_diferencia = '0';

$fecha			= $_POST['fecha'];	
$turno 			= $_POST['turno'];
$turno_anterior	= $_POST['turno_anterior']; 


$revisa 	= explode("/" ,$_POST['fecha']);
$compara 	= explode("/", date('d/m/Y'));

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


		$temp = $turno;
		$turno = $turno;
		$a = 0;
		do{

								
												if($a == 0)
				{ 
				    $this->encuentra[$a]  = $temp ;
					$a = $a ;
				}		
								
							if($a <= $dias_diferencia && $temp == 1 ){	
									for($b = 0 ; $b < 2 ; $b++){
										$encuentra[$a+$b]  = $temp+1 ;
										$a = $a + $b ;		
									}
									$temp =  $temp + 1 ;	
									$a = $a+1;
							}  
						
							if( $a < $dias_diferencia && $temp == 2 ){	
									for($c = 0 ; $c < 2 ; $c++){
										$encuentra[$a+$c]  = $temp+1 ;
										$a = $a + $c ;		
									}
									$temp =  $temp + 1 ;	
									$a = $a+1;
							}
								
							if($a <= $dias_diferencia && $temp == 3 ){	
									for($d = 0 ; $d < 2	 ; $d++){
									 	$encuentra[$a+$d]  = $temp+1 ;	
										$a = $a + $d ;										

									}
								$temp =  $temp +1 ;	
								$a = $a+1;
							}
							
							if($a < $dias_diferencia && 	$temp == 4 ){	
								$temp = 0;
									for($e = 0 ; $e < 2	 ; $e++){
									 	$encuentra[$a+$e]  = $temp+1 ;
										$a = $a + $e ;									
									}
								$temp =  $temp +1;
								$a = $a+0 ;		
							}						
				$a++;
			
			}  while($a < $dias_diferencia)	;	
			
			
		
	//	echo array_values($encuentra);
 $dias_diferencia;
 $dias_diferencia = $dias_diferencia - 1;
 $encuentra[$dias_diferencia];
 $resultado.'  Resultado <br>';
 $turno_nuevo.'  Turno <br>';
 $dias_diferencia.' dias <br>';

?>
   	  <tr>
            	<td width="65%" align="left" class="style7">Fecha actual: </td>
              <td width="35%" class="style5"><? echo date('d/m/Y')?></td>
          </tr>
            <tr>
            	<td align="left" class="style7">Fecha destino: </td>
           	  <td class="style5"><?=$_REQUEST['fecha']?></td>
		  </tr>
            <tr>
            	<td align="left" class="style7">Turno asignado a la fecha destino: </td>
           	  <td class="style5"><b style="color:#FF0000"><?
        if($dias_diferencia == '-1' )
			$encuentra[$dias_diferencia] =	 $_REQUEST['turno'];	
		 
				
		if($encuentra[$dias_diferencia] == 1) echo "Matutino";
		if($encuentra[$dias_diferencia] == 2) echo "Vespertino";
		if($encuentra[$dias_diferencia] == 3) echo "Nocturno";
		if($encuentra[$dias_diferencia] == 4) echo "Descanzo";				
			
				?></b></td>
			</tr>            

    </table></td></tr>         
<? } ?>
</table>
<br>
<br>
</form>