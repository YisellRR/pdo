-- INSERT INTO `sucursales` (`id`, `sucursal`) VALUES ('2', 'Sucursal 2');

-- ALTER TABLE `ventas_tmp` ADD `id_sucursal` INT(11) NULL AFTER `can_factura`;
-- ALTER TABLE `ventas` ADD `id_sucursal` INT(11) NULL AFTER `moneda`;
-- ALTER TABLE `presupuestos_tmp` ADD `id_sucursal` INT(11) NULL AFTER `id_producto`;
-- ALTER TABLE `presupuestos` ADD `id_sucursal` INT(11) NULL AFTER `id_producto`;

-- UPDATE productos SET stock_s1 = stock;


-- ----------------------------------------------------------
-- -- 06/02/20233
-- --
-- -- Estructura de tabla para la tabla `transferencia_productos`
-- --

-- CREATE TABLE `transferencia_productos` (
--   `id` int(11) NOT NULL,
--   `id_transferencia_producto` int(11) NOT NULL,
--   `id_cliente` int(11) NOT NULL,
--   `id_vendedor` int(11) NOT NULL,
--   `id_producto` int(11) NOT NULL,
--   `destino_transferencia` varchar(20) DEFAULT NULL,
--   `precio_venta` int(11) NOT NULL,
--   `cantidad` float NOT NULL,
--   `fecha_transferencia_producto` datetime NOT NULL,
--   `fecha_confirmacion` datetime DEFAULT NULL,
--   `anulado` int(11) NOT NULL DEFAULT 0,
--   `descuento` float NOT NULL DEFAULT 0,
--   `tipo_transferencia` varchar(20) DEFAULT NULL,
--   `estado` text DEFAULT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- -- --------------------------------------------------------

-- --
-- -- Estructura de tabla para la tabla `transferencia_productos_tmp`
-- --

-- CREATE TABLE `transferencia_productos_tmp` (
--   `id` int(11) NOT NULL,
--   `id_transferencia_producto` int(11) NOT NULL,
--   `id_vendedor` int(11) NOT NULL,
--   `id_producto` varchar(25) NOT NULL,
--   `precio_venta` int(11) NOT NULL,
--   `cantidad` float NOT NULL,
--   `descuento` float NOT NULL,
--   `fecha_transferencia_producto` datetime NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --
-- -- Índices para tablas volcadas
-- --

-- --
-- -- Indices de la tabla `transferencia_productos`
-- --
-- ALTER TABLE `transferencia_productos`
--   ADD PRIMARY KEY (`id`);

-- --
-- -- Indices de la tabla `transferencia_productos_tmp`
-- --
-- ALTER TABLE `transferencia_productos_tmp`
--   ADD PRIMARY KEY (`id`);

-- --
-- -- AUTO_INCREMENT de las tablas volcadas
-- --

-- --
-- -- AUTO_INCREMENT de la tabla `transferencia_productos`
-- --
-- ALTER TABLE `transferencia_productos`
--   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --
-- -- AUTO_INCREMENT de la tabla `transferencia_productos_tmp`
-- --
-- ALTER TABLE `transferencia_productos_tmp`
--   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


-- ALTER TABLE `transferencia_productos` ADD `emisor_transferencia` INT(11) NULL AFTER `id_producto`;
-- ALTER TABLE `transferencia_productos` CHANGE `destino_transferencia` `destino_transferencia` INT(11) NULL DEFAULT NULL;


-- 07/02/2023
-- ALTER TABLE `devoluciones` ADD `id_sucursal` INT(11) NULL AFTER `anulado`;

--08/02/2023
ALTER TABLE `compras` ADD `id_sucursal` INT(11) NULL AFTER `id_producto`;