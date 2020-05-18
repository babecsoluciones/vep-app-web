<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
$clSistema = new clSis();
session_start();
$select = "SELECT * FROM CatTiendas WHERE eCodTienda = ".$_GET['v1'];
$rsCliente = mysql_query($select);
$rCliente = mysql_fetch_array($rsCliente);
?>
<div class="row">
                            <div class="col-lg-12">
                                
                                <div class="col-md-6">
                                <div class="form-group">
                                    
                                    <label>Nombre</label>
              <?=$rCliente{'tNombre'}?>
                                    </div>
                                    </div>
                                    
                                <div class="col-md-12">
                                <div class="form-group">
                                    <label>Direcci&oacute;n</label>
              <?=$rCliente{'tDireccion'}?>
                                </div>
                                    </div>
                            </div>
    
    
                            
                        </div>