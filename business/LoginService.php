<?php

//require_once '../autoLoader.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;



class LoginService {
    
    private $database = null;
    private $logger = null;
    
    public function __construct(){
        $this->logger = new Logger('main');
        $this->logger->pushHandler( new StreamHandler('php://stdout', Logger::DEBUG));
        $this->logger->debug("Creating LoginService", ["session"=> session_id(), 'class' => 'LoginService', 'method' => 'construct']);
        $this->database = new Database();
    }
    
    public function validateLogin(?string $Login, ?string $passwordHash){
        $this->logger->debug("Validating Login", ['session' => session_id(), 'Login' => $Login, 'class' => 'LoginService', 'method' => 'validateLogin']);
        $conn = $this->database->getConnection();
        $dao = new LoginDAO();
        
        $userId = $dao->getLoginId($Login, $conn);
        if($userId == "Error"){
            $this->logger->error("Error occured retrieving userid from database.", ['session' => session_id(), 'class' => 'LoginService', 'method' => 'validateLogin']);
            $conn->close();
            return FALSE;
        }

        $passwordId = $dao->getPasswordId($userId, $conn);
        if($passwordId == "Error"){
            $this->logger->error("Error occured retrieving password from database.", ['session' => session_id(), 'class' => 'LoginService', 'method' => 'validateLogin']);
            $conn->close();
            return FALSE;
        }

        $authenticated = $dao->validatePasswordMatch($passwordHash, $passwordId, $conn);
        $conn->close();

        if($authenticated){
            return TRUE;
        } else {
            return FALSE;
        }

    }
    
    public function getUserId(?string $Login){
        $this->logger->debug("Retrieving user id", ['session' => session_id(), 'Login' => $Login, 'class' => 'LoginService', 'method' => 'getUserId']);
        $conn = $this->database->getConnection();
        $dao = new LoginDAO();
        $userId = $dao->getLoginId($Login, $conn);
        $conn->close();
        return $userId;
    }
    
    public function getUserName(?string $userId){
        $this->logger->debug("Retrieving user name", ['session' => session_id(), 'userid' => $userId, 'class' => 'LoginService', 'method' => 'getUserName']);
        $conn = $this->database->getConnection();
        $dao = new LoginDAO();
        $userName = $dao->getUserName($userId, $conn);
        $conn->close();
        return $userName;
    }
}

?>
