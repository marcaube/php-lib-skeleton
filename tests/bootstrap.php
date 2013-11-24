<?php

if ((!$autoloadFile = file_exists(__DIR__.'/../vendor/autoload.php')) &&
    (!$autoloadFile = file_exists(__DIR__.'/../../../autoload.php'))) {
    die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install --dev'.PHP_EOL);
}

require $autoloadFile;