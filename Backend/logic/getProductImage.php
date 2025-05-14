<?php
$filename = $_GET['file'] ?? '';

$path = realpath(__DIR__ . '/../productpictures/' . $filename);

// Безопасность: не пускаем выше каталога productpictures
if (!$filename || !file_exists($path) || strpos($path, realpath(__DIR__ . '/../productpictures')) !== 0) {
    http_response_code(404);
    echo "Datei nicht gefunden";
    exit;
}

// Правильный Content-Type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
header("Content-Type: " . finfo_file($finfo, $path));
finfo_close($finfo);

// Кеширование
header("Cache-Control: public, max-age=86400");

readfile($path);
exit;
