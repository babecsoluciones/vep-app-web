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
        $nombre = "./fot/".uniqid().'.jpg';
        $datos1 = explode(',', ($datos));
        $content = base64_decode($datos1[1]);
        
        $pf = fopen($nombre,"w");
        fwrite($pf,$content);
        fclose($pf);
        
        return str_replace("./fot","/fot",$nombre);
    }

session_start();

$errores = array();

$data = json_decode( file_get_contents('php://input') );

/*Preparacion de variables*/
        
        $eCodPromotoria = $data->eCodPromotoria ? $data->eCodPromotoria : false;
        $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
        $eCodTipoImagen = $data->eCodTipoImagen ? $data->eCodTipoImagen : false;
        $eCodTienda = $data->eCodTienda ? $data->eCodTienda : false;
        

        $fhFecha = "'".date('Y-m-d H:i:s')."'";

        if(!$eCodPromotoria)
            $errores[] = 'No se recibio el codigo de la promotoria';
        if(!$eCodTienda)
            $errores[] = 'Falta indicar la tienda';

        if(!$eCodTipoImagen)
            $errores[] = 'Falta indicar el tipo de imagen';
        
        if(sizeof((array)$data->fotos)<2)
            $errores[] = 'Debe ingresar al menos una foto';

        if(!sizeof($errores)){
            $bTipo = 1;

            foreach($data->fotos as $imagen)
            {
                $tFotografia       	= $imagen->tArchivo ? "'".base64imagen($imagen->tArchivo)."'" : false;
                if($tFotografia)
                {
                    $rs = mysql_query("INSERT INTO RelPromotoriasImagenes (eCodPromotoria,eCodTienda,eCodTipoImagen,eCodUsuario,tLatitud,tLongitud,tArchivo,fhFecha) VALUES ($eCodPromotoria,$eCodTienda,$eCodTipoImagen,$eCodUsuario,'-','-',$tFotografia,$fhFecha)");
                    if(!$rs)
                    { $errores[] = 'Error al guardar la imagen'; }
                }
            }
       
        }

if(!sizeof($errores))
{
    $tDescripcion = "Se ha ".(($bTipo==1) ? 'insertado' : 'actualizado')." un total de ".sizeof((array)$data->fotos)." imágenes a la promotoria código ".sprintf("%07d",$eCodPromotoria);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>