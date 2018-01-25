#!/usr/bin/php
<?php
require './config.php';
require CLASSES_PATH.'/DocsToZip.php';

$archive_file_name = $argv[1];
$config = new Config();
$docs_to_zip = new DocsToZip($archive_file_name, $config);

echo $docs_to_zip->perform();
?>