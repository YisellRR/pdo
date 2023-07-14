<?php 
    $cierre = $this->cierre->Consultar($_SESSION['user_id']);  ?>
<h1 class="page-header">
    <?php echo $egreso->id != null ? $egreso->fecha : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=egreso">egreso</a></li>
  <li class="active"><?php echo $egreso->id != null ? $egreso->fecha : 'Nuevo Registro'; ?></li>
</ol>

<form id="crud-frm" method="post" action="?c=egreso&a=guardar" enctype="multipart/form-data">
    <input type="hidden" name="c" value="egreso" id="c"/>
    <input type="hidden" name="id" value="<?php echo $egreso->id; ?>" id="id" />
    <div class="form-group col-md-12">
        <label>Fecha</label>
        <input type="datetime-local" name="fecha" value="<?php echo (!$egreso->fecha)? (date("Y-m-d")."T".date("H:i")) : date("Y-m-d", strtotime($egreso->fecha))."T".date("H:i", strtotime($egreso->fecha)); ?>" class="form-control" placeholder="Fecha" required>
    </div>

    
    <div class="form-group col-md-6" >
        <label>Proveedor</label>
        <select name="id_cliente" id="cliente" class="form-control selectpicker" data-show-subtext="true" data-live-search="true" data-style="form-control"
                                    title="-- Seleccione al proveedor --" autofocus>
            <option value="1" selected>Proveedor ocasional</option>
            <?php foreach($this->cliente->Listar() as $cliente): ?> 
                 <option data-subtext="<?php echo $cliente->ruc; ?>" value="<?php echo $cliente->id; ?>"<?php echo ($cliente->id ==$egreso->id_cliente)? "selected":""; ?>><?php echo $cliente->nombre; ?> </option>
            <?php endforeach; ?>
        </select>
    </div>
     <div class="form-group col-md-6">
        <label>Sucursal</label>
        <select name="sucursal" id="sucursal"  class="form-control"  required>
            <option value="1" <?php echo ($egreso->sucursal == 1)? "selected":""; ?>>Central</option>
            <option value="2" <?php echo ($egreso->sucursal == 2)? "selected":""; ?>>Sucursal 2</option>
            <option value="3" <?php echo ($egreso->sucursal == 3)? "selected":""; ?>>Shopping Paris</option>
        </select>
    </div>
    <div class="form-group col-md-6">
        <label>Categoria</label>
        <input type="text" name="categoria" value="<?php echo $egreso->categoria; ?>" class="form-control" placeholder="Ingrese la categoria" required>
    </div>

    <div class="form-group col-md-6">
        <label>Concepto</label>
        <input type="text" name="concepto" value="<?php echo $egreso->concepto; ?>" class="form-control" placeholder="Ingrese su concepto" required>
    </div>
    
    <div class="form-group col-md-6">
        <label>Comprobante</label>
        <select name="comprobante" class="form-control">
            <option value="Sin comprobante" <?php echo ($egreso->comprobante == "Sin comprobante")? "selected":""; ?>>Sin comprobante</option>
            <option value="Factura" <?php echo ($egreso->comprobante == "Factura")? "selected":""; ?>>Factura</option>
            <option value="Recibo" <?php echo ($egreso->comprobante == "Recibo")? "selected":""; ?>>Recibo</option>
            <option value="Ticket" <?php echo ($egreso->comprobante == "Ticket")? "selected":""; ?>>Ticket</option>
        </select>
    </div>
    <div class="form-group col-md-6">
        <label>N° Comprobante</label>
        <input type="text" name="nro_comprobante" value="<?php echo $egreso->nro_comprobante; ?>" class="form-control" placeholder="Ingrese su comprobante" >
    </div>
    <div class="form-group col-md-3">
        <label>Monto</label>
        <input type="float" name="monto" value="<?php echo $egreso->monto; ?>" class="form-control" placeholder="Ingrese el monto" min="1" required>
    </div>

    <div class="form-group col-md-2" id="mon">
        <label>Moneda</label>
        <select name="moneda" id="moneda"  class="form-control"  required>
            <option value="USD" <?php echo ($egreso->moneda == "USD")? "selected":""; ?>>USD</option>
            <option value="GS" <?php echo ($egreso->moneda == "GS")? "selected":""; ?>>GS</option>
            <option value="RS" <?php echo ($egreso->moneda == "RS")? "selected":""; ?>>RS</option>
        </select>
    </div>

    <div class="form-group col-md-3" id="can">
        <label style="color: black">Cambio</label>
        <input type="text" name="cambio" id="cambio" value="<?php echo $egreso->cambio; ?>" class="form-control" placeholder="Ingrese el monto" min="1" required>
    </div>



    <div class="form-group col-md-3">
        <label>Forma de pago</label>
        <select name="forma_pago" class="form-control" id="pago" >
            <?php foreach ($this->metodo->Listar() as $m): ?>
                <option value="<?php echo $m->metodo ?>"<?php echo ($egreso->forma_pago ==  $m->metodo)? "selected":""; ?>><?php echo $m->metodo ?></option>
            <?php endforeach; ?>
        </select>
    </div>
     <div class="form-group"id="nro_cheque"style="display: none;" >
        <label>Nro Cheque</label>
        <input type="text" name="nro_cheque" id="cheque" value="<?php echo $egreso->nro_cheque; ?>" class="form-control" placeholder="Ingrese el comprobante"  >
    </div>
    <div class="form-group" id="plazo" style="display: none;">
        <label>Plazo</label>
        <input type="date" name="plazo" id="plazo" value="<?php echo $egreso->plazo; ?>" class="form-control" placeholder="Ingrese plazo"  >
    </div>
    
 
    
    <hr />
    
    <div class="text-right">
        <button class="btn btn-primary">Guardar</button>
    </div>
</form>


<script>
	$('#pago').on('change',function(){
		var valor = $(this).val();
		if (valor == "Cheque") {
			
			$("#plazo").show();
			$("#nro_cheque").show();
		}else{
		
			$("#plazo").hide();
			$("#nro_cheque").hide();
		}
	});

    $('#moneda').on('change',function(){
		var valor = $(this).val();
		
		var cot_dol = '1';
		var cot_real = <?php echo $cierre->cot_real_tmp; ?>;
		var cot_gs = <?php echo $cierre->cot_dolar_tmp; ?>;
		
		console.log($(this).val());
		if (valor == "GS") {
			$("#cambio").val(cot_gs);
			console.log('valor = '+ valor);
			
		}else if(valor == "RS"){
			$("#cambio").val(cot_real);
			console.log('valor = '+ valor);

		}else{
			$("#cambio").val(cot_dol);
			console.log('valor = '+ valor);
		}

	});

</script>
