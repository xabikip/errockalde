DROP DATABASE IF EXISTS europio_testing;
CREATE DATABASE IF NOT EXISTS europio_testing;
USE europio_testing;


DROP TABLE IF EXISTS stfake;
CREATE TABLE stfake (
    stfake_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , name VARCHAR(5)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS user;
CREATE TABLE user (
    user_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , name VARCHAR(5)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS userstfake;
CREATE TABLE userstfake (
    connector_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , compuesto INT(11) NOT NULL
    , INDEX (compuesto)
    , FOREIGN KEY (compuesto)
        REFERENCES stfake (stfake_id)
        ON DELETE CASCADE
    , compositor INT(11) NOT NULL
    , INDEX (compositor)
    , FOREIGN KEY (compositor)
        REFERENCES user (user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


DROP TABLE IF EXISTS foo;
CREATE TABLE foo (
    foo_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , name VARCHAR(5)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS foostfake;
CREATE TABLE foostfake (
    connector_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , compuesto INT(11) NOT NULL
    , INDEX (compuesto)
    , FOREIGN KEY (compuesto)
        REFERENCES stfake (stfake_id)
        ON DELETE CASCADE
    , compositor INT(11) NOT NULL
    , INDEX (compositor)
    , FOREIGN KEY (compositor)
        REFERENCES foo (foo_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


DROP TABLE IF EXISTS compositor;
CREATE TABLE compositor (
    compositor_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , name VARCHAR(15)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS compuesto;
CREATE TABLE compuesto (
    compuesto_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , simple VARCHAR(15)
    , foo INT(11)
    , INDEX (foo)
    , FOREIGN KEY (foo)
        REFERENCES foo (foo_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;



DROP TABLE IF EXISTS exclusivo;
CREATE TABLE exclusivo (
    exclusivo_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , name VARCHAR(15)
    , compuesto INT(11)
    , INDEX (compuesto)
    , FOREIGN KEY (compuesto)
        REFERENCES compuesto (compuesto_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


DROP TABLE IF EXISTS compositorcompuesto;
CREATE TABLE compositorcompuesto (
    connector_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
    , compuesto INT(11) NOT NULL
    , INDEX (compuesto)
    , FOREIGN KEY (compuesto)
        REFERENCES compuesto (compuesto_id)
        ON DELETE CASCADE
    , compositor INT(11) NOT NULL
    , INDEX (compositor)
    , FOREIGN KEY (compositor)
        REFERENCES compositor (compositor_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


INSERT INTO stfake (stfake_id, name) VALUES (1, 'foo');
INSERT INTO user (user_id, name) VALUES (1, 'bar1'), (2, 'bar2'), (3, 'bar3');
INSERT INTO foo (foo_id, name) VALUES (1, 'lala1'), (2, 'lala2');
INSERT INTO userstfake (compuesto, compositor) VALUES (1, 1), (1, 2), (1, 3);
INSERT INTO foostfake (compuesto, compositor) VALUES (1, 1), (1, 2);
INSERT INTO compositor (compositor_id, name) VALUES (1, 'compositor1'), (2, 'compositor2');
INSERT INTO compuesto (compuesto_id, simple, foo) 
     VALUES (1, 'compuesto1', 1), (2, 'compuesto2', 2);
INSERT INTO exclusivo (exclusivo_id, name, compuesto) 
     VALUES (1, 'exclusivo1', 1), (2, 'exclusivo2', 1), (3, 'exclusivo3', 1);
INSERT INTO compositorcompuesto (compuesto, compositor) VALUES (1, 1), (1, 2);



