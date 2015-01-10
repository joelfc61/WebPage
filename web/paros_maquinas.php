<? 
if(!isset($_REQUEST['accion'])){}
else{
require_once('libs/funciones.php');
}
?>
<script src="CalendarControl.js.php" type="text/javascript"></script>
<link href="CalendarControl.css" rel="stylesheet" type="text/css"/>
<style type="text/css" media="screen">@import 'style.css';</style>
<style type="text/css" media="screen">@import 'design/estilos.css';</style>
<script type="text/javascript" src="libs/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="libs/checkboxes_jquery_plug.js"></script>
<script type="text/javascript">
	function revisa_check(divi){
		var contador = 0;
			try {
				$("#"+divi).find("input[@type$='checkbox']").each(function(){
						if(this.checked == false)
							contador = contador + 1;
						if(contador == 3)
							$("#"+divi).find("input[@type$='hidden']:eq(1)").each(function(){
								this.value	= 0;
						});	
						
				});
			
				if(contador == 3)
					return false;
				if(contador == 0)
					return true;
			}catch(mierror){
		//	   alert("Error detectado: " + mierror.description)
			}
	}
	
			
	function activo(x,y,divi)
	{
		var f = document.forms['eaea']; 

			if(document.getElementById(x).checked == true ){
			//	alert('eaea');
				f.elements[y].value = '1';
	  		}
				revisa_check(divi);
	}	
	
	function chekaTodos(y,divi)
	{
		var f = document.forms['eaea']; 
		var x =  revisa_check(divi);
		
			if(x == false){
				$("#"+divi).checkCheckboxes();
				f.elements[y].value = '1';
			}
			else
			{
				$("#"+divi).unCheckCheckboxes();
				f.elements[y].value = '0';
			}
		
	}
</script>
<?php

