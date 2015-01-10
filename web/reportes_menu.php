<script type="text/javascript" src="libs/checkboxes_jquery_plug.js"></script>
 <script>

     function revisa_check(divi,y){
		var contador = 0;
			try {
				$j("#"+divi).find("input[@type$='checkbox']").each(function(){
						if(this.checked == false)
							contador = contador + 1;
							
						});	
									
				if(contador == y)
					return false;
				if(contador == 0)
					return true;
			}catch(mierror){
		//	   alert("Error detectado: " + mierror.description)
			}
	}
	

	function chekaTodos(y,divi)
	{
		var x =  revisa_check(divi,y);
		
			if(x == false){
				$j("#"+divi).checkCheckboxes();
			}
			else
			{
				$j("#"+divi).unCheckCheckboxes();
			}
		
	}	 
</script>
	


<link rel="stylesheet" type="text/css" media="screen" href="DatePicker.css" />

<?php include "libs/conectar.php"; 


$tabla	=	"configuracion_bolseo";
$indice	=	"id_configuracion";

$campos	=	describeTabla($tabla,$indice);


if(!empty($_POST['guardar']))
{
    
	if(	isset($_POST[$indice]) )  $id = intval( $_POST[$indice] );
	else
	{
		$qId		=	"SELECT MAX($indice) FROM $tabla";
		$rId		=	mysql_query($qId);
		$dId		=	mysql_fetch_row($rId);
		$id			=	intval($dId[0]+1);
	}

	$query			=	"";
	foreach($campos as $clave)
		if(isset($_POST[$clave]))
		{
			if($clave == "desde"){
				$_POST[$clave] = fecha_tablaInv($_REQUEST['desde']);
			}
			if($clave == "hasta"){
				$_POST[$clave] = fecha_tablaInv($_REQUEST['hasta']);
			}
			
			$query		.=	(($query=="")?"":",")."$clave='".$_POST[$clave]."'";
		}	
  		
	
   if(!empty($_POST[$indice] )){   
	   $query		=	"UPDATE $tabla SET ".$query." WHERE ($indice=".$_POST[$indice].")";
	}else { 
	echo	  $query		=	"INSERT INTO $tabla SET $query";
	}


	$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	$redirecciona	=	true;
	$ruta		=	"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin.php?seccion={$_GET[seccion]}&accion=listar";
}



