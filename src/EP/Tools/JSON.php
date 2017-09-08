<?php
/**
 * Ferramentas para JSON
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */


namespace EP\Tools;

use EP\App\Kernel;

class JSON
{
    const ERROR_INVALID_DATA    = 400;
    const ERROR_NOT_AUTHORIZED  = 401;
    const ERROR_UNKNOWN_SERVICE = 404;
    const ERROR_FATAL           = 500;

    /**
     * Emite a saída de dados
     *
     * @param        $msg
     * @param int    $errorType
     * @param string $forceMethod
     */
    static function show($msg, $errorType = null, $forceMethod = null)
    {
        $isError = ! empty($errorType);

        $method = $forceMethod ?? Kernel::uri()->getMethod() ?? 'GET';

        // Se os headers ainda não foram enviados, seta de acordo com o método
        if (! headers_sent()) {
            // Mão tem erro?
            if (! $isError) {
                // Verifica o método
                switch ($method) {
                    case 'POST':
                        header('HTTP/1.0 201 Created');
                        break;
                    case 'PUT':
                        header('HTTP/1.0 202 Accepted');
                        break;
                    case 'DELETE':
                        header('HTTP/1.0 204 No Content');
                        break;
                    default:
                        header('HTTP/1.0 200 OK');
                }

            } else {
                // Verifica qual o erro
                switch ($errorType) {
                    case self::ERROR_INVALID_DATA:
                        header('HTTP/1.0 400 Bad Request');
                        break;
                    case self::ERROR_NOT_AUTHORIZED:
                        header('HTTP/1.0 401 Unauthorized');
                        break;
                    case self::ERROR_UNKNOWN_SERVICE:
                        header('HTTP/1.0 404 Not Found');
                        break;
                    default:
                        header('HTTP/1.0 500 Internal Server Error');
                }
            }
        }

        // Emite a saída
        die(json_encode([
            'error'    => $isError,
            'response' => $msg
        ]));
    }
}