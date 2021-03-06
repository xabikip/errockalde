
CREATE TABLE kategoria (
    kategoria_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    deitura VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE post (
    post_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titularra VARCHAR(250),
    sortua DATE NOT NULL,
    aldatua DATE NOT NULL,
    urtea VARCHAR(4),
    hilabetea VARCHAR(2),
    slug VARCHAR(300),
    kategoria INT(11),
    FOREIGN KEY (kategoria)
        REFERENCES kategoria (kategoria_id)
        ON DELETE SET NULL,
    user INT(11) NOT NULL,
        FOREIGN KEY(user) REFERENCES user(user_id)
) ENGINE=InnoDB;
