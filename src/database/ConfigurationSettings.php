<?php
/**
 * Database configuration settings
 */

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

define('DB_HOST', $url["host"]);
define('DB_NAME', substr($url["path"], 1));
define('DB_USERNAME', $url["user"]);
define('DB_PASSWORD', $url["pass"]);
