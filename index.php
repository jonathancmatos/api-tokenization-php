<?php

require __DIR__."/vendor/autoload.php";

use CoffeeCode\Router\Router;
use Illuminate\Database\Capsule\Manager;

/**
 * SETUP DATABASE
 */
$capsule = new Manager();
$capsule->addConnection(CONF_DATABASE);
$capsule->setAsGlobal();
$capsule->bootEloquent();


/**
 * BOOTSTRAP
 */
$router = new Router(CONF_URL_BASE);
$router->namespace("Source\App");


/**
 * WEB ROUTES
 */
$router->group(null);
$router->get("/", function (){
   echo "Você está conectado com sucesso.";
});


/**
 * ERROR ROUTES
 */
$router->group("/ops");
$router->get("/{errcode}", function ($data){
    echo "Ooops! Error:{$data["errcode"]}";
});


/**
 *ROUTER
 */
$router->dispatch();


/**
 * ERROR REDIRECT
 */
if($router->error()){
    $router->redirect("/ops/{$router->error()}");
}

?>