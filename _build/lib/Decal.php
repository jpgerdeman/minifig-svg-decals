<?php
class Decal
{
	
	private $path;
	private $config;
	private $logger = null;

	public function __construct($path, $config)
	{
		$this->path = $path;
		$this->setConfig($config);
	}
    
    public function extractName()
    {
        $pathinfo = pathinfo($this->getSourcePath());
		$title = $pathinfo['filename'];
        return $title;
    }

	public function getSourcePath()
	{
		return $this->path;
	}

	public function computeTargetPath()
	{
		return str_replace($this->masterPath, $this->ghPath, $this->path);
	}

	public function computePngPath()
	{
		return $this->computeTargetPath().'.png';
	}

	public function computeThumbnailPath()
	{
		return $this->computeTargetPath().'.tb.png';
	}

	
	public function setMasterBranchPath($path)
	{
		$this->masterPath = $path;
		return $this;
	}

	public function setGhBranchPath($path)
	{
		$this->ghPath = $path.DIRECTORY_SEPARATOR.'decals';
		return $this;
	}

	public function setConfig( Config $config )
	{
		$this->config = $config;
		$this->setMasterBranchPath($config->getMasterPath());
		$this->setGhBranchPath($config->getGhPath());
		return $this;
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
