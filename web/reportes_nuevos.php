<?php include "libs/conectar.php"; ?>
<style type="text/css" media="screen">@import 'DatePicker.css';</style>
<!-- <script type="text/javascript" src="http://www.styledisplay.com/mootoolsdatepicker/mootools.v1.11.js"></script>-->
<script type="text/javascript" src="DatePicker.js"></script>
<script type="text/javascript" src="select.js"></script>

<script type="text/javascript">

// The following should be put in your external js file,
// with the rest of your ondomready actions.

window.addEvent('domready', function(){

	$$('input.DatePicker').each( function(el){
		new DatePicker(el);
	});

});


</script>


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

 if(document.form.id_empleado.value==0)
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

function paso6()
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

function paso7()
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

function paso8()
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

</script>

<form name="form" method="post" action="<? if($_REQUEST['id_reporte'] < 4) { ?>generar_pdf.php<? } if($_REQUEST['id_reporte'] > 4){ ?>reportes/desperdicios_generales.php<? } ?>" onSubmit="paso2();">
<table width="500" border="0" align="center" class="texto2">
  <tr>
    <td class="contenidos" align="right">Seleccion de reporte : </td>
    <td>
	<select name="id_reporte" class="eliminar" onChange="paso3();">
	  <option value="0" <? if($_REQUEST['id_reporte']==0) echo "selected";?>>Elija un reporte</option>
	  <option value="1" <? if($_REQUEST['id_reporte']==1) echo "selected";?>>Reporte Total de Empleados</option>
	  <option value="2" <? if($_REQUEST['id_reporte']==2) echo "selected";?>>Reporte de Produccion </option>
      <option value="3" <? if($_REQUEST['id_reporte']==3) echo "selected";?>>Reporte de Produccion por Maquina</option>
      <option value="5" <? if($_REQUEST['id_reporte']==5) echo "selected";?>>Reporte de Desperdicios</option>
      <option value="6" <? if($_REQUEST['id_reporte']==6) echo "selected";?>>Reporte de Metas</option>
    </select>	</td>
  </tr>
  
<? if($_REQUEST['id_reporte']==1) {?> 
   <tr>
  	<td colspan="3"><br /></td>
  </tr> 
  <tr>
  	<td class="contenidos" align="right">Area : </td>
  	 <td> <select name="area" >
        <option value="0" selected="selected">Todas</option>
        <option value="1">Extruder</option>
        <option value="3">Impresion</option>
        <option value="2">Bolseo</option>
      </select>
     </td>
  </tr>
  <tr></tr>
<? }?> 

<? if($_REQUEST['id_reporte']==2){?> 
<tr>
	<td><br />
	  <br /></td>
</tr>
  <tr>
    <td class="contenidos">Elija un rango de fechas: </td>
    <td>
      <table width="266" border="0">
       <tr>
          <td width="31"><span class="contenidos">Desde:
            </span></td>
          <td width="225"><input id="desde" name="desde" type="text" class="DatePicker" alt="{
			dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<?=date('d-m-Y')?>" /></td>
        </tr>
        <tr>
          <td class="contenidos">Hasta:</td>
          <td class="contenidos"><input id="hasta" name="hasta" type="text" class="DatePicker" alt="{
		dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<?=date('d-m-Y')?>" /></td>
        </tr>
      </table>
    </td>
</tr>
  <tr>
    <td>Supervisor: </td>
    <td><select name="id_supervisor">
    	<? 
		 	$qSupervisor	=	"SELECT * FROM supervisor ORDER BY nombre";
			$rSupervisor	=	mysql_query($qSupervisor);
			while($dSupervisor	=	mysql_fetch_assoc($rSupervisor)){
		?>
    		<option value="<?=$dSupervisor['id_supervisor']?>"><?=$dSupervisor['nombre']?></option>
		<? } ?>
    </select></td>
  </tr>
  <tr>
    <td>Turno: </td>
    <td><select name="turno">
    		<option value="0">Todos</option>
    		<option value="1">Matutino</option>
    		<option value="2">Vespertino</option>
    		<option value="3">Nocturno</option>
    </select></td>
  </tr>
    <tr>
    <td>Area: </td>
    <td><select name="area">
    		<option value="0">Todas</option>
    		<option value="1">Extruder</option>
    		<option value="2">Bolseo</option>
    		<option value="3">Impresion</option>
    </select></td>
  </tr>
  <tr>
  	<td>
    	<table cellpadding="0" cellspacing="0">
        	<tr>
            	<td>Listado de reportes</td>
            </tr>
        </table>
    </td>
  </tr>
<? } ?> 

