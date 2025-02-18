<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        .container { margin: 100px auto; padding: 20px; width: 300px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        a { text-decoration: none; color: white; background: red; padding: 10px; display: inline-block; border-radius: 5px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Bienvenido, <?php echo $_SESSION["admin"]; ?></h2>
    <p>Has iniciado sesión correctamente.</p>
    <a href="logout.php">Cerrar Sesión</a>
</div>

<h1>Administración de Libros</h1>

    <!-- Formulario para agregar un libro -->
    <h2>Agregar Libro</h2>
    <form method="POST">
        <input type="text" name="titulo" placeholder="Título" required>
        <input type="text" name="autor" placeholder="Autor" required>
        <input type="date" name="fecha_publicacion" placeholder="Fecha de Publicación" required>
        <input type="text" name="editorial" placeholder="Editorial" required>
        <input type="text" name="estado" placeholder="Estado" required>
        <button type="submit" name="accion" value="agregar">Agregar</button>
    </form>

    <!-- Formulario para editar un libro -->
    <h2>Editar Libro</h2>
    <form method="POST" action="actualizar_libro.php">
        <input type="hidden" name="idEditar" id="inputId">
        <input type="text" name="tituloEditar" id="inputTitulo" placeholder="Título">
        <input type="text" name="autorEditar" id="inputAutor" placeholder="Autor">
        <input type="date" name="fecha_publicacionEditar" id="inputFecha" placeholder="Fecha de publicación">
        <input type="text" name="editorialEditar" id="inputEditorial" placeholder="Editorial">
        <input type="text" name="estadoEditar" id="inputEstado" placeholder="Estado">
        
        <button type="submit" name="accion" value="editar">Actualizar</button>
    </form>





    <!-- Formulario para eliminar un libro -->
    <h2>Eliminar Libro</h2>
    <form method="POST">
        <input type="number" name="id" placeholder="ID del libro" required>
        <button type="submit" name="accion" value="eliminar">Eliminar</button>
    </form>

    <!-- Formulario para buscar un libro -->
    <h2>Buscar Libro</h2>
    <form method="POST">
        <input type="text" name="texto" placeholder="Buscar por título o autor">
        <button type="submit" name="accion" value="buscar">Buscar</button>
    </form>

    <?php
    require 'libros.php';
    $admin = new Administrador();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accion = $_POST["accion"];

        if ($accion == "agregar") {
            $fechaPublicacion = $_POST["fecha_publicacion"];
            $titulo = $_POST["titulo"];
            $autor = $_POST["autor"];
            $editorial = $_POST["editorial"];
            $estado = $_POST["estado"];
            echo $admin->agregarLibro($titulo, $autor, $editorial, $estado, $fechaPublicacion);

        } elseif ($accion == "editar") {
            echo $admin->editarLibro($_POST["idEditar"], $_POST["tituloEditar"], $_POST["autorEditar"], $_POST["editorialEditar"], $_POST["estadoEditar"], $_POST["fecha_publicacionEditar"]);

        } elseif ($accion == "eliminar") {
            echo $admin->eliminarLibro($_POST["id"]);

        } elseif ($accion == "buscar") {
            $resultados = $admin->buscarLibro($_POST["texto"]);
            echo "<h3>Resultados:</h3>";
            foreach ($resultados as $libro) {
                echo "ID: " . $libro["id"] . " - " . $libro["titulo"] . " - " . $libro["autor"] . "<br>";
            }
        }
    }

    echo $admin->mostrarLibros();
    ?>
</body>
</html>
