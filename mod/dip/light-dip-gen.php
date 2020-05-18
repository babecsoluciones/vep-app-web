<?php
require_once("../../cnx/swgc-mysql.php");
require_once("../../cls/cls-sistema.php");
include("../../inc/fun-ini.php");
$clSistema = new clSis();
session_start();

$select = "SELECT bc.*,brc.eCodRegistro, su.tTitulo, su.tNombre, su.tApellidos FROM BitCursos bc INNER JOIN BitRegistrosCursos brc ON brc.eCodCurso=bc.eCodCurso INNER JOIN SisUsuarios su ON su.eCodUsuario=brc.eCodUsuario = ".$_GET['eCodRegistro'];
$rsRegistro = mysql_query($select);
$rRegistro = mysql_fetch_array($rsRegistro);



?>
<!doctype html>
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

<body style="margin:0; padding:0; background-image:url('<?=obtenerURL()?>cla/<?=$rRegistro{'tArchivoDiploma'};?>'); background-repeat: no-repeat; background-size: 100% 100%;">
   
    <table width="100%" height="100%">
    <tr>
        <td height="464" colspan="2"></td>
    </tr>
    <tr>
        <td height="198" valign="middle" align="center" colspan="2"><h1 style="font-family: dynalight, sans-serif;"><?=utf8_encode($rRegistro{'tTitulo'}.' '.$rRegistro{'tNombre'}.' '.$rRegistro{'tApellidos'});?></h1></td>
    </tr>
    <tr>
        <td colspan="2" height="130"></td>
    </tr>
    </table>
    
</body>
</html>

