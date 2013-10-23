<?php

require_once 'classes/Security.class.php';

$get_request	= $_GET;
$file_request	= $_FILES;

$security = new Security();

if (!$security->checkFile($file_request['icon'])) return;

$security->setIdentifier($get_request['time']);

if (!$security->checkIconProperties()) return;

mkdir($security->getPathIconFolder(), 0777, true);

move_uploaded_file($file_request['icon']['tmp_name'], $security->getPathIconFile());
