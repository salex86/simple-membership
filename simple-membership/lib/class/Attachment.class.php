<?php

/**
 * Attachment short summary.
 *
 * Attachment description.
 *
 * @version 1.0
 * @author stephen
 */
class Attachment {

    public $attachmentRef; //int
    public $ref; //Date
    public $refType; //String
    public $description; //String
    public $priority; //String
    public $extension; //String
    public $attachType; //String
    public $fileName; //String
    public $attachmentBytes; //String

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