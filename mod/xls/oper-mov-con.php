<?php

$conexion = mysql_connect("localhost","diariosm_root","B@surto91");
mysql_select_db("diariosm_promotorias");

include_once("../../cls/xlsxwriter.class.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

$filename = "FMD-".date('Y-m-d').".xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$select = "SELECT
	rm.fhFecha,
	ct.tNombre tTienda,
	cp.tNombre tProducto,
	cr.tNombre tPresentacion,
	rm.eInicial,
	rm.eResurtido,
	rm.eFinal,
	rm.eTotalVenta,
	rm.dPrecioVenta 
FROM
	RelPromotoriasProductosPresentacionesMovimientos rm
	INNER JOIN CatTiendas ct ON ct.eCodTienda= rm.eCodTienda
	INNER JOIN CatProductos cp ON cp.eCodProducto = rm.eCodProducto
	INNER JOIN CatPresentaciones cr ON cr.eCodPresentacion = rm.ecodPresentacion 
WHERE
	rm.eCodPromotoria = ".$_GET['v1']." ORDER BY rm.eCodTienda ASC, rm.eCodProducto ASC";
$rsConsulta = mysql_query($select);

$tienda = "";
$producto = "";
$presentacion = "";

//imprimimos
while($rConsulta = mysql_fetch_array($rsConsulta)){ 
     if($tienda!=$rConsulta{'tTienda'}){ 
        $tienda = $rConsulta{'tTienda'}; 
        $producto = ""; $presentacion = ""; 
        $rows[] = array($rConsulta{'tTienda'});
     } 
     if($producto!=$rConsulta{'tProducto'}){ 
        $producto = $rConsulta{'tProducto'}; 
     } 
     if($presentacion!=$rConsulta{'tPresentacion'}){ 
        $presentacion = $rConsulta{'tPresentacion'}; 
        $rows[] = array(
        $producto,
        $rConsulta{'tPresentacion'});
       
    $rows[] = array(
        'Fecha',
        'Inv. Inicial',
        'Resurtido',
        'Inv. Final',
        'Venta',
        'Precio Venta');
    

     } 
    $rows[] = array(
        date('d/m/Y H:i',strtotime($rConsulta{'fhFecha'})),
        $rConsulta{'eInicial'},
        $rConsulta{'eResurtido'},
        $rConsulta{'eFinal'},
        $rConsulta{'eTotalVenta'},
        '$'.$rConsulta{'dPrecioVenta'}
    );
 }
//imprimimos

$writer = new XLSXWriter();
$writer->setAuthor('Fussion MD'); 
foreach($rows as $row)
	$writer->writeSheetRow('Sheet1', $row);
$writer->writeToStdOut();
//$writer->writeToFile('example.xlsx');
//echo $writer->writeToString();
exit(0);


