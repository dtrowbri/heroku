<?php
require_once '../../autoLoader.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('main');
$logger->pushHandler( new StreamHandler('php://stderr', Logger::DEBUG));

session_start();

$logger->info("Session close", ['session'=> session_id(), 'class' => 'logout.php']);

session_destroy();

header("Location: ./login.php");
?>
