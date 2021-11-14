<?php
require_once '../shared/authenticationCheck.php';
require_once '../../autoLoader.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('main');
$logger->pushHandler( new StreamHandler('php://stdout', Logger::DEBUG));

$secretId = $_POST["secretId"];
$numOfKVPairs = $_POST['numOfKVPairs'];

$logger->info("Updating secret", ['session' => session_id(), 'secret_id' => $secretId, 'class' => 'updatesecret.php']);

$KVPairs = array();

for($i = 1; $i <= $numOfKVPairs; $i++){
    $keyId = "keyId" . $i;
    $key = "key" . $i;
    $value = "value" . $i;
    
    $KVPair = new KVPair($_POST[$keyId], $_POST[$key], $_POST[$value]);
    array_push($KVPairs, $KVPair);
}

$service = new SecretsService();
$isSuccessful = $service->updateKVPair($KVPairs);
if($isSuccessful){
    $logger->info("Update secret successful", ['session' => session_id(), 'secret_id' => $secretId, 'class' => 'updatesecret.php']);
    echo "Successful: " . true;
}else {
    $logger->error("Update secret failed", ['session' => session_id(), 'secret_id' => $secretId, 'class' => 'updatesecret.php']);
    echo "Successful: " . true;
}

if(!$isSuccessful){
    require_once '../../_header.php';
    echo '<p>Error: Failed to update the key or value!</p>';
    require_once '../../_footer.php';
} else {
    header("Location: ./secrets.php");
}
?>
