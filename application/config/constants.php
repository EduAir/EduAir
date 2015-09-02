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

define('WEB_STORAGE_NAME', 'michou');
define('DOMAINE_NAME','kwiki');


define('HOSTER', 'localhost');
define('KIWIX_PORT', 8100);
define('SIGNAL_SERVER','http://'.HOSTER.':8888');
define('ZIM', 'wikipedia_fr_all_11_2013');
define('GUTENBERG','gutenberg_fr_all_10_2014');

//List of all zim file
define('ZIM_LIST','wikipedia_fr_all_11_2013,gutenberg_fr_all_10_2014,ted_business_05_2014,ted_entertainment_05_2014');



define('KIWIX', 'http://'.HOSTER.':'.KIWIX_PORT.'/'.ZIM);
define('HOST_WIKI', 'http://'.HOSTER.':'.KIWIX_PORT);
define('HOST', 'http://'.HOSTER);
define('TYPE_API', 0);//1 for wikipedia and 0 for local wikipedia
define('NODE','http://'.HOSTER.':8080/');
define('PEER_HOST',HOSTER);
define('PEER_PORT',9000);

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