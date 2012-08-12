<?php
class Index
{
    private $title = null;
    private $path = null;
    private $currentDecal = null;
    private $template = null;
    private $config = null;
    private $logger = null;
    function __construct( $path )
    {
        $this->path = $path;
        $title = explode( DIRECTORY_SEPARATOR, dirname($path));
		$title = array_pop($title);			
        $this->setTitle($title);        
    }
    
    public function setTitle( $title )
    {
        $this->title = $title;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setPath( $path )
    {
        $this->path = $path;        
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate( $template )
    {
        $this->template = $template;
    }
        
    public function getCurrentDecal()
    {
        return $this->currentDecal;
    }

    public function setCurrentDecal( Decal $currentDecal )
    {
        $this->currentDecal = $currentDecal;
    }
    
    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig(Config $config )
    {
        $this->config = $config;
    }
    
    public function addDecal( Decal $decal )
    {
        $this->currentDecal = $decal;        
		$html = $this->renderHtml();
		$this->appendIndex($html);
    }
    protected function relativePath($path, $appendbaseurl = true)
	{
		$root = $this->config->getGhPath();
		$baseurl = $this->config->getBaseurl();

		$link = str_replace($root, DIRECTORY_SEPARATOR, $path);
		$link = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $link);
        $link = $appendbaseurl ? $baseurl.$link : $link;
		return $link;
	}
    
    private function renderHtml()
    {        
		$png = $this->relativePath( $this->currentDecal->computePngPath() );
		$thumbnail = $this->relativePath( $this->currentDecal->computeThumbnailPath() );
		$svg =  $this->relativePath( $this->currentDecal->computeTargetPath() );
        $title = $this->currentDecal->extractName();
		ob_start();
		include $this->getTemplate();
		$html = ob_get_clean();	
        return $html;
    }
    
    private function appendIndex($html)
	{        
		$file = $this->getPath();
        $this->log("appending $file ");
		file_put_contents($file, $html,FILE_APPEND);
	}


    public function reset()
	{
			@unlink($file);
			$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'index.html');
			$html = str_replace('%title%', $this->getTitle(), $html);
			file_put_contents($this->getPath(), $html);
	}
    /**
	 * Set the logger we want to use to log messages.
	 *
	 * @param Logger $logger
	 * @return $this
	 */
	public function setLogger(Logger $logger)
	{
		$this->logger = $logger;
		return $this;
	}

	/**
	 * Write a message into the log.
	 *
	 * If no logger is set no message will be logged.
	 * @see setLogger
	 *
	 * @param string $msg the message to log
	 * @param flag $severity one of the constants Logger::Debug through Logger::ERROR
	 */
	protected function log( $msg, $severity = Logger::DEBUG)
	{
		if( !is_null($this->logger) )
		{
			$this->logger->log($msg, $severity, __CLASS__);
		}
	}
}