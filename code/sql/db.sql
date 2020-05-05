CREATE TABLE Net_devices (
	MACND varchar(17),
     TYPE varchar(50) NOT NULL,
	IP_ADDR varchar(15) NOT NULL,
	SSH tinyint(1) NOT NULL,
	TELNET tinyint(1) NOT NULL,
     NPORTS int(3) NOT NULL,
     BRAND varchar(50) NOT NULL,
     MODEL varchar(50) NOT NULL,
     PRIMARY KEY (MACND)
);

CREATE TABLE Medical_eq (
     MACEQ varchar(17),
     TYPE varchar(50) NOT NULL,
     BRAND varchar(50),
     MODEL varchar(50),
     PRIMARY KEY (MACEQ)
);

CREATE TABLE Ports (
     ID int AUTO_INCREMENT,
     NAME varchar(50) NOT NULL,
     LOCATION varchar(100) NOT NULL,
     IP_ADDR varchar(15),
     MACND varchar(17),
     MACEQ varchar(17),
     PRIMARY KEY (ID),
     FOREIGN KEY (MACND) REFERENCES Net_devices (MACND),
     FOREIGN KEY (MACEQ) REFERENCES Medical_eq (MACEQ)
);

CREATE TABLE Users (
     ID int AUTO_INCREMENT,
     NAME varchar(100) NOT NULL,
     USERNAME varchar(50) NOT NULL,
     PASS varchar(255) NOT NULL,
     ROLE varchar(10) NOT NULL,
     PRIMARY KEY (ID)
);
