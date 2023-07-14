<?php

// require_once('plugins/tcpdf/pdf/tcpdf_include.php');
require_once('plugins/tcpdf2/tcpdf.php');

// $moneda = $this->venta_tmp->ObtenerMoneda();
$Meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
       
$desde = date("d-m-Y", strtotime($_REQUEST["desde"]));
$hasta = date("d-m-Y", strtotime($_REQUEST["hasta"]));


/* HEREDAR LA CLASE PARA PODER SOBREESCRIBIR EL HEADER Y FOOTER POR DEFECTO */

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{

	//Page header
	// public function Header()
	// {
	// 	// Logo
	// 	// $image_file = K_PATH_IMAGES . 'logo_example.jpg';
	// 	// $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	// 	// // Set font
	// 	$this->SetFont('helvetica', 'B', 20);
	// 	// Title
	// 	$this->writeHTMLCell(
	// 		$w = 0,
	// 		$h = 10,
	// 		$x = '',
	// 		$y = '',
	// 		"<small>Documento generado el ". date("d/m/Y H:i") . "</small>",
	// 		$border = 0,
	// 		$ln = 1,
	// 		$fill = 0,
	// 		$reseth = true,
	// 		$align = 'center',
	// 		$autopadding = true
	// 	);
	// 	// $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	// }

	// Page footer
	public function Footer()
	{
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . " - Generado el " . date("d/m/Y \a \l\a\s H:i") . " hs.", 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->AddPage('P', 'A4');
$pag_vacia = true;
// $_REQUEST['fecha'] .= '-01';
//$mes = date("m", strtotime($_REQUEST['fecha']));
// $mes = $Meses[intval(date("m", strtotime($_REQUEST['fecha'])))-1];
// $ano = date("Y", strtotime($_REQUEST['fecha']));
$fechaHoraHoy = date("d/m/Y \a \l\a\s H:i \h\s");

// $inicial=number_format($moneda->monto_inicial,0,",",".");
// $caja_inicial = $moneda->monto_inicial;
// $real=number_format($moneda->reales,0,",",".");
// $dolar=number_format($moneda->dolares,0,",",".");


/* ================================ 
	ESTILOS PARA LAS TABLAS
================================ */
$body_table_style = 'font-size:8px; border-top: 1px solid #ccc; border-bottom: .5px solid #ccc;';
$header_table_style = 'border-top: .5px solid #ccc; border-bottom: 1px solid #ccc; font-size:8.5px; background-color: #c0c0c0; color: #202020; font-weight: bold;';
// ********************************

$html1 = <<<EOF
		<h1 align="center"></h1>
		<h3 align="center">Informe de la fecha $desde hasta $hasta</h3>
		<p>Generado el $fechaHoraHoy</p>
	

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

//Traer array y llamar a funciones por separado
// call_user_func(array($controller, $accion));


	/*------------------------
	VENTAS contado - AGRUPADO VENTA SUCURSAL CENTRAL
	-------------------------*/
$html1 = <<<EOF
		<h1 align="center">Ventas al contado Sucursal central</h1>

		<table width"100%" style="$header_table_style">
			<tr align="center">
			<th width="18%" style=""><b>Fecha</b></th>
                <th width="13%" style=""><b>Vendedor</b></th>
                <th width="22%" style=""><b>Cliente</b></th>
                <th width="14%" style="" align="right"><b>Venta</b></th>
             	<th width="14%" style="" align="right"><b>Costo</b></th>
             	<th width="10%" style="" align="right"><b>Utilidad</b></th>
             	<th width="8%" style=""><b>%</b></th>
			</tr>
		</table>

EOF;

	if (isset($_REQUEST['items_informe']['vent_cent'])){
		$pdf->writeHTML($html1, false, false, false, false, '');
		$pag_vacia = false;
	} 

	$totalCredito = 0;
	$totalContado = 0;
	$totalCosto = 0;
	$totalVenta = 0;

	$sumvv = 0; 
	$sumcv = 0;
	$sumuv = 0;

	$indice = 0;

	foreach ($this->model->AgrupadoVentaSucursal($_REQUEST['desde'], $_REQUEST['hasta'], 1) as $v) :

		$totalv = number_format($v->total, 2, ",", ".");
		$costov = number_format($v->costo, 2, ",", ".");
		$utilidadv = number_format($v->total - $v->costo, 2, ",", ".");
		$sumvv += $v->total;
		$sumcv += $v->costo;
		$sumuv += $v->total - $v->costo;
		$porventa = number_format(((($v->total - $v->costo) * 100) / $v->total), 2, ",", ".");

		$bg = ($indice % 2 == 0) ? 'background-color: #eeeeee;' : '';

		$html1 = <<<EOF
		
		<table width"100%" style="$bg $body_table_style">
			<tr align="center">
			<th width="18%" style="" align="left">$v->fecha_venta</th>
                <th width="13%" style=" font-size:8px; " align="center">$v->vendedor ($v->id_venta)</th>
                <th width="22%" style="" align="left">$v->nombre</th>
                <th width="14%" style="" align="right">$totalv</th>
             	<th width="14%" style="" align="right">$costov</th>
             	<th width="10%" style="" align="right">$utilidadv</th>
             	<th width="8%" style="" align="right">$porventa %</th>
			</tr>
		</table>

EOF;

		if (isset($_REQUEST['items_informe']['vent_cent'])) $pdf->writeHTML($html1, false, false, false, false, '');

		$indice++;
	endforeach;
	$g = $sumuv; //ganancia bruta
	$gv = $sumvv; //venta
	$gc = $sumcv; //costo

	$sumvv = number_format($sumvv, 2, ",", ".");
	$sumcv = number_format($sumcv, 2, ",", ".");
	$sumuv = number_format($sumuv, 2, ",", ".");

	$gv = ($gv != 0) ? $gv : 1;
	$porventas = number_format(((($gv - $gc) * 100) / $gv), 2, ",", ".");

	$html1 = <<<EOF
		
		<table width"100%" style="$header_table_style" align="left">
			<tr align="center" style="padding:10px">
                <th width="53%" style="" align="left";><b>RESULTADOS (+)</b></th>
                <th width="14%" style="" align="right"><b>$sumvv</b></th>
             	<th width="14%" style="" align="right"><b>$sumcv</b></th>
             	<th width="10%" style="" align="right"><b>$sumuv</b></th>
             	<th width="8%" style="" align="right"><b>$porventas %</b></th>
			</tr>
		</table>
		<br><br>

EOF;

	if (isset($_REQUEST['items_informe']['vent_cent'])) $pdf->writeHTML($html1, false, false, false, false, '');
	/* ================================ 
	fin agrupado ventas
	================================ */

	/*------------------------
	VENTAS contado - AGRUPADO VENTA SUCURSAL 2
	-------------------------*/
	if(
		isset($_REQUEST['items_informe']['vent_suc2'])) {
	if (!$pag_vacia) $pdf->AddPage();
	$pag_vacia = false;
}

	$html1 = <<<EOF
	<h1 align="center">Ventas al contado Sucursal 2</h1>

	<table width"100%" style="$header_table_style">
		<tr align="center">
		<th width="18%" style=""><b>Fecha</b></th>
			<th width="13%" style=""><b>Vendedor</b></th>
			<th width="22%" style=""><b>Cliente</b></th>
			<th width="14%" style="" align="right"><b>Venta</b></th>
			 <th width="14%" style="" align="right"><b>Costo</b></th>
			 <th width="10%" style="" align="right"><b>Utilidad</b></th>
			 <th width="8%" style=""><b>%</b></th>
		</tr>
	</table>

EOF;

if (isset($_REQUEST['items_informe']['vent_suc2'])){
	$pdf->writeHTML($html1, false, false, false, false, '');
	$pag_vacia = false;
} 

$totalCredito = 0;
$totalContado = 0;
$totalCosto = 0;
$totalVenta = 0;

$sumvv = 0; 
$sumcv = 0;
$sumuv = 0;

$indice = 0;

foreach ($this->model->AgrupadoVentaSucursal($_REQUEST['desde'], $_REQUEST['hasta'], 2) as $v) :

	$totalv = number_format($v->total, 2, ",", ".");
	$costov = number_format($v->costo, 2, ",", ".");
	$utilidadv = number_format($v->total - $v->costo, 2, ",", ".");
	$sumvv += $v->total;
	$sumcv += $v->costo;
	$sumuv += $v->total - $v->costo;
	$porventa = number_format(((($v->total - $v->costo) * 100) / $v->total), 2, ",", ".");

	$bg = ($indice % 2 == 0) ? 'background-color: #eeeeee;' : '';

	$html1 = <<<EOF
	
	<table width"100%" style="$bg $body_table_style">
		<tr align="center">
		<th width="18%" style="" align="left">$v->fecha_venta</th>
			<th width="13%" style=" font-size:8px; " align="center">$v->vendedor ($v->id_venta)</th>
			<th width="22%" style="" align="left">$v->nombre</th>
			<th width="14%" style="" align="right">$totalv</th>
			 <th width="14%" style="" align="right">$costov</th>
			 <th width="10%" style="" align="right">$utilidadv</th>
			 <th width="8%" style="" align="right">$porventa %</th>
		</tr>
	</table>

EOF;

	if (isset($_REQUEST['items_informe']['vent_suc2'])) $pdf->writeHTML($html1, false, false, false, false, '');

	$indice++;
endforeach;
$g = $sumuv; //ganancia bruta
$gv = $sumvv; //venta
$gc = $sumcv; //costo

$sumvv = number_format($sumvv, 2, ",", ".");
$sumcv = number_format($sumcv, 2, ",", ".");
$sumuv = number_format($sumuv, 2, ",", ".");

$gv = ($gv != 0) ? $gv : 1;
$porventas = number_format(((($gv - $gc) * 100) / $gv), 2, ",", ".");

$html1 = <<<EOF
	
	<table width"100%" style="$header_table_style" align="left">
		<tr align="center" style="padding:10px">
			<th width="53%" style="" align="left";><b>RESULTADOS (+)</b></th>
			<th width="14%" style="" align="right"><b>$sumvv</b></th>
			 <th width="14%" style="" align="right"><b>$sumcv</b></th>
			 <th width="10%" style="" align="right"><b>$sumuv</b></th>
			 <th width="8%" style="" align="right"><b>$porventas %</b></th>
		</tr>
	</table>
	<br><br>

EOF;

if (isset($_REQUEST['items_informe']['vent_suc2'])) $pdf->writeHTML($html1, false, false, false, false, '');
/* ================================ 
fin agrupado ventas
================================ */
	/*------------------------
	VENTAS contado - AGRUPADO VENTA SHOPPING PARIS
	-------------------------*/
	if(
		isset($_REQUEST['items_informe']['vent_paris'])) {
	if (!$pag_vacia) $pdf->AddPage();
	$pag_vacia = false;
}
	
	$html1 = <<<EOF
	<h1 align="center">Ventas al contado Shopping Paris</h1>

	<table width"100%" style="$header_table_style">
		<tr align="center">
		<th width="18%" style=""><b>Fecha</b></th>
			<th width="13%" style=""><b>Vendedor</b></th>
			<th width="22%" style=""><b>Cliente</b></th>
			<th width="14%" style="" align="right"><b>Venta</b></th>
			 <th width="14%" style="" align="right"><b>Costo</b></th>
			 <th width="10%" style="" align="right"><b>Utilidad</b></th>
			 <th width="8%" style=""><b>%</b></th>
		</tr>
	</table>

EOF;

if (isset($_REQUEST['items_informe']['vent_paris'])){
	$pdf->writeHTML($html1, false, false, false, false, '');
	$pag_vacia = false;
} 

$totalCredito = 0;
$totalContado = 0;
$totalCosto = 0;
$totalVenta = 0;

$sumvv = 0; 
$sumcv = 0;
$sumuv = 0;

$indice = 0;

foreach ($this->model->AgrupadoVentaSucursal($_REQUEST['desde'], $_REQUEST['hasta'], 3) as $v) :

	$totalv = number_format($v->total, 2, ",", ".");
	$costov = number_format($v->costo, 2, ",", ".");
	$utilidadv = number_format($v->total - $v->costo, 2, ",", ".");
	$sumvv += $v->total;
	$sumcv += $v->costo;
	$sumuv += $v->total - $v->costo;
	$porventa = number_format(((($v->total - $v->costo) * 100) / $v->total), 2, ",", ".");

	$bg = ($indice % 2 == 0) ? 'background-color: #eeeeee;' : '';

	$html1 = <<<EOF
	
	<table width"100%" style="$bg $body_table_style">
		<tr align="center">
		<th width="18%" style="" align="left">$v->fecha_venta</th>
			<th width="13%" style=" font-size:8px; " align="center">$v->vendedor ($v->id_venta)</th>
			<th width="22%" style="" align="left">$v->nombre</th>
			<th width="14%" style="" align="right">$totalv</th>
			 <th width="14%" style="" align="right">$costov</th>
			 <th width="10%" style="" align="right">$utilidadv</th>
			 <th width="8%" style="" align="right">$porventa %</th>
		</tr>
	</table>

EOF;

	if (isset($_REQUEST['items_informe']['vent_paris'])) $pdf->writeHTML($html1, false, false, false, false, '');

	$indice++;
endforeach;
$g = $sumuv; //ganancia bruta
$gv = $sumvv; //venta
$gc = $sumcv; //costo

$sumvv = number_format($sumvv, 2, ",", ".");
$sumcv = number_format($sumcv, 2, ",", ".");
$sumuv = number_format($sumuv, 2, ",", ".");

$gv = ($gv != 0) ? $gv : 1;
$porventas = number_format(((($gv - $gc) * 100) / $gv), 2, ",", ".");

$html1 = <<<EOF
	
	<table width"100%" style="$header_table_style" align="left">
		<tr align="center" style="padding:10px">
			<th width="53%" style="" align="left";><b>RESULTADOS (+)</b></th>
			<th width="14%" style="" align="right"><b>$sumvv</b></th>
			 <th width="14%" style="" align="right"><b>$sumcv</b></th>
			 <th width="10%" style="" align="right"><b>$sumuv</b></th>
			 <th width="8%" style="" align="right"><b>$porventas %</b></th>
		</tr>
	</table>
	<br><br>

EOF;

if (isset($_REQUEST['items_informe']['vent_paris'])) $pdf->writeHTML($html1, false, false, false, false, '');
/* ================================ 
fin agrupado ventas
================================ */


/* ================================ 
	compras por sucursal CENTRAL
================================ */
if (isset($_REQUEST['items_informe']['comp_cent'])  ){
	if(!$pag_vacia) $pdf->AddPage();
	$pag_vacia = false;
}
$html1 = <<<EOF
		<h1 align="center">Compras por producto Central</h1>

	<table width"100%" style="$header_table_style">
			<tr align="">
			 <th width="20%" style="">Fecha</th>
                <th width="48%" style="">Producto</th>
                <th width="10%" style="">Cantidad</th>
                <th width="10%" style="">Precio</th>
                <th width="12%" style="">Total</th>
			</tr>
		</table>

EOF;

if (isset($_REQUEST['items_informe']['comp_cent'])) $pdf->writeHTML($html1, false, false, false, false, '');

$totalContadoCompra = 0;
$totalCreditoCompra = 0;
$totalCompra = 0;

$indice = 0;
foreach($this->compra->AgrupadoProductoSucursal($_REQUEST['desde'],$_REQUEST['hasta'], 1) as $r):

$total=number_format($r->total,2,",",".");
$unidad=number_format($r->precio_costo,2,",",".");
$cantidad=number_format($r->cantidad,2,",",".");
$fecha_compra = date("d/m/Y ", strtotime($r->fecha_compra));
$bg = ($indice % 2 == 0) ? 'background-color: #eeeeee;' : '';


$html1 = <<<EOF
		
		<table width"100%" style="$bg $body_table_style">
			<tr align="left">
			<td width="20%" style="" >$fecha_compra</td>
				<td width="48%" style="" >$r->producto</td>
				<td width="10%" style="">$cantidad</td>
				<td width="10%" style="">$unidad</td>
				<td width="12%" style="" align="left">$total</td>
			</tr>
		</table>

EOF;

if (isset($_REQUEST['items_informe']['comp_cent'])) $pdf->writeHTML($html1, false, false, false, false, '');



if($r->contado=='Contado'){
    $totalContadoCompra += $r->total;
}else{
    $totalCreditoCompra += $r->total;
}

$totalCompra += $r->total;

$indice++;
endforeach;

$totalCompraV=number_format($totalCompra,2,",",".");

$html1 = <<<EOF
		<table width"100%" style="$header_table_style">
			<tr align="center">
				<td width="78%" style="" align="left"><b>RESULTADO (-)</b></td>
				<td width="10%" style=""></td>
				<td width="12%" style="" align="left"><b>$totalCompraV</b></td>
			</tr>
		</table>

EOF;
if (isset($_REQUEST['items_informe']['comp_cent'])) $pdf->writeHTML($html1, false, false, false, false, '');
/* ================================ 
	fin compras
================================ */



/* ================================ 
	compras por sucursal 2
================================ */
if (isset($_REQUEST['items_informe']['comp_suc2'])  ){
	if(!$pag_vacia) $pdf->AddPage();
	$pag_vacia = false;
}
$html1 = <<<EOF
		<h1 align="center">Compras por producto sucursal 2</h1>

	<table width"100%" style="$header_table_style">
			<tr align="">
			 <th width="20%" style="">Fecha</th>
                <th width="48%" style="">Producto</th>
                <th width="10%" style="">Cantidad</th>
                <th width="10%" style="">Precio</th>
                <th width="12%" style="">Total</th>
			</tr>
		</table>

EOF;

if (isset($_REQUEST['items_informe']['comp_suc2'])) $pdf->writeHTML($html1, false, false, false, false, '');

$totalContadoCompra = 0;
$totalCreditoCompra = 0;
$totalCompra = 0;

$indice = 0;
foreach($this->compra->AgrupadoProductoSucursal($_REQUEST['desde'],$_REQUEST['hasta'], 2) as $r):

$total=number_format($r->total,2,",",".");
$unidad=number_format($r->precio_costo,2,",",".");
$cantidad=number_format($r->cantidad,2,",",".");
$fecha_compra = date("d/m/Y ", strtotime($r->fecha_compra));
$bg = ($indice % 2 == 0) ? 'background-color: #eeeeee;' : '';


$html1 = <<<EOF
		
		<table width"100%" style="$bg $body_table_style">
			<tr align="left">
			<td width="20%" style="" >$fecha_compra</td>
				<td width="48%" style="" >$r->producto</td>
				<td width="10%" style="">$cantidad</td>
				<td width="10%" style="">$unidad</td>
				<td width="12%" style="" align="left">$total</td>
			</tr>
		</table>

EOF;

if (isset($_REQUEST['items_informe']['comp_suc2'])) $pdf->writeHTML($html1, false, false, false, false, '');



if($r->contado=='Contado'){
    $totalContadoCompra += $r->total;
}else{
    $totalCreditoCompra += $r->total;
}

$totalCompra += $r->total;

$indice++;
endforeach;

$totalCompraV=number_format($totalCompra,2,",",".");

$html1 = <<<EOF
		<table width"100%" style="$header_table_style">
			<tr align="center">
				<td width="78%" style="" align="left"><b>RESULTADO (-)</b></td>
				<td width="10%" style=""></td>
				<td width="12%" style="" align="left"><b>$totalCompraV</b></td>
			</tr>
		</table>

EOF;
if (isset($_REQUEST['items_informe']['comp_suc2'])) $pdf->writeHTML($html1, false, false, false, false, '');
/* ================================ 
	fin compras
================================ */



/* ================================ 
	compras por sshopping paris
================================ */
if (isset($_REQUEST['items_informe']['comp_paris'])  ){
	if(!$pag_vacia) $pdf->AddPage();
	$pag_vacia = false;
}
$html1 = <<<EOF
		<h1 align="center">Compras por producto Shopping Paris</h1>

	<table width"100%" style="$header_table_style">
			<tr align="">
			 <th width="20%" style="">Fecha</th>
                <th width="48%" style="">Producto</th>
                <th width="10%" style="">Cantidad</th>
                <th width="10%" style="">Precio</th>
                <th width="12%" style="">Total</th>
			</tr>
		</table>

EOF;

if (isset($_REQUEST['items_informe']['comp_paris'])) $pdf->writeHTML($html1, false, false, false, false, '');

$totalContadoCompra = 0;
$totalCreditoCompra = 0;
$totalCompra = 0;

$indice = 0;
foreach($this->compra->AgrupadoProductoSucursal($_REQUEST['desde'],$_REQUEST['hasta'], 3) as $r):

$total=number_format($r->total,2,",",".");
$unidad=number_format($r->precio_costo,2,",",".");
$cantidad=number_format($r->cantidad,2,",",".");
$fecha_compra = date("d/m/Y ", strtotime($r->fecha_compra));
$bg = ($indice % 2 == 0) ? 'background-color: #eeeeee;' : '';


$html1 = <<<EOF
		
		<table width"100%" style="$bg $body_table_style">
			<tr align="left">
			<td width="20%" style="" >$fecha_compra</td>
				<td width="48%" style="" >$r->producto</td>
				<td width="10%" style="">$cantidad</td>
				<td width="10%" style="">$unidad</td>
				<td width="12%" style="" align="left">$total</td>
			</tr>
		</table>

EOF;

if (isset($_REQUEST['items_informe']['comp_paris'])) $pdf->writeHTML($html1, false, false, false, false, '');



if($r->contado=='Contado'){
    $totalContadoCompra += $r->total;
}else{
    $totalCreditoCompra += $r->total;
}

$totalCompra += $r->total;

$indice++;
endforeach;

$totalCompraV=number_format($totalCompra,2,",",".");

$html1 = <<<EOF
		<table width"100%" style="$header_table_style">
			<tr align="center">
				<td width="78%" style="" align="left"><b>RESULTADO (-)</b></td>
				<td width="10%" style=""></td>
				<td width="12%" style="" align="left"><b>$totalCompraV</b></td>
			</tr>
		</table>

EOF;
if (isset($_REQUEST['items_informe']['comp_paris'])) $pdf->writeHTML($html1, false, false, false, false, '');
/* ================================ 
	fin compras
================================ */

/*

  INICIO RESUMEN

*/

// if(
// 	!$pag_vacia
// ) {
// 	$pdf->AddPage();
// } else {
// 	$pag_vacia = false;
// }


// $utilidad_real = number_format((($totalVenta - $totalCosto) - $totalEgreso + $totalIngreso),2,",",".");

// $utilidads_USD = number_format((($totalVenta+$totalIngreso_USD)-($totalCompra+$totalEgreso_USD)),2,",",".");

// $bg = 'background-color: #eeeeee;';
// $html1 = <<<EOF
// 		<h1 align="center">Resumen</h1>
// 		<table width"100%" style="border-top: .5px solid #ccc; border-bottom: 1px solid #ccc; font-size:10px; background-color: #fff; color: #202020; font-weight: bold;">
// 			<tr align="center" style="$bg">
// 				<td style="" colspan="3">Ventas (+): </td>
// 				<td style="" align="right">$totalVentaV</td>
// 				<td style="" align="left">USD</td>
// 			</tr>
// 			<tr align="center">
// 				<td style="" colspan="3">Ingresos USD(+): </td>
// 				<td style="" align="right">$ingreso_USD</td>
// 				<td style="" align="left">USD</td>
// 			</tr>
// 			<tr align="center" style="$bg">
// 				<td style="" colspan="3">Ingresos PYG (+): </td>
// 				<td style="" align="right">$ingreso_GS</td>
// 				<td style="" align="left">PYG</td>
// 			</tr>

// 			<tr align="center">
// 				<td style="" colspan="3">Ingresos BRL(+): </td>
// 				<td style="" align="right">$ingreso_RS</td>
// 				<td style="" align="left">BRL</td>
// 			</tr>
// 		    <tr align="center" style="$bg">
// 				<td style="" colspan="3">Compras (-): </td>
// 				<td style="" align="right">$totalCompraV</td>
// 				<td style="" align="left">USD</td>
// 			</tr>
// 			<tr align="center">
// 				<td style="" colspan="3">Gastos USD (-): </td>
// 				<td style="" align="right">$egreso_USD</td>
// 				<td style="" align="left">USD</td>
// 			</tr>
// 			<tr align="center" style="$bg">
// 				<td style="" colspan="3">Gastos PYG (-): </td>
// 				<td style="" align="right">$egreso_GS</td>
// 				<td style="" align="left">PYG</td>
// 			</tr>
// 			<tr align="center">
// 				<td style="" colspan="3">Gastos BRL (-): </td>
// 				<td style="" align="right">$egreso_RS</td>
// 				<td style="" align="left">BRL</td>
// 			</tr>
// 			<tr align="center" style="$bg">
// 				<td style="" colspan="3">Utilidad Bruta: </td>
// 				<td style="" align="right">$utilidads_USD</td>
// 				<td style="" align="left"></td>
// 			</tr>
// 		</table>

// EOF;
// $pdf->writeHTML($html1, false, false, false, false, '');

/*

  FIN RESUMEN

*/









/*

$html1 = <<<EOF
		<h1 align="center">Productos en falta</h1>

		<table width"100%" style="border: 1px solid #333; font-size:12px; background-color: #348993; color: white">
			<tr align="center">
                <th width="88%" style="border-left-width:1px ; border-right-width:1px">Producto</th>
                <th width="12%" style="border-left-width:1px ; border-right-width:1px">Precio</th>
			</tr>
		</table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

    

$totalStock = 0;

foreach($this->producto->ListarTodo() as $r):

if($r->stock <= 0){

$total=number_format(($r->precio_costo),0,",",".");

$html1 = <<<EOF
		
		<table width"100%" style="border: 1px solid #333; font-size:10px">
			<tr align="center">
				<td width="88%" style="border-left-width:1px ; border-right-width:1px" align="left">$r->producto</td>
				<td width="12%" style="border-left-width:1px ; border-right-width:1px" align="right">$total</td>
			</tr>
		</table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');


}
endforeach;
*/

$pdf->Output("Informe de la fecha $desde hasta $hasta.pdf", 'I');


//============================================================+
// END OF FILE
//============================================================+
  ?>