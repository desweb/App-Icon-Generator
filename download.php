<?php

$get_request = $_GET;

$file = 'zip/app-icon-' . $get_request['hash'] . '.zip';

header('Content-disposition:attachment;filename=' . $file);
header('Content-Type:application/zip');
header('Content-Transfer-Encoding:fichier');
header('Content-Length:' . filesize($file));
header('Pragma:no-cache');
header('Cache-Control:no-store,no-cache,must-revalidate,post-check=0,pre-check=0');
header('Expires:0');

readfile($file);
