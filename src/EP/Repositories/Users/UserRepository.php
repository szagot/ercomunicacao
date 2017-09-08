<?php
/**
 * Repositório de Usuários
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Repositories\Users;

use EP\App\Kernel;
use EP\Entities\Users\User;
use EP\Repositories\RepositoryAbstract;

class UserRepository extends RepositoryAbstract
{
    /**
     * Solicita acesso ao usuário
     *
     * @param string $pass     Senha informada
     * @param User   $user     Usuário gravado
     * @param bool   $longTime Deve permanecer logado por mais tempo?
     *
     * @return bool
     */
    public function getAccess($pass, User $user, $longTime = false)
    {
        // Senha correta?
        if (passVerify($pass, $user->getPass())) {
            // Precisa de um novo Hash?
            if (passNeedsRehash($user->getPass())) {
                $user->setPass($pass);
                // Grava no BD
                Kernel::em()->persist($user);
                Kernel::em()->flush();
            }

            // Marca como logado na sessão
            Kernel::session()->user = $user;
            // Se o usuário desejar permanecer logado, a sessão dura 24h após o ultimo uso. Senão, dura 20 min
            Kernel::session()->setTimeMin($longTime ? 1440 : 20)->setStartSession(true);

            return true;
        }

        return false;
    }

    /**
     * Está logado?
     *
     * @return bool
     */
    public function isLogged()
    {
        $isLogged =
            // Foi setado um usuário na sessão?
            Kernel::session()->keyExists('user') && Kernel::session()->user instanceof User;

        // Verifica se tem usuário logado
        if ($isLogged) {
            // Restarta a sessão
            Kernel::session()->setStartSession(true);
        }

        return $isLogged;
    }
}