<?php

		$tabla	=	"pedidos";
		$indice	=	"id_pedido";	
	
		$campos	=	describeTabla($tabla,$indice);
	
		$_POST['fecha_pedido']	=	preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" , $_POST['fecha_pedido']);
		$_POST['fecha_entrega']	=	preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" , $_POST['fecha_entrega']);

if(!empty($_POST['submit']))
{
	/*
	$qPro		=	"SELECT * FROM productos WHERE id_producto = ".$_POST['id_producto']."";
	$rPro		=	mysql_query($qPro);
	$dPro		=	mysql_fetch_assoc($rPro);
	
	$_POST['kg_mi_resultado']	= 	$dPro['kg_mi'] - (.10 * $_POST['cantidad']);
	*/	
    
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
	
<style type="text/css" media="screen">@import 'style.css';</style>
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

</script><?php if($nuevo || $modificar) { ?>
<form name="pedidos" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
<br /><br />
<div id="container">
  <div id="content">
	  <div id="datosgenerales" style="background-color:#FFFFFF;">
<table class="tablaCentrada">
                    <tr>
                    	<td><p class="titulos">Pedidos</p></td>
                    </tr>
        </table>
            <table width="285" class="tablaIzq" border="0">
<tr>
						<td width="119"><b>Pedido No.:</b></td>
						<td width="93"><input type="text" class="numeros" name="num_pedido" id="num_pedidos" readonly="readonly" value="<? 					if($nuevo){
						$res_id		=	mysql_query("SELECT MAX($indice) FROM $tabla");
						$next_id	=	mysql_fetch_row($res_id);
						echo $id			=	$next_id[0]+1;
						} if($modificar)
						echo $dato['id_pedido'];?>" /></td>
       	      </tr>
                    <tr>
						<td><b>Fecha de Pedido:</b></td>
					    <td><input type="text" class="numeros" name="fecha_pedido" readonly="readonly" id="fecha_pedido"  value="<? if($modificar) echo preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dato['fecha_pedido']);?>"/></td>
                        <td width="59"><a href="#" onClick="NewWindow('minical.php?destino=fecha_pedido','Calendario',300,270,false,'center')">
                      <img border="0" alt="Calendario" src="images/calendario.jpg" /></a></td>
       		  </tr>
                    <tr>
						<td><b>Fecha de Entrega:</b></td>
					  <td><input type="text" class="numeros" name="fecha_entrega" readonly="readonly" id="fecha_entrega" value="<? if($modificar) echo preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dato['fecha_entrega']);?>" /></td>
                        <td><a href="#" onClick="NewWindow('minical.php?destino=fecha_entrega','Calendario',300,270,false,'center')">
                      <img border="0" alt="Calendario" src="images/calendario.jpg" /></a></td>
					</tr>
		    </table>
            <br />
<table width="534" class="tablaCentrada">
              <tr>
              	<td class="fecha" bgcolor="0A4662" colspan="4" align="center">Facturar a:</td>
              </tr>	
              <tr>
                <td align="left">Cliente:</td>
                <td width="402" colspan="3"><input size="50" name="razon" id="razon" accesskey="4" onkeypress="buscarCliente('razon','resultado');" onkeyup="buscarCliente('razon','resultado');" value="<? 
				
				 echo $dato['razon'];
				 
				 ?>" />                <br />
                <div id="resultado" class="datosgenerales"></div>                </td>
              </tr>
              <tr></tr>
              <tr>
                <td width="120" align="left">Agente:</td>
                <td colspan="3"><input type="type" name="agente" size="50" id="agente" value="<?php if($modificar) echo $dato['agente']; ?>" /></td>
              </tr>
              <tr>
                <td width="120" align="left">Condiciones de pago:</td>
                <td colspan="3"><input type="text" size="50" name="condiciones" /></td>
              </tr>
          </table>
              <br />
          <table width="530" class="tablaCentrada">
	      <tr>
              	<td colspan="4" align="center" bgcolor="0A4662" class="fecha">Impresi&oacute;n</td>
              </tr> 
     </table>
              <table width="529" class="tablaCentrada">
              	<tr>
                	<td colspan="4">Producto: 
                    <select name="id_producto" style="size:80px">
                    <?					
						$qProductos	= "SELECT * FROM productos ORDER BY codigo ASC";
						$rProductos	=	mysql_query($qProductos);
						
							while($dProductos	=	mysql_fetch_assoc($rProductos)){
					?>
                           <option value="<?=$dProductos['id_producto']?>" <? if($modificar && $dato['id_producto'] == $dProductos['id_producto'] ) echo "selected"; ?>><?=$dProductos['codigo']." : ".$dProductos['largo']." x ".$dProductos['ancho']." x ".$dProductos['fuelle']?></option>
                 <? } ?>
                     </select>
                     
                     
                     </td>
                </tr>
				<tr>
                  <td width="68" align="left">Cantidad:</td>
                  <td width="192"><input type="text" size="5" name="cantidad" value="<? if($modificar) echo $dato['cantidad']?>" /></td>
                  <td width="120" align="left">Precio Unitario:</td>
                  <td width="129"><input type="text" size="15" name="precio_unitario" value="<? if($modificar) echo $dato['precio_unitario']?>" /></td>
                </tr>            
          </table>          
      <br />
          <table width="529" class="tablaCentrada">
			<tr>
              	<td width="69" align="left">1er Lado:</td>
                <td width="190"><textarea rows="3" cols="20" name="lado_uno"><? if($modificar) echo $dato['lado_uno']?></textarea></td>
              	<td width="72" align="left">2do Lado:</td>
                <td width="178"><textarea rows="3" cols="20" name="lado_dos" ><? if($modificar) echo $dato['lado_dos']?></textarea></td>              
            </tr> 
              <tr>
              	<td align="left">Color:</td>
                <td><textarea rows="3" cols="20" name="color"><? if($modificar) echo $dato['color']?></textarea></td>
              	<td align="left">Tono:</td>
                <td><textarea rows="3" cols="20" name="tono"><? if($modificar) echo $dato['tono']?></textarea></td>              
              </tr> 
          </table>
              <br />
		 <table width="531" class="tablaCentrada">  
              <tr>
              	<td width="120" align="left">Costo de Grabado:</td>
                <td width="138"><input type="text" size="16" name="costos" value="<? if($modificar) echo $dato['costos']?>" /></td>
              	<td width="74" align="left">Total:</td>
                <td width="178"><input type="text" size="15" name="total" value="<? if($modificar) echo $dato['total']?>" /></td>              
              </tr> 
          </table>
              <br />
      
<table width="531" class="tablaCentrada">
			<tr>
				<td width="547" align="center" bgcolor="#0A4662" class="fecha">Producto</td>
            </tr>
			<tr>
			  <td width="547" align="center" class="fecha">&nbsp;
                
              </td>
            </tr>		  </table>
    <br />
		 	<table width="531" class="tablaCentrada">
              <tr>
              	<td width="76" align="left">Orden Ant.</td>
                <td width="199"><input type="text" size="16" name="ord_ant" value="<? if($modificar) echo $dato['ord_ant']?>" /></td>
              	<td width="91" align="left">No. Orden:</td>
                <td width="145"><input type="text" size="15" name="ord_num" value="<? if($modificar) echo $dato['ord_num']?>" /></td>              
           </tr> 
              <tr>
              	<td width="76" align="left">Fecha Planta</td>
                <td width="199"><input type="text" size="16" name="fecha_planta" id="fecha_planta" value="<? if($modificar) echo preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $dato['fecha_planta']); ?>" />
                <a href="#" onClick="NewWindow('minical.php?destino=fecha_planta','Calendario',300,270,false,'center')">
                      <img border="0" alt="Calendario" src="images/calendario.jpg" /></a></td>
              	<td width="91" align="left">No. Relacion:</td>
                <td width="145"><input type="text" size="15" name="no_relacion" value="<? if($modificar) echo $dato['no_relacion']?>"  /></td>              
           </tr>           </table>
              <br />
        <table width="529" class="tablaCentrada">
          <tr>
          	<td width="162">Produccion Reportada: </td>
            <td width="355"><input type="text" name="produccion_repor" value="<? if($modificar) echo $dato['produccion_repor']?>" />
            <!--- <input type="hidden" name="kg_mi_resultado" value="" /> --->
            </td>
          </tr>
          </table>
          
	    <br />
	    <table width="529" class="tablaCentrada">
          <tr>
          	<td width="162">Instrucciones especiales: </td>
            <td width="355"><textarea rows="6" cols="50" name="instrucciones" ><? if($modificar) echo $dato['instrucciones']?></textarea></td>
          </tr>
        </table>
        <table class="tablaCentrada" width="529">
			  <tr>
  				  <td class="contenidos" align="right"><?php if($modificar) { ?>
                    <input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>" />
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
<table class="tablaCentrada">
                    <tr>
                    	<td><p class="titulos">Pedidos</p></td>
                    </tr>
        </table>
            <table width="285" class="tablaIzq" border="0">
<tr>
						<td width="119"><b>Pedido No.:</b></td>
						<td width="88" class="style7"><?=$dato['id_pedido']?></td>
</tr>
                    <tr>
						<td><b>Fecha de Pedido:</b></td>
					    <td class="style7"><?=$dato['fecha_pedido']?></td>
				      <td width="62">&nbsp;</td>
              		</tr>
                    <tr>
						<td><b>Fecha de Entrega:</b></td>
					    <td class="style7"><?=$dato['fecha_entrega']?></td>
				      <td>&nbsp;</td>
					</tr>
		    </table>
            <br />
<table width="534" class="tablaCentrada">
              <tr>
              	<td class="fecha" bgcolor="0A4662" colspan="4" align="center">Facturar a:</td>
              </tr>	
              <tr>
                <td align="left">Cliente:</td>
                <td width="402" colspan="3" class="style7"><?=$dato['razon']?></td></tr>
              <tr>
                <td width="120" align="left">Agente:</td>
                <td colspan="3" class="style7"><?=$dato['agente']?></td>
              </tr>
              <tr>
                <td width="120" align="left">Condiciones de pago:</td>
                <td colspan="3" class="style7"><?=$dato['condiciones']?></td>
              </tr>
          </table>
              <br />
          <table width="530" class="tablaCentrada">
	      <tr>
              	<td colspan="4" align="center" bgcolor="0A4662" class="fecha">Impresi&oacute;n</td>
              </tr> 
     </table>
              <table width="529" class="tablaCentrada">
              <? 
			  	$qProducto = "SELECT * FROM productos WHERE id_producto = ".$dato['id_producto']."";
				$rProducto = mysql_query($qProducto);
				$dProducto = mysql_fetch_assoc($rProducto);
				
				
			  ?>
        <tr>
        	<td>Tipo: </td>
          <td colspan="4" class="style7"><?
          if($dProducto['tipo'] == 1) echo "Camiseta";
          if($dProducto['tipo'] == 2) echo "Bolsa Plana";		  
          if($dProducto['tipo'] == 3) echo "Saco";
		  if($dProducto['tipo'] == 4) echo "Bolsa Larga";
		  if($dProducto['tipo'] == 5) echo "Rollo Tubular";          
		  if($dProducto['tipo'] == 6) echo "Pelicula plana";		  
		  
		  ?></td>
         </tr>
<tr>
				<td width="69" align="left">Cantidad:</td>
                <td width="47" class="style7"><?=$dato['cantidad']?></td>
                <td width="89" align="right">Unidad:&nbsp;&nbsp;</td>
                <td width="124" class="style7"><?=$dato['id_producto']?></td>
                <td width="116" align="left">Precio Unitario:</td>
                <td width="56" class="style7"><?=$dato['precio_unitario']?></td>
</tr>            
          </table>          
      <br />
          <table width="529" class="tablaCentrada">   
			<tr>
              	<td width="69" align="left">1er Lado:</td>
                <td width="190" class="style7"><?=$dato['lado_uno']?></td>
              <td width="72" align="left">2do Lado:</td>
                <td width="178" class="style7"><?=$dato['lado_dos']?></td>
			</tr> 
              <tr>
              	<td align="left">Color:</td>
                <td class="style7"><?=$dato['color']?></td>
                <td align="left">Tono:</td>
                <td class="style7"><?=$dato['tono']?></td>
            </tr> 
          </table>
              <br />
		 <table width="531" class="tablaCentrada">  
              <tr>
              	<td width="120" align="left">Costo de Grabado:</td>
                <td width="138" class="style7"><?=$dato['id_pedido']?></td>
                <td width="74" align="left">Total:</td>
                <td width="178" class="style7"><?=$dato['id_pedido']?></td>
              </tr> 
          </table>
              <br />
        <table width="532" class="tablaCentrada">
              <tr>
              	<td align="center" colspan="4" bgcolor="#0A4662" class="fecha">Material</td>
              </tr>
              <tr>
              	<td align="center" width="50%" >Resina</td>
                <td align="center" width="50%" >Pigmento</td>
              </tr>
              <tr>
              	<td>
   	  <table class="tablaCentrada" width="100%">
                    	<tr>
                        	<td width="17%">B.D.</td>
                          	<td width="33%" class="style7"><?=$dProducto['densidad']?></td>
                          	<td width="15%">A.D.</td>
                            <td width="35%" class="style7"><?=$dProducto['densidad']?></td>
                    	</tr>
                    	<tr>
                        	<td colspan="1" width="17%">P</td>
                          	<td colspan="1" width="33%" class="style7"><?=$dProducto['p']?></td>
                    	</tr>
                    	<tr>
                        	<td colspan="1" width="17%">X</td>
                          	<td colspan="1" width="33%" class="style7"><?=$dProducto['x']?></td>
                    	</tr>
                    	<tr>
                        	<td colspan="1" width="17%">004</td>
                          	<td colspan="1" width="33%" class="style7"><?=$dProducto['ccc']?></td>
                    	</tr>                
				  </table>                </td> 
              	<td valign="top">
<table class="tablaCentrada" width="100%">

                    	<tr>
                        	<td colspan="1" width="25%">Natural:</td>
                       	    <td colspan="1" width="16%" class="style7"><?=$dProducto['pigmento']?></td>
                    	</tr>
                    	<tr>
                        	<td colspan="1" width="25%">Color:</td>
                       	  	<td colspan="1" width="16%" class="style7"><?=$dProducto['pigmento']?></td>
                   	  	  <td width="59%" class="style7"><?=$dProducto['color']?></td>
                    	</tr>
				  </table>                </td> 
              </tr>		  
        </table>    
		  <br />
      <table width="531" class="tablaCentrada">
			<tr>
				<td align="center" colspan="4" bgcolor="#0A4662" class="fecha">Producto</td>
            </tr>
            <tr>
            	<td width="76">Descripcion:</td>
           	    <td width="218" class="style7"><?=$dato['id_pedido']?></td>
           	    <td width="35">Sello:</td>
           	  <td width="218" class="style7"><?=$dato['id_pedido']?></td>
            </tr>
		  </table>
              <br />
		 	<table width="531" class="tablaCentrada">  
              <tr>
              	<td width="76" align="left">Orden Ant.</td>
                <td width="199" class="style7"><?=$dato['ord_ant']?></td>
                <td width="91" align="left">No. Orden:</td>
                <td width="145" class="style7"><?=$dato['ord_num']?></td>
              </tr> 
              <tr>
              	<td width="76" align="left">Fecha Planta</td>
                <td width="199" class="style7"><?=$dato['fecha_planta']?></td>
                <td width="91" align="left">No. Relacion:</td>
                <td width="145" class="style7"><?=$dato['id_pedido']?></td>
              </tr>           </table>
              <br />		  
            <table width="529" class="tablaCentrada">
			<tr>
            	<td width="280" align="center">Medidas</td>
            	<td width="237" align="center">Calibre</td>
            </tr>
          	<tr>
            	<td width="280">
   	    		  <table class="tablaCentrada" width="100%">
                        <tr>
                            <td width="27%">Ancho: </td>
                            <td width="73%" class="style7"><?=$dProducto['ancho']?></td>
                        </tr>
                        <tr>
                        	<td>Fuelle: </td>
                            <td class="style7"><?=$dProducto['fuelle']?></td>
                        </tr>
                        <tr>
                        	<td>Largo: </td>
                            <td class="style7"><?=$dProducto['largo']?></td>
                        </tr>
                  </table></td>
   					<td valign="top" align="center">
                	<table class="tablaCentrada" width="100%" align="center">
                    	<tr>
                        	<td width="100%" align="center" class="style7"><?=$dProducto['calibre']?></td>
                    	</tr>
                    </table>                
                 </td>
            </tr>
          </table>
          <br />
        <table width="529" class="tablaCentrada">
          <tr>
          	<td width="145">Produccion Reportada: </td>
            <td width="314" class="style7"><?=$dato['produccion_repor']?></td>
          </tr>
          </table>
          
	    <br />
         <table width="529" class="tablaCentrada">
          <tr>
          	<td width="145">Codigo del producto: </td>
            <td width="314" class="style7"><?=$dProducto['codigo']?></td>
          </tr>
        </table>

         <br />
        <table width="529" class="tablaCentrada">
          <tr>
          	<td width="162">Instrucciones especiales: </td>
            <td width="355" class="style7"><?=$dato['instrucciones']?></td>
          </tr>
        </table>
        <table class="tablaCentrada" width="529">
			  <tr>
  				  <td class="contenidos" align="right">&nbsp;</td>
          </tr>
            </table>
          </div>
	</div>
</div>
<?php }  ?>
<?php if($listar) { ?>

<?php
	$tabla	=	"pedidos";
	$indice	=	"id_pedido";	

$qListar	=	"SELECT * FROM $tabla  WHERE 1 ORDER BY id_pedido ASC ";
$rListar	=	mysql_query($qListar) OR die("<p>$qListar</p><p>".mysql_error()."</p>");

if(mysql_num_rows($rListar) > 0) { ?>
<br>
<br>
<table align="center" width="531" class="style2">
<tr>
    	<td align="center" colspan="4">PEDIDOS<br />
   	    <br /></td>
  </tr>
<tr>
	<td width="84" align="center">Orden num.</td>
	<td width="327" align="left"><b>Producto</b></td>
  </tr>
<?php for($flag=true;$d	=	mysql_fetch_assoc($rListar);$flag=!$flag) { ?>
		<tr <?=($flag)?"class=\"cabecera\"":""?>>
        <? 
	 	$qProductos	= "SELECT * FROM productos WHERE id_producto = ".$d['id_producto']." ORDER BY codigo ASC";
		$rProductos	=	mysql_query($qProductos);
		$dProductos =	mysql_fetch_assoc($rProductos);
		?>
    <td class="mostrar" align="center"><?=$d['id_pedido']?></td>
<td align="left" class="mostrar" ><? echo $d['razon'] ." - ". $dProductos['codigo'] ." - ". $dProductos['largo'] ." x ". $dProductos['ancho'] ." x ". $dProductos['fuelle']?></td>

<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=mostrar&<?=$indice?>=<?=$d['id_pedido']?>"><img src="<?=IMAGEN_MOSTRAR?>" border="0"></a></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&<?=$indice?>=<?=$d['id_pedido']?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&<?=$indice?>=<?=$d['id_pedido']?>" onClick="javascript: return confirm('Realmente deseas eliminar a este supervisor?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
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

    <?php } else { ?>

<table align="center" width="54%">
<tr>
		<td align="center"><br />
		  <br />
		  <br />
	    Aun no hay Pedidos registrados en la base de datos<br>
	    <br />
	    <br /></td>
  </tr>
  <tr>
	  <td align="center">
		  <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo"><strong> <br />
		  | Nuevo Pedido | <br />
		  </strong></a></td>
  </tr>
</table>
	
	      <?php } ?>
        <?php } ?>