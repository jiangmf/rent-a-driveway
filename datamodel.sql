BEGIN;

DROP DATABASE IF EXISTS parking;
DROP USER IF EXISTS `jiangmf`@`localhost`;

CREATE DATABASE parking;

USE parking;

CREATE TABLE puser (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name text NOT NULL,
    last_name text NOT NULL,
    email text NOT NULL,
    password text NOT NULL,
    phone text NOT NULL,
    salt text NOT NULL,
    CONSTRAINT PRIMARY KEY (`id`)
)
;
CREATE TABLE parking (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    puser_id INT(11) UNSIGNED NOT NULL REFERENCES puser(id),
    CONSTRAINT FOREIGN KEY (`puser_id`) REFERENCES puser(`id`) ON DELETE CASCADE,
    title text NOT NULL,
    latitude real DEFAULT NULL,
    longitude real DEFAULT NULL,
    price real NOT NULL,
    description text NOT NULL,
    image text NOT NULL,
    CONSTRAINT PRIMARY KEY (`id`)
)
;
CREATE TABLE review (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    parking_id INT(11) UNSIGNED NOT NULL,
    CONSTRAINT FOREIGN KEY (`parking_id`) REFERENCES parking(`id`) ON DELETE CASCADE,
    title text NOT NULL,
    description text NOT NULL,
    rating real NOT NULL,
    CONSTRAINT PRIMARY KEY (`id`)
)
;

CREATE USER `jiangmf`@`localhost` IDENTIFIED BY "password";
GRANT ALL PRIVILEGES ON `parking`.* TO `jiangmf`@`localhost`;

INSERT INTO puser
        (id, first_name, last_name, salt, password, email, phone)
        VALUES ('1', 'David', 'Jiang', '1eabf7eb3099ae09d13a8bc05669bb5c8ecb47ab', SHA2(CONCAT('password', salt), 0), 'jiangmf@mcmaster.ca', '6475233213');

INSERT INTO parking
        (id, puser_id, title, latitude, longitude, price, description, image)
        VALUES ('1', '1', 'Spot on Mcmaster', '43.260919', '-79.919923', '300', 'Bacon ipsum dolor amet salami sirloin short loin andouille ball tip, tenderloin filet mignon. Filet mignon bresaola drumstick, leberkas jowl ground round capicola flank porchetta bacon frankfurter fatback short loin pork loin shankle. Rump ground round beef doner bacon tri-tip. Buffalo frankfurter picanha ham. Flank pancetta turkey boudin capicola jowl.', '');

INSERT INTO parking
        (id, puser_id, title, latitude, longitude, price, description, image)
        VALUES ('2', '1', 'Spot on Sanders', '43.258649', '-79.931055', '10', 'Bacon ipsum dolor amet salami sirloin short loin andouille ball tip, tenderloin filet mignon. Filet mignon bresaola drumstick, leberkas jowl ground round capicola flank porchetta bacon frankfurter fatback short loin pork loin shankle. Rump ground round beef doner bacon tri-tip. Buffalo frankfurter picanha ham. Flank pancetta turkey boudin capicola jowl.', '');


INSERT INTO parking
        (id, puser_id, title, latitude, longitude, price, description, image)
        VALUES ('3', '1', 'Spot on Emmerson', '43.253521', '-79.921425', '15', 'Bacon ipsum dolor amet salami sirloin short loin andouille ball tip, tenderloin filet mignon. Filet mignon bresaola drumstick, leberkas jowl ground round capicola flank porchetta bacon frankfurter fatback short loin pork loin shankle. Rump ground round beef doner bacon tri-tip. Buffalo frankfurter picanha ham. Flank pancetta turkey boudin capicola jowl.', '');


INSERT INTO parking
        (id, puser_id, title, latitude, longitude, price, description, image)
        VALUES ('4', '1', 'Spot on Carling', '43.261483', '-79.897467', '5', 'Bacon ipsum dolor amet salami sirloin short loin andouille ball tip, tenderloin filet mignon. Filet mignon bresaola drumstick, leberkas jowl ground round capicola flank porchetta bacon frankfurter fatback short loin pork loin shankle. Rump ground round beef doner bacon tri-tip. Buffalo frankfurter picanha ham. Flank pancetta turkey boudin capicola jowl.', '');


INSERT INTO parking
        (id, puser_id, title, latitude, longitude, price, description, image)
        VALUES ('5', '1', 'Spot on Florence', '43.264967', '-79.885129', '20', 'Bacon ipsum dolor amet salami sirloin short loin andouille ball tip, tenderloin filet mignon. Filet mignon bresaola drumstick, leberkas jowl ground round capicola flank porchetta bacon frankfurter fatback short loin pork loin shankle. Rump ground round beef doner bacon tri-tip. Buffalo frankfurter picanha ham. Flank pancetta turkey boudin capicola jowl.', '');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('1', 'Great Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '4');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('1', 'Amazing Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '5');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('1', 'Good Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '3');


INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('2', 'Great Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '4');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('2', 'Amazing Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '5');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('2', 'Terrible Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '1');


INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('3', 'Bad Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '2');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('3', 'Super Terrible Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '1');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('3', 'Terrible Spot', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '1');


INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('4', 'WOW', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '5');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('4', 'Super WOW', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '5');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('4', 'WOWOWOW', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '5');


INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('5', 'Meh', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '3');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('5', 'Super WOW', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '5');

INSERT INTO review
        (parking_id, title, description, rating)
        VALUES ('5', 'eh', 'Porchetta jerky hamburger flank burgdoggen alcatra. Turkey cow turducken hamburger biltong strip steak cupim chicken.', '2');

COMMIT;