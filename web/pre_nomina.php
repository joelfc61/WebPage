<?php
$_POST['semana']	=	intval($_POST['semana']);
$_REQUEST['semana']	=	intval($_REQUEST['semana']);

include "debugger.php";	




if(!empty($_POST['autoriza']))
{

	$qPre	=	"SELECT * FROM operadores WHERE area = ".$_REQUEST['area']." ORDER BY id_operador";
	$rPre	=	mysql_query($qPre);
		while($dPre	=	mysql_fetch_assoc($rPre)){

		   	$query			=	"UPDATE pre_nomina_calificada SET autorizada = 1 WHERE (semana=".$_POST['semana'].") AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND (id_operador=".$dPre['id_operador'].") ";
			$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	   }

	
	 if(isset($_SESSION['id_admin']) ){ 
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=listar\';</script>';
	} else {
	echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion='.$_REQUEST['seccion'].'&accion=listar\';</script>';
	}   
}

if(!empty($_POST['pre_autoriza']))
{

	$qPre	=	"SELECT * FROM operadores WHERE area = ".$_REQUEST['area']." ORDER BY id_operador";
	$rPre	=	mysql_query($qPre);
		while($dPre	=	mysql_fetch_assoc($rPre)){

		   	$query			=	"UPDATE pre_nomina_calificada SET pre_autorizada = 1 WHERE (semana=".$_POST['semana'].") AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND (id_operador=".$dPre['id_operador'].") ";
			$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	   }

	
	 if(isset($_SESSION['id_admin']) ){ 
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=listar\';</script>';
	} else {
	echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion='.$_REQUEST['seccion'].'&accion=listar\';</script>';
	}   
}

if(!empty($_POST['no_pre_autoriza']))
{

	$qPre	=	"SELECT * FROM operadores WHERE area = ".$_REQUEST['area']." ORDER BY id_operador";
	$rPre	=	mysql_query($qPre);
		while($dPre	=	mysql_fetch_assoc($rPre)){

		   	$query			=	"UPDATE pre_nomina_calificada SET pre_autorizada = 0 WHERE (semana=".$_POST['semana'].") AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND (id_operador=".$dPre['id_operador'].") ";
			$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	   }

	
	 if(isset($_SESSION['id_admin']) ){ 
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=listar\';</script>';
	} else {
	echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion='.$_REQUEST['seccion'].'&accion=listar\';</script>';
	}   
}

if(!empty($_POST['no_autoriza']))
{

	$qPre	=	"SELECT * FROM operadores WHERE area = ".$_REQUEST['area']." ORDER BY id_operador";
	$rPre	=	mysql_query($qPre);
		while($dPre	=	mysql_fetch_assoc($rPre)){

		   	$query			=	"UPDATE pre_nomina_calificada SET autorizada = 0 WHERE (semana=".$_POST['semana'].") AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND (id_operador=".$dPre['id_operador'].") AND autorizada = 1 ";
			$res_query		=	mysql_query($query) OR die("<p>$query</p><p>".mysql_error()."</p>");
	   }

	
	 if(isset($_SESSION['id_admin']) ){ 
	echo '<script laguaje="javascript">location.href=\'admin.php?seccion='.$_REQUEST['seccion'].'&accion=listar\';</script>';
	} else {
	echo '<script laguaje="javascript">location.href=\'admin_supervisor.php?seccion='.$_REQUEST['seccion'].'&accion=listar\';</script>';
	}   
}

if(!empty($_GET['accion']))
{
	$nuevo		= ($_GET['accion']=="nuevo"	)?true:false;
	$busqueda		= ($_GET['accion']=="busqueda"	)?true:false;
	$listar		= ($_GET['accion']=="listar")?true:false;
	$supervisor	= ($_GET['accion']=="supervisor")?true:false;
	$listarNomina	= ($_GET['accion']=="listarNomina")?true:false;
	
	
	if(!empty($_GET['id_util']) && is_numeric($_GET['id_util']) )
	{
		$mostrar	= ($_GET['accion']=="mostrar"	)?true:false;
		$modificar	= ($_GET['accion']=="modificar"	)?true:false;
		$eliminar	= ($_GET['accion']=="eliminar" && valida_root())?true:false;
		$traduccion	= ($_GET['accion']=="traduccion")?true:false;
	}

}
if($nuevo)
{
	$nEntradas	=	4;
	
}

?>

<?php  if($listarNomina){ ?>

<script type="text/javascript" src="js/isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
	last = valor;
}
function Blur(elemento, valor, campo, id, tabla, tipo) {
	if (last != valor)
		myajax.Link('actualiza.php?valor='+valor+'&campo='+campo+'&id='+id+'&tabla='+tabla+'&tipo='+tipo);
}

</script>

<script type="text/javascript" language="javascript">
function genera()
{
document.califica.action="reportes_pdf_nomina.php?tipo=prenomina&area=<?=$_REQUEST['area']?>&semana=<?=$_REQUEST['semana']?>&anio_nom_as=<?=$_REQUEST['anio_nom_as']?>";
document.califica.submit();
document.califica.action="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listar";
}
</script>

