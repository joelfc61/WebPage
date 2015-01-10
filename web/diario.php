<?php
include('libs/conectar.php');

if(!empty($_GET['accion']))
{	
		$listar	=	($_GET['accion']=="listar")?true:false;

	if(!empty($_GET['id_entrada_general']) && is_numeric($_GET['id_entrada_general']) )
	{
		$extruder	=	($_GET['accion']=="extruder")?true:false;
		$impresion	=	($_GET['accion']=="impresion")?true:false;
	}
	if(!empty($_GET['id_bolseo']) && is_numeric($_GET['id_bolseo']) )
	{
		$bolseo	=	($_GET['accion']=="bolseo")?true:false;
	}
	if($extruder)
	{	
		$qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
								"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE (id_entrada_general={$_REQUEST[id_entrada_general]})";
		$rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");
		$dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral);
		
		$qOrdenProduccion	=	"SELECT * FROM orden_produccion WHERE (id_entrada_general = {$dEntradaGeneral[id_entrada_general]})";
		$rOrdenProduccion	=	mysql_query($qOrdenProduccion) OR die("<p>$qOrdenProduccion</p><p>".mysql_error()."</p>");
		
		$qActuImpresion	=	"SELECT * FROM impresion WHERE (id_impresion = {$dEntradaGeneral[id_entrada_general]})";
		$rActuImpresion	=	mysql_query($qActuImpresion) OR die("<p>$qImpresion</p><p>".mysql_error()."</p>");		
	}
	if($impresion)
	{	
		$qEntradaGeneral	=	"SELECT id_entrada_general, fecha, turno, supervisor.nombre, supervisor.id_supervisor, supervisor.rol FROM entrada_general INNER JOIN supervisor ".
								"ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE (id_entrada_general={$_REQUEST[id_entrada_general]})";
		$rEntradaGeneral	=	mysql_query($qEntradaGeneral) OR die("<p>$qEntradaGeneral</p><p>".mysql_error()."</p>");
		$dEntradaGeneral	=	mysql_fetch_assoc($rEntradaGeneral);
				
		$qActuImpresion	=	"SELECT * FROM impresion WHERE (id_entrada_general = {$dEntradaGeneral[id_entrada_general]})";
		$rActuImpresion	=	mysql_query($qActuImpresion) OR die("<p>$qActuImpresion</p><p>".mysql_error()."</p>");	
		
		
			$num = mysql_num_rows($rActuImpresion);
		 	$dImpresion	=	mysql_fetch_assoc($rActuImpresion);	
	}	
	if($bolseo)
	{	

		$qBolseo	=	"SELECT * FROM bolseo INNER JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor WHERE (id_bolseo={$_REQUEST[id_bolseo]})";
		$rBolseo	=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");
		$dBolseo	=	mysql_fetch_assoc($rBolseo);
	
	}


}
?>
<head>
	<script type="text/javascript" src="mootools.js"></script>
	<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />	
    <!--<link rel="shortcut icon" href="http://moofx.mad4milk.net/favicon.gif" />-->
	<title>Reportes</title>
	<style type="text/css" media="screen">@import 'style.css';</style>
    <style type="text/css" media="screen">
	<!--
	ul.navlist
	{
		width: 558px;
		\width: 560px;
		w\idth: 558px;
		padding: 0px;
		border: 1px solid #808080;
		border-top: 0px;
		margin: 0px;
		font: bold 12px verdana,helvetica,arial,sans-serif;
		background: #FFFFFF;
	}
	
	ul.navlist li
	{
		list-style: none;
		margin: 0px;
		border: 0px;
		border-top: 1px solid #FFFFFF;
	}
	
	ul.navlist li a
	{
		display: block;
		width: 522px;
		\width: 558px;
		w\idth: 522px;
		padding: 4px 8px 4px 8px;
		border: 0px;
		border-left: 20px solid #aaaabb;
		background: #FFFFFF;
		text-decoration: none;
		text-align: right;
	}
	
	ul.navlist li a:link { color: #666677; }
	div.navcontainer li a:visited { color: #666677; }

	div.navcontainer { padding-left: 40px; }

	ul.navlist li a:hover
	{
		border-color:#0A4662;
		color: #ffffff;
		background: #000d33;
	}
	-->
	</style>
</head>
<? if($bolseo){ ?>
<body>
<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
		<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor: </label><?=$dBolseo['nombre']?><br />
				<label for="fecha">Fecha: </label><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dBolseo['fecha'])?><br />
                <label for="fecha">Turno: </label>
                	<? if($dBolseo['turno'] == 1) echo "Matutino";?>
                	<? if($dBolseo['turno'] == 2) echo "Vespertino";?>
                	<? if($dBolseo['turno'] == 3) echo "Nocturno";?>
          <br />
			</p>
			<table class="tablaCentrada">
				<colgroup>
					<col class="ordenTrabajo" />
					<col class="kilogramos" />
					<col class="millares" />
					<col class="desperdicioTira" />
					<col class="desperdicioTroquel" />
					<col class="segundas" />
				</colgroup>
				
			</table>
		</div>
		<br /><br />
		<? 	
		$qResumenMaquinas	=	"SELECT * FROM resumen_maquina_bs INNER JOIN maquina ON resumen_maquina_bs.id_maquina = maquina.id_maquina ".
								" LEFT JOIN operadores ON resumen_maquina_bs.id_operador = operadores.id_operador WHERE (id_bolseo=".$dBolseo['id_bolseo'].") ORDER BY maquina.numero";
				$rResumenMaquinas	=	mysql_query($qResumenMaquinas) OR die("<p>$qResumenMaquinas</p><p>".mysql_error()."</p>");
				while($dResumenMaquinas	=	mysql_fetch_assoc($rResumenMaquinas)){
		 ?>
         <tr>
         	<td>
            	<table width="70%" align="center" cellpadding="0" cellspacing="0">
                <tr>
                	<td>
		<h3><a style="color: rgb(255, 255, 255);" href="#maquina<?=$dResumenMaquinas['numero']?>">Bolseadora <?=$dResumenMaquinas['numero']?>
	    -  
	    <?=$dResumenMaquinas['nombre'] ?> </a></h3>

		<div id="tabla_<?=$dResumenMaquinas['id_maquina']?>" style="background-color:#FFFFFF;">
			<table class="tablaCentrada" cellpadding="0" cellspacing="0">
				<colgroup>
					<col class="ordenTrabajo" />
					<col class="millares" />
					<col class="kilogramos" />
					<col class="desperdicioTira" />
					<col class="desperdicioTroquel" />
					<col class="segundas" />
				</colgroup>
				<thead>
					<tr>
						<td><b>O. T.</b></td>
						<td><b>Millares</b></td>
						<td><b>Kilogramos</b></td>
						<td><b>D. Tira</b></td>
						<td><b>D. Troquel</b></td>
						<td><b>Segundas</b></td>
					</tr>
				</thead>
				<tbody><? 
					$qDetalleResumen	=	"SELECT * FROM detalle_resumen_maquina_bs WHERE (id_resumen_maquina_bs = ".$dResumenMaquinas['id_resumen_maquina_bs'].")";
					$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
					for($a = 0;$dDetalleResumen	= 	mysql_fetch_assoc($rDetalleResumen); $a++){	
					?>
                    <tr <? if(bcmod($a,2) == 0) echo "bgcolor='#EEEEEE'"; else echo "";?>>
						<td class="style5"><?=$dDetalleResumen['orden']?></td>
						<td class="style5"><?=$dDetalleResumen['millares']?></td>
                        <td class="style5"><?=$dDetalleResumen['kilogramos']?></td>
						<td class="style5"><?=$dDetalleResumen['dtira']?></td>
						<td class="style5"><?=$dDetalleResumen['dtroquel']?></td>
						<td class="style5"><?=$dDetalleResumen['segundas']?></td>
					</tr>
				<?php } ?>
					<tr>
						<td><strong>Subtotales</strong></td>
						<td class="style5"><b><?=$dResumenMaquinas['kilogramos']?></b></td>
						<td class="style5"><b><?=$dResumenMaquinas['millares']?></b></td>
						<td class="style5"><b><?=$dResumenMaquinas['dtira']?></b></td>
						<td class="style5"><b><?=$dResumenMaquinas['dtroquel']?></b></td>
						<td class="style5"><b><?=$dResumenMaquinas['segundas']?></b></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
				</tbody>
			</table></div></td></tr></table>
		<?php } ?>
        <br /><br />
        <div id="datosgenerales" style="background-color:#FFFFFF;">
        <table width="737" align="center">
		<tbody>

        			<tr>
						<td width="93">&nbsp;</td>
						<td width="123" align="center" class="style5"><strong>KILOGRAMOS</strong></td>
						<td width="123" align="center" class="style5"><strong>MILLARES</strong></td>
						<td width="111" align="center" class="style5"><strong>TIRA</strong></td>
						<td width="121" align="center" class="style5"><strong>TROQUEL</strong></td>
						<td width="138" align="center" class="style5"><strong>SEGUNDAS</strong></td>
		  </tr>
					<tr>
						<td><strong>TOTALES: </strong></td>
						<td class="style5" align="center"><b><?=$dBolseo['kilogramos']?></b></td>
						<td class="style5" align="center"><b><?=$dBolseo['millares']?></b></td>
						<td class="style5" align="center"><b><?=$dBolseo['dtira']?></b></td>
						<td class="style5" align="center"><b><?=$dBolseo['dtroquel']?></b></td>
						<td class="style5" align="center"><b><?=$dBolseo['segundas']?></b></td>
					</tr>
                <tr>
        	<td colspan="4">&nbsp;</td>
        </tr>
                <tr>
        	<td colspan="4">&nbsp;</td>
        </tr>
            
                <tr>
        	<td colspan="4">&nbsp;</td>
        </tr>
		  </tbody>
        </table>
        </div>   
<p align="center"><strong><br>
   		      <br>
   		    TIEMPOS MUERTOS</strong><br />
   		    <br />
    		</p>             
    <p align="center">          
           
		<?  
		 	$sql_lic= "SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dBolseo['id_bolseo']."' AND tipo= 4 ORDER BY numero ASC ";
			$res_lic=mysql_query($sql_lic);
			$cant_lic=mysql_num_rows($res_lic);
			$cant=ceil($cant_lic/1);
			$c=0;
			while($muertos = mysql_fetch_assoc($res_lic))
			{
				$codigo[$c]		=	$muertos['numero'];
				$id[$c] 		= 	$muertos['id_maquina'];
				$falta[$c]			=	$muertos['falta_personal'];
				$mantenimiento[$c]	=	$muertos['mantenimiento'];
				$fallo[$c]			=	$muertos['fallo_electrico'];
				$observaciones[$c]	=	$muertos['observaciones'];
				$c++;
			}
			$reg=0;
			for($i=0;$i<$cant;$i++)
{
?>
<table width="70%" align="center" style="border:#CCCCCC; border:thin">
<tr>
    <? for($x=1;$x<=1; $x++){?>
<td width="70%" align="center" valign="top"><br />
  <? if($reg<$cant_lic){ ?>				
		  
		<h3 align="left" style="color: rgb(255, 255, 255);">MAQUINA <?=$codigo[$reg]?>
		</h3>
<div id="div_<?=$id[$reg]?>" style="display: ">
<table width="88%" align="center" cellpadding="0" cellspacing="0" >
<? if( $observaciones[$reg] != "" || $falta[$reg] != "00:00:00" || $fallo[$reg] != "00:00:00" || $mantenimiento[$reg] != "00:00:00" ) {?>
<? if($observaciones[$reg]){ ?>
<tr>
						<td  align="left"><strong>Observaciones</strong></td>
				  <td colspan="3" align="left"><?=nl2br($observaciones[$reg])?></td>
                    </tr>
<? } if($falta[$reg] != "00:00:00"){ ?>
                  	<tr>
           	  			<td align="left" class="style7">Falta de Personal :</td>
               		  <td width="460" align="left"><? if($falta[$reg] != "00:00:00") echo "Si" ?></td>
                </tr>
<? } if($fallo[$reg] != "00:00:00"){ ?>
                    <tr>
           	  			<td width="133" align="left" class="style7">Fallo E. Electrica :</td>
                    <? $tiempo2 	= explode(":" ,$fallo[$reg]);  $horas2	=	$tiempo2[0]; $minutos2 =	$tiempo2[1];?>
           		  	  <td width="460" align="left"><?=$horas2?>:<?=$minutos2?></td>
   			    </tr>
<? } if($mantenimiento[$reg] != "00:00:00"){ ?>
             		<tr>
           	  			<td align="left" class="style7">Mantenimiento :</td>
                    <? $tiempo3 	= explode(":" ,$mantenimiento[$reg]);  $horas3	=	$tiempo3[0]; $minutos3 =	$tiempo3[1];?>
               			<td width="460" align="left"><?=$horas3?>:<?=$minutos3?></td>
                </tr>
        <? } ?>
<? } else {?>
<tr>
	<td colspan="2">SIN OBSERVACIONES</td>
</tr>
<? } ?>			</table>
		  </div>

  <? $reg++;}?></td><? }?>
</tr>
</table>
  <? }?>   <br>
<br>
<br>
<br>
      </p>        
      
  
		<div id="barraSubmit" style="background-color:#FFFFFF; text-align:right;">
                      <input type="button" name="regresar" id="regresar" value="Regresar" onClick="javascript: history.go(-1);">
                      <input type="button" name="imprimir" id="imprimir" value="Imprimir" onClick="javascript: window.print(); ">		
        </div>
		<?php }  if($extruder){ ?>
<body>
<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
		<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor: </label><?=$dEntradaGeneral['nombre']?><br />
				<label for="fecha">Fecha: </label><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?><br />
                <label for="fecha">Turno: </label>
					<? if($dEntradaGeneral['turno'] == 1) echo "Matutino";?>
                	<? if($dEntradaGeneral['turno'] == 2) echo "Vespertino";?>
                	<? if($dEntradaGeneral['turno'] == 3) echo "Nocturno";?>
             <br />
			</p><br>
          <div align="center">
      <b>Extruder de alta - Nave 2</b>
      <br>
      </div>
 <p align="center">          
           
		<?  
		
			$dOrdenProduccion	=	mysql_fetch_assoc($rOrdenProduccion);
			
			$sql_lic	=	"SELECT * FROM resumen_maquina_ex INNER JOIN maquina ON resumen_maquina_ex.id_maquina = maquina.id_maquina ".
								" LEFT JOIN operadores ON resumen_maquina_ex.id_operador = operadores.id_operador WHERE (id_orden_produccion=".$dOrdenProduccion['id_orden_produccion'].") ORDER BY maquina.numero";
			$res_lic	=	mysql_query($sql_lic) OR die("<p>$qResumenMaquinas</p><p>".mysql_error()."</p>");
			$cant_lic=mysql_num_rows($res_lic);
			$cant=ceil($cant_lic/3);
			$a=0;
			while($dResumenMaquinas = mysql_fetch_assoc($res_lic))
			{
				$numero[$a]					= $dResumenMaquinas['numero'];
				$id_maquina[$a] 			= $dResumenMaquinas['id_maquina'];
				$id_operador[$a]			= $dResumenMaquinas['id_operador'];
				$nombre[$a]					= $dResumenMaquinas['nombre'];
				$id_resumen_maquina_ex[$a]	= $dResumenMaquinas['id_resumen_maquina_ex'];
				$subtotales[$a]				= $dResumenMaquinas['subtotal'];
				$a++;
			}
			$reg=0;
			for($i=0;$i<$cant;$i++)
{
?>
<table align="center">
<tr>
    <? for($x=1;$x<=4; $x++){?>
<td width="190" align="left" valign="top"><br />
  <? if($reg<$cant_lic)
  		{
  
		    $qOperadorextr = "SELECT oper_maquina.id_operador, nombre FROM oper_maquina INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador WHERE id_maquina = ".$id_maquina[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";
			$rOperadorextr = mysql_query($qOperadorextr);
			$dAsignacionextr = mysql_fetch_assoc($rOperadorextr);?>
            
		<h3 align="left" style="color: rgb(255, 255, 255); font-size:9px">EXTRUDER <?=$numero[$reg]?><br>
        Op.: 	<?=$nombre[$reg] ?>
         </h3>
<table width="194" height="59"  cellpadding="0" cellspacing="0" >
<thead>
					<tr>
						<td width="67" height="18"><strong>O. T.</strong></td>
					  <td width="224"><strong>Kilogramos</strong></td>
			    </tr>
				</thead>
				<tbody>
				<?php 
					
		 	$qDetalleResumen	=	"SELECT orden_trabajo, kilogramos FROM detalle_resumen_maquina_ex WHERE (id_resumen_maquina_ex = ".$id_resumen_maquina_ex[$reg].")";
			$rDetalleResumen	=	mysql_query($qDetalleResumen) OR die("<p>$qDetalleResumen</p><p>".mysql_error()."</p>");
			for($a = 0;$dDetalleResumen	= 	mysql_fetch_assoc($rDetalleResumen); $a++){	
					?>
					<tr <? if(bcmod($a,2) == 0) echo "bgcolor='#EEEEEE'"; else echo "";?>>
						<td height="19" class="style5"><?=$dDetalleResumen['orden_trabajo']?></td>
					  <td class="style5"><?=$dDetalleResumen['kilogramos']?></td>
					</tr>
        <?php  }  ?>
					<tr>
						<td height="20" ><strong>Subtotal</strong></td>
					  <td class="style5"><b><?=$subtotales[$reg]?></b></td>
					</tr>
				</tbody>
		  </table>
            
  <? $reg++;}?></td><? }?>
</tr>
</table>
  <? }?>
  </p>


<div id="datosgenerales" style="background-color:#FFFFFF;">
<table align="center">
  <tbody>
        			<tr>
						<td width="73"></td>
						<td width="142" align="center">PRODUCCION TOTAL</td>
						<td width="162" align="center">DESPERDICIOS TIRA</td>
						<td width="185" align="center">DESPERDICIOS DURO</td>
			  </tr>
					<tr>
						<td><strong>TOTALES: </strong></td>
						<td align="center" class="style5"><b><?=$dOrdenProduccion['total']?></b></td>
					  <td align="center" class="style5"><b><?=$dOrdenProduccion['desperdicio_tira']?></b></td>
					  <td align="center" class="style5"><b><?=$dOrdenProduccion['desperdicio_duro']?></b></td>
			  </tr>
			</tbody> 
      </table></div>

<p align="center"><strong><br>
   		      <br>
   		    TIEMPOS MUERTOS</strong><br />
   		    <br />
    		</p>
<p align="center">          
           
		<?  
	
		 	$sql_lic= "SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dOrdenProduccion['id_orden_produccion']."' AND tipo= 1 ";
			$res_lic=mysql_query($sql_lic);
			$cant_lic=mysql_num_rows($res_lic);
			$cant=ceil($cant_lic/3);
			$c=0;
			while($muertos = mysql_fetch_assoc($res_lic))
			{
				$codigo[$c]		=	$muertos['numero'];
				$id[$c] 		= 	$muertos['id_maquina'];
				$falta[$c]			=	$muertos['falta_personal'];
				$mantenimiento[$c]	=	$muertos['mantenimiento'];
				$mallas[$c]			=	$muertos['mallas'];
				$fallo[$c]			=	$muertos['fallo_electrico'];
				$observaciones[$c]	=	$muertos['observaciones'];
				$c++;
			}
			$reg=0;
			for($i=0;$i<$cant;$i++)
{
?>

<table align="center">
<tr>
    <? for($x=1;$x<=4; $x++){?>
<td width="190" align="left" valign="top">
  <? if($reg<$cant_lic){ ?>		

		<h3 align="left" style="color: rgb(255, 255, 255);">EXTRUDER <?=$codigo[$reg]?> 
		  <br /></h3>
<div id="extruder<?=$codigo[$reg]?>">
<table width="190" cellpadding="0" cellspacing="0" >
<? if( $observaciones[$reg] != "" || $falta[$reg] != "00:00:00" || $fallo[$reg] != "00:00:00" || $mantenimiento[$reg] != "00:00:00" ){ ?> 
<tbody>
	<?  if($observaciones[$reg] != ""){ ?>
			 <tr>
						<td width="96"><strong>Obser.</strong></td>
				        <td width="132" colspan="3"><textarea  name="ob_extruder<?=$codigo[$reg]?>" id="ob_extruder<?=$codigo[$reg]?>" cols="12" rows="3" ><?=$observaciones[$reg]?></textarea></td>
                  </tr>
     <? } ?>
                  <TR>
                  	<td colspan="2">
           	   <table width="100%">
               <? if($mallas[$reg] == 1){ ?>
                  			   <tr>
                        		<td>Cambio de mallas: </td>                      
                                <td  style="font-size:10px"><strong>SI</strong></td>
                               </tr>
                           <? } if($falta[$reg] != "00:00:00"){ ?>
                               <tr>
                                  <td width="111" align="left" class="style7">Falta de Personal:</td>
                                 <td width="65">SI</td>
                   		  </tr>
                           <? } if($fallo[$reg] != "00:00:00"){ ?>
                              <tr>
                                  <td width="111" align="left" class="style7">Fallo Electrico:</td>
                   				<? $tiempo2 	= explode(":" ,$fallo[$reg]);  $horas2	=	$tiempo2[0]; $minutos2 =	$tiempo2[1];?>
                                <td width="65"><?=$horas2?>:<?=$minutos2?> Hrs.</td>
                          </tr>
                           <? } if($mantenimiento[$reg] != "00:00:00"){ ?>
                                       <tr>
                                          <td height="22" align="left" class="style7">Mantenimiento:</td>
                    				<? $tiempo3 	= explode(":" ,$mantenimiento[$reg]);  $horas3	=	$tiempo3[0]; $minutos3 =	$tiempo3[1];?>
                                         <td width="65"><?=$horas3?>:<?=$minutos3?> Hrs.</td>
                          </tr>
							<? } ?>
						</table>
                  </td>
                 </TR>
				</tbody>
             <? } else { ?>
             <tbody>
             	<tr>
                	<td colspan="2">SIN OBSERVACIONES</td>
                </tr>
             </tbody>
            <? } ?>
		  </table>
  </div>        
            
  <? $reg++;}?>
  <br /></td><? }?>
</tr>
</table>
  <? }?>
  </p>

          <br><br>
          <div id="datosgenerales" style="background-color:#FFFFFF;">
<table align="right">  	
                          <tr>      
                      <td  align="right" colspan="7">
                      <input type="button" name="regresar" id="regresar" value="Regresar" onClick="javascript: history.go(-1);">
                      <input type="button" name="imprimir" id="imprimir" value="Imprimir" onClick="javascript: window.print(); ">
                      </td>
					</tr>
        </table>
        </div>   
        
          <br><br>
</div></div></div></form>

<? } if($impresion) { ?>
<body>
<form id="form" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
<div id="container">
	<br />
	<div id="content">
		<div id="datosgenerales" style="background-color:#FFFFFF;">
			<p>
				<label for="supervisor">Supervisor: </label><?=$dEntradaGeneral['nombre']?><br />
				<label for="fecha">Fecha: </label><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dEntradaGeneral['fecha'])?><br />
                <label for="fecha">Turno: </label>
				<? if($dEntradaGeneral['turno'] == 1) echo "Matutino";?>
                <? if($dEntradaGeneral['turno'] == 2) echo "Vespertino";?>
                <? if($dEntradaGeneral['turno'] == 3) echo "Nocturno";?>
             <br />
			</p><br>
        <div align="center">
      <b>Impresi&oacute;n - Nave 2</b>
      <br>
      </div>
	
		<?  
		 	$sql_lic= 	"SELECT maquina.numero, operadores.nombre, subtotal, maquina.id_maquina, id_resumen_maquina_im, resumen_maquina_im.id_operador FROM resumen_maquina_im  ".
						"LEFT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".
						"LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador ". 
						"WHERE (id_impresion=".$dImpresion['id_impresion'].") AND linea_impresion = '0' ORDER BY maquina.numero";

			$res_lic=mysql_query($sql_lic);
			$cant_lic=mysql_num_rows($res_lic);
			$cant=ceil($cant_lic/3);
			$a=0;
			while($dResumenMaquinasImpr = mysql_fetch_assoc($res_lic))
			{
			
				$numero[$a]					= $dResumenMaquinasImpr['numero'];
				$id_maquina[$a] 			= $dResumenMaquinasImpr['id_maquina'];
				$nombre[$a]					= $dResumenMaquinasImpr['nombre'];
				$id_operador[$a]			= $dResumenMaquinasImpr['id_operador'];
				$id_resumen_maquina_impr[$a]	= $dResumenMaquinasImpr['id_resumen_maquina_im'];
				$subtotales[$a]				= $dResumenMaquinasImpr['subtotal'];
			

				$a++;
			}
			$reg=0;
			for($i=0;$i<$cant;$i++)
{
?>
<table width="85%" align="center" cellpadding="0" cellspacing="0">
<tr>
    <? for($x=1;$x<=4; $x++){?>
<td width="25%" align="center" valign="top"><br />
  <? if($reg<$cant_lic){ ?>		

	 
		<?
		
		    $qOperadorimpr = 	"SELECT oper_maquina.id_operador, nombre FROM oper_maquina ".
								"INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador ".
								"WHERE id_maquina = ".$id_maquina[$reg]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";
			$rOperadorimpr = mysql_query($qOperadorimpr);
			$dAsignacionimpr = mysql_fetch_assoc($rOperadorimpr);?>
              
        
     <h3 align="left" style="color: rgb(255, 255, 255); font-size:9px">FLEXO <?=$numero[$reg]?><br>
	Op.: <?=$nombre[$reg];?>
     
     
       </h3>
<table width="190" align="left" cellpadding="0" cellspacing="0" >
<thead>
					<tr>
						<td width="65" height="20"><strong>O. T.</strong></td>
					  <td width="102"><strong>Kilogramos</strong></td>
			    </tr>
				</thead>
				<tbody>
				<?php 
			$qDetalleResumenIM	=	"SELECT * FROM detalle_resumen_maquina_im WHERE (id_resumen_maquina_im = '".$id_resumen_maquina_impr[$reg]."')";
			$rDetalleResumenIM	=	mysql_query($qDetalleResumenIM) OR die("<p>$qDetalleResumenIM</p><p>".mysql_error()."</p>");
			for($a3 = 0;$dDetalleResumenIM	= 	mysql_fetch_assoc($rDetalleResumenIM); $a3++){					
		?>
					<tr <? if(bcmod($a3,2) == 0) echo "bgcolor='#EEEEEE'"; else echo "";?>>
                          <td height="21" align="left" class="style5" ><?=$dDetalleResumenIM['orden_trabajo']?></td>
                      <td align="left" colspan="3" class="style5"><?=$dDetalleResumenIM['kilogramos']?></td>
				  </tr>
				<?php } ?>
				<tr>
						<td height="22" ><strong>Subtotal</strong></td>
					<td colspan="3" class="style5"><b><?=$subtotales[$reg]?></b></td>
                 </tr>
				</tbody>
		  </table>
            
  <? $reg++;}?></td><? }?>
</tr>
</table>
  <? }?>
  </p> 

        <br>
        <br>
<br />
		    		<p align="center"><strong>Alta L&iacute;nea de Impresi&oacute;n - Nave 2</strong></p>	
                    
                   
         <p align="center">          
           
		<?  
		 	$sql_lic2= 	"SELECT * FROM resumen_maquina_im  ".
					 	"RIGHT JOIN maquina ON resumen_maquina_im.id_maquina = maquina.id_maquina ".
						"LEFT JOIN operadores ON resumen_maquina_im.id_operador = operadores.id_operador ".
						"WHERE id_impresion = ".$dImpresion['id_impresion']." AND linea_impresion = '1' ".
						"ORDER BY maquina.numero";
				$res_lic2=mysql_query($sql_lic2);
			$cant_lic2=mysql_num_rows($res_lic2);
			$cant2=ceil($cant_lic2/3);
			$a2=0;
			while($dResumenMaquinasLimpr = mysql_fetch_assoc($res_lic2))
			{
			
				$numero2[$a2]					= $dResumenMaquinasLimpr['numero'];
				$id_maquina2[$a2] 				= $dResumenMaquinasLimpr['id_maquina'];
				$nombre2[$a2]					= $dResumenMaquinasLimpr['nombre'];
				$id_operador2[$a2]				= $dResumenMaquinasLimpr['id_operador'];
				$id_resumen_maquina_limpr[$a2]	= $dResumenMaquinasLimpr['id_resumen_maquina_im'];
				$subtotales2[$a2]				= $dResumenMaquinasLimpr['subtotal'];

				$a2++;
			}
			$reg2=0;
			for($i2=0;$i2<$cant2;$i2++)
{
?>
<table width="85%" align="center" cellpadding="0" cellspacing="0	">
<tr>
    <? for($x2=1;$x2<=4; $x2++){?>
<td width="25%" align="left" valign="top"><br />
  <? if($reg2<$cant_lic2){ ?>		

	 
		<?
		
		    $qOperadorlimp2 = 	"SELECT oper_maquina.id_operador, nombre FROM oper_maquina ".
								"INNER JOIN operadores ON oper_maquina.id_operador = operadores.id_operador ".
								"WHERE id_maquina = ".$id_maquina2[$reg2]."  AND oper_maquina.rol = '".$_SESSION['rol']."'  ";
			$rOperadorlimp2 = mysql_query($qOperadorlimp2);
			$dAsignacionlimp2 = mysql_fetch_assoc($rOperadorlimp2);?>
            
		<h3 align="left" style="color: rgb(255, 255, 255); font-size:9px">Linea <?=$numero2[$reg2]?><br>
        Op.: <?=$nombre2[$reg2]?>
      
		
		 </h3>
<table width="206" height="71" align="left" cellpadding="0" cellspacing="0" >
<thead>
					<tr>
						<td width="69"><strong>O. T.</strong></td>
					  <td width="122"><strong>Kilogramos</strong></td>
			    </tr>
				</thead>
				<tbody>
				<?php 
				
				
			$qDetalleResumenLIM	=	"SELECT * FROM detalle_resumen_maquina_im WHERE (id_resumen_maquina_im = '".$id_resumen_maquina_limpr[$reg2]."')";
			$rDetalleResumenLIM	=	mysql_query($qDetalleResumenLIM) OR die("<p>$qDetalleResumenIM</p><p>".mysql_error()."</p>");
			for($a4 = 0;$dDetalleResumenLIM	= 	mysql_fetch_assoc($rDetalleResumenLIM); $a4++){	
				 ?>
					<tr <? if(bcmod($a4,2) == 0) echo "bgcolor='#EEEEEE'"; else echo "";?>>
                          <td align="left" class="style5" ><?=$dDetalleResumenLIM['orden_trabajo']?></td>
                          <td align="left" colspan="3" class="style5"><?=$dDetalleResumenLIM['kilogramos']?></td>
				  </tr>
				<?php } ?>
					<tr>
						<td ><strong>Subtotal</strong></td>
						<td colspan="3" class="style5"><b><?=$subtotales2[$reg2]?></b></td>
					</tr>
				</tbody>
		  </table>
            
  <? $reg2++;}?>
  <br /></td><? }?>
</tr>
</table>
  <? }?>
</p>
</p></tr>    

	


                 <br><br>
<div id="datosgenerales" style="background-color:#FFFFFF;">
        <table align="center">
        <tbody>
        			<tr>
						<td width="59"></td>
						<td width="167" align="center">TOTAL PRODUCCION HD.</td>
						<td width="174" align="center">TOTAL PRODUCCION BD.</td>
						<td width="140" align="center">DESPERDICIOS HD.</td>
						<td width="149" align="center">DESPERDICIOS BD.</td>
			  </tr>
					<tr>
						<td><strong>TOTALES:</strong></td>
						<td align="center" class="style5"><b><?=$dImpresion['total_hd']?></b></td>
						<td align="center" class="style5"><b><?=$dImpresion['total_bd']?></b></td>
						<td align="center" class="style5"><b><?=$dImpresion['desperdicio_hd']?></b></td>
                        <td align="center" class="style5"><b><?=$dImpresion['desperdicio_bd']?></b></td>
					</tr>
                    
           </tbody></table></div>
<br><bR>        		<p align="center"><strong><br />
   		    TIEMPOS MUERTOS</strong><br />
   		    <br />
    		</p>
            
            
<p align="center">          
           
		<?  
		 	$sql_lic= "SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dImpresion['id_impresion']."' AND tipo= 2 ORDER BY numero ASC";
			$res_lic=mysql_query($sql_lic);
			$cant_lic=mysql_num_rows($res_lic);
			$cant=ceil($cant_lic/3);
			$d=0;
			while($muertos = mysql_fetch_assoc($res_lic))
			{
				$codigo[$d]			=	$muertos['numero'];
				$id[$d] 			= 	$muertos['id_maquina'];
				$falta[$d]			=	$muertos['falta_personal'];
				$mantenimiento[$d]	=	$muertos['mantenimiento'];
				$mallas[$d]			=	$muertos['mallas'];
				$fallo[$d]			=	$muertos['fallo_electrico'];
				$observaciones[$d]	=	$muertos['observaciones'];
				$cambio[$d]			=	$muertos['cambio_impresion'];
				$d++;
			}
			$reg=0;
			for($i=0;$i<$cant;$i++)
{
?>
<table width="85%" align="center" cellpadding="0" cellspacing="0">
<tr>
    <? for($x=1;$x<=4; $x++){?>
<td width="25%" align="left" valign="top"><br />
  <? if($reg<$cant_lic){ ?>			             
        <h3 align="left" style="color: rgb(255, 255, 255);">FLEXO <?=$codigo[$reg]?></h3>
<div id="impresion<?=$codigo[$reg]?>" >
<table cellpadding="0" cellspacing="0">
 <? if( $observaciones[$reg] != "" || $falta[$reg] != "00:00:00" || $fallo[$reg] != "00:00:00" || $mantenimiento[$reg] != "00:00:00" || $cambio[$reg] == 1 ){ ?>
					<? if($observaciones[$reg] != "") {?>
					<tr></tr>
						<td><strong>Observaciones</strong></td>
						<td colspan="3"><textarea  class="observaciones"  cols="12" rows="3"><?=$observaciones[$reg]?></textarea></td>
                    </tr>
					<? } if($cambio[$reg] == 1 ){?>                    
                    <tr>
           	  			<td align="left" class="style7">Cambio de impresi&oacute;n:</td>
                        <td width="163">SI</td>
				  </tr>
                 	<? } if($falta[$reg] == 1 ){?>
                   <tr>
                        <td align="left" class="style7">Falta de Personal:</td>
                        <td width="185">SI</td>
                    </tr>
                    <? } if($fallo[$reg] != "00:00:00" ){?>
                    <tr>
                        <td width="103" align="left" class="style7">Fallo E. Electrica:</td>
                    				<? $tiempo2 	= explode(":" ,$fallo[$reg]);  $horas	=	$tiempo2[0]; $minutos =	$tiempo2[1];?>
                        <td width="163"><?=$horas?>:<?=$minutos?> Hrs.</td>
            		</tr>
                    <? } if($mantenimiento[$reg] != "00:00:00" ){?>
             		<tr>
           	  			<td align="left" class="style7">Mantenimiento:</td>
                    				<? $tiempo3 	= explode(":" ,$mantenimiento[$reg2]);  $horas2	=	$tiempo3[0]; $minutos2 =	$tiempo3[1];?>
                		<td width="185"><?=$horas2?>:<?=$minutos2?> Hrs.</td>
                    </tr>
                    <? } ?>
<? } else { ?>
<tr>
	<td colspan="2" >Sin Observaciones</td>
</tr>
<? } ?>
</table>
          </div>
            
  <? $reg++;}?></td><? }?>
</tr>
</table>
  <? }?>
  </p>    

        <br />
                  <p align="center">LINEA DE IMPRESION</p>
                  <br> 
         <p align="center">          
           
		<?  
		 	$sql_lic2	= 	"SELECT * FROM tiempos_muertos INNER JOIN maquina ON tiempos_muertos.id_maquina = maquina.id_maquina  WHERE id_produccion	= '".$dImpresion['id_impresion']."' AND tipo= 3 ";
			$res_lic2	=	mysql_query($sql_lic2);
			$cant_lic2	=	mysql_num_rows($res_lic2);
			$cant2=ceil($cant_lic2/3);
			$c=0;
			while($muertos2 = mysql_fetch_assoc($res_lic2))
			{
				$codigo2[$c]		=	$muertos2['numero'];
				$id2[$c] 			= 	$muertos2['id_maquina'];
				$falta2[$c]			=	$muertos2['falta_personal'];
				$mantenimiento2[$c]	=	$muertos2['mantenimiento'];
				$mallas2[$c]		=	$muertos2['mallas'];
				$fallo2[$c]			=	$muertos2['fallo_electrico'];
				$observaciones2[$c]	=	$muertos2['observaciones'];
				$cambio2[$c]		=	$muertos2['cambio_impresion'];
				$c++;
			}
			$reg2=0;
			for($i2=0;$i2<$cant2;$i2++)
{
?>
<table width="85%" align="center" cellpadding="0" cellspacing="0">
<tr>
    <? for($x2=1;$x2<=4; $x2++){?>
    
<td width="25%" align="left" valign="top"><br />
  <? if($reg2<$cant_lic2){ ?>			             
		<h3 align="left" style="color: rgb(255, 255, 255);">LINEA <?=$codigo2[$reg2]?><br />
		</h3>
<div id="linea<?=$codigo2[$reg2]?>">
<table width="190" cellpadding="0" cellspacing="0">
<thead>
<? if( $observaciones2[$reg2] != "" || $falta2[$reg2] != "00:00:00" || $fallo2[$reg2] != "00:00:00" || $mantenimiento2[$reg2] != "00:00:00" || $cambio2[$reg2] == 1 ){ ?>
					<? if($observaciones2[$reg2] != "") {?>
					<tr>
						<td><strong>Observaciones</strong></td>
						<td colspan="3"><textarea  class="observaciones" name="ob_limpr<?=$codigo2[$reg2]?>" id="ob_limpr<?=$id2[$reg2]?>" cols="12" rows="3" ><?=$observaciones2[$reg2]?></textarea></td>
                    </tr>
					<? } if($cambio2[$reg2] == 1 ){?>                    
                    <tr>
           	  			<td align="left" class="style7">Cambio de impresi&oacute;n:</td>
                        <td width="163">SI</td>
				  </tr>
                 	<? } if($falta2[$reg2] == 1 ){?>
                   <tr>
                        <td align="left" class="style7">Falta de Personal:</td>
                        <td width="185">SI</td>
                    </tr>
                    <? } if($fallo2[$reg2] != "00:00:00" ){?>
                    <tr>
                        <td width="103" align="left" class="style7">Fallo E. Electrica:</td>
                    				<? $tiempo4 	= explode(":" ,$fallo2[$reg2]);  $horas4	=	$tiempo4[0]; $minutos4 =	$tiempo4[1];?>
                        <td width="163"><?=$horas4?>:<?=$minutos4?> Hrs.</td>
            		</tr>
                    <? } if($mantenimiento2[$reg2] != "00:00:00" ){?>
             		<tr>
           	  			<td align="left" class="style7">Mantenimiento:</td>
                    				<? $tiempo5 	= explode(":" ,$mantenimiento2[$reg2]);  $horas5	=	$tiempo5[0]; $minutos5 =	$tiempo5[1];?>
                		<td width="185"><?=$horas5?>:<?=$minutos5?> Hrs.</td>
                    </tr>
                    <? } ?>
<? } else { ?>
<tr>
	<td colspan="2" >Sin Observaciones</td>
</tr>
<? } ?>
				</thead>
		  </table>
  </div>          
  <? $reg2++;}?>
  <br /></td><? }?>
</tr>
</table>
  <? }?>
</p>
</p> 
        
        
        <br><br>
        
        
        <div class="datosgenerales">
                    <tr>      
                      <td  align="right" colspan="7"><br>
						<input type="button" name="regresar" id="regresar" value="Regresar" onClick="javascript: history.go(-1);">
                   	   <input type="button" name="imprimir" id="imprimir" value="Imprimir" onClick="javascript: window.print(); ">
                      </td>
					</tr>
			</tbody>
        </table>
        </div>          <br><br>
        
    </div>
    
    
    </div>
 </div>
</form>

<? } if($listar) { ?>
<body>
<form id="form" action="admin.php?seccion=6" method="post">
<div id="container">
		<div id="content">
<? if( $_SESSION['area'] == 1){ ?>
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES EXTRUDER</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
              <table>
                <tr>
                  <td width="124" style="color:#000000"><b>Fecha</b></td>
                  <td width="279" style="color:#000000"><b>Nombre</b></td>
                  <td width="39" style="color:#000000"><b>Turno</b></td>
                </tr>
                <?php
				$qGenerales		=	"SELECT fecha, supervisor.nombre, turno,id_entrada_general FROM entrada_general".
									" INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor WHERE autorizada = '1' AND entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."'  AND impresion = '0' ORDER BY fecha, turno ASC";
				$rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");
				$sGenerales		=	array();
				while($dGenerales	=	mysql_fetch_assoc($rGenerales)){ ?>
                <tr onMouseOver="this.style.backgroundColor='#CCC'"
                    onMouseOut="this.style.backgroundColor='#EAEAEA'" 
                    style="cursor:default; background-color:#EAEAEA">
                  <td><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dGenerales['fecha'])?></td>
                  <td><?=$dGenerales['nombre']?></td>
                  <td><?=$dGenerales['turno']?></td>
                  <td width="81"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=extruder&id_entrada_general=<?=$dGenerales['id_entrada_general']?>">VER</a></td>
                </tr>
                <?php } ?>
              </table>
			<br>
            </div>

<? }		  if( $_SESSION['area2'] == 1 ){ ?>
		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#extruder">REPORTES IMPRESION</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
        <table>
               <tr>
                        <td width="124" style="color:#000000"><b>Fecha</b></td>
                      <td width="279" style="color:#000000"><b>Nombre</b></td>
                      <td width="39" style="color:#000000"><b>Turno</b></td>
               </tr>
                  
                <?php
				$qGenerales2		=	"SELECT fecha, supervisor.nombre, turno, entrada_general.id_entrada_general FROM entrada_general ".
									"LEFT JOIN impresion  ON entrada_general.id_entrada_general = impresion.id_entrada_general ".
									"LEFT JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor ".
									"WHERE entrada_general.id_supervisor = '".$_SESSION['id_supervisor']."' AND autorizada = '1' AND impresion = '1' ORDER BY fecha ASC";
									
									
				$rGenerales2		=	mysql_query($qGenerales2) OR die("<p>$qGenerales2</p><p>".mysql_error()."</p>");
				$sGenerales2		=	array();
				while($dGenerales2	=	mysql_fetch_assoc($rGenerales2)){ ?>
                 <tr onMouseOver="this.style.backgroundColor='#CCC'"
                    onmouseout="this.style.backgroundColor='#EAEAEA'" 
                    style="cursor:default; background-color:#EAEAEA">                              
               		<td><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dGenerales2['fecha'])?></td>
                    <td><?=$dGenerales2['nombre']?></td>
                    <td><?=$dGenerales2['turno']?></td>
                    <td width="81"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=impresion&id_entrada_general=<?=$dGenerales2['id_entrada_general']?>">VER</a></td>
          		</tr>				
				<?php } ?>
                <br />
              </table><br>
		</div>
       
<? } if($_SESSION['area3'] == 1){ ?>

		<h3 class="toggler introduction"><a style="color: rgb(255, 255, 255);" href="#bolseo">REPORTES BOLSEO</a></h3>
            <div style="background-color:#FFFFFF;" class="navcontainer">
          <table>
        		<tr>
            		<td colspan="5">&nbsp;</td>
            </tr>
                <tr>
                	<td width="128" style="color:#000000"><b>Fecha</b></td>
               	  <td width="270" style="color:#000000"><b>Nombre</b></td>
               	  <td width="44" style="color:#000000"><b>Turno</b></td>
			  </tr>
 		        <?php
				$qBolseo		=	"SELECT fecha, supervisor.nombre, turno,id_bolseo FROM bolseo".
									" LEFT JOIN supervisor ON bolseo.id_supervisor = supervisor.id_supervisor  WHERE bolseo.id_supervisor = '".$_SESSION['id_supervisor']."' AND autorizada = '1'  ORDER BY fecha ASC";
				$rBolseo		=	mysql_query($qBolseo) OR die("<p>$qBolseo</p><p>".mysql_error()."</p>");
				$sBolseo		=	array();
				while($dBolseo	=	mysql_fetch_assoc($rBolseo)){ ?>
                <tr onMouseOver="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#EAEAEA'" 
	style="cursor:default; background-color:#EAEAEA">
                	<td><?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3 / \\2 / \\1" ,$dBolseo['fecha'])?></td>
                    <td><?=$dBolseo['nombre']?></td>
                    <td><?=$dBolseo['turno']?></td>
                    <td width="81"><a href="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=bolseo&bolseo&id_bolseo=<?=$dBolseo['id_bolseo']?>">VER</a></td>
          </tr>
                <?php } ?>               
                <br />
				</table><br>
            </div>
 <? } ?>
     
	</div>
	
</div>
</form>

<? } ?>
