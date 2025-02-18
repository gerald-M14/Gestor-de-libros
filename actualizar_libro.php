<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    // Leer el archivo JSON con los libros
    $libros = json_decode(file_get_contents('libros.json'), true);
    
    // Buscar el libro por ID y actualizar sus valores
    foreach ($libros as &$libro) {
        if ($libro['id'] == $_POST['idEditar']) {
            $libro['titulo'] = $_POST['tituloEditar'];
            $libro['autor'] = $_POST['autorEditar'];
            $libro['fecha_publicacion'] = $_POST['fecha_publicacionEditar'];
            $libro['editorial'] = $_POST['editorialEditar'];
            $libro['estado'] = $_POST['estadoEditar'];
            break;
        }
    }

    // Guardar los datos actualizados en el archivo JSON
    file_put_contents('libros.json', json_encode($libros, JSON_PRETTY_PRINT));
    
    // Redirigir a la página principal después de la actualización
    header('Location: dashboard.php');
    exit();
}
?>