$listar =	false;
if(!empty($_GET['accion']))
{
	$listar				=	($_GET['accion']=="listar")?true:false;
	$agregar		=	($_GET['accion']=="agregar")?true:false;
	$tipos		=	($_GET['accion']=="tipos")?true:false;
	
	if((!empty($_GET[$indice]) && is_numeric($_GET[$indice])))
	{
		
		$eliminar	=	($_GET['accion']=="eliminar")?true:false;
		$modificar	=	($_GET['accion']=="modificar")?true:false;
					
	}
	
	if($modificar)
	{
		$query_dato	=	"SELECT * FROM $tabla  WHERE ($indice={$_GET[$indice]})";
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
<? if(empty($_REQUEST['accion'])){ ?>
<link rel="stylesheet" type="text/css" href="desing/estilos.css">
<link rel="stylesheet" type="text/css" href="style.css">

<script type="text/javascript" src="select.js"></script>


<script language="javascript">
function paso2()
{

 if(document.form.id_empresa.value==0)
 {
   alert("Opcion no valida en el campo empresa.");
   return false;
 }  
 else
 {
  document.form.action="admin.php?seccion=<? echo $_REQUEST['seccion']?>";
  document.form.submit();
 } 
}


function paso3()
{

 if(document.form.id_reporte.value==0)
 {
   alert("Opcion no valida en el campo seccion de reporte.");
   return false;
 } 
  if(document.form.id_reporte.value==2)
{
  document.form.action="admin.php?seccion=30";
  document.form.submit();
}
 else
 {
  document.form.action="admin.php?seccion=<? echo $_REQUEST['seccion']?>";
  document.form.submit();
 } 
}

function paso4()
{
<? if($_GET['id_reporte'] == 1){?>
  document.form.action="admin.php?seccion=41";
<? } else if($_GET['id_reporte'] == 2){?>
  document.form.action="admin.php?seccion=40";
<? } else if($_GET['id_reporte'] == 3){?>
  document.form.action="admin.php?seccion=42";
<? } else {  ?>
  document.form.action="admin.php?seccion=31";
<? } ?>
  document.form.submit();	 
}

function grafica()
{
  document.form.action="admin.php?seccion=39";
  document.form.submit();
 	 
}

function paso5()
{

 if(document.form.id_evento.value==0)
 {
   alert("Opcion no valida en el campo Empleado.");
   return false;
 }  
 else
 {
  document.form.action="admin.php?seccion=<? echo $_REQUEST['seccion']?>";
  document.form.submit();
 } 
}





function genera()
{
document.form.action="generar_pdf.php";
document.form.submit();

}

function genera2()
{
document.form.action="reportes/desperdicios_generales.php";
document.form.submit();

}

function genera3()
{
document.form.action="reportes/metas_generales.php";
document.form.submit();

}

function web()
{
document.form.action="pruebas.php";
document.form.submit();

}

function mostrarExtr(id){
	<? if($extruder_d) 			{ ?>document.getElementById(1).style.display	= "none";
	document.getElementById(51).style.display	= "none";<? } ?>
	<? if($concentrado_pegt) 	{ ?>document.getElementById(39).style.display	= "none";document.getElementById(89).style.display	= "none";<? } ?>
	<? if($extruder_i) 			{ ?>document.getElementById(14).style.display	= "none";document.getElementById(64).style.display	= "none"; <? } ?>
	<? if($extruder_mh) 		{ ?>document.getElementById(17).style.display	= "none"; document.getElementById(67).style.display	= "none";<? } ?>
									document.getElementById(42).style.display	= "none";document.getElementById(92).style.display	= "none";
									document.getElementById(34).style.display	= "none";document.getElementById(84).style.display	= "none";
									document.getElementById(46).style.display	= "none";document.getElementById(96).style.display	= "none";
	<? if($concentrado_rpr)		{ ?>document.getElementById(41).style.display		= "none";document.getElementById(92).style.display	= "none";<? } ?>
	document.getElementById(40).style.display	= "none";document.getElementById(90).style.display	= "none";
	document.getElementById(45).style.display	= "none";document.getElementById(95).style.display	= "none";

	document.getElementById(id).style.display			= "block";
}


function mostrarImpr(id){
	<? if($impresion_d){ ?>document.getElementById(6).style.display		= "none";<? } ?>
	<? if($impresion_i){ ?>document.getElementById(15).style.display	= "none";<? } ?>
	document.getElementById(34).style.display	= 	"none";
	document.getElementById(43).style.display	= 	"none";
	document.getElementById(51).style.display	= 	"none";
	document.getElementById(55).style.display	= 	"none";
	document.getElementById(62).style.display	= 	"none";
	document.getElementById(18).style.display	= 	"none";
	document.getElementById(46).style.display	= 	"none";
	document.getElementById(47).style.display	= 	"none";
	document.getElementById(49).style.display	=	"none";

	document.getElementById(id).style.display			= "block";
}

function mostrarBol(id){
	<? if($bolseo_d) 		{ ?>document.getElementById(11).style.display	= "none";<? } ?>
	<? if($bolseo_i) 		{ ?>document.getElementById(16).style.display	= "none";<? } ?>
	<? if($concentrado_ccm)	{ ?>document.getElementById(37).style.display	= "none";<? } ?>
	<? if($concentrado_bmp)	{ ?>document.getElementById(38).style.display	= "none";<? } ?>
	document.getElementById(34).style.display	= "none";
	document.getElementById(52).style.display	= "none";
	document.getElementById(45).style.display	= "none";
	
	document.getElementById(57).style.display	= "none";
	document.getElementById(id).style.display	= "block";
	
}

function mostrarCon(id){
//	document.getElementById(29).style.display	= "none";
	<? if($concentrado_pd){  ?>document.getElementById(30).style.display	= "none";<? } ?>
	<? if($concentrado_re){  ?>document.getElementById(31).style.display	= "none";<? } ?>
	<? if($concentrado_ot){  ?>document.getElementById(32).style.display	= "none";<? } ?>
	<? if($concentrado_mp){  ?>document.getElementById(33).style.display	= "none";<? } ?>
	<? if($concentrado_pm){  ?>document.getElementById(34).style.display	= "none";<? } ?>
	<? if($concentrado_rd){  ?>document.getElementById(35).style.display	= "none";<? } ?>
	<? if($concentrado_dm){  ?>document.getElementById(36).style.display	= "none";<? } ?>
	<? if($concentrado_khpt){?><? } ?>
	document.getElementById(44).style.display	= "none";


	document.getElementById(id).style.display			= "block";
}

function seleccion(id){
	document.getElementById(100).style.display	= "none";
	document.getElementById(101).style.display	= "none";

	document.getElementById(id).style.display	= "block";
}


function seleccionbd(id){
	document.getElementById(200).style.display	= "none";
	document.getElementById(201).style.display	= "none";

	document.getElementById(id).style.display	= "block";
}
</script>

<body <? if($_REQUEST['id_reporte'] == 3) echo ' onLoad="myajax = new isiAJAX();" '; ?> >

       <? if($_REQUEST['id_reporte'] == 3 && $configuracion){
		$qConfig	=	"SELECT * FROM configuracion_bolseo  ORDER BY desde DESC LIMIT 1 ";
	   	$rConfig	=	mysql_query($qConfig);
	   	$dConfig	=	mysql_fetch_assoc($rConfig);
		$nConfig	=	mysql_num_rows($rConfig);
		if($nConfig > 0){
		$qDesactiva = 	"UPDATE configuracion_bolseo SET activado = 0 WHERE id_configuracion != ".$dConfig['id_configuracion']." ";
		$rDesactiva	=	mysql_query($qDesactiva);
		}
		else if($nConfig == 0){
		$qDesactiva = 	"UPDATE configuracion_bolseo SET activado = 0 ";
		$rDesactiva	=	mysql_query($qDesactiva);
		}
	   
	   	?> 
        
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
		myajax.Link('actualiza.php?valor='+valor+'&campo='+campo+'&id='+id+'&tabla=configuracion_bolseo&tipo=id_configuracion');
}
</script>
<script language="javascript" type="text/javascript">
			function checar(x){
			if(document.getElementById('a'+x).checked == true )
				document.getElementById('a'+x).value = 0;
			else 
				document.getElementById('a'+x).value = 1;
				
				}
		</script>
<table width="100%">
          <tr>
              	<? if($nConfig > 0){ ?>
            <td colspan="5" class="style7"><label>Existe una configuracion para reportes de bolseo, desea usarla ? .<input <? if($dConfig['activado'] == 1){ echo "checked"; echo ' value="0"';} else if($dConfig['activado'] == 0) { echo "value='1'";} ?> type="checkbox"  id="a<?=$dConfig['id_configuracion']?>" name="activado"  onclick="Blur(this.id,  this.value, 'activado', '<?=$dConfig['id_configuracion']?>'); checar('<?=$dConfig['id_configuracion']?>');  mostrarAreas('confi'); " /> Si.</label><br><br></td>
          		<? } ?>
          </tr>
  		<tr>
       	  <td width="10%" >&nbsp;</td>
           	<td width="25%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=agregar" class="style7" >Agregar configuracion</a></td>
           	<td width="25%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listar" class="style7" >Ver Configuraciones anteriores</a></td>
        	<td width="50%"><br><br></td>
        </tr>
        <? if($nConfig > 0){ ?>
        <tr>
        	<td colspan="6" align="left">
            	<form name="configuracion_bolseo" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&id_reporte=<?=$_REQUEST['id_reporte']?>&accion=listar" method="post">           	
            		<table width="100%"  bgcolor="#EEEEEE"  style="display:<? if($dConfig['activado'] == 1 ) echo 'block" '; else echo "none" ?>" id="confi">      
                        <tr>
                          <td width="34%" class="style5"><br>
                         <label style="width:70px; text-align:right" class="style7" >Desde : </label>	<?=fecha($dConfig['desde']);?></td>
                         <td class=" style5" width="66%"><br>
                          <label style="width:90px; text-align:right" class="style7" >Hasta : </label>	<?=fecha($dConfig['hasta']);?></td>
                      </tr>
                      <tr>
                            <td class="style5">
                            <label for="b<?=$dConfig['id_configuracion']?>" style="width:150px; text-align:right" class="style7">Peso Promedio Millar : </label><?=$dConfig['ppm']; ?> Millares.<br />
                             </td>
                              <td rowspan="2" valign="top" class="style5">	
                           <label for="c<?=$dConfig['id_configuracion']?>" style="width:90px; text-align:right;" class="style7">Observaciones :</label> 
                          <?=$dConfig['observaciones']?></td>
                        </tr>
                        <tr>
                            <td class="style5" >
                            <label for="c<?=$dConfig['id_configuracion']?>" style="width:150px; text-align:right;" class="style7">Empaques de Segunda : </label><?=$dConfig['seg']; ?> Segundas.<br />
                            <br><br> </td>
                		</tr>       
            		</table>
				</form>
           </td>
      </tr><? }	 ?>
 </table>
      <? } ?>


<form name="form" method="post" id="form" action="admin.php?seccion=<?=$_REQUEST['seccion']?>" >
  <table width="99%" border="0" align="center" class="texto2">
 <? if(!isset($_REQUEST['id_reporte'])){ ?> 
  <tr>
    <td><table width="81%" border="0" align="center" class="texto2">
      <tr>
        <td colspan="5">
        	<table width="100%" cellpadding="0" cellspacing="0" align="center">
              <tr align="center">
                  <td width="30%" height="67"><? if($reportes_e	) {?><a href="admin.php?seccion=<?=$_REQUEST['seccion']?>&id_reporte=1" class="style7"><img src="images/r_ext.jpg" border="0" /></a><? } ?></td>
                  <td width="30%"><? if($reportes_i) {?><a href="admin.php?seccion=<?=$_REQUEST['seccion']?>&id_reporte=2" class="style7"><img src="images/r_imp.jpg" border="0" /></a><? } ?></td>
                  <td width="30%"><? if($reportes_b) {?><a href="admin.php?seccion=<?=$_REQUEST['seccion']?>&id_reporte=3" class="style7"><img src="images/r_bolseo.jpg" border="0" /></a><? } ?></td>
             	 </tr>
              	<tr align="center">
                  <td width="20%" height="67"><? if($concentrado_emp) {?><a href="admin.php?seccion=<?=$_REQUEST['seccion']?>&id_reporte=4" class="style7"><img src="images/r_emp.jpg" border="0" /></a><? } ?></td>
                  <td width="20%"><? if($concentrado_rep  || $_SESSION['id_supervisor']) {?><a href="admin.php?seccion=<?=$_REQUEST['seccion']?>&id_reporte=5" class="style7"><img src="images/c_rep.jpg" border="0" /></a><? } ?></td>
                  <td width="20%"><? if($historial) {?><a href="admin.php?seccion=30" class="style7"><img src="images/h_rep.jpg" border="0" /></a><? } ?></td>
            	</tr>
        	</table>
		</td>
      </tr>
 <?  } ?> 
      <? if($_REQUEST['id_reporte']==1) {?>
	<tr>
	<div id="xsnazzy"> 

		<td colspan="5">
			<h1>ALTA DENSIDAD</H1>
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" style="background-color: #ffffff">
      <? if($extruder_d){?>
		<p>
        	<label><input type="radio" value="1" name="tipo" onClick="javascript: mostrarExtr(1);" disabled />
            <span class="style7">PRODUCCION DIARIA </span></label></p><br>
    			<p>
                	<div id="1" class="hidden_box"> 
                       <br />           
                       <label><input type="radio" name="tiempo" value="0" onClick="seleccion(100);"  />Por Fecha</label><br /><br />
                       <label><input type="radio" name="tiempo" value="1" onClick="seleccion(101);"  />Por Mes</label>
                       <br>
                        <p>
                            <div id="101" style="display:none;"><br /><br />Mes 
                                      <select name="mes" class="style5" id="mes" >
                                                    <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                                    <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option> 
                                                    <? } ?>
                                       </select>&nbsp;&nbsp;&nbsp; 
                                        Año
                                       <select name="anho" class="style5" id="anho">
                                                        <? for($a = 0 ; $a < sizeof($anio) ; $a++){?>
                                                        <option value="<?=$anio[$a]?>" <? if($anio[$a] == date('Y')) echo "selected";?>><?=$anio[$a]?></option>
                                                        <? } ?>        
                                       </select>
                            </div>
                        </p>
               			<p>
                        	<div id="100" style="display:none;">
                                <br /><br>
                                <div style="float:left">Desde:</div><div style="float:left"><input id="desde" name="desde" type="text" class="fecha" value="<?=date('d-m-Y')?>"   /></div>
                                <div style="float:left">&nbsp;&nbsp;Hasta:</div><div style="float:left"><input id="hasta" name="hasta" type="text" value="<?=date('d-m-Y')?>" class="fecha"   /></div>
                                <br />
                                <br />
                             </div>
                         </p>
                         <br>
					</div>
				</p>
                         <br>

                      <? } if($concentrado_pegt){ ?>
					  <p><label><input type="radio" value="39" name="tipo" onClick=" javascript: mostrarExtr('39')" disabled />
                            <span class="style7">PRODUCCION EXTRUDER POR GRUPO Y TURNO </span></label></p><br>
 							<p><div id="39"class="hidden_box">
                            <br />Mes 
                              <select name="mes_extr" class="style5" id="mes_extr" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_extr" class="style5" id="ano_extr">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select> 
                         <input type="button" class="boton1" value="Graficar" onClick="grafica();" >
                         <br />
                                
                                </div>
                         </p>
                      
      				</p>
      <br>
     <? } if($extruder_i){ ?> 
     <p>
     	<label><input type="radio" value="14" name="tipo" onClick="javascript: mostrarExtr(14)" />
        	<span class="style7">REPORTE DE INCIDENCIAS DIARIO</span></label></p><br>
     			<p style="height:5px;" >
                	<div id="14"class="hidden_box" > 
                        <br />
                        <div style="float:left;">Desde :</div> 
                        <div style="float:left;"><input id="fecha_incidencia" name="fecha_incidencia" type="text" class="fecha" value="<?=date('d-m-Y')?>"></div>
                        <div style="float:left;">&nbsp;&nbsp;Hasta :</div> <div style="float:left"><input id="fecha_incidencia_f" name="fecha_incidencia_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"></div>
                        <br><br />            
         			</div><br>
                </p>
     <? } if($extruder_mh){ ?> 
           <p><label><input type="radio" value="17" name="tipo" onClick="javascript: mostrarExtr(17)" />
            <span class="style7">HISTORIAL DE MALLAS</span></label></p><br>
   				<p>
                	<div id="17"class="hidden_box" > 
            			<br>
						 <div id="maquinas_impre" >	<br> Maquina:
                             <?	$qMa	=	"SELECT * FROM maquina WHERE area IN(4)  and tipo_d=1 ORDER By area, numero"; 
								$rMa	=	mysql_query($qMa);
								$nMa	=	mysql_num_rows($rMa);
							for($a=0;$dMa	=	mysql_fetch_assoc($rMa);$a++){?>
                            <div style="float:left; width:40px; height:25px;"><input type="checkbox" name="maq_id_mallas[]" value="<?=$dMa['id_maquina']?>"><?=$dMa['numero']?></div>
                            <? if($dMa['numero']==10) echo "<br><br><br>";?>
							<? } ?>
                            </div>
                            <label><input type="checkbox" name="todos" id="todos" onClick="javascript:  chekaTodos(<?=$nMa?>,'maquinas_impre');">Todas la maquinas</label>
						<br>
                        <br /><div style="float:left; padding-right:15px;">Desde :</div> 
                        <div style="float:left; padding-right:15px;"><input id="fecha_hist" name="fecha_hist" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>
                       <div style="float:left; padding-right:15px;">Hasta :</div> 
                       <div style="float:left; padding-right:15px;"><input id="fecha_hist_f" name="fecha_hist_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>  <br><br>                     
                        </div><br></p>
                        
                      
                        
                        
                        
                        
                        

                <? } //if($concentrado_rpr){ ?>
     <p>
     	<label><input type="radio" value="46" name="tipo" onClick="javascript: mostrarExtr(46)" />
        	<span class="style7">DESPERDICIOS DIARIOS</span></label></p><br>
     			<p>
                	<div id="46"class="hidden_box" > 
                        <div style="padding:10px 25px;">
                        <div style="float:left; padding-right:10px;">Desde :</div>
                        <div style="float:left;  padding-right:10px;"><input id="fecha_des" name="fecha_des" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                        ></div>
                       <div style="float:left;">Hasta : </div>
                       <div style="float:left; padding-right:25px;"><input id="fecha_des_f" name="fecha_des_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                        ></div>
                        </div><br><br />            
         			</div>
                </p>
            <br>
			  <p><label><input type="radio" value="42" name="tipo" onClick=" javascript: mostrarExtr('42')" />
						<span class="style7">PRODUCCI&Oacute;N DIARIA CONTRA META Y PAROS</span></label>
						</p><br>
                      	<p><div id="42"class="hidden_box">
                        <br>
                        <div style="float:left;">Desde : </div>
                        <div style="float:left"><input id="fecha_mp" name="fecha_mp" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         /></div>
                        <div style="float:left; padding-left:10px;">Hasta : </div><div style="float:left;"><input id="fecha_mpf" name="fecha_mpf" type="text" class="fecha" value="<?=date('d-m-Y')?>" 
                         /></div>&nbsp;&nbsp;   
                         <input type="button" class="button1" name="Grafica_prod" value="Graficar" onClick=" javascript: grafica();"><br><br>
                         </div></p><br>
                         
                <? //} ?>   
                <? if($concentrado_rpr){ ?>
						<p><label><input type="radio" value="41" name="tipo" onClick=" javascript: mostrarExtr('41')" />
						<span class="style7">REPORTE CONCENTRADO EXTRUDER POR MAQUINA Y SUPERVISOR</span></label></p><br>
                      	<p><div id="41"class="hidden_box">
                             <br />Mes 
                              <select name="mes_rpr" class="style5" id="mes_rpr" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_rpr" class="style5" id="ano_rpr">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select><br><br>
                                </div></p><br>
                          
                <? } ?>

						<p><label><input type="radio" value="45" name="tipo" onClick=" javascript: mostrarExtr('45')" />
						<span class="style7">REPORTE DE PRODUCCION POR MAQUINA</span></label></p><br>
                      	<p><div id="45"class="hidden_box">
                        <div id="maquinas" >	<br> Maquinas:
                            <input type="hidden" name="area_maq" value="1">
                            <?	$qMa	=	"SELECT * FROM maquina WHERE area = 4 and tipo_d=1 ORDER By numero"; 
								$rMa	=	mysql_query($qMa);
								$nMa	=	mysql_num_rows($rMa);
							for($a=0;$dMa	=	mysql_fetch_assoc($rMa);$a++){?>
                            <div style="float:left; width:40px; height:25px;"><input type="checkbox" name="maq_id[]" value="<?=$dMa['id_maquina']?>"><?=$dMa['numero']?></div>
                            <? if($dMa['numero']==10) echo "<br><br><br>";?>
							<? } ?>
                            </div>
                            <label><input type="checkbox" name="todos" id="todos" onClick="javascript:  chekaTodos(<?=$nMa?>,'maquinas');">Todas la maquinas</label>
                             <br><br />Mes 
                              <select name="mes_maq" class="style5" id="mes_maq" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_maq" class="style5" id="ano_maq">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select>
                         <input type="button" name="Grafica_prod" value="Graficar" onClick=" javascript: grafica();"><br><br>
                    </div></p><br>
                          
					<p><label><input type="radio" value="34" name="tipo" onClick=" javascript: mostrarExtr('34')" />
                    <input type="hidden" name="par_extr" value="1">
					<span class="style7">PAROS MENSUALES.</span></label></p><br>
					<p><div id="34"class="hidden_box">
					<br>Mes: 
                    <select name="mes_pm" class="style5" id="mes_pm" >
					<? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                    	<option value="<?=$meses?>" <? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
					<? } ?>
					</select> 
					&nbsp;&nbsp;&nbsp; Año: 
                    <select name="anho_pm" class="style5" id="anho_pm">
					<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
						<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
					<? } ?>        
                    </select><br /><br />
					</div></p><br>
          <? if($concentrado_khpt){ ?>
                      	<p><label><input type="radio" value="40" name="tipo" onClick=" javascript: mostrarExtr('40')" />
                        <span class="style7">KGS HORAS POR GRUPO Y TURNO</span></label></p><br>
                      	<p><div id="40"class="hidden_box">
						<br />Mes <select name="mes_kgs" class="style5" id="mes_kgs" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_kgs" class="style5" id="ano_kgs">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select>
                
                                    <br /><br>
                                </div></p>
                                <br>                
         <? } ?>
                   
               		</div>
              <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
           <H2> .</H2>
          </td>
      </tr><tr>
		<td colspan="5">
			<h1>BAJA DENSIDAD</H1>
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" style="background-color: #ffffff">
      <? if($extruder_d){?>
		<p>
        	<label><input type="radio" value="51" name="tipo" onClick="javascript: mostrarExtr(51)" disabled />
            <span class="style7">PRODUCCION DIARIA </span></label></p><br>
    			<p>
                	<div id="51" class="hidden_box"> 
                       <br />           
                       <label><input type="radio" name="tiempo" value="0" onClick="seleccionbd(200);"  />Por Fecha</label><br /><br />
                       <label><input type="radio" name="tiempo" value="1" onClick="seleccionbd(201);"  />Por Mes</label>
                       <br>
                        <p>
                            <div id="201" style="display:none;"><br /><br />Mes 
                                      <select name="mes" class="style5" id="mes" >
                                                    <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                                    <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option> 
                                                    <? } ?>
                                       </select>&nbsp;&nbsp;&nbsp; 
                                        Año
                                       <select name="anho" class="style5" id="anho">
                                                        <? for($a = 0 ; $a < sizeof($anio) ; $a++){?>
                                                        <option value="<?=$anio[$a]?>" <? if($anio[$a] == date('Y')) echo "selected";?>><?=$anio[$a]?></option>
                                                        <? } ?>        
                                       </select>
                            </div>
                        </p>
               			<p>
                        	<div id="200" style="display:none;">
                                <br /><br>
                                <div style="float:left">Desde:</div><div style="float:left"><input id="desde" name="desde" type="text" class="fecha" value="<?=date('d-m-Y')?>"   /></div>
                                <div style="float:left">&nbsp;&nbsp;Hasta:</div><div style="float:left"><input id="hasta" name="hasta" type="text" value="<?=date('d-m-Y')?>" class="fecha"   /></div>
                                <br />
                                <br />
                             </div>
                         </p>
                         <br>
					</div>
				</p>
                         <br>

                      <? } if($concentrado_pegt){ ?>
					  <p><label><input type="radio" value="89" name="tipo" onClick=" javascript: mostrarExtr(89)" disabled />
                            <span class="style7">PRODUCCION EXTRUDER POR GRUPO Y TURNO </span></label></p><br>
 							<p><div id="89"class="hidden_box">
                            <br />Mes 
                              <select name="mes_extr" class="style5" id="mes_extr" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_extr" class="style5" id="ano_extr">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select> 
                         <input type="button" class="boton1" value="Graficar" onClick="grafica();" >
                         <br />
                                
                                </div>
                         </p>
                      
      				</p>
      <br>
     <? } if($extruder_i){ ?> 
     <p>
     	<label><input type="radio" value="64" name="tipo" onClick="javascript: mostrarExtr(64)" />
        	<span class="style7">REPORTE DE INCIDENCIAS DIARIO</span></label></p><br>
     			<p style="height:5px;" >
                	<div id="64" class="hidden_box" > 
                        <br />
                        <div style="float:left;">Desde :</div> 
                        <div style="float:left;"><input id="fecha_incidencia" name="fecha_incidencia" type="text" class="fecha" value="<?=date('d-m-Y')?>"></div>
                        <div style="float:left;">&nbsp;&nbsp;Hasta :</div> <div style="float:left"><input id="fecha_incidencia_f" name="fecha_incidencia_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"></div>
                        <br><br />            
         			</div><br>
                </p>
     <? } if($extruder_mh){ ?> 
           <p><label><input type="radio" value="67" name="tipo" onClick="javascript: mostrarExtr(67)" />
            <span class="style7">HISTORIAL DE MALLAS</span></label></p><br>
   				<p>
                	<div id="67"class="hidden_box" > 
            			<br>
						 <div id="maquinas_impre" >	<br> Maquina:
                             <?	$qMa	=	"SELECT * FROM maquina WHERE area IN(4) and tipo_d=2 ORDER By area, numero"; 
								$rMa	=	mysql_query($qMa);
								$nMa	=	mysql_num_rows($rMa);
							for($a=0;$dMa	=	mysql_fetch_assoc($rMa);$a++){?>
                            <div style="float:left; width:40px; height:25px;"><input type="checkbox" name="maq_id_mallas[]" value="<?=$dMa['id_maquina']?>"><?=$dMa['numero']?></div>
                            <? if($dMa['numero']==10) echo "<br><br><br>";?>
							<? } ?>
                            </div>
                            <label><input type="checkbox" name="todos" id="todos" onClick="javascript:  chekaTodos(<?=$nMa?>,'maquinas_impre');">Todas la maquinas</label>
						<br>
                        <br /><div style="float:left; padding-right:15px;">Desde :</div> 
                        <div style="float:left; padding-right:15px;"><input id="fecha_hist" name="fecha_hist" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>
                       <div style="float:left; padding-right:15px;">Hasta :</div> 
                       <div style="float:left; padding-right:15px;"><input id="fecha_hist_f" name="fecha_hist_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>  <br><br>                     
                        </div><br></p>
                        
                      
                        
                        
                        
                        
                        

                <? } //if($concentrado_rpr){ ?>
     <p>
     	<label><input type="radio" value="96" name="tipo" onClick="javascript: mostrarExtr(96)" />
        	<span class="style7">DESPERDICIOS DIARIOS</span></label></p><br>
     			<p>
                	<div id="96"class="hidden_box" > 
                        <div style="padding:10px 25px;">
                        <div style="float:left; padding-right:10px;">Desde :</div>
                        <div style="float:left;  padding-right:10px;"><input id="fecha_des" name="fecha_des" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                        ></div>
                       <div style="float:left;">Hasta : </div>
                       <div style="float:left; padding-right:25px;"><input id="fecha_des_f" name="fecha_des_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                        ></div>
                        </div><br><br />            
         			</div>
                </p>
            <br>
			  <p><label><input type="radio" value="92" name="tipo" onClick=" javascript: mostrarExtr(92)" />
						<span class="style7">PRODUCCI&Oacute;N DIARIA CONTRA META Y PAROS</span></label>
						</p><br>
                      	<p><div id="92"class="hidden_box">
                        <br>
                        <div style="float:left;">Desde : </div>
                        <div style="float:left"><input id="fecha_mp" name="fecha_mp" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         /></div>
                        <div style="float:left; padding-left:10px;">Hasta : </div><div style="float:left;"><input id="fecha_mpf" name="fecha_mpf" type="text" class="fecha" value="<?=date('d-m-Y')?>" 
                         /></div>&nbsp;&nbsp;   
                         <input type="button" class="button1" name="Grafica_prod" value="Graficar" onClick=" javascript: grafica();"><br><br>
                         </div></p><br>
                         
                <? //} ?>   
                <? if($concentrado_rpr){ ?>
						<p><label><input type="radio" value="91" name="tipo" onClick=" javascript: mostrarExtr(91)" />
						<span class="style7">REPORTE CONCENTRADO EXTRUDER POR MAQUINA Y SUPERVISOR</span></label></p><br>
                      	<p><div id="91"class="hidden_box">
                             <br />Mes 
                              <select name="mes_rpr" class="style5" id="mes_rpr" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_rpr" class="style5" id="ano_rpr">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select><br><br>
                                </div></p><br>
                          
                <? } ?>

						<p><label><input type="radio" value="95" name="tipo" onClick=" javascript: mostrarExtr(95)" />
						<span class="style7">REPORTE DE PRODUCCION POR MAQUINA</span></label></p><br>
                      	<p><div id="95"class="hidden_box">
                        <div id="maquinas" >	<br> Maquinas:
                            <input type="hidden" name="area_maq" value="1">
                            <?	$qMa	=	"SELECT * FROM maquina WHERE area = 4 and tipo_d=2 ORDER By numero"; 
								$rMa	=	mysql_query($qMa);
								$nMa	=	mysql_num_rows($rMa);
							for($a=0;$dMa	=	mysql_fetch_assoc($rMa);$a++){?>
                            <div style="float:left; width:40px; height:25px;"><input type="checkbox" name="maq_id[]" value="<?=$dMa['id_maquina']?>"><?=$dMa['numero']?></div>
                            <? if($dMa['numero']==10) echo "<br><br><br>";?>
							<? } ?>
                            </div>
                            <label><input type="checkbox" name="todos" id="todos" onClick="javascript:  chekaTodos(<?=$nMa?>,'maquinas');">Todas la maquinas</label>
                             <br><br />Mes 
                              <select name="mes_maq" class="style5" id="mes_maq" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_maq" class="style5" id="ano_maq">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select>
                         <input type="button" name="Grafica_prod" value="Graficar" onClick=" javascript: grafica();"><br><br>
                    </div></p><br>
                          
					<p><label><input type="radio" value="84" name="tipo" onClick=" javascript: mostrarExtr(84)" />
                    <input type="hidden" name="par_extr" value="1">
					<span class="style7">PAROS MENSUALES.</span></label></p><br>
					<p><div id="84"class="hidden_box">
					<br>Mes: 
                    <select name="mes_pm" class="style5" id="mes_pm" >
					<? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                    	<option value="<?=$meses?>" <? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
					<? } ?>
					</select> 
					&nbsp;&nbsp;&nbsp; Año: 
                    <select name="anho_pm" class="style5" id="anho_pm">
					<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
						<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
					<? } ?>        
                    </select><br /><br />
					</div></p><br>
          <? if($concentrado_khpt){ ?>
                      	<p><label><input type="radio" value="90" name="tipo" onClick=" javascript: mostrarExtr(90)" />
                        <span class="style7">KGS HORAS POR GRUPO Y TURNO</span></label></p><br>
                      	<p><div id="90"class="hidden_box">
						<br />Mes <select name="mes_kgs" class="style5" id="mes_kgs" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_kgs" class="style5" id="ano_kgs">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select>
                
                                    <br /><br>
                                </div></p>
                                <br>                
         <? } ?>
                   
               		</div>
              <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
           <H2> . </H2>
          </td>

         </div>
      </tr>                             
      <tr>
        <td colspan="5" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" align="left">&nbsp;</td>
      </tr>      
	  <? }?>


      <? if($_REQUEST['id_reporte']==2){?>
<tr>
	<div id="xsnazzy"> 
		<td colspan="5">
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" style="background-color: #ffffff">
					<? if($impresion_d){ ?> 
						<p><label><input type="radio" value="6" name="tipo" onClick="javascript: mostrarImpr('6')" />
            			<span class="style7">PRODUCCION DIARIA</span></label></p><br>
                      	<p><div id="6"class="hidden_box">
                            <br>
                            <input type="radio" name="tiempo" value="0" onClick="seleccion(100);"  />Por Fecha 
                            <br><br>
                            <input type="radio" name="tiempo" value="1" onClick="seleccion(101);" />Por Mes
                            <div id="101" style="display:none;"><br />Mes 
                            <select name="mes" class="style5" id="mes" >
                                <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                <? } ?>
                            </select> 
                            &nbsp;&nbsp;&nbsp; Año
                            <select name="anho" class="style5" id="anho">
                                <? for($a = 0 ; $a < sizeof($anio) ; $a++){?>
                                <option value="<?=$anio[$a]?>" <? if($anio[$a] == date('Y')) echo "selected";?>><?=$anio[$a]?></option>
                                <? } ?>
                            </select>
             			</div>
                		<div id="100" style="display:none;">
 						<br /><br>
                        <div style="float:left">Desde:</div>
                        <div style="float:left;"><input id="desde" name="desde" type="text" class="fecha" value="<?=date('d-m-Y')?>" /></div>
            			<div style="float:left; padding-left:15px;">Hasta:</div>
                        <div style="float:left"><input id="hasta" name="hasta" class="fecha" type="text" value="<?=date('d-m-Y')?>"  />
                           </div>
                		</div>
      					<br><br>
                        </div></p><br>
                        
 					  <p><label><input type="radio" value="46" name="tipo" onClick=" javascript: mostrarImpr('46')" />
                            <span class="style7">PRODUCCION IMPRESION POR GRUPO Y TURNO</span></label></p><br>
 							<p><div id="46"class="hidden_box">
                            <br />Mes 
                              <select name="mes_impr" class="style5" id="mes_impr" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_impr" class="style5" id="ano_impr">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select> 
                         <input type="button" value="Graficar" onClick="grafica();" >
                         
                                    <br />
                                </div>
                         </p><br>
                    
                               
     					<? } if($impresion_i){ ?> 
       					<p><label><input type="radio" value="15" name="tipo" onClick="javascript: mostrarImpr(15)" />
            			<span class="style7">REPORTE DE INCIDENCIAS DIARIO</span></label></p><br>
                      	<p><div id="15"class="hidden_box" > 
                        <br /><div style=" float:left;">Desde :</div><div style="float:left"><input id="fecha_incidencia" name="fecha_incidencia" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>
                        <div style=" float:left;">Hasta :</div><div style="float:left"><input id="fecha_incidencia_f" name="fecha_incidencia_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>
                        <br><br />            
                        </div></p><br>
                        
						<? } if($impresion_ci){ ?> 
           				<p><label><input type="radio" value="18" name="tipo" onClick="javascript: mostrarImpr(18)" />
            			<span class="style7">HISTORIAL DE CAMBIO DE IMPRESION</span></label></p><br>
                      	<p><div id="18"class="hidden_box" > 
						 <div id="maquinas_impre" >	<br> Maquina:
                             <?	$qMa	=	"SELECT * FROM maquina WHERE area IN(2,3) ORDER By area, numero"; 
								$rMa	=	mysql_query($qMa);
								$nMa	=	mysql_num_rows($rMa);
							for($a=0;$dMa	=	mysql_fetch_assoc($rMa);$a++){?>
                            <div style="float:left; width:40px; height:25px;"><input type="checkbox" name="maq_id_impre[]" value="<?=$dMa['id_maquina']?>"><?=$dMa['numero']?></div>
                            <? if($dMa['numero']==10) echo "<br><br><br>";?>
							<? } ?>
                            </div>
                            <label><input type="checkbox" name="todos" id="todos" onClick="javascript:  chekaTodos(<?=$nMa?>,'maquinas_impre');">Todas la maquinas</label>
						<br>
                        <br /><div style="float:left; padding-right:15px;">Desde :</div> <div style="float:left; padding-right:15px;"><input id="fecha_hist" name="fecha_hist" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>
                       <div style="float:left; padding-right:15px;">Hasta :</div> <div style="float:left; padding-right:15px;"><input id="fecha_hist_f" name="fecha_hist_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>  <br><br>                     
                        </div><br></p>
						<? }?>
		<p>
     	<label><input type="radio" value="55" name="tipo" onClick="javascript: mostrarImpr(55)" />
        	<span class="style7">DESPERDICIOS DIARIOS</span></label></p><br>
     			<p>
                	<div id="55"class="hidden_box" > 
                        <div style="padding:10px 25px;">
                        <div style="float:left; padding-right:10px;">Desde :</div>
                        <div style="float:left;  padding-right:10px;"><input id="fecha_des" name="fecha_des" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                        ></div>
                       <div style="float:left;">Hasta : </div>
                       <div style="float:left; padding-right:25px;"><input id="fecha_des_f" name="fecha_des_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                        ></div>
                        </div><br><br />            
         			</div>
                </p>
            <br>


						<p><label><input type="radio" value="62" name="tipo" onClick=" javascript: mostrarImpr('62')" />
						<span class="style7">PRODUCCI&Oacute;N DIARIA CONTRA META Y PAROS</span></label>
						</p><br>
                      	<p><div id="62" class="hidden_box"><br>
                        <div style="float:left">Desde :</div><div style="float:left"><input id="fecha_mp" name="fecha_mp" type="text" class="fecha" value="<?=date('d-m-Y')?>"/></div>
                        <div style="float:left">&nbsp;&nbsp;Hasta :</div><div style="float:left;"><input id="fecha_mpf" name="fecha_mpf" type="text" class="fecha" value="<?=date('d-m-Y')?>"/></div>   
                         <input type="button" name="Grafica_prod" value="Graficar" onClick=" javascript: grafica();"><br><br>
                         </div></p><br>
                         
       					<p><label><input type="radio" value="43" name="tipo" onClick="javascript: mostrarImpr(43)" />
            			<span class="style7">REPORTE DE PRODUCCION EN BAJA DENSIDAD</span></label></p><br>
                      	<p><div id="43"class="hidden_box" > 
                        <br /><div style=" float:left;">Desde :</div><div style="float:left"><input id="fecha_bd" name="fecha_bd" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>
                        <div style=" float:left;">Hasta :</div><div style="float:left"><input id="fecha_bdf" name="fecha_bdf" type="text" class="fecha" value="<?=date('d-m-Y')?>"
                         ></div>
                        <br><br />            
                        </div></p><br>
     
	                <? if($concentrado_rpr){ ?>
						<p><label><input type="radio" value="49" name="tipo" onClick=" javascript: mostrarImpr('49')" />
						<span class="style7">REPORTE CONCENTRADO IMPRESION POR MAQUINA Y SUPERVISOR</span></label></p><br>
                      	<p><div id="49" class="hidden_box">
                             <br />Mes 
                              <select name="mes_rpr" class="style5" id="mes_rpr" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_rpr" class="style5" id="ano_rpr">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select><br><br>
                                </div></p><br>
   
                          
                		<? } ?>						
						
						<p><label><input type="radio" value="51" name="tipo" onClick=" javascript: mostrarImpr('51')" />
						<span class="style7">REPORTE DE PRODUCCION POR MAQUINA</span></label></p><br>
                      	<p><div id="51"class="hidden_box">
                        <div id="maquinas" >	<br> Maquina:
                             <input type="hidden" name="area_maq" value="2">
                           <?	$qMa	=	"SELECT * FROM maquina WHERE area IN (2,3) ORDER By area, numero"; 
								$rMa	=	mysql_query($qMa);
								$nMa	=	mysql_num_rows($rMa);
							for($a=0;$dMa	=	mysql_fetch_assoc($rMa);$a++){?>
                            <div style="float:left; width:40px; height:25px;"><input type="checkbox" name="maq_id[]" value="<?=$dMa['id_maquina']?>"><?=$dMa['numero']?></div>
                            <? if($dMa['numero']==10) echo "<br><br><br>";?>
							<? } ?> </div>
                            <label><input type="checkbox" name="todos" id="todos" onClick="javascript:  chekaTodos(<?=$nMa?>,'maquinas');">Todas la maquinas</label>
                            <br><br />Mes 
                              <select name="mes_maq" class="style5" id="mes_maq" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_maq" class="style5" id="ano_maq">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select>
                         <input type="button" name="Grafica_prod" value="Graficar" onClick=" javascript: grafica();"><br><br>
            		</div></p><br>

					<p><label><input type="radio" value="34" name="tipo" onClick=" javascript: mostrarImpr('34')" />
                    <input type="hidden" name="par_impr" value="1">

					<span class="style7">PAROS MENSUALES.</span></label></p><br>
					<p><div id="34"class="hidden_box">
					<br>Mes: 
                    <select name="mes_pm" class="style5" id="mes_pm" >
					<? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                    	<option value="<?=$meses?>" <? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
					<? } ?>
					</select> 
					&nbsp;&nbsp;&nbsp; Año: 
                    <select name="anho_pm" class="style5" id="anho_pm">
					<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
						<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
					<? } ?>        
                    </select><br /><br />
					</div></p><br>
                    
                      	<p><label><input type="radio" value="47" name="tipo" onClick=" javascript: mostrarImpr('47')" />
                        <span class="style7">KGS HORAS POR GRUPO Y TURNO</span></label></p><br>
                      	<p><div id="47"class="hidden_box">
						<br />Mes <select name="mes_imp" class="style5" id="mes_imp" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_imp" class="style5" id="ano_imp">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select>
                
                                    <br /><br>
                         </div></p><br>
                    
                    
                    </div>
              <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
          </td>
         </div>
      </tr>  
      
      <? }?>
      <? if($_REQUEST['id_reporte']==3){?>
      <tr>
        <td colspan="5" align="left"><br /></td>
      </tr>

<tr>
	<div id="xsnazzy"> 
		<td colspan="5">
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" style="background-color: #ffffff">
		  <?  if($bolseo_d){ ?>          
                      <p><label><input type="radio" value="11" name="tipo" onClick="javascript: mostrarBol('11')" />
                      <span class="style7">PRODUCCION DIARIA</span></label></p>
                      <p><div id="11" class="hidden_box" > <br><br>            
                          <label> <input type="radio" name="tiempo" value="0" onClick="seleccion(100);"  />Por Fecha </label><br><br>
                          <label><input type="radio" name="tiempo" value="1" onClick="seleccion(101);" />Por Mes</label>
                             <br><br><div id="101" style="display:none;"><br />Mes 
                              <select name="mes" class="style5" id="mes" >
                                        <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                               </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="anho" class="style5" id="anho">
                                            <? for($a = 0 ; $a < sizeof($anio) ; $a++){?>
                                            <option value="<?=$anio[$a]?>" <? if($anio[$a] == date('Y')) echo "selected";?>><?=$anio[$a]?></option>
                                            <? } ?>
                               </select>
                                </div>
                            <div id="100" style="display: none;">
                            <br />
                            <div style="float:left;">Desde:</div>
                            <div style="float:left;"><input id="desde" name="desde" type="text" class="fecha" value="<?=date('d-m-Y')?>" /></div>
                            
                            <div style="float:left;">&nbsp;&nbsp;Hasta:</div>
                            <div style="float:left;"><input id="hasta" name="hasta" type="text" class="fecha" value="<?=date('d-m-Y')?>"  /></div>
                                </div><br><br>
                     </div></p>
                     <br><br>
                <? } if($concentrado_ccm){ ?>
                    <p><label><input type="radio" value="37" name="tipo" onClick=" javascript: mostrarBol('37')" />
                        <span class="style7">CONCENTRADO POR MAQUINA</span></label></p><br>
                    <p><div id="37" class="hidden_box">
                            <br />Mes 
                                <select name="mes_grafica" class="style5" id="mes_grafica" >
                                <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                    <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                <? } ?>
                                </select> 
                                &nbsp;&nbsp;&nbsp; Año
                                <select name="ano_grafica" class="style5" id="ano_grafica">
                                        <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                                            <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                                        <? } ?>        
                                </select> 
          				</div>
                     </p>
                    <br> 
                <? } ?>
						<p><label><input type="radio" value="57" name="tipo" onClick=" javascript: mostrarBol('57')" />
						<span class="style7">CONCENTRADO DE BOLSEO POR SUPERVISOR.</span></label>
						</p><br>
                      	<p><div id="57"class="hidden_box">
                        <br>
                        <div style=" float:left">Desde :</div> <div style="float:left"><input id="fecha_mpb" name="fecha_mpb" type="text" class="fecha" value="<?=date('d-m-Y')?>" /></div>
                        <div style=" float:left">&nbsp;&nbsp;Hasta :</div><div style="float:left;"><input id="fecha_mpfb" name="fecha_mpfb" type="text" class="fecha" value="<?=date('d-m-Y')?>"/></div>
                        &nbsp;&nbsp;   
                         </div></p><br>                
                
                <? if($concentrado_bmp){ ?>
                    <p><label><input type="radio" value="38" name="tipo" onClick=" javascript: mostrarBol('38')" />
                        <span class="style7">PRODUCCION-META DE BOLSEO POR GRUPO Y TURNO</span></label></p><br>
                    <p>
          <div id="38"class="hidden_box">
                            <br />Mes 
                            <select name="mes_bols" class="style5" id="mes_bols" >
                                <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                    <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                <? } ?>
                            </select> 
                            &nbsp;&nbsp;&nbsp; Año
                            <select name="ano_bols" class="style5" id="ano_bols">
                                <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                                    <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                                <? } ?>        
                            </select>
                          	<input type="button" value="Graficar" onClick="grafica();" >
