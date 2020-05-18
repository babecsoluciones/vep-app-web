<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");

$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$bValidar = (($_SESSION['sessionAdmin']['eCodPerfil']==3) ? true : false);

$tCampo = ($_SESSION['sessionAdmin']['eCodPerfil']==4 ? 'eCodPromotor' : 'eCodSupervisor');

$eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];

$select = "SELECT ct.eCodTienda, ct.tNombre FROM CatTiendas ct INNER JOIN RelPromotoriasPromotores rt ON rt.eCodTienda = ct.eCodTienda WHERE  1=1 ".
($bValidar ? " AND $tCampo = $eCodUsuario" : " rt.eCodPromotoria = ".($_SESSION['sesionPromotoria']['eCodPromotoria'] ? $_SESSION['sesionPromotoria']['eCodPromotoria'] : $_GET['v1']));
$rsTiendas = mysql_query($select);

$select = "SELECT * FROM CatTiposImagenes WHERE tCodEstatus = 'AC'";
$rsTiposImagenes = mysql_query($select);

?>



    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="eCodPromotoria" id="eCodPromotoria" value="<?=($_SESSION['sesionPromotoria']['eCodPromotoria'] ? $_SESSION['sesionPromotoria']['eCodPromotoria'] : $_GET['v1']);?>">
        <input type="hidden" name="eAccion" id="eAccion">
                            <div class="col-lg-12">
								
                                <div class="card col-lg-12">
                                    
                                    <div class="card-body card-block">
                                        <!--campos-->
                                        <div class="form-group">
              
           </div>
           <div class="form-group">
              <label>Tienda</label>
              <? if(!$_SESSION['sesionPromotoria']){ ?>
              <select id="eCodTienda" name="eCodTienda" class="form-control" onchange="consultarImagenes()">
                  <option value="">Seleccione...</option>
                  <? while($rTienda = mysql_fetch_array($rsTiendas)){ ?>
                  <option value="<?=$rTienda{'eCodTienda'};?>"><?=$rTienda{'tNombre'};?></option>
                  <? } ?>
              </select>
              <? }else{ ?>
              <input type="hidden" name="eCodTienda" id="eCodTienda" value="<?=$_SESSION['sesionPromotoria']['eCodTienda'];?>"><?=$_SESSION['sesionPromotoria']['tTienda'];?>
              <? } ?>
           </div>
           <div class="form-group">
              <label>Tipo Foto</label>
              <select id="eCodTipoImagen" name="eCodTipoImagen" class="form-control">
                  <option value="">Seleccione...</option>
                  <? while($rImagen = mysql_fetch_array($rsTiposImagenes)){ ?>
                  <option value="<?=$rImagen{'eCodTipoImagen'};?>"><?=$rImagen{'tNombre'};?></option>
                  <? } ?>
              </select>
           </div>
           <div class="form-group">
              <table width="100%" id="imagenes">
                  <tr id="img0">
                      <td>
                      <label for="tArchivo0" class="form-control btn btn-info">
                            <i class="fas fa-camera"></i> Tomar/subir Foto
                        </label>
                      <input type="file" id="tArchivo0" onchange="guardarImagen('0')" accept="image/*" capture="camera">
                      <input type="hidden" id="imgArchivo0" name="fotos[0][tArchivo]">
                      </td>
                  </tr>
              </table>
           </div>
            
                                        <!--campos-->
                                    </div>
                                </div>
                            </div>
    </form>
   
<script>
    
    //autocompletes
   function guardarImagen(indice) {
  var preview = document.getElementById('imgArchivo'+indice);
  var file    = document.getElementById('tArchivo'+indice).files[0];
  var reader  = new FileReader();

  reader.onloadend = function () {
    preview.value = reader.result;
      indice++;
      agregarFilaArchivo(indice);
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.value = "";
  }
}
    
    
    //validaciones
 
function agregarFilaArchivo(indice)
    {
        var x = document.getElementById("imagenes").rows.length;
        
        
        var eCodProducto = document.getElementById('imgArchivo'+indice);
        if(eCodProducto)
            {}
        else
        {
           
    var table = document.getElementById("imagenes");
    var row = table.insertRow(x);
    row.id="img"+(indice);
    row.innerHTML = '<label for="tArchivo'+indice+'" class="form-control btn btn-info"><i class="fas fa-camera"></i> Tomar/subir Foto</label><input type="file" id="tArchivo'+indice+'" onchange="guardarImagen(\''+indice+'\')" accept="image/*" capture="camera"><input type="hidden" id="imgArchivo'+indice+'" name="fotos['+indice+'][tArchivo]">';
        }
        
    }
 
   
    $(document).ready(function() {
              $('#fhFechaPromotoria').datepicker({
                  locale:'es',
                  dateFormat: "dd/mm/yy"
              });
          });
    
   
    

		</script>