CREATE DATABASE IF NOT EXISTS avatar;

USE avatar;

DROP TABLE IF EXISTS user;

CREATE TABLE IF NOT EXISTS user(
    userid INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    useremail VARCHAR(50) NOT NULL,
    userpassword VARCHAR(30) NOT NULL
)ENGINE = InnoDB;

INSERT INTO user(username, useremail, userpassword) VALUES('admin', 'admin@host.com', 'admin')
