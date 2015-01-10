<link rel="stylesheet" type="text/css" href="desing/estilos.css">
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
		myajax.Link('actualiza.php?valor='+valor+'&campo='+campo+'&id='+id+'&tabla=operadores&tipo=id_operador');
}
</script>
<body onLoad="myajax = new isiAJAX();">
<?

$tabla	=	"operadores";
$indice	=	"id_operador";

$tabla2	=	"oper_maquina";
$indice2	=	"id_opr_maquina";

$campos	=	describeTabla($tabla,$indice);
$campos2	=	describeTabla($tabla2,$indice2);

		$numDia			= 	date('w');//numero del dia de la semana 0 para Domingo y 6 para sabado
				
		if($numDia >= 0 && $numDia < 4)
	 		$semana 		= 	date('W');//numero de la semana actual
		else 
			$semana 		= 	date('W')+1;//numero de la semana actual


if(!empty($_POST['submit2']))
{

$nArticulos	=	$_POST['numeros'];
	
	for($i=1;$i<=$nArticulos;$i++)
	{
	
		$sufijo				=	$_POST['codigos'][$i];
		$id_maquina		=	intval($_POST['id_maquina_'.$i]);

		$qResumenMaquinaEx	=	"INSERT INTO $tabla2 (id_operador, id_maquina, rol)"."VALUES ('{$_REQUEST[id_operador]}','$id_maquina','{$_REQUEST[rol]}')";
		$rResumenMaquinaEx	=	mysql_query($qResumenMaquinaEx) OR die("<p>$qResumenMaquinaEx</p><p>".mysql_error()."</p>");

			
	}	
	
	
		
		//		$redirecciona	=	true;
	//	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&buscar={$_REQUEST[buscar]}&rol={$_REQUEST['rol']}&nombre={$_REQUEST['nombre']}";
  	
}

	
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
  		
	
	  $query		=	"INSERT INTO $tabla SET $query";
	  $res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	
		$id_operador 	=	mysql_insert_id(); // Id del operador agregado.
		
		$por_asist	=	5;

		
		
		$qSemana	=	"SELECT semana, desde, hasta FROM pre_nomina_calificada WHERE semana = ".$semana." AND YEAR(desde) = '".$_POST['anio']."' GROUP BY semana";
				
		$rSemana	=	mysql_query($qSemana);
		$dSemana	=	mysql_fetch_assoc($rSemana);
		
		$desde		=	$dSemana['desde'];	
		$hasta		=	$dSemana['hasta'];
		$semana2	=	$dSemana['semana'];
	
	
        echo	$ciclo	=	$semana2 - $_REQUEST['semana_ingreso'];
	
	
		for($c = 0 ; $c <= $ciclo; $c++){
		$nuevo =	$_REQUEST['semana_ingreso']+$c;
		$qSemana	=	"SELECT semana, desde, hasta FROM pre_nomina_calificada WHERE semana = $nuevo AND YEAR(desde) = '".$_POST['anio']."' GROUP BY semana DESC";
		
		$rSemana	=	mysql_query($qSemana);
		$dSemana	=	mysql_fetch_assoc($rSemana);
		
		$desde		=	$dSemana['desde'];	
		$hasta		=	$dSemana['hasta'];
		$semana2	=	$dSemana['semana'];
		
				$qNomina	=	"INSERT INTO pre_nomina_calificada (desde, hasta, por_asist,punt,prod, id_operador, semana)".
								" VALUES('$desde','$hasta','$por_asist','5','10','$id_operador','$nuevo')";
				$rNomina	=	mysql_query($qNomina);
				$dias = array("J","V","S","D","L","M","Mi");
				
	
				for($dia = 0  ; $dia < 7 ; $dia++ ){
					
								$fecha	=	$desde;
								$nueva_fecha 	= explode("-", $fecha);
		
									$ano_avanza = $nueva_fecha[0];
									$mes_avanza = $nueva_fecha[1];
									$dia_avanza = $nueva_fecha[2]+$dia;
									$dia_ultimo	=	UltimoDia($ano_avanza,$mes_avanza);
									
									if($dia_avanza > $dia_ultimo){
										$dia_avanza	=	$dia_avanza - $dia_ultimo;
										$mes_avanza	=	$mes_avanza + 1;	
											if($mes_avanza > 12){
												$mes_avanza	=	1;
												$ano_avanza	=	$ano_avanza + 1;
											}
									} 
									
								 $fecha_ideal	=	$ano_avanza.'-'.$mes_avanza.'-'.$dia_avanza;	

					
				$qResumenAsistencias	=	"INSERT INTO asistencias (fecha, id_operador, semana, dia) ".
											"VALUES ('$fecha_ideal','$id_operador','$nuevo', '$dias[$dia]')";
											
				$rResumenAsistencias	=	mysql_query($qResumenAsistencias);	
			}
	}
	
	$redirecciona	=	true;
	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar&buscar=".$_REQUEST['area']."";
}

