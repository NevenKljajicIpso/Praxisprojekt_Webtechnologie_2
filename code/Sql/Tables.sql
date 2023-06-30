CREATE TABLE Zimmer
(
    Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    HotelId INT NOT NULL,
    RaumNummer INT NOT NULL,
    AnzahlRaeume INT NOT NULL,
    Kategorie INT NOT NULL
);

INSERT INTO Zimmer (HotelId, RaumNummer, AnzahlRaeume, Kategorie) VALUES
(1, 101, 1, 1), -- Zimmer im Hotel mit Id 1, Raumnummer 101, 1 Raum, Kategorie Normal
(2, 201, 2, 2), -- Zimmer im Hotel mit Id 2, Raumnummer 201, 2 Räume, Kategorie Premium
(3, 301, 1, 3); -- Zimmer im Hotel mit Id 3, Raumnummer 301, 1 Raum, Kategorie Presidential