$tabla	=	"paros_maquinas";
$indice	=	"id_paros_maquinas";
$campos	=	describeTabla($tabla,$indice);
if(!empty($_REQUEST['asignar']))
{

    if(!empty($_POST[$indice])){
	
		$query		=	"UPDATE $tabla SET turno_m = '".$_POST['turno_m']."', turno_v = '".$_POST['turno_v']."', turno_n = '".$_POST['turno_n']."', meta_dia = '".$_POST['meta_dia']."'  WHERE ($indice=".$_POST[$indice].")";
		$res_query	=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");

	} else { 
		
    	for($i=0; $i < sizeof($_POST['activo']); $i++){
			
			if($_POST['activo'][$i] == 1){
				($_POST['turno_m'][$i+1] == 1)?$turno_m = 1:$turno_m = 0;
				($_POST['turno_v'][$i+1] == 1)?$turno_v = 1:$turno_v = 0;
				($_POST['turno_n'][$i+1] == 1)?$turno_n = 1:$turno_n = 0;
						
				$query		=	"INSERT INTO $tabla (fecha,turno_m, turno_v, turno_n, id_maquina,area, meta_dia) 
								VALUES('".$_POST['fecha'][$i]."',".$turno_m.",".$turno_v.",".$turno_n.",".$_POST['id_maquina'].",".$_POST['area'].",".$_POST['meta_dia'][$i].")";
				$res_query	=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
				$fm	=	explode('-',$_POST['fecha'][$i]);

		 		$qMM	=	"SELECT metas_maquinas.id_meta_diaria, turnos, dias, meta.id_meta FROM metas_maquinas ".
							" LEFT JOIN meta ON metas_maquinas.id_meta = meta.id_meta ".
							" WHERE id_maquina = '".$_POST['id_maquina']."' AND mes BETWEEN '".$fm[0]."-".$fm[1]."-01' AND '".$_POST['fecha'][$i]."'";
				
				$rMM	=	mysql_query($qMM);
				$dMM	=	mysql_fetch_assoc($rMM);
				$dN		=	$dMM['dias'];
				$tN		=	$dMM['turnos'];
			
				if($turno_m == 1){
					$dN		= 	$dN	 - 0.32;	
					$tN		= 	$tN-1;
				}
				if($turno_v == 1){
					$dN		= 	$dN	 - 0.33;
					$tN		= 	$tN-1;
				}
				if($turno_n == 1){
					$dN		= 	$dN	 - 0.35;
					$tN		= 	$tN-1;
				}				
						
					$query2		=	"UPDATE metas_maquinas SET turnos = ".$tN.", dias =".$dN." WHERE id_meta_diaria = ".$dMM['id_meta_diaria']."  ";
					$res_query2	=	mysql_query($query2) OR die("<p>$query</p><p>".mysql_error()."</p>");
				}
		
				echo '<script type="text/javascript" language="javascript">
					 window.opener.document.location.href = "admin.php?seccion=15&accion=modificar&id_meta='.$dMM['id_meta'].'";
				 </script>';
		
			}
		}
	
 	redirecciona("paros_maquinas.php?accion=listar_paros&id_maquina=".$_POST['id_maquina']."");

	
	
}
$listar =	true;
if(!empty($_GET['accion']))
{
    $generar    	=   ($_GET['accion']=="generar")?true:false; 
	$listar_paros	=	($_GET['accion']=="listar_paros")?true:false;
	$mostrar_paros	=	($_GET['accion']=="mostrar_paros")?true:false;
	$listar			=	($_GET['accion']=="listar")?true:false;
	
	if(!empty($_GET[$indice]) && is_numeric($_GET[$indice]) )
	{
		$mostrar_paros_registrados	=	($_GET['accion']=="mostrar_paros_registrados")?true:false;
		$eliminar_paros				=	($_GET['accion']=="eliminar_paros")?true:false;
		$modificar					=	($_GET['accion']=="modificar")?true:false;
	}

	if($mostrar || $modificar)
	{
		$query_dato	=	"SELECT * FROM $tabla WHERE ($indice={$_GET[$indice]})";
		$res_dato	=	mysql_query($query_dato) OR die("<p>$query_dato</p><p>".mysql_error()."</p>");
		$dato		=	mysql_fetch_assoc($res_dato);
	}

	
	if($eliminar_paros)
	{
		$q_eliminar	=	"DELETE FROM $tabla WHERE ($indice={$_GET[$indice]}) LIMIT 1";
		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");
		
		
		$fm	=	explode('-',$_GET['fecha']);

		$qMM	=	"SELECT metas_maquinas.id_meta_diaria, turnos, dias, meta.id_meta FROM metas_maquinas ".
					" LEFT JOIN meta ON metas_maquinas.id_meta = meta.id_meta ".
					" WHERE id_maquina = '".$_GET['id_maquina']."' AND mes BETWEEN '".$fm[0]."-".$fm[1]."-01' AND '".$_GET['fecha']."'";
		$rMM	=	mysql_query($qMM);
		$dMM	=	mysql_fetch_assoc($rMM);
				$dN		=	$dMM['dias'];
				$tN		=	$dMM['turnos'];
			
				if($_GET['turno_m'] == 1){
					$dN		= 	$dN	 + 0.32;	
					$tN		= 	$tN+1;
				}
				if($_GET['turno_v'] == 1){
					$dN		= 	$dN	 +0.33;
					$tN		= 	$tN+1;
				}
				if($_GET['turno_n'] == 1){
					$dN		= 	$dN	 + 0.35;
					$tN		= 	$tN+1;
				}	
				
			$query2		=	"UPDATE metas_maquinas SET turnos = ".$tN.", dias =".$dN." WHERE id_meta_diaria = ".$dMM['id_meta_diaria']."  ";
			$res_query2	=	mysql_query($query2) OR die("<p>$query</p><p>".mysql_error()."</p>");
		
	echo'<script type="text/javascript" language="javascript">
	     window.opener.document.location.href = "admin.php?seccion=15&accion=modificar&id_meta='.$dMM['id_meta'].'";
     </script>'; 
	 		
	redirecciona("paros_maquinas.php?accion=listar_paros&id_maquina={$_GET[$indice]}&anio={$_GET[anio]}&mes={$_GET[mes]}&id_maquina={$_GET[id_maquina]}");
	}
}

