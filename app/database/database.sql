CREATE TABLE `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(50) NOT NULL,
  `apellido1` varchar(100) NOT NULL,
  `apellido2` varchar(100),
  `email` varchar(150) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `libros` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` INT(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `autor` varchar(100),
  `genero` varchar(50) NOT NULL,
  `anio` int(11),
  `caratula` varchar(255) DEFAULT NULL,

  FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
 
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `prestamos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion` date,
  `descripcion` text,
  `id_usuario` INT NOT NULL,
  `id_libro` INT NOT NULL,
  
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
  FOREIGN KEY (id_libro) REFERENCES libros(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
