<?php

ini_set('max_execution_time', 60000000);
ini_set('memory_limit','36M');
$rootPath = realpath(dirname(__FILE__));

// Initialize archive object
$zip = new ZipArchive();
$zip->open('download.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}


echo '<pre>';
print_r($zip);
// Zip archive will be created only after closing object
$zip->close();


?>