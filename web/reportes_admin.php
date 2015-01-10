<link rel="stylesheet" type="text/css" href="desing/estilos.css">

<script type="text/javascript" src="select.js"></script>
<script language="javascript">
function paso4()
{
	document.form.action="admin_supervisor.php?seccion=39";
	document.form.submit();
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


function mostrarCon(id){
//	document.getElementById(29).style.display	= "none";
	document.getElementById(35).style.display	= "none";
	document.getElementById(37).style.display	= "none";
	document.getElementById(38).style.display	= "none";
	document.getElementById(39).style.display	= "none";
	document.getElementById(40).style.display	= "none";
	document.getElementById(41).style.display	= "none";
	document.getElementById(42).style.display	= "none";
	document.getElementById(44).style.display	= "none";

	document.getElementById(id).style.display	= "block";
}
</script>
<table align="center" width="80%">
<tr>
<td>
<form name="form" method="post" id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" >
 <? if(!isset($_REQUEST['id_reporte'])){ ?>
	<div id="xsnazzy" > 
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" style="background-color: #ffffff">
					<table width="588" align="center" cellpadding="0" cellspacing="0">
                          <tr align="center">
                              <td ><a href="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&id_reporte=5" class="style7"><img src="images/c_rep.jpg" border="0" /></a></td>
                              <td width="20%">&nbsp;</td>
                              <td ><a href="<?=$_SERVER['PHP_SELF']?>?seccion=38&accion=listar" class="style7"><img src="images/h_rep.jpg" border="0" /></a></td>
                          </tr>
                    </table>
	  			</div>
            <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
    </div>
<? } ?>
<? if($_REQUEST['id_reporte'] == 5){?>
<br><br>
	<div id="xsnazzy"> 
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" wid style="background-color: #ffffff">
                
					<p align="center">Varios</p><br>

             	
                </div>
              <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
         </div>


	<div id="xsnazzy" > 
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
				<div class="xboxcontent" style="background-color: #ffffff">
				<p align="center">Resumen Mensual<br></p>
                                                                                                

                        <p><label><input type="radio" value="35" name="tipo" onClick=" javascript: mostrarCon('35')" />
                        <span class="style7">RESUMEN DE DESPERDICIOS ALTA DENSIDAD.</span></label></p><br>
                    	<p style="height:5px;"><div id="35" style="display:none; background-color: #EEEEEE">
                        <br>Mes 
                              <select name="mes_da" class="style5" id="mes_da" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                      </select> 
                              &nbsp;&nbsp;&nbsp; A&ntilde;o
                         <select name="anho_da" class="style5" id="anho_da">
						<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
							<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
						<? } ?>        
                      </select><br />
                           
                                    <br />
                                </div></p>
                                <br>

  
                       <p><label><input type="radio" value="37" name="tipo" onClick=" javascript: mostrarCon('37')" />
                            <span class="style7">CONCENTRADO AREA DE CAMISETAS MENSUAL.</span></label></p><br>
                     <p style="height:5px;"><div id="37" style="display:none; background-color: #EEEEEE">
                             <br />Mes 
                              <select name="mes_cm" class="style5" id="mes_cm" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; A&ntilde;o
						<select name="anho_cm" class="style5" id="anho_cm">
						<? for($a = 2007 ; $a <= date('Y') ; $a++){?>
							<option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
						<? } ?>        
						</select><br /><br />
						</div></p><br> 


					  <p><label><input type="radio" value="39" name="tipo" onClick=" javascript: mostrarCon('39')" />
                            <span class="style7">PRODUCCION EXTRUDER POR GRUPO Y TURNO</span></label></p><br>
 							<p style="height:5px;"><div id="39" style="display:none; background-color: #EEEEEE">
                            <br />Mes 
                              <select name="mes_extr" class="style5" id="mes_extr" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; A&ntilde;o
                         <select name="ano_extr" class="style5" id="ano_extr">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select><br />
                                    <br />
                                </div>
                         </p>
                         <br>


                       <p><label><input type="radio" value="38" name="tipo" onClick=" javascript: mostrarCon('38')" />
                            <span class="style7">PRODUCCION-META DE BOLSEO POR GRUPO Y TURNO</span></label></p><br>
                      <p style="height:5px;"><div id="38" style="display:none; background-color: #EEEEEE">
                             <br />Mes 
                              <select name="mes_bols" class="style5" id="mes_bols" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; A&ntilde;o
                         <select name="ano_bols" class="style5" id="ano_bols">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select><br />
                
                                    <br />
                                </div></p>
                                <br>


                      	<p><label><input type="radio" value="40" name="tipo" onClick=" javascript: mostrarCon('40')" />
                        <span class="style7">KGS HORAS POR GRUPO Y TURNO</span></label></p><br>
                      	<p style="height:5px;"><div id="40" style="display:none; background-color: #EEEEEE">
						<br />Mes <select name="mes_kgs" class="style5" id="mes_kgs" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; A&ntilde;o
                         <select name="ano_kgs" class="style5" id="ano_kgs">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select>
                
                                    <br /><br>
                                </div></p>
                                <br>               
                               

						<p><label><input type="radio" value="41" name="tipo" onClick=" javascript: mostrarCon('41')" />
						<span class="style7">REPORTE PRODUCCION</span></label></p><br>
                      	<p style="height:5px;"><div id="41" style="display:none; background-color: #EEEEEE">
                             <br />Mes 
                              <select name="mes_rpr" class="style5" id="mes_rpr" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; A&ntilde;o
                         <select name="ano_rpr" class="style5" id="ano_rpr">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select><br><br>
                                </div></p><br>
                          

						<p><label><input type="radio" value="42" name="tipo" onClick=" javascript: mostrarCon('42')" />
						<span class="style7">REPORTE PRODUCCION EXTRUDER (TODO EXTRUDER)</span></label>
						</p><br>
                      	<p style="height:5px;"><div id="42" style="display:none; background-color: #EEEEEE">
                             <br />Mes 
                              <select name="mes_t_ext" class="style5" id="mes_t_ext" >
                                            <? for($meses	= 1; $meses < sizeof($mes); $meses++){ ?>
                                            <option value="<?=$meses?>" 	<? if(date('m') == $meses)echo "selected"; ?>><?=$mes[$meses]?></option>
                                            <? } ?>
                             </select> 
                              &nbsp;&nbsp;&nbsp; A&ntilde;o
                         <select name="ano_t_ext" class="style5" id="ano_t_ext">
                         <? for($a = 2007 ; $a <= date('Y') ; $a++){?>
                              <option value="<?=$a?>" <? if($a == date('Y')) echo "selected";?>><?=$a?></option>
                         <? } ?>        
                         </select><br><br>
                         </div></p><br>
                <? //} ?>   
                
                    <p><label><input type="radio" value="44" name="tipo" onClick=" javascript: mostrarCon('44')" />
            		<span class="style7">REPORTE DE ORDENES TOTALIZADAS EN EXTRUDER E IMPRESION.</span></label></p><br>      
					<p style="height:5px;"><div id="44" style="display:none; background-color: #EEEEEE">
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
					Desde: <input id="desdeOrd_ext" name="desdeOrd_ext" type="text" value="<?=date('d-m-Y')?>" />
            		&nbsp;&nbsp;Hasta: <input id="hastaOrd_ext" name="hastaOrd_ext" type="text" value="<?=date('d-m-Y')?>" />
       				<br /><br />
					</div></p><br>



                
                
                
                             
               		</div>
              <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
         </div>
      <? }?>

  
<? if( isset($_REQUEST['id_reporte'])) {?>
	<div align="center">
<input type="button" value="Regresar" onClick="javascript: history.go(-1)" />&nbsp;&nbsp;<input name="Submit" type="button" class="texto3" value="Generar Reporte Web" onClick=" paso4();"/>
    </div>
   <? } ?>
</form>
</td></tr></table>
</body>