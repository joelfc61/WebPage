          <tr>
            <td height="40" colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="txt_titulos" align="left">
            <? if(!isset($_REQUEST['seccion'])){ ?>CENTRO DE ACTIVIDADES DIARIAS<? } ?>
            <? if($_REQUEST['seccion'] == 1) { ?>ALTA DE MAQUINAS<? } ?>
            <? if($_REQUEST['seccion'] == 2) { ?>REPORTES EXTRUDER<? } ?>
            <? if($_REQUEST['seccion'] == 3) { ?>REPORTES BOLSEO<? } ?>
            <? if($_REQUEST['seccion'] == 4) { ?>CALENDARIO<? } ?>
            <? if($_REQUEST['seccion'] == 5) { ?>REPORTEO DE PRODUCCION <? } ?>
            <? if($_REQUEST['seccion'] == 21) { ?>REPORTES IMPRESI&Oacute;N<? } ?>
            <? if($_REQUEST['seccion'] == 7) { ?>REPESADAS<? } ?>
            <? if($_REQUEST['seccion'] == 8) { ?>ALTA DE VENDEDORES<? } ?>
            <? if($_REQUEST['seccion'] == 13) { ?>ALTA DE SUPERVISORES<? } ?>
            <? if($_REQUEST['seccion'] == 14) { ?>ALTA DE OPERADORES<? } ?>
            <? if($_REQUEST['seccion'] == 15) { ?>METAS DE PRODUCCI&Oacute;N<? } ?>
            <? if($_REQUEST['seccion'] == 23) { ?>ALTA DE INCIDENCIAS EXTRUDER<? } ?>
            <? if($_REQUEST['seccion'] == 27) { ?>ALTA DE INCIDENCIAS IMPRESI&Oacute;N<? } ?>
            <? if($_REQUEST['seccion'] == 29) { ?>ALTA DE INCIDENCIAS BOLSEO<? } ?>            <? if($_REQUEST['seccion'] == 31 && isset($_REQUEST['extruder'])) { ?>EDICI&Oacute;N DE REPORTES EXTRUDER<? } ?>
            <? if($_REQUEST['seccion'] == 31 && isset($_REQUEST['impresion'])) { ?>EDICI&Oacute;N DE REPORTES IMPRESI&Oacute;N<? } ?>
            <? if($_REQUEST['seccion'] == 31 && isset($_REQUEST['bolseo'])) { ?>EDICI&Oacute;N DE REPORTES BOLSEO<? } ?>
            <? if($_REQUEST['seccion'] == 32) { ?>REPORTES PARA SUPERVISOR<? } ?>
            <? if($_REQUEST['seccion'] == 33) { ?>ASISTENCIA<? } if($_REQUEST['area'] == 1){ ?>&nbsp;EXTRUDER<? } if($_REQUEST['area'] == 2){ ?>&nbsp;BOLSEO<? } if($_REQUEST['area'] == 3){ ?>&nbsp;IMPRESION<? }?>
            </td>
          </tr>
          <tr>
            <td width="50">&nbsp;</td>
            <td align="left" valign="top" class="txt_subs">
                        <? if(!isset($_REQUEST['seccion'])){ ?>PENDIENTES:<? } else { ?>
            <? if($_REQUEST['seccion'] == '3' && $_REQUEST['accion'] == 'metas' ){?> Reporte de producci&oacute;n<? } ?>
            <? if($_REQUEST['seccion'] == '3' && $_REQUEST['accion'] == 'metas' ){?> Reporte de producci&oacute;n<? } ?>
            <? }?>
            </td>
          </tr>          
             <tr>
            <td colspan="2" height="10">&nbsp;</td>
          </tr>  