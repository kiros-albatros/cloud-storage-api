<?php

function autoload($className)
{
    if (file_exists('./src/base/' . $className . '.php')) {
        require_once './src/base/' . $className . '.php';
    }
}

spl_autoload_register('autoload');