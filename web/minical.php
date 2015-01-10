<script language="javascript" type="text/javascript">

function fecha(day,month,year){
	var fecha = opener.document.getElementById('<?=$_GET['destino']?>');
	fecha.value = day + '/' + month + '/' + year;
	window.close();
}

</script>
<!--Inicia calendario-->
<?


//*************INICIA CONFIGURACION************ 
$value1="value"; 
$value2="value"; 
$value3="value"; 

$item1="Día hábil"; //Description 1
$item2="Fin de semana"; //Description 2
$item3="Día actual"; //Description 3

//ACTION SETTINGS
$doc_destino ='minical.php';// File to open. If empty be used PHP_SELF
$vars ="V1=$value1"; // Vars to send [var=value] NOTE: The ampersand only will be used from 2nd value +
$vars .="&V2=$value2"; // Vars to send [&var=value]
$vars .="&V3=$value3"; // Vars to send [&var=value] .... can be extended

//FORMAT SETTINGS
$lang ="es"; // Language to display. If empty english will be displayed [ es | en ]
$font ="arial"; // Font family General

//TITLE
$fs_title ="8pt"; // Font size title SAMPLE:10pt [ INTpt | INTpx ]
$fc_title ="#FFFFFF"; // Font color title SAMPLE: #FFFFFF
$fw_title ="regular"; // Font weight title [ bold | lighter ]
$bg_title ="#595F6B"; // Background title SAMPLE: #404c8c

//DAY NAMES
$fs_daynames ="8pt"; // Font size day names
$fc_daynames ="#FFFFFF"; // Font color day names SAMPLE: #FFFFFF
$fw_daynames ="lighter"; // Font weight day names [ bold | lighter ]
$bg_daynames ="#44484F"; // Background color daynames SAMPLE: #666666

//LUNES A SABADO
$fs_entre ="8pt"; // Font size Monday to Friday
$fc_entre ="#187EBC"; // Font Color Monday to Friday SAMPLE: #0000AA
$bg_entre ="#FFFFFF"; // Background Color Monday to Friday SAMPLE: #DDDDDD
$fw_entre ="lighter"; // Font weight Monday to Friday [ bold | lighter ]

//SATURDAY AND SUNDAY
$fs_finde ="8pt"; // Font size Monday to Weekend
$fc_finde ="#1F3D5F"; // Font Color Weekend SAMPLE: #DDDDDD
$bg_finde ="#E2E2E2"; // Background Color Weekend SAMPLE: #AA0000
$fw_finde ="lighter"; // Font weight Weekend [ bold | lighter ]

//ACTUAL DAY
$fs_dct ="8pt"; // Font size actual day
$fc_dct ="#187EBC";
$bg_dct ="#FFFFFF"; // Background Color actual day SAMPLE: #F0E68C
$fw_dct ="lighter"; // Font weight actual day [ bold | lighter ]

//TABLE SETTINGS
$width ="200px"; // Width table
$height ="220px"; // Height table
$cellpadding ="0"; // Cellpadding table
$cellspacing ="1"; // Cellspacing table
$border ="0"; // Border Table

//*************TERMINA CONFIGURACION*********** 

?>
<div align="center">
  <style type=text/css>
