<?php
	/*
	EL SCRIPT ELIMINA LOS REGISTROS DE LA BASE DE DATOS DE UN AÑO ESPECIFICO HACIA ATRÁS
	REALZIADO POR: ING. ARTURO SÁNCHEZ PEÑA
	*/
	define("SERVER","localhost");
	define("USER","dolfra_dolfra");
	define("PASSWORD","wizapoll");
	define("BD","dolfra_dolfra");
	$db_link = mysql_connect(SERVER, USER, PASSWORD) OR die("Error de conexión: " . mysql_error());
	mysql_select_db(BD,$db_link);
	
	$ANIO = 2009;//Se borran los registros de éste año hacia atrás, inclusive!!!
	
	//TIEMPOS MUERTOS
		/* EXTRUDER */
		$qryE = "DELETE m 
				 FROM entrada_general e
					  INNER JOIN orden_produccion p ON e.id_entrada_general = p.id_entrada_general
					  LEFT JOIN tiempos_muertos m ON p.id_orden_produccion = m.id_produccion
				 WHERE e.impresion = '0'
				 AND m.Tipo = '1'
				 AND YEAR(e.fecha) <= '$ANIO'";
		$rstE = mysql_query($qryE);
		echo mysql_affected_rows()." registros eliminados en la tabla de 'tiempos_muertos' - EXTRUDER<br/>";
		
		/* IMPRESION */
		$qryI = "DELETE m 
				 FROM entrada_general e
					  INNER JOIN impresion i ON e.id_entrada_general = i.id_entrada_general
					  LEFT JOIN tiempos_muertos m ON i.id_impresion = m.id_produccion
				 WHERE e.impresion = '1'
				 AND (m.tipo = '2' OR m.tipo = '3')
				 AND YEAR(e.fecha) <= '$ANIO'";
		$rstI = mysql_query($qryI);
		echo mysql_affected_rows()." registros eliminados en la tabla de 'tiempos_muertos' - IMPRESION<br/>";
		
		/* BOLSEO */
		$qryB = "DELETE m 
				 FROM bolseo b LEFT JOIN tiempos_muertos m ON b.id_bolseo = m.id_produccion
				 WHERE m.Tipo = '4'
				 AND YEAR(b.fecha) <= '$ANIO'";
		$rstB = mysql_query($qryB);
		echo mysql_affected_rows()." registros eliminados en la tabla de 'tiempos_muertos' - BOLSEO<br/>";
	
	//MAQUINAS DE EXTRUDER
	$qry2 = "DELETE d
			 FROM resumen_maquina_ex r, detalle_resumen_maquina_ex d, orden_produccion p, entrada_general e
			 WHERE r.id_resumen_maquina_ex = d.id_resumen_maquina_ex
			 AND r.id_orden_produccion = p.id_orden_produccion
			 AND p.id_entrada_general = e.id_entrada_general
			 AND e.impresion = '0'
			 AND YEAR(e.fecha) <= '$ANIO'";
	$rst2 = mysql_query($qry2);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'detalle_resumen_maquina_EX'<br/>";
	
	$qry3 = "DELETE r
			 FROM resumen_maquina_ex r, orden_produccion p, entrada_general e
			 WHERE r.id_orden_produccion = p.id_orden_produccion
			 AND p.id_entrada_general = e.id_entrada_general
			 AND e.impresion = '0'
			 AND YEAR(e.fecha) <= '$ANIO'";
	$rst3 = mysql_query($qry3);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'resumen_maquina_EX'<br/>";
	
	$qry12 = "DELETE p
			 FROM orden_produccion p, entrada_general e
			 WHERE p.id_entrada_general = e.id_entrada_general
			 AND e.impresion = '0'
			 AND YEAR(e.fecha) <= '$ANIO'";
	$rst12 = mysql_query($qry12);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'orden_produccion'<br/>";
	
	//MAQUINAS DE IMPRESION
	$qry4 = "DELETE d
			 FROM resumen_maquina_im r, detalle_resumen_maquina_im d, impresion p, entrada_general e
			 WHERE r.id_resumen_maquina_im = d.id_resumen_maquina_im
			 AND r.id_impresion = p.id_impresion
			 AND p.id_entrada_general = e.id_entrada_general
			 AND e.impresion = '1'
			 AND YEAR(e.fecha) <= '$ANIO'";
	$rst4 = mysql_query($qry4);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'detalle_resumen_maquina_IM'<br/>";	
	
	$qry5 = "DELETE r
			 FROM resumen_maquina_im r, impresion p, entrada_general e
			 WHERE r.id_impresion = p.id_impresion
			 AND p.id_entrada_general = e.id_entrada_general
			 AND e.impresion = '1'
			 AND YEAR(e.fecha) <= '$ANIO'";
	$rst5 = mysql_query($qry5);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'resumen_maquina_IM'<br/>";
	
	$qry6 = "DELETE p
			 FROM impresion p, entrada_general e
			 WHERE p.id_entrada_general = e.id_entrada_general
			 AND e.impresion = '1'
			 AND YEAR(e.fecha) <= '$ANIO'";
	$rst6 = mysql_query($qry6);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'impresion'<br/>";
	
	//REPESADAS
		/*EXTRUDER*/
		$qry9 = "DELETE r
				 FROM repesadas r, entrada_general e
				 WHERE r.id_entrada_general = e.id_entrada_general
				 AND r.bolseo = '0'
				 AND e.repesada = '1'
				 AND e.impresion = '0'
				 AND YEAR(e.fecha) <= '$ANIO'";
		$rst9 = mysql_query($qry9);
		echo mysql_affected_rows()." registros eliminados en la tabla de 'repesadas' - EXTRUDER<br/>";
		
		/*IMPRESION*/
		$qry10 = "DELETE r
				 FROM repesadas r, entrada_general e
				 WHERE r.id_entrada_general = e.id_entrada_general
				 AND r.bolseo = '0'
				 AND e.repesada = '1'
				 AND e.impresion = '1'
				 AND YEAR(e.fecha) <= '$ANIO'";
		$rst10 = mysql_query($qry10);
		echo mysql_affected_rows()." registros eliminados en la tabla de 'repesadas' - IMPRESION<br/>";
		
		/*BOLSEO*/
		$qry11 = "DELETE r
				 FROM repesadas r, bolseo b
				 WHERE r.id_entrada_general = b.id_bolseo
				 AND r.bolseo = '1'
				 AND b.repesada = '1'
				 AND YEAR(b.fecha) <= '$ANIO'";
		$rst11 = mysql_query($qry11);
		echo mysql_affected_rows()." registros eliminados en la tabla de 'repesadas' - BOLSEO<br/>";
	
	//MAQUINAS DE BOLSEO
	$qry7 = "DELETE d
			 FROM resumen_maquina_bs r, detalle_resumen_maquina_bs d, bolseo b
			 WHERE r.id_resumen_maquina_bs = d.id_resumen_maquina_bs
			 AND r.id_bolseo = b.id_bolseo
			 AND YEAR(b.fecha) <= '$ANIO'";
	$rst7 = mysql_query($qry7);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'detalle_resumen_maquina_BS'<br/>";
	
	$qry8 = "DELETE r
			 FROM resumen_maquina_bs r, bolseo b
			 WHERE r.id_bolseo = b.id_bolseo
			 AND YEAR(b.fecha) <= '$ANIO'";
	$rst8 = mysql_query($qry8);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'resumen_maquina_BS'<br/>";	
	
	$qry13 = "DELETE FROM bolseo
			 WHERE YEAR(fecha) <= '$ANIO'";
	$rst13 = mysql_query($qry13);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'bolseo'<br/>";
	
	//ENTRADA GENERAL!!!!!!!
	$qry14 = "DELETE FROM entrada_general
			 WHERE YEAR(fecha) <= '$ANIO'";
	$rst14 = mysql_query($qry14);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'entrada_general'<br/>";
	
	//ASISTENCIAS
	$qry15 = "DELETE FROM asistencias
			 WHERE YEAR(fecha) <= '$ANIO'";
	$rst15 = mysql_query($qry15);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'asistencias'<br/>";
	
	//MOVIMIENTOS
	$qry16 = "DELETE FROM movimientos
			 WHERE YEAR(fecha) <= '$ANIO'";
	$rst16 = mysql_query($qry16);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'movimientos'<br/>";
	
	//PRE-NOMINA
	$qry17 = "DELETE FROM pre_nomina_calificada
			 WHERE YEAR(hasta) <= '$ANIO'";
	$rst17 = mysql_query($qry17);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'pre_nomina_calificada'<br/>";
	
	//MENSAJERIA
	$qry18 = "DELETE FROM mensajeria
			 WHERE YEAR(fecha) <= '$ANIO'";
	$rst18 = mysql_query($qry18);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'mensajeria'<br/>";
	
	//PAROS MAQUINAS
	$qry19 = "DELETE FROM paros_maquinas
			 WHERE YEAR(fecha) <= '$ANIO'";
	$rst19 = mysql_query($qry19);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'paros_maquinas'<br/>";

	//METAS	
	$qry20 = "DELETE m2
			 FROM metas_maquinas m2, meta m
			 WHERE m2.id_meta = m.id_meta
			 AND m.ano <= '$ANIO'";
	$rst20 = mysql_query($qry20);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'metas_maquinas'<br/>";	
	
	$qry21 = "DELETE FROM meta
			 WHERE ano <= '$ANIO'";
	$rst21 = mysql_query($qry21);
	echo mysql_affected_rows()." registros eliminados en la tabla de 'metas'<br/>";
	
?>