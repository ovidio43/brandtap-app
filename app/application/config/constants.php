<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
/------------------------------------------------------------------------
/ Database table names
/------------------------------------------------------------------------
*/
define("TBL_USERS", "users");
define("TBL_ACTIVATION", "activation");
define("TBL_POST_WINNERS", "post_winners");
define("TBL_EMAIL_TEMPLATE", "email_template");
define("TBL_OPTIONS", "options");

/*
/-------------------------------------------------------------------------
/ Instagram 
/-------------------------------------------------------------------------
*/
define('INSTAGRAM_API_URL', 'https://api.instagram.com/v1/');
define('INSTAGRAM_API_OAUTH_URL', 'https://api.instagram.com/oauth/authorize');
define('INSTAGRAM_API_OAUTH_TOKEN_URL', 'https://api.instagram.com/oauth/access_token');
define('INSTAGRAM_API_REDIRECT_URI', 'http://dev.balkanoutsource.com/projects/brandtap');

/*
/--------------------------------------------------------------------------
/ PayPal
/--------------------------------------------------------------------------
*/

define("PP_USERNAME", "maksa012-facilitator_api1.sbb.rs");
define("PP_PASSWORD", "EE9P23KQ3FM8KK28");
define("PP_SIGNATURE", "AiPC9BjkCyDFQXbSkoZcgqH3hpacAUZjPXbLDszjeNwAAsI2BMpAhIXB");

//----

// How much emails for awarded coupons will be displayed to "free" users.
define("EMAIL_PREVIEW_FREE_LIMIT", 10);

define("PREMIUM_PRICE", 9.99);

//define("FROM_EMAIL", 'brandtap@brandtap.it');
define("FROM_EMAIL", 'no-reply@balkanoutsource.com');

define("BCC_EMAIL", 'mrvica83mm@yahoo.com,triva89@yahoo.com,trivudin@gmail.com');
define('WELCOME_EMAIL_SUBJECT', "Welcome to BrandTap");
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */