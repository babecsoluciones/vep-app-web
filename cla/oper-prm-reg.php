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

function validarProducto($cadena)
{
    $select = "SELECT * FROM CatProductos WHERE tNombre = $cadena";
    
        $pf = fopen("logPromo.txt","a");
        fwrite($pf,$select."\n\n");
        fclose($pf);

    $rs = mysql_query($select);
    if(mysql_num_rows($rs))
    {
        $r = mysql_fetch_array($rs);
        $eCodProducto = $r{'eCodProducto'};
    }
    else
    {
        $query = "INSERT INTO CatProductos (tNombre) VALUES ($cadena)";
        $rs = mysql_query($query);
        
        $pf = fopen("logPromo.txt","a");
        fwrite($pf,$query."\n\n");
        fclose($pf);

        $eCodProducto = mysql_insert_id();
    }
    
    return $eCodProducto;
}

function validarPresentacion($cadena)
{
    $select = "SELECT * FROM CatPresentaciones WHERE tNombre = $cadena";
    
        $pf = fopen("logPromo.txt","a");
        fwrite($pf,$select."\n\n");
        fclose($pf);
        
    $rs = mysql_query($select);
    if(mysql_num_rows($rs))
    {
        $r = mysql_fetch_array($rs);
        $eCodPresentacion = $r{'eCodPresentacion'};
    }
    else
    {
        $query = "INSERT INTO CatPresentaciones (tNombre) VALUES ($cadena)";
        $rs = mysql_query($query);
        
        $pf = fopen("logPromo.txt","a");
        fwrite($pf,$query."\n\n");
        fclose($pf);
        
        $eCodPresentacion = mysql_insert_id();
    }
    
    return $eCodPresentacion;
}

session_start();

$errores = array();

$data = json_decode( file_get_contents('php://input') );

$pf = fopen("logPromo.txt","w");
fwrite($pf,json_encode($data)."\n\n");
fclose($pf);

