<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once("../../cnx/swgc-mysql.php");
require_once("../../cls/cls-sistema.php");
include("../../inc/fun-ini.php");
$url = obtenerURL()."mod/dip/light-dip-gen.php?eCodRegistro=".$_GET['v1'];

//$html=file_get_contents($url);


//==============================================================
$select = "SELECT bc.*,brc.eCodRegistro, su.tTitulo, su.tNombre, su.tApellidos FROM BitCursos bc INNER JOIN BitRegistrosCursos brc ON brc.eCodCurso=bc.eCodCurso INNER JOIN SisUsuarios su ON su.eCodUsuario=brc.eCodUsuario = ".$_GET['v1'];
$rsRegistro = mysql_query($select);
$rRegistro = mysql_fetch_array($rsRegistro);

$html = '
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>Detalle del Evento</title>
    <script src="//use.edgefonts.net/dynalight.js"></script>
    <style>
    
    .h1
        {
            font-family: dynalight, sans-serif;
        }
    </style>
</head>

<body style="margin:0; padding:0; background-image:url(\''.obtenerURL().'cla/'.$rRegistro{'tArchivoDiploma'}.'\'); background-repeat: no-repeat; background-size: 100% 100%;">
   
    <table width="100%" height="100%">
    <tr>
        <td height="444" colspan="2"></td>
    </tr>
    <tr>
        <td height="198" valign="middle" align="center" colspan="2"><h1 style="font-family: dynalight, sans-serif;">'.utf8_encode($rRegistro{'tTitulo'}.' '.$rRegistro{'tNombre'}.' '.$rRegistro{'tApellidos'}).'</h1></td>
    </tr>
    <tr>
        <td colspan="2" height="130"></td>
    </tr>
    </table>
    
</body>
</html>
';

//==============================================================


//==============================================================
//==============================================================
//==============================================================


include("../../mpdf/mpdf-2.php");
$mpdf=new mPDF('c'); 

$mpdf->mirrorMargins = true;

$mpdf->SetDisplayMode('fullpage','two');

$mpdf->WriteHTML($html);



$mpdf->Output();
exit;

//echo '<script>window.location="/das/inicio/consultar-sistema-dashboard/";</script>';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>