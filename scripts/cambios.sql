-- ALTER TABLE `cierres` CHANGE `cot_real` `cot_real` FLOAT NOT NULL;
-- ALTER TABLE `cierres` CHANGE `cot_dolar` `cot_dolar` FLOAT NOT NULL;

-- ALTER TABLE `pagos_tmp` CHANGE `monto` `monto` FLOAT NOT NULL;

-- ALTER TABLE `productos` ADD `precio_turista` DOUBLE NULL AFTER `precio_mayorista`;

-- ALTER TABLE `cierres` ADD `cot_dolar_tmp` FLOAT NULL AFTER `cot_dolar`, ADD `cot_real_tmp` FLOAT NULL AFTER `cot_dolar_tmp`;
-- ALTER TABLE `ventas` ADD `cot_dolar` FLOAT NULL AFTER `can_factura`;
-- ALTER TABLE `ventas` ADD `cot_real` FLOAT NULL AFTER `cot_dolar`;

--Cambios 29/11/2022
-- ALTER TABLE `productos` ADD `sinfactura` INT(11) NULL AFTER `anulado`, ADD `confactura` INT(11) NULL AFTER `sinfactura`, ADD `estado_venta` INT(11) NULL AFTER `confactura`;
-- ALTER TABLE `productos` ADD `precio_turista` DOUBLE NULL AFTER `precio_mayorista`;
-- ALTER TABLE `ventas_tmp` ADD `prod_factura` INT(11) NULL AFTER `id_presupuesto`, ADD `can_factura` INT(11) NULL AFTER `prod_factura`;
-- ALTER TABLE `cierres` ADD `apertura_rs` DOUBLE NOT NULL AFTER `monto_apertura`, ADD `apertura_usd` DOUBLE NOT NULL AFTER `apertura_rs`;
-- ALTER TABLE `cierres` ADD `monto_dolares` DOUBLE NULL AFTER `monto_cierre`, ADD `monto_reales` DOUBLE NULL AFTER `monto_dolares`;
-- ALTER TABLE `cierres` ADD `cot_dolar_tmp` FLOAT NULL AFTER `cot_dolar`, ADD `cot_real_tmp` FLOAT NULL AFTER `cot_dolar_tmp`;

-- ALTER TABLE `cierres` CHANGE `cot_real` `cot_real` FLOAT NOT NULL;
-- ALTER TABLE `cierres` CHANGE `cot_dolar` `cot_dolar` FLOAT NOT NULL;

-- ALTER TABLE `compras` ADD `facturable` TEXT NOT NULL AFTER `anulado`;
-- ALTER TABLE `egresos` ADD `moneda` TEXT NOT NULL AFTER `id_devolucion`, ADD `cambio` DOUBLE NOT NULL AFTER `moneda`;
-- ALTER TABLE `egresos` ADD `nro_comprobante` VARCHAR(15) NULL AFTER `comprobante`;

-- ALTER TABLE `ventas` ADD `prod_factura` INT(11) NULL AFTER `id_devolucion`, ADD `can_factura` INT(11) NULL AFTER `prod_factura`, ADD `cot_dolar` FLOAT NULL AFTER `can_factura`, ADD `cot_real` FLOAT NULL AFTER `cot_dolar`;

-- ALTER TABLE `ingresos` ADD `nro_comprobante` VARCHAR(11) NULL AFTER `comprobante`;
-- ALTER TABLE `ingresos` ADD `moneda` TEXT NOT NULL AFTER `id_gift`, ADD `cambio` FLOAT NOT NULL AFTER `moneda`;
-- ALTER TABLE `pagos_tmp` ADD `moneda` TEXT NOT NULL AFTER `monto`;

-- ALTER TABLE `deudas` CHANGE `monto` `monto` FLOAT NOT NULL, CHANGE `saldo` `saldo` FLOAT NOT NULL, CHANGE `sucursal` `sucursal` FLOAT NOT NULL;
-- ALTER TABLE `presupuestos_tmp` CHANGE `precio_venta` `precio_venta` FLOAT NOT NULL;
-- ALTER TABLE `ventas` CHANGE `precio_venta` `precio_venta` FLOAT NOT NULL;
-- ALTER TABLE `ventas` CHANGE `descuento` `descuento` FLOAT NOT NULL;
-- ALTER TABLE `ventas_tmp` CHANGE `precio_venta` `precio_venta` FLOAT NOT NULL;
-- ALTER TABLE `presupuestos` CHANGE `precio_venta` `precio_venta` FLOAT NOT NULL;
-- ALTER TABLE `compras_tmp` CHANGE `precio_compra` `precio_compra` FLOAT NOT NULL;
-- ALTER TABLE `pagos_tmp` CHANGE `monto` `monto` FLOAT NOT NULL;
-- ALTER TABLE `acreedores` CHANGE `monto` `monto` FLOAT NOT NULL;
-- ALTER TABLE `acreedores` CHANGE `saldo` `saldo` FLOAT NOT NULL;

-- ALTER TABLE `ingresos` CHANGE `monto` `monto` FLOAT NOT NULL;
-- ALTER TABLE `egresos` CHANGE `monto` `monto` FLOAT NOT NULL;
-- ALTER TABLE `deudas` ADD `moneda` TEXT NULL AFTER `monto`, ADD `cambio` FLOAT NULL AFTER `moneda`;

-- ALTER TABLE `acreedores` ADD `moneda` TEXT NOT NULL AFTER `sucursal`, ADD `cambio` FLOAT NOT NULL AFTER `moneda`;

-- ALTER TABLE `compras` ADD `moneda` TEXT NOT NULL AFTER `metodo`, ADD `cambio` FLOAT NOT NULL AFTER `moneda`;
-- ALTER TABLE `ventas` ADD `moneda` TEXT NOT NULL AFTER `cot_real`;

--20/12/2022

ALTER TABLE `deudas` ADD `id_deuda` INT(11) NOT NULL AFTER `id_cliente`;