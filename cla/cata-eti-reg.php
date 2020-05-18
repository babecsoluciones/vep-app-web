<? header('Access-Control-Allow-Origin: *');  ?>
<? header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method"); ?>
<? header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE"); ?>
<? header("Allow: GET, POST, OPTIONS, PUT, DELETE"); ?>
<? header('Content-Type: application/json'); ?>
<?

error_reporting(0);
ini_set('display_errors', 0);

if (isset($_SERVER{'HTTP_ORIGIN'})) {
        header("Access-Control-Allow-Origin: {$_SERVER{'HTTP_ORIGIN'}}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

require("../cnx/swgc-mysql.php");

session_start();

$errores = array();

function obtenerURL()
{
	$select = "SELECT tValor FROM SisVariables WHERE tNombre = 'tURL'";
    $rCFG = mysql_fetch_array(mysql_query($select));
    return $rCFG{'tValor'};
}

$data = json_decode( file_get_contents('php://input') );



/*Preparacion de variables*/
        
    $eCodCategoria   =   $data->eCodCategoria ? (int)$data->eCodCategoria : false;
    $eCodUsuario     =   $_SESSION['sessionAdmin']['eCodUsuario'];
    $eCodEntidad     =   $_SESSION['sessionAdmin']['eCodEntidad'] ? $_SESSION['sessionAdmin']['eCodEntidad'] : 1;
    $fhFecha         =   "'".date('Y-m-d H:i:s')."'";
    $eCodEstatus     =   3;
    $tNombre         =   $data->tNombre ? "'".base64_encode($data->tNombre)."'" : false;
    
        
    if(!$tNombre)       {$errores[] = 'El campo NOMBRE es obligatorio'; }

        
if(!sizeof($errores))
{
        if(!$eCodCategoria)
        {
            $insert = " INSERT INTO CatCategorias
            (
            tNombre
			)
            VALUES
            (
            $tNombre
            )";
        }
        else
        {
            $insert = "UPDATE 
                            CatServicios
                        SET
            				tNombre=$tNombre
                            WHERE
                            eCodCategoria = ".$eCodCategoria;
        }
}
        
        $rs = mysql_query($insert);

        

        if(!$rs)
        {
            $errores[] = 'Error de insercion/actualizacion de la categoria '.mysql_error();
        }


if(!sizeof($errores))
{
    $tDescripcion = "Se ha insertado/actualizado la categoria ".sprintf("%07d",$eCodDescanso);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>