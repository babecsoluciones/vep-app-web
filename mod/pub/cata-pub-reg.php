<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");


$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$select = "SELECT bp.* FROM BitPublicaciones bp WHERE bp.eCodPublicacion = ".$_GET['v1'];
$rsPublicacion = mysql_query($select);
$rPublicacion = mysql_fetch_array($rsPublicacion);

$select = "SELECT * FROM CatTiposPublicaciones WHERE tCodEstatus = 'AC'";
$rsTipos = mysql_query($select);

?>



	<script type="text/javascript">
		function readURL(input,destino) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#falseinput').attr('src', e.target.result);
					$('#base').val(e.target.result);
          document.getElementById(destino).value=e.target.result;
          //llenar();
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
        
        function guardarImagen() {
  var preview = document.getElementById('imgArchivo');
  var file    = document.getElementById('tArchivo').files[0];
  var reader  = new FileReader();

  reader.onloadend = function () {
    preview.value = reader.result;

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.value = "";
  }
}
}
</script>
    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="eCodPublicacion" id="eCodPublicacion" value="<?=$_GET['v1']?>">
        <input type="hidden" name="nvaFecha" id="nvaFecha">
        <input type="hidden" name="eAccion" id="eAccion">
       <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <!--campos-->
                        <div class="position-relative form-group">
                  <label>Tipo</label>
                  <select class="form-control" id="eCodTipoPublicacion" name="eCodTipoPublicacion">
                      <option value="">Seleccione...</option>
                      <? while($rTipo = mysql_fetch_array($rsTipos)){ ?>
                      <option value="<?=$rTipo{'eCodTipoPublicacion'};?>" <?=(($rTipo{'eCodTipoPublicacion'}==$rPublicacion{'eCodTipoPublicacion'}) ? 'selected="selected"' : '')?>><?=$rTipo{'tNombre'};?></option>
                      <? } ?>
                  </select>
               </div>
           
           <div class="position-relative form-group">    
              <label><input type="checkbox" id="bRequiereProceso" name="bRequiereProceso" value="1" <?=(($rPublicacion{'bRequiereProceso'}>0) ? 'checked="checked"' : '')?>> Permite generar solicitud?</label>
            </div>
            
           <div class="position-relative form-group">    
              <label>T&iacute;tulo</label>
              <input type="text" class="form-control" name="tTitulo" id="tTitulo" value="<?=$rPublicacion{'tTitulo'} ? base64_decode($rPublicacion{'tTitulo'}) : ""?>" placeholder="Título">
            </div>
                                        
           <div class="position-relative form-group">    
              <label>Contenido</label>
               <textarea class="form-control" name="tContenido" id="tContenido"  placeholder="Contenido" rows="10" style="resize:none;"><?=$rPublicacion{'tContenido'} ? base64_decode($rPublicacion{'tContenido'}) : ""?></textarea>
            </div>
                       
            <div class="position-relative form-group">    
              <label>Imagen</label>
                      <input type="file" id="tArchivo" onchange="readURL(this,'imgArchivo')" accept="image/*">
                      <input type="hidden" id="imgArchivo" name="imgArchivo">
                      <? if($rPublicacion{'tImagen'}){ ?>
                      <img src="/cni/<?=$rPublicacion{'tImagen'};?>" class="img-responsive">
                      <input ytpe="hidden" name="tImagen" id="tImagen" value="<?=$rPublicacion{'tImagen'};?>">
                      <input type="hidden" name="bImagen" id="bImagen" value="1">
                      <? } ?>
            </div>
                        <!--campos-->
                    </div>
                </div>
            </div>
        </div>
    </form>
   
<script>
    
    //autocompletes
   
   
    

		</script>