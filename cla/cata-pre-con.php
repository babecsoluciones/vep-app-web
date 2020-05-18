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

$errores = array();

$data = json_decode( file_get_contents('php://input') );

/*Preparacion de variables*/

$codigo = $data->eCodAccion ? $data->eCodAccion : $data->eAccion;
$accion = $data->tCodAccion ? $data->tCodAccion : $data->tAccion;


$eCodPresentacion = $data->eCodPresentacion ? $data->eCodPresentacion : false;
$eCodEstatus = $data->eCodEstatus ? $data->eCodEstatus : false;

$terms = explode(" ",$data->tNombres);
    
    $termino = "";
    
    for($i=0;$i<sizeof($terms);$i++)
    {
        $termino .= " AND cc.tNombre like '%".$terms[$i]."%' ";
    }



$eLimit = $data->eMaxRegistros;
$bOrden = $data->rOrden;
$rdOrden = $data->rdOrden ? $data->rdOrden : 'eCodPresentacion';

switch($accion)
{
    case 'D':
                $insert = "UPDATE CatPresentaciones SET eCodEstatus=7 WHERE eCodPresentacion = ".$codigo;
        break;
    case 'F':
        $insert = "UPDATE CatPresentaciones SET eCodEstatus = 8 WHERE eCodPresentacion = ".$codigo;
        break;
    case 'C':
        $tHTML =  '<table class="table table-hover" width="100%">'.
        '<thead>'.
        '<tr>'.
        '<th>C&oacute;digo</th>'.
        '<th>Nombre</th>'.
        '</tr>'.
        '</thead>'.
        '<tbody>';
        /* hacemos select */
        $select = "SELECT * FROM (SELECT 
		cc.*, 
		ce.tIcono as estatus
		FROM
			CatPresentaciones cc
		INNER JOIN CatEstatus ce ON cc.eCodEstatus = ce.eCodEstatus
        WHERE 1=1".
        ($eCodPresentacion ? " AND cc.eCodPresentacion = ".$eCodPresentacion : "").
        ($data->tNombre    ?   $termino    :   "").
        " ORDER BY cc.$rdOrden $bOrden".
        " LIMIT 0, $eLimit ".
		")N0 ";
		
        $rsConsulta = mysql_query($select);
        while($rConsulta=mysql_fetch_array($rsConsulta)){
            //imprimimos
       $tHTML .=    '<tr>'.
        '<td>'.menuEmergenteJSON($rConsulta{'eCodPresentacion'},'cata-pre-con').'</td>'.
        '<td>'.utf8_encode($rConsulta{'tNombre'}).'</td>'.
        '</tr>';
            //imprimimos
        }
        /* hacemos select */
        $tHTML .= '</tbody>'.
            '</table>';
        break;
}
        
if($accion=="D" || $accion=="F")
{      
    $rs = mysql_query($insert);

    if(!$rs)
    {
        $errores[] = 'Error al efectuar la operacion '.mysql_error();
    }

    if(!sizeof($errores))
    {
        $tDescripcion = "Se ha ".(($accion=="D") ? 'Eliminado' : 'Finalizado')." la presentación código ".sprintf("%07d",$codigo);
        $tDescripcion = "'".utf8_encode($tDescripcion)."'";
        $fecha = "'".date('Y-m-d H:i:s')."'";
        $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
        mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
    }
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores,'registros'=>(int)mysql_num_rows($rsConsulta),"consulta"=>$tHTML,"select",$select));

?>