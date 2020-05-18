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
        
        $eCodProducto            = $data->eCodProducto ? $data->eCodProducto : false;
        $tNombre                = $data->tNombre ? "'".utf8_encode($data->tNombre)."'" : false;
		$eCodUsuario            = $_SESSION['sessionAdmin']['eCodUsuario'];
		$fhFechaCreacion        = "'".date('Y-m-d H:i')."'";

        if(!$tNombre)
            $errores[] = 'El nombre es obligatorio';

        
        if(!sizeof($errores))
        {
    if(!$eCodProducto)
        {
            $insert = " INSERT INTO CatProductos
            (
            tNombre
			)
            VALUES
            (
            $tNombre
            )";
            
            $bTipo = 1;
        }
        else
        {
            $insert = "UPDATE 
                            CatProductos
                        SET
                            tNombre= $tNombre
                            WHERE
                            eCodProducto = ".$eCodProducto;
                            
                            $bTipo = 2;
        }
}
        
        
        $rs = mysql_query($insert);
        
        $eCodProducto = $eCodProducto ? $eCodProducto : mysql_insert_id();

        if(!$rs)
        {
            $errores[] = 'Error de insercion/actualizacion del producto '.mysql_error();
        }

if(!sizeof($errores))
{
    $tDescripcion = "Se ha ".(($bTipo==1) ? 'insertado' : 'actualizado')." el producto ".sprintf("%07d",$eCodProducto);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>