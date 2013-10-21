<?php

$get_request = $_GET;

define('identifier', $get_request['time']);
define('hash', $get_request['time'] . rand());

$path				= '/homez.488/deswebcr/www/_code/app-icon/';
$path_dl			= 'http://code.desweb-creation.fr/app-icon/zip/';
$path_upload		= $path . 'uploads/';
$path_zip			= $path . 'zip/';
$path_upload_file	= $path_upload . identifier . '/';

if (!is_dir($path_upload_file) && !file_exists($path_upload_file . 'icon.png'))
{
	echo '{ "is_error" : 1 }';
	return;
}

folders($path_upload_file);

icons(array(
	'path_upload'		=> $path_upload_file,
	'path_upload_file'	=> $path_upload_file . 'icon.png'));

$id_convert = base_convert(hash, 16, 10);

zip($path_upload_file, $path_zip . 'app-icon-' . $id_convert . '.zip');

$zip_link = $path_dl . 'app-icon-' . $id_convert . '.zip';

remove($path_upload_file);

echo '{ "is_success" : 1, "hash" : "' . $id_convert . '" }';
return;

/**
 * Functions
 */

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

function folders($path_upload_icon)
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
		mkdir($path_upload_icon . $value, 0777, true);
}

function icons($datas)
{
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
		exec('/usr/bin/convert -quality 72 ' . $datas['path_upload_file'] . ' -resize ' . $value['size'] . 'x' . $value['size'] . ' ' . $datas['path_upload'] . $value['path']);
}

function resize($file, $width, $height, $crop = false)
{
	$img = new Imagick($file);

	if ($crop)	$img->cropThumbnailImage($width, $height);
	else		$img->thumbnailImage	($width, $height, true);

	return $img;
}

function zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) return false;

    $zip = new ZipArchive();

    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) return false;

    $source = str_replace('\\', '/', realpath($source));

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

    return $zip->close();
}

function remove($path_upload_file)
{
	$it		= new RecursiveDirectoryIterator($path_upload_file);
	$files	= new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

	foreach($files as $file)
	{
		if ($file->getFilename() === '.' || $file->getFilename() === '..') continue;
		
		if ($file->isDir())	rmdir	($file->getRealPath());
		else				unlink	($file->getRealPath());
	}
	rmdir($path_upload_file);
}

?>