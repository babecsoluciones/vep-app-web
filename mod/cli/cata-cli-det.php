<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
$clSistema = new clSis();
session_start();
$select = "SELECT * FROM CatClientes WHERE eCodCliente = ".$_GET['v1'];
$rsCliente = mysql_query($select);
$rCliente = mysql_fetch_array($rsCliente);
?>
<div class="row">
                            <div class="col-lg-12">
                                
                                <div class="col-md-12">
                                <div class="form-group">
                                    
                                    <label>Nombre(s)</label>
              <?=$rCliente{'tNombres'}?>
                                    </div>
                                    </div>
                                    
                                <div class="col-md-12">
                                <div class="form-group">
                                    <label>E-mail</label>
              <?=$rCliente{'tCorreo'}?>
                                </div>
                                    </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    
                                    <label>Teléfono Fijo</label>
              <?=$rCliente{'tTelefonoFijo'}?>
                                    </div>
                                </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                    <label>Teléfono Móvil</label>
              <?=$rCliente{'tTelefonoMovil'}?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                <div class="form-group">
                                    <label>Comentarios</label>
              <?=$rCliente{'tComentarios'}?>
                                </div>
                                    </div>
                            </div>
    
    
                        </div>