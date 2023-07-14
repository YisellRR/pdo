<style>
    @media screen and (min-width: 600px) {
    .btn_movil {
        display: none;
    }
    }

    .swal-wide{
    font-size: 15px;
    width: 270px !important;
    height: 70px ;
    }

</style>

<?php $fecha = date("Y-m-d"); ?>
<h1 class="page-header">Nuevo presupuesto<a class="btn btn-info " href="#clienteModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="cliente">+Cliente</a>
    <!--<a class="btn btn-lg btn-primary pull-right" href="#cierreModal" class="btn btn-success" data-toggle="modal" data-target="#cierreModal" data-c="venta">Cierre de caja</a>-->
</h1>
<!-- <div class="container"> -->
        <div class="row">
            <div class="form-group col-sm-3">
                <label style="color: black">Stock. <small>(Central.)</small></label>
                <input type="text" step="any" id="suc_1"  value=" " class="form-control" min="1" readonly>
            </div>
            <div class="form-group col-sm-3">
                <label style="color: black">Stock.<small>(Sucursal. 2)</small></label>
                <input type="text" step="any" id="suc_2"  value=" " class="form-control" min="1" readonly>
            </div>
            <div class="form-group col-sm-3">
                <label style="color: black">Stock. <small>(Shopping Paris)</small></label>
                <input type="text" step="any" id="suc_3"  value=" " class="form-control" min="1" readonly>
            </div>
        </div>
    <div class="row">
        <form method="post" id="productoNuevo">
            <div class="col-sm-3">
                <label>Producto</label>
                <select name="id_producto" id="producto" class="form-control selectpicker" data-show-subtext="true" data-live-search="true" data-style="form-control" title="-- Seleccione el producto --" autofocus required>
                    <?php foreach ($this->producto->Listar() as $producto) : $promo = ($producto->precio_promo > 0) ? " promo = " . number_format($producto->precio_promo, 0, ".", ".") : ""; ?>
                        <option style="font-size: 18px" data-subtext="<?php echo $producto->codigo; ?>" value="<?php echo $producto->id; ?>" <?php echo ($producto->stock < 1) ? 'disabledd' : ''; ?>><?php echo $producto->producto . ' ( ' . $producto->stock . ' ) - ' . number_format($producto->precio_minorista, 0, ".", ".") . $promo; ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-sm-2">
                <label>Sucursal</label>
                <select name="id_sucursal" class="form-control" id="sucursal">
                    <?php foreach ($this->sucursal->ListarSucursalAsignado() as $r) : ?>
                        <option value="<?php echo $r->id; ?>"><?php echo $r->sucursal; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-sm-1">
                <label>Cant.</label>
                <input type="number" name="cantidad" class="form-control" id="cantidad" value="1" step="any" min="0" >
            </div>
            <div class="col-sm-2">
                <label>Precio</label>
                <select name="precio_venta" class="form-control" id="precio_venta" step="0.01">
                    <option id="precio_minorista" value="precio_minorista"> Precio minorista</option>
                    <option id="precio_mayorista" value="precio_mayorista"> Precio mayorista</option>
                    <option id="precio_turista" value="precio_turista"> Precio turista</option>
                </select>
            </div>
            <div class="col-sm-2">
                 <label>MONTO</label>
                 <input type="number"  class="form-control" id="montos" step="any" value="" required>
                 
             </div>
            <div class="col-sm-2" >
                <label>Descuento</label>
                <input type="number" name="descuento" class="form-control" id="descuento" value="0" step="0.01">
                
            </div>
            <div class="btn_movil">
                <input class="btn btn-primary center-block" type="submit" name="bton" value="Confirmar">
            </div>
        </form>
    </div>
<!-- </div> -->
<p> </p>

<?php include("view/crud-modal.php"); ?>
<div class="table-responsive" id="tabla_items">

    <table class="table table-striped table-bordered display responsive nowrap" width="100%" id="tabla1">

        <thead>
            <tr style="background-color: #000; color:#fff">
                <th>Codigo</th>
                <th>Sucursal</th>
                <th>Producto</th>
                <th>Precio por Unidad</th>
                <th>Cantidad</th>
                <th>Descuento</th>
                <th>Total (Usd)</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $subtotal = 0;
            $totalItem = 0;
            foreach ($this->model->Listar() as $r) :
                $totalItem = (($r->precio_venta * $r->cantidad) - ($r->descuento));
                $subtotal += ($totalItem); ?>
                <tr>
                    <td><?php echo $r->codigo; ?></td>
                    <td><?php echo $r->sucursal; ?></td>
                    <td><?php echo $r->producto; ?></td>
                    <td><?php echo number_format($r->precio_venta, 2, ",", "."); ?></td>
                    <td><?php echo $r->cantidad; ?></td>
                    <td><?php echo $r->descuento; ?></td>
                    <td>
                        <div id="precioTotal<?php echo $r->id; ?>" class="total_item">
                            <?php echo number_format($totalItem, 2, ",", "."); ?></div>
                    </td>
                    <td>
                        <a class="btn btn-danger cancelar" id_item="<?php echo $r->id; ?>">Cancelar</a>
                    </td>
                </tr>
                <input type="hidden" id="clienteId" value="<?php echo $r->id_venta; ?>">
            <?php endforeach; ?>


            <tr>
                <td></td>
                 <td></td>
                <td></td>
                <td>Total USD: <div id="totalus" style="font-size: 30px"><img src="http://www.customicondesign.com/images/freeicons/flag/round-flag/48/USA.png"><?php echo number_format(($subtotal), 2, ",", ".") ?></div>
                </td>
                <td>Total Gs: <div id="total" style="font-size: 30px"><img src="http://www.customicondesign.com/images/freeicons/flag/round-flag/48/Paraguay.png"></i></i> <span id="total_gua"><?php echo number_format(($subtotal * $cierre->cot_dolar_tmp), 0, ",", ".") ?></span></div>
                </td>
                <td>Total Rs: <div id="totalrs" style="font-size: 30px"><img src="http://www.customicondesign.com/images/freeicons/flag/round-flag/48/Brazil.png"></i></i><span id="total_real"> <?php echo number_format(($subtotal * $cierre->cot_real_tmp), 2, ",", ".") ?></span></div>
                </td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php include("view/presupuesto/finalizar-modal.php"); ?>
    <?php if ($subtotal > 0) { ?>
        <div align="center">
            <a class="btn btn-lg btn-primary " href="#finalizarModal" class="btn btn-success" data-toggle="modal" data-target="#finalizarModal" data-c="presupuesto">Finalizar</a>
            <a class="btn btn-lg btn-danger delete" href="?c=presupuesto_tmp&a=CancelarPresupuesto">Cancelar Todo</a>
        </div>
    <?php } ?>
