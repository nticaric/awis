<?php namespace Nticaric\Awis;

class Awis {
    
    protected $accessKeyId;
    protected $secretAccessKey;

    public function __construct($accessKeyId, $secretAccessKey)
    {
        $this->accessKeyId     = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
    }
    
}