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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../styles.css">
    <title>Panel de Administrador</title>

</head>
<body>

<div class="container">

    <div class="container__bienvenida">
    <h2>Bienvenido, <?php echo $_SESSION["admin"]; ?></h2>
    <a class="logout" href="logout.php">Cerrar Sesión</a>
    </div>
    


<h1>Administración de Libros</h1>

<div class="container_acciones">

<section class="agregar"> 
    <!-- Formulario para agregar un libro -->
    <h2>Agregar Libro</h2>
    <form class="form form_agregar" method="POST">
        <input type="text" name="titulo" placeholder="Título" required>
        <input type="text" name="autor" placeholder="Autor" required>
        <input type="date" name="fecha_publicacion" placeholder="Fecha de Publicación" required>
        <input type="text" name="editorial" placeholder="Editorial" required>
        
        <!-- Cambié el input de estado por un select -->
        <select name="estado" required>
            <option value="disponible">Disponible</option>
            <option value="no_disponible">No disponible</option>
            <option value="prestamo_activo">Préstamo activo</option>
        </select>
        
        <button type="submit" name="accion" value="agregar">Agregar</button>
    </form>
</section>

<!-- Formulario para editar un libro -->
<section class="editar"> 
    <h2>Editar Libro</h2>
    <form class="form form_agregar" method="POST" action="actualizar_libro.php">
        <input type="hidden" name="idEditar" id="inputId">
        <input type="text" name="tituloEditar" id="inputTitulo" placeholder="Título">
        <input type="text" name="autorEditar" id="inputAutor" placeholder="Autor">
        <input type="date" name="fecha_publicacionEditar" id="inputFecha" placeholder="Fecha de publicación">
        <input type="text" name="editorialEditar" id="inputEditorial" placeholder="Editorial">
        
        <!-- Cambié el input de estado por un select -->
        <select name="estadoEditar" id="inputEstado" required>
            <option value="disponible">Disponible</option>
            <option value="no_disponible">No disponible</option>
            <option value="prestamo_activo">Préstamo activo</option>
        </select>
        
        <button type="submit" name="accion" value="editar">Actualizar</button>
    </form>
</section>

    

<section class="eliminar">
    <!-- Formulario para eliminar un libro -->
    <h2>Eliminar Libro por ID</h2>
    <form class="form form_eliminar" method="POST">
        <input type="number" name="id" placeholder="ID del libro" required>
        <button type="submit" name="accion" value="eliminar">Eliminar</button>
    </form>
    </section>

    <section class="buscar">
    <!-- Formulario para buscar un libro -->
    <h2>Buscar Libro</h2>
    <form class="form form_agregar"method="POST">
        <input type="text" name="texto" placeholder="Buscar por título o autor">
        <button type="submit" name="accion" value="buscar">Buscar</button>
    </form>
    </section>

</div>
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
</div>
</body>
</html>
