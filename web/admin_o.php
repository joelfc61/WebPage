<?php 

@session_start();
if(isset($_REQUEST['usuario']) && isset($_REQUEST['contra']))
{
	$_SESSION['user']=$_REQUEST['usuario'];
	$_SESSION['pass']=$_REQUEST['contra'];
      
}

include 'libs/valida_sesion.php';
include 'libs/funciones.php'; 

?>

<html >
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Dolfra - Sistema de Producci&oacute;n</title>
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
</head>
<body <? if($_REQUEST['accion'] == 'nuevaentrada' && $_REQUEST['seccion'] == 6 ) echo 'onLoad="redirTimer()"'; ?> >
<table border="0" style="background-color:transparent" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th scope="col"><table align="center" border="0" width="100%" cellspacing="0" cellpadding="0" >
      <tr>
        <th rowspan="2" align="left" valign="top" scope="col"  class="styleTabla"><img src="images/admin_02.jpg" height="183" alt="" /></th>
        <th scope="col" align="right"  class="styleTabla" ><img src="images/admin_03.jpg" alt="" height="74" border="0" usemap="#MapAdmin"  /></th>
      </tr>
      <tr >
        <th align="left" valign="bottom" scope="col"><table width="80%" border="0" cellspacing="0" cellpadding="0" align="right"  id="logo" >
        <tr align="right" class="styleTabla">
        	<th colspan="<?=$colspan2+$colspan-$colspan3?>" align="left">Bienvenido : <strong><?=$_SESSION['nombre_admin']?></strong></th>
        	<? if($pendientes){?><th scope="col"  ><a href="admin.php?seccion=6&accion=listarPendientes" ><img src="images/reportes_pendientes.jpg"  alt="Reportes pendientes" border="0" class="Tips4" title="REPORTES PENDIENTES :: Reportes sin autorizar y sin repesar pendientes." /></a></th><? } ?>
            <th scope="col"  class="styleTabla"><a href="admin.php?seccion=36&accion=centro" class="style6"><img src="images/mensajeria_instantanea.jpg"  alt="messenger" border="0" class="Tips4" title="CENTRO DE MENSAJES :: Mantenga al tanto su personal sobre sus avances."/></a></th>
        </tr>
          <tr align="right" class="styleTabla">
			<? if($colspan2 > 0){ for($col = 0; $col < $colspan2; $col++){?><th width="50px">&nbsp;</th><? } }?>
            <? if($maquinaria){?><th scope="col"><a href="admin.php?seccion=1&accion=listar" ><img src="images/admin_04.jpg"  alt="Maquinaria" border="0" class="Tips4" title="MAQUINAS :: Alta de maquinaria" /></a></th><? } ?>
            <? if($supervisores){?><th scope="col"><a href="admin.php?seccion=13&accion=listar" class="style6"><img src="images/admin_05.jpg"  alt="Supervisores" border="0" class="Tips4" title="SUPERVISORES :: Alta de supervisores"  /></a></th><? } ?>
            <? if($empleados){?><th scope="col"><a href="admin.php?seccion=14&accion=listar" class="style6"><img src="images/admin_06.jpg" alt="Operadores" border="0" class="Tips4" title="EMPLEADOS :: Alta de empleados"  /></a></th><? } ?>
            <? if($usuarios){?><th scope="col"><a href="admin.php?seccion=32&accion=listar" class="style6"><img src="images/usuariodelsistema.jpg"  alt="Usuarios" border="0" class="Tips4" title="USUARIOS :: Alta de Usuarios para el sistema"/></a></th><? } ?>
            <? if($ventas){?><th scope="col"><a href="admin.php?seccion=8&accion=listar" class="style6"><img src="images/admin_07.jpg" alt="Ventas" border="0" class="Tips4" title="VENTAS :: Alta de vendedores"  /></a></th><? } ?>
            <? if($areaExtruder){?><th scope="col"><a href="admin.php?seccion=25&accion=supervisor" class="style6"><img src="images/admin_12.jpg"  alt="Extruder" border="0" class="Tips4" title="REPORTES EXTRUDER :: Reporteo de extruder"  /></a></th><? } ?>
            <? if($areaImpresion){?><th scope="col"><a href="admin.php?seccion=26&accion=supervisor" class="style6"><img src="images/admin_13.jpg" alt="Impresion" border="0"class="Tips4" title="REPORTES DE IMPRESION :: Reporteo de impresion" /></a></th><? } ?>
           	<? if($areaBolseo){?><th scope="col"><a href="admin.php?seccion=28&accion=supervisor" class="style6"><img src="images/admin_11.jpg"  alt="Bolseo" border="0" class="Tips4" title="REPORTES DE BOLSEO :: Reporteo de bolseo"/></a></th><? } ?>
            <? if($autorizacion){?><th scope="col"><a href="admin.php?seccion=6&accion=listar" class="style6"><img src="images/permiso_c.jpg"  alt="Autorizaciones" border="0" class="Tips4" title="AUTORIZACIONES :: Autorizacion de capturas de reportes"/></a></th><? } ?>
            <? if($repesadas){?><th scope="col"><a href="admin.php?seccion=7&accion=edicion" class="style6"><img src="images/repesadas.jpg" alt="Repesadas" border="0" class="Tips4" title=" REPESADAS :: Modificacion de sus datos de desperdicio"/></a></th><? } ?>
            <? if($reportes_e || $concentrado_rep || $reportes_i || $reportes_b || $reportes_emp || $historial){?>
             <th scope="col"><a href="admin.php?seccion=5" class="style6"><img src="images/admin_10.jpg"  alt="Reportes" border="0" class="Tips4" title="REPORTES :: Resultados de sus capturas"/></a></th><? } ?>
            <? if($prenomina){?><th scope="col"><a href="admin.php?seccion=34&accion=listar" class="style6"><img src="images/lista.jpg"  alt="PRENOMINA" height="50" border="0" class="Tips4" title="PRENOMINA :: Modifique, o realice movimientos de personal, o simplemente tome asistencia de su personal." /></a></th><? } ?>
            <? if($meta){?><th scope="col"><a href="admin.php?seccion=15&accion=listar&metas_viejas=0" class="style6"><img src="images/admin_08.jpg"  alt="Metas" border="0" class="Tips4" title="METAS :: De de alta metas para su sistema"/></a></th><? } ?>
            <th scope="col"><a href="logout.php" class="style6"><img src="images/admin_14.jpg"  alt="Cerrar Sesion" 	border="0" class="Tips4" title="CERRAR SESION :: salir del sistema"/></a></th>
          </tr>
        </table></th>
      </tr>
      <tr >
        <td colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td >
            	<table cellpadding="0" cellspacing="0" width="80%" class="txt_titulos" align="left" >
                <? include("menu.php") ?>
                </table>            </td>
         </tr>
          <tr>
            <td colspan="2" align="center">
     <?php

		switch($_GET['seccion']){
			case 1:
				include("maquinas.php");	break;
			case 2:
				include("extruder.php");	break;
			case 3:
				include("bolseo2.php");		break;
			case 4:
				include("revisa.php");		break;
			case 5:
				include("reportes_menu.php");	break;	
			case 6:
				include("autorizar.php");	break;
			case 7:
				include("actualiza_desper.php");	break;
			case 8:
				include("vendedores.php");	break;	
			case 9:
				include("clientes.php");	break;	
			case 13:
				include("supervisor.php");  break;
			case 14:
			    include("operador.php");    break;	
            case 15:
			    include("meta.php");        break;	
            case 20:
			    include("impresion.php");        break;
            case 25:
			    include("tiempos_extruder.php");       break;
            case 26:
			    include("tiempos_impresion.php");       break;	
            case 28:
			    include("tiempos_bolseo.php");       break;	
            case 30:
			    include("reportes.php");       break;
            case 31:
			    include("reportes_web.php");       break;
			case 32:
				include("administrador.php");	break;
			case 33:
				include("diario.php");	break;	
			case 34:
				include("pre_nomina.php");	break;	
			case 35:
				include("paros.php");	break;	
			case 36:
				include("mensajeria.php");	break;	
			case 37:
				include("movimientos.php");	break;	
			case 38:
				include("paros_maquinas.php");	break;	
			case 39:
				include("grap.php");	break;	
			case 40:
				include("reportes_impresion.php");	break;	
			case 41:
				include("reportes_extruder.php");	break;					
			case 42:
				include("reportes_bolseo.php");	break;
			case 43:
				include("reporte_promedios.php");	break;
			case 44:
				include("reporte_total_x_super.php");	break;
			case 45:
				include("reporte_prod_diaria.php");	break;
			case 46:
				include("metas_mensuales.php");	break;
			case 47:
				include("reporte_paros(bolseo).php");	break;
			default:
				include("home_admin.php");  break;
		}
	?>            </td>
            </tr>
        </table></td>
        </tr>
      <tr>
        <td colspan="2"><img src="images/admin_17.jpg" height="38" alt="" id="logo" /></td>
      </tr>
    </table></th>
  </tr>
</table>

<?php if(!empty($redirecciona)) { ?>
<script language="javascript" type="text/javascript">
<!--
window.onload = function(){
	window.location.href = "<?=$ruta?>";
}

// 
-->
</script>
<?php } ?>
<map name="MapAdmin" id="MapAdmin">
	<area shape="circle" coords="675,28,16" href="#"  class="Tips4" title="MENSAJES RAPIDOS :: Envie mensajes rapidos, sin salir de la seccion en que se encuentra." onClick="Abrir_ventana('pop_up.php?id_admin=<?=$_SESSION['id_admin']?>&accion=nuevo');" />
	<area shape="circle" coords="642,28,15" href="admin.php" class="Tips4" title="CENTRO DE ACTIVIDADES :: Regrese al centro de actividades diarias."  /></map>
</body>
</html>

