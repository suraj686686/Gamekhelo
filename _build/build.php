<?php


use GameKhelo\Games\GameKhelo;

$class = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'GameKhelo.php';
if (!is_readable($class)) {
    print "Fatal Error: File Not Found: {$class}\n";

    return;
}

require_once $class;

try {
    new GameKhelo();
} catch (Throwable $error) {
    print 'Fatal Error: ' . $error->getMessage() . "\n";
}
