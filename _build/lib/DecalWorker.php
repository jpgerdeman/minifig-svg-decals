<?php
/**
 * Does work on a Decal instance. 
 *
 * Abstracts Rendering and copying of decals. We make a distinction
 * between a thumbnail and png. Though technically both are pngs, 
 * thumbnail denotes a smaller preview image, while png denotes a
 * ready to print picture.
 */
class DecalWorker
{
	/**
	 * @var Logger
	 */
	protected $logger = null;
    protected $config = null;
    protected $decal = null;
	/**
	 * Creates a new DecalWorker instance.
	 *
	 * @param Decal $decal a Decal Representation
	 */
	public function setDecal(Decal $decal)
	{
		$this->decal = $decal;
        return $this;
	}

	/**
	 * Copies a Decal from the masterbranch to the gh-pages branch.	 *
	 */	
	public function copySVG()
	{
		copy($this->decal->getSourcePath(), $this->decal->computeTargetPath());
	}

	/**
	 * Renders a Thumbnail of the decal.
	 *
	 * The Thumbnail Renderer has to be set 
	 * @see setThumbnailRenderer
	 */
	public function renderThumbnail()
	{
		$converter = $this->thumbnailRenderer;
		$converter->setInfile($this->decal->getSourcePath())
			->setOutfile($this->decal->computeThumbnailPath())
			->exec();
	}

	/**
	 * Renders a Png of the decal.
	 *
	 * The Png Renderer has to be set 
	 * @see setPngRenderer
	 */
	public function renderPng()
	{
		$converter = $this->pngRenderer;
		$converter->setInfile($this->decal->getsourcePath())
			->setOutfile($this->decal->computePngPath())
			->exec();
	
	}

	public function appendIndex()
	{
	
		$pathInfo = pathinfo($this->gitpath->computeGhPath());
		$index = $pathInfo['dirname'].'/index.html';
		
		$title = $pathInfo['filename'];		
		
		file_put_contents($index, $html,FILE_APPEND);
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
	 * Set the renderer for the thumbnail.
	 *
	 * @param SvgConvert $renderer
	 * @return $this
	 */
	public function setThumbnailRenderer(SvgConvert $renderer)
	{
		$this->thumbnailRenderer = $renderer;
		return $this;
	}

	/**
	 * Set the renderer for the png image.
	 *
	 * @param SvgConvert $renderer
	 * @return $this
	 */
	public function setPngRenderer(SvgConvert $renderer)
	{
		$this->pngRenderer = $renderer;
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
