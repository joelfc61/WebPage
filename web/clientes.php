<?php

$tabla	=	"clientes";
$indice	=	"id_cliente";

$campos	=	describeTabla($tabla,$indice);

if(!empty($_POST['submit']))
{
    
	if(	isset($_POST[$indice]) ) $id = intval( $_POST[$indice] );
	else
	{
		$res_id		=	mysql_query("SELECT MAX($indice) FROM $tabla");
		$next_id	=	mysql_fetch_assoc($res_id);
		$id			=	$next_id[$indice]+1;
	}
	

	$query		=	"";
	foreach($campos as $clave)
		if(isset($_POST[$clave]))
			$query		.=	(($query=="")?"":",")."$clave='".$_POST[$clave]."'";

	if(!empty($_POST[$indice]))
		$query		=	"UPDATE $tabla SET ".$query." WHERE ($indice=".$_POST[$indice].")";
	else 
	$query		=	"INSERT INTO $tabla SET $query";
	$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";
}

$listar =	true;
if(!empty($_GET['accion']))
{
	$listar		=	($_GET['accion']=="listar")?true:false;
	$nuevo		=	($_GET['accion']=="nuevo")?true:false;
	if(!empty($_GET[$indice]) && is_numeric($_GET[$indice]) )
	{
		$mostrar	=	($_GET['accion']=="mostrar")?true:false;
		$eliminar	=	($_GET['accion']=="eliminar")?true:false;
		$modificar	=	($_GET['accion']=="modificar")?true:false;
	}
	
	if($mostrar || $modificar)
	{
		$query_dato	=	"SELECT * FROM $tabla WHERE ($indice={$_GET[$indice]})";
		$res_dato	=	mysql_query($query_dato) OR die("<p>$query_dato</p><p>".mysql_error()."</p>");
		$dato		=	mysql_fetch_assoc($res_dato);
	}
	
	if($eliminar)
	{
		$q_eliminar	=	"DELETE FROM $tabla WHERE ($indice={$_GET[$indice]}) LIMIT 1";
		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin_vendedores.php?seccion={$_GET[seccion]}&accion=listar";
	
	}
}
?>
	
	<style type="text/css" media="screen">@import 'style.css';</style>

<?php if($nuevo || $modificar) { ?>
<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
<br>
<br>
<table width="522" align="center" cellpadding="3" cellspacing="3">
<tr>
    	<td colspan="4" align="center">
   	    <span class="titulos">CLIENTES</span><br />
   	    <br /></td>
    </tr>
  <tr>
    <td width="67" align="left">Razon Social: </td>
    <td alingn"left" colspan="3"><input name="razon" type="text"  class="style1" id="razon" value="<?php if($modificar) echo $dato['razon']; ?>" size="53" maxlength="55" />    </td>
  </tr>
  <tr>
    <td width="67" align="left">RFC:</td>
    <td align="left" colspan="3">
      <input name="rfc" type="text" class="style1" id="rfc" value="<?php if($modificar) echo $dato['rfc']; ?>" size="53" maxlength="55" />
    </td>
  </tr>
  <tr>
    <td width="67" align="left">Domicilio:</td>
    <td colspan="3"><input type="type" name="domicilio" size="53" id="domicilio" value="<?php if($modificar) echo $dato['domicilio']; ?>" /></td>
  </tr>
  <tr>
    <td width="67" align="left">Telefono:</td>
    <td width="182"><input type="text" id="telefono" name="telefono" size="20" value="<?php if($modificar) echo $dato['telefono']; ?>" /></td>
  </tr>
  <tr>
    <td width="67" align="left">Ciudad:</td>
    <td width="182"><input type="text" name="ciudad" id="ciudad" size="20" value="<?php if($modificar) echo $dato['ciudad']; ?>" /></td>
    <td width="65" align="left">Estado:</td>
    <td width="177"><input type="text" name="estado" id="estado" size="20" value="<?php if($modificar) echo $dato['estado']; ?>" /></td>
  </tr>
  <tr>
    <td width="67" align="left">Dias de Credito:</td>
    <td width="182" align="left" class="contenidos"><span class="titulos">
      <input name="dcredito" type="text" class="style1" id="dcredito" value="<?php if($modificar) echo $dato['dcredito']; ?>" size="15" maxlength="15" />
    </span></td>
    <td width="65" align="left">Limite de Credito:</td>
    <td width="177" align="left" class="contenidos">
      <input name="lcredito" type="text" class="style1" id="lcredito" value="<?php if($modificar) echo $dato['lcredito']; ?>" size="15" maxlength="15" />
    </td>
  </tr>
  <tr>
    <td width="67" align="left">Dias de Revision:</td>
    <td width="182"><input type="text" id="drevision" name="drevision" size="15" value="<?php if($modificar) echo $dato['drevision']; ?>" /></td>
    <td width="65" align="left">Dias de pagos:</td>
    <td width="177"><input type="text" name="dpagos" id="dpagos" size="15" value="<?php if($modificar) echo $dato['dpagos']; ?>" /></td>
  </tr>
  <tr>
    <td width="67" align="left">Pagos:</td>
    <td width="182"><input type="text" name="pagos" id="pagos" size="20" value="<?php if($modificar) echo $dato['pagos']; ?>" /></td>
    <td width="65" align="left">Atiende Horario:</td>
    <td width="177"><input type="text" name="ahorario" id="ahorario" size="20" value="<?php if($modificar) echo $dato['ahorario']; ?>"/></td>
  </tr>
  <tr>
    <td width="67" align="left">Se envia:</td>
    <td width="182"><input type="text" name="seenvia" id="seenvia" size="20" value="<?php if($modificar) echo $dato['seenvia']; ?>" /></td>
  </tr>
  <tr>
    <td width="67" align="left">Compras:</td>
    <td width="182"><input type="text" name="compras" id="compras" size="20" value="<?php if($modificar) echo $dato['compras']; ?>" /></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><span class="total"><br />
      Consignar a:</span></td>
  </tr>
  <tr>
  	<td>Nombre: </td>
    <td colspan="3"><input type="text" name="consignado" id="consignado" value="<?php if($modificar) echo $dato['consignado']; ?>" /></td>
  </tr>
  <tr>
  	<td>Domicilio</td>
    <td colspan="3"><textarea name="consignado_dom" rows="4" cols="45"><?php if($modificar) echo $dato['consignado_dom']; ?></textarea></td>
  <tr>
    <td class="contenidos" align="right" colspan="4"><?php if($modificar) { ?>
        <input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>" />
        <?php } ?>
        <?php if($nuevo) { ?>
        <input type="hidden" name="status" value="0" />
        <?php } ?>
        <input name="submit" type="submit" class="style4" value="Guardar" />    </td>
  </tr>
</table>
<br>
<br>
</form>
<?php } ?>

