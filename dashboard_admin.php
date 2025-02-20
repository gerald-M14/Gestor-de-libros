<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit();
}

require 'libros.php';
$admin = new Administrador();

// Predefined categories
$predefinedCategories = [
    "Ficción",
    "No ficción",
    "Misterio",
    "Ciencia ficción",
    "Fantasía",
    "Romance",
    "Thriller",
    "Biografía"
];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST["accion"] ?? '';

    if ($accion == "agregar") {
        $admin->agregarLibro(
            $_POST["titulo"],
            $_POST["autor"],
            $_POST["editorial"],
            $_POST["estado"],
            $_POST["fecha_publicacion"],
            $_POST["imagen_url"],
            $_POST["categoria"]
        );
    } elseif ($accion == "editar") {
        $admin->editarLibro(
            $_POST["idEditar"],
            $_POST["tituloEditar"],
            $_POST["autorEditar"],
            $_POST["editorialEditar"],
            $_POST["estadoEditar"],
            $_POST["fecha_publicacionEditar"],
            $_POST["imagenEditar"],
            $_POST["categoriaEditar"]
        );
    } elseif ($accion == "eliminar") {
        $admin->eliminarLibro($_POST["id"]);
    }
}

// Handle search and filters
$searchTerm = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$authorFilter = $_GET['author'] ?? '';

$libros = $admin->buscarLibros($searchTerm, $categoryFilter, $authorFilter);
$librosPrestados = $admin->obtenerLibrosPrestados();

