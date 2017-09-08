<?php
/**
 * clear_cache.php
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */
require_once '_autoload.php';

echo PHP_EOL;
echo 'Apagando Sessões...';

$path = (new \EP\Services\Config('paths'))->get('session');

if (is_dir($path)) {
    exec("rm -R ./{$path}*");
} else {
    $file = new \EP\Services\Files();
    $file->setPath($path, true);
}

file_put_contents($path . '_blank', '');