?>
<style type="text/css">
<!--
body {
	background-color:#FFFFFF;
	background-image:none;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}


-->
</style>
<? if ($generar) {?>
<form name="form" method="post" enctype="multipart/form-data" action="paros_maquinas.php?accion=mostrar_paros&id_maquina=<?=$_REQUEST['id_maquina']?>&area=<?=$_GET['area']?>">
<table width="373" border="0" align="center" cellpadding="1" cellspacing="2">
<tr>
	<td colspan="4" align="center"><h3>Generacion de paros</h3></td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
	<tr>
    	<td align="right"><h3>Maquina:</h3></td>
        <td><? $ea = obtenerDato($_GET['id_maquina'],'maquina','id_maquina'); echo $ea[0].'-'.$ea[1];?></td>
    </tr>
  <tr>
    <td width="197" align="right" class="titulos cabecera"><h3>Numero de Paros: </h3></td>
    <td width="166" align="left"><input name="numero_paros" type="text" id="numero_paros"/></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
     <td colspan="2" align="center"><input type="submit" name="generar" id="generar" value="Generar"></td>
  </tr>  
  <input type="hidden" name="id_maquina" value="<?=$_REQUEST['id_maquina']?>">  
  <input type="hidden" name="area" value="<?=$_REQUEST['area']?>">  
</table>
</form>
<? } if ($mostrar_paros) {?>
<form name="eaea" method="post" action="">
    <table align="center">
      <tr>
        <td colspan="10" align="center"><h3>Paros</h3></td>
      </tr>
      <tr>
        <td align="right" ><h3>Maquina:</h3></td>
        <td width="70" colspan="3"><? $ea = obtenerDato($_GET['id_maquina'],'maquina','id_maquina'); echo $ea[0].'-'.$ea[1];?></td>
      </tr>
      <? 
	  echo UltimoDia($_REQUEST['anio'],$_REQUEST['mes']);
	  for ($i=1, $id_c = 5, $ban	=	1; $i<=UltimoDia($_REQUEST['anio'],$_REQUEST['mes']); ){?>
      <tr>
        <?	for($a=0;$a < 5; $a++,$i++,$id_c = $id_c + 5 + $ban ){ 
		if($i <= UltimoDia($_REQUEST['anio'],$_REQUEST['mes'])){ ?>
        <td>
        	<div  id="marca_<?=$i?>">
            	<table width="100%" style="border:#666666; border:medium" border="1" cellpadding="2" cellspacing="2">
                    <tr>
                      <td width="134" align="center"><h3 onclick=" chekaTodos(<?=$id_c?>,'marca_<?=$i?>');">
                        <?=$i.'-'.$_REQUEST['mes'].'-'.$_REQUEST['anio']?>
                          <input type="hidden" size="12" readonly="readonly" name="fecha[]" id="fecha[]" value="<?=$_REQUEST['anio'].'-'.$_REQUEST['mes'].'-'.$i?>" />
                      </h3></td>
                    </tr>
                    <tr>
                      <td width="134" height="25" align="center" bgcolor="#EEEEEE" class="cabecera titulos"><div>Turnos<br />
                        M
                        <input type="checkbox" value="1" name="turno_m[<?=$i?>]" id="<?=$i?>_turno_m[]" onclick="activo(this.id,<?=$id_c?>,'marca_<?=$i?>')" />
                        V
                        <input type="checkbox" value="1" name="turno_v[<?=$i?>]" id="<?=$i?>_turno_v[]" onclick="activo(this.id,<?=$id_c?>,'marca_<?=$i?>')" />
                        N
                        <input type="checkbox" value="1" name="turno_n[<?=$i?>]" id="<?=$i?>_turno_n[]" onclick="activo(this.id,<?=$id_c?>,'marca_<?=$i?>')" />
                      </div></td>
                    </tr>
                    <tr>
                      <td align="center"><div> Meta <br />
                              <input type="text" 	name="meta_dia[]" id="meta_dia[]" value="0.00" size="10"  />
                              <input type="hidden" 	name="activo[]" id="activo[]" value="0"	 />
                      </div></td>
                    </tr>
                    <tr>
                      <td colspan="4" align="center"></td>
                    </tr>
        		</table>
			</div>
         </td>
        <? } } ?>
      </tr>
      <? } ?>
      <tr>
        <td colspan="6" align="center"><input type="hidden" name="numero_paros" value="<?=$_REQUEST['numero_paros']?>" />
            <input type="submit" name="asignar" id="asignar" value="Asignar" class="contenidos" />
            <input type="hidden" name="id_maquina" id="id_maquina" value="<?=$_REQUEST['id_maquina']?>" />
            <input type="hidden" name="area" value="<?=$_REQUEST['area']?>" />        </td>
      </tr>
    </table>
</form>

<? } if ($modificar) {?>
<form name="form" method="post" enctype="multipart/form-data">
<table width="231" align="center">
<tr>
	<td colspan="4" align="center"><h3>Modificar datos</h3></td>
</tr>
<tr>
	<td width="85">&nbsp;</td>
</tr>
<tr>
	<td align="right"><h3>Maquina:</h3></td>
	<td width="134"  colspan="3"><? $ea = obtenerDato($dato['id_maquina'],'maquina','id_maquina'); echo $ea[2].'-'.$ea[1];?></td>
</tr>
<tr>
        <td colspan="2" align="left">
        <div id="marca_<?=$i?>"><table width="73%" style="border:#666666; border:medium" border="1" cellpadding="2" cellspacing="2" >
<tr>
              <td align="center"><h3>
                <?=fecha($dato['fecha'])?>
              </h3></td>
          </tr>
            <tr>
              <td  height="25" align="center" bgcolor="#EEEEEE" class="cabecera titulos"><div>Turnos<br />
                M
                <input type="checkbox" value="1" <?=($dato['turno_m'] == 1)?"checked":""?> name="turno_m" id="<?=$i?>_turno_m" />
                V
                <input type="checkbox" value="1" <?=($dato['turno_v'] == 1)?"checked":""?> name="turno_v" id="<?=$i?>_turno_v" />
                N
                <input type="checkbox" value="1" <?=($dato['turno_n'] == 1)?"checked":""?> name="turno_n" id="<?=$i?>_turno_n"  />
              </div></td>
          </tr>
            <tr>
              <td align="center"><div> Meta <br />
                <input type="text" 	name="meta_dia" id="meta_dia" value="<?=$dato['meta_dia']?>" size="10"  />
              </div></td>
          </tr>
            <tr>
              <td colspan="4" align="center"></td>
            </tr>
        </table></div></td>
</tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center">
    <input type="hidden" name="id_maquina" id="id_maquina" value="<?=$dato['id_maquina']?>">
   	<input type="submit" name="asignar" id="asignar" value="Aplicar Modificacion" class="contenidos" />
    <input type="hidden" name="id_paros_maquinas" id="id_paros_maquinas" value="<?=$dato['id_paros_maquinas']?>" />
    </td>
  </tr>
</table>
</form>
<? }?>

