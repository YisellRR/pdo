<h1 class="page-header">Lista de ingresos </h1>
<a class="btn btn-primary pull-right" href="#ingresoModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="ingreso">Agregar</a>
<br><br><br>
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

<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%" border-collapse: collapse;>

    <thead>
        <tr style="background-color: black; color:#fff">
        	<th>Fecha</th>
            <th>Persona</th>
            <th>Categoría</th>
            <th>Concepto</th>
            <th>Comprobante</th>
            <th>Monto</th> 
            <th>Moneda</th> 
            <th>Cambio</th>
            <th>Forma de pago</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $fecha=date('Y-m-d');
    $lista = (isset($_POST['desde']))? $this->model->Listar_rango($_POST['desde'],$_POST['hasta'],$_POST['id_sucursal']):$this->model->Listar($fecha);
    
    foreach($lista as $r): 

        if(strlen($r->concepto)>=50){$concepto=substr($r->concepto, 0, 50)."...";}else {$concepto=$r->concepto;} 
        
        if (($r->moneda == 'GS') && ($r->anulado == NULL)){
            $total_Gs = $total_Gs + ($r->monto);
        }else if (($r->moneda == 'RS') && ($r->anulado == NULL)){
            $total_RS = $total_RS + ($r->monto);
        }else if (($r->moneda == 'USD') && ($r->anulado == NULL)){
            $total_US = $total_US + ($r->monto);
        }
        
        ?>
        <tr class="click" <?php if($r->anulado){echo "style='color:gray'";}?>>
        	<td><?php echo date("d/m/Y H:i", strtotime($r->fecha)); ?></td>
            <td><?php echo $r->nombre; ?></td>
            <td><?php echo $r->categoria; ?></td>
            <td title="<?php echo $r->concepto; ?>"><?php echo $concepto; ?></td>
            <td><?php echo $r->comprobante.' N° '.$r->nro_comprobante; ?></td>
            <td><?php echo number_format($r->monto,2,".",","); ?></td>
            <td><?php echo $r->moneda; ?></td>
            <td><?php echo $r->cambio; ?></td>
            <td><?php echo $r->forma_pago; ?></td>
            <td >
                 <?php if ($r->id_gift == null || ($r->anulado==1)): ?>
                    <?php if (!$r->anulado): ?>
                        <?php if ($r->id_venta): ?>
                        <a href="#detallesModal" class="btn btn-warning" data-toggle="modal" data-target="#detallesModal" data-id="<?php echo $r->id_venta; ?>">Venta</a>
                            <?php if ($r->id_deuda): ?>
                            <a href="#cobrosModal" class="btn btn-success" data-toggle="modal" data-target="#cobrosModal" data-id="<?php echo $r->id_deuda; ?>">Cobros</a>
                            <a  class="btn btn-danger delete" href="?c=ingreso&a=EliminarPago&id=<?php echo $r->id; ?>&id_venta=<?php echo $r->id_venta; ?>"><i class="fas fa-trash-alt"></i></a>
                            <?php endif ?>
                        <?php elseif($r->id_deuda): ?>
                        <a href="#cobrosModal" class="btn btn-success" data-toggle="modal" data-target="#cobrosModal" data-id="<?php echo $r->id_deuda; ?>">Cobros</a>
                        <a  class="btn btn-danger delete" href="?c=ingreso&a=EliminarPago&id=<?php echo $r->id; ?>&id_venta=<?php echo $r->id_venta; ?>"><i class="fas fa-trash-alt"></i></a>
                        <?php else: ?>
                        <a  class="btn btn-warning edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id;?>" data-c="ingreso">Editar</a>
                        <a  class="btn btn-danger delete" href="?c=ingreso&a=Eliminar&id=<?php echo $r->id; ?>&id_venta=<?php echo $r->id_venta; ?>">Eliminar</a>
                        <?php endif ?>
                    <?php else: ?>
                        ANULADO
                    <?php endif ?>
                <?php endif ?>
            </td>    
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
                
                <tr  style="background-color: #cccccc;">
                    <td></td>
                    <td></td>
                    <td></td>  
                    <td></td>
                    <td><b>Total USD.</b></td> 
                    <td><b><?php  echo number_format($total_US,2,".",","); echo ' USD'; ?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr  style="background-color: #dddddd;">
                    <td></td>
                    <td></td>
                    <td></td> 
                    <td></td> 
                    <td><b>Total Rs.</b></td> 
                    <td><b><?php  echo number_format($total_RS,2,".",","); echo ' RS'; ?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr  style="background-color: #cccccc;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Total Gs.</b></td>
                    <td><b><?php  echo number_format($total_Gs,2,".",","); echo ' GS.'; ?></b></td> 
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
<?php include("view/venta/detalles-modal.php"); ?>
<?php include("view/deuda/cobros-modal.php"); ?>

<script type="text/javascript">
    $( "#filtrar" ).click(function() {
      $("#filtro").toggle("slow");
      $("i").toggle();
    });
</script>