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
    $_POST  = array(
        'status' => 'reject',
        'session_id' => '634ashdasdv1623=234',
        'ip' => '192.168.23.4',
        'email' => 'sample@gmail.com',
        'phone' => '+847236478234',
        'order_number' => '20094657'
    );

    $order = new \Model\Order($_POST);
    $order->getOutput();
    /*$data = $order->getByProperty('name', 'World');
    foreach($data as $row) {
        echo "  ".$row['n']->getProperty('name')."\n";
    }*/
}
