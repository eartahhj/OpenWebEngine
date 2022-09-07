<?php
require_once $_SERVER['APP_ROOT'] . 'bootstrap.php';
require_once $_SERVER['APP_ROOT'] . 'core/functions.php';
require_once $_SERVER['APP_ROOT'] . 'config/config.php';
require_once $_SERVER['APP_ROOT'] . 'models/model.php';
require_once $_SERVER['APP_ROOT'] . 'models/files.php';

$request = substr($_SERVER['PATH_INFO'], strlen('request=') + 1);
$requestParts = explode(',', $request);

$requestedFile = $requestParts[0] ?? '';
$requestedFile = str_replace(substr(Config::$mediaLibraryRelativeDir, 1), '', $requestedFile);

$fileFormat = $requestParts[1] ?? '';

$fileType = $requestParts[2] ?? '';

$fileName = '';
$craftedUrl = '';

if ($requestedFile) {
    preg_match('/([0-9]+){1}/', $requestedFile, $matches);
    if (!empty($matches) and isset($matches[1])) {
        $requestedFileId = intval($matches[1]);
    }

    if ($requestedFileId) {
        $file = new File();

        if ($fileRecord = $file->getRecordById($requestedFileId)) {
            $file->setDataByObject($fileRecord);
        }

        if ($file->getId()) {
            $fileUrl = $file->getProperty('url_en');

            $craftedUrl = $file->getId() . '-' . $fileUrl;
        }

        if ($craftedUrl and $craftedUrl != $requestedFile) {
            http_response_code(302);
            header("Location: " . Config::$mediaLibraryRelativeDir . $craftedUrl);
        }

        $internalDirectory = '';
        if ($file->getExtension() == 'jpg' or $file->getExtension() == 'png') {
            if (is_file(Config::$mediaLibraryAbsoluteDir . 'webp/' . $file->getFileNameWithouExtension() . '.webp')) {
                $internalDirectory = 'webp/';
                $fileName = $file->getFileNameWithouExtension() . '.webp';
            } elseif (is_file(Config::$mediaLibraryAbsoluteDir . $file->getFileName())) {
                $fileName = $file->getFileName();
            }
        }
    }
}

if ($fileName) {
    header('Content-Type:' . mime_content_type(Config::$mediaLibraryAbsoluteDir . $internalDirectory . $fileName));
    header('Content-Length:' . filesize(Config::$mediaLibraryAbsoluteDir . $internalDirectory . $fileName));

    readfile(Config::$mediaLibraryAbsoluteDir . $internalDirectory . $fileName);
} else {
    http_response_code(404); ?>
    <h2>
        <?=($_language == 'it' ? '404 - File non trovato' : '404 - File not found')?>
    </h2>
    <a href="/">
        <?=($_language == 'it' ? 'Torna alla homepage' : 'Go back to the homepage')?>
    </a>
    <?php
}
?>