</div>
</div>

</div>
</div>
</div>

<script type="text/javascript">

    $('#montos').on('keyup', function() {
        
        var monto=$("#montos").val();
        var venta=$("#precio_venta").val();
        var descuento = venta - monto;
        
    $('#descuento').val(descuento);

    });

    $('#sucursal').on('change', function() {
        var id = $("#producto").val();
        var url = "?c=producto&a=buscar&id=" + id;
        $.ajax({

            url: url,
            method: "POST",
            data: id,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                var producto = JSON.parse(respuesta);
                
                var cantidad_sucursal = $('#sucursal').val();
               
                if(cantidad_sucursal == 3){
                    $("#cantidad").attr("max",producto.stock_s3);
                }else if(cantidad_sucursal == 2){
                    $("#cantidad").attr("max",producto.stock_s2);
                }else{
                    $("#cantidad").attr("max",producto.stock_s1);
                }
               
                $("#cantidad").select();
            }
            })
    
    });
    $('.cancelar').on('click', function() {
        var datos = {};
        datos.id = $(this).attr("id_item");
        $.ajax({
            method: "POST",
            url: "?c=presupuesto_tmp&a=eliminar",
            data: datos,
            success: function(data) {
                $("#tabla_items").html(data)
            }
        });
    });

    $('#productoNuevo').submit(function(e) {

        e.preventDefault();

        var datos = $(this).serialize();
        var tipo_venta = $('#precio_venta').val();
        console.log(tipo_venta);

        var existencia = false;
        //valid
        $.ajax({
            method: "POST",
            url: "?c=producto&a=verificarExistencia",
            data: datos,
            async: false,
            success: function(data) {
                existencia = JSON.parse(data);

            }
        });

        // console.log(existencia);

        if(! existencia.existencia) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        customClass: 'swal-wide',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                        })

                        Toast.fire({
                        icon: 'error',
                        title: 'La cantidad sobrepasa al stock'
                    })

                    return false
                }


        
        $.ajax({
            method: "POST",
            url: "?c=presupuesto_tmp&a=guardar",
            data: datos,
            success: function(data) {
                $("#tabla_items").html(data);
                $("#productoNuevo")[0].reset();
                $('#producto').selectpicker('refresh');
                $("#precio_minorista").html("");
                $("#producto").focus();
                $('.selectpicker').selectpicker();
            }
        });
    });


    $('#finalizarModal').on('show.bs.modal', function(event) {
        $("#monto_efectivo").focus();
    })

    $('#producto').on('change', function() {
        var id = $(this).val();
        var url = "?c=producto&a=buscar&id=" + id;
        $.ajax({

            url: url,
            method: "POST",
            data: id,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                var producto = JSON.parse(respuesta);
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                today = yyyy + '-' + mm + '-' + dd;
                console.log(producto.precio_promo + '> 0 && ' + producto.desde + '<=' + today + '<=' + producto.hasta);
                if (producto.precio_promo > 0 && producto.desde <= today && producto.hasta >= today) {
                    $("#precio_minorista").val(producto.precio_promo);
                    $("#precio_minorista").html(producto.precio_promo);
                } else {
                    $("#precio_minorista").val(producto.precio_minorista);
                    $("#precio_minorista").html(producto.precio_minorista);
                }
                $("#precio_minorista").val(producto.precio_minorista);
                $("#precio_minorista").html(producto.precio_minorista+ " (Minorista)");
                $("#precio_mayorista").val(producto.precio_mayorista);
                $("#precio_mayorista").html(producto.precio_mayorista + " (Mayorista)");
                $("#precio_turista").html(producto.precio_turista + " (turista)");
                //$("#descuento").attr("max",producto.descuento_max);
                //$("#cantidad").attr("max",producto.stock);
                var cantidad_sucursal = $('#sucursal').val();
                //console.log(cantidad_sucursal);
                if(cantidad_sucursal == 3){
                    $("#cantidad").attr("max",producto.stock_s3);
                }else if(cantidad_sucursal == 2){
                    $("#cantidad").attr("max",producto.stock_s2);
                }else{
                    $("#cantidad").attr("max",producto.stock_s1);
                }
                $("#cantidad").select();
                //$('#productoNuevo').submit();
            }

        })
    });

    $('#cantidad').on('change', function() {

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
                if (valor >= producto.apartir) {
                    console.log('precio mayorista');
                    $("#precio_venta").val(producto.precio_mayorista);
                    // $("#precio_venta").html(producto.precio_mayorista + " (Mayorista)");     
                }
            }

        })

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