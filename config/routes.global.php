<?php
/**
 * Configuração de Rotas
 *      'id'      => identificador da rota,
 *      'action'  => Classe que executa a rota,
 *      'methods' => Método(s) permitido(s) (GET, POST, PUT, DELETE),
 *      'type'    => Tipo da saída (HTML, XML, JSON)
 *      'auth'    => Tipo de autenticação (BASIC, LOGIN)
 *      'params'  => array. Parâmetros no path (chave) e regex do mesmo (valor). Não use parênteses neste regex,
 *      'debug'   => bool. Se TRUE só executa se estiver em modo de depuração
 *
 * Exemplo:
 *      'teste' => [
 *          'path'    => '/teste/{id}/ok/{name}/{tel}',
 *          'action'  => Home::class,
 *          'methods' => ['GET'],
 *          'type'    => 'HTML',
 *          'auth'    => 'LOGIN',
 *          'params'  => [
 *              '$id'  => '[0-9]+',
 *              'name' => '[a-zA-Z]+-?[a-zA-Z]*?',
 *              'tel'  => '[0-9]{10,11}'
 *          ]
 *      ],
 */

use EP\Action\Info;
use EP\Action\Tests;
use EP\Action\Login;
use EP\Action\Logout;

return [
    // Login
    'login'                    => [
        'path'    => '/login',
        'action'  => Login::class,
        'methods' => ['GET', 'POST'],
        'type'    => 'HTML',
    ],
    // Logout
    'logout'                   => [
        'path'    => '/logout',
        'action'  => Logout::class,
        'methods' => ['GET'],
        'type'    => 'HTML',
    ],

    // PHP Info - Apenas Depuração
    'info'                     => [
        'path'    => '/info',
        'action'  => Info::class,
        'methods' => ['GET'],
        'type'    => 'HTML',
        'debug'   => true,
    ],

    // Home
    'home'                     => [
        'path'    => '/',
        'action'  => Tests::class,
        'methods' => ['GET'],
        'type'    => 'HTML',
        'auth'    => 'LOGIN',
    ],

];