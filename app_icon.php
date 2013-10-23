<?php

require_once 'classes/Zip.class.php';

$get_request = $_GET;

$zip = new Zip();
$zip->setIdentifier($get_request['time']);

if ($zip->checkFile())
{
	echo '{ "is_error" : 1 }';
	return;
}

$zip->generateZip();

echo '{ "is_success" : 1, "hash" : "' . $zip->getHash() . '" }';
