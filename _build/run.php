<?php
include_once __DIR__.'/config.php';
include_once __DIR__.'/lib/GitPath.php';
include_once __DIR__.'/lib/Logger.php';
include_once __DIR__.'/lib/EchoLogger.php';
include_once __DIR__.'/lib/FileLogger.php';
include_once __DIR__.'/lib/SvgConvert.php';
include_once __DIR__.'/lib/SvgConvertRsvg.php';
include_once __DIR__.'/lib/SvgConvertInkscape.php';
include_once __DIR__.'/lib/SvgConvertException.php';
include_once __DIR__.'/lib/Decal.php';

$logger = new EchoLogger();

$logger->log("master branch at: ".$configuration['masterpath']);
$logger->log("gh-pages branch at: ".$configuration['gh-path']);

$workspace = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($configuration['masterpath']) );

	$reset_indices = array();
foreach( $workspace as $path )
{
	$d = new GitPath($path);
	$d->setLogger($logger)
		->setMasterBranchPath($configuration['masterpath'])
		->setGhBranchPath($configuration['gh-path']);
	
	if( $d->isSvg() )
	{
		$logger->log("processing $path");
		// TODO: @ is the evil!
		@mkdir( dirname($d->computeGhPath()), 0777, true);

		$index = dirname($d->computeGhPath()).'/index.html';
		$logger->log($index);
		if( !isset($reset_indices[$index]) )
		{			
			$logger->log(print_r($reset_indices, true));
			$logger->log($index);

			$title = explode( '/', dirname($d->computeGhPath()));
			$title = array_pop($title);			
			
			$reset_indices[$index] = $index;
			unlink($index);
			$html = file_get_contents(__DIR__.'/templates/index.html');
			$html = str_replace('%title%', $title, $html);
			file_put_contents($index, $html);
		}

		$decal = new Decal($path, $configuration, $logger);
		$decal->renderThumbnail();
		$decal->renderPng();
		$decal->copySVG();
		$decal->appendIndex();
	}
}
