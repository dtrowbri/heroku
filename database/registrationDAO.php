<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class RegistrationDAO {
    
    private $logger = null;
    
    public function __construct(){
        $this->logger = new Logger('main');
        $this->logger->pushHandler( new StreamHandler('php://stdout', Logger::DEBUG));
        $this->logger->debug("Creating RegistrationDAO", ['session' => session_id(), 'class' => 'RegistrationDAO', 'method' => 'construct']);
    }
    
    public function addUser(?string $user, $conn){
        $addUserQuery = "insert into users (`UserId`,`Login`) values (null,?)";
        $stmt = $conn->prepare($addUserQuery);
        $stmt->bind_param('s', $user);
        
        try{
            $stmt->execute();
        } catch (Exception $e) {
            $this->logger->error("Error executing add user query", ['session' => session_id(), 'user' => $user, 'class' => 'RegistrationDAO', 'method' => 'addUser']);
        }
        
        if($stmt->affected_rows == 1){
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function addPassword(?string $passwordHash, $conn){      
        $addPasswordQuery = "insert into passwords (`PasswordId`,`PasswordHash`) values (null,?)";
        $stmt = $conn->prepare($addPasswordQuery);
        $stmt->bind_param('s', $passwordHash);
        
        try{
            $stmt->execute();
        } catch (Exception $e) {
            $this->logger->error("Error executing add password query", ['session' => session_id(), 'class' => 'RegistrationDAO', 'method' => 'addPassword']);
        }
        
        if($stmt->affected_rows == 1){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function relateUserAndPassword(?int $UserId, ?int $passwordId, $conn){
        $relationQuery = "insert into userpasswords (`UserId`, `PasswordId`) values (?,?)";
        $stmt = $conn->prepare($relationQuery);
        $stmt->bind_param('ii', $UserId, $passwordId);
        
        try{
            $stmt->execute();
        } catch (Exception $e) {
            $this->logger->error("Error executing relate user password query", ['session' => session_id(), 'user' => $UserId, 'class' => 'RegistrationDAO', 'method' => 'relateUserAndPassword']);
        }
        
        if($stmt->affected_rows == 1){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function doesLoginExist(?string $Login, $conn){
        $userQuery = "select * from users where Login = ?";
        $stmt = $conn->prepare($userQuery);
        $stmt->bind_param('s', $Login);
     
        try{
            $stmt->execute();
        } catch (Exception $e) {
            $this->logger->error("Error executing add secret query", ['session' => session_id(), 'user' => $Login, 'class' => 'RegistrationDAO', 'method' => 'doesLoginExist']);
        }
        
        $results = $stmt->get_result();
        
        if($results->num_rows > 0){
            return true;
        } else {
            return false;
        }
    }
}

?>
