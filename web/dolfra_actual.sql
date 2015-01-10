-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 28-06-2007 a las 15:25:32
-- Versión del servidor: 5.0.27
-- Versión de PHP: 5.2.0
-- 
-- Base de datos: `dolfra`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `administrador`
-- 

CREATE TABLE `administrador` (
  `id_admin` int(10) NOT NULL auto_increment,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  PRIMARY KEY  (`id_admin`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Volcar la base de datos para la tabla `administrador`
-- 

INSERT INTO `administrador` VALUES (1, 'admin', 'admin');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `detalle_resumen_maquina_ex`
-- 

CREATE TABLE `detalle_resumen_maquina_ex` (
  `id_detalle_resumen_maquina_ex` int(10) unsigned NOT NULL auto_increment,
  `id_resumen_maquina_ex` int(10) unsigned NOT NULL,
  `orden_trabajo` float NOT NULL,
  `kilogramos` float NOT NULL,
  PRIMARY KEY  (`id_detalle_resumen_maquina_ex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `detalle_resumen_maquina_ex`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `detalle_resumen_maquina_im`
-- 

CREATE TABLE `detalle_resumen_maquina_im` (
  `id_detalle_resumen_maquina_im` int(10) unsigned NOT NULL auto_increment,
  `id_resumen_maquina_im` int(10) unsigned NOT NULL,
  `orden_trabajo` float NOT NULL,
  `kilogramos` float NOT NULL,
  PRIMARY KEY  (`id_detalle_resumen_maquina_im`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `detalle_resumen_maquina_im`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `entrada_general`
-- 

CREATE TABLE `entrada_general` (
  `id_entrada_general` int(10) unsigned NOT NULL auto_increment,
  `id_supervisor` int(10) unsigned NOT NULL,
  `fecha` date NOT NULL,
  `turno` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id_entrada_general`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `entrada_general`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `impresion`
-- 

CREATE TABLE `impresion` (
  `id_impresion` int(10) unsigned NOT NULL auto_increment,
  `id_entrada_general` int(10) unsigned NOT NULL,
  `total_hd` float NOT NULL,
  `total_bd` float NOT NULL,
  `desperdicio_hd` float NOT NULL,
  `desperdicio_bd` float NOT NULL,
  `observaciones` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_impresion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `impresion`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `maquina`
-- 

CREATE TABLE `maquina` (
  `id_maquina` int(10) NOT NULL auto_increment,
  `numero` int(10) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `area` int(10) NOT NULL,
  `lineas` int(10) NOT NULL,
  PRIMARY KEY  (`id_maquina`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- 
-- Volcar la base de datos para la tabla `maquina`
-- 

INSERT INTO `maquina` VALUES (28, 1, 'Epson', 4, 2);
INSERT INTO `maquina` VALUES (29, 2, 'Epson', 3, 2);
INSERT INTO `maquina` VALUES (30, 3, 'Epson', 4, 1);
INSERT INTO `maquina` VALUES (31, 4, 'HP', 4, 1);
INSERT INTO `maquina` VALUES (42, 2, 'eewrt', 4, 1);
INSERT INTO `maquina` VALUES (33, 6, 'Prueba', 4, 1);
INSERT INTO `maquina` VALUES (34, 7, 'Prueba', 3, 1);
INSERT INTO `maquina` VALUES (35, 8, 'epson', 3, 1);
INSERT INTO `maquina` VALUES (36, 9, 'sdfasd', 4, 1);
INSERT INTO `maquina` VALUES (37, 10, 'sdfasdf', 1, 1);
INSERT INTO `maquina` VALUES (38, 11, 'efsfad', 4, 1);
INSERT INTO `maquina` VALUES (39, 1, 'gdfsgo', 2, 1);
INSERT INTO `maquina` VALUES (40, 12, 'sagdfg', 4, 2);
INSERT INTO `maquina` VALUES (41, 15, 'x', 2, 1);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `meta`
-- 

CREATE TABLE `meta` (
  `id_meta` int(11) NOT NULL auto_increment,
  `nrmetap` int(11) NOT NULL,
  `nrmetad` int(11) NOT NULL,
  `nrmillaresp` int(11) NOT NULL,
  `nrmillaresd` int(11) NOT NULL,
  `srmes` varchar(20) collate latin1_general_ci NOT NULL,
  `nrano` int(11) NOT NULL,
  PRIMARY KEY  (`id_meta`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=13 ;

-- 
-- Volcar la base de datos para la tabla `meta`
-- 

INSERT INTO `meta` VALUES (1, 10, 20, 30, 40, '3', 2007);
INSERT INTO `meta` VALUES (12, 0, 0, 0, 0, '9', 2008);
INSERT INTO `meta` VALUES (2, 60, 70, 80, 90, '2', 2007);
INSERT INTO `meta` VALUES (4, 0, 0, 0, 0, '1', 2007);
INSERT INTO `meta` VALUES (5, 0, 0, 0, 0, '1', 2007);
INSERT INTO `meta` VALUES (11, 1, 2, 3, 4, '5', 2007);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `oper_maquina`
-- 

CREATE TABLE `oper_maquina` (
  `id_opr_maquina` int(11) NOT NULL auto_increment,
  `id_operador` int(11) NOT NULL,
  `id_maquina` int(11) NOT NULL,
  `rol` int(10) NOT NULL,
  PRIMARY KEY  (`id_opr_maquina`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

-- 
-- Volcar la base de datos para la tabla `oper_maquina`
-- 

INSERT INTO `oper_maquina` VALUES (47, 3, 37, 2);
INSERT INTO `oper_maquina` VALUES (51, 4, 28, 1);
INSERT INTO `oper_maquina` VALUES (46, 2, 39, 2);
INSERT INTO `oper_maquina` VALUES (49, 4, 31, 1);
INSERT INTO `oper_maquina` VALUES (48, 6, 36, 2);
INSERT INTO `oper_maquina` VALUES (54, 1, 28, 2);
INSERT INTO `oper_maquina` VALUES (53, 1, 35, 2);
INSERT INTO `oper_maquina` VALUES (52, 1, 34, 2);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `operadores`
-- 

CREATE TABLE `operadores` (
  `id_operador` int(11) NOT NULL auto_increment,
  `nombre` varchar(50) collate latin1_general_ci NOT NULL,
  `numnomina` varchar(30) collate latin1_general_ci NOT NULL,
  `rol` int(11) NOT NULL,
  PRIMARY KEY  (`id_operador`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

-- 
-- Volcar la base de datos para la tabla `operadores`
-- 

INSERT INTO `operadores` VALUES (6, 'Manuel', 'werwqerlk', 2);
INSERT INTO `operadores` VALUES (7, 'Google', 'rwert', 1);
INSERT INTO `operadores` VALUES (8, 'ajam', 'dsfasf', 3);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `orden_produccion`
-- 

CREATE TABLE `orden_produccion` (
  `id_orden_produccion` int(10) unsigned NOT NULL auto_increment,
  `id_entrada_general` int(10) unsigned NOT NULL,
  `total` float NOT NULL,
  `desperdicio_tira` float NOT NULL,
  `desperdicio_duro` float NOT NULL,
  `observaciones` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_orden_produccion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `orden_produccion`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `resumen_maquina_ex`
-- 

CREATE TABLE `resumen_maquina_ex` (
  `id_resumen_maquina_ex` int(10) unsigned NOT NULL auto_increment,
  `id_orden_produccion` int(10) unsigned NOT NULL,
  `id_maquina` int(10) unsigned NOT NULL,
  `id_operador` int(10) unsigned NOT NULL,
  `subtotal` float NOT NULL,
  PRIMARY KEY  (`id_resumen_maquina_ex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `resumen_maquina_ex`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `resumen_maquina_im`
-- 

CREATE TABLE `resumen_maquina_im` (
  `id_resumen_maquina_im` int(10) unsigned NOT NULL auto_increment,
  `id_impresion` int(10) unsigned NOT NULL,
  `id_maquina` int(10) unsigned NOT NULL,
  `id_operador` int(10) unsigned NOT NULL,
  `linea_impresion` tinyint(3) unsigned NOT NULL,
  `subtotal` float NOT NULL,
  PRIMARY KEY  (`id_resumen_maquina_im`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `resumen_maquina_im`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `supervisor`
-- 

CREATE TABLE `supervisor` (
  `id_supervisor` int(11) NOT NULL auto_increment,
  `nombre` varchar(50) collate latin1_general_ci NOT NULL,
  `usuario` varchar(15) collate latin1_general_ci NOT NULL,
  `password` varchar(15) collate latin1_general_ci NOT NULL,
  `area` int(10) NOT NULL,
  `area2` int(10) NOT NULL,
  `area3` int(10) NOT NULL,
  `numnomina` varchar(30) collate latin1_general_ci NOT NULL,
  `rol` int(10) NOT NULL,
  PRIMARY KEY  (`id_supervisor`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

-- 
-- Volcar la base de datos para la tabla `supervisor`
-- 

INSERT INTO `supervisor` VALUES (1, 'Jorge', 'jor', 'jor', 1, 1, 0, '10', 2);
INSERT INTO `supervisor` VALUES (2, 'menio', 'menio', 'menio', 1, 1, 1, '23424345', 3);
INSERT INTO `supervisor` VALUES (3, 'manuel', 'manuel', 'manuel', 1, 0, 0, '224314', 1);
