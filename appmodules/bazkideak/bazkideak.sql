
CREATE TABLE IF NOT EXISTS bazkide (
    bazkide_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(45) NOT NULL,
    abizena VARCHAR(45),
    goitizena VARCHAR(45),
    emaila VARCHAR(30),
    telefonoa VARCHAR(9)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS talde (
    talde_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(45) NOT NULL,
    web VARCHAR(45),
    emaila VARCHAR(30) NOT NULL,
    telefonoa VARCHAR(20)
) ENGINE=InnoDB;

ALTER TABLE bazkide ADD user INT(11) NOT NULL;

ALTER TABLE bazkide ADD FOREIGN KEY(user) REFERENCES user(user_id);