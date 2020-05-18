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

$tHTML = '<option value="">Seleccione...</option>';

$select = "SELECT DISTINCT ct.eCodPresentacion, ct.tNombre tPresentacion FROM RelPromotoriasPresentaciones rp 
                                INNER JOIN CatPresentaciones ct ON ct.eCodPresentacion=rp.eCodPresentacion WHERE rp.eCodPromotoria = ".$eCodPromotoria." AND rp.eCodProducto = ".$eCodProducto." ORDER BY ct.eCodPresentacion ASC";
                                $rsPresentaciones = mysql_query($select);
                                while($rPresentacion = mysql_fetch_array($rsPresentaciones)){ 
                                   $tHTML .= '<option value="'.$rPresentacion{'eCodPresentacion'}.'">'.$rPresentacion{'tPresentacion'}.'</option>'; 
                                }


echo json_encode(
    array(
        "exito"=>((!sizeof($errores)) ? 1 : 0),
        "tHTML"=>$tHTML
        )
    );

?>