<!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Registrar usuario</title>
            <link rel="stylesheet" href="../../resources/css/dashboard.css">
            <link rel="stylesheet" href="../../resources/css/bootstrap.min.css">
            <link rel="shortcut icon" href="../../resources/img/brand/qRoja.png" type="image/x-icon">
        </head>
    <body>
        <style >  
            body {
                /* Base64 encoded transparent gif */
                background-image: url("../../resources/img/background/fondoDashboard.png");
                background-size: 100% 100%;
                background-repeat: no-repeat;
                margin: 0 auto;
                height: 720px; 
            }
            .moverContenedor{ 
                padding-left:230px; 
            }
        </style>
        <div class="container moverContenedor"><br><br> 
            <div class="row">                  
                <h3>Registrar usuario</h3>  
                <form method="post" id="register-form">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Código</label>
                                <input autocomplete="off" id="txtId" name="txtId" type="number" min="1" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Nombre</label>
                                <input autocomplete="off" id="txtNombre" name="txtNombre" type="text" class="form-control" required>
                            </div>	
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input autocomplete="off" id="txtClave" name="txtClave" type="password" class="form-control" placeholder="" required>
                            </div>								
                            <div class="form-group">
                                <label>Teléfono</label>
                                <div class="form-group">
                                    <input autocomplete="off" id="txtTelefono" name="txtTelefono" type="text" class="form-control" placeholder="0000-0000" required>
                                </div>			
                            </div>			
                        </div>		
                        <div class="col-6">
                            <div class="form-group">
                                <label>Usuario</label>
                                <input autocomplete="off" id="txtUsuario" name="txtUsuario" type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Apellido</label>
                                <input autocomplete="off" id="txtApellido" name="txtApellido" type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Confirmar contraseña</label>
                                <input autocomplete="off" id="txtClave2" name="txtClave2" type="password" class="form-control" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label>DUI</label>
                                <div class="form-group">
                                    <input autocomplete="off" id="txtDui" name="txtDui" type="text" class="form-control" placeholder="01234567-8" required>
                                </div> 
                            </div>		
                        </div>
                        <div class="col-6">
                            <label>Correo electrónico</label>
                            <input autocomplete="off" id="txtCorreo" name="txtCorreo" type="email" class="form-control" placeholder="correo@example.com" required>				
                        </div>    
                        <div class="col-6">
                            <label>Dirección</label>
                            <input autocomplete="off" id="txtDireccion" name="txtDireccion" type="text" class="form-control"  required>				
                        </div>		
                    </div>
                    <br><br>
                    <center><button onclick="registrarUsuario()" type="button" class="btn btn-primary">Guardar</button></center>
                </form>
            </div>
        </div>
        <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
        <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../app/helpers/components.js"></script>
        <script type="text/javascript" src="../../app/controllers/dashboard/register.js"></script>
    </body>
</html>