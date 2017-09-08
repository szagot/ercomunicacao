<?php
/**
 * Inicializa serviços do sistema
 * [Bootstrap Class]
 */

namespace EP\App;

use Doctrine\ORM\EntityRepository;
use Intervention\Image\ImageManager;
use EP\Entities\Users\User;
use EP\Services\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use GuzzleHttp\Client;
use Twig_Environment as Twig;
use Sz\Config\Session;
use Sz\Config\Uri;

class Kernel
{
    /** @var EntityManager */
    private static $em;
    /** @var  Uri */
    private static $uri;
    /** @var  Session */
    private static $session;
    /** @var Twig */
    private static $template;
    /** @var Client */
    private static $http;
    /** @var Config */
    private static $config;
    /** @var  Config */
    private static $paths;
    /** @var \PHPMailer https://github.com/PHPMailer/PHPMailer */
    private static $mail;
    /** @var ImageManager https://github.com/Intervention/image */
    private static $image;
    /** @var Router */
    private static $router;

    /**
     * Inicializa serviços
     */
    public static function run()
    {
        // Tem WWW?
        if (self::config()->get('www')) {
            // Precisa adicionar o WWW?
            if (self::uri()->addWWW()) {
                exit;
            }
        } else {
            // Precisa remover o WWW?
            if (self::uri()->removeWWW()) {
                exit;
            }
        }

        // Inicializa rota e template
        Router::run();
    }

    /**
     * @return Config paths
     */
    public static function paths(): Config
    {
        if (! self::$paths) {
            // Pega as configurações da loja
            self::$paths = new Config('paths');
        }

        return self::$paths;
    }

    /**
     * @return Config config
     */
    public static function config(): Config
    {
        if (! self::$config) {
            // Pega as configurações da loja
            self::$config = new Config('config');
        }

        return self::$config;
    }

    /**
     * @return Uri
     */
    public static function uri(): Uri
    {
        if (! self::$uri) {
            // Pega as configurações da loja
            self::$uri = new Uri(self::paths()->get('raiz'));
        }

        return self::$uri;
    }

    /**
     * @return Session
     */
    public static function session(): Session
    {
        if (! self::$session) {
            self::$session = Session::start('TMW2D', null, self::paths()->get('session'));
        }

        return self::$session;
    }

    /**
     * @return Client
     */
    public static function http(): Client
    {
        if (! self::$http) {
            self::$http = new Client();
        }

        return self::$http;
    }

    /**
     * @return Twig
     */
    public static function template(): Twig
    {
        if (! self::$http) {
            self::setTemplate();
        }

        return self::$template;
    }

    /**
     * @return EntityManager
     */
    public static function em(): EntityManager
    {
        if (! self::$em) {
            $database = new Config('database');

            self::$em = EntityManager::create(
                $database->getAll(),
                Setup::createAnnotationMetadataConfiguration(
                    [self::paths()->get('entities')],
                    self::config()->get('debug')
                )
            );
        }

        return self::$em;
    }

    /**
     * Gerenciador de emails
     *
     * @return \PHPMailer
     */
    public static function mail(): \PHPMailer
    {
        if (! self::$mail) {
            self::$mail = new \PHPMailer();
        }

        return self::$mail;
    }

    /**
     * Gerenciador de imagens
     *
     * @return ImageManager
     */
    public static function image(): ImageManager
    {
        if (! self::$image) {
            self::$image = new ImageManager([
                'driver' => self::config()->get('image_driver'),
            ]);

            // Verifica se existe a pasta de salvamento de imagens
            if (! file_exists(self::paths()->get('images'))) {
                mkdir(self::paths()->get('images'));
            }
        }

        return self::$image;
    }

    /**
     * @return Router
     */
    public static function router(): Router
    {
        if (! self::$router) {
            self::$router = Router::getRouter();
        }

        return self::$router;
    }


    /**
     * Seta o Template
     */
    private static function setTemplate()
    {
        $loader = new \Twig_Loader_Filesystem(self::paths()->get('template'));
        $data = [
            'debug' => self::config()->get('debug'),
        ];

        // Tem cache?
        if (self::config()->get('cache')) {
            $data[ 'cache' ] = self::paths()->get('cache');
        }
        self::$template = new Twig($loader, $data);

        self::setTemplateGlobals();
    }

    /**
     * Seta as funções e variáveis globais para o template
     */
    private static function setTemplateGlobals()
    {
        /**
         * uri($idRoute)
         * Retorna a URL da rota do ID informado
         *
         * @param int $idRoute
         */
        $function = new \Twig_Function('url', function ($idRoute, $params = []) {
            return self::router()->getUrl($idRoute, $params);
        });
        self::$template->addFunction($function);

        /**
         * getParam($param)
         * Retorna o parâmetro (GET ou POST), se existir, priorizando o que foi salvo na sessão, quando aplicável
         *
         * @param string $param
         */
        $function = new \Twig_Function('getParam', function ($param) {
            return (self::session()->keyExists($param)) ? self::session()->$param : self::uri()->getParam($param);
        });
        self::$template->addFunction($function);

        /**
         * @var string raiz
         * Retorna a Raiz da pasta Pública
         */
        self::$template->addGlobal('raiz', self::$uri->getRaiz());

        // Se estiver em modo de depuração, adiciona a extensão de depuração
        if (self::config()->get('debug')) {
            self::$template->addExtension(new \Twig_Extension_Debug());
        }
    }

    /**
     * Monta a saída para Exception
     *
     * @param \Exception $e
     *
     * @return string
     */
    static function makeExceptionMessage(\Exception $e)
    {
        if (self::config()->get('debug')) {
            return <<<EXCEPTION
<pre class="exception_error">
{$e->getCode()} - {$e->getMessage()}

Linha {$e->getLine()} no arquivo {$e->getFile()}

{$e->getTraceAsString()}
</pre>
EXCEPTION;
        }

        return '';
    }

}