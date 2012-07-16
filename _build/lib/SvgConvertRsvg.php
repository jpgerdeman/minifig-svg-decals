<?php
class SvgConvertRsvg extends SvgConvert
{
	protected function generateCommand()
	{
		$cmd = "rsvg-convert --keep-aspect-ratio --format=".$this->format." --output='".$this->outfile."' ";
		if( !is_null($this->width) )
		{
			$cmd.="--width=".$this->width." ";
		}
		
		if( !is_null($this->dpi) )
		{
			$cmd.="--dpi-x=".$this->dpi." "."--dpi-y=".$this->dpi." ";
		}
		$cmd.= "'".$this->infile."' 2>&1";		
	
		return $cmd;
	}
}
