<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
//include("inc/fun-ini.php");

$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$select = "	SELECT 
	ci.*
FROM
	CatMontajes ci
	
WHERE ci.eCodMontaje = ".$_GET['v1'].
($bAll ? "" : " AND ci.eCodEntidad=".$_SESSION['sessionAdmin']['eCodEntidad']);
//echo $select;
$rsPublicacion = mysql_query($select);
$rPublicacion = mysql_fetch_array($rsPublicacion);

?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.js"></script>
	<script type="text/javascript">
		
   
function validar()
{
var bandera = false;

   
    
    if(!bandera)
    {
        guardar();
    }
    else
    {
        alert("<- Los campos en rojo son obligatorios ->")
    }
}
   
</script>
    
<div class="row">
    <div class="col-lg-12">
    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" id="eCodMontaje" name="eCodMontaje" value="<?=$_GET['v1']?>">
        <input type="hidden" name="eAccion" id="eAccion">
        <input type="hidden" name="eCodEntidad" id="eCodEntidad" value="<?=$_SESSION['sessionAdmin']['eCodEntidad'];?>">
                            <div class="col-lg-12">
								<h2 class="title-1 m-b-25"><?=$_GET['v1'] ? 'Actualizar ' : '+ '?>Montaje</h2>
                                <div class="card col-lg-12">
                                    
                                    <div class="card-body card-block">
                                        <!--campos-->
                                        <div class="form-group">
              
           </div>
           <div class="form-group">
              <label>Nombre</label>
              <input type="text" class="form-control" name="tNombre" id="tNombre" placeholder="Nombre" value="<?=($rPublicacion{'tNombre'})?>" >
           </div>
           
           
           
                                        <!--campos-->
                                    </div>
                                </div>
                            </div>
    </form>
    </div>
                        </div>

