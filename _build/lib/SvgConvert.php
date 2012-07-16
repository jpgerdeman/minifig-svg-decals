<?php
abstract class SvgConvert
{
	protected $dpi = null;
	protected $width = null;
	protected $infile = '';
	protected $outfile = '';
	protected $logger = null;

	public function setInfile($path)
	{
		$this->infile = $path;
		return $this;
	}

	public function setOutfile($path, $format = 'png' )
	{
		$this->outfile = $path;
		$this->format = $format;
		return $this;
	}

	public function setDpi($dpi)
	{
		$this->dpi = $dpi;
		return $this;
	}

	public function setWidth($width)
	{
		$this->width = $width;
		return $this;
	}

	public function exec()
	{
		$output = array();
		$returncode = 0;
		$cmd = $this->generateCommand();
		$this->log("executing ".$cmd );

		exec( $cmd, $output, $returncode );

		$msgStatus = $returncode == 0 ? Logger::DEBUG : Logger::ERR;

		foreach( $output as $key => $val )
		{
			$this->log( $val, $msgStatus );
		}

		if( $returncode != 0 )
		{
			throw new SvgConvertException($cmd, $returncode, $output);
		}
	}

	abstract protected function generateCommand();

	static public function factory( $converter = 'rsvg' )
	{
		$class = __CLASS__.ucfirst($converter);
		return new $class;
	}

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}

	protected function log( $msg, $severity = Logger::DEBUG)
	{
			if( !is_null($this->logger) )
			{
				$this->logger->log($msg, $severity, __CLASS__);
			}
	}
}