<? if($_REQUEST['id_reporte'] == 3){?> 
<tr>
	<td colspan="2"><br /></td>
</tr>
  <tr>
  <td class="contenidos"><br />
    Area : </td>
   <td><br />
   <select name="area" id="area" onChange='cargaContenido(this.id)' >
     <option value="0" >Selecciona un area</option>
     <option value="1" >BOLSEO</option>
     <option value="2" >IMPRESION</option>
     <option value="3" >LINEAS DE IMPRESION</option>
     <option value="4" >EXTRUDER</option>
   </select></td>
   </tr>
   <tr>
   	<td>Maquina :</td>
   	<td><div id="demo"><div id="demoDer"><select disabled="disabled" name="id_maquina" id="id_maquina">
						<option value="0">Selecciona opci&oacute;n...</option>
					</select></div></div>   
   </td></tr>
    <tr>
    <td class="contenidos">Elija un rango de fechas: </td>
    <td>
      <table width="266" border="0">
     <tr>
          <td width="31"><span class="contenidos">Desde:</td>
          <td width="225"><input id="desde" name="desde" type="text" class="DatePicker" alt="{
			dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<?=date('d-m-Y')?>" /></td>
        </tr>
        <tr>
          <td class="contenidos">Hasta:</td>
          <td class="contenidos"><input id="hasta" name="hasta" type="text" class="DatePicker" alt="{
			dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<?=date('d-m-Y')?>" /></td>
        </tr>
            </table>            </td>
</tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<? }?> 



<? if($_REQUEST['id_reporte']==4){?> 
<tr>
	<td>&nbsp;<br />
<br />
</td>
</tr>
  <tr>
  <td class="contenidos">Supervisor: </td>
   <td><select name="id_supervisor" id="id_supervisor" class="eliminar" >
    <?php
    $query = "SELECT * FROM supervisor ORDER BY nombre ASC";
    $res = mysql_query($query);
    
    while( $depa = mysql_fetch_row($res)) { ?>
   <option value="<?=$depa[0]?>" ><?=$depa[1]?></option>
                                   <?php  }  ?>
    </select>    </td>
  </tr>
  
   <tr>
    <td class="contenidos">Elija un rango de fechas: </td>
    <td>
      <table width="266" border="0">
       <tr>
          <td width="31"><span class="contenidos">Desde:</span></td>
          <td width="225"><input id="desde" name="desde" type="text" class="DatePicker" alt="{
			dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<?=date('d-m-Y')?>" /></td>     
     </tr>
      <tr>
          <td class="contenidos">Hasta:</td>
          <td class="contenidos"><input id="hasta" name="hasta" type="text" class="DatePicker" alt="{
			dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<?=date('d-m-Y')?>" /></td>
        </tr>
            </table>            </td>
</tr>

   <tr></tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<? }?> 

<? if($_REQUEST['id_reporte']==5){?> 
  <tr>
  <td class="contenidos">&nbsp; </td>
   <td>&nbsp;</td>
  </tr>
   <tr>
    <td class="contenidos">Elija un rango de fechas: </td>
    <td>
      <table width="266" border="0">
      <tr>
          <td width="31"><span class="contenidos">Mes :</span></td>
          <td width="225">
          <select name="m">
          	<? for($a=1; $a < 13; $a++){ ?>
          	<option value="<?=$a?>">
            <? 
			if($a == 1) echo "Enero"; 
			if($a == 2) echo "Febrero"; 
			if($a == 3) echo "Marzo"; 
			if($a == 4) echo "Abril"; 
			if($a == 5) echo "Mayo"; 
			if($a == 6) echo "Junio"; 
			if($a == 7) echo "Julio"; 
			if($a == 8) echo "Agosto"; 
			if($a == 9) echo "Septiembre"; 
			if($a == 10) echo "Octubre"; 
			if($a == 11) echo "Noviembre"; 
			if($a == 12) echo "Diciembre"; 

			
			?></option>
          <? }?>
          </select></td>     
     </tr>
      <tr>
          <td class="contenidos">Año: </td>
          <td class="contenidos"><select name="a">
          <? for($b = 2007; $b < 2015; $b++){ ?>
          	<option value="<?=$b?>"><?=$b?></option>
           <? } ?>
          </select></td>
        </tr>
            </table>            </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<? }?>


