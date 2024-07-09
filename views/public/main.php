<?php
include('../../app/helpers/public.php');
Public_Page::headerTemplate('SigmaQ - Bienvenido');
?>

<section>

    <div class="welcome--container">
        <div class="welcome--info">
            <div class="welcome--info__container">
                <p class="welcome--info__title">Bienvenido a</p>
                <img class="info--title__img" src="../../resources/img/brand/logoBlanco.png" alt="">
            </div>
            <div class="welcome--info__text">
                <p>
                    Nos esforzamos por brindarte un servicio excepcional. <br>
                    En este portal podrás tener acceso directo a información de interes para ti y tu compañía.
                </p>
            </div>
            <div class="welcome--info__button">
                <a href="#section--title" class="welcome--info__link">
                    <img class="info--button__icon" src="../../resources/img/icons/continuar.png" alt="">
                    Ver más
                </a>
            </div>
        </div>
        <div class="welcome--img">
            <img class="welcome--img__svg" src="../../resources/img/svgs/undraw_Browser_stats_re_j7wy.svg" alt="">
        </div>
    </div>

    <?php
    Public_Page::sectionTitleTemplate('MI INFORMACIÓN EMPRESARIAL', 'section--title');
    ?>

    <div class="card--options__container">
        <a class="card--options__link" href="estadoCuenta.php">
            <div class="card col-mb-3 col-bg-3">
                <h3 class="card-header">ESTADOS DE CUENTA</h3>
                <div class="options--img__container">
                    <img class="card--options__img" src="../../resources/img/svgs/undraw_All_the_data_re_hh4w.svg" alt="">
                </div>
                <div class="card-body">
                    <p class="card-text">Accede a todos tus estados de cuenta actualizados a la fecha.</p>
                </div>
            </div>
        </a>
        <a class="card--options__link" href="statusPedidos.php">
            <div class="card col-mb-3 col-bg-3">
                <h3 class="card-header">STATUS DE PÉDIDO</h3>
                <div class="options--img__container">
                    <img class="card--options__img" src="../../resources/img/svgs/undraw_Booked_re_vtod.svg" alt="">
                </div>
                <div class="card-body">
                    <p class="card-text">Accede a la información de los status de tus pedidos.</p>
                </div>
            </div>
        </a>
        <a class="card--options__link" href="indiceEntrega.php">
            <div class="card col-mb-3 col-bg-3">
                <h3 class="card-header">ÍNDICE DE ENTREGA</h3>
                <div class="options--img__container">
                    <img class="card--options__img" src="../../resources/img/svgs/undraw_deliveries_131a.svg" alt="">
                </div>
                <div class="card-body">
                    <p class="card-text">Verifica nuestros índices de estado de entrega aquí.</p>
                </div>
            </div>
        </a>
    </div>

    <?php
    Public_Page::sectionTitleTemplate('TU ÉXITO ES NUESTRO ÉXITO', 'section--announcement');
    ?>

    <div class="announcement--container">
        <h1 class="announcement--container__title">¡Celebramos más de 20 años de relación comercial contigo!</h1>
        <img class="announcement--container__img  img-fluid" src="../../resources/img/svgs/undraw_happy_announcement_ac67.svg" alt="">
    </div>

    <div class="contact--container">
        <!-- <div class="contact--container__img">
            <img class="contact--img" src="../../resources/img/profile/person.jpg" alt="">
        </div> -->
        <div class="contact--info mt-4">
            <h5 class="contact--info__title">Contacta a tu Ejecutivo de Ventas de manera fácil y rápida</h5>
            <h3 class="contact--info__name" id="responsable-name">CARLOS PERÉZ</h3>
            <p class="contact--info__position">Ejecutivo de Negocios</p>
            <p class="contact--info__contacts" id="responsable-telefono">T: (502) 2301-9800</p>
            <p class="contact--info__mail" id="responsable-correo">cfernandezlitozadik.sigmaq.com</p>
        </div>
    </div>

</section>

<?php
Public_Page::footerTemplate('main');
?>