<?php
session_start();
if (!isset($_SESSION["hash_pay"])) {
    header("location:index.php");
}

/**
 * Require routes
 */
require_once(dirname(__FILE__)."/routes.php");


if (isset($_POST['action'])) {
    if ($_POST["action"] == "return_account") {
        $routes = new Route();
        $routes->__set("id_user", $_SESSION["id_user"]);
        echo $routes->return_account();

    } elseif ($_POST["action"] == "return_transactions") {
        $routes = new Route();
        $routes->__set("id_user", $_SESSION["id_user"]);
        $routes->__set("account_number", $_POST["account_number"]);
        echo $routes->return_transactions();
    }
} else {
    $routes = new Route();
    $routes->__set('id_user', $_SESSION['id_user']);
    $routes->__set('email', $_SESSION['email']);
    echo '<pre>';
    var_dump($routes->update_personal());
    echo '</pre>';
}
