<?php
class Decal
{
	protected $logger = null;
	public function __construct($path, $configuration, $logger)
	{
		$this->logger = $logger;
		$this->gitpath = new GitPath($path);
		$this->gitpath->setLogger($this->logger)
			->setMasterBranchPath($configuration['masterpath'])
			->setGhBranchPath($configuration['gh-path']);
		$this->configuration = $configuration;
	}
	
	public function copySVG()
	{
		copy($this->gitpath->getMasterPath(), $this->gitpath->computeGhPath());
	}

	public function renderThumbnail()
	{
		$converter = SvgConvert::factory($this->configuration['renderer']);
		$converter->setLogger($this->logger);
		$converter->setInfile($this->gitpath->getMasterPath())
			->setOutfile($this->gitpath->computeThumbnailPath())
			->setWidth($this->configuration['thumbnail-width'])
			->exec();
	}

	public function renderPng()
	{
		$converter = SvgConvert::factory($this->configuration['renderer']);
		$converter->setLogger($this->logger);
		$converter->setInfile($this->gitpath->getMasterPath())
			->setOutfile($this->gitpath->computePngPath())
			->setDpi($this->configuration['output-dpi'])
			->exec();
	
	}

	public function appendIndex()
	{
		$relativePath = function($path, $root) {
						str_replace($root, '/', $path);
		};
		$pathInfo = pathinfo($this->gitpath->computeGhPath());
		$index = $pathInfo['dirname'].'/index.html';
		$root = $this->configuration['gh-path'];
		
		$title = $pathInfo['filename'];		
		$png = str_replace('//', '/', str_replace($root, '/', $this->gitpath->computePngPath()));
		$thumbnail = str_replace('//', '/', str_replace($root, '/', $this->gitpath->computeThumbnailPath()));
		$svg =  str_replace('//', '/', str_replace($root, '/', $this->gitpath->computeGhPath()));

		ob_start();
		include __DIR__.'/../templates/decal.template';
		$html = ob_get_clean();

		file_put_contents($index, $html,FILE_APPEND);
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
