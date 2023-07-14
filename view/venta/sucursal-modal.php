<style>
    .scrolleable {
        height: 200px;
        overflow-y: scroll;
    }
</style>
<div id="sucursalModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="edit_form">
                <form method="post">
                    <h1>Generar informe mensual</h1>
                    <input type="hidden" name="c" value="venta">
                    <input type="hidden" name="a" value="sucursales">
                    <div class="form-group">
                        <label>Desde</label>
                        <input type="date" required min="2020-11-01" max="<?php echo date("Y-m-d") ?>" name="desde" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Hasta</label>
                        <input type="date" required min="2020-11-01" max="<?php echo date("Y-m-d") ?>" name="hasta" class="form-control">
                    </div>
                    <p>
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseSucursal" aria-expanded="false" aria-controls="collapseSucursal">
                            Personalizar contenido del informe
                        </button>
                    </p>
                    <div class="collapse scrolleable" id="collapseSucursal">
                        <div class="card card-body">

                            <div class="list-group">
                                <label class="list-group-item-primary">
                                    <input class="form-check-input me-1" type="checkbox" id="select_all" name="" checked value="">
                                    Marcar/Desmarcar todo </label>
                                <label class="list-group-item">
                                    <input class="form-check-input me-1" type="checkbox" name="items_informe[vent_cent]" checked value="true">
                                     Ventas Sucursal central </label>
                                <label class="list-group-item">
                                    <input class="form-check-input me-1" type="checkbox" name="items_informe[vent_suc2]" checked value="true">
                                    Ventas Sucursal 2
                                </label>
                                <label class="list-group-item"> 
                                    <input class="form-check-input me-1" type="checkbox" name="items_informe[vent_paris]" checked value="true">
                                    Ventas Shopping Paris
                                </label>
                                <label class="list-group-item"> 
                                    <input class="form-check-input me-1" type="checkbox" name="items_informe[comp_cent]" checked value="true">
                                    Compras Sucursal central 
                                </label>
                                <label class="list-group-item"> 
                                    <input class="form-check-input me-1" type="checkbox" name="items_informe[comp_suc2]" checked value="true">
                                    Compras Sucursal 2
                                </label>
                                <label class="list-group-item"> 
                                    <input class="form-check-input me-1" type="checkbox" name="items_informe[comp_paris]" checked value="true">
                                    Compras Shopping Paris
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button class="btn btn-primary">Generar</button>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#select_all').click(function() {
            $('input[type="checkbox"]').prop('checked', this.checked);
        })
    });
</script>