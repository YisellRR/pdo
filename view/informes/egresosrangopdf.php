<?php

require_once('plugins/tcpdf/pdf/tcpdf_include.php');

$Meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
       
$desde = date("d-m-Y", strtotime($_REQUEST["desde"]));
$hasta = date("d-m-Y", strtotime($_REQUEST["hasta"]));

$fechaHoy = date("d-m-Y");
$horaHoy = date("H:i");


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->AddPage('P', 'A4');

$sucursal=$this->sucursal->Obtener($_REQUEST['id_sucursal']);
$s=$sucursal!=null ? $sucursal->sucursal :'';
$html1 = <<<EOF

	<table width"100%" style="border: 1px solid #333; font-size:12px; color: black">
		<tr align="center">
		    <th width="100%" style="font-size:15px"><b>INFORME DE GASTOS</b></th>
		</tr>
			<tr align="center">
		    <th width="100%"><b>$s</b></th>
		</tr>
		<tr align="center">
		    <th width="100%">desde $desde hasta $hasta</th>
		</tr>
	</table>
	
	
		<p>Generado el $fechaHoy a las $horaHoy</p>
	

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');



/*

   INICIO GASTOS
*/

$html1 = <<<EOF

		<table width"100%" style="border: 1px solid #333; font-size:12px; background-color: #c0c0c0; color: black">
			<tr align="center">
			    <th width="12%" style="border-left-width:1px ; border-right-width:1px">Fecha</th>
                <th width="43%" style="border-left-width:1px ; border-right-width:1px">Concepto</th>
             	<th width="15%" style="border-left-width:1px ; border-right-width:1px">USD</th>
             	<th width="15%" style="border-left-width:1px ; border-right-width:1px">RS</th>
             	<th width="15%" style="border-left-width:1px ; border-right-width:1px">GS</th>
			</tr>
		</table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$totalEgreso = 0;
$suma_gs=0;
$suma_rs=0;
$suma_usd=0;

foreach($this->model->Listar_rango($_REQUEST['desde'], $_REQUEST['hasta'], $_REQUEST['id_sucursal']) as $e):

if($e->moneda=='GS'){
    $suma_gs += $e->monto;
    $monto_gs = number_format($e->monto,0,",",".");
    $monto_usd='';
    $monto_rs='';
}elseif($e->moneda=='RS'){
    $suma_rs += $e->monto;
    $monto_rs = number_format($e->monto,0,",",".");
    $monto_gs='';
    $monto_usd='';
}else{
    $suma_usd += $e->monto;
    $monto_usd = number_format($e->monto,0,",",".");
    $monto_rs='';
    $monto_gs='';
}

$dia = date("d", strtotime($e->fecha));
$fecha_gasto = date("d/m/Y", strtotime($e->fecha));
if($_REQUEST['id_sucursal']!=''){
$html1 = <<<EOF
		<table width"100%" style="border: 1px solid #333; font-size:8px; line-height: 13px">
			<tr align="center">
			   <th width="12%" style="border-left-width:1px ; border-right-width:1px">$fecha_gasto</th>
                <th align="left" width="43%" style="border-left-width:1px ; border-right-width:1px">$e->concepto</th>
             	<th align="rigth"width="15%" style="border-left-width:1px ; border-right-width:1px">$monto_usd</th>
             	<th align="rigth"width="15%" style="border-left-width:1px ; border-right-width:1px">$monto_rs</th>
             	<th align="rigth"width="15%" style="border-left-width:1px ; border-right-width:1px">$monto_gs</th>
			</tr>
		</table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');
}else{
    $html1 = <<<EOF
		<table width"100%" style="border: 1px solid #333; font-size:8px; line-height: 13px">
			<tr align="center">
			   <th width="12%" style="border-left-width:1px ; border-right-width:1px">$fecha_gasto</th>
			   <th width="15%" style="border-left-width:1px ; border-right-width:1px">$e->sucursal</th>
                <th align="left" width="28%" style="border-left-width:1px ; border-right-width:1px">$e->concepto</th>
             	<th align="rigth"width="15%" style="border-left-width:1px ; border-right-width:1px">$monto_usd</th>
             	<th align="rigth"width="15%" style="border-left-width:1px ; border-right-width:1px">$monto_rs</th>
             	<th align="rigth"width="15%" style="border-left-width:1px ; border-right-width:1px">$monto_gs</th>
			</tr>
		</table>
EOF;

$pdf->writeHTML($html1, false, false, false, false, '');
    
}
endforeach;

$total_gs=number_format($suma_gs,0,",",".");
$total_rs=number_format($suma_rs,0,",",".");
$total_usd=number_format($suma_usd,0,",",".");

$html1 = <<<EOF
		<table width"100%" style="border: 1px solid #333; font-size:10px; line-height: 13px">
			<tr align="rigth">
			    <th width="12%" style="border-left-width:1px ; border-right-width:1px"></th>
                <th width="43%" style="border-left-width:1px ; border-right-width:1px"><b>RESULTADO (-)</b></th>
             	<th width="15%" style="border-left-width:1px ; border-right-width:1px">$total_usd</th>
             	<th width="15%" style="border-left-width:1px ; border-right-width:1px">$total_rs</th>
             	<th width="15%" style="border-left-width:1px ; border-right-width:1px">$total_gs</th>
			</tr>
		</table>

EOF;
$pdf->writeHTML($html1, false, false, false, false, '');

/*

   FIN GASTOS

*/




$pdf->Output("Informe de gastos de la fecha $desde hasta $hasta.pdf", 'I');


//============================================================+
// END OF FILE
//============================================================+
  ?>