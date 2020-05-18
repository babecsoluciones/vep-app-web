-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 19-05-2020 a las 00:49:34
-- Versión del servidor: 5.7.23
-- Versión de PHP: 5.6.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sdiba691_vep`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BitPublicaciones`
--

CREATE TABLE `BitPublicaciones` (
  `eCodPublicacion` int(11) NOT NULL,
  `eCodTipoPublicacion` int(11) NOT NULL,
  `eCodUsuario` int(11) NOT NULL,
  `tCodEstatus` char(2) DEFAULT 'AC',
  `fhFecha` datetime NOT NULL,
  `fhFechaActualizacion` datetime NOT NULL,
  `bRequiereProceso` int(11) DEFAULT NULL,
  `tTitulo` text,
  `tContenido` text,
  `tImagen` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `BitPublicaciones`
--

INSERT INTO `BitPublicaciones` (`eCodPublicacion`, `eCodTipoPublicacion`, `eCodUsuario`, `tCodEstatus`, `fhFecha`, `fhFechaActualizacion`, `bRequiereProceso`, `tTitulo`, `tContenido`, `tImagen`) VALUES
(1, 1, 1, 'AC', '2020-05-18 17:37:22', '2020-05-18 17:37:22', 1, 'VGVzdCBkZSBhbG1hY2VuYW1pZW50bw==', 'QWxhbWNlbmFuZG8=', '5ec30e22175b8.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CatEstatus`
--

CREATE TABLE `CatEstatus` (
  `eCodEstatus` int(11) NOT NULL,
  `tCodEstatus` varchar(2) DEFAULT NULL,
  `tNombre` varchar(15) DEFAULT NULL,
  `tIcono` varchar(25) DEFAULT NULL,
  `tColor` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `CatEstatus`
--

INSERT INTO `CatEstatus` (`eCodEstatus`, `tCodEstatus`, `tNombre`, `tIcono`, `tColor`) VALUES
(1, 'NU', 'Nuevo', 'far fa-question-circle', NULL),
(2, 'PR', 'En proceso...', 'fas fa-cogs', '#84eefa'),
(3, 'AC', 'Activo', 'fa fa-check', NULL),
(4, 'CA', 'Cancelado', 'fas fa-ban', NULL),
(5, 'RE', 'Rechazado', 'fas fa-minus-circle', NULL),
(6, 'BL', 'Bloqueado', 'fas fa-lock', NULL),
(7, 'EL', 'Eliminado', 'far fa-trash-alt', NULL),
(8, 'FI', 'Finalizado', 'fas fa-check-double', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CatTiposPublicaciones`
--

CREATE TABLE `CatTiposPublicaciones` (
  `eCodTipoPublicacion` int(11) NOT NULL,
  `tCodEstatus` char(2) NOT NULL,
  `tNombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `CatTiposPublicaciones`
--

INSERT INTO `CatTiposPublicaciones` (`eCodTipoPublicacion`, `tCodEstatus`, `tNombre`) VALUES
(1, 'AC', 'Noticia'),
(2, 'AC', 'Medicamento'),
(3, 'AC', 'Evento'),
(4, 'AC', 'Estudios'),
(5, 'AC', 'Programas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CatTiposSecciones`
--

CREATE TABLE `CatTiposSecciones` (
  `eCodTipoSeccion` int(11) NOT NULL,
  `tCodTipoSeccion` char(3) NOT NULL,
  `tNombre` varchar(50) NOT NULL,
  `tIcono` varchar(50) DEFAULT NULL,
  `ePosicion` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `CatTiposSecciones`
--

INSERT INTO `CatTiposSecciones` (`eCodTipoSeccion`, `tCodTipoSeccion`, `tNombre`, `tIcono`, `ePosicion`) VALUES
(1, 'cat', 'Catalogo', 'fas fa-folder-open', 2),
(2, 'ser', 'Servicio', 'fas fa-list-alt', 3),
(3, 'sis', 'Sistema', 'fas fa-cogs', 5),
(4, 'con', 'Consultas', 'fas fa-search', 4),
(5, 'ini', 'Inicio', 'fas fa-tachometer-alt', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisBotones`
--

CREATE TABLE `SisBotones` (
  `tCodBoton` varchar(2) NOT NULL,
  `tTitulo` varchar(20) DEFAULT NULL,
  `tFuncion` varchar(25) DEFAULT NULL,
  `tAccion` varchar(25) NOT NULL,
  `tIcono` varchar(45) DEFAULT NULL,
  `tId` varchar(15) NOT NULL,
  `tClase` varchar(50) NOT NULL,
  `tHTML` varchar(255) DEFAULT NULL,
  `bDeshabilitado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisBotones`
--

INSERT INTO `SisBotones` (`tCodBoton`, `tTitulo`, `tFuncion`, `tAccion`, `tIcono`, `tId`, `tClase`, `tHTML`, `bDeshabilitado`) VALUES
('CO', 'Listado', 'window.location=\'url\';', '', '<i class=\"fas fa-table\"></i>', '', 'btn btn-primary', NULL, NULL),
('EL', 'Eliminar', 'eliminar();', '', '<i class=\"far fa-trash-alt\"></i>', '', 'btn btn-danger', NULL, NULL),
('GU', 'Guardar', 'validar();', '', '<i class=\"fa fa-floppy-o\"></i>', 'btnGuardar', 'btn btn-primary', NULL, NULL),
('IM', 'Imprimir', 'imprimir();', '', '<i class=\"fas fa-print\"></i>', '', 'btn btn-success', NULL, NULL),
('NU', 'Nuevo', 'window.location=\'url\';', '', '<i class=\"fa fa-plus\"></i>', 'btnNuevo', 'btn btn-primary', NULL, NULL),
('PD', 'Descargar PDF', 'window.location=\'url\';', 'generar/pdf', '<i class=\"fas fa-file-pdf\"></i>', '', 'btn btn-danger', NULL, NULL),
('RE', 'Rechazar', 'rechazar();', '', '<i class=\"far fa-trash-alt\"></i>', '', 'btn btn-danger', NULL, NULL),
('SR', 'Consultar', 'consultarFecha();', '', '<i class=\"fas fa-search\"></i>', '', 'btn btn-info', '<form id=\"Datos\" method=\"post\" action=\"<?=$_SERVER[\'REQUEST_URI\']?>\"><input type=\"text\" id=\"datepicker\"><input type=\"hidden\" name=\"fhFechaConsulta\" id=\"datepicker1\"></form>', NULL),
('VA', NULL, 'activarValidacion();', '', '<i class=\"fa fa-key\" ></i>', 'btnValidar', 'btn btn-primary', '<input type=\"password\" class=\"form-control col-md-3\" onkeyup=\"validarUsuario()\"  id=\"tPasswordOperaciones\"  style=\"display:none;\" size=\"8\">', NULL),
('XL', 'Descargar XLS', 'window.location=\'url\';', 'exportar/xls', '<i class=\"fas fa-file-excel\"></i>', '', 'btn btn-success', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisLogs`
--

CREATE TABLE `SisLogs` (
  `eCodEvento` int(11) NOT NULL,
  `eCodUsuario` int(11) NOT NULL,
  `fhFecha` datetime NOT NULL,
  `tDescripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisLogs`
--

INSERT INTO `SisLogs` (`eCodEvento`, `eCodUsuario`, `fhFecha`, `tDescripcion`) VALUES
(1, 1, '2020-05-18 17:37:22', 'Se ha insertado la publicacion con código 0000001');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisMaximosRegistros`
--

CREATE TABLE `SisMaximosRegistros` (
  `eCodRegistro` int(11) NOT NULL,
  `eRegistros` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisMaximosRegistros`
--

INSERT INTO `SisMaximosRegistros` (`eCodRegistro`, `eRegistros`) VALUES
(1, 10),
(2, 25),
(3, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisPerfiles`
--

CREATE TABLE `SisPerfiles` (
  `eCodPerfil` int(11) NOT NULL,
  `tNombre` varchar(15) DEFAULT NULL,
  `tCodEstatus` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisPerfiles`
--

INSERT INTO `SisPerfiles` (`eCodPerfil`, `tNombre`, `tCodEstatus`) VALUES
(1, 'Administrador', 'AC'),
(2, 'Coordinador', 'AC'),
(3, 'Editor', 'AC'),
(4, 'Afiliado', 'AC');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisSecciones`
--

CREATE TABLE `SisSecciones` (
  `tCodSeccion` varchar(20) NOT NULL,
  `tCodPadre` varchar(20) DEFAULT NULL,
  `tCodTipoSeccion` char(3) NOT NULL,
  `tDirectorio` char(3) NOT NULL,
  `tTitulo` varchar(60) DEFAULT NULL,
  `eCodEstatus` int(11) DEFAULT NULL,
  `ePosicion` int(11) DEFAULT NULL,
  `bFiltro` int(11) NOT NULL,
  `bPublico` int(11) DEFAULT NULL,
  `tIcono` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisSecciones`
--

INSERT INTO `SisSecciones` (`tCodSeccion`, `tCodPadre`, `tCodTipoSeccion`, `tDirectorio`, `tTitulo`, `eCodEstatus`, `ePosicion`, `bFiltro`, `bPublico`, `tIcono`) VALUES
('cata-afi-con', 'sis-dash-con', 'cat', 'afi', 'Afiliados', 3, 3, 1, NULL, 'fa fa-file-text-o'),
('cata-afi-det', 'cata-afi-con', '', 'afi', 'Detalles de Afiliado', 3, 2, 0, 1, 'fa fa-file-text-o'),
('cata-afi-reg', 'cata-afi-con', '', 'afi', '+ Afiliado', 3, 1, 0, NULL, 'fa fa-file-text-o'),
('cata-per-reg', 'cata-per-sis', '', 'sis', '+ Perfiles', 3, 5, 0, NULL, 'fa fa-file-text-o'),
('cata-per-sis', 'sis-dash-con', 'sis', 'sis', 'Perfiles', 3, 6, 0, NULL, 'fa fa-file-text-o'),
('cata-pub-con', 'sis-dash-con', 'con', 'pub', 'Publicaciones', 3, 2, 1, NULL, 'fa fa-file-text-o'),
('cata-pub-det', 'cata-pub-con', '', 'pub', 'Detalles de Publicación', 3, 1, 0, 1, 'fa fa-file-text-o'),
('cata-pub-reg', 'cata-pub-con', 'ser', 'pub', '+ Publicación', 3, 1, 0, NULL, 'fa fa-file-text-o'),
('cata-usr-reg', 'cata-usr-sis', '', 'sis', '+ Usuarios', 3, 5, 0, NULL, 'fa fa-file-text-o'),
('cata-usr-sis', 'sis-dash-con', 'sis', 'sis', 'Usuarios', 3, 5, 0, NULL, 'fa fa-file-text-o'),
('sis-dash-con', NULL, 'ini', 'das', 'Dashboard', 3, 1, 1, NULL, 'fa-tachometer-alt'),
('sis-log-con', 'sis-dash-con', 'sis', 'sis', 'Logs', 3, 15, 0, NULL, 'fa fa-file-text-o');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisSeccionesBotones`
--

CREATE TABLE `SisSeccionesBotones` (
  `eCodRegistro` int(11) NOT NULL,
  `tCodPadre` varchar(15) DEFAULT NULL,
  `tCodSeccion` varchar(15) DEFAULT NULL,
  `tCodBoton` varchar(2) DEFAULT NULL,
  `tFuncion` varchar(25) DEFAULT NULL,
  `tEtiqueta` varchar(30) DEFAULT NULL,
  `ePosicion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisSeccionesBotones`
--

INSERT INTO `SisSeccionesBotones` (`eCodRegistro`, `tCodPadre`, `tCodSeccion`, `tCodBoton`, `tFuncion`, `tEtiqueta`, `ePosicion`) VALUES
(2, 'cata-afi-reg', 'cata-cli-reg', 'GU', NULL, NULL, 2),
(3, 'cata-afi-reg', 'cata-cli-con', 'CO', NULL, NULL, 3),
(8, 'cata-per-reg', 'cata-per-reg', 'GU', 'guardar();', NULL, 2),
(14, 'cata-usr-reg', 'cata-usr-reg', 'GU', NULL, NULL, 2),
(18, 'cata-pub-reg', 'cata-pub-con', 'CO', NULL, NULL, 2),
(22, 'cata-afi-con', 'cata-cli-reg', 'NU', NULL, NULL, 1),
(24, 'cata-pub-con', 'oper-eve-cot', 'NU', NULL, NULL, 1),
(28, 'cata-per-sis', 'cata-per-reg', 'NU', NULL, NULL, 1),
(29, 'cata-usr-sis', 'cata-usr-reg', 'NU', NULL, NULL, 1),
(30, 'cata-afi-det', 'cata-cli-con', 'CO', NULL, NULL, 3),
(31, 'cata-pub-det', 'cata-eve-det', 'PD', NULL, NULL, 1),
(33, 'cata-afi-con', 'cata-cli-reg', 'XL', NULL, NULL, 2),
(34, 'cata-pub-det', 'cata-eve-con', 'CO', NULL, NULL, 3),
(37, 'sis-dash-con', 'oper-eve-cot', 'NU', NULL, 'Nuevo Evento', 1),
(38, 'sis-dash-con', 'oper-ren-cot', 'NU', NULL, 'Nueva Renta', 2),
(51, 'cata-pub-reg', 'cata-pub-reg', 'GU', 'guardar();', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisSeccionesMenusEmergentes`
--

CREATE TABLE `SisSeccionesMenusEmergentes` (
  `eCodMenuEmergente` int(11) NOT NULL,
  `tCodPadre` varchar(20) DEFAULT NULL,
  `tCodSeccion` varchar(20) DEFAULT NULL,
  `tCodPermiso` char(1) NOT NULL,
  `tTitulo` varchar(30) NOT NULL,
  `tAccion` varchar(25) DEFAULT NULL,
  `tFuncion` varchar(50) DEFAULT NULL,
  `tValor` varchar(20) DEFAULT NULL,
  `ePosicion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisSeccionesMenusEmergentes`
--

INSERT INTO `SisSeccionesMenusEmergentes` (`eCodMenuEmergente`, `tCodPadre`, `tCodSeccion`, `tCodPermiso`, `tTitulo`, `tAccion`, `tFuncion`, `tValor`, `ePosicion`) VALUES
(1, 'cata-afi-con', 'cata-afi-det', 'A', 'Detalles', 'detalles', 'window.location=\'url\';', 'detalles', 2),
(6, 'cata-pub-con', 'cata-pub-reg', 'A', 'Editar', 'editar-cotizacion', 'window.location=\'url\';', 'editar-cotizacion', 1),
(7, 'cata-afi-con', 'cata-afi-reg', 'A', 'Editar', 'editar', 'window.location=\'url\';', 'editar', 1),
(8, 'cata-usr-sis', 'cata-usr-reg', 'A', 'Editar', 'editar', 'window.location=\'url\';', 'editar', 1),
(10, 'cata-afi-con', 'cata-afi-con', 'D', 'Eliminar', 'eliminar', 'acciones(codigo,\'D\');', 'eliminar', 3),
(16, 'cata-usr-sis', 'cata-usr-sis', 'D', 'Eliminar', 'eliminar', 'acciones(codigo,\'D\');', 'eliminar', 2),
(25, 'cata-per-sis', 'cata-per-reg', 'A', 'Editar', 'editar', 'window.location=\'url\';', 'editar', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisSeccionesPerfiles`
--

CREATE TABLE `SisSeccionesPerfiles` (
  `eCodPerfil` int(11) DEFAULT NULL,
  `tCodSeccion` varchar(15) DEFAULT NULL,
  `bAll` int(11) DEFAULT NULL,
  `bDelete` int(11) DEFAULT NULL,
  `bWrite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisSeccionesPerfiles`
--

INSERT INTO `SisSeccionesPerfiles` (`eCodPerfil`, `tCodSeccion`, `bAll`, `bDelete`, `bWrite`) VALUES
(1, 'sis-dash-con', 1, 1, NULL),
(1, 'cata-pub-con', 1, 1, NULL),
(1, 'cata-pub-det', 0, 0, NULL),
(1, 'cata-pub-reg', 0, 0, NULL),
(1, 'cata-afi-con', 1, 1, NULL),
(1, 'cata-afi-reg', 0, 0, NULL),
(1, 'cata-afi-det', 0, 0, NULL),
(1, 'cata-usr-sis', 0, 1, NULL),
(1, 'cata-usr-reg', 0, 0, NULL),
(1, 'cata-per-sis', 0, 1, NULL),
(1, 'cata-per-reg', 0, 0, NULL),
(1, 'sis-log-con', 0, 1, NULL),
(3, 'sis-dash-con', 1, 0, NULL),
(3, 'cata-pub-con', 1, 0, NULL),
(3, 'cata-pub-det', 1, 0, NULL),
(3, 'cata-pub-reg', 1, 0, NULL),
(3, 'cata-afi-con', 1, 0, NULL),
(3, 'cata-afi-reg', 1, 0, NULL),
(3, 'cata-afi-det', 1, 0, NULL),
(2, 'sis-dash-con', 1, 1, 1),
(2, 'cata-pub-con', 1, 1, 0),
(2, 'cata-pub-det', 1, 1, 0),
(2, 'cata-pub-reg', 1, 1, 0),
(2, 'cata-afi-con', 1, 1, 0),
(2, 'cata-afi-reg', 1, 1, 0),
(2, 'cata-afi-det', 1, 1, 0),
(2, 'cata-per-sis', 1, 1, 1),
(2, 'sis-log-con', 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisSeccionesPerfilesInicio`
--

CREATE TABLE `SisSeccionesPerfilesInicio` (
  `eCodPerfil` int(11) NOT NULL,
  `tCodSeccion` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `SisSeccionesPerfilesInicio`
--

INSERT INTO `SisSeccionesPerfilesInicio` (`eCodPerfil`, `tCodSeccion`) VALUES
(1, 'sis-dash-con'),
(2, 'sis-dash-con'),
(5, 'sis-bod-con'),
(6, 'sis-bod-con'),
(3, 'sis-dash-con');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisSeccionesReemplazos`
--

CREATE TABLE `SisSeccionesReemplazos` (
  `eCodReemplazo` int(11) NOT NULL,
  `tBase` varchar(4) NOT NULL,
  `tNombre` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisSeccionesReemplazos`
--

INSERT INTO `SisSeccionesReemplazos` (`eCodReemplazo`, `tBase`, `tNombre`) VALUES
(1, 'cata', 'catalogo'),
(2, 'oper', 'operaciones'),
(3, 'reg', 'registrar'),
(4, 'inv', 'inventario'),
(5, 'usr', 'usuario'),
(6, 'sis', 'sistema'),
(7, 'bod', 'bodega'),
(8, 'ser', 'paquetes'),
(9, 'per', 'perfiles'),
(10, 'con', 'consultar'),
(11, 'dash', 'dashboard'),
(12, 'eve', 'eventos'),
(13, 'noti', 'notificaciones'),
(14, 'det', 'detalles'),
(15, 'del', 'eliminar'),
(16, 'log', 'logs'),
(17, 'tra', 'transacciones'),
(18, 'cit', 'citas'),
(19, 'gen', 'generar'),
(20, 'xls', 'excel'),
(21, 'pdf', 'pdf'),
(22, 'ren', 'rentas'),
(23, 'cli', 'clientes'),
(24, 'reg', 'editar'),
(25, 'xls', 'xls'),
(26, 'cot', 'crear'),
(27, 'cot', 'editar-cotizacion'),
(28, 'cam', 'camionetas'),
(29, 'car', 'carga'),
(30, 'cam', 'camionetas'),
(31, 'tiv', 'tipo-inventario'),
(32, 'pub', 'publicacion'),
(33, 'afi', 'afiliado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisUsuarios`
--

CREATE TABLE `SisUsuarios` (
  `eCodUsuario` int(11) NOT NULL,
  `eCodEntidad` int(11) DEFAULT NULL,
  `tNombre` varchar(50) DEFAULT NULL,
  `tApellidos` varchar(50) DEFAULT NULL,
  `tCorreo` varchar(100) DEFAULT NULL,
  `tPasswordAcceso` varchar(60) DEFAULT NULL,
  `tPasswordOperaciones` varchar(60) DEFAULT NULL,
  `fhFechaCreacion` datetime DEFAULT NULL,
  `eCodEstatus` int(11) DEFAULT NULL,
  `eCodPerfil` int(11) DEFAULT NULL,
  `bAll` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisUsuarios`
--

INSERT INTO `SisUsuarios` (`eCodUsuario`, `eCodEntidad`, `tNombre`, `tApellidos`, `tCorreo`, `tPasswordAcceso`, `tPasswordOperaciones`, `fhFechaCreacion`, `eCodEstatus`, `eCodPerfil`, `bAll`) VALUES
(1, NULL, 'Mario Ernesto', 'Basurto Medrano', 'babec.soluciones@gmail.com', 'MjgwMjkx', 'MjgwMjkx', '2018-07-29 00:00:00', 3, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SisVariables`
--

CREATE TABLE `SisVariables` (
  `eCodVariable` int(11) NOT NULL,
  `tNombre` varchar(50) NOT NULL,
  `tValor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SisVariables`
--

INSERT INTO `SisVariables` (`eCodVariable`, `tNombre`, `tValor`) VALUES
(1, 'tURL', 'http://app.vivirenpurpura.mx/');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `BitPublicaciones`
--
ALTER TABLE `BitPublicaciones`
  ADD PRIMARY KEY (`eCodPublicacion`);

--
-- Indices de la tabla `CatTiposPublicaciones`
--
ALTER TABLE `CatTiposPublicaciones`
  ADD PRIMARY KEY (`eCodTipoPublicacion`);

--
-- Indices de la tabla `SisBotones`
--
ALTER TABLE `SisBotones`
  ADD PRIMARY KEY (`tCodBoton`);

--
-- Indices de la tabla `SisLogs`
--
ALTER TABLE `SisLogs`
  ADD PRIMARY KEY (`eCodEvento`);

--
-- Indices de la tabla `SisMaximosRegistros`
--
ALTER TABLE `SisMaximosRegistros`
  ADD PRIMARY KEY (`eCodRegistro`);

--
-- Indices de la tabla `SisPerfiles`
--
ALTER TABLE `SisPerfiles`
  ADD PRIMARY KEY (`eCodPerfil`);

--
-- Indices de la tabla `SisSecciones`
--
ALTER TABLE `SisSecciones`
  ADD PRIMARY KEY (`tCodSeccion`);

--
-- Indices de la tabla `SisSeccionesBotones`
--
ALTER TABLE `SisSeccionesBotones`
  ADD PRIMARY KEY (`eCodRegistro`),
  ADD KEY `tCodPadre_rel_fk_botones_idx` (`tCodPadre`),
  ADD KEY `tCodBoton_rel_fk_botones_idx` (`tCodBoton`);

--
-- Indices de la tabla `SisSeccionesMenusEmergentes`
--
ALTER TABLE `SisSeccionesMenusEmergentes`
  ADD PRIMARY KEY (`eCodMenuEmergente`),
  ADD KEY `tcodpadre_me_fk_idx` (`tCodPadre`),
  ADD KEY `tCodseccion_me_fk_idx` (`tCodSeccion`);

--
-- Indices de la tabla `SisSeccionesPerfiles`
--
ALTER TABLE `SisSeccionesPerfiles`
  ADD KEY `perfil_rel_seccion_fk_idx` (`eCodPerfil`),
  ADD KEY `seccion_rel_perfil_idx` (`tCodSeccion`);

--
-- Indices de la tabla `SisSeccionesReemplazos`
--
ALTER TABLE `SisSeccionesReemplazos`
  ADD PRIMARY KEY (`eCodReemplazo`);

--
-- Indices de la tabla `SisUsuarios`
--
ALTER TABLE `SisUsuarios`
  ADD PRIMARY KEY (`eCodUsuario`),
  ADD KEY `SisUsuarios_rel_perfiles_fk_idx` (`eCodPerfil`),
  ADD KEY `CatEstatus_rel_usuarios_fk_idx` (`eCodEstatus`);

--
-- Indices de la tabla `SisVariables`
--
ALTER TABLE `SisVariables`
  ADD PRIMARY KEY (`eCodVariable`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `BitPublicaciones`
--
ALTER TABLE `BitPublicaciones`
  MODIFY `eCodPublicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `CatTiposPublicaciones`
--
ALTER TABLE `CatTiposPublicaciones`
  MODIFY `eCodTipoPublicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `SisLogs`
--
ALTER TABLE `SisLogs`
  MODIFY `eCodEvento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `SisMaximosRegistros`
--
ALTER TABLE `SisMaximosRegistros`
  MODIFY `eCodRegistro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `SisPerfiles`
--
ALTER TABLE `SisPerfiles`
  MODIFY `eCodPerfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `SisSeccionesBotones`
--
ALTER TABLE `SisSeccionesBotones`
  MODIFY `eCodRegistro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `SisSeccionesMenusEmergentes`
--
ALTER TABLE `SisSeccionesMenusEmergentes`
  MODIFY `eCodMenuEmergente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `SisSeccionesReemplazos`
--
ALTER TABLE `SisSeccionesReemplazos`
  MODIFY `eCodReemplazo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `SisUsuarios`
--
ALTER TABLE `SisUsuarios`
  MODIFY `eCodUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `SisVariables`
--
ALTER TABLE `SisVariables`
  MODIFY `eCodVariable` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `SisSeccionesBotones`
--
ALTER TABLE `SisSeccionesBotones`
  ADD CONSTRAINT `tCodBoton_rel_fk_botones` FOREIGN KEY (`tCodBoton`) REFERENCES `SisBotones` (`tCodBoton`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tCodPadre_rel_fk_botones` FOREIGN KEY (`tCodPadre`) REFERENCES `SisSecciones` (`tCodSeccion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `SisSeccionesMenusEmergentes`
--
ALTER TABLE `SisSeccionesMenusEmergentes`
  ADD CONSTRAINT `tCodseccion_me_fk` FOREIGN KEY (`tCodSeccion`) REFERENCES `SisSecciones` (`tCodSeccion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tcodpadre_me_fk` FOREIGN KEY (`tCodPadre`) REFERENCES `SisSecciones` (`tCodSeccion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `SisSeccionesPerfiles`
--
ALTER TABLE `SisSeccionesPerfiles`
  ADD CONSTRAINT `perfil_rel_seccion_fk` FOREIGN KEY (`eCodPerfil`) REFERENCES `SisPerfiles` (`eCodPerfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seccion_rel_perfil` FOREIGN KEY (`tCodSeccion`) REFERENCES `SisSecciones` (`tCodSeccion`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
