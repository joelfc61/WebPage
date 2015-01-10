<?php

$indice	=	"id_producto";	
$tabla	=	"productos";
	
	 $kg_mi = (((($_REQUEST['ancho'] + $_REQUEST['fuelle']) * $_REQUEST['largo']) * $_REQUEST['densidad']) * $_REQUEST['calibre']);
	
$campos	=	describeTabla($tabla,$indice);

if(!empty($_POST['submit']))
{
    
	if(	isset($_POST[$indice]) ) $id = intval( $_POST[$indice] );
	else
	{
		$query		=   "SELECT MAX($indice) FROM $tabla";
		$res_id		=	mysql_query($query);
		$next_id	=	mysql_fetch_row($res_id);
		$id			=	$next_id[0]+1;
	}
	
	if($_REQUEST['unidad'] == 1) 
		$codigo = "402-";
	if($_REQUEST['unidad'] == 2)
		$codigo = "401-";
		
	if($_REQUEST['densidad'] == .47)	
		$codigo .=	"01-";
	if($_REQUEST['densidad'] == .49)	
		$codigo .=	"02-";
				
	//CAMISETA
	if($_REQUEST['tipo'] == 1){
		if($id < 100 )
			$pre	 = "0000";
		if($id < 1000 && $id >= 100)
			$pre	 = "000";
		if($id < 10000 && $id >= 1000)
			$pre	 = "00";

	 	$codigo .=	"$pre$id";
	}
		
	//BOLSA PLANA
	if($_REQUEST['tipo'] == 2)	{
		$numero 	 =	$id + 20000;
		$codigo 	.=	"$numero";
	}	
	//SACO
	if($_REQUEST['tipo'] == 3)	{
		$numero 	 =	$id + 30000;
		$codigo 	.=	"$numero";
	}
	//BOLSA LARGA
	if($_REQUEST['tipo'] == 4)	{
		$numero 	 =	$id + 40000;
		$codigo 	.=	"$numero";
	}
	//ROLLO TUBULAR
	if($_REQUEST['tipo'] == 5)	{
		$numero 	 =	$id + 60000;
		$codigo 	.=	"$numero";	
	}
	//PELICULA_PLANA
	if($_REQUEST['tipo'] == 6)	{
		$numero 	 =	$id + 70000;
		$codigo 	.=	"$numero";
	}	
	$descripcion = addslashes(htmlspecialchars($_REQUEST['descripcion']));
	if($_REQUEST['color_material'] == "")
	$color = "";
	else
	$color = $_REQUEST['color_material'];
	

	if(!empty($_POST[$indice])){
	
		$query		=	"UPDATE $tabla SET 
							unidad 		= '{$_REQUEST['unidad']}', 
							densidad 	= '".$_REQUEST['densidad']."', 
							p 			= '{$_REQUEST['p']}',
							x 			= '{$_REQUEST['x']}', 
							ccc 		= '{$_REQUEST[ccc]}', 
							pigmento 	= '{$_REQUEST['pigmento']}',
							color 		= '".$color."', 
							descripcion = '".$descripcion."', 
							sello 		= '{$_REQUEST['sello']}',
							ancho 		= '{$_REQUEST['ancho']}',
							largo 		= '{$_REQUEST['largo']}',
							fuelle 		= '{$_REQUEST['fuelle']}',
							calibre		= '{$_REQUEST['calibre']}',
							codigo 		= '".$codigo."',
							tipo		= '{$_REQUEST['tipo']}',
							kg_mi		= '".$kg_mi."'
						 WHERE ($indice=".$_POST[$indice].")";
	
	}
		
	else
	
	{ 
	
echo	$query		=	"INSERT INTO $tabla (sello, unidad, densidad, p, x, ccc, pigmento, color, descripcion, ancho, largo, fuelle, calibre, codigo, tipo, kg_mi)".
					" VALUES (	
								'{$_POST['sello']}',
								'{$_POST['unidad']}',
								'".$_REQUEST['densidad']."',
								'{$_POST['p']}',
								'{$_POST['x']}',
								'{$_POST['ccc']}',
								'{$_POST['pigmento']}',
								'".$color."',
								'".$descripcion."',
								'{$_POST['ancho']}',
								'{$_POST['largo']}',
								'{$_POST['fuelle']}',
								'{$_POST['calibre']}',
								'".$codigo."',
								'".$_REQUEST['tipo']."',
								'".$kg_mi."')";
	}
	
	
	$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	
	
	$redirecciona	=	true;
	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin_vendedores.php?seccion={$_GET[seccion]}&accion=listar";
}

