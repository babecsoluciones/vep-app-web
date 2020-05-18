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



$data = json_decode( file_get_contents('php://input') );

$indice = $data->eIndice ? $data->eIndice : 0;
$fila = $data->eFila ? $data->eFila : 0;

//preparamos variables


//preparamos tabla
$tHTML = '      <td><i class="far fa-trash-alt" onclick="deleteRow(\'pro'.$indice.'\',\'promotores'.$indice.'\')"></i></td>';
$tHTML .= '      <td>';
//
$tHTML .= '<input type="hidden" id="eCodPromotor'.$indice.'-'.$fila.'" name="promotores['.$indice.']['.$fila.'][eCodPromotor]">';
$tHTML .= '<input type="text" class="form-control" id="tPromotor'.$indice.'-'.$fila.'" name="promotores['.$indice.']['.$fila.'][tPromotor]" onkeyup="agregarPromotor('.$indice.','.$fila.')" onkeypress="agregarPromotor('.$indice.','.$fila.')" onblur="validarPromotor('.$indice.','.$fila.')" placeholder="Demovendedor" autocomplete="off">';
//
$tHTML .= '      </td>';
$tHTML .= '      <td>';
//
$tHTML .= '<input type="hidden" id="eCodTienda'.$indice.'-'.$fila.'" name="promotores['.$indice.']['.$fila.'][eCodTienda]">';
$tHTML .= '<input type="text" class="form-control" id="tTienda'.$indice.'-'.$fila.'" name="promotores['.$indice.']['.$fila.'][tTienda]" onkeyup="agregarTienda('.$indice.','.$fila.')" onkeypress="agregarTienda('.$indice.','.$fila.')" onblur="validarPromotor('.$indice.','.$fila.')" placeholder="Tienda" autocomplete="off">';
//
$tHTML .= '      </td>';

echo json_encode(array("tHTML"=>$tHTML));

?>