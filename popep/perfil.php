<?php
include 'header.php';
?>
</div>
</header>
<?php
//include "autoload.php";
//
//use \Clases\BaseDeDatos as bd;
//
//session_start();
//if (isset($_SESSION['rol'])) {
//    $bd = new bd($_SESSION['rol']);
//    session_start();
//} else {
//    $bd = new bd();
//}
if (isset($_POST['modify'])) {
    if ($bd->modify_usser($_POST['id'], $_POST['name'], $_POST['email'], $_POST['telf'], $_POST['direccion'])) {
        $_SESSION['usuario'] = $_POST['email'];
        echo 'Datos modificados con éxito';
        $bd->set_fecha_modify($_POST['email']);
        header('Location: perfil.php');
    } else {
        echo 'Error al modificar los datos';
    }
}
$arrayData = $bd->get_data_usser($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="archive/style/style.css">
        <link rel="stylesheet" href="archive/style/normalice/normalice.css">
        <link rel="stylesheet" href="archive\icons\fontawesome-free-5.15.2-web\css\all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Roboto:wght@300&display=swap"
              rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="archive/jQuery/js/jquery-3.3.1.min.js" type="text/javascript"></script>
    </head>

    <body>
        <h1>Perfil:</h1>
        <br>
        <table style="text-align: center">
            <tr>
                <th>Nombre</th><th>Email</th><th>Teléfono</th><th>Dirección</th><th></th>
            </tr>
            <tr>
                <?php
                echo $arrayData["text"];
                ?>
            </tr>
        </table>
        <br>
        <h1>Reservas:</h1>
        <br>
        <table style="text-align: center;border: solid 2px;">
            <tr style="text-align: center;border: solid 2px;">
                <th style='text-align: center;border: solid 2px;'>Número de la reserva </th><th style='text-align: center;border: solid 2px;'>Id del usuario </th><th style='text-align: center;border: solid 2px;'>Fecha de entrada </th><th style='text-align: center;border: solid 2px;'>Fecha de salida </th><th style='text-align: center;border: solid 2px;'>Numero de habitación </th>
            </tr>
            <tr style="text-align: center;border: solid 2px;">
                <?php
                echo $bd->view_data_reservas($arrayData["id_usuario"]);
                ?>
            </tr>
        </table>
        <br>
        <?php
        ?>
        <a href="close_session.php">Cerrar sesión</a>
    </body>


