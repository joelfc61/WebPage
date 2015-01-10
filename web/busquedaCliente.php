<?php
include 'libs/conectar.php';
$busqueda=$_POST['busqueda'];
$nombre=$_POST['razon'];
// DEBO PREPARAR LOS TEXTOS QUE VOY A BUSCAR si la cadena existe
if ($busqueda<>''){
		//SI SOLO HAY UNA PALABRA DE BUSQUEDA SE ESTABLECE UNA INSTRUCION CON LIKE
		$cadbusca="SELECT * FROM clientes WHERE (razon LIKE '%$busqueda%') LIMIT 10;";
	
	
	function limitarPalabras($cadena, $longitud, $elipsis = "..."){
		$palabras = explode(' ', $cadena);
		if (count($palabras) > $longitud)
			return implode(' ', array_slice($palabras, 0, $longitud)) . $elipsis;
		else
			return $cadena;
	}
?>
	<table style="width:100%;" border="0" bgcolor="#FFFFFF" id="tabla"> 
	<tbody>
<?php
	$result=mysql_query($cadbusca);
	$i=1;
	while ($row = mysql_fetch_array($result)){
		echo "
			<tr onclick=\"document.pedidos.razon.value='".$row['razon']."'; oculta('tabla'); busqueda('".$row['razon']."');\" style=\"cursor:default\">
				<td class=\"numeros\">".$row['razon']."</td>
			</tr>";
		$i++;
	}
}
?>
	</tbody>
	</table>