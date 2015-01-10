<?php
//session_start();
require_once("conectar.php");

define("RUTA_IMAGENES","img/");

define("IMAGEN_BANNER",RUTA_IMAGENES."sistema_techno_04.jpg");
define("IMAGEN_MOSTRAR",RUTA_IMAGENES."mostrar.jpg");
define("IMAGEN_MODIFICAR",RUTA_IMAGENES."editar.jpg");
define("IMAGEN_ELIMINAR",RUTA_IMAGENES."eliminar.jpg");

///////////// CONFIGURACION PARA BOLSEO //////////////////////////////////////////////////////////////////
  	$qConfig	=	"SELECT * FROM configuracion_bolseo WHERE activado = 1 ORDER BY hasta ASC LIMIT 1 ";
	$rConfig	=	mysql_query($qConfig);
	$dConfig	=	mysql_fetch_assoc($rConfig);
	$nConfig	=	mysql_num_rows($rConfig);
		
	$vtsf = 0;
	if ( $nConfig > 0 ){
		$real_milla = 1;
		$valor_ppm	=	$dConfig['ppm'];
		$valor_seg	=	$dConfig['seg']; 
		$ultimo_Seg = UltimoDia(date('Y'),date('m'));
		$turnos_totale_mes	=	$ultimo_Seg*3;
		$vts	=	$valor_seg	/ $turnos_totale_mes;
		$vtsf	=	$vts*3;
	}
///////////////////////////////////////////////////////////////////////////////////////////


function cebra($numero){
	if(bcmod($numero,2) == 0 ) echo $bgcolor = ' bgcolor="#EEEEEE" ';
	else echo $bgcolor = '';
}

function cebra2($numero){
	if(bcmod($numero,2) == 0 ) return $bgcolor = ' bgcolor="#EEEEEE" ';
	else return $bgcolor = '';
}

function Burbuja($arreglo)
{
$b	=	"";
	for ($e = 0; $e < sizeof($arreglo); $e++)
	{
		for ($i = 0; $i < sizeof($arreglo); $i++)
			{
				if ($arreglo[$i] < $arreglo[$i + 1])
				{
					$b 					= $arreglo[$i];
					$arreglo[$i] 		= $arreglo[$i + 1];
					$arreglo[$i + 1] 	= $b;
               }
		}
	}
      return	$arreglo;
}

