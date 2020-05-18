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
        
    $eCodCurso      =   $data->eCodCurso ? (int)$data->eCodCurso : false;
    $eCodUsuario    =   $_SESSION['sessionAdmin']['eCodUsuario'];
    $eCodEntidad    =   $_SESSION['sessionAdmin']['eCodEntidad'] ? $_SESSION['sessionAdmin']['eCodEntidad'] : 1;
    $fhFecha        =   "'".date('Y-m-d H:i:s')."'";
    $eCodEstatus    =   3;
    $tTitulo        =   $data->tTitulo ? "'".$data->tTitulo."'" : false;
    $tObjetivo      =   trim($data->tObjetivo) ? "'".$data->tObjetivo."'" : false;
    $tDescripcion   =   trim($data->tDescripcion) ? "'".$data->tDescripcion."'" : false;
    $tCodTipo       =   trim($data->tCodTipo) ? "'".$data->tCodTipo."'" : false;
    $fhFechaCurso   =   trim($data->fhFechaCurso) ? "'".date('Y-m-d H:i',strtotime($data->fhFechaCurso))."'" : false;
    $eHoras         =   $data->eHoras ? $data->eHoras : false;
    $tUbicacion     =   trim($data->tUbicacion) ? "'".$data->tUbicacion."'" : false;

    $tDias = trim($data->tDias) ? "'".$data->tDias."'" : false;
    $tPaypal = trim($data->tPaypal) ? "'".base64_encode($data->tPaypal)."'" : "NULL";

    $tFlyer = ($data->bFicheroFlyer) ? "'".$data->tFicheroFlyer."'" : ($data->tImagen ? "'".base64toImage(base64_encode($data->tImagen))."'" : false);

    $tSlider = ($data->bFicheroSlider) ? "'".$data->tFicheroSlider."'" : ($data->tImagen2 ? "'".base64toImage(base64_encode($data->tImagen2))."'" : false);

    $tArchivoDiploma = ($data->bFicheroDiploma) ? "'".$data->tFicheroDiploma."'" : ($data->tImagen3 ? "'".base64toImage(base64_encode($data->tImagen3))."'" : false);

    if(!$tTitulo)       {$errores[] = 'El campo TITULO es obligatorio'; }
    if(!$tObjetivo)     {$errores[] = 'El campo OBJETIVO es obligatorio'; }
    if(!$tDescripcion)  {$errores[] = 'El campo DESCRIPCION es obligatorio'; }
    if(!$fhFechaCurso)  {$errores[] = 'El campo FECHA CURSOes obligatorio'; }
    if(!$eHoras)        {$errores[] = 'El campo  HORAS TOTALES es obligatorio'; }
    if(!$tUbicacion)    {$errores[] = 'El campo UBICACION es obligatorio'; }
    if(!$tCodTipo)      {$errores[] = 'El campo TIPO es obligatorio'; }
    if(!$tFlyer)        {$errores[] = 'El campo FLYER es obligatorio'; }
    if(!$tSlider)       {$errores[] = 'El campo SLIDER es obligatorio'; }
    if(!$tDias)       {$errores[] = 'El campo DIAS es obligatorio'; }

    $items = $data->modalidad;
    $eModalidad = 0;
        foreach($items as $modalidad)
        {
            $eLugares       = $modalidad->eLugares;
            $dPrecio        = $modalidad->dPrecio;
            
            if($eLugares>0 && $dPrecio>0)
            {
               $eModalidad++;
            }
        }

    if($eModalidad==0)       {$errores[] = 'Debe ingresar al menos una modalidad'; }
        
