<?php
/**
 * A very simple interface for writing logs. 
 */
interface Logger
{
	const EMERG   = 1; 
	const ALERT   = 2; 
	const CRIT    = 4; 
	const ERR     = 8; 
	const WARNING = 16; 
	const NOTICE  = 32; 
	const INFO    = 64; 
	const DEBUG   = 128; 
	
    /**
     * Write a message to the log.
     * 
     * @param string $msg 
     * @param flag $severity Logger::DEBUG through Logger::EMERG
     * @param string $category This can be used to determine from where the 
     *      log message comes. Some Loggers could also use this to limit the
     *      messages logged. 
     */
	public function log( $msg, $severity = self::DEBUG, $category = null);
    
    /**
     * Set the level messages have to reach before being written to the log.
     * 
     * @param flag $severity Logger::DEBUG through Logger::EMERG
     */
    public function setMinimumLogLevel($severity);
}
# vim:encoding=utf8:syntax=php

