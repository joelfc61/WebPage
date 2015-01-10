<? 

// DIAS PARO GENERAL 
if($_POST['parosDiaGeneral'])
{
		$fecha	= $_REQUEST['ano'].'-'.$_REQUEST['mes'].'-'.$_REQUEST['dia'];
		
		if(!isset($_REQUEST['id_paros_general']))
		$qParos	=	"INSERT INTO paros_general (fecha, motivo, bolseo, impresion, lineas, extruder)"."VALUES ('$fecha','{$_REQUEST[motivo]}','{$_REQUEST[bolseo]}','{$_REQUEST[impresion]}','{$_REQUEST[lineas]}','{$_REQUEST[extruder]}')";
		if(isset($_REQUEST['id_paros_general']))
		$qParos	=	"UPDATE paros_general SET fecha = '$fecha', motivo = '{$_REQUEST['motivo']}', bolseo = '{$_REQUEST[bolseo]}', impresion = '{$_REQUEST[impresion]}', lineas = '{$_REQUEST[lineas]}', extruder = '{$_REQUEST[extruder]}' WHERE id_paros_general = ".$_REQUEST['id_paros_general']."";
		
		$rParos	=	mysql_query($qParos) OR die("<p>$qParos</p><p>".mysql_error()."</p>");
			
	
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}";
  				
			
}	



$listar =	true;
if(!empty($_GET['accion']))
{
	$listar		=	($_GET['accion']=="listar")?true:false;
	$nuevoGeneralDia		=	($_GET['accion']=="nuevoGeneralDia")?true:false;
	$nuevoMaquinasDia		=	($_GET['accion']=="nuevoMaquinasDia")?true:false;
	$nuevoGeneralTiempo		=	($_GET['accion']=="nuevoGeneralTiempo")?true:false;
	$nuevoMaquinaTiempo		=	($_GET['accion']=="nuevoMaquinaTiempo")?true:false;

	if(!empty($_GET['id_paros_general']) && is_numeric($_GET['id_paros_general']))
	{
		$mostrar			=	($_GET['accion']=="mostrar")?true:false;
		$eliminarGeneralDia	=	($_GET['accion']=="eliminarGeneralDia")?true:false;
		$eliminar2			=	($_GET['accion']=="eliminar2")?true:false;
		$editarGeneralDia	=	($_GET['accion']=="editarGeneralDia")?true:false;
			
	}
	
	if( $editarGeneralDia)
	{
		$query_dato	=	"SELECT * , YEAR(fecha) AS ano, MONTH(fecha) AS mes, DAY(fecha) AS dia FROM paros_general WHERE ( id_paros_general ={$_GET[id_paros_general]})";
		$res_dato	=	mysql_query($query_dato) OR die("<p>$query_dato</p><p>".mysql_error()."</p>");
		$dato		=	mysql_fetch_assoc($res_dato);		
		
	}
	
if($eliminarGeneralDia)
	{
		$q_eliminar	=	"DELETE FROM paros_general WHERE (id_paros_general ={$_GET[id_paros_general]}) LIMIT 1";
		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}";
}

}
?>

