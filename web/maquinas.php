<?php
//if( $_GET['listarbd'])
//	echo "listar BD";

$tabla	=	"maquina";
//$tabla2 = "maquina_bd";
$indice	=	"id_maquina";

//print_r($_SESSION);
/*
  //$ejecuta = "delete from pre_nomina_calificada where semana=43 and year(desde) = 2014";
  $ejecuta = "delete from asistencias where  year(fecha)=2014 and semana=43";
   $rdatos =mysql_query($ejecuta);
   if(!$rdatos){
     echo "Sí se agrego";
     }
     else 
      echo  "Se modificaron ".$rDatos. " Registros";
   */  

//$ejecuta = "update resumen_maquina_ex set densidad =1";
 

//$crearReg="insert into maquina_bd(numero,marca,area,lineas,serie,tipo_d) values(18,'PRUEBA',1,2,'HHFFSS',2)";

if(!empty($_POST['submit']))
{
	$campos		=	describeTabla($tabla,$indice);
	//$campos2		=	describeTabla($tabla2,$indice);
	$query			=	"";
	//$query2			=	"";
	
	if(	isset($_POST[$indice]) )
		$id = $_POST[$indice] = intval( $_POST[$indice] );
	else
	{
		$qMaxId		=	"SELECT MAX($indice)+1 FROM $tabla";
		$rMaxId		=	mysql_query($qMaxId) OR die("<p>$rMaxId</p><p>".mysql_error()."</p>");
		list($id)	=	mysql_fetch_row($rMaxId);
		$id			=	(empty($id))?1:$id;
		$query		.=	"$indice=$id";
	}

	$query		=	"";
	foreach($campos as $clave)
		if(isset($_POST[$clave]))
			$query		.=	(($query=="")?"":",")."$clave='".$_POST[$clave]."'";
	//foreach($campos2 as $clave)
	//	if(isset($_POST[$clave]))
	//		$query2		.=	(($query2=="")?"":",")."$clave='".$_POST[$clave]."'";


	if(!empty($_POST[$indice])){
 	  $query		=	"UPDATE $tabla SET ".$query." WHERE ($indice=".$_POST[$indice].")";
	  echo $res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	  $redirecciona	=	true;
	  //$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=mostrar&$indice=$id";
	  $ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";
	}

	
	if(empty($_POST[$indice]))
	{

		$numero 	= $_REQUEST['numero'];
		$area	 	= $_REQUEST['area'];
		
		$qCheca = "SELECT id_maquina FROM maquina WHERE area = $area AND numero = $numero";
		$rCheca = mysql_query($qCheca);
	    $dCheca = mysql_num_rows($rCheca);
		
		if($dCheca > 0){
			$redirecciona	=	true;
			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=nuevo&error=1";
			
		}
		else
		{

			$query		=	"INSERT INTO $tabla SET $query"; //$Tabla2 es $tabla
			
			$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
			$redirecciona	=	true;
			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";

		}					
	}	
			
}
//echo "$ruta";
//echo "$query";

