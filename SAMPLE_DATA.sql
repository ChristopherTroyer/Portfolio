-- Insert rows into PRODUCT --
-- (PID, NAME, WEIGHT, PRICE, RATING, QTY, DESCRIPT, IMG) --
INSERT INTO PRODUCT
VALUES
       ('1', 'D3LL Desktop A7350', '20', '849.99', '4.3', '50', 'A sturdy D3LL Desktop to get your daily computing needs accomplished.', 'img/d3ll_desktop.jpg'),
       ('2', 'D3LL Desktop E7300', '17', '599.99', '3.5', '200', 'A good D3LL Desktop for your workforce to accomplish their tasks.', 'img/d3ll_desktop.jpg'),
       ('3', 'D3LL Desktop X7400', '25', '1599.99', '5.0', '30', 'This D3LL Xtreme device will give the highest performance in your games.', 'img/d3ll_desktop.jpg'),
       ('4', '4PPLE A21 M4c Desktop', '15', '999.99', '5.0', '100', 'This sublime 4PPLE all-in-one M4c Desktop will provide a smooth clean computing experience at a reasonable cost.', 'img/4pple_mac_desktop.jpg'),
       ('5', '4PPLE M4cbook L21', '10', '1399.99', '4.8', '100', 'This slim aluminum cased laptop will help you do all your work on the go.', 'img/4pple_macbook.jpg'),
       ('6', '4PPLE EPad', '5', '849.99', '5.0', '50', 'This 4PPLE EPad is a sleek powerhouse that fits in the palm of your hand.', 'img/4pple_epad.jpg'),
       ('7', '4PPLE Wireless Earpods', '3', '399.99', '4.9', '30', 'These bluetooth Earpods will work seamlessly with all your 4PPLE Devices on the go.', 'img/4pple_earpods.jpg'),
       ('8', 'NVODIA RTX 3080', '8', '949.99', '4.8', '0', 'This GPU from NVODIA will give you top of the line performance in all of you games.', 'img/nvodia_rtx_3080.jpg'),
       ('9', 'AWD 7X4200', '6', '749.99', '4.4', '20', 'A power GPU like the 7X4200 will bring extra FPS to your gaming experience.', 'img/AWD_7x4200.jpg'),
       ('10', 'Intull I7 13200k', '5', '799.99', '4.0', '40', 'This processor from Intull will push your computer system to the brink.', 'img/Intull_processor.jpg'),
       ('11', 'Intull I5 13000', '5', '549.99', '4.8', '60', 'A sturdy processor from Intull that will complete your daily tasks without fail.', 'img/Intull_processor.jpg'),
       ('12', 'TridentX 2x16GB DDR5 4000mhz Memory', '2', '400.00', '4.2', '20', 'More memory than your system will ever need.', 'img/TridentX.jpg'),
       ('13', 'TridentX 4x4GB DDR4 3200mhz Memory', '4', '200.00', '5.0', '30', 'A good memory package to support all your applications with speed.', 'img/TridentX.jpg'),
       ('14', 'LoggyTech Q520 Mouse', '3', '79.99', '5.0', '50', 'A well tested and revered mouse with integrated programmable buttons.', 'img/LoggyTech_mouse.jpg'),
       ('15', '4PPLE Bluetooth Trackpad', '5', '199.99', '4.6', '20', 'This bluetooth trackpad will connect to your desktop so you use it instead of a mouse if you like that.', 'img/trackpad.jpg'),
       ('16', 'D3LL Wired Mouse', '2', '9.99', '4.1', '50', 'A sturdy mouse that will withstand almost anything. Comes with most prebuilt desktops from D3LL.', 'img/d3ll_mouse.jpg'),
       ('17', 'Raiser Gaming Headset', '7', '179.99', '4.0', '35', 'This gaming headset has an integrated microphone so that you can communicate in games or during classes.', 'img/Raiser_headset.jpg'),
       ('18', 'LoggyTech Mousepad', '2', '4.99', '4.3', '50', 'It gets the job done.', 'img/loggytech_mouse_pad.jpg'),
       ('19', 'Macrosoft Office License', '0', '199.99', '4.9', '200', 'This license will give you access to the whole suite of Macrosoft productivity software.', 'img/macrosoft_office_license.jpg'),
       ('20', 'Generic Laptop Charger Cable', '5', '29.99', '2.3', '30', 'Useful in case you lose your original. Not compatible with 4PPLE Laptops', 'img/charging_cable.jpg');

-- Insert rows into CUSTOMER --
-- (UID, NAME, PHONE, EMAIL, ADDR, BDAY, PASS, EMP) --
INSERT INTO CUSTOMER
VALUES
      ('1', 'Jared Wilkinson', '8157877272', 'Z1885727@students.niu.edu', NULL, NULL, 'boley', '1'),
      ('2', 'Tim Lenny', '7727641123', 'TLENNY@realmail.com', '132 House St Dallas, Florida 44823, United States', '1987-03-14', 'LennyFl0rida', '0'),
      ('3', 'Agatha Vilakay', '2238460123', 'vkayagath@aol.gov', '1924 342nd Street APT 3 Denver, Arizona 77372, United Sates', '2000-11-23', 'agath123', '0'),
      ('4', 'Suzannah McReallyLongnameBecauseLongName', '7736429984', 'shortemail@mail.co', '3 Clamshell Blvd. Burger, Illinois, United States', '1999-01-01', '123pass', '0'),
      ('5', 'Gary Yunmer', '7736429984', 'ComparativelyReallyLongEmailFromSomeoneWhoHasSamePhoneNumber@mail.co', '3 Clamshell Blvd. Burger, Illinois, United States', '1996-04-21', 'SomeSomewhatLongPassword22', '0');

-- Insert rows into ORDR --
-- (OID, UID, STATUS) --
INSERT INTO ORDR
VALUES
      ('1', '1', 'PENDING'),
      ('2', '1', 'SHOPPING'),
      ('3', '2', 'COMPLETE'),
      ('4', '2', 'SHOPPING'),
      ('5', '3', 'PENDING'),
      ('6', '4', 'SHOPPING'),
      ('7', '5', 'SHOPPING'),
      ('8', '3', 'SHOPPING');

-- Insert rows into CART --
-- (OID, PID, NUM) --
INSERT INTO CART
VALUES
      ('1', '3', '1'),
      ('1', '14', '1'),
      ('1', '17', '1'),
      ('1', '18', '1'),
      ('2', '19', '1'),
      ('3', '2', '20'),
      ('3', '18', '20'),
      ('3', '19', '20'),
      ('3', '16', '20'),
      ('4', '17', '20'),
      ('5', '6', '5'),
      ('5', '7', '4'),
      ('6', '1', '1'),
      ('6', '8', '1'),
      ('7', '20', '1'),
      ('8', '7', '2');
      
-- Insert rows into WISH --
-- (UID, PID, NUM) --
INSERT INTO WISH
VALUES
      ('1', '19', '1'),
      ('1', '8', '2'),
      ('2', '20', '19'),
      ('3', '5', '1'),
      ('4', '3', '1');

-- NO rows inserted into SESS --