$listar =	true;
if(!empty($_GET['accion']))
{
	$listar		=	($_GET['accion']=="listar")?true:false;
	$nuevo		=	($_GET['accion']=="nuevo")?true:false;
	if(!empty($_GET[$indice]) && is_numeric($_GET[$indice]))
	{
		$maquina	=	($_GET['accion']=="maquina")?true:false;
		$mostrar	=	($_GET['accion']=="mostrar")?true:false;
		$eliminar	=	($_GET['accion']=="eliminar")?true:false;
		$eliminar2	=	($_GET['accion']=="eliminar2")?true:false;
		$eliminarOp	=	($_GET['accion']=="eliminarOp")?true:false;
		$modificar	=	($_GET['accion']=="modificar")?true:false;
		$regresarEmpleado	=	($_GET['accion']=="regresarEmpleado")?true:false;
	}
	
	if($mostrar || $modificar || $maquina)
	{
		$query_dato	=	"SELECT * FROM $tabla WHERE ($indice={$_GET[$indice]})";
		$res_dato	=	mysql_query($query_dato) OR die("<p>$query_dato</p><p>".mysql_error()."</p>");
		$dato		=	mysql_fetch_assoc($res_dato);
		
		$qMaquina	=	"SELECT * FROM $tabla2 WHERE ($indice={$_GET[$indice]})";
		$rMaquina	=	mysql_query($qMaquina) OR die("<p>$qMaquina</p><p>".mysql_error()."</p>");
		
		
	}
	
	if($eliminar)
	{
		$q_eliminar	=	"UPDATE $tabla SET activo = 1 WHERE ($indice={$_GET[$indice]}) LIMIT 1";
		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");
		$redirecciona	=	true;
		
		if(isset($_REQUEST['buscar']))
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&buscar={$_GET[buscar]}&rol={$_GET[rol]}";
		else if(!isset($_REQUEST['buscar']) && !isset($_REQUEST['rol']) )
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}";
		else if(isset($_REQUEST['buscar']) )
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&buscar={$_GET[buscar]}&rol={$_GET[rol]}";
	}
	if($eliminarOp)
	{
		$q_eliminar	=	"DELETE FROM $tabla WHERE ($indice={$_GET[$indice]}) LIMIT 1";
		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");
		$redirecciona	=	true;
		
		if(isset($_REQUEST['buscar']))
			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&buscar={$_GET[buscar]}&rol={$_GET[rol]}&activos=eliminados";
		else if(!isset($_REQUEST['buscar']) && !isset($_REQUEST['rol']) )
			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&activos=eliminados";
		else if(isset($_REQUEST['buscar']) )
			$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&buscar={$_GET[buscar]}&rol={$_GET[rol]}&activos=eliminados";
	}

	if($regresarEmpleado)
	{
		$q_eliminar	=	"UPDATE $tabla SET activo = 0 WHERE ($indice={$_GET[$indice]}) LIMIT 1";
		$r_eliminar	=	mysql_query($q_eliminar) OR die("<p>$q_eliminar</p><p>".mysql_error()."</p>");
		$redirecciona	=	true;
		
		if(isset($_REQUEST['buscar']))
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&buscar={$_GET[buscar]}&rol={$_GET[rol]}";
		else if(!isset($_REQUEST['buscar']) && !isset($_REQUEST['rol']) )
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}";
		else if(isset($_REQUEST['buscar']) )
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&buscar={$_GET[buscar]}&rol={$_GET[rol]}";
	}

	if($eliminar2)
	{
		$q_eliminar2	=	"DELETE FROM $tabla2 WHERE ($indice2={$_GET[$indice2]}) LIMIT 1";
		$r_eliminar2	=	mysql_query($q_eliminar2) OR die("<p>$q_eliminar2</p><p>".mysql_error()."</p>");
		$redirecciona	=	true;
		$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=maquina&$indice={$_GET[id_operador]}&area={$_GET[area]}&rol={$_REQUEST[rol]}";
	}	
}
?>
<?php if($nuevo || $modificar) { ?>
<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>" method="post">
  <br />
  <table align="center">
<tr>
	<td class="style7" align="left">Nombre</td>
	<td class="titulos">
		<input name="nombre" type="text" class="style1" id="nombre" value="<?php if($modificar) echo $dato['nombre']; ?>" size="30" maxlength="60">	</td>
</tr>

<tr>
  <td class="style7" align="left">Nomina no.</td>
  <td align="left" class="contenidos"><span class="titulos">
    <input name="numnomina" type="text" class="style1" id="numnomina" value="<?php if($modificar) echo $dato['numnomina']; ?>" size="30" maxlength="60" />
  </span></td>
</tr>
<tr>
	<td class="style7">Area</td>
    <td><select name='area' id='area'>
    <option value="0" <? if(($modificar && $dato['area']== 0) || $_REQUEST['buscar'] == 0) echo "selected";?>>Elige</option>
	<option value="1" <? if(($modificar && $dato['area']== 1) || $_REQUEST['buscar'] == 1) echo "selected";?>>Extruder</option>
    <option value="3" <? if(($modificar && $dato['area']== 3) || $_REQUEST['buscar'] == 3) echo "selected";?>>Impresion</option>
    <option value="2" <? if(($modificar && $dato['area']== 2) || $_REQUEST['buscar'] == 2) echo "selected";?>>Camiseta</option>    
    <option value="6" <? if(($modificar && $dato['area']== 6) || $_REQUEST['buscar'] == 6) echo "selected";?>>RPS</option>
    <option value="7" <? if(($modificar && $dato['area']== 7) || $_REQUEST['buscar'] == 7) echo "selected";?>>SF</option>
    <option value="4" <? if(($modificar && $dato['area']== 4) || $_REQUEST['buscar'] == 4) echo "selected";?>>Mantenimiento</option>
    <option value="5" <? if(($modificar && $dato['area']== 5) || $_REQUEST['buscar'] == 5) echo "selected";?>>Empaque</option>
    <option value="8" <? if(($modificar && $dato['area']== 8) || $_REQUEST['buscar'] == 8) echo "selected";?>>Almacen A</option>
    <option value="9" <? if(($modificar && $dato['area']== 9) || $_REQUEST['buscar'] == 9) echo "selected";?>>Almacen B</option>
	</select></td>
</tr>
<tr>
	<td class="style7" align="left">Ingreso:</td>
    <td><select name="semana_ingreso">
    		<? 
		$qSemana	=	"SELECT semana FROM pre_nomina_calificada GROUP BY semana ORDER BY semana ASC";
		$rSemana	=	mysql_query($qSemana);
		
		for($c = 0; $dSemana	=	mysql_fetch_assoc($rSemana); $c++){?>
        <option value="<?=$dSemana['semana']?>" <? if($semana == $dSemana['semana']) echo 'selected';?> >Semana no. <?=$dSemana['semana']?></option>
        <? } ?>
        </select>
        <select name="anio" id="anio">
        	<option <?=(date('Y')== '2007')?' selected':''?>>2007</option>
        	<option <?=(date('Y')== '2008')?' selected':''?>>2008</option>
        	<option <?=(date('Y')== '2009')?' selected':''?>>2009</option>
        	<option <?=(date('Y')== '2010')?' selected':''?>>2010</option>
        	<option <?=(date('Y')== '2011')?' selected':''?>>2011</option>
        	<option <?=(date('Y')== '2012')?' selected':''?>>2012</option>
        	<option <?=(date('Y')== '2013')?' selected':''?>>2013</option>
        	<option <?=(date('Y')== '2014')?' selected':''?>>2014</option>
        	<option <?=(date('Y')== '2015')?' selected':''?>>2015</option>
        	<option <?=(date('Y')== '2016')?' selected':''?>>2016</option>
        	<option <?=(date('Y')== '2017')?' selected':''?>>2017</option>
        	<option <?=(date('Y')== '2018')?' selected':''?>>2018</option>
        	<option <?=(date('Y')== '2019')?' selected':''?>>2019</option>
        </select>
     </td>
</tr>
<tr>
	<td class="style7" align="left">Puesto:</td>
	<td><input type="text" size="30" name="puesto" id="puesto"></td>
</tr>
<tr>
	<td class="style7" align="left">Nave:</td>
	<td><input type="text" size="30" name="nave" id="nave"></td>
</tr>
<tr>
  <td class="style7" align="left">Rol no.</td>
  <td align="left" class="contenidos">
    <select name="rol" id="rol">
		<? for($a=1; $a <= 6 ; $a++){ ?>
        <option value="<?=$a?>" <? if($modificar && $dato['rol']== $a) echo "selected";?> ><?=$a?></option>
        <? } ?>
    </select>
	</td>
</tr>
<tr>
	<td colspan="2" class="style7"><? if($nuevo){ ?> <? } else { ?> Maquinas asignadas: <? } ?>  </td>
</tr>
<? 	if($modificar){	while($dMaquina		=	mysql_fetch_assoc($rMaquina)){?>
<tr><td colspan="2" ><table><tr>
    <td class="style7" align="left"><b> Maquina no. <?
    $qMaquinas = "SELECT numero, area FROM maquina WHERE id_maquina = '".$dMaquina['id_maquina']."'";
	$rMaquinas = mysql_query($qMaquinas);
	$dMaquinas = mysql_fetch_assoc($rMaquinas);
	echo $dMaquinas['numero']; 
	echo " - ";
	if ($dMaquinas['area'] == 1) echo "Camiseta";
	if ($dMaquinas['area'] == 2) echo "Impresión";
	if ($dMaquinas['area'] == 3) echo "Linea de Impresión";
	if ($dMaquinas['area'] == 4) echo "Extruder";
	if ($dMaquinas['area'] == 5) echo "RPS";
	if ($dMaquinas['area'] == 6) echo "SF";


	?></b></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&<?=$indice?>=<?=$dato['id_operador']?>&accion=eliminar2&<?=$indice2?>=<?=$dMaquina['id_opr_maquina']?>" onClick="javascript: return confirm('Realmente deseas quitar esta maquina ?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
</tr></table></td></tr>
<? }
} ?>
<tr>
	<td class="contenidos" align="right" colspan="2">
	<?php if($modificar) { ?>
		<input type="hidden" name="<?=$indice?>" value="<?=$_GET[$indice]?>">
	<?php } ?>
	    <input type="button" class ="style4" name="cancel" value="Cancelar" onclick="javascript: history.go(-1);">
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
  if($dato['area'] == 2) echo "Camiseta";
  if($dato['area'] == 3) echo "Impresion";
  if($dato['area'] == 4) echo "Mantenimiento";
  if($dato['area'] == 5) echo "Empaque";
  if($dato['area'] == 6) echo "RPS";
  if($dato['area'] == 7) echo "SF";
  if($dato['area'] == 8) echo "Almacen A";
  if($dato['area'] == 9) echo "Almacen B";

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
               html += 'Maquina ' + i + ': <input type="hidden" name="codigos[]" value="'+ i +'"> <select style="width:150" name="id_maquina_'+ i +'" class="contenidos" id="id_maquina_'+ i +'">      <? if($_REQUEST['area'] == 1 || $_REQUEST['area'] == 4) 	$qMaquina = "SELECT * FROM maquina WHERE area = ".$_REQUEST['area']." order by numero";  if($_REQUEST['area'] == 2 || $_REQUEST['area'] == 3) 	$qMaquina = "SELECT * FROM maquina WHERE area = 2 OR area = 3 ORDER BY area,numero";  $rMaquina = mysql_query($qMaquina); while ($dMaquina = mysql_fetch_assoc($rMaquina)){?>                 	<option value="<?=$dMaquina['id_maquina']?>"><?=$dMaquina['numero']?> - <?  if($dMaquina['area'] == 1)                  		echo "Bolseo";           	if($dMaquina['area'] == 2)                 		echo "Impresion";                 	if($dMaquina['area'] == 3)                 		echo "Linea de Impr.";                 	if($dMaquina['area'] == 4)    echo "Extruder"; echo ($dMaquina[tipo_d]==1)?" HD":" BD"?></option> <? } ?>     </select><br>'; 
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
         </table>      
     </td>
   </tr>
<tr>
	<td colspan="2" class="style7"><br>
	  Maquinas asignadas:<input type="hidden" name="area" value="<?=$_REQUEST['area']?>">
      <input type="hidden" name="rol" value="<?=$_REQUEST['rol']?>">
      <input type="hidden" name="buscar" value="<?=$_REQUEST['buscar']?>">
      <input type="hidden" name="nombre" value="<?=$_REQUEST['nombre']?>">
      </td>
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
	if ($dMaquinas['area'] == 1) echo "Camiseta";
	if ($dMaquinas['area'] == 2) echo "Impresión";
	if ($dMaquinas['area'] == 3) echo "Linea de Impresión";
	if ($dMaquinas['area'] == 4) echo "Extruder";
	if ($dMaquinas['area'] == 5) echo "RPS";
	if ($dMaquinas['area'] == 6) echo "SF";
	?></b></td>
<td width="32"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&<?=$indice?>=<?=$dato['id_operador']?>&accion=eliminar2&<?=$indice2?>=<?=$dMaquina['id_opr_maquina']?>&area=<? if($dato['area'] == 1) echo "4";
																																							  if($dato['area'] == 2) echo "1";
																																							  if($dato['area'] == 3) echo "3";?>&rol=<?=$_REQUEST['rol']?>" onClick="javascript: return confirm('Realmente deseas quitar esta maquina ?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
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

if($_REQUEST['activos'] == 'eliminados'){
	$where 		= 	"  activo = 1 ";
	$tituloElim	=	"Empleados dados de baja";	 
	$linkElim 	=	"Volver a empleados";
	$linkReal	=	"";
} else if($_REQUEST['activos'] == 'todos'){
	$where		=	" 1";
	$tituloElim	=	"";	 
	$linkElim 	=	"Ver empleados dados de baja";
	$linkReal	=	"&eliminados";
} else if($_REQUEST['activos'] == 'sinbaja' || !isset($_REQUEST['activos'])){
	$where		=	" activo = 0 ";
	$tituloElim	=	"";	 
	$linkElim 	=	"Ver empleados dados de baja";
	$linkReal	=	"&eliminados";

}
$qListar	=	"SELECT * FROM $tabla  WHERE ".$where." ORDER BY nombre ASC ";
$rListar	=	mysql_query($qListar) OR die("<p>$qListar</p><p>".mysql_error()."</p>");

if(mysql_num_rows($rListar) > 0) { ?>
<table width="100%" align="center"  class="style2" cellpadding="1" cellspacing="1">

 	<tr>
		<td colspan="9" align="center">
			<? if($agregar_empleado){ ?><span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo<? if(isset($_REQUEST['buscar'])){?>&buscar=<?=$_REQUEST['buscar']; }?>" class="style7">Nuevo Empleado</a>
	  <span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br>
	  <br /><? } ?></td>
  </tr>
  <tr>
  	<td colspan="9" align="center">
    	<table width="100%" align="center">
  		 <tr>
		  <td width="16%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=4"><img src="images/mantenimiento.jpg" border="0"></a></td>
		  <td width="16%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=6"><img src="images/rps.jpg" border="0"></a></td>
          <td width="16%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=5"><img src="images/empaque.jpg" border="0"></a></td>
          <td width="16%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=7"><img src="images/sf.jpg" border="0"></a></td>
          <td width="16%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=8"><img src="images/almacena.jpg" border="0"></a></td>
          <td width="16%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=9"><img src="images/almacenb.jpg" border="0"></a></td>
          </tr>
          <tr>
          	<td colspan="66">
            	<table width="100%" >
                <tr>
                  <td width="18%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=1"><img src="images/extruder.jpg" border="0"></a></td>
                  <td width="18%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=3"><img src="images/impresion.jpg" border="0"></a></td>
                  <td width="18%" align="center" valign="middle"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&buscar=2"><img src="images/bolseo.jpg" border="0"></a></td>
                  
                </tr>   
                </table>
            </td>
          </tr>
      </table>
    </td>
  </tr>
    <tr>
  	<td>&nbsp;</td>
  </tr>
 <tr>
	<td colspan="10"><table>
      <tr>
        <td width="100%" colspan="10" align="left" valign="middle" >
        <form action="admin.php?seccion=<?=$_REQUEST['seccion']?>" method="get" name="busca" id="busca">
            <span class="style7">Buscar por:</span> <input type="hidden" name="buscar" value="<?=$_REQUEST['buscar']?>">     
              &nbsp;&nbsp;Rol : <select name="rol" class="style1" id="rol">
              <option value="x">Todos</option>
              <option value="1" <? if($_REQUEST['rol'] == 1) echo "selected";?>>1</option>
              <option value="2" <? if($_REQUEST['rol'] == 2) echo "selected";?>>2</option>
              <option value="3" <? if($_REQUEST['rol'] == 3) echo "selected";?>>3</option>
              <option value="4" <? if($_REQUEST['rol'] == 4) echo "selected";?>>4</option>
              </select>
              <select name="activos" id="activos">
              	<option value="sinbaja"  <? if($_REQUEST['activos'] == 'sinbaja') echo "selected";?>>Sin baja</option>
              	<option value="eliminados"  <? if($_REQUEST['activos'] == 'eliminados') echo "selected";?>>Con baja</option>
              	<option value="todos"  <? if($_REQUEST['activos'] == 'todos') echo "selected";?>>Ambos</option>
              </select>
              &nbsp;&nbsp;Nombre: 
              <input name="nombre" id="nombre" size="35" value="<?=$_REQUEST['nombre']?>">
              &nbsp;&nbsp;
             <input type="submit" value="Buscar">
             <input type="hidden" value="14" name="seccion">
             </form></td>
  </tr>
  </table></td>
  </tr>
  <?php if($_GET['activos'] == 'eliminados'){ ?>
  <tr>
  	<td style="background:#aa0000" colspan="10"><div style="text-align:center"><h3 style="background:#cc0000; border-color:#cc0000"><?php echo $tituloElim?></h3></div></td>
  </tr>
  <?php } ?>
<tr>
    <td class="style7"><h3><a href="admin.php?seccion=<?=$_REQUEST['seccion']?><? if(isset($_REQUEST['buscar'])){	?>&buscar=<?=$_REQUEST['buscar']?><? } ?>&rol=<?=$_REQUEST['rol']?>&nombre=<?=$_REQUEST['nombre']?>&acomodar=numnomina">Nomina</a></h3></td>
	<td align="left" class="style7" ><h3><a href="admin.php?seccion=<?=$_REQUEST['seccion']?><? if(isset($_REQUEST['buscar'])){	?>&buscar=<?=$_REQUEST['buscar']?><? } ?>&rol=<?=$_REQUEST['rol']?>&nombre=<?=$_REQUEST['nombre']?>&nave=<?=$_REQUEST['nave']?>&acomodar=nombre">Nombre</a></h3></td>
	<td align="center" class="style7" ><h3><a href="admin.php?seccion=<?=$_REQUEST['seccion']?><? if(isset($_REQUEST['buscar'])){	?>&buscar=<?=$_REQUEST['buscar']?><? } ?>&rol=<?=$_REQUEST['rol']?>&nombre=<?=$_REQUEST['nombre']?>&nave=<?=$_REQUEST['nave']?>&acomodar=nave">Nave</a></h3></td>
    <td align="center" class="style7" ><h3><a href="admin.php?seccion=<?=$_REQUEST['seccion']?><? if(isset($_REQUEST['buscar'])){	?>&buscar=<?=$_REQUEST['buscar']?><? } ?>&rol=<?=$_REQUEST['rol']?>&nombre=<?=$_REQUEST['nombre']?>&nave=<?=$_REQUEST['nave']?>&acomodar=rol">Rol </a></h3></td>
    <td  align="center" class="style7" ><h3><a href="admin.php?seccion=<?=$_REQUEST['seccion']?><? if(isset($_REQUEST['buscar'])){	?>&buscar=<?=$_REQUEST['buscar']?><? } ?>&rol=<?=$_REQUEST['rol']?>&nombre=<?=$_REQUEST['nombre']?>&nave=<?=$_REQUEST['nave']?>&acomodar=area">Area</a></h3></td>
    <td align="left" class="style7"><h3>Puesto</h3></td>
    <td  class="style7"><h3>Status</h3></td>
    <td  align="left" class="style7" ><h3>Maquinas</h3></td>
	<td colspan="2"><h3>&nbsp;</h3></td>
  </tr>

<?php


	  if(!isset($_REQUEST['buscar']) || $_REQUEST['buscar'] == "" ){
			if($_REQUEST['nombre']!= '' && $_REQUEST['rol'] == 'x')
					$qListar1	= "SELECT * FROM $tabla  WHERE  nombre LIKE '%".$_REQUEST['nombre']."%' AND {$where} ORDER BY ";
			else if( (isset($_REQUEST['rol']) && $_REQUEST['rol'] != 'x') && (!isset($_REQUEST['nombre']) || $_REQUEST['nombre'] != ''))			
					$qListar1	= "SELECT * FROM $tabla WHERE rol = ".$_REQUEST['rol']."  AND  {$where} ORDER BY ";

			else if($_REQUEST['nombre'] != "" && $_REQUEST['rol'] != 'x')			
					$qListar1	= "SELECT * FROM $tabla  WHERE nombre LIKE '%".$_REQUEST['nombre']."%' AND rol = ".$_REQUEST['rol']." AND  {$where} ORDER BY ";
			else 
					$qListar1	= "SELECT * FROM $tabla WHERE  {$where} ORDER BY ";
				if(isset($_REQUEST['acomodar']))
					$qListar1 .= " ".$_REQUEST['acomodar'].", ";
					
		  	 $qListar1 .= "  numnomina, nombre,  rol, area  ASC ";
	  }
	  
	include "debugger.php";		  
	  if( $_REQUEST['buscar'] != ''){

	     if((!isset($_REQUEST['rol']) || $_REQUEST['rol'] == 'x' || $_REQUEST['rol'] == '') && ($_REQUEST['nombre'] == "" || !isset($_REQUEST['nombre'])) ) {
	     	//if($_REQUEST['buscar']=="2")
	     	//		$qListar1	= "SELECT * FROM $tabla  WHERE area = 2 OR area = 6 OR area = 7 AND  {$where}  ORDER BY ";
	     	//else	
			 $qListar1	= "SELECT * FROM $tabla  WHERE area = ".$_REQUEST['buscar']." AND  {$where}  ORDER BY ";
	     }    
	     
		 else if(isset($_REQUEST['rol']) && !isset($_REQUEST['nombre']) || ($_REQUEST['rol'] != '' && $_REQUEST['rol'] != 'x'))
			$qListar1	= "SELECT * FROM $tabla  WHERE area = ".$_REQUEST['buscar']." AND rol = '".$_REQUEST['rol']."' AND  {$where}  ORDER BY ";
	
		 else if(isset($_REQUEST['nombre']) && (!isset($_REQUEST['rol']) || $_REQUEST['rol'] == 'x' || $_REQUEST['rol'] == '')  && $_REQUEST['nombre'] != "") 
			$qListar1	= "SELECT * FROM $tabla  WHERE area = ".$_REQUEST['buscar']." AND nombre LIKE '%".$_REQUEST['nombre']."%' AND  {$where}  ORDER BY ";
	
		 else if(isset($_REQUEST['rol'])  && isset($_REQUEST['nombre']) && $_REQUEST['nombre'] != "" || ($_REQUEST['rol'] != '' && $_REQUEST['rol'] != 'x') ) 
			$qListar1	= "SELECT * FROM $tabla  WHERE area = ".$_REQUEST['buscar']." AND rol = '".$_REQUEST['rol']."' AND nombre LIKE '%".$_REQUEST['nombre']."%' AND  {$where}  ORDER BY ";
	
		 if(isset($_REQUEST['acomodar']))
					$qListar1 .= " ".$_REQUEST['acomodar'].", ";
			$qListar1 .= " numnomina, rol, nombre ASC ";
	  }
	  
	  
	   pDebug($qListar1);
		$rListar1	= mysql_query($qListar1);
		
	if(!$rListar1)
		die("<p>$query</p><p>".mysql_error()."</p>");
		
						if(mysql_num_rows($rListar1) == '0'){
				
			echo " <tr>
                        <td  valign='top' colspan='10' class='style4' align='center'>NO SE ENCONTRARON REGISTROS</td>
                   </tr>";
		}
		else {
?>
<?php for($k = 0 ;$dListar1=	mysql_fetch_assoc($rListar1);$k++) { 
						if($dListar1['activo'] == 1)
							$coloresE	=	"style=\"background:#cc0000; color:#fff !important; text-align:center\"";
						else	
							$coloresE = "";
			?>
		<tr  <? if(bcmod(intval($k),2) == 0){ 
								$back 	= "#EEEEEE";  
								$frente = "#B4C3EC";
								echo "bgcolor='".$back."'";
								}  else { 
								$back 	= "#FFFFFF";  
								$frente = "#B4C3EC";
								echo "bgcolor='".$back."'";
	
			
								}?> onMouseOver="this.bgColor='<?=$frente?>'" onMouseOut="this.bgColor='<?=$back?>'">
<td <?=$coloresE?> width="50" align="center" class="style7" ><input <?=$coloresE?> size="8" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'numnomina', <?=$dListar1['id_operador'];?>)" class="inputoff" id="a<?=$dListar1['id_operador'];?>" value="<?=$dListar1['numnomina']?>" style="text-align:center"  <? if(!$modificar_emp) echo "readonly";  ?>  /></td>
<td width="242" align="left" class="style7" ><input size="30" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre', <?=$dListar1['id_operador'];?>)" class="inputoff" id="b<?=$dListar1['id_operador'];?>" value="<?=$dListar1['nombre']?>"  /></td>
<td width="242" align="center" class="style7" ><input size="3" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nave', <?=$dListar1['id_operador'];?>)" class="inputoff" id="g<?=$dListar1['id_operador'];?>" value="<?=$dListar1['nave']?>"  style="text-align:center"  <? if(!$modificar_emp) echo "readonly";  ?>  /></td>
<td width="29" align="center" class="style7" ><input size="4" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'rol', <?=$dListar1['id_operador'];?>)"    class="inputoff" id="c<?=$dListar1['id_operador'];?>" value="<?=$dListar1['rol']?>" style="text-align:center"  <? if(!$modificar_emp) echo "readonly";  ?> /></td>
<td align="left" >
<select name='area'  <? if(!$modificar_emp) echo "disabled";  ?> onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'area', <?=$dListar1['id_operador'];?>)" onChange='cargaContenido(this.id)' class="inputoff" id="d<?=$dListar1['id_operador'];?>" style="text-align:center">
	<option value="1" <? if($dListar1['area']== 1) echo "selected";?>>Extruder</option>
    <option value="3" <? if($dListar1['area']== 3) echo "selected";?>>Impresion</option>
    <option value="2" <? if($dListar1['area']== 2) echo "selected";?>>Camiseta</option>
    <option value="6" <? if($dListar1['area']== 6) echo "selected";?>>RPS</option> 
    <option value="7" <? if($dListar1['area']== 7) echo "selected";?>>SF</option> 
    <option value="4" <? if($dListar1['area']== 4) echo "selected";?>>Mantenimiento</option>     
    <option value="5" <? if($dListar1['area']== 5) echo "selected";?>>Empaque</option>     
    <option value="8" <? if($dListar1['area']== 8) echo "selected";?>>Almacen A</option> 
    <option value="9" <? if($dListar1['area']== 9) echo "selected";?>>Almacen B</option>     
   	</select>
</td>
<td><input type="text" size="15" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'puesto', <?=$dListar1['id_operador'];?>)"    class="inputoff" id="e<?=$dListar1['id_operador'];?>" value="<?=$dListar1['puesto']?>" style="text-align:center"  <? if(!$modificar_emp) echo "readonly";  ?> /></td>
<td><select name="status" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'status', <?=$dListar1['id_operador'];?>)" onChange='cargaContenido(this.id)' class="inputoff" id="f<?=$dListar1['id_operador'];?>" style="text-align:center"  <? if(!$modificar_emp) echo "disabled";  ?> >
	<option value="0" <? if($dListar1['status'] == 0) echo "selected";?> >Activo</option>
    <option value="1" <? if($dListar1['status'] == 1) echo "selected";?>>Inactivo</option>
   </select>
    <td align="left"><?

$qMaquinas = "SELECT * FROM oper_maquina INNER JOIN maquina ON oper_maquina.id_maquina = maquina.id_maquina WHERE id_operador = ".$dListar1['id_operador']." ORDER BY maquina.numero ASC";
$rMaquinas = mysql_query($qMaquinas);
while($dMaquinas = mysql_fetch_assoc($rMaquinas)){
	echo $dMaquinas['numero'].'&nbsp;&nbsp;';
}

 ?></td>
    <td width="10">
    	<? if($maquinas_asignadas && $dListar1['activo'] == 0 ){ ?>
    	<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&rol=<?=$_REQUEST['rol']?>
			&buscar=<?=$_REQUEST['buscar']?>
			&accion=maquina
			&<?=$indice?>=<?=$dListar1['id_operador']?>&area=<? if($dListar1['area'] == 1) echo "4";if($dListar1['area'] == 2) echo "1";if($dListar1['area'] == 3) echo "3";?>&nombre=<?=$_REQUEST['nombre']?>">
			<img src="<?=IMAGEN_MODIFICAR?>" border="0" alt="AGREGAR MAQUINAS">
		</a>
		<? } else if($dListar1['activo'] == 1 ){ ?>
		<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&rol=<?=$_REQUEST['rol']?>
			&buscar=<?=$_REQUEST['buscar']?>
			&accion=regresarEmpleado
			&<?=$indice?>=<?=$dListar1['id_operador']?>&area=<? if($dListar1['area'] == 1) echo "4";if($dListar1['area'] == 2) echo "1";if($dListar1['area'] == 3) echo "3";?>&nombre=<?=$_REQUEST['nombre']?>" onClick="javascript: return confirm('Desea quitar la baja a este empleado ?');">
			<img src="img/mostrar.jpg" border="0" alt="Quitar baja">
		</a>		
		<?php } else {} ?>
	</td>


<?php if($dListar1['activo'] == 0 ){ ?>
<td width="10"><? if($eliminar_emp){ ?><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?><? if(isset($_REQUEST['rol'])){?>&rol=<?=$_REQUEST['rol']?><? } if(isset($_REQUEST['buscar'])){?>&buscar=<?=$_REQUEST['buscar']?><? } ?>&amp;accion=eliminar&<?=$indice?>=<?=$dListar1['id_operador']?>" onClick="javascript: return confirm('Realmente desea dar de baja a este empleado ? \n No se borrará del sistema para futuras consultas o reingresos.');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a><? } ?></td>
<?php } else { ?>
<td width="10"><? if($eliminar_emp){ ?><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?><? if(isset($_REQUEST['rol'])){?>&rol=<?=$_REQUEST['rol']?><? } if(isset($_REQUEST['buscar'])){?>&buscar=<?=$_REQUEST['buscar']?><? } ?>&amp;accion=eliminarOp&<?=$indice?>=<?=$dListar1['id_operador']?>" onClick="javascript: return confirm('Realmente deseas eliminar totalmente del sistema a este empleado?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a><? } ?></td>
<?php } ?>
</tr>

<?php }?>
   <tr>
   		<td colspan="9">&nbsp;</td>
   </tr> 
   	<tr>
		<td colspan="9" align="center">
			<? if($agregar_empleado){ ?><span class="titulos"><br />
			&nbsp;<strong>|</strong>&nbsp;</span>
			<a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7">Nuevo Empleado</a>
			<span class="titulos">&nbsp;<strong>|</strong>&nbsp;</span>		<br /><? } ?></td>
  </tr>
</table>
<blockquote>
  <p><br />
    <?php } } else { ?>
  </p>
</blockquote>
<table align="center" width="700">
<tr>
		<td class="style7" align="center"><br />
	      <br />
	      <br />
	      Aun no hay operadores registrados en la base de datos<br />
	    <br /></td>
  </tr>
  <tr>
	  <td align="center">
		  <? if($agregar_empleado){ ?><span class="style7">&nbsp;<strong><br />
		  |</strong>&nbsp;</span>
		  <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=nuevo" class="style7"><strong>Nuevo Empleado</strong></a>
		  <span class="style7">&nbsp;<strong>|</strong>&nbsp;<br />
		  <br />
		  </span><? } ?></td>
  </tr>
</table>
	
	<?php } ?>
<?php } ?>
</body>
