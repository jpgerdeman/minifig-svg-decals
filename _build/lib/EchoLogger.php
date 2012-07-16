<?php

class EchoLogger implements Logger
{
	public function log( $msg, $severity = self::DEBUG, $category = null )
	{
		$time = date( 'Y.m.d H:i:s' );
		$severity = strtoupper($this->mapSeverityLevel( $severity ));
		$nl = $this->isCLi()?PHP_EOL:"<br/>".PHP_EOL;
		echo $time." ".$severity." ".$category.": ".$msg.$nl.$nl;
	}
    
    protected function isCli() {
 
     if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
          return true;
     } else {
          return false;
     }
}

	public function mapSeverityLevel( $lvl )
	{
		switch( $lvl )
		{
			case self::EMERG: 
				return 'emergency';
				break;
			case self::ALERT: 
				return 'alert';
				break;
			case self::CRIT: 
				return 'crit';
				break;
			case self::ERR: 
				return 'error';
				break;
			case self::WARNING: 
				return 'warning';
				break;
			case self::NOTICE: 
				return 'notice';
				break;
			case self::INFO: 
				return 'info';
				break;
			case self::DEBUG: 
				return 'debug';
				break;
		}
	}
}