/////////////////////////// GRAFICAS ////////////////////////////////////////////////////////////////////
function grafica_barras($arreglo_titulos_x,$arreglo_datos_y,$medida,$re,$arreglo_datos_z,$area){
	
	$arreglo_mayor	=	Burbuja($arreglo_titulos_x);
	$numero	=	 strlen($arreglo_mayor[0]);
	$numero	=	$numero-2;
	if($medida == '%')
		$tope			=	ceil($arreglo_mayor[0]);
	else
		$tope			=	round($arreglo_mayor[0],-$numero);
	
	$tamaño_alto	=	466;
	$tamaño_ancho	=	300;
	if($area	==	1){
	$colores		=	array('amar','azul','gris','verde','01_a','02_a','03_a','04_a','05_a','06_a','07_a','08_a','09_a','10_a','11_a','12_a','13_a','14_a','15_a','16_a','17_a');
	$final_color	=	array('amar1','azul1','gris1','verde1','01_b','02_b','03_b','04_b','05_b','06_b','07_b','08_b','09_b','10_b','11_b','12_b','13_b','14_b','15_b','16_b','17_b');
	}
	else if($area	==	2){
	$colores		=	array('12_a','19_a','20_a','21_a','01_a','02_a','03_a','04_a','05_a','06_a','07_a','08_a','09_a','10_a','11_a','amar','13_a','14_a','15_a','16_a','17_a');
	$final_color	=	array('12_b','19_b','20_b','21_b','01_b','02_b','03_b','04_b','05_b','06_b','07_b','08_b','09_b','10_b','11_b','amar1','13_b','14_b','15_b','16_b','17_b');
	}
	else if($area	==	3){
	$colores		=	array('04_a','05_a','06_a','07_a','08_a','09_a','amar','azul','gris','verde','01_a','02_a','03_a','10_a','11_a','12_a','13_a','14_a','15_a','16_a','17_a');
	$final_color	=	array('04_b','05_b','06_b','07_b','08_b','09_b','amar1','azul1','gris1','verde1','01_b','02_b','03_b','10_b','11_b','12_b','13_b','14_b','15_b','16_b','17_b');
	}	
	$tamaño_arreglos	=	sizeof($arreglo_datos_y);
	$tamaño_arreglos2	=	sizeof($arreglo_datos_z);
	$tamaño_datos		=	sizeof($arreglo_titulos_x);
	$tamaño_tabla_x		=	($tamaño_arreglos*2)-1;
	
	$tabla_principal	=	"	<table class='tabla_graficas' height='".$tamaño_alto."' align='center'>";
	$tabla_principal	.=	"		<tr>";
	$tope_dos		=	ceil($tope/15);
	$tope_dos_m		=	$tope*.0025;
	///Ciclo ke crea las barras///////////

		for($a=1,$b=0;$a<=$tamaño_tabla_x+1;$a++,$b++){
			if($re == 1){	
				if($a==1)
				{
					$tabla_principal	.=	"<td align='right' valign='bottom' height='402' >";
					$tabla_principal	.=	"	<table width='100%' border='0'  height='100%' width='15' >";
						for($z=0,$recta=$tope;$z<$tope/$tope_dos;$z++,$recta=$recta-$tope_dos){
							$tabla_color	=	cebra2($z);
							$tabla_principal	.=	"	<tr ".$tabla_color."  >";
								$tabla_principal	.=	"	<td align='right'  class='style5' style='font-size:9px;'  valign='top'>";
										$tabla_principal	.=	number_format(ceil($recta)).$medida."-";
								$tabla_principal	.=	"	</td>";
							$tabla_principal	.=	"	</tr>";
						}
						
					$b=$b-1;		
					$tabla_principal	.=	"	</table>";
					$tabla_principal	.=	"</td>";			
				}
				else if(bcmod($a,2) == 0)
				{

					$tabla_principal	.=	"<td ".cebra2($a)." align='center' valign='bottom'>";
					$tabla_principal	.=	"	<table ".cebra2($a)." align='center' >";
					$tabla_principal	.=	"		<img src='reportes/imgs/$final_color[$b].png' width='12' border='0'><br>";
						for($t=12;$t<$arreglo_titulos_x[$b]/$tope_dos_m	;$t++){
							$tabla_principal	.=	"<img src='reportes/imgs/$colores[$b].jpg' height='1.5' width='12'  border='0'><br>";
						}
					$tabla_principal	.=	"	</table>";
					$tabla_principal	.=	"</td>";
				}
				else
				{
					$tabla_principal	.=	"<td cebra($a+1) width='2%'></td>";
					$b=$b-1;
				}
			}
			else
			{
				if(bcmod($a,2) == 0)
				{
					$tabla_principal	.=	"<td ".cebra2($a)." align='center' valign='bottom'>";
					$tabla_principal	.=	"	<table ".cebra2($a)." align='center' >";
					$tabla_principal	.=	"		<img src='reportes/imgs/$final_color[$b].png' width='12' border='0'><br>";
						for($t=15;$t<$arreglo_titulos_x[$b]/$tope_dos_m	;$t++){
							$tabla_principal	.=	"<img src='reportes/imgs/$colores[$b].jpg' height='1.5' width='12'  border='0'><br>";
						}
					$tabla_principal	.=	"	</table>";
					$tabla_principal	.=	"</td>";
				}
				else
				{
					$tabla_principal	.=	"<td cebra($a+1) width='2px'></td>";
					$b=$b-1;
				}			
			
			
			}	
				
				
	}	
//////Tabla de Titulos///////////	
	$tabla_principal	.=	"</tr>";	
	$tabla_principal	.=	"<tr>";	

		for($a=1,$b=0;$a<=$tamaño_tabla_x+1;$a++,$b++){
			if(bcmod($a,2) == 0)			
				$tabla_principal	.=	"<td cebra($a+1) align='center'>".$arreglo_datos_y[$b]."</td>";
			else
			{
				$tabla_principal	.=	"<td cebra($a+1)>&nbsp;</td>";
				$b=$b-1;
			}
		}
		
	$colsp	=	$tamaño_tabla_x +1;	
	$tabla_principal	.=	"</td>";
		$tabla_principal	.=	"<tr>
										<td colspan='". $colsp ."' align='center' width='100%'>
											<table class='tabla_graficas' width='100%'>";
/////TABLA DE IDENTIFICACION /////////////////////////////////
		for($a=1,$b=0;$a<=$tamaño_arreglos2;$a++,$b++){
			$tabla_principal	.=	"<tr ".cebra2($b).">";	
				$tabla_principal	.=	"<td align='center'><img src='reportes/imgs/$final_color[$b].png' width='12' height='5'></td>";											
				$tabla_principal	.=	"<td class='style5' colspan='". ($tamaño_tabla_x-1) ."' cebra($a+1) align='left'><b>".$arreglo_datos_z[$b]."</b></td>";
				$tabla_principal	.=	"<td cebra($a+1) align='right' class='style4'>". number_format($arreglo_titulos_x[$b],2).$medida."</td>";
				if($tamaño_arreglos2 > 4 && $arreglo_titulos_x[$b+1] != ""){
				$tabla_principal	.=	"<td align='center'><img src='reportes/imgs/".$final_color[$b+1].".png' width='12' height='5'></td>";											
				$tabla_principal	.=	"<td class='style5' colspan='". ($tamaño_tabla_x-1) ."' cebra($a+1) align='left'><b>".$arreglo_datos_z[$b+1]."</b></td>";
				$tabla_principal	.=	"<td cebra($a+1) align='right' class='style4'>". number_format($arreglo_titulos_x[$b+1]).$medida."</td>";
				$tabla_principal	.=	"<td align='center'><img src='reportes/imgs/".$final_color[$b+2].".png' width='12' height='5'></td>";											
				$tabla_principal	.=	"<td class='style5' colspan='". ($tamaño_tabla_x-1) ."' cebra($a+1) align='left'><b>".$arreglo_datos_z[$b+2]."</b></td>";
				$tabla_principal	.=	"<td cebra($a+1) align='right' class='style4'>". number_format($arreglo_titulos_x[$b+2]).$medida."</td>";

				$b = $b+2;
				$a= $a+2;
				}
				else 
				{
				
				}

			$tabla_principal	.=	"</tr>";
		}
		
	$tabla_principal	.=	"				  		</table>
												</td>
											</tr>";
	$tabla_principal	.=	"			</table>";	
	return $tabla_principal;
}

