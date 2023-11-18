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
 * STACK_API
 */
$router->group("/api");

/** AUTH **/
$router->post("/signup","Users:signUp");
$router->post("/signin","Users:signIn");
$router->post("/google-sign-in","Users:googleSignIn");
$router->get("/current-user","Users:currentUser");
$router->post("/logout","Users:logout");

/** TOKEN **/
$router->post("/refresh-token", "Tokens:refresh");


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