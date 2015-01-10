<?php 

@session_start();

//print_r($_SESSION);

if(isset($_REQUEST['usuario']) && isset($_REQUEST['contra']))

{

	$_SESSION['user']=$_REQUEST['usuario'];

	$_SESSION['pass']=$_REQUEST['contra'];

}

//include 'valida_sesion2.php';

include 'libs/funciones.php';

 ?>

  

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>Dolfra - Sistema de Produccion</title>

	<link href="print.css" rel="stylesheet" type="text/css" media="print"> 

<link rel="stylesheet" type="text/css" media="screen" href="DatePicker.css" />

	<style type="text/css" media="screen">@import 'style.css';</style>

	<style type="text/css" media="screen">@import 'style.css';</style>

	<style type="text/css" media="screen">@import 'design/estilos.css';</style>

<script type="text/javascript" src="select_dependientes.js"></script>

<script type="text/javascript" src="select.js"></script>

<script language="javascript" type="text/javascript" src="js/mootools.svn.js"></script>

<script type="text/javascript" src="libs/jquery-1.2.6.pack.js"></script>

<script language="javascript" type="text/javascript" src="js/date.js"></script>

<script language="javascript" type="text/javascript" src="js/DatePicker.js"></script>

<script> var $j = jQuery.noConflict();</script>

<script src="libs/imprimir.js" ></script>

<script language="javascript" type="text/javascript">

$j(document).ready(function(){

	$j('.link').click(function(){ $j.jPrintArea('#tablaimpr') });	

	$j('.fecha').datePicker({clickInput:true, startDate:'01/01/1996'})

});

</script>

<script type="text/javascript" src="libs/funciones.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>:: DOLFRA ::</title>



<style type="text/css">

<!--

body {

	margin-left: 0px;

	margin-top: 0px;

	margin-right: 0px;

	margin-bottom: 0px;

}





-->

</style>

<link href="dolfra.css" rel="stylesheet" type="text/css" />

</head>



<body>

 

