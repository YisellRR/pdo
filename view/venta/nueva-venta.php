<style>
    .swal-wide{
    font-size: 15px;
    width: 270px !important;
    height: 70px ;
    }
</style>
<?php $fecha = date("Y-m-d"); ?>
<?php $cierre = $this->cierre->Ultimo(); ?>

<h1 class="page-header">Nueva venta <a class="btn btn-info " href="#clienteModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="cliente">+Cliente</a>
    <a class="btn btn-lg btn-primary pull-right" href="#cierreModal" class="btn btn-success" data-toggle="modal" data-target="#cierreModal" data-c="venta">Cierre de caja</a>
</h1>

<!--<div class="container">-->
    <form method="post" id="productoNuevo">
        <div class="row">
            <div class="form-group col-sm-2">
                <label style="color: black">Guaraníes <small>(1 USD a Gs. )</small></label>
                <input type="number" id="dolares" name="cot_dolar" value="<?php echo $cierre->cot_dolar_tmp; ?>" class="form-control" min="1">
            </div>

            <div class="form-group col-sm-2">
                <label style="color: black">Reales <small>(1 USD a Rs.)</small></label>
                <input type="number" step="any" id="reales" name="cot_real" value="<?php echo $cierre->cot_real_tmp; ?>" class="form-control" min="1">
            </div>
            <div class="form-group col-sm-3">
                <label style="color: black">Cant. disponible <small>(Central.)</small></label>
                <input type="text" step="any" id="suc_1"  value=" " class="form-control" min="1" readonly>
            </div>
            <div class="form-group col-sm-3">
                <label style="color: black">Cant. disponible <small>(Suc. 2)</small></label>
                <input type="text" step="any" id="suc_2"  value=" " class="form-control" min="1" readonly>
            </div>
            <div class="form-group col-sm-2">
                <label style="color: black">Disponible <small>(s. Paris)</small></label>
                <input type="text" step="any" id="suc_3"  value=" " class="form-control" min="1" readonly>
            </div>
        </div>
        <div class="row">
             <div class="col-sm-3">
                 <label>Producto</label>
                 <select name="id_producto" id="producto" class="form-control selectpicker" data-show-subtext="true" data-live-search="true" data-style="form-control" title="-- Seleccione el producto --" autofocus required>
                     <?php foreach ($this->producto->Listar() as $producto) : $promo = ($producto->precio_promo > 0) ? " promo = " . number_format($producto->precio_promo, 0, ".", ".") : ""; ?>
                         <option style="font-size: 18px" data-subtext="<?php echo $producto->codigo; ?>" value="<?php echo $producto->id; ?>" <?php echo ($producto->stock < 1) ? 'disabledd' : ''; ?>><?php echo $producto->producto; ?> </option>
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
                 <input type="number" name="cantidad" class="form-control" id="cantidad" value="1" step="any" min="0">
             </div>

             <div class="col-sm-2">
                 <label>Precio</label>
                 <select name="precio_venta" class="form-control" id="precio_venta" >
                     <option id="precio_minorista" value="precio_minorista"> Precio minorista</option>
                     <option id="precio_mayorista" value="precio_mayorista"> Precio mayorista</option>
                     <option id="precio_turista" value="precio_turista"> Precio turista</option>
                 </select>
             </div>
              <div class="col-sm-2">
                 <label>MONTO</label>
                 <input type="number"  class="form-control" id="montos" step="any" value="" required>
                 
             </div>
             <div class="col-sm-2">
                 <label>Descuento</label>
                 <input type="float" name="descuento" class="form-control" id="descuento" value="0">
                 
             </div>
             <div class="btn_movil">
                <input class="btn btn-primary center-block" style="visibility:hidden;" type="submit" name="bton" value="Confirmar">
            </div>
         </div>
     </form>
 <!--</div>-->

<p> </p>

<?php include("view/crud-modal.php"); ?>
<div class="table-responsive" id="tabla_items">


<?php  include("view/venta/tabla_venta.php"); ?>

</div>

</div>
</div>
</div>

