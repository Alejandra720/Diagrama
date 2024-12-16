<?php

declare(strict_types=1);

/**
 * Interface1: Define operaciones comunes para la administración de libros
 */
interface Interface1 {
    public function agregarLibro(string $titulo, string $autor, string $categoria, int $isbn): bool;
    public function eliminarLibro(int $isbn): bool;
    public function buscarLibro(string $criterio): array;
}

/**
 * Interface2: Define operaciones comunes para los préstamos de libros
 */
interface Interface2 {
    public function registrarPrestamo(int $isbn, string $usuario): bool;
    public function actualizarEstadoLibro(int $isbn, string $estado): void;
}

/**
 * Clase base Persona: Representa a un usuario básico
 */
class Persona {
    protected string $nombre;
    protected string $apellido;
    protected string $rol;
    protected string $usuario;
    private string $password;

    public function __construct(string $nombre, string $apellido, string $usuario, string $password, string $rol) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->usuario = $usuario;
        $this->password = $password;
        $this->rol = $rol;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getUser(): string {
        return $this->usuario;
    }

    public function registrar(): string {
        return "Usuario {$this->nombre} registrado correctamente.";
    }
}

/**
 * Clase Usuario: Hereda de Persona y gestiona préstamos
 */
class Usuario extends Persona implements Interface2 {
    private array $prestamos = [];

    public function registrarPrestamo(int $isbn, string $usuario): bool {
        $this->prestamos[] = ["ISBN" => $isbn, "Usuario" => $usuario, "Fecha" => date('Y-m-d')];
        echo "Préstamo registrado con éxito.\n";
        return true;
    }

    public function actualizarEstadoLibro(int $isbn, string $estado): void {
        echo "El libro con ISBN {$isbn} ha cambiado su estado a {$estado}.\n";
    }
}

/**
 * Clase Empleado: Hereda de Persona y maneja la gestión de libros
 */
class Empleado extends Persona implements Interface1 {
    private array $libros = [];

    public function agregarLibro(string $titulo, string $autor, string $categoria, int $isbn): bool {
        $this->libros[$isbn] = ["Titulo" => $titulo, "Autor" => $autor, "Categoria" => $categoria];
        echo "Libro '{$titulo}' agregado correctamente.\n";
        return true;
    }

    public function eliminarLibro(int $isbn): bool {
        if (isset($this->libros[$isbn])) {
            unset($this->libros[$isbn]);
            echo "Libro eliminado correctamente.\n";
            return true;
        }
        return false;
    }

    public function buscarLibro(string $criterio): array {
        return array_filter($this->libros, function($libro) use ($criterio) {
            return stripos($libro['Titulo'], $criterio) !== false || stripos($libro['Autor'], $criterio) !== false;
        });
    }
}

/**
 * Clase Biblioteca: Gestiona libros y préstamos
 */
class Biblioteca {
    private array $libros = [];
    private array $prestamos = [];

    public function agregarLibro(string $titulo, string $autor, string $categoria, int $isbn): void {
        $this->libros[$isbn] = ["Titulo" => $titulo, "Autor" => $autor, "Categoria" => $categoria];
        echo "Libro '{$titulo}' agregado a la biblioteca.\n";
    }

    public function registrarPrestamo(int $isbn, string $usuario): void {
        if (isset($this->libros[$isbn])) {
            $this->prestamos[] = ["ISBN" => $isbn, "Usuario" => $usuario, "Fecha" => date('Y-m-d')];
            echo "Préstamo registrado para el libro con ISBN {$isbn}.\n";
        } else {
            echo "Libro no encontrado.\n";
        }
    }

    public function buscarLibro(string $titulo): ?array {
        foreach ($this->libros as $isbn => $libro) {
            if ($libro['Titulo'] === $titulo) {
                return ["ISBN" => $isbn, "Libro" => $libro];
            }
        }
        return null;
    }
}

/**
 * Ejemplo de uso del sistema de biblioteca
 */
$empleado = new Empleado("Juan", "Perez", "juanp", "1234", "Empleado");
$empleado->agregarLibro("PHP Avanzado", "Carlos Ruiz", "Programación", 123456);
$empleado->agregarLibro("Diseño de Bases de Datos", "Maria Lopez", "Bases de Datos", 789012);

$resultado = $empleado->buscarLibro("PHP");
print_r($resultado);

$usuario = new Usuario("Luis", "Martinez", "luism", "5678", "Usuario");
$usuario->registrarPrestamo(123456, "luism");
$usuario->actualizarEstadoLibro(123456, "Prestado");

$biblioteca = new Biblioteca();
$biblioteca->agregarLibro("Algoritmos", "John Smith", "Ciencia", 345678);
$biblioteca->registrarPrestamo(345678, "luism");

?>
