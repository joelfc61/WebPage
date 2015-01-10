<?
if (isset($_GET['campo']) && isset($_GET['valor']) && isset($_GET['id'])) {
	include('libs/conectar.php');
	$qActualiza = "UPDATE $_GET[tabla] SET $_GET[campo]='$_GET[valor]' WHERE $_GET[tipo]='$_GET[id]'";
	$rActualiza = mysql_query($qActualiza);
		if($_GET['campo']=='rol'){
		$qMaquinas	=	"SELECT * FROM oper_maquina WHERE id_operador ='$_GET[id]'";
		$rMaquinas	= mysql_query($qMaquinas);
			while($dMaquinas	=	mysql_fetch_assoc($rMaquinas)){
				$qUpdate	=	"UPDATE oper_maquina SET rol='$_GET[valor]' WHERE id_operador='$_GET[id]'";
				$rUpdate	=	mysql_query($qUpdate);
		}
	}
}
?>
