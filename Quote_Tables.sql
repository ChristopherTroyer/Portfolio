CREATE TABLE if not exists Associate (
    `AssocID` int NOT NULL AUTO_INCREMENT,
    `First_name` varchar(100),
    `last_name` varchar(100),
    `username` varchar(25),
    `password` char(128),
    `address` varchar(250),
    `commission` decimal(10, 2),
    `permission` int,
    `email` varchar(100),
    PRIMARY KEY (AssocID)
);

CREATE TABLE if not exists New_Quote (
    `QuoteID` INT NOT NULL,
    `CustID` INT NOT NULL,
    `AssocID` INT NOT NULL,
    `cust_talk` varchar(50),
    `status` varchar(50),
    `discount_amnt` decimal(8, 2),
    `discount_prcn` decimal(4, 2),
    `price` decimal(8, 2),
    `process_date` date,
    PRIMARY KEY (QuoteID),
    FOREIGN KEY (AssocID) REFERENCES Associate(AssocID)
);

CREATE TABLE if not exists Quote_Note (
    `NoteID` int NOT NULL,
    `QuoteID` INT NOT NULL,
    `note` varchar(1000),
    PRIMARY KEY (NoteID, QuoteID),
    FOREIGN KEY (QuoteID) REFERENCES New_Quote(QuoteID)
);

CREATE TABLE if not exists Line_Items (
    `ItemID` INT NOT NULL,
    `QuoteID` INT NOT NULL,
    `Price` decimal(8, 2),
    `Free_Desc` varchar(50),
    PRIMARY KEY(ItemID, QuoteID),
    FOREIGN KEY (QuoteID) REFERENCES New_Quote(QuoteID)
);
	
