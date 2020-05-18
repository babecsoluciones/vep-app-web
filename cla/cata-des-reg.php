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

function base64toImage($datos)
{
    
    $fname = "inv/".uniqid().'.jpg';
        $datos1 = explode(',', base64_decode($datos));
        $content = base64_decode($datos1[1]);
        //$img = filter_input(INPUT_POST, "image");
        //$img = str_replace(array('data:image/png;base64,','data:image/jpg;base64,'), '', base64_decode($data));
        //$img = str_replace(' ', '+', $img);
        //$img = base64_decode($img);
        
        //file_put_contents($fname, $img);
        
        $pf = fopen($fname,"w");
        fwrite($pf,$content);
        fclose($pf);
        
        return $fname;
}

$data = json_decode( file_get_contents('php://input') );



/*Preparacion de variables*/
        
    $eCodDescanso   =   $data->eCodDescanso ? (int)$data->eCodDescanso : false;
    $eCodUsuario    =   $_SESSION['sessionAdmin']['eCodUsuario'];
    $eCodEntidad    =   $_SESSION['sessionAdmin']['eCodEntidad'] ? $_SESSION['sessionAdmin']['eCodEntidad'] : 1;
    $fhFecha        =   "'".date('Y-m-d H:i:s')."'";
    $eCodEstatus    =   3;
    $tNombre        =   $data->tNombre ? "'".$data->tNombre."'" : false;
    $dPrecio        =   trim($data->dPrecio) ? "'".$data->dPrecio."'" : false;
    
        
    if(!$tNombre)       {$errores[] = 'El campo NOMBRE es obligatorio'; }
    if(!$dPrecio)     {$errores[] = 'El campo PRECIO es obligatorio'; }

        
if(!sizeof($errores))
{
        if(!$eCodServicio)
        {
            $insert = " INSERT INTO CatServicios
            (
            eCodEstatus,
            tNombre,
            dPrecio,
            eCodEntidad
			)
            VALUES
            (
            $eCodEstatus,
            $tNombre,
            $dPrecio,
            $eCodEntidad
            )";
        }
        else
        {
            $insert = "UPDATE 
                            CatServicios
                        SET
            				tNombre=$tNombre,
                            dPrecio=$dPrecio
                            WHERE
                            eCodDescanso = ".$eCodDescanso;
        }
}
        
        $rs = mysql_query($insert);

        

        if(!$rs)
        {
            $errores[] = 'Error de insercion/actualizacion del coffee break '.mysql_error();
        }


if(!sizeof($errores))
{
    $tDescripcion = "Se ha insertado/actualizado el coffee break ".sprintf("%07d",$eCodDescanso);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>