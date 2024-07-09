<?php
include('../../app/helpers/login.php');
Login_Page::headerTemplateDashboard('Autenticación | Clientes');
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
            <img class="Avatar" src="../../resources/img/svgs/undraw_user_black-theme.svg" alt="dashboard03">
            <h2>Autenticación</h2>
            <!-- INPUTS -->
            <div class="input-div">
                <div>
                    <h5>Código</h5>
                    <input autocomplete="off" type="number" max="6" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" id="codigo" name="codigo" class="input">
                </div>
            </div>
            <div style="display: flex; justify-content:center">
                <a onclick="verificarCodigo()" class="btnDashboard">
                    VALIDAR CÓDIGO
                </a>
            </div>
            <a href="index.php">¿Desea regresar al login?</a>
        </form>
    </div>
</div>
<script type="text/javascript" src="../../app/controllers/initialization.js"></script>
<?php
Login_Page::footerTemplate('public/autentication.js');
?>