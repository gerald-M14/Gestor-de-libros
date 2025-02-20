<?php

class Administrador {
    private $archivo = 'libros.json';
    private $archivoPrestamos = 'prestamos.json';
    
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
    public function agregarLibro($titulo, $autor, $editorial, $estado, $fechaPublicacion, $imagen_url, $categoria) {
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
            "estado" => $estado, 
            "imagen_url" => $imagen_url,         
            "categoria" => $categoria         

        ];
        
        // Agregar el nuevo libro al array
        $libros[] = $nuevoLibro;
        
        // Guardar los libros actualizados
        $this->guardarLibros($libros);
        
        return "Libro agregado correctamente";
    }

    //buscar libros

    public function buscarLibros($searchTerm = '', $categoryFilter = '', $authorFilter = '') {
        $libros = $this->cargarLibros();
        
        return array_filter($libros, function($libro) use ($searchTerm, $categoryFilter, $authorFilter) {
            $matchSearch = empty($searchTerm) || 
                           stripos($libro['titulo'], $searchTerm) !== false || 
                           stripos($libro['autor'], $searchTerm) !== false;
            
            $matchCategory = empty($categoryFilter) || $libro['categoria'] == $categoryFilter;
            
            $matchAuthor = empty($authorFilter) || $libro['autor'] == $authorFilter;
            
            return $matchSearch && $matchCategory && $matchAuthor;
        });
    }

    // Editar un libro por ID
    public function editarLibro($id, $nuevoTitulo, $nuevoAutor, $nuevoEditorial, $nuevoEstado, $nuevaFechaPublicacion, $nuevaImagen, $nuevaCategoria) {
        $libros = $this->cargarLibros();
        foreach ($libros as &$libro) {
            if ($libro['id'] == $id) {
                $libro['titulo'] = $nuevoTitulo;
                $libro['autor'] = $nuevoAutor;
                $libro['editorial'] = $nuevoEditorial;
                $libro['estado'] = $nuevoEstado;
                $libro['fecha_publicacion'] = date("Y-m-d", strtotime($nuevaFechaPublicacion));
                $libro['imagen_url'] = $nuevaImagen;
                $libro['categoria'] = $nuevaCategoria;

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
        


        $html = "<div class='tarjetas-libros'>";

            foreach ($libros as $libro) {
                $html .= "<div class='tarjeta-libro max-w-xs mx-auto bg-white rounded-lg shadow-lg overflow-hidden transform transition-all hover:scale-105 hover:shadow-xl'>
                            <img src='{$libro['imagen_url']}' alt='{$libro['imagen_url']}' class='imagen-libro w-full h-48 object-cover'>
                            <div class='info-libro p-4'>
                                <h3 class='text-lg font-semibold text-gray-800'>{$libro['titulo']}</h3>
                                <p class='text-sm text-gray-600'><strong>Autor:</strong> {$libro['autor']}</p>
                                <p class='text-sm text-gray-600'><strong>Fecha de Publicación:</strong> {$libro['fecha_publicacion']}</p>
                                <p class='text-sm text-gray-600'><strong>Editorial:</strong> {$libro['editorial']}</p>
                                <p class='text-sm text-gray-600'><strong>Estado:</strong> {$libro['estado']}</p>
                                <p class='text-sm text-gray-600'><strong>Categoría:</strong> {$libro['categoria']}</p>
                                <div class='mt-4'>
                                    
                                    <button data-modal-target='authentication-modal' data-modal-toggle='authentication-modal' class='block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800' type='button' class='btn-editar' onclick='editarLibro({$libro['id']}, \"{$libro['titulo']}\", \"{$libro['autor']}\", \"{$libro['fecha_publicacion']}\", \"{$libro['editorial']}\", \"{$libro['estado']}\")'>Editar</button>

                                    
                                    <form method='POST' class='inline-block'>
                                        <input type='hidden' name='id' value='{$libro['id']}'>
                                        <button type='submit' name='accion' value='eliminar' class='bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none ml-2'>Eliminar</button>
                                    </form>
                                </div>
                            </div>



                        </div>";
            }
            
            $html .= "</div>";



                $html .= "
            <script>
            // Función para abrir el modal y llenar los campos del formulario con los datos del libro
            function editarLibro(id, titulo, autor, fecha_publicacion, editorial, estado, imagen_url, categoria) {
                // Asignamos los valores a los campos del formulario en el modal
                document.querySelector('#inputId').value = id;
                document.querySelector('#inputTitulo').value = titulo;
                document.querySelector('#inputAutor').value = autor;
                document.querySelector('#inputFecha').value = fecha_publicacion;
                document.querySelector('#inputEditorial').value = editorial;
                document.querySelector('#inputEstado').value = estado;
                document.querySelector('#inputImagen_url').value = imagen_url;
                document.querySelector('#inputCategoria').value = categoria;


                // Mostramos el modal
                document.getElementById('modalEditar').style.display = 'block';
            }

            // Función para cerrar el modal
            function cerrarModal() {
                document.getElementById('modalEditar').style.display = 'none';
            }

            // Cerrar el modal si el usuario hace clic fuera de él
            window.onclick = function(event) {
                if (event.target == document.getElementById('modalEditar')) {
                    cerrarModal();
                }
            }
        </script>
        ";

                return $html;
    }  
    
    public function obtenerLibrosPrestados() {
        if (!file_exists($this->archivoPrestamos)) {
            file_put_contents($this->archivoPrestamos, json_encode([]));
        }
        $json = file_get_contents($this->archivoPrestamos);
        return json_decode($json, true);
    }

    public function prestarLibro($idLibro, $nombreCliente, $emailCliente) {
        $libros = $this->cargarLibros();
        $prestamos = $this->obtenerLibrosPrestados();

        foreach ($libros as &$libro) {
            if ($libro['id'] == $idLibro && $libro['estado'] == 'Disponible') {
                $libro['estado'] = 'Prestamo activo';
                $fechaPrestamo = date('Y-m-d');
                $fechaDevolucion = date('Y-m-d', strtotime('+1 month'));

                $prestamo = [
                    'id_libro' => $idLibro,
                    'titulo' => $libro['titulo'],
                    'autor' => $libro['autor'],
                    'nombre_cliente' => $nombreCliente,
                    'email_cliente' => $emailCliente,
                    'fecha_prestamo' => $fechaPrestamo,
                    'fecha_devolucion' => $fechaDevolucion
                ];

                $prestamos[] = $prestamo;

                $this->guardarLibros($libros);
                file_put_contents($this->archivoPrestamos, json_encode($prestamos, JSON_PRETTY_PRINT));

                return "Libro prestado correctamente";
            }
        }

        return "El libro no está disponible para préstamo";
    }

    public function devolverLibro($idLibro, $emailCliente) {
        $libros = $this->cargarLibros();
        $prestamos = $this->obtenerLibrosPrestados();

        foreach ($libros as &$libro) {
            if ($libro['id'] == $idLibro) {
                $libro['estado'] = 'Disponible';
                break;
            }
        }

        $prestamos = array_filter($prestamos, function($prestamo) use ($idLibro, $emailCliente) {
            return !($prestamo['id_libro'] == $idLibro && $prestamo['email_cliente'] == $emailCliente);
        });

        $this->guardarLibros($libros);
        file_put_contents($this->archivoPrestamos, json_encode(array_values($prestamos), JSON_PRETTY_PRINT));

        return "Libro devuelto correctamente";
    }
}

class Cliente {
    private $archivo = 'libros.json';
    private $archivoPrestamos = 'prestamos.json';
    private $nombre;
    private $correo;

    // ... (previous methods remain the same)

    public function prestarLibro($idLibro, $nombreCliente, $emailCliente) {
        $admin = new Administrador();
        return $admin->prestarLibro($idLibro, $nombreCliente, $emailCliente);
    }

    public function devolverLibro($idLibro, $emailCliente) {
        $admin = new Administrador();
        return $admin->devolverLibro($idLibro, $emailCliente);
    }

    public function obtenerLibrosPrestados($emailCliente) {
        $prestamos = json_decode(file_get_contents($this->archivoPrestamos), true);
        return array_filter($prestamos, function($prestamo) use ($emailCliente) {
            return $prestamo['email_cliente'] == $emailCliente;
        });
    }

    public function buscarLibros($searchTerm = '', $categoryFilter = '', $authorFilter = '') {
        $admin = new Administrador();
        return $admin->buscarLibros($searchTerm, $categoryFilter, $authorFilter);
    }
    public function validarCredenciales($correo, $contrasena) {
        // Implement your validation logic here
        // If valid, set the $nombre and $correo properties
        $this->nombre = "Nombre del Cliente"; // Replace with actual name
        $this->correo = $correo;
        return true; // Return true if credentials are valid, false otherwise
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCorreo() {
        return $this->correo;
    }
}


?>
