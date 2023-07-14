 
<?php $fecha = date("Y-m-d"); ?>
<h1 class="page-header">Detalles de pagos</h1> 
<div align="center" width="30%"> 
    
</div>

<div class="table-responsive">

<table class="table table-striped table-bordered display responsive nowrap">

    <thead>
        <tr style="background-color: #5DACCD; color:#fff">
            <th>Fecha</th>
            <th>Comprobante</th>
            <th>Monto</th>
        </tr>
    </thead>
    <tbody>
    <?php
     $sumatotal = 0;
     $id_acreedor = $_GET['acreedor'];
     foreach($this->model->ListarAcreedor($id_acreedor) as $r):  ?>
        <tr>
            
            <td><?php echo date("d/m/Y", strtotime($r->fecha)); ?></td>
            <td><?php echo $r->comprobante; ?></td>
            <td><?php echo number_format($r->monto, 2, "," , "."); ?></td>
        </tr>
    <?php $sumatotal += $r->monto ;endforeach; ?>
        
        
        <tr>
            <td align="right" colspan="2"><b>Total USD:</b></td>
            <td><div id="total" style="font-size: 20px"><?php echo number_format($sumatotal,2,",",".") ?></div></td>
        </tr>
    </tbody>
</table> 
</div> 
</div>
</div>
