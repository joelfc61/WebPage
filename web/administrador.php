<link rel="stylesheet" type="text/css" href="desing/estilos.css">
<link rel="stylesheet" type="text/css" href="style.css">
<script language="javascript" src="js/isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
	$(elemento).className = 'inputon';
	last = valor;
}
function Blur(elemento, valor, campo, id) {
	$(elemento).className = 'inputoff';
	if (last != valor)
		myajax.Link('actualiza.php?valor='+valor+'&campo='+campo+'&id='+id+'&tabla=administrador&tipo=id_admin');
}
</script>
<body onLoad="myajax = new isiAJAX();">
<?php
	

$tabla	=	"administrador";
$indice	=	"id_admin";

$tabla2		=	"permisos";
$indice2	=	"id_tipo";

$campos	=	describeTabla($tabla,$indice);
$campos2	=	describeTabla($tabla2,$indice2);

if(!empty($_POST['submit']))
{
    
	if(	isset($_POST[$indice]) )  $id = intval( $_POST[$indice] );
	else
	{
		$qId		=	"SELECT MAX(id_admin) FROM administrador";
		$rId		=	mysql_query($qId);
		$dId		=	mysql_fetch_row($rId);
		$id			=	intval($dId[0]+1);
	}
	

	$query			=	"";
	foreach($campos as $clave)
		if(isset($_POST[$clave]))
		{
			$query		.=	(($query=="")?"":",")."$clave='".$_POST[$clave]."'";
		}	
  		
	
   if(!empty($_POST[$indice] )){   
	   $query		=	"UPDATE $tabla SET ".$query." WHERE ($indice=".$_POST[$indice].")";
	}else { 
	  $query		=	"INSERT INTO $tabla SET $query";
	  $query2		=	"INSERT INTO $tabla2 SET id_usuario = '".$id."'";
	}


	$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	$res_query2		=	mysql_query($query2) OR die("<p>$query2</p><p>".mysql_error()."</p>");
	$redirecciona	=	true;
	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=tipos&id_admin=".$id."";
}




if(!empty($_REQUEST['guardar_tipo'])){

	$query			=	"";
	foreach($campos2 as $clave)
			
			$query		.=	(($query=="")?"":",")."$clave='".$_POST[$clave]."'";
  		
		    $query		=	"UPDATE $tabla2 SET ".$query." WHERE (id_usuario=".$_POST['id_usuario'].")";
	  
	$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	$redirecciona	=	true;
	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";

}

