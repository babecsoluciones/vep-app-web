<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");


$clSistema = new clSis();
session_start();
$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$select = "SELECT bp.*, cc.tNombres FROM BitPromotoria bp INNER JOIN CatClientes cc ON cc.eCodCliente=bp.eCodCliente WHERE bp.eCodPromotoria = ".($_SESSION['sesionPromotoria']['eCodPromotoria'] ? $_SESSION['sesionPromotoria']['eCodPromotoria'] : $_GET['v1']);
$rsPromotoria = mysql_query($select);
$rPromotoria = mysql_fetch_array($rsPromotoria);

$select = "SELECT ct.eCodTienda, ct.tNombre FROM CatTiendas ct INNER JOIN RelPromotoriasTiendas rt ON rt.eCodTienda = ct.eCodTienda WHERE rt.eCodPromotoria = ".$rPromotoria{'eCodPromotoria'};
$rsTiendas = mysql_query($select);

$select = "SELECT DISTINCT cp.eCodProducto, cp.tNombre tProducto FROM RelPromotoriasPresentaciones rp INNER JOIN CatProductos cp ON cp.eCodProducto=rp.eCodProducto WHERE rp.eCodPromotoria = ".$rPromotoria{'eCodPromotoria'}." ORDER BY cp.eCodProducto ASC";
$rsProductos = mysql_query($select);

$select = "SELECT
    rs.eCodPromotoria,
	su.eCodUsuario,
	su.tNombre,
	su.tApellidos 
FROM
	SisUsuarios su
	INNER JOIN RelPromotoriasSupervisores rs ON rs.eCodSupervisor= su.eCodUsuario
WHERE
	rs.eCodPromotoria =".$rPromotoria{'eCodPromotoria'}.
($_SESSION['sessionAdmin']['ecodPerfil']==3 ? " AND rs.eCodSupervisor = ".$_SESSION['sessionAdmin']['eCodUsuario'] : "");
$rsSupervisores = mysql_query($select);



$select = "SELECT su.eCodUsuario, su.tNombre, su.tApellidos FROM SisUsuarios su INNER JOIN RelPromotoriasClientes rs ON rs.eCodUsuario=su.eCodUsuario WHERE rs.eCodPromotoria = ".$rPromotoria{'eCodPromotoria'};
$rsClientes = mysql_query($select);

?>

<div class="row">
    <div class="col-lg-12">
      <div class="card card-body">
          <table class="table table-striped table-bordered">
              <tr>
                  <td>Empresa</td>
                  <td><?=utf8_encode($rPromotoria{'tNombres'});?></td>
                  <td>Fecha</td>
                  <td><?=date('d/m/Y',strtotime($rPromotoria{'fhFechaPromotoria'}));?></td>
              </tr>
              <tr>
                  <td>Coordinadores</td>
                  <td colspan="3"></td>
              </tr>
              <? while($rCliente = mysql_fetch_array($rsClientes)){ ?>
              <tr>
                  <td></td>
                  <td colspan="3"><?=utf8_encode($rCliente{'tNombre'}.($rCliente{'tApellidos'} ? ' '.$rCliente{'tApellidos'} : ''));?></td>
              </tr>
              <? } ?>
              <tr>
                  <td colspan="4" align="center"><b>Productos</b></td>
              </tr>
              <tr>
                  <td colspan="2">Producto</td>
                  <td colspan="2">Presentación</td>
              </tr>
              <? while($rProducto = mysql_fetch_array($rsProductos)){ ?>
                  <tr>
                      <td colspan="2"><?=utf8_encode($rProducto{'tProducto'});?></td>
                      <td colspan="2"></td>
                  </tr>
                  <?
                  $select = "SELECT DISTINCT ct.eCodPresentacion, ct.tNombre tPresentacion FROM RelPromotoriasPresentaciones rp 
                   INNER JOIN CatPresentaciones ct ON ct.eCodPresentacion=rp.eCodPresentacion WHERE rp.eCodPromotoria = ".$rPromotoria{'eCodPromotoria'}." AND rp.eCodProducto = ".$rProducto{'eCodProducto'}." ORDER BY ct.eCodPresentacion ASC";
                   $rsPresentaciones = mysql_query($select);
                   while($rPresentacion = mysql_fetch_array($rsPresentaciones)){ ?>
                    <tr>
                     <td></td>
                      <td><?=utf8_encode($rPresentacion{'tPresentacion'});?></td>
                      <td colspan="2"></td>
                  </tr>   
                   <? } ?>
              <? } ?>
              <tr>
                  <td colspan="4" align="center"><b>Supervisor(es)</b></td>
              </tr>
              <tr>
                  <td>Supervisor</td>
                  <td>Demovendedor</td>
                  <td colspan="2">Tienda</td>
              </tr>
              <? while($rSupervisor = mysql_fetch_array($rsSupervisores)){ ?>
                  <tr>
                      <td><?=utf8_encode($rSupervisor{'tNombre'}.' '.$rSupervisor{'tApellidos'});?></td>
                      <td colspan="3"></td>
                  </tr>
                  <?
                  $select = "SELECT su.eCodUsuario, su.tNombre, su.tApellidos,
	               ct.eCodTienda, ct.tNombre tTienda 
                   FROM SisUsuarios su 
                   INNER JOIN RelPromotoriasPromotores rs ON rs.eCodPromotor=su.eCodUsuario 
                   INNER JOIN CatTiendas ct ON ct.eCodTienda = rs.eCodTienda WHERE rs.eCodPromotoria = ".$rSupervisor{'eCodPromotoria'}.
                    " AND rs.eCodSupervisor = ".$rSupervisor{'eCodUsuario'};
                    $rsPromotores = mysql_query($select);
                    while($rPromotor = mysql_fetch_array($rsPromotores)){ ?>
                     <tr>
                         <td></td>
                         <td><?=utf8_encode($rPromotor{'tNombre'}.' '.$rPromotor{'tApellidos'});?></td>
                         <td colspan="2"><?=utf8_encode($rPromotor{'tTienda'});?></td>
                     </tr>
                    <? } ?>
              <? } ?>
          </table>
      </div>  
    </div>
</div>