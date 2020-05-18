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

$data = json_decode( file_get_contents('php://input') );

$eCodUsuario = $data->eCodUsuario ? $data->eCodUsuario : false;
        $eCodPerfil = 4;
        $tNombre = $data->tNombre ? "'".utf8_encode($data->tNombre)."'" : false;
        $tApellidos = $data->tApellidos ? "'".utf8_encode($data->tApellidos)."'" : false;
        $tPasswordAcceso = $data->tPassword ? "'".base64_encode($data->tPassword)."'" : false;
        $tPasswordOperaciones = $data->tPassword ? "'".base64_encode($data->tPassword)."'" : false;
        $tCorreo = $data->tCorreo ? "'".$data->tCorreo."'" : false;
        $bAll = $data->bAll ? 1 : 0;

$tTitulo        = $data->tTitulo            ?   "'".$data->tTitulo."'"          : "''";
$tTelefonoFijo  = $data->tTelefonoFijo      ?   "'".$data->tTelefonoFijo."'"    : "''";
$tTelefonoMovil = $data->tTelefonoMovil     ?   "'".$data->tTelefonoMovil."'"   : "''";
$tEstado       = $data->tEstado           ?   "'".$data->tEstado."'"         : "''";
        
        $fhFechaCreacion = "'".date('Y-m-d H:i:s')."'";
        
        $select = "SELECT * FROM SisUsuarios WHERE tCorreo=$tCorreo";
        $rsUsuarios = mysql_query($select);
        
 if(!$tNombre)  
     {$errores[] = utf8_encode('El nombre es obligatorio');}
 if(!$tApellidos)  
     {$errores[] = utf8_encode('Los apellidos son obligatorios');}
 if(!$tPasswordAcceso)  
     {$errores[] = utf8_encode('El password de acceso es obligatorio');}
 if(!$tCorreo)  
     {$errores[] = utf8_encode('El correo es obligatorio');}
if($tCorreo && mysql_num_rows($rsUsuarios)>0)  
     {$errores[] = utf8_encode('El correo ya está registrado. Intente nuevamente...');}

if(!sizeof($errores))
{
        if(!$eCodUsuario)
        {
            $insert = "INSERT INTO SisUsuarios (tNombre, tApellidos, tCorreo, tPasswordAcceso, tPasswordOperaciones,  eCodEstatus, eCodPerfil, fhFechaCreacion,bAll, tTitulo, tTelefonoFijo, tTelefonoMovil, tEstado) VALUES ($tNombre, $tApellidos, $tCorreo, $tPasswordAcceso, $tPasswordOperaciones, 3, $eCodPerfil, $fhFechaCreacion,$bAll, $tTitulo, $tTelefonoFijo, $tTelefonoMovil, $tEstado)";
        }
        else
        {
            $insert = "UPDATE SisUsuarios SET
            tPasswordAcceso = $tPasswordAcceso,
            tPasswordOperaciones = $tPasswordOperaciones,
            eCodPerfil = $eCodPerfil,
            bAll = $bAll
            WHERE
            eCodUsuario = $eCodUsuario";
        }
        $rs = mysql_query($insert);

        if(!$rs)
        {
            $errores[] = utf8_encode('Error de insercion/actualizacion del usuario '.mysql_error().' '.$insert);
        }
}
        
        


echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>