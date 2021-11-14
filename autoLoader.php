<?php

session_start();

/*spl_autoload_register(function($class){
    
    $lastDirectories = substr(getcwd(), strlen(__DIR__));
    
    $numberOfLastDirectories = substr_count($lastDirectories, '\\');
    
    $directories = ["business", "database", "presentation", "presentation/login", "presentation/secrets", "presentation/signup", "business/models"];
    
    foreach($directories as $dir){
        $currentDirectory = $dir;
        for($i = 0; $i < $numberOfLastDirectories; $i++){
            $currentDirectory = "../" . $currentDirectory;
        }
        
        $classFile = $currentDirectory . "/" . $class . ".php";
        
        if(is_readable($classFile)){
            if(require $dir . "/" . $class . ".php"){
                break;
            }
        }
    }
});*/

require_once 'business/LoginService.php';
require_once 'business/RegistrationService.php';
require_once 'business/SecretsService.php';
require_once 'business/models/KVPair.php';
require_once 'business/models/Secret.php';
require_once 'database/database.php';
require_once 'database/loginDAO.php';
require_once 'database/registrationDAO.php';
require_once 'database/secretsDAO.php';
require_once __DIR__ .'/vendor/autoload.php';
?>
