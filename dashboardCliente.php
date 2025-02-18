<?php
session_start();
if (!isset($_SESSION["Cliente"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles.css">
    <title>Panel de cliente</title>

</head>
<body>

<div class="container">

    <div class="container__bienvenida">
    <h2>Bienvenido, <?php echo $_SESSION["cliente"]; ?></h2>
    <a class="logout" href="logout.php">Cerrar Sesión</a>
    </div>

    <h1>Biblioteca</h1>

    <section class="buscar">
    <!-- Formulario para buscar un libro -->
    <h2>Buscar Libro</h2>
    <form class="form form_agregar"method="POST">
        <input type="text" name="texto" placeholder="Buscar por título o autor">
        <button type="submit" name="accion" value="buscar">Buscar</button>
    </form>
    </section>

    <?php
    require 'libros.php';
    $Cliente = new Cliente();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accion = $_POST["accion"];

        if ($accion == "buscar") {
            $resultados = $cliente->buscarLibro($_POST["texto"]);
            echo "<h3>Resultados:</h3>";
            foreach ($resultados as $libro) {
                echo "ID: " . $libro["id"] . " - " . $libro["titulo"] . " - " . $libro["autor"] . "<br>";
            }
        }
    }

    echo $cliente->mostrarLibros();
    ?>

    </div>
    </body>
</html>