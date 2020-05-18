<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");

function supervisores($indice,$datos)
{
    ?>
    <table width="100%" id="supervisores<?=$indice;?>">
    <tr>
      <td><i class="far fa-trash-alt" onclick="deleteRow('sup<?=$indice;?>','supervisores')"></i></td>
    
      <td>
 
    <input type="hidden" id="eCodSupervisor<?=$indice;?>" name="supervisores[<?=$indice;?>][eCodSupervisor]" value="<?=$datos['eCodUsuario'];?>">
    <input type="text" class="form-control" id="tSupervisor<?=$indice;?>" name="supervisores[<?=$indice;?>][tSupervisor]" onkeyup="agregarSupervisor(<?=$indice;?>)" onkeypress="agregarSupervisor(<?=$indice;?>)" onblur="validarSupervisor(<?=$indice;?>)" placeholder="Supervisor" value="<?=($datos['tNombre'] ? ($datos['tNombre'].' '.$datos['tApellidos']) : '');?>" autocomplete="off">

     </td>
        </tr>
        <tr>
        <td></td>
      <td>
        <table width="100%" id="promotores<?=$indice;?>">
    <?
    //
    $select = "SELECT su.eCodUsuario, su.tNombre, su.tApellidos,
	ct.eCodTienda,
	ct.tNombre tTienda FROM SisUsuarios su INNER JOIN RelPromotoriasPromotores rs ON rs.eCodPromotor=su.eCodUsuario INNER JOIN CatTiendas ct ON ct.eCodTienda = rs.eCodTienda WHERE rs.eCodPromotoria = ".$datos{'eCodPromotoria'}.
    " AND rs.eCodSupervisor = ".$datos{'eCodUsuario'};
    print $select;
    $rsPromotores = mysql_query($select);
    $j = 0;
    while($rPromotor = mysql_fetch_array($rsPromotores)){
     promotores($indice,$j,$rPromotor);
        $j++;
    }
    promotores($indice,$j);
    //
    ?>
        </table>
      </td>
    </tr>
    </table>
    <?
}

function promotores($indice,$fila,$datos)
{
    ?>
        <tr id="pro<?=$indice;?>-<?=$fila;?>">
          <td><i class="far fa-trash-alt" onclick="deleteRow('pro<?=$indice;?>-<?=$fila;?>','promotores<?=$indice;?>')"></i></td>
          <td>

    <input type="hidden" id="eCodPromotor<?=$indice;?>-<?=$fila;?>" name="promotores[<?=$indice;?>][<?=$fila;?>][eCodPromotor]" value="<?=$datos['eCodUsuario'];?>">
    <input type="text" class="form-control" id="tPromotor<?=$indice;?>-<?=$fila;?>" name="promotores[<?=$indice;?>][<?=$fila;?>][tPromotor]" onkeyup="agregarPromotor(<?=$indice;?>,<?=$fila;?>)" onkeypress="agregarPromotor(<?=$indice;?>,<?=$fila;?>)" onblur="validarPromotor(<?=$indice;?>,<?=$fila;?>)" placeholder="Demovendedor" value="<?=($datos['tNombre'] ? ($datos['tNombre'].' '.$datos['tApellidos']) : '');?>" autocomplete="off">

          </td>
          <td>

    <input type="hidden" id="eCodTienda<?=$indice;?>-<?=$fila;?>" name="promotores[<?=$indice;?>][<?=$fila;?>][eCodTienda]" value="'.$datos['eCodTienda'].'">
    <input type="text" class="form-control" id="tTienda<?=$indice;?>-<?=$fila;?>" name="promotores[<?=$indice;?>][<?=$fila;?>][tTienda]" onkeyup="agregarTienda(<?=$indice;?>,<?=$fila;?>)" onkeypress="agregarTienda(<?=$indice;?>,<?=$fila;?>)" onblur="validarPromotor(<?=$indice;?>,<?=$fila;?>)" placeholder="Tienda" value="<?=$datos['tTienda'];?>" autocomplete="off">
  
          </td>
        </tr>
    <?
}


$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$select = "SELECT bp.*, cc.tNombres FROM BitPromotoria bp INNER JOIN CatClientes cc ON cc.eCodCliente=bp.eCodCliente WHERE bp.eCodPromotoria = ".$_GET['v1'];
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
	rs.eCodPromotoria =".$rPromotoria{'eCodPromotoria'};
$rsSupervisores = mysql_query($select);