<body onLoad="myajax = new isiAJAX();">
<div id="content">
	<div id="datosgenerales" style="background-color:#FFFFFF;">		
    	<div class="tablaCentrada">
          <blockquote>
                <?php 
                

		
				if(isset($_SESSION['id_admin'])){		
					$qAutorizar	=	"SELECT * FROM pre_nomina_calificada INNER JOIN operadores ON pre_nomina_calificada.id_operador = operadores.id_operador WHERE semana = '".$_REQUEST['semana']."' AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND operadores.area = ".$_REQUEST['area']." AND activo = 0  ORDER BY numnomina ASC ";
				}  
				else if(!isset($_SESSION['id_admin'])){		
					$qAutorizar	=	"SELECT * FROM pre_nomina_calificada INNER JOIN operadores ON pre_nomina_calificada.id_operador = operadores.id_operador WHERE semana = '".$_REQUEST['semana']."' AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' )  AND operadores.area = ".$_REQUEST['area']." AND rol = ".$_SESSION['rol']." AND activo = 0 ORDER BY numnomina ASC";
				}	
				pDebug($qAutorizar);
				$rAutorizar	=	mysql_query($qAutorizar);
				$dAutorizar	=	mysql_fetch_assoc($rAutorizar);
		  
		  //$qDelAl	=	"SELECT DAY(desde), DAY(hasta), MONTH(desde), MONTH(hasta), desde, hasta FROM pre_nomina_calificada WHERE semana = ".$_REQUEST['semana']." AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND   GROUP BY semana";
		  $qDelAl	=	"SELECT DAY(desde), DAY(hasta), MONTH(desde), MONTH(hasta), desde, hasta FROM pre_nomina_calificada WHERE semana = ".$_REQUEST['semana']." AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) ".
		  				"AND MONTH(desde) = (SELECT MONTH(desde) FROM pre_nomina_calificada WHERE semana = ".$_REQUEST['semana']." AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) GROUP BY semana ORDER BY desde DESC ) GROUP BY semana";
		
		  pDebug($qDelAl);
		  $rDelAl	=	mysql_query($qDelAl);
		  $dDelAl	=	mysql_fetch_row($rDelAl);
		  
		  $desdeFec	=	$dDelAl[4];
		  $hastaFec	=	$dDelAl[5];
		  
		  if( ( $dAutorizar['autorizada'] == 0  && $dAutorizar['pre_autorizada'] == 0 ) || ($prenominaAutoriza && $dAutorizar['autorizada'] == 0 ) ){?>
             <form name="califica" action="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listar"  method="post">
             <input type="hidden" name="anio_nom_as" value="<?=$_REQUEST['anio_nom_as']?>">

              <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
             	<? if($dAutorizar['pre_autorizada'] == 1 && $dAutorizar['autorizada'] == 0  ){ ?>
                <tr>
                	<td colspan="20" style="color:#FF0000" align="center">PRENOMINA PRE-AUTORIZADA</td>
                </tr>
                <? } ?>
                <tr>
                  <td colspan="2" align="left" class="style5">Semana No..
                  <?=$_REQUEST['semana']?>
                  <input type="hidden" name="anio_nom_as" value="<?=$_REQUEST['anio_nom_as']?>">
                  <input type="hidden" name="area" value="<?=$_REQUEST['area']?>">
                  <input type="hidden" name="semana" value="<?=$_REQUEST['semana']?>"></td>
                  <td align="center" class="pre">Asistencia</td>
                  <td width="12">&nbsp;</td>
                  <td  align="center"  class="pre">Retardos</td>
                  <td width="11">&nbsp;</td>
                  <td width="65" align="center"  class="pre">Extras</td>
                  <td width="29" align="center"  class="pre">P.D.</td>
                  <td width="22" align="center"  class="pre">A.</td>
                  <td width="22" align="center"  class="pre">P.</td>
                  <td width="21" align="center"  class="pre">Pr.</td>
                  <td width="113" align="center" class="pre">Causas  perdida</td>
                  <td width="107" align="center" class="pre">Observaciones</td>
            </tr>

                <tr>
                  <td colspan="2" class="style5">Del 
                    <?=$dDelAl['0']?>
                    <? if($dDelAl[2] != $dDelAl[3]) echo " de ".$mes[$dDelAl[2]];?> 
                    al 
                    <?=$dDelAl['1']?> 
                    
                  <? if($dDelAl[2] != $dDelAl[3]) echo " de ".$mes[$dDelAl[3]]; else echo " de ".$mes[$dDelAl[intval(2)]]?> del <?=$_REQUEST['anio_nom_as']?> </td>
          	  <td width="71" align="left"><table cellpadding="0" cellspacing="0" align="left"><tr><? 
			 $dias = array("J","V","S","D","L","M","Mi");
			 for($z = 0; $z < 7; $z++){?><td><input type="text" name="" class="inputoff2"  readonly size="1" value="<?=$dias[$z]?>" style="font-size:10px; color:#000000; font-weight:bold; text-align:center"></td><? } ?></tr></table></td>
           	  <td width="12" align="left">&nbsp;</td>
              <td align="left"><table cellpadding="0" cellspacing="0" align="left"><tr>
                <? for($z = 0; $z < 7; $z++){?><td><input type="text" name="" class="inputoff2"  readonly size="1" value="<?=$dias[$z]?>" style="font-size:10px; color:#000000; font-weight:bold; text-align:center"></td><? } ?></tr></table></td>
           	  <td width="11" align="left">&nbsp;</td>
              <td align="left"><table cellpadding="0" cellspacing="0" align="left"><tr>
                <? for($z = 0; $z < 7; $z++){?><td><input type="text" name=""  readonly size="1" value="<?=$dias[$z]?>" style="font-size:10px; color:#000000; font-weight:bold; text-align:center"></td><? } ?></tr></table></td>
          	  <td colspan="5" ></td>
		  </tr>
                <tr>
                  <td width="55"  class="pre" align="center">Codigo</td>
              <td width="300" align="left"  class="pre">Nombre</td>
           	  <td width="71" align="left"><table cellpadding="0" cellspacing="0" align="left"><tr><?

					$semana	=	$_REQUEST['semana'];
				    $qNumDia	=	"SELECT DAY(fecha),MONTH(fecha), YEAR(fecha) FROM asistencias ".
						//	" WHERE semana = '".$_REQUEST['semana']."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'   ORDER BY fecha ASC";
													" WHERE semana = '".$_REQUEST['semana']."' AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'   ORDER BY fecha ASC";
					pDebug($qNumDia);
					$rNumDia	=	mysql_query($qNumDia);
								 
					$dNumDia	=	mysql_fetch_row($rNumDia);	
								
					$u_dia	= UltimoDia($dNumDia[2], $dNumDia[1]);
													
					$dNumDia[0];
			 for($z = 0; $z < 7; $z++){?><td><input type="text" name=""  class="inputoff2" readonly size="1" value="<? $el_dia	=	$dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} echo $el_dia;?>" style="font-size:10px; color:#000000; font-weight:bold;text-align:center"></td><? } ?></tr></table>            </td>
           	  <td width="12" align="left">&nbsp;</td>
           	  <td align="left" width="73">
  <table cellpadding="0" cellspacing="0" align="left">
    <tr><? for($z = 0; $z < 7; $z++){?>
      <td><input type="text" name="" class="inputoff2" readonly size="1" value="<? $el_dia	= $dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} echo $el_dia;?>" style="font-size:10px; color:#000000; font-weight:bold; text-align:center"></td>
					  <? } ?>
      </tr>
    </table>            </td>
           	  <td width="11" align="left">&nbsp;</td>
  <td align="left">
    <table cellpadding="0" cellspacing="0" align="left">
      <tr><? for($z = 0; $z < 7; $z++){?>
        <td><input type="text" name="" class="inputoff2" readonly size="1" value="<? $el_dia = $dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} echo $el_dia;?>" style="font-size:10px; color:#000000; font-weight:bold; text-align:center"></td>
					  <? } ?>
        </tr>
      </table>            </td>
         	  <td></td>
          	  <td align="center">%</td>
          	  <td align="center">%</td>
          	  <td align="center">%</td>
         </tr>
                <? 
					if(isset($_SESSION['id_admin'])){		
					 	$qAsistencias	=	"SELECT * FROM pre_nomina_calificada INNER JOIN operadores ON pre_nomina_calificada.id_operador = operadores.id_operador".
										" WHERE semana = '".$_REQUEST['semana']."' AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND operadores.area = ".$_REQUEST['area']." AND activo = 0 AND pre_nomina_calificada.desde = '".$desdeFec."' AND pre_nomina_calificada.hasta = '".$hastaFec."'  ORDER BY numnomina ASC";
					}  else if(!isset($_SESSION['id_admin'])){		
					 	$qAsistencias	=	"SELECT * FROM pre_nomina_calificada INNER JOIN operadores ON pre_nomina_calificada.id_operador = operadores.id_operador".
										" WHERE semana = '".$_REQUEST['semana']."' AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND operadores.area = ".$_REQUEST['area']." AND rol = ".$_SESSION['rol']." AND activo = 0 AND pre_nomina_calificada.desde = '".$desdeFec."' AND pre_nomina_calificada.hasta = '".$hastaFec."'  ORDER BY numnomina ASC";
					}
					
					$rAsistencias	=	mysql_query($qAsistencias);
					$nAsistencias	=	mysql_num_rows($rAsistencias);
					
					if($nAsistencias > 0){
					for( $k = 0;  $dAsistencias	=	mysql_fetch_assoc($rAsistencias); $k++){ 
						
					 	$qFaltas	=	"SELECT extra, retardo, asistencia, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC";
					// 	$qFaltas	=	"SELECT extra, retardo, asistencia, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC";
//						$qFaltas	=	"SELECT extra, retardo, asistencia, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']."   ORDER BY fecha ASC";
						$rFaltas	=	mysql_query($qFaltas);
						
						for($c = 0; $dFaltas = mysql_fetch_assoc($rFaltas); $c++){
						$id[$c]			=	array($dFaltas['id_asistencia']);
						$faltas[$c]  	=	array($dFaltas['asistencia']);
						$retardos[$c]  	=	array($dFaltas['retardo']);
						$extras[$c]  	=	array($dFaltas['extra']);
						}
					
					 ?>
                <tr <? if(bcmod(intval($k),2) == 0){ 
								$back 	= "#DDDDDD";  
								$frente = "#B4C3EC";
								echo "bgcolor='".$back."'";
								}  else { 
								$back 	= "#FFFFFF";  
								$frente = "#B4C3EC";
								echo "bgcolor='".$back."'";
								
								}?> onMouseOver="this.bgColor='<?=$frente?>'" onMouseOut="this.bgColor='<?=$back?>'">
                  <td style="font-size:10px" align="left" height="25" class="style5"><?=$dAsistencias['numnomina']?></td>
                <td align="left" class="style5" style="font-size:10px"><?=$dAsistencias['nombre']?></td>
                <td >
                  <table  style="border-color:#FFFFFF" cellpadding="0" cellspacing="0">
                    <tr>
                      <? for($d = 0 ; $d < 7; $d++) { $numeros = array("a","b","c","d","e","f","g");?>
                      <td><input type="text"  onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'asistencia', <?=$id[$d][0];?>,'asistencias','id_asistencia'); colorear('<?=$id[$d][0];?>','<?=$numeros[$d]?>')" id="<? $numeros = array("a","b","c","d","e","f","g"); echo $numeros[$d];?><?=$id[$d][0];?>" value="<? if($faltas[$d][0] == '' ||$faltas[$d][0]== '0') echo ""; else echo $faltas[$d][0];  ?>"   size="1" 
                    	style="font-size:10px; <? 	if($faltas[$d][0] == 'F' || $faltas[$d][0] == 'f') echo "color:#FF0000;"; 
													if($faltas[$d][0] == 'I' || $faltas[$d][0] == 'i' ) echo "color:#0000FF;"; 
													if($faltas[$d][0] == 'FJ' || $faltas[$d][0] == 'fj') echo "color:#008800;";
													if($faltas[$d][0] == 'V' || $faltas[$d][0] == 'v') echo "color:#FF00AA;";
													if($faltas[$d][0] == 'B' || $faltas[$d][0] == 'b' || $faltas[$d][0] == 'A' || $faltas[$d][0] == 'a' || $faltas[$d][0] == 'J' || $faltas[$d][0] == 'j' || $faltas[$d][0] == 'Baja' || $faltas[$d][0] == 'BAJA' || $faltas[$d][0] == 'baja') echo "color:#884400;";
													
													?> font-weight:bold; text-align:center"></td><? }  ?>
                      </tr>
                  </table>            </td>	
              <td width="12">&nbsp;</td>
            <td align="left" style="font-size:9px">              	
              <table cellpadding="0" cellspacing="0" style="border-color:#FFFFFF">
                <tr>
                  <? for($d = 0, $y = 0 ; $d < 7; $d++){?><td><input  <? if(isset($_SESSION['id_admin']) && $prenomina_m) echo ""; else echo "readonly "; ?>  type="text" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'retardo', <?=$id[$d][$y];?>,'asistencias','id_asistencia');" id="<? $numeros = array("a","b","c","d","e","f","g"); echo $numeros[$z];?><?=$id[$d][$y];?>" size="1" value="<? if($retardos[$d][$y] == '' || $retardos[$d][$y] == '0') echo ""; else echo $retardos[$d][$y];?>" style="font-size:10px; color:#FF0000;font-weight:bold; text-align:center"></td><? } ?>
                  </tr>
                </table>              </td>
           	  <td width="11" align="left">&nbsp;</td>
      <td align="left" style="font-size:9px">              	
        <table cellpadding="0" cellspacing="0" style="border-color:#FFFFFF">
          <tr>
            <? for($d = 0, $x =0 ; $d < 7; $d++){?><td><input type="text"  onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'extra', <?=$id[$d][$x];?>,'asistencias','id_asistencia');" id="<? $numeros = array("a","b","c","d","e","f","g"); echo $numeros[$z];?><?=$id[$d][$x];?>"  size="1" value="<? if($extras[$d][$x] == '' || $extras[$d][$x] == '0') echo ""; else echo $extras[$d][$x];?>" style="font-size:10px; color:#0000CC;font-weight:bold;  text-align:center"></td><? } ?>
            </tr>
          </table></td>
              <td align="center"><? 
				$qPrima	=	"SELECT * FROM asistencias WHERE dia = 'D' AND asistencia IN ('1','2','3','m')  AND semana = ".$semana." AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']." AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'   ";
//echo $qPrima;
				$rPrima	=	mysql_query($qPrima);
				$nPrima	=	mysql_num_rows($rPrima);
					if($nPrima > 0)
					 echo "SI";
			?></td>
              <td align="center" style="font-size:9px"><input <? if(isset($_SESSION['id_admin']) && $prenomina_m) echo ""; else echo "readonly";?> onFocus="Focus(this.id, this.value)" onBlur="nummaximo('<?=$dAsistencias['id_pre_nomina'];?>'); Blur(this.id, this.value, 'por_asist', <?=$dAsistencias['id_pre_nomina'];?>,'pre_nomina_calificada','id_pre_nomina')" 		id="h<?=$dAsistencias['id_pre_nomina'];?>" type="text" name="asist" 		size="2"  maxlength="2"  value="<? if($dAsistencias['por_asist'] == '' ) echo "10"; else echo $dAsistencias['por_asist'];?>" style="font-size:10px; text-align:center"></td>
              <td align="center" style="font-size:9px"><input <? if(isset($_SESSION['id_admin']) && $prenomina_m) echo ""; else echo "readonly";?> onFocus="Focus(this.id, this.value)" onBlur="nummaximo('<?=$dAsistencias['id_pre_nomina'];?>'); Blur(this.id, this.value, 'punt', <?=$dAsistencias['id_pre_nomina'];?>,'pre_nomina_calificada','id_pre_nomina')" 			id="i<?=$dAsistencias['id_pre_nomina'];?>" type="text" name="punt" 			size="2"  maxlength="2"  value="<? if($dAsistencias['punt'] == '') echo "5"; else echo $dAsistencias['punt'];?>" style="font-size:10px; text-align:center "></td>
              <td align="center" style="font-size:9px"><input onFocus="Focus(this.id, this.value)" onBlur="nummaximo('<?=$dAsistencias['id_pre_nomina'];?>'); Blur(this.id, this.value, 'prod', <?=$dAsistencias['id_pre_nomina'];?>,'pre_nomina_calificada','id_pre_nomina')" 			id="j<?=$dAsistencias['id_pre_nomina'];?>" type="text" name="prod" 			size="2"  maxlength="2"  value="<? if($dAsistencias['prod'] == '') echo "10";  else echo $dAsistencias['prod'];?>" style="font-size:10px; text-align:center "></td>
              <td align="center" style="font-size:9px"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'causas', <?=$dAsistencias['id_pre_nomina'];?>,'pre_nomina_calificada','id_pre_nomina')" 		id="k<?=$dAsistencias['id_pre_nomina'];?>" type="text" name="causas" 			size="15" value="<? if($dAsistencias['causas'] == '' || $dAsistencias['causas'] == '0') echo ""; else echo $dAsistencias['causas'];?>" style="font-size:10px"></td>
              <td align="center" style="font-size:9px"><input <? if(isset($_SESSION['id_admin']) && $prenomina_m) echo ""; else echo "readonly";?> onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'observaciones', <?=$dAsistencias['id_pre_nomina'];?>,'pre_nomina_calificada','id_pre_nomina')" id="l<?=$dAsistencias['id_pre_nomina'];?>" type="text" name="observaciones" 	size="15" value="<? if($dAsistencias['observaciones'] == '' || $dAsistencias['observaciones'] == '0') echo ""; else echo $dAsistencias['observaciones'];?>" style="font-size:10px; "></td>
          </tr>
 
                <? } 
			
			} else { ?>
                <tr>
                  <td colspan="18" style="color:#FF0000" align="center"><br>
  <br>
                    NO HAY EMPLEADOS DADOS DE ALTA EN EL SISTEMA EN ESTA AREA<br>
  <br></td>
              </tr>
                <? }?>
        
          
         <tr>
         	<td></td>
            <td colspan="12" align="justify" class="style5"><br>