<? if($listar_paros){

if(isset($_REQUEST['mes']) && isset($_REQUEST['anio']) ){
	$_REQUEST['mes'] = $_REQUEST['mes']; 
	$_REQUEST['anio'] = $_REQUEST['anio'];
}
else {
$_REQUEST['mes'] 	= date('m'); 
$_REQUEST['anio']   = date('Y');
}
$qNumRut="SELECT * FROM paros_maquinas WHERE id_maquina=".$_GET['id_maquina']." AND (MONTH(fecha)  = '".date('m')."' AND YEAR(fecha) = '".date('Y')."') ORDER BY fecha ASC";
  
if(isset($_REQUEST['buscar'])) 
$qNumRut="SELECT * FROM paros_maquinas WHERE id_maquina=".$_GET['id_maquina']." AND (MONTH(fecha)  = '".$_REQUEST['mes']."' AND YEAR(fecha) = '".$_REQUEST['anio']."')  ORDER BY fecha ASC";


$rNumRut=mysql_query($qNumRut);
//$nNumRut=mysql_num_rows($rNumRut);
?>
<table width="350" align="center" cellpadding="2"   cellspacing="2">
<tr>
  <td colspan="5" class="titulos"><strong>Paros Registrados</strong></td>
</tr>
<tr>
	<td colspan="6"><table>
      <tr>
        <td width="323" colspan="2" align="left" valign="middle"><form method="post" name="busca" id="busca">
              <select name="mes" class="style1" id="mes">
				<? for($a=1;$a<=12;$a++){?>
                	<option value="<?=$a?>" <? if($_REQUEST['mes'] == $a) echo "selected";?>><?=$mes[$a]?></option>
                <? } ?>
              </select>
              <select name="anio" class="style1" id="anio">
				<? for($a = 0 ; $a < sizeof($anio) ; $a++){?>
				<option value="<?=$anio[$a]?>" <? if($anio[$a] == date('Y')) echo "selected";?> <? if($_REQUEST['anio'] == $anio[$a]) echo "selected";?>><?=$anio[$a]?></option>
				<? } ?>  
              </select>
              <input name="buscar" type="submit" class="style1" id="buscar" value="Buscar">
          </form></td>
  </tr>
  </table></td>
  </tr>

<tr>
	<td align="right"><h3>Maquina:</h3></td>
	<td colspan="4"><? $ea = obtenerDato($_GET['id_maquina'],'maquina','id_maquina'); echo $ea[0].'-'.$ea[1];?></td>
</tr>
    
<tr bgcolor="#EEEEEE">        
	<td width="95"><h3>Fecha</h3></td>
	<td width="67"><h3>Turnos</h3></td>
	<td width="90"><h3>Metas</h3></td>
	<td colspan="2"><h3>&nbsp;</h3></td>
</tr>
<? 	if(!$rNumRut)
		die("<p>$query</p><p>".mysql_error()."</p>");
		if(mysql_num_rows($rNumRut) == '0'){
			echo ' 
			<table align="center" cellpadding="2"   cellspacing="2">
			<tr>
				<td>&nbsp;</tr>
			</tr>
			<tr>
      			<td width="281" class="style4" align="center">No Hay paros registrados</td>
  			</tr>
			</table>';
	}
		else 
	{

for($a=0;$dNumRut=mysql_fetch_assoc($rNumRut);$a++) { ?>

<tr <? cebra($a);?>>
            <td class="contenidos"><? fecha($dNumRut['fecha'])?></td>
            <td class="contenidos" align="center"><?=($dNumRut['turno_m']==1)?"Matutino":"";?><br /><?=($dNumRut['turno_v']==1)?"Vesper.":"";?><br /><?=($dNumRut['turno_n']==1)?"Noct.":"";?></td>
            <td class="contenidos" align="center"><?=ceil($dNumRut['meta_dia'])?></td>
    <td width="32" align="center" ><a href="paros_maquinas.php?accion=modificar&amp;id_paros_maquinas=<?=$dNumRut['id_paros_maquinas']?>"><img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>
    <td width="32" align="center" ><a href="paros_maquinas.php?accion=eliminar_paros&amp;id_paros_maquinas=<?=$dNumRut['id_paros_maquinas']?>&id_maquina=<?=$_GET['id_maquina']?>&mes=<?=$_REQUEST['mes']?>&anio=<?=$_REQUEST['anio']?>&turno=<?=$dNumRut['turno']?>&fecha=<?=$dNumRut['fecha']?>" onclick="javascript: return confirm('Realmente deseas eliminar este registro?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
  </tr>
          <? }?>
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
</table>
<? 	
	}
}?>

