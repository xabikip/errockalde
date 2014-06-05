
CREATE TABLE IF NOT EXISTS bazkide (
    bazkide_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(45) NOT NULL,
    abizena VARCHAR(45),
    goitizena VARCHAR(45),
    emaila VARCHAR(30),
    telefonoa VARCHAR(9),
    user INT(11) NOT NULL,
        FOREIGN KEY(user) REFERENCES user(user_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS talde (
    talde_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(45) NOT NULL,
    web VARCHAR(45),
    emaila VARCHAR(30) NOT NULL,
    telefonoa VARCHAR(20),
    deskribapena VARCHAR(250),
) ENGINE=InnoDB;

ALTER TABLE bazkide ADD user INT(11) NOT NULL;

ALTER TABLE bazkide ADD FOREIGN KEY(user) REFERENCES user(user_id);

ALTER TABLE talde ADD deskribapena VARCHAR(250);

INSERT INTO talde (izena, web, emaila, telefonoa, deskribapena) VALUES
("RO", " www.nireweb.com", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("LOKO FUNJY", " ", "emailanomar@domeinua.com", "654 376 876"),
("KIMERA", " www.kimera.com", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("IXILIK", " ", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("HERSTURA", " ", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("MALENKONIA", " www.nireweb.com", "emailanomar@domeinua.com", "","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("SHARON STONER", " www.nireweb.com", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("HARTZAK", " www.nireweb.com", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("PAPALAGI", " ", "emailanomar@domeinua.com", "654 376 876"),
("drakes", " www.nireweb.com", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("BENEH", " www.nireweb.com", "emailanomar@domeinua.com", "654 376 876"),
("gela berezia", " www.nireweb.com", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),
("tRIKIMAILU", " www.nireweb.com", "emailanomar@domeinua.com", "654 376 876"),
("bitz", " www.nireweb.com", "emailanomar@domeinua.com", "654 376 876","Muy lejos, más allá de las montañas de palabras, alejados de los países de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la semántica, un gran océano de lenguas. Un riachuelo llamado Pons"),