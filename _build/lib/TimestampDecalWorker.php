<?php
class TimestampDecalWorker extends DecalWorker
{
	protected $haschanged = null;

	protected function hasChanged()
	{
				// Is source newer than target?
				$sourcetime = filemtime($this->decal->getSourcePath());
				$targettime = filemtime($this->decal->computeTargetPath());

				$changed = $sourcetime > $targettime;
				$this->log($this->decal->computeTargetPath().' has changed? '.print_r($changed,true));

				return $sourcetime > $targettime;				
	}

	public function setDecal(Decal $decal)
	{
		$this->log(__METHOD__);
		$this->hasChanged = null;
		parent::setDecal($decal);
        return $this;
	}


	public function copySVG()
	{
		if( $this->hasChanged() )
		{
			parent::copySVG();
		}
	}

	public function renderThumbnail()
	{
		if( $this->hasChanged() )
		{
			parent::renderThumbnail();
		}
	}

	public function renderPng()
	{
		if( $this->hasChanged() )
		{
			parent::renderPng();
		}	
	}
}