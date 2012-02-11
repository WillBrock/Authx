<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Configuration settings for the authx library.
 */


/*
|--------------------------------------------------------------------------
| General
|--------------------------------------------------------------------------
|
| 'admin_email' 				= Email for 'sent mail from' address and other misc. emails 
| 'home_page'					= After a successful login this is where the user is redirected to
| 'message_start_delimiter'		= Form message html
| 'message_end_delimiter'		= Closing tag
| 'error_start_delimiter'		= Form error message html
| 'error_end_delimiter'			= Closing tag
|
|--------------------------------------------------------------------------
*/
$config['admin_email']				= 'email@example.com';
$config['home_page']				= '';
$config['message_start_delimiter']		= '<p class="message">';
$config['message_end_delimiter']		= '</p>';
$config['error_start_delimiter']		= '<p class="error">';
$config['error_end_delimiter']			= '</p>';


/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
|
| 'users_table'			= Name of the table that stores the users info like email, password, etc.	
| 'username_field'		= The form input name for username
| 'email_field'			= The form input name for email
| 'password_field'		= The form input name for password
| 'groups_table'		= Name of the table for groups
| 'default_group_id'	= The default group the user is assigned to when registering
|
|--------------------------------------------------------------------------
*/
$config['users_table']					= 'users';
$config['username_field']				= 'username';
$config['email_field']					= 'email';
$config['password_field']				= 'password';
$config['groups_table']					= 'groups';
$config['default_group_id']				= 1;


/*
|--------------------------------------------------------------------------
| Register
|--------------------------------------------------------------------------
|
| 'allow_registration'			= Allow people to register
| 'use_username'				= Enable username option on register
| 'registration_captcha'		= Use a captcha for registration
| 'number_allowed_members'		= (int) 0 equals unlimited users can sign up
| 'email_activation'			= Send user a email to activate account.
| 'email_admin_on_register'		= Email the admin when a new user registers
| 'registration_token'			= Replace false with the code you want user to use, max_length = 15. This lets you make the user enter your code before registering
| 'min_username_length'			= Min length of the username
| 'max_username_length'			= Max length of the username
| 'registration_view'			= The View to display the registration form/page
| 'request_signup'				= If registration is turned off then let the user enter their email to be notified when sign up becomes available.
| 'request_signup_page'			= The url for the page that displays the form for the user to enter their email 
|
|--------------------------------------------------------------------------
*/
$config['allow_registration']			= TRUE;
$config['use_username']				= FALSE;
$config['registration_captcha']			= TRUE;
$config['number_allowed_members']		= 50; 
$config['email_activation']			= TRUE;
$config['email_admin_register']			= FALSE;
$config['registration_token']			= FALSE;  
$config['min_username_length'] 			= 4;
$config['max_username_length'] 			= 20;
$config['registration_view']			= 'auth/register';
$config['request_signup']			= FALSE; // uses the requests table 
$config['request_signup_page']			= 'auth/request_signup';
$config['send_welcome_email']			= TRUE;


/*
|--------------------------------------------------------------------------
| Login Settings
|--------------------------------------------------------------------------
|
| 'login_allowed'					= Can users login
| 'store_user_logins'				= This will store EVERY user login and failed login in a table that is only written to and only read if a someone suspects a security concern.
| 'login_captcha'					= display captcha on all logins
| 'login_attempts_before_captcha'	= How many login attempts before displaying the Captcha
| 'max_login_attempts'				= How many failed logins before we ban the user for x amount of time
| 'ban_duration'					= How long is the user banned? Based in seconds
| 'login_input'						= Name of the input field for either the username or email
| 'login_by_username'				= Allow the user to login by either their username or email
| 'remember_user'					= Use cookies to remember the user.
| 'login_view'						= The View to display the login form/page
| 'send_welcome_email'				= Send a welcome email to new users
|
|--------------------------------------------------------------------------
*/
$config['login_allowed']					= TRUE;
$config['store_user_logins']				= TRUE; // uses logins table
$config['login_captcha']					= FALSE;
$config['login_attempts_before_captcha']	= 7;
$config['max_login_attempts']				= 10; 
$config['ban_duration']						= 60 * 45; // 45 minutes
$config['login_input'] 						= 'email'; // TODO
$config['login_by_username'] 				= FALSE;
$config['remember_user']      				= FALSE;
$config['login_view']						= 'auth/login';


/*
|--------------------------------------------------------------------------
| Emailing Defaults
|--------------------------------------------------------------------------
|
| 'mailtype'					= HTML or plain text 
| 'email_directory'				= Directory that holds the email template view files
| 'email_extension'				= html, txt, etc. used to seperate the html from txt email
|
|--------------------------------------------------------------------------
*/
$config['email_directory']				= 'email';
$config['email_extension']				= 'html';
$config['mailtype'] 					= 'html';


