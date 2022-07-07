<?php
/**
 * database.php
 *
 * @created      06.07.2022
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2022 smiley
 * @license      MIT
 */

use lsolesen\pel\PelEntryInterface;
use lsolesen\pel\PelIfd;
use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelTag;

require_once __DIR__.'/../vendor/autoload.php';

$imgdir = __DIR__.'/../test';
$data   = [];

/** @var \SplFileInfo $fileInfo */
foreach(new RecursiveIteratorIterator((new RecursiveDirectoryIterator($imgdir))) as $fileInfo){

	if($fileInfo->isDir() || strtolower($fileInfo->getExtension()) !== 'jpg'){
		continue;
	}

	$file   = $fileInfo->getLinkTarget();
	$values = ['_filename' => $file];

	try{
		$entries = (new PelJpeg($file))->getExif()->getTiff()->getIfd()->getEntries();

		foreach($entries as $entry){

			if(!$entry instanceof PelEntryInterface){
				continue;
			}

			$values[PelTag::getName(PelIfd::EXIF, $entry->getTag())] = $entry->getText();
		}

		$data[] = $values;
	}
	catch(Throwable $e){
		echo sprintf("Error: %s (%s)\n", $e->getMessage(), $file);
	}

}

/** @noinspection PhpComposerExtensionStubsInspection */
var_dump(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
