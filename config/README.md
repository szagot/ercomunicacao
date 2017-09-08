# Sobre os arquivos de configuração

Para testes locais, você pode criar uma duplicata do arquivo de configuração que deseja personalizar localmente, 
trocando `.global.php` por `.local.php`.

Em seu conteúdo, coloque apenas as chaves que serão alteradas.

## Exemplos / Sugestões

**_config.local.php_**

    <?php
    return [
        'debug' => true,
        'cache' => false,
    ];
    
**_database.local.php_**

    <?php
    return [
        'user'     => 'root',
        'password' => '',
    ];