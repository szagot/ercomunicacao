<?php
/**
 * Doctrine Configuration
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */
require_once '_autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use EP\App\Kernel;

// replace with mechanism to retrieve EntityManager in your app
return ConsoleRunner::createHelperSet(Kernel::em());