CREATE DATABASE vehiculos;

USE vehiculos;

CREATE TABLE registros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patente VARCHAR(20) NOT NULL,
    chofer VARCHAR(100) NOT NULL,
    kilometraje INT NOT NULL,
    tipo_movimiento ENUM('Entrada', 'Salida') NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL
);
