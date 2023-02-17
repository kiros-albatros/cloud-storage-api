<?php

function autoload($className)
{
    if (file_exists('src/Controllers/' . $className . '.php')) {
        require_once 'src/Controllers/' . $className . '.php';
    }
}

spl_autoload_register('autoload');