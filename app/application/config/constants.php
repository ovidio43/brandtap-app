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
define("TBL_EMAIL_FREQUENCY", "email_frequency");
define("TBL_EMAIL_MULTI_TEMPLATE", "email_multi_template");
define("TBL_POST_DETAILS", "post_details");

/*
/-------------------------------------------------------------------------
/ Instagram 
/-------------------------------------------------------------------------
*/
define('INSTAGRAM_API_URL', 'https://api.instagram.com/v1/');
define('INSTAGRAM_API_OAUTH_URL', 'https://api.instagram.com/oauth/authorize');
define('INSTAGRAM_API_OAUTH_TOKEN_URL', 'https://api.instagram.com/oauth/access_token');
define('INSTAGRAM_API_REDIRECT_URI', 'http://dev.balkanoutsource.com/brandtap/brandtap.co/');

//Balkan Outsource's API:
define('INSTAGRAM_API_CLIENT', '52a219b6e7a34e9d904f514333551e75');
define('INSTAGRAM_API_SECRET', '2b717132ff9849d48518829dc02bde98');

// BrandTap.it API:
//define('INSTAGRAM_API_CLIENT', '8369ea9d5dfa49c28dd6e93f20920f09');
//define('INSTAGRAM_API_SECRET', 'a7e1cf8a344340199e63a91fcec7bdf3');

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