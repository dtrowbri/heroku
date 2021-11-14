<?php

//require_once './database.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoginDAO {
    
    private $logger = null;
    
    public function __construct(){
        $this->logger = new Logger('main');
        $this->logger->pushHandler( new StreamHandler('php://stdout', Logger::DEBUG));
        $this->logger->debug("Creating LoginDAO", ['session' => session_id(), 'class' => 'LoginDAO', 'method' => 'construct']);
    }
    
    public function getPasswordHash(?int $passwordId, $conn){
            $getPasswordHash = "select PasswordHash from passwords where PasswordId = ?";
            $stmt = $conn->prepare($getPasswordHash);
            $stmt->bind_param('i', $passwordId);
            
            try{
                $stmt->execute();
            } catch (Exception $e) {
                $this->logger->error("Error executing get password hash query", ['session' => session_id(), 'class' => 'LoginDAO', 'method' => 'getPasswordHash']);
            }
            
            $results = $stmt->get_result();
            
            if($results->num_rows == 1){
                $passwordHash = $results->fetch_assoc();
                return array_values($passwordHash)[0];
            } else {
                return "Error";
            }
    }
    
    public function getLoginId(?string $Login, $conn){
        $userIdQuery = "select UserId from users where Login = ?";
        $stmt = $conn->prepare($userIdQuery);
        $stmt->bind_param('s', $Login);
        
        try{
            $stmt->execute();
        } catch (Exception $e) {
            $this->logger->error("Error executing loginID query", ['session' => session_id(), 'user' => $Login, 'class' => 'LoginDAO', 'method' => 'getLoginId']);
        }
        
        $results = $stmt->get_result();
        
        if($results->num_rows == 1){
            return ($results->fetch_assoc())["UserId"];
        } else {
            return "Error";
        }
    }
    
    public function getPasswordId(?int $userId, $conn){
        $passwordIdQuery = "select PasswordId from userpasswords where UserId = ?";
        $stmt = $conn->prepare($passwordIdQuery);
        $stmt->bind_param('i', $userId);
        
        try{
            $stmt->execute();
        } catch (Exception $e) {
            $this->logger->error("Error executing password query", ['session' => session_id(), 'user' => $userId, 'class' => 'LoginDAO', 'method' => 'getPasswordId']);
        }
        
        $results = $stmt->get_result();
        
        if($results->num_rows == 1){
            return ($results->fetch_assoc())["PasswordId"];
        } else {
            return "Error";
        }
    }
    
    public function validatePasswordMatch(?string $PasswordHash, $PasswordId, $conn){
        $passwordMatchQuery = "select PasswordHash from passwords where PasswordId = ? and PasswordHash = ?";
        $stmt = $conn->prepare($passwordMatchQuery);
        $stmt->bind_param('is', $PasswordId, $PasswordHash);
        
        try{
            $stmt->execute();
        } catch (Exception $e) {
            $this->logger->error("Error executing password match query", ['session' => session_id(), 'class' => 'LoginDAO', 'method' => 'validatePasswordMatch']);
        }
        
        $results = $stmt->get_result();
        
        if($results->num_rows == 1){
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function getUserName(?int $userid, $conn){
        $query = "select Login from users where UserId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $userid);
        
        try{
            $stmt->execute();
        } catch (Exception $e) {
            $this->logger->error("Error executing get username query", ['session' => session_id(), 'user' => $userid, 'class' => 'LoginDAO', 'method' => 'getUserName']);
        }
        
        $results = $stmt->get_result();
        
        if($results->num_rows == 1){
            $result = $results->fetch_assoc();
            return $result["Login"];
        } else {
            return null;
        }
    }
}

?>
