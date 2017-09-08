<?php
/**
 * Base para arquivos de ação
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Action;


interface ActionInterface
{
    /**
     * Executa a página atual
     *
     * @param string $method    Método da requisição
     * @param string $type      Tipo de saída
     * @param array  $uriParams Parâmetros da requisição enviados pela URI
     */
    public static function run($method, $type, $uriParams = []);
}