<?php
function autoloadModel($className) {
    // autoload for model
    $filename = __DIR__ . "/" . str_replace("\\", '/', $className) . ".php";

    if (file_exists($filename)) {
        include($filename);
        if (class_exists($className)) {
            return true;
        }
    }

    // autoload vendor
    $sLibPath = 'vendor/lib/';
    $sClassFile = str_replace('\\' , DIRECTORY_SEPARATOR , $className) . '.php';
    $sClassPath = $sLibPath . $sClassFile;

    if (file_exists($sClassPath)) {
        include($sClassPath);
        if (class_exists($className)) {
            return true;
        }
    }
    return false;
}
spl_autoload_register("autoloadModel");

//if (!empty($_POST)) {
{
    $order = new \Model\Order($_POST);
}
