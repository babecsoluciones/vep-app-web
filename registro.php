<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");
include("inc/fun-ini.php");
$clSistema = new clSis();
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Required meta tags-->
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Title Page-->
    <title>G.W.C. - Registro</title>

    <link href="/css/font-face.css" rel="stylesheet" media="all">
    <link href="/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="/css/theme.css" rel="stylesheet" media="all">
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    
    <!--DataTables-->
    <link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css"/>

    <!--DatePicker-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/Start/jquery-ui.css">
    
    <link href="/css/calendario.css" rel="stylesheet" media="all">
    
    <link href="/ext/autocomplete/easy-autocomplete.min.css" rel="stylesheet" media="all">

</head>

<body class="animsition" oncontextmenu="return false;">
    <div class="page-wrapper">
        <div class="page-content--bge5" style="overflow:scroll">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="/images/icon/logo.png" alt="G.W.C.">
                            </a>
                        </div>
                        <div class="login-form">
                            <form action="" method="post" name="Datos" id="Datos">
                                <div class="form-group">
                                    <label>T&iacute;tulo(s)</label>
                                    <input class="au-input au-input--full" type="text" id="tTitulo" name="tTitulo" placeholder="Lic, Ing, C.P.C">
                                </div>
                                <div class="form-group">
                                    <label>Nombre(s)</label>
                                    <input class="au-input au-input--full" type="text" id="tNombre" name="tNombre" placeholder="Nombre(s)">
                                </div>
                                <div class="form-group">
                                    <label>Apellido(s)</label>
                                    <input class="au-input au-input--full" type="text" id="tApellidos" name="tApellidos" placeholder="Apellido(s)">
                                </div>
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input class="au-input au-input--full" type="email" id="tCorreo" name="tCorreo" placeholder="E-mail">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" type="password" id="tPassword" name="tPassword" placeholder="Password" onkeyup="asignarPassword()">
                                    <input type="hidden" id="tPasswordAcceso" name="tPasswordAcceso">
                                    <input type="hidden" id="tPasswordOperaciones" name="tPasswordOperaciones">
                                </div>
                                <div class="form-group">
                                    <label>Tel. Fijo</label>
                                    <input class="au-input au-input--full" type="text" id="tTelefonoFijo" name="tTelefonoFijo" placeholder="Tel. Fijo">
                                </div>
                                <div class="form-group">
                                    <label>Tel. M&oacute;vil</label>
                                    <input class="au-input au-input--full" type="text" id="tTelefonoMovil" name="tTelefonoMovil" placeholder="Tel. M&oacute;vil">
                                </div>
                                <div class="form-group">
                                    <label>Estado</label>
                                    <input class="au-input au-input--full" type="text" id="tEstado" name="tEstado" placeholder="Aguascalientes, Baja California, CDMX, etc.">
                                </div>
                                
                                        <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">RFC</label>
                                                    <input type="text" name="tRFC" placeholder="RFC" class="form-control">
                                                </div>
                                        
                                        <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Raz&oacute;n Social</label>
                                                    <input type="text" name="tRazonSocial" placeholder="Razon Social" class="form-control">
                                                </div>
                                        
                                        <div class="form-group">
                                                    <label for="postal-code" class=" form-control-label">Domicilio Fiscal</label>
                                                    <input type="text" name="tDomicilioFicsal" placeholder="Domicilio Fiscal" class="form-control">
                                                </div>
                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="aggree" id="bPolitica" onclick="activarBoton()"><a href="#" data-toggle="modal" data-target="#aviso">Acepto la Pol&iacute;tica de Privacidad</a>
                                    </label>
                                </div>
                                
                                
                            </form>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" onclick="guardar();" id="registrar" disabled>Guardar</button>
                            <div class="register-link">
                                <p>
                                    Ya tienes cuenta?
                                    <a href="/login/">Inicia Sesi&oacute;n</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
     <!-- Modal -->
  <div class="modal fade" id="aviso" role="dialog">
    <div class="modal-dialog  modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Aviso de Privacidad</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" style="max-height:500px; overflow-y: scroll;">
          <p align="justify">
          En cumplimiento con la Ley Federal de Protecci&oacute;n de Datos Personales en Posesi&oacute;n de los Particulares E-MICAPACITACION les comunica: 