<? if($nuevoGeneralDia || $editarGeneralDia){ ?>
<div id="container">
		<div id="content">
        <form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
        	<table width="70%" cellpadding="0" cellspacing="0" align="center">
            	<tr>
                	<td class="style5"><b>Motivo de paro:</b></td>
                	<td><input type="text" name="motivo" size="50" value="<? if($editarGeneralDia) echo $dato['motivo']?>"/></td>
                </tr>
            	<tr>
            		<td class="style5"><b>Fecha:</b> </td>
                    <td><input type="text" name="dia" size="3" value="<? if($editarGeneralDia) echo $dato['dia']; else date('d');?>" /> de 
                    	<select name="mes" >
                        	<? for($a = 1; $a < 13; $a++) {?>
                    		<option value="<?=$a?>" <? if($editarGeneralDia &&  $dato['mes'] == $a ) echo "select"; else if($a == date('m')) echo "selected";?>><?=$mes[$a]?></option>
                            <? } ?>
                        </select> de 
                        <input type="text" name="ano" size="6" value=" <? if($editarGeneralDia) echo $dato['ano']; else echo date('Y');?>" />
                    </td>
                </tr>
                <tr>
                	<td class="style5"><b>Area(s) a afectar:</b></td>
                    <td><input type="checkbox" value="1" <? if($editarGeneralDia && $dato['bolseo'] == 1 ) echo "checked" ?> name="bolseo"> Bolseo<br>
                    	<input type="checkbox" value="1" <? if($editarGeneralDia && $dato['impresion'] == 1 ) echo "checked";?> name="Impresion"> Impresi&oacute;n<br>
                        <input type="checkbox" value="1" <? if($editarGeneralDia && $dato['lineas'] == 1 ) echo "checked"; ?> name="lineas"> Lineas de impresi&oacute;n<br>
                        <input type="checkbox" value="1" <? if($editarGeneralDia && $dato['extruder'] ==  1) echo "checked";?> name="extruder"> Extruder
                    </td>    
                </tr>
                <tr>
                	<td colspan="2" align="center"><br>
<br>
<? if($editarGeneralDia){ ?>
	<input type="hidden" name="id_paros_general" value="<?=$dato['id_paros_general']?>">
<? } ?>
<input type="hidden" name="parosDiaGeneral" value="1">
<input type="submit" name="guardar" value="Guardar"><br>
<br>
</td>
                </tr>
            </table></form>
        </div>
</div>
<? } if($nuevoMaquinasDia){?>
<link rel="stylesheet" type="text/css" href="desing/estilos.css">
<SCRIPT Language="JAVASCRIPT"> 
<!-- 

function howMany(form,x){ 
    var html = ''; 
	var x = x;
	if( x == 'extruder'){ 
    	var numObj = parseInt(form.numeros_extruder.value); 
   		var container = document.getElementById('maquina_extruder'); 
        if (numObj > 0) { 
            for(i=1; i<=numObj; i++) { 
			
               html += 'Maquina ' + i + ': <input type="hidden" name="codigosE[]" value="'+ i +'"><select name="id_maquina_'+ i +'" class="contenidos" id="id_maquina_'+ i +'"><? echo $qMaquina = "SELECT * FROM maquina WHERE area = 4 ORDER BY numero ASC"; $rMaquina = mysql_query($qMaquina); while($dMaquina = mysql_fetch_assoc($rMaquina)){?><option value="<?=$dMaquina['id_maquina'];?>"><?=$dMaquina['numero'];?></option><? } ?></select><br>'; 
            } 
        } 
	}
	else if( x == 'impresion'){ 
    	var numObj = parseInt(form.numeros_impresion.value); 
   		var container = document.getElementById('maquina_impresion'); 
	    if (numObj > 0) { 
            for(i=1; i<=numObj; i++) { 
			
               html += 'Maquina ' + i + ': <input type="hidden" name="codigosI[]" value="'+ i +'"><select name="id_maquina_'+ i +'" class="contenidos" id="id_maquina_'+ i +'"><? echo $qMaquina = "SELECT * FROM maquina WHERE area = 2 ORDER BY numero ASC"; $rMaquina = mysql_query($qMaquina); while($dMaquina = mysql_fetch_assoc($rMaquina)){?><option value="<?=$dMaquina['id_maquina'];?>"><?=$dMaquina['numero'];?></option><? } ?></select><br>'; 
            } 
        } 
	}
	
	else if( x == 'lineas'){ 
    	var numObj = parseInt(form.numeros_lineas.value); 
   		var container = document.getElementById('maquina_lineas'); 	
        if (numObj > 0) { 
            for(i=1; i<=numObj; i++) { 
			
               html += 'Maquina ' + i + ': <input type="hidden" name="codigosL[]" value="'+ i +'"><select name="id_maquina_'+ i +'" class="contenidos" id="id_maquina_'+ i +'"><? echo $qMaquina = "SELECT * FROM maquina WHERE area = 3 ORDER BY numero ASC"; $rMaquina = mysql_query($qMaquina); while($dMaquina = mysql_fetch_assoc($rMaquina)){?><option value="<?=$dMaquina['id_maquina'];?>"><?=$dMaquina['numero'];?></option><? } ?></select><br>'; 
            } 
        } 
	}	
	else if( x == 'bolseo'){ 
    	var numObj = parseInt(form.numeros_bolseo.value); 
   		var container = document.getElementById('maquina_bolseo');
	    if (numObj > 0) { 
            for(i=1; i<=numObj; i++) { 
			
               html += 'Maquina ' + i + ': <input type="hidden" name="codigosB[]" value="'+ i +'"><select name="id_maquina_'+ i +'" class="contenidos" id="id_maquina_'+ i +'"><? echo $qMaquina = "SELECT * FROM maquina WHERE area = 1 ORDER BY numero ASC"; $rMaquina = mysql_query($qMaquina); while($dMaquina = mysql_fetch_assoc($rMaquina)){?><option value="<?=$dMaquina['id_maquina'];?>"><?=$dMaquina['numero'];?></option><? } ?></select><br>'; 
            } 
        } 
	}
			
			
			container.innerHTML = html; 

} 
//--> 
</SCRIPT>
<div id="container">
		<div id="content">
        <form name="numeros" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
        	<table width="70%" cellpadding="0" cellspacing="0" align="center">
            	<tr>
                	<td width="26%" class="style5"><div align="right">Motivo de paro: </div></td>
                	<td width="74%"><input type="text" name="motivo" size="50"/></td>
                </tr>
            	<tr>
            		<td class="style5"><div align="right">Fecha: </div></td>
                    <td><input type="text" name="dia" size="5" value="<?=date('d');?>" /> de 
                    	<select name="mes" >
                        	<? for($a = 1; $a < 13; $a++) {?>
                    		<option value="<?=$a?>" <? if($a == date('m')) echo "selected";?>><?=$mes[$a]?></option>
                            <? } ?>
                        </select> de 
                        <input type="text" name="anho" size="6" value="<?=date('Y');?>" />                    </td>
                 </tr>
                  <tr>
                    <td colspan="2" class="style5"><br><br><h3>Extruder: </h3><br></td>
                  </tr>
                  <tr>
                    <td><div align="right">No. de maquinas a afectar: </div></td>
                    <td><input type="text" value="0" name="numeros_extruder" size="3" maxlength="2" onChange="javascript: howMany(this.form,'extruder')"></td>
                  </tr>
                   <tr>
                     <td colspan="2">
                          <table width="100%">
                  			<tr class="titulos cabecera">
                            	<td width="401" id="maquina_extruder"></td> 
                            </tr> 
                          </table>                     
                     </td>
                   </tr>
                  <tr>
                    <td colspan="2" class="style5"><br><br><h3>Impresion: </h3><br></td>
                  </tr>
                  <tr>
                    <td><div align="right">No. de maquinas a afectar: </div></td>
                    <td><input type="text" value="0" name="numeros_impresion" size="3" maxlength="2" onChange="javascript: howMany(this.form,'impresion')"></td>
                  </tr>
                   <tr>
                     <td colspan="2">
                          <table width="100%">
                  			<tr class="titulos cabecera">
                            	<td width="401" id="maquina_impresion"></td> 
                            </tr> 
                          </table>                     
                     </td>
                   </tr>
                  <tr>
                    <td colspan="2" class="style5"><br><br><h3>Lineas de Impresion: </h3><br></td>
                  </tr>
                  <tr>
                    <td><div align="right">No. de maquinas a afectar: </div></td>
                    <td><input type="text" value="0" name="numeros_lineas" size="3" maxlength="2" onChange="javascript: howMany(this.form,'lineas')"></td>
                  </tr>
                   <tr>
                     <td colspan="2">
                          <table width="100%">
                  			<tr class="titulos cabecera">
                            	<td width="401" id="maquina_lineas"></td> 
                            </tr> 
                          </table>                     
                     </td>
                   </tr>                
                  <tr>	
                    <td colspan="2" class="style5"><br><br><h3>Bolseo: </h3><br></td>
                  </tr>
                  <tr>
                    <td><div align="right">No. de maquinas a afectar: </div></td>
                    <td><input type="text" value="0" name="numeros_bolseo" size="3" maxlength="2" onChange="javascript: howMany(this.form,'bolseo')"></td>
                  </tr>
                   <tr>
                     <td colspan="2">
                          <table width="100%">
                  			<tr class="titulos cabecera">
                            	<td width="401" id="maquina_bolseo"></td> 
                            </tr> 
                          </table>                     
                     </td>
                   </tr>                   
                </table>
          </form>
        </div>
</div>
<? } if($listar){ ?>
<div id="container">
		<div id="content">
        	<table width="100%">
            	<tr>
                	<td>
                        <table width="30%">
                        	<tr>
                            	<td colspan="4" align="center">
                                <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=nuevoGeneralDia">NUEVO PARO GENERAL</a><br>
								<br>
								</td>
                            </tr>
                            <tr>
                                <td colspan="4"><h3>DIAS PARO GENERAL DEL <?=date('Y')?></h3></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha</strong></td>
                                <td><strong>Motivo</strong></td>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <? $qParoG	=	"SELECT * FROM paros_general WHERE YEAR(fecha) = ".date('Y')."";
								$rParoG	=	mysql_query($qParoG);
								while($dParoG	= mysql_fetch_assoc($rParoG)){?>
                            <tr>
                                <td width="30%" class="style5" align="left"><?=$dParoG['fecha'];?></td>
                                <td width="60%" class="style5"><?=$dParoG['motivo']?></td>
                                <td width="5%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&id_paros_general=<?=$dParoG['id_paros_general']?>&accion=editarGeneralDia"><img src="<?=IMAGEN_MODIFICAR?>" alt="Modificar" border="0"></a></td>
                                <td width="5%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&id_paros_general=<?=$dParoG['id_paros_general']?>&accion=eliminarGeneralDia"><img src="<?=IMAGEN_ELIMINAR?>" alt="eliminar" border="0"></a></td>
                            </tr>
                            <? } ?>
                        </table>
                    </td>
                </tr>
             </table>
        </div>
</div>
<? } ?>