
<div class="txt_titulos"   id="logo">
        
            <? if(!isset($_REQUEST['seccion'])){ ?>CENTRO DE ACTIVIDADES DIARIAS<? } ?>
            <? if($_REQUEST['seccion'] == 1) { ?>ALTA DE MAQUINAS<? } ?>
            <? if($_REQUEST['seccion'] == 2) { ?>REPORTES EXTRUDER<? } ?>
            <? if($_REQUEST['seccion'] == 3) { ?>REPORTES BOLSEO<? } ?>
            <? if($_REQUEST['seccion'] == 5) { ?>REPORTES DEL SISTEMA  <? } ?>
            <? if($_REQUEST['seccion'] == 6 && $_REQUEST['accion'] == 'listar') { ?>AUTORIZACI&Oacute;N DE REPORTES<? } ?>
            <? if($_REQUEST['seccion'] == 6 && (isset($_REQUEST['pendiente']) || $_REQUEST['accion'] == 'listarPendientes' )) { ?>REPORTES SIN AUTORIZAR NI REPESAR<? } ?>
            <? if($_REQUEST['seccion'] == 7 && !isset($_REQUEST['accion'])) { ?>REPESADAS<? } ?>	
            <? if($_REQUEST['seccion'] == 7 && $_REQUEST['accion'] == 'ver') { ?>REPORTE REPESADA<? } ?>	
            <? if($_REQUEST['seccion'] == 8) { ?>ALTA DE VENDEDORES<? } ?>
            <? if($_REQUEST['seccion'] == 13) { ?>ALTA DE SUPERVISORES<? } ?>
            <? if($_REQUEST['seccion'] == 14) { ?>ALTA DE OPERADORES<? } ?>
            <? if($_REQUEST['seccion'] == 15) { ?>METAS DE PRODUCCI&Oacute;N<? } ?>

            <? if($_REQUEST['seccion'] == 25 && $_REQUEST['accion'] == 'supervisor') { ?>REPORTES EXTRUDER<? } ?>
            <? if($_REQUEST['seccion'] == 25 && $_REQUEST['accion'] == 'nuevo') { ?>INCIDENCIAS EXTRUDER<? } ?>
            <? if($_REQUEST['seccion'] == 28 && $_REQUEST['accion'] == 'supervisor') { ?>REPORTES BOLSEO<? } ?>
            <? if($_REQUEST['seccion'] == 28 && $_REQUEST['accion'] == 'nuevo') { ?>INCIDENCIAS BOLSEO<? } ?>
            <? if($_REQUEST['seccion'] == 29 && $_REQUEST['accion'] == 'supervisor') { ?>REPORTES RPS y SF<? } ?>
		<? if($_REQUEST['seccion'] == 26 && $_REQUEST['accion'] == 'nuevo') { ?>INCIDENCIAS DE IMPRESI&Oacute;N<? } ?>
            <? if($_REQUEST['seccion'] == 26 && $_REQUEST['accion'] == 'supervisor') { ?>REPORTES DE IMPRESI&Oacute;N<? } ?>
            <? if($_REQUEST['seccion'] == 30) { ?>HISTORIAL DE PRODUCCION (REPORTES)<? } ?>
			<? if($_REQUEST['seccion'] == 32) { ?>ALTA DE USUARIOS<? } ?>
           <? if($_REQUEST['seccion'] == 34) { ?>ASISTENCIA
		   				<?  if($_REQUEST['area'] == 1){ ?>&nbsp;EXTRUDER
						<? } if($_REQUEST['area'] == 2){ ?>&nbsp;BOLSEO
						<? } if($_REQUEST['area'] == 3){ ?>&nbsp;IMPRESION
						<? } if($_REQUEST['area'] == 4){ ?>&nbsp;MANTENIMIENTO
						<? } if($_REQUEST['area'] == 5){ ?>&nbsp;EMPAQUE
						<? } if($_REQUEST['area'] == 6){ ?>&nbsp;R.P.S.
						<? } if($_REQUEST['area'] == 7){ ?>&nbsp;S.F.
						<? } if($_REQUEST['area'] == 8){ ?>&nbsp;ALMACEN
						<? } if($_REQUEST['area'] == 9){ ?>&nbsp;CALIDAD
						<? } }?>
            <? if($_REQUEST['seccion'] == 36) { ?>MENSAJERIA INSTANT&Aacute;NEA<? } ?>
            <? if($_REQUEST['seccion'] == 37) { ?>MOVIMIENTO DE PERSONAL<? } ?>
            <? if($_REQUEST['seccion'] == 38) { ?>PAROS POR MAQUINA<? } ?>
           
</div>
<div class="txt_subs" style="padding:4px 40px 4px">
      
            <?
            //switch(date('w')){
             // case 0: $dia="Domingo";break;
              //case 1: $dia="Lunes";break;
              //case 2: $dia="Martes";break;
              //case 3: $dia="Miercoles";break;
              //case 4: $dia="Jueves";break;
              //case 5: $dia="Viernes";break;
              //case 6: $dia="Sabado";break;
            //} 
             if(!isset($_REQUEST['seccion'])){ ?><?=date('d')?> de <?=$mes[intval(date('m'))]?> de <?=date('Y')?></span><? } ?>
			<? if($_REQUEST['seccion'] == 1) { ?>Administre su maquinaria:<? } ?>
            <? if($_REQUEST['seccion'] == 2) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 3) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 5 && $_REQUEST['id_reporte'] == 1) { ?>REPORTES DE PRODUCCION EXTRUDER<? } ?>
            <? if($_REQUEST['seccion'] == 5 && $_REQUEST['id_reporte'] == 2) { ?>REPORTES DE PRODUCCION IMPRESION<? } ?>
            <? if($_REQUEST['seccion'] == 5 && $_REQUEST['id_reporte'] == 3) { ?>REPORTES DE PRODUCCION BOLSEO<? } ?>
            <? if($_REQUEST['seccion'] == 5 && $_REQUEST['id_reporte'] == 4) { ?>REPORTES DE PRODUCCION EMPAQUE<? } ?>
            <? if($_REQUEST['seccion'] == 5 && $_REQUEST['id_reporte'] == 5) { ?>CONCENTRADO DE REPORTES<? } ?>
            <? if($_REQUEST['seccion'] == 6) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 7) { ?><? } ?>	
            <? if($_REQUEST['seccion'] == 8) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 13) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 14) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 15) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 20) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 30) { ?>Buscador de reportes.<? } ?>
		<? if($_REQUEST['seccion'] == 32) { ?><? } ?>
            <? if($_REQUEST['seccion'] == 36 && $_REQUEST['accion'] == "reenviar") { ?>REENVIO DE MENSAJE<? } ?>
            <? if($_REQUEST['seccion'] == 36 && $_REQUEST['accion'] == "listarEnviados") { ?>BANDEJA DE SALIDA<? } ?>
            <? if($_REQUEST['seccion'] == 36 && $_REQUEST['accion'] == "listarRecibidos") { ?>BANDEJA DE ENTRADA<? } ?>
            <? if($_REQUEST['seccion'] == 36 && $_REQUEST['accion'] == "nuevo") { ?>MENSAJE NUEVO<? } ?>
            <? if($_REQUEST['seccion'] == 38 && !isset($_REQUEST['accion'])) { ?>SELECCIONE UNA MAQUINA<? } ?>
</div>