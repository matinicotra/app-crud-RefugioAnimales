<?php include('template/header-admin.php'); ?>

<div class="col-md-12">
    <div class="jumbotron">
        <h1 class="display-3">Bienvenido <?php echo $nombreUsuario?> </h1>
        <p class="lead">Vamos a administrar nuestros gatitos en el sitio web</p>
        <hr class="my-2">
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="seccion/gatos.php" role="button">Administrar gatitos</a>
        </p>
    </div>
</div>

<?php include('template/footer-admin.php'); ?>