////////////////////////////////////////////////////////////////////////////////////////

/////////////PARA EXTRAER EL MES EN NUMERO////////////////////////////////////////////////////
function num_mes($fecha){

$meses_m = explode("-", $fecha);

   	$m =  $meses_m[1];
	$posicion = strpos($m,'0');
	
	if($posicion === false || $posicion == 1)
	return	$m =  $meses_m[1];
	else{
		$dias_d	=	explode(0,$m);
	return	$m =  $dias_d[1];
    }

}
//////////////////////////////////////////////////////////////////////////////

////////////PARA AÑADIR UN CERO AL MES/////////////////////////////////////
function num_mes_cero($fecha){

$meses_m = explode("-", $fecha);

   	$m =  $meses_m[1];
	$posicion = strpos($m,'0');
	
	if($posicion === false || $posicion == 1)
	return	$m =  '0'.$meses_m[1];
	else{
		$dias_d	=	explode(0,$m);
	return	$m =  $dias_d[1];
    }

}

//////////////////////////////////////////////

///////////////REDIRECCION////////////////////////////////
function redirecciona($url)
{
 echo '<script language="javascript">location.href="'.$url.'";</script>';
}

///////////////FUNCIONES PARA FECHAS /////////////////////////////////////////////
function fecha($fecha){
	echo preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$fecha); 
}
function fecha_tabla($fecha){
	return preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$fecha); 
}
function fecha_tablaInv($fecha){
	return preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$fecha); 
}		
function fechaInv($fecha){
	echo preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" ,$fecha); 
}			
///////////////////////////////////////////////////////////////////////			

if(!get_magic_quotes_gpc())
{
	foreach($_REQUEST as $clave => $valor)
	{
		if(!is_numeric($valor))
			@$_REQUEST[$clave] = mysql_real_escape_string($valor);
	}	
	
}

