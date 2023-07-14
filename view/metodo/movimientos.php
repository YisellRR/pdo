<h1 class="page-header">Movimientos</h1>
<br><br><br>

<p> </p>
<table class="table table-bordered display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: #cccccc; color:black">
            <th>N°</th>
        	<th>Fecha</th>
            <th>Categoría</th>
            <th>Concepto</th>
            <th>N° de comprobante</th>
            <th>Ingreso</th>
            <th>Egreso</th>
            <th>Moneda</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sumaTotal = 0; 
    $c=1;
    $sumin_gs = 0;
    $sumin_us = 0;
    $sumin_rs = 0;
    $sumeg_gs = 0;
    $sumeg_us = 0;
    $sumeg_rs = 0;
    foreach($this->model->ListarMovimientos($_GET['metodo']) as $r):
        
    if(strlen($r->concepto)>=50){$concepto=substr($r->concepto, 0, 50)."...";}else{$concepto=$r->concepto;}
    if($r->monto>0){
        $ingreso = number_format($r->monto,2,".",",");
        $egreso = "";

        if($r->moneda == 'GS'){
            $sumin_gs += $r->monto;
        }else if($r->moneda == 'USD'){
            $sumin_us += $r->monto;
        }else if($r->moneda == 'RS'){
            $sumin_rs += $r->monto;
        }

    }else{
        $ingreso = "";
        $egreso = number_format(($r->monto*-1),2,".",",");

        if($r->moneda == 'GS'){
            $sumeg_gs += ($r->monto*-1);
        }else if($r->moneda == 'USD'){
            $sumeg_us += ($r->monto*-1);
        }else if($r->moneda == 'RS'){
            $sumeg_rs += ($r->monto*-1);
        }

    } ?>
        <tr class="click" <?php if($r->anulado){echo "style='color:red'";}elseif($r->descuento>0){echo "style='color:#F39C12'";} ?>>
            <td><?php echo $c++; ?></td>
        	<td><?php echo date("d/m/Y H:i", strtotime($r->fecha)); ?></td>
            <td><?php echo $r->categoria; ?></td>
            <td title="<?php echo $r->concepto; ?>"><?php echo $concepto; ?></td>
            <td><?php echo $r->comprobante; ?></td>
            <td><?php echo $ingreso; ?></td>
            <td><?php echo $egreso; ?></td>
            <td><?php echo $r->moneda; ?></td>
        </tr>
    <?php $sumaTotal += $r->monto; endforeach; ?>
    </tbody>
    <tfoot>

    <?php 
        $suma_total = array("Total USD"=>"$sumin_us", "Total GS"=>"$sumin_gs", "Total RS"=>"$sumin_rs");
        foreach ($suma_total as $nombre => $sumaT):
    ?>

        <tr style="background-color: #dddddd; ">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><?php echo $nombre ;?></th>
            <th><?php echo number_format($sumaT,2,".",","); ?></th> 
            <th></th>
            <th></th>
        </tr>

    <?php endforeach ;?>
    <?php 
        $suma_total = array("Total USD"=>"$sumeg_us", "Total GS"=>"$sumeg_gs", "Total RS"=>"$sumeg_rs");
        foreach ($suma_total as $nombre => $sumaT):
    ?>

        <tr style="background-color: #dddddd; ">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><?php echo $nombre ;?></th>
            <th></th>
            <th><?php echo number_format($sumaT,2,".",","); ?></th> 
            <th></th>
        </tr>

    <?php endforeach ;?>
    </tfoot>
</table>
</div>
</div>
</div>