<br>
<p>Claves:</p>
<p align="justify">1,2,3 son Turnos, M = Turno Mixto, I = incapacidad&nbsp;&nbsp;&nbsp;&nbsp;C = castigo&nbsp;&nbsp;&nbsp;&nbsp;
              FS = Festivo&nbsp;&nbsp;&nbsp;&nbsp;
              Ps = Permiso sin sueldo</p>
              <p>V = Vacaciones&nbsp;&nbsp;&nbsp;&nbsp;
                D = Descanso&nbsp;&nbsp;&nbsp;&nbsp;
                F = Falta injustificada.&nbsp;&nbsp;&nbsp;&nbsp;
                FJ = Falta Justificada.  R = Repone d&iacute;a, Txt = Tiempo por Tiempo 
                </p>
                <p>
                NCE = no checo entrada.&nbsp;&nbsp;&nbsp;&nbsp;
                NSC = No checo salida.&nbsp;&nbsp;&nbsp;&nbsp;
                Alim. Alimentos: retados o checadas. <br>
                <br>
                </p>
              </td>
         </tr>
                <tr>
                  <td colspan="25" align="center" ><br>
  <br>
  <input type="button" name="cambios" value="Movimiento" onClick="javascript: location.href='<?=$_SERVER['PHP_SELF']?>?seccion=37&accion=nuevo&semana=<?=$_REQUEST['semana']?>'">&nbsp;&nbsp;<input type="submit" value="Aceptar" />
  &nbsp;
  <? if($prenominaAutoriza) {	?>  
  <input type="submit" name="autoriza" value="Autorizar" onClick="javascript: return confirm('Usted está a punto de autorizar la Pre-nomina,\nestá seguro de continuar ??');"> 
  <? } if($prenominaPre_Autoriza && $dAutorizar['pre_autorizada'] == 0 ) {	?>  
  <input type="submit" name="pre_autoriza" value="Pre Autorizar" onClick="javascript: return confirm('Usted está a punto de hacer una Pre-autorización de esta Pre-nomina,está seguro de continuar ??');"> 
  <? } else if($prenominaPre_Autoriza && $dAutorizar['pre_autorizada'] == 1 && $dAutorizar['autorizada'] == 0 ) {	?>  
  <input type="submit" name="no_pre_autoriza" value="Quitar Pre Autorizar" onClick="javascript: return confirm('Usted está a punto de hacer una Pre-autorización de esta Pre-nomina,está seguro de continuar ??');"> 

  
  <?php } if(isset($_SESSION['id_admin'])){ ?>
  <br><br>
  <input type="button" name="pfd" value="Formato de impresion" onClick="genera()"> 
  <? } ?>
  <br>
  <br>
  <br></td>
      </tr> 
              </table>
            </form>
