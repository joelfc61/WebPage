<?php

	$indice2	=	(isset($_GET['indice2']) && is_numeric($_GET['indice2']) )?$_GET['indice2']:0;
	$lapso	=	10;
	//$max =	$lapso + indice2;
	
	
$tabla	=	"mensajeria";
$indice	=	"id_mensaje";


if(isset($_SESSION['id_admin'])){ $usuarios = $_SESSION['id_admin'];  $tiposs = 'u'; }
if(isset($_SESSION['id_supervisor'])){ $usuarios = $_SESSION['id_supervisor'];  $tiposs = 's'; }

$campos	=	describeTabla($tabla,$indice);


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
					 if(isset($_SESSION['id_supervisor']) && $dNumeroSuper['id_supervisor'] == $_SESSION['id_supervisor']){ }
					 else {
				 $mensaje		=	"INSERT INTO mensajeria (de,para,titulo,mensaje,checado,fecha,grupo,tipo,tipo2)".
								" VALUES('$id','".$dNumeroSuper['id_supervisor']."','{$_REQUEST[titulo]}','{$_REQUEST[mensaje]}','{$_REQUEST[checado]}','{$_REQUEST[fecha]}', '$grupo', '$tipo','s');";
	 			 $res_query		=	mysql_query($mensaje) OR die("<p>$mensaje</p><p>".mysql_error()."</p>");
			}
			}
		
		} else {
				
				
				$mensaje		=	"INSERT INTO mensajeria (de,para,titulo,mensaje,checado,fecha, grupo,tipo,tipo2)".
								" VALUES('$id','$para2','{$_REQUEST[titulo]}','{$_REQUEST[mensaje]}','{$_REQUEST[checado]}','{$_REQUEST[fecha]}', '$grupo','$tipo','$tipo2');";		
			   $res_query		=	mysql_query($mensaje) OR die("<p>$mensaje</p><p>".mysql_error()."</p>");

		}
		
	echo '<script laguaje="javascript">location.href=\''.$_SERVER['host'].'?seccion='.$_REQUEST['seccion'].'&accion=listarEnviados\';</script>';
}

$listar =	true;
if(!empty($_GET['accion']))
{
	$centro				=	($_GET['accion']=="centro")?true:false;
	$listarEnviados		=	($_GET['accion']=="listarEnviados")?true:false;
	$listarRecibidos	=	($_GET['accion']=="listarRecibidos")?true:false;
	$nuevo				=	($_GET['accion']=="nuevo")?true:false;
	$eliminar			=	($_GET['accion']=="eliminar")?true:false;
	$eliminarSupervisor		=	($_GET['accion']=="eliminarSupervisor")?true:false;
	if(!empty($_GET[$indice]) && is_numeric($_GET[$indice]) )
	{
		$ver	=	($_GET['accion']=="ver")?true:false;
		$modificar	=	($_GET['accion']=="reenviar")?true:false;
			
	}
	if($modificar || $ver)
	{
		$qModificar	=	"SELECT * FROM $tabla WHERE ($indice={$_GET[$indice]}) LIMIT 1";
		$rModificar	=	mysql_query($qModificar) OR die("<p>$qModificar</p><p>".mysql_error()."</p>");
		$dModificar	=	mysql_fetch_assoc($rModificar);
	}	
	
	if($eliminar)
	{
		$grupo = $_REQUEST['grupo'];
		if($grupo == 'a') echo $num = mysql_num_rows(mysql_query("SELECT id_supervisor FROM supervisor"));
		else if($grupo == 'b') $num = mysql_num_rows(mysql_query("SELECT id_supervisor FROM supervisor WHERE area = 1"));
		else if($grupo == 'c') $num = mysql_num_rows(mysql_query("SELECT id_supervisor FROM supervisor WHERE area2 = 1"));
		else if($grupo == 'd') $num = mysql_num_rows(mysql_query("SELECT id_supervisor FROM supervisor WHERE area3 = 1"));
		else if($grupo == 'todos'){ 
		$num = mysql_num_rows(mysql_query("SELECT id_supervisor FROM supervisor"));
		$num = $num + ($num = mysql_num_rows(mysql_query("SELECT id_admin FROM administrador")));
		}
		else 
		$num = 1;
		
		 $num;

		for($a = 0; $a < $num; $a++){
		$id_mensaje	=	$_GET['id_mensaje']+$a;
		$q_eliminar	=	"DELETE FROM $tabla WHERE (id_mensaje ='$id_mensaje') ";
		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");
		
		}
		
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=".$_GET['accion2']."";
		
	}
	if($eliminarSupervisor)
	{
					
		$q_eliminarSuper	=	"UPDATE $tabla SET desactivado = 1 WHERE (id_mensaje='{$_GET[id_mensaje]}')";
		$r_eliminarSuper	=	mysql_query($q_eliminarSuper) OR die("<p>$q_eliminarSuper</p><p>".mysql_error()."</p>");
		
			echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion='.$_GET['seccion'].'&accion='.$_GET['accion2'].'\';</script>';

	}	
}
?>



