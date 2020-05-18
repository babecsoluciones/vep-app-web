<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
//include("inc/fun-ini.php");

$clSistema = new clSis();
session_start();

$select = "	SELECT 
                                                          bc.*,
                                                          brc.eCodRegistro,
                                                          ce.tNombre tEmpresa,
                                                          cep.tNombre tEstatusPago,
                                                          cm.tNombre tModalidad,
                                                          su.tNombre tNombreUsuario,
                                                          su.tApellidos tApellidosUsuario,
                                                          brc.dMonto,
                                                          brc.bRequiereIVA,
                                                          su.tCorreo,
                                                          su.tRFC,
                                                          su.tRazonSocial,
                                                          su.tDomicilioFiscal
                                                    FROM
                                                        BitCursos bc
                                                        INNER JOIN BitRegistrosCursos brc ON brc.eCodCurso=bc.eCodCurso
                                                        INNER JOIN CatEntidades ce ON ce.eCodEntidad=bc.eCodEntidad
                                                        INNER JOIN CatEstatus cep ON cep.eCodEstatus=brc.eCodEstatusPago
                                                        INNER JOIN CatModalidades cm ON cm.eCodModalidad=brc.eCodModalidad
                                                        INNER JOIN SisUsuarios su ON su.eCodUsuario=brc.eCodUsuario
                                                    WHERE brc.eCodRegistro = ".$_GET['v1'].
                                                    (($_SESSION['sessionAdmin']['eCodPerfil']!=1 && $_SESSION['sessionAdmin']['eCodPerfil']!=4) ? " AND bc.eCodEntidad=".$_SESSION['sessionAdmin']['eCodEntidad'] : "");
//echo $select;
$rsPublicacion = mysql_query($select);
$rPublicacion = mysql_fetch_array($rsPublicacion);

?>

    
<div class="row">
    <div class="col-lg-12">
    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" id="eCodRegistro" name="eCodRegistro" value="<?=$_GET['v1']?>">
        <input type="hidden" name="eAccion" id="eAccion">
                            <div class="col-lg-12">
								<h2 class="title-1 m-b-25">Detalle del Registro</h2>
                                <div class="card col-lg-12">
                                    
                                    <div class="card-body card-block">
                                        <!--campos-->
                                        <div class="form-group">
              
           </div>
           <div class="form-group">
              <label>Fecha</label>
               <div class="form-control" name="fhFechaCurso" id="fhFechaCurso">  <?=date('d/m/Y H:i',strtotime($rPublicacion{'fhFechaCurso'}))?> </div>
           </div>
           <div class="form-group">
              <label>T&iacute;tulo</label>
              <div class="form-control" name="tTitulo" id="tTitulo">  <?=($rPublicacion{'tTitulo'})?> </div>
           </div>
           <div class="form-group">
              <label>Participante</label>
              <div class="form-control" name="tObjetivo" id="tObjetivo" ><?=ucwords($rPublicacion{'tNombreUsuario'}.' '.$rPublicacion{'tApellidosUsuario'})?></div>
           </div>
                                        
           <div class="form-group">
              <label>Modalidad</label>
              <div class="form-control" name="tModalidad" id="tModalidad" ><?=($rPublicacion{'tModalidad'})?></div>
           </div>
                                        
          <div class="form-group">
              <label>Monto a Pagar</label>
              <div class="form-control" name="tEstatusPago" id="tEstatusPago" ><b>$<?=number_format($rPublicacion{'dMonto'},2,'.',',')?></b> <?=(($rPublicacion{'bRequiereIVA'}) ? '(Facturar)' : '')?></div>
           </div>
                                        
           <? if($rPublicacion{'bRequiereIVA'}) { ?>
            <div class="form-group">
              <label>RFC</label>
              <div class="form-control" name="tRFC" id="tRFC" ><?=ucwords($rPublicacion{'tRFC'})?></div>
           </div>
           <div class="form-group">
              <label>Raz&oacute;n Social</label>
              <div class="form-control" name="tRazonSocial" id="tRazonSocial" ><?=ucwords($rPublicacion{'tRazonSocial'})?></div>
           </div>
           <div class="form-group">
              <label>Domicilio Fiscal</label>
              <div class="form-control" name="tDomicilioFiscal" id="tDomicilioFiscal" ><?=ucwords($rPublicacion{'tDomicilioFiscal'})?></div>
           </div>
            <? } ?>
                                        
           <div class="form-group">
              <label>M&eacute;todo de pago</label>
              <select class="form-control" id="eCodTipoPago" name="eCodTipoPago">
               <option value="">Seleccione...</option>
                  <?
                    $select = "SELECT * FROM CatTiposPagos";
                    $rsTiposPagos=mysql_query($select);
                    while($rTipoPago = mysql_fetch_array($rsTiposPagos))
                    {?><option value="<?=$rTipoPago{'eCodTipoPago'};?>"><?=$rTipoPago{'tNombre'};?></option><? } ?>
               </select>
           </div>
                                        
           <div class="form-group">
              <label>M&oacute;dulos</label>
              <table width="100%" id="tblModulos" cellspacing="1">
                  <tr>
                    <td>Nombre</td>
                    <td>Fecha</td>
                    <td>Horas</td>
                  </tr>
              <?
                $select = "SELECT crm.* FROM RelCursosModulos crm INNER JOIN RelRegistrosCursosModulos rrcm ON rrcm.eCodModulo=crm.eCodRegistro WHERE rrcm.eCodRegistro = ".$rPublicacion{'eCodRegistro'}." ORDER BY fhFechaModulo ASC";
          
                $rsModulos = mysql_query($select);
                $i=1;
                while($rModulo = mysql_fetch_array($rsModulos))
                {?> 
                <tr id="fila<?=$i;?>">
                    <td>
                        <?=$rModulo{'tNombre'};?>
                    </td>
                    <td>
                        <?=date('d/m/Y H:i',strtotime($rModulo{'fhFechaModulo'}));?>
                    </td>
                    <td>
                        <?=$rModulo{'eHoras'};?>
                    </td>
                  </tr>
                <? $i++; ?>
                <? } ?>
                 
               </table>
               <input type="hidden" name="eFilas" id="eFilas" value="<?=($i+1);?>">
           </div>

           
                                        <!--campos-->
                                    </div>
                                </div>
                            </div>
    </form>
    </div>
                        </div>

