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
        
        $eCodTienda             = $data->eCodTienda ? $data->eCodTienda : false;
        $tNombre                = $data->tNombre ? "'".utf8_encode($data->tNombre)."'" : false;
        $tDireccion             = $data->tDireccion ? "'".utf8_encode($data->tDireccion)."'" : false;
		$eCodUsuario            = $_SESSION['sessionAdmin']['eCodUsuario'];
		$fhFechaCreacion        = "'".date('Y-m-d H:i')."'";

        if(!$tNombre)
            $errores[] = 'El nombre es obligatorio';

        if(!$tDireccion)
            $errores[] = 'La direccion es obligatoria';

        
        if(!sizeof($errores))
        {
    if(!$eCodTienda)
        {
            $insert = " INSERT INTO CatTiendas
            (
            tNombre,
            tDireccion,
			eCodEstatus
			)
            VALUES
            (
            $tNombre,
            $tDireccion,
			3
            )";
            
            $bTipo = 1;
        }
        else
        {
            $insert = "UPDATE 
                            CatTiendas
                        SET
                            tNombre= $tNombre,
                            tDireccion= $tDireccion
                            WHERE
                            eCodTienda = ".$eCodTienda;
                            
                            $bTipo = 2;
        }
}
        
        
        $rs = mysql_query($insert);
        
        $eCodTienda = $eCodTienda ? $eCodTienda : mysql_insert_id();

        if(!$rs)
        {
            $errores[] = 'Error de insercion/actualizacion de la tienda '.mysql_error();
        }

if(!sizeof($errores))
{
    $tDescripcion = "Se ha ".(($bTipo==1) ? 'insertado' : 'actualizado')." la tienda ".sprintf("%07d",$eCodTienda);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>