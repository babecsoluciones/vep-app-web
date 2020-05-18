<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");


$clSistema = new clSis();
session_start();
$bAll = $_SESSION['bAll'];
?>

<div class="row">
                            <div class="col-lg-12">
                                
                                
                                    <table class="display" id="table" width="100%">
                                        <thead>
                                            <tr>
                                                 <th></th>
												 <th align="center">E</th>
                                                 <th>Nombre</th>
                                                 <th>Correo</th>
                                                 <th>Tel&eacute;fono</th>
                                            </tr>
                                        </thead>
                                        <tbody>
											<?
											$select = "	SELECT 
															cc.*, 
															ce.tCodEstatus as estatus,
															cp.tNombre as perfil
														FROM
															SisUsuarios cc
														INNER JOIN CatEstatus ce ON cc.eCodEstatus = ce.eCodEstatus 
														INNER JOIN SisPerfiles cp ON cp.eCodPerfil = cc.eCodPerfil".
										              " WHERE cp.eCodPerfil = 4 ".
													  " ORDER BY cc.eCodUsuario ASC";
											
											$rsPublicaciones = mysql_query($select);
											while($rPublicacion = mysql_fetch_array($rsPublicaciones))
											{
												?>
											<tr>
                                                <td><? menuEmergente($rPublicacion{'eCodUsuario'}); ?></td>
                                                <td><?=utf8_decode($rPublicacion{'estatus'})?></td>
                                                <td><?=utf8_decode($rPublicacion{'tNombre'}.' '.$rPublicacion{'tApellidos'})?></td>
                                                <td><?=utf8_decode($rPublicacion{'tCorreo'})?></td>
                                                <td><?=utf8_decode($rPublicacion{'tTelefono'})?></td>
                                            </tr>
											<?
											}
											?>
                                        </tbody>
                                    </table>
                                
                            </div>
                            
                        </div>