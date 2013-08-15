<?php
/* GENERAL */
define('ENVIRONMENT', 'development');

/* SITE  */
define('SITE_NAME', 'insideMusic');
define('SITE_EMAIL', 'maildtm@gmail.com');
define('SITE_SUPPORTER_EMAIL', 'maildtm@gmail.com');

/* CONSTANT */
define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME'].':81/phucanh/');
define('ASSETS_URL', BASE_URL.'assets/');

/* LOG */
define('DISPLAY_LOG_ENABLED', 1); // 1: enable else 0 
// log path
define('LOG_PATH', $_SERVER['DOCUMENT_ROOT'] .'phucanh/logs/');

define('LOG_ENABLED', '1');
define('LOG_CLEAN_DAY', '30');
define('LOG_PRINT_SCREEN','1'); //print to screen

/* MAIL */
//enable send mail error 
define('SEND_MAIL_ERROR_ENABLED', 0); // enable else 0 
//enable send mail support 
define('SEND_SUPPORT_MAIL_ENABLED', 1); // enable else 0 
// config mail to address 
define('SUPPORT_MAIL_TO', 'maildtm@gmail.com');
// config mail from address 
define('SUPPORT_MAIL_FROM', 'maildtm@gmail.com');

define('PAGE_LIMIT', '20');
define('SEARCH_ZN_ENABLED', TRUE);
define('SEARCH_NCT_ENABLED', FALSE);
define('SEARCH_NS_ENABLED', TRUE);