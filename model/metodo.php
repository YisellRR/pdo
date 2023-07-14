<?php
class metodo
{
	private $pdo;
    
    public $id;
    public $metodo;

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
			$result = array();

			$stm = $this->pdo->prepare("SELECT *, 
			(SELECT SUM(i.monto) FROM ingresos i WHERE i.forma_pago = m.metodo AND i.anulado IS NULL AND i.moneda = 'GS') AS ingresos_GS, 
			(SELECT SUM(i.monto) FROM ingresos i WHERE i.forma_pago = m.metodo AND i.anulado IS NULL AND i.moneda = 'USD') AS ingresos_USD, 
			(SELECT SUM(i.monto) FROM ingresos i WHERE i.forma_pago = m.metodo AND i.anulado IS NULL AND i.moneda = 'RS') AS ingresos_RS, 
			
			(SELECT SUM(e.monto) FROM egresos e WHERE e.forma_pago = m.metodo AND e.anulado IS NULL AND e.moneda = 'USD') AS egresos_USD,
			(SELECT SUM(e.monto) FROM egresos e WHERE e.forma_pago = m.metodo AND e.anulado IS NULL AND e.moneda = 'GS') AS egresos_GS,
			(SELECT SUM(e.monto) FROM egresos e WHERE e.forma_pago = m.metodo AND e.anulado IS NULL AND e.moneda = 'RS') AS egresos_RS
			
			FROM metodos m WHERE m.anulado = 0;");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarMovimientos($metodo)
	{
		try
		{
			$result = array();
			$query = "SELECT i.fecha, i.categoria, i.concepto, i.comprobante, (i.monto * 1) as monto, i.moneda, i.forma_pago, i.anulado, (SELECT v.descuento FROM ventas v WHERE v.id_venta = i.id_venta LIMIT 1) as descuento FROM ingresos i WHERE forma_pago = ? AND anulado IS NULL
				UNION ALL 
				SELECT e.fecha, e.categoria, e.concepto, e.comprobante, (e.monto * -1) as monto, e.moneda, e.forma_pago, e.anulado, (SELECT v.descuento FROM compras v WHERE v.id_compra = e.id_compra LIMIT 1) as descuento FROM egresos e WHERE e.forma_pago = ? AND anulado IS NULL ";
			$stm = $this->pdo->prepare($query);
			$stm->execute(array($metodo ,$metodo));

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
			          ->prepare("SELECT *, SUM(SELECT monto FROM ingresos WHERE forma_pago = metodo) AS total FROM metodos WHERE id = ?");
			          

			$stm->execute(array($id));
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
			            ->prepare("DELETE FROM pagos_tmp WHERE id = ?");			          

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
			            ->prepare("UPDATE metodos SET anulado = 1 WHERE id = ?");			          

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
			$stm = $this->pdo
			            ->prepare("DELETE FROM pagos_tmp");			          

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
			$sql = "UPDATE metodos SET 

						metodo   = ?
						
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				    	$data->metodo,
				    	$data->id
					)
				);
		return "Modificado";
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar(metodo $data)
	{
		try 
		{
		$sql = "INSERT INTO metodos (metodo) 
		        VALUES (?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
					$data->metodo
                )
			);
		return "Agregado";
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}