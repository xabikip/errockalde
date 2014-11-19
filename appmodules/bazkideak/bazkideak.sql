CREATE TABLE IF NOT EXISTS talde (
    talde_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(45) NOT NULL,
    web VARCHAR(45),
    emaila VARCHAR(30) NOT NULL,
    telefonoa VARCHAR(20),
    customurl VARCHAR(40),
    deskribapena VARCHAR(250)
) ENGINE=InnoDB;

CREATE TABLE diskoa (
    diskoa_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(60) NOT NULL,
    data VARCHAR(25),
    iraupena VARCHAR(20),
    abestiak VARCHAR(250),
    talde INT(11) NOT NULL,
        INDEX (talde),
        FOREIGN KEY (talde) REFERENCES talde (talde_id) ON DELETE CASCADE
) ENGINE=InnoDB;