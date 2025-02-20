
# Sistema de Biblioteca

Este proyecto es un sistema de gestión de libros para una biblioteca, con dos roles principales: **Administrador** y **Cliente**. Los **Administradores** pueden gestionar los libros (agregar, editar, eliminar) y ver una lista de libros prestados. Los **Clientes** pueden buscar, filtrar libros, y pedir prestado solo si están disponibles.

## Funcionalidades

### 1. **Para el Cliente**
   - **Ver Libros**: El cliente puede ver la lista de libros disponibles.
   - **Buscar y Filtrar**: Puede buscar libros por título, autor y categoría, y filtrar los resultados para encontrar más fácilmente lo que necesita.
   - **Pedir Prestado**: Solo puede pedir prestado un libro si su **estado es "Disponible"**. Si el libro no está disponible, el botón de "Pedir Prestado" se deshabilitará.
   - **Pedir Prestado (Detalles)**:
     - Al pedir un libro prestado, se guardará la **fecha de préstamo** (fecha actual) y la **fecha de devolución** (1 mes después de la fecha de préstamo).
     - El estado del libro se actualizará a **"Préstamo activo"**.
     - El libro se añadirá a la lista de libros prestados con los detalles del cliente (nombre y correo).
   - **Ver Libros Prestados**: El cliente puede ver los libros que ha pedido prestados, junto con su fecha de préstamo y de devolución.
   - **Devolver Libro**: El cliente podrá devolver los libros que haya pedido prestados, y su estado se actualizará a **"Disponible"** nuevamente.
   
### 2. **Para el Administrador**
   - **Ver, Agregar, Editar y Eliminar Libros**: El administrador tiene total control sobre los libros, pudiendo agregar nuevos, editar detalles de los existentes o eliminarlos.
   - **Buscar y Filtrar Libros**: El administrador puede buscar y filtrar libros por título, autor y categoría.
   - **Ver Libros Prestados**: El administrador puede ver la lista completa de los libros que están prestados, junto con los detalles del cliente (nombre, correo).
   - **Lista de Libros Prestados**: El administrador puede visualizar al final de la página la lista de libros que han sido prestados, incluyendo los datos del cliente que ha pedido el libro.
   
### 3. **Credenciales para Iniciar Sesión**
   Puedes probar el sistema usando las siguientes credenciales predefinidas:

   - **Administrador**:
     - **Correo**: `juan@example.com`
     - **Contraseña**: `123456`
   - **Cliente**:
     - **Correo**: `maria@example.com`
     - **Contraseña**: `abcdef`

### 4. **Diagrama del Flujo**
   El flujo de la aplicación es el siguiente:
   1. El cliente inicia sesión usando las credenciales proporcionadas.
   2. El cliente puede ver la lista de libros, buscar, filtrar, y pedir prestado un libro disponible.
   3. Si el cliente pide un libro, se guarda la fecha de préstamo y la fecha de devolución.
   4. El cliente puede ver los libros que ha prestado y devolverlos.
   5. El administrador puede gestionar los libros, ver los libros prestados, y ver detalles de los préstamos.

---

## Instalación

### Requisitos previos:
1. **PHP** (>= 7.4).
2. **XAMPP** o cualquier servidor PHP para ejecutar el código localmente.
3. Asegúrate de tener configurado un servidor local (por ejemplo, Apache).

### Pasos para la instalación:
1. Descarga o clona el repositorio.
2. Coloca los archivos en tu directorio de servidor local (por ejemplo, dentro de `htdocs` si usas XAMPP).
3. Abre tu navegador y navega a `http://localhost/[carpeta_del_proyecto]`.
4. Para iniciar sesión como Administrador o Cliente, utiliza las credenciales predefinidas.

### Archivos principales:
- `validar.php`: Contiene la lógica para validar los datos de inicio de sesión de **Administrador** y **Cliente**.
- `dashboard_admin.php`: Interfaz del administrador para gestionar los libros y ver los libros prestados.
- `dashboardCliente.php`: Interfaz del cliente donde puede ver, buscar, y pedir prestado libros.
- `persona.php`: Contiene las clases base `Persona`, `Administrador` y `Cliente` con la información de usuario.
- `libros.json`: Archivo donde se almacenan los libros de la biblioteca.
- `prestamos.json`: Archivo donde se almacenan los registros de los libros prestados.

---

## Cómo Funciona

1. **Iniciar sesión:**
   - Los usuarios pueden iniciar sesión como **Administrador** o **Cliente** utilizando las credenciales predefinidas.
   
2. **Administrador:**
   - Al iniciar sesión, el **Administrador** tiene acceso a todas las funcionalidades de gestión de libros: puede agregar, eliminar, editar, y buscar libros.
   - Además, puede ver la lista de libros prestados, junto con los detalles del cliente que ha tomado el libro.

3. **Cliente:**
   - Al iniciar sesión, el **Cliente** solo puede ver los libros disponibles, buscar y filtrar libros, y pedir prestado un libro si su estado es **"Disponible"**.
   - Al pedir prestado un libro, se actualizará el estado del libro a **"Préstamo activo"**, y se añadirá el registro de préstamo.
   - El cliente podrá ver los libros que ha prestado y devolverlos en cualquier momento, actualizando el estado del libro a **"Disponible"**.

4. **Flujo de préstamos:**
   - Cuando el cliente pide un libro prestado, la **fecha de préstamo** será la fecha actual y la **fecha de devolución** será un mes después de la fecha de préstamo.
   - El libro prestado aparecerá en la lista de libros prestados tanto para el administrador como para el cliente.

---

## Tecnologías Utilizadas

- **PHP**: Lenguaje de programación para la lógica de negocio.
- **HTML/CSS**: Estructura y estilo de las páginas.
- **JSON**: Almacenamiento de datos de libros y préstamos.
- **JavaScript**: Para algunas funcionalidades como deshabilitar el botón de "Pedir Prestado" cuando el libro no está disponible.

---

