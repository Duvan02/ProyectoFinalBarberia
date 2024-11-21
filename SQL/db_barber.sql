CREATE TABLE `horarios_estilistas`(
    `idEstilista` INT NOT NULL,
    `idHorario` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `diaSemana` VARCHAR(10) NOT NULL,
    `horaSalida` TIME NOT NULL,
    `horaIngreso` TIME NOT NULL
);
CREATE TABLE `estilistas`(
    `foto` VARCHAR(200) NOT NULL,
    `idEstilista` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombres` VARCHAR(100) NOT NULL
);
CREATE TABLE `usuarios`(
    `idUsuario` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(100) NOT NULL,
    `telefono` VARCHAR(20) NOT NULL,
    `password` VARCHAR(100) NOT NULL,
    `foto` LONGTEXT NULL,
    `nombres` VARCHAR(200) NOT NULL
);
CREATE TABLE `administradores`(
    `idAdministrador` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `password` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL
);
CREATE TABLE `reservas`(
    `idEstilista` INT NOT NULL,
    `horaInicio` TIME NOT NULL,
    `idServicio` INT NOT NULL,
    `estado` VARCHAR(50) NOT NULL,
    `idReserva` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `fechaCreacionReserva` DATETIME NOT NULL,
    `fechaReserva` DATE NOT NULL,
    `horaFin` TIME NOT NULL,
    `idUsuario` INT NOT NULL
);
CREATE TABLE `servicios`(
    `imagen` VARCHAR(200) NOT NULL,
    `idServicio` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `descripcion` VARCHAR(200) NOT NULL,
    `precio` FLOAT(53) NOT NULL,
    `tiempoDuracion` INT NOT NULL
);

INSERT INTO `administradores`(nombre,password,email) VALUES('Administrador','1234','admin')