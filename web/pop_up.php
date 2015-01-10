<?
@session_start();
if(isset($_REQUEST['usuario']) && isset($_REQUEST['contra']))
{
	$_SESSION['user']=$_REQUEST['usuario'];
	$_SESSION['pass']=$_REQUEST['contra'];
}

//include 'libs/valida_sesion.php';
include 'libs/funciones.php';

if(!empty($_POST['mandar']))
{

	$grupo = $_REQUEST['para'];
	$de	=	$_REQUEST['de'];
	$revisa 	= explode("_", $de);
	
	$id		=	$revisa[0];
	$tipo	= 	$revisa[1];
	
	$para	= explode("_", $grupo);
		
	$para2 	= $para[0];
	$tipo2 	= $para[1];
	
	if($_REQUEST['para'] == 'todos'){
	
		$qUsuarios	=	"SELECT * FROM administrador";
		$rUsuarios	=	mysql_query($qUsuarios);
			
			while($dUsuarios	=	mysql_fetch_assoc($rUsuarios)){	
					 if(isset($_SESSION['id_admin']) && $dUsuarios['id_admin'] == $_SESSION['id_admin']){ }
					 else {
				 $mensaje		=	"INSERT INTO mensajeria (de,para,titulo,mensaje,checado,fecha,grupo,tipo,tipo2)".
								" VALUES('$id','".$dUsuarios['id_admin']."','{$_REQUEST[titulo]}','{$_REQUEST[mensaje]}','{$_REQUEST[checado]}','{$_REQUEST[fecha]}', '$grupo', '$tipo','u');";
	 			 $res_query		=	mysql_query($mensaje) OR die("<p>$mensaje</p><p>".mysql_error()."</p>");
			}
			}	
	}		
			
	if($_REQUEST['para'] == 'a' || $_REQUEST['para'] == 'b' || $_REQUEST['para'] == 'c' || $_REQUEST['para'] == 'd' || $_REQUEST['para'] == 'todos' ){
			if($_REQUEST['para'] == 'a' || $_REQUEST['para'] == 'todos')
			$qNumeroSuper	=	"SELECT * FROM supervisor";
			if($_REQUEST['para'] == 'b')
			$qNumeroSuper	=	"SELECT * FROM supervisor WHERE area = 1 ";
			if($_REQUEST['para'] == 'c')
			$qNumeroSuper	=	"SELECT * FROM supervisor WHERE area2 = 1 ";
			if($_REQUEST['para'] == 'd')
			$qNumeroSuper	=	"SELECT * FROM supervisor WHERE area3 = 1 ";
		
			
			$rNumeroSuper	=	mysql_query($qNumeroSuper);
			while($dNumeroSuper	=	mysql_fetch_assoc($rNumeroSuper)){	
					 if(isset($_SESSION['id_supervisor']) && $dNumeroSuper['id_supervisor'] == $_SESSION['id_supervisor'])
						{
						 
					 	} else {
							$mensaje		=	"INSERT INTO mensajeria (de,para,titulo,mensaje,checado,fecha,grupo,tipo,tipo2)".
								" VALUES('{$_REQUEST[de]}','".$dNumeroSuper['id_supervisor']."','{$_REQUEST[titulo]}','{$_REQUEST[mensaje]}','{$_REQUEST[checado]}','{$_REQUEST[fecha]}', '$grupo','$tipo','s')";
	 			 			$res_query		=	mysql_query($mensaje) OR die("<p>$mensaje</p><p>".mysql_error()."</p>");
						}
			
	} 
	} else {
	
				
				$mensaje		=	"INSERT INTO mensajeria (de,para,titulo,mensaje,checado,fecha, grupo,tipo,tipo2)".
								" VALUES('$id','$para2','{$_REQUEST[titulo]}','{$_REQUEST[mensaje]}','{$_REQUEST[checado]}','{$_REQUEST[fecha]}', '$grupo','$tipo','$tipo2');";		
			   $res_query		=	mysql_query($mensaje) OR die("<p>$mensaje</p><p>".mysql_error()."</p>");
			   	
			}
		
	echo '<script laguaje="javascript">location.href=\'pop_up.php?accion=enviando\';</script>';
}


?>
	<style type="text/css" media="screen">@import 'style.css';</style>
    <style type="text/css" media="screen">
	body {
	background-color:#FFFFFF;
	background-image:none;}
