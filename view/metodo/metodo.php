<h1 class="page-header">Lista de métodos de pago</h1>
<a class="btn btn-primary pull-right" href="#imagenModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="metodo">Agregar</a>
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th>Método</th>
            <th>Ingresos(USD)</th>
            <th>Ingresos(GS)</th>
            <th>Ingresos(RS)</th>
            <th>Egresos (USD)</th>
            <th>Egresos (GS)</th>
            <th>Egresos (RS)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($this->model->Listar() as $r): 
        $sumin_us += $r->ingresos_USD;
        $sumin_gs += $r->ingresos_GS;
        $sumin_rs += $r->ingresos_RS;

        $sumeg_us += $r->egresos_USD;
        $sumeg_gs += $r->egresos_GS;
        $sumeg_rs += $r->egresos_RS;

        ?>
        <tr class="click">
            <td><a class="btn btn-default" href="?c=metodo&a=movimientos&metodo=<?php echo $r->metodo; ?>"><?php echo $r->metodo; ?></a></td>
            <td><?php echo number_format($r->ingresos_USD, 2, "," , "."); ?></td>
            <td><?php echo number_format($r->ingresos_GS, 2, "," , "."); ?></td>
            <td><?php echo number_format($r->ingresos_RS, 2, "," , "."); ?></td>
            <td><?php echo number_format($r->egresos_USD, 2, "," , "."); ?></td>
            <td><?php echo number_format($r->egresos_GS, 2, "," , "."); ?></td>
            <td><?php echo number_format($r->egresos_RS, 2, "," , "."); ?></td>
            <td>
                <a class="btn btn-danger delete" href="?c=metodo&a=Eliminar&id=<?php echo $r->id; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr style="background-color: #cccccc;">
            <th>TOTAL:</th>
            <th><?php echo number_format($sumin_us, 2, "," , ".").' USD';?></th>
            <th><?php echo number_format($sumin_gs, 2, "," , ".").' GS' ;?></th>
            <th><?php echo number_format($sumin_rs, 2, "," , ".").' RS' ;?></th>
            <th><?php echo number_format($sumeg_us, 2, "," , ".").' USD' ;?></th>
            <th><?php echo number_format($sumeg_gs, 2, "," , ".").' GS' ;?></th>
            <th><?php echo number_format($sumeg_rs, 2, "," , ".").' RS' ;?></th>
            <th></th>
        </tr>


    </tfoot>
</table>
</div>
</div>
</div> 
<?php include("view/crud-modal.php"); ?>