<? if($_REQUEST['id_reporte']==6){?> 
  <tr>
  <td class="contenidos">&nbsp; </td>
   <td>&nbsp;</td>
  </tr>
   <tr>
    <td class="contenidos">Elija un rango de fechas: </td>
    <td>
      <table width="266" border="0">
      <tr>
      	<td>Area:</td>
        <td><select name="area">
        		<option value="1">Extruder</option>
        		<option value="2">Impresion</option>
        		<option value="3">Bolseo</option>
        	</select></td>
      </tr>
      <tr>
          <td width="31"><span class="contenidos">Mes :</span></td>
          <td width="225">
          <select name="m">
          	<? for($a=1; $a < 13; $a++){ ?>
          	<option value="<?=$a?>">
            <? 
			if($a == 1) echo "Enero"; 
			if($a == 2) echo "Febrero"; 
			if($a == 3) echo "Marzo"; 
			if($a == 4) echo "Abril"; 
			if($a == 5) echo "Mayo"; 
			if($a == 6) echo "Junio"; 
			if($a == 7) echo "Julio"; 
			if($a == 8) echo "Agosto"; 
			if($a == 9) echo "Septiembre"; 
			if($a == 10) echo "Octubre"; 
			if($a == 11) echo "Noviembre"; 
			if($a == 12) echo "Diciembre"; 

			
			?></option>
          <? }?>
          </select></td>     
     </tr>
      <tr>
          <td class="contenidos">Año: </td>
          <td class="contenidos"><select name="a">
          <? for($b = 2007; $b < 2015; $b++){ ?>
          	<option value="<?=$b?>"><?=$b?></option>
           <? } ?>
          </select></td>
        </tr>
            </table>            </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<? }?>

<? if($_REQUEST['id_reporte']==7){?> 
<tr>
	<td>&nbsp;<br />
<br />
</td>
</tr>
  <tr>
  <td class="contenidos">Supervisor: </td>
   <td><select name="id_supervisor" id="id_supervisor" class="eliminar" >
    <?php
    $query = "SELECT * FROM supervisor ORDER BY nombre ASC";
    $res = mysql_query($query);
    
    while( $depa = mysql_fetch_row($res)) { ?>
   <option value="<?=$depa[0]?>" ><?=$depa[1]?></option>
                                   <?php  }  ?>
    </select>    </td>
  </tr>
  
   <tr>
    <td class="contenidos">Elija un rango de fechas: </td>
    <td>
      <table width="266" border="0">
       <tr>
          <td width="31"><span class="contenidos">Desde:</span></td>
          <td width="225"><input id="desde" name="desde" type="text" class="DatePicker" alt="{
			dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<?=date('d-m-Y')?>" /></td>     
     </tr>
      <tr>
          <td class="contenidos">Hasta:</td>
          <td class="contenidos"><input id="hasta" name="hasta" type="text" class="DatePicker" alt="{
			dayChars:3,
			dayNames:['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			daysInMonth:[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
			format:'dd-mm-yyyy',
			monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			startDay:1,
			yearOrder:'desc',
			yearRange:90,
			yearStart:2007
		}" tabindex="1" value="<?=date('d-m-Y')?>" /></td>
        </tr>
            </table>            </td>
</tr>

   <tr></tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
     <tr>
    <td colspan="2" align="right"><input name="Submit" type="button" class="texto3" value="Generar" onClick="<? if($_REQUEST['id_reporte'] == 5 ) { ?>genera2();<? }  if($_REQUEST['id_reporte'] == 6 ) { ?>genera3();<? } if($_REQUEST['id_reporte'] < 5) { ?> genera(); <? } ?>"/></td>
  </tr>
<? }?>

  <tr><td colspan="3"><br />
    <br />
    <br /></td></tr>
</table>
</form>