if(!empty($_GET['accion']))
$listar =	true;
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" version=
"-//W3C//DTD XHTML 1.1//EN" xml:lang="en">
<head>
	<script type="text/javascript" src="mootools.js"></script>
	<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Bolseo</title>
	<style type="text/css" media="screen">@import 'style.css';</style>
</head>

<script type="text/javascript" src="funciones.js"></script>
<script language="javascript">

var win=null;
function NewWindow(mypage,myname,w,h,scroll,pos){
   if(pos=="random"){
      LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;
	  TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;
	  }

   if(pos=="center"){
      LeftPosition=(screen.width)?(screen.width-w)/2:100;
	  TopPosition=(screen.height)?(screen.height-h)/2:100;
	  }

   else if((pos!="center" && pos!="random") || pos==null){
      LeftPosition=0;TopPosition=20
	  }

   settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
   win=window.open(mypage,myname,settings);

}

</script>
</head>
<body>
<?php if($nuevo || $modificar) { ?>
<form name="pedidos" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
<br /><br />
<div id="container">
  <div id="content">
	  <div id="datosgenerales" style="background-color:#FFFFFF;">
<table align="center">
                    <tr>
                    	<td><p class="titulos">PRODUCTOS</p></td>
                    </tr>
        </table>
            <br />
          <table width="530" align="center">
	      <tr>
              	<td colspan="4" align="center" bgcolor="0A4662" class="fecha">Impresi&oacute;n</td>
            </tr> 
     </table>
              <table width="529" align="center">
        <tr>
        	<td>Tipo:</td>
            <td colspan="4">
            	<select name="tipo">
            		<option value="1" <? if($_REQUEST['tipo'] == '1') echo "selected";?>>Camiseta</option>
            		<option value="2" <? if($_REQUEST['tipo'] == '2') echo "selected";?>>Bolsa Plana</option>
            		<option value="3" <? if($_REQUEST['tipo'] == '3') echo "selected";?>>Saco</option>
            		<option value="4" <? if($_REQUEST['tipo'] == '4') echo "selected";?>>Bolsa Larga</option>
            		<option value="5" <? if($_REQUEST['tipo'] == '5') echo "selected";?>>Rollo Tubular</option>
            		<option value="6" <? if($_REQUEST['tipo'] == '6') echo "selected";?>>Pelicula plana</option>
                </select>            </td>
         </tr>
<tr>
            <td width="56" align="right">Unidad:&nbsp;&nbsp;</td>
<td width="461"><select name="unidad">
                            <option value="1">Kilogramos</option>
                            <option value="2">Millares</option>
                    </select></td>
</tr>            
          </table>          
          <br />

        <table width="532" align="center">
              <tr>
              	<td align="center" colspan="4" bgcolor="#0A4662" class="fecha">Material</td>
              </tr>
              <tr>
              	<td align="center" width="50%" >Resina</td>
                <td align="center" width="50%" >Pigmento</td>
              </tr>
              <tr>
              	<td>
   	  <table align="center" width="100%">
                    	<tr>
                        	<td width="17%">B.D.</td>
                          	<td width="33%"><input type="radio" name="densidad" value=".47" /></td>
                            <td width="15%">A.D.</td>
                          <td width="35%"><input type="radio" name="densidad" value=".49" /></td>
                      </tr>
                    	<tr>
                        	<td colspan="1" width="17%">P</td>
                          	<td colspan="1" width="33%"><input type="checkbox" name="p" value="1" /></td>
                       </tr>
                    	<tr>
                        	<td colspan="1" width="17%">X</td>
                          	<td colspan="1" width="33%"><input type="checkbox" name="x" value="1" /></td>
                       </tr>
                    	<tr>
                        	<td colspan="1" width="17%">004</td>
                          	<td colspan="1" width="33%"><input type="checkbox" name="ccc" value="1" /></td>
                       </tr>                
				  </table>                </td> 
              	<td valign="top">
<table align="center" width="100%">

                    	<tr>
                        	<td colspan="1" width="25%">Natural:</td>
                       	  <td colspan="1" width="16%"><input type="radio" name="pigmento" value="1" /></td>
                      </tr>
                    	<tr>
                        	<td colspan="1" width="25%">Color:</td>
                       	  	<td colspan="1" width="16%"><input type="radio" name="pigmento" value="2" /></td>
						  	<td width="59%"><input type="text" name="color_material" size="18">                       </tr>
				  </table>                </td> 
              </tr>		  
        </table>    
		  <table width="531" align="center">
			<tr>
				<td align="center" colspan="4" bgcolor="#0A4662" class="fecha">Producto</td>
            </tr>
            <tr>
            	<td width="76">Descripcion:</td>
           	  <td width="218"><textarea rows="3" cols="20" name="descripcion"></textarea></td>
           	  <td width="35">Sello:</td>
           	  <td width="218"><textarea rows="3" cols="20" name="sello"></textarea></td>
            </tr>
		  </table>
            <table width="529" align="center">
			<tr>
            	<td width="280" align="center">Medidas</td>
            	<td width="237" align="center">Calibre</td>
            </tr>
          	<tr>
            	<td width="280">
   	    		  <table align="center" width="100%">
                        <tr>
                            <td width="27%">Ancho: </td>
                            <td width="73%"><input type="text" name="ancho" /></td>
                        </tr>
                        <tr>
                        	<td>Fuelle: </td>
                            <td><input type="text" name="fuelle" /></td>
                        </tr>
                        <tr>
                        	<td>Largo: </td>
                            <td><input type="text" name="largo" /></td>
                        </tr>
                  </table></td>
   					<td valign="top" align="center">
                	<table align="center" width="100%" >
                    	<tr>
                        	<td width="100%" align="center"><input type="text" name="calibre" /></td>
                        </tr>
                    </table>                
                 </td>
            </tr>
          </table>
          <br />
  
        <table align="center" width="529">
			  <tr>
  				  <td class="contenidos" align="right"><?php if($modificar) { ?>
                    <input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>" />
                    <?php } ?>
                    <?php if($nuevo) { ?>
                    <input type="hidden" name="status" value="0" />
                    <?php } ?>
                    <input name="submit" type="submit" class="style4" value="Guardar" />    </td>
              </tr>
        </table>
      </div>
	</div>
</div>
</form>
<?php } ?>

