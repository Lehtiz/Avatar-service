DROP DATABASE IF EXISTS avatar;
CREATE DATABASE avatar;
USE avatar;

CREATE TABLE user(
    userid INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    useremail VARCHAR(50) NOT NULL,
    userpassword VARCHAR(256) NOT NULL
)ENGINE = InnoDB;

/*admin::admin*/
INSERT INTO user(username, useremail, userpassword) VALUES('admin', 'admin@host.com', '$1$FSL.ycce$TYNM1ZN4MY/vZPNi42Zoj0');


CREATE TABLE avatar(
    avatarid INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    avatarname VARCHAR(30) NOT NULL,
    avatarscale DECIMAL(10,3) NOT NULL,
    avatarfile VARCHAR(100) NOT NULL
)ENGINE = InnoDB;

INSERT INTO avatar(avatarname, avatarscale, avatarfile) VALUES('first', '0.1', 'avatar1.dae');
INSERT INTO avatar(avatarname, avatarscale, avatarfile) VALUES('second', '0.1', 'avatar2.dae');
INSERT INTO avatar(avatarname, avatarscale, avatarfile) VALUES('third', '0.1', 'avatar3.dae');

/*
CREATE TABLE useravatar(
    userid,
    avatarid
)ENGINE = InnoDB;
*/
