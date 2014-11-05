CREATE TABLE ekitaldimota (
    ekitaldimota_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    deitura VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE lekua (
    lekua_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(45) NOT NULL,
    helbidea VARCHAR(125),
    herria VARCHAR(35)
) ENGINE=InnoDB;

CREATE TABLE ekitaldia (
    ekitaldia_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ekitaldimota INT(11),
    FOREIGN KEY (ekitaldimota)
        REFERENCES ekitaldimota (ekitaldimota_id)
        ON DELETE SET NULL,
    izena VARCHAR(45) NOT NULL,
    data DATE NOT NULL,
    ordua TIME NOT NULL,
    deskribapena VARCHAR(250),
    lekua INT(11) NOT NULL,
    FOREIGN KEY (lekua)
        REFERENCES lekua (lekua_id)
) ENGINE=InnoDB;