<?php
include('../../app/helpers/login.php');
Login_Page::headerTemplateDashboard('Login | Administradores');
?>
    <img class="fondo" src="../../resources/img/background/fondoDashboard.png" alt="dashboard01">
    <div class="container">
        <!-- IMAGEN DE FONDO -->
        <div class="img">
            <img src="../../resources/img/brand/logoSinFondo.png" alt="dashboard02">
        </div>
        <!-- AQUÍ VA EL LOGIN -->
        <div class="login-container">
            <form method="post" id="email-form">
                <img class="Avatar" src="../../resources/img/utilities/mail.jpg" alt="dashboard03">
                <h2>Recuperacíon</h2>
                <!-- INPUTS -->
                <div class="input-div one">
                    <div>
                        <h5>Correo</h5>
                        <input autocomplete="off" type="text" maxlength="35" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" id="correo" name="correo" class="input">
                    </div>
                    </div>
                    <div class="input-div two">
                        <div>
                            <h5>Código</h5>
                            <input autocomplete="off" type="password" maxlength="35" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" id="codigo" name="codigo" class="input" disabled>
                        </div>
                    </div>
                    <div style="display: flex; justify-content:center">
                    <a onclick="enviarCorreo()" class="btnDashboard">
                        <label id="texto">ENVIAR CÓDIGO</label>
                    </a>
                </div>  
                <a href="index.php">¿Desea regresar al login?</a>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
<?php
    Login_Page::footerTemplate('dashboard/email.js');
?>