<? } if( $dAutorizar['autorizada'] == 1 ||  ( ( isset($_SESSION['id_admin']) || isset($_SESSION['id_supervisor']) )   && !$prenominaAutoriza && $dAutorizar['pre_autorizada'] == 1 )){ ?>
            <form name="califica" action="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listar"  method="post">
<input type="hidden" name="anio_nom_as" value="<?=$_REQUEST['anio_nom_as']?>">

              <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
              	<? if($dAutorizar['pre_autorizada'] == 1 && $dAutorizar['autorizada'] == 0  ){ ?>
                <tr>
                	<td colspan="20" style="color:#FF0000" align="center">PRENOMINA PRE-AUTORIZADA</td>
                </tr>
                <? } ?>
                <tr>
                  <td colspan="2" align="left" class="style5">Semana No.
                  <?=$_REQUEST['semana']?><input type="hidden" name="semana" value="<?=$_REQUEST['semana']?>">
                  <input type="hidden" name="area" value="<?=$_REQUEST['area']?>"></td>
              <td width="170" align="center" class="pre">Asistencia</td>
           	  <td width="12">&nbsp;</td>
              <td  width="170" align="center"  class="pre">Retardos</td>
           	  <td width="11">&nbsp;</td>
              <td width="170"  align="center"  class="pre">Extrasi</td>
              <td width="29" align="center"  class="pre">P.D.</td>
              <td width="22" align="center"  class="pre">A.</td>
              <td width="22" align="center"  class="pre">P.</td>
              <td width="21" align="center"  class="pre">Pr.</td>
              <td width="113" align="center"  class="pre">Causas  perdida</td>
              <td width="107" align="center" class="pre">Observaciones</td>
            </tr>

                <tr>
                  <td colspan="2" class="style5">Del 
                    <?=$dDelAl['0']?>
                    <? if($dDelAl[2] != $dDelAl[3]) echo " de ".$mes[$dDelAl[2]];?> 
                    al 
                    <?=$dDelAl['1']?> 
                    
                  <? if($dDelAl[2] != $dDelAl[3]) echo " de ".$mes[$dDelAl[3]]; else echo " de ".$mes[$dDelAl[intval(2)]]?> del <?=$_REQUEST['anio_nom_as']?> </td>
          	  <td width="170" align="left">
              	<table cellpadding="0" width="100%" cellspacing="0" align="left" border="1" style="border:thin"><tr><? 
			 $dias = array("J","V","S","D","L","M","Mi");
			 for($z = 0; $z < 7; $z++){?>
              <td width="10%" class="style5" style="font-size:10px; color:#000000; text-align:center"><?=$dias[$z]?></td>
			 <? } ?></tr></table></td>
           	  <td width="12" align="left">&nbsp;</td>
              <td width="170" align="left">
              	<table cellpadding="0" width="100%" cellspacing="0" align="left" border="1" style="border:thin">
                      <tr>
                     <? for($z = 0; $z < 7; $z++){?>
                      <td width="10%" class="style5" style="font-size:10px; color:#000000; text-align:center"><?=$dias[$z]?></td>
                      <? } ?>
                      </tr>
              	</table>
              </td>
           	  <td width="11" align="left">&nbsp;</td>
              <td width="170" align="left"><table cellpadding="0" width="100%" cellspacing="0" border="1" style="border:thin">
              	<tr>
                	<? for($z = 0; $z < 7; $z++){?>
                    	<td width="10%" class="style5" style="font-size:10px; color:#000000; text-align:center"><?=$dias[$z]?></td>
					<? } ?>
                </tr>
              </table>
            </td>
          	  <td colspan="5" ></td>
		  </tr>
                <tr>
                  <td width="55"  class="pre" align="center">Codigo</td>
              <td width="300" align="left"  class="pre">Nombre</td>
           	  <td width="70" align="left">
              	<table width="100%" cellpadding="0" cellspacing="0" align="left"  border="1" style="border:thin"><tr><?
					$semana	=	$_REQUEST['semana'];
								$qNumDia	=	"SELECT DAY(fecha),MONTH(fecha), YEAR(fecha) FROM asistencias ".
													//" WHERE semana = '".$_REQUEST['semana']."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'   ORDER BY fecha ASC";
													" WHERE semana = '".$_REQUEST['semana']."' AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'   ORDER BY fecha ASC";
								$rNumDia	=	mysql_query($qNumDia);
								$dNumDia	=	mysql_fetch_row($rNumDia);	
								
								$u_dia	= UltimoDia($dNumDia[2], $dNumDia[1]);
													
								$dNumDia[0];
			 for($z = 0; $z < 7; $z++){?>
              <td width="10%" class="style5" style="font-size:10px; color:#000000;text-align:center"><? $el_dia	=	$dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} echo $el_dia;?></td><? } ?></tr></table>            </td>
           	  <td width="12" align="left">&nbsp;</td>
           	  <td align="left" width="73">
  <table width="100%" cellpadding="0" cellspacing="0" align="left"  border="1" style="border:thin">
    <tr><? for($z = 0; $z < 7; $z++){?>
      <td width="10%" class="style5" style="font-size:10px; color:#000000; text-align:center"><? $el_dia	= $dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} echo $el_dia;?></td>
	<? } ?>
      </tr>
    </table>            </td>
           	  <td width="11" align="left">&nbsp;</td>
  <td align="left">
    <table width="100%" cellpadding="0" cellspacing="0" align="left"  border="1" style="border:thin">
      <tr><? for($z = 0; $z < 7; $z++){?>
        <td  width="10%"class="style5" style="font-size:10px; color:#000000; text-align:center"><? $el_dia = $dNumDia[0] + $z; if($el_dia > $u_dia){ $el_dia = $el_dia - $u_dia ;} echo $el_dia;?></td>
					  <? } ?>
        </tr>
      </table>            </td>
         	  <td></td>
          	  <td align="center">%</td>
          	  <td align="center">%</td>
          	  <td align="center">%</td>
         </tr>
                <? 
					if(isset($_SESSION['id_admin'])){		
						 $qAsistencias	=	"SELECT * FROM pre_nomina_calificada INNER JOIN operadores ON pre_nomina_calificada.id_operador = operadores.id_operador".
										" WHERE semana = '".$_REQUEST['semana']."' AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' )   AND operadores.area = ".$_REQUEST['area']." AND activo = 0 AND ( autorizada = 1 OR pre_autorizada = 1 ) AND pre_nomina_calificada.desde = '".$desdeFec."' AND pre_nomina_calificada.hasta = '".$hastaFec."'   ORDER BY numnomina ASC";
					}  else if(!isset($_SESSION['id_admin'])){		
						 $qAsistencias	=	"SELECT * FROM pre_nomina_calificada INNER JOIN operadores ON pre_nomina_calificada.id_operador = operadores.id_operador".
										" WHERE semana = '".$_REQUEST['semana']."' AND (YEAR(desde) = '".$_REQUEST['anio_nom_as']."' || YEAR(hasta) = '".$_REQUEST['anio_nom_as']."' ) AND operadores.area = ".$_REQUEST['area']." AND activo = 0 AND rol = ".$_SESSION['rol']."  AND ( autorizada = 1 OR pre_autorizada = 1 ) AND pre_nomina_calificada.desde = '".$desdeFec."' AND pre_nomina_calificada.hasta = '".$hastaFec."'  ORDER BY numnomina ASC";
					}
					
					$rAsistencias	=	mysql_query($qAsistencias);
					$nAsistencias	=	mysql_num_rows($rAsistencias);
					
					if($nAsistencias > 0){
					for( $k = 0;  $dAsistencias	=	mysql_fetch_assoc($rAsistencias); $k++){ 

						
						$qFaltas	=	"SELECT extra, retardo, asistencia, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC";
						//$qFaltas	=	"SELECT extra, retardo, asistencia, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ORDER BY fecha ASC";
						//$qFaltas	=	"SELECT extra, retardo, asistencia, id_asistencia FROM asistencias WHERE semana = '".$semana."' AND YEAR(fecha) = '".$_REQUEST['anio_nom_as']."' AND id_operador = ".$dAsistencias['id_operador']."   ORDER BY fecha ASC";
						$rFaltas	=	mysql_query($qFaltas);
						
						for($c = 0; $dFaltas = mysql_fetch_assoc($rFaltas); $c++){
						
						$faltas[$c]  =	array($dFaltas['asistencia']);
						$retardos[$c]  =	array($dFaltas['retardo']);
						$extras[$c]  =	array($dFaltas['extra']);
						}
					
					 ?>
                <tr <? if(bcmod(intval($k),2) == 0){ 
								$back 	= "#DDDDDD";  
								$frente = "#B4C3EC";
								echo "bgcolor='".$back."'";
								}  else { 
								$back 	= "#FFFFFF";  
								$frente = "#B4C3EC";
								echo "bgcolor='".$back."'";
								
								}?> onMouseOver="this.bgColor='<?=$frente?>'" onMouseOut="this.bgColor='<?=$back?>'">
                                
                  <td style="font-size:10px" align="left" height="25" class="style5"><?=$dAsistencias['numnomina']?></td>
                <td align="left" class="style5" style="font-size:10px"><?=$dAsistencias['nombre']?></td>
                <td>
                  <table width="100%" style="border:thin;" cellpadding="0" cellspacing="0" border="1">
                    <tr>
                     <? for($z = 0; $z <1; $z++){ 
					 		for($d = 0 ;$d < 7; $d++){
					  ?>
                      <td width="10%" class="style5" 
                      style="font-size:10px;text-align:center; background-color:#FFFFFF; 
					  <? if($faltas[$d][$z] == 'F' || $faltas[$d][$z] == 'f') echo "color:#FF0000;"; 
					  	  if($faltas[$d][$z] == 'I' || $faltas[$d][$z] == 'i' ) echo "color:#0000FF;"; 
						  if($faltas[$d][$z] == 'FJ') echo "color:#008800;";?>">
						  <? if($faltas[$d][$z] == '' || $faltas[$d][$z] == '0') echo "&nbsp;";
						   else echo $faltas[$d][$z];  ?></td><? } } ?>
                     
                      </tr>
                  </table>            
                </td>
              <td width="12">&nbsp;</td>
            <td align="left" style="font-size:9px">              	
              <table width="100%" style="border:thin;" cellpadding="0" cellspacing="0" border="1">
                <tr>
                  <? for($y = 0; $y < 1; $y++){
				  		for($d = 0; $d< 7 ;$d++){?>
                  		
                  	<td width="10%" class="style5" style="font-size:10px;text-align:center; background-color:#FFFFFF;">
					<? if($retardos[$d][$y] == '' || $retardos[$d][$y]  == '0') echo "&nbsp;"; else echo $retardos[$d][$y] ;?></td><? } } ?>
                  </tr>
                </table>              </td>
           	  <td width="11" align="left">&nbsp;</td>
      <td align="left" style="font-size:9px">              	
        <table width="100%" style="border:thin;" cellpadding="0" cellspacing="0" border="1">
          <tr>
            <? for($x = 0; $x < 1; $x++){
					for($d = 0; $d< 7 ;$d++){ ?>
            	<td width="10%" class="style5" style="font-size:10px;text-align:center; background-color:#FFFFFF;">
				<? if($extras[$d][$x] == '' || $extras[$d][$x] == '0') echo "&nbsp;"; else echo $extras[$d][$x];?></td><? } } ?>
            </tr>
          </table></td>
              <td align="center"><? 
				$qPrima	=	"SELECT * FROM asistencias WHERE dia = 'D' AND  asistencia IN ('1','2','3','m')  AND semana = ".$semana." AND id_operador = ".$dAsistencias['id_operador']."  AND fecha BETWEEN '".$desdeFec."' AND '".$hastaFec."'  ";
//echo $qPrima;
				$rPrima	=	mysql_query($qPrima);
				$nPrima	=	mysql_num_rows($rPrima);
					if($nPrima > 0)
					 echo "SI";
			?></td>
              <td align="center" style="font-size:10px" class="style5"><? if($dAsistencias['por_asist'] == '' ) echo "10"; else echo $dAsistencias['por_asist'];?></td>
              <td align="center" style="font-size:10px" class="style5"><? if($dAsistencias['punt'] == '') echo "5"; else if($dAsistencias['punt'] == '0') echo "0"; else echo $dAsistencias['punt'];?></td>
              <td align="center" style="font-size:10px" class="style5"><? if($dAsistencias['prod'] == '') echo "10"; else if($dAsistencias['prod'] == '0') echo "0"; else echo $dAsistencias['prod'];?></td>
              <td align="center" style="font-size:10px" class="style5"><? if($dAsistencias['causas'] == '' || $dAsistencias['causas'] == '0') echo ""; else echo $dAsistencias['causas'];?></td>
              <td align="center" style="font-size:10px" class="style5"><? if($dAsistencias['observaciones'] == '' || $dAsistencias['observaciones'] == '0') echo ""; else echo $dAsistencias['observaciones'];?></td>
          </tr>
 
                <? } 
			
			} else { ?>
                <tr>
                  <td colspan="18" style="color:#FF0000" align="center"><br>
  <br>
                    NO HAY EMPLEADOS DADOS DE ALTA EN EL SISTEMA EN ESTA AREA<br>
  <br></td>
              </tr>
                <? }?>
        
          
         <tr>
         	<td></td>
            <td colspan="12" align="justify" class="style5"><br>
