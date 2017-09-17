<?php
/**
 * Ação para Lista de Usuários
 *
 * @author Daniel Bispo <szagot@gmail.com.br>
 * @since  12/09/2017
 */


namespace EP\Action\Users;

use EP\App\Kernel;
use EP\Action\ActionInterface;
use EP\Entities\Users\User;
use EP\Repositories\Products\BrandRepository;

class UserAction implements ActionInterface
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
        $msg = null;
        $erro = false;
        /** @var BrandRepository $repo */
        $repo = Kernel::em()->getRepository(User::class);

        if (Kernel::uri()->getMethod() == 'POST') {
            $exclude = filter_input(INPUT_POST, 'exclude');
            if (!empty($exclude)) {
                //É pra excluir?

                /** @var User $thisBrand */
                $thisBrand = $repo->find($exclude);
                if ($repo->delete($thisBrand)) {
                    $msg = 'Marca excluída com sucesso';
                } else {
                    $erro = true;
                    $msg = 'Não foi possível excluir a marca no momento';
                }

            } else {
                $postName = filter_input(INPUT_POST, 'name', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                $postPass = filter_input(INPUT_POST, 'pass', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

                if (!empty($postName)) {
                    foreach ($postName as $code => $name) {
                        if (empty($name) && empty($postPass[$code])) {
                            continue;
                        }

                        // É cadastro?
                        if ($code == 'new_user') {
                            $thisCode = filter_input(INPUT_POST, 'code');
                            if (empty($thisCode) || empty($name) || empty($postPass[$code])) {
                                $erro = true;
                                continue;
                            }
                            $thisUser = new User();
                            $thisUser->setCode($thisCode);
                            $thisUser->setName($name);
                            $thisUser->setPass($postPass[$code]);
                            if (!$repo->create($thisUser)) {
                                $erro = true;
                            }
                        } else {
                            if (empty($code) || empty($postName[$code])) {
                                $erro = true;
                                continue;
                            }
                            /** @var User $thisUser */
                            $thisUser = $repo->find($code);
                            $thisUser->setName($name);
                            if (!empty($postPass[$code])) {
                                $thisUser->setPass($postPass[$code]);
                            }
                            if (!$repo->update($thisUser)) {
                                $erro = true;
                            }
                        }
                    }

                    $msg = $erro ? 'Não foi possível atualizar todos os usuários' : 'Usuários atualizados com sucesso';
                }
            }
        }

        $users = $repo->findAll('name');

        echo Kernel::template()->render('users/list.twig', [
            'msg'   => $msg,
            'erro'  => $erro,
            'users' => $users,
            'userLogged' => Kernel::session()->user
        ]);
    }
}