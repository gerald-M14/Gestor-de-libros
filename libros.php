<?php

class Administrador {
    private $archivo = 'libros.json';

    // Cargar los libros desde el archivo JSON
    private function cargarLibros() {
        if (!file_exists($this->archivo)) {
            file_put_contents($this->archivo, json_encode([]));
        }
        $json = file_get_contents($this->archivo);
        return json_decode($json, true);
    }

    // Guardar los libros en el archivo JSON
    private function guardarLibros($libros) {
        file_put_contents($this->archivo, json_encode($libros, JSON_PRETTY_PRINT));
    }

    // Agregar un libro
    public function agregarLibro($titulo, $autor, $editorial, $estado, $fechaPublicacion) {
        // Cargar los libros
        $libros = $this->cargarLibros();
        
        $fechaPublicacion = date("Y-m-d", strtotime($fechaPublicacion)); 

        // Crear un nuevo libro
        $nuevoLibro = [
            "id" => count($libros) + 1,
            "titulo" => $titulo,
            "autor" => $autor,
            "fecha_publicacion" => $fechaPublicacion,
            "editorial" => $editorial,  
            "estado" => $estado         
        ];
        
        // Agregar el nuevo libro al array
        $libros[] = $nuevoLibro;
        
        // Guardar los libros actualizados
        $this->guardarLibros($libros);
        
        return "Libro agregado correctamente";
    }

    // Editar un libro por ID
    public function editarLibro($id, $nuevoTitulo, $nuevoAutor, $nuevoEditorial, $nuevoEstado, $nuevaFechaPublicacion) {
        $libros = $this->cargarLibros();
        foreach ($libros as &$libro) {
            if ($libro['id'] == $id) {
                $libro['titulo'] = $nuevoTitulo;
                $libro['autor'] = $nuevoAutor;
                $libro['editorial'] = $nuevoEditorial;
                $libro['estado'] = $nuevoEstado;
                $libro['fecha_publicacion'] = date("Y-m-d", strtotime($nuevaFechaPublicacion));
                $this->guardarLibros($libros);
                return "Libro editado correctamente";
            }
        }
        return "Libro no encontrado";
    }

    // Eliminar un libro por ID
    public function eliminarLibro($id) {
        $libros = $this->cargarLibros();
        $libros = array_filter($libros, function($libro) use ($id) {
            return $libro['id'] != $id;
        });
        $this->guardarLibros(array_values($libros)); 
        return "Libro eliminado correctamente";
    }

    // Buscar libros por título o autor
    public function buscarLibro($texto) {
        $libros = $this->cargarLibros();
        $resultados = array_filter($libros, function($libro) use ($texto) {
            return stripos($libro['titulo'], $texto) !== false || stripos($libro['autor'], $texto) !== false;
        });
        return $resultados;
    }

    // Obtener y mostrar todos los libros
    public function mostrarLibros() {
        $libros = $this->cargarLibros();
        if (empty($libros)) {
            return "<p>No hay libros registrados.</p>";
        }
        $html = "<table class='tabla-libros'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Fecha de Publicación</th>
                <th>Editorial</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>";

foreach ($libros as $libro) {
$html .= "<tr>
            <td>{$libro['id']}</td>
            <td>{$libro['titulo']}</td>
            <td>{$libro['autor']}</td>
            <td>{$libro['fecha_publicacion']}</td>
            <td>{$libro['editorial']}</td>
            <td>{$libro['estado']}</td>
            <td>
                <button class='btn-editar' onclick='editarLibro({$libro['id']}, \"{$libro['titulo']}\", \"{$libro['autor']}\", \"{$libro['fecha_publicacion']}\", \"{$libro['editorial']}\", \"{$libro['estado']}\")'>Editar</button>
                <form method='POST' class='form-eliminar'>
                    <input type='hidden' name='id' value='{$libro['id']}'>
                    <button type='submit' name='accion' value='eliminar' class='btn-eliminar'>Eliminar</button>
                </form>
            </td>
        </tr>";
}

$html .= "</tbody></table>";


$html .= "
<script>
function editarLibro(id, titulo, autor, fecha_publicacion, editorial, estado) {
document.querySelector('input[name=\"idEditar\"]').value = id;
document.querySelector('input[name=\"tituloEditar\"]').value = titulo;
document.querySelector('input[name=\"autorEditar\"]').value = autor;
document.querySelector('input[name=\"fecha_publicacionEditar\"]').value = fecha_publicacion;
document.querySelector('input[name=\"editorialEditar\"]').value = editorial;
document.querySelector('input[name=\"estadoEditar\"]').value = estado;
}
</script>";

return $html;
}
}

class Cliente {
    private $archivo = 'libros.json';

    // Cargar los libros desde el archivo JSON
    private function cargarLibros() {
        if (!file_exists($this->archivo)) {
            file_put_contents($this->archivo, json_encode([]));
        }
        $json = file_get_contents($this->archivo);
        return json_decode($json, true);
    }

    // Guardar los libros en el archivo JSON
    private function guardarLibros($libros) {
        file_put_contents($this->archivo, json_encode($libros, JSON_PRETTY_PRINT));
    }

     // Obtener y mostrar todos los libros
     public function mostrarLibros() {
        $libros = $this->cargarLibros();
        if (empty($libros)) {
            return "<p>No hay libros registrados.</p>";
        }
        $html = "<table class='tabla-libros'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Fecha de Publicación</th>
                <th>Editorial</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>";

        foreach ($libros as $libro) {
            $disabled = ($libro['estado'] !== 'disponible') ? 'disabled' : '';
        
            $html .= "<tr>
                        <td>{$libro['id']}</td>
                        <td>{$libro['titulo']}</td>
                        <td>{$libro['autor']}</td>
                        <td>{$libro['fecha_publicacion']}</td>
                        <td>{$libro['editorial']}</td>
                        <td>{$libro['estado']}</td>
                        <td>
                            <form method='POST' class='form-prestar'>
                                <input type='hidden' name='id' value='{$libro['id']}'>
                                <button type='submit' name='accion' value='pedir_prestado' class='btn-prestar' $disabled>
                                    Pedir prestado
                                </button>
                            </form>
                        </td>
                    </tr>";
        }
        
        $html .= "</tbody></table>";
        


return $html;
}

}
?>
