<?php
define('PASS_COST', 9);
define('PASS_ALGO', PASSWORD_DEFAULT);

/**
 * password.php
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

/**
 * Gera uma senha de hash aleatório
 *
 * @param string $pass Senha do usuário
 *
 * @return bool|string
 */
function passHash($pass)
{
    return password_hash($pass, PASS_ALGO, [
        'cost' => PASS_COST
    ]);
}

/**
 * Verifica se uma senha bate
 *
 * @param string $pass Senha do usuário
 * @param string $hash Hash do BD
 *
 * @return bool
 */
function passVerify($pass, $hash)
{
    return password_verify($pass, $hash);
}

/**
 * Verifica se precisa ser gerado um novo Hash
 *
 * @param string $hash Hash do BD
 *
 * @return bool
 */
function passNeedsRehash($hash)
{
    return password_needs_rehash($hash, PASS_ALGO, [
        'cost' => PASS_COST
    ]);
}