<?php if($nuevo || $modificar) { ?>
<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
    <table width="100%" >
    <tr>
    <td width="15%" valign="top">
   				<table width="100%" cellpadding="0" cellspacing="0" align="left">
                	<tr>
                    	<td align="center"><img src="images/mensajeria.jpg" border="0" usemap="#mensajes" /></td>
                    </tr>
                </table>
        </td>
   <td width="85%" valign="top">
  <table align="center" width="80%">
  <tr>
  	<td>&nbsp;</td>
  </tr>
<tr>
	<td width="12%" align="right" class="style7"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Para: </td>
	<? if(isset($_REQUEST['id_de'])){?>
    <td width="88%" align="left" style="background-color:#DDDDDD" class="style5"><b>
    <?
	$revisa 	= explode("_", $_REQUEST['id_de']);
		
	$id 	= $revisa[0];
	$tipo 	= $revisa[1];		
	
	if($tipo == "u"){
	$buscar		=	"administrador"	;
	$id_tabla	=	"id_admin";
	}
	if($tipo == "s"){
	$buscar		=	"supervisor";			
	$id_tabla	=	"id_supervisor";
	}
	$res	= tabla_muestra($id,$buscar,$id_tabla);
	?><input type="hidden" value="<?=$res[0];?>_<?=$tipo?>" name="para"  /><?=$res[1]?></b>
    </td>
    <? } else { ?>
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
  <? } ?>
</tr>
<tr>
  <td class="style7" align="right"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Titulo: </td>
  <td align="left" style="background-color:#EEEEEE"><span class="titulos">
    <input name="titulo" type="text" class="style1" id="titulo" value="<? if($modificar || isset($_REQUEST['id_de'])) { echo "Re : "; echo htmlspecialchars($dModificar['titulo']); } if(isset($_REQUEST['id_de'])) echo $_REQUEST['titulo'];	?>" size="60"  />
    <input type="hidden" name="fecha" id="fecha" value="<?=date('Y-m-d');?>">
    <input type="hidden" name="checado" id="checado" value="0">
    <input type="hidden" name="de" id="de" value="<? if (isset($_SESSION['id_admin'])){ echo $_SESSION['id_admin']."_u"; } else { echo $_SESSION['id_supervisor']."_s"; }?>"></span></td>
</tr>
<tr>
  <td class="style7" align="right" valign="top"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Mensaje: </td>
  <td align="left" class="contenidos" valign="top" style="background-color:#DDDDDD"><span class="titulos"><textarea name="mensaje" id="mensaje" cols="60" rows="8"><? if($modificar) echo htmlspecialchars($dModificar['mensaje']);?></textarea></span></td>
</tr>
<tr>
	<td colspan="2" align="right"><input name="mandar" type="submit" class="style4" value="Enviar">
    </td>
</tr>
</table></td></tr></table>
</form>
<?php } ?>

