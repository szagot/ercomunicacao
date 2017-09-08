<?php
/**
 * Limpeza de logs
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

require_once '_autoload.php';

use EP\App\Kernel;

echo PHP_EOL;

// Só executa localmente
if (! Kernel::config()->get('debug') && ! in_array('--force', $argv)) {
    die('Serviço não autorizado!' . PHP_EOL);
}

echo 'Apagando Logs...';

$path = (new \EP\Services\Config('paths'))->get('logs');

if (is_dir($path)) {
    exec("rm -R ./{$path}*");
} else {
    $file = new \EP\Services\Files();
    $file->setPath($path, true);
}

file_put_contents($path . '_blank', '');