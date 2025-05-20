<?php
$filename = $_GET['file'] ?? '';

// Absoluten Pfad zur Datei ermitteln
$path = realpath(__DIR__ . '/../productpictures/' . $filename);

// Sicherheit prüfen: Datei existiert und liegt im Produktbilder-Ordner
if (!$filename || !file_exists($path) || strpos($path, realpath(__DIR__ . '/../productpictures')) !== 0) {
    http_response_code(404);
    echo "Datei nicht gefunden";
    exit;
}

// Content-Type anhand der Datei ermitteln und setzen
$finfo = finfo_open(FILEINFO_MIME_TYPE);
header("Content-Type: " . finfo_file($finfo, $path));
finfo_close($finfo);

// Cache-Header setzen (1 Tag)
header("Cache-Control: public, max-age=86400");

// Datei ausgeben
readfile($path);
exit;
