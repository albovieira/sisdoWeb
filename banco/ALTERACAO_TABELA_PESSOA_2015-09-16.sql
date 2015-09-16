CREATE TABLE person
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    sex CHAR(1) NOT NULL,
    birth_date DATE,
    user_id INT NOT NULL,
    picture VARCHAR(200) DEFAULT '/img/sem-imagem/sem-imagem-avatar.jpg' NOT NULL,
    about_me VARCHAR(500),
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX fk_person_user1_idx ON person (user_id);