.CAL_TD { font-family : <?=$font;?>; font-size : <?=$fs_entre;?>; color:<?=$fc_entre;?>;  font-weight:<?=$fw_entre;?>;}
.CAL_DOMINGO { font-family : <?=$font;?>; font-size : <?=$fs_finde;?>; color:<?=$fc_finde;?>; background-color:<?=$bg_finde;?>; font-weight:<?=$fw_finde;?>;}
A{ COLOR:<?=$fc_entre;?>; }
A.CAL:LINK { COLOR:<?=$fc_entre;?>; }
A.FDS:LINK { COLOR:<?=$fc_finde;?>; }
A.FDS:VISITED{ COLOR:<?=$fc_finde;?>; }
A.THISDAY:LINK { COLOR:<?=$fc_dct;?>; }
A.THISDAY:VISITED{ COLOR:<?=$fc_dct;?>; }
A:VISITED{ COLOR:<?=$fc_entre;?>; }
.altn { font-family : <?=$font;?>; font-size : <?=$fs_daynames;?>; color: <?=$fc_daynames;?>; background-color: <?=$bg_daynames;?>; font-weight : <?=$fw_daynames;?>;}
.tit { font-family : <?=$font;?>; font-size : <?=$fs_title;?>; color: <?=$fc_title;?>; background-color: <?=$bg_title;?>; font-weight: <?=$fw_title;?>;}
.fs { font-family : <?=$font;?>; background-color: <?=$bg_finde;?>; color: <?=$fc_finde;?>; font-size : <?=$fs_finde;?>; font-weight: <?=$fw_finde;?>; text-align: center; }
.da { font-family : <?=$font;?>; background-color: <?=$bg_entre;?>; color: <?=$fc_entre;?>; font-size : <?=$fs_finde;?>; font-weight: <?=$fw_finde;?>; text-align: center; }
.nom{ font-family : <?=$font;?>; font-size : <?=$fs_daynames;?>;}
.entre { font-family : <?=$font;?>; background-color: <?=$bg_entre;?>; color: <?=$fc_entre;?>; font-size : <?=$fs_finde;?>; font-weight: <?=$fw_finde;?>; text-align: center; }

  </style>
  <?
//EXTRAER DIA DE LA SEMANA  
function extraer_d_semana($d,$cal_mes,$ano){
	$numerodsemana = date('w', mktime(0,0,0,$cal_mes,$d,$ano));
	if ($numerodsemana == 0) {
		$numerodsemana = 6;
	}
	else{
		$numerodsemana--;
	}
	return $numerodsemana;
}

//ULTIMO DIA
function ultimod($cal_mes,$ano){
	$ultimo_d=28;
	while (checkdate($cal_mes,$ultimo_d + 1,$ano)){
		$ultimo_d++;
	}
	return $ultimo_d;
}

//EXTRAER MES EN ESPAÑOL
function extraer_mes_es($cal_mes)
{
	switch ($cal_mes)
	{
	case 1:
	$nombre_mes="ENERO";
	break;
	case 2:
	$nombre_mes="FEBRERO";
	break;
	case 3:
	$nombre_mes="MARZO";
	break;
	case 4:
	$nombre_mes="ABRIL";
	break;
	case 5:
	$nombre_mes="MAYO";
	break;
	case 6:
	$nombre_mes="JUNIO";
	break;
	case 7:
	$nombre_mes="JULIO";
	break;
	case 8:
	$nombre_mes="AGOSTO";
	break;
	case 9:
	$nombre_mes="SEPTIEMBRE";
	break;
	case 10:
	$nombre_mes="OCTUBRE";
	break;
	case 11:
	$nombre_mes="NOVIEMBRE";
	break;
	case 12:
	$nombre_mes="DICIEMBRE";
	break;
	}
return $nombre_mes;
}

//EXTRAER MES EN INGLES
function extraer_mes_en($cal_mes){
	switch ($cal_mes)
		{
		case 1:
		$nombre_mes="January";
		break;
		case 2:
		$nombre_mes="Febraury";
		break;
		case 3:
		$nombre_mes="March";
		break;
		case 4:
		$nombre_mes="April";
		break;
		case 5:
		$nombre_mes="May";
		break;
		case 6:
		$nombre_mes="June";
		break;
		case 7:
		$nombre_mes="July";
		break;
		case 8:
		$nombre_mes="August";
		break;
		case 9:
		$nombre_mes="September";
		break;
		case 10:
		$nombre_mes="October";
		break;
		case 11:
		$nombre_mes="November";
		break;
		case 12:
		$nombre_mes="December";
		break;
		}
	return $nombre_mes;
}

