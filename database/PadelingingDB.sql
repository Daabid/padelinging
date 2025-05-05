DROP DATABASE padelinging;
CREATE DATABASE padelinging;
use padelinging;

CREATE TABLE Usuario (
  DNI VARCHAR(9) PRIMARY KEY NOT NULL,
  Nombre VARCHAR(15) NOT NULL,
  Apellido VARCHAR(20) NOT NULL,
  Correo VARCHAR(25) NOT NULL,
  FechaNacimiento DATE NOT NULL,
  Contrasena VARCHAR(255) NOT NULL,
  Rol VARCHAR(10) NOT NULL
);

CREATE TABLE Inventario (
  IDProducto VARCHAR(20) PRIMARY KEY NOT NULL,
  Tipo VARCHAR(20) NOT NULL,
  Precio DECIMAL(8,2) NOT NULL,
  Estado VARCHAR(10) NOT NULL,
  URL VARCHAR(255)
);

CREATE TABLE Pista (
  IDPista VARCHAR(20) PRIMARY KEY NOT NULL,
  Tipo VARCHAR(15) NOT NULL,
  Superficie DECIMAL(8,2) NOT NULL,
  Estado VARCHAR(15) NOT NULL,
  Precio DECIMAL(8,2) NOT NULL
);

CREATE TABLE Alquiler (
  ID VARCHAR(20) PRIMARY KEY NOT NULL,
  Usuario VARCHAR(9) NOT NULL,
  FInicio DATETIME NOT NULL,
  FFinal DATETIME NOT NULL,
  Precio DECIMAL(8,2),
  FOREIGN KEY (Usuario) REFERENCES Usuario(DNI)
);

CREATE TABLE Reserva (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  Usuario VARCHAR(9) NOT NULL,
  Pista VARCHAR(20) NOT NULL,
  Alquiler VARCHAR(20),
  FInicio DATETIME NOT NULL,
  FFinal DATETIME NOT NULL,
  FOREIGN KEY (Usuario) REFERENCES Usuario(DNI),
  FOREIGN KEY (Pista) REFERENCES Pista(IDPista),
  FOREIGN KEY (Alquiler) REFERENCES Alquiler(ID)
);

CREATE TABLE Carrito (
  ID VARCHAR(20) PRIMARY KEY NOT NULL,
  Usuario VARCHAR(9) NOT NULL,
  Precio DECIMAL(8,2) NOT NULL,
  Fecha DATETIME NOT NULL,
  FOREIGN KEY (Usuario) REFERENCES Usuario(DNI)
);

CREATE TABLE Venta (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  Producto VARCHAR(20) NOT NULL,
  Carrito VARCHAR(20) NOT NULL,
  Precio DECIMAL(8,2),
  FOREIGN KEY (Producto) REFERENCES Inventario(IDProducto),
  FOREIGN KEY (Carrito) REFERENCES Carrito(ID)
);

CREATE TABLE AProducto (
  Producto VARCHAR(20) NOT NULL,
  Alquiler VARCHAR(20) NOT NULL,
  PRIMARY KEY (Producto, Alquiler),
  FOREIGN KEY (Producto) REFERENCES Inventario(IDProducto),
  FOREIGN KEY (Alquiler) REFERENCES Alquiler(ID)
);

CREATE TABLE Incidencia (
  ID VARCHAR(20) PRIMARY KEY NOT NULL,
  Reserva INT,
  Descripcion VARCHAR(255) NOT NULL,
  Estado VARCHAR(20) NOT NULL,
  FOREIGN KEY (Reserva) REFERENCES Reserva(ID)
);

CREATE TABLE Tiempo (
  Fecha DATE PRIMARY KEY NOT NULL,
  Tiempo VARCHAR(20)
);

INSERT INTO Usuario (DNI, Nombre, Apellido, Correo, FechaNacimiento, Contrasena, Rol) VALUES 
('12345678A', 'Admin', 'Padel', 'admin@padel.com', '1990-01-01', 'root', 'admin'),
('87654321B', 'Usuario', 'Normal', 'usuario@padel.com', '1995-05-05', 'padelinging', 'usuario');

INSERT INTO Inventario (IDProducto, Tipo, Precio, Estado, URL) VALUES
-- Pelotas
('PEL-001', 'Compra', 5.99, 'disponible', NULL),
('PEL-002', 'Compra', 7.49, 'disponible', NULL),
('PEL-003', 'Alquiler', 2.00, 'disponible', NULL),
-- Palas para comprar
('PALA-COMP-NIÑO', 'Compra', 45.00, 'disponible', NULL),
('PALA-COMP-ADULTO', 'Compra', 79.99, 'disponible', NULL),
-- Palas para alquilar
('PALA-ALQ-NIÑO', 'Alquiler', 3.00, 'disponible', NULL),
('PALA-ALQ-ADULTO', 'Alquiler', 5.00, 'disponible', NULL);

INSERT INTO Pista (IDPista, Tipo, Superficie, Estado, Precio) VALUES
-- Pistas exteriores
('PISTA-EXT-01', 'Exterior', 100.00, 'disponible', 10.00),
('PISTA-EXT-02', 'Exterior', 100.00, 'disponible', 10.00),
('PISTA-EXT-03', 'Exterior', 90.00, 'mantenimiento', 8.00),
('PISTA-EXT-04', 'Exterior', 95.00, 'disponible', 9.00),
-- Pistas cubiertas
('PISTA-CUB-01', 'Cubierta', 100.00, 'disponible', 15.00),
('PISTA-CUB-02', 'Cubierta', 100.00, 'disponible', 15.00);