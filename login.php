<?php

/**
 * Require routes
 */
require_once(dirname(__FILE__)."/routes.php");

use DB\Support\Support;

if ($_POST["action"] == "login")
{
    $routes = new Route();
    $routes->__set('email', $_POST["email"]);
    $routes->__set('password', $_POST["password"]);
    if (!$routes->login()) {
        return false;
    } else {
        echo json_encode($routes->login());
    }
} elseif ($_POST["action"] == "register")
{
    $routes = new Route();
    $routes->__set("new_user_data", $_POST);
    echo $routes->createNewUserAccess();

} elseif ($_POST["action"] == "logout")
{
    $routes = new Route();
    $routes->logout();
}

//