Que es responsable de los datos personales recabados a los participantes en los cursos y eventos que organiza, 
<br><br>
Los datos personales a recabar ser&aacute;n: nombre completo, telefono particular fijo o movil, Correo, direccion completa, giro, Clave &uacute;nica de Registro de Poblaci&oacute;n(CURP), curr&iacute;culum profesional, fotograf&iacute;as; datos profesionales: 
<br><br>
Sus datos personales recabados incluyendo los sensibles, im&aacute;genes tomadas en fotograf&iacute;a, video u ac&uacute;sticos, por motivo de la relaci&oacute;n jur&iacute;dica celebrada o a celebrarse, ser&aacute;n considerados y tratados para los fines vinculantes con la relaci&oacute;n informativa. 
La informaci&oacute;n personal que proporcione ser&aacute; tratada para las siguientes finalidades: a) Mantener actualizada la base de datos de E-MICAPACITACION  para env&iacute;o de informaci&oacute;n enfocada a difundir y publicitar los programas de capacitaci&oacute;n y actualizaci&oacute;n que este lleve a cabo.
b) Difundir los resultados de los eventos, concursos creadoss para la investigaci&oacute;n y el an&aacute;lisis de las pymes  
h) Compartir en redes sociales las fotograf&iacute;as que sean tomadas en los eventos de negocios i) Notificaci&oacute;n de cambios en los horarios, sedes, cancelaci&oacute;n, suspensi&oacute;n y/o modificaci&oacute;n de seminarios o eventos.j) Reconocimiento de participaci&oacute;n en seminarios o eventos.
No es finalidad de E-MICAPACITACION  recabar datos de menores de edad en forma directa, ni de terceras personas ni por trasferencia de datos, en caso de ser recabados, ser&aacute;n protegidos y tratados conforme la Ley Federal de Protecci&oacute;n de Datos Personales en Posesi&oacute;n de los Particulares y de su Reglamento. 
E-MICAPACITACION  no recabar&aacute; datos personales de los usuarios a trav&eacute;s de “cookies”. La p&aacute;gina Web de e-micapacitacion, con direcci&oacute;n www.e-micapacitacion.com contiene “cookies”. Los cookies son archivos de datos que se almacenan en el disco duro de equipo de c&oacute;mputo o el dispositivo de comunicaciones electr&oacute;nica, y se usan para determinar sus preferencias cuando se conecta para ver los servicios e informaci&oacute;n que ofrece E-MICAPACITACION. 
<br><br>
Usted como usuario de la p&aacute;gina Web de E-MICAPACITACION., al proporcionar datos para inscribirse a seminarios en l&iacute;nea, estos estar&aacute;n protegidos por un servidor seguro bajo el protocolo de Secure Socket Layer, de tal forma que los datos transmitidos ser&aacute;n encriptados para asegurar su resguardo, manteniendo su seguridad y confidencialidad. Usted debe saber que ning&uacute;n sitio de seguridad o de transmisi&oacute;n de datos que no est&eacute; bajo el control absoluto de E-MICAPACITACION y tenga derivaci&oacute;n en Internet, E-MICAPACITACION, no puede garantizar que sea totalmente seguro. Usted como usuario de la p&aacute;gina Web de E-MICAPACITACION., encontrara en ella, enlaces, v&iacute;nculos (links), direcciones electr&oacute;nicas que no funcionan bajo la pol&iacute;tica de privacidad delE-MICAPACITACION, por lo que al conectarse a esos nuevos v&iacute;nculos, debe revisar las pol&iacute;ticas de privacidad de cada sitio antes de revelar cualquier dato personal solicitado. 
<br><br>
Usted tiene el derecho al Acceso, Rectificaci&oacute;n, Cancelaci&oacute;n y Oposici&oacute;n (ARCO), de sus datos personales, le informamos que el responsable de recibir y resguardarlos es el Director Administrativo, por lo que al solicitar la modificaci&oacute;n o la supresi&oacute;n o la revocaci&oacute;n de sus datos personales, ser&aacute; necesario lo comunique al correo electr&oacute;nico y env&iacute;e solicitud de derechos ARCO. 
 <br><br>
La solicitud ARCO debe contener cuando menos: 1) Nombre del titular de los datos personales. 2) Direcci&oacute;n f&iacute;sica y correo electr&oacute;nico para recibir notificaciones. 3) Documento de identidad oficial vigente. 4) Firma aut&oacute;grafa del titular de los derechos. 5) Indicaci&oacute;n clara de los datos personales respecto de los que se busca ejercer el derecho y descripci&oacute;n del derecho que pretende ejercer. El plazo para que elE-MICAPACITACION, atienda y comunique al titular la determinaci&oacute;n adoptada ser&aacute; de 20 d&iacute;as y se duplicara en caso de existir justificaci&oacute;n. La respuesta a la solicitud ser&aacute; notificada al correo electr&oacute;nico indicado en la solicitud, y el e-micapacitacion, la conservara en el domicilio señalado al principio del presente. 
 <br><br>
