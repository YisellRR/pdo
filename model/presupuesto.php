<?php
class presupuesto
{
	private $pdo;
    
    public $id;
    public $id_presupuesto;
    public $id_cliente;
    public $id_vendedor;
    public $id_producto;
    public $precio_venta;
    public $cantidad;
    public $fecha_presupuesto; 
    public $anulado;
	public $descuento;
	public $estado;
    
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

	public function Listar()
	{
		try
		{ session_start();
			$userId= $_SESSION['user_id'];
			if(($_SESSION['nivel']<3)){
				$vendedor = "";
            }else{
				$vendedor = "AND v.id_vendedor = '$userId'";
                
            }
			
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, SUM((v.cantidad*v.precio_venta)-(v.descuento)) AS total
			FROM presupuestos v 
			LEFT JOIN productos p ON v.id_producto = p.id
			LEFT JOIN usuario u ON v.id_vendedor = u.id
			LEFT JOIN clientes c ON v.id_cliente = c.id
			WHERE 1=1 $vendedor GROUP BY v.id_presupuesto ORDER BY v.id DESC");
			$stm->execute(array());

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function ListarDetalle($id_presupuesto)
	{
		try
		{
			
			$result = array();

			$stm = $this->pdo->prepare("SELECT *
			FROM presupuestos v 
			LEFT JOIN productos p ON v.id_producto = p.id
			LEFT JOIN usuario u ON v.id_vendedor = u.id
			LEFT JOIN clientes c ON v.id_cliente = c.id
			WHERE id_presupuesto = ? ORDER BY v.id DESC");
			$stm->execute(array($id_presupuesto));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function ListarPresupuesto($id_presupuesto)
	{
		try
		{
			
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, SUM((v.cantidad*v.precio_venta)-(((v.cantidad*v.precio_venta)*v.descuento))/100) AS total
			FROM presupuestos v 
			LEFT JOIN productos p ON v.id_producto = p.id
			LEFT JOIN usuario u ON v.id_vendedor = u.id
			LEFT JOIN clientes c ON v.id_cliente = c.id
			WHERE id_presupuesto = ? ORDER BY v.id DESC");
			$stm->execute(array($id_presupuesto));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function ObtenerId_presupuesto($id)
	{
		try 
		{
			$stm = $this->pdo->prepare("SELECT * FROM presupuestos WHERE id_presupuesto = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Obtener()
	{
		try 
		{
		    if(!isset($_SESSION['user_id'])){
				session_start();
			}
		    $user_id = $_SESSION['user_id'];
			$stm = $this->pdo
			          ->prepare("SELECT * FROM presupuestos  WHERE id_vendedor = '$user_id' GROUP BY id_presupuesto");
			          

			$stm->execute();
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
			          ->prepare("SELECT MAX(id_presupuesto) as id_presupuesto FROM presupuestos");
			$stm->execute();
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function ObtenerMoneda()
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM monedas WHERE id = 1");
			          

			$stm->execute();
			return $stm->fetch(PDO::FETCH_OBJ);
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
			            ->prepare("DELETE FROM presupuestos WHERE id = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Vaciar()
	{
		try 
		{
		    session_start();
		    $id_vendedor = $_SESSION['user_id'];
			$stm = $this->pdo
			            ->prepare("DELETE FROM presupuestos WHERE id_vendedor = ? ");
			$stm->execute(array($id_vendedor));			          

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function CambiarEstado($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE presupuestos SET estado='Vendido' WHERE id_presupuesto = ? ");
			$stm->execute(array($data->id_presupuesto));			          

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function VentaAnulada($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE presupuestos SET estado='NULL' WHERE id_presupuesto = ? ");
			$stm->execute(array($data->id_presupuesto));			          

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar($data)
	{
		try 
		{
			$sql = "UPDATE presupuestos SET
						id_presupuesto     = ?,
						id_vendedor     = ?,
						id_producto     = ?,
						precio_venta   = ?,
                        cantidad      = ?, 
						fecha_presupuesto      = ?
						
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->id_presupuesto,
                        $data->id_vendedor, 
                        $data->id_producto,                 
                        $data->precio_venta,
                        $data->cantidad,
                        $data->fecha_presupuesto,
                        $data->id
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Moneda($data)
	{
		try 
		{
			$sql = "UPDATE monedas SET
						reales     = ?,
						dolares     = ?,
						monto_inicial = ?
						";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->reales,
                        $data->dolares,
                        $data->monto_inicial
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
		$sql = "INSERT INTO presupuestos (id_presupuesto, id_cliente, id_vendedor, id_producto,id_sucursal, precio_venta, cantidad, fecha_presupuesto, descuento) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
                    $data->id_presupuesto,
					$data->id_cliente,
                    $data->id_vendedor,
                    $data->id_producto,                 
                    $data->id_sucursal,                 
                    $data->precio_venta,
                    $data->cantidad, 
                    $data->fecha_presupuesto,
					$data->descuento
                   
                )
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}