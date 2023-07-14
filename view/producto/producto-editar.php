<h1 class="page-header">
    <?php session_start(); echo $producto->id != null ? $producto->producto : 'Nuevo Registro';
    $user = $_SESSION['nivel'] ?>
</h1>

<ol class="breadcrumb">
    <li><a href="?c=producto">Producto</a></li>
    <li class="active"><?php echo $producto->id != null ? $producto->producto : 'Nuevo Registro'; ?></li>
</ol>

<form id="crud-frm" method="post" action="?c=producto&a=guardar" enctype="multipart/form-data">
    <input type="hidden" name="c" value="producto" id="c" />
    <input type="hidden" name="id" value="<?php echo $producto->id; ?>" id="id" />
    <input type="hidden" name="stock" value="<?php echo $producto->stock; ?>" id="stock" />
    <div class="row">
        <div class="form-group col-sm-12">
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="display: none;" id="incorrecta">
                <strong> Error!</strong> Ya existe el Codigo
            </div>
            <label>Código <a href='#' class='btn btn-default' id="autocodigo">Auto código</a></label>
            <input type="text" name="codigo" id="codigo" value="<?php echo $producto->codigo; ?>" class="form-control" placeholder="Ingrese el codigo" required>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label>Categoría</label>
            <select name="id_categoria" class="form-control">
                <?php foreach ($this->categoria->Listar() as $r) : ?>
                    <option value="<?php echo $r->id; ?>" <?php echo ($r->id == $producto->id_categoria) ? "selected" : ""; ?>><?php echo $r->categoria; ?></option>

                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label>Marca</label>
            <select name="marca" class="form-control" data-show-subtext="true" data-live-search="true" data-style="form-control">
                <?php foreach ($this->marca->Listar() as $r) : ?>
                    <option value="<?php echo $r->id; ?>" <?php echo ($r->id == $producto->marca) ? "selected" : ""; ?>><?php echo $r->marca; ?></option>

                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12">
            <label>Producto</label>
            <input type="text" name="producto" value="<?php echo $producto->producto; ?>" class="form-control" placeholder="Ingrese el producto" list="prod" required>
            <datalist id="prod">
                <?php foreach ($this->model->Listar() as $prod) : ?>
                    <option data-subtext="<?php echo $prod->codigo; ?>" value="<?php echo $prod->id; ?>" <?php echo ($prod->stock < 1) ? 'disabled' : ''; ?>><?php echo $prod->producto . ' ( ' . $prod->stock . ' ) - ' . number_format($prod->precio_minorista, 0, ".", "."); ?> </option>
                <?php endforeach; ?>
            </datalist>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12" style="display:none;">
            <label>Descripción</label>
            <textarea name="descripcion" id="editorr" class="form-control"><?php echo $producto->descripcion; ?></textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6" >
            <label>Stock con factura</label>
            <input type="number" name="confactura" value="<?php echo $producto->confactura; ?>" class="form-control" placeholder="Ingrese el stock "<?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>
        <div class="form-group col-sm-6">
            <label>Stock sin factura</label>
            <input type="number" name="sinfactura" value="<?php echo $producto->sinfactura; ?>" class="form-control" placeholder="Ingrese el stock " <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <label>Stock Suc. Central </label>
            <input type="number" name="stock_s1" value="<?php echo $producto->stock_s1; ?>" class="form-control" placeholder="Ingrese el stock "readonly <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>
        <div class="form-group col-sm-4">
            <label>Stock Suc. 2 </label>
            <input type="number" name="stock_s2" value="<?php echo $producto->stock_s2; ?>" class="form-control" placeholder="Ingrese el stock "readonly>
        </div>
        <div class="form-group col-sm-4">
            <label>Stock. (Shopping Paris)</label>
            <input type="number" name="stock_s3" value="<?php echo $producto->stock_s3; ?>" class="form-control" placeholder="Ingrese el stock "readonly>
        </div>
        <input type="hidden" name="iva" value="10" class="form-control">

    </div>


    <div class="row">
        <div class="form-group col-sm-4">
            <label>Precio mayorista</label>
            <input type="float" name="precio_mayorista" id="precio_mayorista" value="<?php echo $producto->precio_mayorista; ?>" class="form-control" placeholder="Ingrese el precio"<?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>
        <div class="form-group col-sm-4">
            <label>A partir de</label>
            <input type="float" name="apartir" id="apartir" value="<?php echo $producto->apartir; ?>" class="form-control" placeholder="Ingrese la cantidad" <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>
        <div class="form-group col-sm-4">
            <label>Cantidad por fardo</label>
            <input type="float" name="fardo" id="fardo" value="<?php echo $producto->fardo; ?>" class="form-control" placeholder="Ingrese la cantidad" <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>

    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <label>Precio costo</label>
            <input type="text" name="precio_costo" id="precio_costo" value="<?php echo $producto->precio_costo; ?>" class="form-control" placeholder="Ingrese el precio" <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?> required>
        </div>
        <div class="form-group col-sm-4">
            <label>Precio minorista</label>
            <input type="float" name="precio_minorista" id="precio_minorista" value="<?php echo $producto->precio_minorista; ?>" class="form-control" placeholder="Ingrese el precio" <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?> required>
        </div>
        <div class="form-group col-sm-4">
            <label>Precio turista</label>
            <input type="float" name="preciob" id="preciob" value="<?php echo $producto->preciob; ?>" class="form-control" placeholder="Ingrese el precio" <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>
    </div>


    <div class="row">
        <div class="form-group col-sm-4">
            <label>Precio promocional</label>
            <input type="number" name="precio_promo" id="precio_promo" class="form-control" value="<?php echo $producto->precio_promo; ?>" <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?> placeholder="Ingrese el precio">
        </div>

        <div class="form-group col-sm-4">
            <label>Promo desde</label>
            <input type="date" name="desde" id="desde" class="form-control" value="<?php echo $producto->desde; ?>" <?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>

        <div class="form-group col-sm-4">
            <label>Promo hasta</label>
            <input type="date" name="hasta" id="hasta" class="form-control" value="<?php echo $producto->hasta; ?>"<?php if (($_SESSION['nivel'] != 1) && ($producto->id != null))  echo "readonly"; ?>>
        </div>
    </div>
    <div class="row">

        <div class="form-group col-sm-12" style="display:none;">
            <label>Descuento máximo</label>
            <input type="number" name="descuento_max" value="<?php echo $producto->descuento_max; ?>" class="form-control" placeholder="Ingrese el descuento máximo">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12" style="display:none;">
            <label>Importado</label>
            <select name="importado" class="form-control">
                <option value="NO" <?php echo ($producto->importado == 'NO') ? "selected" : ""; ?>>NO</option>
                <option value="SI" <?php echo ($producto->importado == 'SI') ? "selected" : ""; ?>>SI</option>
            </select>
        </div>
    </div>



    <input type="hidden" name="imagen[]" class="form-control" multiple>


    <hr />

    <div class="text-right">
        <button class="btn btn-primary" id="guardar">Guardar</button>
    </div>
