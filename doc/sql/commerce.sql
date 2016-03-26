CREATE DATABASE Commerce;

USE Commerce;

CREATE TABLE Customer (
  cid INTEGER NOT NULL AUTO_INCREMENT,
  uname VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  PRIMARY KEY(cid)
  CHECK LEN(password) > 5
) ENGINE=INNODB;

CREATE TABLE Staff (
  sid INTEGER NOT NULL,
  FOREIGN KEY(sid) REFERENCES Customer(cid)
) ENGINE=INNODB;

CREATE TABLE Manager (
  mid  INTEGER NOT NULL,
  FOREIGN KEY(mid) REFERENCES Customer(cid)
) ENGINE=INNODB;

CREATE TABLE Item (
  pid INTEGER NOT NULL AUTO_INCREMENT,
  pname VARCHAR(500) NOT NULL,
  ptype VARCHAR(100) NOT NULL,
  cost REAL NOT NULL,
  discount REAL NOT NULL,
  quantity INTEGER NOT NULL,
  PRIMARY KEY(pid),
  CHECK ptype='toy' OR ptype='game' 
  CHECK cost>0,
  CHECK discount < 1 AND discount >=0,
  CHECK quantity >=0
) ENGINE=INNODB;

CREATE TABLE Orders (
  cid INTEGER NOT NULL,
  pid INTEGER NOT NULL,
  status VARCHAR(100) NOT NULL,
  quantity INTEGER NOT NULL,
  odate DATETIME,
  FOREIGN KEY(cid) REFERENCES Customer(cid),
  FOREIGN KEY(pid) REFERENCES Item(pid)
  CHECK cid IN (SELECT cid FROM Customer),
  CHECK pid IN (SELECT pid FROM Item),
  CHECK status="cart",
  CHECK quantity > 0
) ENGINE=INNODB;
