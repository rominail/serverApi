<?php

function returnError(int $code, string $message)
{
    header('HTTP/2.0 ' . $code);
    echo json_encode(['error' => $message]);
    exit();
}

function returnSuccess($data)
{
    echo json_encode($data);
    exit();
}

try {
    $config = require_once 'config/env.php';
    $jwtKey = require_once 'config/jwtKey.php';
    require_once 'manager/DatabaseConnexion.php';
    require_once 'manager/AuthenticationManager.php';
    require_once 'manager/ServerManager.php';
    require 'vendor/autoload.php';
} catch (Throwable $e) {
    returnError(500, 'internal error, please retry');
}

$action = $_GET['action'] ?? null;
try {
    $userEntries = file_get_contents("php://input");
    $userEntries = json_decode($userEntries, true);
} catch (Exception $e) {
    returnError(400, 'Error while parsing user entries, the message might have been tempered');
}

$authManager = new \manager\AuthenticationManager($jwtKey);
if ($action === 'login' && isset($userEntries['name'], $userEntries['password']) && $authManager->isValidLogin($userEntries['name'], $userEntries['password'])) {
    returnSuccess([
        'jwt' => $authManager->getJwt(),
        'jsScript' => 'js/admin.js?tmp=' . time(),
        'body' => file_get_contents('admin.html')
    ]);
} elseif ($action === 'login') {
    returnError(403, 'Invalid credentials');
} elseif (isset($userEntries['jwt'])) {
    $authManager->connect($userEntries['jwt']);
}

if (!$authManager->isLogged()) {
    returnError(403, 'Authentication needed');
}

$serverManager = new \manager\ServerManager();

try {
    switch ($action) {
        case 'listServers':
            returnSuccess(['list' => $serverManager->listServers()]);
            break;
        case 'addServer':
            returnSuccess(['done' => $serverManager->addServer($userEntries['name'], $userEntries['ip'])]);
            break;
        case 'deleteServer':
            returnSuccess(['done' => $serverManager->deleteServer((int) $userEntries['id'])]);
            break;
        case 'renameServer':
            returnSuccess(['done' => $serverManager->renameServer((int) $userEntries['id'], $userEntries['name'])]);
            break;
        default:
            returnError(400, 'Error');
    }
} catch (Exception $e) {
    returnError(400, $e->getMessage());
}