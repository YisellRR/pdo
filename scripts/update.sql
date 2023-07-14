
UPDATE ventas v, ingresos i  
					SET i.sucursal = 
							CASE 
								WHEN v.id_sucursal IS NULL 
									THEN (1) 
								ELSE 
									v.id_sucursal
							END
					WHERE i.id_venta = v.id_venta