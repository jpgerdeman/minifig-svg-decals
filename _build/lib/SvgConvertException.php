<?php
class SvgConvertException extends Exception
{
	protected $cmd = '';
	protected $rc = null;
	protected $results = '';
	protected $message = '';

    public function __construct( $cmd, $rc = '', $results = array())
    {
        $this->rc = $rc;
        $this->results = $results;
	$this->message = implode("<br/>", $results);
		
	$msg = "ERROR processing the following command:<br/><br/>";
	$msg.= $cmd."<br/><br/>";
	$msg.= $this->message;
        parent::__construct( $msg );
    }
    
    public function __toString()
    {
        return __CLASS__ . ": ".$this->cmd." [{".$this->rc."}]: {".$this->message."}\n";
    }
}
# vim:encoding=utf8:syntax=php