if(!sizeof($errores))
{
        if(!$eCodCurso)
        {
            $insert = " INSERT INTO BitCursos
            (
            eCodUsuario,
            fhFecha,
            eCodEstatus,
            tTitulo,
            tObjetivo,
            tDescripcion,
            fhFechaCurso,
            eHoras,
            tUbicacion,
            tFlyer,
            tSlider,
            tCodTipo,
            eCodEntidad,
            tPaypal,
            tDias
			)
            VALUES
            (
            $eCodUsuario,
            $fhFecha,
            $eCodEstatus,
            $tTitulo,
            $tObjetivo,
            $tDescripcion,
            $fhFechaCurso,
            $eHoras,
            $tUbicacion,
            $tFlyer,
            $tSlider,
            $tCodTipo,
            $eCodEntidad,
            $tPaypal,
            $tDias
            )";
        }
        else
        {
            $insert = "UPDATE 
                            BitCursos
                        SET
            				eCodUsuario=$eCodUsuario,
                            fhFecha=$fhFecha,
                            eCodEstatus=$eCodEstatus,
                            tTitulo=$tTitulo,
                            tObjetivo=$tObjetivo,
                            tDescripcion=$tDescripcion,
                            fhFechaCurso=$fhFechaCurso,
                            eHoras=$eHoras,
                            tUbicacion=$tUbicacion,
                            tFlyer=$tFlyer,
                            tSlider=$tSlider,
                            tCodTipo=$tCodTipo,
                            tPaypal=$tPaypal,
                            tDias=$tDias
                            WHERE
                            eCodCurso = ".$eCodCurso;
        }
}
        
        $rs = mysql_query($insert);

        $pf = fopen("logCursos.txt","a");
        fwrite($pf,$insert."\n\n");
        fclose($pf);

        if($rs)
        {
            
            $eCodCurso = $eCodCurso ? $eCodCurso : mysql_insert_id();

        /* ****** MODALIDADES ******* */
        mysql_query("DELETE FROM RelCursosModalidades WHERE eCodCurso = $eCodCurso");
        $items = $data->modalidad;

        foreach($items as $modalidad)
        {
            $eCodModalidad  = $modalidad->eCodModalidad;
            $eLugares       = $modalidad->eLugares ? $modalidad->eLugares : false;
            $dPrecio        = $modalidad->dPrecio ? $modalidad->dPrecio : false;
            
            if($eLugares && $dPrecio)
            {
                $insert = "INSERT INTO RelCursosModalidades (eCodCurso,eCodModalidad,eLugares,dPrecio) VALUES ($eCodCurso,$eCodModalidad,$eLugares,$dPrecio);";
                mysql_query($insert);
                
                        $pf = fopen("logCursos.txt","a");
        fwrite($pf,$insert."\n\n");
        fclose($pf);
            }
        }
        /* ****** FIN MODALIDADES ******* */
            
        /* ****** MODULOS ******* */
        
        $items = $data->modulos;
            
            $arrModulos = array();

        foreach($items as $modulos)
        {
            $eCodRegistro = $modulos->eCodRegistro ? $modulos->eCodRegistro : false;
           
            $eModulo = $modulos->eModulo ? $modulos->eModulo : false;
            $tNombre = trim($modulos->tNombre) ? "'".$modulos->tNombre."'" : false;
            $fhFechaModulo = trim($modulos->fhFechaModulo) ? "'".date('Y-m-d H:i',strtotime($modulos->fhFechaModulo))."'" : false;
            $eHoras = $modulos->eHoras ? $modulos->eHoras : 0;
            $tEmbed = trim($modulos->tEmbed) ? "'".$modulos->tEmbed."'" : "NULL";
            
           
            
            if($fhFechaModulo&&$tNombre)
            {
                if(!$eCodRegistro)
                {
                    $insert = "INSERT INTO RelCursosModulos
                    (
                    eCodCurso,
                    eModulo,
                    tNombre,
                    fhFechaModulo,
                    eHoras,
                    tEmbed
                    )
                    VALUES
                    (
                    $eCodCurso,
                    $eModulo,
                    $tNombre,
                    $fhFechaModulo,
                    $eHoras,
                    $tEmbed
                    )";
                }
                else
                {
                    $insert = "UPDATE RelCursosModulos
                    SET
                    eCodCurso=$eCodCurso,
                    eModulo=$eModulo,
                    tNombre=$tNombre,
                    fhFechaModulo=$fhFechaModulo,
                    eHoras=$eHoras,
                    tEmbed=$tEmbed
                    WHERE eCodRegistro = $eCodRegistro";
                    
                    $arrModulos[] = $eCodRegistro;
                }
                
                      $pf = fopen("logCursos.txt","a");
        fwrite($pf,$insert."\n\n");
        fclose($pf);
                
                if(!mysql_query($insert))
                { $errores[] = 'Error de insercion/actualizacion del modulo al curso '.mysql_error().' - '.$insert; }
            }
        }
            
            mysql_query("DELETE FROM RelCursosModulos WHERE eCodRegistro NOT IN (".implode(",",$arrModulos).")");
        /* ****** FIN MODULOS ******* */
            
        }
        else
        {
            $errores[] = 'Error de insercion/actualizacion del curso '.mysql_error().' - '.$insert;
        }


if(!sizeof($errores))
{
    $tDescripcion = "Se ha insertado/actualizado el curso ".sprintf("%07d",$eCodCurso);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>