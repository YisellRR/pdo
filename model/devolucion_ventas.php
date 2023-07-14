<?php
class devolucion_ventas
{
	private $pdo;
    
    public $id;
    public $id_venta;
    public $id_cliente;
    public $id_vendedor;
    public $vendedor_salon;
    public $id_producto;
    public $precio_costo;
    public $precio_venta;
    public $subtotal;
    public $descuento;
    public $total;
    public $comprobante;
    public $nro_comprobante;
    public $cantidad;
    public $margen_ganancia;
    public $fecha_venta;
    public $metodo;
    public $contado;
    public $motivo;    
    public $id_sucursal_producto; 
    
    
	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = Database::StartUp();     
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Listar($id_venta)
	{
		try
		{
			
			if($id_venta==0){
			    
				$stm = $this->pdo->prepare("SELECT v.id, v.id_venta, v.comprobante, v.metodo, v.anulado, contado, p.producto, SUM(subtotal) AS subtotal, descuento, 
					SUM(total) as total, AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante, 
					c.nombre AS nombre_cli, c.ruc, c.direccion, c.telefono, v.id_producto, 
					(SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
					(SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon,
					(SELECT ve.id_venta FROM ventas ve WHERE v.id_venta= ve.id_devolucion LIMIT 1)  AS id_devolucion
					FROM devoluciones_ventas v 
					LEFT JOIN productos p ON v.id_producto = p.id 
					LEFT JOIN clientes c ON v.id_cliente = c.id 
					GROUP BY v.id_venta ORDER BY v.id_venta DESC");
				$stm->execute();
			}else{
				$stm = $this->pdo->prepare("SELECT v.id, p.producto,v.comprobante, v.metodo, v.anulado, contado, p.codigo,p.iva, v.cantidad, v.precio_venta, subtotal, descuento, total, margen_ganancia, fecha_venta, nro_comprobante, c.nombre AS nombre_cli,
				 c.ruc, c.direccion, c.telefono, v.id_producto 
				 FROM devoluciones_ventas v 
				 LEFT JOIN productos p ON v.id_producto = p.id 
				 LEFT JOIN clientes c ON v.id_cliente = c.id 
				 WHERE v.id_venta = ?");
				$stm->execute(array($id_venta));
			}

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarId_venta()
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT d.id AS id, id_venta, c.nombre, c.ruc  
				FROM devoluciones_ventas d
				LEFT JOIN clientes c ON d.id_cliente = c.id
				GROUP BY d.id_venta ORDER BY d.id_venta DESC");
			$stm->execute(array());

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function AgrupadoProducto($fecha)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT p.producto, SUM(v.cantidad) as cantidad, SUM(v.total) as total, SUM(v.cantidad*v.precio_costo) as costo, v.id_cliente FROM devoluciones_ventas v 
				LEFT JOIN productos p ON v.id_producto = p.id 
				LEFT JOIN clientes c ON v.id_cliente = c.id 
				WHERE MONTH(fecha_venta) = MONTH(?) AND YEAR(fecha_venta) = YEAR(?) AND anulado = 0 
				OR (MONTH(fecha_venta) = '7' AND DAY(fecha_venta) = '1' AND anulado = 0) 
				GROUP BY v.id_producto 
				ORDER BY v.id_venta DESC");
			$stm->execute(array($fecha, $fecha));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarProducto($id_producto, $desde, $hasta, $sucursal)
	{
		try
		{
			$sucursal=$sucursal != 0 ?"AND v.id_sucursal_producto = '$sucursal'" : '';
			$rango = ($desde=='')? "":"AND fecha_venta >= '$desde' AND fecha_venta <= '$hasta'";
			$stm = $this->pdo->prepare("SELECT v.id, v.id_venta,
			p.producto,
			v.comprobante,
			v.metodo, 
			v.anulado, 
			v.contado, 
			p.codigo,
			p.iva,
			SUM(v.cantidad) AS cantidad, 
			AVG(v.precio_costo) AS precio_costo, 
			AVG(v.precio_venta) AS precio_venta,
			v.subtotal,
			AVG(v.descuento) AS descuento,
			SUM(v.total) AS total,
			v.margen_ganancia, 
			v.fecha_venta,
			v.nro_comprobante,
			c.nombre as nombre_cli,
			c.ruc, 
			c.direccion,
			c.telefono, 
			v.id_producto,
			(v.id_sucursal_producto) AS id_sucursal,
			(SELECT user FROM usuario u WHERE u.id = v.id_vendedor ) as vendedor,
			(SELECT user FROM usuario WHERE id = v.vendedor_salon) as vendedor_salon 
			FROM devoluciones_ventas v  
			LEFT JOIN productos p ON v.id_producto = p.id 
			LEFT JOIN clientes c ON v.id_cliente = c.id 
			WHERE v.id_producto = ? $rango AND v.anulado = 0 $sucursal GROUP BY v.id_venta");
			$stm->execute(array($id_producto));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarCliente($id_cliente)
	{
		try
		{
			
			
			$stm = $this->pdo->prepare("SELECT v.id, v.id_venta, v.comprobante, v.metodo, v.anulado, contado, p.producto, SUM(subtotal) AS subtotal, descuento, SUM(total) AS total, 
				AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante, 
				c.nombre AS nombre_cli, c.ruc, c.direccion, c.telefono, v.id_producto, 
				(SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
				(SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon 
				FROM devoluciones_ventas v 
				LEFT JOIN productos p ON v.id_producto = p.id 
				LEFT JOIN clientes c ON v.id_cliente = c.id 
				WHERE id_cliente = ? 
				GROUP BY v.id_venta 
				ORDER BY v.id_venta DESC");
			$stm->execute(array($id_cliente));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarUsuarioMes($id_usuario, $mes)
	{
		try
		{
			
			$fecha = $mes."-10";
			$stm = $this->pdo->prepare("SELECT v.id, v.id_venta, v.comprobante, v.metodo, v.anulado, contado, p.producto, SUM(subtotal) AS subtotal, descuento, SUM(total) AS total, 
				AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante, c.nombre AS nombre_cli,
				c.ruc, c.direccion, c.telefono, v.id_producto, 
				(SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
				(SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon, 
				(SELECT comision FROM usuario WHERE id = v.id_vendedor) AS comision  
				FROM devoluciones_ventas v 
				LEFT JOIN productos p ON v.id_producto = p.id 
				LEFT JOIN clientes c ON v.id_cliente = c.id 
				WHERE vendedor_salon = ? AND MONTH(fecha_venta) = MONTH(?) AND YEAR(fecha_venta) = YEAR(?) 
				AND anulado = '0' 
				GROUP BY v.id_venta 
				ORDER BY v.id_venta DESC");
			$stm->execute(array($id_usuario, $fecha, $fecha));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarDia($fecha)
	{
		try
		{
			if(!isset($_SESSION['user_id'])){
			    session_start();
			}
			$id_vendedor = $_SESSION['user_id'];
			$usuario = ($_SESSION['nivel']==1)? "":"AND v.id_vendedor = $id_vendedor";
			$stm = $this->pdo->prepare("SELECT v.metodo, v.contado, v.id_venta, a.nombre AS nombre_cli, v.anulado, c.producto, SUM(subtotal) AS subtotal, v.descuento, SUM(v.total) AS total, 
				AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante, v.id_producto, 
				(SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
				(SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon 
				FROM devoluciones_ventas v 
				LEFT JOIN productos c ON v.id_producto = c.id 
				LEFT JOIN clientes a ON v.id_cliente = a.id 
				WHERE CAST(v.fecha_venta AS date) = ? $usuario  
				GROUP BY v.id_venta DESC");
			$stm->execute(array($fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarDiaSesion()
	{
		try
		{
			if(!isset($_SESSION['user_id'])){
			    session_start();
			}
			$id_vendedor = $_SESSION['user_id'];
			$usuario = ($_SESSION['nivel']==1)? "":"AND v.id_vendedor = $id_vendedor";
			$stm = $this->pdo->prepare("SELECT v.metodo, v.contado, v.id_venta, a.nombre AS nombre_cli, v.anulado, c.producto, SUM(subtotal) as subtotal, v.descuento, SUM(v.total) AS total, 
				AVG(margen_ganancia) as margen_ganancia, fecha_venta, nro_comprobante, v.id_producto, 
				(SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
				(SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon 
				FROM devoluciones_ventas v 
				LEFT JOIN productos c ON v.id_producto = c.id 
				LEFT JOIN clientes a ON v.id_cliente = a.id 
				WHERE fecha_venta >= (SELECT fecha_apertura FROM cierres WHERE id_usuario = ? AND fecha_cierre IS NULL) $usuario  GROUP BY v.id_venta DESC");
			$stm->execute(array($id_vendedor));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarDiaSinAnular($fecha)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT v.metodo, v.contado, v.id_venta, a.nombre AS nombre_cli, v.anulado, c.producto, SUM(subtotal) AS subtotal, v.descuento, SUM(v.precio_costo * v.cantidad) AS costo, SUM(v.total) AS total, AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante,
			    v.id_producto, (SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
			    (SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon 
			    FROM devoluciones_ventas v 
			    LEFT JOIN productos c ON v.id_producto = c.id 
			    LEFT JOIN clientes a ON v.id_cliente = a.id 
			    WHERE CAST(v.fecha_venta AS date) = ? AND anulado <> 1  
			    GROUP BY v.id_venta DESC");
			$stm->execute(array($fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarRango($desde, $hasta)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT v.id_cliente, v.metodo, v.contado, v.id_venta, 
				a.nombre AS nombre_cli, v.anulado, c.producto, SUM(subtotal) AS subtotal, 
				v.descuento, SUM(v.precio_costo * v.cantidad) AS costo, SUM(v.total) AS total, 
				AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante, v.id_producto, 
				(SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
				(SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon 
				FROM devoluciones_ventas v 
				LEFT JOIN productos c ON v.id_producto = c.id 
				LEFT JOIN clientes a ON v.id_cliente = a.id 
				WHERE CAST(v.fecha_venta AS date) >= ? AND CAST(v.fecha_venta AS date) <= ? 
				AND anulado <> 1  
				GROUP BY v.id_venta DESC");
			$stm->execute(array($desde, $hasta));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarUsados($desde, $hasta)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT c.producto, v.fecha_venta, v.precio_costo, v.precio_venta, 
				v.cantidad, (v.precio_venta*v.cantidad) AS total 
				FROM devoluciones_ventas v 
				LEFT JOIN productos c ON v.id_producto = c.id 
				WHERE CAST(v.fecha_venta AS date) >= ? AND CAST(v.fecha_venta AS date) <= ? 
				AND anulado <> 1 AND v.id_cliente = 14 
				ORDER BY v.id DESC");
			$stm->execute(array($desde, $hasta));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarRangoSinAnular($desde, $hasta, $id_vendedor)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT v.metodo, v.contado, v.id_venta, a.nombre AS nombre_cli,
			 v.anulado, c.producto, SUM(subtotal) AS subtotal, v.descuento, 
			 SUM(v.precio_costo * v.cantidad) AS costo, SUM(v.total) AS total, 
			 AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante, v.id_producto, 
			 (SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
			 (SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon 
			 FROM devoluciones_ventas v 
			 LEFT JOIN productos c ON v.id_producto = c.id 
			 LEFT JOIN clientes a ON v.id_cliente = a.id 
			 WHERE fecha_venta >= ? AND fecha_venta <= ?  AND anulado <> 1 AND id_vendedor = ?  
			 GROUP BY v.id_venta DESC");
			$stm->execute(array($desde, $hasta, $id_vendedor));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarMesSinAnular($fecha)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT v.id_cliente, v.metodo, v.contado, v.id_venta, 
				a.nombre AS nombre_cli, v.anulado, c.producto, SUM(subtotal) AS subtotal,
				v.descuento, SUM(v.precio_costo * v.cantidad) AS costo, SUM(v.total) AS total, 
				AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante, v.id_producto, 
				(SELECT user FROM usuario WHERE id = v.id_vendedor) AS vendedor, 
				(SELECT user FROM usuario WHERE id = v.vendedor_salon) AS vendedor_salon 
				FROM devoluciones_ventas v 
				LEFT JOIN productos c ON v.id_producto = c.id 
				LEFT JOIN clientes a ON v.id_cliente = a.id 
				WHERE MONTH(v.fecha_venta) = MONTH(?) AND YEAR(v.fecha_venta) = YEAR(?) AND anulado <> 1  
				GROUP BY v.id_venta DESC");
			$stm->execute(array($fecha, $fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarDiaContado($fecha)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT v.metodo, v.contado, v.id_venta, cli.Nombre AS nombre_cli,
			 c.producto, SUM(subtotal) AS subtotal, v.descuento, SUM(v.total) AS total, 
			 AVG(margen_ganancia) AS margen_ganancia, fecha_venta, nro_comprobante 
			 FROM devoluciones_ventas v 
			 LEFT JOIN productos c ON v.id_producto = c.id 
			 LEFT JOIN clientes cli ON v.id_cliente = cli.id 
			 WHERE CAST(v.fecha_venta AS date) = ? AND contado = 'contado' 
			 GROUP BY v.id_venta DESC");
			$stm->execute(array($fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarMes($fecha)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT v.id_venta, cli.Nombre AS nombre_cli, v.metodo, c.producto, 
				SUM(subtotal) AS subtotal, descuento, SUM(total) AS total, AVG(margen_ganancia) AS ganancia, fecha_venta, nro_comprobante 
				FROM devoluciones_ventas v 
				LEFT JOIN productos c ON v.id_producto = c.id 
				LEFT JOIN clientes cli ON cli.id = v.id_cliente  
				WHERE MONTH(v.fecha_venta) = MONTH(?) AND YEAR(v.fecha_venta) = YEAR(?) 
				GROUP BY v.id_venta DESC");
			$stm->execute(array($fecha, $fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Detalles($id_venta)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT c.producto, subtotal, descuento, total, margen_ganancia, fecha_venta, nro_comprobante 
				FROM devoluciones_ventas v 
				JOIN productos c ON v.id_producto = c.id 
				WHERE v.id_venta = ?");
			$stm->execute(array($id_venta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function Obtener($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT *, SUM(total) AS monto FROM devoluciones_ventas WHERE id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function ObtenerId_venta($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT *, SUM(total) AS monto FROM devoluciones_ventas WHERE id_venta = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function ObtenerProducto($id_venta, $id_producto)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM devoluciones_ventas WHERE id_venta = ? AND id_producto = ?");
			          

			$stm->execute(array($id_venta, $id_producto));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function ObtenerUNO($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM devoluciones_ventas WHERE id_venta = ? LIMIT 1");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Recibo($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM devoluciones_ventas WHERE id_venta = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Ultimo()
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT MAX(id) as id FROM devoluciones_ventas");
			$stm->execute();
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}


	public function Cantidad($id_item, $id_venta, $cantidad)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("UPDATE devoluciones_ventas SET cantidad = ?, subtotal = precio_venta * ?, 
			          	total = precio_venta * ? WHERE id = ?");
			$stm->execute(array($cantidad, $cantidad, $cantidad, $id_item));
			$stm = $this->pdo
			          ->prepare("SELECT *, (SELECT SUM(total) 
			          	FROM devoluciones_ventas WHERE id_venta = ? 
			          	GROUP BY id_venta) AS total_venta 
			          	FROM devoluciones_ventas WHERE id = ?");
			$stm->execute(array($id_venta, $id_item));
		
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function CancelarItem($id_item)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("DELETE FROM devoluciones_ventas WHERE id = ?");			          

			$stm->execute(array($id_item));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	
	public function Eliminar($id)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("DELETE FROM devoluciones_ventas WHERE id_venta = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}


	public function Anular($id)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE devoluciones_ventas SET anulado = 1 WHERE id_venta = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}


	public function Actualizar($data)
	{
		try 
		{
			$sql = "UPDATE devoluciones_ventas SET
						id_venta     = ?,
						id_vendedor     = ?,
						id_producto     = ?,
						precio_venta   = ?,
                        cantidad      = ?, 
						margen_ganancia     = ?,
						fecha_venta      = ?
						
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->id_venta,
                        $data->id_vendedor, 
                        $data->id_producto,                 
                        $data->precio_venta,
                        $data->cantidad,
                        $data->margen_ganancia, 
                        $data->fecha_venta,
                        $data->id
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar($data)
	{
		try 
		{



		$sql = "INSERT INTO devoluciones_ventas (id_venta, id_cliente, id_vendedor, vendedor_salon, id_producto, precio_costo, precio_venta, subtotal, descuento, iva, total, comprobante, nro_comprobante, cantidad, margen_ganancia, fecha_venta, metodo, banco, contado, motivo, id_sucursal_producto) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
					$data->id_venta,
                    $data->id_cliente,
                    $data->id_vendedor,
                    $data->vendedor_salon,
                    $data->id_producto,
                    $data->precio_costo,            
                    $data->precio_venta,
                    $data->subtotal,
                    $data->descuento,
                    $data->iva,
                    $data->total,
                    $data->comprobante,
                    $data->nro_comprobante,
                    $data->cantidad,
                    $data->margen_ganancia, 
                    $data->fecha_venta,
                    $data->metodo,
                    $data->banco,
                    $data->contado,
                    $data->motivo,
                    $data->id_sucursal_producto
                   
                )
			);

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}