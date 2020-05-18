<? header('Access-Control-Allow-Origin: *');  ?>
<? header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method"); ?>
<? header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE"); ?>
<? header("Allow: GET, POST, OPTIONS, PUT, DELETE"); ?>
<? header('Content-Type: application/json'); ?>
<?

if (isset($_SERVER{'HTTP_ORIGIN'})) {
        header("Access-Control-Allow-Origin: {$_SERVER{'HTTP_ORIGIN'}}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }


require_once("../cnx/swgc-mysql.php");
include("../inc/fun-ini.php");

session_start();
$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bAll'];


$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

date_default_timezone_set('America/Mexico_City');



$hoy = "'".date('Y-m-d H:i:s')."'";

$data = json_decode( file_get_contents('php://input') );

$select =   " SELECT cc.tNombre tCliente, bp.eCodPromotoria, ".
            " bp.fhFechaPromotoria, ce.tIcono estatus".
            " FROM BitPromotoria bp ".
            " INNER JOIN CatClientes cc ON cc.eCodCliente = bp.eCodCliente ".
            " INNER JOIN CatEstatus ce ON ce.eCodEstatus = bp.eCodEstatus ".
            " INNER JOIN RelPromotoriasClientes pc ON pc.eCodPromotoria = bp.eCodPromotoria ".
            " INNER JOIN RelPromotoriasPromotores pp ON pp.eCodPromotoria = bp.eCodPromotoria ".
            " INNER JOIN RelPromotoriasSupervisores ON ps.eCodPromotoria = bp.eCodPromotoria ".
            " WHERE DATE(bp.fhFechaPromotoria) >= '".date('Y-m-d H:i:s')."'".
            ($bAll ? "" : " AND ".$_SESSION['sessionAdmin']['eCodUsuario']." IN pc.eCodUsuario, pp.eCodUsuario, ps.eCodUsuario ");
$rsConsulta = mysql_query($select);

$tHTML =  '<table class="table table-hover" width="100%">'.
        '<thead>'.
        '<tr>'.
        '<th>C&oacute;digo</th>'.
		'<th>E</th>'.
        '<th>Cliente</th>'.
        '<th class="text-left">Fecha</th>'.
        '</tr>'.
        '</thead>'.
        '<tbody>';

    while($rConsulta=mysql_fetch_array($rsConsulta)){
            //imprimimos
       $tHTML .=    '<tr>'.
        '<td>'.menuEmergenteJSON($rConsulta{'eCodPromotoria'},'sis-dash-con').'</td>'.
        '<td><i class="'.$rConsulta{'estatus'}.'"></i></td>'.
        '<td>'.utf8_encode($rConsulta{'tCliente'}).'</td>'.
		'<td>'.date('d/m/Y',strtotime($rConsulta{'fhFechaPromotoria'})).'</td>'.
                    '</tr>';
            //imprimimos
        }

$tHTML .= '</tbody></table>';

echo json_encode(array('tHTML'=>$tHTML));

?>