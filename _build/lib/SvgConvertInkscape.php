<?php
class SvgConvertInkscape extends SvgConvert
{
	protected function generateCommand()
	{
		return "inkscape --format=".$this->format." --output='".$this->outfile."' '".$this->infile."' 2>&1";		
	}
}
