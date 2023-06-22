<?php
class Database {
    private $host = 'localhost';
    private $dbName = 'LenzerheideTest';
    private $username = 'admin_LH';
    private $password = '1234';

    public function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            $dbConnection = new PDO($dsn, $this->username, $this->password, $options);
            return $dbConnection;
        } catch (PDOException $e) {
            // Bei Verbindungsfehlern eine Fehlermeldung ausgeben
            echo 'Verbindungsfehler: ' . $e->getMessage();
            exit;
        }
    }
}
?>
