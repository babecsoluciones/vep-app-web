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
        
    $eCodRenta      =   $data->eCodRenta ? (int)$data->eCodRenta : false;
    $eCodUsuario    =   $_SESSION['sessionAdmin']['eCodUsuario'];
    $eCodEntidad    =   $_SESSION['sessionAdmin']['eCodEntidad'] ? $_SESSION['sessionAdmin']['eCodEntidad'] : 1;
    $fhFecha        =   "'".date('Y-m-d H:i:s')."'";
    $fhFechaRenta   =   trim($data->fhFechaRenta) ? "'".date('Y-m-d H:i',strtotime($data->fhFechaRenta))."'" : false;
    $tNombre        =   trim($data->tNombre) ? "'".$data->tNombre."'" : false;
    $tCorreo        =   trim($data->tCorreo) ? "'".$data->tCorreo."'" : false;
    $tTelefono      =   trim($data->tTelefono) ? "'".$data->tTelefono."'" : false;
    $eCodMontaje    =   trim($data->eCodMontaje) ? $data->eCodMontaje : false;
    $eCodDescanso   =   trim($data->eCodDescanso) ? $data->eCodDescanso : false;
    $eHoras         =   trim($data->eHoras) ? $data->eHoras : false;
    $ePersonas      =   trim($data->ePersonas) ? $data->ePersonas : false;
    $eCodEstatus    =   3;
   
    
        
    if(!$tNombre)       {$errores[] = 'El campo NOMBRE es obligatorio'; }
    if(!$fhFechaRenta)  {$errores[] = 'El campo FECHA DE LA RENTA es obligatorio'; }
    if(!$tCorreo)       {$errores[] = 'El campo E-MAIL es obligatorio'; }
    if(!$tTelefono)     {$errores[] = 'El campo TELEFONO es obligatorio'; }
    if(!$eCodMontaje)   {$errores[] = 'El campo MONTAJE es obligatorio'; }
    if(!$eCodDescanso)  {$errores[] = 'El campo COFFEE BREAK es obligatorio'; }
    if(!$eHoras)        {$errores[] = 'El campo HORAS es obligatorio'; }
    if(!$ePersonas)     {$errores[] = 'El campo PERSONAS es obligatorio'; }

    $items = $data->servicios;
    $eServicios = 0;
        foreach($items as $modalidad)
        {
            $eCodServicio   = $modalidad->eCodServicio;
            
            if($eCodServicio>0)
            {
               $eServicios++;
            }
        }

    if($eServicios==0)       {$errores[] = 'Debe ingresar al menos un servicio'; }
   
        
if(!sizeof($errores))
{
        if(!$eCodRenta)
        {
            $insert = " INSERT INTO BitSolicitudesRentas
            (
            eCodEntidad,
            fhFecha,
            fhFechaRenta,
            eCodMontaje,
            tNombre,
            tCorreo,
            tTelefono,
            eHoras,
            ePersonas,
            eCodDescanso
			)
            VALUES
            (
            $eCodEntidad,
            $fhFecha,
            $fhFechaRenta,
            $eCodMontaje,
            $tNombre,
            $tCorreo,
            $tTelefono,
            $eHoras,
            $ePersonas,
            $eCodDescanso
            )";
        }
        else
        {
            $insert = "UPDATE 
                            BitSolicitudesRentas
                        SET
            				fhFechaRenta =$fhFechaRenta,
                            eCodMontaje =$eCodMontaje,
                            tNombre =$tNombre,
                            tCorreo =$tCorreo,
                            tTelefono =$tTelefono,
                            eHoras =$eHoras,
                            ePersonas =$ePersonas,
                            eCodDescanso = $eCodDescanso
                            WHERE
                            eCodRenta = ".$eCodRenta;
        }
}
        
        $rs = mysql_query($insert);

        

        if($rs)
        {
            
            $eCodRenta = $eCodRenta ? $eCodRenta : mysql_insert_id();

        /* ****** MODALIDADES ******* */
        mysql_query("DELETE FROM RelSolicitudesRentasServicios WHERE eCodRenta = $eCodRenta");
        $items = $data->servicios;
    
        foreach($items as $modalidad)
        {
            $eCodServicio   = $modalidad->eCodServicio;
            
            if($eCodServicio>0)
            {
               mysql_query("INSERT INTO RelSolicitudesRentasServicios (eCodRenta,eCodServicio) VALUES ($eCodRenta,$eCodServicio)");
            }
        }
        /* ****** FIN MODALIDADES ******* */
            
        
            
        }
        else
        {
            $errores[] = 'Error de insercion/actualizacion de la renta  '.mysql_error();
        }


if(!sizeof($errores))
{
    $tDescripcion = "Se ha insertado/actualizado la solicitud de renta ".sprintf("%07d",$eCodRenta);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>