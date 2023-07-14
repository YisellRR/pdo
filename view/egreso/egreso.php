<h1 class="page-header">Lista de egresos 
<a class="btn btn-info" href="#mesModal" data-toggle="modal" data-target="#mesModal" data-c="egreso">Informe</a>
<a class="btn btn-primary" href="#egresoModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="egreso">Agregar</a></h1>

<h3 id="filtrar" align="center">Filtros <i class="fas fa-angle-down"></i><i class="fas fa-angle-up"></i></h3>
<div class="row">
    <div class="col-sm-12">
        <div align="center" id="filtro">
            <form method="post">
                <div class="form-group col-md-1">
                
                </div>
                <div class="form-group col-md-3">
                    <label>Desde</label>
                    <input type="date" name="desde" value="<?php echo (isset($_GET['desde'])) ? $_GET['desde'] : ''; ?>" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label>Hasta</label>
                    <input type="date" name="hasta" value="<?php echo (isset($_GET['hasta'])) ? $_GET['hasta'] : ''; ?>" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>Sucursal</label>
                    <select name="id_sucursal" class="form-control">
                        <option value="" <?php echo ($_POST['id_sucursal'] == "") ? "selected" : ""; ?>>Sin seleccionar</option>
                        <option value="1" <?php echo ($_POST['id_sucursal'] == 1) ? "selected" : ""; ?>>Central</option>
                        <option value="2" <?php echo ($_POST['id_sucursal'] == 2) ? "selected" : ""; ?>>Sucursal 2</option>
                        <option value="3" <?php echo ($_POST['id_sucursal'] == 3) ? "selected" : ""; ?>>Shopping Paris</option>
                    </select>
                </div>
                 <div class="form-group col-md-1">
                </div>
               <center><input type="submit" name="filtro" value="Filtrar" class="btn btn-success"></center>
              
            </form>
        </div>
    </div>
</div>
<p> </p>
<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%">

    <thead>
        <tr style="background-color: black; color:#fff">
            <th>ID</th>
            <th>Proveedor</th>
        	<th>Fecha</th>
            <th>Categoría</th>
            <th>Concepto</th>
            <th>N° de comprobante</th>
            <th>Monto</th> 
            <th>Moneda</th>
            <th>Cambio</th>
            <th>Forma de pago</th>
            <th>Sucursal</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $lista =$this->model->Listar_rango($_POST['desde'],$_POST['hasta'],$_POST['id_sucursal']);
    
    foreach($lista as $r): 
    if(strlen($r->concepto)>=50){$concepto=substr($r->concepto, 0, 50)."...";}else{$concepto=$r->concepto;}
   
    if (($r->moneda == 'GS') && ($r->anulado == NULL)){
        $total_Gs = $total_Gs + ($r->monto);
    }else if (($r->moneda == 'RS') && ($r->anulado == NULL)){
        $total_RS = $total_RS + ($r->monto);
    }else if (($r->moneda == 'USD') && ($r->anulado == NULL)){
        $total_US = $total_US + ($r->monto);
    }

    ?>


        <tr class="click" <?php if($r->anulado){echo "style='color:gray'";} ?>>
          <?php $total +=$r->monto; ?>
            <td><?php echo $r->id; ?></td>
            <td><?php if($r->id_cliente==1) {?>
            Proveedor ocasional
            <?php }else{?>
            <?php echo $r->nombre; ?>
            <?php }?></td>
        	<td><?php echo date("d/m/Y H:i", strtotime($r->fecha)); ?></td>
            <td><?php echo $r->categoria; ?></td>
            <td title="<?php echo $r->concepto; ?>"><?php echo $concepto; ?></td>
            <td><?php echo $r->comprobante.' N° '.$r->nro_comprobante; ?></td>
            <td><?php echo number_format($r->monto,2,".",","); ?></td>
            <td><?php echo $r->moneda; ?></td>
            <td><?php echo $r->cambio; ?></td>
            <td><?php echo $r->forma_pago; ?></td>
            <td><?php echo $r->sucursal; ?></td>
            <td>
                 <a  class="btn btn-info" href="?c=egreso&a=ReciboEgreso&id=<?php echo $r->id; ?>">Recibo</a>
                <?php if (!$r->anulado): ?>
                    <?php if ($r->id_compra): ?>
                    <a href="#detallesCompraModal" class="btn btn-warning" data-toggle="modal" data-target="#detallesCompraModal" data-id="<?php echo $r->id_compra;?>">Compra</a>
                        <?php if ($r->id_acreedor): ?>
                        <a href="#pagosModal" class="btn btn-success" data-toggle="modal" data-target="#pagosModal" data-id="<?php echo $r->id_acreedor; ?>">Pagos</a>
                        <a  class="btn btn-danger delete" href="?c=egreso&a=EliminarPago&id=<?php echo $r->id; ?>&id_compra=<?php echo $r->id_compra; ?>"><i class="fas fa-trash-alt"></i></a>
                        <?php endif ?>
                    <?php elseif($r->id_acreedor): ?>
                    <a href="#pagosModal" class="btn btn-success" data-toggle="modal" data-target="#pagosModal" data-id="<?php echo $r->id_acreedor; ?>">Pagos</a>
                    <a  class="btn btn-danger delete" href="?c=egreso&a=EliminarPago&id=<?php echo $r->id; ?>&id_compra=<?php echo $r->id_compra; ?>"><i class="fas fa-trash-alt"></i></a>
                    <?php else: ?>
                    <a  class="btn btn-warning edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id;?>" data-c="egreso">Editar</a>
                    <a  class="btn btn-danger delete" href="?c=egreso&a=Eliminar&id=<?php echo $r->id; ?>&id_compra=<?php echo $r->id_compra; ?>">Eliminar</a>
                    <?php endif ?>
                <?php else: ?>
                    ANULADO
                <?php endif ?>
            </td>    
        </tr>
    <?php endforeach; ?>
    </tbody>
     <tfoot>
                
                <tr  style="background-color: #cccccc; line-height: 13px; font-size:20px">
                    
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>  
                    <td></td>
                    <td align="left"><b>Total USD.</b><br><b>Total RS.</b><br><b>Total GS.</b></td> 
                    <td align="right"><b><?php  echo number_format($total_US,2,".",","); echo ' US.'; ?></b>
                    <br><b><?php  echo number_format($total_RS,2,".",","); echo ' RS.'; ?></b>
                    <br><b><?php  echo number_format($total_Gs,2,".",","); echo ' GS.'; ?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
               
                
    </tfoot>
</table>

</div>
</div>
</div>
<?php include("view/crud-modal.php"); ?>
<?php include("view/compra/detalles-modal.php"); ?>
<?php include("view/egreso/mes-modal.php"); ?>
<?php include("view/acreedor/pagos-modal.php"); ?>

<script type="text/javascript">
    $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>