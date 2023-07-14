<div id="finalizarModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" action="?c=transferencia_producto&a=guardar" id="finalizar">
                    <h3 align="center">Datos de la transferencia</h3>

                    <div class="form-group col-sm-12" style="display:none;">
                        <label>Fecha de la transferencia</label>
                        <input type="datetime-local" name="fecha_transferencia_producto" class="form-control" value="<?php echo date("Y-m-d") ?>T<?php echo date("H:i") ?>">
                    </div>

                    <div class="form-group col-sm-12">
                        <label>Emisor de la transferencia</label>
                        <select name="emisor_transferencia" id="emisor" class="form-control" autofocus require>
                            <!-- <option id="emisor" value="0"> -- Seleccionar local emisor --</option> -->
                            <?php foreach($this->sucursal->Listar() as $r):?>
                                <option value="<?php echo $r->id; ?>"><?php echo $r->sucursal; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-sm-12">
                        <label>Destino de transferencia </label>
                        <select name="destino_transferencia" id="cliente" class="form-control selectpickerr" data-show-subtext="true" data-live-search="true" data-style="form-control" title="-- Seleccione el cliente --"  require>
                        
                            <!-- <option id="receptor" value="0"> -- Seleccionar local receptor --</option> -->

                            <?php foreach($this->sucursal->Listar() as $r):?>
                                <option value="<?php echo $r->id; ?>"><?php echo $r->sucursal; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div align="center">
                        <input type="submit" class="btn btn-primary" value="Finalizar" onclick="this.disabled=true;this.value='Guardando, Espere...';this.form.submit();">
                    </div>

            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            </div>

        </div>
    </div>
</div>
<script>
     $('#emisor').on('change', function() {
        var url = "?c=transferencia_producto&a=VerificarCantidad&id=" + id;
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
</script>