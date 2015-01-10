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
if($_REQUEST['tabla'] == 'ordenExt')	
$cadbusca	=	"SELECT detalle_resumen_maquina_ex.orden_trabajo FROM resumen_maquina_ex 
					 LEFT JOIN detalle_resumen_maquina_ex ON resumen_maquina_ex.id_resumen_maquina_ex = detalle_resumen_maquina_ex.id_resumen_maquina_ex
					 WHERE detalle_resumen_maquina_ex.orden_trabajo LIKE '%$busqueda%' GROUP BY detalle_resumen_maquina_ex.orden_trabajo ORDER BY detalle_resumen_maquina_ex.orden_trabajo ASC LIMIT 15";		
if($_REQUEST['tabla'] == 'empleado')	
$cadbusca	=	"SELECT * FROM operadores WHERE nombre LIKE '%$busqueda%' ORDER BY nombre ASC";	
	
?>
	<table style="width:70%; background-color:#EEEEEE" border="0" id="<?=$resul?>"> 
	<tbody>
<?php
	$result=mysql_query($cadbusca);
	$i=1;
	while ($row = mysql_fetch_array($result)){
	if($_REQUEST['tabla'] == 'ordenExt'){	
		echo "
			<tr onclick=\"document.form.ordenExt.value='".$row['orden_trabajo']."'; oculta('".$resul."');\" style=\"cursor:default\">
				<td>&nbsp;</td>
				<td class=\"style5\"><b>Orden No: ".$row['orden_trabajo']."</b></td>
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