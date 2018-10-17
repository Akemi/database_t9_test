CREATE DATABASE contacts DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE contacts;

CREATE TABLE contacts (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  name_t9 varchar(100) NOT NULL,
  firstname varchar(100) NOT NULL,
  firstname_t9 varchar(100) NOT NULL,
  number varchar(50) NOT NULL,
  PRIMARY KEY (id),
  INDEX name_index (name_t9),
  INDEX firstname_index (firstname_t9)
);

INSERT INTO contacts (id, name, name_t9, firstname, firstname_t9, number) VALUES
(46, 'otto', '6886', 'wahl', '9245', '0123'),
(47, 'nutzer', '688937', 'kein', '5346', '0256'),
(48, 'schmidt', '7246438', 'andy', '2639', '034564'),
(49, 'suppe', '78773', 'rahmen', '724636', '06789'),
(50, 'trt', '878', 'etre', '3873', '5765'),
(51, 'hgdfg*?/(', '443340000', 'gfdgf', '43343', '4654');