<?php if($mostrar) { ?>
<br /><br />
<div id="container">
  <div id="content">
	  <div id="datosgenerales" style="background-color:#FFFFFF;">
<table align="center">
                    <tr>
                    	<td><p class="titulos">PRODUCTOS</p></td>
                    </tr>
        </table>
            <br />
            <br />
          <table width="530" align="center">
	      <tr>
              	<td colspan="4" align="center" bgcolor="0A4662" class="fecha">Impresi&oacute;n</td>
            </tr> 
     </table>
              <table width="529" align="center">
        <tr>
        	<td>Tipo:</td>
          <td colspan="4" class="style7"><? 
		  		
				if($dato['tipo'] == '1')
					echo "Camiseta";
				if($dato['tipo'] == '2')
					echo "Bolsa Plana";
				if($dato['tipo'] == '3')
					echo "Saco";
				if($dato['tipo'] == '4')
					echo "Bolsa Larga";
				if($dato['tipo'] == '5')
					echo "Rollo Tubular";
				if($dato['tipo'] == '6')
					echo "Pelicula Plana";
				 ?></td>
         </tr>
<tr>
          <td width="56" align="right">Unidad:&nbsp;&nbsp;</td>
                <td width="461" class="style7">
                	<? 
					if($dato['unidad'] == 1){
					echo "Kilogramos";
					}
					if($dato['unidad'] == 2){
					echo "Millares";
					}
					?></td>
</tr>            
        </table>          
          <br />
        <table width="532" align="center">
              <tr>
              	<td align="center" colspan="4" bgcolor="#0A4662" class="fecha">Material</td>
              </tr>
              <tr>
              	<td align="center" width="50%" >Resina</td>
                <td align="center" width="50%" >Pigmento</td>
              </tr>
              <tr>
              	<td>
<table align="center" width="100%">
                    	<tr><? if($dato['densidad'] == 0.47 ){?>
                        	<td width="17%">B.D.</td>
                          	<td width="33%" class="style7"><?=$dato['densidad']?></td>
                            <? } if($dato['densidad'] == 0.49){ ?>
                          	<td width="15%">A.D.</td>
                            <td width="35%" class="style7"><?=$dato['densidad']?></td>
                            <? } ?>
                    	</tr>
                    	<tr>
                        	<td colspan="1" width="17%">P</td>
                          	<td colspan="1" width="33%" class="style7"><? if($dato['p'] == 1) echo "SI"; else echo "No";?></td>
                    	</tr>
                    	<tr>
                        	<td colspan="1" width="17%">X</td>
                          	<td colspan="1" width="33%" class="style7"><? if($dato['x'] == 1) echo "Si"; else echo "No";?></td>
                    	</tr>
                    	<tr>
                        	<td colspan="1" width="17%">004</td>
                          	<td colspan="1" width="33%" class="style7"><? if($dato['ccc'] == 1) echo "Si"; else echo "No";?></td>
                    	</tr>                
				  </table>                </td> 
              	<td valign="top">
<table align="center" width="100%">
						<? if($dato['pigmento'] == 1){ ?>
                    	<tr>
                        	<td colspan="2" align="center" class="style7"><b>Natural</b></td>
                    	</tr>
                        <? } if($dato['pigmento'] == 2){  ?>
                    	<tr>
                        	<td colspan="1" width="22%">Color:</td>
                       	  	<td colspan="1" width="78%"><?=$dato['color']?></td>
                        <? } ?>                    	
           	  </tr>
				  </table>                </td> 
              </tr>		  
        </table>    
		  <table width="531" align="center">
			<tr>
				<td align="center" colspan="4" bgcolor="#0A4662" class="fecha">Producto</td>
            </tr>
            <tr>
            	<td width="76">Descripcion:</td>
           	    <td width="218" class="style7"><?=$dato['descripcion']?></td>
           	    <td width="35">Sello:</td>
           	  <td width="218" class="style7"><?=$dato['sello']?></td>
            </tr>
		  </table>
              <br />		  
            <table width="529" align="center">
			<tr>
            	<td width="280" align="center">Medidas</td>
            	<td width="237" align="center">Calibre</td>
            </tr>
          	<tr>
            	<td width="280">
   	    		  <table align="center" width="100%">
                        <tr>
                            <td width="27%">Ancho: </td>
                            <td width="73%" class="style7"><?=$dato['ancho']?></td>
                        </tr>
                        <tr>
                        	<td>Fuelle: </td>
                            <td class="style7"><?=$dato['fuelle']?></td>
                        </tr>
                        <tr>
                        	<td>Largo: </td>
                            <td class="style7"><?=$dato['largo']?></td>
                        </tr>
                  </table></td>
   					<td valign="top" align="center">
                	<table width="100%" align="center">
               	  <tr>
                        	<td width="100%" align="center" class="style7"><?=$dato['calibre']?></td>
                    	</tr>
                    </table>                
                 </td>
            </tr>
        </table>
          <br />
    <table align="center" width="529" >
			  <tr>
  				  <td class="contenidos" align="right">Codigo: <span class="style7"><?=$dato['codigo']?></span> </td>
                  <td>&nbsp;</td>
              </tr>
        </table>
        <table align="center" width="529" >
			  <tr>
  				  <td class="contenidos" align="right">&nbsp;<!---- <a href="admin_vendedores.php?seccion=<?=$_REQUEST['seccion']?>&accion=modificar&tipos=<?=$_REQUEST['tipos']?>&id_producto=<?=$dato['id_producto']?>">Editar</a> ---> </td>
              </tr>
        </table>
    </div>
  </div>
</div>
<?php }  ?>
<?php if($listar) { ?>
<br>
<div id="container">
		<div id="content">
        
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#camiseta">CAMISETA</a></h3>
		<div class="accordion">
            <div style="background-color:#FFFFFF;" class="navcontainer">
				<p>&nbsp;</p>
				<ul class="navlist">
 		        <?php
				$qCamiseta		=	"SELECT * FROM productos WHERE tipo = 1 ORDER BY id_producto ASC";
				$rCamiseta		=	mysql_query($qCamiseta) OR die("<p>$qCamiseta</p><p>".mysql_error()."</p>");
				$nCamiseta 		= 	mysql_num_rows($rCamiseta);
				if($nCamiseta > 0){
					while($dCamiseta	=	mysql_fetch_assoc($rCamiseta)){ ?>
                    <li><a href="admin_vendedores.php?seccion=<?=$_REQUEST['seccion']?>&tipos=camiseta&accion=mostrar&id_producto=<?=$dCamiseta['id_producto']?>"><? echo "Codigo:" .$dCamiseta['codigo']."  Medidas: ".$dCamiseta['ancho']." x ".$dCamiseta['largo']." x ".$dCamiseta['fuelle']?></a></li>
                <? } ?>               
                <? } else {?>
                <li>Aun no hay registros de este producto</li>
                <? } ?>              
                </ul>
                <br />
            </div>
		</div>
        
        
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#bolsa_plana">BOLSA PLANA</a></h3>
		<div class="accordion">
            <div style="background-color:#FFFFFF;" class="navcontainer">
				<p>&nbsp;</p>
				<ul class="navlist">
 		        <?php
				$qBolsa_plana		=	"SELECT * FROM productos WHERE tipo = 2 ORDER BY id_producto ASC";
				$rBolsa_plana		=	mysql_query($qBolsa_plana) OR die("<p>$qBolsa_plana</p><p>".mysql_error()."</p>");
				$nBolsa_plana		=	mysql_num_rows($rBolsa_plana);
				if($nBolsa_plana > 0){
				while($dBolsa_plana	=	mysql_fetch_assoc($rBolsa_plana)){ ?>
                    <li><a href="admin_vendedores.php?seccion=<?=$_REQUEST['seccion']?>&tipos=camiseta&accion=mostrar&id_producto=<?=$dBolsa_plana['id_producto']?>"><? echo "Codigo:".$dBolsa_plana['codigo']."  Medidas: ".$dBolsa_plana['ancho']." x ".$dBolsa_plana['largo']." x ".$dBolsa_plana['fuelle']?></a></li>
                <? } ?>               
                <? } else {?>
                <li>Aun no hay registros de este producto</li>
                <? } ?>     
                </ul>
                <br />
            </div>
		</div>
        
        
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#sacos">SACOS</a></h3>
		<div class="accordion">
            <div style="background-color:#FFFFFF;" class="navcontainer">
            <p>&nbsp;</p>
				<ul class="navlist">
 		        <?php
				$qSaco		=	"SELECT * FROM productos WHERE tipo = 3 ORDER BY id_producto ASC";
				$rSaco		=	mysql_query($qSaco) OR die("<p>$qSaco</p><p>".mysql_error()."</p>");
				$nSaco		=	mysql_num_rows($rSaco);
				if($nSaco > 0){
				while($dSaco	=	mysql_fetch_assoc($rSaco)){ ?>
                    <li><a href="admin_vendedores.php?seccion=<?=$_REQUEST['seccion']?>&tipos=saco&accion=mostrar&id_producto=<?=$dSaco['id_producto']?>"><? echo "Codigo:".$dSaco['codigo']."  Medidas: ".$dSaco['ancho']." x ".$dSaco['largo']." x ".$dSaco['fuelle']?></a></li>
                <? } ?>               
                <? } else {?>
                <li>Aun no hay registros de este producto</li>
                <? } ?>
                </ul>
                <br />
            </div>
		</div>

		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#bolsa_larga">BOLSA LARGA</a></h3>
		<div class="accordion">
            <div style="background-color:#FFFFFF;" class="navcontainer">
				<p>&nbsp;</p>
				<ul class="navlist">
 		        <?php
				$qBolsa_larga	=	"SELECT * FROM productos WHERE tipo = 4  ORDER BY id_producto ASC";
				$rBolsa_larga	=	mysql_query($qBolsa_larga) OR die("<p>$qBolsa_larga</p><p>".mysql_error()."</p>");
				$nBolsa_larga	=	mysql_num_rows($rBolsa_larga);
				if($nBolsa_larga > 0){
				while($dBolsa_larga	=	mysql_fetch_assoc($rBolsa_larga)){ ?>
                    <li><a href="admin_vendedores.php?seccion=<?=$_REQUEST['seccion']?>&tipos=bolsa_larga&accion=mostrar&id_producto=<?=$dBolsa_larga['id_producto']?>"><? echo "Codigo:".$dBolsa_larga['codigo']."  Medidas: ".$dBolsa_larga['ancho']." x ".$dBolsa_larga['largo']." x ".$dBolsa_larga['fuelle']?></a></li>
                <? } ?>               
                <? } else {?>
                <li>Aun no hay registros de este producto</li>
                <? } ?>         
                </ul>
                <br />
            </div>
		</div>
        
        <h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#rollo_tubular">ROLLO TUBULAR</a></h3>
		<div class="accordion">
            <div style="background-color:#FFFFFF;" class="navcontainer">
				<p>&nbsp;</p>
				<ul class="navlist">
 		        <?php
				$qRollo_tubular		=	"SELECT * FROM productos WHERE tipo = 5 ORDER BY id_producto ASC";
				$rRollo_tubular		=	mysql_query($qRollo_tubular) OR die("<p>$qRollo_tubular</p><p>".mysql_error()."</p>");
				$nRollo_tubular		=	mysql_num_rows($rRollo_tubular);
				if($nRollo_tubular > 0){
				while($dRollo_tubular	=	mysql_fetch_assoc($rRollo_tubular)){ ?>
                    <li><a href="admin_vendedores.php?seccion=<?=$_REQUEST['seccion']?>&tipos=bolsa_larga&accion=mostrar&id_producto=<?=$dRollo_tubular['id_producto']?>"><? echo "Codigo:".$dRollo_tubular['codigo']."  Medidas: ".$dRollo_tubular['ancho']." x ".$dRollo_tubular['largo']." x ".$dRollo_tubular['fuelle']?></a></li>
                <? } ?>               
                <? } else {?>
                <li>Aun no hay registros de este producto</li>
                <? } ?>              
                </ul>
                <br />
            </div>
		</div>
        
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#bolseo">PELICULA PLANA</a></h3>
		<div class="accordion">
            <div style="background-color:#FFFFFF;" class="navcontainer">
				<p>&nbsp;</p>
				<ul class="navlist">
 		        <?php
				$qPelicula_plana		=	"SELECT * FROM productos WHERE tipo = 6 ORDER BY id_producto ASC";
				$rPelicula_plana		=	mysql_query($qPelicula_plana) OR die("<p>$qPelicula_plana</p><p>".mysql_error()."</p>");
				$nPelicula_plana		=	mysql_num_rows($rPelicula_plana);
				if($nPelicula_plana > 0){
				while($dPelicula_plana	=	mysql_fetch_assoc($rPelicula_plana)){ ?>
                    <li><a href="admin_vendedores.php?seccion=<?=$_REQUEST['seccion']?>&tipos=pelicula_plana&accion=mostrar&id_producto=<?=$dPelicula_plana['id_producto']?>"><? echo "Codigo:".$dPelicula_plana['codigo']."  Medidas: ".$dPelicula_plana['ancho']." x ".$dPelicula_plana['largo']." x ".$dPelicula_plana['fuelle']?></a></li>
                <? } ?>               
                <? } else {?>
                <li>Aun no hay registros de este producto</li>
                <? } ?>                   
                </ul>
                <br />
            </div>
		</div>
        
		<div align="center">
		  <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo"><strong><br />
		  <br>
		  | Nuevo Producto | <br>
		  <br>
		  <br />
		  </strong></a></div>
     </div>
   </div>
 <?php } ?> 
    
</body>
<script type="text/javascript">
<!--

	var stretchers = $$('div.accordion');
	var togglers = $$('h3.toggler');

	stretchers.setStyles({'height': '0', 'overflow': 'hidden'});

	window.addEvent('load', function(){
		togglers.each(function(toggler, i){
			toggler.color = toggler.getStyle('background-color');
			toggler.$tmp.first = toggler.getFirst();
			toggler.$tmp.fx = new Fx.Style(toggler, 'background-color', {'wait': false, 'transition': Fx.Transitions.Quart.easeOut});
		});

		var myAccordion = new Accordion(togglers, stretchers, {
			'opacity': false,
			'start': false,
			'transition': Fx.Transitions.Quad.easeOut,
			onActive: function(toggler){
				toggler.$tmp.fx.start('#e0542f');
				toggler.$tmp.first.setStyle('color', '#fff');
			},
			onBackground: function(toggler){
				toggler.$tmp.fx.stop();
				toggler.setStyle('background-color', toggler.color).$tmp.first.setStyle('color', '#222');
			}
		});

		var found = 0;
		$$('h3.toggler a').each(function(link, i){
			if (window.location.hash.test(link.hash)) found = i;
		});
		myAccordion.display(found);

	});
	//-->	
</script>
  