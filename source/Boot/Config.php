<?php

#*-> sets
date_default_timezone_set("America/Sao_Paulo");

/**
 * DATABASE
 */
define("CONF_DATABASE",[
   "drive" => "mysql",
   "host" => "localhost",
    "database" => "db_tokenization",
    "username" => "root",
    "password" => "",
    "charset" => "utf8",
    "collation" => "utf8_unicode_ci",
    "prefix" => ""
]);


/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", "http://localhost/api-tokenization/");


/**
 * PASSWORDS
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

?>