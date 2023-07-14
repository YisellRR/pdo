 
<?php $fecha = date("Y-m-d"); ?>
<h1 class="page-header">Detalles del presupuesto</h1> 
<div align="center" width="30%"> 
    
</div>

<div class="table-responsive">

<table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla1">

    <thead>
        <tr style="background-color: #5DACCD; color:#fff">
            <th>Producto</th>
            <th>Precio</th>
            <th>Cant</th>
            <th>Descuento</th>
            <th>Total (Usd.)</th>
        </tr>
    </thead>
    <tbody>
    <?php
     $subtotal=0;
     $sumatotal = 0;
     $id_presupuesto = $_GET['id_presupuesto'];
     foreach($this->presupuesto->ListarDetalle($id_presupuesto) as $r): 
        $total = (($r->precio_venta*$r->cantidad)-(($r->descuento)));
     ?>
        <tr>
            <td><?php echo $r->producto; ?></td>
            <td><?php echo number_format($r->precio_venta, 2, "," , "."); ?></td>
            <td><?php echo $r->cantidad; ?></td>
            <td><?php echo number_format($r->descuento, 2, "," , "."); ?></td>
            <td><?php echo number_format($total, 2, "," , "."); ?></td>
        </tr>
    <?php $sumatotal += $total ;endforeach; ?>
        
        
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total Usd: <div id="total" style="font-size: 20px"><?php echo number_format($sumatotal,2,",",".") ?></div></td>
        </tr>
    </tbody>
</table> 
</div> 
</div>
</div>
