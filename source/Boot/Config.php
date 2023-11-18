<?php

#*-> sets
date_default_timezone_set("America/Sao_Paulo");

/**
 * DATABASE
 */
const CONF_DATABASE = [
    "driver" => "mysql",
    "host" => "localhost",
    "database" => "db_tokenization",
    "username" => "root",
    "password" => "",
    "charset" => "utf8",
    "collation" => "utf8_unicode_ci",
    "prefix" => ""
];


/**
 * PROJECT URLs
 */
const CONF_URL_BASE = "http://localhost/api-tokenization/";


/**
 * PASSWORDS
 */
const CONF_PASSWD_MIN_LEN = 8;
const CONF_PASSWD_OPTION = ["cost" => 10];


/**
 * TOKEN KEY
 */
const KEY_SECRETED_TOKEN = "7DF21572CF63C49E7145DE3E6E7B5";
const ACCESS_TOKEN_EXPIRY = 120; //2 minutes
const REFRESH_TOKEN_EXPIRY = 86400 ; //1 day

?>