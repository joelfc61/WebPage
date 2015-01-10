
<body >
<?php

function pDebug($str)
{

	global $debug;
    if(gettype($str)=="array"){
       $x="";
       foreach ($str as $key => $value) {
       	  $x=$x."[".$key."]=>".$value.",";
       }
       $str=$x;
    }
	$fp = fopen('debug_log.txt','a');
   if($fp)
   {
     //echo "se creó con éxito";
     fwrite($fp,$str."\r\n");
    
     fclose($fp);
    }
     
		//echo $str . "\n";
}


$tabla	=	"supervisor";
$indice	=	"id_supervisor";

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
	

	$query			=	"";
	foreach($campos as $clave)
		if(isset($_POST[$clave]))
		{

			$query		.=	(($query=="")?"":",")."$clave='".$_POST[$clave]."'";
		}	
  		
		
		
   if(!empty($_POST[$indice])){
        $area= ($_POST[area]=="")?0:1;
        $area2= ($_POST[area2]=="")?0:1;
        $area3= ($_POST[area3]=="")?0:1;
        $area4= ($_POST[area4]=="")?0:1;
	   $query		=	"UPDATE $tabla SET nombre = '{$_POST[nombre]}', usuario = '{$_POST[usuario]}', password = '{$_POST[password]}', area = $area, area2 = $area2, area3 = $area3, area4 = $area4 , numnomina = '{$_POST[numnomina]}', rol = '{$_POST[rol]}', nave = '{$_POST[nave]}'  WHERE ($indice=".$_POST[$indice].")";
	
	 pDebug($query);
	} else {		
	        $area = ($_POST[area]=="")?0:1;
	        $area2= ($_POST[area2]=="")?0:1;
	        $area3= ($_POST[area3]=="")?0:1;
	        $area4= ($_POST[area4]=="")?0:1;	
	    if(isset($_REQUEST['area'])) $area = 1; else $area = 0	;	
		if(isset($_REQUEST['area2'])) $area2 = 1; else $area2 = 0 ;		
		if(isset($_REQUEST['area3'])) $area3 = 1; else $area3 = 0 ;	
		if(isset($_REQUEST['area4'])) $area4 = 1; else $area4 = 0 ;		
			
		$query 		=   "INSERT INTO $tabla  (nombre, usuario, password, area, area2, area3, area4, numnomina, rol, nave) 
		VALUES ('{$_POST[nombre]}','{$_POST[usuario]}','{$_POST[password]}','$area', '$area2', '$area3', '$area4','{$_POST[numnomina]}','{$_POST[rol]}','{$_POST[nave]}')";
		pDebug($query);		
	  }
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
		$desactivar	=	($_GET['accion']=="desactivar")?true:false;
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
	if($desactivar)
	{
		$q_eliminar	=	"UPDATE $tabla SET activo = ".$_GET['valor']." WHERE ($indice={$_GET[$indice]}) LIMIT 1";
		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";
	
	}	
	
}
?>



