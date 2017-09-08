<?php
/**
 * Página com informações do PHP - Apenas depuração
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Action;

use EP\App\Kernel;

class Info implements ActionInterface
{
    public static function run($method, $type, $uriParams = [])
    {
        // Pega o PHP Info
        ob_start();
        phpinfo();
        $info = ob_get_contents();
        ob_end_clean();

        // Formata saídas de body e CSS
        preg_match('/<body>(.*)<\/body>/si', $info, $body);
        preg_match('/<style type="text\/css">(.*)<\/style>/si', $info, $css);

        // Renderiza o template
        echo Kernel::template()->render('info.twig', [
            'css'  => $css[ 1 ],
            'info' => $body[ 1 ],
        ]);
    }
}