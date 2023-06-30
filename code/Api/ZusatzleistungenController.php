<?php
require_once 'Zusatzleistungen.php';
require_once 'Database.php';

class ZusatzleistungenController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getZusatzleistungen($id = null) {
        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // SQL-Abfrage zum Abrufen aller Zusatzleistungen
        $query = "SELECT * FROM zusatzleistungen";
    
        // Abfrage ausführen
        $result = $dbConnection->query($query);
        
        // Überprüfen, ob die Abfrage erfolgreich war
        if ($result) {
            // Array für die Zusatzleistungen erstellen
            $zusatzleistungenArray = array();
    
            // Zusatzleistungen aus dem Abfrageergebnis lesen und dem Array hinzufügen
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $zusatzleistungen = new Zusatzleistungen();
                $zusatzleistungen->id = $row['Id'];
                $zusatzleistungen->beschreibung = $row['Beschreibung'];
    
                // Zusatzleistungen zum Array hinzufügen
                $zusatzleistungenArray[] = $zusatzleistungen;
            }
    
            // JSON-Antwort mit den Zusatzleistungen senden
            header('Content-Type: application/json');
            echo json_encode($zusatzleistungenArray);
        } else {
            // Bei einem Fehler eine Fehlermeldung senden
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array('message' => 'Fehler beim Abrufen der Zusatzleistungen.'));
        }
    }

    public function getZusatzleistungenById($params) {
        $zusatzleistungId = $params[0];
        
        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // SQL-Abfrage zum Abrufen einer bestimmten Zusatzleistung anhand der Zusatzleistungs-ID
        $query = "SELECT * FROM zusatzleistungen WHERE Id = :id";
        
        // Vorbereiten der Abfrage
        $statement = $dbConnection->prepare($query);
        
        // Zusatzleistungs-ID als Parameter binden
        $statement->bindParam(':id', $zusatzleistungId, PDO::PARAM_INT);
        
        // Abfrage ausführen
        $result = $statement->execute();
        
        // Überprüfen, ob die Abfrage erfolgreich war
        if ($result) {
            // Zusatzleistungsdaten aus dem Abfrageergebnis lesen
            $zusatzleistungData = $statement->fetch(PDO::FETCH_ASSOC);
    
            if ($zusatzleistungData) {
                // Zusatzleistungs-Objekt erstellen und mit den Daten füllen
                $zusatzleistung = new Zusatzleistungen();
                $zusatzleistung->id = $zusatzleistungData['Id'];
                $zusatzleistung->beschreibung = $zusatzleistungData['Beschreibung'];
    
                // JSON-Antwort mit den Zusatzleistungsdaten senden
                header('Content-Type: application/json');
                echo json_encode($zusatzleistung);
            } else {
                // Wenn die Zusatzleistung nicht gefunden wurde, eine entsprechende Meldung senden
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('message' => 'Die Zusatzleistung wurde nicht gefunden.'));
            }
        } else {
            // Bei einem Fehler eine Fehlermeldung senden
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array('message' => 'Fehler beim Abrufen der Zusatzleistung.'));
        }
    }    

    public function createZusatzleistung() {
        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // JSON-Daten aus der Anfrage lesen
        $jsonData = file_get_contents('php://input');
        
        // JSON-Daten in ein Array konvertieren
        $zusatzleistungData = json_decode($jsonData, true);
    
        // Überprüfen, ob die JSON-Daten korrekt gelesen und konvertiert wurden
        if ($zusatzleistungData) {
            // Überprüfen, ob alle erforderlichen Felder vorhanden sind
            if (isset($zusatzleistungData['beschreibung'])) {
                // Zusatzleistungen aus dem Array auslesen
                $beschreibung = $zusatzleistungData['beschreibung'];
    
                // SQL-Abfrage zum Erstellen einer neuen Zusatzleistung
                $query = "INSERT INTO zusatzleistungen (Beschreibung) VALUES (:beschreibung)";
                
                // Vorbereiten der Abfrage
                $statement = $dbConnection->prepare($query);
                
                // Parameter binden
                $statement->bindParam(':beschreibung', $beschreibung, PDO::PARAM_STR);
                
                // Abfrage ausführen
                $result = $statement->execute();
                
                // Überprüfen, ob die Abfrage erfolgreich war
                if ($result) {
                    // Erfolgreiche Erstellung der Zusatzleistung
                    header('HTTP/1.1 201 Created');
                    echo json_encode(array('message' => 'Die Zusatzleistung wurde erfolgreich erstellt.'));
                } else {
                    // Bei einem Fehler eine Fehlermeldung senden
                    header('HTTP/1.1 500 Internal Server Error');
                    echo json_encode(array('message' => 'Fehler beim Erstellen der Zusatzleistung.'));
                }
            } else {
                // Wenn erforderliche Felder fehlen, eine Fehlermeldung senden
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array('message' => 'Fehlende Felder für die Zusatzleistungserstellung.'));
            }
        } else {
            // Bei einem Fehler beim Lesen der JSON-Daten eine Fehlermeldung senden
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array('message' => 'Ungültige JSON-Daten.'));
        }
    }
    
    public function updateZusatzleistung($params) {
        $zusatzleistungId = $params[0];

        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // JSON-Daten aus der Anfrage lesen
        $jsonData = file_get_contents('php://input');
        
        // JSON-Daten in ein Array konvertieren
        $zusatzleistungData = json_decode($jsonData, true);
    
        // Überprüfen, ob die JSON-Daten korrekt gelesen und konvertiert wurden
        if ($zusatzleistungData) {
            // Überprüfen, ob alle erforderlichen Felder vorhanden sind
            if (isset($zusatzleistungData['beschreibung'])) {
                // Zusatzleistungen aus dem Array auslesen
                $beschreibung = $zusatzleistungData['beschreibung'];
    
                // SQL-Abfrage zum Aktualisieren der Zusatzleistung
                $query = "UPDATE zusatzleistungen SET Beschreibung = :beschreibung WHERE Id = :zusatzleistungId";
                
                // Vorbereiten der Abfrage
                $statement = $dbConnection->prepare($query);
                
                // Parameter binden
                $statement->bindParam(':beschreibung', $beschreibung, PDO::PARAM_STR);
                $statement->bindParam(':zusatzleistungId', $zusatzleistungId, PDO::PARAM_INT);
                
                // Abfrage ausführen
                $result = $statement->execute();
                
                // Überprüfen, ob die Abfrage erfolgreich war
                if ($result) {
                    // Erfolgreiche Aktualisierung der Zusatzleistung
                    header('HTTP/1.1 200 OK');
                    echo json_encode(array('message' => 'Die Zusatzleistung wurde erfolgreich aktualisiert.'));
                } else {
                    // Bei einem Fehler eine Fehlermeldung senden
                    header('HTTP/1.1 500 Internal Server Error');
                    echo json_encode(array('message' => 'Fehler beim Aktualisieren der Zusatzleistung.'));
                }
            } else {
                // Wenn erforderliche Felder fehlen, eine Fehlermeldung senden
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array('message' => 'Fehlende Felder für die Zusatzleistungsaktualisierung.'));
            }
        } else {
            // Bei einem Fehler beim Lesen der JSON-Daten eine Fehlermeldung senden
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array('message' => 'Ungültige JSON-Daten.'));
        }
    }
    
    public function deleteZusatzleistung($params) {
        $zusatzleistungId = $params[0];

        // Verbindung zur Datenbank herstellen
        $dbConnection = $this->db->connect();
    
        // SQL-Abfrage zum Löschen der Zusatzleistung
        $query = "DELETE FROM zusatzleistungen WHERE Id = :zusatzleistungId";
    
        // Vorbereiten der Abfrage
        $statement = $dbConnection->prepare($query);
    
        // Zusatzleistung-ID als Parameter binden
        $statement->bindParam(':zusatzleistungId', $zusatzleistungId, PDO::PARAM_INT);
    
        // Abfrage ausführen
        $result = $statement->execute();
    
        // Überprüfen, ob die Abfrage erfolgreich war
        if ($result) {
            // Erfolgreiches Löschen der Zusatzleistung
            header('HTTP/1.1 200 OK');
            echo json_encode(array('message' => 'Die Zusatzleistung wurde erfolgreich gelöscht.'));
        } else {
            // Bei einem Fehler eine Fehlermeldung senden
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array('message' => 'Fehler beim Löschen der Zusatzleistung.'));
        }
    }
}
?>