CREATE TABLE Zimmer
(
	Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	HotelId INT NOT NULL,
	RaumNummer INT NOT NULL,
	AnzahlRaeume INT NOT NULL,
	Kategorie INT NOT NULL -- 1 Normal, 2 Premium, 3 Presidential
);