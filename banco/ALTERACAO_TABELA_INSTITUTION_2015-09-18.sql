CREATE TABLE institution
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    corporate_name VARCHAR(100) NOT NULL,
    fancy_name VARCHAR(100),
    cnpj VARCHAR(14) NOT NULL,
    branch VARCHAR(50) NOT NULL,
    about VARCHAR(500) NOT NULL,
    user_id INT NOT NULL,
    picture VARCHAR(200) DEFAULT '/img/pictures/sem-imagem.jpg' NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX fk_institution_user_idx ON institution (user_id);
