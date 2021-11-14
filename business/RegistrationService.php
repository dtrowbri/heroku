<?php

//require_once '../autoLoader.php';
//require_once '../database/database.php';
//require_once '../database/registrationDAO.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class RegistrationService {

    private $database = null;
    private $logger = null;
    
    public function __construct(){
        $this->logger = new Logger('main');
        $this->logger->pushHandler( new StreamHandler('php://stdout', Logger::DEBUG));
        $this->logger->debug("Creating Registration Service", ['session' => session_id()]);
        $this->database = new Database();
    }
    
    public function registerNewUser(?string $login, ?string $passwordHash){
        $this->logger->debug("Registering new user", ['session' => session_id(), 'user' => $login, 'class' => 'RegistrationService', 'method' => 'registerNewUser']);
        $conn = $this->database->getConnection();
        $dao = new RegistrationDAO();
        
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        
        $dao->addUser($login, $conn);
        if($conn->insert_id == 0){
            $this->logger->error("Error adding user to database", ['session' => session_id(), 'user' => $login, 'class' => 'RegistrationService', 'method' => 'registerNewUser']);
            $conn->rollback();
            $conn->close();
            return FALSE;
        }
        $userInsertId = $conn->insert_id;
        
        $dao->addPassword($passwordHash, $conn);
        if($conn->insert_id == 0){
            $this->logger->error("Error adding password to database", ['session' => session_id(), 'class' => 'RegistrationService', 'method' => 'registerNewUser']);
            $conn->rollback();
            $conn->close();
            return FALSE;
        }
        $passwordInsertId = $conn->insert_id;

        $isSuccess = $dao->relateUserAndPassword($userInsertId, $passwordInsertId, $conn);
        if(!$isSuccess){
            $this->logger->error("Error connecting user and password", ['session' => session_id(), 'user' => $login, 'class' => 'RegistrationService', 'method' => 'registerNewUser']);
            $conn->rollback();
            $conn->close();
            return FALSE;
        }
        $conn->commit();
        $conn->close();
        
        return TRUE;
    }
    
    public function doesLoginExist(?string $Login){
       $this->logger->debug("Verifying if login exists", ['session' => session_id(), 'user' => $Login, 'Location' => 'RegistrationService.php', 'class' => 'RegistrationService', 'method' => 'doesLoginExist']);
       $conn = $this->database->getConnection();
       $dao = new RegistrationDAO();
       $doesExist = $dao->doesLoginExist($Login, $conn);
       $conn->close();
       return $doesExist;
    }

}

?>
