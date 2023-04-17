<?php

/**
 * Communication short summary.
 *
 * Communication description.
 *
 * @version 1.0
 * @author stephen
 */
class Communication {
    public $communicationId; //Date
    public $communicationRef; //String
    public $serialNumber; //String
    public $externalRef; //String
    public $externalRefType; //String
    public $category; //String
    public $communicationType; //String
    public $dateOfCommunication; //Date
    public $subject; //String
    public $notes; //Date
    public $priority; //String
    public $inOrOut; //String
    public $response; //array( undefined )
    public $segmentId; //array( undefined )
    public $author; //String
    public $created; //Date
    public $createdBy; //String
    public $modified; //Date
    public $modifiedBy; //String
    public $contactDataHubId; //String
    public $contactExternalRef; //String

    public function __construct($data)
	{

        if (is_array($data) || is_object($data))
        {
            // If yes, then foreach() will iterate over it.
            foreach ($data AS $key => $value) $this->{$key} = $value;
            //Do something.
        }

    }

}