/*Preparacion de variables*/
        
        $eCodPromotoria = $data->eCodPromotoria ? $data->eCodPromotoria : false;
        $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
        $eCodCliente = $data->eCodCliente ? $data->eCodCliente : false;
        $fhFechaPromotoria = $data->fhFechaPromotoria ? explode("/",$data->fhFechaPromotoria) : false;
        $eCodEstatus = 2;

        $fhFechaPro = "'".$fhFechaPromotoria[2]."-".$fhFechaPromotoria[1]."-".$fhFechaPromotoria[0]."'";


        $fhFecha = "'".date('Y-m-d H:i:s')."'";

        if(!$eCodCliente)
            $errores[] = 'El cliente es obligatorio';
        if(!$fhFechaPromotoria)
            $errores[] = 'La fecha es obligatoria';

        //if(sizeof((array)$data->tiendas)<2)
        //    $errores[] = 'Debe ingresar al menos una tienda';

        if(sizeof((array)$data->productos)<2)
            $errores[] = 'Debe ingresar al menos un producto';

        if(sizeof((array)$data->supervisores)<2)
            $errores[] = 'Debe ingresar al menos un supervisor';
        //if(sizeof((array)$data->promotores)<2)
        //    $errores[] = 'Debe ingresar al menos un promotor';
        if(sizeof((array)$data->clientes)<2)
            $errores[] = 'Debe ingresar al menos un usuario cliente';

        if(!sizeof($errores)){

        if(!$eCodPromotoria)
        {
           $query = "INSERT INTO BitPromotoria (eCodEstatus,eCodUsuario,eCodCliente,fhFecha,fhFechaPromotoria) VALUES ($eCodEstatus,$eCodUsuario,$eCodCliente,$fhFecha,$fhFechaPro)"; 
            $bTipo = 1;
            

        }
        else
        {
            $query = "UPDATE BitPromotoria SET eCodCliente = $eCodCliente, fhFechaPromotoria = $fhFechaPro WHERE eCodPromotoria = $eCodPromotoria";
            $bTipo = 2;
        }
            
            $pf = fopen("logPromo.txt","a");
            fwrite($pf,$query."\n\n");
            fclose($pf);
            
            $rs = mysql_query($query);
        
        $eCodPromotoria = $eCodPromotoria ? $eCodPromotoria : mysql_insert_id();

            if(!$rs)
            {
                $errores[] = 'Error de insercion/actualizacion de la promotoria ';
            }
            else
            {
                //vaciamos tablas previo a actualizacion
                mysql_query("DELETE FROM RelPromotoriasTiendas WHERE eCodPromotoria = $eCodPromotoria");
                mysql_query("DELETE FROM RelPromotoriasProductos WHERE eCodPromotoria = $eCodPromotoria");
                mysql_query("DELETE FROM RelPromotoriasPresentaciones WHERE eCodPromotoria = $eCodPromotoria");
                mysql_query("DELETE FROM RelPromotoriasSupervisores WHERE eCodPromotoria = $eCodPromotoria");
                mysql_query("DELETE FROM RelPromotoriasPromotores WHERE eCodPromotoria = $eCodPromotoria");
                mysql_query("DELETE FROM RelPromotoriasClientes WHERE eCodPromotoria = $eCodPromotoria");
                
                //agregamos tiendas
                /*foreach($data->tiendas as $tienda)
                {
                    $eCodTienda = $tienda->eCodTienda;
                    if($eCodTienda>0)
                    { 
                        $rs = mysql_query("INSERT INTO RelPromotoriasTiendas(eCodPromotoria,eCodTienda) VALUES ($eCodPromotoria,$eCodTienda)");
                        if(!$rs){ $errores[] = 'Error al insertar la tienda'; }
                    }
                }*/
                
                //agregamos productos
                $i = 0;
                foreach($data->productos as $producto)
                {
                    $eCodProducto = $producto->eCodProducto ? $producto->eCodProducto : false;
                    $tProducto = $producto->tProducto ? "'".utf8_encode($producto->tProducto)."'" : false;
                    
                    if($tProducto)
                    {
                    if(!$eCodProducto && $tProducto)
                        { $eCodProducto = validarProducto($tProducto); }
                    
                    $rs = mysql_query("INSERT INTO RelPromotoriasProductos (eCodPromotoria,eCodProducto) VALUES ($eCodPromotoria,$eCodProducto)");
                    if(!$rs){ $errores[] = 'Error al insertar el producto'; }
                    else
                    {
                        foreach($data->presentaciones->$i as $presentacion)
                        {
                            
                            $pf = fopen("logPresentaciones$i.txt","a");
                            fwrite($pf,json_encode($presentacion)."\n\n");
                            fclose($pf);
                            
                        $eCodPresentacion = $presentacion->eCodPresentacion ? $presentacion->eCodPresentacion : false;
                    $tPresentacion = $presentacion->tPresentacion ? "'".utf8_encode($presentacion->tPresentacion)."'" : false;
                    
                            if($tPresentacion){
                                
                                $eCodPresentacion = validarPresentacion($tPresentacion);
                                
                        
                    
                            $query = "INSERT INTO RelPromotoriasPresentaciones (eCodPromotoria,eCodProducto,eCodPresentacion) VALUES ($eCodPromotoria,$eCodProducto,$eCodPresentacion)";
                            $rs = mysql_query($query);
                            
                            $pf = fopen("logPromo.txt","a");
        fwrite($pf,$query."\n\n");
        fclose($pf);
        
                            if(!$rs){ $errores[] = 'Error al insertar la presentacion'; }   
                        }
                        
                    }
                        
                    }
                    }
                    $i++;
                }
                
                //agregamos supervisores
                $i = 0;
                foreach($data->supervisores as $supervisor)
                {
                    $eCodSupervisor = $supervisor->eCodSupervisor;
                    
                    if($eCodSupervisor>0)
                    { 
                        $query = "INSERT INTO RelPromotoriasSupervisores(eCodPromotoria,eCodSupervisor) VALUES ($eCodPromotoria,$eCodSupervisor)";
                        
                        $rs = mysql_query($query);
                        
                        $pf = fopen("logPromo.txt","a");
                        fwrite($pf,$query."\n\n");
                        fclose($pf);
                        
                        if(!$rs){ $errores[] = 'Error al insertar el supervisor'; } 
                        else
                        {
                            
                            //agregamos promotores
                            foreach($data->promotores->$i as $promotor)
                            {
                               
                                
                                $eCodPromotor   = $promotor->eCodPromotor;
                                $eCodTienda     = $promotor->eCodTienda;
                                if($eCodPromotor>0 && $eCodTienda>0)
                                { 
                                    $query = "INSERT INTO RelPromotoriasPromotores(eCodPromotoria,eCodPromotor,eCodSupervisor,eCodTienda) VALUES ($eCodPromotoria,$eCodPromotor,$eCodSupervisor,$eCodTienda)";
                                    
                                    $rs = mysql_query($query);
                        
                                    $pf = fopen("logPromo.txt","a");
                                    fwrite($pf,$query."\n\n");
                                    fclose($pf);
                        
                                    
                                    if(!$rs){ $errores[] = 'Error al insertar el promotor'; }  
                                }
                            }
                        }
                    }
                    $i++;
                }
                
                //agregamos clientes
                foreach($data->clientes as $cliente)
                {
                    $eCodCliente = $cliente->eCodCliente;
                    if($eCodCliente>0)
                    { 
                        $rs = mysql_query("INSERT INTO RelPromotoriasClientes(eCodPromotoria,eCodUsuario) VALUES ($eCodPromotoria,$eCodCliente)");
                        if(!$rs){ $errores[] = 'Error al insertar el cliente-marca'; }  
                    }
                }
                
            }
        }

if(!sizeof($errores))
{
    $tDescripcion = "Se ha ".(($bTipo==1) ? 'insertado' : 'actualizado')." la promotoria ".sprintf("%07d",$eCodPromotoria);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>