$listar =	true;
if(!empty($_GET['accion']))
{
	$listar		=	($_GET['accion']=="listar")?true:false;
	//$listarbd   = 	($_GET['accion']=="listarbd")?true:false;
	$nuevo		=	($_GET['accion']=="nuevo")?true:false;
	//$nuevobd    =	($_GET['accion']=="nuevobd")?true:false;
	if(!empty($_GET[$indice]) && is_numeric($_GET[$indice]) )
	{
		$mostrar	=	($_GET['accion']=="mostrar")?true:false;
		$eliminar	=	($_GET['accion']=="eliminar")?true:false;
		$modificar	=	($_GET['accion']=="modificar")?true:false;
	}
	if($mostrar || $modificar)
	{	
		$qDatos	=	"SELECT * FROM $tabla WHERE ($indice={$_GET[$indice]})";
		$rDatos	=	mysql_query($qDatos) OR die("<p>$qDatos</p><p>".mysql_error()."</p>");
		$dDatos	=	mysql_fetch_assoc($rDatos);
	}

	if($eliminar)
	{
		$qEliminar	=	"DELETE FROM $tabla WHERE ($indice={$_GET[$indice]}) LIMIT 1";
		$rEliminar	=	mysql_query($qEliminar) OR die("<p>$qEliminar</p><p>".mysql_error()."</p>");
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";
	}
}
?>
<?php if($nuevo || $modificar) { ?>
 
<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post" enctype="multipart/form-data" name="form" onsubmit="Verifica();" >
<br />
<br />
<table border="0" align="center" width="45%">
<? if($_GET['error'] == 1){ ?>
<tr>
  <td align="center" colspan="2" class="style5"><font color="#FF0000"> <br />
    No. de Maquina repetido en la misma area,<br /> 
    favor de cambiarlo<br />
  <br />
</font></td></tr>
<? } ?><tr>

	<td width="45%" align="right" class="style7">No Maquina:</td>

	<td width="55%" align="left"><input type="text" name="numero" id="numero" class="style5"  value="<?=($modificar)?$dDatos['numero']:""?>"></td>
</tr>

<tr>

	<td align="right" class="style7">Marca:</td>

	<td align="left"><input type="text" name="marca" id="marca" class="style5"  value="<?=($modificar)?$dDatos['marca']:""?>"></td>
</tr>

<tr>

	<td align="right" class="style7">&Aacute;rea</td>

	<td align="left">
	<select name="area" type="text" id="area" class="style5">
	<option value="1" <? if($modificar && $dDatos['area'] == 1){ echo "selected"; } ?>>Camiseta</option>
	<option value="5" <? if($modificar && $dDatos['area'] == 5){ echo "selected"; } ?>>R.P.S.</option>	
	<option value="6" <? if($modificar && $dDatos['area'] == 6){ echo "selected"; } ?>>S.F.</option>		
	<option value="2" <? if($modificar && $dDatos['area'] == 2){ echo "selected"; } ?>>Impresión</option>
	<option value="3" <? if($modificar && $dDatos['area'] == 3){ echo "selected"; } ?>>Linea de Impresion</option>
    <option value="4" <? if($modificar && $dDatos['area'] == 4){ echo "selected"; } ?>>Extruder</option>
    </select>    
    </td>
</tr>
<tr>
	<td align="right" class="style7">No. de Serie.</td>
    <td align="left"><input type="text" name="serie" id="serie" class="style5"  value="<?=($modificar)?$dDatos['serie']:""?>"  /></td>
<tr>
  <td align="right" class="style7">No. de L&iacute;neas:</td>
  <td align="left"><p>
    <select name="lineas" type="text" id="lineas" class="style5">
      <option value="1" <? if($modificar && $dDatos['lineas'] == 1){ echo "selected"; } ?>>1</option>
      <option value="2" <? if($modificar && $dDatos['lineas'] == 2){ echo "selected"; } ?>>2</option>
      <option value="3" <? if($modificar && $dDatos['lineas'] == 3){ echo "selected"; } ?>>3</option>
      <option value="4" <? if($modificar && $dDatos['lineas'] == 4){ echo "selected"; } ?>>4</option>
      <option value="5" <? if($modificar && $dDatos['lineas'] == 5){ echo "selected"; } ?>>5</option>
    </select>
  </p></td>
</tr>
<tr>
  <td align="right" class="style7">Densidad:</td>
  <td align="left"><p>
    <select name="tipo_d" type="text" id="densidad" class="style5">
      <option value="1" <? if($modificar && $dDatos['tipo_d'] == 1){ echo "selected"; } ?>>HD</option>
      <option value="2" <? if($modificar && $dDatos['tipo_d'] == 2){ echo "selected"; } ?>>BD</option>
    </select>
  </p></td>
</tr>

</tr>

<tr>

	<td colspan="2" align="right">

		<?php if($modificar) { ?>

		<input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>" />

		<?php } ?>
        <input type="button" name="cancel" class="style7" value="Cancelar" onclick="javascript: history.go(-1);">	
		<input type="submit" name="submit" class="style7" value="Guardar" >	</td>
</tr>
</table>

<p>
  <?php } ?>

  <? if($mostrar){?>
</p>
</form>


<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post" enctype="multipart/form-data" name="form" onsubmit="Verifica();" >
<br />
<br />
<table align="center" width="45%">

<tr>

	<td width="45%" align="right" class="style7">No Maquina:</td>

	<td width="55%" align="left" class="style5"><fieldset><?=$dDatos['numero']?></fieldset></td>
</tr>

<tr>

	<td align="right" class="style7">Marca:</td>

	<td align="left" class="style5"><fieldset><?=$dDatos['marca']?></fieldset></td>
</tr>


<tr>

	<td align="right" class="style7">&Aacute;rea</td>

	<td align="left" class="style5"><fieldset>
	<? if($dDatos['area'] == 1){ echo "Camiseta"; } ?>
	<? if($dDatos['area'] == 5){ echo "R.P.S."; } ?>
	<? if($dDatos['area'] == 6){ echo "S.F."; } ?>
	<? if($dDatos['area'] == 2){ echo "Impresión"; } ?>
	<? if($dDatos['area'] == 3){ echo "Lineas de Impresión"; } ?>
    <? if($dDatos['area'] == 4){ echo "Extruder"; } ?>
	</fieldset> 
   </td>
</tr>
<tr>
	<td align="right" class="7">No. de Serie:</td>
    <td align="left" class="5"><fieldset><?=$dDatos['serie']?></fieldset></td>
</tr>
<tr>

  <td align="right" class="style7">No. de L&iacute;neas:</td>

  <td align="left" class="style5"><fieldset>
	<? if($dDatos['lineas'] == '1' ){ echo"una"; } ?>
	<? if($dDatos['lineas'] == '2' ){ echo"dos	"; } ?>
    </fieldset>
    </td>
</tr>
<tr>
  <td align="right" class="style7">Densidad:</td>
  <td align="left"><p>
    <select name="densidad" type="text" id="densidad" class="style5">
      <option value="1" <? if($modificar && $dDatos['lineas'] == 1){ echo "selected"; } ?>>HD</option>
      <option value="2" <? if($modificar && $dDatos['lineas'] == 2){ echo "selected"; } ?>>BD</option>
    </select>
  </p></td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="admin.php?seccion=1&accion=modificar&<?=$indice?>=<?=$dDatos['id_maquina']?>" class="style7">Editar</a></td>
</tr>
</table>

<br />
</form>
<? } ?>

