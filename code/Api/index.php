<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once 'ZimmerController.php';

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
    ],
    'POST' => [
        '/zimmer' => 'createZimmer',
    ],
    'PUT' => [
        '/zimmer/(\d+)' => 'updateZimmer',
    ],
    'DELETE' => [
        '/zimmer/(\d+)' => 'deleteZimmer',
    ],
];

// ZimmerController-Objekt erstellen
$zimmerController = new ZimmerController();

// Überprüfe, ob die angeforderte Route definiert ist, und rufe die entsprechende Callback-Funktion auf
if (isset($routes[$requestMethod])) {
    foreach ($routes[$requestMethod] as $route => $callback) {
        $pattern = '#^' . $route . '$#';
        if (preg_match($pattern, $pathInfo, $matches)) {
            $params = array_slice($matches, 1);
            call_user_func_array([$zimmerController, $callback], $params);
            break; // Schleife verlassen, nachdem eine passende Route gefunden wurde
        }
    }
}

// Wenn keine passende Route gefunden wurde, zeige eine Fehlermeldung an
header('HTTP/1.1 404 Not Found');
echo '404 Not Found';
?>
