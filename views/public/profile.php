<?php
include('../../app/helpers/public.php');
Public_Page::headerTemplate('SigmaQ - Configuración personal');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 espacex2">
            <div class="container">
                <h3 class="centrar">Modificar datos personales</h3>
                <form method="post" id="save-form" enctype="multipart/form-data">
                    <div class="row">
                        <input type="hidden" id="idCliente" name="idCliente" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                        <div class="col-sm-12 col-md-6">
                            <br>
                            <label for="txtEmpresa" class="form-label">Empresa</label>
                            <input type="text" class="form-control" id="txtEmpresa" name="txtEmpresa" disabled>
                            <b>
                                <font COLOR="red">El campo no puede ser modificado.</font>
                            </b>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <br>
                            <label for="txtTelefono" class="form-label">Télefono</label>
                            <input autocomplete="off" onkeydown="validateTextPhone('txtTelefono','legTelefono')" type="text" class="form-control" id="txtTelefono" name="txtTelefono">
                            <div id="legTelefono" class="form-text">
                                <b>
                                    <font COLOR="blue">Puedes actualizar tu número de teléfono.</font>
                                </b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <br>
                            <label for="txtCorreo" class="form-label">Correo</label>
                            <input autocomplete="off" onkeydown="validateTextMail('txtCorreo','legCorreo')" type="email" class="form-control" id="txtCorreo" name="txtCorreo">
                            <div id="legCorreo" class="form-text">
                                <b>
                                    <font COLOR="blue">Puedes actualizar tu dirección de correo.</font>
                                </b>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <br>
                            <label for="txtUsuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="txtUsuario" name="txtUsuario" disabled>
                            <b>
                                <font COLOR="red">El campo no puede ser modificado.</font>
                            </b>
                        </div>
                    </div><br><br>
                </form>
                <div class="row">
                    <div class="d-grid gap-2 col-12 mx-auto">
                        <button onclick="modificarDatos()" class="btn btn-primary botonesProfile centrar" type="button">Modificar perfil</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="container"><br><br>
                <h3 class="centrar">Cambiar contraseña</h3>
                <form method="post" id="password-form" enctype="multipart/form-data">
                    <div class="row espace">
                        <input type="hidden" id="idCliente2" name="idCliente2" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                        <div class="d-grid gap-2 col-sm-12 col-md-4 mx-auto">
                            <br>
                            <label for="txtClaveActual" class="form-label">Clave actual</label>
                            <input onkeydown="validateTextPassword('txtClaveActual','legClaveActual')" type="password" id="txtClaveActual" name="txtClaveActual" class="form-control" aria-describedby="claveActual">
                            <div id="legClaveActual" class="form-text">
                                Si desea cambiar su clave complete el campo.
                            </div>
                        </div>
                        <div class="d-grid gap-2 col-sm-12 col-md-4 mx-auto">
                            <br>
                            <label for="txtClaveNueva" class="form-label">Nueva clave</label>
                            <input onkeydown="validateTextNewPassword('txtClaveNueva','legClaveNueva')" type="password" id="txtClaveNueva" name="txtClaveNueva" class="form-control" aria-describedby="claveNueva">
                            <div id="legClaveNueva" class="form-text">
                                Si deseas cambiar tu clave ingresa la nueva clave.
                            </div>
                        </div>
                        <div class="d-grid gap-2 col-sm-12 col-md-4 mx-auto">
                            <br>
                            <label for="txtClaveConfirmar" class="form-label">Confirmar clave</label>
                            <input onkeydown="validateTextNewPassword('txtClaveConfirmar','legClaveConfirmar')" type="password" id="txtClaveConfirmar" name="txtClaveConfirmar" class="form-control" aria-describedby="claveConfirmar">
                            <div id="legClaveConfirmar" class="form-text">
                                Ingrese nuevamente su nueva contraseña para confirmar.
                            </div>
                        </div>
                    </div>
                </form><br><br>
                <div class="row espace inferior">
                    <div class="d-grid gap-2 col-12 mx-auto">
                        <button onclick="actualizarContraseña()" class="btn btn-primary botonesProfile centrar" type="button">Modificar contraseña</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../../app/controllers/legends.js"></script>
<?php
Public_Page::footerTemplate('profile');
?>