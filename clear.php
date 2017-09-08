<?php
/**
 * Limpeza total
 *
 * Use a opção --local se for local e/ou --force para forçar atualização, mesmo que não esteja em modo debug
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

require_once 'clear_logs.php';
require_once 'clear_session.php';
require_once 'clear_cache.php';
require_once 'clear_images.php';
require_once 'clear_db.php';

echo PHP_EOL . 'Finalizado' . PHP_EOL;