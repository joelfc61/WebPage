<?php

$tabla	=	"vendedores";
$indice	=	"id_vendedor";

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
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";
	
	}
	
	
}
?>

<?php if($nuevo || $modificar) { ?>
<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
<br>
<br>
<table align="center">
<tr>
	<td class="style7" align="right">Nombre</td>
	<td alingn"left"><input name="nombre" type="text"  class="style1" id="nombre" value="<?php if($modificar) echo $dato['nombre']; ?>" size="30" maxlength="60">	</td>
</tr>

<tr>
  <td class="style7" align="right">Usuario</td>
  <td align="left"><span class="titulos">
    <input name="user" type="text" class="style1" id="user" value="<?php if($modificar) echo $dato['user']; ?>" size="30" maxlength="60" /> </span></td>
</tr>
<tr>
  <td class="style7" align="right">Password</td>
  <td align="left" class="contenidos"><span class="titulos">
    <input name="pass" type="text" class="style1" id="pass" value="<?php if($modificar) echo $dato['pass']; ?>" size="30" maxlength="60" />
  </span></td>
</tr>
<tr>
  <td class="style7" align="right">Nomina no:</td>
  <td align="left" class="contenidos"><span class="titulos">
    <input name="numnomina" type="text" class="style1" id="numnomina" value="<?php if($modificar) echo $dato['numnomina']; ?>" size="30" maxlength="60" />
  </span></td>
</tr>
<tr>
	<td class="contenidos" align="right" colspan="2">
	<?php if($modificar) { ?>
		<input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>">
	<?php } ?>
	<?php if($nuevo) { ?>
		<input type="hidden" name="status" value="0">
	<?php } ?>	
		<input name="submit" type="submit" class="style4" value="Guardar">	</td>
</tr>
</table>
<br>
<br>
</form>
<?php } ?>

<?php if($mostrar) { ?>
<br />
<br />
<table width="339" align="center">
<tr>
  <td width="95" align="right" class="style7">Nombre</td>
  <td width="243" align="left" class="style5"><fieldset><?=$dato['nombre']?></fieldset></td>
</tr>
<tr>
  <td class="style7" align="right">Usuario</td>
  <td class="style5" align="left"><fieldset><?=$dato['user']?></fieldset></td>
</tr>
<tr>
  <td class="style7" align="right">Password</td>
  <td class="style5" align="left"><fieldset><?=$dato['pass']?></fieldset></td>
</tr>

<tr>
  <td class="style7" align="right">Nomina no.</td>
  <td class="style5" align="left"><fieldset><?=$dato['numnomina']?></fieldset></td>
</tr>
<tr>
	<td class="style7" colspan="2" align="center"><span class="style7"><br />
	  &nbsp;<strong>|</strong>&nbsp;
		<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&amp;<?=$indice?>=<?=$_REQUEST[$indice]?>" class="style7">Modificar</a>	  &nbsp;<strong>|</strong>&nbsp;
		<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=listar" class="style7">Listado</a>	  &nbsp;<strong>|</strong>&nbsp;	<br />
	    <br />
	</span></td>
</tr>
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
<table align="center" width="571" class="style2">
<tr>
	<td width="205" align="left" class="style7"><b>Vendedor</b></td>
	<td width="121" align="left" class="style7"><b>Usuario</b></td>
	<td colspan="4" align="left" class="style7"><strong>Contrase&ntilde;a</strong></td>
  </tr>
<?php for($flag=true;$d	=	mysql_fetch_row($rListar);$flag=!$flag) { ?>
		<tr <?=($flag)?"class=\"cabecera\"":""?>>
<td align="left" class="style5" ><?=$d[1]?></td>
<td align="left" class="style5" ><?=$d[2]?></td>
<td width="121" align="left" class="style5" ><?=$d[3]?></td>

<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&<?=$indice?>=<?=$d[0]?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&<?=$indice?>=<?=$d[0]?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&<?=$indice?>=<?=$d[0]?>" onclick="javascript: return confirm('Realmente deseas eliminar a este supervisor?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
</tr>

	<?php } ?>
   <tr>
   		<td colspan="5">&nbsp;</td>
   </tr> 
   	<tr>
		<td colspan="7" align="center">
			<span class="titulos"><br />
			&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7">Nuevo Vendedor</a>
			<span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br /></td>
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
		<td class="style7" align="center"><br />
		  <br />
		  <br />
	    Aun no hay Vendedores registrados en la base de datos<br>
	    <br />
	    <br /></td>
  </tr>
  <tr>
	  <td align="center">
		  <span class="style7"><br />
		  &nbsp;<strong>|</strong>&nbsp;</span>
		  <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7"><strong>Nuevo Vendedor</strong></a>
		  <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span><br />
</td>
  </tr>
</table>
	
	      <?php } ?>
        <?php } ?>

