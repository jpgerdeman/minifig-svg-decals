<?php
/**
 * Methoden zur Ausführung von Shell-Kommandos.
 * 
 * @author: epct
 */

class Exec
{
	const LOG_ERR = 0;
	const LOG_DEBUG = 1;

	/**
	 * Führt ein Shell-Kommando im Hintergrund aus
	 *
	 * @param String $cmd
	 */
	public static function inBackground( $cmd )
	{
		self::log( $cmd );

		// Windows-Maschinen
		if( Filesystem::isWindows() )
		{
			self::log('System = Windows' );
			pclose( popen( 'start /B ' . $cmd, 'r' ) );
		}
		// *nix-Maschinen
		else
		{
			self::log('System = *nix' );
			exec( $cmd . ' >/dev/null 2>&1 &' );
		}
	}

	/**
	 * Führt ein Shell-Kommando aus und loggt seine Ausgabe.
	 *
	 * @param String $cmd
	 */
	public static function logged( $cmd )
	{
		self::log( 'Führe Shell-Kommando aus: "' . $cmd . '"' );
		

		$output = array( );
		$returnCode = 0;
		exec( $cmd . ' 2>&1', $output, $returnCode );

		$msgStatus = $returnCode == 0 ? self::LOG_DEBUG : self::LOG_ERR;

		foreach( $output as $key => $val )
		{
			self::log( $val . ' ' . $msgStatus, $msgStatus );
		}

		if( $returnCode != 0 )
		{
			$msg = 'Fehler bei der Ausführung des Shell-Kommandos "' . $cmd . '"';
			self::log( $msg, self::LOG_ERR );
			throw new ExecException( $cmd,  $returnCode, $output);
		}
	}

	/**
	 * Log-Ausgabe.
	 *
	 * Außerhalb dieser Methode sollte kein Aufruf von Logger erscheinen.
	 *
	 * @param String $msg
	 * @param Flag $prority - Default LOGGER::INFO
	 */
	protected static function log( $msg, $priority=null )
	{
		switch( $priority )
		{
			case self::LOG_DEBUG:
				$priority = Logger::DEBUG;
				break;
			case self::LOG_ERR:
				$priority = Logger::ERR;
				break;
			default:
				$priority = Logger::INFO;
				break;
		}
		$caller = Logger::getCaller();
		Logger::log($caller.$msg, $priority);
	}
}
?>