</form>

<script src="plugins/ckeditor/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>

<script type="text/javascript">
    $('#codigo').change(function() {

        event.preventDefault();
        var codigo = $("#codigo").val();

        var url = "?c=producto&a=BuscarCodigo&codigo=" + codigo;
        console.log(url);
        $.ajax({
            url: url,
            method: "POST",
            data: id,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                var correcta = respuesta;
                //alert(correcta);


                if (correcta == "true") {
                    $("#incorrecta").show();
                } else {
                    $("#incorrecta").hide();
                }




                // alert(respuesta);

            }

        })
    });

    $("#porcentaje_minorista").keyup(function() {

        var costo = parseInt($("#precio_costo").val());
        var porcentaje = parseInt($("#porcentaje_minorista").val());

        var precio_minorista = costo + (costo * (porcentaje / 100));

        $("#precio_minorista").val(precio_minorista);

    });

    $("#autocodigo").click(function() {

        // find diff
        let difference = 999999 - 100000;

        // generate random number 
        let rand = Math.random();

        // multiply with difference 
        rand = Math.floor(rand * difference);

        // add with min value 
        rand = rand + 100000;

        $("#codigo").val(rand);
    });

    $("#porcentaje_mayorista").keyup(function() {

        var costo = parseInt($("#precio_costo").val());
        var porcentaje = parseInt($("#porcentaje_mayorista").val());

        var precio_mayorista = costo + (costo * (porcentaje / 100));

        $("#precio_mayorista").val(precio_mayorista);

    });
</script>
<script type="text/javascript">
    hotkeys('f2, f4, ctrl+b', function(event, handler) {
        switch (handler.key) {
            case 'f2':
                location.href = "?c=venta_tmp";
                break;
            case 'f4':
                $("#guardar").click();
                break;
            case 'ctrl+b':
                alert('you pressed ctrl+b!');
                break;
            default:
                alert(event);
        }
    });
</script>