// Get unique authors for filter dropdown
$authors = array_unique(array_column($libros, 'autor'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Bienvenido, <?php echo $_SESSION["admin"]; ?></h2>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Cerrar Sesión</a>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Administración de Libros</h1>

        <!-- Search and Filter Form -->
        <form method="GET" action="" class="mb-8 flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Buscar por título o autor" value="<?php echo htmlspecialchars($searchTerm); ?>" class="p-2 border rounded">
            <select name="category" class="p-2 border rounded">
                <option value="">Todas las categorías</option>
                <?php foreach ($predefinedCategories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $category === $categoryFilter ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="author" class="p-2 border rounded">
                <option value="">Todos los autores</option>
                <?php foreach ($authors as $author): ?>
                    <option value="<?php echo htmlspecialchars($author); ?>" <?php echo $author === $authorFilter ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($author); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Filtrar</button>
        </form>

        <button onclick="openModal('addModal')" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-8">Agregar Libro</button>

        <!-- Display books -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($libros as $libro): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                    <div class="h-64 overflow-hidden">
                        <img src="<?php echo htmlspecialchars($libro['imagen_url']); ?>" alt="<?php echo htmlspecialchars($libro['titulo']); ?>" class="w-full h-full object-cover object-center transform scale-110">
                    </div>
                    <div class="p-4 flex-grow">
                        <h3 class="font-bold text-xl mb-2"><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                        <p class="text-gray-700 text-base mb-1"><strong>Autor:</strong> <?php echo htmlspecialchars($libro['autor']); ?></p>
                        <p class="text-gray-700 text-base mb-1"><strong>Categoría:</strong> <?php echo htmlspecialchars($libro['categoria']); ?></p>
                        <p class="text-gray-700 text-base mb-1"><strong>Editorial:</strong> <?php echo htmlspecialchars($libro['editorial']); ?></p>
                        <p class="text-gray-700 text-base mb-1"><strong>Fecha de Publicación:</strong> <?php echo htmlspecialchars($libro['fecha_publicacion']); ?></p>
                        <p class="text-gray-700 text-base mb-4"><strong>Estado:</strong> <?php echo htmlspecialchars($libro['estado']); ?></p>
                    </div>
                    <div class="p-4 bg-gray-50">
                        <div class="flex justify-between">
                            <button onclick='openEditModal(<?php echo htmlspecialchars(json_encode($libro)); ?>)' class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Editar</button>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
                                <button type="submit" name="accion" value="eliminar" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Add Book Modal -->
        <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Agregar Libro</h3>
                    <form method="POST" class="mt-2 text-left">
                        <input type="text" name="titulo" placeholder="Título" required class="mt-2 p-2 w-full border rounded">
                        <input type="text" name="autor" placeholder="Autor" required class="mt-2 p-2 w-full border rounded">
                        <input type="date" name="fecha_publicacion" required class="mt-2 p-2 w-full border rounded">
                        <input type="text" name="editorial" placeholder="Editorial" required class="mt-2 p-2 w-full border rounded">
                        <select name="estado" required class="mt-2 p-2 w-full border rounded">
                            <option value="Disponible">Disponible</option>
                            <option value="No disponible">No disponible</option>
                            <option value="Prestamo activo">Préstamo activo</option>
                        </select>
                        <input type="text" name="imagen_url" placeholder="URL de la imagen" required class="mt-2 p-2 w-full border rounded">
                        <select name="categoria" required class="mt-2 p-2 w-full border rounded">
                            <?php foreach ($predefinedCategories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="items-center px-4 py-3">
                            <button type="submit" name="accion" value="agregar" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                Agregar Libro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Book Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Editar Libro</h3>
                    <form method="POST" class="mt-2 text-left">
                        <input type="hidden" id="idEditar" name="idEditar">
                        <input type="text" id="tituloEditar" name="tituloEditar" placeholder="Título" required class="mt-2 p-2 w-full border rounded">
                        <input type="text" id="autorEditar" name="autorEditar" placeholder="Autor" required class="mt-2 p-2 w-full border rounded">
                        <input type="date" id="fecha_publicacionEditar" name="fecha_publicacionEditar" required class="mt-2 p-2 w-full border rounded">
                        <input type="text" id="editorialEditar" name="editorialEditar" placeholder="Editorial" required class="mt-2 p-2 w-full border rounded">
                        <select id="estadoEditar" name="estadoEditar" required class="mt-2 p-2 w-full border rounded">
                            <option value="Disponible">Disponible</option>
                            <option value="No disponible">No disponible</option>
                            <option value="Prestamo activo">Préstamo activo</option>
                        </select>
                        <input type="text" id="imagenEditar" name="imagenEditar" placeholder="URL de la imagen" required class="mt-2 p-2 w-full border rounded">
                        <select id="categoriaEditar" name="categoriaEditar" required class="mt-2 p-2 w-full border rounded">
                            <?php foreach ($predefinedCategories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="items-center px-4 py-3">
                            <button type="submit" name="accion" value="editar" class="px-4 py-2 bg-yellow-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                                Actualizar Libro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Borrowed Books Section -->
        <h2 class="text-2xl font-bold text-gray-900 mt-12 mb-4">Libros Prestados</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Título</th>
                        <th class="py-2 px-4 border-b">Autor</th>
                        <th class="py-2 px-4 border-b">Cliente</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Fecha de Préstamo</th>
                        <th class="py-2 px-4 border-b">Fecha de Devolución</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($librosPrestados as $prestamo): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['titulo']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['autor']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['nombre_cliente']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['email_cliente']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['fecha_devolucion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <script>
            function openModal(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }

            function openEditModal(libro) {
                document.getElementById('idEditar').value = libro.id;
                document.getElementById('tituloEditar').value = libro.titulo;
                document.getElementById('autorEditar').value = libro.autor;
                document.getElementById('fecha_publicacionEditar').value = libro.fecha_publicacion;
                document.getElementById('editorialEditar').value = libro.editorial;
                document.getElementById('estadoEditar').value = libro.estado;
                document.getElementById('imagenEditar').value = libro.imagen_url;
                document.getElementById('categoriaEditar').value = libro.categoria;
                openModal('editModal');
            }

            window.onclick = function(event) {
                if (event.target.classList.contains('fixed')) {
                    event.target.classList.add('hidden');
                }
            }
        </script>
    </div>
</body>
</html>