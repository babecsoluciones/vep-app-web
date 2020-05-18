<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
//include("inc/fun-ini.php");

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

date_default_timezone_set('America/America/Mexico_City');
$hoy = date('Y-m-d H:i:s',strtotime("+12 hours"));

?>

<div class="row">
<!--widgets-->
    <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title-1 m-b-25"><?=($_SESSION['sessionAdmin']['eCodPerfil']==4 ? "Mis" : "&Uacute;ltimos")?> Registros a Cursos</h2>
                                <div class="custom-tab" style="background-color:rgb(229,229,229)">
                                     <table class="display" id="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Codigo</th>
                                                <th>Empresa</th>
                                                <th>Fecha</th>
                                                <? if($_SESSION['sessionAdmin']['eCodPerfil']!=4) { ?><th>Participante</th><? } ?>
                                                <th>Curso</th>
                                                <th>Estatus Pago</th>
                                                <th>Diploma</th>
                                            </tr>
                                        </thead>
                                        <?
                                        $select = "SELECT 
                                                          bc.*,
                                                          brc.eCodRegistro,
                                                          brc.eCodEstatusPago,
                                                          brc.eCodUsuario eCodUsuarioRegistro,
                                                          ce.tNombre tEmpresa,
                                                          cep.tNombre tEstatusPago,
                                                          brc.fhFechaRegistro,
                                                          su.tNombre tNombreUsuario,
                                                          su.tApellidos tApellidosUsuario
                                                    FROM
                                                        BitCursos bc
                                                        INNER JOIN BitRegistrosCursos brc ON brc.eCodCurso=bc.eCodCurso
                                                        INNER JOIN CatEntidades ce ON ce.eCodEntidad=bc.eCodEntidad
                                                        INNER JOIN CatEstatus cep ON cep.eCodEstatus=brc.eCodEstatusPago
                                                        INNER JOIN SisUsuarios su ON su.eCodUsuario=brc.eCodUsuario
                                                    WHERE 1=1 ".
                                            ($_SESSION['sessionAdmin']['eCodPerfil']==4 ? " AND brc.eCodUsuario =".$_SESSION['sessionAdmin']['eCodUsuario'] : "").
                                            ((!$bAll && $_SESSION['sessionAdmin']['eCodPerfil']!=4) ? " AND bc.eCodEntidad =".$_SESSION['sessionAdmin']['eCodEntidad'] : "").
                                                    " ORDER BY brc.eCodRegistro DESC LIMIT 0,10";
                                        $rsPedidos = mysql_query($select);
                                        ?>
                                        <tbody>
                                            <? while($rPedido = mysql_fetch_array($rsPedidos)) { ?>
                                            <tr>
                                                <td><? menuEmergente($rPedido{'eCodRegistro'}); ?></td>
                                                <td><?=ucwords(utf8_encode($rPedido{'tEmpresa'}));?></td>
                                                <td><?=date('d/m/Y H:i',strtotime($rPedido{'fhFechaRegistro'}));?></td>
                                                <? if($_SESSION['sessionAdmin']['eCodPerfil']!=4) { ?><td><?=ucwords($rPedido{'tNombreUsuario'}.' '.$rPedido{'tApellidosUsuario'});?></td><? } ?>
                                                <td><?=ucwords($rPedido{'tTitulo'});?></td>
                                                <td><?=ucwords($rPedido{'tEstatusPago'});?></td>
												<td>
													<? if($rPedido{'tArchivoDiploma'} && $rPedido{'eCodEstatusPago'}==9 && ($rPedido{'fhFechaCurso'}<=$hoy) && ($_SESSION['sessionAdmin']['eCodUsuario']==$rPedido{'eCodUsuarioRegistro'})) { ?>
                        							<a href="<?=obtenerURL();?>mod/dip/cata-dip-gen.php?v1=<?=$rPedido{'eCodRegistro'};?>" class="btn btn-info" target="_blank">Descargar Diploma</a>
                        							<? } else{ ?>
													<a href="#" class="btn btn-warning">No Disponible</a>
													<? } ?>
												</td>
                                            </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-4" style="display:none;">
                                <h2 class="title-1 m-b-25">&Uacute;ltimos Registros</h2>
                                <div class="au-card au-card--bg-blue au-card-top-countries m-b-40">
                                    <div class="au-card-inner">
                                        <div class="table-responsive">
                                            <table class="table table-top-countries">
                                                <tbody>
                                                    <?
                                                    $select = "SELECT * FROM CatClientes ORDER BY eCodCliente DESC LIMIT 0,10";
                                                    $rsClientes = mysql_query($select);
                                                    while($rCliente = mysql_fetch_array($rsClientes)) { ?>
                                                    <tr>
                                                        <td><?=ucwords($rCliente{'tNombres'}.' '.$rCliente{'tApellidos'});?></td>
                                                    </tr>
                                                    <? } ?>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--widgets-->

</div>