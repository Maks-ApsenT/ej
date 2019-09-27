<?php

define('NEEDS_PHP', '7.0.0');

define('NEEDS_EXT', array('Core', 'date', 'openssl', 'pcre', 'curl', 'dom', 'libxml', 'hash', 'filter', 'SPL', 'session', 'standard', 'mbstring', 'fileinfo', 'gd', 'exif', 'PDO', 'pdo_mysql', 'zip')); // Расширения

$error = NULL;

if (version_compare(PHP_VERSION, NEEDS_PHP, '<')) {

    $error .= 'Нужная версия PHP - <b>' . NEEDS_PHP . '</b> и выше, а у вас - <b>' . PHP_VERSION . '</b><br />';

}

foreach (NEEDS_EXT as $name) {

    if (!extension_loaded($name)) {

        $error .= 'Включите расширение <b>' . $name . '</b><br />';

    }

}

if ($error) {

    echo '<h2>ERROR</h2>';

    die($error);

}


if (!class_exists('PDO'))
die('Fatal Error: Для работы нужна поддержка PDO.');