<? if($listar){ 
  $qNumRut="SELECT * FROM maquina ORDER BY area, numero";
  $rNumRut=mysql_query($qNumRut);
  $nNumRut=mysql_num_rows($rNumRut);
  if ($nNumRut>0)
  { 
  for($a=0;$dNumRut=mysql_fetch_assoc($rNumRut);$a++){
  
    if($dNumRut['area'] == 1){
		$Bol_id[$a]		=	$dNumRut['id_maquina'];
		$Bol_marca[$a]	=	$dNumRut['marca'];
		$Bol_num[$a]	=	$dNumRut['numero'];
		$Bol_area[$a]	=	$dNumRut['area'];
	}  
  

	
  	if($dNumRut['area'] == 2 || $dNumRut['area'] == 3){
		$Imp_id[$a]		=	$dNumRut['id_maquina'];
		$Imp_marca[$a]	=	$dNumRut['marca'];
		$Imp_num[$a]	=	$dNumRut['numero'];	
		$Imp_area[$a]	=	$dNumRut['area'];
	}

  	if($dNumRut['area'] == 4){
		$Ext_id[$a]		=	$dNumRut['id_maquina'];
		$Ext_marca[$a]	=	$dNumRut['marca'];
		$Ext_num[$a]	=	$dNumRut['numero'];	
		$Ext_area[$a]	=	$dNumRut['area'];
	} 	

  } 

?>
<table width="341" align="center">

<tr>
          	<td colspan="3"><h3>Impresion</h3></td>
          </tr>
          <? for($a=sizeof($Bol_id);$a < (sizeof($Imp_id)+sizeof($Bol_id));$a++) {?>
          <tr <? cebra($a);?>>
            <td width="261"  class="contenidos"><?=$Imp_num[$a].' - '.$Imp_marca[$a]?></td>
            <td width="32" ><a style="cursor:pointer" onClick="window.open('paros_maquinas.php?accion=listar_paros&amp;id_maquina=<?=$Imp_id[$a]?>', this.target, 'width=400,height=400,scrollbars =yes');"><img src="<?=IMAGEN_MOSTRAR?>" title=" Mostrar paros de esta maquina" border="0"></a></td>
            <td width="32" ><a style="cursor:pointer" onClick="window.open('paros_maquinas.php?accion=generar&amp;id_maquina=<?=$Imp_id[$a]?>&area=<?=$Imp_area[$a]?>', this.target, 'width=400,height=400,scrollbars =yes');"><img src="<?=IMAGEN_MODIFICAR?>" border="0" title=" Agregar paros a esta maquina"></a></td>
  </tr>
          <? }?>
	<tr>
    	<td>&nbsp;</td>
    </tr>
          <tr>
          	<td colspan="3"><h3>Extruder</h3></td>
          </tr>
          <? for($v=(sizeof($Imp_id)+sizeof($Bol_id));$v < (sizeof($Ext_id)+sizeof($Imp_id)+sizeof($Bol_id));$v++) {?>
          <tr <? cebra($v);?>>
            <td  class="contenidos"><?=$Ext_num[$v].' - '.$Ext_marca[$v]?></td>
            <td width="32" ><a style="cursor:pointer" onClick="window.open('paros_maquinas.php?accion=listar_paros&amp;id_maquina=<?=$Ext_id[$v]?>', this.target, 'width=400,height=400,scrollbars =yes');"><img src="<?=IMAGEN_MOSTRAR?>" border="0" title=" Mostrar paros de esta maquina"></a></td>
            <td width="32" ><a style="cursor:pointer" onClick="window.open('paros_maquinas.php?accion=generar&amp;id_maquina=<?=$Ext_id[$v]?>&area=<?=$Ext_area[$v]?>', this.target, 'width=400,height=400,scrollbars =yes');"><img src="<?=IMAGEN_MODIFICAR?>" border="0" title=" Agregar paros a esta maquina"></a></td>
  </tr>
          <? }?>
	<tr>
    	<td>&nbsp;</td>
    </tr>    
    
</table>

<? } else { ?>
<table align="center">
  <tr>
      <td width="281" class="style4" align="center">No existen maquinas registradas</td>
  </tr>
</table>
<? }
}?>

