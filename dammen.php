<?php

namespace Dammen;

spl_autoload_register(
    function ($class_name) {
        $class_name = str_replace("Dammen\\", DIRECTORY_SEPARATOR, $class_name);
        include __DIR__ . "/classes/" . $class_name . '.class.php';
    }
);

$spel = new DamSpel();

$spel->start();
