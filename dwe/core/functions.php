<?php

# NOTE: These are helper and utility functions

function translate(array $text): string
{
    global $_language, $_languages;

    return $text[$_language] ?? $text[$_languages[1]] ?? '';
}

function eur2rfc2822($dateTime)
{
    $timestamp=mktime(
        (int)substr($dateTime, 11, 2),
        (int)substr($dateTime, 14, 2),
        (int)substr($dateTime, 17, 2),
        (int)substr($dateTime, 3, 2),
        (int)substr($dateTime, 0, 2),
        (int)substr($dateTime, 6, 4)
    );
    return date(DATE_RFC2822, $timestamp);
}

function returnFileSizeFormatted($file): string
{
    if (is_file($file)) {
        $fileInfo=stat($file);
    } else {
        $fileInfo=null;
    }
    if ($fileInfo) {
        $fileSize=$fileInfo['size'];
        if ($fileSize>1000000000) {
            return number_format(((float)$fileSize)/1000000000.0, 0, ',', '').' GB';
        }
        if ($fileSize>1000000) {
            return number_format(((float)$fileSize)/1000000.0, 0, ',', '').' MB';
        }
        if ($fileSize>1000) {
            return number_format(((float)$fileSize)/1000.0, 0, ',', '').' kB';
        }
        return $fileSize.' B';
    }
    return '';
}

function returnUploadMaxFileSizeInBytes()
{
    // NOTE: The overhead in encoding multipart can increase the size of the request by 30%
    // This is why the return is multiplied by 0.67%

    $maxFileSize=0;
    (int)$maxFileSize=substr(ini_get('upload_max_filesize'), 0, -1);
    $maxFileSize=$maxFileSize*1024*1024;
    return $maxFileSize*0.67;
}