/*
|--------------------------------------------------------------------------
| Captcha
|--------------------------------------------------------------------------
|
| 'captcha_path' 			= Directory where the catpcha will be created.
| 'captcha_fonts_path' 		= Font in this directory will be used when creating captcha.
| 'captcha_font_size' 		= Font size when writing text to captcha. Leave blank for random font size.
| 'captcha_grid' 			= Show grid in created captcha.
| 'captcha_expire' 			= Life time of created captcha before expired, default is 3 minutes (180 seconds).
| 'captcha_case_sensitive' 	= Captcha case sensitive or not.
|
|--------------------------------------------------------------------------
*/
$config['captcha_path'] = 'captcha/';
$config['captcha_fonts_path'] = 'captcha/fonts/5.ttf';
$config['captcha_width'] = 200;
$config['captcha_height'] = 50;
$config['captcha_font_size'] = 14;
$config['captcha_grid'] = FALSE;
$config['captcha_expire'] = 180;
$config['captcha_case_sensitive'] = FALSE;


/*
|--------------------------------------------------------------------------
| Passwords
|--------------------------------------------------------------------------
|
| 'min_password_length'			= Min length of passwords
| 'max_password_length'			= Max length of passwords
| 'password_include_letter		= require passwords to include a letter
| 'password_include_digit		= require passwords to include a digit
| 'password_include_capital		= require passwords to include a capital letter
| 'password_include_symbol'		= require passwords to include a symbol
| 'not_allowed_passwords'		= Force the user to not use a stupid password like 123, asdf, 123456, etc.
| 'reset_pass_expire'			= How long is the token vaild when a user forgets their password, in seconds
| 'not_allowed_pw_list'			= Array of bad passwords for 'not_allowed_passwords'
|
|--------------------------------------------------------------------------
*/
$config['min_password_length'] 			= 6;
$config['max_password_length'] 			= 20;
$config['password_salt']				= 'Hm39s2%ah#yy19';
$config['password_include_letter']		= 1;  	 //Enter the number of how many letters must be in the password
$config['password_include_capital']		= FALSE; //Enter the number of how many capitals must be in the password
$config['password_include_digit']		= FALSE; //Enter the number of how many digits must be in the password
$config['password_include_symbol']		= FALSE; //Enter the number of how many symbols must be in the password
$config['not_allowed_passwords']		= TRUE;
$config['reset_pass_expire']			= 60*15;


$config['not_allowed_pw_list'] 			= array(
		'password',
		'Password',
		'PASSWORD',
		'123',
		'123456',
		'1234',
		'consumer',
		'gizmodo',
		'whatever',
		'fuckyou',
		'starwars',
		'cheese',
		'qwerty',
		'Qwerty',
		'QWERTY',
		'asdf',
		'asdfjkl',
		'hello',
		'123456789',
		'iloveyou',
		'princess',
		'rockyou',
		'1234567',
		'12345678',
		'654321',
		'987654321',
		'abc123',
		'Nicole',
		'Daniel',
		'babygirl',
		'computer',
		'monkey',
		'Jessica',
		'Lovely',
		'michael',
		'Ashley',
		'trustno1',
		'football',
		'qazwsx',
		'111111',
		'master',
		'sunshine',
		'dragon',
		'letmein',
		'superman',
		'bailey',
		'baseball'
		
		);


/*
|--------------------------------------------------------------------------
| Future Versions
|--------------------------------------------------------------------------
|
| 
| 
|
|
|
|
|
|
|
|
|
|--------------------------------------------------------------------------
*/


$config['automatic_logouts']		= FALSE;	
$config['ip_allowed_to_register']	= ""; // not sure yet // maybe check the browser as well as the ip
$config['mandate_password_aging']	= FALSE; // Mandate the user to change their password every x amount of months/days
$config['optional_password_aging']	= FALSE; // Same as above but optional, this will just notify that its is a good idea to change their password.
$config['password_strength_level']	= FALSE; 	 // use "strong" to enforce hard to guess pw's; used "decent" to enable alright pw's.
$config['last_login_dif_ip']		= FALSE;
$config['only_allow_american_ip']	= FALSE;
$config['email_non_american_ip']	= FALSE;

$config['two_step_auth']			= FALSE;
$config['use_ssh_login']			= FALSE;
$config['app_name']					= "My App Name"; // for text message

$config['phones']['verizon']		= 'vtext.com';
$config['phones']['t-mobile']		= 'tmomail.net';
$config['phones']['sprint']			= 'messaging.sprintpcs.com';
$config['phones']['virgin']			= 'vmobl.com';

