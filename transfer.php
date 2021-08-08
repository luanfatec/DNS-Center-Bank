<?php

session_start();
if (!isset($_SESSION["hash_pay"])) {
    header("location:index.php");
}

/**
 * Require routes
 */
require_once(dirname(__FILE__)."/routes.php");

if ($_POST["action"] == "transfer")
{
    $routes = new Route();
    $routes->__set('transference', $_POST["data"]);
    echo $routes->transfer();

} elseif ($_POST["action"] == "unblocked")
{
   $routes = new Route();
   $routes->__set('unblocked_data', $_POST);
   echo $routes->unblockedBalance();

} elseif ($_POST["action"] == "transfer_details")
{
    $routes = new Route();
    $routes->__set("transfer_details", $_POST);
    echo $routes->return_transaction_details();
}