function describeTabla($tabla,$excluye = "")
{
	$salida		=	array();
	$qDescribe	=	"DESCRIBE $tabla";
	$rDescribe	=	mysql_query($qDescribe) OR die("<p>$rDescribe</p><p>".mysql_error()."</p>");

	while(	$dDescribe	=	mysql_fetch_row($rDescribe)	)
	{
		if( (is_array($excluye) && !in_array($dDescribe[0],$excluye)) || (!is_array($excluye) && $excluye != $dDescribe[0]) )
			$salida[]	=	$dDescribe[0];
	}
	return $salida;
}

function obtenerDato($indice,$tabla,$campoIndice)
{
 $qDato="SELECT * FROM $tabla WHERE $campoIndice='".$indice."'";
 $rDato=mysql_query($qDato);
 $dDato=mysql_fetch_row($rDato);
 return $salida=array($dDato[1],$dDato[2],$dDato[3]);
}

function rellenaSelect($tabla,$indice,$campo)
{
    $salida="";
	$qSelect="SELECT * FROM $tabla WHERE 1";
	$rSelect=mysql_query($qSelect);
	while($dSelect=mysql_fetch_assoc($rSelect))
	{
	 $selected="";
	 if($_REQUEST[$indice]==$dSelect[$indice])
	  $selected="selected";
	  
	  $salida.='<option value="'.$dSelect[$indice].'" '.$selected.'>'.$dSelect[$campo].'</option>';
	}
	return $salida;	
}

function rellenaSelect2($tabla,$indice,$campo,$campo2)
{
    $salida="";
	$qSelect="SELECT * FROM $tabla WHERE 1 ORDER BY area ASC";
	$rSelect=mysql_query($qSelect);
	while($dSelect=mysql_fetch_assoc($rSelect))
	{
	
	if($dSelect[$campo2] == 1)  $area = "Bolseo";
	if($dSelect[$campo2] == 2)  $area = "Impresión";
	if($dSelect[$campo2] == 3)  $area = "Lineas de Impresión";
	if($dSelect[$campo2] == 4)  $area = "Extruder";

	 $selected="";
	 if($_REQUEST[$indice]==$dSelect[$indice])
	  $selected="selected";
	  
	  $salida.='<option value="'.$dSelect[$indice].'" '.$selected.'>'.$dSelect[$campo].' - '.$area.'</option>';
	}
	return $salida;	
}


// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"id_marca"=>"marca_auto",
"id_submarca"=>"submarca_auto"
);

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects;
	if(isset($listadoSelects[$selectDestino])) return true;
	else return false;
}

function validaOpcion($opcionSeleccionada)
{
	// Se valida que la opcion seleccionada por el usuario en el select tenga un valor numerico
	if(is_numeric($opcionSeleccionada)) return true;
	else return false;
}

$selectDestino=$_GET["select"]; $opcionSeleccionada=$_GET["opcion"];

if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	$tabla=$listadoSelects[$selectDestino];
	$consulta=mysql_query("SELECT * FROM $tabla WHERE id_marca='$opcionSeleccionada'") or die(mysql_error());
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>Elige</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";
}
	
