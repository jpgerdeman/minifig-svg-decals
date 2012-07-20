<?php
class GitPath
{
	private $path;
	public function __construct($path)
	{
		$this->path = $path;
	}


	public function isSvg()
	{
		return $this->endswith('.svg');
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

}
