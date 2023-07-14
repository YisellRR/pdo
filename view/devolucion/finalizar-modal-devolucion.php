<div id="finalizarDevolucionModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
			<div class="modal-body">
				<form method="post" action="?c=devolucion&a=guardar">
					<h2 align="center">Devolución</h2>
				    


				    <div class="form-group">
						<label>En forma de</label>
						<select name="contado" id="contado" class="form-control"> 
							<option value="Efectivo">Efectivo</option>
							<option value="Credito">Crédito</option>
						</select>
				    </div>

					<div class="form-group">
                        <label>Sucursal </label>
                        <select name="id_sucursal" id="sucursal" class="form-control selectpickerr" data-show-subtext="true" data-live-search="true" data-style="form-control" title="-- Seleccione el cliente --"  require>
                            <?php foreach($this->sucursal->ListarSucursalAsignado() as $r):?>
                                <option value="<?php echo $r->id; ?>"><?php echo $r->sucursal; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
				    
				    
				    <input type="hidden" name="id_venta" value="<?php echo $id_venta ?>">
				    <input type="hidden" name="subtotal" value="<?php echo $subtotal ?>">
				    <input type="hidden" name="total" class="totaldesc" id="totaldesc" value="<?php echo $subtotal ?>">
				    <input type="hidden" name="descuentoval" id="descuentoval" value="0">
				    <input type="hidden" name="ivaval" id="ivaval" value="0">
				    <input type="hidden" name="id_vendedor" value="12">
				    <input type="submit" class="btn btn-primary" value="Finalizar devolución">
				</form>

            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            </div>
            
        </div>
    </div>
</div>

<script>
	$('#pago').on('change',function(){
		var valor = $(this).val();
		if (valor == "Transferencia" || valor == "Giro") {
			$("#banco").show();
		}else{
			$("#banco").hide();
		}
	});
	$('#contado').on('change',function(){
		var valor = $(this).val();
		if (valor == "Cuota") {
			$("#entrega").show();
		}else{
			$("#entrega").hide();
		}
	});
</script>