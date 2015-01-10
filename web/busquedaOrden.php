<?php
include 'libs/conectar.php';
$busqueda	=	$_POST['busqueda'];
$resul		=	$_POST['campo'];
$resul2		=	$_POST['campo2'];
$id			=	$_REQUEST['id'];
// DEBO PREPARAR LOS TEXTOS QUE VOY A BUSCAR si la cadena existe
if ($busqueda<>'' ){
		//SI SOLO HAY UNA PALABRA DE BUSQUEDA SE ESTABLECE UNA INSTRUCION CON LIKE
	//	$cadbusca="SELECT * FROM clientes WHERE (nombre LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%') LIMIT 10;";
if($_REQUEST['tabla'] == 'orden')	
$cadbusca	=	"SELECT detalle_resumen_maquina_bs.orden FROM bolseo ".
					" INNER JOIN resumen_maquina_bs ON bolseo.id_bolseo = resumen_maquina_bs.id_bolseo".
					" LEFT JOIN detalle_resumen_maquina_bs ON resumen_maquina_bs.id_resumen_maquina_bs = detalle_resumen_maquina_bs.id_resumen_maquina_bs".
					" WHERE detalle_resumen_maquina_bs.orden  LIKE '%$busqueda%' GROUP BY detalle_resumen_maquina_bs.orden ORDER BY detalle_resumen_maquina_bs.orden ASC LIMIT 15";		
if($_REQUEST['tabla'] == 'empleado')	
$cadbusca	=	"SELECT * FROM operadores WHERE nombre LIKE '%$busqueda%' ORDER BY nombre ASC";	
	
?>
	<table style="width:70%; background-color:#EEEEEE" border="0" id="<?=$resul?>"> 
	<tbody>
<?php
	$result=mysql_query($cadbusca);
	$i=1;
	while ($row = mysql_fetch_array($result)){
	if($_REQUEST['tabla'] == 'orden'){	
		echo "
			<tr onclick=\"document.form.orden.value='".$row['orden']."'; oculta('".$resul."');\" style=\"cursor:default\">
				<td>&nbsp;</td>
				<td class=\"style5\"><b>Orden No: ".$row['orden']."</b></td>
			</tr>";
	    }
	if($_REQUEST['tabla'] == 'empleado'){
		echo "
			<tr onclick=\"document.form.".$resul2.".value='".$row['nombre']."'; document.form.".$id.".value='".$row['id_operador']."'; oculta('".$resul."');\" style=\"cursor:default\">
				<td>&nbsp;</td>
				<td class=\"style5\"><b>".$row['nombre']."</b></td>
			</tr>";		
		
		}
		$i++;
	}
}
?>
	</tbody>
	</table>