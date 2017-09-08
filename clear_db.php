<?php
/**
 * Faz a limpeza e Executa inserção de dados básicos no BD
 *
 * Use a opção --local se for local ou --force para forçar atualização, mesmo que não esteja em modo debug
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

require_once '_autoload.php';

use EP\App\Kernel;
use EP\Entities\Users\AccessLevel;
use EP\Entities\Users\User;
use EP\Repositories\Users\UserRepository;

echo PHP_EOL;

// Só executa localmente (a menos que se use --force)
if (!Kernel::config()->get('debug') && !in_array('--force', $argv)) {
    die('Serviço não autorizado!' . PHP_EOL);
}

$preExec = in_array('--local', $argv) ? 'bash ./' : '';

echo 'Apagando Tabelas do BD...' . PHP_EOL;
exec($preExec . 'vendor/bin/doctrine orm:schema-tool:drop --force');
echo 'Criando Tabelas do BD...' . PHP_EOL;
exec($preExec . 'vendor/bin/doctrine orm:schema-tool:create');

echo 'Cadastrando Dados de Base...' . PHP_EOL;

/**
 * Criando usuário inicial, caso não exista
 */
echo '    • Usuário' . PHP_EOL;

/** @var UserRepository $userRepo */
$userRepo = Kernel::em()->getRepository(User::class);
$user = new User();
$user
    ->setCode('admin')
    ->setName('Administrador')
    ->setPass('admin');
$userRepo->create($user);