<?php if($ver) { 

if(isset($_REQUEST['actualiza'])){
$qActualizar	=	"UPDATE $tabla SET checado = 1 WHERE ($indice ='{$_GET[$indice]}')";
$rActualizar	=	mysql_query($qActualizar);
}
?>
<div id="content">
	<div id="datosgenerales" style="background-color:#FFFFFF;">	
     <table width="100%" >
    <tr>
        <td width="2%" valign="top">&nbsp;</td>
        <td width="20%" valign="top">    
   				<table width="100%" cellpadding="0" cellspacing="0" align="left">
                	<tr>
                    	<td align="center"><img src="images/mensajeria.jpg" border="0" usemap="#mensajes" /></td>
                    </tr>
                </table>
        </td>
        <td width="2%" valign="top">&nbsp;</td>
   <td width="60%" valign="top">
<table width="100%" align="center" class="style2" border="0" cellpadding="2" cellspacing="2" >

<tr>
	<td width="11%" align="right" class="style7" style="background-color: rgb(25, 121, 169); color:#FFFFFF">De: </td>
	<td class="style5" style="background-color:#DDDDDD"><? 
	
	if($dModificar['tipo'] == 'u')
	$res = tabla_muestra($dModificar['de'],'administrador','id_admin');
	if($dModificar['tipo'] == 's')
	 $res = tabla_muestra($dModificar['de'],'supervisor','id_supervisor');
	
	echo $res[1].' - '.$res[2];
	?></td>
 </tr>
<tr>
	<td width="11%" align="right" class="style7" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Para: </td>
	<td width="89%" align="left" class="style5" style="font-size:12px; background-color:#EEEEEE">
	<? if($dModificar['grupo'] == 'todos') echo "Todos los usuarios del sistema";?>	
	<? if($dModificar['grupo'] == 'a') echo "Extruder, Impresi&oacute;n y Bolseo";?>	
	<? if($dModificar['grupo'] == 'b') echo "Supervisores de Extruder";?>
	<? if($dModificar['grupo'] == 'c') echo "Supervisores de Impresi&oacute;n";?>
	<? if($dModificar['grupo'] == 'd') echo "Supervisores de Bolseo";?>
    <? if($dModificar['grupo'] != 'a' && $dModificar['grupo'] != 'b' && $dModificar['grupo'] != 'c' && $dModificar['grupo'] != 'd' && $dModificar['grupo'] != 'todos' ){
	
	$id 	= $dModificar['para'];
	$tipo 	= $dModificar['tipo2'];		
	
	if($tipo == "u"){
	$buscar		=	"administrador"	;
	$id_tabla	=	"id_admin";
	}
	if($tipo == "s"){
	$buscar		=	"supervisor";			
	$id_tabla	=	"id_supervisor";
	}
	$res	= tabla_muestra($id,$buscar,$id_tabla);
		
		echo $res['1'];
	
	} ?>    </td>
</tr>
<tr>
  <td class="style7" align="right" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Fecha: </td>
<td align="left" class="style5" style="font-size:12px; background-color:#DDDDDD"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$dModificar['fecha'])?></td>
</tr>
<tr>
  <td class="style7" align="right" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Titulo: </td>
  <td align="left" class="style5" style="font-size:12px; background-color:#EEEEEE;"><?=htmlspecialchars($dModificar['titulo']);?></td>
</tr>
<tr>
  <td class="style7" align="right" valign="top" style="background-color: rgb(25, 121, 169); color:#FFFFFF">Mensaje: </td>
  <td align="left" class="style5" style="font-size:12px; background-color:#FFFFFF" valign="top"><? if($ver) echo htmlspecialchars($dModificar['mensaje']);?>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br /></td>
</tr>
<tr>
	<td colspan="2" align="right"><input type="button" value="Imprimir" name="imprimir"  onClick="window.print();" />&nbsp;&nbsp;<input type="button" class="style7" value="Contestar" onclick="javascript: location.href='<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&id_de=<?=$dModificar['de']?>_<?=$dModificar['tipo']?>&accion=nuevo';">&nbsp;&nbsp;<input name="regresar" type="button" class="style7" value="Regresar" onClick="javascript: history.go(-1)"></td>
