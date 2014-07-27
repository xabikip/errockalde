
CREATE TABLE kategoria (
    kategoria_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    deitura VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE post (
    post_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titularra VARCHAR(250),
    sortua DATE NOT NULL,
    aldatua DATE NOT NULL,
    kategoria INT(11),
    FOREIGN KEY (kategoria)
        REFERENCES kategoria (kategoria_id)
        ON DELETE SET NULL
) ENGINE=InnoDB;
