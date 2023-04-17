<?php

/**
 * ContactConsent short summary.
 *
 * ContactConsent description.
 *
 * @version 1.0
 * @author stephen
 */
class ContactConsent {
    public $serialNumber; //String
    public $purpose; //String
    public $channel; //String
    public $status; //String
    public $sourceCode; //String
    public $received; //Date
    public $receivedBy; //String
    public $expiry; //Date
    public $notes; //String
    public $dataHubId; //String

    public function __construct($data)
	{
        $this->purpose = "Event";
        $this->sourceCode = "Web";
        
        if (is_array($data) || is_object($data))
        {
            // If yes, then foreach() will iterate over it.
            foreach ($data AS $key => $value) $this->{$key} = $value;
            //Do something.
        }

    }

}