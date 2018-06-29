<?php
    
    class Autoloader {

        function load() {

            spl_autoload_register(function($class) {
                $path = str_replace("\\", DIRECTORY_SEPARATOR, $class);
                require_once($path . '.php');
            });
        } 
    }

    $loading = new Autoloader;
    $loading->load();

