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

$errores = array();

$data = json_decode( file_get_contents('php://input') );

/*Preparacion de variables*/

$codigo = $data->eCodublicacion ? $data->eCodublicacion : $data->eCodublicacion;
$accion = $data->tCodAccion ? $data->tCodAccion : $data->tAccion;

$eCodPromotoria = $data->eCodPromotoria ? $data->eCodPromotoria : false;
$eCodublicacion = $data->eCodublicacion ? $data->eCodublicacion : false;

    $terms = explode(" ",$data->tNombre);
    
    $termino = "";
    
    for($i=0;$i<sizeof($terms);$i++)
    {
        $termino .= " AND tNombre like '%".$terms[$i]."%' ";
    }

$fhFecha = $data->fhFechaInicio ? explode("/",$data->fhFechaInicio) : false;
$fhFecha2 = $data->fhFechaTermino ? explode("/",$data->fhFechaTermino) : false;

$fhFechaInicio = "'".$fhFecha[2]."-".$fhFecha[1]."-".$fhFecha[0]."'";
$fhFechaTermino = $fhFecha2 ? "'".$fhFecha2[2]."-".$fhFecha2[1]."-".$fhFecha2[0]."'" : "'".$fhFechaInicio."'";

$eLimit = $data->eMaxRegistros;
$bOrden = $data->rOrden;
$rdOrden = $data->rdOrden ? $data->rdOrden : 'eCodPublicacion';

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];


switch($accion)
{
    case 'D':
        $select = "SELECT COUNT(*) ePaquetes FROM RelEventosPaquetes WHERE eCodTipo = 1 AND eCodServicio = $codigo";
        $rContador = mysql_fetch_array(mysql_query($select));
        if($rContador{'ePaquetes'}>=1)
        {
            $errores[] = 'El paquete se encuentra en '.$rContador{'ePaquetes'}.' cotizacion(es). Imposible eliminar';
        }
        else
        {
        $insert = "DELETE FROM CatServicios WHERE eCodServicio = ".$codigo;
        }
        break;
    case 'F':
        $insert = "UPDATE CatServicios SET eCodEstatus = 8 WHERE eCodServicio = ".$codigo;
        break;
    case 'C':
        $tHTML =  '<table class="table table-hover" width="100%">'.
        '<thead>'.
        '<tr>'.
        '<th>C&oacute;digo</th>'.
		'<th>E</th>'.
        '<th>T&iacute;tulo</th>'.
        '<th class="text-left">Fecha</th>'.
        '</tr>'.
        '</thead>'.
        '<tbody>';
        /* hacemos select */
        $select =   " SELECT bp.*, ce.tIcono estatus".
            " FROM BitPublicaciones bp ".
            " INNER JOIN CatEstatus ce ON ce.tCodEstatus = bp.tCodEstatus ".
            " WHERE 1=1 ".
            ($fhFecha ? " AND DATE(bp.fhFechaActualizacion) BETWEEN $fhFechaInicio AND $fhFechaTermino " : " DATE(bp.fhFechaActualizacion) >= '".date('Y-m-d')."'").
            ($eCodPublicacion ? " AND bp.eCodPublicacion = eCodPublicacion ": "").
            " LIMIT 0, $eLimit ";
        
        $select = "SELECT * FROM ($select) N0 ORDER BY $rdOrden $bOrden";
        
        $rsConsulta = mysql_query($select);
        while($rConsulta=mysql_fetch_array($rsConsulta)){
         /* validamos si est√° cargado */
           
            
            //imprimimos
       $tHTML .=    '<tr>'.
                    '<td>'.menuEmergenteJSON($rConsulta{'eCodPublicacion'},'cata-pub-con').'</td>'.
                    '<td><i class="'.$rConsulta{'estatus'}.'"></i></td>'.
                    '<td>'.base64_decode($rConsulta{'tTitulo'}).'</td>'.
                    '<td>'.date('d/m/Y',strtotime($rConsulta{'fhFechaActualizacion'})).'</td>'.
                    '</tr>';
            //imprimimos
        }
        /* hacemos select */
        $tHTML .= '</tbody>'.
            '</table>';
        
        
        
        break;
}
        
 if(!sizeof($errores) && ($accion=="D" || $accion=="F"))
{       
        $rs = mysql_query($insert);

        if(!$rs)
        {
            $errores[] = 'Error al efectuar la operacion '.mysql_error();
        }

     if(!sizeof($errores))
     {
         $tDescripcion = "Se ha ".(($accion=="D") ? 'Eliminado' : 'Finalizado')." el paquete c®Ædigo ".sprintf("%07d",$codigo);
         $tDescripcion = "'".utf8_encode($tDescripcion)."'";
         $fecha = "'".date('Y-m-d H:i:s')."'";
         $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
         mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
     }
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores,'registros'=>(int)mysql_num_rows($rsConsulta),"consulta"=>$tHTML,"query"=>$select));

?>