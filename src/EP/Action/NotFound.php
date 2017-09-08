<?php
/**
 * 404 NotFound
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Action;

use EP\App\Kernel;

class NotFound implements ActionInterface
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
        echo Kernel::template()->render('404.twig');
    }
}