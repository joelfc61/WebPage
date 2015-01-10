<?php #login
	ob_start();
	header( "Cache-control: private" );
	$header = "Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) ;
	if(!isset($_POST['usuario']) || !isset($_POST['contra']) )
	{
		sleep(4);
		$header .= "/index.php";
		header($header);
		ob_end_flush();
		exit();
	}
	require_once("libs/conectar.php");
	$fecha = date("Y-m-d");
	
	$qUsuario	=	"SELECT * FROM administrador WHERE (user='{$_POST[usuario]}') AND (pass='{$_POST['contra']}') LIMIT 1";
	$rUsuario	=	mysql_query($qUsuario) OR die("<p>$qUsuario</p><p>".mysql_error()."</p>");

	if(mysql_num_rows($rUsuario) == 1)
	{
		$dUsuario	=	mysql_fetch_row($rUsuario);
		session_start();
		$_SESSION['id_admin']	=	$dUsuario[0];
		$_SESSION['usuario']	=	$dUsuario[1];
		$_SESSION['contra']		=	$dUsuario[2];
		header($header ."/admin.php");
	}
	else
	{
		sleep(4);
		$header .= "/index.php?error=1";
		header($header);
		ob_end_flush();
		exit();
	}
	ob_end_flush();
	@mysql_free_result($resultado);
	@mysql_close($db_link);
?>