function generatePasswordHash(string $password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

function generateUniqueToken($additionalValue = '')
{
    $string = uniqid() . $additionalValue . time();
    return hash_hmac('sha256', $string, time());
}

function returnValidatedUrl(string $url): string
{
    $url = mb_strtolower($url);
    $replacements = array(
        ' ' => '-',
        '--' => '-',
        '_' => '-',
        '€' => 'EUR',
        'à' => 'a',
        'è' => 'e',
        'ì' => 'i',
        'ò' => 'o',
        'ù' => 'u',
        'á' => 'a',
        'é' => 'e' ,
        'í' => 'i' ,
        'ó' => 'o',
        'ú' => 'u',
    );
    $url = strtr($url, $replacements);
    $url = str_replace('--', '-', $url);
    $url = mb_eregi_replace('[^a-z0-9\-\_\.]', '', $url);

    return $url;
}

function returnValidatedFileName(string $fileName): string
{
    return returnValidatedUrl($fileName);
}

function isUsernameFormatValid(string $username): bool
{
    if (preg_match('/^[a-zA-Z0-9\._-]+$/', $username)) {
        return true;
    }

    return false;
}

function deleteFile(string $filePath): bool
{
    if (is_file($filePath)) {
        return unlink($filePath);
    }

    return false;
}

function getDbColumnBooleanValue($originalValue): ?bool
{
    if (
        $originalValue === true or $originalValue === 'true' or $originalValue === 't'
        or $originalValue === 1 or $originalValue === '1'
    ) {
        return true;
    }

    if (
        $originalValue === false or $originalValue === 'false' or $originalValue === 'f'
        or $originalValue === 0 or $originalValue === '0'
    ) {
        return false;
    }

    return null;
}

function isBooleanValue($originalValue): bool
{
    return $this->getDbColumnBooleanValue($originalValue);
}

function getNoDbImages(array $imagesNames, array $extensions = []): array
{
    $images = [];

    if (!$extensions) {
        $extensions = $admittedImageExtensions;
    }

    $i = 0;

    foreach ($extensions as $extension) {
        foreach ($imagesNames as $imageName) {
            if (is_file($image = Config::$uploadsAbsoluteDir . $imageName . '.' . $extension)) {
                $i++;
                $images[$i]['path'] = $image;
                $images[$i]['fileName'] = $imageName . '.' . $extension;
            }
        }
    }

    return $images;
}

function deleteNoDbImages(array $imagesNames)
{
    $images = getNoDbImages($imagesNames);

    foreach ($images as $image) {
        $imageMD5 = md5_file($image['path']);
        if (!unlink($image['path'])) {
            $_messages->add(ALERT_TYPE_ERROR, 'Could not delete file: ' . htmlspecialchars($image['fileName']));
            return false;
        }
    }

    return true;
}

function isExtensionAdmitted(string $extension, array $admittedExtensions=[])
{
    if (!$admittedExtensions) {
        $admittedExtensions = $admittedFileExtensions;
    }
    if (!in_array($extension, $admittedExtensions)) {
        return false;
    }
    return true;
}

function returnFileExtension(string $fileName)
{
    $extension = '';
    $extension = substr($fileName, strripos($fileName, '.') + 1);
    return strtolower($extension);
}

function returnFileExtensionFromUpload(string $fieldName = 'file')
{
    if (isset($_FILES[$fieldName]) and $_FILES[$fieldName]) {
        $fileName = $_FILES[$fieldName]['name'];
        return returnFileExtension($fileName);
    } else {
        return '';
    }
}

function returnImageExtensionFromUpload(string $fieldName = 'image')
{
    return returnFileExtensionFromUpload($fieldName);
}

function uploadFile(string $tempName, string $finalName, string $destinationDirectory): bool
{
    if (!move_uploaded_file($tempName, $destinationDirectory . $finalName)) {
        $_messages->add(ALERT_TYPE_ERROR, 'Error uploading file');
        return false;
    } else {
        return true;
    }

    return false;
}

function returnFilesUrlsByIds(array $ids): array
{
    global $_messages;

    $url = '';
    $fileName = '';
    $query = "SELECT id, filename FROM files WHERE id" . ' IN (' . implode(',', $ids) . ')';
    $filesUrls = [];

    if (!$result = Database::query($query)) {
        $_messages[] = new AlertDebugQuery($query);
    } else {
        while ($record = Database::fetch($result)) {
            if ($record->filename) {
                $filesUrls[$record->id] = $record->filename;
            }
        }
    }

    return $filesUrls;
}

function returnPagesUrlsByIds(array $ids): array
{
    global $_messages, $_language;

    $url = '';
    $fileName = '';

    $query = <<<SQL
    SELECT pages.id AS page_id, pages.url_{$_language} AS page_url, page_categories.id AS category_id, page_categories.url_{$_language} AS category_url
    FROM pages
    LEFT JOIN link_pages_categories ON pages.id = link_pages_categories.id_page
    LEFT JOIN page_categories ON link_pages_categories.id_category = page_categories.id
    WHERE pages.id IN
    SQL;
    $query .= '(' . implode(',', $ids) . ')';

    $pagesUrls = [];

    if (!$result = Database::query($query)) {
        $_messages->add(ALERT_TYPE_DEBUGQUERY, $query);
    } else {
        while ($record = Database::fetch($result)) {
            if ($record->page_url) {
                $pagesUrls[$record->page_id] = $record->category_url . '-' . $record->category_id . '/' . $record->page_url . '-' . $record->page_id;
            }
        }
    }

    return $pagesUrls;
}

function getEuropeanDateTimeFromISOTimestamp(string $ISOTimestamp, string $returnFormat = ''): string|DateTime
{
    $dateEur = DateTime::createFromFormat('Y-m-d H:i:s', $ISOTimestamp);

    if ($ISOTimestamp == '0000-00-00 00:00:00') {
        return '';
    }

    if (!$returnFormat) {
        $returnFormat = 'd/m/Y H:i:s';
    }

    if ($dateEur) {
        return $dateEur->format($returnFormat);
    }
}

function getEuropeanDateFromISOTimestamp(string $ISOTimestamp, string $returnFormat = 'm/d/Y'): string|DateTime
{
    return getEuropeanDateTimeFromISOTimestamp($ISOTimestamp, $returnFormat);
}

function getItalianDateTimeFromISOTimestamp(string $ISOTimestamp, string $returnFormat = 'd/m/Y'): string|DateTime
{
    return getEuropeanDateTimeFromISOTimestamp($ISOTimestamp, $returnFormat);
}

function loadFile(string $fileName, string $type = '')
{
    if (file_exists($_SERVER['APP_PUBLIC'] . $fileName)) {
        readfile($_SERVER['APP_PUBLIC'] . $fileName);
    }
}
