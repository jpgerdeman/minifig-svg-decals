<?php
class SvgConvertInkscape extends SvgConvert
{
	protected function generateCommand()
	{		
		// On Windows single quotation marks are ignored. use double quotation
        // marks instead
		$cmd = "inkscapec.exe ";
		
		switch($this->format)
		{
			case "ps":
				$cmd.= "--export-ps=\"".$this->outfile."\" ";
				break;
			case "eps":
				$cmd.= "--export-eps=\"".$this->outfile."\" ";
				break;
			case "pdf":
				$cmd.= "--export-pdf=\"".$this->outfile."\" ";
				break;
			case "png":
			default:
				$cmd.= "--export-png=\"".$this->outfile."\" ";
				break;
		}
		
		if( !is_null($this->width) )
		{
			$cmd.="--export-width=".$this->width." ";
		}
		
		if( !is_null($this->dpi) )
		{
			$cmd.="--export-dpi=".$this->dpi." ";
		}
		$cmd.= "\"".$this->infile."\" 2>&1";		
	
		return $cmd;
	}
}
