--  Module: ECAD
--  Database Script for setting up the MySQL database
--  required for ECAD Assignment.
--  Creation Date: 29 Dec 2021.

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- Edit 27/01/22 create and use db "donut"
CREATE DATABASE IF NOT EXISTS `donut` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `donut`;

-- Delete tables before creating
DROP TABLE IF EXISTS GST;
DROP TABLE IF EXISTS Ranking;
DROP TABLE IF EXISTS OrderData;
DROP TABLE IF EXISTS ShopCartItem;
DROP TABLE IF EXISTS ShopCart;
DROP TABLE IF EXISTS CatProduct;
DROP TABLE IF EXISTS ProductSpec;
DROP TABLE IF EXISTS Specification;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Shopper;


-- Create the tables

CREATE TABLE Shopper
(
  ShopperID INT(4) NOT NULL AUTO_INCREMENT,
  Name VARCHAR(50) NOT NULL,
  BirthDate DATE DEFAULT NULL,
  Address VARCHAR(150) DEFAULT NULL,
  Country VARCHAR(50) DEFAULT NULL,
  Phone VARCHAR(20) DEFAULT NULL,
  Email VARCHAR(50) NOT NULL,
  Password VARCHAR(255) NOT NULL,
  PwdQuestion VARCHAR(100) DEFAULT NULL,
  PwdAnswer VARCHAR(50) DEFAULT NULL,
  ActiveStatus INT(4) NOT NULL DEFAULT 1,
  DateEntered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ShopperID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Category
(
  CategoryID INT(4) NOT NULL AUTO_INCREMENT,
  CatName VARCHAR(255) DEFAULT NULL,
  CatDesc LONGTEXT DEFAULT NULL,
  CatImage VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (CategoryID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Product
(
  ProductID INT(4) NOT NULL AUTO_INCREMENT,
  ProductTitle VARCHAR(255) DEFAULT NULL,
  ProductDesc LONGTEXT DEFAULT NULL,
  ProductImage VARCHAR(255) DEFAULT NULL,
  Price DOUBLE NOT NULL DEFAULT 0.0,
  Quantity INT(4) NOT NULL DEFAULT 0,
  Offered INT(4) NOT NULL DEFAULT 0,
  OfferedPrice DOUBLE DEFAULT NULL,
  OfferStartDate DATE DEFAULT NULL,
  OfferEndDate DATE DEFAULT NULL,
  PRIMARY KEY (ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Specification
(
  SpecID INT(4) NOT NULL AUTO_INCREMENT,
  SpecName VARCHAR(64) DEFAULT NULL,
  PRIMARY KEY (SpecID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE ProductSpec
(
  ProductID INT(4) NOT NULL,
  SpecID INT(4) NOT NULL,
  SpecVal VARCHAR(255) DEFAULT NULL,
  Priority INT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (ProductID, SpecID),
  FOREIGN KEY fk_PS_Product(ProductID) REFERENCES Product(ProductID),
  FOREIGN KEY fk_PS_Specification(SpecID) REFERENCES Specification(SpecID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE CatProduct
(
  CategoryID INT(4) NOT NULL,
  ProductID INT(4) NOT NULL,
  PRIMARY KEY (CategoryID, ProductID),
  FOREIGN KEY fk_CP_Category(CategoryID) REFERENCES Category(CategoryID),
  FOREIGN KEY fk_CP_Product(ProductID) REFERENCES Product(ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE ShopCart
(
  ShopCartID INT(4) NOT NULL AUTO_INCREMENT,
  ShopperID INT(4) NOT NULL,
  OrderPlaced INT(4) NOT NULL DEFAULT 0,
  Quantity INT(4) DEFAULT NULL,
  SubTotal DOUBLE DEFAULT NULL,
  Tax DOUBLE DEFAULT NULL,
  ShipCharge DOUBLE DEFAULT NULL,
  Discount DOUBLE NOT NULL DEFAULT 0.0,
  Total DOUBLE DEFAULT NULL,
  DateCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ShopCartID),
  FOREIGN KEY fk_SC_Shopper(ShopperID) REFERENCES Shopper(ShopperID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE ShopCartItem
(
  ShopCartID INT(4) NOT NULL,
  ProductID INT(4) NOT NULL,
  Price DOUBLE NOT NULL,
  Name VARCHAR(255) NOT NULL,
  Quantity INT(4) NOT NULL,
  PRIMARY KEY (ShopCartID, ProductID),
  FOREIGN KEY fk_SCI_ShopCart(ShopCartID) REFERENCES ShopCart(ShopCartID),
  FOREIGN KEY fk_SCI_Product(ProductID) REFERENCES Product(ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE OrderData
(
  OrderID INT(4) NOT NULL AUTO_INCREMENT,
  ShopCartID INT(4) NOT NULL,
  ShipName VARCHAR(50) NOT NULL,
  ShipAddress VARCHAR(150) NOT NULL,
  ShipCountry VARCHAR(50) NOT NULL,
  ShipPhone VARCHAR(20) DEFAULT NULL,
  ShipEmail VARCHAR(50) DEFAULT NULL,
  BillName VARCHAR(50) DEFAULT NULL,
  BillAddress VARCHAR(150) DEFAULT NULL,
  BillCountry VARCHAR(50) DEFAULT NULL,
  BillPhone VARCHAR(20) DEFAULT NULL,
  BillEmail VARCHAR(50) DEFAULT NULL,
  DeliveryDate DATE DEFAULT NULL,
  DeliveryTime VARCHAR(50) DEFAULT NULL,
  DeliveryMode VARCHAR(50) DEFAULT NULL,
  Message VARCHAR(255) DEFAULT NULL,
  OrderStatus INT(4) NOT NULL DEFAULT 1,
  DateOrdered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (OrderID),
  FOREIGN KEY fk_Order_ShopCart(ShopCartID) REFERENCES ShopCart(ShopCartID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE GST
(
  GstId INT(4) NOT NULL AUTO_INCREMENT,
  EffectiveDate DATE NOT NULL,
  TaxRate DOUBLE NOT NULL,
  PRIMARY KEY (GstId)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Ranking
(
  RankingID INT(4) NOT NULL AUTO_INCREMENT,
  ShopperID INT(4) NOT NULL,
  ProductID INT(4) NOT NULL,
  Rank INT(4) DEFAULT NULL,
  Comment LONGTEXT NULL,
  PRIMARY KEY (RankingID),
  FOREIGN KEY fk_Ranking_Shopper(ShopperID) REFERENCES Shopper(ShopperID),
  FOREIGN KEY fk_Ranking_Product(ProductID) REFERENCES Product(ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- Load the tables with sample data

-- Shoppers
insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered)
values('James Ecader','1980-01-01','School of Infocomm Technology, Ngee Ann Polytechnic','Singapore','(65) 64601234','ecader@np.edu.sg','ecader','Which polytechnic?','Ngee Ann', 1, '2020-01-01 10:05:30' );

insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered)
values('Peter Tan','1997-05-15','Blk 108, Hougang Ave 1, #04-04','Singapore','(65) 62881111','PeterTan@hotmail.com','PeterTan','wife''s name?','Lucy', 0, '2020-01-01 15:35:20' );

insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered)
values('Mary Mai','1992-08-09','123, Sunset Way, Singapore 555123','Singapore','(65) 62881111','MaryMai@yahoo.com','MaryMai','How many brothers and sisters?','0', 1, '2019-05-01 09:45:23' );


-- Categories
insert into Category(CatName, CatDesc, CatImage)
values('Ring Donut','Formed by joining the ends of a long, skinny piece of dough into a ring, often topped with a glaze (icing) or a powder such as cinnamon or sugar.','RingDonut.gif');

insert into Category(CatName, CatDesc, CatImage)
values('Filling Donut','A flattened sphere injected with jelly, cream, or other sweet filling.','FillingDonut.gif');


-- Specifications
insert into Specification(SpecName)
values('Basic Ingredient');

insert into Specification(SpecName)
values('Sweetness (Out of 5)');


-- Products
insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate)
values('Cinnamon Circle', 'Sugar and Cinnamon Powder',
'Donut_cinnamoncircle.jpg', 1.20, 9000, 1, 1.00, '2021-12-01', '2021-12-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate)
values('Honeydew Chocolate', 'Smooth Honeydew flavoured Chocolate',
'Donut_honeydewchocolate.jpg', 2.00, 800, 1, 1.20, '2021-12-01', '2021-03-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity)
values('Rainbow Bright', 'White Chocolate Dazzling with colourful Rainbow Rice.',
'Donut_rainbowbright.jpg', 1.50, 0);

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity)
values('Summer Snow', 'A light dusting of frosty powedered Sugar.',
'Donut_summersnow.jpg', 1.30, 600);

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity)
values('Blueberry Breeze', 'Tingling snow powder donut with Blueberry insides.',
'Donut_blueberrybreeze.jpg', 1.75, 100);

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate)
values('Mango Cheesecake', 'Mouth-tingling snow powder, filled with delicious mango cheesecake.',
'Donut_mangocheesecake.jpg', 2.50, 800, 1, 1.50, '2022-01-01', '2022-03-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate)
values('Strawberry Shortcake', 'Strawberry Chocolate topping with Strawberry filling.',
'Donut_shortcake.jpg', 2.00, 700, 1, 1.50, '2022-03-01', '2022-03-31');


-- Product Specifications
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(1, 1, 'Egg, Milk, Soy, Wheat', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(1, 2, '2', 2);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(2, 1, 'Egg, Milk, Soy, Wheat', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(2, 2, '3', 2);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(3, 1, 'Egg, Milk, Soy, Wheat', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(3, 2, '3.5', 2);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(4, 1, 'Egg, Milk, Soy, Wheat', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(4, 2, '2', 2);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(5, 1, 'Egg, Milk, Soy, Wheat', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(5, 2, '3.5', 2);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(6, 1, 'Egg, Milk, Soy, Wheat', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(6, 2, '3.5', 2);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(7, 1, 'Egg, Milk, Soy, Wheat', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority)
values(7, 2, '4', 2);


-- Products' Category
insert into CatProduct(CategoryID, ProductID) values(1,1);
insert into CatProduct(CategoryID, ProductID) values(1,2);
insert into CatProduct(CategoryID, ProductID) values(1,3);
insert into CatProduct(CategoryID, ProductID) values(1,4);
insert into CatProduct(CategoryID, ProductID) values(2,5);
insert into CatProduct(CategoryID, ProductID) values(2,6);
insert into CatProduct(CategoryID, ProductID) values(2,7);


-- Shopping Cart
insert into ShopCart (ShopperId, OrderPlaced, Quantity, Subtotal, Tax, ShipCharge, Discount, Total, DateCreated)
values(1, 1, 30, 40.00, 2.80, 2.00, 0.00, 44.80,'2021-12-20 09:56:30');


-- Shopping Cart Items
insert into ShopCartItem(ShopCartId, ProductId, Name, Price, Quantity)
values(1, 1, 'Cinnamon Circle', 1.00, 20);

insert into ShopCartItem(ShopCartId, ProductId, Name, Price, Quantity)
values(1, 2, 'Honeydew Chocolate', 2.00, 10);


-- Order Data
insert into OrderData(ShopCartId,ShipName,ShipAddress,ShipCountry,ShipPhone,ShipEmail,
BillName,BillAddress,BillCountry,BillPhone,BillEmail,DeliveryDate,DeliveryMode,
Message,OrderStatus,DateOrdered)
values(1, 'Jenny Lai', 'Blk 222, Ang Mo Kio Ave 1, #12-12, S(560222)', 'Singapore', '(65) 63447777', 'JennyLai@yahoo.com.sg',
'James Ecader', 'School of InfoComm Technology, Ngee Ann Polytechnic', 'Singapore','(65) 64601234', 'ecader@np.edu.sg', '2016-12-23', 'Normal',
'Merry Christmas!', 3, '2021-12-20 10:01:35');


-- GST
insert into GST(EffectiveDate, TaxRate) values ('2004-01-01',5.0);
insert into GST(EffectiveDate, TaxRate) values ('2007-07-01',7.0);
insert into GST(EffectiveDate, TaxRate) values ('2024-01-01',10.0);


-- Rankings
insert into Ranking(ShopperId, ProductId, Rank, Comment)
values(1, 2, 4, 'Very nice!');

insert into Ranking(ShopperId, ProductId, Rank, Comment)
values(2, 4, 3, 'Cheep and Good!');

insert into Ranking(ShopperId, ProductId, Rank, Comment)
values(3, 2, 3, 'Look nice!');
