<?
include('libs/conectar.php');
function FWhere($w) {
	return ($w == '') ? ' WHERE ' : "$w AND ";
}
		if ($_POST['id_supervisor'] != '') 
			$WHERE = Fwhere($WHERE) . " entrada_general.id_supervisor LIKE '%$_POST[nombre]%'";
		if ($_POST['placas'] != '')
			$WHERE = Fwhere($WHERE) . " moto_c.placas LIKE '%$_POST[placas]%'";
		if ($_POST['id_orden'] != '')
			$WHERE = Fwhere($WHERE) . " linea_de_tiempo.id_orden LIKE '%$_POST[id_orden]%'";
		if ($_POST['status'] != '')
			$WHERE = Fwhere($WHERE) . " linea_de_tiempo.status LIKE '%$_POST[status]%'";
		if ($_POST['fecha'] != ''){ 
			$fecha	= preg_replace( "/^\s*([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/" , "\\3-\\2-\\1" , $_POST['fecha'] );
			$WHERE = Fwhere($WHERE) . " linea_de_tiempo.fecha = '".$fecha."'";
			
			}
			
		if($_POST['area'] == '1')
		$qGenerales		=	"SELECT CONCAT(DAY(fecha),' / ',MONTH(fecha),' / ',YEAR(fecha)), supervisor.nombre, turno,id_entrada_general FROM entrada_general"." INNER JOIN supervisor ON entrada_general.id_supervisor = supervisor.id_supervisor  $WHERE AND impresion = '0'  ORDER BY fecha DESC";
		
				$rGenerales		=	mysql_query($qGenerales) OR die("<p>$qGenerales</p><p>".mysql_error()."</p>");
		?>
<table width="100%" border="0" cellpadding="4" cellspacing="3">
  <tr>
    <td colspan="4" style="color:#666666"><b>
      <?=mysql_num_rows($rLista);?>
    </b>&nbsp; Registros encontrados</td>
  </tr>
  <tr  style="background-color:#FF9900">
    <td width="8%"  align="center" style="color: rgb(255, 255, 255);" ><b>Fecha</b></td>
    <td width="14%"  align="center" style="color: rgb(255, 255, 255);" ><b>Supervisor</b></td>
    <td width="39%"  align="center"  style="color: rgb(255, 255, 255);"><b>Rol</b></td>
    <td width="9%"  align="center" style="color: rgb(255, 255, 255);" ><b>Turno</b></td>
    <td width="17%"  align="center" style="color: rgb(255, 255, 255);" ><b>Status</b></td>
    <td width="13%" >&nbsp;</td>
  </tr>
  <?
while ($listado = mysql_fetch_assoc($rLista)) {
?>
  <tr
  	class="td"
	onmouseover="this.style.backgroundColor='#CCC'"
	onmouseout="this.style.backgroundColor='#EAEAEA'" 
	style="cursor:default; background-color:#EAEAEA"
  >
    <td align="center"><b>
      <?=$listado['id_orden'];?>
    </b></td>
    <td align="center"><b>
      <?=preg_replace( "/^\s*([0-9]{1,4})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,2})/" , "\\3/\\2/\\1" , $listado['fecha']);?>
    </b></td>
    <td><b>
      <?=$listado['nombre'];?>
    </b></td>
    <td align="center"><b>
      <?=$listado['placas'];?>
    </b></td>
    <td align="center">
<? 

if($listado['status'] == 1) echo "ALMACEN";
if($listado['status'] == 2) echo "MECANICO";
if($listado['status'] == 3) echo "<span style='color:#FF0000'>ENTREGADO</span>"; 
if($listado['status'] == 6) echo "CANCELADO"; 
?></td>
    <td align="center">
	<? if($listado['status'] != 3){ ?><a href="pendientes.php?accion=entregar&id_orden=<?=$listado['id_orden']?>" onclick="javascript: return confirm('Usted esta a punto de dar de alta una entrega,\n Desea continuar ?');">ENTREGAR</a><? }?>
 <!---  	<? if($listado['status'] == 3){ ?><a href="facturar.php?accion=entregar&id_orden=<?=$dEstadoActual['id_orden']?>" onClick="javascript: return confirm('Usted esta a punto de mostrar datos para facturación,\n Desea continuar ?');">FACTURAR</a><? } ?> --->
 </td>
  </tr>
  <?
}
mysql_close();
?>
<tr style="background-color:#FF9900"></tr>
</table>