<?php // include("view/venta/finalizar-modal.php"); ?>
<?php include("view/venta/cierre-modal.php"); ?>

<div id="finalizarModalNew" class="modal fade bd-example-modal-lg">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-body" id="modal-finalizar">
                 <i class="fas fa-sync fa-spin"></i>
             </div>
             <div class="modal-footer">
                 <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
             </div>
         </div>
     </div>
 </div>

<script type="text/javascript">

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

   $('#montos').on('keyup', function() {
    
        var monto=$("#montos").val();
        var venta=$("#precio_venta").val();
        var descuento = venta - monto;
      
        
       $('#descuento').val(descuento);

    });
    
    $('#finalizarModalNew').on('show.bs.modal', function(event) {
         var url = "?c=venta_tmp&a=finalizarModal";
         $.ajax({

             url: url,
             method: "POST",
             cache: false,
             contentType: false,
             processData: false,
             success: function(respuesta) {
                 $("#modal-finalizar").html(respuesta);
             }

         })
     });

    $('.prod_factura').on('change', function() {
        var prod_factura = $(this).val();
        var id = parseInt($(this).attr("id"));
        //console.log('id=' + id);
        //  alert(id);
        var url = "?c=venta_tmp&a=ProductoFactura&id=" + id + "&prod_factura=" + prod_factura;

        $.ajax({

            url: url,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                //$("#precioTotal"+idItem).html(precio);
                //$("#tabla_items").html(respuesta);
                //location.reload(true);
                //alert(respuesta);
            }

        })
    });
    
    


    // $('.can_factura').on('change', function() {

    //     var can_factura = $(this).val();
    //     var id = parseInt($(this).attr("id"));
    //     var url = "?c=venta_tmp&a=CantidadFactura&id=" + id + "&can_factura=" + can_factura;

    //     $.ajax({

    //         url: url,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         success: function(respuesta) {
    //             //$("#precioTotal"+idItem).html(precio);
    //             //$("#tabla_items").html(respuesta);
    //             //location.reload(true);
    //             //alert(respuesta);
    //         }

    //     })
    // });

    $('#dolares').on('change', function() {
        // $("#dolares").val(this.val());
        $("#cot_dolar").val($(this).val());
        var total_formatted = ($(this).val() * <?php echo $subtotal; ?>).toLocaleString('en-US', {});

        $("#total_gua").text(total_formatted);
        $("#total_gstabla").text(total_formatted);
        $.ajax({
            method: "POST",
            url: "?c=cierre&a=ActualizarCotizacion",
            data: {
                cot_dolar_tmp: $(this).val(),
            },
            success: function(data) {
                console.log("cotizacion actualizada gs");
            }
        });

    });

    $('#reales').on('change', function() {
        $("#cot_real").val($(this).val());
        var total_real = ($(this).val() * <?php echo $subtotal; ?>).toLocaleString('en-US', {})
        $("#total_real").text(total_real);
        $("#total_rstabla").text(total_real);
        $.ajax({
            method: "POST",
            url: "?c=cierre&a=ActualizarCotizacion",
            data: {
                cot_real_tmp: $(this).val(),
            },
            success: function(data) {
                console.log("cotizacion actualizada real");
            }
        });

    });


    $('.cancelar').on('click', function() {
        var datos = {};
        datos.id = $(this).attr("id_item");
        $.ajax({
            method: "POST",
            url: "?c=venta_tmp&a=eliminar",
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
        // console.log(tipo_venta);

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

        // if(! existencia.existencia) {
        //             const Toast = Swal.mixin({
        //                 toast: true,
        //                 position: 'top-end',
        //                 customClass: 'swal-wide',
        //                 showConfirmButton: false,
        //                 timer: 3000,
        //                 timerProgressBar: true,
        //                 didOpen: (toast) => {
        //                     toast.addEventListener('mouseenter', Swal.stopTimer)
        //                     toast.addEventListener('mouseleave', Swal.resumeTimer)
        //                 }
        //                 })

        //                 Toast.fire({
        //                 icon: 'error',
        //                 title: 'La cantidad sobrepasa al stock'
        //             })

        //             return false
        //         }

        
        $.ajax({
            method: "POST",
            url: "?c=venta_tmp&a=guardar&tipo_venta" + tipo_venta,
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
                //console.log(producto.precio_promo + '> 0 && ' + producto.desde + '<=' + today + '<=' + producto.hasta);
                if (producto.precio_promo > 0 && producto.desde <= today && producto.hasta >= today) {
                    $("#precio_minorista").val(producto.precio_promo);
                    $("#precio_minorista").html(producto.precio_promo + " (Promo)");
                } else {
                    $("#precio_minorista").val(producto.precio_minorista);
                    $("#precio_minorista").html(producto.precio_minorista+ " (Minorista)");
                }

                //$("#montos").attr("min",producto.precio_turista); // que el precio atacado sea el precio minimo 
                $("#precio_mayorista").val(producto.precio_mayorista);
                $("#precio_mayorista").html(producto.precio_mayorista + " (Mayorista)");
                $("#precio_turista").val(producto.precio_turista);
                $("#precio_turista").html(producto.precio_turista + " (turista)");
                $("#precio_minorista").val(producto.precio_minorista);
                $("#precio_minorista").html(producto.precio_minorista+ " (Minorista)");
                //$("#descuento").attr("max",producto.descuento_max);
                
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
                
                if(parseFloat(producto.ultimo_precio) > 0){// solo poder seleccionar si esta cargado el precio
                    $("#ultimo_precio").val(producto.ultimo_precio);
                    $("#ultimo_precio").html(producto.ultimo_precio + " (Último)");
                    $("#ultimo_precio").attr("disabled", false);
                    //puede que el ultimo precio sea menor que el atacado, entonces usar ese como minimo
                    if(producto.ultimo_precio < producto.precio_turista){
                        //$("#montos").attr("min",producto.precio_costo); // que el precio atacado sea el precio minimo
                    }else{
                        //$("#montos").attr("min",producto.precio_costo); // que el precio atacado sea el precio minimo
                    }
                    
                }else{
                    $("#ultimo_precio").val('');
                    //$("#ultimo_precio").html( " (Último)");
                    //$("#ultimo_precio").attr("disabled", true);
                   // $("#montos").attr("min",producto.precio_costo); // que el precio atacado sea el precio minimo
                    
                }
                //$('#productoNuevo').submit();
            }

        })
    });


    $('#cliente').on('change', function() {
        var id = $(this).val();
        var url = "?c=cliente&a=buscar&id=" + id;
        var categoria = "Plata";
        var descuento = 0;
        $.ajax({

            url: url,
            method: "POST",
            data: id,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                var cliente = JSON.parse(respuesta);
                $("#puntos").val(cliente.puntos);
                if (cliente.gastado < 3000000) {
                    categoria = 'Plata';
                    descuento = 5;
                } else if (cliente.gastado >= 3000000 && cliente.gastado < 10000000) {
                    categoria = 'Oro';
                    descuento = 10;
                } else {
                    categoria = 'Platino';
                    descuento = 15;
                }
            }

        })
    });

    $('#cantidad').on('change', function() {

        var valor = $(this).val();
        var id = $("#producto").val();
        var url = "?c=producto&a=buscar&id=" + id;

        //console.log(id);
        //console.log(valor);

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
                    //console.log('precio mayorista');
                    $("#precio_venta").val(producto.precio_mayorista);
                    // $("#precio_venta").html(producto.precio_mayorista + " (Mayorista)");     
                }
            }

        })

    });

    $('#producto').on('change', function() {

        var valor = $(this).val();
        var id = $("#producto").val();
        var url = "?c=producto&a=buscar&id=" + id;

        //console.log(id);
        //console.log(valor);

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
        var totalus = (total / dolares).toFixed(2);
        var totalrs = (total / reales).toFixed(2);
        var totalc = total.toLocaleString();

        $('#totalus').val(totalus);
        $('#totalrs').val(totalrs);
        $('.totaldesc').val(totalc);
    }



</script>