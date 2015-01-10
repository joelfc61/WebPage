<?
session_start();
include "conectar.php";


    $tipo_usuario=0;


	if($tipo_usuario==0){  //Verifica si el usuario es un supervisor
	 $sSQL4="SELECT * FROM supervisor WHERE usuario='".@$_SESSION['user']."' AND password='".@$_SESSION['pass']."'";
     $resultado=mysql_query($sSQL4);
	 $nResultado	=	mysql_num_rows($resultado);
	 if($nResultado > 0){
	  if($dato=mysql_fetch_assoc($resultado)){
	
	   $tipo_usuario=1;
       $_SESSION['id_supervisor'] = $dato['id_supervisor'];
	   $_SESSION['rol'] 	= $dato['rol'];
	   $_SESSION['area'] 	= $dato['area'];
	   $_SESSION['area2'] 	= $dato['area2'];
	   $_SESSION['area3'] 	= $dato['area3'];
	   $_SESSION['nombre'] 	= $dato['nombre'];
	 
	   if($_SESSION['rol'] <= 4 || $_SESSION['rol'] >= 1 )
	    header("Location:admin_supervisor.php"); 
	 
	   }
	  }
	}

	
	if($tipo_usuario==0){ //verifica si el usuario es un vendedor
	 $sSQL2="SELECT * FROM vendedores WHERE user='".@$_SESSION['user']."' AND pass='".@$_SESSION['pass']."'";
       $resultado2=mysql_query($sSQL2);
	 $nResultado2	=	mysql_num_rows($resultado2);
	 if($nResultado2 > 0){
	  if($dato2=mysql_fetch_assoc($resultado2)){
	   $tipo_usuario=1;
	   $_SESSION['id_vendedor'] 	= $dato2['id_vendedor'];
 	   $_SESSION['nombre'] 		= $dato2['nombre'];
	   header("Location:admin_vendedores.php?id=".$_SESSION['id_vendedor'].""); 
	  }
	 }
	}
	

	if($tipo_usuario==0){   // checa en la tabla administrador y en permisos
	 $sSQL3="SELECT * FROM administrador INNER JOIN permisos ON administrador.id_admin = permisos.id_usuario WHERE user='".@$_SESSION['user']."' AND pass='".@$_SESSION['pass']."'";
       $resultado3=mysql_query($sSQL3);
	 if($dato3=mysql_fetch_assoc($resultado3)){	
 	  $tipo_usuario=2;
	  $_SESSION['id_admin'] 		= 	$dato3['id_admin'];
	  $_SESSION['nombre_admin'] 		= 	$dato3['nombre'];
	  $colspan	=	0;
	  $colspan3	=   0;
	  if($dato3['maquinaria'] 	== 1){
         $maquinaria		=	$dato3['maquinaria'];	
         $colspan 	=	$colspan + 1; }
	   if($dato3['empleados'] 	== 1){ 	
          $empleados 	=	$dato3['empleados'];	
          $colspan 	=	$colspan + 1; }
          if($dato3['empleados_a'] 	== 1){ 	
           $agregar_empleado		=	$dato3['empleados_a']; 	
          }
	    if($dato3['empleados_am'] 	== 1){ 	
           $maquinas_asignadas		=	$dato3['empleados_am']; 
          }
	    if($dato3['empleados_e'] 	== 1){ 	
           $eliminar_emp			=	$dato3['empleados_e']; 	
          }
	    if($dato3['empleados_m'] 	== 1){ 	
                  $modificar_emp			=	$dato3['empleados_m'];	
          }
	 
	    if($dato3['supervisores'] 	== 1){ 	
                  $supervisores	=	$dato3['supervisores'];	
                  $colspan 	=	$colspan + 1; 
          }
	    if($dato3['usuarios'] 		== 1){ 	
                 $usuarios		=	$dato3['usuarios'];
      	      $colspan 	=	$colspan + 1; 
          }
	    if($dato3['usuarios_pass'] 	== 1){ 	$usuarios_pass	=	$dato3['usuarios_pass']; }
	    if($dato3['usuarios_permisos'] == 1){ 	$usuarios_permisos	=	$dato3['usuarios_permisos']; }
	    if($dato3['usuarios_a'] 		== 1){ 	$usuarios_a		=	$dato3['usuarios_a']; }
	    if($dato3['usuarios_m'] 		== 1){ 	$usuarios_m		=	$dato3['usuarios_m']; }
	    if($dato3['usuarios_e'] 		== 1){ 	$usuarios_e		=	$dato3['usuarios_e']; }
		
	    if($dato3['extruder'] 		== 1){ 	$areaExtruder	=	$dato3['extruder'];		$colspan 	=	$colspan + 1; }
	    if($dato3['impresion'] 	== 1){ 	$areaImpresion	=	$dato3['impresion'];	$colspan 	=	$colspan + 1; }
	    if($dato3['bolseo'] 		== 1){ 	$areaBolseo		=	$dato3['bolseo'];		$colspan 	=	$colspan + 1; }
	    if($dato3['autorizacion'] 	== 1){ 	$autorizacion	=	$dato3['autorizacion'];	$colspan 	=	$colspan + 1; }
	 
	    if($dato3['prenomina'] 	== 1){ 	$prenomina		=	$dato3['prenomina'];	$colspan 	=	$colspan + 1; }
	    if($dato3['prenomina_m'] 	== 1) 			$prenomina_m			=	$dato3['prenomina_m'];
	    if($dato3['prenomina_ext'] 	== 1) 		$prenominaExt			=	$dato3['prenomina_ext'];	
	    if($dato3['prenomina_impr'] 	== 1) 		$prenominaImpr			=	$dato3['prenomina_impr'];	
	    if($dato3['prenomina_bol'] 	== 1) 		$prenominaBol			=	$dato3['prenomina_bol'];	
	    if($dato3['prenomina_mtto'] 	== 1) 		$prenominaMtto			=	$dato3['prenomina_mtto'];	
	    if($dato3['prenomina_mttob'] 	== 1) 		$prenominaMttob			=	$dato3['prenomina_mttob'];	
	    if($dato3['prenomina_emp'] 	== 1) 		$prenominaEmp			=	$dato3['prenomina_emp'];	
	    if($dato3['prenomina_empb'] 	== 1) 		$prenominaEmpb			=	$dato3['prenomina_empb'];	
	    if($dato3['prenomina_alm'] 	== 1) 		$prenominaAlm			=	$dato3['prenomina_alm'];	
	    if($dato3['prenomina_almb'] 	== 1) 		$prenominaAlmb			=	$dato3['prenomina_almb'];	
	    if($dato3['prenomina_re_mo'] 	== 1) 		$prenominaReMo			=	$dato3['prenomina_re_mo'];
	    if($dato3['prenomina_autoriza'] 		== 1) 	$prenominaAutoriza			=	$dato3['prenomina_autoriza'];
	    if($dato3['prenomina_pre_autoriza'] 	== 1) 	$prenominaPre_Autoriza		=	$dato3['prenomina_pre_autoriza'];
	    if($dato3['autoriza_movimiento'] 		== 1) 	$autoriza_movimiento		=	$dato3['autoriza_movimiento'];
	    if($dato3['prenomina_mo'] 				== 1) 	$modificar_movimientos		=	$dato3['prenomina_mo'];
	    if($dato3['prenomina_semana'] 			== 1) 	$prenomina_semana			=	$dato3['prenomina_semana'];
	    if($dato3['movimientos_produccion'] 	== 1) 	$movimientos_produccion		=	$dato3['movimientos_produccion'];
	    if($dato3['movimientos_almacen'] 		== 1) 	$movimientos_almacen		=	$dato3['movimientos_almacen'];
	    if($dato3['historial_m'] 				== 1) 	$historialMod		=	$dato3['historial_m'];
				 
				 		
	    if($dato3['meta']	 		== 1){ 	$meta			=	$dato3['meta'];			$colspan 	=	$colspan + 1; }
	    if($dato3['repesadas'] 	== 1){ 	$repesadas		=	$dato3['repesadas'];	$colspan 	=	$colspan + 1; }
	    if($dato3['pendientes'] 	== 1){ 	$pendientes		=	$dato3['pendientes'];	$colspan3 	=	$colspan3 + 1;}
	 
	    if($dato3['reportes_extruder'] == 1){ 	$reportes_e		=	$dato3['reportes_extruder'];	 }
	    if($dato3['extruder_d'] == 1){ 	$extruder_d		=	$dato3['extruder_d'];	 }
	    if($dato3['extruder_ma'] == 1){ 	$extruder_m		=	$dato3['extruder_ma'];	 }
	    if($dato3['extruder_s'] == 1){ 	$extruder_s		=	$dato3['extruder_s'];	 }
	    if($dato3['extruder_i'] == 1){ 	$extruder_i		=	$dato3['extruder_i'];	 }
	    if($dato3['extruder_mh'] == 1){ 	$extruder_mh	=	$dato3['extruder_mh'];	 }
	 
	    if($dato3['reportes_impr'] 	== 1){ 	$reportes_i		=	$dato3['reportes_impr'];	 }
	    if($dato3['impresion_d'] 	== 1){ 	$impresion_d		=	$dato3['impresion_d'];	 }
	    if($dato3['impresion_m'] 	== 1){ 	$impresion_m		=	$dato3['impresion_m'];	 }
	    if($dato3['impresion_s'] 	== 1){ 	$impresion_s		=	$dato3['impresion_s'];	 }
	    if($dato3['impresion_i'] 	== 1){ 	$impresion_i		=	$dato3['impresion_i'];	 }
	    if($dato3['impresion_ci'] 	== 1){ 	$impresion_ci		=	$dato3['impresion_ci'];	 }
	 
	    if($dato3['reportes_bolseo'] 	== 1){ 	$reportes_b		=	$dato3['reportes_bolseo'];	 }
	    if($dato3['bolseo_config'] 	== 1){ 	$configuracion		=	$dato3['bolseo_config'];	 }
	    if($dato3['bolseo_d'] 			== 1){ 	$bolseo_d			=	$dato3['bolseo_d'];	 }
	    if($dato3['bolseo_m'] 			== 1){ 	$bolseo_m			=	$dato3['bolseo_m'];	 }
	    if($dato3['bolseo_s'] 			== 1){ 	$bolseo_s			=	$dato3['bolseo_s'];	 }
	    if($dato3['bolseo_i'] 			== 1){ 	$bolseo_i			=	$dato3['bolseo_i'];	 }
	 
	    if($dato3['historial'] == 1){ 	$historial		=	$dato3['historial'];	 }
	    if($dato3['historial_v'] == 1){ 	$historial_v		=	$dato3['historial_v'];	 }
	    if($dato3['historial_m'] == 1){ 	$historial_m		=	$dato3['historial_m'];	 }
	 
	    if($dato3['concentrado_rep'] 	== 1){ 	$concentrado_rep		=	$dato3['concentrado_rep']; $colspan 	=	$colspan + 1;	}
	    if($dato3['concentrado_pd'] 		== 1){ 	$concentrado_pd		=	$dato3['concentrado_pd'];	}
	    if($dato3['concentrado_re'] 		== 1){ 	$concentrado_re		=	$dato3['concentrado_re'];	}
	    if($dato3['concentrado_ot'] 		== 1){ 	$concentrado_ot		=	$dato3['concentrado_ot'];	}
	    if($dato3['concentrado_ot_mod'] 	== 1){ 	$concentrado_ot_mod	=	$dato3['concentrado_ot_mod'];	}		 	
	    if($dato3['concentrado_mp'] 		== 1){ 	$concentrado_mp		=	$dato3['concentrado_mp'];	}
	    if($dato3['concentrado_pm'] 		== 1){ 	$concentrado_pm		=	$dato3['concentrado_pm'];	}
	    if($dato3['concentrado_rd'] 		== 1){ 	$concentrado_rd		=	$dato3['concentrado_rd'];	}
	    if($dato3['concentrado_dm'] 		== 1){ 	$concentrado_dm		=	$dato3['concentrado_dm'];	}
	    if($dato3['concentrado_ccm'] 		== 1){ 	$concentrado_ccm	=	$dato3['concentrado_ccm'];	}
	    if($dato3['concentrado_pegt'] 		== 1){ 	$concentrado_pegt	=	$dato3['concentrado_pegt'];	}
	    if($dato3['concentrado_bmp'] 		== 1){ 	$concentrado_bmp	=	$dato3['concentrado_bmp'];	}
	    if($dato3['concentrado_khpt'] 		== 1){ 	$concentrado_khpt	=	$dato3['concentrado_khpt'];	}
	    if($dato3['concentrado_rpr'] 		== 1){ 	$concentrado_rpr	=	$dato3['concentrado_rpr'];	}

	    $colspan2	=	14 - $colspan;	 	
	 
	 }
	}

	if($tipo_usuario==0){
		@session_unset();
		@session_destroy();
		header("Location:index.php?msg=1"); 
		}
	
?>