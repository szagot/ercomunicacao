<?php
/**
 * Página inicial
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Action;

use EP\App\Kernel;
use EP\Entities\Users\User;
use EP\Repositories\Users\UserRepository;

class Tests implements ActionInterface
{
    public static function run($method, $type, $uriParams = [])
    {
        ////////////////////////////////
        /// Execute antes /clear.php ///
        ////////////////////////////////

        // Exemplo pegando usuário
        /** @var UserRepository $userRepo */
        $userRepo = Kernel::em()->getRepository(User::class);
        /** @var User $user */
        $user = $userRepo->find('szagot');

        echo Kernel::template()->render('test.twig', [
            'uri'        => Kernel::uri()->getUri(),
            'metodo'     => Kernel::uri()->getMethod(),
            'params'     => $uriParams,
            'user'       => $user,
            'session'    => Kernel::session()->getId(),
            'sessionEnd' => (new \DateTime(Kernel::session()->getEndedAt()))->format('d/m/Y H:i:s'),
        ]);
    }
}