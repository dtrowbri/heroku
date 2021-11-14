<?php


class KVPair{
    
    private $KeyId = null;
    private $Key = NULl;
    private $Value = null;
    
    public function __construct(?int $keyid, ?string $key, ?string $value){
        $this->KeyId = $keyid;
        $this->Key = $key;
        $this->Value = $value;
    }
    
    public function getKeyId(){
        return $this->KeyId;
    }
    
    public function getKey(){
        return $this->Key;
    }
    
    public function getValue(){
        return $this->Value;
    }
}
?>