/*INICIO FUNCION MOSTRAR CALENDARIO*/
function mostrar_calendario($d,$cal_mes,$ano)
{
	global $mes_anterior, $mes_siguiente, $vars, $doc_destino, $lang, $width, $height, $cellpadding,    $cellspacing, $border;
	if ($doc_destino==""){$doc_destino=$_SERVER['PHP_SELF'];}
	$cal_mes_hoy=date("m");
	$ano_hoy=date("Y");
	if (($cal_mes_hoy <> $cal_mes) && ($ano_hoy <> $ano))
		{
		$hoy=0;
		}
	else
		{
		$hoy=date("d");
		}
	//Extraigo datos que serán mostrados en pantalla
	$lang=strtolower($lang);
	if($lang=="es")
		{
		$nombre_mes = extraer_mes_es($cal_mes);
		$GLOBALS['seleccionado']="Seleccionado";
		$ds[]="L";
		$ds[]="M";
		$ds[]="M";
		$ds[]="J";
		$ds[]="V";
		$ds[]="S";
		$ds[]="D";
		}
	else if($lang=="en")
		{
		$nombre_mes = extraer_mes_en($cal_mes);
		$GLOBALS['seleccionado']="Selected";
		$ds[]="Mo";
		$ds[]="Tu";
		$ds[]="We";
		$ds[]="Th";
		$ds[]="Fr";
		$ds[]="Sa";
		$ds[]="Su";
		}
	else
		{
		$nombre_mes = extraer_mes_en($cal_mes);
		$GLOBALS['seleccionado']="Selected";
		$ds[]="Mo";
		$ds[]="Tu";
		$ds[]="We";
		$ds[]="Th";
		$ds[]="Fr";
		$ds[]="Sa";
		$ds[]="Su";
		}
	echo "<table width=$width height=$height cellspacing=$cellspacing cellpadding=$cellpadding
	border=$border><tr><td colspan=8 align=center class=tit bgcolor=#1F3D5F>";
	echo "<table width=100% cellspacing=1 cellpadding=0 border=0><tr><td 
	style=font-size:10pt;font-weight:bold;color:white>";
	$cal_mes_anterior = $cal_mes - 1;
	$ano_anterior = $ano;
	if ($cal_mes_anterior==0)
		{
		$ano_anterior--;
		$cal_mes_anterior=12;
		}
	$mes_anterior ="$doc_destino?$vars&d=1&m=$cal_mes_anterior&a=$ano_anterior&destino=".$_GET['destino']."";
	echo "<a style=color:white;text-decoration:none href=$mes_anterior>&lt;&lt;</a></td>";

	echo "<td align=center class=tit>$nombre_mes $ano</td>";
	
	echo "<td align=right style=font-size:10pt;font-weight:bold;color:white>";
	$cal_mes_siguiente = $cal_mes + 1;
	$ano_siguiente = $ano;
	if ($cal_mes_siguiente==13)
		{
		$ano_siguiente++;
		$cal_mes_siguiente=1;
		}
	$mes_siguiente ="$doc_destino?$vars&d=1&m=$cal_mes_siguiente&a=$ano_siguiente&destino=".$_GET['destino']."";
	echo "<a style=color:white;text-decoration:none href=$mes_siguiente>&gt;&gt;</a></td></tr>
	</table></td></tr>";
	print(" <tr>
	<td width=14% align=center class=altn>$ds[0]</td>
	<td width=14% align=center class=altn>$ds[1]</td>
	<td width=14% align=center class=altn>$ds[2]</td>
	<td width=14% align=center class=altn>$ds[3]</td>
	<td width=14% align=center class=altn>$ds[4]</td>
	<td width=14% align=center class=altn>$ds[5]</td>
	<td width=14% align=center class=altn>$ds[6]</td>
	</tr>
	");
	 $d_actual = 1;
     $numero_d = extraer_d_semana(1,$cal_mes,$ano);
	 $ultimo_d = ultimod($cal_mes,$ano);
	
	echo "<tr>";
/*****ESTA PARTE DEL CODIGO ESCRIBE LA PRIMER SEMANA DEL MES***/	
	for ($i=0;$i<7;$i++)
	{
		if ($i < $numero_d)
			{
			echo "<td></td>";
			} 
		else 
			{
			if ( ($i == 6))
				{
				if ($d_actual == $hoy)
					{ 
					echo "<td  ".$bg." style=\"cursor: pointer; cursor: hand;\" class=entre><a nohref=$doc_destino?$vars&d=$d_actual&m=$cal_mes&a=$ano Onclick=\"fecha($d_actual,$cal_mes,$ano )\" class=entre>$d_actual</a></td>";
					}
				else
					{ 
					echo "<td CLASS=fs style=\"cursor: pointer; cursor: hand;\"><a nohref=$doc_destino?$vars&d=$d_actual&m=$cal_mes&a=$ano Onclick=\"fecha($d_actual,$cal_mes,$ano )\" class=fs >$d_actual</a></td>";
					}
				}
	
			else
				{
				if ($d_actual == $hoy)
					{   
					
						echo "<td  ".$bg." style=\"cursor: pointer; cursor: hand;\" class=entre><a nohref=$doc_destino?$vars&d=$d_actual&m=$cal_mes&a=$ano Onclick=\"fecha($d_actual,$cal_mes,$ano )\" class=entre> $d_actual </a></td>";
					}
				else
					{
					
 
					
					echo "<td align=center ".$bg." style=\"cursor: pointer; cursor: hand;\" class=entre><a nohref=$doc_destino?$vars&d=$d_actual&m=$cal_mes&a=$ano Onclick=\"fecha($d_actual,$cal_mes,$ano )\" class=entre> $d_actual </a></td>";
					}
				}
		$d_actual++;
		}
	}
	echo "</tr>";



/*****EL SIGUIENTE CICLO (WHILE) ESCRIBE EL RESTO DE LAS SEMANAS DEL MES******/	
	$numero_d = 0;
	while ($d_actual <= $ultimo_d)
	{ 
	
		if ($numero_d == 0)
			echo "<tr>";
		if ( ($numero_d == 6))
			{
			
			if ($d_actual == $hoy)
				{
                   echo "<td ".$bg." style=\"cursor: pointer; cursor: hand;\" class=entre><a nohref=$doc_destino?$vars&d=$d_actual&m=$cal_mes&a=$ano Onclick=\"fecha($d_actual,$cal_mes,$ano )\" class=entre> $d_actual </a></td>";
				}
			else
				{
					
				
				echo "<td CLASS=fs style=\"cursor: pointer; cursor: hand;\"><a nohref=$doc_destino?$vars&d=$d_actual&m=$cal_mes&a=$ano Onclick=\"fecha($d_actual,$cal_mes,$ano )\" class=fs >$d_actual </a></td>";
				}
			}
		else
			{
			if ($d_actual == $hoy)
				{

				
				echo "<td  ".$bg." style=\"cursor: pointer; cursor: hand;\" class=entre><a nohref=$doc_destino?$vars&d=$d_actual&m=$cal_mes&a=$ano Onclick=\"fecha($d_actual,$cal_mes,$ano )\" class=entre> $d_actual</a></td>";
				}
			else
				{
				
		
				
				echo "<td  align=center ".$bg." style=\"cursor: pointer; cursor: hand;\" class=entre><a nohref=$doc_destino?$vars&d=$d_actual&m=$cal_mes&a=$ano Onclick=\"fecha($d_actual,$cal_mes,$ano )\" class=entre> $d_actual </a></td>";
				}
			}
		$d_actual++;
		$numero_d++;
		if ($numero_d == 7)
			{
			$numero_d = 0;
			echo "</tr>";
			}
	}

	for ($i=$numero_d;$i<7;$i++)
		{
		//DIBUJO CELDAS VACIAS PARA LOS ULTIMOS DIAS DEL MES
		echo "<td></td>";
		}
	echo "</tr>";
	}
	if (!$HTTP_POST_VARS && !$HTTP_GET_VARS){
	$tiempo_actual = time();
	$cal_mes = date("m", $tiempo_actual);
	$ano = date("Y", $tiempo_actual);
	$d=date("d");
	$fecha=$ano . "-" . $cal_mes . "-" . $d;
	}else {
	
	if(!isset($_GET['m'])){$cal_mes=date("m");}else{$cal_mes = $_GET['m'];}
	if(!isset($_GET['a'])){$ano=date("Y");}else{$ano = $_GET['a'];}
	if(!isset($_GET['d'])){$d=date("d");}else{$d = $_GET['d'];}
	
	$fecha=$ano . "-" . $cal_mes . "-" . $d;
}
if(isset($_REQUEST['m']))
 $cal_mes=$_REQUEST['m'];
if(isset($_REQUEST['a']))
 $ano=$_REQUEST['a']; 
mostrar_calendario($d,$cal_mes,$ano);
echo "<tr><td colspan=7 align=left style=width:8px></td></tr>";
echo "</table>";

?>
</div>
<!--Termina calnedario.php-->