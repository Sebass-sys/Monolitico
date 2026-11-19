-- Base de datos del taller monolítico: Alquiler de vehículos
-- Importar desde phpMyAdmin (XAMPP) o con: mysql -u root < database.sql

CREATE DATABASE IF NOT EXISTS alquiler_vehiculos
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alquiler_vehiculos;

DROP TABLE IF EXISTS alquileres;
DROP TABLE IF EXISTS vehiculos;
DROP TABLE IF EXISTS clientes;

CREATE TABLE clientes (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(100) NOT NULL,
    documento VARCHAR(20)  NOT NULL UNIQUE,
    telefono  VARCHAR(20)  NOT NULL,
    email     VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE vehiculos (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    placa         VARCHAR(10)  NOT NULL UNIQUE,
    marca         VARCHAR(50)  NOT NULL,
    modelo        VARCHAR(50)  NOT NULL,
    anio          SMALLINT     NOT NULL,
    tipo          VARCHAR(30)  NOT NULL,
    tarifa_diaria DECIMAL(10,2) NOT NULL,
    estado        ENUM('disponible','alquilado','mantenimiento') NOT NULL DEFAULT 'disponible'
) ENGINE=InnoDB;

CREATE TABLE alquileres (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id   INT NOT NULL,
    vehiculo_id  INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin    DATE NOT NULL,
    dias         INT NOT NULL,
    total        DECIMAL(12,2) NOT NULL,
    estado       ENUM('activo','finalizado') NOT NULL DEFAULT 'activo',
    FOREIGN KEY (cliente_id)  REFERENCES clientes(id),
    FOREIGN KEY (vehiculo_id) REFERENCES vehiculos(id)
) ENGINE=InnoDB;

-- Datos de ejemplo
INSERT INTO clientes (nombre, documento, telefono, email) VALUES
('Laura Gómez',  '1002345678', '3101234567', 'laura@correo.com'),
('Carlos Pérez', '74321098',   '3159876543', 'carlos@correo.com');

INSERT INTO vehiculos (placa, marca, modelo, anio, tipo, tarifa_diaria, estado) VALUES
('ABC123', 'Chevrolet', 'Spark GT', 2022, 'Automóvil', 120000, 'disponible'),
('DEF456', 'Renault',   'Duster',   2023, 'Camioneta', 220000, 'disponible'),
('GHI789', 'Mazda',     'CX-30',    2024, 'Camioneta', 260000, 'mantenimiento'),
('JKL012', 'Kia',       'Picanto',  2021, 'Automóvil', 100000, 'disponible');
