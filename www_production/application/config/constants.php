<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

These constants are used in APIs
*/

define('STATUS','status');
define('SUCCESS_STATUS','SUCCESS');
define('ERR_STATUS','ERROR');
define('ERR_STR','errorString');
define('ERR_CODE_KEY','errorCode');
define('NULL_DATA','101');
define('NULL_DATA_STR','Null josn data');
define('PARAM_REQ_STR','Required parameters are missing');
define('PARAM_REQ','102');
define('ALREADY_REGD','103');
define('ALREADY_REGD_STR','User already registered');
define('INVALID_API','104');
define('INVALID_API_STR','Invalid request');
define('DB_ERR','105');
define('DB_ERR_STR','Database Error');
define('DUPLICATE_CMP','106');
define('DUPLICATE_CMP_STR','Duplicate Campaign');
define('INVALID_USER','107');
define('INVALID_USER_STR','Invalid user');
define('INVALID_EMAIL','108');
define('INVALID_EMAIL_STR','Invalid email');
define('EMAIL_SEND_FAILED','109');
define('EMAIL_SEND_FAILED_STR','Failed to send email');

define('USD_RATE',60);
define('CPM_RATE',1);
define('NO_OF_DAYS',4);




/**
 * Campaign Status Constants
 */

define('STATUS_RUN',0);
define('STATUS_PAUSE',1);




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