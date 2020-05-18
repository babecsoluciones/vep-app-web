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

function base64imagen($datos)
    {
        $nombre = "../cni/".uniqid().'.jpg';
        $datos1 = explode(',', ($datos));
        $content = base64_decode($datos1[1]);
        
        $pf = fopen($nombre,"w");
        fwrite($pf,$content);
        fclose($pf);
        
        return str_replace("../cni/","",$nombre);
    }

$fhFecha = "'".date('Y-m-d H:i:s')."'";
$eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];


session_start();

$errores = array();

$data = json_decode( file_get_contents('php://input') );

/*Preparacion de variables*/
$eCodPublicacion        = $data->eCodPublicacion                    ? $data->eCodPublicacion                     : false;
$eCodTipoPublicacion    = $data->eCodTipoPublicacion                ? $data->eCodTipoPublicacion                 : false;
$tTitulo                = trim($data->tTitulo)                      ? "'".base64_encode($data->tTitulo)."'"      : false;
$tContenido             = trim($data->tContenido )                  ? "'".base64_encode($data->tContenido)."'"   : false;
$bRequiereProceso       = $data->bRequiereProceso                   ? 1                                          : 0;
$fhFechaActualizacion   = $fhFecha;
$tImagen                = ($data->bImagen && !$data->imgArchivo)    ? "'".$data->tImagen."'"                     : ( $data->imgArchivo ? "'".base64imagen($data->imgArchivo)."'" : false);


$eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];


if(!$eCodTipoPublicacion){ $errores[] = "El tipo de publicación es obligatorio"; }
if(!$tTitulo){ $errores[] = "El título es obligatorio"; }
if(!$tContenido){ $errores[] = "El contenido es obligatorio"; }
if(!$tImagen){ $errores[] = "La imagen es obligatoria"; }


if(!sizeof($errores))
{
    if(!$eCodPublicacion)
    {
        $query = "INSERT INTO BitPublicaciones
                    (
                        eCodTipoPublicacion,
                        tCodEstatus,
                        eCodUsuario,
                        fhFecha,
                        fhFechaActualizacion,
                        tTitulo,
                        tContenido,
                        tImagen,
                        bRequiereProceso
                    ) VALUES(
                        $eCodTipoPublicacion,
                        'AC',
                        $eCodUsuario,
                        $fhFecha,
                        $fhFechaActualizacion,
                        $tTitulo,
                        $tContenido,
                        $tImagen,
                        $bRequiereProceso
                    )";
        $bTipo = 1;
    }
    else
    {
        $query = "UPDATE BitPublicaciones
                    SET
                        eCodTipoPublicacion     =   $eCodTipoPublicacion,
                        eCodUsuario             =   $eCodUsuario,
                        fhFechaActualizacion    =   $fhFechaActualizacion,
                        tTitulo                 =   $tTitulo,
                        tContenido              =   $tContenido,
                        tImagen                 =   $tImagen,
                        bRequiereProceso        =   $bRequiereProceso
                    WHERE eCodPublicacion       =   $eCodPublicacion
                    ";
        $bTipo = 2;
    }
    $rs = mysql_query($query);
    
    $eCodPublicacion = $eCodPublicacion ? $eCodPublicacion : mysql_insert_id();
    
    if(!$rs){ $errores[] = "Error al insertar la publicación"; }
}

if(!sizeof($errores))
{
    $tDescripcion = "Se ha ".(($bTipo==1) ? 'insertado' : 'actualizado')." la publicacion con código ".sprintf("%07d",$eCodPublicacion);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>