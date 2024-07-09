<?php
include('../../app/helpers/dashboard.php');
Dashboard_Page::headerTemplate('Edición de perfil', 'dashboard');
?>                                                                                                                                                                                      <style>.fondoProfile {background: #ffffff;} .botonesProfile { width: 100%;} .fondoInput { background-color: #f7f7f9; } </style>

<div class="container-fluid fondoProfile">
    <div class="container "><br><br>
        <center>
            <h3>Modificar datos personales</h3>
        </center>
        <br>
        <form method="post" id="save-form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="txtNombre" class="form-label">Nombre</label>
                    <input autocomplete="off" onkeydown="validatetextEmpty('txtNombre','legNombre')" type="text" class="form-control fondoInput" id="txtNombre" name="txtNombre">
                    <div id="legNombre" class="form-text">
                        <b>
                            <font COLOR="blue">Puedes actualizar el campo nombre.</font>
                        </b>
                    </div><br>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="txtApellido" class="form-label">Apellido</label>
                    <input autocomplete="off" onkeydown="validatetextEmpty('txtApellido','legApellido')" type="text" class="form-control" id="txtApellido" name="txtApellido">
                    <div id="legApellido" class="form-text">
                        <b>
                            <font COLOR="blue">Puedes actualizar el campo apellido.</font>
                        </b>
                    </div><br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="txtDui" class="form-label">DUI</label>
                    <input autocomplete="off" type="text" class="form-control" id="txtDui" name="txtDui" disabled>
                    <div id="legDui" class="form-text">
                        <b>
                            <b>
                                <font COLOR="red">El campo no puede ser modificado.</font>
                            </b>
                        </b>
                    </div><br>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="txtCorreo" class="form-label">Correo</label>
                    <input autocomplete="off" onkeydown="validateTextMail('txtCorreo','legCorreo')" type="email" class="form-control" id="txtCorreo" name="txtCorreo">
                    <div id="legCorreo" class="form-text">
                        <b>
                            <font COLOR="blue">Puedes actualizar tu dirección de correo electrónico.</font>
                        </b>
                    </div><br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="txtTelefono" class="form-label">Teléfono</label>
                    <input autocomplete="off" onkeydown="validateTextPhone('txtTelefono','legTelefono')" type="phone" class="form-control" id="txtTelefono" name="txtTelefono">
                    <div id="legTelefono" class="form-text">
                        <b>
                            <font COLOR="blue">Puedes actualizar tu número de teléfono.</font>
                        </b>
                    </div><br>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="txtUsuario" class="form-label">Usuario</label>
                    <input autocomplete="off" type="text" class="form-control" id="txtUsuario" name="txtUsuario">
                    <div id="legUsuario" class="form-text">
                        <b>
                            <font COLOR="blue">Puedes actualizar tu nombre de usuario siempre y cuando este disponible.</font>
                        </b>
                    </div>
                </div>
            </div>
        </form><br>
        <div class="row">
            <div class="d-grid gap-2 col-12 mx-auto">
                <center><button onclick="modificarDatos()" class="btn btn-primary botonesProfile" type="button">Modificar perfil</button></center>
            </div>
        </div><br>
        <hr>
    </div>

    <div class="container ">
        <br>
        <center>
            <h3>Cambiar contraseña</h3>
        </center>
        <br>
        <form method="post" id="password-form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-4">
                    <label for="txtClaveActual" class="form-label">Clave actual</label>
                    <input onkeydown="validateTextPassword('txtClaveActual','legClaveActual')" type="password" id="txtClaveActual" name="txtClaveActual" class="form-control" aria-describedby="claveActual">
                    <div id="legClaveActual" class="form-text">
                        Ingrese la contraseña actual de su usuario en caso querer actualizarla.
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-4">
                    <label for="txtClaveNueva" class="form-label">Nueva clave</label>
                    <input onkeydown="validateTextNewPassword('txtClaveNueva','legClaveNueva')" type="password" id="txtClaveNueva" name="txtClaveNueva" class="form-control" aria-describedby="claveNueva">
                    <div id="legClaveNueva" class="form-text">
                        Si deseas cambiar tu clave ingresa la nueva clave.
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-4">
                    <label for="txtClaveConfirmar" class="form-label">Confirmar clave</label>
                    <input onkeydown="validateTextNewPassword('txtClaveConfirmar','legClaveConfirmar')" type="password" id="txtClaveConfirmar" name="txtClaveConfirmar" class="form-control" aria-describedby="claveConfirmar">
                    <div id="legClaveConfirmar" class="form-text">
                        Ingrese nuevamente su nueva contraseña para confirmar.
                    </div>
                </div>
            </div>
        </form>
        <br><br>
        <div class="row">
            <div class="d-grid gap-2 col-12 mx-auto">
                <center><button onclick="actualizarContraseña()" class="btn btn-primary botonesProfile" type="button">Modificar contraseña</button></center>
            </div>
        </div><br><br>

    </div>
</div>
<script src="../../app/controllers/legends.js"></script>
<?php
Dashboard_Page::footerTemplate('profile');
?>