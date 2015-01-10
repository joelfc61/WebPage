<?php 
@session_start();
if(isset($_REQUEST['usuario']) && isset($_REQUEST['contra']))
{
	$_SESSION['user']=$_REQUEST['usuario'];
	$_SESSION['pass']=$_REQUEST['contra'];
}
//include 'libs/valida_sesion2.php';
include 'libs/funciones.php';

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" version=
"-//W3C//DTD XHTML 1.1//EN" xml:lang="en">
<head>
	<script type="text/javascript" src="mootools.js"></script>
	<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Dolfra - Sistema de Produccion</title>
	<style type="text/css" media="screen">@import 'style.css';</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="672" height="274" border="0" align="center" cellpadding="0" cellspacing="0" id="Table_01" >
<tr>
		<td width="4" rowspan="5" align="left" valign="top" background="images/admin_01.jpg" >&nbsp;</td>
            
<td height="98" colspan="6" align="left" valign="top"><img src="images/admin_02.jpg" width="672" height="98" alt=""></td>
		<td width="93" rowspan="5" align="left" valign="top" background="images/admin_03.jpg">&nbsp;</td>
  </tr>
     <tr>
    	<td colspan="6" align="center">
      <table width="100%" align="center" cellpadding="0" cellspacing="0">
                <tr>
                	<td colspan="6" >
                    	<table width="100%" style="background-color:#0A4662">
                            <td width="7">&nbsp;</td>
                            <td width="70"><span class="fecha">Bienvenido:</span></td>
							<td width="280" class="fecha" align="left" ><?=$_SESSION['nombre']?></td>
                            <td width="270" align="right" class="fecha"><?=date('d/m/Y'); ?></td>
                            <td width="11">&nbsp;</td>
                        </table>
                     </td>
                </tr>
                <tr>
                  <td width="85" align="center"><a href="admin_vendedores.php?seccion=9&accion=listar" class="style6">Clientes</a></td>
                  <td width="124" align="center"><a href="admin_vendedores.php?seccion=10&accion=listar" class="style6">Pedidos</a></td>
                  <td width="110" align="center"><a href="admin_vendedores.php?seccion=1&accion=listar" class="style6">Productos</a></td>
                  <td width="6" >&nbsp;</td>
                  <td width="209">&nbsp;</td>
                  <td width="128" align="center" class="style6"><a href="logout.php" class="style6">Cerrar sesion</a></td>
        </tr>
      </table>    </td>
  </tr>
	<tr>
	  <td colspan="6" bgcolor="#FFFFFF" valign="top">
        <?php
	if(isset($_GET['seccion']) && is_numeric($_GET['seccion']))
	{
		switch($_GET['seccion'])
		{
			case 1:
				include("productos.php");	break;	
			case 9:
				include("clientes.php");	break;	
			case 10:
				include("pedidos.php");  break;
				
		}
	}
	?>
        <br>
      <br></td>
  </tr>
	<tr>
	  <td colspan="7" align="center" valign="bottom"><img src="images/admin_06.jpg" width="100%" height="46"></td>
  </tr>
</table>
<?php if(!empty($redirecciona)) { ?>

<script language="javascript" type="text/javascript">
<!--
window.onload = function(){
	window.location.href = "<?=$ruta?>";
}
-->
</script>

<?php } ?>
</body>
</html>