</style>
<link href="dolfra.css" rel="stylesheet" type="text/css" />
<? if($_REQUEST['accion'] == "nuevo") {?>
<body>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
  <table align="center" width="100%" bgcolor="#FFFFFF">
          <tr>
            <td colspan="3" class="txt_titulos" align="left" ><br>
            MENSAJERIA INSTANTANEA</td>
    </tr>
          <tr>
            <td width="55">&nbsp;</td>
            <td colspan="2" align="left" valign="top" class="txt_subs">MENSAJE NUEVO</td>
          </tr>
          <tr>
          	<td>&nbsp;</td>
            <td width="65"  align="right" class="style7"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Para: </td>
    <td width="88%" align="left" style="background-color:#DDDDDD"><select name="para">
    				<option value="todos" >TODOS LOS USUARIOS</option>    
    				<option value="a" <? if($modificar && $dModificar['grupo'] == 'a') echo "selected"; ?>>EXTRUDER, IMPRESION Y BOLSEO</option>
                    <option value="x">---------------</option>
                    <option value="x"> </option>
                    <option value="x">Areas: </option>
                    <option value="b" <? if($modificar && $dModificar['grupo'] == 'b') echo "selected"; ?>>EXTRUDER</option>
                    <option value="c" <? if($modificar && $dModificar['grupo'] == 'c') echo "selected"; ?>>IMPRESION</option>
                    <option value="d" <? if($modificar && $dModificar['grupo'] == 'd') echo "selected"; ?>>BOLSEO</option>
                    <option value="x">---------------</option>
                    <option value="x"></option>
                    <option value="x">Supervisores: </option>
<? $rSuper	= mysql_query("SELECT * FROM supervisor ORDER BY nombre ASC");
						while($dSuper	=	mysql_fetch_assoc($rSuper)){ ?>
                        <? if( isset($_SESSION['d_supervisor']) && $dSuper['id_supervisor'] == $_SESSION['id_supervisor']) { ?><? } else {  ?>
    					<option value="<?=$dSuper['id_supervisor']?>_s" <? if($modificar && $dModificar['grupo'] == $dSuper['id_supervisor']) echo "selected"; ?> ><?=$dSuper['nombre']?></option>
                    <? } }?>
                    <option value="x">---------------</option>
                    <option value="x"></option>
					<option value="x">Usuarios: </option>
<? $rAdmin	= mysql_query("SELECT * FROM administrador ORDER BY nombre ASC");
						while($dAdmin	=	mysql_fetch_assoc($rAdmin)){ ?>
                        <? if( isset($_SESSION['id_admin']) && $dAdmin['id_admin'] == $_SESSION['id_admin']) { ?><? } else {  ?>
    					<option value="<?=$dAdmin['id_admin']?>_u" <? if($modificar && $dModificar['grupo'] == $dAdmin['id_admin']) echo "selected"; ?> ><?=$dAdmin['nombre']?></option>
                    <? }  }?>                    
                    
    </select></td>

    <td width="42">&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
  <td class="style7" align="right"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Titulo: </td>
  <td align="left" bgcolor="#EEEEEE"><span class="titulos">
    <input name="titulo" type="text" class="style1" id="titulo" value="" size="40"  />
    <input type="hidden" name="fecha" id="fecha" value="<?=date('Y-m-d');?>">
    <input type="hidden" name="checado" id="checado" value="0">
    <input type="hidden" name="de" id="de" value="<? if (isset($_SESSION['id_admin'])){ echo $_SESSION['id_admin']."_u"; } else { echo $_SESSION['id_supervisor']."_s"; }?>"></span></td>
</tr>
<tr>
	<td></td>
  <td class="style7" align="right" valign="top"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Mensaje: </td>
  <td align="left" class="contenidos" valign="top" bgcolor="#DDDDDD"><span class="titulos"><textarea name="mensaje" id="mensaje" cols="40" rows="3"><? if($modificar) echo htmlspecialchars($dModificar['mensaje']);?></textarea></span></td>
</tr>
<tr>
	<td colspan="3" align="right"><input name="mandar" type="submit" class="style7" value="Enviar"></td>
</tr>
</table>
</form>
</body>
<? } ?>
<? if($_REQUEST['accion'] == 'enviando'){?>
<SCRIPT LANGUAGE="JavaScript">
redirTime = "4000";
function redirTimer() { self.setTimeout("window.close()",redirTime); }
//  FIN -->
</script>
<body onLoad="redirTimer()">
<table align="center" width="59%">
<tr>
	<td width="12%" align="CENTER" class="style7"><br>
	  <br>
    ENVIANDO MENSAJE<br>
    <br>
    <img src="images/ajax.gif" border="0">
    <br><br>
</td>
</tr>
</table>
</body>
<? } ?>