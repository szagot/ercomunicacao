<?php
/**
 * clear_cache.php
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */
require_once '_autoload.php';

echo PHP_EOL;
echo 'Apagando Cache...';

// Limpa cache do template
$path = (new \EP\Services\Config('paths'))->get('cache');

if (is_dir($path)) {
    exec("rm -R ./{$path}*");
} else {
    $file = new \EP\Services\Files();
    $file->setPath($path, true);
}

file_put_contents($path . '_blank', '');

// Limpa cache de imagens
$path = (new \EP\Services\Config('paths'))->get('images');
$pathCache = $path . '_blank_dir/cache/';

if (is_dir($pathCache)) {
    exec("rm -R ./{$path}*/cache");
} else {
    $file = new \EP\Services\Files();
    $file->setPath($pathCache, true);
    file_put_contents($pathCache . '_blank', '');
}

