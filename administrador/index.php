<?php
session_start();

if ($_POST) {
	if (($_POST['usuario'] == "matinicotra") && ($_POST['password'] == "abc123")) {				// VALIDACION SIMPLE... A MODIFICAR CON UNA CONSULTA A BD !!!!!!!!!!
		$_SESSION['usuario'] = "ok";
		$_SESSION['nombreUsuario'] = "matinicotra";

		// redireccionar a inicio
		header('location:inicio.php');
	} else {
		$message = "Error! Usuario y/o contraseña incorrecto.";
	}
}
?>

<!doctype html>
<html lang="en">

<head>
	<title>Administrador</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4">

			</div>

			<div class="col-md-4">
				<br>
				<div class="card">
					<div class="card-header">
						Login
					</div>

					<div class="card-body">

						<?php if (isset($message)) { ?>
							<div class="alert alert-danger" role="alert">
								<?php echo $message; ?>
							</div>
						<?php } ?>

						<form method="POST">
							<div class="form-group">
								<label for="">Usuario</label>
								<input type="text" class="form-control" name="usuario" placeholder="Escribe tu usuario">
							</div>

							<div class="form-group">
								<label for="exampleInputPassword1">Password</label>
								<input type="password" class="form-control" name="password" placeholder="Escribe tu password">
							</div>

							<button type="submit" class="btn btn-primary">Entrar al administrador</button>
							<a href="../index.php">VOLVER</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>