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

//preparamos variables


//preparamos tabla
$tHTML = '<table width="100%" id="supervisores'.$indice.'">';
$tHTML .= '<tr>';
$tHTML .= '  <td><i class="far fa-trash-alt" onclick="deleteRow(\'sup'.$indice.'\',\'supervisores\')"></i></td>';
$tHTML .= '  <td>';
//
$tHTML .= '<input type="hidden" id="eCodSupervisor'.$indice.'" name="supervisores['.$indice.'][eCodSupervisor]">';
$tHTML .= '<input type="text" class="form-control" id="tSupervisor'.$indice.'" name="supervisores['.$indice.'][tSupervisor]" onkeyup="agregarSupervisor('.$indice.')" onkeypress="agregarSupervisor('.$indice.')" onblur="validarSupervisor('.$indice.')" placeholder="Supervisor" autocomplete="off">';
//
$tHTML .= ' </td>';
$tHTML .= '</tr>';
$tHTML .= '<tr>';
$tHTML .= '  <td></td>';
$tHTML .= '  <td>';
$tHTML .= '    <table width="100%" id="promotores'.$indice.'">';
$tHTML .= '    <tr id="pro'.$indice.'">';
$tHTML .= '      <td><i class="far fa-trash-alt" onclick="deleteRow(\'pro'.$indice.'\',\'promotores'.$indice.'\')"></i></td>';
$tHTML .= '      <td>';
//
$tHTML .= '<input type="hidden" id="eCodPromotor'.$indice.'-0" name="promotores['.$indice.'][0][eCodPromotor]">';
$tHTML .= '<input type="text" class="form-control" id="tPromotor'.$indice.'-0" name="promotores['.$indice.'][0][tPromotor]" onkeyup="agregarPromotor('.$indice.',\'0\')" onkeypress="agregarPromotor('.$indice.',\'0\')" onblur="validarPromotor('.$indice.',\'0\')" placeholder="Demovendedor" autocomplete="off">';
//
$tHTML .= '      </td>';
$tHTML .= '      <td>';
//
$tHTML .= '<input type="hidden" id="eCodTienda'.$indice.'-0" name="promotores['.$indice.'][0][eCodTienda]">';
$tHTML .= '<input type="text" class="form-control" id="tTienda'.$indice.'-0" name="promotores['.$indice.'][0][tTienda]" onkeyup="agregarTienda('.$indice.',\'0\')" onkeypress="agregarTienda('.$indice.',\'0\')" onblur="validarPromotor('.$indice.',\'0\')" placeholder="Tienda" autocomplete="off">';
//
$tHTML .= '      </td>';
$tHTML .= '    </tr>';
$tHTML .= '    </table>';
$tHTML .= '  </td>';
$tHTML .= '</tr>';
$tHTML .= '</table>';

echo json_encode(array("tHTML"=>$tHTML));

?>