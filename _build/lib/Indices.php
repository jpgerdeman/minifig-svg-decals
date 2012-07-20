<?php
class IndiexFacade
{
	private $indices = array();
    private $logger = null;
    private $config = null;
	public function addDecal(Decal $decal)
	{
		$this->currentDecal = $decal;
		$this->resetLocalIndexIfNecessary();
		$this->resetGlobalIndexIfNecessary();
		$html = $this->renderHtml();
		$this->appendLocalIndex($html);
		$this->appendGlobalIndex($html);
	}

	protected function resetLocalIndexIfNecessary()
	{
		$file = $this->getLocalIndexPath();
		$title = explode( DIRECTORY_SEPARATOR, realpath(dirname($this->currentDecal->computeTargetPath())));
		$title = array_pop($title);			
		$this->resetIndexIfNecessary($file, $title);
	}

	protected function resetGlobalIndexIfNecessary()
	{
		$file = $this->getGlobalIndexPath();
		$this->resetIndexIfNecessary($file, 'Decal Overview');
	}
	
	protected function resetIndexIfNecessary( $file, $title )
	{
		if( !isset( $this->indices[$file] ) )
		{
            $this->log( "resetting $file" );
			@unlink($file);
			$html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates/index.html');
			$html = str_replace('%title%', $title, $html);
			file_put_contents($file, $html);

			$this->indices[$file] = $file;
		}
	}

	protected function appendLocalIndex($html)
	{        
		$file = $this->getLocalIndexPath();
        $this->log("appending $file ");
		file_put_contents($file, $html,FILE_APPEND);
	}

	protected function appendGlobalIndex($html)
	{
		$file = $this->getGlobalIndexPath();
        $this->log("appending $file ");
		file_put_contents($file, $html,FILE_APPEND);
	}
    
	protected function getLocalIndexPath()
	{		
		$index = dirname($this->currentDecal->computeTargetPath()).DIRECTORY_SEPARATOR.'index.html';	
        return $index;
	}
    
    
	protected function getGlobalIndexPath()
	{		
		$index = dirname($this->config->getGhPath()).DIRECTORY_SEPARATOR.'index.html';	
        return $index;
	}

	protected function renderHtml()
	{
		$png = $this->relativePath( $this->currentDecal->computePngPath() );
		$thumbnail = $this->relativePath( $this->currentDecal->computeThumbnailPath() );
		$svg =  $this->relativePath( $this->currentDecal->computeTargetPath() );
        $title = $this->currentDecal->extractName();
		ob_start();
		include $this->getTemplatePath();
		$html = ob_get_clean();	
        return $html;
	}

	protected function getTemplatePath()
	{
		return $this->templatePath;
	}

	public function setTemplatePath($path)
	{
		$this->templatePath = $path;
	}
	
	protected function relativePath($path, $appendbaseurl = true)
	{
		$root = $this->config->getGhPath();
		$baseurl = $this->config->getBaseurl();

		$link = str_replace($root, '/', $path);
		$link = str_replace('//', '/', $link);
        $link = $appendbaseurl ? $baseurl.$link : $link;
		return $link;
	}
    
    public function setConfig( Config $config)
    {
        $this->config = $config;
    }
    
    private function generateMenu()
    {
        $extractTitle = function($index){
            $title = explode( DIRECTORY_SEPARATOR, dirname($index));
            $title = array_pop($title);			
            return $title;
        };
        
        $computeDepth = function($index){
            $depth = explode(DIRECTORY_SEPARATOR, $relativePath);
            $depth = count($depth);
            return $depth;
        };
        
        $menu = '';
        foreach($this->indices as $index)
        {
            $relativePath  = $this->relativePath($index);
            $title = $extractTitle($index);
            $depth = $computeDepth($this->relativePath($index,false));
            
            $class = "{{active}} depth-".$depth;
            $menu.="<li class='$class'><a class='$class' href='$relativePath'>$title</a></li>".PHP_EOL;
        }
        
        return '<ul>'.PHP_EOL.$menu.PHP_EOL.'</ul>';
    }
    
    public function writeMenu()
    {
        $menu = $this->generateMenu();
        $menufile = $this->config->getGhPath().DIRECTORY_SEPARATOR.'_includes'.DIRECTORY_SEPARATOR.'decal_menu';
        $this->log( 'Writing menu '.$menufile, Logger::INFO);
        @unlink($menufile);
        file_put_contents($menufile, $menu);
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
