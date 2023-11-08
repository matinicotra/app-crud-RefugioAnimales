<?php include("../template/header-admin.php") ?>

<?php

// DECLARACION DE VARIABLES Y ASIGNACION DE LOS CAMPOS DEL FORM Y ACCIONES

$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";           // isset — Determina si una variable está definida y no es null

$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";

$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";     // El array global $_FILES contendrá toda la información de los los ficheros subidos

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";


include("../config/bd.php");


// ACCIONES DEL CRUD

switch ($accion) {
    case "Agregar":
        // insert nuevo registro en BD
        $sentenciaSQL = $conexion->prepare("INSERT INTO Gatos (nombre, imagen) VALUES (:nombre, :imagen)");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);        // bindParam vincula a una variable php un parametro generado por una sentencia sql
        
        // GUARDAR ARCHIVO
        $fecha = new DateTime();        // crear variable del tipo DateTime para concatenar posteriormente al nombre del archivo de img a guardar y que no haya sobreescritura de archivos
        
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";   // concatena la fecha al nombre del archivo
        
        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];      // guarda la informacion del archivo en la variable temporal para consultar si no es nulo
        
        if ($tmpImagen != "") {
            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);      // guardar el archivo en el directorio
        }
        
        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->execute();

        // redireccionar para que no vuelva a enviar los datos
        header("Location:gatos.php");
        break;

    case "Modificar":
        // update nombre en BD
        $sentenciaSQL = $conexion->prepare("UPDATE Gatos SET nombre = :nombre WHERE id = :id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        // MODIFICAR ARCHIVO
        if ($txtImagen != "") {
            // guardar archivo en directorio
            $fecha = new DateTime();
            $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);

            // borrar archivo anterior
            $sentenciaSQL = $conexion->prepare("SELECT imagen FROM Gatos WHERE id = :id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();

            // Para devolver una única fila de un conjunto de resultados como una matriz u objeto, llame al método PDOStatement::fetch.
            $gato = $sentenciaSQL->fetch(PDO::FETCH_LAZY);          // FETCH_LAZY Combina PDO::FETCH_BOTH y PDO::FETCH_OBJ, creando los nombres de variable de objeto conforme se accede a los mismos.

            if (isset($gato["imagen"]) && ($gato["imagen"] != "imagen.jpg")) {
                if (file_exists("../../img/" . $gato["imagen"])) {
                    unlink("../../img/" . $gato["imagen"]);
                }
            }

            // update img en BD
            $sentenciaSQL = $conexion->prepare("UPDATE Gatos SET imagen = :imagen WHERE id = :id");
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
        }

        // redireccionar para que no vuelva a enviar los datos
        header("Location:gatos.php");
        break;

    case "Cancelar":
        // redireccionar
        header("Location:gatos.php");
        break;

    case "Seleccionar":
        // consulta BD
        $sentenciaSQL = $conexion->prepare("SELECT * FROM Gatos WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        $gato = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre = $gato['nombre'];
        $txtImagen = $gato['imagen'];
        break;

    case "Borrar":
        // consulta BD
        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM Gatos WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        $gato = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        // eliminar archivo
        if (isset($gato["imagen"]) && ($gato["imagen"] != "imagen.jpg")) {
            if (file_exists("../../img/" . $gato["imagen"])) {
                unlink("../../img/" . $gato["imagen"]);
            }
        }

        $sentenciaSQL = $conexion->prepare("DELETE FROM Gatos WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        // redireccionar para que no vuelva a enviar los datos
        header("Location:gatos.php");
        break;
}

// CARGA LA LISTA
// consulta sql
$sentenciaSQL = $conexion->prepare("SELECT * FROM Gatos");
$sentenciaSQL->execute();
// Para devolver todas las filas del conjunto de resultados como una matriz de matrices u objetos, llame al método PDOStatement::fetchAll.
$listaGatos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);            // PDO::FETCH_ASSOC Devuelve una matriz indexada por nombre de columna tal y como se ha devuelto en el conjunto de resultados.
                                                                    
?>



<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Datos del gato
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data"> <!-- $_POST is a PHP super global variable which is used to collect form data after submitting an HTML form with method="post". $_POST is also widely used to pass variables. -->

                <div class="form-group">
                    <label for="txtID">ID:</label>
                    <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
                </div>

                <div class="form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre">
                </div>

                <div class="form-group">
                    <label for="txtImagen">Imagen:</label>

                    <?php   
                        if ($txtImagen != "") { ?>
                            <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen; ?>" width="50"> 
                    <?php } ?>

                    <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen">
                </div>

                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : "" ?> value="Agregar" class="btn btn-success">Agregar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : "" ?> value="Modificar" class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : "" ?> value="Cancelar" class="btn btn-info">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaGatos as $gato) { ?>
                <tr>
                    <td><?php echo $gato['id']; ?></td>
                    <td><?php echo $gato['nombre']; ?></td>
                    <td>
                        <img class="img-thumbnail rounded" src="../../img/<?php echo $gato['imagen']; ?>" width="50">
                    </td>

                    <td>
                        <form method="post">
                            <input type="hidden" name="txtID" id="txtID" value="<?php echo $gato['id']; ?>" />
                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>




<?php include("../template/footer-admin.php") ?>