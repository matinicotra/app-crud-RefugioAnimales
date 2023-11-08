<?php include("template/header.php"); ?>

<?php
include("administrador/config/bd.php");
$sentenciaSQL = $conexion->prepare("SELECT * FROM Gatos");
$sentenciaSQL->execute();
$listaGatos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>


<?php foreach($listaGatos as $gato) { ?>
<div class="col-md-3">
    <div class="card">
        <img class="card-img-top" src="./img/<?php echo $gato['imagen'];?>" alt="">
        <div class="card-body">
            <h4 class="card-title"><?php echo $gato['nombre']; ?></h4>
            <a name="" id="" class="btn btn-primary" href="#" role="button">Ver mas</a>
        </div>
    </div>
</div>
<?php } ?>


<?php include("template/footer.php"); ?>