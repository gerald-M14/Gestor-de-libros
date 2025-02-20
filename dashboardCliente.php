<?php
session_start();
if (!isset($_SESSION["cliente"])) {
    header("Location: index.php");
    exit();
}

require 'libros.php';
$cliente = new Cliente();

// Predefined categories
$predefinedCategories = [
    "Ficción", "No ficción", "Misterio", "Ciencia ficción",
    "Fantasía", "Romance", "Thriller", "Biografía"
];

// Handle book borrowing and returning
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST["accion"] ?? '';

    if ($accion == "prestar") {
        $mensaje = $cliente->prestarLibro($_POST["id"], $_SESSION["cliente"], $_SESSION["email"]);
        echo "<script>alert('$mensaje');</script>";
    } elseif ($accion == "devolver") {
        $mensaje = $cliente->devolverLibro($_POST["id"], $_SESSION["email"]);
        echo "<script>alert('$mensaje');</script>";
    }
}

// Handle search and filters
$searchTerm = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$authorFilter = $_GET['author'] ?? '';

$libros = $cliente->buscarLibros($searchTerm, $categoryFilter, $authorFilter);
$librosPrestados = $cliente->obtenerLibrosPrestados($_SESSION["email"]);

// Get unique authors for filter dropdown
$authors = array_unique(array_column($libros, 'autor'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Bienvenido, <?php echo $_SESSION["cliente"]; ?></h2>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Cerrar Sesión</a>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Catálogo de Libros</h1>

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
                        <form method="POST" class="inline">
                            <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
                            <button type="submit" name="accion" value="prestar" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full" <?php echo $libro['estado'] !== 'Disponible' ? 'disabled' : ''; ?>>
                                <?php echo $libro['estado'] === 'Disponible' ? 'Pedir Prestado' : 'No Disponible'; ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Borrowed Books Section -->
        <h2 class="text-2xl font-bold text-gray-900 mt-12 mb-4">Mis Libros Prestados</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Título</th>
                        <th class="py-2 px-4 border-b">Autor</th>
                        <th class="py-2 px-4 border-b">Fecha de Préstamo</th>
                        <th class="py-2 px-4 border-b">Fecha de Devolución</th>
                        <th class="py-2 px-4 border-b">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($librosPrestados as $prestamo): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['titulo']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['autor']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($prestamo['fecha_devolucion']); ?></td>
                            <td class="py-2 px-4 border-b">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo $prestamo['id_libro']; ?>">
                                    <button type="submit" name="accion" value="devolver" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded">
                                        Devolver
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>