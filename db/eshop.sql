-- drop database eshop;

CREATE DATABASE IF NOT EXISTS eshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SET SQL_SAFE_UPDATES = 0;

use eshop;


-- tabulky
CREATE TABLE if NOT EXISTS produkty (
	product_id INT AUTO_INCREMENT PRIMARY KEY,
	nazev VARCHAR(200),
	popis TEXT,
	cena DECIMAL(10, 2) NOT NULL,
	mnozstvi INT NOT NULL DEFAULT 0,
	datum_pridani TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	obrazek_url VARCHAR (255) NULL,
	stav ENUM('aktivni', 'zruseno') default 'aktivni' not null
)
ENGINE = InnoDB;


CREATE TABLE if NOT EXISTS role (
	role_id INT AUTO_INCREMENT PRIMARY KEY, 
	nazev VARCHAR(50) UNIQUE NOT NULL
)
ENGINE = InnoDB;


CREATE TABLE if NOT EXISTS uzivatele (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	jmeno VARCHAR(100) NOT NULL,
	prijmeni VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	heslo VARCHAR(200) NOT NULL,
	datum_registrace TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	telefon VARCHAR(20),
	ulice VARCHAR(150) NOT NULL,
	cislo_popisne VARCHAR(15) NOT NULL,
	mesto VARCHAR(50) NOT NULL,
	psc VARCHAR(10) NOT NULL,
	role_id INT,
		FOREIGN KEY (role_id) REFERENCES role(role_id)
)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS objednavky (
    objednavka_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    datum_objednavky TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    celkova_cena DECIMAL(10, 2),
    stav ENUM('nová', 'zpracovává se', 'odeslána') DEFAULT 'nová',
    platba ENUM('nezaplaceno', 'zaplaceno') DEFAULT 'nezaplaceno',
    typ_platby ENUM('dobírka') DEFAULT 'dobírka',
    ulice VARCHAR(150) NOT NULL,
    cislo_popisne VARCHAR(150) NOT NULL,
    mesto VARCHAR(150) NOT NULL,
    psc VARCHAR(150) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES uzivatele(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS objednavky_produkty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objednavka_id INT,
    product_id INT,
    mnozstvi INT,
    cena DECIMAL(10, 2),
    FOREIGN KEY (objednavka_id) REFERENCES objednavky(objednavka_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES produkty(product_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- produkty
INSERT INTO produkty (nazev, popis, cena, mnozstvi, datum_pridani, obrazek_url)
VALUES
    ('Činky jednoruční 2 x 1kg', 'Sada činek s pevnou váhou, vinylovým potahem a kovovým jádrem. Vhodné pro kondiční cvičení.', 220.00, 10, CURDATE(), 'https://img.kokiskashop.cz/p/10/4578/1461442147-296362-big.jpg'),
    ('Činky jednoruční 2 x 2kg', 'Sada činek s pevnou váhou, hodí se převážně na aerobik, bodystyling, nebo jogging.', 430.00, 15, CURDATE(), 'https://www.eureko.cz/userfiles/file2/fotky-produktu/800/cinky-neopren-2-kg--par.jpg'),
    ('Činky jednoruční 2 x 5kg', 'Sada činek s pevnou váhou, vnniřní materiál ocelový odlitek.', 750.00, 13, CURDATE(), 'https://i00.eu/img/652/1024x1024/5vi58k5e/16262.jpg'),
    ('Činky jednoruční 2 x 10kg', 'Sada činek s pevnou váhou, ideální na procvičení celého těla.', 1800.00, 10, CURDATE(), 'https://img.gorillasports.cz/p/49/76599/1633338454-354495-big.jpg'),
    ('Činky jednoruční 2 x 20kg', 'Sada činek s pevnou váhou na procičení celého těla pro zkušenější sportovce.', 2500.00, 8, CURDATE(), 'https://res.cloudinary.com/chal-tec/image/upload/w_750,q_auto,f_auto,dpr_3.0/bbg/10028382/Gallery/10028382_yy_0001_titel___01_Capital_Sports_Hexbell_Kurzhantel_set_20kg.jpg'),
    ('Činky jednoruční 2 x 25kg', 'Sada činek s pevnou váhou, rovným úchopem a nejmodernějším typem vroubkování.', 3300.00, 7, CURDATE(), 'https://getstronger.s8.cdn-upgates.com/_cache/9/9/990654d0f8a41d17f7c5e6565bcbc579-hexagonalni-jednorucka-truegrip-25-kg-truesteel-vroubkovani.webp'),
    ('Olympijská osa', 'Osa s celkovou délkou 1850 mm a pruměrem konců 50 mm je vhodná pro všechny olympijské činky s otvorem 50/51 mm, rukojeť o průměru 28 mm.', 3000.00, 5, CURDATE(), 'https://www.360globalfitness.cz/upload/28529-1105077555.jpg'),
    ('Bench lavice', 'Na posílení horní svalové partie, ocelová konstrukce s maximální nosností 180 kg.', 3500.00, 3, CURDATE(), 'https://cdn.myshoptet.com/usr/www.duvlan.cz/user/shop/big/16814_bench-lavice-duvlan-press-up.jpg?6601352a');

-- role
INSERT INTO role (nazev) VALUES ('admin');
INSERT INTO role (nazev) VALUES ('uzivatel');

INSERT INTO uzivatele (jmeno, prijmeni, email, heslo, telefon, ulice, cislo_popisne, mesto, psc, role_id)
VALUES
	('admin','admin', 'admin@admin.com', '$2y$10$izATjmtLjsAn8xAENyuSrumrnh46qZsP46abssg0WBcRh5NmaDQdu', '123456789', 'admin', '111', 'admin', '11111', '1');
-- později si pomoci INSERT vlozim do tabulky jedineho admina, ktery bude moct upravovat

INSERT INTO objednavky (user_id, celkova_cena, stav, platba, ulice, cislo_popisne, mesto, psc) 
VALUES (2, 1000, 'nová', 'nezaplaceno', 'Hlavní', '123', 'Praha', '11000');


UPDATE produkty
SET popis = 'Sada činek s pevnou váhou, vinylovým potahem a kovovým jádrem. Vhodné pro kondiční cvičení.'
WHERE product_id = 1;

UPDATE produkty
SET popis = 'Sada činek s pevnou váhou, vinylovým potahem a kovovým jádrem. Vhodné pro kondiční cvičení.'
WHERE product_id = 2;

UPDATE produkty
SET popis = 'Sada činek s pevnou váhou, vinylovým potahem a kovovým jádrem. Vhodné pro kondiční cvičení.'
WHERE product_id = 3;

UPDATE produkty
SET popis = 'Sada činek s pevnou váhou, vinylovým potahem a kovovým jádrem. Vhodné pro kondiční cvičení.'
WHERE product_id = 4;

UPDATE produkty
SET popis = 'Sada činek s pevnou váhou, vinylovým potahem a kovovým jádrem. Vhodné pro kondiční cvičení.'
WHERE product_id = 5;

UPDATE produkty
SET popis = 'Sada činek s pevnou váhou, rovným úchopem a nejmodernějším typem vroubkování na celém trhu.'
WHERE product_id = 6;

UPDATE produkty
SET popis = 'Osa s celkovou délkou 1 850 mm a průměrem konců 50 mm je vhodná pro procvičení celého těla.'
WHERE product_id = 7;

UPDATE produkty
SET popis = 'Vhodné na posílení celého prsního svalstva, ocelová konstrukce s maximální nosností 180 kg.'
WHERE product_id = 8;
