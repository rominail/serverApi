use test;
CREATE TABLE user
(
    id       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO user(name, password)
VALUES ('robby', '$2y$10$o5/RNf/p.cBvgAMO26IoFunwzFsmT4Z5lpSjcFLE0bd5PSbYZKx6C');


CREATE TABLE server
(
    id   INT              NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)     NOT NULL,
    ip   INT(11) UNSIGNED NOT NULL
);