$listar =	true;
if(!empty($_GET['accion']))
{
	$listar				=	($_GET['accion']=="listar")?true:false;
	$listartipos		=	($_GET['accion']=="listartipos")?true:false;
	
	$nuevo		=	($_GET['accion']=="nuevo")?true:false;
	$tipos		=	($_GET['accion']=="tipos")?true:false;
	
	if((!empty($_GET[$indice]) && is_numeric($_GET[$indice])) || (!empty($_GET[$indice2]) && is_numeric($_GET[$indice2])))
	{
		$maquina	=	($_GET['accion']=="maquina")?true:false;
		
		$mostrar	=	($_GET['accion']=="mostrar")?true:false;
		$eliminar	=	($_GET['accion']=="eliminar")?true:false;
		$modificar	=	($_GET['accion']=="modificar")?true:false;
		
		$eliminartipos	=	($_GET['accion']=="eliminartipos")?true:false;
		$modificartipos	=	($_GET['accion']=="modificartipos")?true:false;
					
	}
	
	
	if($mostrar || $modificar || $tipos)
	{
		$query_dato	=	"SELECT * FROM $tabla WHERE ($indice={$_GET[$indice]})";
		$res_dato	=	mysql_query($query_dato) OR die("<p>$query_dato</p><p>".mysql_error()."</p>");
		$dato		=	mysql_fetch_assoc($res_dato);	
		
			
		
	}
	
	if($modificartipos )
	{
		$query_dato	=	"SELECT * FROM $tabla2 INNER JOIN $tabla ON $tabla2.id_usuario = $tabla.$indice WHERE ($indice2={$_GET[$indice2]})";
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
	
	if($eliminarTipos)
	{
		$q_eliminar	=	"DELETE FROM $tabla2 WHERE ($indice2={$_GET[$indice2]}) LIMIT 1";
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
	<td width="72" align="right" class="style7"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Nombre</td>
	<td width="242" class="titulos">
	  <input name="nombre" type="text" class="style1" id="nombre" value="" size="30" maxlength="60">	</td>
</tr>
<tr>
  <td class="style7" align="right"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Usuario:</td>
  <td align="left" class="contenidos"><span class="titulos">
    <input name="user" type="text" class="style1" id="user" value="" size="30" maxlength="60" />
  </span></td>
</tr>
<tr>
  <td class="style7" align="right" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Contrase&ntilde;a</td>
  <td align="left" class="contenidos"><span class="titulos">
    <input name="pass" type="password" class="style1" id="pass" value="" size="30" maxlength="60" />
  </span></td>
</tr>
<tr>
	<td class="style7" align="right"   style="background-color: rgb(25, 121, 169); color:#FFFFFF">Puesto:</td>
	<td><input type="text"  name="puesto" id="puesto" value="" size="30" maxlength="60" ></td>
</tr>




<tr>
	<td class="contenidos" align="right" colspan="2">
	<?php if($modificar) { ?>
		<input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>">
	<?php } ?>
		<input name="cancel" type="button" class="style4" value="Cancelar" onclick="javascript: history.go(-1);">
		<input name="submit" type="submit" class="style4" value="Guardar">	</td>
</tr>
</table>
  <br />
</form>
<?php } ?>

<?php if($mostrar) { ?>
<br />
<br />
<table width="350" align="center">

<tr>
  <td width="87" align="left" class="style7">Nombre</td>
  <td width="251" align="left" class="style5"><?=$dato['nombre']?></td>
</tr>
<tr>
  <td class="style7" align="left">Nomina no.</td>
  <td class="style5" align="left"><?=$dato['numnomina']?></td>
</tr>
<tr>
  <td class="style7" align="left">Area</td>
  <td class="style5" align="left"><?
  if($dato['area'] == 1) echo "Extruder";
  if($dato['area'] == 2) echo "Bolseo";
  if($dato['area'] == 3) echo "Impresion";
  if($dato['area'] == 4) echo "Area";
  ?></td>
</tr>
<tr>
  <td class="style7" align="left">Puesto</td>
  <td class="style5" align="left"><?
  if($dato['id_puesto'] == 1) echo "Resinero";
  if($dato['id_puesto'] == 2) echo "Operador";
  if($dato['id_puesto'] == 3) echo "Lider";
  if($dato['id_puesto'] == 4) echo "Ayudante";
  if($dato['id_puesto'] == 5) echo "Operador";
  if($dato['id_puesto'] == 6) echo "Rollero";
  if($dato['id_puesto'] == 7) echo "Maquinista";
  if($dato['id_puesto'] == 8) echo "Operador";
  if($dato['id_puesto'] == 9) echo "Ayudante";
  if($dato['id_puesto'] == 10) echo "Aprendiz";
  if($dato['id_puesto'] == 11) echo "Electro-Mecanico";
  if($dato['id_puesto'] == 12) echo "Electronico";
  if($dato['id_puesto'] == 13) echo "Ayudante";  
  if($dato['id_puesto'] == 14) echo "Lubricador";
  if($dato['id_puesto'] == 15) echo "Comodin";
  if($dato['id_puesto'] == 16) echo "Lider";
			  ?></td>
</tr>
<tr>
  <td class="style7" align="left">Rol no.</td>
  <td class="style5" align="left"><?=$dato['rol']?></td>
</tr>
<tr>
	<td class="style2" colspan="2" align="center"><span class="style4"><br />
	  &nbsp;<strong>|</strong>&nbsp;
	<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&amp;<?=$indice?>=<?=$_REQUEST[$indice]?>" class="style7">Modificar</a>	  &nbsp;<strong>|</strong>&nbsp;
	<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=listar" class="style7">Listado</a>	&nbsp;<strong>|</strong>&nbsp;	<br />
	<br />
	</span></td>
</tr>
</table>
<br />
<br />
<?php }  ?>
<? if($maquina){ ?>
<br />
<SCRIPT Language="JAVASCRIPT"> 
<!-- 
function howMany(form){ 
    var numObj = parseInt(form.numeros.value); 
    var html = ''; 
    var container = document.getElementById('maquina'); 

        if (numObj > 0) { 
            for(i=1; i<=numObj; i++) { 
               html += 'Maquina ' + i + ': <input type="hidden" name="codigos[]" value="'+ i +'"><select name="id_maquina_'+ i +'" class="contenidos" id="id_maquina_'+ i +'"><? if($_REQUEST['area'] == 1 || $_REQUEST['area'] == 4) $qMaquina = "SELECT * FROM maquina WHERE area = ".$_REQUEST['area'].""; if($_REQUEST['area'] == 2 || $_REQUEST['area'] == 3) $qMaquina = "SELECT * FROM maquina WHERE area = 2 OR area = 3 ORDER BY area"; $rMaquina = mysql_query($qMaquina); while ($dMaquina = mysql_fetch_assoc($rMaquina)){?> <option value="<?=$dMaquina['id_maquina']?>"><?=$dMaquina['numero']?> - <?  if($dMaquina['area'] == 1) echo "Bolseo"; if($dMaquina['area'] == 2) echo "Impresion"; if($dMaquina['area'] == 3) echo "Linea de Extruder"; if($dMaquina['area'] == 4) echo "Extruder"; ?></option> <? } ?></select><br>'; 
            } 
        } 

container.innerHTML = html; 

} 
//--> 
</SCRIPT>
<form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
<table align="center" width="358" class="style2">
<tr>
  <td width="50" align="left" class="style7" >Nombre:</td>
  <td width="296" align="left" class="style5"><?=$dato['nombre']?></td>
</tr>
<tr>
	<td class="style7" align="left">Rol No.</td>
    <td class="style5" align="left"><?=$dato['rol']?><input type="hidden" value="<?=$dato['rol']?>" name="rol" /></td>
</tr>
	<tr>
    	<td colspan="2">
        	<table width="100%">
            	<tr>
           	     <td width="57%" align="left" class="style7">Numero de Maquinas a Operar:</td>
           	     <td width="43%"><select name="numeros" class="contenidos" onChange="howMany(this.form)">
                 	<option value="0" selected>0</option>
					<? for($a = 1 ; $a < 10; $a++){?> 
                    	<option value="<?=$a?>"><?=$a?></option>
                    <? } ?>
                    </select></td>		
   			  </tr>
			</table>
        </td>
    </tr>
    <tr>
   	  <td colspan="2">
   		  <table width="100%">
  <tr class="titulos cabecera">
                  <td width="401" id="maquina"></td> 
			  </tr> 
            </table>      </td>
   </tr>
<tr>
	<td colspan="2" class="style7"><br>
	  Maquinas asignadas:<input type="hidden" name="area" value="<?=$_REQUEST['area']?>"></td>
</tr>
<? 	
		$qMaquina	=	"SELECT * FROM $tabla2 WHERE ($indice={$_GET[$indice]})";
		$rMaquina	=	mysql_query($qMaquina) OR die("<p>$qMaquina</p><p>".mysql_error()."</p>");
		$nMaquina	=	mysql_num_rows($rMaquina);
		
		if($nMaquina < 1){ ?>
		<tr><td colspan="2"><b>AUN NO SE HAN ASIGNADO MAQUINAS A ESTE OPERADOR</b></td></tr>
        <? } else { 
while($dMaquina		=	mysql_fetch_assoc($rMaquina)){?>
<tr><td colspan="2" ><table><tr>
    <td class="style5" align="left"><b>Maquina no. <?
    $qMaquinas = "SELECT numero, area FROM maquina WHERE id_maquina = '".$dMaquina['id_maquina']."'";
	$rMaquinas = mysql_query($qMaquinas);
	$dMaquinas = mysql_fetch_assoc($rMaquinas);
	echo $dMaquinas['numero']; 
	echo " - ";
	if ($dMaquinas['area'] == 1) echo "Bolseo";
	if ($dMaquinas['area'] == 2) echo "Impresión";
	if ($dMaquinas['area'] == 3) echo "Linea de Impresión";
	if ($dMaquinas['area'] == 4) echo "Extruder";
	?></b></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&<?=$indice?>=<?=$dato['id_operador']?>&accion=eliminar2&<?=$indice2?>=<?=$dMaquina['id_opr_maquina']?>&area=<? if($dato['area'] == 1) echo "4";
																																							  if($dato['area'] == 2) echo "1";
																																							  if($dato['area'] == 3) echo "3";?>" onClick="javascript: return confirm('Realmente deseas quitar esta maquina ?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
</tr></table></td></tr>
<? }
}	 ?>
<tr>
	<td class="contenidos" align="right" colspan="2">
    <input class="style4" value="Regresar al Listado" type="button" onClick="javascript: location.href='admin.php?seccion=<?=$_REQUEST['seccion']?>&buscar=<? if($_REQUEST['area'] == 4) echo "1";
																																							  if($_REQUEST['area'] == 1) echo "2";
																																							  if($_REQUEST['area'] == 3) echo "3";?>&rol=<?=$_REQUEST['rol']?>&nombre=<?=$_REQUEST['nombre']?>';">
		<input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>">
        
		<input name="submit2" type="submit" class="style4" value="Guardar">	</td>
</tr>
</table>
</form>
<br />
<? } ?>

<?php if($listar) { ?>
<?php
$qListar	=	"SELECT * FROM $tabla INNER JOIN $tabla2 ON $tabla.$indice = $tabla2.id_usuario  ORDER BY nombre ASC ";
$rListar	=	mysql_query($qListar) OR die("<p>$qListar</p><p>".mysql_error()."</p>");

?><table width="80%" align="center"  class="style2" cellpadding="2" cellspacing="2">
 	<tr>
		<td colspan="9" align="center">
			<? if($usuarios_a){ ?><span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7">Nuevo Usuario</a>
	  <span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br>
	  <br /><? } ?></td>
  </tr>
<tr>
	<td colspan="9">&nbsp;</td>
</tr>
<tr>
	<td align="left" class="style7" ><h3>Nombre</h3></td>
    <td width="182" align="left" class="style7"><h3>Puesto</h3></td>
    <td width="107" class="style7"><h3>Usuario</h3></td>
    <td width="96" align="left" class="style7" ><h3>Contrase&ntilde;a</h3></td>
	<td colspan="2" width="15"><h3>&nbsp;</h3></td>
  </tr>
<?php
	  		
	if(mysql_num_rows($rListar) == '0'){
				
			echo " <tr>
                        <td  valign='top' colspan='6' class='style2' align='center'>NO SE ENCONTRARON REGISTROS</td>
                   </tr>";
		}
		else {
?>
<?php for($$a= 0;$dListar=	mysql_fetch_assoc($rListar);$a++) { 
			?>
		<tr <?=(bcmod($a,2) == 0)?"bgcolor='#DDDDDD'":""?>>
<td width="242" align="left" class="style7" ><input <? if(!$usuarios_m) echo "readonly"; ?> size="35" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre', <?=$dListar['id_admin'];?>)" class="inputoff" id="a<?=$dListar['id_admin'];?>" value="<?=$dListar['nombre']?>" /></td>
<td><input <? if(!$usuarios_m) echo "readonly"; ?>  type="text" size="35" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'puesto', <?=$dListar['id_admin'];?>)"    class="inputoff" id="b<?=$dListar['id_admin'];?>" value="<?=$dListar['puesto']?>" /></td>
<td><input <? if(!$usuarios_m) echo "readonly"; ?> type="text" size="15" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'user', <?=$dListar['id_admin'];?>)"    class="inputoff" id="c<?=$dListar['id_admin'];?>" value="<?=$dListar['user']?>" /></td>
<td><input <? if(!$usuarios_m) echo "readonly"; ?> type="<? if($usuarios_pass) echo "text"; else echo "password";?>" size="15" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'pass', <?=$dListar['id_admin'];?>)"    class="inputoff" id="d<?=$dListar['id_admin'];?>" value="<?=$dListar['pass']?>" /></td>
<td><? if($usuarios_permisos){ ?><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificartipos&<?=$indice2?>=<?=$dListar['id_tipo']?>" ><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a><? } ?></td>
<td><? if($usuarios_e){ ?><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&<?=$indice?>=<?=$dListar['id_admin']?>" onClick="javascript: return confirm('Realmente deseas eliminar al usuario <?=$dListar['nombre']?>?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a><? } ?></td>
</tr>

<?php }?>
   <tr>
   		<td colspan="9">&nbsp;</td>
   </tr> 
   	<tr>
		<td colspan="9" align="center">
			<? if($usuarios_a){ ?><span class="titulos"><br />
			&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7">Nuevo Usuario</a>
			<span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br /><? } ?></td>
  </tr>
</table>
<blockquote>
  <p><br />
    <?php } ?>
        <?php } ?>
        
 <? if($tipos || $modificartipos){ ?>
 <script language="javascript" type="text/javascript">
function mostrarAreas(id)
{
	id=id;
	
	var objeto=document.getElementById(id);
	if(objeto.style.display=="none")
		objeto.style.display="block";
	else if(objeto.style.display=="block")
		objeto.style.display="none";
	
}

 
 </script>
<form name="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
    	<table width="90%" align="center" cellpadding="2" cellspacing="2">
        	<tr>
            	<td  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Usuario: </td>
				<td class="style4" style="background-color:#DDDDDD"><? $rNombre =	mysql_query("SELECT nombre FROM administrador WHERE id_admin = {$dato[id_admin]}");
															   $dNombre	=	mysql_fetch_assoc($rNombre); echo $dNombre['nombre']?>
                                                               <input type="hidden" name="id_usuario" value="<?=$dato['id_admin']?>"></td>
            </tr>
        	<tr>
            	<td class="style7" align="right" valign="middle" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Modulos:</td>
                <td><table width="100%" cellpadding="2" cellspacing="2">
<tr>
                       	  <td width="50%" valign="top"class="style7" ><label class="style7"><input <? if($dato['maquinaria'] == 1) echo "checked";?> type="checkbox" name="maquinaria" value="1" onChange=" javascript: mostrarAreas('1')" > Maquinaria</label></td>
			  <td width="50%" valign="top"class="style7"><label class="style7"><input <? if($dato['empleados'] == 1) echo "checked";?> type="checkbox" name="empleados" value="1" onChange=" javascript: mostrarAreas('2')" > Empleados</label></td>
           		  </tr>
                        <tr>
                        	<td colspan="2" valign="top">
                           	  <table width="100%" style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
                                   	  <td width="48%" valign="top"><div id="1" style="display:<? if($dato['maquinaria'] == 0) echo "none"; else echo "block"; ?>">
                                      <label><input type="checkbox" name="maquinaria_a" <? if($dato['maquinaria_a'] == 1) echo "checked";?> value="1">Agregar</label><br><br>
                                      <label><input type="checkbox" name="maquinaria_m" <? if($dato['maquinaria_m'] == 1) echo "checked";?> value="1">Modificar</label><br><br>
                   					  <label><input type="checkbox" name="maquinaria_e" <? if($dato['maquinaria_e'] == 1) echo "checked";?> value="1">Eliminar</label></div></td>
                                      <td width="2%"></td>
									  <td width="48%" valign="top"> <div id="2" style="display:<? if($dato['empleados'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox" name="empleados_a" <? if($dato['empleados_a'] == 1) echo "checked";?> value="1">Agregar</label><br><br>
                                      <label><input type="checkbox" name="empleados_am" <? if($dato['empleados_am'] == 1) echo "checked";?> value="1">Asignar maquinas</label><br><br>
                                      <label><input type="checkbox" name="empleados_m" <? if($dato['empleados_m'] == 1) echo "checked";?> value="1">Modificar</label><br><br>
                   					  <label><input type="checkbox" name="empleados_e" <? if($dato['empleados_e'] == 1) echo "checked";?> value="1">Eliminar</label></div></td>                                   
                                    </tr>
                              </table>
                          </td>
                  </tr>
                        <tr>
                       	  <td valign="top" class="style7"><label class="style7"><input type="checkbox" <? if($dato['supervisores'] == 1) echo "checked";?> name="supervisores" value="1" onChange=" javascript: mostrarAreas('3')"> Supervisores</label></td>
                        	<td valign="top"  ><label class="style7"><input type="checkbox" <? if($dato['usuarios'] == 1) echo "checked";?> name="usuarios" value="1" onChange=" javascript: mostrarAreas('4')">Usuarios y Administradores</label></td>
                        </tr>
                        <tr>
                        	<td colspan="2" valign="top">
                            	<table width="100%" style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
                                   	  <td width="48%"><div id="3" style="display:<? if($dato['supervisores'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox" <? if($dato['supervisores_a'] == 1) echo "checked";?> name="supervisores_a" value="1">Agregar</label><br><br>
                                      <label><input type="checkbox" <? if($dato['supervisores_m'] == 1) echo "checked";?> name="supervisores_m" value="1">Modificar</label><br><br>
                   					  <label><input type="checkbox"<? if($dato['supervisores_e'] == 1) echo "checked";?>  name="supervisores_e" value="1">Eliminar</label></div></td>
                                      <td width="2%"></td>
									  <td width="48%"><div id="4" style="display:<? if($dato['usuarios'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox" <? if($dato['usuarios_pass'] == 1) echo "checked";?> name="usuarios_pass" value="1">Ver contrase&ntilde;as</label><br><br>
                                      <label><input type="checkbox" <? if($dato['usuarios_permisos'] == 1) echo "checked";?> name="usuarios_permisos" value="1">Administrar permisos</label><br><br>
                                      <label><input type="checkbox" <? if($dato['usuarios_a'] == 1) echo "checked";?> name="usuarios_a" value="1">Agregar</label><br><br>
                                      <label><input type="checkbox" <? if($dato['usuarios_m'] == 1) echo "checked";?> name="usuarios_m" value="1">Modificar</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['usuarios_e'] == 1) echo "checked";?> name="usuarios_e" value="1">Eliminar</label></div></td>                                   
                                    </tr>
                                </table>
                          </td>
                        </tr>                        
                        <tr>
                   	      <td valign="top" class="style7"><label class="style7"><input type="checkbox" <? if($dato['extruder'] == 1) echo "checked";?> name="extruder" value="1" >Area Extruder</label></td>
                        	<td valign="top" class="style7"><label class="style7"><input type="checkbox" <? if($dato['autorizacion'] == 1) echo "checked";?> name="autorizacion" value="1"> Autorizacion</label></td>
                        </tr>
                        <tr>
                        	<td colspan="2" valign="top">
                            	<table width="100%"  style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
                                   	  <td width="48%"></td>
                                      <td width="2%"></td>
									  <td width="48%"></td>                                   
                                    </tr>
                                </table>
                          </td>
                        </tr>                          
                        <tr>
                       	  <td valign="top" class="style7"><label class="style7"><input type="checkbox" <? if($dato['impresion'] == 1) echo "checked";?> name="impresion" value="1" > Area Impresion</label></td>
                        	<td valign="top" class="style7"><label class="style7"><input type="checkbox" <? if($dato['prenomina'] == 1) echo "checked";?> name="prenomina" value="1" onChange=" javascript: mostrarAreas('5')"> Pre-nomina</label></td>
                        </tr>
                        <tr>
                        	<td colspan="2" valign="top">
                            	<table width="100%"  style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
                                   	  <td width="48%"></td>
                                      <td width="2%"></td>
									  <td width="48%"><div id="5" style="display:<? if($dato['prenomina'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox" <? if($dato['prenomina_m'] 		== 1) echo "checked";?> name="prenomina_m" value="1">Modificar</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_autoriza'] 	== 1) echo "checked";?> name="prenomina_autoriza" value="1">Autorizar</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_pre_autoriza'] 	== 1) echo "checked";?> name="prenomina_pre_autoriza" value="1">Pre - Autorizar</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_ext'] 	== 1) echo "checked";?> name="prenomina_ext" value="1">Extruder </label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_impr'] 	== 1) echo "checked";?> name="prenomina_impr" value="1">Impresion</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_bol'] 	== 1) echo "checked";?> name="prenomina_bol" value="1">Bolseo</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_mtto'] 	== 1) echo "checked";?> name="prenomina_mtto" value="1">Mantenimiento</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_mttob'] 	== 1) echo "checked";?> name="prenomina_mttob" value="1">Mantenimiento B</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_emp'] 	== 1) echo "checked";?> name="prenomina_emp" value="1">Empaque</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_empb'] 	== 1) echo "checked";?> name="prenomina_empb" value="1">Empaque B</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_alm'] 	== 1) echo "checked";?> name="prenomina_alm" value="1">Almacen </label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_almb'] 	== 1) echo "checked";?> name="prenomina_almb" value="1">Almacen B</label><br><br>
                                      <label><input type="checkbox" <? if($dato['movimientos_produccion'] 	== 1) echo "checked";?> name="movimientos_produccion" value="1">Ver Movimientos de Produccion</label><br><br>
                                      <label><input type="checkbox" <? if($dato['movimientos_almacen'] 	== 1) echo "checked";?> name="movimientos_almacen" value="1">Ver Movimientos de Almacen</label><br><br>
                                      <label><input type="checkbox" <? if($dato['prenomina_mo'] 	== 1) echo "checked";?> name="prenomina_mo" value="1">Modificar Movimientos</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['autoriza_movimiento']	== 1) echo "checked";?> name="autoriza_movimiento" value="1">Autorizar movimientos</label><br><br>   
                   					  <label><input type="checkbox" <? if($dato['prenomina_semana']	== 1) echo "checked";?> name="prenomina_semana" value="1">Cerrar semana de movimientos</label><br><br>   
                                                                      
                   					  <label><input type="checkbox" <? if($dato['prenomina_re_mo']	== 1) echo "checked";?> name="prenomina_re_mo" value="1">Reporteo de movimientos</label></div></td>                                   
                                    </tr>
                                </table>
                          </td>
                        </tr>                          
                        <tr>
                       	  <td valign="top" class="style7"><label class="style7"><input type="checkbox" value="1" <? if($dato['bolseo'] == 1) echo "checked";?> name="bolseo" > Area Bolseo</label></td>
                        	<td valign="top" class="style7"><label class="style7"><input type="checkbox" <? if($dato['meta'] == 1) echo "checked";?> name="meta" value="1" onChange=" javascript: mostrarAreas('7')"> Metas</label></td>
                        </tr>     
                        <tr>
                        	<td colspan="2" valign="top">
                            	<table width="100%"  style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
									  <td width="48%"></td>
                                      <td width="2%"></td>
                                      <td width="48%"><div id="7" style="display:<? if($dato['meta'] == 0) echo "none"; else echo "block"; ?>;">
                   					  <label><input type="checkbox" <? if($dato['meta_a'] 	== 1) echo "checked";?> name="meta_a" value="1">Agregar</label><br><br>
                                      <label><input type="checkbox" <? if($dato['meta_m'] 	== 1) echo "checked";?> name="meta_m" value="1">Modificar</label><br><br>
                                      <label><input type="checkbox" <? if($dato['meta_e'] 	== 1) echo "checked";?> name="meta_e" value="1">Eliminar</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['paro_a'] 	== 1) echo "checked";?> name="paro_a" value="1">Agregar Paros</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['paro_m'] 	== 1) echo "checked";?> name="paro_m" value="1">Modificar Paros</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['paro_e'] 	== 1) echo "checked";?> name="paro_e" value="1">Eliminar Paros</label></div>
                                      </td>                                   
                                    </tr>
                                </table>
                          </td>
                        </tr>
                        <tr>
                       	  <td valign="top" class="style7"><label class="style7"><input type="checkbox" name="repesadas" value="1" <? if($dato['repesadas'] == 1) echo "checked";?> onChange=" javascript: mostrarAreas('8')"> 
                       	  Repesadas</label></td>
                        	<td valign="top" class="style7"><label class="style7"><input type="checkbox" name="pendientes" value="1" <? if($dato['pendientes'] == 1) echo "checked";?> onChange=" javascript: mostrarAreas('9')">
                       	  Reportes Pendientes</label></td>
                        </tr>
                        <tr>
                        	<td colspan="2" valign="top">
                            	<table width="100%"  style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
                                      <td width="48%" valign="top"><div id="8" style="display:<? if($dato['repesadas'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox" <? if($dato['repesadas_p'] == 1) echo "checked";?> name="repesadas_p" value="1">Pendientes</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['repesadas_m'] == 1) echo "checked";?> name="repesadas_m" value="1">Modificar</label></div></td>
                                      <td width="2%"></td>
									  <td width="48% " valign="top"><div id="9" style="display:<? if($dato['pendientes'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox" <? if($dato['pendientes_m'] == 1) echo "checked";?> name="pendientes_m" value="1">Modificar</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['pendientes_e'] == 1) echo "checked";?> name="pendientes_e" value="1">Eliminar</label></div>
                                      </td>
                                    </tr>
                                </table>
                          </td>
                        </tr>
                        <tr>
                       	  <td valign="top" class="style7"><label class="style7"><input type="checkbox" name="reportes_extruder" value="1" <? if($dato['reportes_extruder'] == 1) echo "checked";?> onChange=" javascript: mostrarAreas('10')"> 
                       	  Reportes Extruder</label></td>
                        	<td valign="top" class="style7"><label class="style7"><input type="checkbox" name="reportes_empaque" value="1" <? if($dato['reportes_empaque'] == 1) echo "checked";?> onChange=" javascript: mostrarAreas('11')">
                       	  Reportes Empaque</label></td>
                        </tr>
                        <tr>
                        	<td colspan="2" valign="top">
                            	<table width="100%"  style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
									  <td width="48%" valign="top"><div id="10" style="display:<? if($dato['reportes_extruder'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox"  <? if($dato['extruder_d'] == 1) echo "checked";?> name="extruder_d" value="1">Diario</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['extruder_ma'] == 1) echo "checked";?> name="extruder_ma" value="1">Por Maquinas</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['extruder_s'] == 1) echo "checked";?> name="extruder_s" value="1">Por Supervisor</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['extruder_mh'] == 1) echo "checked";?> name="extruder_mh" value="1">Historial cambio de mallas</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['extruder_i'] == 1) echo "checked";?> name="extruder_i" value="1">Por Indicencias</label></div>
                                      </td>
                                      <td width="2%"></td>
                                      <td width="48%" valign="top"><div id="11" style="display:<? if($dato['reportes_empaque'] == 0) echo "none"; else echo "block"; ?>;">
                   					  <label><input type="checkbox"  <? if($dato['empaque_d'] == 1) echo "checked";?> name="empaque_d" value="1">Diario</label><br><br>
                                      <label><input type="checkbox"  <? if($dato['empaque_e'] == 1) echo "checked";?> name="empaque_e" value="1">Por Empacador</label></div>
                                      </td>                                   
                                    </tr>
                                </table>
                          </td>
                        </tr>
                        
                        <tr>
                       	  <td valign="top" class="style7"><label class="style7"><input type="checkbox" name="reportes_impr" value="1" <? if($dato['reportes_impr'] == 1) echo "checked";?>  onChange=" javascript: mostrarAreas('12')">
                   	      Reportes Impresion</label></td>
                        	<td valign="top" class="style7"><label class="style7"><input type="checkbox" name="concentrado_rep" value="1" <? if($dato['concentrado_rep'] == 1) echo "checked";?> onChange=" javascript: mostrarAreas('13')">
                   	      Concentrado de Reportes</label></td>
                        </tr>
                        <tr>
                        	<td colspan="2" valign="top">
                       	  <table width="100%"  style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
									  <td width="48%" valign="top"><div id="12" style="display:<? if($dato['reportes_impr'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox"  <? if($dato['impresion_d'] == 1) echo "checked";?>  name="impresion_d" value="1">Diario</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['impresion_m'] == 1) echo "checked";?>  name="impresion_m" value="1">Por Maquinas</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['impresion_s'] == 1) echo "checked";?>  name="impresion_s" value="1">Por Supervisor</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['impresion_ci'] == 1) echo "checked";?>  name="impresion_ci" value="1">Historial Cambio de Impresion</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['impresion_i'] == 1) echo "checked";?>  name="impresion_i" value="1">Por Incidencias</label></div>
                                      </td>
                                      <td width="2%"></td>
                                      <td width="48%" valign="top"><div id="13" style="display:<? if($dato['concentrado_rep'] == 0) echo "none"; else echo "block"; ?>;">
                   					  <label><input type="checkbox"  <? if($dato['concentrado_pd'] == 1) echo "checked";?>  name="concentrado_pd" value="1">Produccion Diaria</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_re'] == 1) echo "checked";?>  name="concentrado_re" value="1">Repesadas</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_ot'] == 1) echo "checked";?>  name="concentrado_ot" value="1">Ordenes totalizadas</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_ot_mod'] == 1) echo "checked";?>  name="concentrado_ot_mod" value="1">Modificar Ordenes totalizadas</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_mp'] 	== 1) echo "checked";?>  name="concentrado_mp" value="1">Metas y producciones mensuales.</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_pm'] 	== 1) echo "checked";?>  name="concentrado_pm" value="1">Paros mensual</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_rd'] 	== 1) echo "checked";?>  name="concentrado_rd" value="1">Resumen desperdicios</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_ccm'] == 1) echo "checked";?>  	name="concentrado_ccm" value="1">Concentrado Area de Camisetas</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_pegt'] == 1) echo "checked";?>  	name="concentrado_pegt" value="1">Produccion extruder por grupo y turno</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_bmp'] == 1) echo "checked";?>  	name="concentrado_bmp" value="1">Produccion-Meta Bolseo por grupo y turno</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_khpt'] == 1) echo "checked";?>  	name="concentrado_khpt" value="1">KGS x Hora por grupo y turno</label><br><br>
                   					  <label><input type="checkbox"  <? if($dato['concentrado_rpr'] == 1) echo "checked";?>  	name="concentrado_rpr" value="1">Reporte de Produccion Extruder por Maquinas</label><br><br>
                                      <label><input type="checkbox"  <? if($dato['concentrado_dm'] == 1) echo "checked";?>  	name="concentrado_dm" value="1">Desperdicios mensuales.</label></div>
                                      </td>                                   
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                       	  <td valign="top" class="style7"><label class="style7"><input type="checkbox" value="1" id="reportes_bolseo" name="reportes_bolseo" <? if($dato['reportes_bolseo'] == 1) echo "checked";?> onChange=" javascript: mostrarAreas('14')">
                   	      Reportes Bolseo</label></td>
                        	<td valign="top" class="style7"><label class="style7"><input type="checkbox" value="1" id="historial" name="historial" <? if($dato['historial'] == 1) echo "checked";?> onChange=" javascript: mostrarAreas('15')">
                        	Historial de Reportes
                       	  </label></td>
                        </tr>
                        <tr>
                        	<td colspan="2" valign="top">
                            	<table width="100%"  style="background-color:#EEEEEE;">
                                	<tr>
                                      <td width="2%"></td>
									  <td width="48%" valign="top"><div id="14" style="display:<? if($dato['reportes_bolseo'] == 0) echo "none"; else echo "block"; ?>;">
                                      <label><input type="checkbox" <? if($dato['bolseo_d'] == 1) echo "checked";?>  name="bolseo_d" value="1">Diario</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['bolseo_m'] == 1) echo "checked";?>  name="bolseo_m" value="1">Por Maquinas</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['bolseo_s'] == 1) echo "checked";?>  name="bolseo_s" value="1">Por Supervisor</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['bolseo_i'] == 1) echo "checked";?>  name="bolseo_i" value="1">Por Incidencias</label><br><br>
                   					  <label><input type="checkbox" <? if($dato['bolseo_config'] == 1) echo "checked";?>  name="bolseo_config" value="1">Configuracion de bolseo</label></div>
                                      </td>
                                      <td width="2%"></td>
                                      <td width="48%" valign="top"><div id="15" style="display:<? if($dato['historial'] == 0) echo "none"; else echo "block"; ?>;">
                   					  <label><input type="checkbox" <? if($dato['historial_v'] == 1) echo "checked";?>  name="historial_v" value="1">Ver</label><br><br>
                                      <label><input type="checkbox" <? if($dato['historial_m'] == 1) echo "checked";?>  name="historial_m" value="1">Modificar</label></div>
                                      </td>                                   
                                    </tr>
                                </table>
                          </td>
                        </tr>                        
                	</table>

              <br>
              <br>
              <br></td></tr> 
              <tr>
              	<td colspan="2" align="center"><input name="cancel" type="button" class="style4" value="Cancelar" onclick="javascript: history.go(-1);">	
              		<input name="guardar_tipo" type="submit" class="style4" value="Guardar">	
                <? if($modificartipos || $tipos) {?><input type="hidden" name="<?=$indice2?>" value="<?=$dato['id_tipo']?>"><? } ?></td>
              </tr><br><br><br><br><br>
 		</table></form>
 <? } ?>
 