<br>
<p align="justify">I = incapacidad&nbsp;&nbsp;&nbsp;&nbsp;C = castigo&nbsp;&nbsp;&nbsp;&nbsp;
              FS = Festivo&nbsp;&nbsp;&nbsp;&nbsp;
              Ps = Permiso sin sueldo</p>
              <p align="justify">V = Vacaciones&nbsp;&nbsp;&nbsp;&nbsp;
                D = Descanso&nbsp;&nbsp;&nbsp;&nbsp;
                F = Falta injustificada.&nbsp;&nbsp;&nbsp;&nbsp;
                FJ = Falta Justificada.
                </p>
                <p align="justify">
                NCE = No Chec&oacute; entrada.&nbsp;&nbsp;&nbsp;&nbsp;
                NSC = No Chec&oacute; salida.&nbsp;&nbsp;&nbsp;&nbsp;
                Alim. Alimentos: retados o checadas. <br>
                <br>
                </p>
              </td>
         </tr>
                <tr>
                  <td colspan="20" align="center" ><br>
  <br>
 <?  if($prenominaAutoriza) {	?> <input type="submit" name="no_autoriza" value="Quitar autorizaci&oacute;n" onClick="javascript: return confirm('Usted está a punto de quitar la autorización de la Pre-nomina, está seguro de continuar ??');">&nbsp;&nbsp;<? } ?>
 <input type="button" value="Regresar" onClick="javascript: history.go(-1)" />
  
 <?php  if($prenominaPre_Autoriza && $dAutorizar['pre_autorizada'] == 1 && $dAutorizar['autorizada'] == 0 ) {	?>  
  <input type="submit" name="no_pre_autoriza" value="Quitar Pre Autorizar" onClick="javascript: return confirm('Usted está a punto de hacer una Pre-autorización de esta Pre-nomina,está seguro de continuar ??');"> 
  <? } if(isset($_SESSION['id_admin'])){ ?>
  <br><br>
  <input type="button" name="pfd" value="Formato de impresion" onClick="genera()"> <br>
  <? } ?>
  <br></td>
      </tr> 
              </table> </form><? } ?>
          </blockquote>
   	  </div>
  </div>
