<?php
class Config
{
    private $outputDpi = null;
    private $thumbnailWidth = null;
    private $masterpath = null;
    private $ghPath = null;
    private $renderer = null;
    private $baseUrl = null;

    public function __construct(Array $configuration )
    {
        $this->setThumbnailWidth($configuration['thumbnail-width']);
        $this->setOutputDpi($configuration['output-dpi']);
        $this->setMasterpath($configuration['masterpath']);
        $this->setGhPath($configuration['gh-path']);
        $this->setRenderer($configuration['renderer']);
        $this->setBaseUrl($configuration['base-url']);
    }
    
    public function getOutputDpi()
    {
        return $this->outputDpi;
    }

    public function setOutputDpi( $outputDpi )
    {
        $this->outputDpi = $outputDpi;
    }

    public function getThumbnailWidth()
    {
        return $this->thumbnailWidth;
    }

    public function setThumbnailWidth( $thumbnailWidth )
    {
        $this->thumbnailWidth = $thumbnailWidth;
    }

    public function getMasterpath()
    {
        return $this->masterpath;
    }

    public function setMasterpath( $masterpath )
    {
        $this->masterpath = $masterpath;
    }

    public function getGhPath()
    {
        return $this->ghPath;
    }

    public function setGhPath( $ghPath )
    {
        $this->ghPath = $ghPath;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    public function setRenderer( $renderer )
    {
        $this->renderer = $renderer;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl( $baseUrl )
    {
        $this->baseUrl = $baseUrl;
    }

    public function __toString()
    {
        $obj_vars = get_object_vars($this);
        
        $str = '';
        foreach ($obj_vars as $name => $value) {
            $str.= "$name : $value".PHP_EOL;
        }
        
        return $str;
    }
}