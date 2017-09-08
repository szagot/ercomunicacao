<?php
/**
 * Serviço de Configuração
 */

namespace EP\Services;


class Config
{
    private $path = 'config' . DIRECTORY_SEPARATOR;

    /** @var array */
    private $config = [];

    /**
     * Config constructor.
     *
     * @param string $config
     */
    public function __construct($config)
    {
        $file = $this->path . $config . '.global.php';
        $fileLocal = $this->path . $config . '.local.php';
        if (file_exists($file)) {
            $this->config = include($file);

            if (file_exists($fileLocal)) {
                $temp = include($fileLocal);
                $this->config = array_merge($this->config, $temp);
            }
        }
    }

    /**
     * Pega o parâmetro específico da configuração solicitada
     *
     * @return mixed|null
     */
    public function get($param)
    {
        return $this->config[ $param ] ?? null;
    }

    /**
     * Pega todos os parâmetros da configuração desejada
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->config;
    }
}