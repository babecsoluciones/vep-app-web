<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
$clSistema = new clSis();
session_start();


    $select = "SELECT * FROM SisUsuarios WHERE eCodUsuario = ".$_SESSION['sessionAdmin']['eCodUsuario'];

$rsUsuario = mysql_query($select);
$rUsuario = mysql_fetch_array($rsUsuario);

?>

<script>
function validar()
    {
        guardar();
    }
</script>
<div class="row">
    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" class="col-lg-6">
        <input type="hidden" name="eCodUsuario" value="<?=$_GET['v1']?>">
        <input type="hidden" name="eAccion" id="eAccion">
                            <div class="col-lg-12">
								<h2 class="title-1 m-b-25">Mi Perfil</h2>
                                <div class="card">
                                    
                                    <div class="col-lg 6 card-body card-block">
                                        <?
                                        if($_SESSION['sessionAdmin']['bAll'])
                                        {
                                        ?>
                                        <div class="form-group">
                                            <label for="company" class=" form-control-label">Administrador?</label>
                                            <input type="checkbox" name="bAll" <?=($rUsuario{'bAll'} ? "checked" : "")?> value="1">
                                        </div>
                                        <?
                                        }
                                            ?>
                                        <div class="form-group">
                                            <label for="company" class=" form-control-label">Correo electr&oacute;nico</label>
                                            <input type="text" name="tCorreo" placeholder="Correo electrónico" value="<?=$rUsuario{'tCorreo'}?>" class="form-control"<?=$_GET['v1'] ? 'readonly' : ''?>>
                                        </div>
                                        <div class="form-group">
                                            <label for="vat" class=" form-control-label">Password Acceso</label>
                                            <input type="password" name="tPasswordAcceso" placeholder="Password Acceso" value="<?=base64_decode($rUsuario{'tPasswordAcceso'})?>" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="street" class=" form-control-label">Password Operaciones</label>
                                            <input type="password" name="tPasswordOperaciones" placeholder="Password Operaciones" value="<?=base64_decode($rUsuario{'tPasswordOperaciones'})?>" class="form-control">
                                        </div>
                                        <div class="form-group" <?=($_SESSION['sessionAdmin']['bAll']==1) ? '' : 'style="display:none;"';?>>
                                            <label for="street" class=" form-control-label">Empresa</label>
                                            <select class="form-control" id="eCodEntidad" name="eCodEntidad">
                                                <option value="">Seleccione...</option>
                                                <?
                                                $select = "SELECT * FROM CatEntidades ORDER BY tNombre ASC";
                                                   $rsEntidades = mysql_query($select);
                                                   while($rEntidad = mysql_fetch_array($rsEntidades))
                                                   { ?> <option value="<?=($rEntidad{'eCodEntidad'});?>" <?=($rEntidad{'eCodEntidad'}==$rUsuario{'eCodEntidad'} ? 'selected="selected"' : '');?>><?=utf8_encode($rEntidad{'tNombre'});?></option><? } ?>
                                            </select>
                                        </div>
                                        
                                                <div class="form-group">
                                                    <label for="city" class=" form-control-label">T&iacute;tulo(s)</label>
                                                    <input type="text" name="tTitulo" placeholder="Lic. Ing, C.P.C. etc" value="<?=utf8_decode($rUsuario{'tTitulo'})?>" class="form-control">
                                                </div>
                                        
                                                <div class="form-group">
                                                    <label for="city" class=" form-control-label">Nombre(s)</label>
                                                    <input type="text" name="tNombre" placeholder="Nombre(s)" value="<?=utf8_decode($rUsuario{'tNombre'})?>" class="form-control" <?=$_GET['v1'] ? 'readonly' : ''?>>
                                                </div>
                                        
                                                <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Apellido(s)</label>
                                                    <input type="text" name="tApellidos" placeholder="Apellido(s)" value="<?=utf8_decode($rUsuario{'tApellidos'})?>" class="form-control"<?=$_GET['v1'] ? 'readonly' : ''?>>
                                                </div>
                                        
                                                <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Tel. Fijo</label>
                                                    <input type="text" name="tTelefonoFijo" placeholder="Tel. Fijo" value="<?=utf8_decode($rUsuario{'tTelefonoFijo'})?>" class="form-control"<?=$_GET['v1'] ? 'readonly' : ''?>>
                                                </div>
                                        
                                                <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Tel. Movil</label>
                                                    <input type="text" name="tTelefonoMovil" placeholder="Tel. Movil" value="<?=utf8_decode($rUsuario{'tTelefonoMovil'})?>" class="form-control"<?=$_GET['v1'] ? 'readonly' : ''?>>
                                                </div>
                                        
                                                <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Estado</label>
                                                    <input type="text" name="tEstado" placeholder="Oaxaca, CDMX, Etc." value="<?=utf8_decode($rUsuario{'tEstado'})?>" class="form-control"<?=$_GET['v1'] ? 'readonly' : ''?>>
                                                </div>
                                        
                                        <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">RFC</label>
                                                    <input type="text" name="tRFC" placeholder="RFC" value="<?=utf8_decode($rUsuario{'tRFC'})?>" class="form-control">
                                                </div>
                                        
                                        <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Raz&oacute;n Social</label>
                                                    <input type="text" name="tRazonSocial" placeholder="tRazonSocial" value="<?=utf8_decode($rUsuario{'tRazonSocial'})?>" class="form-control">
                                                </div>
                                        
                                        <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Domicilio Fiscal</label>
                                                    <input type="text" name="tDomicilioFicsal" placeholder="Domicilio Fiscal" value="<?=utf8_decode($rUsuario{'tDomicilioFicsal'})?>" class="form-control">
                                                </div>
                                            
                                        <div class="form-group" <?=($_SESSION['sessionAdmin']['bAll']==1) ? '' : 'style="display:none;"';?>>
                                            <label for="country" class=" form-control-label">Perfil</label>
											<select id="eCodPerfil" name="eCodPerfil" class="form-control col-md-6">
											<option value="">Seleccione</option>
												<?
												$select = "SELECT * FROM SisPerfiles".
															($_SESSION['sessionAdmin']['bAll'] ? "" : " WHERE eCodPerfil > 1").
															" ORDER BY eCodPerfil ASC";
												$rsPerfiles = mysql_query($select);
												while($rPerfil = mysql_fetch_array($rsPerfiles))
												{
													?>
												<option value="<?=$rPerfil{'eCodPerfil'}?>" <?=($rUsuario{'eCodPerfil'}==$rPerfil{'eCodPerfil'}) ? 'selected="selected"': '' ?>><?=$rPerfil{'tNombre'}?></option>
												<?
												}
												?>
											</select>
                                        </div>
                                    </div>
                                </div>
                            </div>
    </form>
                        </div>