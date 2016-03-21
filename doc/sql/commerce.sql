CREATE DATABASE Commerce;

USE Commerce;

CREATE TABLE Customer (
       cid	INTEGER NOT NULL AUTO_INCREMENT,
       uname	VARCHAR(50) NOT NULL,
       password VARCHAR(100) NOT NULL,
       email 	VARCHAR(100) NOT NULL,
       PRIMARY KEY(cid)
) ENGINE=INNODB;

CREATE TABLE Staff (
       sid	INTEGER NOT NULL AUTO_INCREMENT,
       uname VARCHAR(100) NOT NULL,
       password VARCHAR(100) NOT NULL,
       PRIMARY KEY(sid)
) ENGINE=INNODB;

CREATE TABLE Manager (
       mid	INTEGER NOT NULL AUTO_INCREMENT,
       uname VARCHAR(100) NOT NULL,
       password VARCHAR(100) NOT NULL,
       PRIMARY KEY(mid)
) ENGINE=INNODB;

CREATE TABLE Item (
       pid	INTEGER NOT NULL AUTO_INCREMENT,
       pname VARCHAR(500) NOT NULL,
       type VARCHAR(100) NOT NULL,
       cost REAL NOT NULL,
       discount REAL NOT NULL,
       der_price REAL NOT NULL,
       quantity INTEGER NOT NULL,
       PRIMARY KEY(pid)
) ENGINE=INNODB;

CREATE TABLE Orders (
       cid	INTEGER NOT NULL,
       pid 	INTEGER NOT NULL,
       status	VARCHAR(50) NOT NULL,
       quantity INTEGER NOT NULL,
       odate DATETIME
       FOREIGN KEY(cid) REFERENCES Customer(cid),
       FOREIGN KEY(pid) REFERENCES Item(pid)
) ENGINE=INNODB;