</div>
</body>
<? } if($listar){ 
if(date('w') < 4) $semana_re = date('W')-1;
else	$semana_re = date('W');
 ?>
	<div id="datosgenerales" style="background-color:#FFFFFF;">	
        	<table align="center" width="80%" >
				<tr>
                	<td colspan="5" align="center">| <a href="<?=$_SERVER['PHP_SELF']?>?seccion=37&accion=portada&semana_reportes=<?=$semana_re?>" class="style7">Lista de movimientos de personal </a>|<br>
                	  <br>
                	</td>
                </tr>
                <tr>
                <? if(isset($_SESSION['id_admin']) && $prenominaReMo){ ?>
                	<td width="30%" align="center" valign="top">
                      <form action="<?=$_SERVER['PHP_SELF']?>?seccion=31&tipo=100" method="post" name="form" >	
                    	<table width="100%" align="center">
                        	<tr>
                            	<td colspan="2" align="left"><h3>REPORTES MOVIMIENTOS</h3></td>
                            </tr>
  							<tr>
                            	<td width="25%">Semana:</td>
               	  				<td width="75%"><select name="semana_reportes" >
									<? if(date('w') < 4) 
									      $semana_re = date('W')-1;
									   else	
											$semana_re = date('W');
										$qSemanas		=	"SELECT semana FROM pre_nomina_calificada WHERE semana != 0 GROUP BY semana ORDER BY semana ASC";
										$rSemanas	=	mysql_query($qSemanas);	
										while($dSemanas 	=	mysql_fetch_assoc($rSemanas)){
									?>
               		 					 <option value="<?=$dSemanas['semana']?>" <? if( $semana_re == $dSemanas['semana']) echo "selected"; ?>>Semana No <?=$dSemanas['semana']?></option>
                       				  <? } ?>
                       				</select>
                                </td>
                            </tr>
                            <tr>
                            	<td width="25%">A&Ntilde;O:</td>
                                <td width="75%"><select name="anio_nom">
                                    <option <?=(date('Y') == '2008')?"selected":""?>>2008</option>
                                    <option <?=(date('Y') == '2009')?"selected":""?>>2009</option>
                                    <option <?=(date('Y') == '2010')?"selected":""?>>2010</option>
                                    <option <?=(date('Y') == '2011')?"selected":""?>>2011</option>
                                    <option <?=(date('Y') == '2012')?"selected":""?>>2012</option>
                                    <option <?=(date('Y') == '2013')?"selected":""?>>2013</option>
                                    <option <?=(date('Y') == '2014')?"selected":""?>>2014</option>
                                    <option <?=(date('Y') == '2015')?"selected":""?>>2015</option>
                                    <option <?=(date('Y') == '2016')?"selected":""?>>2016</option>
                                    <option <?=(date('Y') == '2017')?"selected":""?>>2017</option>
                                    <option <?=(date('Y') == '2018')?"selected":""?>>2018</option>
                                    <option <?=(date('Y') == '2019')?"selected":""?>>2019</option>
                                   </select> 
                           	  </td>
                          </tr>
                            <tr>
                            	<td>Tipo:</td>
                                <td><select name="movimiento">
                                            <option value="1">Normal</option>
                                            <option value="2">Economico</option>
                                            <option value="3">Ambos</option>
                                         </select></td>
                            </tr>
                            <tr>
                            	<td>Areas :</td>
                                <td>
                                	<table width="100%">
                                    	<tr>
                                        	<td>
                                            	<? if($_SESSION['area'] == 1 || $prenominaExt){ ?>
                                                 	<label ><input type="checkbox"  value="1" name="extruder" >Extruder</label><br><br>
												<? } if($_SESSION['area2'] == 1 || $prenominaImpr){ ?>                                            	
                                                	<label ><input type="checkbox"  value="1" name="impresion" >Impresion</label><br><br>
                                                <? } if($_SESSION['area3'] == 1 || $prenominaBol){ ?>
                                            	<label ><input type="checkbox"   value="1" name="bolseo" >Bolseo</label><br><br>
                                                <? } if($prenominaMtto){ ?>
                                            	<label ><input type="checkbox"  value="1" name="mantto" >Mantto</label><br><br>
                                                <? } if($prenominaMttob){ ?>
                                            	<label ><input type="checkbox"  value="1" name="manttob" >R.P.S.</label><br><br>
                                                <? } if($prenominaEmp){ ?>
                                            	<label ><input type="checkbox"  value="1" name="emp" >Empaque</label><br><br>
                                                <? } if($prenominaEmpb){ ?>
                                            	<label ><input type="checkbox"  value="1" name="empb" >S.F.</label><br><br>
                                                <? } if($prenominaAlm){ ?>
                                            	<label ><input type="checkbox"  value="1" name="alm" >Almacen</label><br><br>
                                                <? } if($prenominaAlmb){ ?>
                                            	<label ><input type="checkbox"  value="1" name="almb" >Calidad</label><br><br>
                                                <? } ?>
                                                </td>
                               				</tr>
                                     	</table>
                                  </td>
                            </tr>
                       		<tr>
                            	<td colspan="2" align="center"><input type="submit" name="genera" value="generar_reporte"></td>
                           </tr>
                       </table></form> 
					</td> <? } ?>
         
                	<td align="center" width="5%">&nbsp;</td>
                
                	<td width="30%" align="center" valign="top">
<script type="text/javascript" language="javascript">
function genera2()
{
document.pre.action="reportes_pdf_nomina.php?tipo=prenomina";
document.pre.submit();
}

function genera()
{
document.pre.action="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listarNomina";
document.pre.submit();
}
</script>
                       <form name="pre" method="post" action="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>&accion=listarNomina">
                    	<table width="100%">
            				<tr>
                                <td colspan="2" align="left"><h3>Asistencias</h3></td>
                            </tr>
                            <tr>
                            	<td width="24%">Semana:</td>
                      <td width="76%">
                      <? 
                                        if(date('w') < 4) 
                                        	$semana = date('W')-1;
                                        else	
                                        	$semana = date('W');
                                        ?>
                                  <select name="semana" id="semana">
										<?php
										
                                        $qSemana		=	"SELECT semana FROM pre_nomina_calificada WHERE semana != 0 GROUP BY semana ORDER BY semana ASC";
                                            $rSemana	=	mysql_query($qSemana);	
                                            while($dSemana 	=	mysql_fetch_assoc($rSemana)){
                                        ?>
                                          <option value="<?=$dSemana['semana']?>" <? if( $semana == $dSemana['semana']) echo "selected"; ?>>Semana No <?=$dSemana['semana']?></option>
                                         <? } ?>
                                    </select>
                                    <br>
                                    <select name="anio_nom_as">
                                	    <option <?=(date('Y') == '2008')?"selected":""?>>2008</option>
                            	        <option <?=(date('Y') == '2009')?"selected":""?>>2009</option>
                        	            <option <?=(date('Y') == '2010')?"selected":""?>>2010</option>
                    	                <option <?=(date('Y') == '2011')?"selected":""?>>2011</option>
                	                    <option <?=(date('Y') == '2012')?"selected":""?>>2012</option>
                	                    <option <?=(date('Y') == '2013')?"selected":""?>>2013</option>
                	                    <option <?=(date('Y') == '2014')?"selected":""?>>2014</option>
                	                    <option <?=(date('Y') == '2015')?"selected":""?>>2015</option>
                	                    <option <?=(date('Y') == '2016')?"selected":""?>>2016</option>
                	                    <option <?=(date('Y') == '2017')?"selected":""?>>2017</option>
                	                    <option <?=(date('Y') == '2018')?"selected":""?>>2018</option>
                	                    <option <?=(date('Y') == '2019')?"selected":""?>>2019</option>

				                   </select> 
                                    </td>
                          </tr>
                            <tr>
                              	<td>Area :</td>
                                <td><select name="area" >
									<? if($_SESSION['area'] == 1 || $prenominaExt){ ?> 
                                    <option value="1">Extruder</option>
									<? } if($_SESSION['area2'] == 1 || $prenominaImpr){ ?>
									<option value="3">Impresion</option>
									<? } if($_SESSION['area3'] == 1 || $prenominaBol){ ?>
									<option value="2">Bolseo</option>
									<? } if($prenominaMtto){ ?>
									<option value="4">Mantenimiento</option>
									<? } if($prenominaMttob){ ?>
									<option value="6">R.P.S.</option>
									<? } if($prenominaEmp){ ?>
									<option value="5">Empaque</option>
									<? } if($prenominaEmpb){ ?>
									<option value="7">S.F.</option>
                                   	<? } if($prenominaAlm){ ?>
									<option value="8">Almacen</option>
									<? } if($prenominaAlmb){ ?>
									<option value="9">Calidad</option>
									<? } ?>
                   		 
                  					</select></td>
                             </tr>
                             <tr>
                             	<td colspan="2" valign="middle" align="center"><br><input name="ver" type="submit" class="texto3" value="  Ver  " onClick="genera();" ><br><br></td>
                             </tr>                             
                             <tr>
                             	<td colspan="2" align="center">
                                	<? if(isset($_SESSION['id_admin'])){ ?>
 										<label><input type="checkbox" value="1" name="todas" id="todas">Imprimir todas las areas</label><br>
                                       
 									<? } ?>
                                </td>
                             </tr>
                             
                             <tr>
                             	<td colspan="2" align="center">
                                	<? if(isset($_SESSION['id_admin'])){ ?>
 									 <input type="button" name="pre" value="Formato de impresion" onClick="genera2()"> 
 									<? } ?>                                
                                </td>
                             </tr>
                         </table>
          		</form></td>
            	<td width="5%">&nbsp;</td>
 
        	<td width="30%" align="center" valign="top">
              <form name="buscar" method="post" action="<?=$_SERVER['PHP_SELF']?>?seccion=<?=$_REQUEST['seccion']?>&accion=busqueda">
            	<table width="100%">
                	<tr>
                    	<td colspan="2"><h3>Buscador Asistencias</h3></td>
                    </tr>
                	<tr>
                    	<td width="19%">Fecha:</td>
       			  	  <td width="81%"><input type="text" name="fecha" id="fecha" value="<?=date('d-m-Y')?>" size="20">    
                      <a href="#" onClick="NewWindow('minical.php?destino=fecha','Calendario',300,250,false,'center')"><img border="0" alt="Calendario" src="images/calendario.jpg" width="25" class="Tips4" title="Calendario :: Haga click para abrir el calendario." /></a></td>
                  </tr>
                    <tr>
                      <td colspan="2" align="center"><input type="submit" value="Buscar" name="buscar"></td>
                    </tr>
                </table></form>
             </td>
             </tr>
             <tr>
             <td><br><br><br><br><br><br></td>
             
        </tr>
	</table>
    </div>
<? } ?>

