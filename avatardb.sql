DROP DATABASE IF EXISTS avatar;
CREATE DATABASE avatar;
USE avatar;

CREATE TABLE user(
    userid INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    useremail VARCHAR(50) NOT NULL,
    userpassword VARCHAR(30) NOT NULL
)ENGINE = InnoDB;

INSERT INTO user(username, useremail, userpassword) VALUES('admin', 'admin@host.com', 'admin')


CREATE TABLE(
    avatarid INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    avatarname VARCHAR(30) NOT NULL,
    avatarscale DECIMAL NOT NULL,
    avatarfile VARCHAR(100) NOT NULL
)ENGINE = InnoDB;


CREATE TABLE useravatar(
    userid,
    avatarid
)ENGINE = InnoDB;
