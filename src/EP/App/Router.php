<?php
/**
 * Sistema de Roteamento
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\App;

use EP\Action\Login;
use EP\Action\NotFound;
use EP\Entities\Users\User;
use EP\Services\Config;
use Sz\Config\Uri;

class Router
{
    /** @var self */
    private static $router;

    /** @var Config */
    private $routes;
    /** @var array */
    private $uriParams = [];

    /**
     * @return Router
     */
    public static function getRouter(): Router
    {
        if (!self::$router) {
            self::$router = new self();
        }

        return self::$router;
    }

    /**
     * Tenta seguir para a rota especificada.
     * Caso não exista, segue para 404
     */
    public static function run()
    {
        $uri = Kernel::uri()->getUri();

        /** @var array $route Pega a rota conforme a URI */
        $route = self::getRouter()->getRoute($uri);

        // Pega a URI de destino
        $action = $route['action'] ?? null;

        // Verifica se a página está em modo de depuração
        $notValidDebug = false;
        // Página marcada como "apenas depuração"?
        if ($route['debug'] ?? false) {
            // O sistema está em modo depuração?
            if (!Kernel::config()->get('debug')) {
                // Se não está, indica a página como depuração inválida
                $notValidDebug = true;
            }
        }

        // Motivos para acessar a 404
        if (
            // Página não existe
            empty($action) ||
            // É pagina de depuração mas o sistema não está em modo de depuração
            $notValidDebug
        ) {
            NotFound::run('GET', '404');
            exit;
        }

        // Verifica acesso
        if (
            // Exige login
            isset($route['auth']) && $route['auth'] == 'LOGIN' &&
            // E não está logado
            !Kernel::em()->getRepository(User::class)->isLogged()
        ) {
            // Traz a tela de login
            Login::run('GET', 'HTML', self::getRouter()->uriParams);
            exit;
        }
        // TODO: adicionar método para Basic Auth

        // Seta o formato da saída
        self::getRouter()->setHeader($route['type']);

        // Executa ação
        $action::run(Kernel::uri()->getMethod(), $route['type'], self::getRouter()->uriParams);
    }

    /**
     * Pega a rota pelo ID. Se a rota não for encontrada, o retorno será '#
     *
     * @param string $idRoute
     * @param array  $params
     *
     * @return string
     */
    public function getUrl($idRoute, $params = [])
    {
        $route = $this->routes->get($idRoute);
        $path = $route['path'] ?? '#';

        if (!empty($params) && $path != '#') {
            foreach ($params as $id => $value) {
                // Verifica se os dados batem com o padrão regex
                $regex = '/^' . ($route['params'][$id] ?? '~invalid~') . '$/';
                if (!preg_match($regex, $value)) {
                    $path = '#' . $id;
                    break;
                }

                // Se os dados bateram, prossegue com substituição das tags por seus valores
                $path = str_replace('{' . $id . '}', $value, $path);
            }
        }

        return $path;
    }

    /**
     * Pega a rota pelo URI.
     *
     * @param      $uri
     * @param bool $ignoreMethod
     *
     * @return array
     */
    public function getRoute($uri, $ignoreMethod = false)
    {
        // Adiciona a raiz na URI informada, caso não exista, e remove duplicidade de barras
        $uri = preg_replace('/\/+/', '/', Kernel::uri()->getRaiz() . $uri);

        // Tenta localizar a rota
        foreach ($this->routes->getAll() as $id => $route) {
            $regexPath = '/^' . str_replace('/', '\/', $route['path']) . '$/';
            $params = [];
            // Verifica se há parâmetros nas rotas
            if (isset($route['params']) && !empty($route['params'])) {
                foreach ($route['params'] as $param => $regex) {
                    $regexPath = str_replace('{' . $param . '}', "($regex)", $regexPath);
                    // Salva parâmetro
                    $params[] = $param;
                }
            }

            // Verifica se a URI e o método batem com a Rota atual
            if (
                // URI
                preg_match($regexPath, $uri, $matches) &&
                // Método
                (in_array(Kernel::uri()->getMethod(), $route['methods']) || $ignoreMethod)
            ) {
                // Verifica a existência de parâmetros
                if (!empty($params)) {
                    // Registra parâmetros informados na URI
                    foreach ($params as $index => $param) {
                        $this->uriParams[$param] = $matches[$index + 1];
                    }
                }

                $route['id'] = $id;

                return $route;
            }
        }

        return [];
    }

    /**
     * Vai para a rota especificada
     *
     * @param string $uri
     * @param array  $uriParams Parâmetros a serem transportados via query string
     */
    public function goTo($uri, $uriParams = [])
    {
        // Tenta pegar a rota pelo ID
        $route = $this->getRoute($uri, true);

        // Se a rota não foi encontrada, vai para o 404
        if (!isset($route['action']) || empty($route['action'])) {
            NotFound::run('GET', '404');
            exit;
        }

        if (headers_sent()) {
            $route['action']::run(Kernel::uri()->getMethod(), $route['type'], self::getRouter()->uriParams);
            exit;
        }

        if (!empty($uriParams)) {
            $uri .= '?' . http_build_query($uriParams);
        }

        // Executa a rota
        header('Location: ' . $uri);
    }

    /**
     * Envia o header apropriado de acordo com o $type
     *
     * @param string $type
     */
    private function setHeader($type)
    {
        switch ($type) {
            case 'HTML':
                header('Content-type: text/html; charset=utf-8');
                break;
            case 'XML':
                header('Content-type: text/xml; charset=utf-8');
                break;
            case 'JSON':
                header('Content-type: application/json');
                break;
            case '404':
                header('HTTP/1.0 404 Not Found');
                header('Content-type: text/html; charset=utf-8');
                break;
        }
    }

    /**
     * Router constructor.
     */
    private function __construct()
    {
        $this->routes = new Config('routes');
    }
}