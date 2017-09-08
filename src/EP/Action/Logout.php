<?php
/**
 * Tela de Logout
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Action;


use EP\App\Kernel;

class Logout implements ActionInterface
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
        // Tem loja salva na sessão?
        $uriParams = [];
        if (Kernel::session()->keyExists('actualStore')) {
            $uriParams['store'] = Kernel::session()->actualStore['code'];
        }

        Kernel::session()->destroy();

        // Segue para a home
        Kernel::router()->goTo(Kernel::router()->getUrl('home'), $uriParams);
    }
}