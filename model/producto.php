<?php
class producto
{
	private $pdo;
    
    public $id;
    public $codigo;
    public $id_categoria;
    public $producto;
    public $marca;
    public $descripcion;
    public $precio_costo;
    public $precio_minorista;
    public $precio_mayorista;
    public $precio_turista;
    public $apartir;
    public $fardo;
    public $preciob;
    public $stock;
    public $stock_s1;
    public $stock_s2;
    public $stock_s3;
    public $stock_minimo;
    public $descuento_max;
    public $importado;
    public $iva;
    public $sucursal;
    public $anulado;
	public $sinfactura;
	public $confactura;


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
            if(!isset($_SESSION['nivel'])){
                session_start();
            }
            if(true){
                $sucursal = "WHERE p.sucursal = ".$_SESSION['sucursal'];
            }else{
                $sucursal = "";
            }
            
            
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id
			      LEFT JOIN sucursales s ON p.sucursal = s.id 
			      LEFT JOIN marcas m ON m.id = p.marca  
			      WHERE p.anulado IS NULL
			      ORDER BY CAST(p.codigo AS INT) ASC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarProd($id)
	{
		try
		{
			$result = array();
            if(!isset($_SESSION['nivel'])){
                session_start();
            }
            
			$stm = $this->pdo->prepare("SELECT p.id, s.sucursal, p.precio_costo FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id
			      LEFT JOIN sucursales s ON p.sucursal = s.id 
			      JOIN marcas m ON m.id = p.marca  
			      WHERE p.anulado IS NULL AND p.id = ?
			      ORDER BY CAST(p.codigo AS INT) ASC");
			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarProdctoFactura()
	{
		try
		{
			$result = array();
            if(!isset($_SESSION['nivel'])){
                session_start();
            }
            if(true){
                $sucursal = "WHERE p.sucursal = ".$_SESSION['sucursal'];
            }else{
                $sucursal = "";
            }
            
            
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id
			      LEFT JOIN sucursales s ON p.sucursal = s.id 
			      JOIN marcas m ON m.id = p.marca  
			      WHERE p.anulado IS NULL AND p.confactura>0
			      ORDER BY p.id ASC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function ListarAjax()
	{
		try
		{
            
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal, sub.categoria AS sub_categoria, sub.id_padre, m.marca 
			FROM productos p 
			LEFT JOIN categorias sub ON p.id_categoria = sub.id 
			LEFT JOIN categorias c ON sub.id_padre = c.id 
			LEFT JOIN sucursales s ON p.sucursal = s.id 
			LEFT JOIN marcas m ON m.id = p.marca 
			WHERE p.anulado IS NULL ORDER BY CAST(p.codigo AS INT) ASC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function consultarVenta($id_venta)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT SUM(monto) as total FROM ingresos WHERE id_venta = ?");
			$stm->execute(array($id_venta));
			return $stm->fetch(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ListarVenta($id_venta)
	{
		try
		{
            
			$stm = $this->pdo->prepare("SELECT * FROM productos WHERE id IN (SELECT id_producto FROM ventas WHERE id_venta = ?) AND id NOT IN (SELECT id_producto FROM devoluciones_tmp) ORDER BY id DESC");
			$stm->execute(array($id_venta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarBuscar($q)
	{
		try
		{
			$result = array();
            if(!isset($_SESSION['nivel'])){
                session_start();
            }
            if($_SESSION['nivel']!=1 ){
                $sucursal = "WHERE p.sucursal = ".$_SESSION['sucursal'];
            }else{
                $sucursal = "";
            }
            
            if($q != ""){
                $sucursal = "AND p.sucursal = ".$q;
            }else{
                $sucursal = "";
            }
            
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal FROM productos p JOIN categorias c ON p.id_categoria = c.id LEFT JOIN sucursales s ON p.sucursal = s.id JOIN marcas m ON m.id = p.marca $sucursal  ORDER BY p.id DESC LIMIT 50");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarVentaProducto($id_venta)
	{
		try
		{
            
			$stm = $this->pdo->prepare("
				SELECT * FROM ventas v
				LEFT JOIN productos p ON v.id_producto = p.id
				WHERE id_venta = ?
				ORDER BY p.id");
			$stm->execute(array($id_venta));

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarTodo()
	{
		try
		{
			$result = array();
          
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal, p.sucursal as id_sucursal 
			FROM productos p 
			JOIN categorias c ON p.id_categoria = c.id 
			LEFT JOIN sucursales s ON p.sucursal = s.id 
			JOIN marcas m ON m.id = p.marca ORDER BY p.id DESC");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function ListarTodoBalance()
	{
		try
		{
			$result = array();
          
			$stm = $this->pdo->prepare("SELECT *, p.id, s.sucursal, p.sucursal as id_sucursal 
			FROM productos p 
			LEFT JOIN categorias c ON p.id_categoria = c.id 
			LEFT JOIN sucursales s ON p.sucursal = s.id 
			LEFT JOIN marcas m ON m.id = p.marca 
            WHERE p.anulado IS NULL
            ORDER BY p.id DESC;");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function Buscar($q)
	{
		try
		{
			 
			$q = '%'.$q.'%';
			$stm = $this->pdo->prepare("SELECT *, (SELECT imagen FROM imagenes WHERE id_producto = p.id limit 1) as imagen FROM productos p WHERE producto LIKE ? ORDER BY id DESC");

			$stm->execute(array($q));

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
			          ->prepare("SELECT *, (SELECT categoria FROM categorias c WHERE c.id= p.id_categoria) AS categoria FROM productos p WHERE p.id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function ObtenerPorCodigo($codigo)
	{
		try {
			$stm = $this->pdo
				->prepare("SELECT 
					  				*, 
									(SELECT categoria FROM categorias c WHERE c.id= p.id_categoria) AS categoria 
								FROM productos p 
					  			WHERE p.codigo = ?");


			$stm->execute(array($codigo));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function BuscarCodigo($codigo)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM productos p WHERE p.codigo = ?");
			          

			$stm->execute(array($codigo));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function ObtenerLimpio($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM productos p WHERE p.id = ?");
			          

			$stm->execute(array($id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Codigo($codigo)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT *, (SELECT categoria FROM categorias c WHERE c.id= p.id_categoria) AS categoria FROM productos p WHERE p.codigo = ?");
			          

			$stm->execute(array($codigo));
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
			          ->prepare("SELECT MAX(id) as id FROM productos LIMIT 1");
			          

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
			            ->prepare("UPDATE productos SET anulado = 1 WHERE id = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Restar($data)
	{
		try 
		{
			if($data->id_sucursal==1){
				$stock = 'stock_s1';
			}else if($data->id_sucursal == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}


			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) - ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function RestarTransferencia($data)
	{
		try 
		{
			if($data->emisor_transferencia==1){
				$stock = 'stock_s1';
			}else if($data->emisor_transferencia == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) - ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function RestarStock($data)
	{
		try 
		{

			if($data->id_sucursal == 1){
				$stock = 'stock_s1';
			}else if($data->id_sucursal == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) - ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function RestarConFactura($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET confactura = confactura - ? WHERE id = ?");			          

			$stm->execute(array($data->can_factura, $data->prod_factura));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function RestarSinFactura($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET sinfactura = sinfactura - ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad,  $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function ObtenerPodructoSucursal($id_producto, $emisor)
	{
		try 
		{
			if($emisor==1){
				$stock = 'stock_s1';
			}else if($emisor == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
			          ->prepare("SELECT $stock AS stock, producto FROM productos  WHERE id = ? ");
			          

			$stm->execute(array($id_producto));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function ObtenerSucursal($codigo, $sucursal)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT *, (SELECT categoria FROM categorias c WHERE c.id= p.id_categoria) AS categoria FROM productos p WHERE p.codigo = ? AND sucursal = ?");
			          

			$stm->execute(array($codigo, $sucursal));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function RestarId($id_producto, $cantidad)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");			          

			$stm->execute(array($cantidad, $id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function SumarId($id_producto, $cantidad)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");			          

			$stm->execute(array($cantidad, $id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function SumarPorCodigo($data)
	{ //SUMAR STOCK SEGUN UN CODIGO DE PRODUCTO
		// var_dump($data);
		try {

			if($data->destino_transferencia==1){
				$stock = 'stock_s1';
			}else if($data->destino_transferencia == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
				->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) + ? WHERE codigo = ?");

			$stm->execute(array($data->cantidad, $data->codigo));
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function Insertar($data)
	{
		try 
		{
		$sql = "INSERT INTO productos (codigo, id_categoria, producto, marca, descripcion, precio_costo, precio_minorista, precio_mayorista, stock_s1, stock_s2, stock_s3,stock_minimo, descuento_max, importado, iva, sucursal, anulado) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
					$data->codigo,
					$data->id_categoria,
				    $data->producto,
				    $data->marca, 
                    $data->descripcion,
                    $data->precio_costo,                        
                    $data->precio_minorista,
                    $data->precio_mayorista,
                    $data->stock_s1,
                    $data->stock_s2,
                    $data->stock_s3,
                    $data->stock_minimo,
                    $data->descuento_max,
                    $data->importado,
                    $data->iva,
                    $data->sucursal,
                    $data->anulado
                )
			);
		return "Agregado";
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

    public function Compra($data)
	{
		try 
		{
			if($data->id_sucursal == 1){
				$stock = 'stock_s1';
			}else if($data->id_sucursal == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) + ?, precio_minorista = ?, precio_mayorista = ? WHERE id = ?");			          

			$stm->execute(array(
				$data->cantidad,
				$data->precio_min,
				$data->precio_may,
				$data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Sumar($data)
	{
		try 
		{
			if($data->id_sucursal==1){
				$stock = 'stock_s1';
			}else if($data->id_sucursal == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}


			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) + ?  WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function SumarTransferencia($data)
	{
		try 
		{
			if($data->emisor_transferencia==1){
				$stock = 'stock_s1';
			}else if($data->emisor_transferencia == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) + ?  WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function SumarStock($data)
	{
		try 
		{
			if($data->id_sucursal == 1){
				$stock = 'stock_s1';
			}else if($data->id_sucursal == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) + ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function SumarSinfactura($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET sinfactura = sinfactura + ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function SumarConfactura($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET confactura = confactura + ? WHERE id = ?");			          

			$stm->execute(array($data->can_factura, $data->prod_factura));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function SumarProducto($data)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function SumarDevolucion($data)
	{
		try 
		{
			if($data->id_sucursal_producto == 1){
				$stock = 'stock_s1';
			}else if($data->id_sucursal_producto == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) + ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function AjustarStock($data)
	{
		try 
		{
			if($data->id_sucursal == 1){
				$stock = 'stock_s1';
			}else if($data->id_sucursal == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) + ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	public function AnularAjuste($data)
	{
		try 
		{
			if($data->id_sucursal == 1){
				$stock = 'stock_s1';
			}else if($data->id_sucursal == 2){
				$stock = 'stock_s2';
			}else{
				$stock = 'stock_s3';
			}

			$stm = $this->pdo
			            ->prepare("UPDATE productos SET $stock = IFNULL($stock, 0) + ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function GuardarStock($data)
	{
		try 
		{
			$sql = "UPDATE productos SET stock    = ? WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				    
                        $data->stock,
                        $data->id
					)
				);
	
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar($data)
	{
		try 
		{
			$sql = "UPDATE productos SET 

						codigo              = ?,
						id_categoria        = ?,
						producto      		= ?,
						marca      		    = ?,
						descripcion         = ?,
						precio_costo        = ?, 
						precio_minorista    = ?,
						precio_mayorista    = ?,
						precio_turista      = ?,
						apartir             = ?,
						fardo               = ?,
						preciob             = ?,
						precio_promo        = ?,
						desde               = ?,
						hasta               = ?,
						stock_minimo        = ?,
						descuento_max       = ?,
						importado           = ?,
						iva                 = ?,
						sucursal            = ?,
						sinfactura          = ?,
						confactura          = ?
						
				    WHERE id = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
				    	$data->codigo,
				    	$data->id_categoria,
				    	$data->producto,
				    	$data->marca, 
                        $data->descripcion,             
                        $data->precio_costo,                 
                        $data->precio_minorista,
                        $data->precio_mayorista,
                        $data->precio_turista,
                        $data->apartir,
                        $data->fardo,
                        $data->preciob,
                        $data->precio_promo,
                        $data->desde,
                        $data->hasta,
                        $data->stock_minimo,
                        $data->descuento_max,
                        $data->importado,
                        $data->iva,
                        $data->sucursal,
						$data->sinfactura,
						$data->confactura,
                        $data->id
					)
				);
		return "Modificado";
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function ActualizarPrecio($id_producto, $new_precio)

	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("UPDATE productos SET precio_costo = ? WHERE id = ?");			          

			$stm->execute(array($new_precio, $id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar(producto $data)
	{
		try 
		{
		$sql = "INSERT INTO productos (codigo, id_categoria, producto, marca, descripcion, precio_costo, precio_minorista, precio_mayorista, precio_turista, apartir, fardo, preciob, precio_promo, desde, hasta, stock_s1, stock_s2, stock_s3, stock_minimo, descuento_max, importado, iva, sucursal, anulado, sinfactura, confactura) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

		$this->pdo->prepare($sql)
		     ->execute(
				array(
					$data->codigo,
					$data->id_categoria,
				    $data->producto,
				    $data->marca, 
                    $data->descripcion,
                    $data->precio_costo,                        
                    $data->precio_minorista,
                    $data->precio_mayorista,
					$data->precio_turista,
                    $data->apartir,
                    $data->fardo,
                    $data->preciob,
                    $data->precio_promo,
                    $data->desde,
                    $data->hasta,
                    $data->stock_s1,
                    $data->stock_s2,
                    $data->stock_s3,
                    $data->stock_minimo,
                    $data->descuento_max,
                    $data->importado,
                    $data->iva,
                    $data->sucursal,
                    $data->anulado,
					$data->sinfactura,
					$data->confactura
                )
			);
		return "Agregado";
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function RestarFactura($data)
	{
		try {
			$stm = $this->pdo
				->prepare("UPDATE productos SET confactura = confactura - ? WHERE id = ?");

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function SumarStockFacturable($data)
	{
		try 
		{
			

			$stm = $this->pdo
			            ->prepare("UPDATE productos SET confactura = confactura + ? WHERE id = ?");			          

			$stm->execute(array($data->cantidad, $data->id_producto));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
}