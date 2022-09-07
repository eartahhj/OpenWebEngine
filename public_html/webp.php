<?php

exit(); // Comment this line when you need to run the script

// NOTE: You can use this script manually or with a cron, if needed, otherwise you can just delete this file

$folders = ['/img/', '/uploads/', '/medialibrary/'];

foreach ($folders as $folder) {
    if (is_dir(__DIR__  . $folder)) {
        $files = scandir(__DIR__ . $folder);

        foreach ($files as $fileName) {
            exec('cwebp ' . $fileName . ' -o webp/' . substr($fileName, 0, -4) . '.webp -quiet -q 70');
        }
    }
}