<?php if($mostrar) { ?>
<br />
<br />
<table width="536" align="center" cellpadding="3" cellspacing="3">
<tr>
    	<td colspan="4" align="center">
   	    <span class="titulos">CLIENTES</span><br />
   	    <br /></td>
    </tr>
  <tr>
    <td width="112" align="left">Razon Social: </td>
    <td alingn"left" colspan="3"><span class="mostrar"><?=$dato['razon']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">RFC:</td>
    <td align="left" colspan="3"><span class="mostrar"><?=$dato['rfc']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">Domicilio:</td>
    <td colspan="3"><span class="mostrar"><?=$dato['domicilio']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">Telefono:</td>
    <td width="152"><span class="mostrar"><?=$dato['telefono']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">Ciudad:</td>
    <td width="152"><span class="mostrar"><?=$dato['ciudad']?></span></td>
    <td width="141" align="left">Estado:</td>
    <td width="90"><span class="mostrar"><?=$dato['estado']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">Dias de Credito:</td>
    <td width="152" align="left"><span class="mostrar"><?=$dato['dcredito']?></span></td>
    <td width="141" align="left">Limite de Credito:</td>
    <td width="90" align="left"><span class="mostrar"><?=$dato['lcredito']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">Dias de Revision:</td>
    <td width="152"><span class="mostrar"><?=$dato['drevision']?></span></td>
    <td width="141" align="left">Dias de pagos:</td>
    <td width="90"><span class="mostrar"><?=$dato['dpagos']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">Pagos:</td>
    <td width="152"><span class="mostrar"><?=$dato['pagos']?></span></td>
    <td width="141" align="left">Atiende Horario:</td>
    <td width="90"><span class="mostrar"><?=$dato['ahorario']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">Se envia:</td>
    <td width="152"><span class="mostrar"><?=$dato['seenvia']?></span></td>
  </tr>
  <tr>
    <td width="112" align="left">Compras:</td>
    <td width="152"><span class="mostrar"><?=$dato['compras']?></span></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><span class="total"><br />
    Consignar a:</span></td>
  </tr>
  <tr>
  	<td>Nombre: </td>
    <td colspan="3"><span class="mostrar"><?=$dato['consignado']?></span></td>
  </tr>
  <tr>
  	<td>Domicilio</td>
    <td colspan="3"><span class="mostrar"><?=$dato['consignado_dom']?></span></td>
  <tr>
</table>
<br />
<br />
<?php } // Mostrar ?>
<?php if($listar) { ?>
<?php
$qListar	=	"SELECT * FROM $tabla  WHERE 1 ";
$rListar	=	mysql_query($qListar) OR die("<p>$qListar</p><p>".mysql_error()."</p>");

if(mysql_num_rows($rListar) > 0) { ?>
<br>
<br>
<table align="center" width="531" class="style2">
<tr>
    	<td align="center" colspan="4">CLIENTES</td>
  </tr>
<tr>
	<td width="455" align="left"><b>Nombre</b></td>
  </tr>
<?php for($flag=true;$d	=	mysql_fetch_row($rListar);$flag=!$flag) { ?>
		<tr <?=($flag)?"class=\"cabecera\"":""?>>
<td align="left" class="mostrar" ><?=$d[1]?></td>

<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&<?=$indice?>=<?=$d[0]?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&<?=$indice?>=<?=$d[0]?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&<?=$indice?>=<?=$d[0]?>" onclick="javascript: return confirm('Realmente deseas eliminar a este supervisor?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
</tr>

	<?php } ?>
   <tr>
   		<td colspan="5">&nbsp;</td>
   </tr> 
   	<tr>
		  <td align="center" colspan="7">
		  <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo">
		  <br />
		  | Nuevo Cliente | <br />
	  </a></td>
  </tr>
</table>
<br>
<br>
<blockquote>
  <p><br />
    <?php } else { ?>
  </p>
</blockquote>
<table align="center" width="54%">
<tr>
		<td align="center"><br />
		  <br />
		  <br />
	    Aun no hay Clientes registrados en la base de datos<br>
	    <br />
	    <br /></td>
  </tr>
  <tr>
	  <td align="center">
		  <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo"><strong> <br />
		  | Nuevo Cliente | <br />
		  </strong></a></td>
  </tr>
</table>
	
	      <?php } ?>
        <?php } ?>

