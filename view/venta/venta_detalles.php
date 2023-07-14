 
<?php $fecha = date("Y-m-d"); ?>
<h1 class="page-header">Detalles de la venta</h1> 
<div align="center" width="30%"> 
    
</div>

<div class="table-responsive">

<table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla1">

    <thead>
        <tr style="background-color: #5DACCD; color:#fff">
            <th>Sucursal</th>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cant</th>
            <th>Descuento</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php
     $subtotal=0;
     $sumatotal = 0;
     $id_venta = $_GET['id'];
     foreach($this->venta->Listar($id_venta) as $r): 
        $total = (($r->precio_venta*$r->cantidad)-($r->descuento));
     ?>
        <tr>
            <td><?php echo $r->sucursal; ?></td>
            <td><?php echo $r->producto; ?></td>
            <td><?php echo number_format($r->precio_venta, 2, "," , "."); ?></td>
            <td><?php echo $r->cantidad; ?></td>
            <td><?php echo $r->descuento; ?></td>
            <td><?php echo number_format($total, 2, "," , "."); ?></td>
        </tr>
    <?php $sumatotal += $total ;endforeach; ?>
        
        
        <tr>
             <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total USD: <div id="total" style="font-size: 20px"><?php echo number_format($sumatotal,2,",",".") ?></div></td>
        </tr>
    </tbody>
</table> 
</div> 
</div>
</div>