<? if($busqueda){ ?>
<script type="text/javascript" language="javascript">
function genera()
{
document.form.action="reportes_pdf_nomina.php?tipo=asistencias&area=<?=$_REQUEST['area']?>&fecha=<?=$_REQUEST['fecha']?>";
document.form.submit();

}
</script>
<div id="content">
	<div id="datosgenerales" style="background-color:#FFFFFF;">
    <form name="form" action="<?=$_SERVER['HOST']?>?seccion=<?=$_REQUEST['seccion']?>" method="post">
    <table width="100%">
    	<tr>
        	<td colspan="6">
            	<table width="100%">
                	<tr>
        				<td width="21%"><h3>Reporte del dia:</h3></td>
           				<td width="79%" class="style5"><b><?=$_REQUEST['fecha']?></b>
       				  <input type="hidden" name="fecha" value="<?=$_REQUEST['fecha']?>"></td>
                  </tr>
                </table>
             </td>
         </tr>
    	<tr>
          <td width="14%"><h3>Area</h3></td>
       	  <td width="9%"><h3>Nomina</h3></td>
          <td width="35%"><h3>Nombre</h3></td>
          <td width="8%"><h3>Turno</h3></td>
          <td width="8%"><h3>Horas</h3></td>
          <td width="26%">&nbsp;</td>
      </tr>
        <? 
			$fecha = 	fecha_tablaInv($_REQUEST['fecha']);
			$qBusqueda	=	"SELECT * FROM operadores INNER JOIN asistencias ON operadores.id_operador = asistencias.id_operador WHERE fecha = '".$fecha."' AND ( asistencia = '1' OR asistencia = '2' OR asistencia = '3' OR asistencia = 'M' )  ORDER BY operadores.area, operadores.numnomina ASC";
			$rBusqueda	=	mysql_query($qBusqueda);
			
			for($a = 0; $dBusqueda = mysql_fetch_assoc($rBusqueda); $a++){ ?>
        
        <tr <? if(bcmod($a,2) == 0 ) echo "bgcolor='#EEEEEE'"; ?>>
        	<td class="style5" align="center"><?=$areasEmpleado[$dBusqueda['area']]?></td>
        	<td class="style5" align="center"><?=$dBusqueda['numnomina']?></td>
        	<td  class="style5" align="left"><?=$dBusqueda['nombre']?></td>
            <? if($dBusqueda['asistencia'] >= 1 && $dBusqueda['asistencia'] <= 3 ){ ?>
        	<td  class="style5" align="left"><?=$turnos[$dBusqueda['asistencia']]?></td>
            <? } if($dBusqueda['asistencia'] == 'M' || $dBusqueda['asistencia'] == 'm'  ){ ?>
        	<td  class="style5" align="left">Mixto</td> 
            <? } ?>           
        	<td  class="style5" align="center"><? if($dBusqueda['asistencia'] == 1 ) echo "8";
					if($dBusqueda['asistencia'] == 2 ) echo "7";
					if($dBusqueda['asistencia'] == 3 ) echo "9";
			?></td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
        </tr>

        <? } ?>
                <tr>
        	<td colspan="7" align="center"><br>
<br>
<br>
<br>
<input type="button" name="pfd" value="Formato de impresion" onClick="genera()"></td>
        </tr>
   </table></form>
</div>
</div>
<? } ?>