E-MICAPACITACION proteger&aacute; f&iacute;sica y electr&oacute;nicamente los datos personales proporcionados personalmente, los recabados a trav&eacute;s de terceros o recibidos por transmisi&oacute;n de datos, en base a las disposiciones de la Ley Federal de Protecci&oacute;n de Datos Personales en Posesi&oacute;n de los Particulares, su Reglamento. Si no desea recibir o desea dejar de recibir promocionales f&iacute;sicos o electr&oacute;nicos deE-MICAPACITACION, solic&iacute;telo enviando a la direcci&oacute;n avisodeprovacidad@e-micapacitacion.com un correo electr&oacute;nico indicando en el asunto –baja promocional eventos-, y una breve descripci&oacute;n del porque no desea recibir m&aacute;s informaci&oacute;n sobre los eventos ofrecidos de E-MICAPACITACION.
<br><br>
El cambio de las presentes pol&iacute;ticas de privacidad podr&aacute; realizarse por E-MICAPACITACION en cualquier momento y estar&aacute;n disponibles en http://www.e-micapacitacion.com/aviso-de-privacidad.html
<br>
Fecha de &uacute;ltima actualizaci&oacute;n Agosto del 2019.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      
    </div>
  </div>
    
    <!-- Modal -->
    <div class="modal fade" id="resExito" role="dialog">
      <div class="modal-dialog">
      
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <center>
              <img src="/res/ok.png" style="width:75px; height:75px;"><br>
                <h3>Registro Guardado Exitosamente</h3><br>
              </center>
          </div>
        </div>
        
      </div>
    </div>

       <!-- Jquery JS-->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <!-- Bootstrap JS-->
    <script src="/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="/vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script  src="/vendor/slick/slick.min.js"></script>
    <!--<script  src="/vendor/wow/wow.min.js"></script>-->
    <script  src="/vendor/animsition/animsition.min.js"></script>
    
    <script  src="/vendor/circle-progress/circle-progress.min.js"></script>
    <script  src="/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/vendor/select2/select2.min.js"></script>
    
    <!-- Main JS-->
    <script src="/js/main.js"></script>
	<script src="/js/aplicacion.js"></script>
    
        <!--DataTables-->
        <script type="text/javascript" src="/DataTables/datatables.min.js"></script>
	
          <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        
        <script type="text/javascript" src="/js/jquery.serializejson.js"></script>
        
       
    
    <!--envio-->
    <script>
      
      /*Preparación y envío*/
      function guardar()
      {
          var formulario = document.getElementById('datos');
          
                    if(confirm("Deseas guardar la información?"))
                        {
                            serializar();
                        }
      }

      function enviar(cadena)
      {
          
            $.ajax({
              type: "POST",
              url: "<?=obtenerURL();?>cla/registro.php",
              data: cadena,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  
                  if(data.exito==1)
                  {
                     
                      $('#resExito').modal('show');
                      setTimeout(function(){ $('#resExito').modal('hide'); }, 3000);
                      setTimeout(function(){ window.location="/login/"; }, 3500);
                  }
                  else
                      {
                          var mensaje="";
                          for(var i=0;i<data.errores.length;i++)
                     {
                         mensaje += "-"+data.errores[i]+"\n";
                     }
                          alert("Error al procesar la solicitud.\n<-Valide la siguiente informacion->\n\n"+mensaje);
                         
                      }
                  
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });
         
          
      }

      function serializar()
      {
          var obj = $('#Datos').serializeJSON();
          var jsonString = JSON.stringify(obj);
          //alert(jsonString);
          enviar(jsonString);
      }
        
        function activarBoton()
          {
              var bPolitica = document.getElementById('bPolitica'),
                  registro = document.getElementById('registrar');
              
              if(bPolitica.checked==true)
              { registro.disabled=false; }
              if(bPolitica.checked==false)
              { registro.disabled=true; }
          }
          
          function asignarPassword()
          {
              document.getElementById('tPasswordAcceso').value=document.getElementById('tPassword').value;
              document.getElementById('tPasswordOperaciones').value=document.getElementById('tPassword').value;
          }
        </script>

</body>

</html>
<!-- end document-->