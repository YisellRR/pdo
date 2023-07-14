<?php

// PRUEBA

/**
 * Clase que implementa un coversor de números
 * a letras.
 *
 * Soporte para PHP >= 5.4
 * Para soportar PHP 5.3, declare los arreglos
 * con la función array.
 *
 * @author AxiaCore S.A.S
 *
 */

class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];

    private static $DECENAS = [
        'VENTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];

    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static function convertir($number, $moneda = '', $centimos = '', $forzarCentimos = false)
    {
        $converted = '';
        $decimales = '';

        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }

        $div_decimales = explode('.',$number);

        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'CERO ';
        }

        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }

        if(empty($decimales)){
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }

        return $valor_convertido;
    }

    private static function convertGroup($n)
    {
        $output = '';

        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];   
        }

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }

        return $output;
    }
}



// FIN  PRUEBA 


require_once('plugins/tcpdf2/tcpdf.php');

$medidas = array(80, 250); // Ajustar aqui segun los milimetros necesarios;
$pdf = new TCPDF('P', 'mm', $medidas, true, 'UTF-8', false);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);
$pdf->SetDefaultMonospacedFont('courier');
$pdf->SetMargins(7,0,7, true);
$pdf->SetAutoPageBreak(TRUE, 0);


$pdf->AddPage();

$id_venta = $_GET['id'];
foreach($this->venta->Listar($id_venta) as $r){
    $cliente = $r->nombre_cli;
    $ruc = $r->ruc;
    $fecha = date("d/m/Y", strtotime($r->fecha_venta));
    $contado = $r->contado;
    $telefono = $r->telefono;
    $direccion = $r->direccion;
    $vendedor = $r->vendedor;
}
//<img src="assets/img/CANDY1.jpg" width="100">

$html1 = <<<EOF

	<table width ="100%" style="text-align:center; line-height: 15px; font-size:10px">
	    <p></p>
		<tr>
			<td style="vertical-align: middle;"><img src="assets/img/pdohouseb.png" width="100"></td>
		</tr>
	<tr>
	    <td><b>Ticket N°:</b> $id_venta </td>    
	</tr>
    <tr>
      <td align="left"><b>Fecha de Emisión:</b> $fecha</td>
    </tr>
    <tr align="left">
      <td><b>RUC/CI:</b> $ruc </td>
    </tr>
    <tr align="left">
      <td><b>Cliente:</b> $cliente </td>
    </tr>
        
    </table>
    <table>
		<tr><td>----------------------------------------------</td></tr>
	</table>
    <table width ="100%" style=" text-align:center; line-height: 20px; font-size:12px">
    <tr align="center">
      <td width="40%"><b>Descripción </b></td>
      <td width="35%"><b>| Importe</b></td>
      <td width="25%"><b>| Dto.</b></td>
    </tr>
    </table>
    
EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$sumaTotal = 0;
$items = 0;
foreach($this->venta->Listar($id_venta) as $r){
$items++;

$subTotal = number_format(($r->precio_venta), 2, "," , ".");
$venta = ($r->precio_venta*$r->cantidad);
$venta =  number_format($venta, 2, "," , ".");

if($r->descuentov == 0){
    $descuento = 0;
    $des =0;

}else{
    $descuento = ($r->precio_venta)-($r->descuento); //precio con descuento
    $des = ($r->precio_venta*$r->cantidad)($r->descuentov);
}
$descuento = number_format($descuento, 2, "," , ".");
$total = (($r->precio_venta*$r->cantidad)-(($r->descuento)));
$total =  number_format($total, 2, "," , ".");
$precio_venta =  number_format($r->precio_venta, 2, "," , ".");
$descuentov =  number_format($des, 2, "," , ".");


$html1 = <<<EOF

		<table width="100%" style="text-align:center; line-height: 8px; font-size:8px">
			<tr nowrap="nowrap">
				<td width="40%" style="text-align: left; font-size:5px"><b>$r->cantidad -</b> $r->producto <b>($precio_venta C/u)</b></td>
                <td width="30%" style=""><b>$venta</b></td>
				<td width="30%" style=""><b>- $descuentov</b></td>
			</tr>
			
	    </table>

EOF;

$pdf->writeHTML($html1, false, false, false, false, '');

$cantidad_total += $r->cantidad;
$sumaTotal += ($r->precio_venta*$r->cantidad);
$sumades += $des;

}
$totalventa=$sumaTotal-$sumades;
$sumaTotades =  number_format($sumades, 2, "," , ".");
$sumaTotalV =  number_format($sumaTotal, 2, "," , ".");
$sumaTotalpago =  number_format($totalventa, 2, "," , ".");
$html2 = <<<EOF

	<table>
		<tr><td>----------------------------------------------</td></tr>
	</table>
	
	<table width="100%" style="text-align:center; line-height: 7px; font-size:8px">
	
	    <tr>
	      <td width="30%">Items:$items</td>
	      <td width="30%" align="right"style="text-align:center;font-size:5px">TOTAL VENTA: Usd..</td>
	      <td width="40%"><b>$sumaTotalV</b></td>
	    </tr>
        <tr>
        <td width="60%" align="right"style="text-align:center;font-size:5px">TOTAL DESC: Usd..</td>
        <td width="40%"> <b>- $sumaTotades</b></td>
	    </tr>
        <tr>
        <td width="60%" align="right"style="text-align:center;font-size:5px">TOTAL: Usd..</td>
        <td width="40%"><b> $sumaTotalpago</b></td>
	    </tr>
	</table>
	<br><br><br><br>.
	
	
    
EOF;

$pdf->writeHTML($html2, false, false, false, false, '');




// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('uin.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
  ?>