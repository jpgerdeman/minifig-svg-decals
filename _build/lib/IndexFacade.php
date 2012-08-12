<?php
class IndexFacade
{
	private $indices = array();
    private $logger = null;
    private $config = null;
    
	public function addDecal(Decal $decal)
	{
		$this->currentDecal = $decal;
		$local = $this->fetchLocalIndex();
		$global = $this->fetchGlobalIndex();
        $local->addDecal($decal);
        $global->addDecal($decal);
	}
    
    public function fetchLocalIndex()
    {
		$file = $this->getLocalIndexPath();
        if( !isset($this->indices[$file]) )
        {
            $idx = new Index($file);
            $idx->setConfig( $this->config );
            $idx->setTemplate($this->getTemplatePath());
            $idx->reset();            
            $this->indices[$file] = $idx;
        }
        
        return $this->indices[$file];
    }
    
    public function fetchGlobalIndex()
    {
		$file = $this->getGlobalIndexPath();
        if( !isset($this->indices[$file]) )
        {
            $idx = new Index($file);
            $idx->setTitle('Decal Overview');
            $idx->setConfig( $this->config );
            $idx->setTemplate($this->getTemplatePath());
            $idx->reset();
            $this->indices[$file] = $idx;
        }
        
        return $this->indices[$file];
    }
    
	protected function getLocalIndexPath()
	{		
		$index = dirname($this->currentDecal->computeTargetPath()).DIRECTORY_SEPARATOR.'index.html';	
        return $index;
	}
        
	protected function getGlobalIndexPath()
	{		
		$index = $this->config->getGhPath().DIRECTORY_SEPARATOR.'decals'.DIRECTORY_SEPARATOR.'index.html';	
        return $index;
	}

	protected function getTemplatePath()
	{
		return $this->templatePath;
	}

	public function setTemplatePath($path)
	{
		$this->templatePath = $path;
	}
	
	public function setConfig( Config $config)
    {
        $this->config = $config;
    }

	protected function relativePath($path, $appendbaseurl = true)
	{
		$root = $this->config->getGhPath();
		$baseurl = $this->config->getBaseurl();

		$link = str_replace($root, DIRECTORY_SEPARATOR, $path);
		$link = str_replace('minifig-svg-decals', '', $link);
		$link = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $link);
        $link = $appendbaseurl ? $baseurl.$link : $link;
		return $link;
	}
    
    private function generateMenu()
    {
        $extractTitle = function($index){
            $title = explode( DIRECTORY_SEPARATOR, dirname($index));
            $title = array_pop($title);			
            return $title;
        };
        
        $computeDepth = function($index){
            $depth = explode(DIRECTORY_SEPARATOR, $index);
            $depth = count($depth);
            return $depth;
        };

	ksort( $this->indices );
        $menu = '';
        foreach($this->indices as $path => $index)
        {
            $relativePath  = $this->relativePath($path);
            $title = $extractTitle($path);
            $depth = $computeDepth($this->relativePath($path,false));
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
            $class = "depth-".$depth;
            $menu.="<li class='$class'><a class='$class' href='{{site.url}}$relativePath'>$title</a></li>".PHP_EOL;
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
