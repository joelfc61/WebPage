<html>
<head>
<title>Dolfra - Sistema de Producci&oacute;n</title>
<meta charset='utf-8'>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #00357A;
}
body {
	background-color: #0A4662;
}
-->
</style>
<?php
  include "debugger.php";
  pDebug("Debugger Cargado");
?>
</head>
<script language="javascript" type="text/javascript">
<!--
function revisa(x)
{
	if(x.usuario.value=="")
	{
		alert('Por favor introduce tu nombre de usuario');
		x.usuario.focus();
		return false;
	}
	else if(x.contra.value=="")
	{
		alert('Por favor introduce tu contraseña');
		x.contra.focus();
		return false;
	}
	//setTimeout('window.close()',1000);
	return true;
}
//-->
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- ImageReady Slices (loggin_backoffice.psd) --><br>
<br>
<table width="711" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr valign="middle">
    <td width="24" rowspan="2"><img src="images2/dolfra_01.jpg" width="23" height="289"></td>
    <td width="663" height="255" background="images2/dolfra_02.jpg" bgcolor="#FFFFFF"><table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><p><br>
            </p>
          <form name="form" method="post" onSubmit="return revisa(this);" action="admin.php">
		  <table width="367" border="0" cellpadding="4" cellspacing="4">
          <tr>
            <td width="112" height="39">&nbsp;</td>
            <td width="241">&nbsp;</td>
          </tr>
          <tr>
            <td height="29">&nbsp;</td>
            <td><input name="usuario" type="text" class="style1" id="usuario"></td>
          </tr>
          <tr>
            <td height="26">&nbsp;</td>
            <td><input name="contra" type="password" class="style1" id="contra"></td>
          </tr>
          <tr>
          <td>&nbsp;</td>
          <td><input type="submit" value="Enviar" name="enviar"></td>
          </tr>
        </table>
		</form>
		<?
		if(isset($_GET['msg'])){ 
			if($_GET['msg'] == '1'){ 
        		echo "<span style=\"color:#FF0000; font-family:Arial, Helvetica, sans-serif; font-size:14px; text-align:center\">Datos de acceso incorrectos</span>";
			}
		}
		?>
          <br></td>
      </tr>
      
    </table></td>
    <td width="24" rowspan="2"><img src="images2/dolfra_03.jpg" width="23" height="289"></td>
  </tr>
  <tr>
    <td valign="bottom" bgcolor="#FFFFFF"><img src="images2/dolfra_04.jpg" width="663" height="33"></td>
  </tr>
</table>

<script language="javascript" type="text/javascript">

		window.onload = function(){
		
			document.getElementById("usuario").focus();
			<?php
			if(isset($_GET['error'])){
				if($_GET['error'] == "1")
					echo "alert('Lo sentimos, tus datos de acceso no coinciden');";
			}
			?>
		}

</script>


<!-- End ImageReady Slices -->
  
</body>
</html>