<table border="0" align="center" cellpadding="0" cellspacing="0" border="0">

  <tr>

    <th scope="col"><table border="0" cellspacing="0" cellpadding="0">

      <tr id="logo">

        <th width="208" rowspan="2" align="left" valign="top" scope="col"><img src="images/admin_02.jpg" height="183" alt="" /></th>

        <th width="798" scope="col" align="right"><img src="images/admin_03.jpg" alt="" height="74" border="0" usemap="#Map" /></th>

      </tr>

      <tr id="logo">

        <th align="left" valign="bottom" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr><td colspan="10" align="left">Bienvenido : <strong><?=$_SESSION['nombre']?></strong></td></tr>

	        <tr>

            <? 	  if( $_SESSION['area']  == 1 ){?><th width="8%" scope="col"><a href="admin_supervisor.php?seccion=23&accion=supervisor" ><img src="images/admin_12.jpg"  alt="Extruder" border="0" class="Tips4" title="REPORTEO EXTRUDER :: capture sus reportes de trabajo." /></a></th>

            <? } if( $_SESSION['area2'] == 1 ){?><th width="8%" scope="col"><a href="admin_supervisor.php?seccion=27&accion=supervisor" ><img src="images/admin_13.jpg"  alt="Impresion" border="0" class="Tips4" title="REPORTEO IMPRESION :: capture sus reportes de trabajo " /></a></th>

            <? } if( $_SESSION['area3'] == 1 ){?><th width="8%" scope="col"><a href="admin_supervisor.php?seccion=29&amp;accion=supervisor" class="style6"><img src="images/admin_11.jpg"  alt="Bolseo" border="0" class="Tips4" title="REPORTEO BOLSEO :: capture sus reportes de trabajo " /></a></th>

            <? } if( $_SESSION['area']  == 1 ){?><th width="8%" scope="col"><a href="admin_supervisor.php?seccion=31&accion=listar&extruder" class="style6"><img src="images/rep_ext.jpg" alt="Edicion Reportes Extruder" width="60"  border="0" class="Tips4" title="EDICION REPORTES EXTRUDER :: Modifique sus reportes." /></a></th>

            <? } if( $_SESSION['area2'] == 1 ){?><th width="8%" scope="col"><a href="admin_supervisor.php?seccion=31&accion=listar&impresion" class="style6"><img src="images/rep_impr.jpg" width="60" alt="Edicion Reportes Impresion" border="0" class="Tips4" title="EDICION REPORTES IMPRESION :: Modifique sus reportes"/></a></th>

            <? } if( $_SESSION['area3'] == 1 ){?><th width="8%" scope="col"><a href="admin_supervisor.php?seccion=31&accion=listar&bolseo" class="style6"><img src="images/rep_bol.jpg" border="0" width="60" class="Tips4" title="EDICION REPORTES BOLSEO :: Modifique sus reportes" /></a></th>

            <? } ?>

            <th width="7%" scope="col"><a href="admin_supervisor.php?seccion=32&accion=listar" class="style6"><img src="images/admin_10.jpg"  alt="REPORTES" border="0" class="Tips4" title="REPORTES :: Observe los reportes que USTED a dado de alta." /></a></th>

            <th width="7%" scope="col"><a href="admin_supervisor.php?seccion=33&accion=listar" class="style6"><img src="images/lista.jpg"  alt="ASISTENCIAS" border="0" class="Tips4" title="ASISTENCIAS :: Tome la asistencia dia a dia de sus trabajadores.." /></a></th>

            <th width="8%" scope="col"><a href="admin_supervisor.php?mes=<?=date('m')?>&accion=nuevo&anho=<?=date('Y')?>&seccion=4&submit=IR" class="style6"><img src="images/calendario.jpg" border="0" class="Tips4" title="CALENDARIO :: Revise sus horarios" /></a></th>

            <th width="8%" scope="col"><a href="admin_supervisor.php?seccion=7&amp;accion=listar" class="style6"><img src="images/repesadas.jpg" border="0" class="Tips4" title="REPESADAS :: Modifique sus registros de desperdicios y segundas del turno anterior."/></a></th>

            <th width="7%" scope="col"><a href="admin_supervisor.php?seccion=15&accion=listar" class="style6"><img src="images/admin_08.jpg"  alt="Metas" border="0" class="Tips4" title="METAS :: Mantengase al tanto de las metas del mes." /></a></th>

            <th width="7%" scope="col"><a href="admin_supervisor.php?seccion=35&accion=centro" class="style6"><img src="images/mensajeria_instantanea.jpg"  alt="Mensajeria" border="0" class="Tips4" title="CENTRO DE MENSAJES :: Envio y entrega de mensajes.." /></a></th>

            <th width="29%" scope="col" align="right"><a href="logout.php" class="style6"><img src="images/admin_14.jpg"  alt="Cerrar Sesion" border="0" class="Tips4" title="Cerrar Sesion :: Salir del sistema" /></a></th>

          </tr>

        </table></th>

      </tr>

      <tr>

        <td colspan="2" align="center" valign="top" id="logo"><img src="images/admin_15.jpg" width="100%" height="18" border="0" /></td>

      </tr>

      <tr>

        <td colspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>

        	<td align="left">

            	<table cellpadding="0" cellspacing="0" width="80%" class="txt_titulos">

                <? include("menu2.php") ?>

                </table>            </td>

         </tr>

          <tr>

            <td colspan="2" ><?php



		switch($_GET['seccion'])

		{

			case 2:

				include("extruder.php");	break;

			case 3:

				include("bolseo2.php");		break;

			case 4:

				include("revisa.php");		break;

			case 5:

				include("reportes.php");	break;	

			case 7:

				include("actualiza_desper.php");	break;

            case 15:

			    include("meta.php");        break;

			case 20:

				include("impresion.php");        break;

			case 21:

				include("impresion.php");        break;

			case 22:

				include("actualiza_desper_impresion.php.php"); break;

			case 23:

				include("tiempos_extruder.php"); break;	

			case 24:

				include("pre_nomina.php"); break;

			case 27:

				include("tiempos_impresion.php"); break;

			case 29:

				include("tiempos_bolseo.php"); break;

			case 31:

				include("autorizar.php"); break;

			case 32:

				include("reportes_admin.php"); break;		

			case 33:

				include("pre_nomina.php"); break;

			case 34:

				include("identificacion_segundas.php"); break;

			case 35:

				include("mensajeria.php"); break;

			case 37:

				include("movimientos.php"); break;			

			case 38:

				include("diario.php"); break;

			case 39:

				include("reportes_web.php"); break;

			case 40:

				include("reporte_promedios.php");	break;

			case 41:

				include("reporte_paros(bolseo).php");	break;

			default: 

				include("home.php"); break;

		}

	

	?>

 </td>

            </tr>

      <tr>

        <td colspan="4" align="center" id="logo"><img src="images/admin_17.jpg" height="38" alt="" /></td>

      </tr>

      </table></td>

        </tr>



    </table></th>

  </tr>

</table>

<map name="Map" id="Map">

<area shape="circle" coords="641,28,14" href="admin_supervisor.php" class="Tips4" title="CENTRO DE ACTIVIDADES :: Regrese al centro de actividades diarias."  />

<area shape="circle" coords="676,28,15" href="#" class="Tips4" title="MENSAJES RAPIDOS :: Envie mensajes rapidos, sin salir de la seccion en que se encuentra." onclick="Abrir_ventana('pop_up.php?id_supervisor=<?=$_SESSION['id_supervisor']?>&accion=nuevo');" />

</map>

<?php if(!empty($redirecciona)) { ?>

<script language="javascript" type="text/javascript">

<!--

window.onload = function(){

	window.location.href = "<?=$ruta?>";

}

-->

</script>

<?php } ?>



</body>

</html>