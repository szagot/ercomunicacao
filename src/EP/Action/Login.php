<?php
/**
 * Tela de Login
 *
 * É possível informar a loja para poder pegar o logo: /?store={code}
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Action;

use EP\App\Kernel;
use EP\Entities\Users\User;
use EP\Repositories\Users\UserRepository;

class Login implements ActionInterface
{

    /**
     * Executa a página atual
     *
     * @param string $method    Método da requisição
     * @param string $type      Tipo de saída
     * @param array  $uriParams Parâmetros da requisição enviados pela URI
     */
    public static function run($method, $type, $uriParams = [])
    {
        // Pega a uri da home
        $return = Kernel::router()->getUrl('home');
        if (Kernel::uri()->getParam('return')) {
            // Se foi informado o parâmetro de retorno, seta este
            $return = Kernel::uri()->getParam('return');
        } elseif (!preg_match('/^\/?log(in|out)/', Kernel::uri()->getUri())) {
            // Pega a rota pela uri
            $return = Kernel::uri()->getUri();
        }

        /** @var UserRepository $userRepo */
        $userRepo = Kernel::em()->getRepository(User::class);
        $permiteAcesso = true;

        // Houve tentativa de acesso?
        if ($method == 'POST') {
            /** @var User $user */
            $user = $userRepo->find(Kernel::uri()->getParam('code'));
            if ($user) {
                $permiteAcesso = $userRepo->getAccess(
                    Kernel::uri()->getParam('pass'),
                    $user,
                    Kernel::uri()->getParam('longTime', FILTER_VALIDATE_BOOLEAN)
                );
            } else {
                $permiteAcesso = false;
            }
        }

        // Se estiver logado, vai para a página de retorno ou então para a Home
        if ($userRepo->isLogged()) {
            Kernel::router()->goTo($return);
            exit;
        }

        // Abre o form de login
        echo Kernel::template()->render('login.twig', [
            'return'        => $return,
            'permiteAcesso' => $permiteAcesso,
        ]);
    }
}