<?php if($listartipos) { 
$qListar	=	"SELECT * FROM $tabla2  WHERE 1 ORDER BY nombre ASC ";
$rListar	=	mysql_query($qListar) OR die("<p>$qListar</p><p>".mysql_error()."</p>");

?><table width="50%" align="center"  class="style2" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="9" align="left" class="style4"><br />
    <br /></td>
</tr>
 	<tr>
		<td colspan="9" align="center">
			<span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=tipos" class="style7">Nuevo Tipo de Usuario</a>
	  <span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br>
	  <br /></td>
  </tr>
<tr>
	<td colspan="9">&nbsp;</td>
  </tr>
<tr>
	<td align="left" class="style7" ><h3>Nombre</h3></td>
	<td colspan="4"><h3>&nbsp;</h3></td>
  </tr>

<?php
	  		
	if(mysql_num_rows($rListar) == '0'){
				
			echo " <tr>
                        <td  valign='top' colspan='6' class='style2' align='center'>NO SE ENCONTRARON REGISTROS</td>
                   </tr>";
		}
		else {
?>
<?php for($a= 0;$dListar=	mysql_fetch_assoc($rListar);$a++) { 
			?>
		<tr <?=(bcmod($a,2) == 0)?"bgcolor='#DDDDDD'":""?>>
<td width="242" align="left" class="style7" ><?=$dListar['nombre']?></td>
<td><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificartipos&<?=$indice2?>=<?=$dListar['id_tipo']?>"> <img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>
<td><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminartipos&<?=$indice2?>=<?=$dListar['id_tipo']?>" onClick="javascript: return confirm('Realmente deseas eliminar a este usuario?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
</tr>

<?php }?>
   <tr>
   		<td colspan="9">&nbsp;</td>
   </tr> 
   	<tr>
		<td colspan="9" align="center">
			<span class="titulos"><br />
			&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=tipos" class="style7">Nuevo Tipo de Usuario</a>
			<span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br /></td>
  </tr>
</table>
<blockquote>
  <p><br />
    <?php } ?>
        <?php } ?>
</body>