<br /><br />
          </div>
                    </p>
                    <br>
          <? } if($bolseo_i){ ?>          
                     <p><label><input type="radio" value="16" name="tipo" onClick="javascript: mostrarBol(16)" />
                            <span class="style7">REPORTE DE INCIDENCIAS DIARIO</span></label></p>
                    <p>
          			<div id="16"class="hidden_box" > 
                    <br><br>
                    <div style=" float:left">Fecha : </div><div style="float:left"><input id="fecha_incidencia" name="fecha_incidencia" type="text" class="fecha" value="<?=date('d-m-Y')?>"></div></label>
                    <div style=" float:left">Hasta : </div><div style="float:left"><input id="fecha_incidencia_f" name="fecha_incidencia_f" type="text" class="fecha" value="<?=date('d-m-Y')?>"></div></label>
          			<br><br>
                    </div>
				</p>
        	<br>
        <br>
		<? } ?>
                <? //if($concentrado_rpr){ ?>
						<p><label><input type="radio" value="52" name="tipo" onClick=" javascript: mostrarBol('52')" />
						<span class="style7">REPORTE CONTRA META Y TURNOS</span></label>
						</p><br>
                      	<p><div id="52"class="hidden_box"><br><br>
                        <div style=" float:left">Desde : </div>
                        <div style=" float:left"><input id="fecha_1" name="fecha_1" class="fecha" type="text" value="<?=date('d-m-Y')?>" /></div>
                        <div style=" float:left">&nbsp;&nbsp;Hasta : </div>
                        <div style=" float:left"><input id="fecha_1f" name="fecha_1f" class="fecha" type="text" value="<?=date('d-m-Y')?>" /></div>
                        &nbsp;&nbsp;<input type="button" name="Grafica_prod" value="Graficar" onClick=" javascript: grafica();"><br><br>
                         </div></p><br>
                         
                <? //} ?>   
						<p><label><input type="radio" value="45" name="tipo" onClick=" javascript: mostrarBol('45')" />
						<span class="style7">REPORTE DE PRODUCCION POR MAQUINA</span></label></p><br>
                      	<p><div id="45"class="hidden_box">
                        <div id="maquinas" >	<br> Maquinas:
                            <input type="hidden" name="area_maq" value="1">
                            <?	$qMa	=	"SELECT * FROM maquina WHERE area = 1 ORDER By numero"; 
								$rMa	=	mysql_query($qMa);
								$nMa	=	mysql_num_rows($rMa);
							for($a=0;$dMa	=	mysql_fetch_assoc($rMa);$a++){?>
                            <div style="float:left; width:40px; height:25px;"><input type="checkbox" name="maq_id[]" value="<?=$dMa['id_maquina']?>"><?=$dMa['numero']?></div>
                            <? if($dMa['numero']==10) echo "<br><br><br>";?>
							<? } ?>
                            </div>
                            <label><input type="checkbox" name="todos" id="todos" onClick="javascript:  chekaTodos(<?=$nMa?>,'maquinas');">Todas la maquinas</label>
                             <br><br />Mes 
                              <select name="mes_maq" class="style5" id="mes_maq" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="ano_maq" class="style5" id="ano_maq">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select>
                          	<input type="button" value="Graficar" onClick="grafica();" >
                         <br><br>
            		</div></p><br>
					<p><label><input type="radio" value="34" name="tipo" onClick=" javascript: mostrarBol('34')" />
					<span class="style7">PAROS MENSUALES.</span></label></p><br>
					<p><div id="34"class="hidden_box">
                    <input type="hidden" name="par_bol" value="1">
					<br>Mes: 
                    <select name="mes_pm" class="style5" id="mes_pm" >
					<? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                    	<option value="<?=$meses?>" <? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
					<? } ?>
					</select> 
					&nbsp;&nbsp;&nbsp; Año: 
                    <select name="anho_pm" class="style5" id="anho_pm">
					<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
						<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
					<? } ?>        
                    </select><br /><br />
					</div></p><br>
				</div>
			<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
		</td>
	</div>
