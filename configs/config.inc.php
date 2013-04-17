<?php
define('BASE_DIR', dirname(dirname(__FILE__)));
define('CONF_DIR', BASE_DIR . '/configs');

date_default_timezone_set('America/Denver');

require(BASE_DIR . '/vendor/autoload.php');

Propel::init(CONF_DIR . '/db/project-conf.php');
set_include_path(BASE_DIR . '/models' . PATH_SEPARATOR.get_include_path());

session_name(''); // To keep it separate from NWMR

session_set_cookie_params(2 * 60 * 60);
session_start();

if (isset($_SESSION['user'])) {
  setcookie(
    ini_get("session.name"),
    session_id(),
      time() + ini_get("session.cookie_lifetime"),
    ini_get("session.cookie_path"),
    ini_get("session.cookie_domain"),
    ini_get("session.cookie_secure"),
    ini_get("session.cookie_httponly")
  );
}