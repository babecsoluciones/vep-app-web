<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
$clSistema = new clSis();
session_start();
$bAll = $_SESSION['bAll'];

$select = "SELECT su.*, cc.tNombres tNombreCliente, cc.tApellidos tApellidoCliente FROM SisUsuarios su LEFT JOIN CatClientes cc ON cc.eCodCliente=su.eCodCliente WHERE su.eCodUsuario = ".$_GET['v1'];
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
    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post">
        <input type="hidden" name="eCodUsuario" value="<?=$_GET['v1']?>">
        <input type="hidden" name="eAccion" id="eAccion">
                            <div class="col-lg-12">
								
                                <div class="card">
                                    
                                    <div class="card-body card-block">
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
                                            <label for="company" class=" form-control-label">Estatus</label>
                                            <select class="form-control" id="eCodEstatus" name="eCodEstatus">
                                            <option value="">Seleccione...</option>
                                                <? 
                                                $select = "SELECT * FROM CatEstatus WHERE eCodEstatus IN(1,3,7)"; 
                                                $rsEstatus = mysql_query($select);
                                                while($rEstatus = mysql_fetch_array($rsEstatus)){ ?>
                                                <option value="<?=$rEstatus{'eCodEstatus'};?>" <?=(($rUsuario{'eCodEstatus'}==$rEstatus{'eCodEstatus'}) ? 'selected' : '')?>><?=$rEstatus{'tNombre'};?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="company" class=" form-control-label">Correo electr&oacute;nico</label>
                                            <input type="text" name="tCorreo" placeholder="Correo electrónico" value="<?=$rUsuario{'tCorreo'}?>" class="form-control"<?=$_GET['eCodUsuario'] ? 'readonly' : ''?>>
                                        </div>
                                        <div class="form-group">
                                            <label for="vat" class=" form-control-label">Password Acceso</label>
                                            <input type="password" name="tPasswordAcceso" placeholder="Password Acceso" value="<?=base64_decode($rUsuario{'tPasswordAcceso'})?>" class="form-control">
                                        </div>
                                        <div class="form-group" style="display:none;">
                                            <label for="street" class=" form-control-label">Password Operaciones</label>
                                            <input type="password" name="tPasswordOperaciones" placeholder="Password Operaciones" value="<?=base64_decode($rUsuario{'tPasswordOperaciones'})?>" class="form-control">
                                        </div>
                                        
                                                <div class="form-group">
                                                    <label for="city" class=" form-control-label">Nombre(s)</label>
                                                    <input type="text" name="tNombre" placeholder="Nombre(s)" value="<?=utf8_decode($rUsuario{'tNombre'})?>" class="form-control" <?=$_GET['eCodUsuario'] ? 'readonly' : ''?>>
                                                </div>
                                            
                                                <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Apellido(s)</label>
                                                    <input type="text" name="tApellidos" placeholder="Apellido(s)" value="<?=utf8_decode($rUsuario{'tApellidos'})?>" class="form-control"<?=$_GET['eCodUsuario'] ? 'readonly' : ''?>>
                                                </div>
                                          
                                            <div class="form-group">
              <label> Cliente</label> 
               <input type="hidden" name="eCodCliente" id="eCodCliente" value="<?=$rUsuario{'eCodCliente'};?>"> 
               <input type="text" class="form-control" id="tCliente"  value="<?=(($rUsuario{'eCodCliente'}) ? $rUsuario{'tNombreCliente'} . ' '.$rUsuario{'tApellidoCliente'} : '');?>" placeholder="Cliente" onkeyup="buscarClientes()" onkeypress="buscarClientes()"> 
               <small>Buscar y seleccionar el cliente de la lista</small>
               </div>
                                                
                                        <div class="form-group">
                                            <label for="country" class=" form-control-label">Perfil</label>
											<select id="eCodPerfil" name="eCodPerfil" class="form-control col-md-6">
											<option value="">Seleccione</option>
												<?
												$select = "SELECT * FROM SisPerfiles".
															($_SESSION['sessionAdmin']['bAll'] ? "" : " WHERE eCodPerfil > 2").
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