<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SecretsService {
    
    private $database = null;
    private $logger = null;
    
    public function __construct(){
        $this->logger = new Logger('main');
        $this->logger->pushHandler( new StreamHandler('php://stdout', Logger::DEBUG));
        $this->logger->debug("Creating Secrets Service", ['session' => session_id(), 'class' => 'SecretService', 'method' => 'construct']);
        $this->database = new Database();
    }
    
    public function addSecrets(?string $userId, ?string $secretName,  $KVPairs){
        $this->logger->debug("Adding a secret", ['session' => session_id(), 'user' => $userid, 'secret' => $secretName, 'class' => 'SecretService', 'method' => 'addSecrets']);
        $secretsDAO = new SecretsDAO();
        $userDAO = new LoginDAO();
        
        $conn = $this->database->getConnection();
        $conn->autocommit(FALSE);
        $conn->begin_transaction();

        $secretsDAO->addSecret($secretName, $conn);
        if($conn->insert_id == 0){
            $this->logger->error("Error adding secret to database", ['session' => session_id(), 'secret' => $secretName, 'class' => 'SecretService', 'method' => 'addSecrets']);
            $conn->rollback();
            $conn->close();
            return "Error";
        }
        $secretId = $conn->insert_id;
        
        foreach($KVPairs as $KVPair){
            $secretsDAO->addKeyValuePair($KVPair->getKey(), $KVPair->getValue(), $conn);
            if($conn->insert_id == 0){
                $this->logger->error("Error adding a key to secret ", ['session' => session_id(), 'secret' => $secretName, 'class' => 'SecretService', 'method' => 'addSecrets']);
                $conn->rollback();
                $conn->close();
                return "Error";
            }
            $keyId = $conn->insert_id;
    
            $successfulSKV = $secretsDAO->relateSecretAndKeyValue($secretId, $keyId, $conn);
            if(!$successfulSKV){
                $this->logger->error("Error relating a key to a secret to user", ['session' => session_id(), 'secret' => $secretName, 'class' => 'SecretService', 'method' => 'addSecrets']);
                $conn->rollback();
                $conn->close();
                return "Error";
            }
        }
        $successfulUS = $secretsDAO->relateUserAndSecret($userId, $secretId, $conn);
        if(!$successfulUS){
            $this->logger->error("Error relating a secret to the user", ['session' => session_id(), 'secret' => $secretName, 'class' => 'SecretService', 'method' => 'addSecrets']);
            $conn->rollback();
            $conn->close();
            return "Error";
        }
        
        $conn->commit();
        $conn->close();
        
        return TRUE;
    }
    
    public function getSecrets(?int $userId){
        $this->logger->debug("Retrieving a secret", ['session' => session_id(), 'user' => $userId, 'Location' => 'SecretsService.php', 'class' => 'SecretService', 'method' => 'getSecrets']);
        $dao = new SecretsDAO();
        $secretsArr = array();
        
        $conn = $this->database->getConnection();
        
        $results = $dao->getUserSecretsList($userId, $conn);
        if($results == null){
            $conn->close();
            return null;
        }
        foreach($results as $result){
           $secret = $dao->getSecret($result, $conn);
           if($secret != null){
               array_push($secretsArr, $secret);
           }
        }
        
        $conn->close();
        return $secretsArr;
    }
    
    public function getKVPair(?int $secretId){
        $this->logger->debug("Retrieving a Key-Value pair", ['session' => session_id(), 'secret' => $secretId, 'class' => 'SecretService', 'method' => 'getKVPair']);
        $conn = $this->database->getConnection();
        $dao = new SecretsDAO();
        $keyIds = $dao->getKeyIds($secretId, $conn);

        if($keyIds == null){
            $conn->close();
            return null;
        }
        
        $KVPairs = array();

        foreach($keyIds as $key){
            $kvpair = $dao->getKVPair($key[0], $conn);
            array_push($KVPairs, $kvpair);
        }
        $conn->close();
        return $KVPairs;
    }

    public function deleteSecret(?int $secretId){
        $this->logger->debug("Deleting a secret", ['session' => session_id(), 'secret' => $secretId, 'class' => 'SecretService', 'method' => 'deleteSecret']);
        $dao = new SecretsDAO();
        $conn = $this->database->getConnection();
        
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        
        
        $keyIds = $dao->getKeyIds($secretId, $conn);

        $secretsKeyDeleted = $dao->deleteSecretsKeys($secretId, $conn);
        if(!$secretsKeyDeleted){
            $this->logger->error("Error deleting relation between secret and keys", ['session' => session_id(), 'secret' => $secretId, 'class' => 'SecretService', 'method' => 'deleteSecret']);
            $conn->rollback();
            $conn->close();
            return FALSE;
        }

        foreach($keyIds as $key){

            $KVPairDeleted = $dao->deleteKVPair($key[0], $conn);
            if(!$KVPairDeleted){
                $this->logger->error("Error deleting related Key-Value pair", ['session' => session_id(), 'secret' => $secretId, 'class' => 'SecretService', 'method' => 'deleteSecret']);
                $conn->rollback();
                $conn->close();
                return FALSE;
            }
        }

        $secretDeleted = $dao->deleteSecret($secretId, $conn);
        if(!$secretDeleted){
            $this->logger->error("Error deleting secret", ['session' => session_id(), 'secret' => $secretId, 'class' => 'SecretService', 'method' => 'deleteSecret']);
            $conn->rollback();
            $conn->close();
            return FALSE;
        }
        $conn->commit();
        $conn->close();
        return TRUE;
    }

    public function updateKVPair($kvpairs){
        $this->logger->debug("Updating a secret", ['session' => session_id(), 'Location' => 'SecretsService.php', 'class' => 'SecretService', 'method' => 'updateKVPair']);
        $conn = $this->database->getConnection();
        $dao = new SecretsDAO();
        
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        
        foreach($kvpairs as $kvpair){
            $noChange = false;
            $currentKVPair = $dao->getKVPair($kvpair->getKeyId(), $conn);
            
            if($currentKVPair->getKey() == $kvpair->getKey()){
                if($currentKVPair->getValue() == $kvpair->getValue()){
                    $noChange = true;
                }
            }
            
            if(!$noChange){
                $isSuccessful = $dao->updateKVPair($kvpair, $conn);
                if(!$isSuccessful){
                    $this->logger->error("Error updating key for secret", ['session' => session_id(), 'class' => 'SecretService', 'method' => 'updateKVPair']);
                    $conn->rollback();
                    $conn->close();
                    return false;
                }
            }
        }
        $conn->commit();
        $conn->close();
        return true;
    }

    public function doesSecretExist(?int $userId, ?string $secretName){
        $this->logger->debug("Verifying secret exists", ['session' => session_id(), 'user' => $userid, 'secret' => $secretName, 'class' => 'SecretService', 'method' => 'doesSecretExist']);
        $conn = $this->database->getConnection();
        $dao = new SecretsDAO();
        $doesExist = $dao->doesSecretExist($secretName, $userId, $conn);
        $conn->close();
        if($doesExist){
            return true;
        }
        return false;
    }
}
?>
