<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: text/html; charset=utf-8');

require_once(__DIR__ . '/bootstrap/start.php');

$oRouter = Router::getInstance();
$oRouter->Exec(isset($aRouterParams) ? $aRouterParams : array());