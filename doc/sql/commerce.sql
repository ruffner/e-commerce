CREATE DATABASE Commerce;

USE Commerce;

CREATE TABLE Customer (
  cid INTEGER NOT NULL AUTO_INCREMENT,
  uname VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  PRIMARY KEY(cid),
  CHECK (LEN(password) > 5)
) ENGINE=INNODB;

CREATE TABLE Staff (
  sid INTEGER NOT NULL,
  FOREIGN KEY(sid) REFERENCES Customer(cid)
) ENGINE=INNODB;

CREATE TABLE Manager (
  mid  INTEGER NOT NULL,
  FOREIGN KEY(mid) REFERENCES Customer(cid)
) ENGINE=INNODB;

CREATE TABLE Types (
  tid INTEGER NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  subtype VARCHAR(100),
  about VARCHAR(1000),
  PRIMARY KEY(tid)
) ENGINE=INNODB;

CREATE TABLE Item (
  pid INTEGER NOT NULL AUTO_INCREMENT,
  pname VARCHAR(500) NOT NULL,
  ptype VARCHAR(100) NOT NULL,
  psubtype VARCHAR(100),
  cost REAL NOT NULL,
  discount REAL NOT NULL,
  quantity INTEGER NOT NULL,
  image VARCHAR(100),
  info VARCHAR(1000),
  PRIMARY KEY(pid),
  CHECK ((ptype IN (SELECT name FROM Types)) AND (psubtype IN (SELECT subtype FROM Types))),
  CHECK (cost > 0),
  CHECK (discount < 1 AND discount >=0),
  CHECK (quantity >=0)
) ENGINE=INNODB;

CREATE TRIGGER discountTrig
AFTER UPDATE OF discount ON Item
REFERENCING NEW ROW AS n, OLD ROW AS o
WHEN (discount >=1 OR discount <0)
SET n.discount=o.discount;

CREATE TRIGGER itemQuantityTrig
AFTER UPDATE OF quantity ON Iteam
REFERECING NEW ROW AS n
WHEN (n.quantity < 0)
SET n.quantity=0;

CREATE TABLE Orders (
  cid INTEGER NOT NULL,
  pid INTEGER NOT NULL,
  status VARCHAR(100) NOT NULL,
  quantity INTEGER NOT NULL,
  odate DATETIME,
  FOREIGN KEY(cid) REFERENCES Customer(cid),
  FOREIGN KEY(pid) REFERENCES Item(pid),
  CHECK (cid IN (SELECT cid FROM Customer)),
  CHECK (pid IN (SELECT pid FROM Item)),
  CHECK (status="cart"),
  CHECK (quantity > 0)
) ENGINE=INNODB;

CREATE TRIGGER statusTrig
AFTER UPDATE OF status ON Orders
REFERENCING NEW ROW AS n, OLD ROW AS o
WHEN NOT (n.status="cart" OR n.status="pending" OR n.status="shipped")
SET n.status=o.status;

CREATE TRIGGER quantityTrig
AFTER UPDATE OF quantity ON Orders
REFERENCING NEW ROW AS n
WHEN (n.quantity <0)
SET n.quantity=0;
