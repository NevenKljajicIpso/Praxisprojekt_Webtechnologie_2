<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$pathInfo = $_SERVER['PATH_INFO'];

require_once 'ZimmerController.php';

$controller = new ZimmerController();
switch ($requestMethod) {
    case 'GET':
        if ($pathInfo === '/zimmer') {
            $controller->getZimmer();
        } elseif (preg_match('/\/zimmer\/(\d+)/', $pathInfo, $matches)) {
            $zimmerId = intval($matches[1]);
            $controller->getZimmerById($zimmerId);
        }
        break;
    case 'POST':
        if ($pathInfo === '/zimmer') {
            $controller->createZimmer();
        }
        break;
    case 'PUT':
        if (preg_match('/\/zimmer\/(\d+)/', $pathInfo, $matches)) {
            $zimmerId = intval($matches[1]);
            $controller->updateZimmer($zimmerId);
        }
        break;
    case 'DELETE':
        if (preg_match('/\/zimmer\/(\d+)/', $pathInfo, $matches)) {
            $zimmerId = intval($matches[1]);
            $controller->deleteZimmer($zimmerId);
        }
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        break;
}
?>