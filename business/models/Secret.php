<?php

class Secret{
    
    private $secretId = null;
    private $secretName = null;
    
    public function __construct(?int $secretId, ?string $secretName){
        $this->secretId = $secretId;
        $this->secretName = $secretName;
    }
    
    public function getSecretId(){
        return $this->secretId;
    }
    
    public function getSecretName(){
        return $this->secretName;
    }
    
}

?>