</tr>
</table></td>
        <td width="4%" valign="top">&nbsp;</td>

</tr></table>
</div>
</div>
<?php } ?>


<?php if($listarRecibidos || $listarEnviados) { 


if($listarRecibidos){
  $qListar	=	"SELECT * FROM $tabla WHERE para = '".$usuarios."'  AND tipo2 = '".$tiposs."' ";
 if(!isset($_SESSION['id_admin']))  $qListar	.=	"AND desactivado = 0 ";
 				 $qListar	.=	"ORDER BY  id_mensaje DESC LIMIT $indice2 , $lapso";
}
if($listarEnviados){
 $qListar	=	"SELECT * FROM $tabla  WHERE de = '".$usuarios	."' AND tipo = '".$tiposs."' ";
  if(!isset($_SESSION['id_admin']))  $qListar	.=	"AND desactivado = 0 ";
				 $qListar	.=	" GROUP BY grupo, titulo ORDER BY id_mensaje DESC LIMIT $indice2 , $lapso";
}
$rListar	=	mysql_query($qListar) OR die("<p>$qListar</p><p>".mysql_error()."</p>");


?>
<div id="content">
	<div id="datosgenerales" style="background-color:#FFFFFF;">	
    <table width="100%">
    <tr>
    <td width="15%" valign="top">
   				<table width="100%" cellpadding="0" cellspacing="0" align="left">
                	<tr>
                    	<td align="center"><img src="images/mensajeria.jpg" border="0" usemap="#mensajes" /></td>
                    </tr>
                </table>
        </td>
        <td width="5%">&nbsp;</td>
        
        <td width="80%" valign="top">
<table width="100%" align="center" class="style2" cellpadding="0" cellspacing="0">
<tr>
  <td align="left" class="style4">&nbsp;</td>
  <td colspan="7" align="left" class="style4">&nbsp;</td>
  </tr>
<tr>
	<td width="91"  class="style7" align="center"><h3>Fecha</h3></td>
	<td width="289"  class="style7" align="center"><h3>Titulo</h3></td>
	<td width="292" class="style7" align="center"><h3><? if($listarRecibidos) {?> De: <? } else { ?>Dirigido a<? } ?></h3></td>
	<td colspan="3" class="style7"  align="center"><h3>&nbsp;</h3></td>
  </tr>
<?  	if(mysql_num_rows($rListar) > 0) { ?><?php for($a = 0;$d	=	mysql_fetch_assoc($rListar);$a++) { ?>
		<tr <? if (bcmod($a,2) == 0) echo " bgcolor='#DDDDDD'"; else echo "";?>>
<td align="center" class="style5" ><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" ,$d['fecha'])?></td>
<td align="left" class="style5" ><a class="style5" href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=ver&<?=$indice?>=<?=$d['id_mensaje']?>&grupo=<?=$d['grupo']?><? if($listarRecibidos) {?>&actualiza<? } ?>"><?=$d['titulo']?></a></td>
<td align="left" class="style5" ><a class="style5" href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=ver&<?=$indice?>=<?=$d['id_mensaje']?>&grupo=<?=$d['grupo']?><? if($listarRecibidos) {?>&actualiza<? } ?>"><? 

if($listarRecibidos) {
	$id 	= $d['de'];
	$tipo 	= $d['tipo'];		
	
	if($tipo == "u"){
	$buscar		=	"administrador"	;
	$id_tabla	=	"id_admin";
	}
	if($tipo == "s"){
	$buscar		=	"supervisor";			
	$id_tabla	=	"id_supervisor";
	}
	$res	= tabla_muestra($id,$buscar,$id_tabla);
		
		echo $res['1'];
		
	

} else {
if($d['grupo'] == 'todos' ) echo "Todos los usuarios del sistema";
if($d['grupo'] == 'a' ) echo "Extruder, Impresion y Bolseo";
if($d['grupo'] == 'b' ) echo "Extruder";
if($d['grupo'] == 'c' ) echo "Impresion";
if($d['grupo'] == 'd' ) echo "Bolseo";
?>
    <? if($d['grupo'] != 'a' && $d['grupo'] != 'b' && $d['grupo'] != 'c' && $d['grupo'] != 'd' && $d['grupo'] != 'todos' ){
	
	 $id 	= $d['para'];
	 $tipo 	= $d['tipo2'];
		
	
	if($tipo == "u"){
	$buscar		=	"administrador"	;
	$id_tabla	=	"id_admin";
	}
	if($tipo == "s"){
	$buscar		=	"supervisor";			
	$id_tabla	=	"id_supervisor";
	}
	$res	= tabla_muestra($id,$buscar,$id_tabla);
		
		echo $res[1];
		
	//	echo $dSupervisor['nombre'];
	
	}


}?></a>
</td>
<? if($listarRecibidos) {?> <? } else { ?><td width="2%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=reenviar&<?=$indice?>=<?=$d['id_mensaje']?>&grupo=<?=$d['grupo']?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0" class="Tips4" title="Reenviar Mensaje ::"></a></td><? } ?>
<td width="2%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=<? if(isset($_SESSION['id_admin'])) echo "eliminar"; else echo "eliminarSupervisor"; ?>&id_mensaje=<?=$d['id_mensaje']?>&accion2=<?=$_GET['accion']?>&grupo=<?=$d['grupo']?>" onClick="javascript: return confirm('Realmente deseas eliminar a este mensaje?');"><img src="<?=IMAGEN_ELIMINAR?>" class="Tips4" title="Eliminar Mensaje ::" border="0"></a></td>
</tr>

	<?php } 
	
if($listarRecibidos){
  $qListarPag	=	"SELECT * FROM $tabla WHERE para = '".$usuarios."' AND tipo2 = '".$tiposs."' ";
 if(!isset($_SESSION['id_admin']))  $qListarPag	.=	"AND desactivado = 0 ";
}
if($listarEnviados){
 $qListarPag	=	"SELECT * FROM $tabla  WHERE de = '".$usuarios	."' AND tipo = '".$tiposs."' ";
  if(!isset($_SESSION['id_admin']))  $qListarPag	.=	"AND desactivado = 0 ";
}
	$res_total		=	mysql_query($qListarPag) OR die("<p align=\"center\">$qListarPag<font color=\"#990000\">Error al ejecutar la consulta, por favor inténtelo más tarde.</font></p><p>".mysql_error()."</p>");
	$total 		    = 	mysql_num_rows($res_total);
	
		
	?>
                  <tr>
                  	<td colspan="7">
                    	<table width="100%">
                        	<tr>
                                <td width="20%">
                                <?php if( ($indice2 - $lapso) >= 0 ) { ?>
                                <a class="style7" href="<?=$_SERVER['PHP_SELF']."?seccion=".$_GET['seccion']."&accion=".$_GET['accion']."&indice2=".($indice2 - $lapso)?>">&lt;Anterior</a>
                                <?php } ?>
                                </td>
                                <td width="60%" align="center" class="style7">P&aacute;gina
                                <?php
                                for($i=0, $temp=0; $temp < $total; $i++, $temp+=$lapso)
                                {
                                    if($temp != $indice2) {
                                ?>
                                <a href="<?=$_SERVER['PHP_SELF']."?seccion=".$_GET['seccion']."&accion=".$_GET['accion']."&indice2=".$temp?>" class="style7"><?php } ?><?=$i+1?><?php if($temp != $indice2) { ?></a><?php } ?>
                                <?php
                                }
                                ?></td>
                                <td width="20%">
                                <?php if( ($indice2 + $lapso) < $total ) { ?>
                                <a class="style7" href="<?=$_SERVER['PHP_SELF']."?seccion=".$_GET['seccion']."&accion=".$_GET['accion']."&indice2=".($indice2 + $lapso)?>">Siguiente&gt;</a>
                                <?php } ?>
                                </td>
                            </tr>
                         </table>
                      </td>
                  </tr>    
   <tr>
   		<td colspan="8">&nbsp;</td>
   </tr> 
   
  
	</table>
<blockquote>
  <p><br />
    <?php } else { ?>
  </p>
</blockquote>
<table align="center" width="54%">
<tr>
		<td class="style7" align="center"><p><br />
	      <br />
	      <br />
    No hay mensajes</p>
		  <p><br />
          </p></td>
  </tr>

  </table></td></tr></table>
	      <?php } ?>
	</div>
</div>
<?php } if($centro){ 



?>
<div id="content">
	<div id="datosgenerales" style="background-color:#FFFFFF;">	
  		<table width="100%" align="center">
          <tr>
        <td width="2%" valign="top">&nbsp;</td>
        <td width="20%" valign="top">
   				<table width="100%" cellpadding="0" cellspacing="0" align="left">
                	<tr>
                    	<td align="center"><img src="images/mensajeria.jpg" border="0" usemap="#mensajes" /></td>
                    </tr>
                </table>
        </td>
        
        <td width="2%" valign="top">&nbsp;</td>
        
        <td width="60%" valign="top">
<table width="100%" cellpadding="0" cellspacing="0">
 					<? 
					$qMensajes	=	"SELECT * FROM  mensajeria  WHERE checado = 0 AND para = '".$usuarios."' AND tipo2 = '".$tipo."' ORDER BY fecha ASC LIMIT 10 ";
					$rMensajes	=	mysql_query($qMensajes);

					$qResto	=	"SELECT * FROM  mensajeria  WHERE checado = 0 AND para = '".$usuarios."' AND tipo2 = '".$tipo."' ORDER BY fecha ASC";
					$rResto	=	mysql_query($qResto);
										
					$nMensajes	=	mysql_num_rows($rMensajes);
					$nResto	=	mysql_num_rows($rResto);?>
        		<tr>
                	<td colspan="3"><br /><h3>Centro de Mensajes </h3></td>
        		</tr>
                <tr>
                	<td colspan="3" class="style5" align="right" style="font-size: 10px;">Mostrando <?=$nMensajes?> de <?=$nResto?> mensajes</td>
                </tr>
               <? if($nMensajes < 1){ ?>
              <tr>
              	<td colspan="3" align="center" style="color:#006699"><br /><br />Usted no tiene mensajes nuevos<br /><br /><br /></td>
              </tr>
              <? } else { ?>
  			  <tr>
                	<td width="20%"><strong>Fecha</strong></td>
              <td width="69%"><strong>Titulo</strong></td>
              <td width="11%">&nbsp;</td>
              </tr>
                <? for($a = 0; $dMensajes	=	mysql_fetch_assoc($rMensajes); $a++){?>
                <tr height="20" <? if (bcmod($a, 2) == 0) echo " bgcolor='#DDDDDD'"; else echo "";?>>
                	<td class="style5"><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dMensajes['fecha'])?></td>
                	<td class="style5"><b><?=htmlspecialchars($dMensajes['titulo']);?></b></td>
                	<td><a href="<?=$_SERVER['PHP_SELF']?>?seccion=35&accion=ver&checado=1&id_mensaje=<?=$dMensajes['id_mensaje']?>&actualiza">Leer</a></td>
                </tr>
                 <? }  
				 }?>
        	</table>
        </td>
                <td width="2%" valign="top">&nbsp;</td>

          </tr>
        </table>  
	</div>
</div>

<? } ?>
        
        
         
<map name="mensajes" id="mensajes">
		<area shape="rect" coords="27,33,169,55" href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listarRecibidos"  class="Tips4" title="BANDEJA DE ENTRADA :: Vea sus mensajes recibidos." />
		<area shape="rect" coords="25,74,168,98" href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listarEnviados"  class="Tips4" title="BANDEJA DE SALIDA :: Vea sus mensajes enviados." />
		<area shape="rect" coords="25,116,168,144" href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=nuevo"  class="Tips4" title=" MENSAJE NUEVO:: Redacte y envie un mensaje nuevo."  />
</map>