<?php if($nuevo || $modificar) { ?>
<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
  <table align="center">
<tr>
	<td class="style7" align="right">Nombre</td>
	<td alingn"left"><input name="nombre" type="text"  class="style1" id="nombre" value="<?php if($modificar) echo $dato['nombre']; ?>" size="30" maxlength="60">	</td>
</tr>

<tr>
  <td class="style7" align="right">Usuario</td>
  <td align="left"><span class="titulos">
    <input name="usuario" type="text" class="style1" id="usuario" value="<?php if($modificar) echo $dato['usuario']; ?>" size="30" maxlength="60" /> </span></td>
</tr>
<tr>
  <td class="style7" align="right">Password</td>
  <td align="left" class="contenidos"><span class="titulos">
    <input name="password" type="password" class="style1" id="password" value="<?php if($modificar) echo $dato['password']; ?>" size="30" maxlength="60" />
  </span></td>
</tr>
<tr>
	<td class="style7" align="right">Nave: </td>
	<td class="style5"><select name="nave" id="1">
    	<option value="1">Nave 1</option>
    	<option value="2">Nave 2</option>
    </s elect></td>
</tr >
<tr>
	<td class="style7" align="right">&Aacute;rea</td>
	<td class="style5">
	<input type="checkbox" name="area"  id="area"  value="1" <? if($dato['area'] == 1)  echo "checked";?> >Extruder <br />
	<input type="checkbox" name="area2" id="area2" value="1" <? if($dato['area2'] == 1) echo "checked";?> >Impresión <br />
	<input type="checkbox" name="area3" id="area3" value="1" <? if($dato['area3'] == 1) echo "checked";?> >Camiseta <br />
	<input type="checkbox" name="area4" id="area4" value="1" <? if($dato['area4'] == 1) echo "checked";?> >RPSySF<br />
	
    </select></td>
</tr>
<tr>
  <td class="style7" align="right">Nomina no:</td>
  <td align="left" class="contenidos"><span class="titulos">
    <input name="numnomina" type="text" class="style1" id="numnomina" value="<?php if($modificar) echo $dato['numnomina']; ?>" size="30" maxlength="60" />
  </span></td>
</tr>
<tr>
  <td class="style7" align="right">Rol no:</td>
  <td align="left" class="style5">    <select name="rol" id="rol">
		<? for($a=1; $a <= 4 ; $a++){ ?>
        <option value="<?=$a?>" <? if($dato['rol'] == $a) echo "selected" ?> ><?=$a?></option>
        <? } ?>
    </select></td>
</tr>
<tr>
	<td class="contenidos" align="right" colspan="2">
	<?php if($modificar) { ?>
		<input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>">
	<?php } ?>
	<?php if($nuevo) { ?>
		<input type="hidden" name="status" value="0">
	<?php } ?>	
	    <input name="cancel" type="button" class="style4" value="Cancelar" onclick="javascript: history.go(-1);">
		<input name="submit" type="submit" class="style4" value="Guardar">	</td>
</tr>
</table>
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
  <td class="style5" align="left"><fieldset><?=$dato['usuario']?></fieldset></td>
</tr>
<tr>
  <td class="style7" align="right">Password</td>
  <td class="style5" align="left"><fieldset><?=$dato['password']?></fieldset></td>
</tr>
<tr>
	<td class="style7" align="right">&Aacute;rea:</td>
    <td class="style5" align="left"><fieldset><?
    if($dato['area'] == 1) echo "Extruder<br>"; 
	if($dato['area2'] == 1) echo "Impresion<br>";  
	if($dato['area3'] == 1) echo "Bolseo"; 
	if($dato['area4'] == 1) echo "RPSySF"; 
	
	?></fieldset></td>
</tr>

<tr>
  <td class="style7" align="right">Nomina no.</td>
  <td class="style5" align="left"><fieldset><?=$dato['numnomina']?></fieldset></td>
</tr>
<tr>
  <td class="style7" align="right">Rol no.</td>
  <td class="style5" align="left"><fieldset><?=$dato['rol']?></fieldset></td>
</tr>
<tr>
  <td class="style7" align="right">Nave : </td>
  <td class="style5" align="left"><fieldset><?=$dato['nave']?></fieldset></td>
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
$qListar	=	"SELECT * FROM $tabla WHERE activo = 0 ORDER BY activo, nombre DESC ";
$rListar	=	mysql_query($qListar) OR die("<p>$qListar</p><p>".mysql_error()."</p>");

if(mysql_num_rows($rListar) > 0) { ?>
<table align="center" class="style2" cellpadding="0" cellspacing="0" width="100%">
  <td colspan="8" align="left" class="style4">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="style4">&nbsp;</td>
    <td colspan="7" align="left" class="style4">&nbsp;</td>
  </tr>
  <tr>
    <td width="262" align="left" class="style7"><h3>Nombre</h3></td>
    <td width="129" align="left" class="style7"><h3>Usuario</h3></td>
    <td width="138" align="left" class="style7"><h3>Contrase&ntilde;a</h3></td>
    <td width="56" align="left" class="style7"><h3>Rol</h3></td>
    <td width="56" align="left" class="style7"><h3>Nave</h3></td>
    <td width="144" align="left" class="style7"><h3>Areas</h3></td>
    <td width="56" align="center" class="style7"><h3>&nbsp;</h3></td>
    <td colspan="3"><h3>&nbsp;</h3></td>
  </tr>
  <?php for($a=0;$d	=	mysql_fetch_assoc($rListar);$a++) { ?>
  <tr <? cebra($a)?>>
    <td align="left" class="style5" ><?=$d['nombre']?></td>
    <td align="left" class="style5" ><?=$d['usuario']?></td>
    <td align="left" class="style5" ><?=$d['password']?></td>
    <td align="left" class="style5" ><?=$d['rol'];?></td>
    <td align="left" class="style5" ><?=$d['nave'];?></td>
    <td align="left"><? 
if($d['area'] == '1') echo "Extruder <br>";
if($d['area2'] == '1') echo "Impresion <br>";
if($d['area3'] == '1') echo "Bolseo<br>";
if($d['area4'] == '1') echo "RPSySF<br>";
?></td>
    <td width="61" align="center"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=desactivar&<?=$indice?>=<?=$d['id_supervisor']?>&valor=<?=($d['activo'] == 1)?"0":"1";?>" onClick="javascript: return confirm('Realmente deseas dar de baja a este supervisor');"><strong>
     Desactivar
    </strong></a></td>
    <td width="32" align="center"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&<?=$indice?>=<?=$d['id_supervisor']?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>
    <td width="32" align="center"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&<?=$indice?>=<?=$d['id_supervisor']?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>
    <td width="32" align="center"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&<?=$indice?>=<?=$d['id_supervisor']?>" onClick="javascript: return confirm('Realmente deseas eliminar a este supervisor?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="8"></td>
  </tr>
  <tr>
    <td colspan="8" align="center"><span class="titulos"><br />
      &nbsp;<strong>|</strong>&nbsp;</span> <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7">Nuevo Supervisor</a> <span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span> <br /></td>
  </tr>
</table>
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
	    Aun no hay supervisores registrados en la base de datos<br />
	    <br /></td>
  </tr>
  <tr>
	  <td align="center">
		  <span class="style7"><br />
		  &nbsp;<strong>|</strong>&nbsp;</span>
		  <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7"><strong>Nuevo Supervisor</strong></a>
		  <span class="style7">&nbsp;<strong>|</strong>&nbsp;</span><br />
</td>
  </tr>
  </table>
	
	      <?php } ?>
        <?php } ?>

</body>