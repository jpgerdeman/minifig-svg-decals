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
include_once __DIR__.'/lib/Config.php';
include_once __DIR__.'/lib/Index.php';
include_once __DIR__.'/lib/IndexFacade.php';
include_once __DIR__.'/lib/DecalWorker.php';
include_once __DIR__.'/lib/TimestampDecalWorker.php';

$logger = new EchoLogger();
$config = new Config($configuration);
//$logger->setMinimumLogLevel(Logger::INFO);
$logger->log("Running with the following configuration:".PHP_EOL.$config->__toString(), Logger::INFO);
$thumbnailRenderer = SvgConvert::factory($config->getRenderer());
$thumbnailRenderer->setWidth($config->getThumbnailWidth())
        ->setLogger($logger);

$pngRenderer = SvgConvert::factory($config->getRenderer());
$pngRenderer->setDpi($config->getOutputDpi())
        ->setLogger($logger);

$worker = new TimestampDecalWorker();
$worker->setThumbnailRenderer($thumbnailRenderer);
$worker->setPngRenderer($pngRenderer);
$worker->setLogger($logger);

$indices = new IndexFacade();
$indices->setTemplatePath(__DIR__.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR."decal.template");
$indices->setConfig($config);
$indices->setLogger($logger);
$workspace = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($configuration['masterpath']) );
foreach( $workspace as $path )
{
	$d = new GitPath($path);
	
	if( $d->isSvg() )
	{
		$logger->log("processing $path", Logger::INFO);
		$decal = new Decal($path, $config);
        $decal->setLogger($logger);
		// TODO: @ is the evil!
		@mkdir( dirname($decal->computeTargetPath()), 0777, true);

        $logger->log("creating decal");		
        $worker->setDecal($decal);
        $worker->renderThumbnail();
#		$worker->renderPng();
#		$worker->copySVG();
		$indices->addDecal($decal);
	}
}

$indices->writeMenu();
