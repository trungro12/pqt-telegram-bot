<?php
spl_autoload_register(function ($class) {
    if (strpos($class, PQT_TELEGRAM_BOT_NAMESPACE . "\\") !== 0) {
        return;
    }

    // Convert namespace to file path
    $folderLibPath = PQT_TELEGRAM_BOT_PLUGIN_DIR;
    $class = str_replace(PQT_TELEGRAM_BOT_NAMESPACE, $folderLibPath, $class);
    $class = preg_replace('~[\\\/]~', DIRECTORY_SEPARATOR, $class);
    $file = $class .  ".php";
    // Check if the file exists and include it
    if (file_exists($file)) {
        include_once $file;
    }
});
