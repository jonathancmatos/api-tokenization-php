<?php

use Dotenv\Dotenv;

$dovEnv = Dotenv::createImmutable(__DIR__."../../../");
$dovEnv->safeLoad();


#*-> sets
date_default_timezone_set("America/Sao_Paulo");

/**
 * DATABASE
 */
define("CONF_DATABASE", [
    "driver" => "mysql",
    "host" => "localhost",
    "database" => $_ENV["DEV_DATABASE"],
    "username" => $_ENV["DEV_DB_USERNAME"],
    "password" => $_ENV["DEV_DB_PASSWORD"],
    "charset" => "utf8",
    "collation" => "utf8_unicode_ci",
    "prefix" => ""
]);


/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", $_ENV["DEV_URL"]);


/**
 * PASSWORDS
 */
const CONF_PASSWD_MIN_LEN = 8;


/**
 * TOKEN KEY
 */
define("KEY_SECRETED_TOKEN", $_ENV["API_SECRET_TOKEN"]);
const ACCESS_TOKEN_EXPIRY = 120; //2 minutes
const REFRESH_TOKEN_EXPIRY = 86400 ; //1 day

?>