function generaContenido()
{
	$consulta=mysql_query("SELECT * FROM marca_auto");

	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='id_marca' id='id_marca' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>Elige</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}
	echo "</select>";
}

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

			$anio 	= 	array(	"2007",
								"2008",
								"2009",
								"2010",
								"2011",
								"2012",
								"2013",
								"2014",
								"2015"
						);
						
			$areasEmpleado 	= 	array(	"",
								"Extruder",
								"Bolseo",
								"Impresion",
								"Mantenimiento",
								"Empaque",
								"Mantenimiento B",
								"Empaque B",
								"Almacen",
								"Almacen B"
								
						);			
						
			$areasContra 	= 	array("",
								"Extruder",
								"Bolseo",
								"Impresion",
								"Mannto.",
								"Empaque",
								"Mannto. B",
								"Emp. B",
								"Almacen",
								"Almacen B"
						);		
								
		 	$motivos = array(	"Seleccione algun movimiento",
								"Permiso para Faltar", 
								"Permiso para entrar a laborar",
								"Permiso para ausentarse de labores", 
								"Cambio de turno",
								"Cambio de rol",
								"Cambio de Horario",
								"Suspension", 
								"Castigo",
								"Vacaciones",
								"Tiempo extra",
								"Gratificacion",
								"Prima vacacional", 
								"Baja", 
								"Olvido de gafet",
								"Extravio de gafet",
								"No checo entrada",
								"No checo Salida", 
								"Arreglo entre operadores",
								"Repone un dia", 		
								"Otra",
								"No salio a lonche",
								"Cambio de Descanso",
								"Repone Tiempo"
							);
		$autorizado	=	array("NO","SI");
			
					
	 	$turnos	=	array(	"Seleccione un turno",
	 						"Matutino",
							"Vespertino",
							"Nocturno"
							);	
	
	 	$roles	=	array(	"Seleccione un rol",
	 						"1",
							"2",
							"3",
							"4",
							"5",
							"6"
							);	


	function UltimoDia($anho,$mes){ 
		   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
			   $dias_febrero = 29; 
		   } else {
			   $dias_febrero = 28; 
		   } 
		   switch($mes) { 
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
	
	

			
			function tabla_muestra($id,$buscar,$id_tabla){
				$qSelect	=	"SELECT * FROM $buscar WHERE $id_tabla = '$id'";
				$rSelect	= 	mysql_query($qSelect);
				$dSelect	=	mysql_fetch_assoc($rSelect);
				
				if($id_tabla	==	'id_admin')
				return array($dSelect[$id_tabla],$dSelect['nombre'],$dSelect['puesto']);
				else
				return array($dSelect[$id_tabla],$dSelect['nombre'],'Supevisor');
							
			}
	
			
			
			function redondeado($numero, $decimales) 
			{
				$factor = pow(10, $decimales);
				 return (round($numero*$factor)/$factor); 
			}
			
			function listar($tabla, $indice, $valores)
			{
				$q	=	"SELECT * FROM $tabla";
				$b 	=	1;
				
					if( $b < sizeof($condiciones)){
						$q	.=	" WHERE ";
							for($a = 0; $a < sizeof($condiciones);$a++){
									$q .= "$condiciones[$a] = $indices[$a]"	;
									$b++;
									if($b < sizeof($condiciones))
										$q .= " AND ";
								}
					}
			$r	=	mysql_query($q);
			$tabla	=	"";
			 for ($z = 0; $d = mysql_fetch_assoc($r);$z++){
			 	  $y = 0;
			$tabla	.= 	 "<tr>";
				 for($c = 0 ; $c < sizeof($valores);$c++){
			   	  	   $res =	$valores[$y+$c];
					if($res == "fecha" || $res == "desde" || $res == "hasta" )  $re = fecha_tabla($d[$res]);  
					else if($res == "movimiento") $re = arreglos('motivos',$d[$res]);
					else
					$re = $d[$res];
			$tabla	.=	"	<td>".$re."</td>";
					}	
			$tabla	.=	"<td>".$d[$indice]."</td>";
			$tabla	.= "</tr>";
				}	
		  echo $tabla;
			}
			
			
function selectMes($meses,$anos,$motivos){
			$selectmes	=	"<select name='mes'>";
						for($a = 1; $a < sizeof($meses); $a++){
							if(date('m') == $meses[$a]) $s = "selected"; else $s = "";
							$selectmes	.=	"<option value='".$a."' ".$s.">".$meses[$a]."</option>";
						}
			$selectmes	.=	"</select>";
			
			$selectano	=	"<select name='ano'>";
						for($b = 0; $b < sizeof($anos); $b++){
							if(date('Y') == $anos[$b]) $t = "selected"; else $t = "";
							$selectano	.=	"<option value='".$anos[$b]."' ".$t.">".$anos[$b]."</option>";
						}
			$selectano	.=	"</select>";
			
			$selectmot	=	"<select name='motivos'>";
						for($a = 0; $a < sizeof($motivos); $a++){
							$selectmot	.=	"<option value='".$a."'>".$motivos[$a]."</option>";
						}
			$selectmot	.=	"</select>";
						
			
			return array ($selectmes , $selectano, $selectmot ) ;
			
			}
			
			
?>