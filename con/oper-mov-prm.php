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
$eCodPromotoria = $data->eCodPromotoria ? $data->eCodPromotoria : false;
$eCodProducto = $data->eCodProducto ? $data->eCodProducto : false;
$eCodPresentacion = $data->eCodPresentacion ? $data->eCodPresentacion : false;
$eCodTienda = $data->eCodTienda ? $data->eCodTienda : false;

$select = "SELECT * FROM RelPromotoriasProductosPresentacionesMovimientos WHERE eCodPromotoria = $eCodPromotoria AND eCodProducto = $eCodProducto AND eCodPresentacion = $eCodPresentacion AND eCodTienda = $eCodTienda ORDER BY eCodMovimiento DESC LIMIT 0,1";
$rsConsulta = mysql_query($select);
$rConsulta = mysql_fetch_array($rsConsulta);

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0),"inicial"=>(int)$rConsulta{'eFinal'}, 'errores'=>$errores));

?>