</tr>    
<tr>
	<td colspan="5" align="left">&nbsp;</td>
</tr>
      <? } ?>
      <? if($_REQUEST['id_reporte'] == 5){?>
<tr>
	<td colspan="5" align="left"><br /></td>
</tr>
<tr>
	<div id="xsnazzy"> 
		<td colspan="5">
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" style="background-color: #ffffff">
				<p align="center">Varios</p><br>
      			<? if($concentrado_pd){ ?>
					<p><label><input type="radio" value="30" name="tipo" onClick=" javascript: mostrarCon('30')" />
                    <span class="style7">REPORTE DE PRODUCCION DIARIO DE EXTRUDER, IMPRESION Y BOLSEO</span></label></p><br>
         			<p><div id="30" class="hidden_box">
 					<br><div style="float:left;">Dia: </div>
                    <div style="float:left;"><input id="desde" name="desde" type="text" class="fecha" value="<?=date('d-m-Y')?>"   /></div>
            				 <input type="hidden" value="0" name="tiempo" />	
            		<br /><br />
           			</div></p><br>
      			<? } ?>
				<? if($concentrado_re){ ?>
                    <p><label><input type="radio" value="31" name="tipo" onClick=" javascript: mostrarCon('31')" />
					<span class="style7">REPORTE DIFERENCIAS REPESADAS</span></label></p><br>
					<p><div id="31"class="hidden_box">
           			<br />
                    <div style="float:left">Desde:</div><div style="float:left"><input id="desdeRepe" name="desdeRepe" type="text" class="fecha" value="<?=date('d-m-Y')?>" /></div>
                    <div style="float:left">&nbsp;&nbsp;Hasta:</div><div style="float:left"><input id="hastaRepe" name="hastaRepe" type="text" class="fecha" value="<?=date('d-m-Y')?>" /></div>
                    <br /><br /><br><br>
                    Por Supervisor: <select name="id_responsable">
                        				<option value="y">Ninguno</option>
                       					<option value="x">Todos</option>
            							<? $qSupervisor	=	"SELECT * FROM supervisor ORDER BY nombre";
											$rSupervisor	=	mysql_query($qSupervisor);
											while($dSupervisor	=	mysql_fetch_assoc($rSupervisor))
											{ ?>
                			<option value="<?=$dSupervisor['id_supervisor']?>"><?=$dSupervisor['nombre']?></option>
					<? } ?>
                    </select><br />
                    <br />Modificado o realizado por:
            		<select name="id_administrator">
            		<option value="y">Ninguno</option>
                	<option value="x">Cualquiera</option>
                	<?	$qAdmin	=	"SELECT * FROM administrador ORDER BY nombre";
                        $rAdmin	=	mysql_query($qAdmin);
                        while($dAdmin	=	mysql_fetch_assoc($rAdmin)){ ?>
                	<option value="<?=$dAdmin['id_admin']?>"><?=$dAdmin['nombre']?></option>
            		<? } ?>            
            		</select><br /><br />
                    </div></p><br>
				<? } ?>
				<? if($concentrado_ot	){ ?>
      				<p><label><input type="radio" value="32" name="tipo" onClick=" javascript: mostrarCon('32')" />
            		<span class="style7">REPORTE DE ORDENES TOTALIZADAS EN BOLSEO.</span></label></p><br>      
					<p><div id="32"class="hidden_box">
                    <br />
                   	<label>Cronologicamente: <input type="radio"  value="2" name="modelo" checked="checked"/>&nbsp;&nbsp;</label>
                   	<label>Ordenes Totalizadas: <input type="radio" value="1" name="modelo" /></label>
                    <br /><br />
                    <label><input type="checkbox" name="segundas" >Incluir Segundas</label>
        			<br><br>Orden No : <input name="orden" id="orden" accesskey="4" onKeyPress="buscarOrden('orden','resultado','orden','tabla_orden');" onKeyUp="buscarOrden('orden','resultado','orden','tabla_orden');" >
					<br>
					<div id="resultado">
					</div>        
        			<br />
					<div style="float:left">Desde:</div><div style="float:left"><input id="desdeOrd" name="desdeOrd" type="text" value="<?=date('d-m-Y')?>" class="fecha" /></div>
            		<div style="float:left">&nbsp;&nbsp;Hasta:</div><div style="float:left"><input id="hastaOrd" name="hastaOrd" type="text" value="<?=date('d-m-Y')?>"  class="fecha" /></div>
       				<br /><br /><br>
					</div></p><br>
				<? } ?>
                
                    <p><label><input type="radio" value="44" name="tipo" onClick=" javascript: mostrarCon('44')" />
            		<span class="style7">REPORTE DE ORDENES TOTALIZADAS EN EXTRUDER E IMPRESION.</span></label></p><br>      
					<p><div id="44"class="hidden_box">
                    <br />
                   	<label>Extruder: <input type="radio"  value="1" name="formaR" checked="checked"/>&nbsp;&nbsp;</label>
                   	<label>Impresion: <input type="radio"  value="2" name="formaR" />&nbsp;&nbsp;</label>
                   	<label>Ambos: <input type="radio"  value="3" name="formaR"/>&nbsp;&nbsp;</label>
                   	<label>Cronologicamente: <input type="radio"  value="2" name="modeloExt" checked="checked"/>&nbsp;&nbsp;</label>
                    <!--
                   	<label>Ordenes Totalizadas: <input type="radio" value="1" name="modeloExt" /></label> -->
                    <br /><br />
        			<br><br>Orden No : <input name="ordenExt" id="ordenExt" accesskey="4" onKeyPress="buscarOrdenExt('ordenExt','resultado2','ordenExt','tabla_orden2');" onKeyUp="buscarOrdenExt('ordenExt','resultado2','ordenExt','tabla_orden2');" >
					<br>
					<div id="resultado2">
					</div>        
        			<br />
					<div style="float:left">Desde:</div><div style="float:left"><input id="desdeOrd_ext" name="desdeOrd_ext" type="text" class="fecha" value="<?=date('d-m-Y')?>"/></div>
            		<div style="float:left">Hasta:</div><div style="float:left"><input id="hastaOrd_ext" name="hastaOrd_ext" type="text" class="fecha" value="<?=date('d-m-Y')?>"/></div>
       				<br /><br />
					</div></p><br>

             		</div>
              <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
              <br><br><br>
          </td>
         </div>
      </tr> 


<tr>
	<div id="xsnazzy"> 
		<td colspan="5">
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" style="background-color: #ffffff">
				<p align="center">Resumen Mensual</p>
				<? if($concentrado_mp){ ?>
                
					<p><label><input type="radio" value="33" name="tipo" onClick=" javascript: mostrarCon('33')" />
					<span class="style7">CONCENTRADO MENSUAL POR AREAS<br></span></label></p><br>
                	<p><div id="33"class="hidden_box">
                        <br>Mes: 
                        <select name="mes_mp" class="style5" id="mes_mp" >
                        <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                            <option value="<?=$meses?>" <? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                        <? } ?>
                        </select> 
                        &nbsp;&nbsp;&nbsp; Año
                        <select name="anho_mp" class="style5" id="anho_mp">
                        <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                            <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                        <? } ?>         
                        </select>
                        <br /><br />
                    </div></p><br>
                            
                    <? } ?>
					<? if($concentrado_pm){ ?>
					<p><label><input type="radio" value="34" name="tipo" onClick=" javascript: mostrarCon('34')" />
                  	<input type="hidden" name="par_extr" value="1">
                    <input type="hidden" name="par_impr" value="1">
                    <input type="hidden" name="par_bol" value="1">

					<span class="style7">RESUMEN DE PAROS MENSUAL.</span></label></p><br>
					<p><div id="34"class="hidden_box">
					<br>Mes: 
                    <select name="mes_pm" class="style5" id="mes_pm" >
					<? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                    	<option value="<?=$meses?>" <? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
					<? } ?>
					</select> 
					&nbsp;&nbsp;&nbsp; Año: 
                    <select name="anho_pm" class="style5" id="anho_pm">
					<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
						<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
					<? } ?>        
                    </select><br /><br />
					</div></p><br> 
                                                    
					<? } ?>
                      <? if($concentrado_rd){ ?>
                        <p><label><input type="radio" value="35" name="tipo" onClick=" javascript: mostrarCon('35')" />
                        <span class="style7">DESPERDICIOS CONTRA META POR AREAS</span></label></p><br>
                    	<p><div id="35"class="hidden_box">
                        <br>Mes 
                              <select name="mes_da" class="style5" id="mes_da" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                      </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="anho_da" class="style5" id="anho_da">
						<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
							<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
						<? } ?>        
                      </select>
                      <input type="button" name="graficar_desp" value="Graficar" onClick="grafica();">                               <br />
                                    <br /> </div></p>
                                <br>
                     <? } ?>
                     <? if($concentrado_dm){ ?>      
                    	<p><label><input type="radio" value="36" name="tipo" onClick=" javascript: mostrarCon('36')" />
                            <span class="style7">DESPERDICIOS MENSUALES.</span></label></p><br>
               			<p><div id="36"class="hidden_box">
                        <br>Mes 
                              <select name="mes_d" class="style5" id="mes_d" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; Año
                         <select name="anho_d" class="style5" id="anho_d">
						<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
							<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
						<? } ?>        
                      </select><br />
                           
                                    <br />
                                </div></p>
                                <br>        
                     <? } ?>


                                
               		</div>
              <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
          </td>
         </div>
      </tr>

      <tr>
        <td colspan="5" align="left">&nbsp;</td>
      </tr>
      <? }?>
    </table></td>
  </tr>
  
  
