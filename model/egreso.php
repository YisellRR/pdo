<?php
class egreso
{
	private $pdo;
    
    public $id;
    public $id_cliente;
    public $id_usuario;
    public $id_compra;
    public $id_acreedor;
    public $fecha;
    public $categoria;
    public $concepto;
    public $comprobante;
	public $nro_comprobante;
    public $monto;
    public $forma_pago;  
    public $sucursal;
    public $anulado;
    public $nro_cheque;
    public $plazo;
    public $id_devolucion;
	public $moneda;
	public $cambio;

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
		{
		     session_start();
		     $id_usuario=$_SESSION['user_id'];
		    if($_SESSION['nivel']==1){
		      	
		      	$result = array();

			    $stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE e.anulado IS NULL ORDER BY e.id DESC");
			    $stm->execute();
			    
		    }else{
		   
		     	$result = array();

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE e.anulado IS NULL AND id_usuario=$id_usuario ORDER BY e.id DESC");
			$stm->execute();
		    }
		

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function MiLista()
	{
		try
		{
			session_start();

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id
			    WHERE id_usuario = ? AND anulado IS NULL
			    ORDER BY e.id DESC");
			$stm->execute(array($_SESSION['user_id']));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function MiListaAnulados()
	{
		try
		{
			session_start();

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id
			    WHERE id_usuario = ? AND anulado = 1
			    ORDER BY e.id DESC");
			$stm->execute(array($_SESSION['user_id']));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarSinAnular()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE anulado IS NULL ORDER BY e.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function ListarAcreedor($id_acreedor)
	{
		try
		{
			$result = array();
			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE e.id_acreedor = ?  AND anulado IS NULL  ORDER BY e.id DESC");
			$stm->execute(array($id_acreedor));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarSesion($id_usuario)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE categoria <> 'Venta' AND fecha >= (SELECT fecha_apertura FROM cierres WHERE id_usuario = ? AND fecha_cierre IS NULL) AND anulado IS NULL  ORDER BY id DESC");
			$stm->execute(array($id_usuario));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarRangoSesion($desde,$hasta,$id_usuario)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, e.id as id FROM egresos e LEFT JOIN clientes c ON e.id_cliente = c.id WHERE fecha >= ? AND fecha <= ? AND id_usuario = ? AND id_compra IS NULL AND anulado IS NULL  ORDER BY e.id DESC ");
			$stm->execute(array($desde,$hasta,$id_usuario));

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
			$stm = $this->pdo
			          ->prepare("SELECT * FROM egresos WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) AND categoria <> 'compra'  AND anulado IS NULL AND MONTH(fecha) = '6' AND DAY(fecha) = '1' ");
			          

			$stm->execute(array($fecha, $fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function AgrupadoMes($desde, $hasta, $anho)
	{
		try
		{
			$result = array();
			$mes = date('Y-m').'-01';
			$fecha=date('Y-m-d');

			if($anho > 0){
			   $anho = "AND YEAR(e.fecha) = $anho";
			   $rango = "";
			}else{
				$anho = "";

					if($desde != ''){
						if($hasta!=''){
							$rango = " AND CAST(e.fecha as date) >= '$desde' AND CAST(e.fecha as date) <= '$hasta'";
						}else{
							$rango = " AND CAST(e.fecha as date) >= '$desde' AND CAST(e.fecha as date) <= '$fecha'";  
						}
					}else{
						if($hasta!=''){
								$rango = " AND CAST(e.fecha as date) >= '$mes' AND CAST(e.fecha as date) <= '$hasta'";
						}else{
							$rango = " AND CAST(e.fecha as date) >= '$mes' AND CAST(e.fecha as date) <= '$fecha'";  
						}
					}   
            }

			// die("SELECT e.categoria, e.fecha, SUM(e.monto) as monto FROM egresos e WHERE e.anulado IS NULL $rango $anho AND e.categoria <> 'Transferencia' GROUP BY e.categoria ORDER BY e.id DESC");

			$sql = "SELECT e.categoria, e.fecha, SUM(e.monto) as monto, e.moneda FROM egresos e WHERE e.anulado IS NULL $rango $anho AND e.categoria <> 'Transferencia' GROUP BY e.categoria ORDER BY e.id DESC";
			          
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);
			}
		
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	
	public function AgrupadoFechaMes($fecha)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT categoria, SUM(monto) as monto FROM egresos WHERE MONTH(fecha) = MONTH(?) AND YEAR(fecha) = YEAR(?) AND anulado IS NULL AND categoria <> 'Transferencia' AND categoria <> 'compra' AND categoria <> 'compras' GROUP BY categoria ORDER BY id DESC");
			          

			$stm->execute(array($fecha, $fecha));
			return $stm->fetchAll(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
		public function Listar_rango($desde,$hasta, $id_sucursal)
	{
		try
		{
			$result = array();
			$fecha=date('m');
			
			
			$rango=$desde!=''? "AND CAST(e.fecha as date) >='$desde' AND CAST(e.fecha as date) <='$hasta'":"AND MONTH(e.fecha) >='$fecha'";
			$sucursal = $id_sucursal!=''? "AND  e.sucursal='$id_sucursal'":"";

			$stm = $this->pdo->prepare("SELECT *, e.id as id, s.sucursal AS sucursal
			FROM egresos e 
			LEFT JOIN clientes c ON e.id_cliente = c.id 
			LEFT JOIN sucursales s ON s.id = e.sucursal
			WHERE anulado IS NULL $rango $sucursal ORDER BY e.id DESC");
			$stm->execute(array());

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
		public function ListarSincompra($fecha)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE categoria <> 'compra' AND Cast(fecha as date) = ? AND anulado IS NULL ORDER BY id DESC");
			$stm->execute(array($fecha));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarSinCompraMes($desde, $hasta)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE CAST(fecha AS date) >= ? AND CAST(fecha AS date) <= ? AND id_acreedor IS NULL AND anulado IS NULL AND categoria <> 'compra' AND categoria <> 'Devolución' ORDER BY id ASC");
			$stm->execute(array($desde, $hasta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

public function ListarDevoluciones($desde, $hasta)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM egresos WHERE CAST(fecha AS date) >= ? AND CAST(fecha AS date) <= ? AND id_acreedor IS NULL AND anulado IS NULL AND categoria = 'Devolución' ORDER BY id ASC");
			$stm->execute(array($desde, $hasta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function EditarMonto($id_compra, $monto)
	{
		try 
		{
			$sql = "UPDATE egresos SET 
						monto    = ?
				    WHERE id_compra = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				    	$monto,
                        $id_compra
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Agrupado_egreso($mes)
	{
		try
		{
			$result = array();
			if($mes!='0'){
				$stm = $this->pdo->prepare("SELECT concepto, categoria, sum(monto) as monto, fecha  FROM egresos WHERE MONTH(fecha) = $mes AND anulado IS NULL AND categoria <> 'Transferencia' GROUP BY categoria ORDER BY id DESC");
			}else{
				$stm = $this->pdo->prepare("SELECT concepto, categoria, sum(monto) as monto FROM egresos WHERE anulado IS NULL AND categoria <> 'Transferencia' GROUP BY categoria ORDER BY id DESC");	
			}
			$stm->execute();

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
			          ->prepare("SELECT *, e.id AS id, e.sucursal AS sucursal
			          FROM egresos e
			          LEFT JOIN clientes c ON e.id_cliente = c.id 
			          WHERE e.id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

    public function ActualizarCompra($id_compra)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE egresos e SET e.monto = (SELECT SUM(c.total) FROM compras c WHERE c.id_compra = ?) WHERE id_compra = ?");			          

			$stm->execute(array($id_compra, $id_compra));
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
			            ->prepare("UPDATE egresos SET anulado = 1 WHERE id = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function EliminarDevolucion($id)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE egresos SET anulado = 1 WHERE id_devolucion = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function Anularcompra($id)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("DELETE FROM egresos WHERE id_compra = ?");			          

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
			$sql = "UPDATE egresos SET 
			            id_cliente     = ?,
			            id_compra      = ?,
			            fecha      	   = ?,
						categoria      = ?,
						concepto       = ?,
						comprobante    = ?,
						nro_comprobante = ?, 
						monto          = ?, 
						forma_pago     = ?,
                        sucursal       = ?,
                        nro_cheque     = ?,
                        plazo          = ?,
                        id_devolucion  = ?,
						moneda         = ?,
						cambio         = ?
						
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				        $data->id_cliente,
				        $data->id_compra,
				    	$data->fecha,
                        $data->categoria, 
                        $data->concepto, 
                        $data->comprobante,
						$data->nro_comprobante,                        
                        $data->monto,
                        $data->forma_pago,
                        $data->sucursal,
                        $data->nro_cheque,
                        $data->plazo,
                        $data->id_devolucion,
						$data->moneda,
						$data->cambio,
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
			session_start();
			$id_usuario = $_SESSION['user_id'];
			
			$sql = "INSERT INTO egresos (id_cliente, id_usuario, id_caja, id_compra, id_acreedor, fecha, categoria, concepto, comprobante, nro_comprobante, monto, forma_pago, sucursal, nro_cheque, plazo, id_devolucion, moneda, cambio) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
				    $data->id_cliente,
				    $id_usuario,
				    $data->id_caja,
					$data->id_compra,
					$data->id_acreedor,
					$data->fecha,
                	$data->categoria, 
                	$data->concepto, 
                	$data->comprobante,
					$data->nro_comprobante,                        
                	$data->monto,
                	$data->forma_pago,
                	$data->sucursal,
                	$data->nro_cheque,
                	$data->plazo,
                	$data->id_devolucion,
					$data->moneda,
					$data->cambio
                   
                )
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}