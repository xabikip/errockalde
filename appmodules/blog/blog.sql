
CREATE TABLE kategoria (
    kategoria_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
) ENGINE=InnoDB;

CREATE TABLE post (
    post_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titularra VARCHAR(250),
    sortua DATE NOT NULL,
    aldatua DATE NOT NULL
) ENGINE=InnoDB;
