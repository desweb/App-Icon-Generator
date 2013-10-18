<?php

if (!file_exists($_FILES['icon']['tmp_name']) || !is_uploaded_file($_FILES['icon']['tmp_name'])) return;

define('identifier', $_GET['time']);

$weight		= 1024 * 1024;
$extensions	= array('png', 'PNG');
$size		= array('width' => 1024, 'height' => 1024);
$resolution	= 72;

$path		= '/homez.488/deswebcr/www/_code/app-icon/uploads/';
$path_folder= $path . identifier . '/';

if (!check(array(
	'file'			=> $_FILES['icon'],
	'weight'		=> $weight,
	'extensions'	=> $extensions,
	'size'			=> $size,
	'resolution'	=> $resolution))) exit;

mkdir($path_folder, 0777, true);

move_uploaded_file($_FILES['icon']['tmp_name'], $path_folder . 'icon.png');

/**
 * Functions
 */

function check($datas)
{
	$file = $datas['file'];

	if ($file['error'] > 0) return false;

	if ($file['size'] > $datas['weight'] || $file['size'] <= 0) return false;

	list($icon_width, $icon_height) = getimagesize($file['tmp_name']);

	if ($icon_width != $datas['size']['width'] || $icon_height != $datas['size']['height']) return false;

	list($icon_name, $icon_ext) = explode('.', $file['name']);

	if(!in_array($icon_ext, $datas['extensions'])) return false;

	if (getPngDpi($file['tmp_name']) != $datas['resolution']) return false;

	return true;
}

function getPngDpi($source)
{
	$fh = fopen($source, 'rb');
	
	if (!$fh) return false;

	$dpi = false;

	$buf = array();

	$x		= 0;
	$y		= 0;
	$units	= 0;

	while(!feof($fh))
	{
		array_push($buf, ord(fread($fh, 1)));
		
		if		(count($buf) > 13) array_shift($buf);
		else if	(count($buf) < 13) continue;

		if ($buf[0] == ord('p') && $buf[1] == ord('H') && $buf[2] == ord('Y') && $buf[3] == ord('s'))
		{
			$x = ($buf[4] << 24) + ($buf[5] << 16) + ($buf[6] << 8) + $buf[7];
			$y = ($buf[8] << 24) + ($buf[9] << 16) + ($buf[10] << 8) + $buf[11];

			$units = $buf[12];

			break;
		}
	}

	fclose($fh);

	if ($x == $y)						$dpi = $x;
	if ($dpi != false && $units == 1)	$dpi = round($dpi * 0.0254);

	return $dpi;
}

?>