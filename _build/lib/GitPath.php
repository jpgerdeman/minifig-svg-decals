<?php
class GitPath
{
	private $path;
	private $logger = null;
	public function __construct($path)
	{
		$this->path = $path;
	}

	public function getMasterPath()
	{
		return $this->path;
	}

	public function computeGhPath()
	{
		return str_replace($this->masterPath, $this->ghPath, $this->path);
	}

	public function computePngPath()
	{
		return $this->computeGhPath().'.png';
	}

	public function computeThumbnailPath()
	{
		return $this->computeGhPath().'.tb.png';
	}

	public function isSvg()
	{
		return $this->endswith('.svg');
	}

	public function setMasterBranchPath($path)
	{
		$this->masterPath = $path;
		return $this;
	}

	public function setGhBranchPath($path)
	{
		$this->ghPath = $path.'/decals';
		return $this;
	}

	private function endswith($needle)
	{
		$length = strlen($needle);
		if ($length == 0) 
		{
        		return true;
    		}

		return (substr($this->path, -$length) === $needle);
	}
	
	public function setLogger($logger)
	{
		$this->logger = $logger;
		return $this;
	}

	protected function log( $msg, $severity = Logger::DEBUG)
	{
			if( !is_null($this->logger) )
			{
				$this->logger->log($msg, $severity, __CLASS__."(".$this->path.")");
			}
	}
}
