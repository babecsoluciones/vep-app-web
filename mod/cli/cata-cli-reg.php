<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
$clSistema = new clSis();
session_start();

$select = "SELECT * FROM CatClientes WHERE eCodCliente = ".$_GET['v1'];
$rsPublicacion = mysql_query($select);
$rPublicacion = mysql_fetch_array($rsPublicacion);

?>
<?
if($_POST)
{
    $res = $clSistema -> registrarCliente();
    
    if($res)
    {
        ?>
            <div class="alert alert-success" role="alert">
                El cliente se guard&oacute; correctamente!
            </div>
<script>
setTimeout(function(){
    window.location="?tCodSeccion=cata-cli-con";
},2500);
</script>
<?
    }
    else
    {
  ?>
            <div class="alert alert-danger" role="alert">
                Error al procesar la solicitud!
            </div>
<?
    }
}
?>

<script>
function validar()
{
var bandera = false;
var mensaje = "";
var tNombre = document.getElementById("tNombre");
var tCorreo = document.getElementById("tCorreo");
var tTelefonoFijo = document.getElementById("tTelefonoFijo");
var tTelefonoMovil = document.getElementById("tTelefonoMovil");

    if(!tNombre.value)
    {
        mensaje += "* Nombre\n";
        bandera = true;
    };
    
    if(!tCorreo.value)
    {
        mensaje += "* E-mail\n";
        bandera = true;
    };
    if(!tTelefonoFijo.value)
    {
        mensaje += "* Telefono Fijo\n";
        bandera = true;
    };
    if(!tTelefonoMovil.value)
    {
        mensaje += "* Telefono Movil\n";
        bandera = true;
    };
    
    
    if(!bandera)
    {
        guardar();
    }
    else
    {
        alert("<- Favor de revisar la siguiente información ->\n"+mensaje)
    }
}
   
</script>
    
<div class="row">
    <div class="col-lg-12">
    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="eCodCliente" id="eCodCliente" value="<?=$_GET['v1']?>">
        <input type="hidden" name="eAccion" id="eAccion">
                           
                           <input type="hidden" class="form-control" name="tCorreo" id="tCorreo"  value="-" >
                           <input type="hidden" class="form-control" name="tTelefonoMovil" id="tTelefonoMovil" value="-" >
                           <input type="hidden" class="form-control" name="tComentarios" id="tComentarios" value="-">
                           
                            <div class="col-lg-12">
								
                                <div class="card col-lg-12">
                                    
                                    <div class="card-body card-block">
                                        <!--campos-->
                                        <div class="form-group">
              
           </div>
           <div class="form-group">
              <label>Nombre</label>
              <input type="text" class="form-control" name="tNombre" id="tNombre" placeholder="Nombre" value="<?=utf8_decode($rPublicacion{'tNombres'})?>" >
           </div>
           
                                        <!--campos-->
                                    </div>
                                </div>
                            </div>
    </form>
    </div>
                        </div>