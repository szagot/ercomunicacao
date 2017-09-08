<?php
/**
 * Faz o tratamento de arquivos e diretorios
 *
 * TODO: fazer mais serviços de controle de arquivo
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Services;


class Files
{
    private $path;
    private $file;

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     *
     * @param bool  $create Deve criar a pasta?
     *
     * @return Files
     * @throws \Exception
     */
    public function setPath($path, $create = false)
    {
        // Diretório existe?
        if (is_dir($path)) {
            $this->path = $path;

            if (! preg_match('/\/$/', $this->path)) {
                $this->path .= '/';
            }
        } elseif ($create && ! empty($path)) {
            // Tenta criar o diretório
            if (! $this->createTree($path)) {
                throw new \Exception('Não foi possível criar o diretório ' . $path);
            }
            $this->setPath($path);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     *
     * @return Files
     */
    public function setFile($file)
    {
        // Verifica se o arquivo existe, ou se existe ele + o path
        if (is_file($file)) {
            $this->file = $file;
        } elseif (is_file($this->path . $file)) {
            $this->file = $this->path . $file;
        }

        return $this;
    }

    /**
     * Apaga o arquivo
     *
     * @return Files
     * @throws \Exception
     */
    public function delFile()
    {
        // Arquivo informado?
        if (empty($this->getFile())) {
            throw new \Exception('Você precisa definir um arquivo válido primeiro. Arquivo informado: ' . $this->getFile());
        }

        // Tenta apagar o arquivo
        if (! @unlink($this->getFile())) {
            throw new \Exception('Não foi possível apagar o arquivo');
        }

        $this->file = null;

        return $this;
    }

    /**
     * Tenta criar o caminho da pasta informada
     *
     * @param string $path Caminho a ser criado
     *
     * @return bool
     * @throws \Exception
     */
    private function createTree($path)
    {
        // Caminho vazio?
        if (empty($path)) {
            return false;
        }

        // Se o diretório existe, não cria
        if (is_dir($path)) {
            return true;
        }

        // Remove os separadores de diretório iniciais
        $pathBase = preg_replace('/\/$/', '', $path);

        // Cria um array com o caminho
        $pathArray = explode('/', $pathBase);

        // Tenta criar o caminho
        $baseDir = '';
        foreach ($pathArray as $index => $pathBase) {
            $baseDir .= (($index > 0) ? '/' : '') . $pathBase;

            // Diretório existe?
            if (! is_dir($baseDir)) {
                // Conseguiu criar?
                if (! @mkdir($baseDir)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Apaga um diretório recursivamente
     *
     * @throws \Exception
     */
    public function delTree()
    {
        // Path foi informado?
        if (empty($this->getPath())) {
            throw new \Exception('Você precisa definir uma pasta válida primeiro. Pasta informada: ' . $this->getPath());
        }

        // Se o diretório não existir, é pq finalizou, senão, apaga
        if (! is_dir($this->getPath())) {
            $this->path = null;
        } else {
            $this->delTreeRecursive($this->getPath());
        }
    }

    /**
     * Serviço a ser executado por delTree()
     *
     * @param string $dir
     *
     * @throws \Exception
     */
    private function delTreeRecursive($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            if (is_dir("$dir/$file")) {
                $this->delTreeRecursive("$dir/$file");
            } else {
                @unlink("$dir/$file");
            }
        }

        if (! @rmdir($dir)) {
            throw new \Exception('Não foi possível apagar o diretório');
        }
    }

}