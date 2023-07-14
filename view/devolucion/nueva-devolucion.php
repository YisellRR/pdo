<?php
$fecha = date("Y-m-d");
?>
<h1 class="page-header"> Nuevo ajuste </h1>
<div class="container">
    <div class="row">
        <form method="post">
            <input type="hidden" id="id_venta" name="id_venta" value="<?php echo $id_venta ?>">
            <input type="hidden" name="c" value="devolucion_tmp">
            <input type="hidden" name="a" value="guardar">
            <div class="row">
                <div class="form-group col-sm-3">
                    <label style="color: black">Cant. disponible <small>(Central.)</small></label>
                    <input type="text" step="any" id="suc_1" value=" " class="form-control" min="1" readonly>
                </div>
                <div class="form-group col-sm-3">
                    <label style="color: black">Cant. disponible <small>(Suc. 2)</small></label>
                    <input type="text" step="any" id="suc_2" value=" " class="form-control" min="1" readonly>
                </div>
                <div class="form-group col-sm-3">
                    <label style="color: black">Cant. disponible <small>(S. Paris)</small></label>
                    <input type="text" step="any" id="suc_3" value=" " class="form-control" min="1" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label>Producto</label>
                    <select name="id_producto" id="producto" class="form-control selectpicker" data-show-subtext="true" data-live-search="true" data-style="form-control" autofocus>
                        <option value="" disabled selected>--Seleccionar producto--</option>
                        <?php foreach ($this->producto->Listar() as $producto) : ?>
                            <option style="font-size: 18px" data-subtext="<?php echo $producto->codigo; ?>" value="<?php echo $producto->id; ?>"><?php echo $producto->producto . ' ( ' . $producto->stock . ' ) - ' . number_format($producto->precio_minorista, 2, ".", "."); ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <input class="btn btn-primary center-block" style="visibility:hidden;" type="submit" name="bton" value="Confirmar">
                </div>
                <div class="col-sm-3">
                    <label>Motivo</label>
                    <select name="descuento" id="motivo" class="form-control">
                        <option value="Ajuste">Ajuste</option>
                        <option value="Vencimiento">Vencimiento</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label>Precio</label>
                    <select name="precio_venta" class="form-control" id="precio_venta" min="0" >
                        <option id="precio_minorista" value="" > </option>
                        <option id="precio_costo" value="" > </option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label>Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" id="cantidad"  value="1" step="any">
                </div>
            </div>
        </form>
    </div>
</div>
<p> </p>
<div class="table-responsive">

    <table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla1">

        <thead>
            <tr style="background-color: #000; color:#fff">
                <th>Producto</th>
                <th>Costo por Unidad</th>
                <th>Cantidad</th>
                <th>Motivo</th>
                <th>Total (USD.)</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $subtotal = 0;
            foreach ($this->model->Listar() as $r) :
                $totalItem = $r->precio_venta * $r->cantidad;
                $subtotal += ($totalItem); ?>
                <tr>

                    <td><?php echo $r->producto; ?></td>
                    <td><?php echo number_format($r->precio_venta, 2, ",", "."); ?></td>
                    <td><?php echo $r->cantidad; ?></td>
                    <td><?php echo $r->descuento; ?></td>
                    <td>
                        <div id="precioTotal<?php echo $r->id; ?>" class="total_item">
                            <?php echo number_format($totalItem, 2, ",", "."); ?></div>
                    </td>
                    <td>
                        <a class="btn btn-danger" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=devolucion_tmp&a=Eliminar&id=<?php echo $r->id; ?>">Cancelar</a>
                    </td>
                </tr>
                <input type="hidden" id="clienteId" value="<?php echo $r->id_venta; ?>">
            <?php endforeach; ?>


            <tr>
                <td></td>
                <td>Total USD: <div id="total" style="font-size: 30px"><?php echo number_format($subtotal, 2, ",", ".") ?></div>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php if ($subtotal != 0) { ?>
        <div align="center">
            <a class="btn btn-lg btn-primary " href="#finalizarDevolucionModal" class="btn btn-success" data-toggle="modal" data-target="#finalizarDevolucionModal" data-c="devolucion">Finalizar (F4)</a>
        </div>
    <?php } ?>
</div>
</div>
</div>
</div>

<?php include("view/devolucion/finalizar-modal-devolucion.php"); ?>
<script type="text/javascript">
    $('#producto').on('change', function() {
        var id_producto = $(this).val();
        var id_venta = $("#id_venta").val();
        var url = "?c=producto&a=Buscar&id=" + id_producto;
        $.ajax({

            url: url,
            method: "POST",
            data: id_venta,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                var producto = JSON.parse(respuesta);
                $("#precio_costo").val(producto.precio_costo);
                $("#precio_costo").html(producto.precio_costo);
                $("#precio_minorista").val(producto.precio_minorista);
                $("#precio_minorista").html(producto.precio_minorista);
                $("#cantidad").focus();
            }

        })
    });

    $('#motivo').on('change', function() {
        var motivo = $(this).val();
        if (motivo == "Vencimiento") {
            $("#precio_costo").attr('selected', 'selected');
            $("#precio_minorista").removeAttr("selected");
        } else {
            $("#precio_minorista").attr('selected', 'selected');
            $("#precio_costo").removeAttr("selected");
        }
    });


    function calcular() {
        var subtotal = $('#subtotal').val();
        var descuento = $('#descuento').val();
        var iva = $('#iva').val();
        var reales = $('#reales').val();
        var dolares = $('#dolares').val();
        $('#descuentoval').val(descuento);
        $('#ivaval').val(iva);
        if (descuento == 0 && iva == 0) {
            var total = subtotal;
        }
        if (descuento == 0 && iva != 0) {
            var ivac = parseInt(subtotal * (iva / 100));
            var total = parseInt(subtotal) + ivac;
        }
        if (descuento != 0 && iva == 0) {
            var total = subtotal - (subtotal * (descuento / 100));
        }
        if (descuento != 0 && iva != 0) {
            var ivac = parseInt(subtotal * (iva / 100));
            var num = parseInt(subtotal) + ivac;
            var total = num - (subtotal * (descuento / 100));
        }
        var totalrs = (total / reales).toFixed(2);
        var totalus = (total / dolares).toFixed(2);
        var totalc = total.toLocaleString();

        $('.totaldesc').val(totalc);
        $('#totalrs').val(totalrs);
        $('#totalus').val(totalus);
    }

    $('#producto').on('change', function() {

        var valor = $(this).val();
        var id = $("#producto").val();
        var url = "?c=producto&a=buscar&id=" + id;

        console.log(id);
        console.log(valor);

        $.ajax({

            url: url,
            method: "POST",
            data: id,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                var producto = JSON.parse(respuesta);
                // if (valor >= producto.apartir) {
                //     console.log('precio mayorista');
                //     $("#precio_venta").val(producto.precio_mayorista);
                //     // $("#precio_venta").html(producto.precio_mayorista + " (Mayorista)");     
                // }
                $("#suc_1").val(producto.stock_s1);
                $("#suc_2").val(producto.stock_s2);
                $("#suc_3").val(producto.stock_s3);
            }

        })

    });

</script>