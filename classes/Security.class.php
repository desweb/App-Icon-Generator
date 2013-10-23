<?php

class Security
{
	private $weight		= 1048576;
	private $extensions	= array('png', 'PNG');
	private $size		= array('width' => 1024, 'height' => 1024);
	private $resolution	= 72;

	private $identifier;

	private $file;

	private $path_upload= '/homez.488/deswebcr/www/_code/app-icon/uploads/';

	private $path_icon_folder;
	private $path_icon_file;

	public function checkFile($file)
	{
		$this->file = $file;

		return file_exists($this->file['tmp_name']) && is_uploaded_file($this->file['tmp_name']);
	}

	public function checkIconProperties()
	{
		if ($this->file['error'] > 0) return false;

		if ($this->file['size'] > $this->weight || $this->file['size'] <= 0) return false;

		list($icon_width, $icon_height) = getimagesize($this->file['tmp_name']);

		if ($icon_width != $this->size['width'] || $icon_height != $this->size['height']) return false;

		if(!in_array(explode('.', $this->file['name'])[1], $this->extensions)) return false;

		if ($this->checkPngDpi() != $this->resolution) return false;

		return true;
	}

	private function checkPngDpi()
	{
		$fh = fopen($this->file['tmp_name'], 'rb');

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

	/**
	 * Getters
	 */

	public function getPathIconFolder()	{ return $this->path_icon_folder; }
	public function getPathIconFile()	{ return $this->path_icon_file; }

	/**
	 * Setters
	 */

	public function setIdentifier($identifier)
	{
		$this->identifier	= $identifier;

		$this->path_icon_folder	= $this->path_upload . $identifier . '/';
		$this->path_icon_file	= $this->path_icon_folder . '/icon.png';
	}
}