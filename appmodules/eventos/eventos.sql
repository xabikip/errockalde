CREATE TABLE ekitaldimota (
    tipoevento_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    denominacion VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE lekua (
    lugar_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(35) NOT NULL,
    direccion VARCHAR(125),
    localidad VARCHAR(35)
) ENGINE=InnoDB;

CREATE TABLE ekitaldia (
    evento_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipoevento INT(11),
    FOREIGN KEY (tipoevento)
        REFERENCES tipoevento (tipoevento_id)
        ON DELETE SET NULL,
    nombre VARCHAR(40) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    lugar INT(11) NOT NULL,
    FOREIGN KEY (lugar)
        REFERENCES lugar (lugar_id)
) ENGINE=InnoDB;
