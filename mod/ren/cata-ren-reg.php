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
	BitSolicitudesRentas ci
	
WHERE ci.eCodRenta = ".$_GET['v1'].
($bAll ? "" : " AND ci.eCodEntidad=".$_SESSION['sessionAdmin']['eCodEntidad']);
//echo $select;
$rsPublicacion = mysql_query($select);
$rPublicacion = mysql_fetch_array($rsPublicacion);

?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.js"></script>
	<script type="text/javascript">
		function readURL(input,destino) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#falseinput').attr('src', e.target.result);
					$('#base').val(e.target.result);
          document.getElementById(destino).value=e.target.result;
                    document.getElementById('bFichero').value="";
          //llenar();
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
   
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
        <input type="hidden" id="eCodRenta" name="eCodRenta" value="<?=$_GET['v1']?>">
        <input type="hidden" name="eAccion" id="eAccion">
        <input type="hidden" name="eCodEntidad" id="eCodEntidad" value="<?=$_SESSION['sessionAdmin']['eCodEntidad'];?>">
                            <div class="col-lg-12">
								<h2 class="title-1 m-b-25"><?=$_GET['v1'] ? 'Actualizar ' : '+ '?>Renta</h2>
                                <div class="card col-lg-12">
                                    
                                    <div class="card-body card-block">
                                        <!--campos-->
                                        <div class="form-group">
              
           </div>
           <div class="form-group">
              <label>Fecha de la Renta</label>
              <input type="text" class="form-control" name="fhFechaRenta" id="fhFechaRenta" placeholder="Fecha (dd-mm-YYYY HH:mm)" value="<?=($rPublicacion{'fhFechaRenta'} ? (date('d-m-Y H:i',strtotime($rPublicacion{'fhFechaRenta'}))) : '');?>" >
           </div>
           <div class="form-group">
              <label>Horas</label>
              <input type="text" class="form-control" name="eHoras" id="eHoras" placeholder="Horas" value="<?=($rPublicacion{'eHoras'})?>" >
           </div>
           <div class="form-group">
              <label>Nombre del Solicitante</label>
              <input type="text" class="form-control" name="tNombre" id="tNombre" placeholder="Nombre" value="<?=utf8_encode($rPublicacion{'tNombre'})?>" >
           </div>
           <div class="form-group">
              <label>E-mail del Solicitante</label>
              <input type="email" class="form-control" name="tCorreo" id="tCorreo" placeholder="E-mail" value="<?=($rPublicacion{'tCorreo'})?>" >
           </div>
            <div class="form-group">
              <label>Tel&eacute;fono del Solicitante</label>
              <input type="text" class="form-control" name="tTelefono" id="tTelefono" placeholder="Telefono" value="<?=($rPublicacion{'tTelefono'})?>" >
           </div>
           <div class="form-group">
              <label>Personas</label>
              <input type="text" class="form-control" name="ePersonas" id="ePersonas" placeholder="Personas" value="<?=($rPublicacion{'ePersonas'})?>" >
           </div>
           <div class="form-group">
              <label>Montaje</label>
              <select class="form-control" name="eCodMontaje" id="eCodMontaje">
                  <option value="">Seleccione...</option>
                  <? $select = "SELECT * FROM CatMontajes WHERE 1=1 ".($bAll ? "" : " AND eCodEntidad=".$_SESSION['sessionAdmin']['eCodEntidad']);
                    $rsMontajes = mysql_query($select);
                                    while($rMontaje = mysql_fetch_array($rsMontajes)) { ?>
                  <option value="<?=$rMontaje{'eCodMontaje'};?>" <?=(($rMontaje{'eCodMontaje'}==$rPublicacion{'eCodMontaje'}) ? 'selected="selected"' : '' )?>><?=utf8_encode($rMontaje{'tNombre'});?></option>
                  <? } ?>
               </select>
           </div>
           <div class="form-group">
              <label>Coffee Break</label>
              <select class="form-control" name="eCodMontaje" id="eCodMontaje">
                  <option value="">Seleccione...</option>
                  <? $select = "SELECT * FROM CatDescansos WHERE 1=1 ".($bAll ? "" : " AND eCodEntidad=".$_SESSION['sessionAdmin']['eCodEntidad']);
                    $rsDescansos = mysql_query($select);
                                    while($rDescanso = mysql_fetch_array($rsDescansos)) { ?>
                  <option value="<?=$rDescanso{'eCodDescanso'};?>" <?=(($rDescanso{'eCodDescanso'}==$rPublicacion{'eCodDescanso'}) ? 'selected="selected"' : '' )?>><?=utf8_encode($rDescanso{'tNombre'});?></option>
                  <? } ?>
               </select>
           </div>
                                        
           <div class="form-group">
              <label>Servicios</label><br>
              
               
                  <? $select = "SELECT * FROM CatServicios WHERE 1=1 ".($bAll ? "" : " AND eCodEntidad=".$_SESSION['sessionAdmin']['eCodEntidad']);
                    $rsServicios = mysql_query($select); $i=0;
                                    while($rServicio = mysql_fetch_array($rsServicios)) { ?>
               <? $eContador = mysql_num_rows(mysql_query("SELECT * FROM RelSolicitudesRentasServicios WHERE eCodServicio = ".$rServicio{'eCodServicio'}." AND eCodRenta =".$_GET['v1'])); ?>
                  <label><input onclick="calcular();" type="checkbox" id="servicios[<?=$i;?>][eCodServicio]" name="servicios[<?=$i;?>][eCodServicio]" value="<?=$rServicio{'eCodServicio'};?>" <?=(($eContador>0) ? 'checked' : '' )?>> <?=$rServicio{'tNombre'};?></label><input type="hidden" id="precios[<?=$i;?>][eCodServicio]" value="<?=$rServicio{'dPrecio'};?>"><br>
                  <? $i++; } ?>
               
           </div>
           
           <div class="form-group">
              <label>Total</label><br>
              <div class="form-control" id="total"></div>
           </div>
           
           
           
                                        <!--campos-->
                                    </div>
                                </div>
                            </div>
    </form>
    </div>
                        </div>
<script>
function calcular()
{
var dTotal = 0;
var cmbServicios = document.querySelectorAll("input[id^=servicios]");
cmbServicios.forEach(function(nodo){

    var tCampo = nodo.id.replace("servicios","precios");
    var dPrecio = document.getElementById(tCampo);
    if(nodo.checked==true)
    {dTotal = parseFloat(dTotal) + parseFloat(dPrecio.value);}
});

document.getElementById('total').innerHTML = "<b>"+(dTotal.toFixed(2))+"</b>";

}
</script>