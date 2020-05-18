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
date_default_timezone_set('America/Mexico_City');



session_start();

$errores = array();

$data = json_decode( file_get_contents('php://input') );

/*Preparacion de variables*/
$eCodTienda = $data->eCodTienda ? $data->eCodTienda : false;
$eCodPromotoria = $data->eCodPromotoria ? $data->eCodPromotoria : false;

$tHTML = '';

$select = "SELECT ri.tArchivo, ct.tNombre FROM RelPromotoriasImagenes ri LEFT JOIN CatTiposImagenes ct ON ct.eCodTipoImagen=ri.eCodTipoImagen WHERE ri.eCodPromotoria = $eCodPromotoria AND ri.eCodTienda = $eCodTienda";
$rsConsulta = mysql_query($select);

while($rConsulta = mysql_fetch_array($rsConsulta))
{
    $tHTML .= $rConsulta{'tNombre'}.'<br><img src="/cla/'.$rConsulta{'tArchivo'}.'" class="img-responsive" style="max-width:100%;">';
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0),"tHTML"=>$tHTML, 'errores'=>$errores,"select"=>$select));

?>