$select = "SELECT su.eCodUsuario, su.tNombre, su.tApellidos FROM SisUsuarios su INNER JOIN RelPromotoriasClientes rs ON rs.eCodUsuario=su.eCodUsuario WHERE rs.eCodPromotoria = ".$rPromotoria{'eCodPromotoria'};
$rsClientes = mysql_query($select);


?>



    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="eCodPromotoria" id="eCodPromotoria" value="<?=$_GET['v1']?>">
        <input type="hidden" name="nvaFecha" id="nvaFecha">
        <input type="hidden" name="eAccion" id="eAccion">
        <!--tabs-->
        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <span>Datos Principales</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <span>Productos</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
            <span>Usuarios</span>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Datos Principales</h5>
                        <!--campos-->
                        <div class="position-relative form-group">
              <label> Cliente</label> 
               <input type="hidden" name="eCodCliente" id="eCodCliente" value="<?=$rPromotoria{'eCodCliente'};?>"> 
               <input type="text" class="form-control" id="tCliente" <?=(($_GET['v1']) ? 'readonly="readonly"' : '' )?> value="<?=(($rPromotoria{'eCodCliente'}) ? $rPromotoria{'tNombres'} : '');?>" placeholder="Cliente" onkeyup="buscarClientes()" onkeypress="buscarClientes()"> 
               <small>Buscar y seleccionar el cliente de la lista</small>
               </div>
           
           
            
           <div class="position-relative form-group">    
              <label>Fecha de la Promotor&iacute;a</label>
              <input type="text" class="form-control" name="fhFechaPromotoria" id="fhFechaPromotoria" value="<?=$rPromotoria{'fhFechaPromotoria'} ? date('d/m/Y',strtotime($rPromotoria{'fhFechaPromotoria'})) : ""?>" >
            </div>
                                        
           <div class="position-relative form-group" style="display:none;">
              <label>Tiendas</label>
              <table class="table table-hover" id="tiendas" width="100%">
                   <?
                    $i = 0;
                     while($rTienda = mysql_fetch_array($rsTiendas)){ ?>
                     <tr id="tie<?=$i;?>">
                        <td><i class="far fa-trash-alt" onclick="deleteRow('tie<?=$i?>','tiendas')"></i></td>
                        <td>
                            <input type="hidden" id="eCodTienda<?=$i;?>" name="tiendas[<?=$i;?>][eCodTienda]" value="<?=$rTienda{'eCodTienda'};?>">
                            <input type="text" class="form-control" id="tTienda<?=$i;?>" name="tiendas[<?=$i;?>][tTienda]" onkeyup="agregarTienda(<?=$i;?>)" onkeypress="agregarTienda(<?=$i;?>)" onblur="validarTienda(<?=$i;?>)" value="<?=$rTienda{'tNombre'};?>">
                        </td>
                    </tr>
                     <? $i++; ?>
                    <? } ?>
                    <tr id="tie<?=$i;?>">
                        <td><i class="far fa-trash-alt" onclick="deleteRow('tie<?=$i?>','tiendas')"></i></td>
                        <td>
                            <input type="hidden" id="eCodTienda<?=$i;?>" name="tiendas[<?=$i;?>][eCodTienda]">
                            <input type="text" class="form-control" id="tTienda<?=$i;?>" name="tiendas[<?=$i;?>][tTienda]" onkeyup="agregarTienda(<?=$i;?>)" onkeypress="agregarTienda(<?=$i;?>)" onblur="validarTienda(<?=$i;?>)">
                        </td>
                    </tr>
              </table>
           </div>
                        <!--campos-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Productos</h5>
                        <div class="table-responsive">
                           <table class="table table-hover" id="productos" width="100%">
                                       <? $i = 0; ?>
                                       <? $j = 0; ?>
                                       <? $productos = false; ?>
                                       <? while($rProducto = mysql_fetch_array($rsProductos)){ ?>
                                       <? $j = 0; ?>
                                       <tr id="prd<?=$i;?>">
                        <td width="100%">
                        <table width="100%">
                        <tr>
                        <td>
                        <i style="margin-top:0px;" class="far fa-trash-alt" onclick="deleteRow('prd<?=$i?>','productos')"></i></td>
                        <td>
                            <input type="hidden" id="eCodProducto<?=$i;?>" name="productos[<?=$i;?>][eCodProducto]" value="<?=$rProducto{'eCodProducto'};?>">
                            <input type="text" class="form-control" id="tProducto<?=$i;?>" name="productos[<?=$i;?>][tProducto]" onkeyup="agregarProducto('<?=$i;?>')" onkeypress="agregarProducto('<?=$i;?>')" onblur="validarProducto('<?=$i;?>')" placeholder="Producto" value="<?=$rProducto{'tProducto'};?>">
                            </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                            <table id="presentaciones<?=$i;?>">
                            <?
                                $select = "SELECT DISTINCT ct.eCodPresentacion, ct.tNombre tPresentacion FROM RelPromotoriasPresentaciones rp 
                                INNER JOIN CatPresentaciones ct ON ct.eCodPresentacion=rp.eCodPresentacion WHERE rp.eCodPromotoria = ".$rPromotoria{'eCodPromotoria'}." AND rp.eCodProducto = ".$rProducto{'eCodProducto'}." ORDER BY ct.eCodPresentacion ASC";
                                $rsPresentaciones = mysql_query($select);
                                while($rPresentacion = mysql_fetch_array($rsPresentaciones)){ 
                            ?>
                            <tr id="pre<?=$i;?>-0"><td><i class="far fa-trash-alt" onclick="deleteRow(\'pre<?=$i;?>-<?=$j;?>\',\'presentaciones<?=$i;?>\')"></i></td><td><input type="hidden" id="eCodPresentacion<?=$i;?>-0" name="presentaciones[<?=$i;?>][<?=$j;?>][eCodPresentacion]" value="<?=$rPresentacion{'eCodPresentacion'};?>"><input type="text" class="form-control" id="tPresentacion<?=$i;?>-<?=$j;?>" name="presentaciones[<?=$i;?>][<?=$j;?>][tPresentacion]" onkeyup="agregarPresentacion('<?=$i;?>','<?=$j;?>')" onkeypress="agregarPresentacion('<?=$i;?>','<?=$j;?>')" onblur="validarPresentacion('<?=$i;?>','<?=$j;?>')" placeholder="Presentación" value="<?=$rPresentacion{'tPresentacion'};?>"></td></tr>
                            <? $j++; ?>
                            <? } ?>
                            <tr id="pre<?=$i;?>-0"><td><i class="far fa-trash-alt" onclick="deleteRow(\'pre<?=$i;?>-<?=$j;?>\',\'presentaciones<?=$i;?>\')"></i></td><td><input type="hidden" id="eCodPresentacion<?=$i;?>-0" name="presentaciones[<?=$i;?>][<?=$j;?>][eCodPresentacion]"><input type="text" class="form-control" id="tPresentacion<?=$i;?>-<?=$j;?>" name="presentaciones[<?=$i;?>][<?=$j;?>][tPresentacion]" onkeyup="agregarPresentacion('<?=$i;?>','<?=$j;?>')" onkeypress="agregarPresentacion('<?=$i;?>','<?=$j;?>')" onblur="validarPresentacion('<?=$i;?>','<?=$j;?>')" placeholder="Presentación"></td></tr>
                            </table>
                        </td>
                    </tr>
                                    </table>  
                                            </td>
                               </tr>
                                      <? $i++; ?>
                                       <? } ?>
                                        <tr id="prd<?=$i;?>">
                        <td width="100%">
                        <table width="100%">
                        <tr>
                        <td>
                        <i style="margin-top:0px;" class="far fa-trash-alt" onclick="deleteRow('prd<?=$i?>','productos')"></i></td>
                        <td>
                            <input type="hidden" id="eCodProducto<?=$i;?>" name="productos[<?=$i;?>][eCodProducto]">
                            <input type="text" class="form-control" id="tProducto<?=$i;?>" name="productos[<?=$i;?>][tProducto]" onkeyup="agregarProducto('<?=$i;?>')" onkeypress="agregarProducto('<?=$i;?>')" onblur="validarProducto('<?=$i;?>')" placeholder="Producto">
                            </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                            <table id="presentaciones<?=$i;?>"><tr id="pre<?=$i;?>-0"><td><i class="far fa-trash-alt" onclick="deleteRow(\'pre<?=$i;?>-0\',\'presentaciones<?=$i;?>\')"></i></td><td><input type="hidden" id="eCodPresentacion<?=$i;?>-0" name="presentaciones[<?=$i;?>][0][eCodPresentacion]"><input type="text" class="form-control" id="tPresentacion<?=$i;?>-0" name="presentaciones[<?=$i;?>][0][tPresentacion]" onkeyup="agregarPresentacion('<?=$i;?>','0')" onkeypress="agregarPresentacion('<?=$i;?>','0')" onblur="validarPresentacion('<?=$i;?>','0')" placeholder="Presentación"></td></tr></table>
                        </td>
                    </tr>
                                    </table>  
                                            </td>
                               </tr>
                            </table>
                                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
        <div class="row">
            <div class="col-md-4">
                
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Coordinadores</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="clientes" width="100%">
                                       <? $i = 0; ?>
                                       <?
                                while($rCliente = mysql_fetch_array($rsClientes)){
                                ?>
                                      <tr id="cli<?=$i;?>">
                        <td><i class="far fa-trash-alt" onclick="deleteRow('cli<?=$i?>','clientes')"></i></td>
                        <td>
                            <input type="hidden" id="eCodCliente<?=$i;?>" name="clientes[<?=$i;?>][eCodCliente]" value="<?=$rCliente{'eCodUsuario'};?>">
                            <input type="text" class="form-control" id="tCliente<?=$i;?>" name="clientes[<?=$i;?>][tCliente]" onkeyup="agregarCliente(<?=$i;?>)" onkeypress="agregarCliente(<?=$i;?>)" onblur="validarCliente(<?=$i;?>)" value="<?=utf8_encode($rCliente{'tNombre'} ?  $rCliente{'tNombre'}.' '.$rCliente{'tApellidos'} : '');?>">
                        </td>
                    </tr>
                                       <? $i++; ?>
                                       <? } ?>
                                        <tr id="cli<?=$i;?>">
                        <td><i class="far fa-trash-alt" onclick="deleteRow('cli<?=$i?>','clientes')"></i></td>
                        <td>
                            <input type="hidden" id="eCodCliente<?=$i;?>" name="clientes[<?=$i;?>][eCodCliente]">
                            <input type="text" class="form-control" id="tCliente<?=$i;?>" name="clientes[<?=$i;?>][tCliente]" onkeyup="agregarCliente(<?=$i;?>)" onkeypress="agregarCliente(<?=$i;?>)" onblur="validarCliente(<?=$i;?>)">
                        </td>
                    </tr>
                                    </table> 
                                        
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-md-8">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Supervisores</h5>
                        <div class="table-responsive">
                            <table width="100%" id="supervisores">
                             <? $i = 0; ?>
                             <?
                                while($rSupervisor = mysql_fetch_array($rsSupervisores)){
                             ?>
                             <tr id="sup<?=$i;?>">
                                 <td>
                                 <?  supervisores($i,$rSupervisor); ?>
                                 </td>
                             </tr>
                             <? $i++; ?>
                             <? } ?>
                             <tr id="sup<?=$i;?>">
                                 <td>
                             <? supervisores($i); ?>
                                 </td>
                             </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!--tabs-->
        <input type="hidden" name="eFilas" id="eFilas" value="<?=$i?>">
        <input type="hidden" id="dTotalImportes" value="0">
    </form>
   <form id="frmFilas" method="post">
       <input type="hidden" name="eIndice" id="eIndice" value="0">
       <input type="hidden" name="eFila" id="eFila" value="0">
   </form>
