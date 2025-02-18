<?php
class Persona {
    protected $id_Persona;
    protected $nombre;
    protected $apellido;
    protected $correo;
    protected $contrasena;
    protected $telefono;

    // Constructor
    public function __construct($id, $nombre, $apellido, $correo, $contrasena, $telefono) {
        $this->id_Persona = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->telefono = $telefono;
    }

    // Métodos Get
    public function getIdPersona() {
        return $this->id_Persona;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    // Métodos Set
    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
}

// Clase Administrador que extiende Persona
class Administrador extends Persona {

    // Constructor que llama al de la clase padre
    public function __construct($id, $nombre, $apellido, $correo, $contrasena, $telefono) {
        parent::__construct($id, $nombre, $apellido, $correo, $contrasena, $telefono);
    }
    public function registrarLibro() {
        return "Libro registrado por Administrador";
    }
}

// Clase Administrador que extiende Persona
class Cliente extends Persona {

    // Constructor que llama al de la clase padre
    public function __construct($id, $nombre, $apellido, $correo, $contrasena, $telefono) {
        parent::__construct($id, $nombre, $apellido, $correo, $contrasena, $telefono);
    }
    public function verLibros() {
        return "Libros";
    }
}


// // Crear una instancia de Administrador
// $admin = new Administrador(1, "Juan", "Pérez", "juan@example.com", "123456", "555-1234");

// // Acceder a los datos de manera controlada
// echo "Nombre: " . $admin->getNombre() . "<br>";
// echo "Correo: " . $admin->getCorreo() . "<br>";
// echo "Nuevo Teléfono: " . $admin->getTelefono() . "<br>";

// // Llamar al método específico de Administrador
// echo $admin->registrarLibro();

// // Crear una instancia de Cliente
// $cliente = new Cliente(2, "María", "López", "maria@example.com", "abcdef", "555-5678");

// // Llamar al método específico de Cliente
// echo "Cliente viendo libros: " . $cliente->verLibros();

?>