<? if( isset($_REQUEST['id_reporte'])) {?>
        <tr>
        <td colspan="9" align="center"><input type="button" class="button1" value="Regresar" onClick="javascript: history.go(-1)" id="logo" />&nbsp;&nbsp;<input name="Submit" type="button" class="styleTabla button1" value="Generar Reporte Web" onClick=" paso4();"/></td>       </tr>
   <? } ?>
    <tr>
      <td>&nbsp;</td>
    </tr>
</table>
</form>
<? } 
if($configuracion && ($agregar || $modificar)){?>
<form action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post" name="form_r">
<table width="70%" align="center">
	<tr>
    	<td colspan="2" align="right" class="style7"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Desde: </td>
   	  <td width="15%"><input type="text" size="12" name="desde" id="desde" value="<? if($modificar)  fecha($dato['desde']); else echo date('d/m/Y') ;?>"></td>
    </tr>
     <tr> 
      <td colspan="2" width="22%" align="right" class="style7"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Hasta: </td>
   	  <td width="42%"><input size="12" type="text" name="hasta" id="hasta" value="<? if($modificar)  fecha($dato['hasta']); else echo date('d/m/Y') ;?>"></td>
  </tr>
    <tr>
    	<td colspan="2" align="right" class="style7"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Peso Promedio Millar: </td>
    	<td colspan="3"><input type="text" name="ppm" id="ppm" value="<? if($modificar) echo  $dato['ppm']; else "0.00"; ?>"></td>
	</tr>    
    <tr>
    	<td colspan="2" align="right" class="style7"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Empaques Segundas: </td>
    	<td colspan="3"><input type="text" name="seg" id="seg" value="<? if($modificar) echo  $dato['seg']; else "0.00"; ?>"></td>
	</tr>  
     <tr>
    	<td colspan="2" align="right" class="style7"  style="background-color: rgb(25, 121, 169); color:#FFFFFF">Observaciones: </td>
    	<td colspan="3"><textarea name="observaciones" id="observaciones" cols="60" rows="4"><? if($modificar) echo $dato['observaciones']; ?></textarea></td>
	</tr>
    <? if($modificar){?><input type="hidden" name="<?=$indice?>" value="<?=$dato[$indice]?>"><? } ?> 
	<tr>
    	<td colspan="5" align="center"><br><br><br><input type="submit" name="guardar" id="guardar" class="button1" value="Guardar"><br><br></td>
    </tr> 
     <tr>
     	<td colspan="7" align="center"><input type="button" class="boton1" name="regresar" value="Regresar al Reporteo de Bolseo" onClick="javascript: location.href='<?=$_SERVER['PHP_SELF']?>?seccion=5&id_reporte=3'"><br><br></td>
     </tr>      
</table>

</form>
<? } if($listar){

   	   $qConfig	=	"SELECT * FROM configuracion_bolseo ORDER BY desde ASC";
	   $rConfig	=	mysql_query($qConfig);
	   $nConfig	=	mysql_num_rows($rConfig);

?>
<table width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
    	<td width="18%"></td>
  </tr>
    <tr>
        <td colspan="7" class="style7" align="center">| <a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=agregar" class="style7" >Agregar configuracion</a> |<br>
      <br></td>
    </tr>
<tr>
    	<td align="center"><h3>Fecha</h3></td>
    <td width="15%" align="center"><h3>Promedio Millar</h3></td>
    <td width="17%" align="center"><h3>Empaque Segundas</h3></td>
    <td align="center"><h3>Observaciones</h3></td>  
    <td colspan="2"><h3>&nbsp;</h3></td>
  </tr>  
    <? 
    for($a= 0;$dConfig=	mysql_fetch_assoc($rConfig);$a++){	?>
<tr <?=(bcmod($a,2) == 0)?"bgcolor='#DDDDDD'":""?>>
    	<td align="center" class="style5"><?=fecha_tabla($dConfig['desde']). " - ".fecha_tabla($dConfig['hasta']); ?></td>
        <td align="center" class="style7"><?=$dConfig['ppm']?></td>
        <td align="center" class="style7"><?=$dConfig['seg']?></td>
        <td width="44%" class="style5"><?=$dConfig['observaciones']?></td>
      <td width="1%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=modificar&<?=$indice?>=<?=$dConfig['id_configuracion']?>"> <img src="<?=IMAGEN_MODIFICAR?>" border="0"></a></td>
        <td width="1%"><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_GET['seccion']?>&amp;accion=eliminar&<?=$indice?>=<?=$dConfig['id_configuracion']?>" onClick="javascript: return confirm('Realmente deseas eliminar esta configuración?');"><img src="<?=IMAGEN_ELIMINAR?>" border="0"></a></td>
 
    <? } ?>   
     
  	<tr>
     	<td colspan="7" align="center"><br><br><input type="button" class="boton1" name="regresar" value="Regresar al Reporteo de Bolseo" onClick="javascript: location.href='<?=$_SERVER['PHP_SELF']?>?seccion=5&id_reporte=3'"></td>
     </tr>      
</table>


<? } ?> 
</body>