<script>
    
    //autocompletes
   
    
    function agregarTienda(indice,fila)
        {
            var tTienda = document.getElementById('tTienda'+indice+'-'+fila),
                eCodTienda = document.getElementById('eCodTienda'+indice+'-'+fila);
            
            if(tTienda.value=="" || !tTienda.value)
                {
                    eCodTienda.value="";
                }
            
            
             $( function() {
  
        $( "#tTienda"+indice+"-"+fila ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-tiendas.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tTienda'+indice+'-'+fila).val(ui.item.label);
                $('#eCodTienda'+indice+'-'+fila).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    function agregarProducto(indice)
        {
            var tProducto = document.getElementById('tProducto'+indice),
                eCodProducto = document.getElementById('eCodProducto'+indice);
            
            if(tProducto.value=="" || !tProducto.value)
                {
                    eCodProducto.value="";
                }
            
            
             $( function() {
  
        $( "#tProducto"+indice ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-productos.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tProducto'+indice).val(ui.item.label);
                $('#eCodProducto'+indice).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    function agregarPresentacion(indice,fila)
        {
            var tPresentacion = document.getElementById('tPresentacion'+indice+'-'+fila),
                eCodProducto = document.getElementById('eCodPresentacion'+indice+'-'+fila);
            
            if(tPresentacion.value=="" || !tPresentacion.value)
                {
                    eCodPresentacion.value="";
                }
            
            
             $( function() {
  
        $( "#tPresentacion"+indice+"-"+fila ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-presentaciones.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tPresentacion'+indice+'-'+fila).val(ui.item.label);
                $('#eCodPresentacion'+indice+'-'+fila).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    function agregarSupervisor(indice)
        {
            var tSupervisor = document.getElementById('tSupervisor'+indice),
                eCodSupervisor = document.getElementById('eCodSupervisor'+indice);
            
            if(tSupervisor.value=="" || !tSupervisor.value)
                {
                    eCodSupervisor.value="";
                }
            
            var eCodMarca = document.getElementById('eCodCliente');
            
             $( function() {
  
        $( "#tSupervisor"+indice ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-usuarios.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                        eCodPerfil: 3,
                        eCodUsuarioCliente: eCodMarca.value
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tSupervisor'+indice).val(ui.item.label);
                $('#eCodSupervisor'+indice).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    function agregarPromotor(indice,fila)
        {
            var tPromotor = document.getElementById('tPromotor'+indice+'-'+fila),
                eCodPromotor = document.getElementById('eCodPromotor'+indice+'-'+fila);
            
            if(tPromotor.value=="" || !tPromotor.value)
                {
                    eCodPromotor.value="";
                }
            
            var eCodMarca = document.getElementById('eCodCliente');
            
             $( function() {
  
        $( "#tPromotor"+indice+"-"+fila ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-usuarios.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                        eCodPerfil: 4,
                        eCodUsuarioCliente: eCodMarca.value
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tPromotor'+indice+"-"+fila).val(ui.item.label);
                $('#eCodPromotor'+indice+"-"+fila).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    function agregarCliente(indice)
        {
            var tCliente = document.getElementById('tCliente'+indice),
                eCodCliente = document.getElementById('eCodCliente'+indice);
            
            if(tCliente.value=="" || !tCliente.value)
                {
                    eCodCliente.value="";
                }
            
            var eCodMarca = document.getElementById('eCodCliente');
            
             $( function() {
  
        $( "#tCliente"+indice ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-usuarios.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                        eCodPerfil: 5,
                        eCodUsuarioCliente: eCodMarca.value
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tCliente'+indice).val(ui.item.label);
                $('#eCodCliente'+indice).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    //validaciones
    
    //supervisor

function validarSupervisor(indice)
    {
        var eCodSupervisor    =   document.getElementById('eCodSupervisor'+indice),
            nIndice         =   parseInt(indice)+1;
        
        if(eCodSupervisor.value)
            {
                agregarFilaSupervisor(nIndice);    
            }
    }
    
function agregarFilaSupervisor(indice)
    {
        var x = document.getElementById("supervisores").rows.length;
        
        
        var eCodSupervisor = document.getElementById('eCodSupervisor'+indice);
        if(eCodSupervisor)
            {}
        else
        {
            
            document.getElementById('eIndice').value = indice;
            
            var obj = $('#frmFilas').serializeJSON();
            var jsonString = JSON.stringify(obj);
            
            $.ajax({
              type: "POST",
              url: "/tbl/supervisores.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  /*agregamos en la tabla*/
                    var table = document.getElementById("supervisores");
                    var row = table.insertRow(x);
                    row.id="sup"+(indice);
                    row.innerHTML = '<td>'+data.tHTML+'</td>';
                  /*agregamos en la tabla*/
                  
                  /*vaciamos*/
                  document.getElementById('eFila').value = '';
                    document.getElementById('eIndice').value = '';
                  /*vaciamos*/
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });
           
    
        }
        
    }

//Promotor

function validarPromotor(indice,fila)
    {
        var eCodPromotor    =   document.getElementById('eCodPromotor'+indice+'-'+fila),
         eCodTienda    =   document.getElementById('eCodTienda'+indice+'-'+fila),
            nIndice         =   parseInt(fila)+1;
        
        if(eCodPromotor.value && eCodTienda.value)
            {
                agregarFilaPromotor(indice,nIndice);    
            }
    }
    
function agregarFilaPromotor(indice,fila)
    {
        var x = document.getElementById("promotores"+indice).rows.length;
        
        
        var eCodPromotor = document.getElementById('eCodPromotor'+indice+'-'+fila);
        if(eCodPromotor)
            {}
        else
        {
            
            document.getElementById('eFila').value = fila;
            document.getElementById('eIndice').value = indice;
            
          var obj = $('#frmFilas').serializeJSON();
            var jsonString = JSON.stringify(obj);
          
          
          $.ajax({
              type: "POST",
              url: "/tbl/promotores.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  /*agregamos en la tabla*/
                    var table = document.getElementById("promotores"+indice);
                    var row = table.insertRow(x);
                    row.id="pro"+(indice)+'-'+(fila);
                    row.innerHTML = data.tHTML;
                  /*agregamos en la tabla*/
                  
                  /*vaciamos*/
                  document.getElementById('eFila').value = '';
                    document.getElementById('eIndice').value = '';
                  /*vaciamos*/
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });
           
    
        }
        
    }
    
function validarProducto(indice)
    {
        var eCodProducto    =   document.getElementById('eCodProducto'+indice),
            tProducto    =   document.getElementById('tProducto'+indice),
            nIndice         =   parseInt(indice)+1;
        
        if(eCodProducto.value || tProducto.value)
            {
                agregarFilaProducto(nIndice);    
            }
    }
    
function agregarFilaProducto(indice)
    {
        var x = document.getElementById("productos").rows.length;
        
        
        var eCodProducto = document.getElementById('eCodProducto'+indice);
        if(eCodProducto)
            {}
        else
        {
           
    var table = document.getElementById("productos");
    var row = table.insertRow(x);
    row.id="prd"+(indice);
    row.innerHTML = '<td width="100%"><table width="100%"><tr><td><i style="margin-top:0px;" class="far fa-trash-alt" onclick="deleteRow(\'prd'+indice+'\',\'productos\')"></i></td><td><input type="hidden" id="eCodProducto'+indice+'" name="productos['+indice+'][eCodProducto]"><input type="text" class="form-control" id="tProducto'+indice+'" name="productos['+indice+'][tProducto]" onkeyup="agregarProducto('+indice+')" onkeypress="agregarProducto('+indice+')" onblur="validarProducto('+indice+')" placeholder="Producto"></td></tr><tr><td></td><td><table id="presentaciones'+indice+'"><tr id="pre'+indice+'-0"><td><i class="far fa-trash-alt" onclick="deleteRow(\'pre'+indice+'-0\',\'presentaciones'+indice+'\')"></i></td><td><input type="hidden" id="eCodPresentacion'+indice+'-0" name="presentaciones['+indice+'][0][eCodPresentacion]"><input type="text" class="form-control" id="tPresentacion'+indice+'-0" name="presentaciones['+indice+'][0][tPresentacion]" onkeyup="agregarPresentacion('+indice+',\'0\')" onkeypress="agregarPresentacion('+indice+',\'0\')" onblur="validarPresentacion('+indice+',\'0\')" placeholder="Presentación"></td></tr></table></td></tr></table></td>';
        }
        
    }
    
function validarPresentacion(indice,fila)
    {
        var eCodPresentacion    =   document.getElementById('eCodPresentacion'+indice+'-'+fila),
            tPresentacion    =   document.getElementById('tPresentacion'+indice+'-'+fila),
            nIndice         =   parseInt(fila)+1;
        
        if(eCodPresentacion.value || tPresentacion.value)
            {
                agregarFilaPresentacion(indice,nIndice);    
            }
    }
    
function agregarFilaPresentacion(indice,fila)
    {
        var x = document.getElementById("presentaciones"+indice).rows.length;
        
        
        var eCodPresentacion = document.getElementById('eCodPresentacion'+indice+'-'+fila);
        if(eCodPresentacion)
            {}
        else
        {
           
    var table = document.getElementById("presentaciones"+indice);
    var row = table.insertRow(x);
    row.id="prd"+(indice)+"-"+(fila);
    row.innerHTML = '<td><i class="far fa-trash-alt" onclick="deleteRow(\'pre'+indice+'-'+fila+'\',\'presentaciones'+indice+'\')"></i></td><td><input type="hidden" id="eCodPresentacion'+indice+'-'+fila+'" name="presentaciones['+indice+']['+fila+'][eCodPresentacion]"><input type="text" class="form-control" id="tPresentacion'+indice+'-'+fila+'" name="presentaciones['+indice+']['+fila+'][tPresentacion]" onkeyup="agregarPresentacion(\''+indice+'\',\''+fila+'\')" onkeypress="agregarPresentacion(\''+indice+'\',\''+fila+'\')" onblur="validarPresentacion(\''+indice+'\',\''+fila+'\')" placeholder="Presentación"></td>';
        }
        
    }

   
    $(document).ready(function() {
              $('#fhFechaPromotoria').datepicker({
                  locale:'es',
                  dateFormat: "dd/mm/yy"
              });
          });
    
   
    

		</script>