<div id="mesModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="edit_form">  
                <form method="get">
                    <h1>Generar informe mensual</h1>
                    <input type="hidden" name="c" value="egreso">
                    <input type="hidden" name="a" value="egresorango">
                    <div class="form-group">
                        <label>Desde</label>
                        <input type="date" min="2020-11-01" max="<?php echo date("Y-m-d") ?>" name="desde" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Hasta</label>
                        <input type="date" min="2020-11-01" max="<?php echo date("Y-m-d") ?>" name="hasta" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Sucursal</label>
                        <select name="id_sucursal" class="form-control">
                            <option value="" <?php echo ($_POST['id_sucursal'] == "") ? "selected" : ""; ?>>Sin seleccionar</option>
                            <option value="1" <?php echo ($_POST['id_sucursal'] == 1) ? "selected" : ""; ?>>Central</option>
                            <option value="2" <?php echo ($_POST['id_sucursal'] == 2) ? "selected" : ""; ?>>Sucursal 2</option>
                            <option value="3" <?php echo ($_POST['id_sucursal'] == 3) ? "selected" : ""; ?>>Shopping Paris</option>
                        </select>
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
