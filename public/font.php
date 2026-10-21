<?php
declare(strict_types=1);
/** Sirve la fuente local Yuyu contenida en public/fonts/Yuyu.zip. */
$archivoZip = __DIR__ . '/fonts/Yuyu.zip';
if (!class_exists(ZipArchive::class) || !is_file($archivoZip)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    exit('No se pudo cargar la fuente Yuyu: verificá ZipArchive y public/fonts/Yuyu.zip.');
}
$zip = new ZipArchive();
if ($zip->open($archivoZip) !== true) {
    http_response_code(500);
    exit('No se pudo abrir el archivo de la fuente.');
}
$stream = null;
$extension = '';
for ($i = 0; $i < $zip->numFiles; $i++) {
    $nombre = $zip->getNameIndex($i);
    if (!$nombre || strpos($nombre, '/') !== false) continue;
    $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
    if (in_array($ext, ['woff2', 'woff', 'ttf', 'otf'], true)) {
        $stream = $zip->getStream($nombre);
        $extension = $ext;
        break;
    }
}
if (!$stream) {
    $zip->close();
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    exit('El ZIP no contiene una fuente compatible en su raíz.');
}
$tipos = ['woff2'=>'font/woff2', 'woff'=>'font/woff', 'ttf'=>'font/ttf', 'otf'=>'font/otf'];
header('Content-Type: ' . $tipos[$extension]);
header('Cache-Control: public, max-age=604800');
fpassthru($stream);
fclose($stream);
$zip->close();