<?php if($listar) {
     //if($listar) echo "HD"; 
     //if($listarbd) echo "BD"; 
     
	 if($listar) //lista HD
	   $qListar1	= "SELECT id_maquina,numero, marca, area, tipo_d FROM maquina ORDER BY area,numero ASC ";
	 //if($listarbd) //lista BD
	  // $qListar1	= "SELECT id_maquina,numero, marca, area, tipo_d FROM maquina_bd ORDER BY area,numero ASC ";
	 
	  //Busca en tabla maquina, que por default es HD
	  if(isset($_REQUEST['buscar']))
	   if($_REQUEST['area']>0) 
           $qListar1= "SELECT id_maquina,numero, marca, area, tipo_d FROM maquina WHERE area = ".$_REQUEST['area']." ORDER BY area, numero ASC ";
       else
       	    $qListar1= "SELECT id_maquina,numero, marca, area, tipo_d FROM maquina  ORDER BY area, numero ASC ";

      //Busca en Baja densidad tabla maquina_bd 
      //if(isset($_REQUEST['buscarbd'])) 
    //       $qListar1= "SELECT id_maquina,numero, marca, area, tipo_d FROM maquina_bd WHERE area = ".$_REQUEST['area']." ORDER BY area, numero ASC "; 
	  $rListar1	= mysql_query($qListar1);
	
if(!$rListar1)
	die("<p>$query</p><p>".mysql_error()."</p>");
		
if(mysql_num_rows($rListar1) == '0'){
				
	echo " <tr>
        <td  valign='top' colspan='6' class='style2' align='center'>NO SE ENCONTRARON REGISTROS</td>
         </tr>";
		}
		else {
	
	?>
<br />
<table border="0" align="center" width="51%" cellpadding="0" cellspacing="0">

<tr>
<td colspan="4" align="center">
			<span class="titulos"><br />
			&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7">Nueva Maquina</a>
			<span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br /><br />
		</td>

</tr>

<tr>
	<td colspan="4"><table>
      <tr>
        <td colspan="2" align="left" valign="middle">
        	<form action="admin.php?seccion=<?=$_REQUEST['seccion']?>" method="post" name="busca" id="busca">
              <select name="area" class="style1" id="area">
              	<option value="0"<? if(isset($_REQUEST['area']) && $_REQUEST['area']==0)echo "selected"?>>Todas</option>
              	<option value="4"<? if(isset($_REQUEST['area']) && $_REQUEST['area']==4)echo "selected"?>>Extruder</option>
                <option value="1" <? if(isset($_REQUEST['area']) && $_REQUEST['area']==1)echo "selected"?> >Camiseta</option>
                <option value="5" <? if(isset($_REQUEST['area']) && $_REQUEST['area']==5)echo "selected"?> >R.P.S.</option>
                <option value="6" <? if(isset($_REQUEST['area']) && $_REQUEST['area']==6)echo "selected"?> >S.F.</option>
                <option value="2" <? if(isset($_REQUEST['area']) && $_REQUEST['area']==2)echo "selected"?>>Impresion</option>
                <option value="3" <? if(isset($_REQUEST['area']) && $_REQUEST['area']==3)echo "selected"?>>Linea de Impresion</option>               
              </select>
              <input name="buscar" type="submit" class="style1" id="buscar" value="Buscar"> 
                </form></td>
  </tr>
  </table></td>
  </tr>
  <tr>
	<td width="47" class="style7"><h3>No.</h3></td>
    <td width="124" class="style7"><h3>Marca</h3></td>
    <td width="160" class="style7"><h3>Area</h3></td>
	<td width="80" class="style7"><h3>Dens.</h3></td>
	<td width="50" class="style7" colspan="3"><h3>&nbsp;</h3></td>
  </tr>
		<?php for($a=0;$dListar	=	mysql_fetch_row($rListar1);$a++) { ?>
		<tr <?=(bcmod($a,2) == 0)?"bgcolor='#DDDDDD'":""?>>
			<td class="style5"><?=$dListar[1]?></td>
			<td class="style5"><?=$dListar[2]?></td>
    		<td class="style5"><?
            if($dListar[3] == 1) echo "Camiseta";
            if($dListar[3] == 5) echo "R.P.S.";            
            if($dListar[3] == 6) echo "S.F.";            
			if($dListar[3] == 2) echo "Impresión";
			if($dListar[3] == 3) echo "Linea de impresión";
			if($dListar[3] == 4) echo "Extruder";?></td>
			<!-- bd f30 y hd 09c -->
			<td style =<? if($dListar[4]==1) echo"color:#f30;"; else echo"color:#09c;";?>><?if($dListar[4]==1) echo "HD"; else if($dListar[4]==2) echo "BD";  ?></td>        
            <td width="2%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&amp;<?=$indice?>=<?=$dListar[0]?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>
		 	<td width="2%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&amp;<?=$indice?>=<?=$dListar[0]?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>
		  	<td width="2%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&amp;<?=$indice?>=<?=$dListar[0]?>" onclick="javascript: return confirm('Realmente deseas eliminar a esta maquina?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
  </tr>

		<?php } ?>
   	<tr>
		<? if($listar) { ?>
		<td colspan="7" align="center">
			<span class="titulos"><br />
			&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7">Nueva Maquina</a>
			<span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br />
		</td>
		<? }  ?>
           

  </tr>
	</table>
<br />
	
<?php 
}
} ?>
