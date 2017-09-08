<?php
/**
 * Loader
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

require_once 'vendor/autoload.php';

// Habilitar debug?
$config = new \EP\Services\Config('config');
$debug = $config->get('debug');

ini_set('display_errors', $debug ? 'On' : 'Off');
error_reporting($debug ? E_ALL : 0);

// Seta a Zona local
date_default_timezone_set($config->get('timezone'));

require_once 'functions/_load.php';