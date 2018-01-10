<?php
if(!session_id()) {
session_start();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Public Social</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="jumbotron">
<?php
require_once('vendor/autoload.php');
include 'config.php';
include 'facebookToken.php';
include 'linkedinToken.php';
$facebookToken = new facebookToken($conn);
$facebookToken->getToken();
$linkedin = new linkedinToken($conn);
$linkedin->getToken();
if (!$conn_posteo){
 echo '<div class="alert alert-danger">
  <strong>Error de conexión base de datos de posteo</strong></div>';
}
if($linkedin->DateExpireToken() == 0){
	echo '<div class="alert alert-danger">
  <strong>Requerido: Login Linkedin</strong></div>';
}
if ($result = $conn->query("SHOW TABLES LIKE '".TABLE_CONFIG."'")) {
	if($result->num_rows == 1) {
		echo '<h2>Ahora puedes</h2>';
		echo '<a class="btn btn-default" role="button" href="dashboard.php">Configurar</a>';
		if (!empty($facebookToken->CreateUrlFBLogin()))
		    echo "<a class='btn btn-primary' role='button' href=' ".$facebookToken->CreateUrlFBLogin()." '>Login Facebook</a>";
		if (!empty($linkedin->createUrlLogin())){
	            echo "<a class='btn btn-primary' role='button' href=' ".$linkedin->createUrlLogin()." '>Login Linkedin</a>";
        }
	}else{
	    echo '<h1>Instalar table base de datos</h1>';
		echo '<a class="btn btn-danger" href="install.php">Instalar</a>';
	}
}
?>
        </div>
        </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>