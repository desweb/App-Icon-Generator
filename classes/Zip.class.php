<?php

/**
 * Folders & files system
 * 
 * App
 *  icon.png
 *  iOS
 *   iTunesArtwork.png		(512*512)
 *   iTunesArtwork@2x.png	(1024*1024)
 *   Icon.png				(57*57)
 *   Icon@2x.png			(114*114)
 *   Icon-72.png			(72*72)
 *   Icon-72@2x.png			(144*144)
 *   Icon-Small.png			(29*29)
 *   Icon-Small@2x.png		(58*58)
 *   Icon-Small-50.png		(50*50)
 *   Icon-Small-50@2x.png	(100*100)
 *   iOS7
 *    Icon-40.png		(40*40)
 *    Icon-40@2x.png	(80*80)
 *    Icon-60.png		(60*60)
 *    Icon-60@2x.png	(120*120)
 *    Icon-76.png		(76*76)
 *    Icon-76@2x.png	(152*152)
 *  Android
 *   PlayStore.png (512*512)
 *   drawable_ldpi
 *    ic_launcher.png (36*36)
 *   drawable_mdpi
 *    ic_launcher.png (48*48)
 *   drawable_hdpi
 *    ic_launcher.png (72*72)
 *   drawable_xhdpi
 *    ic_launcher.png (96*96)
 *   drawable_xxhdpi
 *    ic_launcher.png (144*144)
 */

class Zip
{
	private $identifier;
	private $hash;

	private $path_upload= '/homez.488/deswebcr/www/_code/app-icon/uploads/';
	private $path_zip	= '/homez.488/deswebcr/www/_code/app-icon/zip/';

	private $path_icon_folder;
	private $path_icon_file;
	private $path_zip_file;

	public function checkFile()
	{
		return !is_dir($this->path_icon_folder) && !file_exists($this->path_icon_file);
	}

	public function generateZip()
	{
		$this->generateIcons();

		if (!extension_loaded('zip') || !file_exists($this->path_icon_folder)) return false;

		$zip = new ZipArchive();

		if (!$zip->open($this->path_zip_file, ZIPARCHIVE::CREATE)) return false;

		$source = str_replace('\\', '/', realpath($this->path_icon_folder));

		if (is_dir($source) === true)
		{
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			foreach ($files as $file)
			{
				$file = str_replace('\\', '/', $file);

				if(in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) continue;

				$file = realpath($file);

				if		(is_dir($file)	=== true)	$zip->addEmptyDir	(str_replace($source . '/', '', $file . '/'));
				else if	(is_file($file)	=== true)	$zip->addFromString	(str_replace($source . '/', '', $file), file_get_contents($file));
			}
		}
		else if (is_file($source) === true) $zip->addFromString(basename($source), file_get_contents($source));

		$this->removeIcons();

		return $zip->close();
	}

	private function generateIcons()
	{
		$this->generateFolders();

		$icons = array(
			array('path' => 'iOS/iTunesArtwork.png',	'size' => 512),
			array('path' => 'iOS/iTunesArtwork@2x.png',	'size' => 1024),
			array('path' => 'iOS/Icon.png',				'size' => 57),
			array('path' => 'iOS/Icon@2x.png',			'size' => 114),
			array('path' => 'iOS/Icon-72.png',			'size' => 72),
			array('path' => 'iOS/Icon-72@2x.png',		'size' => 144),
			array('path' => 'iOS/Icon-Small.png',		'size' => 29),
			array('path' => 'iOS/Icon-Small@2x.png',	'size' => 58),
			array('path' => 'iOS/Icon-Small-50.png',	'size' => 50),
			array('path' => 'iOS/Icon-Small-50@2x.png',	'size' => 100),
			array('path' => 'iOS/iOS7/Icon-40.png',		'size' => 40),
			array('path' => 'iOS/iOS7/Icon-40@2x.png',	'size' => 80),
			array('path' => 'iOS/iOS7/Icon-60.png',		'size' => 60),
			array('path' => 'iOS/iOS7/Icon-60@2x.png',	'size' => 120),
			array('path' => 'iOS/iOS7/Icon-76.png',		'size' => 76),
			array('path' => 'iOS/iOS7/Icon-76@2x.png',	'size' => 152),
			array('path' => 'Android/PlayStore.png',	'size' => 512),
			array('path' => 'Android/drawable_ldpi/ic_launcher.png',	'size' => 36),
			array('path' => 'Android/drawable_mdpi/ic_launcher.png',	'size' => 48),
			array('path' => 'Android/drawable_hdpi/ic_launcher.png',	'size' => 72),
			array('path' => 'Android/drawable_xhdpi/ic_launcher.png',	'size' => 96),
			array('path' => 'Android/drawable_xxhdpi/ic_launcher.png',	'size' => 144));

		foreach ($icons as $value)
			exec('/usr/bin/convert -quality 72 ' . $this->path_icon_file . ' -resize ' . $value['size'] . 'x' . $value['size'] . ' ' . $this->path_icon_folder . $value['path']);
	}

	private function generateFolders()
	{
		$folders = array(
			'iOS/',
			'iOS/iOS7',
			'Android/',
			'Android/drawable_ldpi/',
			'Android/drawable_mdpi/',
			'Android/drawable_hdpi/',
			'Android/drawable_xhdpi/',
			'Android/drawable_xxhdpi/');

		foreach ($folders as $value)
			mkdir($this->path_icon_folder . $value, 0777, true);
	}

	private function removeIcons()
	{
		$it		= new RecursiveDirectoryIterator($this->path_icon_folder);
		$files	= new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

		foreach($files as $file)
		{
			if ($file->getFilename() === '.' || $file->getFilename() === '..') continue;
			
			if ($file->isDir())	rmdir	($file->getRealPath());
			else				unlink	($file->getRealPath());
		}
		rmdir($this->path_icon_folder);
	}

	/**
	 * Getters
	 */

	public function getHash() { return $this->hash; }

	/**
	 * Setters
	 */

	public function setIdentifier($identifier)
	{
		$this->identifier	= $identifier;
		$this->hash			= base_convert($identifier . rand(), 16, 10);

		$this->path_icon_folder	= $this->path_upload . $identifier . '/';
		$this->path_icon_file	= $this->path_icon_folder . '/icon.png';
		$this->path_zip_file	= $this->path_zip . 'app-icon-' . $this->hash . '.zip';
	}
}