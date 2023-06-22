<?php
require_once 'Zimmer.php';
require_once 'Database.php';

class ZimmerController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getZimmer() {
        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // SQL-Abfrage zum Abrufen aller Zimmer
        $query = "SELECT * FROM zimmer";
    
        // Abfrage ausführen
        $result = $dbConnection->query($query);
        // Überprüfen, ob die Abfrage erfolgreich war
        if ($result) {
            // Array für die Zimmerdaten erstellen
            $zimmerArray = array();
    
            // Zimmerdaten aus dem Abfrageergebnis lesen und dem Array hinzufügen
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $zimmer = new Zimmer();
                $zimmer->id = $row['Id'];
                $zimmer->hotelId = $row['HotelId'];
                $zimmer->raumNummer = $row['RaumNummer'];
                $zimmer->anzahlRaeume = $row['AnzahlRaeume'];
                $zimmer->kategorie = $row['Kategorie'];
    
                // Zimmer zum Array hinzufügen
                $zimmerArray[] = $zimmer;
            }
    
            // JSON-Antwort mit den Zimmerdaten senden
            header('Content-Type: application/json');
            echo json_encode($zimmerArray);
        } else {
            // Bei einem Fehler eine Fehlermeldung senden
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array('message' => 'Fehler beim Abrufen der Zimmerdaten.'));
        }
    }
    

    public function getZimmerById($zimmerId) {
        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // SQL-Abfrage zum Abrufen eines bestimmten Zimmers anhand der Zimmer-ID
        $query = "SELECT * FROM zimmer WHERE Id = :id";
        
        // Vorbereiten der Abfrage
        $statement = $dbConnection->prepare($query);
        
        // Zimmer-ID als Parameter binden
        $statement->bindParam(':id', $zimmerId, PDO::PARAM_INT);
        
        // Abfrage ausführen
        $result = $statement->execute();
        
        // Überprüfen, ob die Abfrage erfolgreich war
        if ($result) {
            // Zimmerdaten aus dem Abfrageergebnis lesen
            $zimmerData = $statement->fetch(PDO::FETCH_ASSOC);
    
            if ($zimmerData) {
                // Zimmer-Objekt erstellen und mit den Daten füllen
                $zimmer = new Zimmer();
                $zimmer->id = $zimmerData['Id'];
                $zimmer->hotelId = $zimmerData['HotelId'];
                $zimmer->raumNummer = $zimmerData['RaumNummer'];
                $zimmer->anzahlRaeume = $zimmerData['AnzahlRaeume'];
                $zimmer->kategorie = $zimmerData['Kategorie'];
    
                // JSON-Antwort mit den Zimmerdaten senden
                header('Content-Type: application/json');
                echo json_encode($zimmer);
            } else {
                // Wenn das Zimmer nicht gefunden wurde, eine entsprechende Meldung senden
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('message' => 'Das Zimmer wurde nicht gefunden.'));
            }
        } else {
            // Bei einem Fehler eine Fehlermeldung senden
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array('message' => 'Fehler beim Abrufen des Zimmers.'));
        }
    }
    

    public function createZimmer() {
        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // JSON-Daten aus der Anfrage lesen
        $jsonData = file_get_contents('php://input');
        
        // JSON-Daten in ein Array konvertieren
        $zimmerData = json_decode($jsonData, true);
    
        // Überprüfen, ob die JSON-Daten korrekt gelesen und konvertiert wurden
        if ($zimmerData) {
            // Überprüfen, ob alle erforderlichen Felder vorhanden sind
            if (isset($zimmerData['hotelId']) && isset($zimmerData['raumNummer']) && isset($zimmerData['anzahlRaeume']) && isset($zimmerData['kategorie'])) {
                // Zimmerdaten aus dem Array auslesen
                $hotelId = $zimmerData['hotelId'];
                $raumNummer = $zimmerData['raumNummer'];
                $anzahlRaeume = $zimmerData['anzahlRaeume'];
                $kategorie = $zimmerData['kategorie'];
    
                // SQL-Abfrage zum Erstellen eines neuen Zimmers
                $query = "INSERT INTO zimmer (HotelId, RaumNummer, AnzahlRaeume, Kategorie) VALUES (:hotelId, :raumNummer, :anzahlRaeume, :kategorie)";
                
                // Vorbereiten der Abfrage
                $statement = $dbConnection->prepare($query);
                
                // Parameter binden
                $statement->bindParam(':hotelId', $hotelId, PDO::PARAM_INT);
                $statement->bindParam(':raumNummer', $raumNummer, PDO::PARAM_INT);
                $statement->bindParam(':anzahlRaeume', $anzahlRaeume, PDO::PARAM_INT);
                $statement->bindParam(':kategorie', $kategorie, PDO::PARAM_INT);
                
                // Abfrage ausführen
                $result = $statement->execute();
                
                // Überprüfen, ob die Abfrage erfolgreich war
                if ($result) {
                    // Erfolgreiche Erstellung des Zimmers
                    header('HTTP/1.1 201 Created');
                    echo json_encode(array('message' => 'Das Zimmer wurde erfolgreich erstellt.'));
                } else {
                    // Bei einem Fehler eine Fehlermeldung senden
                    header('HTTP/1.1 500 Internal Server Error');
                    echo json_encode(array('message' => 'Fehler beim Erstellen des Zimmers.'));
                }
            } else {
                // Wenn erforderliche Felder fehlen, eine Fehlermeldung senden
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array('message' => 'Fehlende Felder für die Zimmererstellung.'));
            }
        } else {
            // Bei einem Fehler beim Lesen der JSON-Daten eine Fehlermeldung senden
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array('message' => 'Ungültige JSON-Daten.'));
        }
    }
    

    public function updateZimmer($zimmerId) {
        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // JSON-Daten aus der Anfrage lesen
        $jsonData = file_get_contents('php://input');
        
        // JSON-Daten in ein Array konvertieren
        $zimmerData = json_decode($jsonData, true);
    
        // Überprüfen, ob die JSON-Daten korrekt gelesen und konvertiert wurden
        if ($zimmerData) {
            // Überprüfen, ob alle erforderlichen Felder vorhanden sind
            if (isset($zimmerData['hotelId']) && isset($zimmerData['raumNummer']) && isset($zimmerData['anzahlRaeume']) && isset($zimmerData['kategorie'])) {
                // Zimmerdaten aus dem Array auslesen
                $hotelId = $zimmerData['hotelId'];
                $raumNummer = $zimmerData['raumNummer'];
                $anzahlRaeume = $zimmerData['anzahlRaeume'];
                $kategorie = $zimmerData['kategorie'];
    
                // SQL-Abfrage zum Aktualisieren des Zimmers
                $query = "UPDATE zimmer SET HotelId = :hotelId, RaumNummer = :raumNummer, AnzahlRaeume = :anzahlRaeume, Kategorie = :kategorie WHERE Id = :zimmerId";
                
                // Vorbereiten der Abfrage
                $statement = $dbConnection->prepare($query);
                
                // Parameter binden
                $statement->bindParam(':hotelId', $hotelId, PDO::PARAM_INT);
                $statement->bindParam(':raumNummer', $raumNummer, PDO::PARAM_INT);
                $statement->bindParam(':anzahlRaeume', $anzahlRaeume, PDO::PARAM_INT);
                $statement->bindParam(':kategorie', $kategorie, PDO::PARAM_INT);
                $statement->bindParam(':zimmerId', $zimmerId, PDO::PARAM_INT);
                
                // Abfrage ausführen
                $result = $statement->execute();
                
                // Überprüfen, ob die Abfrage erfolgreich war
                if ($result) {
                    // Erfolgreiche Aktualisierung des Zimmers
                    header('HTTP/1.1 200 OK');
                    echo json_encode(array('message' => 'Das Zimmer wurde erfolgreich aktualisiert.'));
                } else {
                    // Bei einem Fehler eine Fehlermeldung senden
                    header('HTTP/1.1 500 Internal Server Error');
                    echo json_encode(array('message' => 'Fehler beim Aktualisieren des Zimmers.'));
                }
            } else {
                // Wenn erforderliche Felder fehlen, eine Fehlermeldung senden
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array('message' => 'Fehlende Felder für die Zimmeraktualisierung.'));
            }
        } else {
            // Bei einem Fehler beim Lesen der JSON-Daten eine Fehlermeldung senden
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array('message' => 'Ungültige JSON-Daten.'));
        }
    }
    

    public function deleteZimmer($zimmerId) {
        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // SQL-Abfrage zum Löschen des Zimmers
        $query = "DELETE FROM zimmer WHERE Id = :zimmerId";
    
        // Vorbereiten der Abfrage
        $statement = $dbConnection->prepare($query);
    
        // Parameter binden
        $statement->bindParam(':zimmerId', $zimmerId, PDO::PARAM_INT);
    
        // Abfrage ausführen
        $result = $statement->execute();
    
        // Überprüfen, ob die Abfrage erfolgreich war
        if ($result) {
            // Erfolgreiches Löschen des Zimmers
            header('HTTP/1.1 200 OK');
            echo json_encode(array('message' => 'Das Zimmer wurde erfolgreich gelöscht.'));
        } else {
            // Bei einem Fehler eine Fehlermeldung senden
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array('message' => 'Fehler beim Löschen des Zimmers.'));
        }
    }
    
}
?>
