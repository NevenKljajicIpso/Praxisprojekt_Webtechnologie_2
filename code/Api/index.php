<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once 'ZimmerController.php';
require_once 'ZusatzleistungenController.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$requestUri = $_SERVER['REQUEST_URI'];
$baseUrl = '/Webtechnologie_2/Praxisprojekt_Webtechnologie_2/code/Api';
$pathInfo = substr($requestUri, strlen($baseUrl));

// Definiere deine Routen und die zugehörigen Callback-Funktionen
$routes = [
    'GET' => [
        '/zimmer' => 'getZimmer',
        '/zimmer/(\d+)' => 'getZimmerById',
        '/zusatzleistungen' => 'getZusatzleistungen',
        '/zusatzleistungen/(\d+)' => 'getZusatzleistungenById',
    ],
    'POST' => [
        '/zimmer' => 'createZimmer',
        '/zusatzleistungen' => 'createZusatzleistung',
    ],
    'PUT' => [
        '/zimmer/(\d+)' => 'updateZimmer',
        '/zusatzleistungen/(\d+)' => 'updateZusatzleistung',
    ],
    'DELETE' => [
        '/zimmer/(\d+)' => 'deleteZimmer',
        '/zusatzleistungen/(\d+)' => 'deleteZusatzleistung',
    ],
];

// ZimmerController-Objekt erstellen
$zimmerController = new ZimmerController();

// ZusatzleistungenController-Objekt erstellen
$zusatzleistungenController = new ZusatzleistungenController();

// Überprüfe, ob die angeforderte Route definiert ist, und rufe die entsprechende Callback-Funktion auf
if (isset($routes[$requestMethod])) {
    foreach ($routes[$requestMethod] as $route => $callback) {
        $pattern = '#^' . $route . '$#';
        if (preg_match($pattern, $pathInfo, $matches)) {
            $params = array_slice($matches, 1);
            
            if (strpos($route, 'zimmer') !== false) {
                // Zimmer-Route
                call_user_func_array([$zimmerController, $callback], $params);
            } elseif (strpos($route, 'zusatzleistungen') !== false) {
                // Zusatzleistungen-Route
                call_user_func_array([$zusatzleistungenController, $callback], $params);
            }
            
            break; // Schleife verlassen, nachdem eine passende Route gefunden wurde
        }
    }
}
?>