<?php
//https://drive.google.com/file/d/1q7tRiYkLqaQl4YagfBZs3PR-C-nFeRjW/view?u
//https://github.com/rave82/test_task

error_reporting(E_ALL);
ini_set('display_errors', 'off');

define('CURRENT_WORK_DIR', str_replace('\\', '/', __DIR__));

require_once CURRENT_WORK_DIR . '/config/helpers.php';
require_once CURRENT_WORK_DIR . '/config/connection.php';

spl_autoload_register(function($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, (CURRENT_WORK_DIR.DIRECTORY_SEPARATOR.$class.'.php'));
    
    if(is_file($path))
    {
        require_once $path;
        
        if(class_exists($class, FALSE))
        {
            return TRUE;
        }
        else
        {
            throw new Exception('Класс '.$class.' не найден в файле '.$path.'.');
        }
    }
    else
    {
        throw new Exception('Для класса '.$class.' файл не найден '.$path.'.');
    }
});


$r = new Components\Router();

echo $r->run();
die;