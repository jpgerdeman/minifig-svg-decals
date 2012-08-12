<?php
/**
 * Decorator for the DecalWorker.
 *
 * Instead of adding functions to DecalWorker or creating
 * a subclass we add functions by decorating a DecalWorker 
 * Instance.
 */
class DecalWorkerDecorator
{
	/**
	 * @var Logger
	 */
    protected $worker = null;

    public function __construct( DecalWorker $worker )
    {
    	$this->worker = $worker;
    }

	public function setDecal(Decal $decal)
	{
		$this->worker->setDecal($decal);
        return $this;
	}

	public function copySVG()
	{
		$this->worker->copySVG();
	}

	public function renderThumbnail()
	{
		$this->worker->renderThumbnail();
	}

	public function renderPng()
	{
		$this->worker->renderPng();	
	}

	public function appendIndex()
	{
		$this->worker->appendIndex();		
	}

	public function setLogger(Logger $logger)
	{
		$this->worker->setLogger($logger);
		return $this;
	}

	public function setThumbnailRenderer(SvgConvert $renderer)
	{
		$this->worker->setThumbnailRenderer($renderer);
		return $this;
	}

	public function setPngRenderer(SvgConvert $renderer)
	{
		$this->worker->setPngRenderer($renderer);
		return $this;
	}

	protected function log( $msg, $severity = Logger::DEBUG)
	{
		$this->worker->log($msg, $severity);
	}


}
