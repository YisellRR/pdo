
<h1 class="page-header">Lista de cierres de caja en efectivo <a class="btn btn-primary" href="#cajaModal" class="btn btn-primary" data-toggle="modal" data-target="#cajaModal">Informe de caja</a></h1>
<h3 id="filtrar" align="center">Filtrar por fecha <i class="fas fa-angle-down"></i><i class="fas fa-angle-up" style="display: none"></i></h3>
<div class="container">
  <div class="row">
    <div class="col">
        <div align="center" id="filtro">
            <form method="get" class="form-inline">
                <input type="hidden" name="c" value="cierre">
                <div class="form-group">
                    <label>Desde</label>
                    <input type="datetime-local" name="desde" value="<?php echo (isset($_GET['desde']))? $_GET['desde']:''; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Hasta</label>
                    <input type="datetime-local" name="hasta" value="<?php echo (isset($_GET['hasta']))? $_GET['hasta']:''; ?>" class="form-control" required>
                </div>
                <input type="submit" name="filtro" value="Filtrar" class="btn btn-success"> 
            </form>
        </div>
    </div>
  </div>
</div>
<p> </p>
<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th>Usuario</th>
        	<th>Inicio</th>
            <th>Fin</th>
            <th>Apertura</th>
            <th>Ingreso(GS)</th>
            <th>Ingreso(RS)</th>
            <th>Ingreso(USD)</th>
            <th>Egreso(GS)</th>
            <th>Egreso(RS)</th>
            <th>Egreso(USD)</th>
            <th>Cierre(GS)</th>
            <th>Cierre(RS)</th>
            <th>Cierre(USD)</th>
            <th>Diferencia(GS)</th>
            <th>Diferencia(RS)</th>
            <th>Diferencia(USD)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $sumaSistema = 0;
    $sumaCierre = 0;
    $desde = (isset($_GET["desde"]))? $_GET["desde"]:0;
    $hasta = (isset($_GET["hasta"]))? $_GET["hasta"]:0;
    foreach($this->model->Listar($desde, $hasta) as $r): ?>
        <tr class="click">
            <td><?php echo $r->user; ?></td>
        	<td><?php echo date("d/m/Y H:i", strtotime($r->fecha_apertura)); ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($r->fecha_cierre)); ?></td>
            <td><?php echo number_format($r->monto_apertura,2,".",","); ?></td>
            <td><?php echo number_format($r->ingreso_gs,2,".",","); ?></td>
            <td><?php echo number_format($r->ingreso_rs,2,".",","); ?></td>
            <td><?php echo number_format($r->ingreso_usd,2,".",","); ?></td>
            <td><?php echo number_format($r->egreso_gs,2,".",","); ?></td>
            <td><?php echo number_format($r->egreso_rs,2,".",","); ?></td>
            <td><?php echo number_format($r->egreso_usd,2,".",","); ?></td>
            <td><?php echo number_format($r->monto_cierre,2,".",","); ?></td>
            <td><?php echo number_format($r->monto_reales,2,".",","); ?></td>
            <td><?php echo number_format($r->monto_dolares,2,".",","); ?></td>
            <td><?php echo number_format((($r->ingreso_gs-$r->egreso_gs)-$r->monto_cierre),2,".",","); ?></td>
            <td><?php echo number_format((($r->ingreso_rs-$r->egreso_rs)-$r->monto_reales),2,".",","); ?></td>
            <td><?php echo number_format((($r->ingreso_usd-$r->egreso_usd)-$r->monto_dolares),2,".",","); ?></td>
            <td>
                <a href="?c=cierre&a=detalles&id=<?php echo $r->id; ?>" class="btn btn-warning">Ver detalles</a>
                <a href="?c=cierre&a=cierrepdf&id_cierre=<?php echo $r->id; ?>" class="btn btn-info">Informe</a>
            </td>    
        </tr>
    <?php 
    $sumadiferencia += ($r->monto_cierre-(($r->monto_sistema+$r->monto_apertura)-$r->monto_egreso));
    $sumagsi += $r->ingreso_gs;
    $sumarsi += $r->ingreso_rs;
    $sumausdi += $r->ingreso_usd;

    $sumagse += $r->egreso_gs;
    $sumarse += $r->egreso_rs;
    $sumausde += $r->egreso_usd;

    $sumaCierre += $r->monto_cierre;
    $sumaCierreRS += $r->monto_reales;
    $sumaCierreUSD += $r->monto_dolares;
    endforeach; ?>
    </tbody>
    <tfoot>
        <tr style="background-color: black; color:#fff">
            <th>TOTAL</th>
        	<th></th>
            <th></th>
            <th></th>
            <th><?php echo number_format($sumagsi,2,".",","); ?></th>
            <th><?php echo number_format($sumarsi,2,".",","); ?></th>
            <th><?php echo number_format($sumausdi,2,".",","); ?></th>
            <th><?php echo number_format($sumagse,2,".",","); ?></th>
            <th><?php echo number_format($sumarse,2,".",","); ?></th>
            <th><?php echo number_format($sumausde,2,".",","); ?></th>
            <th><?php echo number_format($sumaCierre,2,".",","); ?></th>
            <th><?php echo number_format($sumaCierreRS,2,".",","); ?></th>
            <th><?php echo number_format($sumaCierreUSD,2,".",","); ?></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
</div>
</div>
</div>
<?php include("view/cierre/rango-modal.php"); ?>
<script type="text/javascript">
    $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>