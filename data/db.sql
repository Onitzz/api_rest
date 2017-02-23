DROP DATABASE IF EXISTS api_user;
CREATE DATABASE IF NOT EXISTS api_user DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE api_user;

DROP TABLE IF EXISTS user;
CREATE TABLE IF NOT EXISTS user(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  firstname varchar(200) NOT NULL,
  lastname varchar(200) NOT NULL,
  email varchar(200) NOT NULL,
  birthday DATE NOT NULL,
  sex char(4) NOT NULL,
  github varchar(200) DEFAULT NULL,
  pet boolean DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_general_ci AUTO_INCREMENT=1;

INSERT INTO user
(firstname,lastname,email,birthday,sex,github,pet)
values
('foo', 'bar', 'foo.bar@pop.eu.com', '2017-01-01', 'M', 'http://github.com/foobar', 0),
('lorem', 'ipsum', 'lorem.ipsum@pop.eu.com', '2017-01-01', 'F', 'http://github.com/loremipsum', 0),
('toto', 'titi', 'toto.titi@pop.eu.com', '2017-01-01', 'M', NULL, NULL);
