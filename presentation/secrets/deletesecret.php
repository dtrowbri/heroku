<?php
require_once '../shared/authenticationCheck.php';
require_once '../../autoLoader.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('main');
$logger->pushHandler( new StreamHandler('php://stdout', Logger::DEBUG));

$secretId = $_POST["secretId"];

$logger->info("Deleting secret", ['session' => session_id(), 'secret_id' => $secretId, 'class' => 'deletesecret.php']);

$service = new SecretsService();
$deletionSucceeded = $service->deleteSecret($secretId);

if($deletionSucceeded){
    $logger->info("Delete secret success", ['session' => session_id(), 'secret_id' => $secretId, 'class' => 'deletesecret.php']);
    header("Location: ./secrets.php");
} else {
    $logger->error("Deleted secret failure", ['session' => session_id(), 'secret_id' => $secretId, 'class' => 'deletescrete.php']);
    echo "<p>there was an error deleting the secret";
}
?>
